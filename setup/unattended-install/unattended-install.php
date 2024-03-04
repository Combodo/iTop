<?php
$bBypassMaintenance = true;
require_once(dirname(__FILE__, 3) . '/approot.inc.php');
require_once(__DIR__ . '/InstallationFileService.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/application/clipage.class.inc.php');
require_once(APPROOT.'/core/config.class.inc.php');
require_once(APPROOT.'/core/log.class.inc.php');
require_once(APPROOT.'/core/kpi.class.inc.php');
require_once(APPROOT.'/core/cmdbsource.class.inc.php');
require_once(APPROOT.'/setup/setuppage.class.inc.php');
require_once(APPROOT.'/setup/wizardcontroller.class.inc.php');
require_once(APPROOT.'/setup/wizardsteps.class.inc.php');
require_once(APPROOT.'/setup/applicationinstaller.class.inc.php');

/////////////////////////////////////////////////
if (! utils::IsModeCLI())
{
	echo "Mode CLI only";
	exit(-1);
}

$sParamFile = utils::ReadParam('response_file', 'null', true /* CLI allowed */, 'raw_data');
if ($sParamFile === 'null') {
	echo "No `--response_file` param specified, using default value !\n";
	$sParamFile = 'default-params.xml';
}
$bCheckConsistency = (utils::ReadParam('check_consistency', '0', true /* CLI allowed */) == '1');

if (false === file_exists($sParamFile)) {
	echo "Param file `$sParamFile` doesn't exist ! Exiting...";
	exit(-1);
}
$oParams = new XMLParameters($sParamFile);

$sMode = $oParams->Get('mode');

$sTargetEnvironment = $oParams->Get('target_env', '');
if ($sTargetEnvironment == '')
{
	$sTargetEnvironment = 'production';
}

$sInstallationXmlPath = utils::ReadParam('use_installation_xml', 'null', true /* CLI allowed */, 'raw_data');
if (! is_null($sInstallationXmlPath) && is_file($sInstallationXmlPath)){
	echo "Use $sInstallationXmlPath for module selection\n";
	$oInstallationFileService = new InstallationFileService($sInstallationXmlPath, $sTargetEnvironment);
	$oInstallationFileService->Init();
	$aSelectedModules = $oInstallationFileService->GetSelectedModules();

	$oParams->Set('selected_modules', $aSelectedModules);
	$oParams->Set('selected_extensions', []);
}

// Configuration file
$sConfigFile = APPCONF.$sTargetEnvironment.'/'.ITOP_CONFIG_FILE;
$bUseItopConfig = ((bool) utils::ReadParam('use-itop-config', 0, true /* CLI allowed */));
if ($bUseItopConfig && file_exists($sConfigFile)){
	//unattended run based on db settings coming from itop configuration
	copy($sConfigFile, "$sConfigFile.backup");

	$oConfig = new Config($sConfigFile);
	$aDBXmlSettings = $oParams->Get('database', array());
	$aDBXmlSettings ['server'] = $oConfig->Get('db_host');
	$aDBXmlSettings ['user'] = $oConfig->Get('db_user');
	$aDBXmlSettings ['pwd'] = $oConfig->Get('db_pwd');
	$aDBXmlSettings ['name'] = $oConfig->Get('db_name');
	$aDBXmlSettings ['prefix'] = $oConfig->Get('db_subname');
	$aDBXmlSettings ['db_tls_enabled'] = $oConfig->Get('db_tls.enabled');
	//cannot be null or infinite loop triggered!
	$aDBXmlSettings ['db_tls_ca'] = $oConfig->Get('db_tls.ca') ?? "";

	$oParams->Set('database', $aDBXmlSettings);

	$oParams->Set('url', $oConfig->Get('app_root_url'));
} else {
	//unattended run based on db settings coming from response_file (XML file)
	$aDBXmlSettings = $oParams->Get('database', array());
}

$sDBServer = $aDBXmlSettings['server'];
$sDBUser = $aDBXmlSettings['user'];
$sDBPwd = $aDBXmlSettings['pwd'];
$sDBName = $aDBXmlSettings['name'];
$sDBPrefix = $aDBXmlSettings['prefix'];

if ($sMode == 'install')
{
	echo "Installation mode detected.\n";

	$bClean = utils::ReadParam('clean', false, true /* CLI allowed */);
	if ($bClean)
	{
		echo "Cleanup mode detected.\n";

		if (file_exists($sConfigFile))
		{
			echo "Trying to delete the configuration file: '$sConfigFile'.\n";
			@chmod($sConfigFile, 0770); // RWX for owner and group, nothing for others
			unlink($sConfigFile);
		}
		else
		{
			echo "No config file to delete ($sConfigFile does not exist).\n";
		}

		// Starting with iTop 2.7.0, a failed setup leaves some lock files, let's remove them
		$aLockFiles = array(
			'data/.readonly' => 'read-only lock file',
			'data/.maintenance' => 'maintenance mode lock file',
		);
		foreach($aLockFiles as $sFile => $sDescription)
		{
	 		$sLockFile = APPROOT.$sFile;
			if (file_exists($sLockFile))
			{
				echo "Trying to delete the $sDescription: '$sLockFile'.\n";
				unlink($sLockFile);
			}
		}

		// Starting with iTop 2.6.0, let's remove the cache directory as well
		// Can cause some strange issues in the setup (apparently due to the Dict class being automatically loaded ??)
		$sCacheDir = APPROOT.'data/cache-'.$sTargetEnvironment;
		if (file_exists($sCacheDir))
		{
			if (is_dir($sCacheDir))
			{
			 	echo "Emptying the cache directory '$sCacheDir'.\n";
			 	SetupUtils::tidydir($sCacheDir);
			}
			else
			{
				die("ERROR the cache directory '$sCacheDir' exists, but is NOT a directory !!!\nExiting.\n");
			}
		}

		// env-xxx directory
		$sTargetDir = APPROOT.'env-'.$sTargetEnvironment;
		if (file_exists($sTargetDir))
		{
			if (is_dir($sTargetDir))
			{
			 	echo "Emptying the target directory '$sTargetDir'.\n";
			 	SetupUtils::tidydir($sTargetDir);
			}
			else
			{
				die("ERROR the target dir '$sTargetDir' exists, but is NOT a directory !!!\nExiting.\n");
			}
		}
		else
		{
			echo "No target directory to delete ($sTargetDir does not exist).\n";
		}

		if ($sDBPrefix != '')
		{
			die("Cleanup not implemented for a partial database (prefix= '$sDBPrefix')\nExiting.");
		}

		$oMysqli = new mysqli($sDBServer, $sDBUser, $sDBPwd);
		if ($oMysqli->connect_errno)
		{
		    die("Cannot connect to the MySQL server (".$oMysqli->connect_errno . ") ".$oMysqli->connect_error."\nExiting");
		}
		else
		{
			if ($oMysqli->select_db($sDBName))
			{
				echo "Deleting database '$sDBName'\n";
				$oMysqli->query("DROP DATABASE `$sDBName`");
			}
			else
			{
				echo "The database '$sDBName' does not seem to exist. Nothing to cleanup.\n";
			}
		}
	}
}
else
{
	//use settings from itop conf
	$sTargetEnvironment = $oParams->Get('target_env', '');
	if ($sTargetEnvironment == '')
	{
		$sTargetEnvironment = 'production';
	}
	$sTargetDir = APPROOT.'env-'.$sTargetEnvironment;
}

$bHasErrors = false;
$aChecks = SetupUtils::CheckBackupPrerequisites(APPROOT.'data'); // mmm should be the backup destination dir

$aSelectedModules = $oParams->Get('selected_modules');
$sSourceDir = $oParams->Get('source_dir', 'datamodels/latest');
$sExtensionDir = $oParams->Get('extensions_dir', 'extensions');
$aChecks = array_merge($aChecks, SetupUtils::CheckSelectedModules($sSourceDir, $sExtensionDir, $aSelectedModules));

foreach($aChecks as $oCheckResult)
{
	switch ($oCheckResult->iSeverity)
	{
		case CheckResult::ERROR:
			$bHasErrors = true;
			$sHeader = "Error";
			break;

		case CheckResult::WARNING:
			$sHeader = "Warning";
			break;

		case 3: // CheckResult::TRACE added in iTop 3.0.0
			// does nothing : those are old debug traces, see N°2214
			$sHeader = 'Trace';
			break;

		case CheckResult::INFO:
		default:
			$sHeader = "Info";
			break;
	}
	echo $sHeader.": ".$oCheckResult->sLabel;
	if (strlen($oCheckResult->sDescription))
	{
		echo ' - '.$oCheckResult->sDescription;
	}
	echo "\n";
}

if ($bHasErrors)
{
	echo "Encountered stopper issues. Aborting...\n";
	$sLogMsg = "Encountered stopper issues. Aborting...";
	echo "$sLogMsg\n";
	SetupLog::Error($sLogMsg);
	die;
}

$bFoundIssues = false;

$bInstall = utils::ReadParam('install', true, true /* CLI allowed */);
if ($bInstall)
{
	echo "Starting the unattended installation...\n";
	$oWizard = new ApplicationInstaller($oParams);
	$bRes = $oWizard->ExecuteAllSteps();
	if (!$bRes)
	{
		echo "\nencountered installation issues!";
		$bFoundIssues = true;
	}
	else
	{
		$oMysqli = new mysqli($sDBServer, $sDBUser, $sDBPwd);
		if (!$oMysqli->connect_errno)
		{
			if ($oMysqli->select_db($sDBName))
			{
				// Check the presence of a table to record information about the MTP (from the Designer)
				$sDesignerUpdatesTable = $sDBPrefix.'priv_designer_update';
				$sSQL = "SELECT id FROM `$sDesignerUpdatesTable`";
				if ($oMysqli->query($sSQL) !== false)
				{
					// Record the Designer Udpates in the priv_designer_update table
					$sDeltaFile = APPROOT.'data/'.$sTargetEnvironment.'.delta.xml';
					if (is_readable($sDeltaFile))
					{
						// Retrieve the revision
						$oDoc = new DOMDocument();
						$oDoc->load($sDeltaFile);
						$iRevision = 0;
						$iRevision = $oDoc->firstChild->getAttribute('revision_id');
						if ($iRevision > 0) // Safety net, just in case...
						{
							$sDate = date('Y-m-d H:i:s');
							$sSQL = "INSERT INTO `$sDesignerUpdatesTable` (revision_id, compilation_date, comment) VALUES ($iRevision, '$sDate', 'Deployed using unattended.php.')";
							if ($oMysqli->query($sSQL) !== false)
							{
								echo "\nDesigner update (MTP at revision $iRevision) successfully recorded.\n";
							}
							else
							{
								echo "\nFailed to record designer updates(".$oMysqli->error.").\n";
							}
						}
						else
						{
							echo "\nFailed to read the revision from $sDeltaFile file. No designer update information will be recorded.\n";

						}
					}
					else
					{
						echo "\nNo $sDeltaFile file (or the file is not accessible). No designer update information to record.\n";
					}
				}
			}
		}
	}
}
else
{
	echo "No installation requested.\n";
}
if (!$bFoundIssues && $bCheckConsistency)
{
	echo "Checking data model consistency.\n";
	ob_start();
	$sCheckRes = '';
	try
	{
		MetaModel::CheckDefinitions(false);
		$sCheckRes = ob_get_clean();
	}
	catch(Exception $e)
	{
		$sCheckRes = ob_get_clean()."\nException: ".$e->getMessage();
	}
	if (strlen($sCheckRes) > 0)
	{
		echo $sCheckRes;
		echo "\nfound consistency issues!";
		$bFoundIssues = true;
	}
}

if (! $bFoundIssues)
{
	// last line: used to check the install
	// the only way to track issues in case of Fatal error or even parsing error!
	$sLogMsg = "installed!";

	if ($bUseItopConfig && is_file("$sConfigFile.backup"))
	{
		echo "\nuse config file provided by backup in $sConfigFile.";
		copy("$sConfigFile.backup", $sConfigFile);
	}

	SetupLog::Info($sLogMsg);
	echo "\n$sLogMsg";
	exit(0);
}

$sLogMsg = "installation failed!";
SetupLog::Error($sLogMsg);
echo "\n$sLogMsg";
exit(-1);
