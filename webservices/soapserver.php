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


// pb ? - login_web_page::DoLogin(); // Check user rights and prompt if needed

// Main program

$oSoapServer = new SoapServer(
	null,
	//"http://localhost:81/trunk/webservices/Itop.wsdl", // to be a file generated dynamically with location = here
	array(
		'uri' => 'http://test-itop/',
		// note: using the classmap and no WSDL spec causes a fault in APACHE (looks like an infinite loop)
		//'classmap' => array('ItopErrorSOAP' => 'ItopError')
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
	echo "";
}
?>
