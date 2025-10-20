<?php

require_once(__DIR__.'/../approot.inc.php');

const RUNNING = "running";
const STOPPED = "stopped";
const ERROR = "error";

$sLogFilename = utils::ReadParam("cron_log_file", null, true, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
if (is_null($sLogFilename)) {
	$sLogFilename = utils::ReadPostedParam("cron_log_file", null, utils::ENUM_SANITIZATION_FILTER_RAW_DATA) ?? "cron.log";
}
$sLogFile = APPROOT . "log/$sLogFilename";
if (is_file($sLogFile)){
	$sContent = exec("tail -n 1 $sLogFile");
	if (0 === strpos($sContent, 'Exiting: ')){
		exec("tail -n 2 $sLogFile", $aContent);
		//var_dump($aContent);
		$sContent = implode("\n", $aContent);
		if (false !== strpos($sContent, 'Already running')){
			echo ERROR . " (already running)";
			exit;
		}

		if (preg_match('/ERROR: (.*)\\n/', $sContent, $aMatches)){
			echo ERROR . " ($aMatches[1])";
			exit;
		}

		echo "$sContent\n";
		echo STOPPED;
		exit;
	}

	echo RUNNING;
	return;
}

echo ERROR . "(missing $sLogFile)";
