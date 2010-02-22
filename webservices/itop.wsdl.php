<?php
// This is to make sure that the client will accept it....
//
header('Content-Type: application/xml; charset=UTF-8');
//header('Content-Disposition: attachment; filename="itop.wsdl"');
header('Content-Disposition: online; filename="itop.wsdl"');

$sMyWsdl = './itop.wsdl.tpl';

$sRawFile = file_get_contents($sMyWsdl);

$sServerURI = 'http'.(empty($_SERVER['HTTPS']) ? '' : 's').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/soapserver.php';

$sFinalFile = str_replace(
	'___SOAP_SERVER_URI___',
	$sServerURI,
	$sRawFile
);

echo $sFinalFile;
?>
