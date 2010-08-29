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
 * Does load data from XML files (currently used in the setup only)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

/**
 * This page is called to load "asynchronously" some xml file into the database
 * parameters
 * 'file' string Name of the file to load
 * 'session_status' string 'start', 'continue' or 'end'
 * 'percent' integer 0..100 the percentage of completion once the file has been loaded 
 */ 
define('SAFE_MINIMUM_MEMORY', 32*1024*1024);
require_once('../application/utils.inc.php');
require_once('./setuppage.class.inc.php');

$sMemoryLimit = trim(ini_get('memory_limit'));
if (empty($sMemoryLimit))
{
	// On some PHP installations, memory_limit does not exist as a PHP setting!
	// (encountered on a 5.2.0 under Windows)
	// In that case, ini_set will not work, let's keep track of this and proceed with the data load
	SetupWebPage::log_info("No memory limit has been defined in this instance of PHP");		
}
else
{
	// Check that the limit will allow us to load the data
	//
	$iMemoryLimit = utils::ConvertToBytes($sMemoryLimit);
	if ($iMemoryLimit < SAFE_MINIMUM_MEMORY)
	{
		if (ini_set('memory_limit', SAFE_MINIMUM_MEMORY) === FALSE)
		{
			SetupWebPage::log_error("memory_limit is too small: $iMemoryLimit and can not be increased by the script itself.");		
		}
		else
		{
			SetupWebPage::log_info("memory_limit increased from $iMemoryLimit to ".SAFE_MINIMUM_MEMORY.".");		
		}
	}

}


function FatalErrorCatcher($sOutput)
{ 
	if ( preg_match('|<phpfatalerror>.*</phpfatalerror>|s', $sOutput, $aMatches) )
	{
		header("HTTP/1.0 500 Internal server error.");
		foreach ($aMatches as $sMatch)
		{
			$errors .= strip_tags($sMatch)."\n";
		}
		$sOutput = "$errors\n";
		// Logging to a file does not work if the whole memory is exhausted...		
		//SetupWebPage::log_error("Fatal error - in $__FILE__ , $errors");
	}
	return $sOutput;
}
	
//Define some bogus, invalid HTML tags that no sane
//person would ever put in an actual document and tell
//PHP to delimit fatal error warnings with them.
ini_set('error_prepend_string', '<phpfatalerror>');
ini_set('error_append_string', '</phpfatalerror>');

// Starts the capture of the ouput, and sets a filter to capture the fatal errors.
ob_start('FatalErrorCatcher'); // Start capturing the output, and pass it through the fatal error catcher

require_once('../core/config.class.inc.php');
require_once('../core/log.class.inc.php');
require_once('../core/duration.class.inc.php');
require_once('../core/cmdbsource.class.inc.php');
require_once('./xmldataloader.class.inc.php');

define('TMP_CONFIG_FILE', '../tmp-config-itop.php');
//define('FINAL_CONFIG_FILE', '../config-itop.php');

// Never cache this page
header("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header("Expires: Fri, 17 Jul 1970 05:00:00 GMT");    // Date in the past

/**
 * Main program
 */
$sFileName = Utils::ReadParam('file', '');
$sSessionStatus = Utils::ReadParam('session_status', '');
$iPercent = (integer)Utils::ReadParam('percent', 0);
SetupWebPage::log_info("Loading file: $sFileName");

try
{
	if (empty($sFileName) || !file_exists($sFileName))
	{
		throw(new Exception("File $sFileName does not exist"));
	}
	
	$oDataLoader = new XMLDataLoader(TMP_CONFIG_FILE); // When called by the wizard, the final config is not yet there
	if ($sSessionStatus == 'start')
	{
		$oChange = MetaModel::NewObject("CMDBChange");
		$oChange->Set("date", time());
		$oChange->Set("userinfo", "Initialization");
		$iChangeId = $oChange->DBInsert();
		SetupWebPage::log_info("starting data load session");
		$oDataLoader->StartSession($oChange);
	}

	$oDataLoader->LoadFile($sFileName);
	$sResult = sprintf("loading of %s done. (Overall %d %% completed).", basename($sFileName), $iPercent);
	//echo $sResult;
	SetupWebPage::log_info($sResult);

	if ($sSessionStatus == 'end')
	{
	    $oDataLoader->EndSession();
	    SetupWebPage::log_info("ending data load session");
	}
}
catch(Exception $e)
{
	header("HTTP/1.0 500 Internal server error.");
	echo "<p>An error happened while loading the data</p>\n";
	echo '<p>'.$e."</p>\n";
	SetupWebPage::log_error("An error happened while loading the data. ".$e);
}

if (function_exists('memory_get_peak_usage'))
{
	SetupWebPage::log_info("loading file '$sFileName', peak memory usage. ".memory_get_peak_usage());
}
?>
