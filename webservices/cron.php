<?php
// Copyright (C) 2010-2012 Combodo SARL
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

/**
 * Heart beat of the application (process asynchron tasks such as broadcasting email)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/nicewebpage.class.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/clipage.class.inc.php');
require_once(APPROOT.'/application/startup.inc.php');



function ReadMandatoryParam($oP, $sParam, $sSanitizationFilter = 'parameter')
{
	$sValue = utils::ReadParam($sParam, null, true /* Allow CLI */, $sSanitizationFilter);
	if (is_null($sValue))
	{
		$oP->p("ERROR: Missing argument '$sParam'\n");
		UsageAndExit($oP);
	}
	return trim($sValue);
}

function UsageAndExit($oP)
{
	$bModeCLI = utils::IsModeCLI();

	if ($bModeCLI)
	{
		$oP->p("USAGE:\n");
		$oP->p("php -q cron.php --auth_user=<login> --auth_pwd=<password> [--param_file=<file>] [--verbose=1]\n");		
	}
	else
	{
		$oP->p("Optional parameters: verbose, param_file\n");		
	}
	$oP->output();
	exit -2;
}


// Known limitation - the background process periodicity is NOT taken into account
function CronExec($oP, $aBackgroundProcesses, $bVerbose)
{
	$iStarted = time();
	$iMaxDuration = MetaModel::GetConfig()->Get('cron_max_execution_time');
	$iTimeLimit = $iStarted + $iMaxDuration;
	
	if ($bVerbose)
	{
		$oP->p("Planned duration = $iMaxDuration seconds");
	}

	$iCronSleep = MetaModel::GetConfig()->Get('cron_sleep');
	
	while (time() < $iTimeLimit)
	{
		foreach ($aBackgroundProcesses as $oBackgroundProcess)
		{
			if ($bVerbose)
			{
				$oP->p("Processing asynchronous task: ".get_class($oBackgroundProcess));
			}
			$sMessage = $oBackgroundProcess->Process($iTimeLimit);
			if ($bVerbose && !empty($sMessage))
			{
				$oP->p("Returned: $sMessage");
			}
		}
		if ($bVerbose)
		{
			$oP->p("Sleeping");
		}
		sleep($iCronSleep);
	}
	if ($bVerbose)
	{
		$oP->p("Reached normal execution time limit (exceeded by ".(time()-$iTimeLimit)."s)");
	}
}

////////////////////////////////////////////////////////////////////////////////
//
// Main
//
if (utils::IsModeCLI())
{
	$oP = new CLIPage("iTop - CRON");
}
else
{
	$oP = new WebPage("iTop - CRON");
}

try
{
	utils::UseParamFile();
}
catch(Exception $e)
{
	$oP->p("Error: ".$e->GetMessage());
	$oP->output();
	exit -2;
}

if (utils::IsModeCLI())
{
	// Next steps:
	//   specific arguments: 'csvfile'
	//   
	$sAuthUser = ReadMandatoryParam($oP, 'auth_user', 'raw_data');
	$sAuthPwd = ReadMandatoryParam($oP, 'auth_pwd', 'raw_data');
	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
	{
		UserRights::Login($sAuthUser); // Login & set the user's language
	}
	else
	{
		$oP->p("Access wrong credentials ('$sAuthUser')");
		$oP->output();
		exit -1;
	}
}
else
{
	$_SESSION['login_mode'] = 'basic';
	require_once(APPROOT.'/application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed
}

if (!UserRights::IsAdministrator())
{
	$oP->p("Access restricted to administrators");
	$oP->Output();
	exit -1;
}


// Enumerate classes implementing BackgroundProcess
//
$aBackgroundProcesses = array();
foreach(get_declared_classes() as $sPHPClass)
{
	$oRefClass = new ReflectionClass($sPHPClass);
	$oExtensionInstance = null;
	if ($oRefClass->implementsInterface('iBackgroundProcess'))
	{
		if (is_null($oExtensionInstance))
		{
			$oExecInstance = new $sPHPClass;
		}
		$aBackgroundProcesses[$sPHPClass] = $oExecInstance;
	}
}


$bVerbose = utils::ReadParam('verbose', false, true /* Allow CLI */);

if ($bVerbose)
{
	$aDisplayProcesses = array();
	foreach ($aBackgroundProcesses as $oExecInstance)
	{
		$aDisplayProcesses[] = get_class($oExecInstance);
	}
	$sDisplayProcesses = implode(', ', $aDisplayProcesses);
	$oP->p("Background processes: ".$sDisplayProcesses);
}

$sLockName = 'itop.cron.php';

$oP->p("Starting: ".time());
$res = CMDBSource::QueryToScalar("SELECT GET_LOCK('$sLockName', 1)");// timeout = 1 second (see also IS_FREE_LOCK)
if (is_null($res))
{
	// TODO - Log ?
	$oP->p("ERROR: Failed to acquire the lock '$sLockName'");
}
elseif ($res === '1')
{
	// The current session holds the lock
	try
	{
		CronExec($oP, $aBackgroundProcesses, $bVerbose);
	}
	catch(Exception $e)
	{
		// TODO - Log ?
	   $oP->p("ERROR:".$e->GetMessage());
	}
	$res = CMDBSource::QueryToScalar("SELECT RELEASE_LOCK('$sLockName')");
}
else
{
	// Lock already held by another session
	// Exit silently
	$oP->p("Already running...");
}
$oP->p("Exiting: ".time());

$oP->Output();
?>
