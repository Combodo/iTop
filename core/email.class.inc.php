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
	// Serialization formats
	const ORIGINAL_FORMAT = 1; // Original format, consisting in serializing the whole object, inculding the Swift Mailer's object.
							   // Did not work with attachements since their binary representation cannot be stored as a valid UTF-8 string
	const FORMAT_V2 = 2; // New format, only the raw data are serialized (base64 encoded if needed)
	
	protected static $m_oConfig = null;
	protected $m_aData; // For storing data to serialize

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
		$this->m_aData = array();
		$this->m_oMessage = Swift_Message::newInstance();

		$oEncoder = new Swift_Mime_ContentEncoder_PlainContentEncoder('8bit');
		$this->m_oMessage->setEncoder($oEncoder);
	}

	/**
	 * Custom serialization method
	 * No longer use the brute force "serialize" method since
	 * 1) It does not work with binary attachments (since they cannot be stored in a UTF-8 text field)
	 * 2) The size tends to be quite big (sometimes ten times the size of the email)
	 */
	public function SerializeV2()
	{
		return serialize($this->m_aData);
	}
	
	/**
	 * Custom de-serialization method
	 * @param string $sSerializedMessage The serialized representation of the message
	 */
	static public function UnSerializeV2($sSerializedMessage)
	{
		$aData = unserialize($sSerializedMessage);
		$oMessage = new Email();
		
		if (array_key_exists('body', $aData))
		{
			$oMessage->SetBody($aData['body']['body'], $aData['body']['mimeType']);
		}
		if (array_key_exists('message_id', $aData))
		{
			$oMessage->SetMessageId($aData['message_id']);
		}
		if (array_key_exists('bcc', $aData))
		{
			$oMessage->SetRecipientBCC($aData['bcc']);
		}
		if (array_key_exists('cc', $aData))
		{
			$oMessage->SetRecipientCC($aData['cc']);
		}
		if (array_key_exists('from', $aData))
		{
			$oMessage->SetRecipientFrom($aData['from']['address'], $aData['from']['label']);
		}
		if (array_key_exists('reply_to', $aData))
		{
			$oMessage->SetRecipientReplyTo($aData['reply_to']);
		}
		if (array_key_exists('to', $aData))
		{
			$oMessage->SetRecipientTO($aData['to']);
		}
		if (array_key_exists('subject', $aData))
		{
			$oMessage->SetSubject($aData['subject']);
		}
		
		
		if (array_key_exists('headers', $aData))
		{
			foreach($aData['headers'] as $sKey => $sValue)
			{
				$oMessage->AddToHeader($sKey, $sValue);
			}
		}
		if (array_key_exists('parts', $aData))
		{
			foreach($aData['parts'] as $aPart)
			{
				$oMessage->AddPart($aPart['text'], $aPart['mimeType']);
			}
		}
		if (array_key_exists('attachments', $aData))
		{
			foreach($aData['attachments'] as $aAttachment)
			{
				$oMessage->AddAttachment(base64_decode($aAttachment['data']), $aAttachment['filename'], $aAttachment['mimeType']);
			}
		}
		return $oMessage;
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

		case 'Null':
			$oTransport = Swift_NullTransport::newInstance();
			break;
		
		case 'LogFile':
			$oTransport = Swift_LogFileTransport::newInstance();
			$oTransport->setLogFile(APPROOT.'log/mail.log');
			break;
			
		case 'PHPMail':
		default:
			$oTransport = Swift_MailTransport::newInstance();
		}

		$oMailer = Swift_Mailer::newInstance($oTransport);

		$aFailedRecipients = array();
		$iSent = $oMailer->send($this->m_oMessage, $aFailedRecipients);
		if ($iSent === 0)
		{
			// Beware: it seems that $aFailedRecipients sometimes contains the recipients that actually received the message !!!
			IssueLog::Warning('Email sending failed: Some recipients were invalid, aFailedRecipients contains: '.implode(', ', $aFailedRecipients));
			$aIssues = array('Some recipients were invalid.');
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
		if (!array_key_exists('headers', $this->m_aData))
		{
			$this->m_aData['headers'] = array();
		}
		$this->m_aData['headers'][$sKey] = $sValue;
		
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
		$this->m_aData['message_id'] = $sId;
		
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
		$this->m_aData['body'] = array('body' => $sBody, 'mimeType' => $sMimeType);
		$this->m_oMessage->setBody($sBody, $sMimeType);
	}

	public function AddPart($sText, $sMimeType = 'text/html')
	{
		if (!array_key_exists('parts', $this->m_aData))
		{
			$this->m_aData['parts'] = array();
		}
		$this->m_aData['parts'][] = array('text' => $sText, 'mimeType' => $sMimeType);
		$this->m_oMessage->addPart($sText, $sMimeType);
	}

	public function AddAttachment($data, $sFileName, $sMimeType)
	{
		if (!array_key_exists('attachments', $this->m_aData))
		{
			$this->m_aData['attachments'] = array();
		}
		$this->m_aData['attachments'][] = array('data' => base64_encode($data), 'filename' => $sFileName, 'mimeType' => $sMimeType);
		$this->m_oMessage->attach(Swift_Attachment::newInstance($data, $sFileName, $sMimeType));
	}

	public function SetSubject($sSubject)
	{
		$this->m_aData['subject'] = $sSubject;
		$this->m_oMessage->setSubject($sSubject);
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
		$this->m_aData['to'] = $sAddress;
		if (!empty($sAddress))
		{
			$aAddresses = $this->AddressStringToArray($sAddress);
			$this->m_oMessage->setTo($aAddresses);
		}
	}

	public function GetRecipientTO($bAsString = false)
	{
		$aRes = $this->m_oMessage->getTo();
		if ($aRes === null)
		{
			// There is no "To" header field
			$aRes = array();
		}
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
		$this->m_aData['cc'] = $sAddress;
		if (!empty($sAddress))
		{
			$aAddresses = $this->AddressStringToArray($sAddress);
			$this->m_oMessage->setCc($aAddresses);
		}
	}

	public function SetRecipientBCC($sAddress)
	{
		$this->m_aData['bcc'] = $sAddress;
		if (!empty($sAddress))
		{
			$aAddresses = $this->AddressStringToArray($sAddress);
			$this->m_oMessage->setBcc($aAddresses);
		}
	}

	public function SetRecipientFrom($sAddress, $sLabel = '')
	{
		$this->m_aData['from'] = array('address' => $sAddress, 'label' => $sLabel);
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
		$this->m_aData['reply_to'] = $sAddress;
		if (!empty($sAddress))
		{
			$this->m_oMessage->setReplyTo($sAddress);
		}
	}

}

/////////////////////////////////////////////////////////////////////////////////////

/**
 * Extension to SwiftMailer: "debug" transport that pretends messages have been sent,
 * but just log them to a file.
 *
 * @package Swift
 * @author  Denis Flaven
 */
class Swift_Transport_LogFileTransport extends Swift_Transport_NullTransport
{
	protected $sLogFile;
	
	/**
	 * Sends the given message.
	 *
	 * @param Swift_Mime_Message $message
	 * @param string[]           $failedRecipients An array of failures by-reference
	 *
	 * @return int     The number of sent emails
	 */
	public function send(Swift_Mime_Message $message, &$failedRecipients = null)
	{
		$hFile = @fopen($this->sLogFile, 'a');
		if ($hFile)
		{
			$sTxt = "================== ".date('Y-m-d H:i:s')." ==================\n";
			$sTxt .= $message->toString()."\n";
		
			@fwrite($hFile, $sTxt);
			@fclose($hFile);
		}
		
		return parent::send($message, $failedRecipients);
	}
	
	public function setLogFile($sFilename)
	{
		$this->sLogFile = $sFilename;
	}
}

/**
 * Pretends messages have been sent, but just log them to a file.
 *
 * @package Swift
 * @author  Denis Flaven
 */
class Swift_LogFileTransport extends Swift_Transport_LogFileTransport
{
	/**
	 * Create a new LogFileTransport.
	 */
	public function __construct()
	{
		call_user_func_array(
		array($this, 'Swift_Transport_LogFileTransport::__construct'),
		Swift_DependencyContainer::getInstance()
		->createDependenciesFor('transport.null')
		);
	}

	/**
	 * Create a new LogFileTransport instance.
	 *
	 * @return Swift_LogFileTransport
	 */
	public static function newInstance()
	{
		return new self();
	}
}