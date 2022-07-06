<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

use Combodo\iTop\Service\Cron\CronLog;

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
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
	$fStart = microtime(true);
	$oCtx = new ContextTag('CRON:Task:'.$TaskClass);

	$sMessage = '';
	$oExceptionToThrow = null;
	try
	{
		// Record (when starting) that this task was started, just in case it crashes during the execution
		if ($oTask->Get('total_exec_count') == 0) {
			// First execution
			$oTask->Set('first_run_date', $oDateStarted->format('Y-m-d H:i:s'));
		}
		$oTask->Set('latest_run_date', $oDateStarted->format('Y-m-d H:i:s'));
		// Record the current user running the cron
		$oTask->Set('system_user', utils::GetCurrentUserName());
		$oTask->Set('running', 1);
		// Compute the next run date
		if ($oRefClass->implementsInterface('iScheduledProcess')) {
			// Schedules process do repeat at specific moments
			$oPlannedStart = $oProcess->GetNextOccurrence();
		} else {
			// Background processes do repeat periodically
			$oDatePlanned = new DateTime($oTask->Get('next_run_date'));
			$oPlannedStart = clone $oDatePlanned;
			// Let's schedule from the previous planned date of execution to avoid shift
			$oPlannedStart->modify('+'.$oProcess->GetPeriodicity().' seconds');
			$oNow = new DateTime();
			while ($oPlannedStart->format('U') <= $oNow->format('U')) {
				// Next planned start is already in the past, increase it again by a period
				$oPlannedStart = $oPlannedStart->modify('+'.$oProcess->GetPeriodicity().' seconds');
			}
		}
		$oTask->Set('next_run_date', $oPlannedStart->format('Y-m-d H:i:s'));
		$oTask->DBUpdate();

		$sMessage = $oProcess->Process($iTimeLimit);
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
	} finally {
		$oTask->Set('running', 0);
		$fDuration = microtime(true) - $fStart;
		$oTask->ComputeDurations($fDuration); // does increment the counter and compute statistics
		$oTask->DBUpdate();
	}

	if ($oExceptionToThrow)
	{
		throw $oExceptionToThrow;
	}

	unset($oCtx);

	return $sMessage;
}

/**
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
function CronExec($bDebug)
{
	$iStarted = time();
	$iMaxDuration = MetaModel::GetConfig()->Get('cron_max_execution_time');
	$iTimeLimit = $iStarted + $iMaxDuration;
	$iCronSleep = MetaModel::GetConfig()->Get('cron_sleep');
	$iMaxCronProcess = MetaModel::GetConfig()->Get('cron.max_processes');

	CronLog::Debug("Planned duration = $iMaxDuration seconds");
	CronLog::Debug("Loop pause = $iCronSleep seconds");

	ReSyncProcesses($bDebug);

	while (time() < $iTimeLimit)
	{
		CheckMaintenanceMode();

		$oNow = new DateTime();
		$sNow = $oNow->format('Y-m-d H:i:s');
		$oSearch = new DBObjectSearch('BackgroundTask');
		$oSearch->AddCondition('next_run_date', $sNow, '<=');
		$oSearch->AddCondition('status', 'active');
		$oTasks = new DBObjectSet($oSearch, ['next_run_date' => true]);

		$aTasks = [];
		if ($oTasks->CountExceeds(0))
		{
			$sCount = $oTasks->Count();
			CronLog::Debug("$sCount Tasks planned to run now ($sNow):");
			while ($oTask = $oTasks->Fetch())
			{
				$sTaskName = $oTask->Get('class_name');
				$oTaskMutex = new iTopMutex("cron_$sTaskName");
				if ($oTaskMutex->IsLocked()) {
					// Already running, ignore
					continue;
				}
				$aTasks[] = $oTask;
				$sStatus = $oTask->Get('status');
				$sLastRunDate = $oTask->Get('latest_run_date');
				$sNextRunDate = $oTask->Get('next_run_date');
				CronLog::Debug(sprintf('Task Class: %1$-25.25s Status: %2$-7s Last Run: %3$-19s Next Run: %4$-19s', $sTaskName, $sStatus, $sLastRunDate, $sNextRunDate));
			}
			$aRunTasks = [];
			while ($aTasks != []) {
				$oTask = array_shift($aTasks);

				$sTaskClass = $oTask->Get('class_name');

				// Check if the current task is running
				$oTaskMutex = new iTopMutex("cron_$sTaskClass");
				if (!$oTaskMutex->TryLock()) {
					// Task is already running, try next one
					continue;
				}

				$aRunTasks[] = $sTaskClass;

				// N°3219 for each process will use a specific CMDBChange object with a specific track info
				// Any BackgroundProcess can overrides this as needed
				CMDBObject::SetCurrentChangeFromParams("Background task ($sTaskClass)");

				// Run the task and record its next run time
				$oNow = new DateTime();
				CronLog::Debug(">> === ".$oNow->format('Y-m-d H:i:s').sprintf(" Start task:%-'=49s", ' '.$sTaskClass.' '));
				try
				{
					$sMessage = RunTask($oTask, $iTimeLimit);
				} catch (MySQLHasGoneAwayException $e)
				{
					CronLog::Error("ERROR : 'MySQL has gone away' thrown when processing $sTaskClass  (error_code=".$e->getCode().")");
					exit(EXIT_CODE_FATAL);
				} catch (ProcessFatalException $e)
				{
					CronLog::Error("ERROR : an exception was thrown when processing '$sTaskClass' (".$e->getInfoLog().")");
				}
				finally {
					$oTaskMutex->Unlock();
				}
				if (!empty($sMessage))
				{
					CronLog::Debug("$sTaskClass: $sMessage");
				}
				$oEnd = new DateTime();
				$sNextRunDate = $oTask->Get('next_run_date');
				CronLog::Debug("<< === ".$oEnd->format('Y-m-d H:i:s').sprintf(" End of:  %-'=42s", ' '.$sTaskClass.' ')." Next: $sNextRunDate");
				if (time() > $iTimeLimit)
				{
					break 2;
				}
				CheckMaintenanceMode();
				if ($iMaxCronProcess > 1) {
					// Reindex tasks every time
					break;
				}
			}

			// Tasks to run later
			if (count($aTasks) == 0)
			{
				$oSearch = new DBObjectSearch('BackgroundTask');
				$oSearch->AddCondition('next_run_date', $sNow, '>');
				$oSearch->AddCondition('status', 'active');
				$oTasks = new DBObjectSet($oSearch, ['next_run_date' => true]);
				while ($oTask = $oTasks->Fetch())
				{
					if (!in_array($oTask->Get('class_name'), $aRunTasks))
					{
						CronLog::Debug(sprintf("-- Skipping task: %-'-40s", $oTask->Get('class_name').' ')." until: ".$oTask->Get('next_run_date'));
					}
				}
			}
		}
		if (count($aTasks) == 0) {
			sleep($iCronSleep);
		}
	}
	CronLog::Debug("Reached normal execution time limit (exceeded by ".(time() - $iTimeLimit)."s)");
}

/**
 */
function CheckMaintenanceMode() {
// Verify files instead of reloading the full config each time
	if (file_exists(MAINTENANCE_MODE_FILE) || file_exists(READONLY_MODE_FILE)) {
		CronLog::Info("Maintenance detected, exiting");
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
function DisplayStatus($oP = null, $aTaskOrderBy = [])
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
function ReSyncProcesses($bDebug)
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
	foreach (get_declared_classes() as $sTaskClass)
	{
		$oRefClass = new ReflectionClass($sTaskClass);
		if ($oRefClass->isAbstract())
		{
			continue;
		}
		if ($oRefClass->implementsInterface('iProcess'))
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
				CronLog::Debug('Creating record for: '.$sTaskClass);
				CronLog::Debug('First execution planned at: '.$oTask->Get('next_run_date'));
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

	$aDisplayProcesses = array();
	foreach ($aProcesses as $oExecInstance)
	{
		$aDisplayProcesses[] = get_class($oExecInstance);
	}
	$sDisplayProcesses = implode(', ', $aDisplayProcesses);
	CronLog::Debug("Background processes: ".$sDisplayProcesses);
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
}
catch (Exception $e)
{
	$oP->p("Error: ".$e->GetMessage());
	$oP->output();
	exit(EXIT_CODE_FATAL);
}

CronLog::Enable(APPROOT.'/log/cron.log');
try
{
	if (!MetaModel::DBHasAccess(ACCESS_ADMIN_WRITE))
	{
		CronLog::Info("A maintenance is ongoing");
	}
	else
	{
		// Limit the number of cron process to run in parallel
		$iMaxCronProcess = MetaModel::GetConfig()->Get('cron.max_processes');
		$bCanRun = false;
		$iProcessNumber = 0;
		for ($i = 0; $i < $iMaxCronProcess; $i++) {
			$oMutex = new iTopMutex("cron#$i");
			if ($oMutex->TryLock()) {
				$iProcessNumber = $i + 1;
				$bCanRun = true;
				break;
			}
		}
		if ($bCanRun) {
			CronLog::$iProcessNumber = $iProcessNumber;
			CronLog::Info('Starting: '.time().' ('.date('Y-m-d H:i:s').')');
			CronExec($bDebug);
		} else {
			CronLog::Debug("Already $iMaxCronProcess are running...");
		}
	}
}
catch (Exception $e)
{
	CronLog::Error("ERROR: '".$e->getMessage()."'");
	// Might contain verb parameters such a password...
	CronLog::Debug($e->getTraceAsString());
}

CronLog::Info("Exiting: ".time().' ('.date('Y-m-d H:i:s').')');
$oP->Output();
