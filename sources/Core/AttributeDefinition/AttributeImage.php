<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use DBObject;
use HTMLSanitizer;
use ormDocument;
use utils;

/**
 * An image is a specific type of document, it is stored as several columns in the database
 *
 * @package     iTopORM
 */
class AttributeImage extends AttributeBlob
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function Get($sParamName)
	{
		$oParamValue = parent::Get($sParamName);

		if ($sParamName === 'default_image') {
			/** @noinspection NestedPositiveIfStatementsInspection */
			if (!empty($oParamValue)) {
				return utils::GetAbsoluteUrlModulesRoot().$oParamValue;
			}
		}

		return $oParamValue;
	}

	public function GetEditClass()
	{
		return "Image";
	}

	/**
	 * {@inheritDoc}
	 * @see AttributeBlob::MakeRealValue()
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		$oDoc = parent::MakeRealValue($proposedValue, $oHostObj);

		if (($oDoc instanceof ormDocument)
			&& (false === $oDoc->IsEmpty())
			&& ($oDoc->GetMimeType() === 'image/svg+xml')) {
			$sCleanSvg = HTMLSanitizer::Sanitize($oDoc->GetData(), 'svg_sanitizer');
			$oDoc = new ormDocument($sCleanSvg, $oDoc->GetMimeType(), $oDoc->GetFileName());
		}

		// The validation of the MIME Type is done by CheckFormat below
		return $oDoc;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return new ormDocument('', '', '');
	}

	/**
	 * Check that the supplied ormDocument actually contains an image
	 * {@inheritDoc}
	 *
	 * @see AttributeDefinition::CheckFormat()
	 */
	public function CheckFormat($value)
	{
		if ($value instanceof ormDocument && !$value->IsEmpty()) {
			return ($value->GetMainMimeType() == 'image');
		}

		return true;
	}

	/**
	 * @see edit_image.js for JS generated markup in form edition
	 *
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @param ormDocument $value
	 *
	 * @return string
	 *
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		$sRet = '';
		$bIsCustomImage = false;

		$iMaxWidth = $this->Get('display_max_width');
		$sMaxWidthPx = $iMaxWidth.'px';
		$iMaxHeight = $this->Get('display_max_height');
		$sMaxHeightPx = $iMaxHeight.'px';

		$sDefaultImageUrl = $this->Get('default_image');
		if ($sDefaultImageUrl !== null) {
			$sRet = $this->GetHtmlForImageUrl($sDefaultImageUrl, $sMaxWidthPx, $sMaxHeightPx);
		}

		$sCustomImageUrl = $this->GetAttributeImageFileUrl($value, $oHostObject);
		if ($sCustomImageUrl !== null) {
			$bIsCustomImage = true;
			$sRet = $this->GetHtmlForImageUrl($sCustomImageUrl, $sMaxWidthPx, $sMaxHeightPx);
		}

		$sCssClasses = 'ibo-input-image--image-view attribute-image';
		$sCssClasses .= ' '.(($bIsCustomImage) ? 'attribute-image-custom' : 'attribute-image-default');

		// Important: If you change this, mind updating edit_image.js as well
		return '<div class="'.$sCssClasses.'" style="max-width: min('.$sMaxWidthPx.',100%); max-height: min('.$sMaxHeightPx.',100%); aspect-ratio: '.$iMaxWidth.' / '.$iMaxHeight.'">'.$sRet.'</div>';
	}

	/**
	 * @param string $sUrl
	 * @param int $iMaxWidthPx
	 * @param int $iMaxHeightPx
	 *
	 * @return string
	 *
	 * @since 2.6.0 new private method
	 * @since 2.7.0 change visibility to protected
	 */
	protected function GetHtmlForImageUrl($sUrl, $iMaxWidthPx, $iMaxHeightPx)
	{
		return '<img src="'.$sUrl.'" style="max-width: min('.$iMaxWidthPx.',100%); max-height: min('.$iMaxHeightPx.',100%)">';
	}

	/**
	 * @param ormDocument $value
	 * @param DBObject $oHostObject
	 *
	 * @return null|string
	 *
	 * @since 2.6.0 new private method
	 * @since 2.7.0 change visibility to protected
	 */
	protected function GetAttributeImageFileUrl($value, $oHostObject)
	{
		if (!is_object($value)) {
			return null;
		}
		if ($value->IsEmpty()) {
			return null;
		}

		$bExistingImageModified = ($oHostObject->IsModified() && (array_key_exists($this->GetCode(), $oHostObject->ListChanges())));
		if ($oHostObject->IsNew() || ($bExistingImageModified)) {
			// If the object is modified (or not yet stored in the database) we must serve the content of the image directly inline
			// otherwise (if we just give an URL) the browser will be given the wrong content... and may cache it
			return 'data:'.$value->GetMimeType().';base64,'.base64_encode($value->GetData());
		}

		return $value->GetDisplayURL(get_class($oHostObject), $oHostObject->GetKey(), $this->GetCode());
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\ImageField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		parent::MakeFormField($oObject, $oFormField);

		// Generating urls
		$value = $oObject->Get($this->GetCode());
		if (is_object($value) && !$value->IsEmpty()) {
			$oFormField->SetDownloadUrl($value->GetDownloadURL(get_class($oObject), $oObject->GetKey(), $this->GetCode()));
			$oFormField->SetDisplayUrl($value->GetDisplayURL(get_class($oObject), $oObject->GetKey(), $this->GetCode()));
		} else {
			$oDefaultImage = $this->Get('default_image');
			if (is_object($oDefaultImage) && !$oDefaultImage->IsEmpty()) {
				$oFormField->SetDownloadUrl($oDefaultImage);
				$oFormField->SetDisplayUrl($oDefaultImage);
			}
		}

		return $oFormField;
	}
}