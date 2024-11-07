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

use Combodo\iTop\Application\WebPage\CLIPage;
use Combodo\iTop\Application\WebPage\WebPage;

if (!defined('APPROOT'))
{
	if (file_exists(__DIR__.'/../../approot.inc.php'))
	{
		require_once __DIR__.'/../../approot.inc.php';   // When in env-xxxx folder
	}
	else
	{
		require_once __DIR__.'/../../../approot.inc.php';   // When in datamodels/x.x folder
	}
}
require_once(APPROOT.'application/application.inc.php');
require_once(APPROOT.'core/log.class.inc.php');
require_once(APPROOT.'application/startup.inc.php');

/**
 * Checks if a parameter (possibly empty) was specified when calling this page
 */
function CheckParam($sParamName)
{
	global $argv;

	if (isset($_REQUEST[$sParamName])) return true; // HTTP parameter either GET or POST
	if (!is_array($argv)) return false;
	foreach($argv as $sArg)
	{
		if ($sArg == '--'.$sParamName) return true; // Empty command line parameter, long unix style
		if ($sArg == $sParamName) return true; // Empty command line parameter, Windows style
		if ($sArg == '-'.$sParamName) return true; // Empty command line parameter, short unix style
		if (preg_match('/^--'.$sParamName.'=(.*)$/', $sArg, $aMatches)) return true; // Command parameter with a value
	}
	return false;
}

function ExitError($oP, $sMessage)
{
	ToolsLog::Error($sMessage);
	$oP->p($sMessage);
	$oP->output();
	exit;
}

function ReadMandatoryParam($oP, $sParam)
{
	$sValue = utils::ReadParam($sParam, null, true /* Allow CLI */, 'raw_data');
	if (is_null($sValue))
	{
		ExitError($oP, "ERROR: Missing argument '$sParam'");
	}
	return trim($sValue);
}

/////////////////////////////////
// Main program

set_time_limit(0);

if (utils::IsModeCLI())
{
	$oP = new CLIPage(GetOperationName());

	SetupUtils::CheckPhpAndExtensionsForCli($oP);
}
else
{
	$oP = new WebPage(GetOperationName());
}

try
{
	utils::UseParamFile();
}
catch(Exception $e)
{
	ExitError($oP, $e->GetMessage());
}

ExecuteMainOperation($oP);

$oP->output();
