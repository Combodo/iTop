<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
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

		// Iterate throw class attributes...
		foreach (MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef) {

			// Set attribute blobs in read only
			if ($oAttDef instanceof AttributeBlob) {
				$aExtraParams['fieldsFlags'][$sAttCode] = OPT_ATT_READONLY;
				$aExtraParams['fieldsComments'][$sAttCode] = '&nbsp;<img src="../images/transp-lock.png" style="vertical-align:middle" title="'.utils::EscapeHtml(Dict::S('UI:UploadNotSupportedInThisMode')).'"/>';
			}
		}
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
		foreach (MetaModel::ListAttributeDefs(get_class($oObject)) as $sAttCode => $oAttDef) {
			if ($oAttDef instanceof AttributeBlob && (!$oAttDef->IsNullAllowed() || ($oObject->GetFormAttributeFlags($sAttCode) & OPT_ATT_MANDATORY))) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns an Alert explaining what will happen when a mandatory attribute blob is displayed in a form
	 * 
	 * @see N°6861 - Display warning when creating/editing a mandatory blob in modal
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function GetAlertForMandatoryAttributeBlobInputsInModal(): Alert
	{
		$oAlert = AlertUIBlockFactory::MakeForWarning('',Dict::S('UI:Object:Modal:MandatoryAttributeBlobInputs:Warning:Text'));
		return $oAlert;
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