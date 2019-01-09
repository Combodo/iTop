<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Shows a usage of the SOAP queries 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('itopsoaptypes.class.inc.php');
$sItopRoot = 'http'.(utils::IsConnectionSecure() ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/..';
$sWsdlUri = $sItopRoot.'/webservices/itop.wsdl.php';
//$sWsdlUri .= '?service_category=';

$aSOAPMapping = SOAPMapping::GetMapping();

ini_set("soap.wsdl_cache_enabled","0");
$oSoapClient = new SoapClient(
	$sWsdlUri,
	array(
		'trace' => 1,
		'classmap' => $aSOAPMapping, // defined in itopsoaptypes.class.inc.php
	)
);

try
{
	// The most simple service, returning a string
	//
	$sServerVersion = $oSoapClient->GetVersion();
	echo "<p>GetVersion() returned <em>$sServerVersion</em></p>";

	// More complex ones, returning a SOAPResult structure
	// (run the page to know more about the returned data)
	//
	$oRes = $oSoapClient->CreateIncidentTicket
	(
		'admin', /* login */
		'admin', /* password */
		'Email server down', /* title */
		'HW found shutdown', /* description */
		null, /* caller */
		new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* customer */
		new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'NW Management'))), /* service */
		new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Troubleshooting'))), /* service subcategory */
		'', /* product */
		new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'NW support'))), /* workgroup */
		array(
			new SOAPLinkCreationSpec(
				'Device',
				array(new SOAPSearchCondition('name', 'switch01')),
				array()
			),
			new SOAPLinkCreationSpec(
				'Server',
				array(new SOAPSearchCondition('name', 'dbserver1.demo.com')),
				array()
			),
		), /* impacted cis */
		'1', /* impact */
		'1' /* urgency */
	);

	echo "<p>CreateIncidentTicket() returned:\n";
	echo "<pre>\n";
	print_r($oRes);
	echo "</pre>\n";
	echo "</p>\n";

	$oRes = $oSoapClient->SearchObjects
	(
		'admin', /* login */
		'admin', /* password */
		'SELECT URP_Profiles' /* oql */
	);

	echo "<p>SearchObjects() returned:\n";
	if ($oRes->status)
	{
		$aResults = $oRes->result;

		echo "<table>\n";

		// Header made after the first line
		echo "<tr>\n";
		foreach ($aResults[0]->values as $aKeyValuePair)
		{
			echo "   <th>".$aKeyValuePair->key."</th>\n";
		}
		echo "</tr>\n";

		foreach ($aResults as $iRow => $aData)
		{
			echo "<tr>\n";
			foreach ($aData->values as $aKeyValuePair)
			{
				echo "   <td>".$aKeyValuePair->value."</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
	else
	{
		$aErrors = array();
		foreach ($oRes->errors->messages as $oMessage)
		{
			$aErrors[] = $oMessage->text;
		}
		$sErrorMsg = implode(', ', $aErrors);
		echo "<p>SearchObjects() failed with message: $sErrorMsg</p>\n";
		//echo "<pre>\n";
		//print_r($oRes);
		//echo "</pre>\n";
	}
	echo "</p>\n";
}
catch(SoapFault $e)
{
	echo "<h1>SoapFault Exception: {$e->getMessage()}</h1>\n"; 
	echo "<h2>Request</h2>\n"; 
	echo "<pre>\n"; 
	echo htmlspecialchars($oSoapClient->__getLastRequest())."\n"; 
	echo "</pre>"; 
	echo "<h2>Response</h2>";
	echo $oSoapClient->__getLastResponse()."\n";
}
?>
