<?php
// Copyright (C) 2010-2017 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class ApplicationInstaller
{
	const OK = 1;
	const ERROR = 2;
	const WARNING = 3;
	const INFO = 4;

	/** @var \PHPParameters */
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
		$iOverallStatus = self::OK;
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
				$iOverallStatus = self::ERROR;
				echo "Error: ".$aRes['message']."\n";
				break;
				
				case self::WARNING:
				$iOverallStatus = self::WARNING;
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
		
		return ($iOverallStatus == self::OK);
	}
	
	/**
	 * Executes the next step of the installation and reports about the progress
	 * and the next step to perform
	 *
	 * @param string $sStep The identifier of the step to execute
	 *
	 * @return array (status => , message => , percentage-completed => , next-step => , next-step-label => )
	 */
	public function ExecuteStep($sStep = '')
	{
		try
		{
			$fStart = microtime(true);
			switch ($sStep)
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
					while (file_exists(APPROOT.'log/'.$sFileName.'.xml'))
					{
						$index++;
						$sFileName = 'install-'.date('Y-m-d').'-'.$index;
					}
					file_put_contents(APPROOT.'log/'.$sFileName.'.xml', $sSafeXml);

					break;

				case 'copy':
					$aPreinstall = $this->oParams->Get('preinstall');
					$aCopies = $aPreinstall['copies'];

					self::DoCopy($aCopies);
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
					// __DB__-%Y-%m-%d
					$sDestination = $aPreinstall['backup']['destination'];
					$sSourceConfigFile = $aPreinstall['backup']['configuration_file'];
					$aDBParams = $this->GetParamValues($this->oParams);
					$oTempConfig = new Config();
					$oTempConfig->UpdateFromParams($aDBParams);
					$sMySQLBinDir = $this->oParams->Get('mysql_bindir', null);
					self::DoBackup($oTempConfig, $sDestination, $sSourceConfigFile, $sMySQLBinDir);

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
					$bUseSymbolicLinks = false;
					$aMiscOptions = $this->oParams->Get('options', array());
					if (isset($aMiscOptions['symlinks']) && $aMiscOptions['symlinks'])
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

					self::DoCompile($aSelectedModules, $sSourceDir, $sExtensionDir, $sTargetDir, $sTargetEnvironment,
						$bUseSymbolicLinks);

					$aResult = array(
						'status' => self::OK,
						'message' => '',
						'next-step' => 'db-schema',
						'next-step-label' => 'Updating database schema',
						'percentage-completed' => 40,
					);
					break;

				case 'db-schema':
					$aSelectedModules = $this->oParams->Get('selected_modules', array());
					$sTargetEnvironment = $this->oParams->Get('target_env', '');
					if ($sTargetEnvironment == '')
					{
						$sTargetEnvironment = 'production';
					}
					$sTargetDir = 'env-'.$sTargetEnvironment;
					$aParamValues = $this->GetParamValues($this->oParams);
					$bOldAddon = $this->oParams->Get('old_addon', false);
					$sUrl = $this->oParams->Get('url', '');

					self::DoUpdateDBSchema($aSelectedModules, $sTargetDir, $aParamValues, $sTargetEnvironment,
						$bOldAddon, $sUrl);

					$aResult = array(
						'status' => self::OK,
						'message' => '',
						'next-step' => 'after-db-create',
						'next-step-label' => 'Creating profiles',
						'percentage-completed' => 60,
					);
					break;

				case 'after-db-create':
					$sTargetEnvironment = $this->oParams->Get('target_env', '');
					if ($sTargetEnvironment == '')
					{
						$sTargetEnvironment = 'production';
					}
					$sTargetDir = 'env-'.$sTargetEnvironment;
					$aParamValues = $this->GetParamValues($this->oParams);
					$aAdminParams = $this->oParams->Get('admin_account');
					$sAdminUser = $aAdminParams['user'];
					$sAdminPwd = $aAdminParams['pwd'];
					$sAdminLanguage = $aAdminParams['language'];
					$aSelectedModules = $this->oParams->Get('selected_modules', array());
					$bOldAddon = $this->oParams->Get('old_addon', false);

					self::AfterDBCreate($sTargetDir, $aParamValues, $sAdminUser, $sAdminPwd, $sAdminLanguage,
						$aSelectedModules, $sTargetEnvironment, $bOldAddon);

					$aResult = array(
						'status' => self::OK,
						'message' => '',
						'next-step' => 'load-data',
						'next-step-label' => 'Loading data',
						'percentage-completed' => 80,
					);
					break;

				case 'load-data':
					$aSelectedModules = $this->oParams->Get('selected_modules');
					$sTargetEnvironment = $this->oParams->Get('target_env', '');
					$sTargetDir = 'env-'.(($sTargetEnvironment == '') ? 'production' : $sTargetEnvironment);
					$aParamValues = $this->GetParamValues($this->oParams);
					$bOldAddon = $this->oParams->Get('old_addon', false);
					$bSampleData = ($this->oParams->Get('sample_data', 0) == 1);

					self::DoLoadFiles($aSelectedModules, $sTargetDir, $aParamValues, $sTargetEnvironment, $bOldAddon,
						$bSampleData);

					$aResult = array(
						'status' => self::INFO,
						'message' => 'All data loaded',
						'next-step' => 'create-config',
						'next-step-label' => 'Creating the configuration File',
						'percentage-completed' => 99,
					);
					break;

				case 'create-config':
					$sTargetEnvironment = $this->oParams->Get('target_env', '');
					if ($sTargetEnvironment == '')
					{
						$sTargetEnvironment = 'production';
					}

					$sTargetDir = 'env-'.$sTargetEnvironment;
					$sPreviousConfigFile = $this->oParams->Get('previous_configuration_file', '');
					$sDataModelVersion = $this->oParams->Get('datamodel_version', '0.0.0');
					$bOldAddon = $this->oParams->Get('old_addon', false);
					$aSelectedModuleCodes = $this->oParams->Get('selected_modules', array());
					$aSelectedExtensionCodes = $this->oParams->Get('selected_extensions', array());
					$aParamValues = $this->GetParamValues($this->oParams);

					self::DoCreateConfig($sTargetDir, $sPreviousConfigFile, $sTargetEnvironment, $sDataModelVersion,
						$bOldAddon, $aSelectedModuleCodes, $aSelectedExtensionCodes, $aParamValues);

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
			$fDuration = round(microtime(true) - $fStart, 2);
			SetupPage::log_info("##### STEP {$sStep} duration: {$fDuration}s");
		}
		catch (Exception $e)
		{
			$aResult = array(
				'status' => self::ERROR,
				'message' => $e->getMessage(),
				'next-step' => '',
				'next-step-label' => '',
				'percentage-completed' => 100,
			);

			SetupPage::log_error('An exception occurred: '.$e->getMessage().' at line '.$e->getLine().' in file '.$e->getFile());
			$idx = 0;
			// Log the call stack, but not the parameters since they may contain passwords or other sensitive data
			SetupPage::log("Call stack:");
			foreach ($e->getTrace() as $aTrace)
			{
				$sLine = empty($aTrace['line']) ? "" : $aTrace['line'];
				$sFile = empty($aTrace['file']) ? "" : $aTrace['file'];
				$sClass = empty($aTrace['class']) ? "" : $aTrace['class'];
				$sType = empty($aTrace['type']) ? "" : $aTrace['type'];
				$sFunction = empty($aTrace['function']) ? "" : $aTrace['function'];
				$sVerb = empty($sClass) ? $sFunction : "$sClass{$sType}$sFunction";
				SetupPage::log("#$idx $sFile($sLine): $sVerb(...)");
				$idx++;
			}
		}

		return $aResult;
	}

	/**
	 * @param array $oParams
	 *
	 * @return array to use with {@see Config::UpdateFromParams}
	 */
	private function GetParamValues($oParams)
	{
		$aDBParams = $oParams->Get('database');
		$aParamValues = array(
			'mode' => $oParams->Get('mode'),
			'db_server' => $aDBParams['server'],
			'db_user' => $aDBParams['user'],
			'db_pwd' => $aDBParams['pwd'],
			'db_name' => $aDBParams['name'],
			'new_db_name' => $aDBParams['name'],
			'db_prefix' => $aDBParams['prefix'],
			'db_tls_enabled' => $aDBParams['db_tls_enabled'],
			'db_tls_ca' => $aDBParams['db_tls_ca'],
			'application_path' => $oParams->Get('url', ''),
			'language' => $oParams->Get('language', ''),
			'graphviz_path' => $oParams->Get('graphviz_path', ''),
			'source_dir' => $oParams->Get('source_dir', ''),
		);

		return $aParamValues;
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

	/**
	 * @param Config $oConfig
	 * @param string $sBackupFileFormat
	 * @param string $sSourceConfigFile
	 *
	 * @throws \Exception
	 *
	 * @since 2.5 uses a {@link Config} object to store DB parameters
	 */
	protected static function DoBackup($oConfig, $sBackupFileFormat, $sSourceConfigFile, $sMySQLBinDir = null)
	{
		$oBackup = new SetupDBBackup($oConfig);
		$sTargetFile = $oBackup->MakeName($sBackupFileFormat);
		if (!empty($sMySQLBinDir))
		{
			$oBackup->SetMySQLBinDir($sMySQLBinDir);
		}
		$oBackup->CreateCompressedBackup($sTargetFile, $sSourceConfigFile);
	}

	
	protected static function DoCompile($aSelectedModules, $sSourceDir, $sExtensionDir, $sTargetDir, $sEnvironment, $bUseSymbolicLinks = false)
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
		$sExtraPath = APPROOT.'/data/'.$sEnvironment.'-modules/';
		if (is_dir($sExtraPath))
		{
			// if the extra dir exists, scan it for additional modules as well
			$aDirsToScan[] = $sExtraPath;
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
		
		$oDictModule = new MFDictModule('dictionaries', 'iTop Dictionaries', APPROOT.'dictionaries');
		$oFactory->LoadModule($oDictModule);
		
		$sDeltaFile = APPROOT.'core/datamodel.core.xml';
		if (file_exists($sDeltaFile))
		{
			$oCoreModule = new MFCoreModule('core', 'Core Module', $sDeltaFile);
			$oFactory->LoadModule($oCoreModule);
		}
		$sDeltaFile = APPROOT.'application/datamodel.application.xml';
		if (file_exists($sDeltaFile))
		{
			$oApplicationModule = new MFCoreModule('application', 'Application Module', $sDeltaFile);
			$oFactory->LoadModule($oApplicationModule);
		}
		
		$aModules = $oFactory->FindModules();

		foreach($aModules as $oModule)
		{
			$sModule = $oModule->GetName();
			if (in_array($sModule, $aSelectedModules))
			{
				$oFactory->LoadModule($oModule);
			}
		}
		// Dump the "reference" model, just before loading any actual delta
		$oFactory->SaveToFile(APPROOT.'data/datamodel-'.$sEnvironment.'.xml');
		
		$sDeltaFile = APPROOT.'data/'.$sEnvironment.'.delta.xml';
		if (file_exists($sDeltaFile))
		{
			$oDelta = new MFDeltaModule($sDeltaFile);
			$oFactory->LoadModule($oDelta);
			$oFactory->SaveToFile(APPROOT.'data/datamodel-'.$sEnvironment.'-with-delta.xml');
		}

		$oMFCompiler = new MFCompiler($oFactory);
		$oMFCompiler->Compile($sTargetPath, null, $bUseSymbolicLinks);
		//$aCompilerLog = $oMFCompiler->GetLog();
		//SetupPage::log_info(implode("\n", $aCompilerLog));
		SetupPage::log_info("Data model successfully compiled to '$sTargetPath'.");

		$sCacheDir = APPROOT.'/data/cache-'.$sEnvironment.'/';
		SetupUtils::builddir($sCacheDir);
		SetupUtils::tidydir($sCacheDir);

		// Special case to patch a ugly patch in itop-config-mgmt
		$sFileToPatch = $sTargetPath.'/itop-config-mgmt-1.0.0/model.itop-config-mgmt.php';
		if (file_exists($sFileToPatch))
		{
			$sContent = file_get_contents($sFileToPatch);
			
			$sContent = str_replace("require_once(APPROOT.'modules/itop-welcome-itil/model.itop-welcome-itil.php');", "//\n// The line below is no longer needed in iTop 2.0 -- patched by the setup program\n// require_once(APPROOT.'modules/itop-welcome-itil/model.itop-welcome-itil.php');", $sContent);
			
			file_put_contents($sFileToPatch, $sContent);
		}
		
		// Set an "Instance UUID" identifying this machine based on a file located in the data directory
		$sInstanceUUIDFile = APPROOT.'data/instance.txt';
		SetupUtils::builddir(APPROOT.'data');
		if (!file_exists($sInstanceUUIDFile))
		{
			$sIntanceUUID = utils::CreateUUID('filesystem');
			file_put_contents($sInstanceUUIDFile, $sIntanceUUID);
		}
	}

	/**
	 * @param $aSelectedModules
	 * @param $sModulesDir
	 * @param $aParamValues
	 * @param string $sTargetEnvironment
	 * @param bool $bOldAddon
	 * @param string $sAppRootUrl
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	protected static function DoUpdateDBSchema($aSelectedModules, $sModulesDir, $aParamValues, $sTargetEnvironment = '', $bOldAddon = false, $sAppRootUrl = '')
	{
		SetupPage::log_info("Update Database Schema for environment '$sTargetEnvironment'.");
		$sMode = $aParamValues['mode'];
		$sDBPrefix = $aParamValues['db_prefix'];
		$sDBName = $aParamValues['db_name'];

		$oConfig = new Config();
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

		// Migrate columns
		self::MoveColumns($sDBPrefix);

		// Migrate application data format
		//
		// priv_internalUser caused troubles because MySQL transforms table names to lower case under Windows
		// This becomes an issue when moving your installation data to/from Windows
		// Starting 2.0, all table names must be lowercase
		if ($sMode != 'install')
		{
			SetupPage::log_info("Renaming '{$sDBPrefix}priv_internalUser' into '{$sDBPrefix}priv_internaluser' (lowercase)"); 
			// This command will have no effect under Windows...
			// and it has been written in two steps so as to make it work under windows!
			CMDBSource::SelectDB($sDBName);
			try
			{
				$sRepair = "RENAME TABLE `{$sDBPrefix}priv_internalUser` TO `{$sDBPrefix}priv_internaluser_other`, `{$sDBPrefix}priv_internaluser_other` TO `{$sDBPrefix}priv_internaluser`";
				CMDBSource::Query($sRepair);
			}
			catch (Exception $e)
			{
				SetupPage::log_info("Renaming '{$sDBPrefix}priv_internalUser' failed (already done in a previous upgrade?)"); 
			}
			
			// let's remove the records in priv_change which have no counterpart in priv_changeop
			SetupPage::log_info("Cleanup of '{$sDBPrefix}priv_change' to remove orphan records"); 
			CMDBSource::SelectDB($sDBName);
			try
			{
				$sTotalCount = "SELECT COUNT(*) FROM `{$sDBPrefix}priv_change`";
				$iTotalCount = (int)CMDBSource::QueryToScalar($sTotalCount);
				SetupPage::log_info("There is a total of $iTotalCount records in {$sDBPrefix}priv_change.");
				
				$sOrphanCount = "SELECT COUNT(c.id) FROM `{$sDBPrefix}priv_change` AS c left join `{$sDBPrefix}priv_changeop` AS o ON c.id = o.changeid WHERE o.id IS NULL";
				$iOrphanCount = (int)CMDBSource::QueryToScalar($sOrphanCount);
				SetupPage::log_info("There are $iOrphanCount useless records in {$sDBPrefix}priv_change (".sprintf('%.2f', ((100.0*$iOrphanCount)/$iTotalCount))."%)");
				if ($iOrphanCount > 0)
				{
					SetupPage::log_info("Removing the orphan records...");
					$sCleanup = "DELETE FROM `{$sDBPrefix}priv_change` USING `{$sDBPrefix}priv_change` LEFT JOIN `{$sDBPrefix}priv_changeop` ON `{$sDBPrefix}priv_change`.id = `{$sDBPrefix}priv_changeop`.changeid WHERE `{$sDBPrefix}priv_changeop`.id IS NULL;";
					CMDBSource::Query($sCleanup);
					SetupPage::log_info("Cleanup completed successfully.");
				}
				else
				{
					SetupPage::log_info("Ok, nothing to cleanup.");
				}
			}
			catch (Exception $e)
			{
				SetupPage::log_info("Cleanup of orphan records in `{$sDBPrefix}priv_change` failed: ".$e->getMessage()); 
			}
			
		}
		
		// Module specific actions (migrate the data)
		//
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation(MetaModel::GetConfig(), APPROOT.$sModulesDir);
		$oProductionEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'BeforeDatabaseCreation');

		if(!$oProductionEnv->CreateDatabaseStructure(MetaModel::GetConfig(), $sMode))
		{
			throw new Exception("Failed to create/upgrade the database structure for environment '$sTargetEnvironment'");		
		}
		
		// Set a DBProperty with a unique ID to identify this instance of iTop
		$sUUID = DBProperty::GetProperty('database_uuid', '');
		if ($sUUID === '')
		{
			$sUUID = utils::CreateUUID('database');
			DBProperty::SetProperty('database_uuid', $sUUID, 'Installation/upgrade of '.ITOP_APPLICATION, 'Unique ID of this '.ITOP_APPLICATION.' Database');
		}
		
		// priv_change now has an 'origin' field to distinguish between the various input sources
		// Let's initialize the field with 'interactive' for all records were it's null
		// Then check if some records should hold a different value, based on a pattern matching in the userinfo field 
		CMDBSource::SelectDB($sDBName);
		try
		{
			$sCount = "SELECT COUNT(*) FROM `{$sDBPrefix}priv_change` WHERE `origin` IS NULL";
			$iCount = (int)CMDBSource::QueryToScalar($sCount);
			if ($iCount > 0)
			{
				SetupPage::log_info("Initializing '{$sDBPrefix}priv_change.origin' ($iCount records to update)"); 
				
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
				$aSuffixes = array();
				foreach(array_keys(Dict::GetLanguages()) as $sLangCode)
				{
					Dict::SetUserLanguage($sLangCode);
					$sSuffix = CMDBSource::Quote('%'.Dict::S('Core:SyncDataExchangeComment'));
					$aSuffixes[$sSuffix] = true;
				}
				Dict::SetUserLanguage($sCurrentLanguage);
				$sCondition = "`userinfo` LIKE ".implode(" OR `userinfo` LIKE ", array_keys($aSuffixes));

				$sInit = "UPDATE `{$sDBPrefix}priv_change` SET `origin` = 'synchro-data-source' WHERE ($sCondition)"; 
				CMDBSource::Query($sInit);
				
				SetupPage::log_info("Initialization of '{$sDBPrefix}priv_change.origin' completed."); 
			}
			else
			{
				SetupPage::log_info("'{$sDBPrefix}priv_change.origin' already initialized, nothing to do."); 
			}
		}
		catch (Exception $e)
		{
			SetupPage::log_error("Initializing '{$sDBPrefix}priv_change.origin' failed: ".$e->getMessage()); 
		}

		// priv_async_task now has a 'status' field to distinguish between the various statuses rather than just relying on the date columns
		// Let's initialize the field with 'planned' or 'error' for all records were it's null
		CMDBSource::SelectDB($sDBName);
		try
		{
			$sCount = "SELECT COUNT(*) FROM `{$sDBPrefix}priv_async_task` WHERE `status` IS NULL";
			$iCount = (int)CMDBSource::QueryToScalar($sCount);
			if ($iCount > 0)
			{
				SetupPage::log_info("Initializing '{$sDBPrefix}priv_async_task.status' ($iCount records to update)"); 
				
				$sInit = "UPDATE `{$sDBPrefix}priv_async_task` SET `status` = 'planned' WHERE (`status` IS NULL) AND (`started` IS NULL)";
				CMDBSource::Query($sInit);

				$sInit = "UPDATE `{$sDBPrefix}priv_async_task` SET `status` = 'error' WHERE (`status` IS NULL) AND (`started` IS NOT NULL)";
				CMDBSource::Query($sInit);
				
				SetupPage::log_info("Initialization of '{$sDBPrefix}priv_async_task.status' completed."); 
			}
			else
			{
				SetupPage::log_info("'{$sDBPrefix}priv_async_task.status' already initialized, nothing to do."); 
			}
		}
		catch (Exception $e)
		{
			SetupPage::log_error("Initializing '{$sDBPrefix}priv_async_task.status' failed: ".$e->getMessage()); 
		}

		SetupPage::log_info("Database Schema Successfully Updated for environment '$sTargetEnvironment'.");
	}

	/**
	 * @param string $sDBPrefix
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	protected static function MoveColumns($sDBPrefix)
	{
		// In 2.6.0 the 'fields' attribute has been moved from Query to QueryOQL for dependencies reasons
		ModuleInstallerAPI::MoveColumnInDB($sDBPrefix.'priv_query', 'fields', $sDBPrefix.'priv_query_oql', 'fields');
	}

	protected static function AfterDBCreate(
		$sModulesDir, $aParamValues, $sAdminUser, $sAdminPwd, $sAdminLanguage, $aSelectedModules, $sTargetEnvironment,
		$bOldAddon
	)
	{
		SetupPage::log_info('After Database Creation');

		$sMode = $aParamValues['mode'];
		$oConfig = new Config();
		$oConfig->UpdateFromParams($aParamValues, $sModulesDir);

		if ($bOldAddon)
		{
			// Old version of the add-on for backward compatibility with pre-2.0 data models
			$oConfig->SetAddons(array(
				'user rights' => 'addons/userrights/userrightsprofile.db.class.inc.php',
			));
		}

		$oProductionEnv = new RunTimeEnvironment($sTargetEnvironment);
		$oProductionEnv->InitDataModel($oConfig, true);  // load data model and connect to the database
		self::$bMetaModelStarted = true; // No need to reload the final MetaModel in case the installer runs synchronously 
		
		// Perform here additional DB setup... profiles, etc...
		//
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation(MetaModel::GetConfig(), APPROOT.$sModulesDir);
		$oProductionEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'AfterDatabaseCreation');

		$oProductionEnv->UpdatePredefinedObjects();
		
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
		
		// Perform final setup tasks here
		//
		$oProductionEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'AfterDatabaseSetup');
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

	protected static function DoLoadFiles(
		$aSelectedModules, $sModulesDir, $aParamValues, $sTargetEnvironment = 'production', $bOldAddon = false,
		$bSampleData = false
	)
	{
		$oConfig = new Config();
		$oConfig->UpdateFromParams($aParamValues, $sModulesDir);

		if ($bOldAddon)
		{
			// Old version of the add-on for backward compatibility with pre-2.0 data models
			$oConfig->SetAddons(array(
				'user rights' => 'addons/userrights/userrightsprofile.db.class.inc.php',
			));
		}
		
		$oProductionEnv = new RunTimeEnvironment($sTargetEnvironment);
		
		//Load the MetaModel if needed (asynchronous mode)
		if (!self::$bMetaModelStarted)
		{
			$oProductionEnv->InitDataModel($oConfig, false);  // load data model and connect to the database
			self::$bMetaModelStarted = true; // No need to reload the final MetaModel in case the installer runs synchronously
		} 
		
		$aAvailableModules = $oProductionEnv->AnalyzeInstallation($oConfig, APPROOT.$sModulesDir);
		$oProductionEnv->LoadData($aAvailableModules, $aSelectedModules, $bSampleData);

        	// Perform after dbload setup tasks here
		//
		$oProductionEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'AfterDataLoad');
	}

	/**
	 * @param string $sModulesDir
	 * @param string $sPreviousConfigFile
	 * @param string $sTargetEnvironment
	 * @param string $sDataModelVersion
	 * @param boolean $bOldAddon
	 * @param array $aSelectedModuleCodes
	 * @param array $aSelectedExtensionCodes
	 * @param array $aParamValues parameters array used to create config file using {@see Config::UpdateFromParams}
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected static function DoCreateConfig(
		$sModulesDir, $sPreviousConfigFile, $sTargetEnvironment, $sDataModelVersion, $bOldAddon, $aSelectedModuleCodes,
		$aSelectedExtensionCodes, $aParamValues
	) {
		$aParamValues['selected_modules'] = implode(',', $aSelectedModuleCodes);
		$sMode = $aParamValues['mode'];

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
			// To preserve backward compatibility while upgrading to 2.0.3 (when tracking_level_linked_set_default has been introduced)
			// the default value on upgrade differs from the default value at first install
			$oConfig->Set('tracking_level_linked_set_default', LINKSET_TRACKING_NONE, 'first_install');
		}
		
		// Final config update: add the modules
		$oConfig->UpdateFromParams($aParamValues, $sModulesDir, $bPreserveModuleSettings);
		if ($bOldAddon)
		{
			// Old version of the add-on for backward compatibility with pre-2.0 data models
			$oConfig->SetAddons(array(
				'user rights' => 'addons/userrights/userrightsprofile.db.class.inc.php',
			));
		}

		// Record which modules are installed...
		$oProductionEnv = new RunTimeEnvironment($sTargetEnvironment);
		$oProductionEnv->InitDataModel($oConfig, true);  // load data model and connect to the database
		if (!$oProductionEnv->RecordInstallation($oConfig, $sDataModelVersion, $aSelectedModuleCodes, $aSelectedExtensionCodes))
		{
			throw new Exception("Failed to record the installation information");
		}

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

class SetupDBBackup extends DBBackup
{
	protected function LogInfo($sMsg)
	{
		SetupPage::log('Info - '.$sMsg);
	}

	protected function LogError($sMsg)
	{
		SetupPage::log('Error - '.$sMsg);
	}
}