<?php
// Copyright (C) 2010-2024 Combodo SAS
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


require_once(APPROOT.'setup/setuppage.class.inc.php');

/**
 * Class ModuleInstaller
 * Defines the API to implement module specific actions during the setup 
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

abstract class ModuleInstallerAPI
{
	public static function BeforeWritingConfig(Config $oConfiguration)
	{
		return $oConfiguration;
	}

	/**
	 * Handler called before creating or upgrading the database schema
	 * @param $oConfiguration Config The new configuration of the application
	 * @param $sPreviousVersion string Previous version number of the module (empty string in case of first install)
	 * @param $sCurrentVersion string Current version number of the module
	 */
	public static function BeforeDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
	{
	}
	
	/**
	 * Handler called after the creation/update of the database schema
	 * @param $oConfiguration Config The new configuration of the application
	 * @param $sPreviousVersion string Previous version number of the module (empty string in case of first install)
	 * @param $sCurrentVersion string Current version number of the module
	 */
	public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
	{
	}

    /**
     * Handler called at the end of the setup of the database (profiles and admin accounts created), but before the data load
     * @param $oConfiguration Config The new configuration of the application
     * @param $sPreviousVersion string Previous version number of the module (empty string in case of first install)
     * @param $sCurrentVersion string Current version number of the module
     */
    public static function AfterDatabaseSetup(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
    {
    }

    /**
     * Handler called at the end of the data load
     * @param $oConfiguration Config The new configuration of the application
     * @param $sPreviousVersion string Previous version number of the module (empty string in case of first install)
     * @param $sCurrentVersion string Current version number of the module
     */
    public static function AfterDataLoad(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
    {
    }

	/**
	 * Helper to complete the renaming of a class
	 * The renaming is made in the datamodel definition, but the name has to be changed in the DB as well	 	 
	 * Must be called after DB update, i.e within an implementation of AfterDatabaseCreation()
	 * 	 
	 * @param string $sFrom Original name (already INVALID in the current datamodel)	 	
	 * @param string $sTo New name (valid in the current datamodel)
	 * @return void	 	 	
	 */
	public static function RenameClassInDB($sFrom, $sTo)
	{
		try
		{
			if (!MetaModel::IsStandaloneClass($sTo))
			{
				$sRootClass = MetaModel::GetRootClass($sTo);
				$sTableName = MetaModel::DBGetTable($sRootClass);
				$sFinalClassCol = MetaModel::DBGetClassField($sRootClass);
				$sRepair = "UPDATE `$sTableName` SET `$sFinalClassCol` = '$sTo' WHERE `$sFinalClassCol` = BINARY '$sFrom'";
				CMDBSource::Query($sRepair);
				$iAffectedRows = CMDBSource::AffectedRows();
				SetupLog::Info("Renaming class in DB - final class from '$sFrom' to '$sTo': $iAffectedRows rows affected");
			}
		}
		catch(Exception $e)
		{
			SetupLog::Warning("Failed to rename class in DB - final class from '$sFrom' to '$sTo'. Reason: ".$e->getMessage());
		} 
	}

	/**
	 * Helper to modify an enum value	
	 * The change is made in the datamodel definition, but the value has to be changed in the DB as well	 	 
	 * Must be called BEFORE DB update, i.e within an implementation of BeforeDatabaseCreation()
	 * This helper does change ONE value at a time	 
	 * 	 
	 * @param string $sClass A valid class name
	 * @param string $sAttCode The enum attribute code
	 * @param string $sFrom Original value (already INVALID in the current datamodel)	 	
	 * @param string $sTo New value (valid in the current datamodel)
	 * @return void	 	 	
	 */
	public static function RenameEnumValueInDB($sClass, $sAttCode, $sFrom, $sTo)
	{
		try
		{
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				SetupLog::Warning("Changing enum in DB - $sClass::$sAttCode - from '$sFrom' to '$sTo' failed. Reason '$sAttCode' is not a valid attribute of the class '$sClass'.");
				return;
			}
			$sOriginClass = MetaModel::GetAttributeOrigin($sClass, $sAttCode);
			$sTableName = MetaModel::DBGetTable($sOriginClass);
	
			$oAttDef = MetaModel::GetAttributeDef($sOriginClass, $sAttCode);
			if ($oAttDef instanceof AttributeEnum)
			{
				$oValDef = $oAttDef->GetValuesDef();
				if ($oValDef)
				{
					$aNewValues = array_keys($oValDef->GetValues(array(), ""));
					if (in_array($sTo, $aNewValues))
					{
						$sEnumCol = $oAttDef->Get("sql");
						$aFields = CMDBSource::QueryToArray("SHOW COLUMNS FROM `$sTableName` WHERE Field = '$sEnumCol'");
						if (isset($aFields[0]['Type']))
						{
							$sColType = $aFields[0]['Type'];
							// Note: the parsing should rely on str_getcsv (requires PHP 5.3) to cope with escaped string
							if (preg_match("/^enum\('(.*)'\)$/", $sColType, $aMatches))
							{
								$aCurrentValues = explode("','", $aMatches[1]);
							}
							else
							{
								// not an enum currently : return !
								// we could update values, but a clear error message will be displayed when altering the column
								return;
							}
						}
						if (!in_array($sFrom, $aNewValues))
						{
							if (!in_array($sTo, $aCurrentValues)) // if not already transformed!
							{
								$sNullSpec = $oAttDef->IsNullAllowed() ? 'NULL' : 'NOT NULL';
		
								if (strtolower($sTo) == strtolower($sFrom))
								{
									SetupLog::Info("Changing enum in DB - $sClass::$sAttCode from '$sFrom' to '$sTo' (just a change in the case)");
									$aTargetValues = array();
									foreach ($aCurrentValues as $sValue)
									{
										if ($sValue == $sFrom)
										{
											$sValue = $sTo;
										}
										$aTargetValues[] = $sValue;
									}
									$sColumnDefinition = "ENUM(".implode(",", CMDBSource::Quote($aTargetValues)).") $sNullSpec";
									$sRepair = "ALTER TABLE `$sTableName` MODIFY `$sEnumCol` $sColumnDefinition";
									CMDBSource::Query($sRepair);
								}
								else
								{
									// 1st - Allow both values in the column definition
									//
									SetupLog::Info("Changing enum in DB - $sClass::$sAttCode from '$sFrom' to '$sTo'");
									$aAllValues = $aCurrentValues;
									$aAllValues[] = $sTo;
									$sColumnDefinition = "ENUM(".implode(",", CMDBSource::Quote($aAllValues)).") $sNullSpec";
									$sRepair = "ALTER TABLE `$sTableName` MODIFY `$sEnumCol` $sColumnDefinition";
									CMDBSource::Query($sRepair);
					
									// 2nd - Change the old value into the new value
									//
									$sRepair = "UPDATE `$sTableName` SET `$sEnumCol` = '$sTo' WHERE `$sEnumCol` = BINARY '$sFrom'";
									CMDBSource::Query($sRepair);
									$iAffectedRows = CMDBSource::AffectedRows();
									SetupLog::Info("Changing enum in DB - $iAffectedRows rows updated");
					
									// 3rd - Remove the useless value from the column definition
									//
									$aTargetValues = array();
									foreach ($aCurrentValues as $sValue)
									{
										if ($sValue == $sFrom)
										{
											$sValue = $sTo;
										}
										$aTargetValues[] = $sValue;
									}
									$sColumnDefinition = "ENUM(".implode(",", CMDBSource::Quote($aTargetValues)).") $sNullSpec";
									$sRepair = "ALTER TABLE `$sTableName` MODIFY `$sEnumCol` $sColumnDefinition";
									CMDBSource::Query($sRepair);
									SetupLog::Info("Changing enum in DB - removed useless value '$sFrom'");
								}
							}
						}
						else
						{
							SetupLog::Warning("Changing enum in DB - $sClass::$sAttCode - '$sFrom' is still a valid value (".implode(', ', $aNewValues).")");
						}
					}
					else
					{
						SetupLog::Warning("Changing enum in DB - $sClass::$sAttCode - '$sTo' is not a known value (".implode(', ', $aNewValues).")");
					}
				}
			}
		}
		catch(Exception $e)
		{
			SetupLog::Warning("Changing enum in DB - $sClass::$sAttCode - '$sTo' failed. Reason ".$e->getMessage());
		}
	}

	/**
	 * Move a column from a table to another table providing:
	 *  - The id matches
	 *  - The original column exists
	 *  - The destination column does not exist
	 *
	 * The values are copied as is.
	 *
	 * @param $sOrigTable
	 * @param $sOrigColumn
	 * @param $sDstTable
	 * @param $sDstColumn
	 * @param bool $bIgnoreExistingDstColumn
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 *
	 * @since 3.2.0 N°7130 Add parameter $bIgnoreExistingDstColumn
	 * @since 3.2.0 No longer copy NULL data in order to avoid writing over existing data
	 */
	public static function MoveColumnInDB($sOrigTable, $sOrigColumn, $sDstTable, $sDstColumn, bool $bIgnoreExistingDstColumn = false)
	{
		if (!MetaModel::DBExists(false))
		{
			// Install from scratch, no migration
			return;
		}

		if (!CMDBSource::IsTable($sOrigTable) || !CMDBSource::IsField($sOrigTable, $sOrigColumn))
		{
			// Original field is not present
			return;
		}
		
		$bDstTableFieldExists = CMDBSource::IsField($sDstTable, $sDstColumn);
		if (!CMDBSource::IsTable($sDstTable) || ($bDstTableFieldExists && !$bIgnoreExistingDstColumn))
		{
			// Destination field is already created, and we are not ignoring it
			return;
		}

		// Create the destination field if necessary
		if($bDstTableFieldExists === false){
			$sSpec = CMDBSource::GetFieldSpec($sOrigTable, $sOrigColumn);
			$sQueryAdd = "ALTER TABLE `{$sDstTable}` ADD `{$sDstColumn}` {$sSpec}";
			CMDBSource::Query($sQueryAdd);	
		}

		// Copy the data
		$sQueryUpdate = "UPDATE `{$sDstTable}` AS d LEFT JOIN `{$sOrigTable}` AS o ON d.id = o.id SET d.`{$sDstColumn}` = o.`{$sOrigColumn}` WHERE o.`{$sOrigColumn}` IS NOT NULL";
		CMDBSource::Query($sQueryUpdate);

		// Drop original field
		$sQueryDrop = "ALTER TABLE `{$sOrigTable}` DROP `{$sOrigColumn}`";
		CMDBSource::Query($sQueryDrop);

		CMDBSource::CacheReset($sOrigTable);
		CMDBSource::CacheReset($sDstTable);
	}

	/**
	 * Rename a table providing:
	 * - The original name exists
	 * - The destination name does not exist
	 *
	 * @param string $sOrigTable
	 * @param string $sDstTable
	 *
	 * @return void
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws MySQLException
	 */
	public static function RenameTableInDB(string $sOrigTable, string $sDstTable)
	{
		if ($sOrigTable == $sDstTable)
		{
			throw new CoreUnexpectedValue("Origin table and destination table are the same");
		}

		if (!MetaModel::DBExists(false))
		{
			// Install from scratch, no migration
			return;
		}

		if (!CMDBSource::IsTable($sOrigTable))
		{
			SetupLog::Warning(sprintf('Rename table in DB - Origin table %s doesn\'t exist', $sOrigTable));
			return;
		}

		if (CMDBSource::IsTable($sDstTable))
		{
			SetupLog::Warning(sprintf('Rename table in DB - Destination table %s already exists', $sDstTable));
			return;
		}

		$sQueryRename = sprintf(/** @lang MariaDB */ "RENAME TABLE `%s` TO `%s`;", $sOrigTable, $sDstTable);
		CMDBSource::Query($sQueryRename);

		CMDBSource::CacheReset($sOrigTable);
	}
}
