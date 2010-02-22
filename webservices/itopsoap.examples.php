<?php

require_once('itopsoaptypes.class.inc.php');

$sItopRoot = 'http'.(empty($_SERVER['HTTPS']) ? '' : 's').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/..';
$sWsdlUri = $sItopRoot.'/webservices/itop.wsdl.php';

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
		'Server', /* type */
		'Email server down', /* description */
		'HW found shutdown', /* initial situation */
		'Email not working', /* impact */
		null, /* caller */
		new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', 'Demo'))), /* customer */
		new SOAPExternalKeySearch(array(new SOAPSearchCondition('id', 1))), /* workgroup */
		array(
			new SOAPLinkCreationSpec(
				'bizDevice',
				array(new SOAPSearchCondition('name', 'Router03')),
				array(new SOAPAttributeValue('impact', 'root cause'))
			),
			new SOAPLinkCreationSpec(
				'bizServer',
				array(new SOAPSearchCondition('name', 'Server01')),
				array(new SOAPAttributeValue('impact', ''))
			),
		), /* impact */
		'high' /* severity */
	);

	echo "<p>CreateIncidentTicket() returned:\n";
	echo "<pre>\n";
	print_r($oRes);
	echo "</pre>\n";
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
