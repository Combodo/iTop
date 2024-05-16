<?php
// Copyright (c) 2010-2024 Combodo SAS
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
//

use Combodo\iTop\Core\MetaModel\HierarchicalKey;

class DatabaseAnalyzer
{
	const LIMIT = 100;

	var $iTimeLimitPerOperation;

	public function __construct($iTimeLimitPerOperation = null)
	{
		$this->iTimeLimitPerOperation = $iTimeLimitPerOperation;
	}

	/**
	 * @param $sSelWrongRecs
	 * @param $sFixItRequest
	 * @param $sErrorDesc
	 * @param $sClass
	 * @param $aErrorsAndFixes
	 * @param array $aValueNames
	 *
	 * @throws \MySQLException
	 */
	private function ExecQuery($sSelWrongRecs, $sFixItRequest, $sErrorDesc, $sClass, &$aErrorsAndFixes, $aValueNames = array())
	{
		if (!is_null($this->iTimeLimitPerOperation))
		{
			set_time_limit(intval($this->iTimeLimitPerOperation));
		}

		$aWrongRecords = CMDBSource::QueryToArray($sSelWrongRecs.' limit '.self::LIMIT);
		if (count($aWrongRecords) > 0)
		{
			foreach($aWrongRecords as $aRes)
			{
				if (!isset($aErrorsAndFixes[$sClass][$sErrorDesc]))
				{
					$aErrorsAndFixes[$sClass][$sErrorDesc] = array(
						'count' => 1,
						'query' => $sSelWrongRecs,
					);
					if (!empty($sFixItRequest))
					{
						$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = array($sFixItRequest);
						$aErrorsAndFixes[$sClass][$sErrorDesc]['cleanup'] = array($sFixItRequest);
					}
				}
				else
				{
					$aErrorsAndFixes[$sClass][$sErrorDesc]['count'] += 1;
				}
				if (empty($aValueNames))
				{
					$aValues = array('id' => $aRes['id']);
				}
				else
				{
					$aValues = array();
					foreach ($aValueNames as $sValueName)
					{
						$aValues[$sValueName] = $aRes[$sValueName];
					}
				}

				if (isset($aRes['value']))
				{
					$value = $aRes['value'];
					$aValues['value'] = $value;
					if (!isset($aErrorsAndFixes[$sClass][$sErrorDesc]['values'][$value]))
					{
						$aErrorsAndFixes[$sClass][$sErrorDesc]['values'][$value] = 1;
					}
					else
					{
						$aErrorsAndFixes[$sClass][$sErrorDesc]['values'][$value] += 1;
					}
				}
				$aErrorsAndFixes[$sClass][$sErrorDesc]['res'][] = $aValues;
			}
		}
	}

	/**
	 * @param $aClassSelection
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public function CheckIntegrity($aClassSelection)
	{
		$aErrorsAndFixes = array();

		if (empty($aClassSelection))
		{
			$aClassSelection = MetaModel::GetClasses();
		}
		else
		{
			$aClasses = $aClassSelection;
			foreach($aClasses as $sClass)
			{
				$aExpectedClasses = MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL);
				$aClassSelection = array_merge($aClassSelection, $aExpectedClasses);
			}
			$aClassSelection = array_unique($aClassSelection);
		}

		foreach ($aClassSelection as $sClass)
		{
			// Check uniqueness rules
			$this->CheckUniquenessRules($sClass, $aErrorsAndFixes);

			if (!MetaModel::HasTable($sClass))
			{
				continue;
			}

			$sRootClass = MetaModel::GetRootClass($sClass);
			$sTable = MetaModel::DBGetTable($sClass);
			$sKeyField = MetaModel::DBGetKey($sClass);

			if (!MetaModel::IsStandaloneClass($sClass))
			{
				$sRootTable = MetaModel::DBGetTable($sRootClass);
				$sRootKey = MetaModel::DBGetKey($sRootClass);
				if (!MetaModel::IsRootClass($sClass))
				{
					$this->CheckRecordsInRootTable($sTable, $sKeyField, $sRootTable, $sRootKey, $sClass, $aErrorsAndFixes);
					$this->CheckRecordsInChildTable($sRootClass, $sClass, $sRootTable, $sRootKey, $sTable, $sKeyField, $aErrorsAndFixes);
					if (!MetaModel::IsLeafClass($sClass))
					{
						$this->CheckIntermediateFinalClass($sRootClass, $sClass, $sRootTable, $sRootKey, $sTable, $sKeyField, $aErrorsAndFixes);
					}
				}
			}

			foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				// Skip this attribute if not defined in this table
				if (!MetaModel::IsAttributeOrigin($sClass, $sAttCode))
				{
					continue;
				}

				if ($oAttDef->IsExternalKey())
				{
					$this->CheckExternalKeys($oAttDef, $sTable, $sKeyField, $sAttCode, $sClass, $aErrorsAndFixes);
					if ((MetaModel::GetAttributeOrigin($sClass, $sAttCode) == $sClass) && $oAttDef->IsHierarchicalKey()) {
						$this->CheckHK($sClass, $sAttCode, $aErrorsAndFixes);
					}
				}
				elseif ($oAttDef->IsDirectField() && !($oAttDef instanceof AttributeTagSet))
				{
					$this->CheckAllowedValues($sClass, $sAttCode, $oAttDef, $sTable, $sKeyField, $aErrorsAndFixes);
				}
			}
		}
		$this->CheckUsers($aErrorsAndFixes);

		return $aErrorsAndFixes;
	}

	/**
	 * @param $sClass
	 * @param $sUniquenessRuleId
	 * @param $aUniquenessRuleProperties
	 * @param $aErrorsAndFixes
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	private function CheckUniquenessRule($sClass, $sUniquenessRuleId, $aUniquenessRuleProperties, &$aErrorsAndFixes)
	{
		$sOqlUniquenessQuery = "SELECT $sClass";
		if (!(empty($sUniquenessFilter = $aUniquenessRuleProperties['filter'])))
		{
			$sOqlUniquenessQuery .= ' WHERE '.$sUniquenessFilter;
		}
		$oUniquenessQuery = DBObjectSearch::FromOQL($sOqlUniquenessQuery);

		$aValueNames = array();
		$aGroupByExpr = array();
		foreach ($aUniquenessRuleProperties['attributes'] as $sAttributeCode)
		{
			$oExpr = Expression::FromOQL("$sClass.$sAttributeCode");
			$aGroupByExpr[$sAttributeCode] = $oExpr;
			$aValueNames[] = $sAttributeCode;
		}

		$aSelectExpr = array();

		$sSQLUniquenessQuery = $oUniquenessQuery->MakeGroupByQuery(array(), $aGroupByExpr, false, $aSelectExpr);

		$sSQLUniquenessQuery .= ' having count(*) > 1';

		$sErrorDesc = $this->GetUniquenessRuleMessage($sUniquenessRuleId);

		$this->ExecQuery($sSQLUniquenessQuery, '', $sErrorDesc, $sClass, $aErrorsAndFixes, $aValueNames);
		if (isset($aErrorsAndFixes[$sClass][$sErrorDesc]['res']))
		{
			$aFixit = array("-- In order to get the duplicates, run the following queries:");
			foreach ($aErrorsAndFixes[$sClass][$sErrorDesc]['res'] as $aValues)
			{
				$oFixSearch = new DBObjectSearch($sClass);
				foreach ($aValues as $sAttCode => $sValue)
				{
					$oFixSearch->AddCondition($sAttCode, $sValue, '=');
				}
				$aFixit[] = $oFixSearch->MakeSelectQuery([], [], null, null, 0, 0, false, false).';';
				$aFixit[] = "";
			}
			$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = $aFixit;
		}
	}

	private function GetUniquenessRuleMessage($sUniquenessRuleId)
	{
		// we could add also a specific message if user is admin ("dict key is missing")
		return Dict::Format('Core:UniquenessDefaultError', $sUniquenessRuleId);
	}

	/**
	 * @param $sClass
	 * @param array $aErrorsAndFixes
	 *
	 * @throws \CoreException
	 */
	private function CheckUniquenessRules($sClass, &$aErrorsAndFixes)
	{
		if (method_exists('MetaModel', 'GetUniquenessRules'))
		{
			$aUniquenessRules = MetaModel::GetUniquenessRules($sClass);
			foreach ($aUniquenessRules as $sUniquenessRuleId => $aUniquenessRuleProperties)
			{
				if ($aUniquenessRuleProperties['disabled'] === true)
				{
					continue;
				}
				$this->CheckUniquenessRule($sClass, $sUniquenessRuleId, $aUniquenessRuleProperties, $aErrorsAndFixes);
			}
		}
	}

	/**
	 * Check that any record found here has its counterpart in the root table
	 *
	 * @param $sTable
	 * @param $sKeyField
	 * @param $sRootTable
	 * @param $sRootKey
	 * @param $sClass
	 * @param array $aErrorsAndFixes
	 *
	 * @throws \MySQLException
	 */
	private function CheckRecordsInRootTable($sTable, $sKeyField, $sRootTable, $sRootKey, $sClass, &$aErrorsAndFixes)
	{
		$sSelect = "SELECT DISTINCT `$sTable`.`$sKeyField` AS id";
		$sDelete = "DELETE `$sTable`";
		$sFilter = "FROM `$sTable` LEFT JOIN `$sRootTable` ON `$sTable`.`$sKeyField` = `$sRootTable`.`$sRootKey` WHERE `$sRootTable`.`$sRootKey` IS NULL";
		$sSelectWrongRecs = "$sSelect $sFilter";
		$sFixItRequest = "$sDelete $sFilter";
		$this->ExecQuery($sSelectWrongRecs, $sFixItRequest, Dict::Format('DBAnalyzer-Integrity-OrphanRecord', $sTable, $sRootTable), $sClass, $aErrorsAndFixes);
	}

	/**
	 * Check that any record found in the root table and referring to a child class
	 * has its counterpart here (detect orphan nodes -root or in the middle of the hierarchy)
	 *
	 * @param $sRootClass
	 * @param $sClass
	 * @param $sRootTable
	 * @param $sRootKey
	 * @param $sTable
	 * @param $sKeyField
	 * @param array $aErrorsAndFixes
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	private function CheckRecordsInChildTable($sRootClass, $sClass, $sRootTable, $sRootKey, $sTable, $sKeyField, &$aErrorsAndFixes)
	{
		$sFinalClassField = MetaModel::DBGetClassField($sRootClass);
		$aExpectedClasses = MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL);
		$sExpectedClasses = implode(",", CMDBSource::Quote($aExpectedClasses, true));
		$sSelect = "SELECT DISTINCT `$sRootTable`.`$sRootKey` AS id";
		$sDelete = "DELETE `$sRootTable`";
		$sFilter = "FROM `$sRootTable` LEFT JOIN `$sTable` ON `$sRootTable`.`$sRootKey` = `$sTable`.`$sKeyField` WHERE `$sTable`.`$sKeyField` IS NULL AND `$sRootTable`.`$sFinalClassField` IN ($sExpectedClasses)";
		$sSelWrongRecs = "$sSelect $sFilter";
		$sFixItRequest = "$sDelete $sFilter";
		$this->ExecQuery($sSelWrongRecs, $sFixItRequest, Dict::Format('DBAnalyzer-Integrity-OrphanRecord', $sRootTable, $sTable), $sRootClass, $aErrorsAndFixes);
	}

	/**
	 * Check that the "finalclass" field is correct for all the classes of the hierarchy
	 *
	 * @param $sRootClass
	 * @param $sClass
	 * @param $sRootTable
	 * @param $sRootKey
	 * @param $sTable
	 * @param $sKeyField
	 * @param $aErrorsAndFixes
	 *
	 * @throws \CoreException
	 */
	private function CheckIntermediateFinalClass($sRootClass, $sClass, $sRootTable, $sRootKey, $sTable, $sKeyField, &$aErrorsAndFixes)
	{
		$sField = MetaModel::DBGetClassField($sClass);
		$sRootField = MetaModel::DBGetClassField($sRootClass);
		$sSelWrongRecs = "SELECT `$sTable`.`$sKeyField` AS id FROM `$sTable` JOIN `$sRootTable` ON `$sRootTable`.`$sRootKey` = `$sTable`.`$sKeyField` WHERE `$sTable`.`$sField` != `$sRootTable`.`$sRootField`";
		// Copy the final class of the root table
		$sFixItRequest = "UPDATE `$sTable`,`$sRootTable` SET  `$sTable`.`$sField` = `$sRootTable`.`$sRootField` WHERE `$sTable`.`$sKeyField` = `$sRootTable`.`$sRootKey`";
		$this->ExecQuery($sSelWrongRecs, $sFixItRequest, Dict::Format('DBAnalyzer-Integrity-FinalClass', $sField, $sTable, $sRootTable), $sClass, $aErrorsAndFixes);
	}
	/**
	 * Check that any external field is pointing to an existing object
	 *
	 * @param \AttributeDefinition $oAttDef
	 * @param $sTable
	 * @param $sKeyField
	 * @param $sAttCode
	 * @param $sClass
	 * @param array $aErrorsAndFixes
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	private function CheckExternalKeys(AttributeDefinition $oAttDef, $sTable, $sKeyField, $sAttCode, $sClass, &$aErrorsAndFixes)
	{
		$sRemoteClass = $oAttDef->GetTargetClass();
		$sRemoteTable = MetaModel::DBGetTable($sRemoteClass);
		$sRemoteKey = MetaModel::DBGetKey($sRemoteClass);

		$aCols = $oAttDef->GetSQLExpressions(); // Workaround a PHP bug: sometimes issuing a Notice if invoking current(somefunc())
		$sExtKeyField = current($aCols); // get the first column for an external key

		// Note: a class/table may have an external key on itself
		$sSelect = "SELECT DISTINCT `$sTable`.`$sKeyField` AS id, `$sTable`.`$sExtKeyField` AS value";
		$sFrom = "FROM `$sTable`";
		$sJoin = "LEFT JOIN `$sRemoteTable` AS `{$sRemoteTable}_1` ON `$sTable`.`$sExtKeyField` = `{$sRemoteTable}_1`.`$sRemoteKey`";

		$sFilter = "WHERE `{$sRemoteTable}_1`.`$sRemoteKey` IS NULL";
		// Exclude the records pointing to 0/null from the errors (separate test below)
		$sFilter .= " AND `$sTable`.`$sExtKeyField` IS NOT NULL";
		$sFilter .= " AND `$sTable`.`$sExtKeyField` != 0";

		$sSelWrongRecs = "$sSelect $sFrom $sJoin $sFilter";

		$sErrorDesc = Dict::Format('DBAnalyzer-Integrity-InvalidExtKey', $sAttCode, $sTable, $sExtKeyField);
		$this->ExecQuery($sSelWrongRecs, '', $sErrorDesc, $sClass, $aErrorsAndFixes);
		$aFixIt = [];
		// Fix it request needs the values of the enum to generate the requests
		if (isset($aErrorsAndFixes[$sClass][$sErrorDesc]['values']))
		{
			if ($oAttDef->IsNullAllowed()) {
				$aFixIt[] = "-- Fix inconsistant values: remove the external key";
				foreach (array_keys($aErrorsAndFixes[$sClass][$sErrorDesc]['values']) as $sKey) {
					$aFixIt[] = "UPDATE `$sTable` SET `$sTable`.`$sExtKeyField` = 0 WHERE `$sTable`.`$sExtKeyField` = '$sKey'";
				}
			} else {
				$aAdditionalFixIt = $this->GetSpecificExternalKeysFixItForNull($sTable, $sExtKeyField, $sFilter, $sJoin);
				foreach ($aAdditionalFixIt as $sFixIt)
				{
					$aFixIt[] = $sFixIt;
				}

				$aFixIt[] = "-- Alternate fix: remove inconsistant entries:";
				$sIds = implode(', ', array_keys($aErrorsAndFixes[$sClass][$sErrorDesc]['values']));
				$aFixIt[] = "DELETE `$sTable` FROM `$sTable` WHERE `$sTable`.`$sExtKeyField` IN ($sIds)";

				$aFixIt[] = "-- Alternate fix: update inconsistant values: Replace XXX with the appropriate value";
				foreach (array_keys($aErrorsAndFixes[$sClass][$sErrorDesc]['values']) as $sKey) {
					$aFixIt[] = "UPDATE `$sTable` SET `$sTable`.`$sExtKeyField` = XXX WHERE `$sTable`.`$sExtKeyField` = '$sKey'";
				}
			}
			$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = $aFixIt;
		}

		if (!$oAttDef->IsNullAllowed()) {
			$sSelect = "SELECT DISTINCT `$sTable`.`$sKeyField` AS id";
			$sDelete = "DELETE `$sTable`";
			$sFrom = "FROM `$sTable`";
			$sFilter = "WHERE `$sTable`.`$sExtKeyField` IS NULL OR `$sTable`.`$sExtKeyField` = 0";
			$sSelWrongRecs = "$sSelect $sFrom $sFilter";
			$sFixItRequest = "$sDelete $sFrom $sFilter";
			$sErrorDesc = Dict::Format('DBAnalyzer-Integrity-MissingExtKey', $sAttCode, $sTable, $sExtKeyField);
			$this->ExecQuery($sSelWrongRecs, '', $sErrorDesc, $sClass, $aErrorsAndFixes);
			$aFixIt = [];
			if (isset($aErrorsAndFixes[$sClass][$sErrorDesc]['count']) && ($aErrorsAndFixes[$sClass][$sErrorDesc]['count'] > 0))
			{
				$aAdditionalFixIt = $this->GetSpecificExternalKeysFixItForNull($sTable, $sExtKeyField, $sFilter);
				foreach ($aAdditionalFixIt as $sFixIt)
				{
					$aFixIt[] = $sFixIt;
				}
				$aFixIt[] = "-- Alternate fix: remove inconsistant entries:";
				$aFixIt[] = $sFixItRequest;
				$aFixIt[] = "-- Alternate fix: replace XXX with the appropriate value";
				$aFixIt[] = "UPDATE `$sTable` SET `$sTable`.`$sExtKeyField` = XXX WHERE `$sTable`.`$sExtKeyField` IS NULL OR `$sTable`.`$sExtKeyField` = 0";
				$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = $aFixIt;
			}
		}
	}

	private function GetSpecificExternalKeysFixItForNull($sTable, $sExtKeyField, $sFilter, $sJoin = '')
	{
		$aFixIt = array();
		if ($sTable == 'ticket' && $sExtKeyField == 'org_id')
		{
			$aFixIt[] = "-- Alternate fix: set the ticket org to the caller org";
			$aFixIt[] = "UPDATE ticket JOIN contact AS c ON ticket.caller_id=c.id $sJoin SET ticket.org_id=c.org_id $sFilter";
		}
		return $aFixIt;
	}

	/**
	 * Check that the values fit the allowed values
	 *
	 * @param $sClass
	 * @param $sAttCode
	 * @param \AttributeDefinition $oAttDef
	 * @param $sTable
	 * @param $sKeyField
	 * @param array $aErrorsAndFixes
	 *
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	private function CheckAllowedValues($sClass, $sAttCode, AttributeDefinition $oAttDef, $sTable, $sKeyField, &$aErrorsAndFixes)
	{
		$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode);
		if (!is_null($aAllowedValues) && count($aAllowedValues) > 0)
		{
			$aAllowedValues = array_keys($aAllowedValues);
			$sExpectedValues = implode(",", CMDBSource::Quote($aAllowedValues, true));

			$aCols = $oAttDef->GetSQLExpressions(); // Workaround a PHP bug: sometimes issuing a Notice if invoking current(somefunc())
			if (empty($aCols)) {
				return;
			}
			$sMyAttributeField = current($aCols); // get the first column for the moment
			$sFilter = "FROM `$sTable` WHERE `$sTable`.`$sMyAttributeField` NOT IN ($sExpectedValues)";
			if ($oAttDef->IsNullAllowed()) {
				// NotEmptyToSql should have been in AttributeDefinition, as a workaround the search type is used
				$sSearchType = $oAttDef->GetSearchType();
				$sCondition = $this->NotEmptyToSql("`$sTable`.`$sMyAttributeField`", $sSearchType);
				$sFilter .= " AND $sCondition";
			}
			$sDelete = "DELETE `$sTable`";
			$sSelect = "SELECT DISTINCT `$sTable`.`$sKeyField` AS id, `$sTable`.`$sMyAttributeField` AS value";
			$sSelWrongRecs = "$sSelect $sFilter";
			$sFixItRequest = "$sDelete $sFilter";
			$sErrorDesc = Dict::Format('DBAnalyzer-Integrity-InvalidValue', $sAttCode, $sTable, $sMyAttributeField);
			$this->ExecQuery($sSelWrongRecs, $sFixItRequest, $sErrorDesc, $sClass, $aErrorsAndFixes);
			// Fix it request needs the values of the enum to generate the requests
			if (isset($aErrorsAndFixes[$sClass][$sErrorDesc]['values']))
			{
				if (isset($aErrorsAndFixes[$sClass][$sErrorDesc]['fixit']))
				{
					$aFixIt = $aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'];
					$aFixIt[] = "-- Alternative: Replace enums with the appropriate value";
				}
				else
				{
					$aFixIt = ["-- Replace enums with the appropriate value"];
				}
				foreach (array_keys($aErrorsAndFixes[$sClass][$sErrorDesc]['values']) as $sKey)
				{
					foreach ($aAllowedValues as $sAllowedValue) {
						$aFixIt[] = "-- Replace $sKey by $sAllowedValue";
						$aFixIt[] = "UPDATE `$sTable` SET `$sTable`.`$sMyAttributeField` = '$sAllowedValue' WHERE `$sTable`.`$sMyAttributeField` = '$sKey'";
					}
				}
				$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = $aFixIt;
			}
		}
	}

	/**
	 * @param $sRef
	 * @param string $sSearchType
	 *
	 * @return string
	 * @since 3.1.0 N°6442
	 */
	private function NotEmptyToSql($sRef, string $sSearchType)
	{
		switch ($sSearchType) {
			case AttributeDefinition::SEARCH_WIDGET_TYPE_NUMERIC:
			case AttributeDefinition::SEARCH_WIDGET_TYPE_EXTERNAL_FIELD:
			case AttributeDefinition::SEARCH_WIDGET_TYPE_DATE:
			case AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME:
				return "ISNULL({$sRef}) = 0";
		}

		return "({$sRef} != '')";
	}

	/**
	 * Check user accounts without profile
	 *
	 * @param $aErrorsAndFixes
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	private function CheckUsers(&$aErrorsAndFixes)
	{
		$sClass = 'User';
		$sUserTable = MetaModel::DBGetTable($sClass);
		$sLinkTable = MetaModel::DBGetTable('URP_UserProfile');
		$sSelect = "SELECT DISTINCT u.id AS id, u.`login` AS value";
		$sFilter = "FROM `$sUserTable` AS u LEFT JOIN `$sLinkTable` AS l ON l.userid = u.id WHERE l.id IS NULL";
		$sSelWrongRecs = "$sSelect $sFilter";
		$sFixit = "-- Remove the corresponding user(s)";
		$sErrorDesc = Dict::S('DBAnalyzer-Integrity-UsersWithoutProfile');
		$this->ExecQuery($sSelWrongRecs, $sFixit, $sErrorDesc, $sClass, $aErrorsAndFixes);
	}

	/**
	 * Check hierarchical keys
	 *
	 * @param $sClass
	 * @param $sAttCode
	 * @param $aErrorsAndFixes
	 *
	 * @throws \Exception
	 */
	private function CheckHK($sClass, $sAttCode, &$aErrorsAndFixes)
	{
		try {
			HierarchicalKey::VerifyIntegrity($sClass, $sAttCode, MetaModel::GetAttributeDef($sClass, $sAttCode));
		} catch (CoreException $e) {
			$sErrorDesc = Dict::Format('DBAnalyzer-Integrity-HKInvalid', $sAttCode);
			$aErrorsAndFixes[$sClass][$sErrorDesc]['count'] = 1;
			$aErrorsAndFixes[$sClass][$sErrorDesc]['query'] = '-- N/A';
			$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = ['-- Run script env-'.utils::GetCurrentEnvironment().DIRECTORY_SEPARATOR.'combodo-db-tools'.DIRECTORY_SEPARATOR.'bin'.DIRECTORY_SEPARATOR.'rebuildhk.php' ];
		}
	}
}
