<?php

require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

const ERROR_ALREADY_RUNNING = "error_already_running";
const RUNNING = "running";
const STOPPED = "stopped";
const ERROR = "error";

$sAuthUser = ReadParam("auth_user");
$sAuthPwd = ReadParam("auth_pwd");

try {
	$sAuthUser = ReadParam("auth_user");
	$sAuthPwd = ReadParam("auth_pwd");

	if (is_null($sAuthUser) || is_null($sAuthPwd)) {
		throw new \Exception("Missing credentials");
	}

	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd)) {
		UserRights::Login($sAuthUser); // Login & set the user's language
	} else {
		throw new \Exception("Invalid credentials");
	}

	$sLogFilename = ReadParam("cron_log_file", "cron.log");

	$sStatus = STOPPED;
	$sMsg = "";
	$sLogFile = APPROOT."log/$sLogFilename";
	if (is_file($sLogFile)) {
		$sContent = exec("tail -n 1 $sLogFile");
		if (0 === strpos($sContent, 'Exiting: ')) {
			exec("tail -n 2 $sLogFile", $aContent);
			//var_dump($aContent);
			$sContent = implode("\n", $aContent);
			if (false !== strpos($sContent, 'Already running')) {
				$sStatus = ERROR_ALREADY_RUNNING;
			} else if (preg_match('/ERROR: (.*)\\n/', $sContent, $aMatches)) {
				$sMsg = "$aMatches[1]";
				$sStatus = ERROR;
			} else {
				$sMsg = "$sContent";
				$sStatus = STOPPED;
			}
		} else {
			$sStatus = RUNNING;
		}
	} else {
		$sMsg = "missing $sLogFile";
		$sStatus = ERROR;
	}

	http_response_code(200);
	$oP = new JsonPage();
	$oP->add_header('Access-Control-Allow-Origin: *');
	$oP->SetData(["status" => $sStatus, 'message' => $sMsg]);
	$oP->SetOutputDataOnly(true);
	$oP->Output();
}
catch (Exception $e) {
	\IssueLog::Error("Cannot cron status", null, ['msg' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);
	http_response_code(500);
	$oP = new JsonPage();
	$oP->add_header('Access-Control-Allow-Origin: *');
	$oP->SetData(["message" => $e->getMessage()]);
	$oP->SetOutputDataOnly(true);
	$oP->Output();
}

function ReadParam($sParam, $sDefaultValue = null, $sSanitizationFilter = utils::ENUM_SANITIZATION_FILTER_RAW_DATA)
{
	$sValue = utils::ReadParam($sParam, null, true, $sSanitizationFilter);
	if (is_null($sValue)) {
		$sValue = utils::ReadPostedParam($sParam, $sDefaultValue, $sSanitizationFilter);
	}

	return trim($sValue);
}