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

require_once('../application/application.inc.php');
require_once('../application/startup.inc.php');

require('./webservices.class.inc.php');

// this file is generated dynamically with location = here
$sWsdlUri = 'http'.((empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off')) ? '' : 's').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../webservices/itop.wsdl.php';


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
