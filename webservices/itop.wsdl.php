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
 * Dynamic generation of the WSDL file for SOAP Web services
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

if (isset($_REQUEST['debug']))
{
	if ($_REQUEST['debug'] == 'text')
	{
		header('Content-Type: text/plain; charset=UTF-8');
	}
	else
	{
		header('Content-Type: application/xml; charset=UTF-8');
	}
}
else
{
	// This is to make sure that the client will accept it....
	//
	header('Content-Type: application/xml; charset=UTF-8');
	////header('Content-Disposition: attachment; filename="itop.wsdl"');
	header('Content-Disposition: online; filename="itop.wsdl"');
}

require_once('../approot.inc.php');
require_once(APPROOT.'webservices/webservices.class.inc.php');
require_once(APPROOT.'core/config.class.inc.php');

// Load the modules installed and enabled
//
$oConfig = new Config(APPROOT.'config-itop.php');
$aFiles = $oConfig->GetWebServiceCategories();
foreach ($aFiles as $sFile)
{
	require_once(APPROOT.$sFile);
}

if (isset($_REQUEST['service_category']) && (!empty($_REQUEST['service_category'])))
{
	$sRawFile = WebServicesBase::GetWSDLContents($_REQUEST['service_category']);
}
else
{
	$sRawFile = WebServicesBase::GetWSDLContents();
}

$sServerURI = 'http'.((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off')) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/soapserver.php';
if (isset($_REQUEST['service_category']) && (!empty($_REQUEST['service_category'])))
{
	$sServerURI .= "?service_category=".$_REQUEST['service_category'];
}

$sFinalFile = str_replace(
	'___SOAP_SERVER_URI___',
	$sServerURI,
	$sRawFile
);

echo $sFinalFile;
?>
