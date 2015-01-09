<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
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
	 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
	 * @param $sCurrentVersion string Current version number of the module
	 */
	public static function AfterDatabaseSetup(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
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
		if (!MetaModel::IsStandaloneClass($sTo))
		{
			$sRootClass = MetaModel::GetRootClass($sTo);
			$sTableName = MetaModel::DBGetTable($sRootClass);
			$sFinalClassCol = MetaModel::DBGetClassField($sRootClass);
			$sRepair = "UPDATE `$sTableName` SET `$sFinalClassCol` = '$sTo' WHERE `$sFinalClassCol` = BINARY '$sFrom'";
			CMDBSource::Query($sRepair);
			$iAffectedRows = CMDBSource::AffectedRows();
			SetupPage::log_info("Renaming class in DB - final class from '$sFrom' to '$sTo': $iAffectedRows rows affected"); 
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
						if (preg_match("/^enum\(\'(.*)\'\)$/", $sColType, $aMatches))
						{
							$aCurrentValues = explode("','", $aMatches[1]);
						}
					}
					if (!in_array($sFrom, $aNewValues))
					{
						if (!in_array($sTo, $aCurrentValues)) // if not already transformed!
						{
							$sNullSpec = $oAttDef->IsNullAllowed() ? 'NULL' : 'NOT NULL';
	
							if (strtolower($sTo) == strtolower($sFrom))
							{
								SetupPage::log_info("Changing enum in DB - $sClass::$sAttCode from '$sFrom' to '$sTo' (just a change in the case)"); 
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
								SetupPage::log_info("Changing enum in DB - $sClass::$sAttCode from '$sFrom' to '$sTo'"); 
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
								SetupPage::log_info("Changing enum in DB - $iAffectedRows rows updated"); 
				
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
								SetupPage::log_info("Changing enum in DB - removed useless value '$sFrom'");
							}
						}
					}
					else
					{
						SetupPage::log_warning("Changing enum in DB - $sClass::$sAttCode - '$sFrom' is still a valid value (".implode(', ', $aNewValues).")"); 
					}
				}
				else
				{
					SetupPage::log_warning("Changing enum in DB - $sClass::$sAttCode - '$sTo' is not a known value (".implode(', ', $aNewValues).")"); 
				}
			}
		}
	}

}
?>
