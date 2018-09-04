<?php
require_once('../../../../approot.inc.php');
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
$sParamFile = utils::ReadParam('response_file', 'default-params.xml', true /* CLI allowed */, 'raw_data');
$bCheckConsistency = (utils::ReadParam('check_consistency', '0', true /* CLI allowed */) == '1');

$oParams = new XMLParameters($sParamFile);
$sMode = $oParams->Get('mode');

if ($sMode == 'install')
{
	echo "Installation mode detected.\n";
	$bClean = utils::ReadParam('clean', false, true /* CLI allowed */);
	if ($bClean)
	{
		echo "Cleanup mode detected.\n";
		$sTargetEnvironment = $oParams->Get('target_env', '');
		if ($sTargetEnvironment == '')
		{
			$sTargetEnvironment = 'production';
		}
		$sTargetDir = APPROOT.'env-'.$sTargetEnvironment;

		// Configuration file
		$sConfigFile = APPCONF.$sTargetEnvironment.'/'.ITOP_CONFIG_FILE;
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

		// env-xxx directory
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

		// Database
		$aDBSettings = $oParams->Get('database', array());
		$sDBServer = $aDBSettings['server'];
		$sDBUser = $aDBSettings['user'];
		$sDBPwd = $aDBSettings['pwd'];
		$sDBName = $aDBSettings['name'];
		$sDBPrefix = $aDBSettings['prefix'];

		if ($sDBPrefix != '')
		{
			die("Cleanup not implemented for a partial database (prefix= '$sDBPrefix')\nExiting.");
		}

		$oMysqli = new mysqli($sDBServer, $sDBUser, $sDBPwd);
		if ($oMysqli->connect_errno)
		{
			die("Cannot connect to the MySQL server (".$mysqli->connect_errno . ") ".$mysqli->connect_error."\nExiting");
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

$bHasErrors = false;
$aChecks = SetupUtils::CheckBackupPrerequisites(APPROOT.'data'); // mmm should be the backup destination dir

$aSelectedModules = $oParams->Get('selected_modules');
$sSourceDir = $oParams->Get('source_dir', 'datamodels/latest');
$sExtensionDir = $oParams->Get('extensions_dir', 'extensions');
$aChecks = array_merge($aChecks, SetupUtils::CheckSelectedModules($sSourceDir, $sExtensionDir, $aSelectedModules));


foreach($aChecks as $oCheckResult)
{
	switch($oCheckResult->iSeverity)
	{
		case CheckResult::ERROR:
			$bHasErrors = true;
			$sHeader = "Error";
			break;

		case CheckResult::WARNING:
			$sHeader = "Warning";
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

if (!$bFoundIssues)
{
	// last line: used to check the install
	// the only way to track issues in case of Fatal error or even parsing error!
	echo "\ninstalled!";
	exit;
}
