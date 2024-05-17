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
 * Checks PHP version
 *
 * This is a hard-coded check that limits errors : we are stopping for anything < PHP 7.0.0
 * The "real one" will be done in {@link \SetupUtils::CheckPhpVersion()}
 *
 * Note that since Composer 2 there is a platform_check that make this useless, but keeping it anyway to be extra safe !
 *
 * @see https://github.com/composer/composer/blob/master/doc/07-runtime.md#platform-check Composer's platform check
 *
 * @since 3.0.0 N°2214
 */
$bIsValidPhpVersion = false;
if (PHP_MAJOR_VERSION >= 7) {
	$bIsValidPhpVersion = true;
} else {
	echo 'Your PHP version ('.PHP_VERSION.') isn\'t supported.';
	exit(-1);
}


define('ITOP_DEFAULT_ENV', 'production');
define('MAINTENANCE_MODE_FILE', APPROOT.'data/.maintenance');
define('READONLY_MODE_FILE', APPROOT.'data/.readonly');

$fItopStarted = microtime(true);
$iItopInitialMemory = memory_get_usage(true);

if (!isset($GLOBALS['bBypassAutoload']) || $GLOBALS['bBypassAutoload'] == false) {
	require_once APPROOT.'/lib/autoload.php';
}

//
// Maintenance mode
//

// Use 'maintenance' parameter to bypass maintenance mode
if (!isset($bBypassMaintenance)) {
	$bBypassMaintenance = isset($_REQUEST['maintenance']) ? boolval($_REQUEST['maintenance']) : false;
}

if (file_exists(MAINTENANCE_MODE_FILE) && !$bBypassMaintenance)
{
	$sTitle = 'Maintenance';
	$sMessage = 'This application is currently under maintenance.';

	http_response_code(503);
	// Display message depending on the request
	include(APPROOT.'application/maintenancemsg.php');
	$sSAPIName = strtoupper(trim(PHP_SAPI));

	switch (true)
	{
		case isset($_SERVER['REQUEST_URI']) && EndsWith($_SERVER['REQUEST_URI'], '/pages/ajax.searchform.php'):
			_MaintenanceHtmlMessage($sMessage);
			break;

		case $sSAPIName == 'CLI':
		case array_key_exists('HTTP_X_COMBODO_AJAX', $_SERVER):
		case isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], '/webservices/soapserver.php') !== false):
		case isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], '/webservices/export-v2.php') !== false):
			_MaintenanceTextMessage($sMessage);
			break;

		case isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'], '/webservices/rest.php') !== false):
		case isset($_SERVER['CONTENT_TYPE']) && ($_SERVER['CONTENT_TYPE'] == 'application/json'):
			_MaintenanceJsonMessage($sTitle, $sMessage);
			break;

		default:
			_MaintenanceSetupPageMessage($sTitle, $sMessage);
			break;
	}
	exit();
}

/**
 * helper to test if a string ends with another
 * @param $haystack
 * @param $needle
 *
 * @return bool
 */
function EndsWith($haystack, $needle) {
	return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}
