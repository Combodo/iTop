<?php
// Copyright (C) 2010-2016 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
require_once(APPROOT.'/core/cmdbobject.class.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/core/contexttag.class.inc.php');


/**
 * File to include to initialize the datamodel in memory
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// This storage is freed on error (case of allowed memory exhausted)
$sReservedMemory = str_repeat('*', 1024 * 1024);
register_shutdown_function(function()
{
	global $sReservedMemory;
	$sReservedMemory = null;
	if (!is_null($err = error_get_last()) && ($err['type'] == E_ERROR))
	{
		IssueLog::error($err['message']);
		if (strpos($err['message'], 'Allowed memory size of') !== false)
		{
			$sLimit = ini_get('memory_limit');
			echo "<p>iTop: Allowed memory size of $sLimit exhausted, contact your administrator to increase 'memory_limit' in php.ini</p>\n";
		}
		elseif (strpos($err['message'], 'Maximum execution time') !== false)
		{
			$sLimit = ini_get('max_execution_time');
			echo "<p>iTop: Maximum execution time of $sLimit exceeded, contact your administrator to increase 'max_execution_time' in php.ini</p>\n";
		}
		else
		{
			echo "<p>iTop: An error occurred, check server error log for more information.</p>\n";
		}
	}
});

// Use 'maintenance' parameter to bypass maintenance mode
$bBypassMaintenance = !is_null(Utils::ReadParam('maintenance', null));

// Maintenance mode
if (file_exists(APPROOT.'.maintenance') && !$bBypassMaintenance)
{
	$sMessage = 'Application is currently in maintenance';
	$sTitle = 'Maintenance';

	http_response_code(503);
	// Display message depending on the request
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

session_name('itop-'.md5(APPROOT));
session_start();
$sSwitchEnv = utils::ReadParam('switch_env', null);
$bAllowCache = true;
if (($sSwitchEnv != null) && (file_exists(APPCONF.$sSwitchEnv.'/'.ITOP_CONFIG_FILE)) && isset($_SESSION['itop_env']) && ($_SESSION['itop_env'] !== $sSwitchEnv))
{
	$_SESSION['itop_env'] = $sSwitchEnv;
	$sEnv = $sSwitchEnv;
    $bAllowCache = false;
    // Reset the opcache since otherwise the PHP "model" files may still be cached !!
    if (function_exists('opcache_reset'))
    {
        // Zend opcode cache
        opcache_reset();
    }
    if (function_exists('apc_clear_cache'))
    {
        // APC(u) cache
        apc_clear_cache();
    }
	// TODO: reset the credentials as well ??
}
else if (isset($_SESSION['itop_env']))
{
	$sEnv = $_SESSION['itop_env'];
}
else
{
	$sEnv = ITOP_DEFAULT_ENV;
	$_SESSION['itop_env'] = ITOP_DEFAULT_ENV;
}
$sConfigFile = APPCONF.$sEnv.'/'.ITOP_CONFIG_FILE;
MetaModel::Startup($sConfigFile, false /* $bModelOnly */, $bAllowCache, false /* $bTraceSourceFiles */, $sEnv);

//
// Maintenance message display functions
//

/**
 * Use a setup page to display the maintenance message
 * @param $sTitle
 * @param $sMessage
 */
function _MaintenanceSetupPageMessage($sTitle, $sMessage)
{
	// Web Page
	@include_once(APPROOT."/setup/setuppage.class.inc.php");
	if (class_exists('SetupPage'))
	{
		$oP = new SetupPage($sTitle);
		$oP->p("<h2>$sMessage</h2>");
		$oP->output();
	}
	else
	{
		_MaintenanceTextMessage($sMessage);
	}
}

/**
 * Use simple text to display the maintenance message
 * @param $sMessage
 */
function _MaintenanceTextMessage($sMessage)
{
	echo $sMessage;
}

/**
 * Use a simple HTML to display the maintenance message
 * @param $sMessage
 */
function _MaintenanceHtmlMessage($sMessage)
{
	echo '<html><body><div>'.$sMessage.'</div></body></html>';
}

/**
 * Use a simple JSON to display the maintenance message
 *
 * @param $sTitle
 * @param $sMessage
 */
function _MaintenanceJsonMessage($sTitle, $sMessage)
{
	@include_once(APPROOT."/application/ajaxwebpage.class.inc.php");
	if (class_exists('ajax_page'))
	{
		$oP = new ajax_page($sTitle);
		$oP->add_header('Access-Control-Allow-Origin: *');
		$oP->SetContentType('application/json');
		$oP->add('{"code":100, "message":"'.$sMessage.'"}');
		$oP->Output();
	}
	else
	{
		_MaintenanceTextMessage($sMessage);
	}
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