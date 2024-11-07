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


//
// Maintenance message display functions
// Only included by approot.inc.php
//
use Combodo\iTop\Application\WebPage\ErrorPage;

/**
 * Use a setup page to display the maintenance message
 * @param $sTitle
 * @param $sMessage
 */
function _MaintenanceSetupPageMessage($sTitle, $sMessage)
{
	// Web Page
	@include_once(APPROOT.'setup/setuppage.class.inc.php');
	if (class_exists('SetupPage'))
	{
		$oP = new ErrorPage($sTitle);
		$oP->p("<h2 class=\"center\">$sMessage</h2>");
		$oP->add_ready_script(
<<<JS
// Reload in 30s to check if maintenance is over
setTimeout(function(){ window.location.reload(); }, 30000);
JS

		);
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
	if (class_exists('JsonPage'))
	{
		$oP = new JsonPage($sTitle);
		$oP->add_header('Access-Control-Allow-Origin: *');

		$aMessage = [
			'code' => 100,
			'message' =>$sMessage
		];

		$oP->AddData($aMessage);
		$oP->Output();
	} else {
		@include_once(APPROOT."/application/ajaxwebpage.class.inc.php");
		if (class_exists('ajax_page')) {
			$oP = new ajax_page($sTitle);
			$oP->add_header('Access-Control-Allow-Origin: *');
			$oP->SetContentType('application/json');
			$oP->add('{"code":100, "message":"'.$sMessage.'"}');
			$oP->Output();
		} else {
			_MaintenanceTextMessage($sMessage);
		}
	}
}
