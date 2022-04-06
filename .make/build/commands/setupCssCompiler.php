<?php

/**
 * Copyright (C) 2010-2021 Combodo SARL
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

use Combodo\iTop\Composer\iTopComposer;

$iTopFolder = __DIR__."/../../../";

require_once("$iTopFolder/approot.inc.php");
require_once(APPROOT."/application/utils.inc.php");

if (php_sapi_name() !== 'cli')
{
	throw new \Exception('This script can only run from CLI');
}

$sCssFile = APPROOT.'/css/setup.css';
if (file_exists($sCssFile))
{
	fwrite(STDERR, "$sCssFile already exists (it should not), removing it.");
	if (!unlink($sCssFile))
	{
		fwrite(STDERR, "Failed to remove $sCssFile, exiting.");
		exit(1);
	}
}
$sCssRelPath = utils::GetCSSFromSASS('css/setup.scss');

if (!file_exists($sCssFile))
{
	fwrite(STDERR, "Failed to compile $sCssFile, exiting.");
	exit(1);
}