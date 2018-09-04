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


/**
 * File to include to initialize the datamodel in memory
 *
 * @copyright   Copyright (C) 2010-2016 Combodo SARL
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
		if (strpos($err['message'], 'Allowed memory size of') !== false)
		{
			$sLimit = ini_get('memory_limit');
			echo "<p>iTop: Allowed memory size of $sLimit exhausted, contact your administrator to increase memory_limit in php.ini</p>\n";
		}
		else
		{
			echo "<p>iTop: An error occurred, check server error log for more information.</p>\n";
		}
	}
});

require_once(APPROOT.'/core/cmdbobject.class.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/core/contexttag.class.inc.php');
session_name('itop-'.md5(APPROOT));
session_start();
$sSwitchEnv = utils::ReadParam('switch_env', null);
if (($sSwitchEnv != null) && (file_exists(APPCONF.$sSwitchEnv.'/'.ITOP_CONFIG_FILE)))
{
	$_SESSION['itop_env'] = $sSwitchEnv;
	$sEnv = $sSwitchEnv;
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
MetaModel::Startup($sConfigFile, false /* $bModelOnly */, true /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);