<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Base;

use Combodo\iTop\Core\MetaModel\FriendlyNameType;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use DBSearch;
use Exception;
use ExceptionLog;
use iDBObjectSetIterator;
use MetaModel;
use utils;
use WizardHelper;

/**
 * Class ObjectRepository
 *
 *
 * @internal
 * @since 3.1.0
 * @package Combodo\iTop\Service\Base
 */
class ObjectRepository
{
	/**
	 * Search.
	 *
	 * @param string $sObjectClass Object class to search
	 * @param array $aFieldsToLoad Additional fields to load
	 * @param string $sSearch Friendly name search string
	 *
	 * @return array|null
	 */
	static public function Search(string $sObjectClass, array $aFieldsToLoad, string $sSearch): ?array
	{
		try {

			// Create db search
			$oDbObjectSearch = new DBObjectSearch($sObjectClass);
			$oDbObjectSearch->SetShowObsoleteData(utils::ShowObsoleteData());

			// Add a friendly name search condition
			$oDbObjectSearch->AddCondition('friendlyname', $sSearch, 'Contains');

			// Create db object set
			$oSet = new DBObjectSet($oDbObjectSearch);

			// Transform set to array
			$aResult = ObjectRepository::DBSetToObjectArray($oSet, $sObjectClass, $aFieldsToLoad);

			// Handle max results for autocomplete
			if (Utils::IsNullOrEmptyString($sSearch)
				&& count($aResult) > MetaModel::GetConfig()->Get('max_autocomplete_results')) {
				return [];
			}

			return $aResult;
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return null;
		}
	}

	/**
	 * SearchFromOql.
	 *
	 * @param string $sObjectClass Object class to search
	 * @param array $aFieldsToLoad Additional fields to load
	 * @param string $sOql Oql expression
	 * @param string $sSearch Friendly name search string
	 * @param DBObject|null $oThisObject This object reference for oql
	 *
	 * @return array|null
	 */
	static public function SearchFromOql(string $sObjectClass, array $aFieldsToLoad, string $sOql, string $sSearch, DBObject $oThisObject = null): ?array
	{
		try {

			// Create db search
			$oDbObjectSearch = DBSearch::FromOQL($sOql);
			$oDbObjectSearch->SetShowObsoleteData(utils::ShowObsoleteData());
			$oDbObjectSearch->AddCondition('friendlyname', $sSearch, 'Contains');

			// Create db set from db search
			$oDbObjectSet = new DBObjectSet($oDbObjectSearch, [], ['this' => $oThisObject]);

			// return object array
			return ObjectRepository::DBSetToObjectArray($oDbObjectSet, $sObjectClass, $aFieldsToLoad);
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return null;
		}
	}

	/**
	 * DBSetToObjectArray.
	 *
	 * @param iDBObjectSetIterator $oDbObjectSet Db object set
	 * @param string $sObjectClass Object class
	 * @param array $aFieldsToLoad Additional fields to load
	 *
	 * @return array
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	static private function DBSetToObjectArray(iDBObjectSetIterator $oDbObjectSet, string $sObjectClass, array $aFieldsToLoad): array
	{
		// Retrieve friendly name complementary specification
		$aComplementAttributeSpec = MetaModel::GetNameSpec($sObjectClass, FriendlyNameType::COMPLEMENTARY);

		// Retrieve image attribute code
		$sObjectImageAttCode = MetaModel::GetImageAttributeCode($sObjectClass);

		// Prepare fields to load
		$aDefaultFieldsToLoad = ObjectRepository::GetDefaultFieldsToLoad($aComplementAttributeSpec, $sObjectImageAttCode);
		$aFieldsToLoad = array_merge($aDefaultFieldsToLoad, $aFieldsToLoad);

		// Optimize columns load
		$oDbObjectSet->OptimizeColumnLoad([
			$sObjectClass => $aFieldsToLoad,
		]);

		// Prepare result
		$aResult = [];

		// Iterate throw objects...
		$oDbObjectSet->Rewind();
		while ($oObject = $oDbObjectSet->Fetch()) {

			// Prepare objet data
			$aObjectData = [];

			// Object key
			$aObjectData['key'] = $oObject->GetKey();

			// Fill loaded columns...
			foreach ($aFieldsToLoad as $sField) {
				$aObjectData[$sField] = $oObject->Get($sField);
			}

			// Compute others data
			$aResult[] = ObjectRepository::ComputeOthersData($oObject, $sObjectClass, $aObjectData, $aComplementAttributeSpec, $sObjectImageAttCode);
		}

		return $aResult;
	}

	/**
	 * GetDefaultFieldsToLoad.
	 *
	 * Return attributes to load for any objects.
	 *
	 * @param array $aComplementAttributeSpec Friendly name complementary spec
	 * @param string $sObjectImageAttCode Image attribute code
	 *
	 * @return mixed
	 */
	static public function GetDefaultFieldsToLoad(array $aComplementAttributeSpec, string $sObjectImageAttCode)
	{
		// Friendly name complementary fields
		$aFieldsToLoad = $aComplementAttributeSpec[1];

		// Image attribute
		if (!empty($sObjectImageAttCode)) {
			$aFieldsToLoad[] = $sObjectImageAttCode;
		}

		// Add friendly name
		$aFieldsToLoad[] = 'friendlyname';

		return $aFieldsToLoad;
	}

	/**
	 * ComputeOthersData.
	 *
	 * @param DBObject $oDbObject Db object
	 * @param string $sClass Object class
	 * @param array $aData Object data to fill
	 * @param array $aComplementAttributeSpec Friendly name complementary spec
	 * @param string $sObjectImageAttCode Image attribute code
	 *
	 * @return array
	 */
	static public function ComputeOthersData(DBObject $oDbObject, string $sClass, array $aData, array $aComplementAttributeSpec, string $sObjectImageAttCode): array
	{
		try {

			// Obsolescence flag
			$aData['obsolescence_flag'] = $oDbObject->IsObsolete();

			// Additional fields
			if (count($aComplementAttributeSpec[1]) > 0) {
				$aArguments = [];
				foreach ($aComplementAttributeSpec[1] as $sAdditionalField) {
					$aArguments[] = $oDbObject->Get($sAdditionalField);
				}
				$aData['additional_field'] = utils::HtmlEntities(vsprintf($aComplementAttributeSpec[0], $aArguments));
				$aData['full_description'] = "{$aData['friendlyname']}<br><i><small>{$aData['additional_field']}</small></i>";
			} else {
				$aData['full_description'] = $aData['friendlyname'];
			}

			// Image
			if (!empty($sObjectImageAttCode)) {
				$aData['has_image'] = true;
				/** @var \ormDocument $oImage */
				$oImage = $oDbObject->Get($sObjectImageAttCode);
				if (!$oImage->IsEmpty()) {
					$aData['picture_url'] = "url('{$oImage->GetDisplayURL($sClass, $oDbObject->GetKey(), $sObjectImageAttCode)}')";
					$aData['initials'] = '';
				} else {
					$aData['initials'] = utils::FormatInitialsForMedallion(utils::ToAcronym($oDbObject->Get('friendlyname')));
				}
			}

			return $aData;
		}
		catch (Exception $e) {

			ExceptionLog::LogException($e);

			return $aData;
		}
	}

	/**
	 * GetObjectFromWizardHelperData
	 *
	 * @param string $sData
	 *
	 * @return DBObject|null
	 */
	public static function GetObjectFromWizardHelperData(string $sData): ?DBObject
	{
		try {
			$oThisObj = null;
			if ($sData != null) {
				$oWizardHelper = WizardHelper::FromJSON($sData);
				$oThisObj = $oWizardHelper->GetTargetObject();
			}

			return $oThisObj;
		}
		catch (Exception $e) {
			return null;
		}
	}

}