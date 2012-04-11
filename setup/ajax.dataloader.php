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
 * Does load data from XML files (currently used in the setup only)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

/**
 * This page is called to perform "asynchronously" the setup actions
 * parameters
 * 'operation': one of 'compile_data_model', 'update_db_schema', 'after_db_creation', 'file'
 * 
 * if 'operation' == 'update_db_schema': 
 * 'mode': install | upgrade
 * 
 *  if 'operation' == 'after_db_creation':
 * 'mode': install | upgrade
 * 
 * if 'operation' == 'file': 
 * 'file': string Name of the file to load
 * 'session_status': string 'start', 'continue' or 'end'
 * 'percent': integer 0..100 the percentage of completion once the file has been loaded 
 */ 
define('SAFE_MINIMUM_MEMORY', 32*1024*1024);
require_once('../approot.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/setup/setuppage.class.inc.php');
require_once(APPROOT.'/setup/moduleinstaller.class.inc.php');

$sMemoryLimit = trim(ini_get('memory_limit'));
if (empty($sMemoryLimit))
{
	// On some PHP installations, memory_limit does not exist as a PHP setting!
	// (encountered on a 5.2.0 under Windows)
	// In that case, ini_set will not work, let's keep track of this and proceed with the data load
	SetupPage::log_info("No memory limit has been defined in this instance of PHP");		
}
else
{
	// Check that the limit will allow us to load the data
	//
	$iMemoryLimit = utils::ConvertToBytes($sMemoryLimit);
	if ($iMemoryLimit < SAFE_MINIMUM_MEMORY)
	{
		if (ini_set('memory_limit', SAFE_MINIMUM_MEMORY) === FALSE)
		{
			SetupPage::log_error("memory_limit is too small: $iMemoryLimit and can not be increased by the script itself.");		
		}
		else
		{
			SetupPage::log_info("memory_limit increased from $iMemoryLimit to ".SAFE_MINIMUM_MEMORY.".");		
		}
	}

}


function FatalErrorCatcher($sOutput)
{ 
	if ( preg_match('|<phpfatalerror>.*</phpfatalerror>|s', $sOutput, $aMatches) )
	{
		header("HTTP/1.0 500 Internal server error.");
		foreach ($aMatches as $sMatch)
		{
			$errors .= strip_tags($sMatch)."\n";
		}
		$sOutput = "$errors\n";
		// Logging to a file does not work if the whole memory is exhausted...		
		//SetupPage::log_error("Fatal error - in $__FILE__ , $errors");
	}
	return $sOutput;
}

/**
 * Helper function to create and administrator account for iTop
 * @return boolean true on success, false otherwise 
 */
function CreateAdminAccount(Config $oConfig, $sAdminUser, $sAdminPwd, $sLanguage)
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
//Define some bogus, invalid HTML tags that no sane
//person would ever put in an actual document and tell
//PHP to delimit fatal error warnings with them.
ini_set('error_prepend_string', '<phpfatalerror>');
ini_set('error_append_string', '</phpfatalerror>');

// Starts the capture of the ouput, and sets a filter to capture the fatal errors.
ob_start('FatalErrorCatcher'); // Start capturing the output, and pass it through the fatal error catcher

require_once(APPROOT.'/core/config.class.inc.php');
require_once(APPROOT.'/core/log.class.inc.php');
require_once(APPROOT.'/core/kpi.class.inc.php');
require_once(APPROOT.'/core/cmdbsource.class.inc.php');
require_once('./xmldataloader.class.inc.php');


// Never cache this page
header("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header("Expires: Fri, 17 Jul 1970 05:00:00 GMT");    // Date in the past

/**
 * Main program
 */
$sOperation = Utils::ReadParam('operation', '');
try
{
	switch($sOperation)
	{
		//////////////////////////////
		//
		case 'compile_data_model':
		//
		SetupPage::log_info("Compiling data model.");

		require_once(APPROOT.'setup/modulediscovery.class.inc.php');
		require_once(APPROOT.'setup/modelfactory.class.inc.php');
		require_once(APPROOT.'setup/compiler.class.inc.php');

		$sSelectedModules = Utils::ReadParam('selected_modules', '', false, 'raw_data');
		$aSelectedModules = explode(',', $sSelectedModules);

		$sWorkspace = Utils::ReadParam('workspace_dir', '', false, 'raw_data');
		$sSourceDir = Utils::ReadParam('source_dir', '', false, 'raw_data');
		$sTargetDir = Utils::ReadParam('target_dir', '', false, 'raw_data');
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
		if (strlen($sWorkspace) > 0)
		{
			$oWorkspace = new MFWorkspace(APPROOT.$sWorkspace);
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
		break;
		
		//////////////////////////////
		//
		case 'update_db_schema':
		//
		SetupPage::log_info("Update Database Schema.");

		$oConfig = new Config();

		$aParamValues = array(
			'db_server' => utils::ReadParam('db_server', '', false, 'raw_data'),
			'db_user' => utils::ReadParam('db_user', '', false, 'raw_data'),
			'db_pwd' => utils::ReadParam('db_pwd', '', false, 'raw_data'),
			'db_name' => utils::ReadParam('db_name', '', false, 'raw_data'),
			'new_db_name' => utils::ReadParam('new_db_name', '', false, 'raw_data'),
			'db_prefix' => utils::ReadParam('db_prefix', '', false, 'raw_data')
		);
		$sModuleDir = Utils::ReadParam('modules_dir', '');
		$oConfig->UpdateFromParams($aParamValues, $sModuleDir);

		InitDataModel($oConfig, true);  // load data model only

		$sMode = Utils::ReadParam('mode', 'install');
		if(!CreateDatabaseStructure(MetaModel::GetConfig(), $sMode))
		{
			throw(new Exception("Failed to create/upgrade the database structure"));		
		}
		SetupPage::log_info("Database Schema Successfully Updated.");
		break;
		
		//////////////////////////////
		//
		case 'after_db_create':
		//
		SetupPage::log_info('After Database Creation');

		$oConfig = new Config();

		$aParamValues = array(
			'db_server' => utils::ReadParam('db_server', '', false, 'raw_data'),
			'db_user' => utils::ReadParam('db_user', '', false, 'raw_data'),
			'db_pwd' => utils::ReadParam('db_pwd', '', false, 'raw_data'),
			'db_name' => utils::ReadParam('db_name', '', false, 'raw_data'),
			'new_db_name' => utils::ReadParam('new_db_name', '', false, 'raw_data'),
			'db_prefix' => utils::ReadParam('db_prefix', '', false, 'raw_data')
		);
		$sModuleDir = Utils::ReadParam('modules_dir', '');
		$oConfig->UpdateFromParams($aParamValues, $sModuleDir);

		InitDataModel($oConfig, false);  // load data model and connect to the database

		$sMode = Utils::ReadParam('mode', 'install');
		$sSelectedModules = Utils::ReadParam('selected_modules', '', false, 'raw_data');
		$aSelectedModules = explode(',', $sSelectedModules);
		
		// Perform here additional DB setup... profiles, etc...
		//
		$aAvailableModules = AnalyzeInstallation(MetaModel::GetConfig(), $sModuleDir);
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

		if (!RecordInstallation($oConfig, $aSelectedModules, $sModuleDir))
		{
			throw(new Exception("Failed to record the installation information"));
		}
		
		if($sMode == 'install')
		{
			// Create the admin user only in case of installation
			$sAdminUser = Utils::ReadParam('auth_user', '', false, 'raw_data');
			$sAdminPwd = Utils::ReadParam('auth_pwd', '', false, 'raw_data');
			$sLanguage = Utils::ReadParam('language', '');
			if (!CreateAdminAccount(MetaModel::GetConfig(), $sAdminUser, $sAdminPwd, $sLanguage))
			{
				throw(new Exception("Failed to create the administrator account '$sAdminUser'"));
			}
			else
			{
				SetupPage::log_info("Administrator account '$sAdminUser' created.");
			}
		}
		break;
		
		//////////////////////////////
		//
		case 'load_data': // Load data files
		//
		$sFileName = Utils::ReadParam('file', '', false, 'raw_data');
		$sSessionStatus = Utils::ReadParam('session_status', '');
		$iPercent = (integer)Utils::ReadParam('percent', 0);
		SetupPage::log_info("Loading file: $sFileName");
		if (empty($sFileName) || !file_exists($sFileName))
		{
			throw(new Exception("File $sFileName does not exist"));
		}

		$oConfig = new Config();

		$aParamValues = array(
			'db_server' => utils::ReadParam('db_server', '', false, 'raw_data'),
			'db_user' => utils::ReadParam('db_user', '', false, 'raw_data'),
			'db_pwd' => utils::ReadParam('db_pwd', '', false, 'raw_data'),
			'db_name' => utils::ReadParam('db_name', '', false, 'raw_data'),
			'new_db_name' => utils::ReadParam('new_db_name', '', false, 'raw_data'),
			'db_prefix' => utils::ReadParam('db_prefix', '', false, 'raw_data')
		);
		$sModuleDir = Utils::ReadParam('modules_dir', '');
		$oConfig->UpdateFromParams($aParamValues, $sModuleDir);

		InitDataModel($oConfig, false);  // load data model and connect to the database

		$oDataLoader = new XMLDataLoader(); 
		if ($sSessionStatus == 'start')
		{
			$oChange = MetaModel::NewObject("CMDBChange");
			$oChange->Set("date", time());
			$oChange->Set("userinfo", "Initialization");
			$iChangeId = $oChange->DBInsert();
			SetupPage::log_info("starting data load session");
			$oDataLoader->StartSession($oChange);
		}
	
		$oDataLoader->LoadFile($sFileName);
		$sResult = sprintf("loading of %s done. (Overall %d %% completed).", basename($sFileName), $iPercent);
		SetupPage::log_info($sResult);
	
		if ($sSessionStatus == 'end')
		{
		    $oDataLoader->EndSession();
		    SetupPage::log_info("ending data load session");
		}
		break;
		
		default:
		throw(new Exception("Error unsupported operation '$sOperation'"));
	}
}
catch(Exception $e)
{
	header("HTTP/1.0 500 Internal server error.");
	echo "<p>An error happened while processing the installation:</p>\n";
	echo '<p>'.$e."</p>\n";
	SetupPage::log_error("An error happened while processing the installation: ".$e);
}

if (function_exists('memory_get_peak_usage'))
{
	if ($sOperation == 'file')
	{
		SetupPage::log_info("loading file '$sFileName', peak memory usage. ".memory_get_peak_usage());
	}
	else
	{
		SetupPage::log_info("operation '$sOperation', peak memory usage. ".memory_get_peak_usage());
	}
}
?>
