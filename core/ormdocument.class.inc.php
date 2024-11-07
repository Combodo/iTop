<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;


/**
 * ormDocument
 * encapsulate the behavior of a binary data set that will be stored an attribute of class AttributeBlob 
 *
 * @package     itopORM
 */
class ormDocument
{
	/**
	 * @var string For content that should be displayed in the browser
	 * @link https://developer.mozilla.org/fr/docs/Web/HTTP/Headers/Content-Disposition#syntaxe
	 * @since 3.1.0
	 */
	public const ENUM_CONTENT_DISPOSITION_INLINE = 'inline';
	/**
	 * @var string For content that should be downloaded on the device. Mind that "attachment" Content-Disposition has nothing to do with the "Attachment" class from the DataModel.
	 * @link https://developer.mozilla.org/fr/docs/Web/HTTP/Headers/Content-Disposition#syntaxe
	 * @since 3.1.0
	 */
	public const ENUM_CONTENT_DISPOSITION_ATTACHMENT = 'attachment';

	/**
	 * @var int Default downloads count of the document, should always be 0.
	 * @since 3.1.0
	 */
	public const DEFAULT_DOWNLOADS_COUNT = 0;

	protected $m_data;
	protected $m_sMimeType;
	protected $m_sFileName;
	/**
	 * @var int $m_iDownloadsCount Number of times the document has been downloaded (through the standard API!). Note that download from the browser's cache won't appear.
	 * @since 3.1.0
	 */
	private $m_iDownloadsCount;

	/**
	 * Constructor
	 *
	 * @param null $data
	 * @param string $sMimeType
	 * @param string $sFileName
	 * @param int $iDownloadsCount
	 *
	 * @since 3.1.0 N°2889 Add $iDownloadsCount parameter
	 */
	public function __construct($data = null, $sMimeType = 'text/plain', $sFileName = '', $iDownloadsCount = self::DEFAULT_DOWNLOADS_COUNT)
	{
		$this->m_data = $data;
		$this->m_sMimeType = $sMimeType;
		$this->m_sFileName = $sFileName;
		$this->m_iDownloadsCount = $iDownloadsCount;
	}

	public function __toString()
	{
	    if($this->IsEmpty()) return '';

		return MyHelpers::beautifulstr($this->m_data, 100, true);
	}

	public function IsEmpty()
	{
		return ($this->m_data == null);
	}

	/**
	 * @param \ormDocument $oCompared
	 *
	 * @return bool True if the current ormDocument is equals to $oCompared EXCEPT for its download count. False if any other property is different OR if count is the same.
	 * @since 3.1.0 N°6502
	 */
	public function EqualsExceptDownloadsCount(ormDocument $oCompared): bool
	{
		// First checking equality on others properties
		if ($oCompared->GetData() !== $this->GetData()) {
			return false;
		}
		if ($oCompared->GetMimeType() !== $this->GetMimeType()) {
			return false;
		}
		if ($oCompared->GetFileName() !== $this->GetFileName()) {
			return false;
		}

		// Finally check equality of the download count
		if ($oCompared->GetDownloadsCount() === $this->GetDownloadsCount()) {
			return false;
		} else {
			return true;
		}
	}
	
	public function GetMimeType()
	{
		return $this->m_sMimeType;
	}
	public function GetMainMimeType()
	{
		$iSeparatorPos = strpos($this->m_sMimeType, '/');
		if ($iSeparatorPos > 0)
		{
			return substr($this->m_sMimeType, 0, $iSeparatorPos);
		}
		return $this->m_sMimeType;
	}

	/**
	 * @return int size in bits
	 * @uses strlen which returns the no of bits used
	 * @since 2.7.0
	 */
	public function GetSize()
	{
		return strlen($this->m_data);
	}

	/**
	 * @param int $precision
	 *
	 * @return string
	 * @uses utils::BytesToFriendlyFormat()
	 */
	public function GetFormattedSize($precision = 2)
	{
		$bytes = $this->GetSize();
		return utils::BytesToFriendlyFormat($bytes, $precision);
	}
	public function GetData()
	{
		return $this->m_data;
	}

	public function GetFileName()
	{
		return $this->m_sFileName;
	}

	/**
	 * @see static::DownloadDocument()
	 * @see static::$m_iDownloadsCount
	 * @return int Number of times the document has been downloaded (through the standard API!)
	 * @since 3.1.0
	 */
	public function GetDownloadsCount(): int
	{
		// Force cast to get 0 instead of null on fields prior to the features that have never been downloaded.
		return (int) $this->m_iDownloadsCount;
	}

	/**
	 * Increase the number of downloads of the document by $iNumber
	 *
	 * @param int $iNumber Step to increase the counter with, default is 1.
	 * @return void
	 * @since 3.1.0
	 */
	public function IncreaseDownloadsCount($iNumber = 1): void
	{
		$this->m_iDownloadsCount += $iNumber;
	}

	public function GetAsHTML()
	{
		$sResult = '';
		if ($this->IsEmpty()) {
			// If the filename is not empty, display it, this is used
			// by the creation wizard while the file has not yet been uploaded
			$sResult = utils::EscapeHtml($this->GetFileName());
		} else {
			$data = $this->GetData();
			$sSize = utils::BytesToFriendlyFormat(strlen($data));
			$iDownloadsCount = $this->GetDownloadsCount();
			$sDownloadsCountForHtml = utils::HtmlEntities(Dict::Format('Core:ormValue:ormDocument:DownloadsCount', $iDownloadsCount));
			$sDownloadsCountTooltipForHtml = utils::HtmlEntities(Dict::Format('Core:ormValue:ormDocument:DownloadsCount+', $iDownloadsCount));
			$sResult = utils::EscapeHtml($this->GetFileName()).' ('.$sSize.' / '.$sDownloadsCountForHtml.' <i class="fas fa-cloud-download-alt" data-tooltip-content="'.$sDownloadsCountTooltipForHtml.'"></i>)<br/>';
		}
		return $sResult;
	}
		
	/**
	 * Returns an hyperlink to display the document *inline*
	 * @return string
	 */	 	 	
	public function GetDisplayLink($sClass, $Id, $sAttCode)
	{
		$sUrl = $this->GetDisplayURL($sClass, $Id, $sAttCode);

		return "<a href=\"$sUrl\" target=\"_blank\" >".utils::EscapeHtml($this->GetFileName())."</a>\n";
	}
	
	/**
	 * Returns an hyperlink to download the document (content-disposition: attachment)
	 * @return string
	 */	 	 	
	public function GetDownloadLink($sClass, $Id, $sAttCode)
	{
		$sUrl = $this->GetDownloadURL($sClass, $Id, $sAttCode);

		return "<a href=\"$sUrl\">".utils::EscapeHtml($this->GetFileName())."</a>\n";
	}

	/**
	 * Returns an URL to display a document like an image
	 * @return string
	 */
	public function GetDisplayURL($sClass, $Id, $sAttCode)
	{
		$sSignature = $this->GetSignature();
		// TODO: When refactoring this with the URLMaker system, mind to also change calls in the portal (look for the "p_object_document_display" route)
		return utils::GetAbsoluteUrlAppRoot() . "pages/ajax.render.php?operation=display_document&class=$sClass&id=$Id&field=$sAttCode&s=$sSignature&cache=86400";
	}

	/**
	 * Returns an URL to download a document like an image (uses HTTP caching)
	 * @return string
	 */
	public function GetDownloadURL($sClass, $Id, $sAttCode)
	{
		// Compute a signature to reset the cache anytime the data changes (this is acceptable if used only with icon files)
		$sSignature = $this->GetSignature();
		// TODO: When refactoring this with the URLMaker system, mind to also change calls in the portal (look for the "p_object_document_display" route)
		return utils::GetAbsoluteUrlAppRoot() . "pages/ajax.document.php?operation=download_document&class=$sClass&id=$Id&field=$sAttCode&s=$sSignature&cache=86400";
	}

	public function IsPreviewAvailable()
	{
		$bRet = false;
		switch($this->GetMimeType())
		{
			case 'image/png':
			case 'image/jpg':
			case 'image/jpeg':
			case 'image/gif':
			case 'image/bmp':
			case 'image/svg+xml':
			$bRet = true;
			break;
		}
		return $bRet;
	}

	/**
	 * Downloads a document to the browser, either as 'inline' or 'attachment'
	 *
	 * @param WebPage $oPage The web page for the output
	 * @param string $sClass Class name of the object
	 * @param mixed $id Identifier of the object
	 * @param string $sAttCode Name of the attribute containing the document to download
	 * @param string $sContentDisposition Either 'inline' or 'attachment'
	 * @param string $sSecretField The attcode of the field containing a "secret" to be provided in order to retrieve the file
	 * @param string $sSecretValue The value of the secret to be compared with the value of the attribute $sSecretField
	 *
	 * @return void
	 */
	public static function DownloadDocument(WebPage $oPage, $sClass, $id, $sAttCode, $sContentDisposition = 'attachment', $sSecretField = null, $sSecretValue = null)
	{
		try
		{
			$oObj = MetaModel::GetObject($sClass, $id, false, false);
			if (!is_object($oObj))
			{
				// If access to the document is not granted, check if the access to the host object is allowed
				$oObj = MetaModel::GetObject($sClass, $id, false, true);
				if ($oObj instanceof Attachment) {
					$sItemClass = $oObj->Get('item_class');
					$sItemId = $oObj->Get('item_id');
					$oHost = MetaModel::GetObject($sItemClass, $sItemId, false, false);
					if (!is_object($oHost)) {
						$oObj = null;
					}
				}
				if (!is_object($oObj)) {
					throw new Exception("Invalid id ($id) for class '$sClass' - the object does not exist or you are not allowed to view it");
				}
			}
			if (($sSecretField != null) && ($oObj->Get($sSecretField) != $sSecretValue))
			{
				usleep(200);
				throw new Exception("Invalid secret for class '$sClass' - the object does not exist or you are not allowed to view it");
			}
			/** @var \ormDocument $oDocument */
			$oDocument = $oObj->Get($sAttCode);
			if (is_object($oDocument))
			{
				$aEventData = array(
					'debug_info' => $oDocument->GetFileName(),
					'object' => $oObj,
					'att_code' => $sAttCode,
					'document' => $oDocument,
					'content_disposition' => $sContentDisposition,
					);
				EventService::FireEvent(new EventData(EVENT_DOWNLOAD_DOCUMENT, $sClass, $aEventData));
				$oPage->TrashUnexpectedOutput();
				$oPage->SetContentType($oDocument->GetMimeType());
				$oPage->SetContentDisposition($sContentDisposition,$oDocument->GetFileName());
				$oPage->add($oDocument->GetData());

				// Update downloads count only when content disposition is set to "attachment" as other disposition are to display the document within the page
				if($sContentDisposition === static::ENUM_CONTENT_DISPOSITION_ATTACHMENT) {
					$oDocument->IncreaseDownloadsCount();
					$oObj->Set($sAttCode, $oDocument);
					// $oObj can be a \DBObject or \cmdbAbstractObject so we ahve to protect it
					if (method_exists($oObj, 'AllowWrite')) {
						// AllowWrite method is implemented in cmdbAbstractObject, but $oObject could be a DBObject or CMDBObject
						$oObj->AllowWrite();
					}
					$oObj->DBUpdate();
				}
			}
		}
		catch(Exception $e)
		{
			$oPage->p($e->getMessage());
		}
	}

	/**
	 * @return string
	 */
	public function GetSignature(): string
	{
		return md5($this->GetData() ?? '');
	}
}
