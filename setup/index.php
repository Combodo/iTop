<?php
/**
 * Wizard to configure and initialize the iTop application
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

$sOperation = Utils::ReadParam('operation', 'step1');
$oP = new SetupWebPage('iTop configuration wizard');

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
		$oP->log("Info - php.ini path: '$sPhpIniFile'");
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
		$oP->error("'magic_quotes_gpc' is set to On in '$sPhpIniFile', please turn if Off before continuing.");
		$bResult = false;
	}
	
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
		$oP->ok("Database structure successfuly created.");
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

//aFilesToLoad[aFilesToLoad.length] = './menus.xml'; // First load the menus

function ListDataFiles($sDirectory, SetupWebPage $oP)
{
	$aFilesToLoad = array();
	if ($hDir = @opendir($sDirectory))
	{
		// This is the correct way to loop over the directory. (according to the documentation)
		while (($sFile = readdir($hDir)) !== false)
		{
			$sExtension = pathinfo($sFile, PATHINFO_EXTENSION );
			if (strcasecmp($sExtension, 'xml') == 0)
			{
				$aFilesToLoad[] = $sDirectory.'/'.$sFile;
			}
		}
		closedir($hDir);
		// Load order is important we expect the files to be ordered
		// like numbered 1.Organizations.xml 2.Locations.xml , etc.
		asort($aFilesToLoad);
	}
	else
	{
		$oP->error("Data directory (".$sDirectory.") not found or not readable.");
	}
	return $aFilesToLoad;
}


/**
 * Scans the ./data directory for XML files and output them as a Javascript array
 */ 
function PopulateDataFilesList(SetupWebPage $oP)
{

	$oP->add("<script type=\"text/javascript\">\n");
	$oP->add("function PopulateDataFilesList()\n");
	$oP->add("{\n");

	// Structure data
	//
	$aStructureDataFiles = ListDataFiles(SETUP_STRUCTURE_DATA_DIR, $oP);
	foreach($aStructureDataFiles as $sFile)
	{
		$oP->add("aFilesToLoad[aFilesToLoad.length] = '$sFile';\n");
	}

	// Sample data - loaded IIF wished by the user
	//
	$oP->add("if (($(\"#sample_data:checked\").length == 1))");
	$oP->add("{");
	$aSampleDataFiles = ListDataFiles(SETUP_SAMPLE_DATA_DIR, $oP);
	foreach($aSampleDataFiles as $sFile)
	{
		$oP->add("aFilesToLoad[aFilesToLoad.length] = '$sFile';\n");
	}
	$oP->add("}\n");

	$oP->add("}\n");
	$oP->add("</script>\n");
}

/**
 * Display the form for the first step of the configuration wizard
 * which consists in the database server selection
 */  
function DisplayStep1(SetupWebPage $oP)
{
	$sNextOperation = 'step2';
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$oP->add("<h2>Checking prerequisites</h2>\n");
	if (CheckPHPVersion($oP))
	{
		$sRedStar = '<span class="hilite">*</span>';
		$oP->add("<h2>Step 1: Configuration of the database connection</h2>\n");
		$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Connection to the database...', 1)\">\n");
		// Form goes here
		$oP->add("<fieldset><legend>Database connection</legend>\n");
		$aForm = array();
		$aForm[] = array('label' => "Server name$sRedStar:", 'input' => "<input id=\"db_server\" type=\"text\" name=\"db_server\" value=\"\">",
						'help' => 'E.g. "localhost", "dbserver.mycompany.com" or "192.142.10.23"');
		$aForm[] = array('label' => "User name$sRedStar:", 'input' => "<input id=\"db_user\" type=\"text\" name=\"db_user\" value=\"\">",
						'help' => 'The account must have the following privileges: SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER');
		$aForm[] = array('label' => 'Password:', 'input' => "<input id=\"db_pwd\" type=\"password\" name=\"db_pwd\" value=\"\">");
		$oP->form($aForm);
		$oP->add("</fieldset>\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		$oP->add("<button type=\"submit\">Next >></button>\n");
		$oP->add("</form>\n");
	}
}

/**
 * Display the form for the second step of the configuration wizard
 * which consists in
 * 1) Validating the parameters by connecting to the database server
 * 2) Prompting to select an existing database or to create a new one  
 */  
function DisplayStep2(SetupWebPage $oP, Config $oConfig, $sDBServer, $sDBUser, $sDBPwd)
{
	$sNextOperation = 'step3';
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$oP->add("<h2>Step 2: Database selection</h2>\n");
	$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Creating database structure...', 2);\">\n");
	$aDatabases = CheckServerConnection($oP, $sDBServer, $sDBUser, $sDBPwd);
	if ($aDatabases === false)
	{
		// Connection failed, invalid credentials ? Go back
		$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
	}
	else
	{
		// Connection is Ok, save it and continue the setup wizard
		$oConfig->SetDBHost($sDBServer);
		$oConfig->SetDBUser($sDBUser);
		$oConfig->SetDBPwd($sDBPwd);
		$oConfig->WriteToFile();

		$oP->add("<fieldset><legend>Specify a database<span class=\"hilite\">*</span></legend>\n");
		$aForm = array();
		if (is_array($aDatabases))
		{
			foreach($aDatabases as $sDBName)
			{
				$aForm[] = array('label' => "<input id=\"db_$sDBName\" type=\"radio\" name=\"db_name\" value=\"$sDBName\" /><label for=\"db_$sDBName\"> $sDBName</label>");
			}
		}
		else
		{
			$aForm[] = array('label' => "<input id=\"current_db\" type=\"radio\" name=\"db_name\" value=\"-1\" /><label for=\"current_db\"> Use the existing database:</label> <input type=\"text\" id=\"current_db_name\" name=\"current_db_name\" value=\"\"  maxlength=\"32\"/>");			
			$oP->add_ready_script("$('#current_db_name').click( function() { $('#current_db').attr('checked', true); });");
		}
		$aForm[] = array('label' => "<input id=\"new_db\" type=\"radio\" name=\"db_name\" value=\"\" /><label for=\"new_db\"> Create a new database:</label> <input type=\"text\" id=\"new_db_name\" name=\"new_db_name\" value=\"\"  maxlength=\"32\"/>");
		$oP->form($aForm);

		$oP->add_ready_script("$('#new_db_name').click( function() { $('#new_db').attr('checked', true); })");
		$oP->add("</fieldset>\n");
		$aForm = array();
		$aForm[] = array('label' => "Add a prefix to all the tables: <input id=\"db_prefix\" type=\"text\" name=\"db_prefix\" value=\"\" maxlength=\"32\"/>");
		$oP->form($aForm);

		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
		$oP->add("&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oP->add("<button type=\"submit\">Next >></button>\n");
	}
	$oP->add("</form>\n");
}

/**
 * Display the form for the third step of the configuration wizard
 * which consists in
 * 1) Validating the parameters by connecting to the database server & selecting the database
 * 2) Creating the database structure  
 * 3) Prompting for the admin account to be created  
 */  
function DisplayStep3(SetupWebPage $oP, Config $oConfig, $sDBName, $sDBPrefix)
{
	$sNextOperation = 'step4';
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$oP->add("<h2>Creation of the database structure</h2>\n");
	$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Creating user and profiles...', 3);\">\n");
	$oConfig->SetDBName($sDBName);
	$oConfig->SetDBSubname($sDBPrefix);
	$oConfig->WriteToFile(TMP_CONFIG_FILE);
	if (CreateDatabaseStructure($oP, $oConfig, $sDBName, $sDBPrefix))
	{
		$sRedStar = "<span class=\"hilite\">*</span>";
		$oP->add("<h2>Step 3: Definition of the administrator account</h2>\n");
		// Database created, continue with admin creation		
		$oP->add("<fieldset><legend>Administrator account</legend>\n");
		$aForm = array();
		$aForm[] = array('label' => "Login$sRedStar:", 'input' => "<input id=\"auth_user\" type=\"text\" name=\"auth_user\" value=\"\">");
		$aForm[] = array('label' => "Password$sRedStar:", 'input' => "<input id=\"auth_pwd\" type=\"password\" name=\"auth_pwd\" value=\"\">");
		$aForm[] = array('label' => "Retype password$sRedStar:", 'input' => "<input  id=\"auth_pwd2\" type=\"password\" name=\"auth_pwd2\" value=\"\">");
		$oP->form($aForm);
		$oP->add("</fieldset>\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
		$oP->add("&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oP->add("<button type=\"submit\">Next >></button>\n");
	}
	else
	{
		$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
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
function DisplayStep4(SetupWebPage $oP, Config $oConfig, $sAdminUser, $sAdminPwd)
{
	$sNextOperation = 'step5';
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$oP->add("<h2>Creation of the administrator account</h2>\n");

	$oP->add("<form method=\"post\"\">\n");
	if (CreateAdminAccount($oP, $oConfig, $sAdminUser, $sAdminPwd) && UserRights::Setup())
	{
		$oP->add("<h2>Step 4: Loading of sample data</h2>\n");
		$oP->p("<fieldset><legend> Do you want to load sample data into the database ? </legend>\n");
		$oP->p("<input type=\"radio\" id=\"sample_data\" name=\"sample_data\" checked value=\"yes\"> Yes, for testing purposes, populate the database with sample data.\n");
		$oP->p("<input type=\"radio\" name=\"sample_data\" unchecked value=\"no\"> No, this is a production system, load only the data required by the application.\n");
		$oP->p("</fieldset>\n");
		$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
		$oP->add("&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oP->add("<button onclick=\"DoSubmit('Finalizing configuration and loading data...', 4);\">Finish</button>\n");
	}
	else
	{
		// Creation failed
		$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
	}
	// End of visible form
	$oP->add("</form>\n");
	// Hidden form
	$oP->add("<form id=\"GoToNextStep\" method=\"post\">\n");
	$oP->add("<input type=\"hidden\" name=\"auth_user\" value=\"$sAdminUser\">\n"); // To be compatible with login page
	$oP->add("<input type=\"hidden\" name=\"auth_pwd\" value=\"$sAdminPwd\">\n"); // To be compatible with login page
	$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
	$oP->add("</form>\n");
	$oP->add("<div id=\"log\" style=\"color:#F00;\"></div>\n");
	$oP->add_linked_script('./jquery.progression.js');

	PopulateDataFilesList($oP);
}
/**
 * Display the form for the fifth (and final) step of the configuration wizard
 * which consists in
 * 1) Creating the final configuration file
 * 2) Prompting the user to make the file read-only  
 */  
function DisplayStep5(SetupWebPage $oP, Config $oConfig, $sAuthUser, $sAuthPwd)
{
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
			
			$oP->add("<h1>iTop configuration wizard</h1>\n");
			$oP->add("<h2>Configuration completed</h2>\n");
			$oP->add("<form method=\"get\" action=\"../index.php\">\n");
			$oP->ok("The initialization completed successfully.");
			// Form goes here
			$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
			$oP->add("&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oP->add("<button type=\"submit\">Enter iTop</button>\n");
			$oP->add("</form>\n");
		}
		else
		{
			$oP->add("<h1>iTop configuration wizard</h1>\n");
			$oP->add("<h2>Step 5: Configuration completed</h2>\n");
			
			@unlink(FINAL_CONFIG_FILE); // remove the aborted config
			$oP->error("Error: Failed to login for user: '$sAuthUser'\n");

			$oP->add("<form method=\"get\" action=\"../index.php\">\n");
			$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
			$oP->add("&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oP->add("</form>\n");
		}
	}
	catch(Exception $e)
	{
		$oP->error("Error: unable to create the configuration file.");
		$oP->p($e->getHtmlDesc());
		$oP->p("Did you forget to remove the previous (read-only) configuration file ?");
		$oP->add("<button type=\"button\" onClick=\"window.history.back();\"><< Back</button>\n");
	}
}
/**
 * Main program
 */
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
	switch($sOperation)
	{
		case 'step1':
		$oP->log("Info - ========= Wizard step 1 ========");
		DisplayStep1($oP);
		break;
		
		case 'step2':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 2 ========");
		$sDBServer = Utils::ReadParam('db_server');
		$sDBUser = Utils::ReadParam('db_user');
		$sDBPwd = Utils::ReadParam('db_pwd');
		DisplayStep2($oP, $oConfig, $sDBServer, $sDBUser, $sDBPwd);
		break;
	
		case 'step3':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 3 ========");
		$sDBName = Utils::ReadParam('db_name');
		if (empty($sDBName))
		{
			$sDBName = Utils::ReadParam('new_db_name');
		}
		$sDBPrefix = Utils::ReadParam('db_prefix');
		DisplayStep3($oP, $oConfig, $sDBName, $sDBPrefix);
		break;
	
		case 'step4':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 4 ========");
		$sAdminUser = Utils::ReadParam('auth_user');
		$sAdminPwd = Utils::ReadParam('auth_pwd');
		DisplayStep4($oP, $oConfig, $sAdminUser, $sAdminPwd);
		break;
	
		case 'step5':
		$oP->no_cache();
		$oP->log("Info - ========= Wizard step 5 ========");
		$sAdminUser = Utils::ReadParam('auth_user');
		$sAdminPwd = Utils::ReadParam('auth_pwd');
		DisplayStep5($oP, $oConfig, $sAdminUser, $sAdminPwd);
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
