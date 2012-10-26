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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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
				
				// Log the parameters...
				$oDoc = new DOMDocument('1.0', 'UTF-8');
				$oDoc->preserveWhiteSpace = false;
				$oDoc->formatOutput = true;
				$this->oParams->ToXML($oDoc, null, 'installation');
				$sXML = $oDoc->saveXML();
				$sSafeXml = preg_replace("|<pwd>([^<]*)</pwd>|", "<pwd>**removed**</pwd>", $sXML);
				SetupPage::log_info("======= Installation starts =======\nParameters:\n$sSafeXml\n");
				
				// Save the response file as a stand-alone file as well
				$sFileName = 'install-'.date('Y-m-d');
				$index = 0;
				while(file_exists(APPROOT.'log/'.$sFileName.'.xml'))
				{
					$index++;
					$sFileName = 'install-'.date('Y-m-d').'-'.$index;
				}
				file_put_contents(APPROOT.'log/'.$sFileName.'.xml', $sSafeXml);
				
				break;
				
				case 'copy':
				$aPreinstall = $this->oParams->Get('preinstall');
				$aCopies = $aPreinstall['copies'];

				$sReport = self::DoCopy($aCopies);
				$sReport = "Copying...";

				$aResult = array(
					'status' => self::OK,
					'message' => $sReport,
				);
				if (isset($aPreinstall['backup']))
				{
					$aResult['next-step'] = 'backup';
					$aResult['next-step-label'] = 'Performing a backup of the database';
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
				$sSourceDir = $this->oParams->Get('source_dir', 'datamodels/latest');
				$sExtensionDir = $this->oParams->Get('extensions_dir', 'extensions');
				$sTargetEnvironment = $this->oParams->Get('target_env', '');
				if ($sTargetEnvironment == '')
				{
					$sTargetEnvironment = 'production';
				}
				$sTargetDir = 'env-'.$sTargetEnvironment;
				$sWorkspaceDir = $this->oParams->Get('workspace_dir', 'workspace');
				$bUseSymbolicLinks = false;
				$aMiscOptions = $this->oParams->Get('options', array());
				if (isset($aMiscOptions['symlinks']) && $aMiscOptions['symlinks'] )
				{
					if (function_exists('symlink'))
					{
						$bUseSymbolicLinks = true;
						SetupPage::log_info("Using symbolic links instead of copying data model files (for developers only!)");
					}
					else
					{
						SetupPage::log_info("Symbolic links (function symlinks) does not seem to be supported on this platform (OS/PHP version).");
					}
				}
						
				self::DoCompile($aSelectedModules, $sSourceDir, $sExtensionDir, $sTargetDir, $sWorkspaceDir, $bUseSymbolicLinks);
				
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
				$bOldAddon = $this->oParams->Get('old_addon', false);
				
				self::DoUpdateDBSchema($sMode, $sTargetDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sTargetEnvironment, $bOldAddon);
				
				$aResult = array(
					'status' => self::OK,
					'message' => '',
					'next-step' => 'after-db-create',
					'next-step-label' => 'Creating profiles',
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
				$sDataModelVersion = $this->oParams->Get('datamodel_version', '0.0.0');
				$bOldAddon = $this->oParams->Get('old_addon', false);
				$sSourceDir = $this->oParams->Get('source_dir', '');
				
				self::AfterDBCreate($sMode, $sTargetDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sAdminUser,
									$sAdminPwd, $sAdminLanguage, $sLanguage, $aSelectedModules, $sTargetEnvironment, $bOldAddon, $sDataModelVersion, $sSourceDir);
				
				$aResult = array(
					'status' => self::OK,
					'message' => '',
					'next-step' => 'sample-data',
					'next-step-label' => 'Loading sample data',
					'percentage-completed' => 80,
				);

				$bLoadData = ($this->oParams->Get('sample_data', 0) == 1);
				if (!$bLoadData)
				{
					$aResult['next-step'] = 'create-config';
					$aResult['next-step-label'] = 'Creating the configuration File';
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
				$bOldAddon = $this->oParams->Get('old_addon', false);
				
				self::DoLoadFiles($aSelectedModules, $sTargetDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sTargetEnvironment, $bOldAddon);
				
				$aResult = array(
					'status' => self::INFO,
					'message' => 'All data loaded',
					'next-step' => 'create-config',
					'next-step-label' => 'Creating the configuration File',
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
				$bOldAddon = $this->oParams->Get('old_addon', false);
				$sSourceDir = $this->oParams->Get('source_dir', '');
				$sPreviousConfigFile = $this->oParams->Get('previous_configuration_file', '');
				
				self::DoCreateConfig($sMode, $sTargetDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sUrl, $sLanguage, $aSelectedModules, $sTargetEnvironment, $bOldAddon, $sSourceDir, $sPreviousConfigFile);
				
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
			
			SetupPage::log_error('An exception occurred: '.$e->getMessage());
			SetupPage::log("Stack trace:\n".$e->getTraceAsString());
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

	
	protected static function DoCompile($aSelectedModules, $sSourceDir, $sExtensionDir, $sTargetDir, $sWorkspaceDir = '', $bUseSymbolicLinks = false)
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
		$aDirsToScan = array($sSourcePath);
		$sExtensionsPath = APPROOT.$sExtensionDir;
		if (is_dir($sExtensionsPath))
		{
			// if the extensions dir exists, scan it for additional modules as well
			$aDirsToScan[] = $sExtensionsPath;
		}
		$sTargetPath = APPROOT.$sTargetDir;
		if (!is_dir($sSourcePath))
		{
			throw new Exception("Failed to find the source directory '$sSourcePath', please check the rights of the web server");
		}		
		if (!is_dir($sTargetPath))
		{
			if (!mkdir($sTargetPath))
			{
				throw new Exception("Failed to create directory '$sTargetPath', please check the rights of the web server");
			}
			else
			{
				// adjust the rights if and only if the directory was just created
				// owner:rwx user/group:rx
				chmod($sTargetPath, 0755);
			}
		}
		else if (substr($sTargetPath, 0, strlen(APPROOT)) == APPROOT)
		{
			// If the directory is under the root folder - as expected - let's clean-it before compiling
			SetupUtils::tidydir($sTargetPath);
		}

		$oFactory = new ModelFactory($aDirsToScan);
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
			$oMFCompiler = new MFCompiler($oFactory);
			$oMFCompiler->Compile($sTargetPath, null, $bUseSymbolicLinks);
			SetupPage::log_info("Data model successfully compiled to '$sTargetPath'.");
		}
		
		// Special case to patch a ugly patch in itop-config-mgmt
		$sFileToPatch = $sTargetPath.'/itop-config-mgmt-1.0.0/model.itop-config-mgmt.php';
		if (file_exists($sFileToPatch))
		{
			$sContent = file_get_contents($sFileToPatch);
			
			$sContent = str_replace("require_once(APPROOT.'modules/itop-welcome-itil/model.itop-welcome-itil.php');", "//\n// The line below is no longer needed in iTop 2.0 -- patched by the setup program\n// require_once(APPROOT.'modules/itop-welcome-itil/model.itop-welcome-itil.php');", $sContent);
			
			file_put_contents($sFileToPatch, $sContent);
		}
	}
	
	protected static function DoUpdateDBSchema($sMode, $sModulesDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sTargetEnvironment  = '', $bOldAddon = false)
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
		if ($bOldAddon)
		{
			// Old version of the add-on for backward compatibility with pre-2.0 data models
			$oConfig->SetAddons(array(
				'user rights' => 'addons/userrights/userrightsprofile.db.class.inc.php',
			));
		}
		
		$oProductionEnv = new RunTimeEnvironment($sTargetEnvironment);
		$oProductionEnv->InitDataModel($oConfig, true);  // load data model only

		if(!$oProductionEnv->CreateDatabaseStructure(MetaModel::GetConfig(), $sMode))
		{
			throw new Exception("Failed to create/upgrade the database structure for environment '$sTargetEnvironment'");		
		}
		SetupPage::log_info("Database Schema Successfully Updated for environment '$sTargetEnvironment'.");
	}
	
	protected static function AfterDBCreate($sMode, $sModulesDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sAdminUser, $sAdminPwd, $sAdminLanguage, $sLanguage, $aSelectedModules, $sTargetEnvironment, $bOldAddon, $sDataModelVersion, $sSourceDir)
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
		if ($bOldAddon)
		{
			// Old version of the add-on for backward compatibility with pre-2.0 data models
			$oConfig->SetAddons(array(
				'user rights' => 'addons/userrights/userrightsprofile.db.class.inc.php',
			));
		}
		$oConfig->Set('source_dir', $sSourceDir); // Needed by RecordInstallation below
		
		$oProductionEnv = new RunTimeEnvironment($sTargetEnvironment);
		$oProductionEnv->InitDataModel($oConfig, true);  // load data model and connect to the database
		
		self::$bMetaModelStarted = true; // No need to reload the final MetaModel in case the installer runs synchronously 
		
		// Perform here additional DB setup... profiles, etc...
		//
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation(MetaModel::GetConfig(), APPROOT.$sModulesDir);
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
				SetupPage::log_info("$sClass::GetPredefinedObjects() returned ".count($aPredefinedObjects)." elements.");

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
					if (!array_key_exists($iRefId, $aDBIds))
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

		if (!$oProductionEnv->RecordInstallation($oConfig, $sDataModelVersion, $aSelectedModules, $sModulesDir))
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
	
	protected static function DoLoadFiles($aSelectedModules, $sModulesDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sTargetEnvironment = '', $bOldAddon = false)
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
		if ($bOldAddon)
		{
			// Old version of the add-on for backward compatibility with pre-2.0 data models
			$oConfig->SetAddons(array(
				'user rights' => 'addons/userrights/userrightsprofile.db.class.inc.php',
			));
		}
		
		//Load the MetaModel if needed (asynchronous mode)
		if (!self::$bMetaModelStarted)
		{
			$oProductionEnv = new RunTimeEnvironment($sTargetEnvironment);
			$oProductionEnv->InitDataModel($oConfig, false);  // load data model and connect to the database
			self::$bMetaModelStarted = true; // No need to reload the final MetaModel in case the installer runs synchronously
		} 
		
		
		$oDataLoader = new XMLDataLoader(); 

		CMDBObject::SetTrackInfo("Initialization");
		$oMyChange = CMDBObject::GetCurrentChange();

		SetupPage::log_info("starting data load session");
		$oDataLoader->StartSession($oMyChange);

		$aFiles = array();		
		$oProductionEnv = new RunTimeEnvironment();
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation($oConfig, APPROOT.$sModulesDir);
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
	
	protected static function DoCreateConfig($sMode, $sModulesDir, $sDBServer, $sDBUser, $sDBPwd, $sDBName, $sDBPrefix, $sUrl, $sLanguage, $aSelectedModules, $sTargetEnvironment, $bOldAddon, $sSourceDir, $sPreviousConfigFile)
	{	
		$aParamValues = array(
			'db_server' => $sDBServer,
			'db_user' => $sDBUser,
			'db_pwd' => $sDBPwd,
			'db_name' => $sDBName,
			'new_db_name' => $sDBName,
			'db_prefix' => $sDBPrefix,
			'application_path' => $sUrl,
			'language' => $sLanguage,
			'selected_modules' => implode(',', $aSelectedModules), 
		);
		
		$bPreserveModuleSettings = false;
		if ($sMode == 'upgrade')
		{
			try 
			{
				$oOldConfig = new Config($sPreviousConfigFile);
				$oConfig = clone($oOldConfig);
				$bPreserveModuleSettings = true;
			}
			catch(Exception $e)
			{
				// In case the previous configuration is corrupted... start with a blank new one
				$oConfig = new Config();
			}
		}
		else
		{
			$oConfig = new Config();
		}
		
		
		// Migration: force utf8_unicode_ci as the collation to make the global search
		// NON case sensitive
		$oConfig->SetDBCollation('utf8_unicode_ci');
		
		// Final config update: add the modules
		$oConfig->UpdateFromParams($aParamValues, $sModulesDir, $bPreserveModuleSettings);
		if ($bOldAddon)
		{
			// Old version of the add-on for backward compatibility with pre-2.0 data models
			$oConfig->SetAddons(array(
				'user rights' => 'addons/userrights/userrightsprofile.db.class.inc.php',
			));
		}
		$oConfig->Set('source_dir', $sSourceDir);
		
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

		// Ready to go !!
		require_once(APPROOT.'core/dict.class.inc.php');
		MetaModel::ResetCache();
	}
}
