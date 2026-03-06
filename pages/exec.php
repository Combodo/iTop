<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

use Combodo\iTop\Application\Helper\Session;

require_once('../approot.inc.php');

// Needed to read the parameters (with sanitization)
require_once(APPROOT.'application/utils.inc.php');
require_once(APPROOT.'core/metamodel.class.php');
IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);

utils::InitTimeZone();

/**
 * @param string $sPagePath full path (if symlink, it will be resolved)
 * @param array $aPossibleBasePaths list of possible base paths
 *
 * @return string|bool false if invalid path
 * @uses utils::RealPath()
 */
function CheckPageExists(string $sPagePath, array $aPossibleBasePaths)
{
	foreach ($aPossibleBasePaths as $sBasePath) {
		$sTargetPage = utils::RealPath($sPagePath, $sBasePath);
		if (
			($sTargetPage !== false)
			&& (strtolower(pathinfo($sTargetPage, PATHINFO_EXTENSION)) === "php")
		) {
			return $sTargetPage;
		}
	}

	return false;
}

$sModule = utils::ReadParam('exec_module', '');
if ($sModule == '') {
	echo "Missing argument 'exec_module'";
	exit;
}

$sPage = utils::ReadParam('exec_page', '', false, 'raw_data');
if ($sPage == '') {
	echo "Missing argument 'exec_page'";
	exit;
}

$oKPI = new ExecutionKPI();
Session::Start();
$sEnvironment = utils::ReadParam('exec_env', utils::GetCurrentEnvironment());
Session::WriteClose();
$oKPI->ComputeAndReport("Session Start");

$sEnvFullPath = APPROOT.'env-'.$sEnvironment;
$sPageRelativePath = $sModule.'/'.$sPage;
$sPageEnvFullPath = $sEnvFullPath.'/'.$sPageRelativePath;
if (is_link($sPageEnvFullPath)) {
	$oConfig = utils::GetConfig();
	$sSourceDir = $oConfig->Get('source_dir'); // generated at compile time, works for legacy build with datamodels/1.x
	// in case module was compiled to symlink, we need to check against real linked path as symlink is resolved
	$aPossibleBasePaths = [
		APPROOT.$sSourceDir,
		APPROOT.'extensions',
		APPROOT.'data/'.$sEnvironment.'-modules',
		APPROOT.'data/downloaded-extensions', // Hub connector
	];
} else {
	$aPossibleBasePaths = [$sEnvFullPath];
}
$sTargetPage = CheckPageExists($sPageEnvFullPath, $aPossibleBasePaths);

if ($sTargetPage === false) {
	// Do not recall the page parameters (security takes precedence)
	echo "Wrong module, page name or environment...";
	exit;
}

/////////////////////////////////////////
//
// GO!
//
// check module white list
// check conf param
// force login if needed
require_once(APPROOT.'/application/startup.inc.php');

$aModuleDelegatedAuthenticationEndpoints = GetModuleDelegatedAuthenticationEndpoints($sModule);
if (is_null($aModuleDelegatedAuthenticationEndpoints) || !in_array($sPage, $aModuleDelegatedAuthenticationEndpoints)) {
	$bForceLoginWhenNoDelegatedAuthenticationEndpoints = MetaModel::GetConfig()->Get('security.force_login_when_no_delegated_authentication_endpoints_list');
	if ($bForceLoginWhenNoDelegatedAuthenticationEndpoints) {
		LoginWebPage::DoLoginEx();
	}
}
if (is_null($aModuleDelegatedAuthenticationEndpoints) && !MetaModel::GetConfig()->Get('security.force_login_when_no_delegated_authentication_endpoints_list')) {
	// check if user is not logged in, if not log a warning in the log file as the page is executed without login, which is not recommended for security reason
	if (is_null(UserRights::GetUserId())) {
		IssueLog::Warning("The page '$sPage' is called be executed without login. In the future, this call will be blocked, and will likely cause unwanted behavior in the module '$sModule'.
		Please define a delegated authentication endpoints for the module as described in https://www.itophub.io/wiki/page?id=latest:customization:new_extension#security.");
	}
}
if (is_array($aModuleDelegatedAuthenticationEndpoints) && !in_array($sPage, $aModuleDelegatedAuthenticationEndpoints)) {
	// if module defined a delegated authentication endpoints but not for the current page, we consider that the page is not allowed to be executed without login
	LoginWebPage::DoLoginEx();
}

require_once($sTargetPage);

function GetModuleDelegatedAuthenticationEndpoints(string $sModuleName): ?array
{
	$sModuleFile =  utils::GetAbsoluteModulePath($sModuleName).'/module.'.$sModuleName.'.php';

	$oExtensionMap = new iTopExtensionsMap();
	$aModuleParam = $oExtensionMap->GetModuleInfo($sModuleFile)[2];
	return $aModuleParam['delegated_authentication_endpoints'] ?? null;
}
