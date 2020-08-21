<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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
require_once(APPROOT.'/application/nicewebpage.class.inc.php');
require_once(APPROOT.'/application/webpage.class.inc.php');
require_once(APPROOT.'/application/clipage.class.inc.php');
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
 * @param iProcess $oProcess
 * @param \BackgroundTask $oTask
 * @param DateTime $oStartDate
 * @param int $iTimeLimit
 *
 * @return string
 * @throws \ProcessFatalException
 * @throws MySQLHasGoneAwayException
 */
function RunTask($oProcess, BackgroundTask $oTask, $oStartDate, $iTimeLimit)
{
	$oDateStarted = new DateTime();
	$fStart = microtime(true);
	$oCtx = new ContextTag('CRON:Task:'.$oTask->Get('class_name'));

	$sMessage = "";
	$oExceptionToThrow = null;
	try
	{
		// Record (when starting) that this task was started, just in case it crashes during the execution
		$oTask->Set('latest_run_date', $oDateStarted->format('Y-m-d H:i:s'));
		// Record the current user running the cron
		$oTask->Set('system_user', utils::GetCurrentUserName());
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
			$sMessage = 'Processing failed with message: '.$e->getTraceAsString();
		}
	else
		{
			$sMessage = 'Processing failed with message: '.$e->getMessage();
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

	$oRefClass = new ReflectionClass(get_class($oProcess));
	if ($oRefClass->implementsInterface('iScheduledProcess'))
	{
		// Schedules process do repeat at specific moments
		$oPlannedStart = $oProcess->GetNextOccurrence();
	}
	else
	{
		// Background processes do repeat periodically
		$oPlannedStart = clone $oDateStarted;
		// Let's assume that the task was started exactly when planned so that the schedule does no shift each time
		// this allows to schedule a task everyday "around" 11:30PM for example
		$oPlannedStart->modify('+'.$oProcess->GetPeriodicity().' seconds');
		$oEnd = new DateTime();
		if ($oPlannedStart->format('U') < $oEnd->format('U'))
		{
			// Huh, next planned start is already in the past, shift it of the periodicity !
			$oPlannedStart = $oEnd->modify('+'.$oProcess->GetPeriodicity().' seconds');
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
 * @param iProcess[] $aProcesses
 * @param boolean $bVerbose
 * @param bool $bDebug
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
function CronExec($oP, $aProcesses, $bVerbose, $bDebug=false)
{
	$iStarted = time();
	$iMaxDuration = MetaModel::GetConfig()->Get('cron_max_execution_time');
	$iTimeLimit = $iStarted + $iMaxDuration;

	if ($bVerbose)
	{
		$oP->p("Planned duration = $iMaxDuration seconds");
	}

	// Reset the next planned execution to take into account new settings
	$oSearch = new DBObjectSearch('BackgroundTask');
	/** @var DBObjectSet $oTasks */
	$oTasks = new DBObjectSet($oSearch);
	/** @var BackgroundTask $oTask */
	while ($oTask = $oTasks->Fetch())
	{
		$sTaskClass = $oTask->Get('class_name');
		// The BackgroundTask can point to a non existing class : this could happen for example if an extension has been removed
		// we could also try/catch when instanciating ReflectionClass, but sometimes old recipes are good too ;)
		if (!class_exists($sTaskClass))
		{
			if ($oTask->Get('status') == 'active')
			{
				$oP->p("ERROR : the background task was paused because it references the non existing class '$sTaskClass'");

				$oTask->Set('status', 'paused');
				$oTask->DBUpdate();
			}

			continue;
		}

		$oRefClass = new ReflectionClass($sTaskClass);
		if (!$oRefClass->implementsInterface('iScheduledProcess'))
		{
			continue;
		}

		$oNow = new DateTime();
		//don't recalculate next occurence if next_run_date is
		if (($oTask->Get('status') != 'active')
			|| ($oTask->Get('next_run_date') > date('Y-m-d H:i:s',$iTimeLimit)))
		{
			if ($bVerbose)
			{
				$oP->p("Resetting the next run date for $sTaskClass");
			}
			$oProcess = $aProcesses[$sTaskClass];
			$oNextOcc = $oProcess->GetNextOccurrence();
			$oTask->Set('next_run_date', $oNextOcc->format('Y-m-d H:i:s'));
			$oTask->DBUpdate();
		}
	}

	$iCronSleep = MetaModel::GetConfig()->Get('cron_sleep');

	$oSearch = new DBObjectSearch('BackgroundTask');
	while (time() < $iTimeLimit)
	{
		// Verify files instead of reloading the full config each time
		if (file_exists(MAINTENANCE_MODE_FILE) || file_exists(READONLY_MODE_FILE))
		{
			$oP->p("Maintenance detected, exiting");
			exit(EXIT_CODE_ERROR);
		}

		$oTasks = new DBObjectSet($oSearch);
		$aTasks = array();
		while ($oTask = $oTasks->Fetch())
		{
			$aTasks[$oTask->Get('class_name')] = $oTask;
		}

		$oNow = new DateTime();
		ReorderProcesses($aProcesses, $aTasks, $oNow, $bVerbose, $oP);

		foreach ($aProcesses as $oProcess)
		{
			$sTaskClass = get_class($oProcess);

			// N°3219 for each process will use a specific CMDBChange object with a specific track info
			// Any BackgroundProcess can overrides this as needed
			CMDBObject::SetCurrentChange(null);
			CMDBObject::SetTrackInfo("Background task ($sTaskClass)");
			CMDBObject::SetTrackOrigin(null);

			if (!array_key_exists($sTaskClass, $aTasks))
			{
				// New entry, let's create a new BackgroundTask record, and plan the first execution
				$oTask = new BackgroundTask();
				$oTask->SetDebug($bDebug);
				$oTask->Set('class_name', get_class($oProcess));
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
				$aTasks[$oTask->Get('class_name')] = $oTask;
			}

			if (($aTasks[$sTaskClass]->Get('status') == 'active') && ($aTasks[$sTaskClass]->Get('next_run_date') <= $oNow->format('Y-m-d H:i:s')))
			{
				// Run the task and record its next run time
				if ($bVerbose)
				{
					$oP->p(">> === ".$oNow->format('Y-m-d H:i:s').sprintf(" Starting:%-'=40s", ' '.$sTaskClass.' '));
				}
				try
				{
					$sMessage = RunTask($oProcess, $aTasks[$sTaskClass], $oNow, $iTimeLimit);
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
		$oP->p("Reached normal execution time limit (exceeded by ".(time() - $iTimeLimit)."s)");
	}
}

function DisplayStatus($oP)
{
	$oSearch = new DBObjectSearch('BackgroundTask');
	$oTasks = new DBObjectSet($oSearch);
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
 * Arrange the list of processes in the best order for their execution.
 * The idea is to continue just after the last task that was run, to let a chance to every task
 * even when there are tasks taking a very long time (for example to process a big backlog)
 * Note: We first record the last_run_date at the startup of a task, then at the end
 *       so that in case of a crash, the task is still listed has having run.
 *       In case the task crashes AND the previous task was very quick (less than 1 second)
 *       both tasks will have the same last_run_date. In this case it is important NOT to start again
 *       by the task that just crashed.
 *
 * @param iProcess[] $aProcesses
 * @param BackgroundTask[] $aTasks
 * @param DateTime $oNow
 * @param $bVerbose
 * @param Page $oP
 *
 * @throws \ArchivedObjectException
 * @throws \CoreException
 */
function ReorderProcesses(&$aProcesses, $aTasks, $oNow, $bVerbose, &$oP)
{
	$aIndexes = array_keys($aProcesses);
	
	// Step 1: find which task was run last
	$idx = 0;
	$idxLastTaskExecuted = 0;
	$sMaxRunDate = '';
	if ($bVerbose)
	{
		$oP->p('Re-ordering the tasks - planned to run now -  to continue after the last task run:');
		$oP->p('+---------------------------+---------+---------------------+---------------------+');
		$oP->p('| Task Class                | Status  | Last Run            | Next Run            |');
		$oP->p('+---------------------------+---------+---------------------+---------------------+');
		
		foreach($aProcesses as $sClass => $oProcess)
		{
			$sTaskClass = get_class($oProcess);
			if (array_key_exists($sTaskClass, $aTasks))
			{
				$oTask = $aTasks[$sTaskClass];
				if (($aTasks[$sTaskClass]->Get('status') == 'active') && ($aTasks[$sTaskClass]->Get('next_run_date') <= $oNow->format('Y-m-d H:i:s')))
				{
					$sTaskName = $oTask->Get('class_name');
					$sStatus = $oTask->Get('status');
					$sLastRunDate = $oTask->Get('latest_run_date');
					$sNextRunDate = $oTask->Get('next_run_date');
					$oP->p(sprintf('| %1$-25.25s | %2$-7s | %3$-19s | %4$-19s |', $sTaskName, $sStatus, $sLastRunDate, $sNextRunDate));
				}
			}
		}
		$oP->p('+---------------------------+---------+---------------------+---------------------+');
	}
	foreach($aProcesses as $sClass => $oProcess)
	{
		$sTaskClass = get_class($oProcess);
		if (array_key_exists($sTaskClass, $aTasks))
		{
			$oTask = $aTasks[$sTaskClass];

			if (($aTasks[$sTaskClass]->Get('status') == 'active') && ($aTasks[$sTaskClass]->Get('next_run_date') <= $oNow->format('Y-m-d H:i:s')))
			{
				if (($oTask->Get('latest_run_date') != '') && strcmp($oTask->Get('latest_run_date'), $sMaxRunDate) >= 0)
				{
					// More recent or equal (!important) will run later
					$sMaxRunDate = $oTask->Get('latest_run_date');
					$idxLastTaskExecuted = $idx;
				}
			}
		}
		$idx++;
	}
	if ($bVerbose)
	{		
		$oLastRunProcess = $aProcesses[$aIndexes[$idxLastTaskExecuted]];
		
		$oP->p('Last run process: '.get_class($oProcess)." (idx=$idxLastTaskExecuted) at ".$sMaxRunDate);
	}
	
	
	
	$aReorderedProcesses = array();
	
	
	// Step 2: the first task will the one just after the last run one, then the next, and so on (circular permutation)
	$idx = 0;
	$iTotal = count($aProcesses);
	foreach($aProcesses as $oProcess)
	{
		$iActualIdx = (1 + $idxLastTaskExecuted + $idx )% $iTotal;
		$sKey = $aIndexes[$iActualIdx];
		$aReorderedProcesses[] =  $aProcesses[$sKey];
		$idx++;
	}
	
	if ($bVerbose)
	{
		$oP->p('After reordering, the execution order is:');
		$oP->p('+---------------------------+---------+---------------------+---------------------+');
		$oP->p('| Task Class                | Status  | Last Run            | Next Run            |');
		$oP->p('+---------------------------+---------+---------------------+---------------------+');
		
		foreach($aReorderedProcesses as $sClass => $oProcess)
		{
			$sTaskClass = get_class($oProcess);
			if (array_key_exists($sTaskClass, $aTasks))
			{
				$oTask = $aTasks[$sTaskClass];
				if (($aTasks[$sTaskClass]->Get('status') == 'active') && ($aTasks[$sTaskClass]->Get('next_run_date') <= $oNow->format('Y-m-d H:i:s')))
				{
					$sTaskName = $oTask->Get('class_name');
					$sStatus = $oTask->Get('status');
					$sLastRunDate = $oTask->Get('latest_run_date');
					$sNextRunDate = $oTask->Get('next_run_date');
					$oP->p(sprintf('| %1$-25.25s | %2$-7s | %3$-19s | %4$-19s |', $sTaskName, $sStatus, $sLastRunDate, $sNextRunDate));
				}
			}
		}
		$oP->p('+---------------------------+---------+---------------------+---------------------+');
	}
	
	// Update the array
	$aProcesses = $aReorderedProcesses;
}

////////////////////////////////////////////////////////////////////////////////
//
// Main
//

set_time_limit(0); // Some background actions may really take long to finish (like backup)

if (utils::IsModeCLI())
{
	$oP = new CLIPage("iTop - cron");
}
else
{
	$oP = new WebPage("iTop - cron");
}

try
{
	utils::UseParamFile();
}
catch (Exception $e)
{
	$oP->p("Error: ".$e->GetMessage());
	$oP->output();
	exit(EXIT_CODE_FATAL);
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

// Enumerate classes implementing BackgroundProcess
//
$aProcesses = array();
foreach (get_declared_classes() as $sPHPClass)
{
	$oRefClass = new ReflectionClass($sPHPClass);
	if ($oRefClass->isAbstract())
	{
		continue;
	}
	$oExtensionInstance = null;
	if ($oRefClass->implementsInterface('iProcess'))
	{
		if (is_null($oExtensionInstance))
		{
			$oExecInstance = new $sPHPClass;
		}
		$aProcesses[$sPHPClass] = $oExecInstance;
	}
}


$bVerbose = utils::ReadParam('verbose', false, true /* Allow CLI */);
$bDebug = utils::ReadParam('debug', false, true /* Allow CLI */);

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
if (utils::ReadParam('status_only', false, true /* Allow CLI */))
{
	// Display status and exit
	DisplayStatus($oP);
	exit(0);
}

require_once(APPROOT.'core/mutex.class.inc.php');
$oP->p("Starting: ".time().' ('.date('Y-m-d H:i:s').')');

try
{
	$oConfig = utils::GetConfig();
	$oMutex = new iTopMutex('cron');
	if (!MetaModel::DBHasAccess(ACCESS_ADMIN_WRITE))
	{
		$oP->p("A maintenance is ongoing");
	}
	else
	{
		if ($oMutex->TryLock())
		{
			CronExec($oP, $aProcesses, $bVerbose, $bDebug);
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
