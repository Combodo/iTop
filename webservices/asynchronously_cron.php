<?php

use Hybridauth\Storage\Session;

require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
require_once(APPROOT.'/application/startup.inc.php');
require_once(__DIR__.'/CronService.php');

function GetCliCommand(string $sPHPExec, string $sLogFile, array $aCronValues): string
{
	$sCliParams = implode(" ", $aCronValues);
	return sprintf("$sPHPExec %s/cron.php $sCliParams 2>&1 >>$sLogFile &", __DIR__);
}

function ReadParam($sParam, $sDefaultValue = null, $sSanitizationFilter = utils::ENUM_SANITIZATION_FILTER_RAW_DATA)
{
	$sValue = utils::ReadParam($sParam, null, true, $sSanitizationFilter);
	if (is_null($sValue)) {
		$sValue = utils::ReadPostedParam($sParam, $sDefaultValue, $sSanitizationFilter);
	}

	return trim($sValue);
}

function LoginAndGetTokenInfo(): array
{
	//handle authentication
	LoginWebPage::ResetSession();
	$iRet = LoginWebPage::DoLogin(false, false, LoginWebPage::EXIT_RETURN);
	if ($iRet != LoginWebPage::EXIT_CODE_OK) {
		throw new \Exception("Unknown authentication error (retCode=$iRet)", RestResult::UNAUTHORIZED);
	}

	$sCurrentLoginMode = \Combodo\iTop\Application\Helper\Session::Get('login_mode', '');
	$oLoginFSMExtensionInstance = LoginWebPage::GetCurrentLoginPlugin($sCurrentLoginMode);

	if (! $oLoginFSMExtensionInstance instanceof iTokenLoginUIExtension) {
		throw new \Exception("cannot call cron asynchronously via current login mode $sCurrentLoginMode");
	}

	/** @var iTokenLoginUIExtension $oLoginFSMExtensionInstance */
	$aTokenInfo = $oLoginFSMExtensionInstance->GetTokenInfo();
	return [$sCurrentLoginMode, base64_encode(json_encode($aTokenInfo))];
}

$sCliForLogs = null;
try {
	$oCtx = new ContextTag(ContextTag::TAG_CRON);

	list($sCurrentLoginMode, $sTokenInfo) = LoginAndGetTokenInfo();

	$sLogFilename = ReadParam("cron_log_file", "cron.log");
	$sLogFile = APPROOT."log/$sLogFilename";
	if (! touch($sLogFile)) {
		throw new \Exception("Cannot touch $sLogFile");
	}
	\IssueLog::Enable($sLogFile);

	$aCronValues = [];
	foreach ([ 'verbose', 'debug'] as $sParam) {
		$value =  ReadParam($sParam, false);
		$aCronValues[] = "--$sParam=".escapeshellarg($value);
	}

	$aCronValues[] = "--login_mode=".escapeshellarg($sCurrentLoginMode);

	$sPHPExec = trim(\MetaModel::GetConfig()->Get('php_path'));
	$aCronValues[] = "--auth_info=".escapeshellarg('XXXX');
	$sCliForLogs = GetCliCommand($sPHPExec, $sLogFile, $aCronValues).PHP_EOL;
	\IssueLog::Info("Launch cron asynchronously", null, ['cli' => $sCliForLogs]);

	$aCronValues[] = "--auth_info=".escapeshellarg($sTokenInfo);
	$sCli = GetCliCommand($sPHPExec, $sLogFile, $aCronValues);

	$process = popen($sCli, 'r');
	if (false === $process) {
		throw new \Exception("CLI execution issue");
	}

	$i = 0;
	$bCronStartedOrFailed = true;
	while ($i < 20) {
		usleep(100000);

		if (CronService::GetInstance()->IsStarted($sLogFile) || CronService::GetInstance()->IsFailed($sLogFile)) {
			//make sure cron is really started or failed before answering by HTTP
			$bCronStartedOrFailed = false;
			break;
		}

		$i++;
	}

	if ($bCronStartedOrFailed) {
		throw new \Exception("Cron execution timeout");
	}

	http_response_code(200);
	$oP = new JsonPage();
	$oP->add_header('Access-Control-Allow-Origin: *');
	$oP->SetData(["message" => "OK"]);
	$oP->SetOutputDataOnly(true);
	$oP->Output();
} catch (\Exception $e) {
	\IssueLog::Error('Cannot run cron', null, ['msg' => $e->getMessage(), 'stack' => $e->getTraceAsString(), 'cli' => $sCliForLogs ?? '', ]);

	http_response_code(500);
	$oP = new JsonPage();
	$oP->add_header('Access-Control-Allow-Origin: *');
	$oP->SetData(['msg' => $e->getMessage()]);
	$oP->SetOutputDataOnly(true);
	$oP->Output();
}
