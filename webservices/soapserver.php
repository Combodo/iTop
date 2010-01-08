<?php

/**
 * SOAP-based web service 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */

// Important note: if some required includes are missing, this might result
// in the error "looks like we got no XML document"...

require_once('../application/application.inc.php');
require_once('../application/startup.inc.php');

require('./webservices.class.inc.php');

// this file is generated dynamically with location = here
$sWsdlUri = 'http'.(empty($_SERVER['HTTPS']) ? '' : 's').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../webservices/itop.wsdl.php';


ini_set("soap.wsdl_cache_enabled","0");

$oSoapServer = new SoapServer
(
	$sWsdlUri,
	array(
		'classmap' => $aSOAPMapping
	)
);
// $oSoapServer->setPersistence(SOAP_PERSISTENCE_SESSION);
$oSoapServer->setClass('WebServices', null);

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
		echo "<li>$sFunc</li>\n";
	}
	echo "</ul>\n";
	echo "<p>Here the <a href=\"$sWsdlUri\">WSDL file</a><p>";
}
?>
