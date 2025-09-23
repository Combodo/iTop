<?php
/**
 * Send an email (abstraction for synchronous/asynchronous modes)
 *
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\Email;

use AsyncSendEmail;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderFactory;
use Combodo\iTop\Core\Email\Transport\SymfonyFileTransport;
use Combodo\iTop\Core\Email\Transport\SymfonyOAuthTransport;
use DOMDocument;
use DOMXPath;
use EMail;
use Exception;
use ExecutionKPI;
use InlineImage;
use IssueLog;
use MetaModel;
use Pelago\Emogrifier\CssInliner;
use Pelago\Emogrifier\HtmlProcessor\CssToAttributeConverter;
use Pelago\Emogrifier\HtmlProcessor\HtmlPruner;
use Symfony\Component\CssSelector\Exception\ParseException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\RelatedPart;
use Symfony\Component\Mime\Part\Multipart\MixedPart;
use Symfony\Component\Mime\Part\Multipart\AlternativePart;
use Symfony\Component\Mime\Part\TextPart;

class EMailSymfony extends Email
{
	// Serialization formats
	const ORIGINAL_FORMAT = 1; // Original format, consisting in serializing the whole object, inculding the Swift Mailer's object.
	// Did not work with attachements since their binary representation cannot be stored as a valid UTF-8 string
	const FORMAT_V2 = 2; // New format, only the raw data are serialized (base64 encoded if needed)

	protected $m_aData; // For storing data to serialize

	protected $m_oMessage;

	/**
	 * @noinspection PhpMissingParentConstructorInspection
	 * @noinspection MagicMethodsValidityInspection
	 */
	public function __construct()
	{
		$this->m_aData = array();
		$this->m_oMessage = new SymfonyEmail();

		$this->InitRecipientFrom();
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
	 */
	public static function UnSerializeV2($sSerializedMessage)
	{
		$aData = unserialize($sSerializedMessage);
		$oMessage = new Email();

		if (array_key_exists('body', $aData)) {
			$oMessage->SetBody($aData['body']['body'], $aData['body']['mimeType']);
		}
		if (array_key_exists('message_id', $aData)) {
			$oMessage->SetMessageId($aData['message_id']);
		}
		if (array_key_exists('bcc', $aData)) {
			$oMessage->SetRecipientBCC($aData['bcc']);
		}
		if (array_key_exists('cc', $aData)) {
			$oMessage->SetRecipientCC($aData['cc']);
		}
		if (array_key_exists('from', $aData)) {
			$oMessage->SetRecipientFrom($aData['from']['address'], $aData['from']['label']);
		}
		if (array_key_exists('reply_to', $aData)) {
			$oMessage->SetRecipientReplyTo($aData['reply_to']['address'], $aData['reply_to']['label']);
		}
		if (array_key_exists('to', $aData)) {
			$oMessage->SetRecipientTO($aData['to']);
		}
		if (array_key_exists('subject', $aData)) {
			$oMessage->SetSubject($aData['subject']);
		}

		if (array_key_exists('headers', $aData)) {
			foreach ($aData['headers'] as $sKey => $sValue) {
				$oMessage->AddToHeader($sKey, $sValue);
			}
		}
		if (array_key_exists('parts', $aData)) {
			foreach ($aData['parts'] as $aPart) {
				$oMessage->AddPart($aPart['text'], $aPart['mimeType']);
			}
		}
		if (array_key_exists('attachments', $aData)) {
			foreach ($aData['attachments'] as $aAttachment) {
				$oMessage->AddAttachment(base64_decode($aAttachment['data']), $aAttachment['filename'], $aAttachment['mimeType']);
			}
		}

		return $oMessage;
	}

	protected function SendAsynchronous(&$aIssues, $oLog = null)
	{
		try {
			AsyncSendEmail::AddToQueue($this, $oLog);
		}
		catch (Exception $e) {
			$aIssues = array($e->GetMessage());
			return EMAIL_SEND_ERROR;
		}
		$aIssues = array();
		return EMAIL_SEND_PENDING;
	}

	public static function GetMailer()
	{
		return new EMailSymfony();
	}

	/**
	 * Send synchronously using symfony/mailer
	 *
	 * @throws \Exception
	 */
	protected function SendSynchronous(&$aIssues, $oLog = null)
	{
		$this->LoadConfig();

		$sTransport = self::$m_oConfig->Get('email_transport');
		$oMailer = null;
		$oTransport = null;

		switch ($sTransport) {
			case 'SMTP':
				$sHost = self::$m_oConfig->Get('email_transport_smtp.host');
				$sPort = self::$m_oConfig->Get('email_transport_smtp.port');
				$sEncryption = self::$m_oConfig->Get('email_transport_smtp.encryption');
				$sUserName = self::$m_oConfig->Get('email_transport_smtp.username');
				$sPassword = self::$m_oConfig->Get('email_transport_smtp.password');
				$bVerifyPeer = static::$m_oConfig->Get('email_transport_smtp.verify_peer');


				// Build the DSN string
				$sDsnUser = $sUserName !== null ? rawurlencode($sUserName) : '';
				$sDsnPassword = ($sPassword !== null && $sPassword !== '') ? ':' . rawurlencode($sPassword) : '';
				$sDsnPort = $sHost . (strlen($sPort) ? ':' . $sPort : '');
				$sDsn = null;

				if (strtolower($sEncryption) === 'ssl') {
					// Implicit TLS (smtps)
					$sDsn = sprintf('smtps://%s%s@%s', $sDsnUser, $sDsnPassword, $sDsnPort);
				} else {
					// Regular smtp, can enable starttls via query param
					$sEncQuery = '';
					if (strtolower($sEncryption) === 'tls') {
						$sEncQuery = '?encryption=starttls';
					}
					$sDsn = sprintf('smtp://%s%s@%s%s', $sDsnUser, $sDsnPassword, $sDsnPort, $sEncQuery);
				}

				$oTransport = Transport::fromDsn($sDsn);

				// Handle peer verification
				$oStream = $oTransport->getStream();
				$aOptions= $oStream->getStreamOptions();
				if (!$bVerifyPeer && array_key_exists('ssl', $aOptions)) {
					// Disable verification
					$aOptions['ssl']['verify_peer'] = false;
					$aOptions['ssl']['verify_peer_name'] = false;
					$aOptions['ssl']['allow_self_signed'] = true;
				}
				$oStream->setStreamOptions($aOptions);

				$oMailer = new Mailer($oTransport);
				break;

			case 'SMTP_OAuth':
				// Use custom SMTP transport
				$sHost = self::$m_oConfig->Get('email_transport_smtp.host');
				$sPort = self::$m_oConfig->Get('email_transport_smtp.port');
				$sEncryption = self::$m_oConfig->Get('email_transport_smtp.encryption');
				$sUserName = self::$m_oConfig->Get('email_transport_smtp.username');

				$oTransport = new SymfonyOAuthTransport([
					'host' => $sHost,
					'port' => $sPort,
					'encryption' => $sEncryption,
					'username' => $sUserName,
				]);
				$oMailer = new Mailer($oTransport);
				SymfonyOAuthTransport::setProvider(OAuthClientProviderFactory::GetProviderForSMTP());
				break;

			case 'Null':
				// Use a dummy transport
				$oTransport = Transport::fromDsn('null://null');
				$oMailer = new Mailer($oTransport);
				break;

			case 'LogFile':
				// Use a custom transport that writes to a log file
				// Note: the log file is not rotated, so this should be used for debugging
				$oTransport = new SymfonyFileTransport(APPROOT . 'log/', 'mail.log');
				$oMailer = new Mailer($oTransport);
				break;

			case 'PHPMail':
			default:
				// Use sendmail transport
				$oTransport = Transport::fromDsn('sendmail://default');
				$oMailer = new Mailer($oTransport);
		}

		$oKPI = new ExecutionKPI();

		try {
			if ($oMailer === null || $oTransport === null) {
				throw new \RuntimeException('No mailer transport configured.');
			}

			$oMailer->send($this->m_oMessage);
			$aIssues = array();
			$oKPI->ComputeStats('Email Sent', 'Succeded');

			return EMAIL_SEND_OK;
		}
		catch (TransportExceptionInterface $e) {
			IssueLog::Warning('Email sending failed: '.$e->getMessage());
			$aIssues = array($e->getMessage());
			$oKPI->ComputeStats('Email Sent', 'Error received');

			return EMAIL_SEND_ERROR;
		}
		catch (Exception $e) {
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
		$oDOMDoc->preserveWhiteSpace = true;
		@$oDOMDoc->loadHTML('<?xml encoding="UTF-8"?>'.$sBody);

		$oXPath = new DOMXPath($oDOMDoc);
		$sXPath = '//img[@'.InlineImage::DOM_ATTR_ID.']';
		$oImagesList = $oXPath->query($sXPath);
		$aImagesParts = [];
		if ($oImagesList->length !== 0) {
			foreach ($oImagesList as $oImg) {
				$iAttId = $oImg->getAttribute(InlineImage::DOM_ATTR_ID);
				$oAttachment = MetaModel::GetObject('InlineImage', $iAttId, false, true /* Allow All Data */);
				if ($oAttachment) {
					$sImageSecret = $oImg->getAttribute('data-img-secret');
					$sAttachmentSecret = $oAttachment->Get('secret');
					if ($sImageSecret !== $sAttachmentSecret) {
						// @see N°1921
						// If copying from another iTop we could get an IMG pointing to an InlineImage with wrong secret
						continue;
					}

					$oDoc = $oAttachment->Get('contents');
					// CID expects to be unique and to contain a @, see RFC 2392
					$sCid = uniqid('', true).'@openitop.org';

					$oPart = new DataPart($oDoc->GetData(), $oDoc->GetFileName(), $oDoc->GetMimeType());
					$oPart->setContentId($sCid)->asInline();

					$aImagesParts[] = $oPart;
					$oImg->setAttribute('src', 'cid:'.$sCid);
				}
			}
		}
		$sBody = $oDOMDoc->saveHTML();

		return $aImagesParts;
	}

	/**
	 * Sends an e-mail.
	 *
	 */
	public function Send(&$aIssues, $iSyncAsync = Email::ENUM_SEND_DEFAULT, $oLog = null)
	{
		//select a default sender if none is provided.
		if (empty($this->m_aData['from']['address']) && !empty($this->m_aData['to'])) {
			$this->SetRecipientFrom($this->m_aData['to']);
		}

		if($iSyncAsync === true) {
			return $this->SendSynchronous($aIssues, $oLog);
		} else {
			switch($iSyncAsync) {
				case Email::ENUM_SEND_FORCE_SYNCHRONOUS:
					return $this->SendSynchronous($aIssues, $oLog);
				case Email::ENUM_SEND_FORCE_ASYNCHRONOUS:
					return $this->SendAsynchronous($aIssues, $oLog);
				case Email::ENUM_SEND_DEFAULT:
				default:
					$oConfig = $this->LoadConfig();
					$bConfigASYNC = $oConfig->Get('email_asynchronous');
					if($bConfigASYNC) {
						return $this->SendAsynchronous($aIssues, $oLog);
					} else {
						return $this->SendSynchronous($aIssues, $oLog);
					}
			}
		}
	}

	/**
	 * Add a header line
	 */
	public function AddToHeader($sKey, $sValue)
	{
		if (!array_key_exists('headers', $this->m_aData)) {
			$this->m_aData['headers'] = array();
		}
		$this->m_aData['headers'][$sKey] = $sValue;

		if (strlen($sValue) > 0) {
			$this->m_oMessage->getHeaders()->addTextHeader($sKey, $sValue);
		}
	}

	public function SetMessageId($sId)
	{
		$this->m_aData['message_id'] = $sId;

		// Note: The email library will add the angle brackets for you
		// so let's remove the angle brackets if present, for historical reasons
		$sId = str_replace(array('<', '>'), '', $sId);

		$this->m_oMessage->getHeaders()->addIdHeader('Message-ID', $sId);
	}

	public function SetReferences($sReferences)
	{
		$this->AddToHeader('References', $sReferences);
	}

	public function SetInReplyTo(string $sMessageId)
	{
		// Note: Symfony will add the angle brackets
		// let's remove the angle brackets if present, for historical reasons
		$sId = str_replace(array('<', '>'), '', $sMessageId);
		$this->m_aData['in_reply_to'] = '<' . $sId . '>';

		$this->m_oMessage->getHeaders()->addTextHeader('In-Reply-To', '<' . $sId . '>');
	}

	/**
	 * Set current Email body and process inline images.
	 */
	public function SetBody($sBody, $sMimeType = 'text/html', $sCustomStyles = null)
	{
		// Inline CSS if needed
		if ($sMimeType === 'text/html') {
			$sBody = static::InlineCssIntoBodyContent($sBody, $sCustomStyles);
		}

		$this->m_aData['body'] = array('body' => $sBody, 'mimeType' => $sMimeType);

		$oTextPart = new TextPart(strip_tags($sBody), 'utf-8', 'plain', 'base64');

		// Embed inline images and store them in attachments (so BuildSymfonyMessageFromInternal can pick them)
		if ($sMimeType === 'text/html') {
			$aAdditionalParts = $this->EmbedInlineImages($sBody);
			$oHtmlPart = new TextPart($sBody, 'utf-8', 'html', 'base64');
			$oAlternativePart = new AlternativePart($oHtmlPart, $oTextPart);
			// Default root part is the HTML body
			$oRootPart = $oAlternativePart;

			if(count($aAdditionalParts) > 0) {
				$aRelatedParts = array_merge([$oAlternativePart], $aAdditionalParts);
				$oRootPart = new RelatedPart(...$aRelatedParts);
			}
		}
		else {
			// Default root part is the text body
			$oRootPart = $oTextPart;
		}

		$this->m_oMessage->setBody($oRootPart);
	}

	protected function GetMimeSubtype($sMimeType, $sDefault = 'html')
	{
		$sMimeSubtype = '';
		if (strpos($sMimeType, '/') !== false) {
			$aParts = explode('/', $sMimeType);
			if (count($aParts) > 1) {
				$sMimeSubtype = $aParts[1];
			}
		}
		return $sMimeSubtype !== '' ?? $sDefault;
	}

	/**
	 * Add a new part to the existing body
	 */
	public function AddPart($sText, $sMimeType = 'text/html')
	{
		$sMimeSubtype = $this->GetMimeSubtype($sMimeType);

		if (!array_key_exists('parts', $this->m_aData)) {
			$this->m_aData['parts'] = array();
		}
		$this->m_aData['parts'][] = array('text' => $sText, 'mimeType' => $sMimeType);

		$oNewPart = new TextPart($sText, $sMimeType, $sMimeSubtype, 'base64');
		$this->m_oMessage->addPart($oNewPart);
	}

	public function AddAttachment($data, $sFileName, $sMimeType)
	{
		if (!array_key_exists('attachments', $this->m_aData)) {
			$this->m_aData['attachments'] = array();
		}
		$this->m_aData['attachments'][] = array('data' => base64_encode($data), 'filename' => $sFileName, 'mimeType' => $sMimeType);

		$oBody = $this->m_oMessage->getBody();

		$oRootPart = $oBody;
		$aAttachmentPart = new DataPart($data, $sFileName, $sMimeType, 'base64');
		if( $oBody instanceof MixedPart) {
			$aCurrentParts = $oBody->getParts();
			$aCurrentParts[] = $aAttachmentPart;
			$oRootPart = new MixedPart(...$aCurrentParts);
		}
		else {
			$oRootPart = new MixedPart($oBody, $aAttachmentPart);
		}

		$this->m_oMessage->setBody($oRootPart);
	}

	public function SetSubject($sSubject)
	{
		$this->m_aData['subject'] = $sSubject;
		$this->m_oMessage->subject($sSubject);
	}

	public function GetSubject()
	{
		return $this->m_oMessage->getSubject();
	}

	/**
	 * Helper to transform and sanitize addresses
	 */
	protected function AddressStringToArray($sAddressCSVList)
	{
		$aAddresses = array();
		foreach (explode(',', $sAddressCSVList) as $sAddress) {
			$sAddress = trim($sAddress);
			if (strlen($sAddress) > 0) {
				$aAddresses[] = $sAddress;
			}
		}
		return $aAddresses;
	}

	public function SetRecipientTO($sAddress)
	{
		$this->m_aData['to'] = $sAddress;
		if (!empty($sAddress)) {
			$aAddresses = $this->AddressStringToArray($sAddress);
			$this->m_oMessage->to(...$aAddresses);
		}
	}

	public function GetRecipientTO($bAsString = false)
	{
		$aRes = $this->m_oMessage->getTo();

		if ($bAsString) {
			$aStrings = array();
			foreach ($aRes as $oEmail) {
				$sName = $oEmail->getName();
				$sEmail = $oEmail->getAddress();
				if (empty($sName)) {
					$aStrings[] = $sEmail;
				} else {
					$sName = str_replace(array('<', '>'), '', $sName);
					$aStrings[] = "$sName <$sEmail>";
				}
			}
			return implode(', ', $aStrings);
		}

		return $aRes;
	}

	public function SetRecipientCC($sAddress)
	{
		$this->m_aData['cc'] = $sAddress;
		if (!empty($sAddress)) {
			$aAddresses = $this->AddressStringToArray($sAddress);
			$this->m_oMessage->cc(...$aAddresses);
		}
	}

	public function SetRecipientBCC($sAddress)
	{
		$this->m_aData['bcc'] = $sAddress;
		if (!empty($sAddress)) {
			$aAddresses = $this->AddressStringToArray($sAddress);
			$this->m_oMessage->bcc(...$aAddresses);
		}
	}

	public function SetRecipientFrom($sAddress, $sLabel = '')
	{
		$this->m_aData['from'] = array('address' => $sAddress, 'label' => $sLabel);
		if ($sLabel != '') {
			$this->m_oMessage->from(sprintf('%s <%s>', $sLabel, $sAddress));
		} else if (!empty($sAddress)) {
			$this->m_oMessage->from($sAddress);
		}
	}

	public function SetRecipientReplyTo($sAddress, $sLabel = '')
	{
		$this->m_aData['reply_to'] = array('address' => $sAddress, 'label' => $sLabel);
		if ($sLabel != '') {
			$this->m_oMessage->replyTo(sprintf('%s <%s>', $sLabel, $sAddress));
		} else if (!empty($sAddress)) {
			$this->m_oMessage->replyTo($sAddress);
		}
	}

	/**
	 * @param string $sBody
	 * @param string $sCustomStyles
	 *
	 * @return string
	 * @throws ParseException
	 * @noinspection PhpUnnecessaryLocalVariableInspection
	 */
	protected static function InlineCssIntoBodyContent($sBody, $sCustomStyles): string
	{
		if (is_null($sCustomStyles)) {
			return $sBody;
		}

		$oDomDocument = CssInliner::fromHtml($sBody)->inlineCss($sCustomStyles)->getDomDocument();
		HtmlPruner::fromDomDocument($oDomDocument)->removeElementsWithDisplayNone();
		$sBody = CssToAttributeConverter::fromDomDocument($oDomDocument)->convertCssToVisualAttributes()->render();

		return $sBody;
	}
}