<?php
/**
 * Wizard to configure and initialize the iTop application
 */
require_once('../application/utils.inc.php');
require_once('../core/config.class.inc.php');
require_once('../core/cmdbsource.class.inc.php');
require_once('./setuppage.class.inc.php');
define(TMP_CONFIG_FILE, '../tmp-config-itop.php');
define(FINAL_CONFIG_FILE, '../config-itop.php');
define(PHP_MIN_VERSION, '5.2.0');
define(MYSQL_MIN_VERSION, '5.0.0');

$sOperation = Utils::ReadParam('operation', 'step1');
$oP = new setup_web_page('iTop configuration wizard');
$oP->no_cache();

/**
 * Helper function to check if the current version of PHP
 * is compatible with the application
 * @return boolean true if this is Ok, false otherwise
 */
function CheckPHPVersion(nice_web_page $oP)
{
	if (version_compare(phpversion(), PHP_MIN_VERSION, '>='))
	{
		$oP->ok("The current PHP Version (".phpversion().") is greater than the minimum required version (".PHP_MIN_VERSION.")");
	}
	else
	{
		$oP->error("Error: The current PHP Version (".phpversion().") is lower than the minimum required version (".PHP_MIN_VERSION.")");
		return false;
	}
	if (extension_loaded('mysql'))
	{
		$oP->ok("The required extension 'mysql' is present.");
	}
	else
	{
		$oP->error("Error: missing required extension 'mysql'.");
		return false;
	}
	return true;
}
  
/**
 * Helper function check the connection to the database and (if connected) to enumerate
 * the existing databases
 * @return Array The list of databases found in the server
 */
function CheckServerConnection(nice_web_page $oP, $sDBServer, $sDBUser, $sDBPwd)
{
	$aResult = array();
	try
	{
		$oDBSource = new CMDBSource;
		$oDBSource->Init($sDBServer, $sDBUser, $sDBPwd);
		$oP->ok("Connection to '$sDBServer' as '$sDBUser' successful.");
		$sDBVersion = $oDBSource->GetDBVersion();
		if (version_compare($sDBVersion, MYSQL_MIN_VERSION, '>='))
		{
			$oP->ok("Current MySQL version ($sDBVersion), greater than minimum required version (".MYSQL_MIN_VERSION.")");
		}
		else
		{
			$oP->error("Error: Current MySQL version is ($sDBVersion), minimum required version (".MYSQL_MIN_VERSION.")");
			return false;
		}
		$aResult = $oDBSource->ListDB();
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
 * Helper function to create the database structure
 * @return boolean true on success, false otherwise
 */
function CreateDatabaseStructure(nice_web_page $oP, Config $oConfig, $sDBName, $sDBPrefix)
{
	$oP->info("Creating the structure in '$sDBName' (prefix = '$sDBPrefix').");
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
	MetaModel::Startup(TMP_CONFIG_FILE, true); // allow missing DB
	//MetaModel::CheckDefinitions();
	if (!MetaModel::DBExists())
	{
		MetaModel::DBCreate();
		$oP->ok("Database structure created in '$sDBName' (prefix = '$sDBPrefix').");
	}
	else
	{
		$oP->error("Error: database '$sDBName' (prefix = '$sDBPrefix') already exists.");
		return false;
	}
	return true;
}

/**
 * Helper function to create and administrator account for iTop
 * @return boolean true on success, false otherwise 
 */
function CreateAdminAccount(nice_web_page $oP, Config $oConfig, $sAdminUser, $sAdminPwd)
{
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
	MetaModel::Startup(TMP_CONFIG_FILE, true); // allow missing DB
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

/**
 * Helper function to load some sample data into the database
 */
function LoadSampleData(nice_web_page $oP)
{
	// TO BE IMPLEMENTED
	$oP->ok("Sample data loaded into the database.");
	return true;
} 
    
/**
 * Display the form for the first step of the configuration wizard
 * which consists in the database server selection
 */  
function DisplayStep1(nice_web_page $oP)
{
	$sNextOperation = 'step2';
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$oP->add("<h2>Checking prerequisites</h2>\n");
	if (CheckPHPVersion($oP))
	{
		$oP->add("<h2>Step 1: Configuration of the database connection</h2>\n");
		$oP->add("<form method=\"post\">\n");
		// Form goes here
		$oP->add("<fieldset><legend>Database connection</legend>\n");
		$aForm = array();
		$aForm[] = array('label' => 'Server name:', 'input' => "<input type=\"text\" name=\"db_server\" value=\"\">",
						 'help' => 'E.g. "localhost", "dbserver.mycompany.com" or "192.142.10.23".');
		$aForm[] = array('label' => 'User name:', 'input' => "<input type=\"text\" name=\"db_user\" value=\"\">");
		$aForm[] = array('label' => 'Password:', 'input' => "<input type=\"password\" name=\"db_pwd\" value=\"\">");
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
function DisplayStep2(nice_web_page $oP, Config $oConfig, $sDBServer, $sDBUser, $sDBPwd)
{
	$sNextOperation = 'step3';
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$oP->add("<h2>Step 2: Database selection</h2>\n");
	$oP->add("<form method=\"post\">\n");
	$aDatabases = CheckServerConnection($oP, $sDBServer, $sDBUser, $sDBPwd);
	if ($aDatabases === false)
	{
		// Connection failed, invalid credentials ? Go back
		$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
	}
	else
	{
		// Connection is Ok, save it and continue the setup wizard
		$oConfig->SetDBHost($sDBServer);
		$oConfig->SetDBUser($sDBUser);
		$oConfig->SetDBPwd($sDBPwd);
		$oConfig->WriteToFile();

		$oP->add("<fieldset><legend>Specify a database</legend>\n");
		$aForm = array();
		foreach($aDatabases as $sDBName)
		{
			$aForm[] = array('label' => "<input type=\"radio\" name=\"db_name\" value=\"$sDBName\" /> $sDBName");
		}
		$aForm[] = array('label' => "<input type=\"radio\" name=\"db_name\" value=\"\" /> create a new database: <input type=\"text\" name=\"new_db_name\" value=\"\" />");
		$oP->form($aForm);

		$oP->add("</fieldset>\n");
		$aForm = array();
		$aForm[] = array('label' => "Add a prefix to all the tables: <input type=\"text\" name=\"db_prefix\" value=\"\" />");
		$oP->form($aForm);

		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
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
function DisplayStep3(nice_web_page $oP, Config $oConfig, $sDBName, $sDBPrefix)
{
	$sNextOperation = 'step4';
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$oP->add("<h2>Creation of the database structure</h2>\n");
	$oP->add("<form method=\"post\">\n");
	$oConfig->SetDBName($sDBName);
	$oConfig->SetDBSubname($sDBPrefix);
	$oConfig->WriteToFile(TMP_CONFIG_FILE);
	if (CreateDatabaseStructure($oP, $oConfig, $sDBName, $sDBPrefix))
	{
		$oP->add("<h2>Step 3: Definition of the administrator account</h2>\n");
		// Database created, continue with admin creation		
		$oP->add("<fieldset><legend>Administrator account</legend>\n");
		$aForm = array();
		$aForm[] = array('label' => "Login:", 'input' => "<input type=\"text\" name=\"auth_user\" value=\"\">");
		$aForm[] = array('label' => "Password:", 'input' => "<input type=\"password\" name=\"auth_pwd\" value=\"\">");
		$aForm[] = array('label' => "Retype password:", 'input' => "<input type=\"password\" name=\"auth_pwd2\" value=\"\">");
		$oP->form($aForm);
		$oP->add("</fieldset>\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
		$oP->add("&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oP->add("<button type=\"submit\">Next >></button>\n");
	}
	else
	{
		$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
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
function DisplayStep4(nice_web_page $oP, Config $oConfig, $sAdminUser, $sAdminPwd)
{
	$sNextOperation = 'step5';
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$oP->add("<h2>Creation of the administrator account</h2>\n");

	$oP->add("<form method=\"post\">\n");
	if (CreateAdminAccount($oP, $oConfig, $sAdminUser, $sAdminPwd))
	{
		$oP->add("<h2>Step 4: Loading of sample data</h2>\n");
		$oP->p("<fieldset><legend> Do you want to load sample data into the database ? </legend>\n");
		$oP->p("<input type=\"radio\" name=\"sample_data\" checked value=\"yes\"> Yes, for testing purposes, populate the database with sample data.\n");
		$oP->p("<input type=\"radio\" name=\"sample_data\" unchecked value=\"no\"> No, this is a production system, I will load real data myself.\n");
		$oP->p("</fieldset>\n");
		$oP->add("<input type=\"hidden\" name=\"auth_user\" value=\"$sAdminUser\">\n"); // To be compatible with login page
		$oP->add("<input type=\"hidden\" name=\"auth_pwd\" value=\"$sAdminPwd\">\n"); // To be compatible with login page
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
		$oP->add("&nbsp;&nbsp;&nbsp;&nbsp;\n");
		$oP->add("<button type=\"submit\">Finish</button>\n");
	}
	else
	{
		// Creation failed
		$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
	}
	// Form goes here
	$oP->add("</form>\n");
}
/**
 * Display the form for the fifth (and final) step of the configuration wizard
 * which consists in
 * 1) Creating the final configuration file
 * 2) Prompting the user to make the file read-only  
 */  
function DisplayStep5(nice_web_page $oP, Config $oConfig, $sAuthUser, $sAuthPwd, $bLoadSampleData)
{
	try
	{
		session_start();
		
		// Write the final configuration file
		$oConfig->WriteToFile(FINAL_CONFIG_FILE);

		// Start the application
		require_once('../application/application.inc.php');
		require_once('../application/startup.inc.php');
		if (UserRights::Login($sAuthUser, $sAuthPwd))
		{
			$_SESSION['auth_user'] = $sAuthUser;
			$_SESSION['auth_pwd'] = $sAuthPwd;
			// remove the tmp config file
			@unlink(TMP_CONFIG_FILE);
			// try to make the final config file read-only
			@chmod(FINAL_CONFIG_FILE, "a-w");
			
			$oP->add("<h1>iTop configuration wizard</h1>\n");
			$oP->add("<h2>Configuration completed</h2>\n");
			$oP->add("<form method=\"get\" action=\"../index.php\">\n");
			if ($bLoadSampleData)
			{
				LoadSampleData($oP);
			}
			// Form goes here
			$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
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
			$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
			$oP->add("&nbsp;&nbsp;&nbsp;&nbsp;\n");
			$oP->add("</form>\n");
		}
	}
	catch(Exception $e)
	{
		$oP->error("Error: unable to create the configuration file.");
		$oP->p($e->getHtmlDesc());
		$oP->p("Did you forget to remove the previous (read-only) configuration file ?");
	}
}
/**
 * Main program
 */
try
{
	$oConfig = new Config(TMP_CONFIG_FILE);
}
catch(Exception $e)
{
	// We'll end here when the tmp config file does not exist. It's normal
	$oConfig = new Config(TMP_CONFIG_FILE, false /* Don't try to load it */);
}
switch($sOperation)
{
	case 'step1':
	DisplayStep1($oP);
	break;
	
	case 'step2':
	$sDBServer = Utils::ReadParam('db_server');
	$sDBUser = Utils::ReadParam('db_user');
	$sDBPwd = Utils::ReadParam('db_pwd');
	DisplayStep2($oP, $oConfig, $sDBServer, $sDBUser, $sDBPwd);
	break;

	case 'step3':
	$sDBName = Utils::ReadParam('db_name');
	if (empty($sDBName))
	{
		$sDBName = Utils::ReadParam('new_db_name');
	}
	$sDBPrefix = Utils::ReadParam('db_prefix');
	DisplayStep3($oP, $oConfig, $sDBName, $sDBPrefix);
	break;

	case 'step4':
	$sAdminUser = Utils::ReadParam('auth_user');
	$sAdminPwd = Utils::ReadParam('auth_pwd');
	DisplayStep4($oP, $oConfig, $sAdminUser, $sAdminPwd);
	break;

	case 'step5':
	$bLoadSampleData = (Utils::ReadParam('sample_data', 'no') == 'yes');
	$sAdminUser = Utils::ReadParam('auth_user');
	$sAdminPwd = Utils::ReadParam('auth_pwd');
	DisplayStep5($oP, $oConfig, $sAdminUser, $sAdminPwd, $bLoadSampleData);
	break;

	default:
	$oP->error("Error: unsupported operation '$sOperation'");
	
}
$oP->output();
?>
