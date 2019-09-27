<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


//
// Maintenance message display functions
//

/**
 * Use a setup page to display the maintenance message
 * @param $sTitle
 * @param $sMessage
 */
function _MaintenanceSetupPageMessage($sTitle, $sMessage)
{
	// Web Page
	@include_once(APPROOT.'bootstrap.inc.php');
	@include_once(APPROOT.'setup/setuppage.class.inc.php');
	if (class_exists('SetupPage'))
	{
		$oP = new SetupPage($sTitle);
		$oP->p("<h2>$sMessage</h2>");
		$oP->output();
	}
	else
	{
		_MaintenanceTextMessage($sMessage);
	}
}

/**
 * Use simple text to display the maintenance message
 * @param $sMessage
 */
function _MaintenanceTextMessage($sMessage)
{
	echo $sMessage;
}

/**
 * Use a simple HTML to display the maintenance message
 * @param $sMessage
 */
function _MaintenanceHtmlMessage($sMessage)
{
	echo '<html><body><div>'.$sMessage.'</div></body></html>';
}

/**
 * Use a simple JSON to display the maintenance message
 *
 * @param $sTitle
 * @param $sMessage
 */
function _MaintenanceJsonMessage($sTitle, $sMessage)
{
	@include_once(APPROOT.'bootstrap.inc.php');
	@include_once(APPROOT."/application/ajaxwebpage.class.inc.php");
	if (class_exists('ajax_page'))
	{
		$oP = new ajax_page($sTitle);
		$oP->add_header('Access-Control-Allow-Origin: *');
		$oP->SetContentType('application/json');
		$oP->add('{"code":100, "message":"'.$sMessage.'"}');
		$oP->Output();
	}
	else
	{
		_MaintenanceTextMessage($sMessage);
	}
}
