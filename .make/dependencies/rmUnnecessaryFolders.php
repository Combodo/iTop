<?php
/**
 * Copyright (C) 2010-2024 Combodo SAS
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

$iTopFolder = __DIR__ . "/../../" ;

require_once ("$iTopFolder/approot.inc.php");
require_once (APPROOT."/setup/setuputils.class.inc.php");

if  (php_sapi_name() !== 'cli')
{
	throw new \Exception('This script can only run from CLI');
}

clearstatcache();

// Read params
$key = array_search("--manager", $argv);
if (false === $key || false === isset($argv[$key + 1]) ) {
	throw new \InvalidArgumentException("Usage: " . __FILE__ . " --manager composer|npm");
}
$sDependenciesHandlerCode = $argv[$key + 1];

switch ($sDependenciesHandlerCode) {
	case "composer":
		$sDependenciesHandlerFQCN = \Combodo\iTop\Dependencies\Composer\iTopComposer::class;
		break;

	case "npm":
		$sDependenciesHandlerFQCN = \Combodo\iTop\Dependencies\NPM\iTopNPM::class;
		break;

	default:
		throw new \Exception("Invalid dependencies handler code, $sDependenciesHandlerCode given, expected composer|npm");
}

// Start handler
$oDependenciesHandler = new $sDependenciesHandlerFQCN();
$aDeniedButStillPresent = $oDependenciesHandler->ListDeniedButStillPresentFilesAbsPaths();

echo "\n";
foreach ($aDeniedButStillPresent as $sDir)
{
	if (false === $oDependenciesHandler::IsQuestionnableFile($sDir))
	{
		echo "ERROR found INVALID denied test dir: '$sDir'\n";
		throw new \RuntimeException("$sDir is in the denied list but doesn't comply with the rule (see IsQuestionnableFolder method)");
	}

	if (false === file_exists($sDir)) {
		echo "INFO $sDir is in denied list, but not existing on disk => skipping !\n";
		continue;
	}

	try {
		if(is_dir($sDir)){
			SetupUtils::rrmdir($sDir);
		}
		else{
			unlink($sDir);
		}
		echo "✔️ Remove denied test dir: '$sDir'\n";
	}
	catch (\Exception $e) {
		echo "\n❌ FAILED to remove denied test dir: '$sDir'\n";
	}
}


$aAllowedAndDeniedDirs = array_merge(
	$oDependenciesHandler->ListAllowedFilesAbsPaths(),
	$oDependenciesHandler->ListDeniedFilesAbsPaths()
);
$aExistingDirs = $oDependenciesHandler->ListAllFilesAbsPaths();
$aMissing = array_diff($aExistingDirs, $aAllowedAndDeniedDirs);
if (false === empty($aMissing)) {
	echo "Some new tests dirs exists !\n"
		."  They must be declared either in the allowed or denied list in {$sDependenciesHandlerFQCN}\n"
		.'  List of dirs:'."\n".var_export($aMissing, true)."\n";
}

// Ensure separation with following scripts
echo "\n";
