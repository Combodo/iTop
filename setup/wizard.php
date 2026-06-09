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

/**
 * This is not the index.php page as we need to check for any PHP parse error first !
 *
 * @see N°3253
 */

use Combodo\iTop\Application\Helper\Session;

$bBypassMaintenance = true; // Reset maintenance mode in case of problem
require_once('../approot.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/core/config.class.inc.php');
require_once(APPROOT.'/setup/setuppage.class.inc.php');
require_once(APPROOT.'/setup/wizardsteps_autoload.php');

Session::Start();
clearstatcache(); // Make sure we know what we are doing !
// Set a long (at least 4 minutes) execution time for the setup to avoid timeouts during this phase
ini_set('max_execution_time', max(240, ini_get('max_execution_time')));
// While running the setup it is desirable to see any error that may happen
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
date_default_timezone_set('Europe/Paris'); // Just to avoid a warning if the timezone is not set in php.ini

/////////////////////////////////////////////////////////////////////
// Fake functions to protect the first run of the installer
// in case the PHP JSON module is not installed...
if (!function_exists('json_encode')) {
	function json_encode($value, $options = null)
	{
		return '[]';
	}
}
if (!function_exists('json_decode')) {
	function json_decode($json, $assoc = null)
	{
		return [];
	}
}
/////////////////////////////////////////////////////////////////////
//N°3671 setup context: force $bForceTrustProxy to be persisted in next calls
utils::GetAbsoluteUrlAppRoot(true);
$oWizard = new WizardController('WizStepWelcome');
//N°3952
if (SetupUtils::IsSessionSetupTokenValid()) {
	// Normal operation
	$oWizard->Run();
} else {
	SetupUtils::ExitMaintenanceMode(false);

	$sConfigFile = utils::GetConfigFilePath(ITOP_DEFAULT_ENV);
	if (file_exists($sConfigFile)) {
		// The configuration file already exists
		if (!is_writable($sConfigFile)) {
			SetupUtils::ExitReadOnlyMode(false); // Reset readonly mode in case of problem
			SetupUtils::EraseSetupToken();
			$sRelativePath = utils::GetConfigFilePathRelative(ITOP_DEFAULT_ENV);
			$oP = new SetupPage('Installation Cannot Continue');
			$oP->add("<h2>Fatal error</h2>\n");
			$oP->error("<b>Error:</b> the configuration file '".$sRelativePath."' already exists and cannot be overwritten.");
			$oP->p("The wizard cannot modify the configuration file for you. If you want to upgrade ".ITOP_APPLICATION.", make sure that the file '<b>".$sRelativePath."</b>' can be modified by the web server.");

			$sButtonsHtml = <<<HTML
<button type="button" class="ibo-button ibo-is-regular ibo-is-primary" onclick="window.location.reload()">Reload</button>
HTML;
			$oP->p($sButtonsHtml);

			$oP->output();
			// Prevent token creation
			exit;
		} else {
			chmod($sConfigFile, 0440);
		}
	}

	SetupUtils::CreateSetupToken();

	// Start the setup
	$oWizard->Start();
}
