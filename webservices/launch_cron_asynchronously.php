<?php

require_once(__DIR__.'/../approot.inc.php');

$sLogFilename = utils::ReadParam("cron_log_file", null, true, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
if (is_null($sLogFilename)) {
	$sLogFilename = utils::ReadPostedParam("cron_log_file", null, utils::ENUM_SANITIZATION_FILTER_RAW_DATA) ?? "cron.log";
}
$sLogFile = APPROOT . "log/$sLogFilename";

$sCliParams = utils::ReadParam("cron_cli_parameters", null, true, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
if (is_null($sCliParams)) {
	$sCliParams = utils::ReadPostedParam("cron_cli_parameters", null, utils::ENUM_SANITIZATION_FILTER_RAW_DATA) ?? "--help";
}

if (is_null($sCliParams)){
	$sCliParams = "--help";
} else {
	$sCliParams = base64_decode($sCliParams, true);
}

$sCli = sprintf("php %s/cron.php $sCliParams 2>&1 >>$sLogFile &", __DIR__);
exec("echo $sCli>>$sLogFile");
$process=popen($sCli, 'r');
