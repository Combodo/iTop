<?php

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

function __clear_cache()
{
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
}

// Use 'maintenance' parameter to bypass maintenance mode
$bMaintenanceAction = !is_null(Utils::ReadParam('maintenance', null));

// Maintenance mode
if (file_exists(APPROOT.'.maintenance') && !$bMaintenanceAction)
{
	require_once(APPROOT.'core/dict.class.inc.php');
	$sMessage = Dict::S('UI:Error:MaintenanceMode');
	$sTitle = Dict::S('UI:Error:MaintenanceTitle');
	__clear_cache();
	throw new MaintenanceException($sMessage, $sTitle);
}

require_once(APPROOT.'/core/cmdbobject.class.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/core/contexttag.class.inc.php');
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
	__clear_cache();
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