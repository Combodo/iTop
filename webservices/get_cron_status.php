<?php

require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');
require_once(__DIR__.'/CronService.php');

const RUNNING = "running";
const STOPPED = "stopped";
const ERROR = "error";

try {
	$oCtx = new ContextTag(ContextTag::TAG_CRON);
	LoginWebPage::ResetSession();
	$iRet = LoginWebPage::DoLogin(false, false, LoginWebPage::EXIT_RETURN);
	if ($iRet != LoginWebPage::EXIT_CODE_OK) {
		throw new Exception("Unknown authentication error (retCode=$iRet)", RestResult::UNAUTHORIZED);
	}

	if (!UserRights::IsAdministrator()) {
		throw new Exception("Access restricted to administrators");
	}

	$sLogFilename = ReadParam("cron_log_file", "cron.log");
	$sLogFile = APPROOT."log/$sLogFilename";
	if (! is_file($sLogFile)) {
		throw new \Exception("Cannot read log file");
	}

	$sMsg = "";
	if (CronService::GetInstance()->IsStopped($sLogFile)) {
		$sStatus = STOPPED;
	} else {
		$sErrorMsg = CronService::GetInstance()->GetErrorMessage($sLogFile);
		if (is_null($sErrorMsg)) {
			$sStatus = RUNNING;
		} else {
			$sMsg = $sErrorMsg;
			$sStatus = ERROR;
		}
		$sStatus = is_null($sMsg) ? RUNNING : ERROR;
	}

	http_response_code(200);
	$oP = new JsonPage();
	$oP->add_header('Access-Control-Allow-Origin: *');
	$oP->SetData(["status" => $sStatus, 'message' => $sMsg]);
	$oP->SetOutputDataOnly(true);
	$oP->Output();
} catch (Exception $e) {
	\IssueLog::Error("Cannot get cron status", null, ['msg' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);
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
