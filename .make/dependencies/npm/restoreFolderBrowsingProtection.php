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

/**
 * Ensure that the files for folder browsing protection (.htaccess, web.config) are kept after an "npm install/update" command
 */

$iTopFolder = __DIR__."/../../../";

require_once("$iTopFolder/approot.inc.php");

$sDependenciesRootFolderAbsPath = APPROOT . "node_modules/";
$aFilesToCheck = [
	".htaccess",
	"web.config",
];

echo "This command aims at ensuring that folder browsing protection files (.htaccess, web.config) are present in the dependencies folder even after an install/upgrade command\n";
echo "Checking files:\n";

foreach($aFilesToCheck as $sFileToCheck) {
	if (file_exists($sDependenciesRootFolderAbsPath . $sFileToCheck)) {
		echo "✔️ $sFileToCheck is present\n";
		continue;
	}

	// If missing, copy the one from /lib as it contains the necessary allow/deny directives for third-parties
	copy(APPROOT . "lib/$sFileToCheck", $sDependenciesRootFolderAbsPath . $sFileToCheck);
	echo "✔️ $sFileToCheck was missing and has been re-created\n";
}

// Ensure separation with following scripts
echo "\n";