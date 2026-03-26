<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Rebuild the hierarchical keys control data
 */

use Combodo\iTop\Core\MetaModel\HierarchicalKey;
use Combodo\iTop\DBTools\Enum\BinExitCode;
use Combodo\iTop\DBTools\Exception\AuthenticationException;

// env-xxx folders
if (file_exists(__DIR__.'/../../../approot.inc.php')) {
	require_once __DIR__.'/../../../approot.inc.php';
}
// datamodel/2.x and data/xxx-modules folders
elseif (file_exists(__DIR__.'/../../../../approot.inc.php')) {
	require_once __DIR__.'/../../../../approot.inc.php';
}
require_once APPROOT.'application/startup.inc.php';

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
			throw new AuthenticationException("Access credentials not provided, usage: php rebuildhk.php --auth_user=<login> --auth_pwd=<password> [--param_file=<file_path>]");
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
	$oP->p($oException->getMessage());
	$oP->output();
	exit(BinExitCode::ERROR->value);
} catch (Exception $oException) {
	$oP->p("Error: ".$oException->GetMessage());
	$oP->output();
	exit(BinExitCode::FATAL->value);
}

// Business logic
try {
	foreach (MetaModel::GetClasses() as $sClass) {
		if (!MetaModel::HasTable($sClass)) {
			continue;
		}

		foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef) {
			// Check (once) all the attributes that are hierarchical keys
			if ((MetaModel::GetAttributeOrigin($sClass, $sAttCode) == $sClass) && $oAttDef->IsHierarchicalKey()) {
				$oP->p("Rebuild hierarchical key $sAttCode from $sClass.");
				HierarchicalKey::Rebuild($sClass, $sAttCode, $oAttDef);
			}
		}
	}

	$oP->p("Done");
	$oP->output();
} catch (AuthenticationException $oException) {
	$oP->p($oException->getMessage());
	$oP->output();
	exit(BinExitCode::ERROR->value);
} catch (Exception $oException) {
	$oP->p("Error: ".$oException->GetMessage());
	$oP->output();
	exit(BinExitCode::FATAL->value);
}
