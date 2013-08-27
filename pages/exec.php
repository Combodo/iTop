<?php
// Copyright (C) 2013 Combodo SARL
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
 * Execute a module page - this is an alternative to invoking /myItop/env-production/myModule/somePage.php
 *
 * The recommended way to build an URL to a module page is to invoke utils::GetAbsoluteUrlModulePage()
 * or its javascript equivalent GetAbsoluteUrlModulePage()
 * 
 * To be compatible with this mechanism, the called page must include approot
 * with an absolute path OR not include it at all (losing the direct access to the page)
 * if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
 * require_once(__DIR__.'/../../approot.inc.php');
 *  
 * @copyright   Copyright (C) 2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('../approot.inc.php');

// Needed to read the parameters (with sanitization)
require_once(APPROOT.'application/utils.inc.php');

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

$sEnvironment = utils::ReadParam('exec_env', 'production');

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
