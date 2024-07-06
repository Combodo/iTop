<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

use Combodo\iTop\Application\WebPage\CLIPage;
use Combodo\iTop\Application\WebPage\Page;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;

require_once(__DIR__.'/../approot.inc.php');

const EXIT_CODE_ERROR = -1;
const EXIT_CODE_FATAL = -2;
// early exit
if (file_exists(READONLY_MODE_FILE))
{
	echo "iTop is read-only. Exiting...\n";
	exit(EXIT_CODE_ERROR);
}

require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/core/background.inc.php');

$sConfigFile = APPCONF.ITOP_DEFAULT_ENV.'/'.ITOP_CONFIG_FILE;
if (!file_exists($sConfigFile))
{
	echo "iTop is not yet installed. Exiting...\n";
	exit(EXIT_CODE_ERROR);
}

require_once(APPROOT.'/application/startup.inc.php');

$oCtx = new ContextTag(ContextTag::TAG_CRON);

function ReadMandatoryParam($oP, $sParam, $sSanitizationFilter = 'parameter')
{
	$sValue = utils::ReadParam($sParam, null, true, $sSanitizationFilter);
	if (is_null($sValue))
	{
		$oP->p("ERROR: Missing argument '$sParam'\n");
		UsageAndExit($oP);
	}

	return trim($sValue);
}

function UsageAndExit($oP)
{
	$bModeCLI = ($oP instanceof CLIPage);

	if ($bModeCLI)
	{
		$oP->p("USAGE:\n");
		$oP->p("php cron.php --auth_user=<login> --auth_pwd=<password> [--param_file=<file>] [--verbose=1] [--debug=1] [--status_only=1]\n");
	}
	else
	{
		$oP->p("Optional parameters: verbose, param_file, status_only\n");
	}
	$oP->output();
	exit(EXIT_CODE_FATAL);
}

/**
 * @param \BackgroundTask $oTask
 * @param int $iTimeLimit
 *
 * @return string
 * @throws \ArchivedObjectException
 * @throws \CoreCannotSaveObjectException
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \MySQLHasGoneAwayException
 * @throws \ProcessFatalException
 * @throws \ReflectionException
 * @throws \Exception
 */
function RunTask(BackgroundTask $oTask, $iTimeLimit)
{
	$TaskClass = $oTask->Get('class_name');
	$oProcess = new $TaskClass;
	$oRefClass = new ReflectionClass(get_class($oProcess));
	$oDateStarted = new DateTime();
	$oDatePlanned = new DateTime($oTask->Get('next_run_date'));
	$fStart = microtime(true);
	$oCtx = new ContextTag('CRON:Task:'.$TaskClass);

	$sMessage = '';
	$oExceptionToThrow = null;
	try
	{
		// Record (when starting) that this task was started, just in case it crashes during the execution
		$oTask->Set('latest_run_date', $oDateStarted->format('Y-m-d H:i:s'));
		// Record the current user running the cron
		$oTask->Set('system_user', utils::GetCurrentUserName());
		$oTask->Set('running', 1);
		$oTask->DBUpdate();
		// Time in seconds allowed to the task
		$iCurrTimeLimit = $iTimeLimit;
		// Compute allowed time
		if ($oRefClass->implementsInterface('iScheduledProcess') === false)
		{
			// Periodic task, allow only X times ($iMaxTaskExecutionTime) its periodicity (GetPeriodicity())
			$iMaxTaskExecutionTime = MetaModel::GetConfig()->Get('cron_task_max_execution_time');
			$iTaskLimit = time() + $oProcess->GetPeriodicity() * $iMaxTaskExecutionTime;
			// If our proposed time limit is less than cron limit, and cron_task_max_execution_time is > 0
			if ($iTaskLimit < $iTimeLimit && $iMaxTaskExecutionTime > 0)
			{
				$iCurrTimeLimit = $iTaskLimit;
			}
		}
		$sMessage = $oProcess->Process($iCurrTimeLimit);
		$oTask->Set('running', 0);
	}
	catch (MySQLHasGoneAwayException $e)
	{
		throw $e;
	}
	catch (ProcessFatalException $e)
	{
		$oExceptionToThrow = $e;
	}
	catch (Exception $e) // we shouldn't get so much exceptions... but we need to handle legacy code, and cron.php has to keep running
	{
		if ($oTask->IsDebug())
		{
			$sMessage = 'Processing failed with message: '. $e->getMessage() . '. ' . $e->getTraceAsString();
		}
		else
		{
			$sMessage = 'Processing failed with message: '. $e->getMessage();
		}
	}
	$fDuration = microtime(true) - $fStart;
	if ($oTask->Get('total_exec_count') == 0)
	{
		// First execution
		$oTask->Set('first_run_date', $oDateStarted->format('Y-m-d H:i:s'));
	}
	$oTask->ComputeDurations($fDuration); // does increment the counter and compute statistics

	// Update the timestamp since we want to be able to re-order the tasks based on the time they finished
	$oDateEnded = new DateTime();
	$oTask->Set('latest_run_date', $oDateEnded->format('Y-m-d H:i:s'));

	if ($oRefClass->implementsInterface('iScheduledProcess'))
	{
		// Schedules process do repeat at specific moments
		$oPlannedStart = $oProcess->GetNextOccurrence();
	}
	else
	{
		// Background processes do repeat periodically
		$oPlannedStart = clone $oDatePlanned;
		// Let's schedule from the previous planned date of execution to avoid shift
		$oPlannedStart->modify('+'.$oProcess->GetPeriodicity().' seconds');
		$oEnd = new DateTime();
		while ($oPlannedStart->format('U') < $oEnd->format('U'))
		{
			// Next planned start is already in the past, increase it again by a period
			$oPlannedStart = $oPlannedStart->modify('+'.$oProcess->GetPeriodicity().' seconds');
		}
	}

	$oTask->Set('next_run_date', $oPlannedStart->format('Y-m-d H:i:s'));
	$oTask->DBUpdate();

	if ($oExceptionToThrow)
	{
		throw $oExceptionToThrow;
	}

	unset($oCtx);

	return $sMessage;
}

/**
 * @param CLIPage|WebPage $oP
 * @param boolean $bVerbose
 *
 * @param bool $bDebug
 *
 * @throws \ArchivedObjectException
 * @throws \CoreCannotSaveObjectException
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \CoreWarning
 * @throws \MissingQueryArgument
 * @throws \MySQLException
 * @throws \MySQLHasGoneAwayException
 * @throws \OQLException
 * @throws \ReflectionException
 */
function CronExec($oP, $bVerbose, $bDebug=false)
{
	$iStarted = time();
	$iMaxDuration = MetaModel::GetConfig()->Get('cron_max_execution_time');
	$iTimeLimit = $iStarted + $iMaxDuration;
	$iCronSleep = MetaModel::GetConfig()->Get('cron_sleep');

	if ($bVerbose)
	{
		$oP->p("Planned duration = $iMaxDuration seconds");
		$oP->p("Loop pause = $iCronSleep seconds");
	}

	ReSyncProcesses($oP, $bVerbose, $bDebug);

	while (time() < $iTimeLimit)
	{
		CheckMaintenanceMode($oP);

		$oNow = new DateTime();
		$sNow = $oNow->format('Y-m-d H:i:s');
		$oSearch = new DBObjectSearch('BackgroundTask');
		$oSearch->AddCondition('next_run_date', $sNow, '<=');
		$oSearch->AddCondition('status', 'active');
		$oTasks = new DBObjectSet($oSearch, ['next_run_date' => true]);
		$bWorkDone = false;

		if ($oTasks->CountExceeds(0))
		{
			$bWorkDone = true;
			$aTasks = array();
			if ($bVerbose)
			{
				$sCount = $oTasks->Count();
				$oP->p("$sCount Tasks planned to run now ($sNow):");
				$oP->p('+---------------------------+---------+---------------------+---------------------+');
				$oP->p('| Task Class                | Status  | Last Run            | Next Run            |');
				$oP->p('+---------------------------+---------+---------------------+---------------------+');
			}
			while ($oTask = $oTasks->Fetch())
			{
				$aTasks[$oTask->Get('class_name')] = $oTask;
				if ($bVerbose)
				{
					$sTaskName = $oTask->Get('class_name');
					$sStatus = $oTask->Get('status');
					$sLastRunDate = $oTask->Get('latest_run_date');
					$sNextRunDate = $oTask->Get('next_run_date');
					$oP->p(sprintf('| %1$-25.25s | %2$-7s | %3$-19s | %4$-19s |', $sTaskName, $sStatus, $sLastRunDate, $sNextRunDate));
				}
			}
			if ($bVerbose)
			{
				$oP->p('+---------------------------+---------+---------------------+---------------------+');
			}
			$aRunTasks = [];
			foreach ($aTasks as $oTask)
			{
				$sTaskClass = $oTask->Get('class_name');
				$aRunTasks[] = $sTaskClass;

				// N°3219 for each process will use a specific CMDBChange object with a specific track info
				// Any BackgroundProcess can overrides this as needed
				CMDBObject::SetCurrentChangeFromParams("Background task ($sTaskClass)");

				// Run the task and record its next run time
				if ($bVerbose)
				{
					$oNow = new DateTime();
					$oP->p(">> === ".$oNow->format('Y-m-d H:i:s').sprintf(" Starting:%-'=49s", ' '.$sTaskClass.' '));
				}
				try
				{
					$sMessage = RunTask($aTasks[$sTaskClass], $iTimeLimit);
				} catch (MySQLHasGoneAwayException $e)
				{
					$oP->p("ERROR : 'MySQL has gone away' thrown when processing $sTaskClass  (error_code=".$e->getCode().")");
					exit(EXIT_CODE_FATAL);
				} catch (ProcessFatalException $e)
				{
					$oP->p("ERROR : an exception was thrown when processing '$sTaskClass' (".$e->getInfoLog().")");
					IssueLog::Error("Cron.php error : an exception was thrown when processing '$sTaskClass' (".$e->getInfoLog().')');
				}
				if ($bVerbose)
				{
					if (!empty($sMessage))
					{
						$oP->p("$sTaskClass: $sMessage");
					}
					$oEnd = new DateTime();
					$sNextRunDate = $oTask->Get('next_run_date');
					$oP->p("<< === ".$oEnd->format('Y-m-d H:i:s').sprintf(" End of:  %-'=42s", ' '.$sTaskClass.' ')." Next: $sNextRunDate");
				}
				if (time() > $iTimeLimit)
				{
					break 2;
				}
				CheckMaintenanceMode($oP);
			}

			// Tasks to run later
			if ($bVerbose)
			{
				$oP->p('--');
				$oSearch = new DBObjectSearch('BackgroundTask');
				$oSearch->AddCondition('next_run_date', $sNow, '>');
				$oSearch->AddCondition('status', 'active');
				$oTasks = new DBObjectSet($oSearch, ['next_run_date' => true]);
				while ($oTask = $oTasks->Fetch())
				{
					if (!in_array($oTask->Get('class_name'), $aRunTasks))
					{
						$oP->p(sprintf("-- Skipping task: %-'-40s", $oTask->Get('class_name').' ')." until: ".$oTask->Get('next_run_date'));
					}
				}
			}
		}

		if ($bVerbose && $bWorkDone)
		{
			$oP->p("Sleeping...\n");
		}
		sleep($iCronSleep);
	}
	if ($bVerbose)
	{
		$oP->p('');
		DisplayStatus($oP, ['next_run_date' => true]);
		$oP->p("Reached normal execution time limit (exceeded by ".(time() - $iTimeLimit)."s)");
	}
}

/**
 * @param WebPage $oP
 */
function CheckMaintenanceMode(Page $oP) {
// Verify files instead of reloading the full config each time
	if (file_exists(MAINTENANCE_MODE_FILE) || file_exists(READONLY_MODE_FILE)) {
		$oP->p("Maintenance detected, exiting");
		exit(EXIT_CODE_ERROR);
	}
}

/**
 * @param $oP
 * @param array $aTaskOrderBy
 *
 * @throws \ArchivedObjectException
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \MySQLException
 * @throws \OQLException
 */
function DisplayStatus($oP, $aTaskOrderBy = [])
{
	$oSearch = new DBObjectSearch('BackgroundTask');
	$oTasks = new DBObjectSet($oSearch, $aTaskOrderBy);
	$oP->p('+---------------------------+---------+---------------------+---------------------+--------+-----------+');
	$oP->p('| Task Class                | Status  | Last Run            | Next Run            | Nb Run | Avg. Dur. |');
	$oP->p('+---------------------------+---------+---------------------+---------------------+--------+-----------+');
	while ($oTask = $oTasks->Fetch())
	{
		$sTaskName = $oTask->Get('class_name');
		$sStatus = $oTask->Get('status');
		$sLastRunDate = $oTask->Get('latest_run_date');
		$sNextRunDate = $oTask->Get('next_run_date');
		$iNbRun = (int)$oTask->Get('total_exec_count');
		$sAverageRunTime = $oTask->Get('average_run_duration');
		$oP->p(sprintf('| %1$-25.25s | %2$-7s | %3$-19s | %4$-19s | %5$6d | %6$7s s |', $sTaskName, $sStatus,
			$sLastRunDate, $sNextRunDate, $iNbRun, $sAverageRunTime));
	}
	$oP->p('+---------------------------+---------+---------------------+---------------------+--------+-----------+');
}

/**
 * @param $oP
 * @param $bVerbose
 * @param $bDebug
 *
 * @throws \ArchivedObjectException
 * @throws \CoreCannotSaveObjectException
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \CoreWarning
 * @throws \MySQLException
 * @throws \OQLException
 * @throws \ReflectionException
 */
function ReSyncProcesses($oP, $bVerbose, $bDebug)
{
	// Enumerate classes implementing BackgroundProcess
	//
	$oSearch = new DBObjectSearch('BackgroundTask');
	$oTasks = new DBObjectSet($oSearch);
	$aTasks = array();
	while ($oTask = $oTasks->Fetch())
	{
		$aTasks[$oTask->Get('class_name')] = $oTask;
	}
	$oNow = new DateTime();

	$aProcesses = array();
	foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iProcess::class) as $sTaskClass)
	{
		$oProcess = new $sTaskClass;
		$aProcesses[$sTaskClass] = $oProcess;

		// Create missing entry if needed
		if (!array_key_exists($sTaskClass, $aTasks))
		{
			// New entry, let's create a new BackgroundTask record, and plan the first execution
			$oTask = new BackgroundTask();
			$oTask->SetDebug($bDebug);
			$oTask->Set('class_name', $sTaskClass);
			$oTask->Set('total_exec_count', 0);
			$oTask->Set('min_run_duration', 99999.999);
			$oTask->Set('max_run_duration', 0);
			$oTask->Set('average_run_duration', 0);
			$oRefClass = new ReflectionClass($sTaskClass);
			if ($oRefClass->implementsInterface('iScheduledProcess'))
			{
				$oNextOcc = $oProcess->GetNextOccurrence();
				$oTask->Set('next_run_date', $oNextOcc->format('Y-m-d H:i:s'));
			}
			else
			{
				// Background processes do start asap, i.e. "now"
				$oTask->Set('next_run_date', $oNow->format('Y-m-d H:i:s'));
			}
			if ($bVerbose)
			{
				$oP->p('Creating record for: '.$sTaskClass);
				$oP->p('First execution planned at: '.$oTask->Get('next_run_date'));
			}
			$oTask->DBInsert();
		}
		else
		{
			/** @var \BackgroundTask $oTask */
			$oTask = $aTasks[$sTaskClass];
			if ($oTask->Get('next_run_date') == '3000-01-01 00:00:00')
			{
				// check for rescheduled tasks
				$oRefClass = new ReflectionClass($sTaskClass);
				if ($oRefClass->implementsInterface('iScheduledProcess'))
				{
					$oNextOcc = $oProcess->GetNextOccurrence();
					$oTask->Set('next_run_date', $oNextOcc->format('Y-m-d H:i:s'));
					$oTask->DBUpdate();
				}
			}
			// Reactivate task if necessary
			if ($oTask->Get('status') == 'removed')
			{
				$oTask->Set('status', 'active');
				$oTask->DBUpdate();
			}
			// task having a real class to execute
			unset($aTasks[$sTaskClass]);
		}
	}

	// Remove all the tasks not having a valid class
	foreach ($aTasks as $oTask)
	{
		$sTaskClass = $oTask->Get('class_name');
		if (!class_exists($sTaskClass))
		{
			$oTask->Set('status', 'removed');
			$oTask->DBUpdate();
		}
	}

	if ($bVerbose)
	{
		$aDisplayProcesses = array();
		foreach ($aProcesses as $oExecInstance)
		{
			$aDisplayProcesses[] = get_class($oExecInstance);
		}
		$sDisplayProcesses = implode(', ', $aDisplayProcesses);
		$oP->p("Background processes: ".$sDisplayProcesses);
	}
}

////////////////////////////////////////////////////////////////////////////////
//
// Main
//

set_time_limit(0); // Some background actions may really take long to finish (like backup)

$bIsModeCLI = utils::IsModeCLI();
if ($bIsModeCLI)
{
	$oP = new CLIPage("iTop - cron");

	SetupUtils::CheckPhpAndExtensionsForCli($oP, EXIT_CODE_FATAL);
}
else
{
	$oP = new WebPage("iTop - cron");
}

try
{
	utils::UseParamFile();

	$bVerbose = utils::ReadParam('verbose', false, true /* Allow CLI */);
	$bDebug = utils::ReadParam('debug', false, true /* Allow CLI */);

	if ($bIsModeCLI)
	{
		// Next steps:
		//   specific arguments: 'csv file'
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
			exit(EXIT_CODE_ERROR);
		}
	}
	else
	{
		require_once(APPROOT.'/application/loginwebpage.class.inc.php');
		LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	}

	if (!UserRights::IsAdministrator())
	{
		$oP->p("Access restricted to administrators");
		$oP->Output();
		exit(EXIT_CODE_ERROR);
	}


	if (utils::ReadParam('status_only', false, true /* Allow CLI */))
	{
		// Display status and exit
		DisplayStatus($oP);
		exit(0);
	}

	require_once(APPROOT.'core/mutex.class.inc.php');
	$oP->p("Starting: ".time().' ('.date('Y-m-d H:i:s').')');
}
catch (Exception $e)
{
	$oP->p("Error: ".$e->GetMessage());
	$oP->output();
	exit(EXIT_CODE_FATAL);
}

try
{
	$oMutex = new iTopMutex('cron');
	if (!MetaModel::DBHasAccess(ACCESS_ADMIN_WRITE))
	{
		$oP->p("A maintenance is ongoing");
	}
	else
	{
		if ($oMutex->TryLock())
		{
			CronExec($oP, $bVerbose, $bDebug);
		}
		else
		{
			// Exit silently
			$oP->p("Already running...");
		}
	}
}
catch (Exception $e)
{
	$oP->p("ERROR: '".$e->getMessage()."'");
	if ($bDebug)
	{
		// Might contain verb parameters such a password...
		$oP->p($e->getTraceAsString());
	}
}
finally
{
	try
	{
		$oMutex->Unlock();
	}
	catch (Exception $e)
	{
		$oP->p("ERROR: '".$e->getMessage()."'");
		if ($bDebug)
		{
			// Might contain verb parameters such a password...
			$oP->p($e->getTraceAsString());
		}
	}
}

$oP->p("Exiting: ".time().' ('.date('Y-m-d H:i:s').')');
$oP->Output();
