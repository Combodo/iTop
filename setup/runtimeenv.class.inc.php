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

/**
 * Manage a runtime environment
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\PhpParser\Evaluation\PhpExpressionEvaluator;
use Combodo\iTop\Setup\FeatureRemoval\SetupAudit;
use Combodo\iTop\Setup\ModuleDependency\Module;
use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReader;
use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReaderException;

require_once APPROOT."setup/modulediscovery.class.inc.php";
require_once APPROOT.'setup/modelfactory.class.inc.php';
require_once APPROOT.'setup/compiler.class.inc.php';
require_once APPROOT.'setup/extensionsmap.class.inc.php';
require_once APPROOT.'setup/moduleinstallation/AnalyzeInstallation.php';
require_once APPROOT.'/setup/moduleinstallation/InstallationChoicesToModuleConverter.php';
require_once APPROOT.'/setup/moduleinstallation/moduleinstallation.class.inc.php';

define('MODULE_ACTION_OPTIONAL', 1);
define('MODULE_ACTION_MANDATORY', 2);
define('MODULE_ACTION_IMPOSSIBLE', 3);
define('ROOT_MODULE', '_Root_'); // Convention to store IN MEMORY the name/version of the root module i.e. application
define('DATAMODEL_MODULE', 'datamodel'); // Convention to store the version of the datamodel

class RunTimeEnvironment
{
	private static bool $bMetamodelStarted = false;

	/**
	 * The name of the environment that the caller wants to build
	 * @var string sFinalEnv
	 */
	protected $sFinalEnv;

	/**
	 * Environment into which the build will be performed
	 * @var string sBuildEnv
	 */
	protected $sBuildEnv;

	/**
	 * Extensions map of the source environment
	 * @var iTopExtensionsMap
	 */
	protected ?iTopExtensionsMap $oExtensionsMap;

	protected function GetExtensionMap(): ?iTopExtensionsMap
	{
		return $this->oExtensionsMap;
	}

	public function InitExtensionMap($aExtraDirs, $oSourceConfig)
	{
		// Actually read the modules available for the build environment,
		// but get the selection from the source environment and finally
		// mark as (automatically) chosen all the "remote" modules present in the
		// build environment (data/<build-env>-modules)
		// The actual choices will be recorded by RecordInstallation below
		$this->oExtensionsMap = new iTopExtensionsMap($this->sBuildEnv, $aExtraDirs);
		$this->oExtensionsMap->LoadChoicesFromDatabase($oSourceConfig);
	}

	/**
	 * Toolset for building a run-time environment
	 *
	 * @param string $sEnvironment (e.g. 'test')
	 * @param bool $bAutoCommit (make the final environment directly, or build a temporary one)
	 */
	public function __construct($sEnvironment = ITOP_DEFAULT_ENV, $bAutoCommit = true)
	{
		$this->sFinalEnv = $sEnvironment;
		if ($bAutoCommit) {
			// Build directly onto the requested environment
			$this->sBuildEnv = $sEnvironment;
		} else {
			// Build into a temporary dir
			$this->sBuildEnv = $sEnvironment.'-build';
		}
		$this->oExtensionsMap = null;
		SetupLog::Enable(APPROOT.'log/setup.log');
	}

	/**
	 * Return the full path to the compiled code (do not use after commit)
	 * @return string
	 */
	public function GetBuildDir(): string
	{
		return APPROOT.'env-'.$this->sBuildEnv;
	}

	/**
	 * Return the full path to the compiled code (do not use after commit)
	 * @return string
	 */
	public function GetBuildEnv(): string
	{
		return $this->sBuildEnv;
	}

	/**
	 * Callback function for logging the queries run by the setup.
	 * According to the documentation the function must be defined before passing it to call_user_func...
	 * @param string $sQuery
	 * @param float $fDuration
	 * @return void
	 */
	public function LogQueryCallback($sQuery, $fDuration)
	{
		$this->log_info(sprintf('%.3fs - query: %s ', $fDuration, $sQuery));
		$this->log_db_query($sQuery);
	}

	/**
	 * Helper function to initialize the ORM and load the data model
	 * from the given file
	 *
	 * @param $oConfig object The configuration (volatile, not necessarily already on disk)
	 * @param $bModelOnly boolean Whether or not to allow loading a data model with no corresponding DB
	 * @param bool $bUseCache
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MySQLException
	 */
	public function InitDataModel($oConfig, $bModelOnly = true, $bUseCache = false): void
	{
		$sConfigFile = $oConfig->GetLoadedFile();
		if (strlen($sConfigFile) > 0) {
			$this->log_info("MetaModel::Startup from $sConfigFile (ModelOnly = $bModelOnly)");
		} else {
			$this->log_info("MetaModel::Startup (ModelOnly = $bModelOnly)");
		}

		if (!$bUseCache) {
			// Reset the cache for the first use !
			MetaModel::ResetAllCaches($this->sBuildEnv);
		}

		if (! isset($_SESSION)) {
			//used in all UI setups (not unattended)
			session_start();
		}
		$_SESSION['itop_env'] = $this->sBuildEnv;

		MetaModel::Startup($oConfig, $bModelOnly, $bUseCache, false, $this->sBuildEnv);

		if ($this->oExtensionsMap === null) {
			$this->oExtensionsMap = new iTopExtensionsMap($this->sBuildEnv);
		}
	}

	/**
	 * @param string $sMode
	 * @param \Config $oConfig
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function MigrateDataBeforeUpdateStructure(string $sMode, Config $oConfig): void
	{
		$this->InitDataModel($oConfig);
		$sDBName = $oConfig->Get('db_name');
		$sDBPrefix = $oConfig->Get('db_subname');

		// In 2.6.0 the 'fields' attribute has been moved from Query to QueryOQL for dependencies reasons
		ModuleInstallerAPI::MoveColumnInDB($sDBPrefix.'priv_query', 'fields', $sDBPrefix.'priv_query_oql', 'fields');

		// Migrate application data format
		//
		// priv_internalUser caused troubles because MySQL transforms table names to lower case under Windows
		// This becomes an issue when moving your installation data to/from Windows
		// Starting 2.0, all table names must be lowercase
		if ($sMode != 'install') {
			SetupLog::Info("Renaming '{$sDBPrefix}priv_internalUser' into '{$sDBPrefix}priv_internaluser' (lowercase)");
			// This command will have no effect under Windows...
			// and it has been written in two steps so as to make it work under windows!
			CMDBSource::SelectDB($sDBName);
			try {
				$sRepair = "RENAME TABLE `{$sDBPrefix}priv_internalUser` TO `{$sDBPrefix}priv_internaluser_other`, `{$sDBPrefix}priv_internaluser_other` TO `{$sDBPrefix}priv_internaluser`";
				CMDBSource::Query($sRepair);
			} catch (Exception $e) {
				SetupLog::Info("Renaming '{$sDBPrefix}priv_internalUser' failed (already done in a previous upgrade?)");
			}

			// let's remove the records in priv_change which have no counterpart in priv_changeop
			SetupLog::Info("Cleanup of '{$sDBPrefix}priv_change' to remove orphan records");
			CMDBSource::SelectDB($sDBName);
			try {
				$sTotalCount = "SELECT COUNT(*) FROM `{$sDBPrefix}priv_change`";
				$iTotalCount = (int)CMDBSource::QueryToScalar($sTotalCount);
				SetupLog::Info("There is a total of $iTotalCount records in {$sDBPrefix}priv_change.");

				$sOrphanCount = "SELECT COUNT(c.id) FROM `{$sDBPrefix}priv_change` AS c left join `{$sDBPrefix}priv_changeop` AS o ON c.id = o.changeid WHERE o.id IS NULL";
				$iOrphanCount = (int)CMDBSource::QueryToScalar($sOrphanCount);
				SetupLog::Info("There are $iOrphanCount useless records in {$sDBPrefix}priv_change (".sprintf('%.2f', ((100.0 * $iOrphanCount) / $iTotalCount)).'%)');
				if ($iOrphanCount > 0) {
					//N°3793
					if ($iOrphanCount > 100000) {
						SetupLog::Info("There are too much useless records ($iOrphanCount) in {$sDBPrefix}priv_change. Cleanup cannot be done during setup.");
					} else {
						SetupLog::Info('Removing the orphan records...');
						$sCleanup = "DELETE FROM `{$sDBPrefix}priv_change` USING `{$sDBPrefix}priv_change` LEFT JOIN `{$sDBPrefix}priv_changeop` ON `{$sDBPrefix}priv_change`.id = `{$sDBPrefix}priv_changeop`.changeid WHERE `{$sDBPrefix}priv_changeop`.id IS NULL;";
						CMDBSource::Query($sCleanup);
						SetupLog::Info('Cleanup completed successfully.');
					}
				} else {
					SetupLog::Info('Ok, nothing to cleanup.');
				}
			} catch (Exception $e) {
				SetupLog::Info("Cleanup of orphan records in `{$sDBPrefix}priv_change` failed: ".$e->getMessage());
			}
		}
	}

	public function MigrateDataAfterUpdateStructure(string $sMode, Config $oConfig): void
	{
		$this->InitDataModel($oConfig);

		$sDBName = $oConfig->Get('db_name');
		$sDBPrefix = $oConfig->Get('db_subname');

		// priv_change now has an 'origin' field to distinguish between the various input sources
		// Let's initialize the field with 'interactive' for all records where it's null
		// Then check if some records should hold a different value, based on a pattern matching in the userinfo field
		CMDBSource::SelectDB($sDBName);
		try {
			$sCount = "SELECT COUNT(*) FROM `{$sDBPrefix}priv_change` WHERE `origin` IS NULL";
			$iCount = (int)CMDBSource::QueryToScalar($sCount);
			if ($iCount > 0) {
				SetupLog::Info("Initializing '{$sDBPrefix}priv_change.origin' ($iCount records to update)");

				// By default all uninitialized values are considered as 'interactive'
				$sInit = "UPDATE `{$sDBPrefix}priv_change` SET `origin` = 'interactive' WHERE `origin` IS NULL";
				CMDBSource::Query($sInit);

				// CSV Import was identified by the comment at the end
				$sInit = "UPDATE `{$sDBPrefix}priv_change` SET `origin` = 'csv-import.php' WHERE `userinfo` LIKE '%Web Service (CSV)'";
				CMDBSource::Query($sInit);

				// CSV Import was identified by the comment at the end
				$sInit = "UPDATE `{$sDBPrefix}priv_change` SET `origin` = 'csv-interactive' WHERE `userinfo` LIKE '%(CSV)' AND origin = 'interactive'";
				CMDBSource::Query($sInit);

				// Syncho data sources were identified by the comment at the end
				// Unfortunately the comment is localized, so we have to search for all possible patterns
				$sCurrentLanguage = Dict::GetUserLanguage();
				$aSuffixes = [];
				foreach (array_keys(Dict::GetLanguages()) as $sLangCode) {
					Dict::SetUserLanguage($sLangCode);
					$sSuffix = CMDBSource::Quote('%'.Dict::S('Core:SyncDataExchangeComment'));
					$aSuffixes[$sSuffix] = true;
				}
				Dict::SetUserLanguage($sCurrentLanguage);
				$sCondition = '`userinfo` LIKE '.implode(' OR `userinfo` LIKE ', array_keys($aSuffixes));

				$sInit = "UPDATE `{$sDBPrefix}priv_change` SET `origin` = 'synchro-data-source' WHERE ($sCondition)";
				CMDBSource::Query($sInit);

				SetupLog::Info("Initialization of '{$sDBPrefix}priv_change.origin' completed.");
			} else {
				SetupLog::Info("'{$sDBPrefix}priv_change.origin' already initialized, nothing to do.");
			}
		} catch (Exception $e) {
			SetupLog::Error("Initializing '{$sDBPrefix}priv_change.origin' failed: ".$e->getMessage());
		}

		// priv_async_task now has a 'status' field to distinguish between the various statuses rather than just relying on the date columns
		// Let's initialize the field with 'planned' or 'error' for all records were it's null
		CMDBSource::SelectDB($sDBName);
		try {
			$sCount = "SELECT COUNT(*) FROM `{$sDBPrefix}priv_async_task` WHERE `status` IS NULL";
			$iCount = (int)CMDBSource::QueryToScalar($sCount);
			if ($iCount > 0) {
				SetupLog::Info("Initializing '{$sDBPrefix}priv_async_task.status' ($iCount records to update)");

				$sInit = "UPDATE `{$sDBPrefix}priv_async_task` SET `status` = 'planned' WHERE (`status` IS NULL) AND (`started` IS NULL)";
				CMDBSource::Query($sInit);

				$sInit = "UPDATE `{$sDBPrefix}priv_async_task` SET `status` = 'error' WHERE (`status` IS NULL) AND (`started` IS NOT NULL)";
				CMDBSource::Query($sInit);

				SetupLog::Info("Initialization of '{$sDBPrefix}priv_async_task.status' completed.");
			} else {
				SetupLog::Info("'{$sDBPrefix}priv_async_task.status' already initialized, nothing to do.");
			}
		} catch (Exception $e) {
			SetupLog::Error("Initializing '{$sDBPrefix}priv_async_task.status' failed: ".$e->getMessage());
		}
	}

	/**
	 * Analyzes the current installation and the possibilities
	 *
	 * @param null|Config $oConfig Defines the build environment (DB)
	 * @param mixed $modulesPath Either a single string or an array of absolute paths
	 * @param bool $bAbortOnMissingDependency ...
	 * @param array $aModulesToLoad List of modules to search for, defaults to all if omitted
	 *
	 * @return array Array with the following format:
	 * array =>
	 *     'iTop' => array(
	 *         'installed_version' => ... (could be empty in case of a fresh install)
	 *         'available_version => ...
	 *     )
	 *     <module_name> => array(
	 *         'installed_version' => ...
	 *         'available_version' => ...
	 *         'install' => array(
	 *             'flag' => SETUP_NEVER | SETUP_OPTIONAL | SETUP_MANDATORY
	 *             'message' => ...
	 *         )
	 *         'uninstall' => array(
	 *             'flag' => SETUP_NEVER | SETUP_OPTIONAL | SETUP_MANDATORY
	 *             'message' => ...
	 *         )
	 *         'label' => ...
	 *         'dependencies' => array(<module1>, <module2>, ...)
	 *         'visible' => true | false
	 *     )
	 * )
	 * @throws \Exception
	 */
	public function AnalyzeInstallation($oConfig, $modulesPath, $bAbortOnMissingDependency = false, $aModulesToLoad = null)
	{
		return AnalyzeInstallation::GetInstance()->AnalyzeInstallation($oConfig, $modulesPath, $bAbortOnMissingDependency, $aModulesToLoad);
	}

	/**
	 * @param \Config $oConfig
	 * @param string $sDataModelVersion
	 * @param array $aSelectedModuleCodes
	 * @param array $aSelectedExtensionCodes
	 * @param string|null $sInstallComment
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public function DoCreateConfig(Config $oConfig, string $sDataModelVersion, array $aSelectedModuleCodes, array $aSelectedExtensionCodes, ?string $sInstallComment = null, string $sSourceDesc = 'Setup')
	{
		$oConfig->Set('access_mode', ACCESS_FULL);

		// Record which modules are installed...
		$this->InitDataModel($oConfig, true);  // load data model and connect to the database

		if (!$this->RecordInstallation($oConfig, $sDataModelVersion, $aSelectedModuleCodes, $aSelectedExtensionCodes, $sInstallComment)) {
			throw new Exception('Failed to record the installation information');
		}

		$oConfig->UpdateIncludes('env-'.$this->sBuildEnv);
		$sEnvironmentLabel = $this->GetFinalEnv().' (built on '.date('Y-m-d').')';
		$oConfig->Set('app_env_label', $sEnvironmentLabel, $sSourceDesc);

		$this->WriteConfigFileSafe($oConfig);

		// Ready to go !!
		require_once(APPROOT.'core/dict.class.inc.php');
		MetaModel::ResetAllCaches();
	}

	/**
	 * @param Config $oConfig
	 *
	 * @throws \Exception
	 */
	public function WriteConfigFileSafe($oConfig)
	{
		self::MakeDirSafe(APPCONF);
		self::MakeDirSafe(APPCONF.$this->sBuildEnv);

		$sBuildConfigFile = APPCONF.$this->sBuildEnv.'/'.ITOP_CONFIG_FILE;

		// Write the config file
		@chmod($sBuildConfigFile, 0770); // In case it exists: RWX for owner and group, nothing for others
		$oConfig->WriteToFile($sBuildConfigFile);
		@chmod($sBuildConfigFile, 0440); // Read-only for owner and group, nothing for others
	}

	/**
	 * Return an array with extra directories to scan for extensions/modules to install
	 * @return string[]
	 */
	protected function GetExtraDirsToScan($aDirs = [])
	{
		// Do nothing, overload this method if needed
		return [];
	}

	/**
	 * Decide whether or not the given extension is selected for installation
	 * @param iTopExtension $oExtension
	 * @return boolean
	 */
	protected function IsExtensionSelected(iTopExtension $oExtension)
	{
		return ($oExtension->sSource == iTopExtension::SOURCE_REMOTE);
	}

	public function GetExtraDirsToCompile(string $sSourceDir): array
	{
		$sSourceDirFull = APPROOT.$sSourceDir;
		if (!is_dir($sSourceDirFull)) {
			throw new Exception("The source directory '$sSourceDirFull' does not exist (or could not be read)");
		}
		$aDirsToCompile = [$sSourceDirFull];

		if (is_dir(APPROOT.'extensions')) {
			$aDirsToCompile[] = APPROOT.'extensions';
		}
		$sExtraDir = utils::GetDataPath().$this->sBuildEnv.'-modules/';
		if (is_dir($sExtraDir)) {
			$aDirsToCompile[] = $sExtraDir;
		}

		return $aDirsToCompile;
	}

	/**
	 * Get the installed modules (only the installed ones)
	 */
	protected function GetMFModulesToCompile($sSourceEnv, $sSourceDir)
	{
		$sSourceDirFull = APPROOT.$sSourceDir;
		if (!is_dir($sSourceDirFull)) {
			throw new Exception("The source directory '$sSourceDirFull' does not exist (or could not be read)");
		}
		$aDirsToCompile = [$sSourceDirFull];
		if (is_dir(APPROOT.'extensions')) {
			$aDirsToCompile[] = APPROOT.'extensions';
		}
		$sExtraDir = utils::GetDataPath().$this->sBuildEnv.'-modules/';
		if (is_dir($sExtraDir)) {
			$aDirsToCompile[] = $sExtraDir;
		}

		$aExtraDirs = $this->GetExtraDirsToScan($aDirsToCompile);
		$aDirsToCompile = array_merge($aDirsToCompile, $aExtraDirs);

		$oSourceConfig = new Config(APPCONF.$sSourceEnv.'/'.ITOP_CONFIG_FILE);

		// Actually read the modules available for the build environment,
		// but get the selection from the source environment and finally
		// mark as (automatically) chosen all the "remote" modules present in the
		// build environment (data/<build-env>-modules)
		// The actual choices will be recorded by RecordInstallation below
		$this->InitExtensionMap($aExtraDirs, $oSourceConfig);
		$this->GetExtensionMap()->LoadChoicesFromDatabase($oSourceConfig);
		foreach ($this->GetExtensionMap()->GetAllExtensions() as $oExtension) {
			if ($this->IsExtensionSelected($oExtension)) {
				$this->GetExtensionMap()->MarkAsChosen($oExtension->sCode);
			}
		}

		$aModulesToLoad = $this->GetModulesToLoad($this->sFinalEnv, $aDirsToCompile);
		$aAvailableModules = $this->AnalyzeInstallation($oSourceConfig, $aDirsToCompile, true, $aModulesToLoad);

		// Do load the required modules
		//
		$oDictModule = new MFDictModule('dictionaries', 'iTop Dictionaries', APPROOT.'dictionaries');

		$aRet = [];
		$aRet[$oDictModule->GetName()] = $oDictModule;

		$oFactory = new ModelFactory($aDirsToCompile);
		$sDeltaFile = APPROOT.'core/datamodel.core.xml';
		if (file_exists($sDeltaFile)) {
			$oCoreModule = new MFCoreModule('core', 'Core Module', $sDeltaFile);
			$aRet[$oCoreModule->GetName()] = $oCoreModule;
		}
		$sDeltaFile = APPROOT.'application/datamodel.application.xml';
		if (file_exists($sDeltaFile)) {
			$oApplicationModule = new MFCoreModule('application', 'Application Module', $sDeltaFile);
			$aRet[$oApplicationModule->GetName()] = $oApplicationModule;
		}

		$aModules = $oFactory->FindModules();
		foreach ($aModules as $oModule) {
			$sModule = $oModule->GetName();
			$bIsExtra = $this->GetExtensionMap()->ModuleIsChosenAsPartOfAnExtension($sModule, iTopExtension::SOURCE_REMOTE);
			if (array_key_exists($sModule, $aAvailableModules)) {
				if (($aAvailableModules[$sModule]['installed_version'] != '') || $bIsExtra && !$oModule->IsAutoSelect()) { //Extra modules are always unless they are 'AutoSelect'
					$aRet[$oModule->GetName()] = $oModule;
				}
			}
		}

		$oPhpExpressionEvaluator = new PhpExpressionEvaluator([], ModuleFileReader::STATIC_CALL_AUTOSELECT_WHITELIST);

		// Now process the 'AutoSelect' modules
		do {
			// Loop while new modules are added...
			$bModuleAdded = false;
			foreach ($aModules as $oModule) {
				if (!array_key_exists($oModule->GetName(), $aRet) && $oModule->IsAutoSelect()) {
					SetupInfo::SetSelectedModules($aRet);
					try {
						$bSelected = $oPhpExpressionEvaluator->ParseAndEvaluateBooleanExpression($oModule->GetAutoSelect());
						if ($bSelected) {
							$aRet[$oModule->GetName()] = $oModule; // store the Id of the selected module
							$bModuleAdded = true;
						}
					} catch (ModuleFileReaderException $e) {
						//do nothing. logged already
					}
				}
			}
		} while ($bModuleAdded);

		$sDeltaFile = utils::GetDataPath().$this->sBuildEnv.'.delta.xml';
		if (file_exists($sDeltaFile)) {
			$oDelta = new MFDeltaModule($sDeltaFile);
			$aRet[$oDelta->GetName()] = $oDelta;
		}

		return $aRet;
	}

	/**
	 * Helper function to create the database structure
	 *
	 * @param \Config $oConfig
	 * @param $sMode
	 *
	 * @return boolean true on success, false otherwise
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function CreateDatabaseStructure(Config $oConfig, $sMode)
	{
		if (strlen($oConfig->Get('db_subname')) > 0) {
			$this->log_info("Creating the structure in '".$oConfig->Get('db_name')."' (table names prefixed by '".$oConfig->Get('db_subname')."').");
		} else {
			$this->log_info("Creating the structure in '".$oConfig->Get('db_name')."'.");
		}

		//MetaModel::CheckDefinitions();
		if ($sMode == 'install') {
			if (!MetaModel::DBExists(/* bMustBeComplete */ false)) {
				MetaModel::DBCreate([$this, 'LogQueryCallback']);
				$this->log_ok("Database structure successfully created.");
			} else {
				if (strlen($oConfig->Get('db_subname')) > 0) {
					throw new Exception("Error: found iTop tables into the database '".$oConfig->Get('db_name')."' (prefix: '".$oConfig->Get('db_subname')."'). Please, try selecting another database instance or specify another prefix to prevent conflicting table names.");
				} else {
					throw new Exception("Error: found iTop tables into the database '".$oConfig->Get('db_name')."'. Please, try selecting another database instance or specify a prefix to prevent conflicting table names.");
				}
			}
		} else {
			if (MetaModel::DBExists(/* bMustBeComplete */ false)) {
				// Have it work fine even if the DB has been set in read-only mode for the users
				// (fix copied from RunTimeEnvironment::RecordInstallation)
				$iPrevAccessMode = $oConfig->Get('access_mode');
				$oConfig->Set('access_mode', ACCESS_FULL);

				MetaModel::DBCreate([$this, 'LogQueryCallback']);
				$this->log_ok("Database structure successfully updated.");

				// Check (and update only if it seems needed) the hierarchical keys
				if (MFCompiler::SkipRebuildHKeys()) {
					$this->log_ok("Hierchical keys are NOT rebuilt due to the presence of the \"data/.setup-rebuild-hkeys-never\" file");
				} else {
					ob_start();
					$this->log_ok("Start of rebuilt of hierchical keys. If you have problems with this step, you can skip it by creating a \".setup-rebuild-hkeys-never\" file in data");
					MetaModel::CheckHKeys(false /* bDiagnosticsOnly */, true /* bVerbose*/, true /* bForceUpdate */); // Since in 1.2-beta the detection was buggy, let's force the rebuilding of HKeys
					$sFeedback = ob_get_clean();
					$this->log_ok("Hierchical keys rebuilt: $sFeedback");
				}

				// Check (and fix) data sync configuration
				ob_start();
				MetaModel::CheckDataSources(false /*$bDiagnostics*/, true/*$bVerbose*/);
				$sFeedback = ob_get_clean();
				$this->log_ok("Data sources checked: $sFeedback");

				// Fix meta enums
				ob_start();
				MetaModel::RebuildMetaEnums(true /*bVerbose*/);
				$sFeedback = ob_get_clean();
				$this->log_ok("Meta enums rebuilt: $sFeedback");

				// Restore the previous access mode
				$oConfig->Set('access_mode', $iPrevAccessMode);
			} else {
				if (strlen($oConfig->Get('db_subname')) > 0) {
					throw new Exception("Error: No previous instance of iTop found into the database '".$oConfig->Get('db_name')."' (prefix: '".$oConfig->Get('db_subname')."'). Please, try selecting another database instance.");
				} else {
					throw new Exception("Error: No previous instance of iTop found into the database '".$oConfig->Get('db_name')."'. Please, try selecting another database instance.");
				}
			}
		}
		return true;
	}

	/**
	 * @param \Config $oConfig
	 * @param string $sMode
	 * @param array|null $aSelectedModules null means all
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function UpdateDBSchema(Config $oConfig, string $sMode, ?array $aSelectedModules = null): void
	{
		$this->InitDataModel($oConfig);  // load data model only

		// Module specific actions (migrate the data)
		$aAvailableModules = $this->AnalyzeInstallation($oConfig, $this->GetBuildDir());
		$this->CallInstallerHandlers($aAvailableModules, 'BeforeDatabaseCreation', $aSelectedModules);

		if (!$this->CreateDatabaseStructure(MetaModel::GetConfig(), $sMode)) {
			throw new Exception("Failed to create/upgrade the database structure for environment");
		}
	}

	public function SetDbUUID(): void
	{
		// Set a DBProperty with a unique ID to identify this instance of iTop
		$sUUID = DBProperty::GetProperty('database_uuid', '');
		if ($sUUID === '') {
			$sUUID = utils::CreateUUID('database');
			DBProperty::SetProperty('database_uuid', $sUUID, 'Installation/upgrade of '.ITOP_APPLICATION, 'Unique ID of this '.ITOP_APPLICATION.' Database');
		}
	}

	/**
	 * @param \Config $oConfig
	 * @param array|null $aSelectedModules null means all
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public function AfterDBCreate(Config $oConfig, string $sMode, ?array $aSelectedModules = null, array $aAdminParams = []): void
	{
		$this->InitDataModel($oConfig);  // load data model and connect to the database

		// Perform here additional DB setup... profiles, etc...
		//
		$aAvailableModules = $this->AnalyzeInstallation($oConfig, $this->GetBuildDir());
		$this->CallInstallerHandlers($aAvailableModules, 'AfterDatabaseCreation', $aSelectedModules);

		$this->UpdatePredefinedObjects();

		if ($sMode == 'install') {
			SetupLog::Info('CreateAdminAccount');

			$sAdminUser = $aAdminParams['user'];
			$sAdminPwd = $aAdminParams['pwd'];
			$sAdminLanguage = $aAdminParams['language'];

			if (!UserRights::CreateAdministrator($sAdminUser, $sAdminPwd, $sAdminLanguage)) {
				throw(new Exception("Failed to create the administrator account '$sAdminUser'"));
			} else {
				SetupLog::Info("Administrator account '$sAdminUser' created.");
			}
		}

		// Perform final setup tasks here
		//
		$this->CallInstallerHandlers($aAvailableModules, 'AfterDatabaseSetup', $aSelectedModules);
	}

	public function UpdatePredefinedObjects()
	{
		// Have it work fine even if the DB has been set in read-only mode for the users
		$oConfig = MetaModel::GetConfig();
		$iPrevAccessMode = $oConfig->Get('access_mode');
		$oConfig->Set('access_mode', ACCESS_FULL);

		// Constant classes (e.g. User profiles)
		//
		foreach (MetaModel::GetClasses() as $sClass) {
			$aPredefinedObjects = call_user_func([
				$sClass,
				'GetPredefinedObjects',
			]);
			if ($aPredefinedObjects != null) {
				$this->log_info("$sClass::GetPredefinedObjects() returned ".count($aPredefinedObjects)." elements.");

				// Create/Delete/Update objects of this class,
				// according to the given constant values
				//
				$aDBIds = [];
				$oAll = new DBObjectSet(new DBObjectSearch($sClass));
				while ($oObj = $oAll->Fetch()) {
					if (array_key_exists($oObj->GetKey(), $aPredefinedObjects)) {
						$aObjValues = $aPredefinedObjects[$oObj->GetKey()];
						foreach ($aObjValues as $sAttCode => $value) {
							$oObj->Set($sAttCode, $value);
						}
						$oObj->DBUpdate();
						$aDBIds[$oObj->GetKey()] = true;
					} else {
						$oObj->DBDelete();
					}
				}
				foreach ($aPredefinedObjects as $iRefId => $aObjValues) {
					if (!array_key_exists($iRefId, $aDBIds)) {
						$oNewObj = MetaModel::NewObject($sClass);
						$oNewObj->SetKey($iRefId);
						foreach ($aObjValues as $sAttCode => $value) {
							$oNewObj->Set($sAttCode, $value);
						}
						$oNewObj->DBInsert();
					}
				}
			}
		}

		// Restore the previous access mode
		$oConfig->Set('access_mode', $iPrevAccessMode);
	}

	public function RecordInstallation(Config $oConfig, $sDataModelVersion, $aSelectedModuleCodes, $aSelectedExtensionCodes, $sShortComment = null)
	{
		// Have it work fine even if the DB has been set in read-only mode for the users
		$iPrevAccessMode = MetaModel::GetConfig()->Get('access_mode');
		MetaModel::GetConfig()->Set('access_mode', ACCESS_FULL);
		//$oConfig->Set('access_mode', ACCESS_FULL);

		if (CMDBSource::DBName() == '') {
			// In case this has not yet been done
			CMDBSource::InitFromConfig($oConfig);
		}

		if ($sShortComment === null) {
			$sShortComment = 'Done by the setup program';
		}
		$sMainComment = $sShortComment."\nBuilt on ".ITOP_BUILD_DATE;

		// Record datamodel version
		$aData = [
			'source_dir' => $oConfig->Get('source_dir'),
		];
		$iInstallationTime = time(); // Make sure that all modules record the same installation time
		$oInstallRec = new ModuleInstallation();
		$oInstallRec->Set('name', DATAMODEL_MODULE);
		$oInstallRec->Set('version', $sDataModelVersion);
		$oInstallRec->Set('comment', json_encode($aData));
		$oInstallRec->Set('parent_id', 0); // root module
		$oInstallRec->Set('installed', $iInstallationTime);
		$oInstallRec->DBInsertNoReload();

		// Record main installation
		$oInstallRec = new ModuleInstallation();
		$oInstallRec->Set('name', ITOP_APPLICATION);
		$oInstallRec->Set('version', ITOP_VERSION_FULL);
		$oInstallRec->Set('comment', $sMainComment);
		$oInstallRec->Set('parent_id', 0); // root module
		$oInstallRec->Set('installed', $iInstallationTime);
		$iMainItopRecord = $oInstallRec->DBInsertNoReload();

		// Record installed modules and extensions
		//
		$aAvailableModules = $this->AnalyzeInstallation($oConfig, $this->GetBuildDir());
		foreach ($aSelectedModuleCodes as $sModuleId) {
			if (($sModuleId == ROOT_MODULE) || ($sModuleId == DATAMODEL_MODULE)) {
				continue;
			}

			if (!array_key_exists($sModuleId, $aAvailableModules)) {
				continue;
			}

			$aModuleData = $aAvailableModules[$sModuleId];
			$sName = $sModuleId;
			$sVersion = $aModuleData['available_version'];
			$sUninstallable = $aModuleData['uninstallable'] ?? 'yes';
			$aComments = [];
			$aComments[] = $sShortComment;
			if ($aModuleData['mandatory']) {
				$aComments[] = 'Mandatory';
			} else {
				$aComments[] = 'Optional';
			}
			if ($aModuleData['visible']) {
				$aComments[] = 'Visible (during the setup)';
			} else {
				$aComments[] = 'Hidden (selected automatically)';
			}

			$aDependencies = $aModuleData['dependencies'];
			if (!empty($aDependencies)) {
				foreach ($aDependencies as $sDependOn) {
					$aComments[] = "Depends on module: $sDependOn";
				}
			}
			$sComment = implode("\n", $aComments);

			$oInstallRec = new ModuleInstallation();
			$oInstallRec->Set('name', $sName);
			$oInstallRec->Set('version', $sVersion);
			$oInstallRec->Set('comment', $sComment);
			$oInstallRec->Set('parent_id', $iMainItopRecord);
			$oInstallRec->Set('installed', $iInstallationTime);
			$oInstallRec->Set('uninstallable', $sUninstallable);
			$oInstallRec->DBInsertNoReload();
		}

		if ($this->GetExtensionMap()) {
			// Mark as chosen the selected extensions code passed to us

			// Note: some other extensions may already be marked as chosen
			foreach ($this->GetExtensionMap()->GetAllExtensions() as $oExtension) {
				if (in_array($oExtension->sCode, $aSelectedExtensionCodes)) {
					$this->GetExtensionMap()->MarkAsChosen($oExtension->sCode);
				}
			}

			foreach ($this->GetExtensionMap()->GetChoices() as $oExtension) {
				$oInstallRec = new ExtensionInstallation();
				$oInstallRec->Set('code', $oExtension->sCode);
				$oInstallRec->Set('label', $oExtension->sLabel);
				$oInstallRec->Set('version', $oExtension->sVersion);
				$oInstallRec->Set('source', $oExtension->sSource);
				$oInstallRec->Set('uninstallable', $oExtension->CanBeUninstalled() ? 'yes' : 'no');
				$oInstallRec->Set('installed', $iInstallationTime);
				$oInstallRec->Set('description', $oExtension->sDescription);
				$oInstallRec->DBInsertNoReload();
			}
		}

		// Restore the previous access mode
		MetaModel::GetConfig()->Set('access_mode', $iPrevAccessMode);

		// Database is created, installation has been tracked into it
		return true;
	}

	/**
	 * @param \Config $oConfig
	 *
	 * @return array|false
	 */
	public function GetApplicationVersion(Config $oConfig)
	{
		try {
			$aSelectInstall = ModuleInstallationRepository::GetInstance()->ReadFromDB($oConfig);
		} catch (MySQLException $e) {
			// No database or erroneous information
			$this->log_error('Can not connect to the database: host: '.$oConfig->Get('db_host').', user:'.$oConfig->Get('db_user').', pwd:'.$oConfig->Get('db_pwd').', db name:'.$oConfig->Get('db_name'));
			$this->log_error('Exception '.$e->getMessage());
			return false;
		}

		$aResult = [];
		// Scan the list of installed modules to get the version of the 'ROOT' module which holds the main application version
		foreach ($aSelectInstall as $aInstall) {
			$sModuleVersion = $aInstall['version'];
			if ($sModuleVersion == '') {
				// Though the version cannot be empty in iTop 2.0, it used to be possible
				// therefore we have to put something here or the module will not be considered
				// as being installed
				$sModuleVersion = '0.0.0';
			}

			if ($aInstall['parent_id'] == 0) {
				if ($aInstall['name'] == DATAMODEL_MODULE) {
					$aResult['datamodel_version'] = $sModuleVersion;
					$aComments = json_decode($aInstall['comment'], true);
					if (is_array($aComments)) {
						$aResult = array_merge($aResult, $aComments);
					}
				} else {
					$aResult['product_name'] = $aInstall['name'];
					$aResult['product_version'] = $sModuleVersion;
				}
			}
		}
		if (!array_key_exists('datamodel_version', $aResult)) {
			// Versions prior to 2.0 did not record the version of the datamodel
			// so assume that the datamodel version is equal to the application version
			$aResult['datamodel_version'] = $aResult['product_version'];
		}
		$this->log_info("GetApplicationVersion returns: product_name: ".$aResult['product_name'].', product_version: '.$aResult['product_version']);

		return count($aResult) == 0 ? false : $aResult;
	}

	public static function MakeDirSafe($sDir)
	{
		if (!is_dir($sDir)) {
			if (!@mkdir($sDir)) {
				throw new Exception("Failed to create directory '$sDir', please check that the web server process has enough rights to create the directory.");
			}
			@chmod($sDir, 0770); // RWX for owner and group, nothing for others
		}
	}

	/**
	 * Wrappers for logging into the setup log files
	 */
	protected function log_error($sText)
	{
		SetupLog::Error($sText);
	}

	protected function log_warning($sText)
	{
		SetupLog::Warning($sText);
	}

	protected function log_info($sText)
	{
		SetupLog::Info($sText);
	}

	protected function log_ok($sText)
	{
		SetupLog::Ok($sText);
	}

	/**
	 * Writes queries run by the setup in a SQL file
	 *
	 * @param string $sQuery
	 *
	 * @since 2.5.0 N°1001 utf8mb4 switch
	 * @uses \SetupUtils::GetSetupQueriesFilePath()
	 */
	protected function log_db_query($sQuery)
	{
		$sSetupQueriesFilePath = SetupUtils::GetSetupQueriesFilePath();
		$hSetupQueriesFile = @fopen($sSetupQueriesFilePath, 'a');
		if ($hSetupQueriesFile !== false) {
			fwrite($hSetupQueriesFile, "$sQuery\n");
			fclose($hSetupQueriesFile);
		}
	}

	public function GetCurrentDataModelVersion()
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT ModuleInstallation WHERE name='".DATAMODEL_MODULE."'");
		$oSet = new DBObjectSet($oSearch, ['installed' => false]);
		$oLatestDM = $oSet->Fetch();
		if ($oLatestDM == null) {
			return '0.0.0';
		}

		return $oLatestDM->Get('version');
	}

	/**
	 * Move the build env to the final env if all the steps went ok
	 *
	 * @return void
	 */
	public function Commit(): void
	{
		if ($this->sFinalEnv != $this->sBuildEnv) {
			if (file_exists(utils::GetDataPath().$this->sBuildEnv.'.delta.xml')) {
				if (file_exists(utils::GetDataPath().$this->sFinalEnv.'.delta.xml')) {
					// Make a "previous" file
					copy(
						utils::GetDataPath().$this->sFinalEnv.'.delta.xml',
						utils::GetDataPath().$this->sFinalEnv.'.delta.prev.xml'
					);
				}
				$this->CommitFile(
					utils::GetDataPath().$this->sBuildEnv.'.delta.xml',
					utils::GetDataPath().$this->sFinalEnv.'.delta.xml'
				);
			}
			$this->CommitFile(
				utils::GetDataPath().'datamodel-'.$this->sBuildEnv.'.xml',
				utils::GetDataPath().'datamodel-'.$this->sFinalEnv.'.xml'
			);
			$this->CommitFile(
				utils::GetDataPath().'datamodel-'.$this->sBuildEnv.'-with-delta.xml',
				utils::GetDataPath().'datamodel-'.$this->sFinalEnv.'-with-delta.xml',
				false
			);
			$this->CommitDir(
				utils::GetDataPath().$this->sBuildEnv.'-modules/',
				utils::GetDataPath().$this->sFinalEnv.'-modules/',
				false
			);
			$this->CommitDir(
				utils::GetDataPath().'cache-'.$this->sBuildEnv,
				utils::GetDataPath().'cache-'.$this->sFinalEnv,
				false
			);
			$this->CommitDir(
				APPROOT.'env-'.$this->sBuildEnv,
				APPROOT.'env-'.$this->sFinalEnv,
				true,
				false
			);

			$this->RerouteSymlinks(APPROOT.'env-'.$this->sFinalEnv, utils::GetDataPath().$this->sBuildEnv.'-modules/', utils::GetDataPath().$this->sFinalEnv.'-modules/');

			// Move the config file
			//
			$sBuildConfig = APPCONF.$this->sBuildEnv.'/config-itop.php';
			$sFinalConfig = APPCONF.$this->sFinalEnv.'/config-itop.php';
			@chmod($sFinalConfig, 0770); // In case it exists: RWX for owner and group, nothing for others
			$this->CommitFile($sBuildConfig, $sFinalConfig);
			@chmod($sFinalConfig, 0440); // Read-only for owner and group, nothing for others
			@rmdir(dirname($sBuildConfig)); // Cleanup the temporary build dir if empty

			if (! isset($_SESSION)) {
				//used in all UI setups (not unattended)
				session_start();
			}
			$_SESSION['itop_env'] = $this->sFinalEnv;
		}
	}

	/**
	 * Overwrite or create the destination file
	 *
	 * @param string $sSource
	 * @param string $sDest
	 * @param bool $bSourceMustExist
	 *
	 * @throws \Exception
	 */
	protected function CommitFile(string $sSource, string $sDest, bool $bSourceMustExist = true): void
	{
		if (file_exists($sSource)) {
			SetupUtils::builddir(dirname($sDest));
			if (file_exists($sDest)) {
				$bRes = @unlink($sDest);
				if (!$bRes) {
					throw new Exception('Commit - Failed to cleanup destination file: '.$sDest);
				}
			}
			rename($sSource, $sDest);
		} else {
			// The file does not exist
			if ($bSourceMustExist) {
				throw new Exception('Commit - Missing file: '.$sSource);
			} else {
				// Align the destination with the source... make sure there is NO file
				if (file_exists($sDest)) {
					$bRes = @unlink($sDest);
					if (!$bRes) {
						throw new Exception('Commit - Failed to cleanup destination file: '.$sDest);
					}
				}
			}
		}
	}

	/**
	 * Overwrite or create the destination directory
	 *
	 * @param $sSource
	 * @param $sDest
	 * @param boolean $bSourceMustExist
	 * @param boolean $bRemoveSource If true $sSource will be removed, otherwise $sSource will just be emptied
	 *
	 * @throws Exception
	 */
	protected function CommitDir($sSource, $sDest, $bSourceMustExist = true, $bRemoveSource = true)
	{
		if (file_exists($sSource)) {
			SetupUtils::movedir($sSource, $sDest, $bRemoveSource);
		} else {
			// The file does not exist
			if ($bSourceMustExist) {
				throw new Exception('Commit - Missing directory: '.$sSource);
			} else {
				// Align the destination with the source... make sure there is NO file
				if (file_exists($sDest)) {
					SetupUtils::rrmdir($sDest);
				}
			}
		}
	}

	public function Rollback()
	{
		if ($this->sFinalEnv != $this->sBuildEnv) {
			SetupUtils::tidydir(APPROOT.'env-'.$this->sBuildEnv);
		}
	}

	/**
	 * Call the given handler method for all selected modules having an installation handler
	 *
	 * @param array[] $aAvailableModules
	 * @param string $sHandlerName
	 * @param string[]|null $aSelectedModules
	 *
	 * @throws CoreException
	 */
	public function CallInstallerHandlers($aAvailableModules, $sHandlerName, $aSelectedModules = null)
	{
		foreach ($aAvailableModules as $sModuleId => $aModule) {
			if (($sModuleId == ROOT_MODULE) || ($sModuleId == DATAMODEL_MODULE)) {
				continue;
			}

			if (is_null($aSelectedModules) || in_array($sModuleId, $aSelectedModules)) {
				$oConfig = MetaModel::GetConfig();
				$oConfig->Set('access_mode', ACCESS_FULL);
				$aArgs = [$oConfig, $aModule['installed_version'], $aModule['available_version']];
				RunTimeEnvironment::CallInstallerHandler($aModule, $sHandlerName, $aArgs);
			}
		}
	}

	/**
	 * Call the given handler method for all selected modules having an installation handler
	 *
	 * @param array $aModuleInfo
	 * @param string $sHandlerName
	 * @param array $aArgs
	 *
	 * @throws CoreException
	 */
	public static function CallInstallerHandler(array $aModuleInfo, $sHandlerName, array $aArgs)
	{
		$sModuleInstallerClass = ModuleFileReader::GetInstance()->GetAndCheckModuleInstallerClass($aModuleInfo);
		if (is_null($sModuleInstallerClass)) {
			return;
		}

		SetupLog::Debug("Calling Module Handler: $sModuleInstallerClass::$sHandlerName");
		$aCallSpec = [$sModuleInstallerClass, $sHandlerName];
		if (is_callable($aCallSpec)) {
			try {
				call_user_func_array($aCallSpec, $aArgs);
			} catch (Exception $e) {
				$sModuleId = $aModuleInfo[ModuleFileReader::MODULE_INFO_ID] ?? "";
				$sErrorMessage = "Module $sModuleId : error when calling module installer class $sModuleInstallerClass for $sHandlerName handler";
				$aExceptionContextData = [
					'ModulelId'              => $sModuleId,
					'ModuleInstallerClass'   => $sModuleInstallerClass,
					'ModuleInstallerHandler' => $sHandlerName,
					'ExceptionClass'         => get_class($e),
					'ExceptionMessage'       => $e->getMessage(),
				];
				throw new CoreException($sErrorMessage, $aExceptionContextData, '', $e);
			}
		}
	}

	public function DoLoadData(Config $oConfig, bool $bSampleData = false, $aSelectedModules = null)
	{
		$this->InitDataModel($oConfig, false);  // load data model and connect to the database

		// Perform here additional DB setup... profiles, etc...
		//
		$aAvailableModules = $this->AnalyzeInstallation($oConfig, $this->GetBuildDir());

		$this->LoadData($aAvailableModules, $bSampleData, $aSelectedModules);

		$this->CallInstallerHandlers($aAvailableModules, 'AfterDataLoad', $aSelectedModules);

	}

	/**
	 * Load data from XML files for the selected modules (structural data and/or sample data)
	 *
	 * @param array $aAvailableModules
	 * @param bool $bSampleData Whether or not to load sample data
	 * @param null $aSelectedModules List of selected modules
	 *
	 * @throws \CoreException
	 */
	public function LoadData($aAvailableModules, $bSampleData, $aSelectedModules = null)
	{
		$oConfig = MetaModel::GetConfig();
		$oConfig->Set('access_mode', ACCESS_FULL);

		$oDataLoader = new XMLDataLoader();

		CMDBObject::SetCurrentChangeFromParams("Initialization from XML files for the selected modules ");
		$oMyChange = CMDBObject::GetCurrentChange();

		SetupLog::Info("starting data load session");
		$oDataLoader->StartSession($oMyChange);

		$aFiles = [];
		$aPreviouslyLoadedFiles = [];
		foreach ($aAvailableModules as $sModuleId => $aModule) {
			if (($sModuleId == ROOT_MODULE) || ($sModuleId == DATAMODEL_MODULE)) {
				continue;
			}

			$sRelativePath = 'env-'.$this->sBuildEnv.'/'.basename($aModule['root_dir']);
			// Load data only for selected AND newly installed modules
			if (is_null($aSelectedModules) || in_array($sModuleId, $aSelectedModules)) {
				if ($aModule['installed_version'] != '') {
					// Simulate the load of the previously loaded XML files to get the mapping of the keys
					if ($bSampleData) {
						$aPreviouslyLoadedFiles = static::MergeWithRelativeDir($aPreviouslyLoadedFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.struct']);
						$aPreviouslyLoadedFiles = static::MergeWithRelativeDir($aPreviouslyLoadedFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.sample']);
					} else {
						// Load only structural data
						$aPreviouslyLoadedFiles = static::MergeWithRelativeDir($aPreviouslyLoadedFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.struct']);
					}
				} else {
					if ($bSampleData) {
						$aFiles = static::MergeWithRelativeDir($aFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.struct']);
						$aFiles = static::MergeWithRelativeDir($aFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.sample']);
					} else {
						// Load only structural data
						$aFiles = static::MergeWithRelativeDir($aFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.struct']);
					}
				}
			}

		}

		// Simulate the load of the previously loaded files, in order to initialize
		// the mapping between the identifiers in the XML and the actual identifiers
		// in the current database
		foreach ($aPreviouslyLoadedFiles as $sFileRelativePath) {
			$sFileName = APPROOT.$sFileRelativePath;
			SetupLog::Info("Loading file: $sFileName (just to get the keys mapping)");
			if (!file_exists($sFileName)) {
				throw(new Exception("File $sFileName does not exist"));
			}

			$oDataLoader->LoadFile($sFileName, true);
			$sResult = sprintf("loading of %s done.", basename($sFileName));
			SetupLog::Info($sResult);
		}

		foreach ($aFiles as $sFileRelativePath) {
			$sFileName = APPROOT.$sFileRelativePath;
			SetupLog::Info("Loading file: $sFileName");
			if (!file_exists($sFileName)) {
				throw(new Exception("File $sFileName does not exist"));
			}

			$oDataLoader->LoadFile($sFileName);
			$sResult = sprintf("loading of %s done.", basename($sFileName));
			SetupLog::Info($sResult);
		}

		$oDataLoader->EndSession();
		SetupLog::Info("ending data load session");
	}

	/**
	 * Merge two arrays of file names, adding the relative path to the files provided in the array to merge
	 *
	 * @param string[] $aSourceArray
	 * @param string $sBaseDir
	 * @param string[] $aFilesToMerge
	 *
	 * @return string[]
	 */
	protected static function MergeWithRelativeDir($aSourceArray, $sBaseDir, $aFilesToMerge)
	{
		$aToMerge = [];
		foreach ($aFilesToMerge as $sFile) {
			$aToMerge[] = $sBaseDir.'/'.$sFile;
		}

		return array_merge($aSourceArray, $aToMerge);
	}

	/**
	 * Check the MetaModel for some common pitfall (class name too long, classes requiring too many joins...)
	 * The check takes about 900 ms for 200 classes
	 *
	 * @return string
	 * @throws Exception
	 */
	public function CheckMetaModel()
	{
		$iCount = 0;
		$fStart = microtime(true);
		foreach (MetaModel::GetClasses() as $sClass) {
			if (false == MetaModel::HasTable($sClass) && MetaModel::IsAbstract($sClass)) {
				//if a class is not persisted and is abstract, the code below would crash. Needed by the class AbstractRessource. This is tolerable to skip this because we check the setup process integrity, not the datamodel integrity.
				continue;
			}

			$oSearch = new DBObjectSearch($sClass);
			$oSearch->SetShowObsoleteData(false);
			$oSQLQuery = $oSearch->GetSQLQueryStructure([], false);
			$sViewName = MetaModel::DBGetView($sClass);
			if (strlen($sViewName) > 64) {
				throw new Exception("Class name too long for class: '$sClass'. The name of the corresponding view ($sViewName) would exceed MySQL's limit for the name of a table (64 characters).");
			}
			$sTableName = MetaModel::DBGetTable($sClass);
			if (strlen($sTableName) > 64) {
				throw new Exception("Table name too long for class: '$sClass'. The name of the corresponding MySQL table ($sTableName) would exceed MySQL's limit for the name of a table (64 characters).");
			}
			$iTableCount = $oSQLQuery->CountTables();
			if ($iTableCount > 61) {
				throw new Exception("Class requiring too many tables: '$sClass'. The structure of the class ($sClass) would require a query with more than 61 JOINS (MySQL's limitation).");
			}
			$iCount++;
		}
		$fDuration = microtime(true) - $fStart;

		return sprintf("Checked %d classes in %.1f ms. No error found.\n", $iCount, $fDuration * 1000.0);
	}

	public function DataToCleanupAudit()
	{
		$oSetupAudit = new SetupAudit(ITOP_DEFAULT_ENV, $this->sBuildEnv);

		//Make sure the MetaModel is started before analysing for issues
		$sConfFile = utils::GetConfigFilePath(ITOP_DEFAULT_ENV);
		MetaModel::Startup($sConfFile, false, false); // Start on production environment
		$oSetupAudit->RunDataAudit(true);
		$iCount = $oSetupAudit->GetDataToCleanupCount();

		if ($iCount > 0) {
			throw new Exception("$iCount elements require data adjustments or cleanup in the backoffice prior to upgrading iTop", DataAuditSequencer::DATA_AUDIT_FAILED);
		}
	}

	public function CopySetupFiles(): void
	{
		$sSourceEnv = $this->sFinalEnv;
		$sDestinationEnv = $this->sBuildEnv;

		if ($sDestinationEnv != $sSourceEnv) {
			SetupUtils::CopyFile(utils::GetDataPath().$sSourceEnv.'.delta.xml', utils::GetDataPath().$sDestinationEnv.'.delta.xml');
			SetupUtils::copydir(utils::GetDataPath().$sSourceEnv.'-modules/', utils::GetDataPath().$sDestinationEnv.'-modules/');

			// Copy the config file
			//
			$sFinalConfig = APPCONF.$sDestinationEnv.'/config-itop.php';
			if (is_file($sFinalConfig)) {
				chmod($sFinalConfig, 0770); // In case it exists: RWX for owner and group, nothing for others
			}
			SetupUtils::copydir(APPCONF.$sSourceEnv, APPCONF.$sDestinationEnv);
			MetaModel::ResetAllCaches($sDestinationEnv);
		}
	}

	/**
	 * Compile the data model by imitating the given environment
	 * The list of modules to be installed in the build environment is:
	 *  - the list of modules present in the "source_dir" (defined by the source environment) which are marked as "installed" in the source environment's database
	 *  - plus the list of modules present in the "extra" directory of the build environment: data/<build_environment>-modules/
	 *
	 * @param string $sSourceEnv The name of the source environment to 'imitate'
	 * @param null $bUseSymLinks Whether to create symbolic links instead of copies
	 *
	 * @return string[]
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function CompileFrom($sSourceEnv, $bUseSymLinks = null)
	{
		$oSourceConfig = new Config(utils::GetConfigFilePath($sSourceEnv));
		$sSourceDir = $oSourceConfig->Get('source_dir');

		$sSourceDirFull = APPROOT.$sSourceDir;
		// Do load the required modules
		//
		$oFactory = new ModelFactory($sSourceDirFull);
		$aModulesToCompile = $this->GetMFModulesToCompile($sSourceEnv, $sSourceDir);
		$oModule = null;
		foreach ($aModulesToCompile as $oModule) {
			if ($oModule instanceof MFDeltaModule) {
				// Just before loading the delta, let's save an image of the datamodel
				// in case there is no delta the operation will be done after the end of the loop
				$oFactory->SaveToFile(utils::GetDataPath().'datamodel-'.$this->sBuildEnv.'.xml');
			}
			$oFactory->LoadModule($oModule);
		}

		if (!is_null($oModule) && ($oModule instanceof MFDeltaModule)) {
			// A delta was loaded, let's save a second copy of the datamodel
			$oFactory->SaveToFile(utils::GetDataPath().'datamodel-'.$this->sBuildEnv.'-with-delta.xml');
		} else {
			// No delta was loaded, let's save the datamodel now
			$oFactory->SaveToFile(utils::GetDataPath().'datamodel-'.$this->sBuildEnv.'.xml');
		}

		$sBuildDir = APPROOT.'env-'.$this->sBuildEnv;
		self::MakeDirSafe($sBuildDir);
		$bSkipTempDir = ($this->sFinalEnv != $this->sBuildEnv); // No need for a temporary directory if sBuildEnv is already a temporary directory
		$oMFCompiler = new MFCompiler($oFactory, $this->sFinalEnv);
		$oMFCompiler->Compile($sBuildDir, null, $bUseSymLinks, $bSkipTempDir);

		MetaModel::ResetAllCaches($this->sBuildEnv);

		return array_keys($aModulesToCompile);
	}

	/**
	 * @param array $aRemovedExtensionCodes
	 * @param array $aSelectedModules
	 * @param string $sSourceDir
	 * @param string $sExtensionDir
	 * @param boolean $bUseSymbolicLinks
	 *
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 *
	 */
	public function DoCompile(array $aRemovedExtensionCodes, array $aSelectedModules, string $sSourceDir, string $sExtensionDir, bool $bUseSymbolicLinks = false): void
	{
		SetupLog::Info('Compiling data model.');

		$sEnvironment = $this->sBuildEnv;
		$sBuildPath = $this->GetBuildDir();

		$sSourcePath = APPROOT.$sSourceDir;
		$aDirsToScan = [$sSourcePath];
		$sExtensionsPath = APPROOT.$sExtensionDir;
		if (is_dir($sExtensionsPath)) {
			// if the extensions dir exists, scan it for additional modules as well
			$aDirsToScan[] = $sExtensionsPath;
		}
		$sExtraPath = APPROOT.'/data/'.$sEnvironment.'-modules/';
		if (is_dir($sExtraPath)) {
			// if the extra dir exists, scan it for additional modules as well
			$aDirsToScan[] = $sExtraPath;
		}

		if (!is_dir($sSourcePath)) {
			throw new CoreException("Failed to find the source directory '$sSourcePath', please check the rights of the web server");
		}

		if (!is_dir($sBuildPath)) {
			if (!mkdir($sBuildPath)) {
				throw new CoreException("Failed to create directory '$sBuildPath', please check the rights of the web server");
			} else {
				// adjust the rights if and only if the directory was just created
				// owner:rwx user/group:rx
				chmod($sBuildPath, 0755);
			}
		} elseif ($this->IsInItop($sBuildPath)) {
			// If the directory is under the root folder - as expected - let's clean-it before compiling
			SetupUtils::tidydir($sBuildPath);
		}

		$oExtensionsMap = new iTopExtensionsMap(ITOP_DEFAULT_ENV, $aDirsToScan);
		// Removed modules are stored as static for FindModules()
		$oExtensionsMap->DeclareExtensionAsRemoved($aRemovedExtensionCodes);

		// Check that all the extensions have a code
		foreach ($oExtensionsMap->GetAllExtensions() as $oExtension) {
			if (empty($oExtension->sCode)) {
				$sExtensionLabel = !empty($oExtension->sLabel) ? $oExtension->sLabel : $oExtension->sSourceDir;
				throw new CoreException(sprintf('Extension "%s" cannot be installed: Missing extension code', $sExtensionLabel));
			}
		}

		$oFactory = new ModelFactory($aDirsToScan);

		$oDictModule = new MFDictModule('dictionaries', 'iTop Dictionaries', APPROOT.'dictionaries');
		$oFactory->LoadModule($oDictModule);

		$sDeltaFile = APPROOT.'core/datamodel.core.xml';
		if (file_exists($sDeltaFile)) {
			$oCoreModule = new MFCoreModule('core', 'Core Module', $sDeltaFile);
			$oFactory->LoadModule($oCoreModule);
		}
		$sDeltaFile = APPROOT.'application/datamodel.application.xml';
		if (file_exists($sDeltaFile)) {
			$oApplicationModule = new MFCoreModule('application', 'Application Module', $sDeltaFile);
			$oFactory->LoadModule($oApplicationModule);
		}

		$aModules = $oFactory->FindModules();

		foreach ($aModules as $oModule) {
			$sModule = $oModule->GetName();
			if (in_array($sModule, $aSelectedModules)) {
				$oFactory->LoadModule($oModule);
			}
		}

		// Dump the "reference" model, just before loading any actual delta
		$oFactory->SaveToFile(utils::GetDataPath().'datamodel-'.$sEnvironment.'.xml');

		$sDeltaFile = utils::GetDataPath().$sEnvironment.'.delta.xml';
		if (file_exists($sDeltaFile)) {
			$oDelta = new MFDeltaModule($sDeltaFile);
			$oFactory->LoadModule($oDelta);
			$oFactory->SaveToFile(utils::GetDataPath().'datamodel-'.$sEnvironment.'-with-delta.xml');
		}

		$oMFCompiler = new MFCompiler($oFactory, $sEnvironment);
		$oMFCompiler->Compile($sBuildPath, null, $bUseSymbolicLinks, false, false);
		SetupLog::Info("Data model successfully compiled to '$sBuildPath'.");

		$sCacheDir = APPROOT.'/data/cache-'.$sEnvironment.'/';
		SetupUtils::builddir($sCacheDir);
		SetupUtils::tidydir($sCacheDir);

		// Set an "Instance UUID" identifying this machine based on a file located in the data directory
		$sInstanceUUIDFile = utils::GetDataPath().'instance.txt';
		SetupUtils::builddir(utils::GetDataPath());
		if (!file_exists($sInstanceUUIDFile)) {
			$sInstanceUUID = utils::CreateUUID('filesystem');
			file_put_contents($sInstanceUUIDFile, $sInstanceUUID);
		}
	}

	/**
	 * @param \Config $oConfig
	 * @param string $sBackupFileFormat
	 * @param string $sSourceConfigFile
	 * @param string|null $sMySQLBinDir
	 *
	 * @throws \BackupException
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @since 2.5.0 uses a {@link Config} object to store DB parameters
	 */
	public function Backup(Config $oConfig, string $sBackupFileFormat, string $sSourceConfigFile, ?string $sMySQLBinDir = null): void
	{
		$oBackup = new SetupDBBackup($oConfig);
		$sTargetFile = $oBackup->MakeName($sBackupFileFormat);
		if (!empty($sMySQLBinDir)) {
			$oBackup->SetMySQLBinDir($sMySQLBinDir);
		}

		CMDBSource::InitFromConfig($oConfig);
		$oBackup->CreateCompressedBackup($sTargetFile, $sSourceConfigFile);
	}

	/**
		* @param \Config $oConfig
		* @return void
	 */
	public function EnterMaintenanceMode(Config $oConfig)
	{
		SetupUtils::EnterMaintenanceMode($oConfig);
	}

	public function EnterReadOnlyMode(Config $oConfig)
	{
		if ($this->GetFinalEnv() != 'production') {
			return;
		}

		if (SetupUtils::IsInReadOnlyMode()) {
			return;
		}

		SetupUtils::EnterReadOnlyMode($oConfig);
	}

	public function ExitMaintenanceMode(): void
	{
		if (SetupUtils::IsInMaintenanceMode()) {
			SetupUtils::ExitMaintenanceMode();
		}
	}

	public function ExitReadOnlyMode()
	{
		if ($this->GetFinalEnv() != 'production') {
			return;
		}

		if (!SetupUtils::IsInReadOnlyMode()) {
			return;
		}

		SetupUtils::ExitReadOnlyMode();
	}

	public function GetFinalEnv(): string
	{
		return $this->sFinalEnv;
	}

	protected function IsInItop(string $sPath): bool
	{
		$sFileRealPath = realpath($sPath);
		if ($sFileRealPath === false) {
			return false;
		}

		$sRealBasePath = realpath(APPROOT); // avoid problems when having '/' on Windows for example
		if (!self::StartsWith($sFileRealPath, $sRealBasePath)) {
			return false;
		}

		return true;
	}

	protected static function StartsWith(string $sHaystack, string $sNeedle): bool
	{
		if (strlen($sNeedle) > strlen($sHaystack)) {
			return false;
		}

		return substr_compare($sHaystack, $sNeedle, 0, strlen($sNeedle)) === 0;
	}

	/**
	 * @param string $sSourceEnv
	 * @param array<string> $aSearchDirs : module/extension dirs to load if they are included in choices
	 *
	 * @return array| null
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \ModuleInstallationException
	 */
	protected function GetModulesToLoad(string $sSourceEnv, array $aSearchDirs): ?array
	{
		if (is_null($this->GetExtensionMap())) {
			return null;
		}

		$oSourceConfig = new Config(utils::GetConfigFilePath($sSourceEnv));

		$aChoices = $this->GetExtensionMap()->GetChoicesFromDatabase($oSourceConfig);
		return $this->GetModulesToLoadFromChoices($oSourceConfig, $aChoices, $aSearchDirs);
	}

	/**
	 * Return modules based on installation choices+package
* @param \Config $oConfig
* @param array|bool $aChoices
* @param array $aSearchDirs
* @return array|null
* @throws \ModuleInstallationException
	 */
	public function GetModulesToLoadFromChoices(Config $oConfig, array|bool $aChoices, array $aSearchDirs): ?array
	{
		if (false === $aChoices) {
			return null;
		}

		$sSourceDir = $oConfig->Get('source_dir');

		$sInstallFilePath = APPROOT.$sSourceDir.'/installation.xml';
		if (! is_file($sInstallFilePath)) {
			$sInstallFilePath = null;
		}

		$aExtensionDirs = [];
		foreach ($this->GetExtensionMap()->GetAllExtensions() as $oExtension) {
			if ($oExtension->bMarkedAsChosen && is_dir($oExtension->sSourceDir)) {
				$aExtensionDirs [] = $oExtension->sSourceDir;
			}
		}

		SetupLog::Info(__METHOD__, null, ['ext_dirs' => $aExtensionDirs]);
		$aModuleIdsToLoad = InstallationChoicesToModuleConverter::GetInstance()->GetModules($aChoices, $aSearchDirs, $sInstallFilePath, $aExtensionDirs);
		$aModulesToLoad = [];
		foreach ($aModuleIdsToLoad as $sModuleId) {
			$oModule = new Module($sModuleId);
			$sModuleName = $oModule->GetModuleName();
			$aModulesToLoad[] = $sModuleName;
		}

		return $aModulesToLoad;
	}

	/**
	 * Replace target for links in sToScan from sSource to sDest
	 *
	 * @param string $sToScan where to search for symlinks
	 * @param string $sSource part to replace in the link target
	 * @param string $sDest replacement in the link target
	 *
	 * @return void
	 */
	private function RerouteSymlinks(string $sToScan, string $sSource, string $sDest)
	{
		if (is_dir($sToScan)) {
			$aFiles = scandir($sToScan);
			if (sizeof($aFiles) > 0) {
				foreach ($aFiles as $sFile) {
					if ($sFile == '.' || $sFile == '..' || $sFile == '.svn' || $sFile == '.git') {
						// Skip
						continue;
					}
					$this->RerouteSymlinks($sToScan.'/'.$sFile, $sSource, $sDest);
				}
			}
		} elseif (is_link($sToScan)) {
			$sLinkPath = readlink($sToScan);
			$sLinkPath = str_replace($sSource, $sDest, $sLinkPath);
			unlink($sToScan);
			symlink($sLinkPath, $sToScan);
		}
	}
}
