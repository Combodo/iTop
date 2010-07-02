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

require_once('../application/utils.inc.php');
require_once('../core/config.class.inc.php');
require_once('../core/log.class.inc.php');
require_once('../core/cmdbsource.class.inc.php');
require_once('./setuppage.class.inc.php');

define('TMP_CONFIG_FILE', '../tmp-config-itop.php');
define('FINAL_CONFIG_FILE', '../config-itop.php');
define('SETUP_STRUCTURE_DATA_DIR', './data/structure');
define('SETUP_SAMPLE_DATA_DIR', './data');
define('PHP_MIN_VERSION', '5.2.0');
define('MYSQL_MIN_VERSION', '5.0.0');
define('MIN_MEMORY_LIMIT', 32*1024*1024);


$sOperation = Utils::ReadParam('operation', 'step0');
$oP = new SetupWebPage('iTop configuration wizard');

///////////////////////////////////////////////////////////////////////////////////////////////////
// Various helper function
///////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Get a nicely formatted version string
 */
function GetITopVersion()
{
	$sVersionString = '';
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
 * Check the value of the PHP setting 'memory_limit'
 * against the minimum recommended value
 * @param SetpWebPage $oP The current web page
 * @param integer $iMinMemoryRequired The minimum memory for the test to pass
 * @return boolean Whether or not it's Ok to continue
 */
function CheckMemoryLimit(SetupWebPage $oP, $iMinMemoryRequired)
{
	$sMemoryLimit = trim(ini_get('memory_limit'));
	$bResult = true;
	if (empty($sMemoryLimit))
	{
		// On some PHP installations, memory_limit does not exist as a PHP setting!
		// (encountered on a 5.2.0 under Windows)
		// In that case, ini_set will not work, let's keep track of this and proceed anyway
		$oP->warning("No memory limit has been defined in this instance of PHP");		
	}
	else
	{
		// Check that the limit will allow us to load the data
		//
		$iMemoryLimit = utils::ConvertToBytes($sMemoryLimit);
		if ($iMemoryLimit < $iMinMemoryRequired)
		{
			$oP->error("memory_limit ($iMemoryLimit) is too small, the minimum value to run iTop is $iMinMemoryRequired.");		
			$bResult = false;
		}
		else
		{
			$oP->log_info("memory_limit is $iMemoryLimit, ok.");		
		}
	}
	return $bResult;
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
	$oP->log('Info - CheckPHPVersion');
	if (version_compare(phpversion(), PHP_MIN_VERSION, '>='))
	{
		$oP->ok("The current PHP Version (".phpversion().") is greater than the minimum required version (".PHP_MIN_VERSION.")");
	}
	else
	{
		$oP->error("Error: The current PHP Version (".phpversion().") is lower than the minimum required version (".PHP_MIN_VERSION.")");
		return false;
	}
	$aMandatoryExtensions = array('mysql', 'iconv', 'simplexml', 'soap');
	asort($aMandatoryExtensions); // Sort the list to look clean !
	$aExtensionsOk = array();
	$aMissingExtensions = array();
	$aMissingExtensionsLinks = array();
	foreach($aMandatoryExtensions as $sExtension)
	{
		if (extension_loaded($sExtension))
		{
			$aExtensionsOk[] = $sExtension;
		}
		else
		{
			$aMissingExtensions[] = $sExtension;
			$aMissingExtensionsLinks[] = "<a href=\"http://www.php.net/manual/en/book.$sExtension.php\">$sExtension</a>";
		}
	}
	if (count($aExtensionsOk) > 0)
	{
		$oP->ok("Required PHP extension(s): ".implode(', ', $aExtensionsOk).".");
	}
	if (count($aMissingExtensions) > 0)
	{
		$oP->error("Missing PHP extension(s): ".implode(', ', $aMissingExtensionsLinks).".");
		$bResult = false;
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
		$oP->error("Files upload is not allowed on this server (file_uploads = ".ini_get('file_uploads').").");
		$bResult = false;
	}

	$sUploadTmpDir = GetUploadTmpDir();
	if (empty($sUploadTmpDir))
	{
        $sUploadTmpDir = '/tmp';
		$oP->warning("Temporary directory for files upload is not defined (upload_tmp_dir), assuming that $sUploadTmpDir is used.");
	}
	// check that the upload directory is indeed writable from PHP
  	if (!empty($sUploadTmpDir))
  	{
  		if (!file_exists($sUploadTmpDir))
  		{
			$oP->error("Temporary directory for files upload ($sUploadTmpDir) does not exist or cannot be read by PHP.");
			$bResult = false;
		}
  		else if (!is_writable($sUploadTmpDir))
  		{
			$oP->error("Temporary directory for files upload ($sUploadTmpDir) is not writable.");
			$bResult = false;
		}
		else
		{
			$oP->log("Info - Temporary directory for files upload ($sUploadTmpDir) is writable.");
		}
	}
	

  	if (!ini_get('upload_max_filesize'))
  	{
		$oP->error("File upload is not allowed on this server (file_uploads = ".ini_get('file_uploads').").");
	}

	$iMaxFileUploads = ini_get('max_file_uploads');
  	if (!empty($iMaxFileUploads) && ($iMaxFileUploads < 1))
  	{
		$oP->error("File upload is not allowed on this server (max_file_uploads = ".ini_get('max_file_uploads').").");
		$bResult = false;
	}
	$oP->log("Info - upload_max_filesize: ".ini_get('upload_max_filesize'));
	$oP->log("Info - max_file_uploads: ".ini_get('max_file_uploads'));

	// Check some more ini settings here, needed for file upload
  	if (get_magic_quotes_gpc())
  	{
		$oP->error("'magic_quotes_gpc' is set to On. Please turn it Off before continuing. You may want to check the PHP configuration file(s): '$sPhpIniFile'. Be aware that this setting can also be overridden in the apache configuration.");
		$bResult = false;
	}
	
	$bResult = $bResult & CheckMemoryLimit($oP, MIN_MEMORY_LIMIT);
	
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
 * Helper function to initialize the ORM and load the data model
 * from the given file
 * @param $sConfigFileName string The name of the configuration file to load
 * @param $bAllowMissingDatabase boolean Whether or not to allow loading a data model with no corresponding DB 
 * @return none
 */    
function InitDataModel(SetupWebPage $oP, $sConfigFileName, $bAllowMissingDatabase = true)
{
	require_once('../core/log.class.inc.php');
	require_once('../core/coreexception.class.inc.php');
	require_once('../core/dict.class.inc.php');
	require_once('../core/attributedef.class.inc.php');
	require_once('../core/filterdef.class.inc.php');
	require_once('../core/stimulus.class.inc.php');
	require_once('../core/MyHelpers.class.inc.php');
	require_once('../core/expression.class.inc.php');
	require_once('../core/cmdbsource.class.inc.php');
	require_once('../core/sqlquery.class.inc.php');
	require_once('../core/dbobject.class.php');
	require_once('../core/dbobjectsearch.class.php');
	require_once('../core/dbobjectset.class.php');
	require_once('../core/userrights.class.inc.php');
	$oP->log("Info - MetaModel::Startup from file '$sConfigFileName' (AllowMissingDB = $bAllowMissingDatabase)");
	MetaModel::Startup($sConfigFileName, $bAllowMissingDatabase);
}
/**
 * Helper function to create the database structure
 * @return boolean true on success, false otherwise
 */
function CreateDatabaseStructure(SetupWebPage $oP, Config $oConfig, $sDBName, $sDBPrefix)
{

	InitDataModel($oP, TMP_CONFIG_FILE, true); // Allow the DB to NOT exist since we're about to create it !
	$oP->log('Info - CreateDatabaseStructure');
	if (strlen($sDBPrefix) > 0)
	{
		$oP->info("Creating the structure in '$sDBName' (table names prefixed by '$sDBPrefix').");
	}
	else
	{
		$oP->info("Creating the structure in '$sDBName'.");
	}

	//MetaModel::CheckDefinitions();
	if (!MetaModel::DBExists(/* bMustBeComplete */ false))
	{
		MetaModel::DBCreate();
		$oP->ok("Database structure successfully created.");
	}
	else
	{
		if (strlen($sDBPrefix) > 0)
		{
			$oP->error("Error: found iTop tables into the database '$sDBName' (prefix: '$sDBPrefix'). Please, try selecting another database instance or specify another prefix to prevent conflicting table names.");
		}
		else
		{
			$oP->error("Error: found iTop tables into the database '$sDBName'. Please, try selecting another database instance or specify a prefix to prevent conflicting table names.");
		}
		return false;
	}
	return true;
}

/**
 * Helper function to create and administrator account for iTop
 * @return boolean true on success, false otherwise 
 */
function CreateAdminAccount(SetupWebPage $oP, Config $oConfig, $sAdminUser, $sAdminPwd)
{
	$oP->log('Info - CreateAdminAccount');
	InitDataModel($oP, TMP_CONFIG_FILE, true);  // allow missing DB
	if (UserRights::CreateAdministrator($sAdminUser, $sAdminPwd))
	{
		$oP->ok("Administrator account '$sAdminUser' created.");
		return true;
	}
	else
	{
		$oP->error("Failed to create the administrator account '$sAdminUser'.");
		return false;
	}
}

function ListModuleFiles($sDirectory, SetupWebPage $oP)
{
	//echo "<p>$sDirectory</p>\n";
	if ($hDir = opendir($sDirectory))
	{
		// This is the correct way to loop over the directory. (according to the documentation)
		while (($sFile = readdir($hDir)) !== false)
		{
			$aMatches = array();
			if (is_dir($sDirectory.'/'.$sFile))
			{
				if (($sFile != '.') && ($sFile != '..') && ($sFile != '.svn'))
				{
					ListModuleFiles($sDirectory.'/'.$sFile, $oP);
				}
			}
			else if (preg_match('/^module\.(.*).php$/i', $sFile, $aMatches))
			{
				try
				{
					//echo "<p>Loading: $sDirectory/$sFile...</p>\n";
					require_once($sDirectory.'/'.$sFile);
					//echo "<p>Done.</p>\n";
				}
				catch(Exception $e)
				{
					// Continue...
				}
			}
		}
		closedir($hDir);
	}
	else
	{
		$oP->error("Data directory (".$sDirectory.") not found or not readable.");
	}
}


/**
 * Scans the ./data directory for XML files and output them as a Javascript array
 */ 
function PopulateDataFilesList(SetupWebPage $oP, $aParamValues)
{

	$oP->add("<script type=\"text/javascript\">\n");
	$oP->add("function PopulateDataFilesList()\n");
	$oP->add("{\n");
	$oP->add("if (aFilesToLoad.length > 0)  return;"); // Populate the list only once...

	$aAvailableModules = GetAvailableModules($oP);
	$aStructureDataFiles = array();
	$aSampleDataFiles = array();
	foreach($aParamValues['module'] as $sModuleId)
	{
		$aModuleStruct = $aAvailableModules[$sModuleId]['data.struct'];
		$aModuleSamples = $aAvailableModules[$sModuleId]['data.sample'];
		$aStructureDataFiles = array_merge($aStructureDataFiles, $aModuleStruct);
		$aSampleDataFiles = array_merge($aSampleDataFiles, $aModuleSamples);
	}
	// Structure data
	//
	foreach($aStructureDataFiles as $sFile)
	{
		// Under Windows, it is a must to escape backslashes (not an issue until a folder name starts with t or n, etc...)
		$sFile = str_replace('\\', '\\\\', $sFile);
		$oP->add("aFilesToLoad[aFilesToLoad.length] = '$sFile';\n");
	}

	// Sample data - loaded IIF wished by the user
	//
	$oP->add("if (($(\"#sample_data:checked\").length == 1))");
	$oP->add("{");
	foreach($aSampleDataFiles as $sFile)
	{
		// Under Windows, it is a must to escape backslashes (not an issue until a folder name starts with t or n, etc...)
		$sFile = str_replace('\\', '\\\\', $sFile);
		$oP->add("aFilesToLoad[aFilesToLoad.length] = '$sFile';\n");
	}
	$oP->add("}\n");

	$oP->add("}\n");
	$oP->add("</script>\n");
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
	}
}

/**
 * Search (on the disk) for all defined iTop modules, load them and returns the list (as an array)
 * of the possible iTop modules to install
 * @param none
 * @return Hash A big array moduleID => ModuleData
 */
function GetAvailableModules(SetupWebpage $oP)
{
	clearstatcache();
	ListModuleFiles('../modules/', $oP);
	return SetupWebPage::GetModules();
}

/**
 * Build the config file from the parameters (especially the selected modules)
 */
function BuildConfig(SetupWebpage $oP, Config &$oConfig, $aParamValues)
{
	$aAvailableModules = GetAvailableModules($oP);
	// Initialize the arrays below with default values for the application...
	$aAddOns = $oConfig->GetAddOns();
	$aAppModules = $oConfig->GetAppModules();
	$aDataModels = $oConfig->GetDataModels();
	$sDictionaries = $oConfig->GetDictionaries();
	// Merge the values with the ones provided by the modules
	// Make sure when don't load the same file twice...
	foreach($aParamValues['module'] as $sModuleId)
	{
		$oP->log('Installed iTop module: '. $sModuleId);
		$aDataModels = array_unique(array_merge($aDataModels, $aAvailableModules[$sModuleId]['datamodel']));
		$sDictionaries = array_unique(array_merge($sDictionaries, $aAvailableModules[$sModuleId]['dictionary']));
	}
	$oConfig->SetAddOns($aAddOns);
	$oConfig->SetAppModules($aAppModules);
	$oConfig->SetDataModels($aDataModels);
	$oConfig->SetDictionaries($sDictionaries);
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
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$sVersionString = GetITopVersion();
	$oP->set_title('Welcome to '.$sVersionString);
	$oP->log($sVersionString);
	$oP->add("<h2>Checking prerequisites</h2>\n");
	if (CheckPHPVersion($oP))
	{
		$oP->add("<h2 class=\"next\">Next: Licence agreement</h2>\n");
		$oP->add("<form id=\"theForm\" method=\"post\" onSubmit=\"return DoSubmit('', 0)\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		AddParamsToForm($oP, $aParamValues);
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:right;\"><button type=\"submit\" type=\"submit\">Next >></button></td>\n");
		$oP->add("</tr></table>\n");
		$oP->add("</form>\n");
	}
}

function LicenceAcknowledgement($oP, $aParamValues, $iCurrentStep)
{
	$sNextOperation = 'step'.($iCurrentStep+1);
	
	$oP->set_title('License Agreement');
	$oP->add('<h2>iTop is released by <a href="http://www.combodo.com">Combodo SARL</a> under the terms of the GPL V3 license. In order to use iTop you must accept the terms of this license.</h2>');
	$oP->add("<iframe style=\"width: 100%; height: 350px; overflow-y:auto; font-size:0.8em;\" src=\"./licence.html\">Next: Database server selection</iframe>\n");
	$oP->add("<form id=\"theForm\" method=\"post\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('licence_ok'));

	$sChecked = $aParamValues['licence_ok'] == 1 ? 'checked' : '';
	$oP->add("<h2><input id=\"licence_ok\" type=\"checkbox\" name=\"licence_ok\" value=\"1\" $sChecked><label for=\"licence_ok\">I accept the terms of this licence agreement</label></h2>\n");

	$oP->add("<h2 class=\"next\">Next: Database server selection</h2>\n");
	$oP->add("<table style=\"width:100%\"><tr>\n");
	$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iCurrentStep)\"><< Back</button></td>\n");
	$oP->add("<td style=\"text-align:right;\"><button type=\"submit\" onClick=\"return DoSubmit('', $iCurrentStep)\">Next >></button></td>\n");
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

	$oP->add("<form id=\"theForm\" method=\"post\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('db_server', 'db_user', 'db_pwd'));
	if ($aParamValues['licence_ok'] == 1)
	{
		$sRedStar = '<span class="hilite">*</span>';
		$oP->set_title("Configuration of the database connection\n");
		$oP->add("<h2>Please enter the name of the MySQL database server you want to use for iTop and supply valid credentials to connect to it</h2>\n");
		// Form goes here
		$oP->add("<fieldset><legend>Database connection</legend>\n");
		$aForm = array();
		$aForm[] = array('label' => "Server name$sRedStar:", 'input' => "<input id=\"db_server\" type=\"text\" name=\"db_server\" value=\"{$aParamValues['db_server']}\">",
						'help' => 'E.g. "localhost", "dbserver.mycompany.com" or "192.142.10.23"');
		$aForm[] = array('label' => "User name$sRedStar:", 'input' => "<input id=\"db_user\" type=\"text\" name=\"db_user\" value=\"{$aParamValues['db_user']}\">",
						'help' => 'The account must have the following privileges: SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER');
		$aForm[] = array('label' => 'Password:', 'input' => "<input id=\"db_pwd\" type=\"password\" name=\"db_pwd\" value=\"{$aParamValues['db_pwd']}\">");
		$oP->form($aForm);
		$oP->add("</fieldset>\n");
		$oP->add("<h2 class=\"next\">Next: Database Instance Selection</h2>\n");
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iCurrentStep)\"><< Back</button></td>\n");
		$oP->add("<td style=\"text-align:right;\"><button type=\"submit\" onClick=\"return DoSubmit('Connecting to the database...', $iCurrentStep);\">Next >></button></td>\n");
		$oP->add("</tr></table>\n");
	}
	else
	{
		$oP->add("<button type=\"button\" onClick=\"return DoGoBack($iCurrentStep);\"><< Back</button>\n");		
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
	$oP->set_title("Database Instance Selection\n");
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
		$oP->add("<button type=\"button\" onClick=\"return DoGoBack($iCurrentStep);\"><< Back</button>\n");
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
		$sChecked = '';
		$sDBName = '';
		// If the 'Create Database' option was checked... and the database still does not exist
		if (!$bExistingChecked && !empty($aParamValues['new_db_name']))
		{
			$sChecked = 'checked';
			$sDBName = $aParamValues['new_db_name'];
		}
		$aForm[] = array('label' => "<input id=\"new_db\" type=\"radio\" name=\"db_name\" value=\"\" $sChecked/><label for=\"new_db\"> Create a new database:</label> <input type=\"text\" id=\"new_db_name\" name=\"new_db_name\" value=\"$sDBName\"  maxlength=\"32\"/>");
		$oP->form($aForm);

		$oP->add_ready_script("$('#new_db_name').click( function() { $('#new_db').attr('checked', true); })");
		$oP->add("</fieldset>\n");
		$aForm = array();
		$aForm[] = array('label' => "Add a prefix to all the tables: <input id=\"db_prefix\" type=\"text\" name=\"db_prefix\" value=\"{$aParamValues['db_prefix']}\" maxlength=\"32\"/>");
		$oP->form($aForm);

		$oP->add("<h2 class=\"next\">Next: iTop Modules Selection</h2>\n");
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iCurrentStep)\"><< Back</button></td>\n");
		$oP->add("<td style=\"text-align:right;\"><button type=\"submit\" onClick=\"return DoSubmit('', $iCurrentStep);\">Next >></button></td>\n");
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
	$sPrevOperation = 'step'.($iCurrentStep-1);
	
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
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('module'));
	$sRedStar = '<span class="hilite">*</span>';
	$oP->set_title("Selection of the iTop Modules\n");
	$oP->add("<h2>Customize your iTop installation to fit your needs</h2>\n");
	
	// Form goes here
	$oP->add("<fieldset><legend>Select the iTop modules you want to install:</legend>\n");
	$oP->add("<div style=\"border: 0;width:100%; height: 350px; overflow-y:auto;\">");
	$sRedStar = '<span class="hilite">*</span>';
	$index = 0;
	$aSelectedModules = $aParamValues['module'];
	if ($aSelectedModules == '')
	{
		// Make sure it gets initialized as an array
		$aSelectedModules = array();
	}
	$aAvailableModules = GetAvailableModules($oP);
	foreach($aAvailableModules as $sModuleId => $aModule)
	{
		$sModuleLabel = $aModule['label'];
		$sModuleHelp = $aModule['doc.more_information'];
		$sClass = ($aModule['mandatory']) ? 'class="read-only"' : '';
		$sChecked = ($aModule['mandatory'] ||  in_array($sModuleId, $aSelectedModules) ) ? 'checked' : '';
		$sMoreInfo = (!empty($aModule['doc.more_information'])) ? "<a href=\"{$aModule['doc.more_information']}\" target=\"_blank\">more info</a>": '';
		if ($aModule['visible'])
		{
			$oP->add("<p><input type=\"checkbox\" $sClass $sChecked id=\"module[$index]\" name=\"module[$index]\" value=\"$sModuleId\"><label $sClass for=\"module[$index]\"> {$aModule['label']}</label> $sMoreInfo</p>\n");
			$index++;
		}
		else
		{
			// For now hidden modules are always on !
			$oP->add("<input type=\"hidden\" id=\"module[$index]\" name=\"module[$index]\" value=\"$sModuleId\">\n");
		}
	}	
	$oP->add("</div>");
	$oP->add("</fieldset>\n");
	$oP->add("<h2 class=\"next\">Next: Administrator Account Creation</h2>\n");
	$oP->add("<table style=\"width:100%\"><tr>\n");
	$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iCurrentStep)\"><< Back</button></td>\n");
	$oP->add("<td style=\"text-align:right;\"><button type=\"submit\" onClick=\"return DoSubmit('Creating the database structure...', $iCurrentStep);\">Next >></button></td>\n");
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
	$oP->set_title("Configuration of the admin account");
	$oP->add("<h2>Creation of the database structure</h2>");
	$oP->add("<form id=\"theForm\" method=\"post\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('auth_user', 'auth_pwd', 'language'));

	$sDBName = $aParamValues['db_name'];
	if ($sDBName == '')
	{
		$sDBName = $aParamValues['new_db_name'];
	}
	$sDBPrefix = $aParamValues['db_prefix'];
	$oConfig->SetDBName($sDBName);
	$oConfig->SetDBSubname($sDBPrefix);
	BuildConfig($oP, $oConfig, $aParamValues); // Load all the includes based on the modules selected
	$oConfig->WriteToFile(TMP_CONFIG_FILE);
	if (CreateDatabaseStructure($oP, $oConfig, $sDBName, $sDBPrefix))
	{
		$sRedStar = "<span class=\"hilite\">*</span>";
		$oP->add("<h2>Default language for the application:</h2>\n");
		// Possible languages (depends on the dictionaries loaded in the config)
		$aForm = array();
		$aAvailableLanguages = Dict::GetLanguages();
		$sLanguages = '';
		$sDefaultCode = $oConfig->GetDefaultLanguage();
		foreach($aAvailableLanguages as $sLangCode => $aInfo)
		{
			$sSelected = ($sLangCode == $sDefaultCode ) ? 'selected ' : '';
			$sLanguages.="<option value=\"{$sLangCode}\">{$aInfo['description']} ({$aInfo['localized_description']})</option>";
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
		$oP->add("<h2 class=\"next\">Next: Administrator Account Creation</h2>\n");
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iCurrentStep)\"><< Back</button></td>\n");
		$oP->add("<td style=\"text-align:right;\"><button type=\"submit\" onClick=\"return DoSubmit('Creating the admin account and profiles...', $iCurrentStep);\">Next >></button></td>\n");
		$oP->add("</tr></table>\n");
	}
	else
	{
		$oP->add("<button type=\"button\" onClick=\"return DoGoBack($iCurrentStep)\"><< Back</button>\n");
	}
	// Form goes here
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
	$sNextOperation = 'step'.($iCurrentStep+1);

	$oP->set_title("Application Initialization");
	$sAdminUser = $aParamValues['auth_user'];
	$sAdminPwd = $aParamValues['auth_pwd'];
	$oConfig->SetDefaultLanguage($aParamValues['language']);
	$oConfig->WriteToFile(TMP_CONFIG_FILE);

	$oP->add("<form id=\"theForm\" method=\"post\"\">\n");
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	AddParamsToForm($oP, $aParamValues, array('sample_data'));
	
	if (CreateAdminAccount($oP, $oConfig, $sAdminUser, $sAdminPwd) && UserRights::Setup())
	{
		$oP->add("<h2>Loading of sample data</h2>\n");
		$oP->p("<fieldset><legend> Do you want to load sample data into the database ? </legend>\n");
		$oP->p("<input type=\"radio\" id=\"sample_data\" name=\"sample_data\" id=\"sample_data_no\" checked value=\"yes\"><label for=\"sample_data_yes\"> Yes, for testing purposes, populate the database with sample data.</label>\n");
		$oP->p("<input type=\"radio\" name=\"sample_data\" unchecked id=\"sample_data_no\" value=\"no\"><label for=\"sample_data_no\"> No, this is a production system, load only the data required by the application.</label>\n");
		$oP->p("</fieldset>\n");	
		$oP->add("<h2 class=\"next\">Next: Application Initialization</h2>\n");
		$oP->add("<table style=\"width:100%\"><tr>\n");
		$oP->add("<td style=\"text-align:left;\"><button type=\"button\" onClick=\"return DoGoBack($iCurrentStep)\"><< Back</button></td>\n");
		$oP->add("<td style=\"text-align:right;\"><button type=\"submit\" onClick=\"return DoSubmit('Finalizing configuration and loading data...', $iCurrentStep)\">Next >></button></td>\n");
		$oP->add("</tr></table>\n");
	}
	else
	{
		// Creation failed
		$oP->error("Internal error: Failed to create the admin account or to setup the user rights");
		$oP->add("<button type=\"button\" onClick=\"return DoGoBack($iCurrentStep)\"><< Back</button>\n");
	}
	// End of visible form
	$oP->add("</form>\n");
	// Hidden form submitted when moving on to the next page, once all the data files
	// have been processed
	$oP->add("<form id=\"GoToNextStep\" method=\"post\">\n");
	AddParamsToForm($oP, $aParamValues, array('sample_data'));
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	$oP->add("</form>\n");
	$oP->add("<div id=\"log\" style=\"color:#F00;\"></div>\n");
	$oP->add_linked_script('./jquery.progression.js');

	PopulateDataFilesList($oP, $aParamValues);
}
/**
 * Display the form for the fifth (and final) step of the configuration wizard
 * which consists in
 * 1) Creating the final configuration file
 * 2) Prompting the user to make the file read-only  
 */  
function SetupFinished(SetupWebPage $oP, $aParamValues, $iCurrentStep, Config $oConfig)
{
	$sAuthUser = $aParamValues['auth_user'];
	$sAuthPwd = $aParamValues['auth_pwd'];
	try
	{
		session_start();
		
		// Write the final configuration file
		$oConfig->WriteToFile(FINAL_CONFIG_FILE);

		// Start the application
		InitDataModel($oP, FINAL_CONFIG_FILE, false); // DO NOT allow missing DB
		if (UserRights::Login($sAuthUser, $sAuthPwd))
		{
			$_SESSION['auth_user'] = $sAuthUser;
			$_SESSION['auth_pwd'] = $sAuthPwd;
			// remove the tmp config file
			@unlink(TMP_CONFIG_FILE);
			// try to make the final config file read-only
			@chmod(FINAL_CONFIG_FILE, 0440); // Read-only for owner and group, nothing for others
			
			$oP->set_title("Configuration completed");
			$oP->add("<form id=\"theForm\" method=\"get\" action=\"../index.php\">\n");

			// Check if there are some manual steps required:
			$aAvailableModules = GetAvailableModules($oP);
			$aManualSteps = array();
			foreach($aParamValues['module'] as $sModuleId)
			{
				if (!empty($aAvailableModules[$sModuleId]['doc.manual_setup']))
				{
					$aManualSteps[$aAvailableModules[$sModuleId]['label']] = $aAvailableModules[$sModuleId]['doc.manual_setup'];
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
			}
			else
			{
				$oP->add("<h2>Congratulations for installing iTop</h2>");
				$oP->ok("The initialization completed successfully.");
			}
			// Form goes here.. No back button since the job is done !
			$oP->add("<h1>Let us know what you think about iTop</h1>");
			$oP->add('<table style="width:100%;border:0;padding:0;"><tr><td style="width:100px;vertical-align:middle;background:#f6f6f1;text-align:center">');
			$oP->add('<a href="http://www.combodo.com" style="padding:0;background:transparent;margin:0;"><img style="border:0" src="../images/logo-combodo.png"></a></td>');
			$oP->add('<td style="padding-left: 10px;font-size:10pt">');
			$oP->add("Combodo built iTop because Combodo believes that modern ITIL tools should be at the center of any IT department.");
			$oP->p("Combodo invested a lot of time and effort in iTop, but you can help us improve it even further by providing your feedbacks</p>");
			$oP->p("<a href=\"http://www.combodo.com/register?product=iTop&version=".urlencode(ITOP_VERSION." revision ".ITOP_REVISION)."\" target=\"_blank\">Register online</a> to get informed about all iTop related events (new versions, webinars, etc...)");
			$oP->p("Check out the <a href=\"http://www.combodo.com/itopsupport\">support options</a> available for iTop.");
			$oP->add('</td></tr></table>');
			$oP->add("<p style=\"text-align:center;width:100%\"><button type=\"submit\">Enter iTop</button></p>\n");
			$oP->add("</form>\n");
		}
		else
		{
			$oP->add("<h1>iTop configuration wizard</h1>\n");
			$oP->add("<h2>Step 5: Configuration completed</h2>\n");
			
			@unlink(FINAL_CONFIG_FILE); // remove the aborted config
			$oP->error("Error: Failed to login for user: '$sAuthUser'\n");

			$oP->add("<form id=\"theForm\" method=\"post\">\n");
			$oP->add("<button type=\"button\" onClick=\"return DoGoBack($iCurrentStep);\"><< Back</button>\n");
			AddParamsToForm($oP, $aParamValues);
			$oP->add("<input type=\"hidden\" name=\"operation\" value=\"step0\">\n");
			$oP->add("</form>\n");
		}
	}
	catch(Exception $e)
	{
		$oP->error("Error: unable to create the configuration file.");
		$oP->p($e->getHtmlDesc());
		$oP->p("Did you forget to remove the previous (read-only) configuration file ?");
		$oP->add("<form id=\"theForm\" method=\"post\">\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"step0\">\n");
		AddParamsToForm($oP, $aParamValues);
		$oP->add("<button type=\"button\" onClick=\"return DoGoBack($iCurrentStep);\"><< Back</button>\n");
		$oP->add("</form>\n");
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
// Main program
///////////////////////////////////////////////////////////////////////////////////////////////////

clearstatcache(); // Make sure we know what we are doing !
if (file_exists(FINAL_CONFIG_FILE))
{
	// The configuration file already exists
	if (is_writable(FINAL_CONFIG_FILE))
	{
		$oP->warning("<b>Warning:</b> a configuration file '".FINAL_CONFIG_FILE."' already exists, and will be overwritten.");
	}
	else
	{
		$oP->add("<h1>iTop configuration wizard</h1>\n");
		$oP->add("<h2>Fatal error</h2>\n");
		$oP->error("<b>Error:</b> the configuration file '".FINAL_CONFIG_FILE."' already exists and cannot be overwritten.");
		$oP->p("The wizard cannot create the configuration file for you. Please remove the file '<b>".realpath(FINAL_CONFIG_FILE)."</b>' or change its access-rights/read-only flag before continuing.");
		$oP->output();
		exit;
	}
}
else
{
	// No configuration file yet
	// Check that the wizard can write into the root dir to create the configuration file
	if (!is_writable(dirname(FINAL_CONFIG_FILE)))
	{
		$oP->add("<h1>iTop configuration wizard</h1>\n");
		$oP->add("<h2>Fatal error</h2>\n");
		$oP->error("<b>Error:</b> the directory where to store the configuration file is not writable.");
		$oP->p("The wizard cannot create the configuration file for you. Please make sure that the directory '<b>".realpath(dirname(FINAL_CONFIG_FILE))."</b>' is writable for the web server.");
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
	$aParams = array('licence_ok', 'db_server', 'db_user', 'db_pwd','db_name', 'new_db_name', 'db_prefix', 'module', 'sample_data', 'auth_user', 'auth_pwd', 'language');
	foreach($aParams as $sName)
	{
		$aParamValues[$sName] = utils::ReadParam($sName, '');
	}
	
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
		SampleDataSelection($oP, $aParamValues, 6, $oConfig);
		break;
	
		case 'step7':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 7 ========");
		SetupFinished($oP, $aParamValues, 7, $oConfig);
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
