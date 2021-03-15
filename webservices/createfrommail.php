<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

// Some PEAR includes that are required for reading emails
include 'Net/POP3.php';
include 'Mail/mimeDecode.php';

// Parameters to access your mailbox
define('MAILBOX_SERVER', 'pop3.combodo.com'); // Replace with the IP or FQDN name of your POP3 server
define('MAILBOX_SERVER_PORT', 110); // 110 is the default for POP3
define('MAILBOX_ACCOUNT', 'test@combodo.com'); // You mailbox account
define('MAILBOX_PASSWORD', 'combodo'); // Password for this mailbox

// Default settings for the ticket creation
define('DEFAULT_IMPACT', '2');
define('DEFAULT_URGENCY', '2');
define('DEFAULT_SERVICE_ID', 2);
define('DEFAULT_SUBSERVICE_ID', 12);
define('DEFAULT_PRODUCT', 'Request via eMail');
define('DEFAULT_WORKGROUP_ID', 5);

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

function GetSender($aHeaders)
{
	$aResult = array('name' => '', 'email' => '');
	$aResult['name'] = $aHeaders['From'];
	$aMatches = array();
	if (preg_match('/\(([0-9a-zA-Z\._]+)@(.+)@(.+)\)/U', array_pop($aHeaders['Received']), $aMatches))
	{
		$aResult['email'] = $aMatches[1].'@'.$aMatches[2];		
	}
	return $aResult;
}

/**
 * Create a User Request ticket from the basic information retrieved from an email
 * @param string $sSenderEmail eMail address of the sender (From), used to lookup a contact in iTop
 * @param string $sSubject eMail's subject, will be turned into the title of the ticket
 * @param string $sBody Body of the email, will be fitted into the ticket's description
 * @return UserRequest The created ticket, or  null if the creation failed for some reason...
 */
function CreateTicket($sSenderEmail, $sSubject, $sBody)
{
	$oTicket = null;
	try
	{
		$oContactSearch = new DBObjectSearch('Contact'); // Can be either a Person or a Team, but must be a valid Contact
		$oContactSearch->AddCondition('email', $sSenderEmail, '=');
		$oSet = new DBObjectSet($oContactSearch);
		if ($oSet->Count() == 1)
		{
			$oContact = $oSet->Fetch();
			$oOrganization = MetaModel::GetObject('Organization', $oContact->Get('org_id'));
			$oTicket = new UserRequest;
			$oTicket->Set('title', $sSubject);
			$oTicket->Set('description', $sBody);
			$oTicket->Set('org_id', $oOrganization->GetKey());
			$oTicket->Set('caller_id', $oContact->GetKey());
			$oTicket->Set('impact', DEFAULT_IMPACT);
			$oTicket->Set('urgency', DEFAULT_URGENCY);
			$oTicket->Set('product', DEFAULT_PRODUCT);
			$oTicket->Set('service_id', DEFAULT_SERVICE_ID); //  Can be replaced by a search for a valid service for this 'org_id'
			$oTicket->Set('servicesubcategory_id', DEFAULT_SUBSERVICE_ID); // Same as above...
			$oTicket->Set('workgroup_id', DEFAULT_WORKGROUP_ID); // Same as above...

			// Record the change information about the object
			$sUserString = $oContact->GetName().', submitted by email';
			CMDBObject::SetTrackInfo($sUserString);
			$oTicket->DBInsert();
		}
		else
		{
			echo "No contact found in iTop having the email: $sSenderEmail, email message ignored.\n";
		}
	}
	catch(Exception $e)
	{
		echo "Error: exception ".$e->getMessage();
		$oTicket = null;
	}
	return $oTicket;
}
/**
 * Main program
 */
// Connect to the POP3 server & open the mailbox
$oPop3 = new Net_POP3();
$oPop3->connect(MAILBOX_SERVER, MAILBOX_SERVER_PORT);
$oPop3->login(MAILBOX_ACCOUNT, MAILBOX_PASSWORD);

// Read all the messages from the mailbox and tries to create a new ticket for each one
// Note: it is expected that the sender of the email exists a valid contact as a 'Contact'
// in iTop (identified by her/his email address), otherwise the ticket creation will fail
$iNbMessages = $oPop3->numMsg();
for($index = 1; $index <= $iNbMessages; $index++)
{
	$params['include_bodies'] = true;
	$params['decode_bodies'] = true;
	$params['decode_headers'] = true;
	$params['crlf']		= "\r\n";
	$aHeaders = $oPop3->getParsedHeaders($index);
	$aSender = GetSender($aHeaders);
	$oDecoder = new Mail_mimeDecode( $oPop3->getRawHeaders($index).$params['crlf'].$oPop3->getBody($index) );  
	$oStructure = $oDecoder->decode($params);
	$sSubject = $aHeaders['Subject'];
	// Search for the text/plain body part
	$iPartIndex = 0;
	$bFound = false;
	$sTextBody = '';
	//echo "<pre>\n";
	//print_r($oStructure);
	//echo "</pre>\n";
	if (!isset($oStructure->parts) || count($oStructure->parts) == 0)
	{
		$sTextBody = $oStructure->body;
	}
	else
	{
		// Find the first "part" of the body which is in text/plain
		while( ($iPartIndex < count($oStructure->parts)) && (!$bFound) )
		{
			//echo "<p>Reading part $iPartIndex</p>\n";
			if ( ($oStructure->parts[$iPartIndex]->ctype_primary == 'text') &&
				 ($oStructure->parts[$iPartIndex]->ctype_secondary == 'plain') )
			{
				$sTextBody = $oStructure->parts[$iPartIndex]->body;
				$bFound = true;
				//echo "<p>Plain text found ! ($sTextBody)</p>\n";
			}
			$iPartIndex++;
		}
		// Try again but this time look for an HTML part
		if (!$bFound)
		{
			while( ($iPartIndex < count($oStructure->parts)) && (!$bFound) )
			{
				//echo "<p>Reading part $iPartIndex</p>\n";
				if ( ($oStructure->parts[$iPartIndex]->ctype_primary == 'text') &&
					 ($oStructure->parts[$iPartIndex]->ctype_secondary == 'html') )
				{
					$sTextBody = $oStructure->parts[$iPartIndex]->body;
					$bFound = true;
					//echo "<p>HTML text found ! (".htmlentities($sTextBody, ENT_QUOTES, 'UTF-8').")</p>\n";
				}
				$iPartIndex++;
			}
		}
	}

	// Bug: depending on the email, the email address could be found in :
	// email => 'john.foo@combodo.com'
	// name  => 'john foo <john.foo@combodo.com>

	$oTicket = CreateTicket($aSender['email'], $sSubject, $sTextBody);
	if ($oTicket != null)
	{
		// Ticket created, delete the email
		$oPop3->deleteMsg($index);
		echo "Ticket: ".$oTicket->GetName()." created.\n";	
	}
}
$oPop3->disconnect();
?> 
