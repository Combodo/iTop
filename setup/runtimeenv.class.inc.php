<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Web page used for displaying the login form
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once(APPROOT."setup/modulediscovery.class.inc.php");
require_once(APPROOT.'setup/modelfactory.class.inc.php');
require_once(APPROOT.'setup/compiler.class.inc.php');
require_once(APPROOT.'core/metamodel.class.php');

define ('MODULE_ACTION_OPTIONAL', 1);
define ('MODULE_ACTION_MANDATORY', 2);
define ('MODULE_ACTION_IMPOSSIBLE', 3);
define ('ROOT_MODULE', '_Root_'); // Convention to store IN MEMORY the name/version of the root module i.e. application

class RunTimeEnvironment
{
	protected $sTargetEnv;
	
	public function __construct($sEnvironment = 'production')
	{
		$this->sTargetEnv = $sEnvironment;
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
			MetaModel::ResetCache($this->sTargetEnv);
		}
	
		MetaModel::Startup($oConfig, $bModelOnly, $bUseCache);
	}
	
	/**
	 * Analyzes the current installation and the possibilities
	 * 
	 * @param $oConfig Config Defines the target environment (DB)
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
	public function AnalyzeInstallation($oConfig, $sModulesRelativePath)
	{
		$aRes = array(
			ROOT_MODULE => array(
				'version_db' => '',
				'name_db' => '',
				'version_code' => ITOP_VERSION.'.'.ITOP_REVISION,
				'name_code' => ITOP_APPLICATION,
			)
		);
	
		$aModules = ModuleDiscovery::GetAvailableModules(APPROOT, $sModulesRelativePath);
		foreach($aModules as $sModuleId => $aModuleInfo)
		{
			list($sModuleName, $sModuleVersion) = ModuleDiscovery::GetModuleName($sModuleId);
	
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
		foreach ($aSelectInstall as $aInstall)
		{
			//$aInstall['comment']; // unsused
			$iInstalled = strtotime($aInstall['installed']);
			$sModuleName = $aInstall['name'];
			$sModuleVersion = $aInstall['version'];
	
			if ($aInstall['parent_id'] == 0)
			{
				$sModuleName = ROOT_MODULE;
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

	protected function GetMFModulesToCompile($sSourceEnv, $sSourceDir)
	{
		$sSourceDirFull = APPROOT.$sSourceDir;
		if (!is_dir($sSourceDirFull))
		{
			throw new Exception("The source directory '$sSourceDir' does not exist (or could not be read)");
		}

		$aRet = array();

		// Determine the installed modules
		//
		$oSourceConfig = new Config(APPCONF.$sSourceEnv.'/'.ITOP_CONFIG_FILE);
		$oSourceEnv = new RunTimeEnvironment($sSourceEnv);
		$aInstalledModules = $oSourceEnv->AnalyzeInstallation($oSourceConfig, $sSourceDir);

		// Do load the required modules
		//
		$oFactory = new ModelFactory($sSourceDirFull);
		$aModules = $oFactory->FindModules();
		foreach($aModules as $foo => $oModule)
		{
			$sModule = $oModule->GetName();
			if (array_key_exists($sModule, $aInstalledModules))
			{
				$aRet[] = $oModule;
			}
		}
		return $aRet;
	}

	public function CompileFrom($sSourceEnv, $sSourceDir = 'datamodel')
	{
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
			$oMFCompiler = new MFCompiler($oFactory, $sSourceDirFull);
			$oMFCompiler->Compile($sTargetDir);

			MetaModel::ResetCache($this->sTargetEnv);
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
				MetaModel::DBCreate();
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
				MetaModel::DBCreate();
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
	
	public function RecordInstallation(Config $oConfig, $aSelectedModules, $sModulesRelativePath)
	{
		// Record main installation
		$oInstallRec = new ModuleInstallation();
		$oInstallRec->Set('name', ITOP_APPLICATION);
		$oInstallRec->Set('version', ITOP_VERSION.'.'.ITOP_REVISION);
		$oInstallRec->Set('comment', "Done by the setup program\nBuilt on ".ITOP_BUILD_DATE);
		$oInstallRec->Set('parent_id', 0); // root module
		$iMainItopRecord = $oInstallRec->DBInsertNoReload();
	
		// Record installed modules
		//
		$aAvailableModules = $this->AnalyzeInstallation($oConfig, $sModulesRelativePath);
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
			$oInstallRec->DBInsertNoReload();
		}
		// Database is created, installation has been tracked into it
		return true;	
	}

	public static function MakeDirSafe($sDir)
	{
		if (!is_dir($sDir))
		{
			@mkdir($sDir);
		}
		@chmod($sDir, 0770); // RWX for owner and group, nothing for others
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


?>
