<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Send an email (abstraction for synchronous/asynchronous modes)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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

	/**
	 * Helper to transform and sanitize addresses
	 * - get rid of empty addresses	 
	 */	 	
	protected function AddressStringToArray($sAddressCSVList)
	{
		$aAddresses = array();
		foreach(explode(',', $sAddressCSVList) as $sAddress)
		{
			$sAddress = trim($sAddress);
			if (strlen($sAddress) > 0)
			{
				$aAddresses[] = $sAddress;
			}
		}
		return $aAddresses;
	}	

	public function SetRecipientTO($sAddress)
	{
		if (!empty($sAddress))
		{
			$aAddresses = $this->AddressStringToArray($sAddress);
			$this->m_oMessage->setTo($aAddresses);
		}
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
		if (!empty($sAddress))
		{
			$aAddresses = $this->AddressStringToArray($sAddress);
			$this->m_oMessage->setCc($aAddresses);
		}
	}

	public function SetRecipientBCC($sAddress)
	{
		if (!empty($sAddress))
		{
			$aAddresses = $this->AddressStringToArray($sAddress);
			$this->m_oMessage->setBcc($aAddresses);
		}
	}

	public function SetRecipientFrom($sAddress, $sLabel = '')
	{
		if ($sLabel != '')
		{
			$this->m_oMessage->setFrom(array($sAddress => $sLabel));		
		}
		else if (!empty($sAddress))
		{
			$this->m_oMessage->setFrom($sAddress);
		}
	}

	public function SetRecipientReplyTo($sAddress)
	{
		if (!empty($sAddress))
		{
			$this->m_oMessage->setReplyTo($sAddress);
		}
	}

}

?>