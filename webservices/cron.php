<?php
// Copyright (C) 2010-2013 Combodo SARL
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
		$oP->p("php cron.php --auth_user=<login> --auth_pwd=<password> [--param_file=<file>] [--verbose=1] [--status_only=1]\n");		
	}
	else
	{
		$oP->p("Optional parameters: verbose, param_file, status_only\n");		
	}
	$oP->output();
	exit -2;
}

function RunTask($oBackgroundProcess, BackgroundTask $oTask, $oStartDate, $iTimeLimit)
{
	try
	{
		$oNow = new DateTime();
		$fStart = microtime(true);
		$sMessage = $oBackgroundProcess->Process($iTimeLimit);
		$fDuration = microtime(true) - $fStart;
		$oTask->ComputeDurations($fDuration);
		$oTask->Set('latest_run_date', $oNow->format('Y-m-d H:i:s'));
		$oPlannedStart = new DateTime($oTask->Get('latest_run_date'));
		// Let's assume that the task was started exactly when planned so that the schedule does no shift each time
		// this allows to schedule a task everyday "around" 11:30PM for example
		$oPlannedStart->modify('+'.$oBackgroundProcess->GetPeriodicity().' seconds');
		$oEnd = new DateTime();
		if ($oPlannedStart->format('U') < $oEnd->format('U'))
		{
			// Huh, next planned start is already in the past, shift it of the periodicity !
			$oPlannedStart = $oEnd->modify('+'.$oBackgroundProcess->GetPeriodicity().' seconds');
		}
		$oTask->Set('next_run_date', $oPlannedStart->format('Y-m-d H:i:s'));
		$oTask->DBUpdate();
	}
	catch(Exception $e)
	{
		$sMessage = 'Processing failed, the following exception occured: '.$e->getMessage();
	}
	return $sMessage;	
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
	
	$oSearch = new DBObjectSearch('BackgroundTask');
	while (time() < $iTimeLimit)
	{
		$oTasks = new DBObjectSet($oSearch);
		$aTasks = array();
		while($oTask = $oTasks->Fetch())
		{
			$aTasks[$oTask->Get('class_name')] = $oTask;
		}
		foreach ($aBackgroundProcesses as $oBackgroundProcess)
		{
			$sTaskClass = get_class($oBackgroundProcess);
			$oNow = new DateTime();
			if (!array_key_exists($sTaskClass, $aTasks))
			{
				// New entry, let's create a new BackgroundTask record and run the task immediately
				$oTask = new BackgroundTask();
				$oTask->Set('class_name', get_class($oBackgroundProcess));
				$oTask->Set('first_run_date', $oNow->format('Y-m-d H:i:s'));
				$oTask->Set('total_exec_count', 0);
				$oTask->Set('min_run_duration', 99999.999);
				$oTask->Set('max_run_duration', 0);
				$oTask->Set('average_run_duration', 0);
				$oTask->Set('next_run_date', $oNow->format('Y-m-d H:i:s')); // in case of crash...
				$oTask->DBInsert();
				if ($bVerbose)
				{
					$oP->p(">> === ".$oNow->format('Y-m-d H:i:s').sprintf(" Starting:%-'=40s", ' '.$sTaskClass.' (first run) '));
				}
				$sMessage = RunTask($oBackgroundProcess, $oTask, $oNow, $iTimeLimit);
				if ($bVerbose)
				{
					if(!empty($sMessage))
					{
						$oP->p("$sTaskClass: $sMessage");
					}
					$oEnd = new DateTime();
					$oP->p("<< === ".$oEnd->format('Y-m-d H:i:s').sprintf(" End of:  %-'=40s", ' '.$sTaskClass.' '));
				}
			}
			else if( ($aTasks[$sTaskClass]->Get('status') == 'active') && ($aTasks[$sTaskClass]->Get('next_run_date') <= $oNow->format('Y-m-d H:i:s')))
			{
				$oTask = $aTasks[$sTaskClass];
				// Run the task and record its next run time
				if ($bVerbose)
				{
					$oP->p(">> === ".$oNow->format('Y-m-d H:i:s').sprintf(" Starting:%-'=40s", ' '.$sTaskClass.' '));
				}
				$sMessage = RunTask($oBackgroundProcess, $aTasks[$sTaskClass], $oNow, $iTimeLimit);
				if ($bVerbose)
				{
					if(!empty($sMessage))
					{
						$oP->p("$sTaskClass: $sMessage");
					}
					$oEnd = new DateTime();
					$oP->p("<< === ".$oEnd->format('Y-m-d H:i:s').sprintf(" End of:  %-'=40s", ' '.$sTaskClass.' '));
				}
			}
			else 
			{
				// will run later
				if (($aTasks[$sTaskClass]->Get('status') == 'active') && $bVerbose)
				{
					$oP->p("Skipping asynchronous task: $sTaskClass until ".$aTasks[$sTaskClass]->Get('next_run_date'));
				}
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

function DisplayStatus($oP)
{
	$oSearch = new DBObjectSearch('BackgroundTask');
	$oTasks = new DBObjectSet($oSearch);
	$oP->p('+---------------------------+---------+---------------------+---------------------+--------+-----------+');
	$oP->p('| Task Class                | Status  | Last Run            | Next Run            | Nb Run | Avg. Dur. |');
	$oP->p('+---------------------------+---------+---------------------+---------------------+--------+-----------+');
	while($oTask = $oTasks->Fetch())
	{
		$sTaskName = $oTask->Get('class_name');
		$sStatus = $oTask->Get('status');
		$sLastRunDate = $oTask->Get('latest_run_date');
		$sNextRunDate = $oTask->Get('next_run_date');
		$iNbRun = (int)$oTask->Get('total_exec_count');
		$sAverageRunTime = $oTask->Get('average_run_duration');
		$oP->p(sprintf('| %1$-25.25s | %2$-7s | %3$-19s | %4$-19s | %5$6d | %6$7s s |', $sTaskName, $sStatus, $sLastRunDate, $sNextRunDate, $iNbRun, $sAverageRunTime));
	}	
	$oP->p('+---------------------------+---------+---------------------+---------------------+--------+-----------+');
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
if (utils::ReadParam('status_only', false, true /* Allow CLI */))
{
	// Display status and exit
	DisplayStatus($oP);
	exit(0);
}

// Compute the name of a lock for mysql
// The name is server-wide
$oConfig = utils::GetConfig();
$sLockName = 'itop.cron.'.$oConfig->GetDBName().'_'.$oConfig->GetDBSubname();

$oP->p("Starting: ".time().' ('.date('Y-m-d H:i:s').')');

// CAUTION: using GET_LOCK anytime on the same connexion will RELEASE the lock
// Todo: invoke GET_LOCK from a dedicated session (encapsulate that into a mutex class)
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
	   $oP->p("ERROR:".$e->getMessage());
	   $oP->p($e->getTraceAsString());
	}
	$res = CMDBSource::QueryToScalar("SELECT RELEASE_LOCK('$sLockName')");
}
else
{
	// Lock already held by another session
	// Exit silently
	$oP->p("Already running...");
}
$oP->p("Exiting: ".time().' ('.date('Y-m-d H:i:s').')');

$oP->Output();
?>
