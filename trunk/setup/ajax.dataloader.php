<?php
/**
 * This page is called to load "asynchronously" some xml file into the database
 * parameters
 * 'file' string Name of the file to load
 * 'session_status' string 'start', 'continue' or 'end'
 * 'percent' integer 0..100 the percentage of completion once the file has been loaded 
 */ 
require_once('../application/utils.inc.php');
require_once('../core/config.class.inc.php');
require_once('../core/cmdbsource.class.inc.php');
require_once('./setuppage.class.inc.php');
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
setup_web_page::log("Info - Loading file: $sFileName");
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
		$oDataLoader->StartSession($oChange);
	}

	$oDataLoader->LoadFile($sFileName);

	if ($sSessionStatus == 'end')
	{
	    $oDataLoader->EndSession();
	}
	$sResult = sprintf("Info - loading of %s done. (Overall %d %% completed).", basename($sFileName), $iPercent);
	echo $sResult;
	setup_web_page::log($sResult);
}
catch(Exception $e)
{
	echo "<p>An error happened while loading the data</p>\n";
	echo '<p>'.$e."</p>\n";
	setup_web_page::log("Error - An error happened while loading the data. ".$e);
	
}

?>
