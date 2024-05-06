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

require_once APPROOT."setup/modulediscovery.class.inc.php";
require_once APPROOT.'setup/modelfactory.class.inc.php';
require_once APPROOT.'setup/compiler.class.inc.php';
require_once APPROOT.'setup/extensionsmap.class.inc.php';

define ('MODULE_ACTION_OPTIONAL', 1);
define ('MODULE_ACTION_MANDATORY', 2);
define ('MODULE_ACTION_IMPOSSIBLE', 3);
define ('ROOT_MODULE', '_Root_'); // Convention to store IN MEMORY the name/version of the root module i.e. application
define ('DATAMODEL_MODULE', 'datamodel'); // Convention to store the version of the datamodel



class RunTimeEnvironment
{
	/**
	 * The name of the environment that the caller wants to build
	 * @var string sFinalEnv
	 */
	protected $sFinalEnv;

	/**
	 * Environment into which the build will be performed
	 * @var string sTargetEnv
	 */
	protected $sTargetEnv;

	/**
	 * Extensions map of the source environment
	 * @var iTopExtensionsMap
	 */
	protected $oExtensionsMap;

	/**
	 * Toolset for building a run-time environment
	 *
	 * @param string $sEnvironment (e.g. 'test')
	 * @param bool $bAutoCommit (make the target environment directly, or build a temporary one)
	 */
	public function __construct($sEnvironment = 'production', $bAutoCommit = true)
	{
		$this->sFinalEnv = $sEnvironment;
		if ($bAutoCommit)
		{
			// Build directly onto the requested environment
			$this->sTargetEnv = $sEnvironment;
		}
		else
		{
			// Build into a temporary target
			$this->sTargetEnv = $sEnvironment.'-build';
		}
		$this->oExtensionsMap = null;
	}

	/**
	 * Return the full path to the compiled code (do not use after commit)
	 * @return string
	 */
	public function GetBuildDir()
	{
		return APPROOT.'env-'.$this->sTargetEnv;
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
	 * @param $oConfig object The configuration (volatile, not necessarily already on disk)
	 * @param $bModelOnly boolean Whether or not to allow loading a data model with no corresponding DB
	 * @return none
	 */
	public function InitDataModel($oConfig, $bModelOnly = true, $bUseCache = false)
	{
		require_once APPROOT.'/setup/moduleinstallation.class.inc.php';

		$sConfigFile = $oConfig->GetLoadedFile();
		if (strlen($sConfigFile) > 0)
		{
			$this->log_info("MetaModel::Startup from $sConfigFile (ModelOnly = $bModelOnly)");
		}
		else
		{
			$this->log_info("MetaModel::Startup (ModelOnly = $bModelOnly)");
		}

		if (!$bUseCache)
		{
			// Reset the cache for the first use !
			MetaModel::ResetCache(md5(APPROOT).'-'.$this->sTargetEnv);
		}

		MetaModel::Startup($oConfig, $bModelOnly, $bUseCache, false /* $bTraceSourceFiles */, $this->sTargetEnv);

		if ($this->oExtensionsMap === null)
		{
			$this->oExtensionsMap = new iTopExtensionsMap($this->sTargetEnv);
		}
	}

	/**
	 * Analyzes the current installation and the possibilities
	 *
	 * @param null|Config $oConfig Defines the target environment (DB)
	 * @param mixed $modulesPath Either a single string or an array of absolute paths
	 * @param bool $bAbortOnMissingDependency ...
	 * @param array $aModulesToLoad List of modules to search for, defaults to all if omitted
	 *
	 * @return array Array with the following format:
	 * array =>
	 *     'iTop' => array(
	 *         'version_db' => ... (could be empty in case of a fresh install)
	 *         'version_code => ...
	 *     )
	 *     <module_name> => array(
	 *         'version_db' => ...
	 *         'version_code' => ...
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
		$aRes = array(
			ROOT_MODULE => array(
				'version_db' => '',
				'name_db' => '',
				'version_code' => ITOP_VERSION_FULL,
				'name_code' => ITOP_APPLICATION,
			)
		);

		$aDirs = is_array($modulesPath) ? $modulesPath : array($modulesPath);
		$aModules = ModuleDiscovery::GetAvailableModules($aDirs, $bAbortOnMissingDependency, $aModulesToLoad);
		foreach($aModules as $sModuleId => $aModuleInfo)
		{
			list($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);
			if ($sModuleName == '')
			{
				throw new Exception("Missing name for the module: '$sModuleId'");
			}
			if ($sModuleVersion == '')
			{
				// The version must not be empty (it will be used as a criteria to determine wether a module has been installed or not)
				//throw new Exception("Missing version for the module: '$sModuleId'");
				$sModuleVersion  = '1.0.0';
			}

			$sModuleAppVersion = $aModuleInfo['itop_version'];
			$aModuleInfo['version_db'] = '';
			$aModuleInfo['version_code'] = $sModuleVersion;

			if (!in_array($sModuleAppVersion, array('1.0.0', '1.0.1', '1.0.2')))
			{
				// This module is NOT compatible with the current version
					$aModuleInfo['install'] = array(
						'flag' => MODULE_ACTION_IMPOSSIBLE,
						'message' => 'the module is not compatible with the current version of the application'
					);
			}
			elseif ($aModuleInfo['mandatory'])
			{
				$aModuleInfo['install'] = array(
					'flag' => MODULE_ACTION_MANDATORY,
					'message' => 'the module is part of the application'
				);
			}
			else
			{
				$aModuleInfo['install'] = array(
					'flag' => MODULE_ACTION_OPTIONAL,
					'message' => ''
				);
			}
			$aRes[$sModuleName] = $aModuleInfo;
		}

		try
		{
			$aSelectInstall = array();
			if (! is_null($oConfig)) {
				CMDBSource::InitFromConfig($oConfig);
				$aSelectInstall = CMDBSource::QueryToArray("SELECT * FROM ".$oConfig->Get('db_subname')."priv_module_install");
			}
		}
		catch (MySQLException $e)
		{
			// No database or erroneous information
		}

		// Build the list of installed module (get the latest installation)
		//
		$aInstallByModule = array(); // array of <module> => array ('installed' => timestamp, 'version' => <version>)
		$iRootId = 0;
		foreach ($aSelectInstall as $aInstall)
		{
			if (($aInstall['parent_id'] == 0) && ($aInstall['name'] != 'datamodel'))
			{
				// Root module, what is its ID ?
				$iId = (int) $aInstall['id'];
				if ($iId > $iRootId)
				{
					$iRootId = $iId;
				}
			}
		}

		foreach ($aSelectInstall as $aInstall)
		{
			//$aInstall['comment']; // unsused
			$iInstalled = strtotime($aInstall['installed']);
			$sModuleName = $aInstall['name'];
			$sModuleVersion = $aInstall['version'];
			if ($sModuleVersion == '')
			{
				// Though the version cannot be empty in iTop 2.0, it used to be possible
				// therefore we have to put something here or the module will not be considered
				// as being installed
				$sModuleVersion = '0.0.0';
			}

			if ($aInstall['parent_id'] == 0)
			{
				$sModuleName = ROOT_MODULE;
			}
			else if($aInstall['parent_id'] != $iRootId)
			{
				// Skip all modules belonging to previous installations
				continue;
			}

			if (array_key_exists($sModuleName, $aInstallByModule))
			{
				if ($iInstalled < $aInstallByModule[$sModuleName]['installed'])
				{
					continue;
				}
			}

			if ($aInstall['parent_id'] == 0)
			{
				$aRes[$sModuleName]['version_db'] = $sModuleVersion;
				$aRes[$sModuleName]['name_db'] = $aInstall['name'];
			}

			$aInstallByModule[$sModuleName]['installed'] = $iInstalled;
			$aInstallByModule[$sModuleName]['version'] = $sModuleVersion;
		}

		// Adjust the list of proposed modules
		//
		foreach ($aInstallByModule as $sModuleName => $aModuleDB)
		{
			if ($sModuleName == ROOT_MODULE) continue; // Skip the main module

			if (!array_key_exists($sModuleName, $aRes))
			{
				// A module was installed, it is not proposed in the new build... skip
				continue;
			}
			$aRes[$sModuleName]['version_db'] = $aModuleDB['version'];

			if ($aRes[$sModuleName]['install']['flag'] == MODULE_ACTION_MANDATORY)
			{
				$aRes[$sModuleName]['uninstall'] = array(
					'flag' => MODULE_ACTION_IMPOSSIBLE,
					'message' => 'the module is part of the application'
				);
			}
			else
			{
				$aRes[$sModuleName]['uninstall'] = array(
					'flag' => MODULE_ACTION_OPTIONAL,
					'message' => ''
				);
			}
		}

		return $aRes;
	}


	/**
	 * @param Config $oConfig
	 *
	 * @throws \Exception
	 */
	public function WriteConfigFileSafe($oConfig)
	{
		self::MakeDirSafe(APPCONF);
		self::MakeDirSafe(APPCONF.$this->sTargetEnv);

		$sTargetConfigFile = APPCONF.$this->sTargetEnv.'/'.ITOP_CONFIG_FILE;

		// Write the config file
		@chmod($sTargetConfigFile, 0770); // In case it exists: RWX for owner and group, nothing for others
		$oConfig->WriteToFile($sTargetConfigFile);
		@chmod($sTargetConfigFile, 0440); // Read-only for owner and group, nothing for others
	}

	/**
	 * Return an array with extra directories to scan for extensions/modules to install
	 * @return string[]
	 */
	protected function GetExtraDirsToScan($aDirs = array())
	{
		// Do nothing, overload this method if needed
		return array();
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

	/**
	 * Get the installed modules (only the installed ones)
	 */
	protected function GetMFModulesToCompile($sSourceEnv, $sSourceDir)
	{
		$sSourceDirFull = APPROOT.$sSourceDir;
		if (!is_dir($sSourceDirFull))
		{
			throw new Exception("The source directory '$sSourceDirFull' does not exist (or could not be read)");
		}
		$aDirsToCompile = array($sSourceDirFull);
		if (is_dir(APPROOT.'extensions'))
		{
			$aDirsToCompile[] = APPROOT.'extensions';
		}
		$sExtraDir = APPROOT.'data/'.$this->sTargetEnv.'-modules/';
		if (is_dir($sExtraDir))
		{
			$aDirsToCompile[] = $sExtraDir;
		}

		$aExtraDirs = $this->GetExtraDirsToScan($aDirsToCompile);
		$aDirsToCompile = array_merge($aDirsToCompile, $aExtraDirs);

		$aRet = array();

		// Determine the installed modules and extensions
		//
		$oSourceConfig = new Config(APPCONF.$sSourceEnv.'/'.ITOP_CONFIG_FILE);
		$oSourceEnv = new RunTimeEnvironment($sSourceEnv);
		$aAvailableModules = $oSourceEnv->AnalyzeInstallation($oSourceConfig, $aDirsToCompile);

		// Actually read the modules available for the target environment,
		// but get the selection from the source environment and finally
		// mark as (automatically) chosen alll the "remote" modules present in the
		// target environment (data/<target-env>-modules)
		// The actual choices will be recorded by RecordInstallation below
		$this->oExtensionsMap = new iTopExtensionsMap($this->sTargetEnv, true, $aExtraDirs);
		$this->oExtensionsMap->LoadChoicesFromDatabase($oSourceConfig);
		foreach($this->oExtensionsMap->GetAllExtensions() as $oExtension)
		{
			if($this->IsExtensionSelected($oExtension))
			{
				$this->oExtensionsMap->MarkAsChosen($oExtension->sCode);
			}
		}

		// Do load the required modules
		//
		$oDictModule = new MFDictModule('dictionaries', 'iTop Dictionaries', APPROOT.'dictionaries');
		$aRet[$oDictModule->GetName()] = $oDictModule;

		$oFactory = new ModelFactory($aDirsToCompile);
		$sDeltaFile = APPROOT.'core/datamodel.core.xml';
		if (file_exists($sDeltaFile))
		{
			$oCoreModule = new MFCoreModule('core', 'Core Module', $sDeltaFile);
			$aRet[$oCoreModule->GetName()] = $oCoreModule;
		}
		$sDeltaFile = APPROOT.'application/datamodel.application.xml';
		if (file_exists($sDeltaFile))
		{
			$oApplicationModule = new MFCoreModule('application', 'Application Module', $sDeltaFile);
			$aRet[$oApplicationModule->GetName()] = $oApplicationModule;
		}

		$aModules = $oFactory->FindModules();
		foreach($aModules as $oModule)
		{
			$sModule = $oModule->GetName();
			$sModuleRootDir = $oModule->GetRootDir();
			$bIsExtra = $this->oExtensionsMap->ModuleIsChosenAsPartOfAnExtension($sModule, iTopExtension::SOURCE_REMOTE);
			if (array_key_exists($sModule, $aAvailableModules))
			{
				if (($aAvailableModules[$sModule]['version_db'] != '') ||  $bIsExtra && !$oModule->IsAutoSelect()) //Extra modules are always unless they are 'AutoSelect'
				{
					$aRet[$oModule->GetName()] = $oModule;
				}
			}
		}

		// Now process the 'AutoSelect' modules
		do
		{
			// Loop while new modules are added...
			$bModuleAdded = false;
			foreach($aModules as $oModule)
			{
				if (!array_key_exists($oModule->GetName(), $aRet) && $oModule->IsAutoSelect())
				{
					try
					{
						$bSelected = false;
						SetupInfo::SetSelectedModules($aRet);
						eval('$bSelected = ('.$oModule->GetAutoSelect().');');
					}
					catch(Exception $e)
					{
						$bSelected = false;
					}
					if ($bSelected)
					{
						$aRet[$oModule->GetName()] = $oModule; // store the Id of the selected module
						$bModuleAdded  = true;
					}
				}
			}
		}
		while($bModuleAdded);

		$sDeltaFile = APPROOT.'data/'.$this->sTargetEnv.'.delta.xml';
		if (file_exists($sDeltaFile))
		{
			$oDelta = new MFDeltaModule($sDeltaFile);
			$aRet[$oDelta->GetName()] = $oDelta;
		}

		return $aRet;
	}

	/**
	 * Compile the data model by imitating the given environment
	 * The list of modules to be installed in the target environment is:
	 *  - the list of modules present in the "source_dir" (defined by the source environment) which are marked as "installed" in the source environment's database
	 *  - plus the list of modules present in the "extra" directory of the target environment: data/<target_environment>-modules/
	 *
	 * @param string $sSourceEnv The name of the source environment to 'imitate'
	 * @param bool $bUseSymLinks Whether to create symbolic links instead of copies
	 *
	 * @return string[]
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
		foreach ($aModulesToCompile as $oModule)
		{
			if ($oModule instanceof MFDeltaModule)
			{
				// Just before loading the delta, let's save an image of the datamodel
				// in case there is no delta the operation will be done after the end of the loop
				$oFactory->SaveToFile(APPROOT.'data/datamodel-'.$this->sTargetEnv.'.xml');
			}
			$oFactory->LoadModule($oModule);
		}


		if ($oModule instanceof MFDeltaModule) {
			// A delta was loaded, let's save a second copy of the datamodel
			$oFactory->SaveToFile(APPROOT.'data/datamodel-'.$this->sTargetEnv.'-with-delta.xml');
		} else {
			// No delta was loaded, let's save the datamodel now
			$oFactory->SaveToFile(APPROOT.'data/datamodel-'.$this->sTargetEnv.'.xml');
		}

		$sTargetDir = APPROOT.'env-'.$this->sTargetEnv;
		self::MakeDirSafe($sTargetDir);
		$bSkipTempDir = ($this->sFinalEnv != $this->sTargetEnv); // No need for a temporary directory if sTargetEnv is already a temporary directory
		$oMFCompiler = new MFCompiler($oFactory, $this->sFinalEnv);
		$oMFCompiler->Compile($sTargetDir, null, $bUseSymLinks, $bSkipTempDir);

		$sCacheDir = APPROOT.'data/cache-'.$this->sTargetEnv;
		SetupUtils::builddir($sCacheDir);
		SetupUtils::tidydir($sCacheDir);

		MetaModel::ResetCache(md5(APPROOT).'-'.$this->sTargetEnv);

		return array_keys($aModulesToCompile);
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
		if (strlen($oConfig->Get('db_subname')) > 0)
		{
			$this->log_info("Creating the structure in '".$oConfig->Get('db_name')."' (table names prefixed by '".$oConfig->Get('db_subname')."').");
		}
		else
		{
			$this->log_info("Creating the structure in '".$oConfig->Get('db_name')."'.");
		}

		//MetaModel::CheckDefinitions();
		if ($sMode == 'install')
		{
			if (!MetaModel::DBExists(/* bMustBeComplete */ false))
			{
				MetaModel::DBCreate(array($this, 'LogQueryCallback'));
				$this->log_ok("Database structure successfully created.");
			}
			else
			{
				if (strlen($oConfig->Get('db_subname')) > 0)
				{
					throw new Exception("Error: found iTop tables into the database '".$oConfig->Get('db_name')."' (prefix: '".$oConfig->Get('db_subname')."'). Please, try selecting another database instance or specify another prefix to prevent conflicting table names.");
				}
				else
				{
					throw new Exception("Error: found iTop tables into the database '".$oConfig->Get('db_name')."'. Please, try selecting another database instance or specify a prefix to prevent conflicting table names.");
				}
			}
		}
		else
		{
			if (MetaModel::DBExists(/* bMustBeComplete */ false))
			{
				// Have it work fine even if the DB has been set in read-only mode for the users
				// (fix copied from RunTimeEnvironment::RecordInstallation)
				$iPrevAccessMode = $oConfig->Get('access_mode');
				$oConfig->Set('access_mode', ACCESS_FULL);

				MetaModel::DBCreate(array($this, 'LogQueryCallback'));
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
			}
			else
			{
				if (strlen($oConfig->Get('db_subname')) > 0)
				{
					throw new Exception("Error: No previous instance of iTop found into the database '".$oConfig->Get('db_name')."' (prefix: '".$oConfig->Get('db_subname')."'). Please, try selecting another database instance.");
				}
				else
				{
					throw new Exception("Error: No previous instance of iTop found into the database '".$oConfig->Get('db_name')."'. Please, try selecting another database instance.");
				}
			}
		}
		return true;
	}

	public function UpdatePredefinedObjects()
	{
		// Have it work fine even if the DB has been set in read-only mode for the users
		$oConfig = MetaModel::GetConfig();
		$iPrevAccessMode = $oConfig->Get('access_mode');
		$oConfig->Set('access_mode', ACCESS_FULL);

		// Constant classes (e.g. User profiles)
		//
		foreach (MetaModel::GetClasses() as $sClass)
		{
			$aPredefinedObjects = call_user_func(array(
				$sClass,
				'GetPredefinedObjects'
			));
			if ($aPredefinedObjects != null)
			{
				$this->log_info("$sClass::GetPredefinedObjects() returned " . count($aPredefinedObjects) . " elements.");

				// Create/Delete/Update objects of this class,
				// according to the given constant values
				//
				$aDBIds = array();
				$oAll = new DBObjectSet(new DBObjectSearch($sClass));
				while ($oObj = $oAll->Fetch())
				{
					if (array_key_exists($oObj->GetKey(), $aPredefinedObjects))
					{
						$aObjValues = $aPredefinedObjects[$oObj->GetKey()];
						foreach ($aObjValues as $sAttCode => $value)
						{
							$oObj->Set($sAttCode, $value);
						}
						$oObj->DBUpdate();
						$aDBIds[$oObj->GetKey()] = true;
					}
					else
					{
						$oObj->DBDelete();
					}
				}
				foreach ($aPredefinedObjects as $iRefId => $aObjValues)
				{
					if (! array_key_exists($iRefId, $aDBIds))
					{
						$oNewObj = MetaModel::NewObject($sClass);
						$oNewObj->SetKey($iRefId);
						foreach ($aObjValues as $sAttCode => $value)
						{
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

		if (CMDBSource::DBName() == '')
		{
			// In case this has not yet been done
			CMDBSource::InitFromConfig($oConfig);
		}

		if ($sShortComment === null)
		{
			$sShortComment = 'Done by the setup program';
		}
		$sMainComment = $sShortComment."\nBuilt on ".ITOP_BUILD_DATE;

		// Record datamodel version
		$aData = array(
			'source_dir' => $oConfig->Get('source_dir'),
		);
		$iInstallationTime = time(); // Make sure that all modules record the same installation time
		$oInstallRec = new ModuleInstallation();
		$oInstallRec->Set('name', DATAMODEL_MODULE);
		$oInstallRec->Set('version', $sDataModelVersion);
		$oInstallRec->Set('comment', json_encode($aData));
		$oInstallRec->Set('parent_id', 0); // root module
		$oInstallRec->Set('installed', $iInstallationTime);
		$iMainItopRecord = $oInstallRec->DBInsertNoReload();

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
		$aAvailableExtensions = array();
		$aAvailableModules = $this->AnalyzeInstallation($oConfig, $this->GetBuildDir());
		foreach ($aSelectedModuleCodes as $sModuleId) {
			if (!array_key_exists($sModuleId, $aAvailableModules)) {
				continue;
			}
			$aModuleData = $aAvailableModules[$sModuleId];
			$sName = $sModuleId;
			$sVersion = $aModuleData['version_code'];
			$aComments = array();
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
			$oInstallRec->DBInsertNoReload();
		}

		if ($this->oExtensionsMap)
		{
			// Mark as chosen the selected extensions code passed to us
			// Note: some other extensions may already be marked as chosen
			foreach($this->oExtensionsMap->GetAllExtensions() as $oExtension)
			{
				if (in_array($oExtension->sCode, $aSelectedExtensionCodes))
				{
					$this->oExtensionsMap->MarkAsChosen($oExtension->sCode);
				}
			}

			foreach($this->oExtensionsMap->GetChoices() as $oExtension)
			{
				$oInstallRec = new ExtensionInstallation();
				$oInstallRec->Set('code', $oExtension->sCode);
				$oInstallRec->Set('label', $oExtension->sLabel);
				$oInstallRec->Set('version', $oExtension->sVersion);
				$oInstallRec->Set('source',  $oExtension->sSource);
				$oInstallRec->Set('installed', $iInstallationTime);
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
		try
		{
			CMDBSource::InitFromConfig($oConfig);
			$sSQLQuery = "SELECT * FROM ".$oConfig->Get('db_subname')."priv_module_install";
			$aSelectInstall = CMDBSource::QueryToArray($sSQLQuery);
		}
		catch (MySQLException $e)
		{
			// No database or erroneous information
			$this->log_error('Can not connect to the database: host: '.$oConfig->Get('db_host').', user:'.$oConfig->Get('db_user').', pwd:'.$oConfig->Get('db_pwd').', db name:'.$oConfig->Get('db_name'));
			$this->log_error('Exception '.$e->getMessage());
			return false;
		}

		$aResult = [];
		// Scan the list of installed modules to get the version of the 'ROOT' module which holds the main application version
		foreach ($aSelectInstall as $aInstall)
		{
			$sModuleVersion = $aInstall['version'];
			if ($sModuleVersion == '')
			{
				// Though the version cannot be empty in iTop 2.0, it used to be possible
				// therefore we have to put something here or the module will not be considered
				// as being installed
				$sModuleVersion = '0.0.0';
			}

			if ($aInstall['parent_id'] == 0)
			{
				if ($aInstall['name'] == DATAMODEL_MODULE)
				{
					$aResult['datamodel_version'] = $sModuleVersion;
					$aComments = json_decode($aInstall['comment'], true);
					if (is_array($aComments))
					{
						$aResult = array_merge($aResult, $aComments);
					}
				}
				else
				{
					$aResult['product_name'] = $aInstall['name'];
					$aResult['product_version'] = $sModuleVersion;
				}
			}
		}
		if (!array_key_exists('datamodel_version', $aResult))
		{
			// Versions prior to 2.0 did not record the version of the datamodel
			// so assume that the datamodel version is equal to the application version
			$aResult['datamodel_version'] = $aResult['product_version'];
		}
		$this->log_info("GetApplicationVersion returns: product_name: ".$aResult['product_name'].', product_version: '.$aResult['product_version']);
		return empty($aResult) ? false : $aResult;
	}

	public static function MakeDirSafe($sDir)
	{
		if (!is_dir($sDir))
		{
			if (!@mkdir($sDir))
			{
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
		if ($hSetupQueriesFile !== false)
		{
			fwrite($hSetupQueriesFile, "$sQuery\n");
			fclose($hSetupQueriesFile);
		}
	}

	public function GetCurrentDataModelVersion()
	{
		$oSearch = DBObjectSearch::FromOQL("SELECT ModuleInstallation WHERE name='".DATAMODEL_MODULE."'");
		$oSet = new DBObjectSet($oSearch, array('installed' => false));
		$oLatestDM = $oSet->Fetch();
		if ($oLatestDM == null)
		{
			return '0.0.0';
		}
		return $oLatestDM->Get('version');
	}

	public function Commit()
	{
		if ($this->sFinalEnv != $this->sTargetEnv)
		{
			if (file_exists(APPROOT.'data/'.$this->sTargetEnv.'.delta.xml'))
			{
				if (file_exists(APPROOT.'data/'.$this->sFinalEnv.'.delta.xml'))
				{
					// Make a "previous" file
					copy(
						APPROOT.'data/'.$this->sFinalEnv.'.delta.xml',
						APPROOT.'data/'.$this->sFinalEnv.'.delta.prev.xml'
					);
				}
				$this->CommitFile(
					APPROOT.'data/'.$this->sTargetEnv.'.delta.xml',
					APPROOT.'data/'.$this->sFinalEnv.'.delta.xml'
				);
			}
			$this->CommitFile(
				APPROOT.'data/datamodel-'.$this->sTargetEnv.'.xml',
				APPROOT.'data/datamodel-'.$this->sFinalEnv.'.xml'
			);
			$this->CommitFile(
				APPROOT.'data/datamodel-'.$this->sTargetEnv.'-with-delta.xml',
				APPROOT.'data/datamodel-'.$this->sFinalEnv.'-with-delta.xml',
				false
			);
			$this->CommitDir(
				APPROOT.'data/'.$this->sTargetEnv.'-modules/',
				APPROOT.'data/'.$this->sFinalEnv.'-modules/',
				false
			);
			$this->CommitDir(
				APPROOT.'data/cache-'.$this->sTargetEnv,
				APPROOT.'data/cache-'.$this->sFinalEnv,
				false
			);
			$this->CommitDir(
				APPROOT.'env-'.$this->sTargetEnv,
				APPROOT.'env-'.$this->sFinalEnv,
                true,
                false
			);

			// Move the config file
			//
			$sTargetConfig = APPCONF.$this->sTargetEnv.'/config-itop.php';
			$sFinalConfig = APPCONF.$this->sFinalEnv.'/config-itop.php';
			@chmod($sFinalConfig, 0770); // In case it exists: RWX for owner and group, nothing for others
			$this->CommitFile($sTargetConfig, $sFinalConfig);
			@chmod($sFinalConfig, 0440); // Read-only for owner and group, nothing for others
			@rmdir(dirname($sTargetConfig)); // Cleanup the temporary build dir if empty

			MetaModel::ResetCache(md5(APPROOT).'-'.$this->sFinalEnv);
		}
	}

	/**
	 * Overwrite or create the destination file
	 *
	 * @param $sSource
	 * @param $sDest
	 * @param bool $bSourceMustExist
	 * @throws Exception
	 */
	protected function CommitFile($sSource, $sDest, $bSourceMustExist = true)
	{
		if (file_exists($sSource))
		{
			SetupUtils::builddir(dirname($sDest));
			if (file_exists($sDest))
			{
				$bRes = @unlink($sDest);
				if (!$bRes)
				{
					throw new Exception('Commit - Failed to cleanup destination file: '.$sDest);
				}
			}
			rename($sSource, $sDest);
		}
		else
		{
			// The file does not exist
			if ($bSourceMustExist)
			{
				throw new Exception('Commit - Missing file: '.$sSource);
			}
			else
			{
				// Align the destination with the source... make sure there is NO file
				if (file_exists($sDest))
				{
					$bRes = @unlink($sDest);
					if (!$bRes)
					{
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
	 * @throws Exception
	 */
	protected function CommitDir($sSource, $sDest, $bSourceMustExist = true, $bRemoveSource = true)
	{
		if (file_exists($sSource))
		{
			SetupUtils::movedir($sSource, $sDest, $bRemoveSource);
		}
		else
		{
			// The file does not exist
			if ($bSourceMustExist)
			{
				throw new Exception('Commit - Missing directory: '.$sSource);
			}
			else
			{
				// Align the destination with the source... make sure there is NO file
				if (file_exists($sDest))
				{
					SetupUtils::rrmdir($sDest);
				}
			}
		}
	}

	public function Rollback()
	{
		if ($this->sFinalEnv != $this->sTargetEnv)
		{
			SetupUtils::tidydir(APPROOT.'env-'.$this->sTargetEnv);
		}
	}

    /**
     * Call the given handler method for all selected modules having an installation handler
     * @param array[] $aAvailableModules
     * @param string[] $aSelectedModules
     * @param string $sHandlerName
     * @throws CoreException
     */
	public function CallInstallerHandlers($aAvailableModules, $aSelectedModules, $sHandlerName)
	{
	    foreach($aAvailableModules as $sModuleId => $aModule)
	    {
	        if (($sModuleId != ROOT_MODULE) && in_array($sModuleId, $aSelectedModules) &&
	            isset($aAvailableModules[$sModuleId]['installer']) )
	        {
	            $sModuleInstallerClass = $aAvailableModules[$sModuleId]['installer'];
		        SetupLog::Info("Calling Module Handler: $sModuleInstallerClass::$sHandlerName(oConfig, {$aModule['version_db']}, {$aModule['version_code']})");
	            $aCallSpec = array($sModuleInstallerClass, $sHandlerName);
	            if (is_callable($aCallSpec))
	            {
                    try {
                        call_user_func_array($aCallSpec, array(MetaModel::GetConfig(), $aModule['version_db'], $aModule['version_code']));
                    } catch (Exception $e) {
                        $sErrorMessage = "Module $sModuleId : error when calling module installer class $sModuleInstallerClass for $sHandlerName handler";
                        $aExceptionContextData = [
                                'ModulelId' => $sModuleId,
                                'ModuleInstallerClass' => $sModuleInstallerClass,
                                'ModuleInstallerHandler' => $sHandlerName,
                                'ExceptionClass' => get_class($e),
                                'ExceptionMessage' => $e->getMessage(),
                        ];
                        throw new CoreException($sErrorMessage, $aExceptionContextData, '', $e);
                    }
                }
	        }
	    }
	}

	/**
	 * Load data from XML files for the selected modules (structural data and/or sample data)
	 * @param array[] $aAvailableModules All available modules and their definition
	 * @param string[] $aSelectedModules List of selected modules
	 * @param bool $bSampleData Wether or not to load sample data
	 */
	public function LoadData($aAvailableModules, $aSelectedModules, $bSampleData)
	{
		$oDataLoader = new XMLDataLoader();

		CMDBObject::SetCurrentChangeFromParams("Initialization from XML files for the selected modules ");
		$oMyChange = CMDBObject::GetCurrentChange();

		SetupLog::Info("starting data load session");
		$oDataLoader->StartSession($oMyChange);

		$aFiles = array();
		$aPreviouslyLoadedFiles = array();
		foreach ($aAvailableModules as $sModuleId => $aModule) {
			if (($sModuleId != ROOT_MODULE)) {
				$sRelativePath = 'env-'.$this->sTargetEnv.'/'.basename($aModule['root_dir']);
				// Load data only for selected AND newly installed modules
				if (in_array($sModuleId, $aSelectedModules)) {
					if ($aModule['version_db'] != '') {
						// Simulate the load of the previously loaded XML files to get the mapping of the keys
						if ($bSampleData) {
	                        $aPreviouslyLoadedFiles = static::MergeWithRelativeDir($aPreviouslyLoadedFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.struct']);
	                        $aPreviouslyLoadedFiles = static::MergeWithRelativeDir($aPreviouslyLoadedFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.sample']);
	                    }
	                    else
	                    {
	                        // Load only structural data
	                        $aPreviouslyLoadedFiles = static::MergeWithRelativeDir($aPreviouslyLoadedFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.struct']);
	                    }
	                }
	                else
	                {
	                    if ($bSampleData)
	                    {
	                        $aFiles = static::MergeWithRelativeDir($aFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.struct']);
	                        $aFiles = static::MergeWithRelativeDir($aFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.sample']);
	                    }
	                    else
	                    {
	                        // Load only structural data
	                        $aFiles = static::MergeWithRelativeDir($aFiles, $sRelativePath, $aAvailableModules[$sModuleId]['data.struct']);
	                    }
	                }
	            }
	        }
	    }

	    // Simulate the load of the previously loaded files, in order to initialize
	    // the mapping between the identifiers in the XML and the actual identifiers
	    // in the current database
	    foreach($aPreviouslyLoadedFiles as $sFileRelativePath)
	    {
	        $sFileName = APPROOT.$sFileRelativePath;
		    SetupLog::Info("Loading file: $sFileName (just to get the keys mapping)");
	        if (empty($sFileName) || !file_exists($sFileName))
	        {
	            throw(new Exception("File $sFileName does not exist"));
	        }

	        $oDataLoader->LoadFile($sFileName, true);
	        $sResult = sprintf("loading of %s done.", basename($sFileName));
		    SetupLog::Info($sResult);
	    }

	    foreach($aFiles as $sFileRelativePath)
	    {
	        $sFileName = APPROOT.$sFileRelativePath;
		    SetupLog::Info("Loading file: $sFileName");
	        if (empty($sFileName) || !file_exists($sFileName))
	        {
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
	 * @param string[] $aSourceArray
	 * @param string $sBaseDir
	 * @param string[] $aFilesToMerge
	 * @return string[]
	 */
	protected static function MergeWithRelativeDir($aSourceArray, $sBaseDir, $aFilesToMerge)
	{
	    $aToMerge = array();
	    foreach($aFilesToMerge as $sFile)
	    {
	        $aToMerge[] = $sBaseDir.'/'.$sFile;
	    }
	    return array_merge($aSourceArray, $aToMerge);
	}

	/**
	 * Check the MetaModel for some common pitfall (class name too long, classes requiring too many joins...)
	 * The check takes about 900 ms for 200 classes
	 * @throws Exception
	 * @return string
	 */
    public function CheckMetaModel()
    {
        $iCount = 0;
        $fStart = microtime(true);
        foreach(MetaModel::GetClasses() as $sClass)
        {
            if (false == MetaModel::HasTable($sClass) && MetaModel::IsAbstract($sClass))
            {
                //if a class is not persisted and is abstract, the code below would crash. Needed by the class AbstractRessource. This is tolerable to skip this because we check the setup process integrity, not the datamodel integrity.
                continue;
            }

            $oSearch = new DBObjectSearch($sClass);
            $oSearch->SetShowObsoleteData(false);
            $oSQLQuery = $oSearch->GetSQLQueryStructure(null, false);
            $sViewName = MetaModel::DBGetView($sClass);
            if (strlen($sViewName) > 64)
            {
                throw new Exception("Class name too long for class: '$sClass'. The name of the corresponding view ($sViewName) would exceed MySQL's limit for the name of a table (64 characters).");
            }
            $sTableName = MetaModel::DBGetTable($sClass);
            if (strlen($sTableName) > 64)
            {
                throw new Exception("Table name too long for class: '$sClass'. The name of the corresponding MySQL table ($sTableName) would exceed MySQL's limit for the name of a table (64 characters).");
            }
            $iTableCount = $oSQLQuery->CountTables();
            if ($iTableCount > 61)
            {
                throw new Exception("Class requiring too many tables: '$sClass'. The structure of the class ($sClass) would require a query with more than 61 JOINS (MySQL's limitation).");
            }
            $iCount++;
        }
        $fDuration = microtime(true) - $fStart;

        return sprintf("Checked %d classes in %.1f ms. No error found.\n", $iCount, $fDuration*1000.0);
    }
} // End of class
