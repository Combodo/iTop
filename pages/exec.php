<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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

require_once('../approot.inc.php');

// Needed to read the parameters (with sanitization)
require_once(APPROOT.'application/utils.inc.php');
require_once(APPROOT.'core/metamodel.class.php');

utils::InitTimeZone();

$sModule = utils::ReadParam('exec_module', '');
if ($sModule == '')
{
	echo "Missing argument 'exec_module'";
	exit;
}
$sModule = basename($sModule); // protect against ../.. ...

$sPage = utils::ReadParam('exec_page', '', false, 'raw_data');
if ($sPage == '')
{
	echo "Missing argument 'exec_page'";
	exit;
}
$sPage = basename($sPage); // protect against ../.. ...

session_name('itop-'.md5(APPROOT));
session_start();
$sEnvironment = utils::ReadParam('exec_env', utils::GetCurrentEnvironment());
session_write_close();

$sTargetPage = APPROOT.'env-'.$sEnvironment.'/'.$sModule.'/'.$sPage;

if (!file_exists($sTargetPage))
{
	// Do not recall the parameters (security takes precedence)
	echo "Wrong module, page name or environment...";
	exit;
}

/////////////////////////////////////////
//
// GO!
//
require_once($sTargetPage);
