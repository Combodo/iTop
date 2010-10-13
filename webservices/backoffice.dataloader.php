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
 * Does load data from XML files (currently used in the setup and the backoffice data loader utility)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

/**
 * This page is called to load an XML file into the database
 * parameters
 * 'file' string Name of the file to load
 */ 
define('SAFE_MINIMUM_MEMORY', 256*1024*1024);
require_once('../application/utils.inc.php');
require_once("../application/nicewebpage.class.inc.php");
// required because the class xmldataloader is reporting errors in the setup.log file
require_once('../setup/setuppage.class.inc.php');


function SetMemoryLimit($oP)
{
	$sMemoryLimit = trim(ini_get('memory_limit'));
	if (empty($sMemoryLimit))
	{
		// On some PHP installations, memory_limit does not exist as a PHP setting!
		// (encountered on a 5.2.0 under Windows)
		// In that case, ini_set will not work, let's keep track of this and proceed with the data load
		$oP->p("No memory limit has been defined in this instance of PHP");		
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
				$oP->p("memory_limit is too small: $iMemoryLimit and can not be increased by the script itself.");		
			}
			else
			{
				$oP->p("memory_limit increased from $iMemoryLimit to ".SAFE_MINIMUM_MEMORY.".");		
			}
		}
	}
}


////////////////////////////////////////////////////////////////////////////////
//
// Main
//
////////////////////////////////////////////////////////////////////////////////

require_once('../core/config.class.inc.php');
require_once('../core/log.class.inc.php');
require_once('../core/kpi.class.inc.php');
require_once('../core/cmdbsource.class.inc.php');
require_once('../setup/xmldataloader.class.inc.php');

define('FINAL_CONFIG_FILE', '../config-itop.php');

// Never cache this page
header("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header("Expires: Fri, 17 Jul 1970 05:00:00 GMT");    // Date in the past

Utils::SpecifyConfigFile(FINAL_CONFIG_FILE);

/**
 * Main program
 */
$sFileName = Utils::ReadParam('file', '');

$oP = new WebPage("iTop - Backoffice data loader");


try
{
	// Note: the data model must be loaded first
	$oDataLoader = new XMLDataLoader(FINAL_CONFIG_FILE); // When called by the wizard, the final config is not yet there

	if (empty($sFileName))
	{
		throw(new Exception("Missing argument 'file'"));
	}
	if (!file_exists($sFileName))
	{
		throw(new Exception("File $sFileName does not exist"));
	}

	SetMemoryLimit($oP);
	

	// The XMLDataLoader constructor has initialized the DB, let's start a transaction 
	CMDBSource::Query('SET AUTOCOMMIT=0');
	CMDBSource::Query('BEGIN WORK');
	
	$oChange = MetaModel::NewObject("CMDBChange");
	$oChange->Set("date", time());
	$oChange->Set("userinfo", "Initialization");
	$iChangeId = $oChange->DBInsert();
	$oP->p("Starting data load.");		
	$oDataLoader->StartSession($oChange);
	
	$oDataLoader->LoadFile($sFileName);
	
	$oP->p("Ending data load session");
	if ($oDataLoader->EndSession(true /* strict */))
	{
		$iCountCreated = $oDataLoader->GetCountCreated();
		CMDBSource::Query('COMMIT');

		$oP->p("Data successfully written into the DB: $iCountCreated objects created");
	}
	else
	{
		CMDBSource::Query('ROLLBACK');
		$oP->p("Some issues have been encountered, changes will not be recorded, please review the source data");
		$aErrors = $oDataLoader->GetErrors();
		if (count($aErrors) > 0)
		{
			$oP->p('Errors ('.count($aErrors).')');
			foreach ($aErrors as $sMsg)
			{
				$oP->p(' * '.$sMsg);
			}
		}
		$aWarnings = $oDataLoader->GetWarnings();
		if (count($aWarnings) > 0)
		{
			$oP->p('Warnings ('.count($aWarnings).')');
			foreach ($aWarnings as $sMsg)
			{
				$oP->p(' * '.$sMsg);
			}
		}
	}

}
catch(Exception $e)
{
	$oP->p("An error happened while loading the data: ".$e->getMessage());		
	$oP->p("Aborting (no data written)...");		
	CMDBSource::Query('ROLLBACK');
}

if (function_exists('memory_get_peak_usage'))
{
	$oP->p("Information: memory peak usage: ".memory_get_peak_usage());
}

$oP->Output();
?>
