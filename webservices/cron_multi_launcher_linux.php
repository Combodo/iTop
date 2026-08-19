<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Launch all the cron processes in one minute
// This script must be launched every minute
// optimal parameters are:
// 'cron_max_execution_time' => 590,
// 'cron_sleep' => 2
// 'cron.max_processes' => 10

require_once(__DIR__.'/../approot.inc.php');

$sConfigFile = utils::GetConfigFilePath();
if (!file_exists($sConfigFile)) {
	echo "iTop is not yet installed. Exiting...\n";
	exit(EXIT_CODE_ERROR);
}

require_once(APPROOT.'/application/startup.inc.php');

function UsageAndExit()
{
	echo "USAGE:\n";
	echo "php cron_multi_launcher_linux.php --param_file=<file>\n";
	exit(EXIT_CODE_FATAL);
}

function ReadMandatoryParam($sParam, $sSanitizationFilter = 'parameter')
{
	$sValue = utils::ReadParam($sParam, null, true, $sSanitizationFilter);
	if (is_null($sValue)) {
		echo "ERROR: Missing argument '$sParam'\n";
		UsageAndExit();
	}

	return trim($sValue);
}

////////////////////////////////////////////////////////////////////////////////
//
// Main
//

try {
	utils::UseParamFile();
	$sAuthUser = ReadMandatoryParam('auth_user', 'raw_data');
	$sAuthPwd = ReadMandatoryParam('auth_pwd', 'raw_data');
	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd)) {
		UserRights::Login($sAuthUser); // Login & set the user's language
	} else {
		echo "Access wrong credentials ('$sAuthUser')\n";
		exit(EXIT_CODE_ERROR);
	}

	if (!UserRights::IsAdministrator()) {
		echo "Access restricted to administrators\n";
		exit(EXIT_CODE_ERROR);
	}

	if (!MetaModel::DBHasAccess(ACCESS_ADMIN_WRITE) || file_exists(MAINTENANCE_MODE_FILE) || file_exists(READONLY_MODE_FILE)) {
		echo "A maintenance is ongoing\n";
		exit(EXIT_CODE_ERROR);
	}

	$oConfig = MetaModel::GetConfig();
	$iMaxCronProcess = max($oConfig->Get('cron.max_processes'), 1);
	$iRespawnTime = 60 / $iMaxCronProcess;
	$sPhpPath = $oConfig->Get('php_path');
	for ($i = 0; $i < $iMaxCronProcess; $i++) {
		$sParamsFile = utils::ReadParam('param_file', '', true, 'raw_data');
		$sCronCmd = APPROOT."webservices/cron.php --param_file=$sParamsFile";
		$sOutputFile = APPROOT.'log/itop-cron.log';

		// Execute command, redirect stdout and stderr LINUX ONLY
		exec(sprintf($sPhpPath.' %s >> %s 2>&1 &', escapeshellcmd($sCronCmd), escapeshellarg($sOutputFile)));
		sleep((int)(floor($iRespawnTime)));
	}

} catch (Exception $e) {
	echo "ERROR: {$e->getMessage()}\n";
}
