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
 * Handling of SOAP queries
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

// Important note: if some required includes are missing, this might result
// in the error "looks like we got no XML document"...

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

// this file is generated dynamically with location = here
$sWsdlUri = 'http'.((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off')) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../webservices/itop.wsdl.php';
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
			$sSoapServerUri = 'http'.((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off')) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../webservices/soapserver.php';
			$sSoapServerUri .= "?service_category=$sServiceCategory";
			echo "<li><a href=\"$sSoapServerUri\">$sServiceCategory</a></li>\n";
		}
	}
	echo "</ul>\n";
}
?>
