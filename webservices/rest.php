<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * Entry point for all the REST services
 *
 * -------------------------------------------------- 
 * Create an object
 * -------------------------------------------------- 
 * POST itop/webservices/rest.php
 * {
 * 	operation: 'object_create',
 * 	comment: 'Synchronization from blah...',
 * 	class: 'UserRequest',
 * 	results: 'id, friendlyname',
 * 	fields:
 * 	{
 * 		org_id: 'SELECT Organization WHERE name = "Demo"',
 * 		caller_id:
 * 		{
 * 			name: 'monet',
 * 			first_name: 'claude',
 * 		}
 * 		title: 'Houston, got a problem!',
 * 		description: 'The fridge is empty'
 * 		contacts_list:
 * 		[
 * 			{
 * 				role: 'pizza delivery',
 * 				contact_id:
 * 				{
 * 					finalclass: 'Person',
 * 					name: 'monet',
 * 					first_name: 'claude'
 * 				}
 * 			}
 * 		]
 * 	}
 * }
 *
 *
 * @copyright   Copyright (C) 2010-2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
require_once(APPROOT.'/application/ajaxwebpage.class.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

require_once(APPROOT.'core/restservices.class.inc.php');


/**
 * Result structure that is specific to the hardcoded verb 'list_operations'
 */ 
class RestResultListOperations extends RestResult
{
	public $version;
	public $operations;

	public function AddOperation($sVerb, $sDescription, $sServiceProviderClass)
	{
		$this->operations[] = array(
			'verb' => $sVerb,
			'description' => $sDescription,
			'extension' => $sServiceProviderClass
		);
	}
}

////////////////////////////////////////////////////////////////////////////////
//
// Main
//
$oP = new ajax_page('rest');

try
{
	utils::UseParamFile();

	$iRet = LoginWebPage::DoLogin(false, true, LoginWebPage::EXIT_RETURN);
	if ($iRet != LoginWebPage::EXIT_CODE_OK)
	{
		switch($iRet)
		{
			case LoginWebPage::EXIT_CODE_MISSINGLOGIN:
			throw new Exception("Missing parameter 'auth_user'", RestResult::MISSING_AUTH_USER);
			break;
			
			case LoginWebPage::EXIT_CODE_MISSINGPASSWORD:
			throw new Exception("Missing parameter 'auth_pwd'", RestResult::MISSING_AUTH_PWD);
			break;
			
			case LoginWebPage::EXIT_CODE_WRONGCREDENTIALS:
			throw new Exception("Invalid login", RestResult::UNAUTHORIZED);
			break;
			
			case LoginWebPage::EXIT_CODE_PORTALUSERNOTAUTHORIZED:
			throw new Exception("Portal user is not allowed", RestResult::UNAUTHORIZED);
			break;
			
			default:
			throw new Exception("Unknown authentication error (retCode=$iRet)", RestResult::UNAUTHORIZED);
		}
	}

	$sVersion = utils::ReadParam('version', null, false, 'raw_data');
	if ($sVersion == null)
	{
		throw new Exception("Missing parameter 'version' (e.g. '1.0')", RestResult::MISSING_VERSION);
	}
	
	$sJsonString = utils::ReadParam('json_data', null, false, 'raw_data');
	if ($sJsonString == null)
	{
		throw new Exception("Missing parameter 'json_data", RestResult::MISSING_JSON);
	}
	$aJsonData = @json_decode($sJsonString);
	if ($aJsonData == null)
	{
		throw new Exception("Parameter json_data is not a valid JSON structure", RestResult::INVALID_JSON);
	}


	$aProviders = array();
	foreach(get_declared_classes() as $sPHPClass)
	{
		$oRefClass = new ReflectionClass($sPHPClass);
		if ($oRefClass->implementsInterface('iRestServiceProvider'))
		{
			$aProviders[] = new $sPHPClass;
		}
	}

	$aOpToRestService = array(); // verb => $oRestServiceProvider
	foreach ($aProviders as $oRestSP)
	{
		$aOperations = $oRestSP->ListOperations($sVersion);
		foreach ($aOperations as $aOpData)
		{
			$aOpToRestService[$aOpData['verb']] = array
			(
				'service_provider' => $oRestSP,
				'description' => $aOpData['description'],
			);
		}
	}

	if (count($aOpToRestService) == 0)
	{
		throw new Exception("There is no service available for version '$sVersion'", RestResult::UNSUPPORTED_VERSION);
	}


	$sOperation = RestUtils::GetMandatoryParam($aJsonData, 'operation');
	if ($sOperation == 'list_operations')
	{
		$oResult = new RestResultListOperations();
		$oResult->message = "Operations: ".count($aOpToRestService);
		$oResult->version = $sVersion;
		foreach ($aOpToRestService as $sVerb => $aOpData)
		{
			$oResult->AddOperation($sVerb, $aOpData['description'], get_class($aOpData['service_provider']));
		}
	}
	else
	{
		if (!array_key_exists($sOperation, $aOpToRestService))
		{
			throw new Exception("Unknown verb '$sOperation' in version '$sVersion'", RestResult::UNKNOWN_OPERATION);
		}
		$oRS = $aOpToRestService[$sOperation]['service_provider'];
	
		CMDBObject::SetTrackOrigin('webservice-rest');
		$oResult = $oRS->ExecOperation($sVersion, $sOperation, $aJsonData);
	}
}
catch(Exception $e)
{
	$oResult = new RestResult();
	if ($e->GetCode() == 0)
	{
		$oResult->code = RestResult::INTERNAL_ERROR;
	}
	else
	{
		$oResult->code = $e->GetCode();
	}
	$oResult->message = "Error: ".$e->GetMessage();
}

// Output the results
//
$oP->add_header('Access-Control-Allow-Origin: *');

$sCallback = utils::ReadParam('callback', null);
if ($sCallback == null)
{
	$oP->SetContentType('application/json');
	$oP->add(json_encode($oResult));
}
else
{
	$oP->SetContentType('application/javascript');
	$oP->add($sCallback.'('.json_encode($oResult).')');
}
$oP->Output();
?>