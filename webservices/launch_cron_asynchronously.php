<?php

use Hybridauth\Storage\Session;

require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

function GetCliCommand(string $sPHPExec, string $sLogFile, array $aCronValues): string
{
	$sCliParams = implode(" ", $aCronValues);
	return sprintf("$sPHPExec %s/cron.php $sCliParams 2>&1 >>$sLogFile &", __DIR__);
}

function IsErrorLine(string $sLine): bool
{
	if (preg_match('/^Access wrong credentials/', $sLine)) {
		return true;
	}

	if (preg_match('/^Access restricted/', $sLine)) {
		return true;
	}

	return false;

}

function IsCronStartingLine(string $sLine): bool
{
	return preg_match('/^Starting: /', $sLine);
}

$oCtx = new ContextTag(ContextTag::TAG_CRON);
\IssueLog::Enable(APPROOT.'log/error.log');

try {
	LoginWebPage::ResetSession();
	$iRet = LoginWebPage::DoLogin(false, false, LoginWebPage::EXIT_RETURN);
	if ($iRet != LoginWebPage::EXIT_CODE_OK) {
		throw new Exception("Unknown authentication error (retCode=$iRet)", RestResult::UNAUTHORIZED);
	}

	$sCurrentLoginMode = \Combodo\iTop\Application\Helper\Session::Get('login_mode', '');
	$oLoginFSMExtensionInstance = LoginWebPage::GetCurrentLoginPlugin($sCurrentLoginMode);

	if (! $oLoginFSMExtensionInstance instanceof iTokenLoginUIExtension) {
		throw new \Exception("cannot call cron asynchronously via current login mode $sCurrentLoginMode");
	}

	$aCronValues = [];
	foreach ([ 'verbose', 'debug'] as $sParam) {
		$value =  ReadParam($sParam, false);
		$aCronValues[] = "--$sParam=".escapeshellarg($value);
	}

	/** @var iTokenLoginUIExtension $oLoginFSMExtensionInstance */
	$aTokenInfo = $oLoginFSMExtensionInstance->GetTokenInfo();
	$sTokenInfo = base64_encode(json_encode($aTokenInfo));
	$aCronValues[] = "--login_mode=".escapeshellarg($sCurrentLoginMode);

	$sLogFilename = ReadParam("cron_log_file", "cron.log");
	$sLogFile = APPROOT."log/$sLogFilename";

	if (! touch($sLogFile)) {
		throw new \Exception("Cannot touch $sLogFile");
	}

	$sPHPExec = trim(\MetaModel::GetConfig()->Get('php_path'));
	$aCronValues[] = "--auth_info=".escapeshellarg('XXXX');
	$sCliForLogs = GetCliCommand($sPHPExec, $sLogFile, $aCronValues).PHP_EOL;
	\IssueLog::Info("launch cron asynchronously/remotely", null, ['cli' => $sCliForLogs]);

	$aCronValues[] = "--auth_info=".escapeshellarg($sTokenInfo);
	$sCli = GetCliCommand($sPHPExec, $sLogFile, $aCronValues);
	$process = popen($sCli, 'r');
	if (false === $process){
		throw new \Exception("CLI execution issue");
	}

	while ($aLines = utils::ReadTail($sLogFile)) {
		$sLastLine = array_shift($aLines);
		if (IsErrorLine($sLastLine) || IsCronStartingLine($sLastLine)) {
			//return answer once we are sure cron is starting or did not pass authentication
			break;
		}
		usleep(100);
	}

	http_response_code(200);
	$oP = new JsonPage();
	$oP->add_header('Access-Control-Allow-Origin: *');
	$oP->SetData(["message" => "OK"]);
	$oP->SetOutputDataOnly(true);
	$oP->Output();
} catch (Exception $e) {
	\IssueLog::Error('Cannot run cron', null, ['msg' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);

	http_response_code(500);
	$oP = new JsonPage();
	$oP->add_header('Access-Control-Allow-Origin: *');
	$oP->SetData(["message" => $e->getMessage(), 'cli' => $sCli ?? '', 'msg' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);
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
