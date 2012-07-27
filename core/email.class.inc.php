<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Send an email (abstraction for synchronous/asynchronous modes)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once(APPROOT.'/lib/swiftmailer/lib/swift_required.php');

Swift_Preferences::getInstance()->setCharset('UTF-8');


define ('EMAIL_SEND_OK', 0);
define ('EMAIL_SEND_PENDING', 1);
define ('EMAIL_SEND_ERROR', 2);


class EMail
{
	protected static $m_oConfig = null;

	public function LoadConfig($sConfigFile = ITOP_DEFAULT_CONFIG_FILE)
	{
		if (is_null(self::$m_oConfig))
		{
			self::$m_oConfig = new Config($sConfigFile);
		}
	}


	protected $m_oMessage;

	public function __construct()
	{
		$this->m_oMessage = Swift_Message::newInstance();

		$oEncoder = new Swift_Mime_ContentEncoder_PlainContentEncoder('8bit');
		$this->m_oMessage->setEncoder($oEncoder);
	}

  	protected function SendAsynchronous(&$aIssues, $oLog = null)
	{
		try
		{
			AsyncSendEmail::AddToQueue($this, $oLog);
		}
		catch(Exception $e)
		{
			$aIssues = array($e->GetMessage());
			return EMAIL_SEND_ERROR;
		}
		$aIssues = array();
		return EMAIL_SEND_PENDING;
	}

	protected function SendSynchronous(&$aIssues, $oLog = null)
	{
		$this->LoadConfig();

		$sTransport = self::$m_oConfig->Get('email_transport');
		switch ($sTransport)
		{
		case 'SMTP':
			$sHost = self::$m_oConfig->Get('email_transport_smtp.host');
			$sPort = self::$m_oConfig->Get('email_transport_smtp.port');
			$sEncryption = self::$m_oConfig->Get('email_transport_smtp.encryption');
			$sUserName = self::$m_oConfig->Get('email_transport_smtp.username');
			$sPassword = self::$m_oConfig->Get('email_transport_smtp.password');

			$oTransport = Swift_SmtpTransport::newInstance($sHost, $sPort, $sEncryption);
			if (strlen($sUserName) > 0)
			{
				$oTransport->setUsername($sUserName);
				$oTransport->setPassword($sPassword);
			}
			break;

		case 'PHPMail':
		default:
			$oTransport = Swift_MailTransport::newInstance();
		}

		$oMailer = Swift_Mailer::newInstance($oTransport);

		$iSent = $oMailer->send($this->m_oMessage);
		if ($iSent === false)
		{
			$aIssues = 'une erreur s\'est produite... mais quoi !!!';
			return EMAIL_SEND_ERROR;
		}
		else
		{
			$aIssues = array();
			return EMAIL_SEND_OK;
		}
	}

	public function Send(&$aIssues, $bForceSynchronous = false, $oLog = null)
	{	
		if ($bForceSynchronous)
		{
			return $this->SendSynchronous($aIssues, $oLog);
		}
		else
		{
			$bConfigASYNC = MetaModel::GetConfig()->Get('email_asynchronous');
			if ($bConfigASYNC)
			{
				return $this->SendAsynchronous($aIssues, $oLog);
			}
			else
			{
				return $this->SendSynchronous($aIssues, $oLog);
			}
		}
	}

	public function AddToHeader($sKey, $sValue)
	{
		if (strlen($sValue) > 0)
		{
			$oHeaders = $this->m_oMessage->getHeaders();
			switch(strtolower($sKey))
			{
				case 'from':
				case 'cc':
				case 'bcc':
				$aMatches = array();
				// Header may be in the form: John Doe <jd@company.com>
				if (preg_match('/^([^<]+) <([^>]+)>$/', $sValue, $aMatches))
				{
					$aHeader = array($aMatches[2] => $aMatches[1]);
				}
				else
				{
					$aHeader = array($sValue);
				}
				$oHeaders->addMailboxHeader($sKey, $aHeader);
				break;

				default:
				$oHeaders->addTextHeader($sKey, $sValue);
			}
		}
	}

	public function SetMessageId($sId)
	{
		// Note: Swift will add the angle brackets for you
		// so let's remove the angle brackets if present, for historical reasons
		$sId = str_replace(array('<', '>'), '', $sId);
		
		$oMsgId = $this->m_oMessage->getHeaders()->get('Message-ID');
		$oMsgId->SetId($sId);
	}
	
	public function SetReferences($sReferences)
	{
		$this->AddToHeader('References', $sReferences);
	}

	public function SetBody($sBody, $sMimeType = 'text/html')
	{
		$this->m_oMessage->setBody($sBody, $sMimeType);
	}

	public function AddPart($sText, $sMimeType = 'text/html')
	{
		$this->m_oMessage->addPart($sText, $sMimeType);
	}

	public function AddAttachment($data, $sFileName, $sMimeType)
	{
		$this->m_oMessage->attach(Swift_Attachment::newInstance($data, $sFileName, $sMimeType));
	}

	public function SetSubject($aSubject)
	{
		$this->m_oMessage->setSubject($aSubject);
	}

	public function GetSubject()
	{
		return $this->m_oMessage->getSubject();
	}

	public function SetRecipientTO($sAddress)
	{
		$this->m_oMessage->setTo($sAddress);
	}

	public function GetRecipientTO($bAsString = false)
	{
		$aRes = $this->m_oMessage->getTo();
		if ($bAsString)
		{
			$aStrings = array();
			foreach ($aRes as $sEmail => $sName)
			{
				if (is_null($sName))
				{
					$aStrings[] = $sEmail;
				}
				else
				{
					$sName = str_replace(array('<', '>'), '', $sName);
					$aStrings[] = "$sName <$sEmail>";
				}
			}
			return implode(', ', $aStrings);
		}
		else
		{
			return $aRes;
		}
	}

	public function SetRecipientCC($sAddress)
	{
		$this->AddToHeader('Cc', $sAddress);
	}

	public function SetRecipientBCC($sAddress)
	{
		$this->AddToHeader('Bcc', $sAddress);
	}

	public function SetRecipientFrom($sAddress, $sLabel = '')
	{
		if ($sLabel != '')
		{
			$this->m_oMessage->setFrom(array($sAddress => $sLabel));		
		}
		else
		{
			$this->m_oMessage->setFrom($sAddress);
		}
	}

	public function SetRecipientReplyTo($sAddress)
	{
		$this->AddToHeader('Reply-To', $sAddress);
	}

}

?>