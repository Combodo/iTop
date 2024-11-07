<?php
// Copyright (C) 2010-2024 Combodo SAS
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
use Combodo\iTop\Application\Helper\Session;

require_once(APPROOT.'core/cmdbobject.class.inc.php');
require_once(APPROOT.'application/utils.inc.php');
require_once(APPROOT.'core/contexttag.class.inc.php');
require_once(APPROOT.'core/kpi.class.inc.php');
require_once(APPROOT.'setup/setuputils.class.inc.php');


/**
 * File to include to initialize the datamodel in memory
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

ExecutionKPI::EnableDuration(1);
ExecutionKPI::EnableMemory(1);

// This storage is freed on error (case of allowed memory exhausted)
$sReservedMemory = str_repeat('*', 1024 * 1024);
register_shutdown_function(function()
{
	global $sReservedMemory;
	$sReservedMemory = null;
	if (!is_null($err = error_get_last()) && ($err['type'] == E_ERROR))
	{
		// Remove stack trace from MySQLException (since 2.7.2 see N°3174)
		$sMessage = $err['message'];
		if (strpos($sMessage, 'MySQLException') !== false) {
			$iStackTracePos = strpos($sMessage, 'Stack trace:');
			if ($iStackTracePos !== false) {
				$sMessage = substr($sMessage, 0, $iStackTracePos);
			}
		}
		// Log additional info but message from $err (since 2.7.6 N°4174)
		$aErrToLog = $err;
		unset($aErrToLog['message']);
		IssueLog::error($sMessage, null, $aErrToLog);
		if (strpos($err['message'], 'Allowed memory size of') !== false) {
			$sLimit = ini_get('memory_limit');
			echo "<p>iTop: Allowed memory size of $sLimit exhausted, contact your administrator to increase 'memory_limit' in php.ini</p>\n";
		} elseif (strpos($err['message'], 'Maximum execution time') !== false) {
			$sLimit = ini_get('max_execution_time');
			echo "<p>iTop: Maximum execution time of $sLimit exceeded, contact your administrator to increase 'max_execution_time' in php.ini</p>\n";
		} else {
			echo "<p>iTop: An error occurred, check server error log for more information.</p>\n";
		}
	}
});
$oKPI = new ExecutionKPI();
Session::Start();
$oKPI->ComputeAndReport("Session Start");

$sSwitchEnv = utils::ReadParam('switch_env', null);
$bAllowCache = true;
if (($sSwitchEnv != null) && file_exists(APPCONF.$sSwitchEnv.'/'.ITOP_CONFIG_FILE) &&( Session::Get('itop_env') !== $sSwitchEnv))
{
	Session::Set('itop_env', $sSwitchEnv);
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
else if (Session::IsSet('itop_env'))
{
	$sEnv = Session::Get('itop_env');
}
else
{
	$sEnv = ITOP_DEFAULT_ENV;
	Session::Set('itop_env', ITOP_DEFAULT_ENV);
}
$sConfigFile = APPCONF.$sEnv.'/'.ITOP_CONFIG_FILE;
try {
    MetaModel::Startup($sConfigFile, false /* $bModelOnly */, $bAllowCache, false /* $bTraceSourceFiles */, $sEnv);
}
catch (MySQLException $e) {
    IssueLog::Debug($e->getMessage());
    throw new MySQLException('Could not connect to the DB server', []);
}