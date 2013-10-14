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

/**
 * Helper to execute an HTTP POST request
 * Source: http://netevil.org/blog/2006/nov/http-post-from-php-without-curl
 *         originaly named after do_post_request
 */ 
function DoPostRequest($sUrl, $aData, $sOptionnalHeaders = null)
{
	// $sOptionnalHeaders is a string containing additional HTTP headers that you would like to send in your request.

	$sData = http_build_query($aData);

	$aParams = array('http' => array(
							'method' => 'POST',
							'content' => $sData,
							'header'=> "Content-type: application/x-www-form-urlencoded\r\nContent-Length: ".strlen($sData)."\r\n",
							));
	if ($sOptionnalHeaders !== null)
	{
		$aParams['http']['header'] .= $sOptionnalHeaders;
	}
	$ctx = stream_context_create($aParams);

	$fp = @fopen($sUrl, 'rb', false, $ctx);
	if (!$fp)
	{
		global $php_errormsg;
		if (isset($php_errormsg))
		{
			throw new Exception("Problem with $sUrl, $php_errormsg");
		}
		else
		{
			throw new Exception("Problem with $sUrl");
		}
	}
	$response = @stream_get_contents($fp);
	if ($response === false)
	{
		throw new Exception("Problem reading data from $sUrl, $php_errormsg");
	}
	return $response;
}

// If the library curl is installed.... use this function
//
function DoPostRequest_curl($sUrl, $aData)
{
	$curl = curl_init($sUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $aData);
	$response = curl_exec($curl);
	curl_close($curl);

	return $response;
}

////////////////////////////////////////////////////////////////////////////////
//
// Main program
//
////////////////////////////////////////////////////////////////////////////////

// Define the operations to perform (one operation per call the rest service)
//

$aOperations = array(
	array(
		'operation' => 'list_operations', // operation code
	),
	array(
		'operation' => 'core/create', // operation code
		'comment' => 'Synchronization from blah...', // comment recorded in the change tracking log
		'class' => 'UserRequest',
		'output_fields' => 'id, friendlyname', // list of fields to show in the results (* or a,b,c)
		// Values for the object to create
		'fields' => array(
			'org_id' => "SELECT Organization WHERE name = 'Demo'",
			'caller_id' => array('name' => 'monet', 'first_name' => 'claude'),
			'title' => 'issue blah',
			'description' => 'something happened'
		),
	),
	array(
		'operation' => 'core/update', // operation code
		'comment' => 'Synchronization from blah...', // comment recorded in the change tracking log
		'class' => 'UserRequest',
		'key' => 'SELECT UserRequest WHERE id=1',
		'output_fields' => 'id, friendlyname, title', // list of fields to show in the results (* or a,b,c)
		// Values for the object to create
		'fields' => array(
			'title' => 'Issue #'.rand(0, 100),
			'contacts_list' => array(
				array(
					'role' => 'fireman #'.rand(0, 100),
					'contact_id' => array('finalclass' => 'Person', 'name' => 'monet', 'first_name' => 'claude'),
				),
			),
		),
	),
	array(
		'operation' => 'core/get', // operation code
		'class' => 'UserRequest',
		'key' => 'SELECT UserRequest',
		'output_fields' => 'id, friendlyname, title, contacts_list', // list of fields to show in the results (* or a,b,c)
	),
	array(
		'operation' => 'core/delete', // operation code
		'comment' => 'Cleanup for synchro with...', // comment recorded in the change tracking log
		'class' => 'UserRequest',
		'key' => 'SELECT UserRequest WHERE org_id = 2',
		'simulate' => true,
	),
	array(
		'operation' => 'core/apply_stimulus', // operation code
		'comment' => 'Synchronization from blah...', // comment recorded in the change tracking log
		'class' => 'UserRequest',
		'key' => 1,
		'stimulus' => 'ev_assign',
		// Values to set
		'fields' => array(
			'team_id' => 15, // Helpdesk
			'agent_id' => 9 // Jules Verne
		),
		'output_fields' => 'id, friendlyname, title, contacts_list', // list of fields to show in the results (* or a,b,c)
	),
	array(
		'operation' => 'core/get_related', // operation code
		'class' => 'Server',
		'key' => 'SELECT Server',
		'relation' => 'impacts', // relation code
		'depth' => 4, // max recursion depth
	),
);

if (true)
{
	echo "Please edit the sample script and configure the server URL";
	exit;
}
else
{
	$sUrl = "http://localhost/trunk/webservices/rest.php?version=1.1";
}

$aData = array();
$aData['auth_user'] = 'admin';
$aData['auth_pwd'] = 'admin';

foreach ($aOperations as $iOp => $aOperation)
{
	echo "======================================\n";
	echo "Operation #$iOp: ".$aOperation['operation']."\n";
	$aData['json_data'] = json_encode($aOperation);

	echo "--------------------------------------\n";
	echo "Input:\n";
	print_r($aOperation);

	$response = DoPostRequest($sUrl, $aData);
	$aResults = json_decode($response);
	if ($aResults)
	{
		echo "--------------------------------------\n";
		echo "Reply:\n";
		print_r($aResults);
	}
	else
	{
		echo "ERROR rest.php replied:\n";
		echo $response;
	}
}

?>