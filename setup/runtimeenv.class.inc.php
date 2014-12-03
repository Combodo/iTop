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


/**
 * Manage a runtime environment
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT."setup/modulediscovery.class.inc.php");
require_once(APPROOT.'setup/modelfactory.class.inc.php');
require_once(APPROOT.'setup/compiler.class.inc.php');
require_once(APPROOT.'core/metamodel.class.php');

define ('MODULE_ACTION_OPTIONAL', 1);
define ('MODULE_ACTION_MANDATORY', 2);
define ('MODULE_ACTION_IMPOSSIBLE', 3);
define ('ROOT_MODULE', '_Root_'); // Convention to store IN MEMORY the name/version of the root module i.e. application
define ('DATAMODEL_MODULE', 'datamodel'); // Convention to store the version of the datamodel



class RunTimeEnvironment
{
	protected $sTargetEnv;
	
	public function __construct($sEnvironment = 'production')
	{
		$this->sTargetEnv = $sEnvironment;
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
		require_once(APPROOT.'/core/log.class.inc.php');
		require_once(APPROOT.'/core/kpi.class.inc.php');
		require_once(APPROOT.'/core/coreexception.class.inc.php');
		require_once(APPROOT.'/core/dict.class.inc.php');
		require_once(APPROOT.'/core/attributedef.class.inc.php');
		require_once(APPROOT.'/core/filterdef.class.inc.php');
		require_once(APPROOT.'/core/stimulus.class.inc.php');
		require_once(APPROOT.'/core/MyHelpers.class.inc.php');
		require_once(APPROOT.'/core/expression.class.inc.php');
		require_once(APPROOT.'/core/cmdbsource.class.inc.php');
		require_once(APPROOT.'/core/sqlquery.class.inc.php');
		require_once(APPROOT.'/core/dbobject.class.php');
		require_once(APPROOT.'/core/dbobjectsearch.class.php');
		require_once(APPROOT.'/core/dbobjectset.class.php');
		require_once(APPROOT.'/application/cmdbabstract.class.inc.php');
		require_once(APPROOT.'/core/userrights.class.inc.php');
		require_once(APPROOT.'/setup/moduleinstallation.class.inc.php');
	
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
	
		MetaModel::Startup($oConfig, $bModelOnly, $bUseCache);
	}
	
	/**
	 * Analyzes the current installation and the possibilities
	 * 
	 * @param Config $oConfig Defines the target environment (DB)
	 * @param mixed $modulesPath Either a single string or an array of absolute paths
	 * @param bool  $bAbortOnMissingDependency ...
	 * @param hash $aModulesToLoad List of modules to search for, defaults to all if ommitted
	 * @return hash Array with the following format:
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
	 */     
	public function AnalyzeInstallation($oConfig, $modulesPath, $bAbortOnMissingDependency = false, $aModulesToLoad = null)
	{
		$aRes = array(
			ROOT_MODULE => array(
				'version_db' => '',
				'name_db' => '',
				'version_code' => ITOP_VERSION.'.'.ITOP_REVISION,
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
			require_once(APPROOT.'/core/cmdbsource.class.inc.php');
			CMDBSource::Init($oConfig->GetDBHost(), $oConfig->GetDBUser(), $oConfig->GetDBPwd(), $oConfig->GetDBName());
			CMDBSource::SetCharacterSet($oConfig->GetDBCharacterSet(), $oConfig->GetDBCollation());
			$aSelectInstall = CMDBSource::QueryToArray("SELECT * FROM ".$oConfig->GetDBSubname()."priv_module_install");
		}
		catch (MySQLException $e)
		{
			// No database or erroneous information
			$aSelectInstall = array();
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
		
		$aRet = array();

		// Determine the installed modules
		//
		$oSourceConfig = new Config(APPCONF.$sSourceEnv.'/'.ITOP_CONFIG_FILE);
		$oSourceEnv = new RunTimeEnvironment($sSourceEnv);
		$aAvailableModules = $oSourceEnv->AnalyzeInstallation($oSourceConfig, $aDirsToCompile);

		// Do load the required modules
		//
		$oFactory = new ModelFactory($aDirsToCompile);
		$aModules = $oFactory->FindModules();
		foreach($aModules as $foo => $oModule)
		{
			$sModule = $oModule->GetName();
			if (array_key_exists($sModule, $aAvailableModules))
			{
				if ($aAvailableModules[$sModule]['version_db'] != '')
				{
					$aRet[] = $oModule;
				}
			}
		}

		$sDeltaFile = APPROOT.'data/'.$this->sTargetEnv.'.delta.xml';
		if (file_exists($sDeltaFile))
		{
			$oDelta = new MFDeltaModule($sDeltaFile);
			$aRet[] = $oDelta;
		}

		return $aRet;
	}

	public function CompileFrom($sSourceEnv, $bUseSymLinks = false)
	{
		$oSourceConfig = new Config(utils::GetConfigFilePath($sSourceEnv));
		$sSourceDir = $oSourceConfig->Get('source_dir');

		$sSourceDirFull = APPROOT.$sSourceDir;
		// Do load the required modules
		//
		$oFactory = new ModelFactory($sSourceDirFull);
		foreach($this->GetMFModulesToCompile($sSourceEnv, $sSourceDir) as $oModule)
		{
			$sModule = $oModule->GetName();
			$oFactory->LoadModule($oModule);
			if ($oFactory->HasLoadErrors())
			{
				break;
			}
		}
		
		if ($oFactory->HasLoadErrors())
		{
			foreach($oFactory->GetLoadErrors() as $sModuleId => $aErrors)
			{
				echo "<h3>Module: ".$sModuleId."</h3>\n";
				foreach($aErrors as $oXmlError)
				{
					echo "<p>File: ".$oXmlError->file." Line:".$oXmlError->line." Message:".$oXmlError->message."</p>\n";
				}
			}
		}
		else
		{
			$oFactory->ApplyChanges();
			//$oFactory->Dump();

			$sTargetDir = APPROOT.'env-'.$this->sTargetEnv;
			self::MakeDirSafe($sTargetDir);
			$oMFCompiler = new MFCompiler($oFactory);
			$oMFCompiler->Compile($sTargetDir, null, $bUseSymLinks);
			
			require_once(APPROOT.'/core/dict.class.inc.php');
			MetaModel::ResetCache(md5(APPROOT).'-'.$this->sTargetEnv);
		}
	}

	/**
	 * Helper function to create the database structure
	 * @return boolean true on success, false otherwise
	 */
	public function CreateDatabaseStructure(Config $oConfig, $sMode)
	{
		if (strlen($oConfig->GetDBSubname()) > 0)
		{
			$this->log_info("Creating the structure in '".$oConfig->GetDBName()."' (table names prefixed by '".$oConfig->GetDBSubname()."').");
		}
		else
		{
			$this->log_info("Creating the structure in '".$oConfig->GetDBSubname()."'.");
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
				if (strlen($oConfig->GetDBSubname()) > 0)
				{
					throw new Exception("Error: found iTop tables into the database '".$oConfig->GetDBName()."' (prefix: '".$oConfig->GetDBSubname()."'). Please, try selecting another database instance or specify another prefix to prevent conflicting table names.");
				}
				else
				{
					throw new Exception("Error: found iTop tables into the database '".$oConfig->GetDBName()."'. Please, try selecting another database instance or specify a prefix to prevent conflicting table names.");
				}
			}
		}
		else
		{
			if (MetaModel::DBExists(/* bMustBeComplete */ false))
			{
				MetaModel::DBCreate(array($this, 'LogQueryCallback'));
				$this->log_ok("Database structure successfully updated.");
	
				// Check (and update only if it seems needed) the hierarchical keys
				ob_start();
				MetaModel::CheckHKeys(false /* bDiagnosticsOnly */, true /* bVerbose*/, true /* bForceUpdate */); // Since in 1.2-beta the detection was buggy, let's force the rebuilding of HKeys
				$sFeedback = ob_get_clean();
				$this->log_ok("Hierchical keys rebuilt: $sFeedback");
	
				// Check (and fix) data sync configuration
				ob_start();
				MetaModel::CheckDataSources(false /*$bDiagnostics*/, true/*$bVerbose*/);
				$sFeedback = ob_get_clean();
				$this->log_ok("Data sources checked: $sFeedback");
			}
			else
			{
				if (strlen($oConfig->GetDBSubname()) > 0)
				{
					throw new Exception("Error: No previous instance of iTop found into the database '".$oConfig->GetDBName()."' (prefix: '".$oConfig->GetDBSubname()."'). Please, try selecting another database instance.");
				}
				else
				{
					throw new Exception("Error: No previous instance of iTop found into the database '".$oConfig->GetDBName()."'. Please, try selecting another database instance.");
				}
			}
		}
		return true;
	}

	public function UpdatePredefinedObjects()
	{
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
	}
	
	public function RecordInstallation(Config $oConfig, $sDataModelVersion, $aSelectedModules, $sModulesRelativePath)
	{
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
		$oInstallRec->Set('version', ITOP_VERSION.'.'.ITOP_REVISION);
		$oInstallRec->Set('comment', "Done by the setup program\nBuilt on ".ITOP_BUILD_DATE);
		$oInstallRec->Set('parent_id', 0); // root module
		$oInstallRec->Set('installed', $iInstallationTime);
		$iMainItopRecord = $oInstallRec->DBInsertNoReload();
	
		
		// Record installed modules
		//
		$aAvailableModules = $this->AnalyzeInstallation($oConfig, APPROOT.$sModulesRelativePath);
		foreach($aSelectedModules as $sModuleId)
		{
			$aModuleData = $aAvailableModules[$sModuleId];
			$sName = $sModuleId;
			$sVersion = $aModuleData['version_code'];
			$aComments = array();
			$aComments[] = 'Done by the setup program';
			if ($aModuleData['mandatory'])
			{
				$aComments[] = 'Mandatory';
			}
			else
			{
				$aComments[] = 'Optional';
			}
			if ($aModuleData['visible'])
			{
				$aComments[] = 'Visible (during the setup)';
			}
			else
			{
				$aComments[] = 'Hidden (selected automatically)';
			}
			foreach ($aModuleData['dependencies'] as $sDependOn)
			{
				$aComments[] = "Depends on module: $sDependOn";
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
		// Database is created, installation has been tracked into it
		return true;	
	}
	
	public function GetApplicationVersion(Config $oConfig)
	{
		$aResult = false;
		try
		{
			require_once(APPROOT.'/core/cmdbsource.class.inc.php');
			CMDBSource::Init($oConfig->GetDBHost(), $oConfig->GetDBUser(), $oConfig->GetDBPwd(), $oConfig->GetDBName());
			CMDBSource::SetCharacterSet($oConfig->GetDBCharacterSet(), $oConfig->GetDBCollation());
			$sSQLQuery = "SELECT * FROM ".$oConfig->GetDBSubname()."priv_module_install";
			$aSelectInstall = CMDBSource::QueryToArray($sSQLQuery);
		}
		catch (MySQLException $e)
		{
			// No database or erroneous information
			$this->log_error('Can not connect to the database: host: '.$oConfig->GetDBHost().', user:'.$oConfig->GetDBUser().', pwd:'.$oConfig->GetDBPwd().', db name:'.$oConfig->GetDBName());
			$this->log_error('Exception '.$e->getMessage());
			return false;
		}
	
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
		return $aResult;	
	}

	public static function MakeDirSafe($sDir)
	{
		if (!is_dir($sDir))
		{
			if (!@mkdir($sDir))
			{
				throw new Exception("Failed to create directory '$sTargetPath', please check the rights of the web server");
			}
			@chmod($sDir, 0770); // RWX for owner and group, nothing for others
		}
	}

	/**
	 * Wrappers for logging into the setup log files	
	 */	
	protected function log_error($sText)
	{
		SetupPage::log_error($sText);
	}
	protected function log_warning($sText)
	{
		SetupPage::log_warning($sText);
	}
	protected function log_info($sText)
	{
		SetupPage::log_info($sText);
	}
	protected function log_ok($sText)
	{
		SetupPage::log_ok($sText);
	}
} // End of class
