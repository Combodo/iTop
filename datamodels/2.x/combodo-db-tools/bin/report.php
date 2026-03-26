<?php

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\DBTools\Enum\BinExitCode;
use Combodo\iTop\DBTools\Exception\AuthenticationException;
use Combodo\iTop\DBTools\Service\DBAnalyzerUtils;

// env-xxx folders
if (file_exists(__DIR__.'/../../../approot.inc.php')) {
	require_once __DIR__.'/../../../approot.inc.php';
}
// datamodel/2.x and data/xxx-modules folders
elseif (file_exists(__DIR__.'/../../../../approot.inc.php')) {
	require_once __DIR__.'/../../../../approot.inc.php';
}

require_once APPROOT.'application/startup.inc.php';
require_once APPROOT.'application/loginwebpage.class.inc.php';

require_once __DIR__.'/../db_analyzer.class.inc.php';

// Prepare output page
$sPageTitle = "Database maintenance tools - Report";
$bIsModeCLI = utils::IsModeCLI();
if ($bIsModeCLI) {
	$oP = new CLIPage($sPageTitle);

	SetupUtils::CheckPhpAndExtensionsForCli($oP, BinExitCode::FATAL->value);
} else {
	$oP = new WebPage($sPageTitle);
}

// Authentication logic
try {
	utils::UseParamFile();

	if ($bIsModeCLI) {
		$sAuthUser = utils::ReadParam('auth_user', null, true, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		$sAuthPwd = utils::ReadParam('auth_pwd', null, true, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
		if (utils::IsNullOrEmptyString($sAuthUser) || utils::IsNullOrEmptyString($sAuthPwd)) {
			throw new AuthenticationException("Access credentials not provided, usage: php report.php --auth_user=<login> --auth_pwd=<password> [--param_file=<file_path>]");
		}
		if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd)) {
			UserRights::Login($sAuthUser);
		} else {
			throw new AuthenticationException("Access wrong credentials ('$sAuthUser')");
		}
	} else {
		// Check user rights and prompt if needed
		LoginWebPage::DoLoginEx(null, true);
	}

	if (!UserRights::IsAdministrator()) {
		throw new AuthenticationException("Access restricted to administrators");
	}
} catch (AuthenticationException $oException) {
	$sExceptionMessage = $oP instanceof WebPage ? utils::EscapeHtml($oException->getMessage()) : $oException->getMessage();
	$oP->p($sExceptionMessage);
	$oP->output();
	exit(BinExitCode::ERROR->value);
} catch (Exception $oException) {
	$sExceptionMessage = $oP instanceof WebPage ? utils::EscapeHtml($oException->getMessage()) : $oException->getMessage();
	$oP->p("Error: ".$sExceptionMessage);
	$oP->output();
	exit(BinExitCode::FATAL->value);
}

// Business logic
try {
	$oDBAnalyzer = new DatabaseAnalyzer(0);
	$aResults = $oDBAnalyzer->CheckIntegrity([]);

	if (empty($aResults)) {
		$oP->p("Database OK");
		$oP->output();
		exit(BinExitCode::SUCCESS->value);
	}

	$sReportFile = DBAnalyzerUtils::GenerateReport($aResults);

	$oP->p("Report generated: {$sReportFile}.log");
	$oP->output();
} catch (AuthenticationException $oException) {
	$sExceptionMessage = $oP instanceof WebPage ? utils::EscapeHtml($oException->getMessage()) : $oException->getMessage();
	$oP->p($sExceptionMessage);
	$oP->output();
	exit(BinExitCode::ERROR->value);
} catch (Exception $oException) {
	$sExceptionMessage = $oP instanceof WebPage ? utils::EscapeHtml($oException->getMessage()) : $oException->getMessage();
	$oP->p("Error: ".$sExceptionMessage);
	$oP->output();
	exit(BinExitCode::FATAL->value);
}
