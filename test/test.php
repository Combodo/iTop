<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Core test page
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

?>
<style>
.vardump {
font-size:8pt;
line-height:100%;
}
</style>
<?

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


require_once('../application/utils.inc.php');
require_once('../core/test.class.inc.php');
require_once('./testlist.inc.php');

require_once('../core/cmdbobject.class.inc.php');

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
		echo "<li><a href=\"?todo=exec&testid=$sClassName\">$sName</a> ($sDescription)</li\n";
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
		echo "<h3>Testing: ".$oTest->GetName()."</h3>\n";
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
