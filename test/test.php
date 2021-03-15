<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

?>
<style>
.vardump {
font-size:8pt;
line-height:100%;
}
</style>
<?php

///////////////////////////////////////////////////////////////////////////////
// Helpers
///////////////////////////////////////////////////////////////////////////////

function ReadMandatoryParam($sName)
{
	$value = utils::ReadParam($sName, null);
	if (is_null($value))
	{
		echo "<p>Missing mandatory argument <b>$sName</b></p>";
		exit;
	}
	return $value;
}

function IsAValidTestClass($sClassName)
{
	// Must be a child of TestHandler
	//
	if (!is_subclass_of($sClassName, 'TestHandler')) return false;

	// Must not be abstract
	//
	$oReflectionClass = new ReflectionClass($sClassName);
	if (!$oReflectionClass->isInstantiable()) return false;

	return true;
}

function GetTestClassLine($sClassName)
{
	$oReflectionClass = new ReflectionClass($sClassName);
	return $oReflectionClass->getStartLine();
}

function DisplayEvents($aEvents, $sTitle)
{
	echo "<h4>$sTitle</h4>\n";
	if (count($aEvents) > 0)
	{
		echo "<ul>\n";
		foreach ($aEvents as $sEvent)
		{
			echo "<li>$sEvent</li>\n";
		}
		echo "</ul>\n";
	}
	else
	{
		echo "<p>none</p>\n";
	}
}

///////////////////////////////////////////////////////////////////////////////
// Main
///////////////////////////////////////////////////////////////////////////////

date_default_timezone_set('Europe/Paris');

require_once('../approot.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once('./test.class.inc.php');
require_once('./testlist.inc.php');

require_once(APPROOT.'/core/cmdbobject.class.inc.php');


$sTodo = utils::ReadParam("todo", "");
if ($sTodo == '')
{
	// Show the list of tests
	//
	echo "<h3>Existing tests</h3>\n";
	echo "<ul>\n";
	foreach (get_declared_classes() as $sClassName)
	{
		if (!IsAValidTestClass($sClassName)) continue;
		
		$sName = call_user_func(array($sClassName, 'GetName'));
		$sDescription = call_user_func(array($sClassName, 'GetDescription'));
		echo "<li><a href=\"?todo=exec&testid=$sClassName\">$sName</a> ($sDescription)</li>\n";
	}
	echo "</ul>\n";
}
else if ($sTodo == 'exec')
{
	// Execute a test
	//
	$sTestClass = ReadMandatoryParam("testid");

	if (!IsAValidTestClass($sTestClass))
	{
		echo "<p>Wrong value for testid, expecting a valid class name</p>\n";
	}
	else
	{
		$oTest  = new $sTestClass();
		$iStartLine = GetTestClassLine($sTestClass);
		echo "<h3>Testing: ".$oTest->GetName()."</h3>\n";
		echo "<h6>testlist.inc.php: $iStartLine</h6>\n";
		$bRes = $oTest->Execute();
	}

/*
MyHelpers::var_dump_html($oTest->GetResults());
MyHelpers::var_dump_html($oTest->GetWarnings());
MyHelpers::var_dump_html($oTest->GetErrors());
*/

	if ($bRes)
	{
		echo "<p>Success :-)</p>\n";
		DisplayEvents($oTest->GetResults(), 'Results');
	}
	else
	{
		echo "<p>Failure :-(</p>\n";
	}
	DisplayEvents($oTest->GetErrors(), 'Errors');
	DisplayEvents($oTest->GetWarnings(), 'Warnings');

	// Render the output
	//
	echo "<h4>Actual output</h4>\n";
	echo "<div style=\"border: dashed; background-color:light-grey;\">\n";
	echo $oTest->GetOutput();
	echo "</div>\n";
}
else
{
}


?>
