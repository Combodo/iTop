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

/**
 * This page is called to perform "asynchronously" the setup actions
 * parameters
 * 'operation': one of 'compile_data_model', 'update_db_schema', 'after_db_creation', 'file'
 * 
 * if 'operation' == 'update_db_schema': 
 * 'mode': install | upgrade
 * 
 *  if 'operation' == 'after_db_creation':
 * 'mode': install | upgrade
 * 
 * if 'operation' == 'file': 
 * 'file': string Name of the file to load
 * 'session_status': string 'start', 'continue' or 'end'
 * 'percent': integer 0..100 the percentage of completion once the file has been loaded 
 */

use Combodo\iTop\Application\WebPage\AjaxPage;

$bBypassMaintenance = true; // Reset maintenance mode in case of problem
define('SAFE_MINIMUM_MEMORY', 64*1024*1024);
require_once('../approot.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/setup/setuppage.class.inc.php');
require_once(APPROOT.'/setup/moduleinstaller.class.inc.php');

ini_set('max_execution_time', max(3600, ini_get('max_execution_time'))); // Under Windows SQL/backup operations are part of the timeout and require extra time
date_default_timezone_set('Europe/Paris'); // Just to avoid a warning if the timezone is not set in php.ini

$sMemoryLimit = trim(ini_get('memory_limit'));
if (empty($sMemoryLimit))
{
	// On some PHP installations, memory_limit does not exist as a PHP setting!
	// (encountered on a 5.2.0 under Windows)
	// In that case, ini_set will not work, let's keep track of this and proceed with the data load
	SetupLog::Info("No memory limit has been defined in this instance of PHP");
}
else
{
	// Check that the limit will allow us to load the data
	//
	$iMemoryLimit = utils::ConvertToBytes($sMemoryLimit);
	if (!utils::IsMemoryLimitOk($iMemoryLimit, SAFE_MINIMUM_MEMORY))
	{
		if (ini_set('memory_limit', SAFE_MINIMUM_MEMORY) === FALSE)
		{
			SetupLog::Error("memory_limit is too small: $iMemoryLimit and can not be increased by the script itself.");
		}
		else
		{
			SetupLog::Info("memory_limit increased from $iMemoryLimit to ".SAFE_MINIMUM_MEMORY.".");
		}
	}
}


define('PHP_FATAL_ERROR_TAG', 'phpfatalerror');


/**
 * Handler for register_shutdown_function, to catch PHP errors
 */
function ShutdownCallback()
{
	$error = error_get_last();
	$bIsErrorToReport = (($error !== null) && ($error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR)));
	if (!$bIsErrorToReport)
	{
		return;
	}

	$errno = $error["type"];
	$errfile = $error["file"];
	$errline = $error["line"];
	$errstr = $error["message"];
	$sLogMessage = "PHP error occured : msg=$errstr, no=$errno, file=$errfile, line=$errline";
	SetupLog::Error("Setup error: $sLogMessage");
	echo '<'.PHP_FATAL_ERROR_TAG.'>'.$sLogMessage.'</'.PHP_FATAL_ERROR_TAG.'>';
}


function FatalErrorCatcher($sOutput)
{
	if (preg_match('|<'.PHP_FATAL_ERROR_TAG.'>.*</'.PHP_FATAL_ERROR_TAG.'>|s', $sOutput, $aMatches))
	{
		header("HTTP/1.0 500 Internal server error.");
		$errors = '';
		foreach ($aMatches as $sMatch)
		{
			$errors .= strip_tags($sMatch)."\n";
		}
		$sOutput = "$errors\n";
		// Logging to a file does not work if the whole memory is exhausted...		
		// SetupLog::Error("Fatal error - in $__FILE__ , $errors");
	}
	return $sOutput;
}

//Define some bogus, invalid HTML tags that no sane
//person would ever put in an actual document and tell
//PHP to delimit fatal error warnings with them.
ini_set('error_prepend_string', '<'.PHP_FATAL_ERROR_TAG.'>');
ini_set('error_append_string', '</'.PHP_FATAL_ERROR_TAG.'>');

// callback on errors to log
register_shutdown_function('ShutdownCallback');
// Starts the capture of the ouput, and sets a filter to capture the fatal errors.
ob_start('FatalErrorCatcher'); // Start capturing the output, and pass it through the fatal error catcher

require_once(APPROOT.'/core/config.class.inc.php');
require_once(APPROOT.'/core/log.class.inc.php');
require_once(APPROOT.'/core/kpi.class.inc.php');
require_once(APPROOT.'/core/cmdbsource.class.inc.php');
require_once('./xmldataloader.class.inc.php');


// Never cache this page
header("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header("Expires: Fri, 17 Jul 1970 05:00:00 GMT");    // Date in the past

/**
 * Main program
 */
$sOperation = Utils::ReadParam('operation', '');
try
{
	SetupUtils::CheckSetupToken();

	switch($sOperation)
	{
		case 'async_action':
		ini_set('max_execution_time', max(240, ini_get('max_execution_time')));
		// While running the setup it is desirable to see any error that may happen
		ini_set('display_errors', true);
		ini_set('display_startup_errors', true);
		
		require_once(APPROOT.'/setup/wizardcontroller.class.inc.php');
		require_once(APPROOT.'/setup/wizardsteps.class.inc.php');
		
		$sClass = utils::ReadParam('step_class', '');
		$sState = utils::ReadParam('step_state', '');
		$sActionCode = utils::ReadParam('code', '');
		$aParams = utils::ReadParam('params', array(), false, 'raw_data');
		$oPage = new AjaxPage('');
		$oDummyController = new WizardController('');
		if (is_subclass_of($sClass, 'WizardStep'))
		{
			/** @var WizardStep $oStep */
			$oStep = new $sClass($oDummyController, $sState);
			$sConfigFile = utils::GetConfigFilePath();
			if (file_exists($sConfigFile) && !is_writable($sConfigFile) && $oStep->RequiresWritableConfig()) {
				$sRelativePath = utils::GetConfigFilePathRelative();
				$oPage->error("<b>Error:</b> the configuration file '".$sRelativePath."' already exists and cannot be overwritten.");
				$oPage->p("The wizard cannot modify the configuration file for you. If you want to upgrade ".ITOP_APPLICATION.", make sure that the file '<b>".$sRelativePath."</b>' can be modified by the web server.");
				$oPage->output();
			} else {
				$oStep->AsyncAction($oPage, $sActionCode, $aParams);
			}
		}
			$oPage->output();
			break;

		case 'toggle_use_symbolic_links':
			$sUseSymbolicLinks = Utils::ReadParam('bUseSymbolicLinks', false);
			$bUseSymbolicLinks = ($sUseSymbolicLinks === 'true');
			MFCompiler::SetUseSymbolicLinksFlag($bUseSymbolicLinks);
			echo "toggle useSymbolicLinks flag : $bUseSymbolicLinks";
			break;

		default:
			throw(new Exception("Error unsupported operation '$sOperation'"));
	}
}
catch(Exception $e)
{
	header("HTTP/1.0 500 Internal server error.");
	echo "<p>An error happened while processing the installation:</p>\n";
	echo '<p>'.$e."</p>\n";
	SetupLog::Error("An error happened while processing the installation: ".$e);
}

if (function_exists('memory_get_peak_usage'))
{
	if ($sOperation == 'file')
	{
		SetupLog::Info("loading file '$sFileName', peak memory usage. ".memory_get_peak_usage());
	}
	else
	{
		SetupLog::Info("operation '$sOperation', peak memory usage. ".memory_get_peak_usage());
	}
}