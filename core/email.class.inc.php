<?php
// Copyright (C) 2010-2021 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Laminas\Mail\Header\ContentType;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\File;
use Laminas\Mail\Transport\FileOptions;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mime\Mime;
use Laminas\Mime\Part;
use Pelago\Emogrifier\CssInliner;
use Pelago\Emogrifier\HtmlProcessor\CssToAttributeConverter;
use Pelago\Emogrifier\HtmlProcessor\HtmlPruner;

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
		$this->m_oMessage = new Message();
		$this->m_oMessage->setEncoding('UTF-8');
		$this->SetRecipientFrom(MetaModel::GetConfig()->Get('email_default_sender_address'), MetaModel::GetConfig()->Get('email_default_sender_label'));
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
	 *
	 * @param string $sSerializedMessage The serialized representation of the message
	 *
	 * @return \EMail
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \Symfony\Component\CssSelector\Exception\SyntaxErrorException
	 */
	public static function UnSerializeV2($sSerializedMessage)
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
			$oMessage->SetRecipientReplyTo($aData['reply_to']['address'], $aData['reply_to']['label']);
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

	/**
	 * @throws \Exception
	 */
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

			$oTransport = new Smtp();
			$aOptions= [
				'host'              => $sHost,
				'port'              => $sPort,
				'connection_class'  => 'login',
				'connection_config' => [
					'ssl' => $sEncryption,
				],
			];
			if (strlen($sUserName) > 0)
			{
				$aOptions['connection_config']['username'] = $sUserName;
				$aOptions['connection_config']['password'] = $sPassword;
			}
			$oOptions = new SmtpOptions($aOptions);
			$oTransport->setOptions($oOptions);
			break;

		case 'Null':
			$oTransport = new Smtp();
			break;
		
		case 'LogFile':
			$oTransport = new File();
			$aOptions   = new FileOptions([
				'path' => APPROOT.'log/mail.log',
			]);
			$oTransport->setOptions($aOptions);
			break;
			
		case 'PHPMail':
		default:
			$oTransport = new Smtp();
		}
		
		$oKPI = new ExecutionKPI();
		try
		{
			$oTransport->send($this->m_oMessage);
			$aIssues = array();
			$oKPI->ComputeStats('Email Sent', 'Succeded');
			return EMAIL_SEND_OK;
		}
		catch(Laminas\Mail\Transport\Exception\RuntimeException $e){
			IssueLog::Warning('Email sending failed: Some recipients were invalid');
			$aIssues = array('Some recipients were invalid.');
			$oKPI->ComputeStats('Email Sent', 'Error received');
			return EMAIL_SEND_ERROR;
		}
		catch (Exception $e)
		{
			$oKPI->ComputeStats('Email Sent', 'Error received');
			throw $e;
		}
	}

	/**
	 * Reprocess the body of the message (if it is an HTML message)
	 * to replace the URL of images based on attachments by a link
	 * to an embedded image (i.e. cid:....) and returns images to be attached as an array
	 *
	 * @param string $sBody Email body to process/alter
	 *
	 * @return array Array of Part that needs to be added as inline attachment later to render as embed
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	protected function EmbedInlineImages(string &$sBody)
	{
		$oDOMDoc = new DOMDocument();
		$oDOMDoc->preserveWhitespace = true;
		@$oDOMDoc->loadHTML('<?xml encoding="UTF-8"?>'.$sBody); // For loading HTML chunks where the character set is not specified

		$oXPath = new DOMXPath($oDOMDoc);
		$sXPath = '//img[@'.InlineImage::DOM_ATTR_ID.']';
		$oImagesList = $oXPath->query($sXPath);
		$oImagesContent = new \Laminas\Mime\Message();
		$aImagesParts = [];
		if ($oImagesList->length != 0)
		{
			foreach($oImagesList as $oImg)
			{
				$iAttId = $oImg->getAttribute(InlineImage::DOM_ATTR_ID);
				$oAttachment = MetaModel::GetObject('InlineImage', $iAttId, false, true /* Allow All Data */);
				if ($oAttachment)
				{
					$sImageSecret = $oImg->getAttribute('data-img-secret');
					$sAttachmentSecret = $oAttachment->Get('secret');
					if ($sImageSecret !== $sAttachmentSecret)
					{
						// @see N°1921
						// If copying from another iTop we could get an IMG pointing to an InlineImage with wrong secret
						continue;
					}

					$oDoc = $oAttachment->Get('contents');

					$sCid = uniqid('', true);

					$oNewAttachment = new Part($oDoc->GetData());
					$oNewAttachment->id = $sCid;
					$oNewAttachment->type = $oDoc->GetMimeType();
					$oNewAttachment->filename = $oDoc->GetFileName();
					$oNewAttachment->disposition = Mime::DISPOSITION_INLINE;
					$oNewAttachment->encoding = Mime::ENCODING_BASE64;
					
					$oImagesContent->addPart($oNewAttachment);
					$oImg->setAttribute('src', 'cid:'.$sCid);
					$aImagesParts[] = $oNewAttachment;
				}
			}
		}
		$sBody = $oDOMDoc->saveHTML();
		return $aImagesParts;
	}

	public function Send(&$aIssues, $bForceSynchronous = false, $oLog = null)
	{
		//select a default sender if none is provided.
		if(empty($this->m_aData['from']['address']) && !empty($this->m_aData['to'])){
			$this->SetRecipientFrom($this->m_aData['to']);
		}

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
				case 'return-path':
					$this->m_oMessage->setReturnPath($sValue);
					break;

				default:
					$oHeaders->addHeaderLine($sKey, $sValue);
			}
		}
	}

	public function SetMessageId($sId)
	{
		$this->m_aData['message_id'] = $sId;
		
		// Note: Swift will add the angle brackets for you
		// so let's remove the angle brackets if present, for historical reasons
		$sId = str_replace(array('<', '>'), '', $sId);

		$this->m_oMessage->getHeaders()->addHeaderLine('Message-ID', $sId);
	}

	public function SetReferences($sReferences)
	{
		$this->AddToHeader('References', $sReferences);
	}

	/**
	 * Set the "In-Reply-To" header to allow emails to group as a conversation in modern mail clients (GMail, Outlook 2016+, ...)
	 *
	 * @link https://en.wikipedia.org/wiki/Email#Header_fields
	 *
	 * @param string $sMessageId
	 *
	 * @since 3.0.1 N°4849
	 */
	public function SetInReplyTo(string $sMessageId)
	{
		$this->AddToHeader('In-Reply-To', $sMessageId);
	}

	/**
	 * Set current Email body and process inline images.
	 * 
	 * @param $sBody
	 * @param string $sMimeType
	 * @param $sCustomStyles
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \Symfony\Component\CssSelector\Exception\SyntaxErrorException
	 */
	public function SetBody($sBody, string $sMimeType = Mime::TYPE_HTML, $sCustomStyles = null)
	{
		$oBody = new Laminas\Mime\Message();
		$aAdditionalParts = [];
		
		if (($sMimeType === Mime::TYPE_HTML) && ($sCustomStyles !== null)) {
			$oDomDocument = CssInliner::fromHtml($sBody)->inlineCss($sCustomStyles)->getDomDocument();
			HtmlPruner::fromDomDocument($oDomDocument)->removeElementsWithDisplayNone();
			$sBody = CssToAttributeConverter::fromDomDocument($oDomDocument)->convertCssToVisualAttributes()->render(); // Adds html/body tags if not already present
		}
		$this->m_aData['body'] = array('body' => $sBody, 'mimeType' => $sMimeType);

		// We don't want these modifications in m_aData['body'], otherwise it'll ruin asynchronous mail as they go through this method twice
		if ($sMimeType === Mime::TYPE_HTML){
			$aAdditionalParts = $this->EmbedInlineImages($sBody);
	    }
		
		// Add body content to as a new part
		$oNewPart = new Part($sBody);
		$oNewPart->encoding = Mime::ENCODING_8BIT;
		$oNewPart->type = $sMimeType;
		$oBody->addPart($oNewPart);
		
		// Add additional images as new body parts
		foreach ($aAdditionalParts as $oAdditionalPart) {
			$oBody->addPart($oAdditionalPart);
		}

		if($oBody->isMultiPart()){
			$oContentTypeHeader = $this->m_oMessage->getHeaders();
			foreach ($oContentTypeHeader as $oHeader) {
				if (!$oHeader instanceof ContentType) {
					continue;
				}

				$oHeader->setType(Mime::MULTIPART_MIXED);
				$oHeader->addParameter('boundary', $oBody->getMime()->boundary());
				break;
			}
		}
		
		$this->m_oMessage->setBody($oBody);
	}

	/**
	 * Add a new part to the existing body
	 * @param $sText
	 * @param string $sMimeType
	 *
	 * @return void
	 */
	public function AddPart($sText, string $sMimeType = Mime::TYPE_HTML)
	{
		if (!array_key_exists('parts', $this->m_aData))
		{
			$this->m_aData['parts'] = array();
		}
		$this->m_aData['parts'][] = array('text' => $sText, 'mimeType' => $sMimeType);
		$oNewPart = new Part($sText);
		$oNewPart->encoding = Mime::ENCODING_8BIT;
		$oNewPart->type = $sMimeType;
		$this->m_oMessage->getBody()->addPart($oNewPart);
	}

	public function AddAttachment($data, $sFileName, $sMimeType)
	{
		$oBody = $this->m_oMessage->getBody();

		if(!$oBody->isMultiPart()){
			$multipart_content = new Part($oBody->generateMessage());
			$multipart_content->setType($oBody->getParts()[0]->getType());
			$multipart_content->setBoundary($oBody->getMime()->boundary());

			$oBody = new Laminas\Mime\Message();
			$oBody->addPart($multipart_content);
		}

		if (!array_key_exists('attachments', $this->m_aData))
		{
			$this->m_aData['attachments'] = array();
		}
		$this->m_aData['attachments'][] = array('data' => base64_encode($data), 'filename' => $sFileName, 'mimeType' => $sMimeType);
		$oNewAttachment = new Part($data);
		$oNewAttachment->type = $sMimeType;
		$oNewAttachment->filename = $sFileName;
		$oNewAttachment->disposition = Mime::DISPOSITION_ATTACHMENT;
		$oNewAttachment->encoding = Mime::ENCODING_BASE64;


		$oBody->addPart($oNewAttachment);

		if($oBody->isMultiPart()){
			$oContentTypeHeader = $this->m_oMessage->getHeaders();
			foreach ($oContentTypeHeader as $oHeader) {
				if (!$oHeader instanceof ContentType) {
					continue;
				}

				$oHeader->setType(Mime::MULTIPART_MIXED);
				$oHeader->addParameter('boundary', $oBody->getMime()->boundary());
				break;
			}
		}

		$this->m_oMessage->setBody($oBody);
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
		if ($aRes === null || $aRes->count() === 0)
		{
			// There is no "To" header field
			$aRes = array();
		}
		if ($bAsString)
		{
			$aStrings = array();
			foreach ($aRes as $oEmail)
			{
				$sName = $oEmail->getName();
				$sEmail = $oEmail->getEmail();
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

	public function SetRecipientReplyTo($sAddress, $sLabel = '')
	{
		$this->m_aData['reply_to'] = array('address' => $sAddress, 'label' => $sLabel);
		if ($sLabel != '')
		{
			$this->m_oMessage->setReplyTo(array($sAddress => $sLabel));
		}
		else if (!empty($sAddress))
		{
			$this->m_oMessage->setReplyTo($sAddress);
		}
	}
}