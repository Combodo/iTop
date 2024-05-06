<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\Helper;

use AttributeBlob;
use Combodo\iTop\Application\UI\Base\Component\Alert\Alert;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use DBObject;
use Dict;
use MetaModel;
use utils;

/**
 * Class FormHelper
 *
 * @internal
 * @author Benjamin Dalsass <benjamin.dalsass@combodo.com>
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @since 3.1.0
 * @package Combodo\iTop\Application\Helper
 */
class FormHelper
{
	/**
	 * @var string
	 * @since 3.1.1 N°6861
	 */
	public const ENUM_MANDATORY_BLOB_MODE_CREATE = 'Create';
	/**
	 * @var string
	 * @since 3.1.1 N°6861
	 */
	public const ENUM_MANDATORY_BLOB_MODE_MODIFY_EMPTY = 'Modify';
	/**
	 * @var string
	 * @since 3.1.1 N°6861
	 */
	public const ENUM_MANDATORY_BLOB_MODE_MODIFY_FILLED = 'Modify:Filled';


	/**
	 * DisableAttributeBlobInputs.
	 *
	 * @see N°5863 to allow blob edition in modal context.
	 *
	 * @param string $sClassName Form object class name
	 * @param array $aExtraParams Array extra parameters (to fill)
	 *
	 * @return void
	 * @throws \CoreException
	 */
	public static function DisableAttributeBlobInputs(string $sClassName, array &$aExtraParams): void
	{
		// Initialize extra params array
		if (!array_key_exists('fieldsFlags', $aExtraParams)) {
			$aExtraParams['fieldsFlags'] = [];
		}
		if (!array_key_exists('fieldsComments', $aExtraParams)) {
			$aExtraParams['fieldsComments'] = [];
		}

		// Iterate through class attributes...
		$sAppRootUrl = utils::GetAbsoluteUrlAppRoot();
		foreach (MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef) {
			// Set attribute blobs in read only
			if ($oAttDef instanceof AttributeBlob) {
				$aExtraParams['fieldsFlags'][$sAttCode] = OPT_ATT_READONLY;
				$aExtraParams['fieldsComments'][$sAttCode] = '&nbsp;<img src="' . $sAppRootUrl . 'images/transp-lock.png" style="vertical-align:middle" title="'.utils::EscapeHtml(Dict::S('UI:UploadNotSupportedInThisMode')).'"/>';
			}
		}
	}

	/**
	 * Returns an attribute code if the object has a mandatory attribute blob, null otherwise
	 *
	 * @see N°6861 - Display warning when creating/editing a mandatory blob in modal
	 *
	 * @param \DBObject $oObject
	 *
	 * @return string|null
	 * @throws \CoreException
	 */
	public static function GetMandatoryAttributeBlobInputs(DBObject $oObject): ?string
	{
		foreach (MetaModel::ListAttributeDefs(get_class($oObject)) as $sAttCode => $oAttDef) {
			if ($oAttDef instanceof AttributeBlob && (!$oAttDef->IsNullAllowed() || ($oObject->GetFormAttributeFlags($sAttCode) & OPT_ATT_MANDATORY))) {
				return $sAttCode;
			}
		}
		return null;
	}
	
	/**
	 * Returns true if the object has a mandatory attribute blob
	 * 
	 * @see N°6861 - Display warning when creating/editing a mandatory blob in modal
	 * 
	 * @param \DBObject $oObject
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	public static function HasMandatoryAttributeBlobInputs(DBObject $oObject): bool
	{
		return self::GetMandatoryAttributeBlobInputs($oObject) !== null;
	}

	/**
	 * Returns an Alert explaining what will happen when a mandatory attribute blob is displayed in a form
	 * 
	 * @see N°6861 - Display warning when creating/editing a mandatory blob in modal
	 * @see self::ENUM_MANDATORY_BLOB_MODE_XXX
	 *
	 * @param string $sMode
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function GetAlertForMandatoryAttributeBlobInputsInModal(string $sMode = self::ENUM_MANDATORY_BLOB_MODE_MODIFY_EMPTY): Alert
	{
		$sMessage = Dict::S('UI:Object:Modal:'.$sMode.':MandatoryAttributeBlobInputs:Warning:Text');
		
		// If the mandatory attribute is already filled, there's no risk to make an object incomplete so we display an information level alert
		if($sMode === self::ENUM_MANDATORY_BLOB_MODE_MODIFY_FILLED){
			return AlertUIBlockFactory::MakeForInformation('', $sMessage);
		}

		return 	AlertUIBlockFactory::MakeForWarning('', $sMessage);
	}
	
	/**
	 * Update flags to be sent to form with url parameters
	 * For now only supports "readonly" param
	 * 
	 * @param \DBObject $oObject
	 * @param array $aExtraParams
	 *
	 * @return void
	 * @throws \Exception
	 */
	public static function UpdateFlagsFromContext(DBObject $oObject, array &$aExtraParams): void
	{
		$aRawValues = utils::ReadParam('readonly', [], '', 'raw_data');
		$sObjectClass = get_class($oObject);
		
		if(array_key_exists('fieldsFlags', $aExtraParams) === false ) {
			$aExtraParams['fieldsFlags'] = [];
		}		
		
		if(array_key_exists('forceFieldsSubmission', $aExtraParams) === false ) {
			$aExtraParams['forceFieldsSubmission'] = [];
		}
		// - For each attribute present in readonly array in url, add a flag and mark them as to be submitted with their default value
		foreach($aRawValues as $sAttCode => $sValue) {
			if(MetaModel::IsValidAttCode($sObjectClass, $sAttCode)) {
				$aExtraParams['fieldsFlags'][$sAttCode] = array_key_exists($sAttCode, $aExtraParams['fieldsFlags']) ?
					$aExtraParams['fieldsFlags'][$sAttCode] & OPT_ATT_READONLY :
					OPT_ATT_READONLY;
				
				$aExtraParams['forceFieldsSubmission'][] = $sAttCode;
			}
		}
	}

	/**
	 * Get attribute flag for an object allowing to cross-check with extra flags present in a form
	 * 
	 * @param \DBObject $oObject
	 * @param string $sAttCode
	 * @param array $aExtraFlags
	 *
	 * @return int
	 */
	public static function GetAttributeFlagsForObject(DBObject $oObject, string $sAttCode, array $aExtraFlags = []): int {
		$iFlags = $oObject->GetFormAttributeFlags($sAttCode);
		if (array_key_exists($sAttCode, $aExtraFlags)) {
			// the caller may override some flags if needed
			$iFlags |= $aExtraFlags[$sAttCode];
		}
		return $iFlags;
	}
}