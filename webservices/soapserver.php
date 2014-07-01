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
 * Handling of SOAP queries
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Important note: if some required includes are missing, this might result
// in the error "looks like we got no XML document"...

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

// this file is generated dynamically with location = here
$sWsdlUri = 'http'.(utils::IsConnectionSecure() ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../webservices/itop.wsdl.php';
if (isset($_REQUEST['service_category']) && (!empty($_REQUEST['service_category'])))
{
	$sWsdlUri .= "soapserver.php?service_category=".$_REQUEST['service_category'];
}


ini_set("soap.wsdl_cache_enabled","0");

$aSOAPMapping = SOAPMapping::GetMapping();
$oSoapServer = new SoapServer
(
	$sWsdlUri,
	array(
		'classmap' => $aSOAPMapping
	)
);
// $oSoapServer->setPersistence(SOAP_PERSISTENCE_SESSION);
if (isset($_REQUEST['service_category']) && (!empty($_REQUEST['service_category'])))
{
	$sServiceClass = $_REQUEST['service_category'];
	if (!class_exists($sServiceClass))
	{
		// not a valid class name (not a PHP class at all)
		throw new SoapFault("iTop SOAP server", "Invalid argument service_category: '$sServiceClass' is not a PHP class");
	}
	elseif (!is_subclass_of($sServiceClass, 'WebServicesBase'))
	{
		// not a valid class name (not deriving from WebServicesBase)
		throw new SoapFault("iTop SOAP server", "Invalid argument service_category: '$sServiceClass' is not derived from WebServicesBase");
	}
	else
	{
		$oSoapServer->setClass($sServiceClass, null);
	}
}
else
{
	$oSoapServer->setClass('BasicServices', null);
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	CMDBObject::SetTrackOrigin('webservice-soap');
	$oSoapServer->handle();
}
else
{
	echo "This SOAP server can handle the following functions: ";
	$aFunctions = $oSoapServer->getFunctions();
	echo "<ul>\n";
	foreach($aFunctions as $sFunc)
	{
		if ($sFunc == 'GetWSDLContents') continue;

		echo "<li>$sFunc</li>\n";
	}
	echo "</ul>\n";
	echo "<p>Here the <a href=\"$sWsdlUri\">WSDL file</a><p>";

	echo "You may also want to try the following service categories: ";
	echo "<ul>\n";
	foreach(get_declared_classes() as $sPHPClass)
	{
		if (is_subclass_of($sPHPClass, 'WebServicesBase'))
		{
			$sServiceCategory = $sPHPClass;
			$sSoapServerUri = 'http'.(utils::IsConnectionSecure() ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../webservices/soapserver.php';
			$sSoapServerUri .= "?service_category=$sServiceCategory";
			echo "<li><a href=\"$sSoapServerUri\">$sServiceCategory</a></li>\n";
		}
	}
	echo "</ul>\n";
}
?>
