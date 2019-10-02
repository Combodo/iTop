<?php

define('APPROOT', dirname(__FILE__).'/');
define('APPCONF', APPROOT.'conf/');
define('ITOP_DEFAULT_ENV', 'production');
define('MAINTENANCE_MODE_FILE', APPROOT.'data/.maintenance');

if (function_exists('microtime'))
{
	$fItopStarted = microtime(true); 
}
else
{
	$fItopStarted = 1000 * time();
}

//
// Maintenance mode
//

// Use 'maintenance' parameter to bypass maintenance mode
if (!isset($bBypassMaintenance))
{
	$bBypassMaintenance = isset($_REQUEST['maintenance']) ? boolval($_REQUEST['maintenance']) : false;
}

if (file_exists(MAINTENANCE_MODE_FILE) && !$bBypassMaintenance)
{
	$sMessage = 'Application is currently in maintenance';
	$sTitle = 'Maintenance';

	http_response_code(503);
	// Display message depending on the request
	include(APPROOT.'application/maintenancemsg.php');

	switch (true)
	{
		case isset($_SERVER['REQUEST_URI']) && EndsWith($_SERVER['REQUEST_URI'], '/pages/ajax.searchform.php'):
			_MaintenanceHtmlMessage($sMessage);
			break;

		case array_key_exists('HTTP_X_COMBODO_AJAX', $_SERVER):
		case isset($_SERVER['REQUEST_URI']) && EndsWith($_SERVER['REQUEST_URI'], '/webservices/soapserver.php'):
		case isset($_SERVER['REQUEST_URI']) && EndsWith($_SERVER['REQUEST_URI'], '/webservices/rest.php'):
			_MaintenanceTextMessage($sMessage);
			break;

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
