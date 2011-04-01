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
 * 'operation': one of 'update_db_schema', 'after_db_creation', 'file'
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
	SetupWebPage::log_info("No memory limit has been defined in this instance of PHP");		
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
			SetupWebPage::log_error("memory_limit is too small: $iMemoryLimit and can not be increased by the script itself.");		
		}
		else
		{
			SetupWebPage::log_info("memory_limit increased from $iMemoryLimit to ".SAFE_MINIMUM_MEMORY.".");		
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
		//SetupWebPage::log_error("Fatal error - in $__FILE__ , $errors");
	}
	return $sOutput;
}

/**
 * Helper function to create and administrator account for iTop
 * @return boolean true on success, false otherwise 
 */
function CreateAdminAccount(Config $oConfig, $sAdminUser, $sAdminPwd, $sLanguage)
{
	SetupWebPage::log_info('CreateAdminAccount');

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

define('TMP_CONFIG_FILE', APPROOT.'/tmp-config-itop.php');
//define('FINAL_CONFIG_FILE', APPROOT.'/config-itop.php');

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
		
		case 'update_db_schema':
		SetupWebPage::log_info("Update Database Schema.");
		InitDataModel(TMP_CONFIG_FILE, true);  // load data model and connect to the database
		$sMode = Utils::ReadParam('mode', 'install');
		$sSelectedModules = Utils::ReadParam('selected_modules', '');
		$aSelectedModules = explode(',', $sSelectedModules);
		if(!CreateDatabaseStructure(MetaModel::GetConfig(), $aSelectedModules, $sMode))
		{
			throw(new Exception("Failed to create/upgrade the database structure"));		
		}
		SetupWebPage::log_info("Database Schema Successfully Updated.");
		break;
		
		case 'after_db_create':
		SetupWebPage::log_info('After Database Creation');
		$sMode = Utils::ReadParam('mode', 'install');
		$sSelectedModules = Utils::ReadParam('selected_modules', '');
		$aSelectedModules = explode(',', $sSelectedModules);
		InitDataModel(TMP_CONFIG_FILE, true);  // load data model and connect to the database
		
		// Perform here additional DB setup... profiles, etc...

		$aAvailableModules = AnalyzeInstallation(MetaModel::GetConfig());
	
		$aStructureDataFiles = array();
		$aSampleDataFiles = array();
	
		foreach($aAvailableModules as $sModuleId => $aModule)
		{
			if (($sModuleId != ROOT_MODULE) && in_array($sModuleId, $aSelectedModules) &&
			     isset($aAvailableModules[$sModuleId]['installer']) )
			{
				$sModuleInstallerClass = $aAvailableModules[$sModuleId]['installer'];
				SetupWebPage::log_info("Calling Module Handler: $sModuleInstallerClass::AfterDatabaseCreation(oConfig, {$aModule['version_db']}, {$aModule['version_code']})");
				// The validity of the sModuleInstallerClass has been established in BuildConfig() 
				$aCallSpec = array($sModuleInstallerClass, 'AfterDatabaseCreation');
				call_user_func_array($aCallSpec, array(MetaModel::GetConfig(), $aModule['version_db'], $aModule['version_code']));								
			}
		}


		if (!RecordInstallation(MetaModel::GetConfig(), $aSelectedModules))
		{
			throw(new Exception("Failed to record the installation information"));
		}
		
		if($sMode == 'install')
		{
			// Create the admin user only in case of installation
			$sAdminUser = Utils::ReadParam('auth_user', '');
			$sAdminPwd = Utils::ReadParam('auth_pwd', '');
			$sLanguage = Utils::ReadParam('language', '');
			if (!CreateAdminAccount(MetaModel::GetConfig(), $sAdminUser, $sAdminPwd, $sLanguage))
			{
				throw(new Exception("Failed to create the administrator account '$sAdminUser'"));
			}
			else
			{
				SetupWebPage::log_info("Administrator account '$sAdminUser' created.");
			}
		}
		break;
		
		case 'load_data': // Load data files
		$sFileName = Utils::ReadParam('file', '');
		$sSessionStatus = Utils::ReadParam('session_status', '');
		$iPercent = (integer)Utils::ReadParam('percent', 0);
		SetupWebPage::log_info("Loading file: $sFileName");
		if (empty($sFileName) || !file_exists($sFileName))
		{
			throw(new Exception("File $sFileName does not exist"));
		}
		
		$oDataLoader = new XMLDataLoader(TMP_CONFIG_FILE); // When called by the wizard, the final config is not yet there
		if ($sSessionStatus == 'start')
		{
			$oChange = MetaModel::NewObject("CMDBChange");
			$oChange->Set("date", time());
			$oChange->Set("userinfo", "Initialization");
			$iChangeId = $oChange->DBInsert();
			SetupWebPage::log_info("starting data load session");
			$oDataLoader->StartSession($oChange);
		}
	
		$oDataLoader->LoadFile($sFileName);
		$sResult = sprintf("loading of %s done. (Overall %d %% completed).", basename($sFileName), $iPercent);
		SetupWebPage::log_info($sResult);
	
		if ($sSessionStatus == 'end')
		{
		    $oDataLoader->EndSession();
		    SetupWebPage::log_info("ending data load session");
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
	SetupWebPage::log_error("An error happened while processing the installation: ".$e);
}

if (function_exists('memory_get_peak_usage'))
{
	if ($sOperation == 'file')
	{
		SetupWebPage::log_info("loading file '$sFileName', peak memory usage. ".memory_get_peak_usage());
	}
	else
	{
		SetupWebPage::log_info("operation '$sOperation', peak memory usage. ".memory_get_peak_usage());
	}
}
?>
