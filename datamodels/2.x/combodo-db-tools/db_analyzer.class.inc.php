<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

class DatabaseAnalyzer
{
	var $iTimeLimitPerOperation;

	public function __construct($iTimeLimitPerOperation = null)
	{
		$this->iTimeLimitPerOperation = $iTimeLimitPerOperation;
	}

	/**
	 * @param $sSelWrongRecs
	 * @param $sFixitRequest
	 * @param $sErrorDesc
	 * @param $sClass
	 * @param $aErrorsAndFixes
	 *
	 * @throws \MySQLException
	 */
	private function ExecQuery($sSelWrongRecs, $sFixitRequest, $sErrorDesc, $sClass, &$aErrorsAndFixes, $aValueNames = array())
	{
		if (!is_null($this->iTimeLimitPerOperation))
		{
			set_time_limit($this->iTimeLimitPerOperation);
		}

		$aWrongRecords = CMDBSource::QueryToArray($sSelWrongRecs);
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
					if (!empty($sFixitRequest))
					{
						$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = array($sFixitRequest);
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
	 * @param $iShowId
	 * @return array
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 * @throws MySQLException
	 * @throws Exception
	 */
	public function CheckIntegrity($aClassSelection, $iShowId)
	{
		// Getting and setting time limit are not symetric:
		// www.php.net/manual/fr/function.set-time-limit.php#72305
		$iPreviousTimeLimit = ini_get('max_execution_time');

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

		foreach($aClassSelection as $sClass)
		{
			// Check uniqueness rules
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

			if (!MetaModel::HasTable($sClass))
			{
				continue;
			}
			$sRootClass = MetaModel::GetRootClass($sClass);
			$sTable = MetaModel::DBGetTable($sClass);
			$sKeyField = MetaModel::DBGetKey($sClass);


			if (!MetaModel::IsStandaloneClass($sClass))
			{
				if (!MetaModel::IsRootClass($sClass))
				{
					$sRootTable = MetaModel::DBGetTable($sRootClass);
					$sRootKey = MetaModel::DBGetKey($sRootClass);
					$sFinalClassField = MetaModel::DBGetClassField($sRootClass);

					$aExpectedClasses = MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL);
					$sExpectedClasses = implode(",", CMDBSource::Quote($aExpectedClasses, true));

					// Check that any record found here has its counterpart in the root table
					//
					$sSelect = "SELECT DISTINCT `$sTable`.`$sKeyField` AS id";
					$sDelete = "DELETE `$sTable`";
					$sFilter = "FROM `$sTable` LEFT JOIN `$sRootTable` ON `$sTable`.`$sKeyField` = `$sRootTable`.`$sRootKey` WHERE `$sRootTable`.`$sRootKey` IS NULL";
					$sSelWrongRecs = "$sSelect $sFilter";
					$sFixitRequest = "$sDelete $sFilter";
					$this->ExecQuery($sSelWrongRecs, $sFixitRequest, Dict::Format('DBAnalyzer-Integrity-OrphanRecord', $sTable, $sRootTable), $sClass, $aErrorsAndFixes);

					// Check that any record found in the root table and referring to a child class
					// has its counterpart here (detect orphan nodes -root or in the middle of the hierarchy)
					//
					$sSelect = "SELECT DISTINCT `$sRootTable`.`$sRootKey` AS id";
					$sDelete = "DELETE `$sRootTable`";
					$sFilter = "FROM `$sRootTable` LEFT JOIN `$sTable` ON `$sRootTable`.`$sRootKey` = `$sTable`.`$sKeyField` WHERE `$sTable`.`$sKeyField` IS NULL AND `$sRootTable`.`$sFinalClassField` IN ($sExpectedClasses)";
					$sSelWrongRecs = "$sSelect $sFilter";
					$sFixitRequest = "$sDelete $sFilter";
					$this->ExecQuery($sSelWrongRecs, $sFixitRequest, Dict::Format('DBAnalyzer-Integrity-OrphanRecord', $sRootTable, $sTable), $sRootClass, $aErrorsAndFixes);
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
					// Check that any external field is pointing to an existing object
					//
					$sRemoteClass = $oAttDef->GetTargetClass();
					$sRemoteTable = MetaModel::DBGetTable($sRemoteClass);
					$sRemoteKey = MetaModel::DBGetKey($sRemoteClass);

					$aCols = $oAttDef->GetSQLExpressions(); // Workaround a PHP bug: sometimes issuing a Notice if invoking current(somefunc())
					$sExtKeyField = current($aCols); // get the first column for an external key

					// Note: a class/table may have an external key on itself
					$sSelect = "SELECT DISTINCT `$sTable`.`$sKeyField` AS id, `$sTable`.`$sExtKeyField` AS value";
					$sFilter = "FROM `$sTable` LEFT JOIN `$sRemoteTable` AS `{$sRemoteTable}_1` ON `$sTable`.`$sExtKeyField` = `{$sRemoteTable}_1`.`$sRemoteKey`";

					$sFilter = $sFilter." WHERE `{$sRemoteTable}_1`.`$sRemoteKey` IS NULL";
					// Exclude the records pointing to 0/null from the errors (separate test below)
					$sFilter .= " AND `$sTable`.`$sExtKeyField` IS NOT NULL";
					$sFilter .= " AND `$sTable`.`$sExtKeyField` != 0";

					$sSelWrongRecs = "$sSelect $sFilter";

					$sErrorDesc = Dict::Format('DBAnalyzer-Integrity-InvalidExtKey', $sAttCode, $sTable, $sExtKeyField);
					$this->ExecQuery($sSelWrongRecs, '', $sErrorDesc, $sClass, $aErrorsAndFixes);
					// Fix it request needs the values of the enum to generate the requests
					if (isset($aErrorsAndFixes[$sClass][$sErrorDesc]['values']))
					{
						$aFixit = array();
						$aFixit[] = "-- Remove inconsistant entries:";
						$sIds = implode(', ', array_keys($aErrorsAndFixes[$sClass][$sErrorDesc]['values']));
						$aFixit[] = "DELETE `$sTable` FROM `$sTable` WHERE `$sTable`.`$sExtKeyField` IN ($sIds)";
						$aFixit[] = "";
						$aFixit[] = "-- Or fix inconsistant values: Replace XXX with the appropriate value";
						foreach (array_keys($aErrorsAndFixes[$sClass][$sErrorDesc]['values']) as $sKey)
						{
							$aFixit[] = "UPDATE `$sTable` SET `$sTable`.`$sExtKeyField` = XXX WHERE `$sTable`.`$sExtKeyField` = '$sKey'";
						}
						$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = $aFixit;
					}

					if (!$oAttDef->IsNullAllowed())
					{
						$sSelect = "SELECT DISTINCT `$sTable`.`$sKeyField` AS id";
						$sDelete = "DELETE `$sTable`";
						$sFilter = "FROM `$sTable` WHERE `$sTable`.`$sExtKeyField` IS NULL OR `$sTable`.`$sExtKeyField` = 0";
						$sSelWrongRecs = "$sSelect $sFilter";
						$sFixitRequest = "$sDelete $sFilter";
						$sErrorDesc = Dict::Format('DBAnalyzer-Integrity-MissingExtKey', $sAttCode, $sTable, $sExtKeyField);
						$this->ExecQuery($sSelWrongRecs, $sFixitRequest, $sErrorDesc, $sClass, $aErrorsAndFixes);
						if (isset($aErrorsAndFixes[$sClass][$sErrorDesc]['count']) && ($aErrorsAndFixes[$sClass][$sErrorDesc]['count'] > 0))
						{
							$aFixit = $aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'];
							$aFixit[] = "-- Alternate fix";
							$aFixit[] = "-- Replace XXX with the appropriate value";
							$aFixit[] = "UPDATE `$sTable` SET `$sTable`.`$sExtKeyField` = XXX WHERE `$sTable`.`$sExtKeyField` IS NULL OR `$sTable`.`$sExtKeyField` = 0";
							$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = $aFixit;
						}
					}
				}
				elseif ($oAttDef->IsDirectField() && !($oAttDef instanceof AttributeTagSet))
				{
					// Check that the values fit the allowed values
					//
					$aAllowedValues = MetaModel::GetAllowedValues_att($sClass, $sAttCode);
					if (!is_null($aAllowedValues) && count($aAllowedValues) > 0)
					{
						$sExpectedValues = implode(",", CMDBSource::Quote(array_keys($aAllowedValues), true));

						$aCols = $oAttDef->GetSQLExpressions(); // Workaround a PHP bug: sometimes issuing a Notice if invoking current(somefunc())
						$sMyAttributeField = current($aCols); // get the first column for the moment
						$sSelWrongRecs = "SELECT DISTINCT `$sTable`.`$sKeyField` AS id, `$sTable`.`$sMyAttributeField` AS value FROM `$sTable` WHERE `$sTable`.`$sMyAttributeField` NOT IN ($sExpectedValues)";
						$sErrorDesc = Dict::Format('DBAnalyzer-Integrity-InvalidValue', $sAttCode, $sTable, $sMyAttributeField);
						$this->ExecQuery($sSelWrongRecs, '', $sErrorDesc, $sClass, $aErrorsAndFixes);
						// Fix it request needs the values of the enum to generate the requests
						if (isset($aErrorsAndFixes[$sClass][$sErrorDesc]['values']))
						{
							if (isset($aErrorsAndFixes[$sClass][$sErrorDesc]['fixit']))
							{
								$aFixit = $aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'];
							}
							else
							{
								$aFixit = array("-- Replace 'XXX' with the appropriate value");
							}
							foreach (array_keys($aErrorsAndFixes[$sClass][$sErrorDesc]['values']) as $sKey)
							{
								$aFixit[] = "UPDATE `$sTable` SET `$sTable`.`$sMyAttributeField` = 'XXX' WHERE `$sTable`.`$sMyAttributeField` = '$sKey'";
							}
							$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = $aFixit;
						}
					}
				}
			}
		}

		// Check user accounts without profile
		$sUserTable = MetaModel::DBGetTable('User');
		$sLinkTable = MetaModel::DBGetTable('URP_UserProfile');
		$sSelect = "SELECT DISTINCT u.id AS id, u.`login` AS value";
		$sFilter = "FROM `$sUserTable` AS u LEFT JOIN `$sLinkTable` AS l ON l.userid = u.id WHERE l.id IS NULL";
		$sSelWrongRecs = "$sSelect $sFilter";
		$sFixit = "-- Remove the corresponding user(s)";
		$this->ExecQuery($sSelWrongRecs, $sFixit, Dict::S('DBAnalyzer-Integrity-UsersWithoutProfile'), 'User', $aErrorsAndFixes);

		if (!is_null($this->iTimeLimitPerOperation))
		{
			set_time_limit($iPreviousTimeLimit);
		}
		return $aErrorsAndFixes;
	}

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

		$sErrorDesc = $this->GetUniquenessRuleMessage($sClass, $sUniquenessRuleId);

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
				$aFixit[] = $oFixSearch->MakeSelectQuery().';';
				$aFixit[] = "";
			}
			$aErrorsAndFixes[$sClass][$sErrorDesc]['fixit'] = $aFixit;
		}

		return;
	}

	private function GetUniquenessRuleMessage($sCurrentClass, $sUniquenessRuleId)
	{
		// we could add also a specific message if user is admin ("dict key is missing")
		return Dict::Format('Core:UniquenessDefaultError', $sUniquenessRuleId);
	}
}
