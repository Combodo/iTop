<?php

require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

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
	$sLogFile = APPROOT."log/$sLogFilename";

	$sCliParams = ReadParam("cron_cli_parameters");

	$bAsynchronous = true;
	if (is_null($sCliParams)) {
		$sCliParams = "--help";
		$bAsynchronous = false;
	} else {
		$sCliParams = trim(base64_decode($sCliParams, true));

		if (false === strpos($sCliParams, '--auth_user=')) {
			$sCliParams = "--auth_user=$sAuthUser ".$sCliParams;
		}

		if (false === strpos($sCliParams, '--auth_pwd=')) {
			$sCliParams = "--auth_pwd=$sAuthPwd ".$sCliParams;
		}

		if (false !== strpos($sCliParams, '--status_only=1')) {
			$bAsynchronous = false;
		}
	}

	touch($sLogFile);
	$sPHPExec = trim(\MetaModel::GetConfig()->Get('php_path'));

	if ($bAsynchronous) {
		$sCli = sprintf("$sPHPExec %s/cron.php $sCliParams 2>&1 >>$sLogFile &", __DIR__);
		file_put_contents($sLogFile, $sCli);
		$process = popen($sCli, 'r');
	} else {


		$sCli = sprintf("\n $sPHPExec %s/cron.php $sCliParams", __DIR__);
		$fp = fopen($sLogFile, 'a+');
		fwrite($fp, $sCli);

		$aDescriptorSpec = [
			0 => ["pipe", "r"],  // stdin
			1 => ["pipe", "w"],  // stdout
		];
		$rProcess = proc_open($sCli, $aDescriptorSpec, $aPipes, __DIR__, null);

		$sStdOut = stream_get_contents($aPipes[1]);
		fclose($aPipes[1]);
		$iCode = proc_close($rProcess);

		fwrite($fp, $sStdOut);
		fwrite($fp, "Exiting: ".time().' ('.date('Y-m-d H:i:s').')');
		fclose($fp);
	}

	http_response_code(200);
	$oP = new JsonPage();
	$oP->add_header('Access-Control-Allow-Origin: *');
	$oP->SetData(["message" => "OK"]);
	$oP->SetOutputDataOnly(true);
	$oP->Output();
}
catch (Exception $e) {
	\IssueLog::Error("Cannot run cron", null, ['msg' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);
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