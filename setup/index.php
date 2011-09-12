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
 * Wizard to configure and initialize the iTop application
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../approot.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/core/config.class.inc.php');
require_once(APPROOT.'/core/log.class.inc.php');
require_once(APPROOT.'/core/kpi.class.inc.php');
require_once(APPROOT.'/core/cmdbsource.class.inc.php');
require_once(APPROOT.'/setup/setuppage.class.inc.php');
require_once(APPROOT.'/setup/moduleinstaller.class.inc.php');

define('TMP_CONFIG_FILE', APPROOT.'/tmp-config-itop.php');
define('FINAL_CONFIG_FILE', APPROOT.'/config-itop.php');
define('PHP_MIN_VERSION', '5.2.0');
define('MYSQL_MIN_VERSION', '5.0.0');
define('MIN_MEMORY_LIMIT', 32*1024*1024);
define('SUHOSIN_GET_MAX_VALUE_LENGTH', 1024); 

$sOperation = Utils::ReadParam('operation', 'step0');
$oP = new SetupWebPage('iTop configuration wizard');

///////////////////////////////////////////////////////////////////////////////////////////////////
// Various helper function
///////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Get a nicely formatted version string
 */
function GetITopVersion($bShort = true)
{
	$sVersionString = '';
	if ($bShort)
	{
		$sVersionString = "iTop Version ".ITOP_VERSION;
	}
	else
	{
		if (ITOP_REVISION == '$WCREV$')
		{
			// This is NOT a version built using the buil system, just display the main version
			$sVersionString = "iTop Version ".ITOP_VERSION;
		}
		else
		{
			// This is a build made from SVN, let display the full information
			$sVersionString = "iTop Version ".ITOP_VERSION." revision ".ITOP_REVISION.", built on: ".ITOP_BUILD_DATE;
		}
	}
	return $sVersionString;
}

/**
 * Helper function to retrieve the system's temporary directory
 * Emulates sys_get_temp_dir if neeed (PHP < 5.2.1) 
 * @return string Path to the system's temp directory 
 */
function GetTmpDir()
{
    // try to figure out what is the temporary directory
    // prior to PHP 5.2.1 the function sys_get_temp_dir
    // did not exist
    if ( !function_exists('sys_get_temp_dir'))
    {
        if( $temp=getenv('TMP') ) return realpath($temp);
        if( $temp=getenv('TEMP') ) return realpath($temp);
        if( $temp=getenv('TMPDIR') ) return realpath($temp);
        $temp=tempnam(__FILE__,'');
        if (file_exists($temp))
        {
            unlink($temp);
            return realpath(dirname($temp));
        }
        return null;
    }
    else
    {
        return realpath(sys_get_temp_dir());
    }
}

/**
 * Helper function to retrieve the directory where files are to be uploaded
 * @return string Path to the temp directory used for uploading files 
 */
function GetUploadTmpDir()
{
    $sPath = ini_get('upload_tmp_dir');
    if (empty($sPath))
    {
        $sPath = GetTmpDir();   
    }    
    return $sPath;
}

/**
 * Helper function to check if the current version of PHP
 * is compatible with the application
 * @return boolean true if this is Ok, false otherwise
 */
function CheckPHPVersion(SetupWebPage $oP)
{
	$bResult = true;
	$aErrors = array();
	$aWarnings = array();
	$aOk = array();
	
	$oP->log('Info - CheckPHPVersion');
	if (version_compare(phpversion(), PHP_MIN_VERSION, '>='))
	{
		$aOk [] = "The current PHP Version (".phpversion().") is greater than the minimum required version (".PHP_MIN_VERSION.")";
	}
	else
	{
		$aErrors[] = "Error: The current PHP Version (".phpversion().") is lower than the minimum required version (".PHP_MIN_VERSION.")";
		$bResult = false;
	}
	$aMandatoryExtensions = array('mysql', 'iconv', 'simplexml', 'soap', 'hash', 'json', 'session', 'pcre', 'dom');
	$aOptionalExtensions = array('mcrypt' => 'Strong encryption will not be used.',
								 'ldap' => 'LDAP authentication will be disabled.');
	asort($aMandatoryExtensions); // Sort the list to look clean !
	ksort($aOptionalExtensions); // Sort the list to look clean !
	$aExtensionsOk = array();
	$aMissingExtensions = array();
	$aMissingExtensionsLinks = array();
	// First check the mandatory extensions
	foreach($aMandatoryExtensions as $sExtension)
	{
		if (extension_loaded($sExtension))
		{
			$aExtensionsOk[] = $sExtension;
		}
		else
		{
			$aMissingExtensions[] = $sExtension;
			$aMissingExtensionsLinks[] = "<a href=\"http://www.php.net/manual/en/book.$sExtension.php\" target=\"_blank\">$sExtension</a>";
		}
	}
	if (count($aExtensionsOk) > 0)
	{
		$aOk[] = "Required PHP extension(s): ".implode(', ', $aExtensionsOk).".";
	}
	if (count($aMissingExtensions) > 0)
	{
		$aErrors[] = "Missing PHP extension(s): ".implode(', ', $aMissingExtensionsLinks).".";
		$bResult = false;
	}
	// Next check the optional extensions
	$aExtensionsOk = array();
	$aMissingExtensions = array();
	foreach($aOptionalExtensions as $sExtension => $sMessage)
	{
		if (extension_loaded($sExtension))
		{
			$aExtensionsOk[] = $sExtension;
		}
		else
		{
			$aMissingExtensions[$sExtension] = $sMessage;
		}
	}
	if (count($aExtensionsOk) > 0)
	{
		$aOk[] = "Optional PHP extension(s): ".implode(', ', $aExtensionsOk).".";
	}
	if (count($aMissingExtensions) > 0)
	{
		foreach($aMissingExtensions as $sExtension => $sMessage)
		{
			$aWarnings[] = "Missing optional PHP extension: $sExtension. ".$sMessage;
		}
	}
	// Check some ini settings here
	if (function_exists('php_ini_loaded_file')) // PHP >= 5.2.4
	{
		$sPhpIniFile = php_ini_loaded_file();
		// Other included/scanned files
		if ($sFileList = php_ini_scanned_files())
		{
		    if (strlen($sFileList) > 0)
		    {
		        $aFiles = explode(',', $sFileList);
		
		        foreach ($aFiles as $sFile)
		        {
		            $sPhpIniFile .= ', '.trim($sFile);
		        }
		    }
		}
		$oP->log("Info - php.ini file(s): '$sPhpIniFile'");
	}
	else
	{
		$sPhpIniFile = 'php.ini';
	}
  	if (!ini_get('file_uploads'))
  	{
		$aErrors[] = "Files upload is not allowed on this server (file_uploads = ".ini_get('file_uploads').").";
		$bResult = false;
	}

	$sUploadTmpDir = GetUploadTmpDir();
	if (empty($sUploadTmpDir))
	{
        $sUploadTmpDir = '/tmp';
		$aErrors[] = "Temporary directory for files upload is not defined (upload_tmp_dir), assuming that $sUploadTmpDir is used.";
	}
	// check that the upload directory is indeed writable from PHP
  	if (!empty($sUploadTmpDir))
  	{
  		if (!file_exists($sUploadTmpDir))
  		{
			$aErrors[] = "Temporary directory for files upload ($sUploadTmpDir) does not exist or cannot be read by PHP.";
			$bResult = false;
		}
  		else if (!is_writable($sUploadTmpDir))
  		{
			$aErrors[] = "Temporary directory for files upload ($sUploadTmpDir) is not writable.";
			$bResult = false;
		}
		else
		{
			$oP->log("Info - Temporary directory for files upload ($sUploadTmpDir) is writable.");
		}
	}
	

  	if (!ini_get('upload_max_filesize'))
  	{
		$aErrors[] = "File upload is not allowed on this server (upload_max_filesize = ".ini_get('upload_max_filesize').").";
	}

	$iMaxFileUploads = ini_get('max_file_uploads');
  	if (!empty($iMaxFileUploads) && ($iMaxFileUploads < 1))
  	{
		$aErrors[] = "File upload is not allowed on this server (max_file_uploads = ".ini_get('max_file_uploads').").";
		$bResult = false;
	}
	
	$iMaxUploadSize = utils::ConvertToBytes(ini_get('upload_max_filesize'));
	$iMaxPostSize = utils::ConvertToBytes(ini_get('post_max_size'));

	if ($iMaxPostSize <= $iMaxUploadSize)
	{
		$aWarnings[] = "post_max_size (".ini_get('post_max_size').") must be bigger than upload_max_filesize (".ini_get('upload_max_filesize')."). You may want to check the PHP configuration file(s): '$sPhpIniFile'. Be aware that this setting can also be overridden in the apache configuration.";
	}


	$oP->log("Info - upload_max_filesize: ".ini_get('upload_max_filesize'));
	$oP->log("Info - post_max_size: ".ini_get('post_max_size'));
	$oP->log("Info - max_file_uploads: ".ini_get('max_file_uploads'));

	// Check some more ini settings here, needed for file upload
	if (function_exists('get_magic_quotes_gpc'))
	{
	  	if (@get_magic_quotes_gpc())
	  	{
			$aErrors[] = "'magic_quotes_gpc' is set to On. Please turn it Off before continuing. You may want to check the PHP configuration file(s): '$sPhpIniFile'. Be aware that this setting can also be overridden in the apache configuration.";
			$bResult = false;
		}
	}
	if (function_exists('magic_quotes_runtime'))
	{
	  	if (@magic_quotes_runtime())
	  	{
			$aErrors[] = "'magic_quotes_runtime' is set to On. Please turn it Off before continuing. You may want to check the PHP configuration file(s): '$sPhpIniFile'. Be aware that this setting can also be overridden in the apache configuration.";
			$bResult = false;
		}
	}

	
	$sMemoryLimit = trim(ini_get('memory_limit'));
	if (empty($sMemoryLimit))
	{
		// On some PHP installations, memory_limit does not exist as a PHP setting!
		// (encountered on a 5.2.0 under Windows)
		// In that case, ini_set will not work, let's keep track of this and proceed anyway
		$aWarnings[] = "No memory limit has been defined in this instance of PHP";		
	}
	else
	{
		// Check that the limit will allow us to load the data
		//
		$iMemoryLimit = utils::ConvertToBytes($sMemoryLimit);
		if ($iMemoryLimit < MIN_MEMORY_LIMIT)
		{
			$aErrors[] = "memory_limit ($iMemoryLimit) is too small, the minimum value to run iTop is ".MIN_MEMORY_LIMIT.".";		
			$bResult = false;
		}
		else
		{
			$oP->log_info("memory_limit is $iMemoryLimit, ok.");		
		}
	}
	
	// Special case for APC
	if (extension_loaded('apc'))
	{
		$sAPCVersion = phpversion('apc');
		$aOk[] = "APC detected (version $sAPCVersion). The APC cache will be used to speed-up iTop.";
	}

	// Special case Suhosin extension
	if (extension_loaded('suhosin'))
	{
		$sSuhosinVersion = phpversion('suhosin');
		$aOk[] = "Suhosin extension detected (version $sSuhosinVersion).";
		
		$iGetMaxValueLength = ini_get('suhosin.get.max_value_length');
		if ($iGetMaxValueLength < SUHOSIN_GET_MAX_VALUE_LENGTH)
		{
			$aErrors[] = "suhosin.get.max_value_length ($iGetMaxValueLength) is too small, the minimum value to run iTop is ".SUHOSIN_GET_MAX_VALUE_LENGTH.". This value is set by the PHP configuration file(s): '$sPhpIniFile'. Be aware that this setting can also be overridden in the apache configuration.";		
			$bResult = false;
		}
		else
		{
			$oP->log_info("suhosin.get.max_value_length = $iGetMaxValueLength, ok.");		
		}
	}
	if (!$bResult)
	{
		$sTitle = 'Checking prerequisites: Failed !';
	}
	else
	{
		if (count($aWarnings) > 0)
		{
			$sTitle = '<img src="../images/messagebox_warning-mid.png" style="vertical-align:middle"> Checking prerequisites: Warning <a href="#" onClick="$(\'#prereq_details\').toggle();">(show details)</a>';
			$oP->add_ready_script("$('#prereq_details').hide();\n");
		}
		else
		{
			$sTitle = '<img src="../images/clean-mid.png" style="vertical-align:middle"> Checking prerequisites: Ok <a href="#" onClick="$(\'#prereq_details\').toggle();">(show details)</a>';
			$oP->add_ready_script("$('#prereq_details').hide();\n");
		}
	}
	$oP->add("<h2>$sTitle</h2>\n");
	$oP->add("<div id=\"prereq_details\">\n");
	foreach($aErrors as $sError)
	{
		$oP->error($sError);
		//$oP->add_ready_script("$('#prereq_details').show();");
	}	
	foreach($aWarnings as $sWarning)
	{
		$oP->warning($sWarning);
	}	
	foreach($aOk as $sOk)
	{
		$oP->ok($sOk);
	}	
	$oP->add("</div>\n");
	return $bResult;
}
 
/**
 * Helper function check the connection to the database and (if connected) to enumerate
 * the existing databases
 * @return Array The list of databases found in the server
 */
function CheckServerConnection(SetupWebPage $oP, $sDBServer, $sDBUser, $sDBPwd)
{
	$aResult = array();
	$oP->log('Info - CheckServerConnection');
	try
	{
		$oDBSource = new CMDBSource;
		$oDBSource->Init($sDBServer, $sDBUser, $sDBPwd);
		$oP->ok("Connection to '$sDBServer' as '$sDBUser' successful.");

		$oP->log("Info - User privileges: ".($oDBSource->GetRawPrivileges()));

		$sDBVersion = $oDBSource->GetDBVersion();
		if (version_compare($sDBVersion, MYSQL_MIN_VERSION, '>='))
		{
			$oP->ok("Current MySQL version ($sDBVersion), greater than minimum required version (".MYSQL_MIN_VERSION.")");
			// Check some server variables
			$iMaxAllowedPacket = $oDBSource->GetServerVariable('max_allowed_packet');
			$iMaxUploadSize = utils::ConvertToBytes(ini_get('upload_max_filesize'));
			if ($iMaxAllowedPacket >= (500 + $iMaxUploadSize)) // Allow some space for the query + the file to upload
			{
				$oP->ok("MySQL server's max_allowed_packet is big enough.");
			}
			else if($iMaxAllowedPacket < $iMaxUploadSize)
			{
				$oP->warning("MySQL server's max_allowed_packet ($iMaxAllowedPacket) is not big enough. Please, consider setting it to at least ".(500 + $iMaxUploadSize).".");
			}
			$oP->log("Info - MySQL max_allowed_packet: $iMaxAllowedPacket");
			$iMaxConnections = $oDBSource->GetServerVariable('max_connections');
			if ($iMaxConnections < 5)
			{
				$oP->warning("MySQL server's max_connections ($iMaxConnections) is not enough. Please, consider setting it to at least 5.");
			}
			$oP->log("Info - MySQL max_connections: ".($oDBSource->GetServerVariable('max_connections')));
		}
		else
		{
			$oP->error("Error: Current MySQL version is ($sDBVersion), minimum required version (".MYSQL_MIN_VERSION.")");
			return false;
		}
		try
		{
			$aResult = $oDBSource->ListDB();
		}
		catch(Exception $e)
		{
			$oP->warning("Warning: unable to enumerate the current databases.");
			$aResult = true; // Not an array to differentiate with an empty array
		}
	}
	catch(Exception $e)
	{
		$oP->error("Error: Connection to '$sDBServer' as '$sDBUser' failed.");
		$oP->p($e->GetHtmlDesc());
		$aResult = false;
	}
	return $aResult;
}

/**
 * Scans the ./data directory for XML files and output them as a Javascript array
 */ 
function PopulateDataFilesList(SetupWebPage $oP, $aParamValues, $oConfig)
{

	$sScript = "function PopulateDataFilesList()\n";
	$sScript .= "{\n";
	$sScript .= "if (aFilesToLoad.length > 0)  return;"; // Populate the list only once...

	$aAvailableModules = AnalyzeInstallation($oConfig);

	$sMode = $aParamValues['mode'];
	$aStructureDataFiles = array();
	$aSampleDataFiles = array();

	foreach($aAvailableModules as $sModuleId => $aModule)
	{
		if (($sModuleId != ROOT_MODULE))
		{
			if (in_array($sModuleId, $aParamValues['module']))
			{
				if (empty($aModule['version_db']))
				{
					// New installation load the data
					$aModuleStruct = $aAvailableModules[$sModuleId]['data.struct'];
					$aModuleSamples = $aAvailableModules[$sModuleId]['data.sample'];
					$aStructureDataFiles = array_merge($aStructureDataFiles, $aModuleStruct);
					$aSampleDataFiles = array_merge($aSampleDataFiles, $aModuleSamples);
				}
			}
		}
	}

	// Structure data
	//
	foreach($aStructureDataFiles as $sFile)
	{
		// Under Windows, it is a must to escape backslashes (not an issue until a folder name starts with t or n, etc...)
		$sFile = APPROOT.$sFile;
		$sFile = str_replace('\\', '\\\\', $sFile);
		$sScript .= "aFilesToLoad[aFilesToLoad.length] = '$sFile';\n";
	}

	// Sample data - loaded IIF wished by the user
	//
	if ($aParamValues['sample_data'] != 'no')
	{
		foreach($aSampleDataFiles as $sFile)
		{
			// Under Windows, it is a must to escape backslashes (not an issue until a folder name starts with t or n, etc...)
			$sFile = APPROOT.$sFile;
			$sFile = str_replace('\\', '\\\\', $sFile);
			$sScript .= "aFilesToLoad[aFilesToLoad.length] = '$sFile';\n";
		}
	}
	$sScript .= "}\n";
	$oP->add_script($sScript);
}

/**
 * Add some parameters as hidden inputs into a form
 * @param SetupWebpage $oP The page to insert the form elements into
 * @param Hash $aParamValues The pairs name/value to be stored in the form
 * @param Array $aExcludeParams A list of parameters to exclude from the previous hash
 */
function AddParamsToForm(SetupWebpage $oP, $aParamValues, $aExcludeParams = array())
{
	foreach($aParamValues as $sName => $value)
	{
		if(!in_array($sName, $aExcludeParams))
		{
			AddHiddenParam($oP, $sName, $value);
		}
	}
}

/**
 * Add a hidden <INPUT> field to store the specified parameter
 * @param $sName string Name of the parameter
 * @param $value mixed Value of the parameter
 */
function AddHiddenParam($oP, $sName, $value)
{
	if (is_array($value))
	{
		foreach($value as $sKey => $sItem)
		{
			$oP->add('<input type="hidden" name="'.$sName.'['.$sKey.']'.'" value="'.$sItem.'">');			
		}
	}
	else
	{
		$oP->add('<input type="hidden" name="'.$sName.'" value="'.$value.'">');			
	}
}

/**
 * Build the config file from the parameters (especially the selected modules)
 */
function BuildConfig(SetupWebpage $oP, Config &$oConfig, $aParamValues, $aAvailableModules)
{
	// Initialize the arrays below with default values for the application...
	$oEmptyConfig = new Config('dummy_file', false); // Do NOT load any config file, just set the default values
	$aAddOns = $oEmptyConfig->GetAddOns();
	$aAppModules = $oEmptyConfig->GetAppModules();
	$aDataModels = $oEmptyConfig->GetDataModels();
	$aWebServiceCategories = $oEmptyConfig->GetWebServiceCategories();
	$aDictionaries = $oEmptyConfig->GetDictionaries();
	// Merge the values with the ones provided by the modules
	// Make sure when don't load the same file twice...
	foreach($aParamValues['module'] as $sModuleId)
	{
		$oP->log('Installed iTop module: '. $sModuleId);
		if (isset($aAvailableModules[$sModuleId]['datamodel']))
		{
			$aDataModels = array_unique(array_merge($aDataModels, $aAvailableModules[$sModuleId]['datamodel']));
		}
		if (isset($aAvailableModules[$sModuleId]['webservice']))
		{
			$aWebServiceCategories = array_unique(array_merge($aWebServiceCategories, $aAvailableModules[$sModuleId]['webservice']));
		}
		if (isset($aAvailableModules[$sModuleId]['dictionary']))
		{
			$aDictionaries = array_unique(array_merge($aDictionaries, $aAvailableModules[$sModuleId]['dictionary']));
		}
		if (isset($aAvailableModules[$sModuleId]['settings']))
		{
			foreach($aAvailableModules[$sModuleId]['settings'] as $sProperty => $value)
			{
				list($sName, $sVersion) = GetModuleName($sModuleId);
				$oConfig->SetModuleSetting($sName, $sProperty, $value);
			}
		}
		if (isset($aAvailableModules[$sModuleId]['installer']))
		{
			$sModuleInstallerClass = $aAvailableModules[$sModuleId]['installer'];
			if (!class_exists($sModuleInstallerClass))
			{
				throw new Exception("Wrong installer class: '$sModuleInstallerClass' is not a PHP class - Module: ".$aAvailableModules[$sModuleId]['label']);
			}
			if (!is_subclass_of($sModuleInstallerClass, 'ModuleInstallerAPI'))
			{
				throw new Exception("Wrong installer class: '$sModuleInstallerClass' is not derived from 'ModuleInstallerAPI' - Module: ".$aAvailableModules[$sModuleId]['label']);
			}
			$aCallSpec = array($sModuleInstallerClass, 'BeforeWritingConfig');
			$oConfig = call_user_func_array($aCallSpec, array($oConfig));
		}
	}
	$oConfig->SetAddOns($aAddOns);
	$oConfig->SetAppModules($aAppModules);
	$oConfig->SetDataModels($aDataModels);
	$oConfig->SetWebServiceCategories($aWebServiceCategories);
	$oConfig->SetDictionaries($aDictionaries);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Handling of the different steps of the setup wizard
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Displays the welcome screen and check some basic prerequisites
 */
function WelcomeAndCheckPrerequisites(SetupWebPage $oP, $aParamValues, $iCurrentStep)
{
	$sNextOperation = 'step'.($iCurrentStep+1);
	$aParamValues['previous_step'] = 0;

	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$sVersionStringShort = GetITopVersion(true);
	$sVersionStringLong = GetITopVersion(false);
	$oP->set_title('Welcome to '.$sVersionStringShort);
	$oP->log($sVersionStringLong);
	$aPreviousParams = array();
	$oP->add("<form id=\"theForm\" method=\"post\" onSubmit=\"return DoSubmit('', 0)\">\n");
	$sMode = 'install'; // Fresh install

	// Check for a previous version
	if (file_exists(FINAL_CONFIG_FILE))
	{
		$oConfig = new Config(FINAL_CONFIG_FILE);
		$oConfig->WriteToFile(TMP_CONFIG_FILE);
		
		$aVersion = AnalyzeInstallation($oConfig);
		if (!empty($aVersion[ROOT_MODULE]['version_db']))
		{
			$aPreviousParams = array('mode', 'db_server', 'db_user', 'db_pwd','db_name', 'new_db_name', 'db_prefix');
			$sMode = 'upgrade';
			if ($aVersion[ROOT_MODULE]['version_db'] == $aVersion[ROOT_MODULE]['version_code'])
			{
				$oP->ok("Version ".$aVersion[ROOT_MODULE]['version_db']." of iTop detected.<br/>The <b>same version</b> of the application will be reinstalled.");
			}
			else
			{
				$oP->ok("Version ".$aVersion[ROOT_MODULE]['version_db']." of iTop detected.<br/>The application will be upgraded to version ".$aVersion[ROOT_MODULE]['version_code'].".");
			}
			AddHiddenParam($oP, 'db_server', $oConfig->GetDBHost());
			AddHiddenParam($oP, 'db_user', $oConfig->GetDBUser());
			AddHiddenParam($oP, 'db_pwd', $oConfig->GetDBPwd());
			AddHiddenParam($oP, 'db_name', $oConfig->GetDBName());
			AddHiddenParam($oP, 'db_prefix', $oConfig->GetDBSubname());
			AddHiddenParam($oP, 'mode', $sMode);
			if (CheckPHPVersion($oP))
			{
				$oP->add("<h2 class=\"next\">Next: Licence agreement</h2>\n");
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
				AddParamsToForm($oP, $aParamValues, $aPreviousParams);
				$oP->add("<table style=\"width:100%\"><tr>\n");
				$oP->add("<td style=\"text-align:right;\"><button type=\"submit\" type=\"submit\">Next >></button></td>\n");
				$oP->add("</tr></table>\n");
			}
			return;
		}
		// else, normal install ??
	}
	
	if (CheckPHPVersion($oP))
	{
		$oP->add("<h2>What do you want to do?</h2>\n");
		$sChecked = ($aParamValues['mode'] == 'install') ? 'checked' : '';
		$oP->p("<input id=\"choice_install\" type=\"radio\" value=\"install\" $sChecked name=\"mode\"><label for=\"choice_install\">&nbsp;Install a new iTop</label>");
		$sChecked = ($aParamValues['mode'] == 'upgrade') ? 'checked' : '';
		$oP->p("<input id=\"choice_upgrade\" type=\"radio\" value=\"upgrade\" $sChecked name=\"mode\"><label for=\"choice_upgrade\">&nbsp;Upgrade an existing iTop instance</label>");
		$oP->add("<h2 class=\"next\">Next: Licence agreement</h2>\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		$aPreviousParams = array('mode');
		AddParamsToForm($oP, $aParamValues, $aPreviousParams);
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:right;\"><button type=\"submit\">Next >></button></td>\n");
		$oP->add("</tr></table>\n");
		$oP->add("</form>\n");
	}
}

function LicenceAcknowledgement($oP, $aParamValues, $iCurrentStep)
{
	$sNextOperation = 'step'.($iCurrentStep+1);
	$iPrevStep = 0;
	$aParamValues['previous_step'] = $iCurrentStep; // Come back here	
	
	$oP->set_title('License agreement');
	$oP->add('<h2>iTop is released by <a href="http://www.combodo.com" target="_blank">Combodo SARL</a> under the terms of the GPL V3 license. In order to use iTop you must accept the terms of this license.</h2>');
	$oP->add("<iframe style=\"width: 100%; height: 350px; overflow-y:auto; font-size:0.8em;\" src=\"./licence.html\">Next: Database server selection</iframe>\n");
	$oP->add("<form id=\"theForm\" method=\"post\">\n");
	AddParamsToForm($oP, $aParamValues, array('licence_ok'));

	$sChecked = $aParamValues['licence_ok'] == 1 ? 'checked' : '';
	$oP->add("<h2><input id=\"licence_ok\" type=\"checkbox\" name=\"licence_ok\" value=\"1\" $sChecked><label for=\"licence_ok\">I accept the terms of this licence agreement</label></h2>\n");

	if (file_exists(FINAL_CONFIG_FILE))
	{
		$oP->add("<h2 class=\"next\">Next: Modules selection</h2>\n");		
		$sNextOperation = 'step4';
	}
	else
	{
		$oP->add("<h2 class=\"next\">Next: Database server selection</h2>\n");		
	}
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");	
	$oP->add("<table style=\"width:100%\"><tr>\n");
	$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iPrevStep)\"><< Back</button></td>\n");
	$oP->add("<td style=\"text-align:right;\"><input type=\"submit\" onClick=\"return DoSubmit('', $iCurrentStep)\" value=\" Next >> \"/></td>\n");
	$oP->add("</tr></table>\n");
	$oP->add("</form>\n");
}

/**
 * Display the form for the first step of the configuration wizard
 * which consists in the database server selection
 */  
function DatabaseServerSelection(SetupWebPage $oP, $aParamValues, $iCurrentStep)
{
	$sNextOperation = 'step'.($iCurrentStep+1);
	$iPrevStep = 1;
	$aParamValues['previous_step'] = $iCurrentStep; // Come back here	

	$oP->add("<form id=\"theForm\" method=\"post\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('db_server', 'db_user', 'db_pwd'));
	if ($aParamValues['licence_ok'] == 1)
	{
		$sRedStar = '<span class="hilite">*</span>';
		$oP->set_title("Database server selection\n");
		$oP->add("<h2>Please enter the name of the MySQL database server you want to use for iTop and supply valid credentials to connect to it</h2>\n");
		// Form goes here
		$oP->add("<fieldset><legend>Database connection</legend>\n");
		$aForm = array();
		$aForm[] = array('label' => "Server name$sRedStar:", 'input' => "<input id=\"db_server\" type=\"text\" name=\"db_server\" value=\"{$aParamValues['db_server']}\">",
						'help' => 'E.g. "localhost", "dbserver.mycompany.com" or "192.142.10.23"');
		$aForm[] = array('label' => "User name$sRedStar:", 'input' => "<input id=\"db_user\" type=\"text\" name=\"db_user\" autocomplete=\"off\" value=\"{$aParamValues['db_user']}\">",
						'help' => 'The account must have the following privileges on the database: SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, CREATE VIEW, TRIGGER');
		$aForm[] = array('label' => 'Password:', 'input' => "<input id=\"db_pwd\" type=\"password\" name=\"db_pwd\" autocomplete=\"off\" value=\"{$aParamValues['db_pwd']}\">");
		$oP->form($aForm);
		$oP->add("</fieldset>\n");
		$oP->add("<h2 class=\"next\">Next: Database instance Selection</h2>\n");
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iPrevStep)\"><< Back</button></td>\n");
		$oP->add("<td style=\"text-align:right;\"><input type=\"submit\" onClick=\"return DoSubmit('Connecting to the database...', $iCurrentStep);\" value=\" Next >> \"/></td>\n");
		$oP->add("</tr></table>\n");
	}
	else
	{
		$oP->add("<button type=\"button\" onClick=\"return DoGoBack($iPrevStep);\"><< Back</button>\n");		
	}
	$oP->add("</form>\n");
}

/**
 * Display the form for the second step of the configuration wizard
 * which consists in
 * 1) Validating the parameters by connecting to the database server
 * 2) Prompting to select an existing database or to create a new one  
 */  
function DatabaseInstanceSelection(SetupWebPage $oP, $aParamValues, $iCurrentStep, $oConfig)
{
	$sNextOperation = 'step'.($iCurrentStep+1);
	$iPrevStep = 2;
	$aParamValues['previous_step'] = $iCurrentStep; // Come back here	

	$oP->set_title("Database instance selection\n");
	$oP->add("<form id=\"theForm\" method=\"post\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('db_name', 'db_prefix', 'new_db_name'));
	$sDBServer = $aParamValues['db_server'];
	$sDBUser = $aParamValues['db_user'];
	$sDBPwd = $aParamValues['db_pwd'];
	$aDatabases = CheckServerConnection($oP, $sDBServer, $sDBUser, $sDBPwd);
	if ($aDatabases === false)
	{
		// Connection failed, invalid credentials ? Go back
		$oP->add("<button type=\"button\" onClick=\"return DoGoBack($iPrevStep);\"><< Back</button>\n");
	}
	else
	{
		// Connection is Ok, save it and continue the setup wizard
		$oConfig->SetDBHost($sDBServer);
		$oConfig->SetDBUser($sDBUser);
		$oConfig->SetDBPwd($sDBPwd);
		$oConfig->WriteToFile();

		$oP->add("<fieldset><legend>Select the database instance to use for iTop<span class=\"hilite\">*</span></legend>\n");
		$aForm = array();
		$bExistingChecked = false;
		$sChecked = '';
		$sDBName = '';
		// If the 'Create Database' option was checked... and the database still does not exist
		if (!$bExistingChecked && !empty($aParamValues['new_db_name']))
		{
			$sChecked = 'checked';
			$sDBName = $aParamValues['new_db_name'];
		}
		if ($aParamValues['mode'] == 'install')
		{
			$aForm[] = array('label' => "<input id=\"new_db\" type=\"radio\" name=\"db_name\" value=\"\" $sChecked/><label for=\"new_db\"> Create a new database:</label> <input type=\"text\" id=\"new_db_name\" name=\"new_db_name\" value=\"$sDBName\"  maxlength=\"32\"/>");
		}
		if (is_array($aDatabases))
		{
			foreach($aDatabases as $sDBName)
			{
				$sChecked = '';
				if ($aParamValues['db_name'] == $sDBName)
				{
					$sChecked = 'checked';
					$bExistingChecked = true;
				}
				$aForm[] = array('label' => "<input id=\"db_$sDBName\" type=\"radio\" name=\"db_name\" value=\"$sDBName\" $sChecked/><label for=\"db_$sDBName\"> $sDBName</label>");
			}
		}
		else
		{
			$aForm[] = array('label' => "<input id=\"current_db\" type=\"radio\" name=\"db_name\" value=\"-1\" /><label for=\"current_db\"> Use the existing database:</label> <input type=\"text\" id=\"current_db_name\" name=\"current_db_name\" value=\"\"  maxlength=\"32\"/>");			
			$oP->add_ready_script("$('#current_db_name').click( function() { $('#current_db').attr('checked', true); });");
		}
		$oP->add('<div style="height:250px;overflow-y:auto;padding-left:1em;">');
		$oP->form($aForm);
		$oP->add('</div>');

		$oP->add_ready_script("$('#new_db_name').click( function() { $('#new_db').attr('checked', true); })");
		$oP->add("</fieldset>\n");
		$aForm = array();
		if ($aParamValues['mode'] == 'install')
		{
			$aForm[] = array('label' => "Add a prefix to all the tables: <input id=\"db_prefix\" type=\"text\" name=\"db_prefix\" value=\"{$aParamValues['db_prefix']}\" maxlength=\"32\"/>");
		}
		else
		{
			$aForm[] = array('label' => "The following prefix is used for the iTop tables: <input id=\"db_prefix\" type=\"text\" name=\"db_prefix\" value=\"{$aParamValues['db_prefix']}\" maxlength=\"32\"/>");
		}
		$oP->form($aForm);

		$oP->add("<h2 class=\"next\">Next: iTop modules selection</h2>\n");
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iPrevStep)\"><< Back</button></td>\n");
		$oP->add("<td style=\"text-align:right;\"><input type=\"submit\" onClick=\"return DoSubmit('', $iCurrentStep);\" value=\" Next >> \"/></td>\n");
		$oP->add("</tr></table>\n");
	}
	$oP->add("</form>\n");
}

/**
 * Display the form to select the iTop modules to be installed
 */  
function ModulesSelection(SetupWebPage $oP, $aParamValues, $iCurrentStep, $oConfig)
{
	$sNextOperation = 'step'.($iCurrentStep+1);
	$aParamValues['previous_step'] = $iCurrentStep; // Come back here	
	
	$sDBName = $aParamValues['db_name'];
	if ($sDBName == '')
	{
		$sDBName = $aParamValues['new_db_name'];
	}

	$sDBPrefix = $aParamValues['db_prefix'];
	$oConfig->SetDBName($sDBName);
	$oConfig->SetDBSubname($sDBPrefix);
	$oConfig->WriteToFile(TMP_CONFIG_FILE);

	$oP->add("<form id=\"theForm\" method=\"post\">\n");
	AddParamsToForm($oP, $aParamValues, array('module'));
	$sRedStar = '<span class="hilite">*</span>';
	$oP->set_title("iTop modules selection");

	$aAvailableModules = AnalyzeInstallation($oConfig);
	
	// Form goes here
	if ($aParamValues['mode'] == 'upgrade')
	{
		if (file_exists(FINAL_CONFIG_FILE))
		{
			$iPrevStep = 1; // depends on where we came from		
		}
		else
		{
			$iPrevStep = 3;
		}
		if (empty($aAvailableModules[ROOT_MODULE]['version_db']))
		{
			$oP->error("Unable to detect the previous installation of iTop. The upgrade cannot continue.\n");	
			$oP->add("<table style=\"width:100%\"><tr>\n");
			$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iPrevStep)\"><< Back</button></td>\n");
			$oP->add("<td style=\"text-align:right;\">&nbsp;</td>\n");
			$oP->add("</tr></table>\n");
			$oP->add("</form>\n");
			return;
		}
		$oP->ok("iTop version ".$aAvailableModules[ROOT_MODULE]['version_db']." detected.\n");
		$oP->add("<h2>Customize your iTop installation to fit your needs</h2>\n");
		$oP->add("<fieldset><legend>Select the iTop modules you want to install or upgrade:</legend>\n");	
	}
	else
	{
		$iPrevStep = 3; // depends on where we came from
		if (!empty($aAvailableModules[ROOT_MODULE]['version_db']))
		{
			$oP->error("A instance of iTop already exists. Please select the \"Upgrade\" mode to upgrade it.\n");	
			$oP->add("<table style=\"width:100%\"><tr>\n");
			$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iPrevStep)\"><< Back</button></td>\n");
			$oP->add("<td style=\"text-align:right;\">&nbsp;</td>\n");
			$oP->add("</tr></table>\n");
			$oP->add("</form>\n");
			return;
		}
		$oP->add("<h2>Customize your iTop installation to fit your needs</h2>\n");
		$oP->add("<fieldset><legend>Select the iTop modules you want to install:</legend>\n");
	}
	$oP->add("<div style=\"border: 0;width:100%; height: 250px; overflow-y:auto;\">");
	$sRedStar = '<span class="hilite">*</span>';
	$index = 0;
	$aSelectedModules = $aParamValues['module'];
	if ($aSelectedModules == '')
	{
		// Make sure it gets initialized as an array, default value: all modules selected !
		$aSelectedModules = array();
		foreach($aAvailableModules as $sModuleId => $aModule)
		{
			$aSelectedModules[] = $sModuleId;
		}
	}
	foreach($aAvailableModules as $sModuleId => $aModule)
	{
		if ($sModuleId == ROOT_MODULE) continue; // Convention: the version number of the application is stored as a module named ROOT_MODULE

		$sModuleLabel = $aModule['label'];
		$sModuleHelp = $aModule['doc.more_information'];
		//$sClass = ($aModule['mandatory']) ? 'class="read-only"' : '';
		try
		{
			$sDefaultAppPath = 	utils::GetDefaultUrlAppRoot();		
		}
		catch(Exception $e)
		{
			$sDefaultAppPath = '..';
		}
		$sMoreInfo = (!empty($aModule['doc.more_information'])) ? "<a href=\"$sDefaultAppPath{$aModule['doc.more_information']}\" target=\"_blank\">more info</a>": '';
		if ($aModule['category'] == 'authentication')
		{
			// For now authentication modules are always on and hidden
			$oP->add("<input type=\"hidden\" id=\"module[$index]\" name=\"module[$index]\" value=\"$sModuleId\">\n");
			$index++;
		}
		elseif ($aModule['visible'])
		{
			switch($aModule['install']['flag'])
			{
				case MODULE_ACTION_OPTIONAL:
				$sClass = '';
				if ($aParamValues['mode'] == 'upgrade')
				{
					if (!empty($aParamValues['module']))
					{
						$sChecked = in_array($sModuleId, $aParamValues['module']) ? 'checked' : '';				
					}
					else
					{
						$sChecked = '';
						// Default value: modules previously installed are checked
						if (!empty($aModule['version_db']))
						{
							$sChecked = 'checked'; // Checked if previously installed
							// Previously installed, are we allowed to uninstall this module ?
							if ($aModule['install']['flag'] == MODULE_ACTION_IMPOSSIBLE)
							{
								$sClass = 'class="read-only"';
							}
						}
					}
				}
				else
				{
					if (!empty($aParamValues['module']))
					{
						$sChecked = in_array($sModuleId, $aParamValues['module']) ? 'checked' : '';				
					}
					else
					{
						$sChecked = 'checked';
					}
				}
				$oP->add("<p><input type=\"checkbox\" $sChecked $sClass id=\"module[$index]\" name=\"module[$index]\" value=\"$sModuleId\"><label $sClass for=\"module[$index]\"> {$aModule['label']}</label> $sMoreInfo</p>\n");
				break;
				
				case MODULE_ACTION_MANDATORY:
				$oP->add("<p><input type=\"checkbox\" class=\"read-only\" checked id=\"module[$index]\" name=\"module[$index]\" value=\"$sModuleId\"><label class=\"read-only\" for=\"module[$index]\"> {$aModule['label']}</label> $sMoreInfo</p>\n");
				break;
				
				case MODULE_ACTION_IMPOSSIBLE:
				if ($aParamValues['mode'] == 'upgrade')
				{
					if (!empty($aModule['version_db']))
					{
						// Previously installed, are we allowed to uninstall this module ?
						if ($aModule['uninstall']['flag'] == MODULE_ACTION_IMPOSSIBLE)
						{
							$oP->error('Error: impossible to uninstall the module: '.$aModule['label']."({$aModule['uninstall']['message']})");
						}
					}
				}
				else
				{
					$oP->add("<p><input type=\"checkbox\" class=\"read-only\" id=\"module[$index]\" name=\"module[$index]\" value=\"$sModuleId\"><label class=\"read-only\" for=\"module[$index]\"> {$aModule['label']}</label> $sMoreInfo</p>\n");
				}
				break;
				
			}
			$index++;
		}
		else
		{
			// For now hidden modules are always on !
			$oP->add("<input type=\"hidden\" id=\"module[$index]\" name=\"module[$index]\" value=\"$sModuleId\">\n");
			$index++;
		}
	}	
	$oP->add("</div>");
	$oP->add("</fieldset>\n");
	if ($aParamValues['mode'] == 'upgrade')
	{
		$oP->add("<h2 class=\"next\">Next: Application path</h2>\n");
		AddHiddenParam($oP, 'operation', 'step6');
	}
	else
	{
		$oP->add("<h2 class=\"next\">Next: Administrator account definition</h2>\n");
		AddHiddenParam($oP, 'operation', 'step5');
	}
	$oP->add("<table style=\"width:100%\"><tr>\n");
	$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iPrevStep)\"><< Back</button></td>\n");
	$oP->add("<td style=\"text-align:right;\"><input type=\"submit\" onClick=\"return DoSubmit('', 4)\" value=\" Next >> \"/></td>\n");
	$oP->add("</tr></table>\n");
	$oP->add("</form>\n");
	$oP->add_ready_script("$('.read-only').click( function() { $(this).attr('checked','checked'); } );");
	
}
/**
 * Display the form for the third step of the configuration wizard
 * which consists in
 * 1) Validating the parameters by connecting to the database server & selecting the database
 * 2) Creating the database structure  
 * 3) Prompting for the admin account to be created  
 */  
function AdminAccountDefinition(SetupWebPage $oP, $aParamValues, $iCurrentStep, Config $oConfig)
{
	$sNextOperation = 'step'.($iCurrentStep+1);
	$aParamValues['previous_step'] = $iCurrentStep; // Come back here	

	$oP->set_title("Administrator account definition");
	$oP->add("<form id=\"theForm\" method=\"post\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('auth_user', 'auth_pwd', 'language'));

	$aAvailableModules = AnalyzeInstallation($oConfig);
	BuildConfig($oP, $oConfig, $aParamValues, $aAvailableModules); // Load all the includes based on the modules selected
	$oConfig->WriteToFile(TMP_CONFIG_FILE);
	InitDataModel(TMP_CONFIG_FILE, true); // Needed to know the available languages
	$sRedStar = "<span class=\"hilite\">*</span>";
	$oP->add("<h2>Default language for the application:</h2>\n");
	// Possible languages (depends on the dictionaries loaded in the config)
	$aForm = array();
	$aAvailableLanguages = Dict::GetLanguages();
	$sLanguages = '';
	$sDefaultCode = $oConfig->GetDefaultLanguage();
	foreach($aAvailableLanguages as $sLangCode => $aInfo)
	{
		$sSelected = ($sLangCode == $sDefaultCode ) ? 'selected' : '';
		$sLanguages.="<option $sSelected value=\"{$sLangCode}\">{$aInfo['description']} ({$aInfo['localized_description']})</option>";
	}
	
	$aForm[] = array('label' => "Default Language$sRedStar:", 'input' => "<select id=\"language\" name=\"language\">$sLanguages</option>");
	$oP->form($aForm);
	$oP->add("<h2>Definition of the administrator account</h2>\n");
	// Database created, continue with admin creation		
	$oP->add("<fieldset><legend>Administrator account</legend>\n");
	$aForm = array();
	$aForm[] = array('label' => "Login$sRedStar:", 'input' => "<input id=\"auth_user\" type=\"text\" name=\"auth_user\" value=\"{$aParamValues['auth_user']}\">");
	$aForm[] = array('label' => "Password$sRedStar:", 'input' => "<input id=\"auth_pwd\" type=\"password\" name=\"auth_pwd\" value=\"{$aParamValues['auth_pwd']}\">");
	$aForm[] = array('label' => "Retype password$sRedStar:", 'input' => "<input  id=\"auth_pwd2\" type=\"password\" name=\"auth_pwd2\" value=\"{$aParamValues['auth_pwd']}\">");
	$oP->form($aForm);
	$oP->add("</fieldset>\n");
	$oP->add("<h2 class=\"next\">Next: Application path</h2>\n");
	$oP->add("<table style=\"width:100%\"><tr>\n");
	$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack(4)\"><< Back</button></td>\n");
	$oP->add("<td style=\"text-align:right;\"><input type=\"submit\" onClick=\"return DoSubmit('', 5);\" value=\" Next >> \"/></td>\n");
	$oP->add("</tr></table>\n");

	// Form goes here
	$oP->add("</form>\n");
}


/**
 * Display the form for validating/entering the URL (path) to the application
 * which consists in
 */  
function ApplicationPathSelection(SetupWebPage $oP, $aParamValues, $iCurrentStep, Config $oConfig)
{
	$sNextOperation = 'step7';
	if ($aParamValues['mode'] == 'upgrade')
	{
		$iPrevStep = 4;
	}
	else
	{
		$iPrevStep = 5;
	}

	$oP->set_title("Application Path");

	$oP->add("<form id=\"theForm\" method=\"post\"\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('application_path'));

	try
	{
		$sDefaultAppPath = 	utils::GetDefaultUrlAppRoot();		
	}
	catch(Exception $e)
	{
		$sDefaultAppPath = 'http://<your_host>/itop';
	}
	$sAppPath = empty($aParamValues['application_path']) ? $sDefaultAppPath : $aParamValues['application_path'];

	$oP->add("<h2>Enter the URL that will be used to connect to the application</h2>\n");
	$oP->p("<fieldset><legend> Application URL </legend>\n");
	$oP->p("<input type=\"text\" id=\"application_path\" size=\"60\" name=\"application_path\" value=\"$sAppPath\">\n");
	$oP->p("</fieldset>\n");	
	$oP->add("<h2 class=\"next\">Next: Sample data selection</h2>\n");
	$oP->add("<table style=\"width:100%\"><tr>\n");
	$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iPrevStep)\"><< Back</button></td>\n");
	$oP->add("<td style=\"text-align:right;\"><input type=\"submit\" onClick=\"return DoSubmit('', 6)\" value=\" Next >> \"/></td>\n");
	$oP->add("</tr></table>\n");
	$oP->add("</form>\n");
}

/**
 * Display the form for the fourth step of the configuration wizard
 * which consists in
 * 1) Creating the admin user account
 * 2) Prompting to load some sample data  
 */  
function SampleDataSelection(SetupWebPage $oP, $aParamValues, $iCurrentStep, Config $oConfig)
{
	$sNextOperation = 'step8';
	$iPrevStep = 6;

	$oP->set_title("Application initialization");
	$sAdminUser = $aParamValues['auth_user'];
	$sAdminPwd = $aParamValues['auth_pwd'];
	$sLanguage = $aParamValues['language'];
	if (($aParamValues['mode'] == 'install') ||  $oConfig->GetDefaultLanguage() == '')
	{
		$oConfig->SetDefaultLanguage($aParamValues['language']);
	}
	$aAvailableModules = AnalyzeInstallation($oConfig);
	BuildConfig($oP, $oConfig, $aParamValues, $aAvailableModules); // Load all the includes based on the modules selected

	$oConfig->Set('app_root_url', $aParamValues['application_path']);
	// in case of upgrade, the value is already present in the config file
	$oConfig->WriteToFile(TMP_CONFIG_FILE);

	$oP->add("<form id=\"theForm\" method=\"post\"\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('sample_data'));

	InitDataModel(TMP_CONFIG_FILE, true);  // load data model and connect to the database
	$aAvailableModules = GetAvailableModules($oP);
	foreach($aParamValues['module'] as $sModuleId)
	{
		if (isset($aAvailableModules[$sModuleId]['installer']))
		{
			$sModuleInstallerClass = $aAvailableModules[$sModuleId]['installer'];
			// The validity of the sModuleInstallerClass has been established in BuildConfig() 
			$aCallSpec = array($sModuleInstallerClass, 'AfterDatabaseCreation');
			call_user_func_array($aCallSpec, array($oConfig));
		}
	}

	$oP->add("<h2>Loading of sample data</h2>\n");
	$oP->p("<fieldset><legend> Do you want to load sample data into the database ? </legend>\n");
	$sChecked = ($aParamValues['sample_data'] == 'no') ? '' : 'checked';
	$oP->p("<input type=\"radio\" id=\"sample_data\" name=\"sample_data\" id=\"sample_data_no\" $sChecked value=\"yes\"><label for=\"sample_data_yes\"> Yes, for testing purposes, populate the database with sample data.</label>\n");
	$sChecked = ($aParamValues['sample_data'] == 'no') ? 'checked' : '';
	$oP->p("<input type=\"radio\" name=\"sample_data\" unchecked id=\"sample_data_no\" $sChecked value=\"no\"><label for=\"sample_data_no\"> No, this is a production system, load only the data required by the application.</label>\n");
	$oP->p("</fieldset>\n");	
	$oP->add("<h2 class=\"next\">Next: Installation summary</h2>\n");
	$oP->add("<table style=\"width:100%\"><tr>\n");
	$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iPrevStep)\"><< Back</button></td>\n");
	$oP->add("<td style=\"text-align:right;\"><input type=\"submit\" onClick=\"DoSubmit('', 7)\" value=\" Next >> \"/></td>\n");
	$oP->add("</tr></table>\n");
	$oP->add("</form>\n");
}

/**
 * Displays the summary of the actions to be taken
 */
function DisplaySummary(SetupWebPage $oP, $aParamValues, $iCurrentStep, Config $oConfig)
{
	$sMode = $aParamValues['mode'];
	$aAvailableModules = AnalyzeInstallation($oConfig);
	BuildConfig($oP, $oConfig, $aParamValues, $aAvailableModules); // Load all the includes based on the modules selected
	$oConfig->WriteToFile(TMP_CONFIG_FILE);
	InitDataModel(TMP_CONFIG_FILE, true); // Needed to know the available languages
	
	$aInstall = array();
	$aUpgrade = array();
	$aUninstall = array();
	$aUnchanged = array();
	switch($sMode)
	{
		case 'install':
		foreach($aAvailableModules as $sModuleId => $aModule)
		{
			if (($sModuleId != ROOT_MODULE) && $aModule['visible'] && ($aModule['category'] != 'authentication'))
			{
				if (in_array($sModuleId, $aParamValues['module']))
				{
						$aInstall[$sModuleId] = $aModule;
				}
			}
		}
		$oP->set_title('Installation Summary');
		$oP->add("<h3>iTop version ".$aAvailableModules[ROOT_MODULE]['version_code']." will be installed.</h3>");

		$oP->add('<div id="summary_content" style="height:350px;overflow-y:auto;border:1px solid #999;padding-left:1em;">');

		// Database information
		$sPrefix = '';
		if ($oConfig->GetDBSubname() != '')
		{
			$sPrefix = " (prefix: ".$oConfig->GetDBSubname().")";
		}
		$oP->collapsible('db', "Database", array($oConfig->GetDBName()." on server: ".$oConfig->GetDBHost().$sPrefix));

		if (count($aInstall) > 0)
		{
			$iCount = count($aInstall);
			$aItems = array();
			foreach($aInstall as $sModuleId => $aModule)
			{
				$aItems[] = $aModule['label'].' version '.$aModule['version_code'];
			}		
			$oP->collapsible('install', "$iCount module(s) will be installed", $aItems, ($iCount < 5));
		}
		
		// Sample data
		if ($aParamValues['sample_data'] != 'no')
		{
			$sSampleData = 'Sample data will be loaded for the newly installed modules.';
		}
		else
		{
			$sSampleData = 'No sample data will be loaded.';
		}
		$oP->collapsible('sample_data', "Sample Data", array($sSampleData));
		
		// Application Path
		$oP->collapsible('application_path', "Application path", array('URL:'.htmlentities($aParamValues['application_path'], ENT_QUOTES, 'UTF-8')));

		// Admin account
		$oP->collapsible('admin_account', "Administrator account", array('Login:'.htmlentities($aParamValues['auth_user'], ENT_QUOTES, 'UTF-8')));
		// Default language
		$aAvailableLanguages = Dict::GetLanguages();
		$oP->collapsible('language', "Default application language", array( $aAvailableLanguages[$aParamValues['language']]['description']." (".$aAvailableLanguages[$aParamValues['language']]['localized_description'].")"));
		$oP->add('</div>');
		
		$oP->add("<form id=\"theForm\" method=\"post\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"step9\">\n");
		AddParamsToForm($oP, $aParamValues);
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack(7)\"><< Back</button></td>\n");
		// Note: the Next >> button is NOT a submit, since moving to the next page is triggered asynchronously
		$oP->add("<td style=\"text-align:right;\"><button type=\"button\" onClick=\"DoSubmit('Installing...', 8)\"> Install ! </button></td>\n");
		$oP->add("</tr></table>\n");
		$oP->add("</form>\n");
		break;
		
		case 'upgrade':
		
		foreach($aAvailableModules as $sModuleId => $aModule)
		{
			if (($sModuleId != ROOT_MODULE) && $aModule['visible'] && ($aModule['category'] != 'authentication'))
			{
				if (in_array($sModuleId, $aParamValues['module']))
				{
					if (empty($aModule['version_db']))
					{
						$aInstall[$sModuleId] = $aModule;
					}
					else if ($aModule['version_db'] == $aModule['version_code'])
					{
						$aUnchanged[$sModuleId] = $aModule;
					}
					else
					{
						// Consider it's an upgrade... TO DO: handle downgrades ??
						$aUpgrade[$sModuleId] = $aModule;
					}	
				}
				else if (!empty($aModule['version_db']))
				{
					$aUninstall[$sModuleId] = $aModule;	
				}
				// Else do nothing: the module was not installed and is not selected
			}
		}
		$oP->set_title('Upgrade Summary');
		$oP->add("<h3>The application will be upgraded from version ".$aAvailableModules[ROOT_MODULE]['version_db'].' to version '.$aAvailableModules[ROOT_MODULE]['version_code'].'</h3>');
		$oP->add('<div id="summary_content" style="height:350px;overflow-y:auto;border:1px solid #999;padding-left:1em;">');

		// Database information
		$sPrefix = '';
		if ($oConfig->GetDBSubname() != '')
		{
			$sPrefix = " (prefix: ".$oConfig->GetDBSubname().")";
		}
		$oP->collapsible('db', "Database", array($oConfig->GetDBName()." on server: ".$oConfig->GetDBHost().$sPrefix));

		// Modules summary, per "type" (install, uninstall...)
		if (count($aUpgrade) > 0) 
		{
			$iCount = count($aUpgrade);
			$aItems = array();
			foreach($aUpgrade as $sModuleId => $aModule)
			{
				$aItems[] = $aModule['label'].' version '.$aModule['version_db'].' to version '.$aModule['version_code'];
			}		
			$oP->collapsible('upgrade', "$iCount module(s) will be upgraded", $aItems, ($iCount < 5));
		}
		if (count($aInstall) > 0)
		{
			$iCount = count($aInstall);
			$aItems = array();
			foreach($aInstall as $sModuleId => $aModule)
			{
				$aItems[] = $aModule['label'].' version '.$aModule['version_code'];
			}		
			$oP->collapsible('install', "$iCount module(s) will be installed", $aItems, ($iCount < 5));
		}
		if (count($aUninstall) > 0)
		{
			$iCount = count($aUninstall);
			$aItems = array();
			foreach($aUninstall as $sModuleId => $aModule)
			{
				$aItems[] = $aModule['label'].' version '.$aModule['version_db'];
			}		
			$oP->collapsible('uninstall', "$iCount module(s) will be removed", $aItems, true /* always open */);
		}
		if (count($aUnchanged) > 0)
		{
			$iCount = count($aUnchanged);
			$aItems = array();
			foreach($aUnchanged as $sModuleId => $aModule)
			{
				$aItems[] = $aModule['label'].' version '.$aModule['version_db'];
			}		
			$oP->collapsible('unchanged', "$iCount module(s) will remain unchanged", $aItems, ($iCount < 5));
		}
		// Sample data
		if ($aParamValues['sample_data'] != 'no')
		{
			$sSampleData = 'Sample data will be loaded for the newly installed modules.';
		}
		else
		{
			$sSampleData = 'No sample data will be loaded.';
		}
		$oP->collapsible('sample_data', "Sample Data", array($sSampleData));

		// Application Path
		$oP->collapsible('application_path', "Application path", array('URL:'.htmlentities($aParamValues['application_path'], ENT_QUOTES, 'UTF-8')));
		
		$oP->add('</div>');
		$oP->add("<form id=\"theForm\" method=\"post\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"step8\">\n");
		AddParamsToForm($oP, $aParamValues);
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"DoGoBack(6)\"><< Back</button></td>\n");
		// Note: the Next >> button is NOT a submit, since moving to the next page is triggered asynchronously
		$oP->add("<td style=\"text-align:right;\"><button type=\"button\" onClick=\"DoSubmit('', 8)\"> Upgrade ! </button></td>\n");
		$oP->add("</tr></table>\n");
		$oP->add("</form>\n");
		break;
		
		default:
		$oP->error("Unsupported mode $sMode");
	}
	// Hidden form submitted when moving on to the next page, once all the data files
	// have been processed
	$oP->add("<form id=\"GoToNextStep\" method=\"post\">\n");
	AddParamsToForm($oP, $aParamValues);
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"step9\">\n");
	$oP->add("</form>\n");
	
	$oP->add("<div id=\"log\" style=\"color:#F00;\"></div>\n");
	$oP->add_linked_script('./jquery.progression.js');
	PopulateDataFilesList($oP, $aParamValues, $oConfig);
	$oP->add_ready_script(
<<<EOF
		$('#log').ajaxError(
				function(e, xhr, settings, exception)
				{
					bStopAysncProcess = true;
					alert('Fatal error detected: '+ xhr.responseText);
					$('#log').append(xhr.responseText);
					$('#setup').unblock();
				} );
EOF
);
}

/** Display the form for the fifth (and final) step of the configuration wizard
 * which consists in
 * 1) Creating the final configuration file
 * 2) Prompting the user to make the file read-only  
 */  
function SetupFinished(SetupWebPage $oP, $aParamValues, $iCurrentStep, Config $oConfig)
{
	$sAuthUser = $aParamValues['auth_user'];
	$sAuthPwd = $aParamValues['auth_pwd'];
	$iPrevStep = $aParamValues['previous_step'];
	$aParamValues['previous_step'] = $iCurrentStep; // Come back here	

	try
	{
		$sSessionName = $oConfig->Get('session_name');
		if ($sSessionName != '')
		{
			$sSessionName = sprintf('iTop-%x', rand());
			$oConfig->Set('session_name', $sSessionName);
		}
		session_name($sSessionName);
		session_start();
		
		// Migration: force utf8_unicode_ci as the collation to make the global search
		// NON case sensitive
		$oConfig->SetDBCollation('utf8_unicode_ci');
		
		
		// Write the final configuration file
		$oConfig->WriteToFile(FINAL_CONFIG_FILE);

		// Start the application
		InitDataModel(FINAL_CONFIG_FILE, false, true); // Load model, startup DB and load the cache
		if ($aParamValues['mode'] == 'install')
		{
			if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
			{
				UserRights::Login($sAuthUser);
				$_SESSION['auth_user'] = $sAuthUser;
				$_SESSION['login_mode'] = 'form'; // Will enable the "log-off button"
			}
			else
			{
				$oP->add("<h1>iTop configuration wizard</h1>\n");
				$oP->add("<h2>Step 5: Configuration completed</h2>\n");
				
				@unlink(FINAL_CONFIG_FILE); // remove the aborted config
				$oP->error("Error: Failed to login for user: '$sAuthUser'\n");
	
				$oP->add("<form id=\"theForm\" method=\"post\">\n");
				$oP->add("<button type=\"button\" onClick=\"return DoGoBack($iPrevStep);\"><< Back</button>\n");
				AddParamsToForm($oP, $aParamValues);
				$oP->add("<input type=\"hidden\" name=\"operation\" value=\"step0\">\n");
				$oP->add("</form>\n");
				return;
			}
		}
			
		// remove the tmp config file
		@unlink(TMP_CONFIG_FILE);
		// try to make the final config file read-only
		@chmod(FINAL_CONFIG_FILE, 0440); // Read-only for owner and group, nothing for others
		
		$oP->set_title("Setup complete");
		$oP->add("<form id=\"theForm\" method=\"get\" action=\"../index.php\">\n");

		// Check if there are some manual steps required:
		$aAvailableModules = AnalyzeInstallation($oConfig);
		$aManualSteps = array();
		$sRootUrl = utils::GetAbsoluteUrlAppRoot();
		foreach($aParamValues['module'] as $sModuleId)
		{
			if (!empty($aAvailableModules[$sModuleId]['doc.manual_setup']))
			{
				$aManualSteps[$aAvailableModules[$sModuleId]['label']] = $sRootUrl.$aAvailableModules[$sModuleId]['doc.manual_setup'];
			}
		}
		if (count($aManualSteps) > 0)
		{
			$oP->add("<h2>Manual operations required</h2>");
			$oP->p("In order to complete the installation, the following manual operations are required:");
			foreach($aManualSteps as $sModuleLabel => $sUrl)
			{
				$oP->p("<a href=\"$sUrl\" target=\"_blank\">Manual instructions for $sModuleLabel</a>");
			}
			$oP->add("<h2>Congratulations for installing iTop</h2>");
		}
		else
		{
			$oP->add("<h2>Congratulations for installing iTop</h2>");
			$oP->ok("The initialization completed successfully.");
		}
		// Form goes here.. No back button since the job is done !
		$oP->add('<table style="width:600px;border:0;padding:0;"><tr>');
		$oP->add("<td><a style=\"background:transparent;padding:0;\" title=\"Free: Register your iTop version.\" href=\"http://www.combodo.com/register?product=iTop&version=".urlencode(ITOP_VERSION." revision ".ITOP_REVISION)."\" target=\"_blank\"><img style=\"border:0\" src=\"../images/setup-register.gif\"/></td></a>");
		$oP->add("<td><a style=\"background:transparent;padding:0;\" title=\"Get Professional Support from Combodo\" href=\"http://www.combodo.com/itopsupport\" target=\"_blank\"><img style=\"border:0\" src=\"../images/setup-support.gif\"/></td></a>");
		$oP->add("<td><a style=\"background:transparent;padding:0;\" title=\"Get Professional Training from Combodo\" href=\"http://www.combodo.com/itoptraining\" target=\"_blank\"><img style=\"border:0\" src=\"../images/setup-training.gif\"/></td></a>");
		$oP->add('</tr></table>');
		$oP->add("<p style=\"text-align:center;width:100%\"><button type=\"submit\">Enter iTop</button></p>\n");
		$oP->add("</form>\n");
	}
	catch(Exception $e)
	{
		$oP->error("Error: unable to create the configuration file.");
		$oP->p($e->getHtmlDesc());
		$oP->p("Did you forget to remove the previous (read-only) configuration file ?");
		$oP->add("<form id=\"theForm\" method=\"post\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"step7\">\n");
		AddParamsToForm($oP, $aParamValues, array('previous_step'));
		$oP->add("<button type=\"button\" onClick=\"return DoGoBack(7);\"><< Back</button>\n");
		$oP->add("</form>\n");
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
// Main program
///////////////////////////////////////////////////////////////////////////////////////////////////

clearstatcache(); // Make sure we know what we are doing !
// Set a long (at least 4 minutes) execution time for the setup to avoid timeouts during this phase
ini_set('max_execution_time', max(240, ini_get('max_execution_time')));
// While running the setup it is desirable to see any error that may happen
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

$aParams = array('mode', 'previous_step', 'licence_ok', 'db_server', 'db_user', 'db_pwd','db_name', 'new_db_name', 'db_prefix', 'module', 'sample_data', 'auth_user', 'auth_pwd', 'language', 'application_path');
foreach($aParams as $sName)
{
	$aParamValues[$sName] = utils::ReadParam($sName, '', false, 'raw_data');
}

if (file_exists(FINAL_CONFIG_FILE))
{
	// The configuration file already exists
	if (!is_writable(FINAL_CONFIG_FILE))
	{
		$oP->add("<h1>iTop configuration wizard</h1>\n");
		$oP->add("<h2>Fatal error</h2>\n");
		$oP->error("<b>Error:</b> the configuration file '".FINAL_CONFIG_FILE."' already exists and cannot be overwritten.");
		$oP->p("The wizard cannot modify the configuration file for you. If you want to upgrade iTop, please make sure that the file '<b>".realpath(FINAL_CONFIG_FILE)."</b>' can be modified by the web server.");
		$oP->output();
		exit;
	}
}
else
{
	// No configuration file yet
	// Check that the wizard can write into the root dir to create the configuration file
	if (!is_writable(dirname(TMP_CONFIG_FILE)))
	{
		$oP->add("<h1>iTop configuration wizard</h1>\n");
		$oP->add("<h2>Fatal error</h2>\n");
		$oP->error("<b>Error:</b> the directory where to store the configuration file is not writable.");
		$oP->p("The wizard cannot create the configuration file for you. Please make sure that the directory '<b>".realpath(dirname(TMP_CONFIG_FILE))."</b>' is writable for the web server.");
		$oP->output();
		exit;
	}
	if (!is_writable(dirname(TMP_CONFIG_FILE).'/setup'))
	{
		$oP->add("<h1>iTop configuration wizard</h1>\n");
		$oP->add("<h2>Fatal error</h2>\n");
		$oP->error("<b>Error:</b> the directory where to store temporary setup files is not writable.");
		$oP->p("The wizard cannot create operate. Please make sure that the directory '<b>".realpath(dirname(TMP_CONFIG_FILE))."/setup</b>' is writable for the web server.");
		$oP->output();
		exit;
	}
	
}
try
{
	$oConfig = new Config(TMP_CONFIG_FILE);
}
catch(Exception $e)
{
	// We'll end here when the tmp config file does not exist. It's normal
	$oConfig = new Config(TMP_CONFIG_FILE, false /* Don't try to load it */);
}
try
{
	switch($sOperation)
	{
		case 'step0':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 0 ========");
		WelcomeAndCheckPrerequisites($oP, $aParamValues, 0);
		break;

		case 'step1':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 1 ========");
		LicenceAcknowledgement($oP, $aParamValues, 1);
		break;

		case 'step2':
		$oP->log("Info - ========= Wizard step 2 ========");
		DatabaseServerSelection($oP, $aParamValues, 2);
		break;
		
		case 'step3':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 3 ========");
		DatabaseInstanceSelection($oP, $aParamValues, 3, $oConfig);
		break;

		case 'step4':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 4 ========");
		ModulesSelection($oP, $aParamValues, 4, $oConfig);
		break;
	
		case 'step5':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 5 ========");
		AdminAccountDefinition($oP, $aParamValues, 5, $oConfig);
		break;
	
		case 'step6':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 6 ========");
		ApplicationPathSelection($oP, $aParamValues, 6, $oConfig);
		break;

		case 'step7':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 7 ========");
		SampleDataSelection($oP, $aParamValues, 6, $oConfig);
		break;

		case 'step8':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 8 ========");
		DisplaySummary($oP, $aParamValues, 7, $oConfig);
		break;
	
		case 'step9':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 9 ========");
		SetupFinished($oP, $aParamValues, 8, $oConfig);
		break;
			
		default:
		$oP->error("Error: unsupported operation '$sOperation'");
		
	}
}
catch(Exception $e)
{
	$oP->error("Error: '".$e->getMessage()."'");	
	$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
}
catch(CoreException $e)
{
	$oP->error("Error: '".$e->getHtmlDesc()."'");	
	$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
}
$oP->output();
?>