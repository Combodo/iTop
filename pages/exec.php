<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

utils::InitTimeZone();


/**
 * @param string $sPagePath relative path
 * @param array $aPossibleBasePaths list of possible base paths
 *
 * @return string|bool false if invalid path
 * @uses utils::RealPath()
 */
function CheckPageExists(string $sPagePath, array $aPossibleBasePaths)
{
	$sTargetPage = false;
	foreach ($aPossibleBasePaths as $sBasePath) {
		$sTargetPage = utils::RealPath($sBasePath.'/'.$sPagePath, $sBasePath);
		if ($sTargetPage !== false) {
			return $sTargetPage;
		}
	}

	return $sTargetPage;
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


// in case module was compiled to symlink, trying multiple paths...
$sPagePath = $sModule.'/'.$sPage;
$aPossibleBasePaths = [
	APPROOT.'env-'.$sEnvironment,
	APPROOT.'datamodels/2.x',
	APPROOT.'extensions',
	APPROOT.'data/'.$sEnvironment.'-modules',
	APPROOT.'data/downloaded-extensions', // Hub connector
];
$sTargetPage = CheckPageExists($sPagePath, $aPossibleBasePaths);

if ($sTargetPage === false) {
	// Do not recall the page parameters (security takes precedence)
	echo "Wrong module, page name or environment...";
	exit;
}

/////////////////////////////////////////
//
// GO!
//
require_once($sTargetPage);
