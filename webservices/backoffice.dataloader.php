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

/**
 * This page is called to load an XML file into the database
 * parameters
 * 'file' string Name of the file to load
 */ 
define('SAFE_MINIMUM_MEMORY', 256*1024*1024);

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');

require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)

// required because the class xmldataloader is reporting errors in the setup.log file
require_once(APPROOT.'/setup/setuppage.class.inc.php');
require_once(APPROOT.'/setup/xmldataloader.class.inc.php');


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
		if (!utils::IsMemoryLimitOk($iMemoryLimit, SAFE_MINIMUM_MEMORY))
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

// Never cache this page
header("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header("Expires: Fri, 17 Jul 1970 05:00:00 GMT");    // Date in the past

/**
 * Main program
 */
$sFileName = Utils::ReadParam('file', '', false, 'raw_data');

$oP = new WebPage("iTop - Backoffice data loader");


try
{
	// Note: the data model must be loaded first
	$oDataLoader = new XMLDataLoader();

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
	CMDBSource::Query('START TRANSACTION');
	
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
