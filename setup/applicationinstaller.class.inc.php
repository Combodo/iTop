<?php
// Copyright (C) 2012 Combodo SARL
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

require_once(APPROOT.'setup/parameters.class.inc.php');
require_once(APPROOT.'setup/xmldataloader.class.inc.php');
require_once(APPROOT.'setup/backup.class.inc.php');

/**
 * The base class for the installation process.
 * The installation process is split into a sequence of unitary steps
 * for performance reasons (i.e; timeout, memory usage) and also in order
 * to provide some feedback about the progress of the installation.
 * 
 * This class can be used for a step by step interactive installation
 * while displaying a progress bar, or in an unattended manner
 * (for example from the command line), to run all the steps
 * in one go.
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html GPL
 */

class ApplicationInstaller
{
	const OK = 1;
	const ERROR = 2;
	const WARNING = 3;
	const INFO = 4;
	
	protected $oParams;
	protected static $bMetaModelStarted = false;
	
	public function __construct($oParams)
	{
		$this->oParams = $oParams;
	}
	
	/**
	 * Runs all the installation steps in one go and directly outputs
	 * some information about the progress and the success of the various
	 * sequential steps.
	 * @return boolean True if the installation was successful, false otherwise
	 */
	public function ExecuteAllSteps()
	{
		$sStep = '';
		$sStepLabel = '';
		do
		{
			if($sStep != '')
			{
				echo "$sStepLabel\n";
				echo "Executing '$sStep'\n";
			}
			else
			{
				echo "Starting the installation...\n";
			}
			$aRes = $this->ExecuteStep($sStep);
			$sStep = $aRes['next-step'];
			$sStepLabel = $aRes['next-step-label'];
			
			switch($aRes['status'])
			{
				case self::OK;
				echo "Ok. ".$aRes['percentage-completed']." % done.\n";
				break;
				
				case self::ERROR:
				echo "Error: ".$aRes['message']."\n";
				break;
				
				case self::WARNING:
				echo "Warning: ".$aRes['message']."\n";
				echo $aRes['percentage-completed']." % done.\n";
				break;
					
				case self::INFO:
				echo "Info: ".$aRes['message']."\n";
				echo $aRes['percentage-completed']." % done.\n";
				break;
			}
		}
		while(($aRes['status'] != self::ERROR) && ($aRes['next-step'] != ''));
		
		return ($aRes['status'] == self::OK);
	}
	
	/**
	 * Executes the next step of the installation and reports about the progress
	 * and the next step to perform
	 * @param string $sStep The identifier of the step to execute
	 * @return hash An array of (status => , message => , percentage-completed => , next-step => , next-step-label => )
	 */
	public function ExecuteStep($sStep = '')
	{
		try
		{
			switch($sStep)
			{
				case '':	
				$aResult = array(
					'status' => self::OK,
					'message' => '',
					'percentage-completed' => 0,
					'next-step' => 'copy',
					'next-step-label' => 'Copying data model files',
				);
				break;
				
				case 'copy':
				$aPreinstall = $this->oParams->Get('preinstall');
				$aCopies = $aPreinstall['copies'];

				// disabled - $sReport = self::DoCopy($aCopies);
				$sReport = "copy disabled...";

				$aResult = array(
					'status' => self::OK,
					'message' => $sReport,
				);
				if (isset($aPreinstall['backup']))
				{
					$aResult['next-step'] = 'backup';
					$aResult['next-step-label'] = 'Backuping the database';
					$aResult['percentage-completed'] = 20;
				}
				else
				{
					$aResult['next-step'] = 'compile';
					$aResult['next-step-label'] = 'Compiling the data model';
					$aResult['percentage-completed'] = 20;
				}
				break;
				
				case 'backup':
				$aPreinstall = $this->oParams->Get('preinstall');
				// __DB__-%Y-%m-%d.zip
				$sDestination = $aPreinstall['backup']['destination'];
				$sSourceConfigFile = $aPreinstall['backup']['configuration_file'];
				$aDBParams = $this->oParams->Get('database');

				self::DoBackup($aDBParams['server'], $aDBParams['user'], $aDBParams['pwd'], $aDBParams['name'], $aDBParams['prefix'], $sDestination, $sSourceConfigFile);

				$aResult = array(
					'status' => self::OK,
					'message' => "Created backup",
					'next-step' => 'compile',
					'next-step-label' => 'Compiling the data model',
					'percentage-completed' => 20,
				);
				break;
				
				case 'compile':
				$aSelectedModules = $this->oParams->Get('selected_modules');
				$sSourceDir = $this->oParams->Get('source_dir', 'datamodel');
				$sTargetEnvironment = $this->oParams->Get('target_env', '');
				if ($sTargetEnvironment == '')
				{
					$sTargetEnvironment = 'production';
				}
				$sTargetDir = 'env-'.$sTargetEnvironment;
				$sWorkspaceDir = $this->oParams->Get('workspace_dir', 'workspace');
						
				self::DoCompile($aSelectedModules, $sSourceDir, $sTargetDir, $sWorkspaceDir);
				
				$aResult = array(
					'status' => self::OK,
					'message' => '',
					'next-step' => 'db-schema',
					'next-step-label' => 'Updating database schema',
					'percentage-completed' => 40,
				);
				break;
				
				case 'db-schema':
				$sMode = $this->oParams->Get('mode');
				$sTargetEnvironment = $this->oParams->Get('target_env', '');
				if ($sTargetEnvironment == '')
				{
					$sTargetEnvironment = 'production';
				}
				$sTargetDir = 'env-'.$sTargetEnvironment;
				$aDBParams = $this->oParams->Get('database');
				$sDBServer = $aDBParams['server'];
				$sDBUser = $aDBParams['user'];
				$sDBPwd = $aDBParams['pwd'];
				$sDBName = $aDBParams['name'];
				$sDBPrefix = $aDBParams['prefix'];
				
				self::DoUpdateDBSchema($sMode, $sTargetDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sTargetEnvironment);
				
				$aResult = array(
					'status' => self::OK,
					'message' => '',
					'next-step' => 'after-db-create',
					'next-step-label' => 'Creating Profiles',
					'percentage-completed' => 60,
				);
				break;
				
				case 'after-db-create':
				$sMode = $this->oParams->Get('mode');
				$sTargetEnvironment = $this->oParams->Get('target_env', '');
				if ($sTargetEnvironment == '')
				{
					$sTargetEnvironment = 'production';
				}
				$sTargetDir = 'env-'.$sTargetEnvironment;
				$aDBParams = $this->oParams->Get('database');
				$sDBServer = $aDBParams['server'];
				$sDBUser = $aDBParams['user'];
				$sDBPwd = $aDBParams['pwd'];
				$sDBName = $aDBParams['name'];
				$sDBPrefix = $aDBParams['prefix'];
				$aAdminParams = $this->oParams->Get('admin_account');
				$sAdminUser = $aAdminParams['user'];
				$sAdminPwd = $aAdminParams['pwd'];
				$sAdminLanguage = $aAdminParams['language'];
				$sLanguage = $this->oParams->Get('language');
				$aSelectedModules = $this->oParams->Get('selected_modules', array());
				
				self::AfterDBCreate($sMode, $sTargetDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sAdminUser, $sAdminPwd, $sAdminLanguage, $sLanguage, $aSelectedModules, $sTargetEnvironment);
				
				$aResult = array(
					'status' => self::OK,
					'message' => '',
					'next-step' => 'sample-data',
					'next-step-label' => 'Loading Sample Data',
					'percentage-completed' => 80,
				);

				$bLoadData = ($this->oParams->Get('sample_data', 0) == 1);
				if (!$bLoadData)
				{
					$aResult['next-step'] = 'create-config';
					$aResult['next-step-label'] = 'Creating the Configuration File';
				}
				break;
				
				case 'sample-data':
				$aSelectedModules = $this->oParams->Get('selected_modules');
				$sTargetEnvironment = $this->oParams->Get('target_env', '');
				$sTargetDir = 'env-'.(($sTargetEnvironment == '') ? 'production' : $sTargetEnvironment);
				$aDBParams = $this->oParams->Get('database');
				$sDBServer = $aDBParams['server'];
				$sDBUser = $aDBParams['user'];
				$sDBPwd = $aDBParams['pwd'];
				$sDBName = $aDBParams['name'];
				$sDBPrefix = $aDBParams['prefix'];
				$aFiles = $this->oParams->Get('files', array());
				
				self::DoLoadFiles($aSelectedModules, $sTargetDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sTargetEnvironment);
				
				$aResult = array(
					'status' => self::INFO,
					'message' => 'All data loaded',
					'next-step' => 'create-config',
					'next-step-label' => 'Creating the Configuration File',
					'percentage-completed' => 99,
				);
				break;
				
				case 'create-config':
				$sMode = $this->oParams->Get('mode');
				$sTargetEnvironment = $this->oParams->Get('target_env', '');
				if ($sTargetEnvironment == '')
				{
					$sTargetEnvironment = 'production';
				}
				$sTargetDir = 'env-'.$sTargetEnvironment;
				$aDBParams = $this->oParams->Get('database');
				$sDBServer = $aDBParams['server'];
				$sDBUser = $aDBParams['user'];
				$sDBPwd = $aDBParams['pwd'];
				$sDBName = $aDBParams['name'];
				$sDBPrefix = $aDBParams['prefix'];
				$sUrl = $this->oParams->Get('url', '');
				$sLanguage = $this->oParams->Get('language', '');
				$aSelectedModules = $this->oParams->Get('selected_modules', array());
				
				self::DoCreateConfig($sMode, $sTargetDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sUrl, $sLanguage, $aSelectedModules, $sTargetEnvironment);
				
				$aResult = array(
					'status' => self::INFO,
					'message' => 'Configuration file created',
					'next-step' => '',
					'next-step-label' => 'Completed',
					'percentage-completed' => 100,
				);
				break;
				
				
				default:
				$aResult = array(
					'status' => self::ERROR,
					'message' => '',
					'next-step' => '',
					'next-step-label' => "Unknown setup step '$sStep'.",
					'percentage-completed' => 100,
				);
			}
		}
		catch(Exception $e)
		{
			$aResult = array(
				'status' => self::ERROR,
				'message' => $e->getMessage(),
				'next-step' => '',
				'next-step-label' => '',
				'percentage-completed' => 100,
			);
		}
		return $aResult;
	}

	protected static function DoCopy($aCopies)
	{
		$aReports = array();
		foreach ($aCopies as $aCopy)
		{
			$sSource = $aCopy['source'];
			$sDestination = APPROOT.$aCopy['destination'];
			
			SetupUtils::builddir($sDestination);
			SetupUtils::tidydir($sDestination);
			SetupUtils::copydir($sSource, $sDestination);
			$aReports[] = "'{$aCopy['source']}' to '{$aCopy['destination']}' (OK)";
		}
		if (count($aReports) > 0)
		{
			$sReport = "Copies: ".count($aReports).': '.implode('; ', $aReports);
		}
		else
		{
			$sReport = "No file copy";
		}
		return $sReport;
	}

	protected static function DoBackup($sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sBackupFile, $sSourceConfigFile)
	{
		$oBackup = new DBBackup($sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix);
		$sZipFile = $oBackup->MakeName($sBackupFile);
		$oBackup->CreateZip($sZipFile, $sSourceConfigFile);
	}

	
	protected static function DoCompile($aSelectedModules, $sSourceDir, $sTargetDir, $sWorkspaceDir = '')
	{
		SetupPage::log_info("Compiling data model.");

		require_once(APPROOT.'setup/modulediscovery.class.inc.php');
		require_once(APPROOT.'setup/modelfactory.class.inc.php');
		require_once(APPROOT.'setup/compiler.class.inc.php');

		if (empty($sSourceDir) || empty($sTargetDir))
		{
			throw new Exception("missing parameter source_dir and/or target_dir");
		}		

		$sSourcePath = APPROOT.$sSourceDir;
		$sTargetPath = APPROOT.$sTargetDir;
		if (!is_dir($sSourcePath))
		{
			throw new Exception("Failed to find the source directory '$sSourcePath', please check the rights of the web server");
		}		
		if (!is_dir($sTargetPath) && !mkdir($sTargetPath))
		{
			throw new Exception("Failed to create directory '$sTargetPath', please check the rights of the web server");
		}		
		// owner:rwx user/group:rx
		chmod($sTargetPath, 0755);

		$oFactory = new ModelFactory($sSourcePath);
		$aModules = $oFactory->FindModules();

		foreach($aModules as $foo => $oModule)
		{
			$sModule = $oModule->GetName();
			if (in_array($sModule, $aSelectedModules))
			{
				$oFactory->LoadModule($oModule);
			}
		}
		if (strlen($sWorkspaceDir) > 0)
		{
			$oWorkspace = new MFWorkspace(APPROOT.$sWorkspaceDir);
			if (file_exists($oWorkspace->GetWorkspacePath()))
			{
				$oFactory->LoadModule($oWorkspace);
			}
		}
		//$oFactory->Dump();
		if ($oFactory->HasLoadErrors())
		{
			foreach($oFactory->GetLoadErrors() as $sModuleId => $aErrors)
			{
				SetupPage::log_error("Data model source file (xml) could not be loaded - found errors in module: $sModuleId");
				foreach($aErrors as $oXmlError)
				{
					SetupPage::log_error("Load error: File: ".$oXmlError->file." Line:".$oXmlError->line." Message:".$oXmlError->message);
				}
			}
			throw new Exception("The data model could not be compiled. Please check the setup error log");
		}
		else
		{
			$oMFCompiler = new MFCompiler($oFactory, $sSourcePath);
			$oMFCompiler->Compile($sTargetPath);
			SetupPage::log_info("Data model successfully compiled to '$sTargetPath'.");
		}
	}
	
	protected static function DoUpdateDBSchema($sMode, $sModulesDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sTargetEnvironment  = '')
	{
		SetupPage::log_info("Update Database Schema for environment '$sTargetEnvironment'.");

		$oConfig = new Config();

		$aParamValues = array(
			'db_server' => $sDBServer,
			'db_user' => $sDBUser,
			'db_pwd' => $sDBPwd,
			'db_name' => $sDBName,
			'db_prefix' => $sDBPrefix,
		);
		$oConfig->UpdateFromParams($aParamValues, $sModulesDir);

		$oProductionEnv = new RunTimeEnvironment($sTargetEnvironment);
		$oProductionEnv->InitDataModel($oConfig, true);  // load data model only

		if(!$oProductionEnv->CreateDatabaseStructure(MetaModel::GetConfig(), $sMode))
		{
			throw new Exception("Failed to create/upgrade the database structure for environment '$sTargetEnvironment'");		
		}
		SetupPage::log_info("Database Schema Successfully Updated for environment '$sTargetEnvironment'.");
	}
	
	protected static function AfterDBCreate($sMode, $sModulesDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sAdminUser, $sAdminPwd, $sAdminLanguage, $sLanguage, $aSelectedModules, $sTargetEnvironment  = '')
	{

		SetupPage::log_info('After Database Creation');

		$oConfig = new Config();

		$aParamValues = array(
			'db_server' => $sDBServer,
			'db_user' => $sDBUser,
			'db_pwd' => $sDBPwd,
			'db_name' => $sDBName,
			'db_prefix' => $sDBPrefix,
		);
		$oConfig->UpdateFromParams($aParamValues, $sModulesDir);

		$oProductionEnv = new RunTimeEnvironment($sTargetEnvironment);
		$oProductionEnv->InitDataModel($oConfig, false);  // load data model and connect to the database
		self::$bMetaModelStarted = true; // No need to reload the final MetaModel in case the installer runs synchronously 
		
		// Perform here additional DB setup... profiles, etc...
		//
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation(MetaModel::GetConfig(), $sModulesDir);
		foreach($aAvailableModules as $sModuleId => $aModule)
		{
			if (($sModuleId != ROOT_MODULE) && in_array($sModuleId, $aSelectedModules) &&
			     isset($aAvailableModules[$sModuleId]['installer']) )
			{
				$sModuleInstallerClass = $aAvailableModules[$sModuleId]['installer'];
				SetupPage::log_info("Calling Module Handler: $sModuleInstallerClass::AfterDatabaseCreation(oConfig, {$aModule['version_db']}, {$aModule['version_code']})");
				// The validity of the sModuleInstallerClass has been established in BuildConfig() 
				$aCallSpec = array($sModuleInstallerClass, 'AfterDatabaseCreation');
				call_user_func_array($aCallSpec, array(MetaModel::GetConfig(), $aModule['version_db'], $aModule['version_code']));								
			}
		}

		// Constant classes (e.g. User profiles)
		//
		foreach (MetaModel::GetClasses() as $sClass)
		{
			$aPredefinedObjects = call_user_func(array($sClass, 'GetPredefinedObjects'));
			if ($aPredefinedObjects != null)
			{
				// Temporary... until this get really encapsulated as the default and transparent behavior
				$oMyChange = MetaModel::NewObject("CMDBChange");
				$oMyChange->Set("date", time());
				$sUserString = CMDBChange::GetCurrentUserName();
				$oMyChange->Set("userinfo", $sUserString);
				$iChangeId = $oMyChange->DBInsert();

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
						$oObj->DBUpdateTracked($oMyChange);
						$aDBIds[$oObj->GetKey()] = true;
					}
					else
					{
						$oObj->DBDeleteTracked($oMyChange);
					}
				}
				foreach ($aPredefinedObjects as $iRefId => $aObjValues)
				{
					if (!array_key_exists($iRefId, $aDBIds))
					{
						$oNewObj = MetaModel::NewObject($sClass);
						$oNewObj->SetKey($iRefId);
						foreach ($aObjValues as $sAttCode => $value)
						{
							$oNewObj->Set($sAttCode, $value);
						}
						$oNewObj->DBInsertTracked($oMyChange);
					}
				}
			}
		}

		if (!$oProductionEnv->RecordInstallation($oConfig, $aSelectedModules, $sModulesDir))
		{
			throw new Exception("Failed to record the installation information");
		}
		
		if($sMode == 'install')
		{
			if (!self::CreateAdminAccount(MetaModel::GetConfig(), $sAdminUser, $sAdminPwd, $sAdminLanguage))
			{
				throw(new Exception("Failed to create the administrator account '$sAdminUser'"));
			}
			else
			{
				SetupPage::log_info("Administrator account '$sAdminUser' created.");
			}
		}
	}
	
	/**
	 * Helper function to create and administrator account for iTop
	 * @return boolean true on success, false otherwise 
	 */
	protected static function CreateAdminAccount(Config $oConfig, $sAdminUser, $sAdminPwd, $sLanguage)
	{
		SetupPage::log_info('CreateAdminAccount');
	
		if (UserRights::CreateAdministrator($sAdminUser, $sAdminPwd, $sLanguage))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	protected static function DoLoadFiles($aSelectedModules, $sModulesDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sTargetEnvironment = '')
	{
		$aParamValues = array(
			'db_server' => $sDBServer,
			'db_user' => $sDBUser,
			'db_pwd' => $sDBPwd,
			'db_name' => $sDBName,
			'new_db_name' => $sDBName,
			'db_prefix' => $sDBPrefix,
		);
		$oConfig = new Config();

		$oConfig->UpdateFromParams($aParamValues, $sModulesDir);

		//Load the MetaModel if needed (asynchronous mode)
		if (!self::$bMetaModelStarted)
		{
			$oProductionEnv = new RunTimeEnvironment($sTargetEnvironment);
			$oProductionEnv->InitDataModel($oConfig, false);  // load data model and connect to the database
			self::$bMetaModelStarted = true; // No need to reload the final MetaModel in case the installer runs synchronously
		} 
		
		
		$oDataLoader = new XMLDataLoader(); 
		$oChange = MetaModel::NewObject("CMDBChange");
		$oChange->Set("date", time());
		$oChange->Set("userinfo", "Initialization");
		$iChangeId = $oChange->DBInsert();
		SetupPage::log_info("starting data load session");
		$oDataLoader->StartSession($oChange);

		$aFiles = array();		
		$oProductionEnv = new RunTimeEnvironment();
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation($oConfig, $sModulesDir);
		foreach($aAvailableModules as $sModuleId => $aModule)
		{
			if (($sModuleId != ROOT_MODULE))
			{
				if (in_array($sModuleId, $aSelectedModules))
				{
					$aFiles = array_merge(
						$aFiles,
						$aAvailableModules[$sModuleId]['data.struct'],
						$aAvailableModules[$sModuleId]['data.sample']
					);
				}
			}
		}

		foreach($aFiles as $sFileRelativePath)
		{
			$sFileName = APPROOT.$sFileRelativePath;
			SetupPage::log_info("Loading file: $sFileName");
			if (empty($sFileName) || !file_exists($sFileName))
			{
				throw(new Exception("File $sFileName does not exist"));
			}
		
			$oDataLoader->LoadFile($sFileName);
			$sResult = sprintf("loading of %s done.", basename($sFileName));
			SetupPage::log_info($sResult);
		}
	
	    $oDataLoader->EndSession();
	    SetupPage::log_info("ending data load session");
	}
	
	protected static function DoCreateConfig($sMode, $sModulesDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sUrl, $sLanguage, $aSelectedModules, $sTargetEnvironment  = '')
	{	
		$aParamValues = array(
			'db_server' => $sDBServer,
			'db_user' => $sDBUser,
			'db_pwd' => $sDBPwd,
			'db_name' => $sDBName,
			'new_db_name' => $sDBName,
			'db_prefix' => $sDBPrefix,
			'application_path' => $sUrl,
			'mode' => $sMode,
			'language' => $sLanguage,
			'selected_modules' => implode(',', $aSelectedModules), 
		);
		
		$oConfig = new Config();
		
		// Migration: force utf8_unicode_ci as the collation to make the global search
		// NON case sensitive
		$oConfig->SetDBCollation('utf8_unicode_ci');
		
		// Final config update: add the modules
		$oConfig->UpdateFromParams($aParamValues, $sModulesDir);

		// Make sure the root configuration directory exists
		if (!file_exists(APPCONF))
		{
			mkdir(APPCONF);
			chmod(APPCONF, 0770); // RWX for owner and group, nothing for others
			SetupPage::log_info("Created configuration directory: ".APPCONF);		
		}

		// Write the final configuration file
		$sConfigFile = APPCONF.(($sTargetEnvironment == '') ? 'production' : $sTargetEnvironment).'/'.ITOP_CONFIG_FILE;
		$sConfigDir = dirname($sConfigFile);
		@mkdir($sConfigDir);
		@chmod($sConfigDir, 0770); // RWX for owner and group, nothing for others

		$oConfig->WriteToFile($sConfigFile);
			
		// try to make the final config file read-only
		@chmod($sConfigFile, 0444); // Read-only for owner and group, nothing for others
	}
}
