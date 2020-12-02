<?php
// Copyright (C) 2017 Combodo SARL
//
// This file is part of iTop.
//
// iTop is free software; you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// iTop is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * Handles various ajax requests - called through pages/exec.php
 *
 * @copyright Copyright (C) 2010-2017 Combodo SARL
 * @license http://opensource.org/licenses/AGPL-3.0
 */
if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once (APPROOT.'application/webpage.class.inc.php');
require_once (APPROOT.'application/ajaxwebpage.class.inc.php');
require_once (APPROOT.'application/utils.inc.php');
require_once (APPROOT.'core/log.class.inc.php');
IssueLog::Enable(APPROOT.'log/error.log');

require_once (APPROOT.'setup/runtimeenv.class.inc.php');
require_once (APPROOT.'setup/backup.class.inc.php');
require_once (APPROOT.'core/mutex.class.inc.php');
require_once (APPROOT.'core/dict.class.inc.php');
require_once (APPROOT.'setup/xmldataloader.class.inc.php');
require_once (__DIR__.'/hubruntimeenvironment.class.inc.php');

/**
 * Overload of DBBackup to handle logging
 */
class DBBackupWithErrorReporting extends DBBackup
{

	protected $aInfos = array();

	protected $aErrors = array();

	protected function LogInfo($sMsg)
	{
		$aInfos[] = $sMsg;
	}

	protected function LogError($sMsg)
	{
		IssueLog::Error($sMsg);
		$aErrors[] = $sMsg;
	}

	public function GetInfos()
	{
		return $this->aInfos;
	}

	public function GetErrors()
	{
		return $this->aErrors;
	}
}

/**
 *
 * @param string $sTargetFile
 * @throws Exception
 * @return DBBackupWithErrorReporting
 */
function DoBackup($sTargetFile)
{
	// Make sure the target directory exists
	$sBackupDir = dirname($sTargetFile);
	SetupUtils::builddir($sBackupDir);
	
	$oBackup = new DBBackupWithErrorReporting();
	$oBackup->SetMySQLBinDir(MetaModel::GetConfig()->GetModuleSetting('itop-backup', 'mysql_bindir', ''));
	$sSourceConfigFile = APPCONF.utils::GetCurrentEnvironment().'/'.ITOP_CONFIG_FILE;
	
	$oMutex = new iTopMutex('backup.'.utils::GetCurrentEnvironment());
	$oMutex->Lock();
	try
	{
		$oBackup->CreateCompressedBackup($sTargetFile, $sSourceConfigFile);
	}
	catch (Exception $e)
	{
		$oMutex->Unlock();
		throw $e;
	}
	$oMutex->Unlock();
	return $oBackup;
}

/**
 * Outputs the status of the current ajax execution (as a JSON structure)
 * 
 * @param string $sMessage
 * @param bool $bSuccess
 * @param number $iErrorCode
 * @param array $aMoreFields
 *        	Extra fields to pass to the caller, if needed
 */
function ReportStatus($sMessage, $bSuccess, $iErrorCode = 0, $aMoreFields = array())
{
	$oPage = new ajax_page("");
	$oPage->SetContentType('application/json');
	$aResult = array(
		'code' => $iErrorCode,
		'message' => $sMessage,
		'fields' => $aMoreFields
	);
	$oPage->add(json_encode($aResult));
	$oPage->output();
}

/**
 * Helper to output the status of a successful execution
 * 
 * @param string $sMessage
 * @param array $aMoreFields
 *        	Extra fields to pass to the caller, if needed
 */
function ReportSuccess($sMessage, $aMoreFields = array())
{
	ReportStatus($sMessage, true, 0, $aMoreFields);
}

/**
 * Helper to output the status of a failed execution
 * 
 * @param string $sMessage
 * @param number $iErrorCode
 * @param array $aMoreFields
 *        	Extra fields to pass to the caller, if needed
 */
function ReportError($sMessage, $iErrorCode, $aMoreFields = array())
{
	if ($iErrorCode==0)
	{
		// 0 means no error, so change it if no meaningful error code is supplied
		$iErrorCode = -1;
	}
	ReportStatus($sMessage, false, $iErrorCode, $aMoreFields);
}

try
{
	SetupUtils::ExitMaintenanceMode(false); // Reset maintenance mode in case of problem

	utils::PushArchiveMode(false);
	
	ini_set('max_execution_time', max(3600, ini_get('max_execution_time'))); // Under Windows SQL/backup operations are part of the PHP timeout and require extra time
	ini_set('display_errors', 1); // Make sure that fatal errors remain visible from the end-user
	                              
	// Most of the ajax calls are done without the MetaModel being loaded
	                              // Therefore, the language must be passed as an argument,
	                              // and the dictionnaries be loaded here
	$sLanguage = utils::ReadParam('language', '');
	if ($sLanguage!='')
	{
		foreach (glob(APPROOT.'env-production/dictionaries/*.dict.php') as $sFilePath)
		{
			require_once ($sFilePath);
		}
		
		$aLanguages = Dict::GetLanguages();
		if (array_key_exists($sLanguage, $aLanguages))
		{
			Dict::SetUserLanguage($sLanguage);
		}
	}
	$sOperation = utils::ReadParam('operation', '');
	switch ($sOperation)
	{
		case 'check_before_backup':
		require_once (APPROOT.'/application/startup.inc.php');
		require_once (APPROOT.'/application/loginwebpage.class.inc.php');
		LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)
		
		$sDBBackupPath = APPROOT.'data/backups/manual';
		$aChecks = SetupUtils::CheckBackupPrerequisites($sDBBackupPath);
		$bFailed = false;
		foreach ($aChecks as $oCheckResult)
		{
			if ($oCheckResult->iSeverity==CheckResult::ERROR)
			{
				$bFailed = true;
				ReportError($oCheckResult->sLabel, -2);
			}
		}
		if (!$bFailed)
		{
			// Continue the checks
			$fFreeSpace = SetupUtils::CheckDiskSpace($sDBBackupPath);
			if ($fFreeSpace!==false)
			{
				$sMessage = Dict::Format('iTopHub:BackupFreeDiskSpaceIn', SetupUtils::HumanReadableSize($fFreeSpace), dirname($sDBBackupPath));
				ReportSuccess($sMessage);
			}
			else
			{
				ReportError(Dict::S('iTopHub:FailedToCheckFreeDiskSpace'), -1);
			}
		}
		break;
		
		case 'do_backup':
		require_once (APPROOT.'/application/startup.inc.php');
		require_once (APPROOT.'/application/loginwebpage.class.inc.php');
		LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)
		
		try
		{
			if (MetaModel::GetConfig()->Get('demo_mode')) throw new Exception('Sorry the installation of extensions is not allowed in demo mode');
			SetupPage::log_info('Backup starts...');	    
			set_time_limit(0);
			$sBackupPath = APPROOT.'/data/backups/manual/backup-';
			$iSuffix = 1;
			$sSuffix = '';
			// Generate a unique name...
			do
			{
				$sBackupFile = $sBackupPath.date('Y-m-d-His').$sSuffix;
				$sSuffix = '-'.$iSuffix;
				$iSuffix++ ;
			}
			while (file_exists($sBackupFile));
			
			$oBackup = DoBackup($sBackupFile);
			$aErrors = $oBackup->GetErrors();
			if (count($aErrors)>0)
			{
			    SetupPage::log_error('Backup failed.');
			    SetupPage::log_error(implode("\n", $aErrors));
			    ReportError(Dict::S('iTopHub:BackupFailed'), -1, $aErrors);
			}
			else
			{
				SetupPage::log_info('Backup successfully completed.');
				ReportSuccess(Dict::S('iTopHub:BackupOk'));
			}
		}
		catch (Exception $e)
		{
			SetupPage::log_error($e->getMessage());
			ReportError($e->getMessage(), $e->getCode());
		}
		break;
		
		case 'compile':
		SetupPage::log_info('Deployment starts...');
		$sAuthent = utils::ReadParam('authent', '', false, 'raw_data');
		if (!file_exists(APPROOT.'data/hub/compile_authent') || $sAuthent !== file_get_contents(APPROOT.'data/hub/compile_authent'))
		{
				throw new SecurityException(Dict::S('iTopHub:FailAuthent'));
		}
		// First step: prepare the datamodel, if it fails, roll-back
		$aSelectedExtensionCodes = utils::ReadParam('extension_codes', array());
		$aSelectedExtensionDirs = utils::ReadParam('extension_dirs', array());
		
		$oRuntimeEnv = new HubRunTimeEnvironment('production', false); // use a temp environment: production-build
		$oRuntimeEnv->MoveSelectedExtensions(APPROOT.'/data/downloaded-extensions/', $aSelectedExtensionDirs);
		
		$oConfig = new Config(APPCONF.'production/'.ITOP_CONFIG_FILE);
		if ($oConfig->Get('demo_mode')) throw new Exception('Sorry the installation of extensions is not allowed in demo mode');
		
		$aSelectModules = $oRuntimeEnv->CompileFrom('production', false); // WARNING symlinks does not seem to be compatible with manual Commit
		
		$oRuntimeEnv->UpdateIncludes($oConfig);
		
		$oRuntimeEnv->InitDataModel($oConfig, true /* model only */);
		
		// Safety check: check the inter dependencies, will throw an exception in case of inconsistency
		$oRuntimeEnv->AnalyzeInstallation($oConfig, $oRuntimeEnv->GetBuildDir(), true);
		
		$oRuntimeEnv->CheckMetaModel(); // Will throw an exception if a problem is detected
		                                
		// Everything seems Ok so far, commit in env-production!
		$oRuntimeEnv->WriteConfigFileSafe($oConfig);
		$oRuntimeEnv->Commit();
		
		// Report the success in a way that will be detected by the ajax caller
		SetupPage::log_info('Compilation completed...');
		ReportSuccess('Ok'); // No access to Dict::S here
		break;
		
		case 'move_to_production':
		// Second step: update the schema and the data
		// Everything happening below is based on env-production
		$oRuntimeEnv = new RunTimeEnvironment('production', true);
		
		try
		{
		    SetupPage::log_info('Move to production starts...');
		    $sAuthent = utils::ReadParam('authent', '', false, 'raw_data');
			if (!file_exists(APPROOT.'data/hub/compile_authent') || $sAuthent !== file_get_contents(APPROOT.'data/hub/compile_authent'))
			{
				throw new SecurityException(Dict::S('iTopHub:FailAuthent'));
			}
			unlink(APPROOT.'data/hub/compile_authent');
			// Load the "production" config file to clone & update it
			$oConfig = new Config(APPCONF.'production/'.ITOP_CONFIG_FILE);
			SetupUtils::EnterReadOnlyMode($oConfig);

			$oRuntimeEnv->InitDataModel($oConfig, true /* model only */);
			
			$aAvailableModules = $oRuntimeEnv->AnalyzeInstallation($oConfig, $oRuntimeEnv->GetBuildDir(), true);
			
			$aSelectedModules = array();
			foreach ($aAvailableModules as $sModuleId => $aModule)
			{
				if (($sModuleId == ROOT_MODULE) || ($sModuleId == DATAMODEL_MODULE))
				{
					continue;
				}
				else
				{
					$aSelectedModules[] = $sModuleId;
				}
			}
			
			$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'BeforeDatabaseCreation');
			
			$oRuntimeEnv->CreateDatabaseStructure($oConfig, 'upgrade');
			
			$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'AfterDatabaseCreation');
			
			$oRuntimeEnv->UpdatePredefinedObjects();
			
			$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'AfterDatabaseSetup');
			
			$oRuntimeEnv->LoadData($aAvailableModules, $aSelectedModules, false /* no sample data*/);

			$oRuntimeEnv->CallInstallerHandlers($aAvailableModules, $aSelectedModules, 'AfterDataLoad');

			// Record the installation so that the "about box" knows about the installed modules
			$sDataModelVersion = $oRuntimeEnv->GetCurrentDataModelVersion();
			
			$oExtensionsMap = new iTopExtensionsMap();
			
			// Default choices = as before
			$oExtensionsMap->LoadChoicesFromDatabase($oConfig);
			foreach ($oExtensionsMap->GetAllExtensions() as $oExtension)
			{
				// Plus all "remote" extensions
				if ($oExtension->sSource==iTopExtension::SOURCE_REMOTE)
				{
					$oExtensionsMap->MarkAsChosen($oExtension->sCode);
				}
			}
			$aSelectedExtensionCodes = array();
			foreach ($oExtensionsMap->GetChoices() as $oExtension)
			{
				$aSelectedExtensionCodes[] = $oExtension->sCode;
			}
			$aSelectedExtensions = $oExtensionsMap->GetChoices();
			$oRuntimeEnv->RecordInstallation($oConfig, $sDataModelVersion, $aSelectedModules, $aSelectedExtensionCodes, 'Done by the iTop Hub Connector');
			
			// Report the success in a way that will be detected by the ajax caller
			SetupPage::log_info('Deployment successfully completed.');
			ReportSuccess(Dict::S('iTopHub:CompiledOK'));
		}
		catch (Exception $e)
		{
			if(file_exists(APPROOT.'data/hub/compile_authent'))
			{
				unlink(APPROOT.'data/hub/compile_authent');
			}
			// Note: at this point, the dictionnary is not necessarily loaded
			SetupPage::log_error(get_class($e).': '.Dict::S('iTopHub:ConfigurationSafelyReverted')."\n".$e->getMessage());
			SetupPage::log_error('Debug trace: '.$e->getTraceAsString());
			ReportError($e->getMessage(), $e->getCode());
		}
		finally
		{
			SetupUtils::ExitReadOnlyMode();
		}
		break;
		
		default:
		ReportError("Invalid operation: '$sOperation'", -1);
	}
}
catch (Exception $e)
{
	SetupPage::log_error(get_class($e).': '.Dict::S('iTopHub:ConfigurationSafelyReverted')."\n".$e->getMessage());
	SetupPage::log_error('Debug trace: '.$e->getTraceAsString());
	
	utils::PopArchiveMode();
	
	ReportError($e->getMessage(), $e->getCode());
}
