<?php

use Hybridauth\Storage\Session;

require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

try {
	$oCtx = new ContextTag(ContextTag::TAG_CRON);
	LoginWebPage::ResetSession(true);
	$iRet = LoginWebPage::DoLogin(false, false, LoginWebPage::EXIT_RETURN);
	if ($iRet != LoginWebPage::EXIT_CODE_OK){
		throw new Exception("Unknown authentication error (retCode=$iRet)", RestResult::UNAUTHORIZED);
	}

	$sCurrentLoginMode = \Combodo\iTop\Application\Helper\Session::Get('login_mode', '');
	$oLoginFSMExtensionInstance = LoginWebPage::GetCurrentLoginPlugin($sCurrentLoginMode);

	if (! $oLoginFSMExtensionInstance instanceof iTokenLoginUIExtension){
		throw new \Exception("cannot call cron asynchronously via current login mode $sCurrentLoginMode");
	}

	$aCronValues = [];
	foreach ([ 'status_only', 'verbose', 'debug'] as $sParam){
		$value =  ReadParam($sParam, false);
		$aCronValues[] = "--$sParam=".escapeshellarg($value);
	}

	/** @var iTokenLoginUIExtension $oLoginFSMExtensionInstance */
	$aTokenInfo = $oLoginFSMExtensionInstance->GetTokenInfo();
	$sTokenInfo = base64_encode(json_encode($aTokenInfo));
	$aCronValues[] = "--auth_info=".escapeshellarg($sTokenInfo);
	$aCronValues[] = "--login_mode=".escapeshellarg($sCurrentLoginMode);

	$sCliParams=implode(" ", $aCronValues);

	$sLogFilename = ReadParam("cron_log_file", "cron.log");
	$sLogFile = APPROOT."log/$sLogFilename";

	touch($sLogFile);
	$sPHPExec = trim(\MetaModel::GetConfig()->Get('php_path'));

	if ($aCronValues['status_only']) {
		//still synchronous
		$sCli = sprintf("$sPHPExec %s/cron.php $sCliParams 2>&1 >>$sLogFile &", __DIR__);
		file_put_contents($sLogFile, $sCli);
		$process = popen($sCli, 'r');
	} else {
		//asynchronous
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