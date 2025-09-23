<?php
// Copyright (C) 2010-2024 Combodo SAS
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
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Helper to execute an HTTP POST request
 *
 * @param $sUrl
 * @param $aData
 * @param null $sOptionnalHeaders
 * @param null $aResponseHeaders
 * @param array $aCurlOptions
 *
 * @return bool|false|string
 * @throws \Exception
 */
function DoPostRequest($sUrl, $aData, $sOptionnalHeaders = null, &$aResponseHeaders = null, $aCurlOptions = array())
{
	// $sOptionnalHeaders is a string containing additional HTTP headers that you would like to send in your request.

	if (function_exists('curl_init'))
	{
		// If cURL is available, let's use it, since it provides a greater control over the various HTTP/SSL options
		// For instance fopen does not allow to work around the bug: http://stackoverflow.com/questions/18191672/php-curl-ssl-routinesssl23-get-server-helloreason1112
		// by setting the SSLVERSION to 3 as done below.
		$aHTTPHeaders = array();
		if ($sOptionnalHeaders !== null)
		{
			$aHeaders = explode("\n", $sOptionnalHeaders);
			// N°3267 - Webservices: Fix optional headers not being taken into account
			//          See https://www.php.net/curl_setopt CURLOPT_HTTPHEADER
			$aHTTPHeaders = array();
			foreach($aHeaders as $sHeaderString)
			{
				$aHTTPHeaders[] = trim($sHeaderString);
			}
		}
		// Default options, can be overloaded/extended with the 4th parameter of this method, see above $aCurlOptions
		$aOptions = array(
			CURLOPT_RETURNTRANSFER	=> true,     // return the content of the request
			CURLOPT_HEADER			=> false,    // don't return the headers in the output
			CURLOPT_FOLLOWLOCATION	=> true,     // follow redirects
			CURLOPT_ENCODING		=> "",       // handle all encodings
			CURLOPT_USERAGENT		=> "spider", // who am i
			CURLOPT_AUTOREFERER		=> true,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT	=> 120,      // timeout on connect
			CURLOPT_TIMEOUT			=> 120,      // timeout on response
			CURLOPT_MAXREDIRS		=> 10,       // stop after 10 redirects
			CURLOPT_SSL_VERIFYHOST	=> 0,   	 // Disabled SSL Cert checks
			CURLOPT_SSL_VERIFYPEER	=> 0,   	 // Disabled SSL Cert checks
			// SSLV3 (CURL_SSLVERSION_SSLv3 = 3) is now considered as obsolete/dangerous: http://disablessl3.com/#why
			// but it used to be a MUST to prevent a strange SSL error: http://stackoverflow.com/questions/18191672/php-curl-ssl-routinesssl23-get-server-helloreason1112
			// CURLOPT_SSLVERSION		=> 3,
			CURLOPT_POST			=> count($aData),
			CURLOPT_POSTFIELDS		=> http_build_query($aData),
			CURLOPT_HTTPHEADER		=> $aHTTPHeaders,
		);
		$aAllOptions = $aCurlOptions + $aOptions;
		$ch = curl_init($sUrl);
		curl_setopt_array($ch, $aAllOptions);
		$response = curl_exec($ch);
		$iErr = curl_errno($ch);
		$sErrMsg = curl_error( $ch );
		if ($iErr !== 0)
		{
			throw new Exception("Problem opening URL: $sUrl, $sErrMsg");
		}
		if (is_array($aResponseHeaders))
		{
			$aHeaders = curl_getinfo($ch);
			foreach($aHeaders as $sCode => $sValue)
			{
				$sName = str_replace(' ' , '-', ucwords(str_replace('_', ' ', $sCode))); // Transform "content_type" into "Content-Type"
				$aResponseHeaders[$sName] = $sValue;
			}
		}
		curl_close( $ch );
	}
	else
	{
		// cURL is not available let's try with streams and fopen...

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
				throw new Exception("Wrong URL: $sUrl, $php_errormsg");
			}
			elseif ((strtolower(substr($sUrl, 0, 5)) == 'https') && !extension_loaded('openssl'))
			{
				throw new Exception("Cannot connect to $sUrl: missing module 'openssl'");
			}
			else
			{
				throw new Exception("Wrong URL: $sUrl");
			}
		}
		$response = @stream_get_contents($fp);
		if ($response === false)
		{
			throw new Exception("Problem reading data from $sUrl, $php_errormsg");
		}
		if (is_array($aResponseHeaders))
		{
			$aMeta = stream_get_meta_data($fp);
			$aHeaders = $aMeta['wrapper_data'];
			foreach($aHeaders as $sHeaderString)
			{
				if(preg_match('/^([^:]+): (.+)$/', $sHeaderString, $aMatches))
				{
					$aResponseHeaders[$aMatches[1]] = trim($aMatches[2]);
				}
			}
		}
	}
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
	// Rewrite the full CaseLog on an existing UserRequest with id=1, setting date and user (optional)
	array(
		'operation' => 'core/update',
		'comment' => 'Synchronization from Client A', // comment recorded in the change tracking log
		'class' => 'UserRequest',
		'key' => 'SELECT UserRequest WHERE id=1',
		'output_fields' => 'id, friendlyname, title',
		'fields' => array(
			'public_log' => array(
				'items' => array(
					0 => array(
						'date' => '2001-02-01 23:59:59', //Allow to set the date of a true event, an alarm for eg.
						'user_login' => 'Alarm monitoring', //Free text
						'user_id' => 0, //0 is required for the user_login to be taken into account
						'message' => 'This is 1st entry as an <b>HTML</b> formatted<br>text',
					),
					1 => array(
						'date' => '2001-02-02 00:00:00', //If ommitted set automatically.
						'user_login' => 'Alarm monitoring', //user=id=0 is missing so will be ignored
						'message' => 'Second entry in text format:
with new line, but format not specified, so treated as HTML!, user_id=0 missing, so user_login ignored',
					),
				),
			),
		),
	),
	// Add a Text entry in the HTML CaseLog of the UserRequest with id=1, setting date and user (optional)
	array(
		'operation' => 'core/update', // operation code
		'comment' => 'Synchronization from Alarm Monitoring', // comment recorded in the change tracking log
		'class' => 'UserRequest',
		'key' => 1, // object id or OQL
		'output_fields' => 'id, friendlyname, title', // list of fields to show in the results (* or a,b,c)
		// Example of adding an entry into a CaseLog on an existing UserRequest
		'fields' => array(
			'public_log' => array(
			'add_item' => array(
				'user_login' => 'New Entry', //Free text
				'user_id' => 0, //0 is required for the user_login to be taken into account
				'format' => 'text', //If ommitted, source is expected to be HTML
				'message' => 'This text is not HTML formatted with 3 lines:
new line
3rd and last line',
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
$aOperations = array(
	array(
		'operation' => 'core/create', // operation code
		'comment' => 'Automatic creation of attachment blah blah...', // comment recorded in the change tracking log
		'class' => 'Attachment',
		'output_fields' => 'id, friendlyname', // list of fields to show in the results (* or a,b,c)
		// Values for the object to create
		'fields' => array(
			'item_class' => 'UserRequest',
			'item_id' => 1,
			'item_org_id' => 3,
			'contents' => array(
				'data' => 'iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAIAAAC0tAIdAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAACmSURBVChTfZHRDYMwDESzQ2fqhHx3C3ao+MkW/WlnaFxfzk7sEnE6JHJ+NgaKZN2zLHVN2ssfkae0Da7FQ5PRk/ve4Hcx19Ie6CEGuh/6vMgNhwanHVUNbt73lUDbYJ+6pg8b3+m2RehsVPdMXyvQY+OVkB+Rrv64lUjb3nq+aCA6v4leRqtfaIgimr53atBy9PlfUhoh3fFCNDmErv9FWR6ylBL5AREbmHBnFj5lAAAAAElFTkSuQmCC',
				'filename' => 'myself.png',
				'mimetype' => 'image/png'
			),
		),
	),
	array(
		'operation' => 'core/get', // operation code
		'class' => 'Attachment',
		'key' => 'SELECT Attachment',
		'output_fields' => '*',
	)
);
$aOperations = array(
	array(
		'operation' => 'core/update', // operation code
		'comment' => 'Synchronization from blah...', // comment recorded in the change tracking log
		'class' => 'Server',
		'key' => 'SELECT Server WHERE name="Server1"',
		'output_fields' => 'id, friendlyname, description', // list of fields to show in the results (* or a,b,c)
		// Values for the object to create
		'fields' => array(
			'description' => 'Issue #'.time(),
		),
	),
);
$aOperations = array(
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
);
$aXXXOperations = array(
	array(
		'operation' => 'core/check_credentials', // operation code
		'user' => 'admin',
		'password' => 'admin',
	),
);
$aDeleteOperations = array(
	array(
		'operation' => 'core/delete', // operation code
		'comment' => 'Cleanup for synchro with...', // comment recorded in the change tracking log
		'class' => 'Server',
		'key' => 'SELECT Server',
		'simulate' => false,
	),
);

if (false)
{
	echo "Please edit the sample script and configure the server URL";
	exit;
}
else
{
	$sUrl = "https://localhost/itop/webservices/rest.php?version=1.3";
}

$aData = array();
$aData['auth_user'] = 'rest';
$aData['auth_pwd'] = 'rest';


foreach ($aOperations as $iOp => $aOperation)
{
	echo "======================================\n";
	echo "Operation #$iOp: ".$aOperation['operation']."\n";
	$aData['json_data'] = json_encode($aOperation);

	echo "--------------------------------------\n";
	echo "Input:\n";
	print_r($aOperation);
	$aResults = null;
	try
	{
		$response = DoPostRequest($sUrl, $aData);
		$aResults = json_decode($response);
	}
	catch (Exception $e)
	{
		$response = $e->getMessage();
	}
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

