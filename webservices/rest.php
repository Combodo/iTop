<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

use Combodo\iTop\Application\WebPage\JsonPage;
use Combodo\iTop\Application\WebPage\JsonPPage;

require_once(__DIR__.'/../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/loginwebpage.class.inc.php');
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
			'extension' => $sServiceProviderClass,
		);
	}
}

if (!function_exists('json_last_error_msg')) {
	function json_last_error_msg() {
		static $ERRORS = array(
			JSON_ERROR_NONE => 'No error',
			JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
			JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
			JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
			JSON_ERROR_SYNTAX => 'Syntax error',
			JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
		);

		$error = json_last_error();
		return isset($ERRORS[$error]) ? $ERRORS[$error] : 'Unknown error';
	}
}

////////////////////////////////////////////////////////////////////////////////
//
// Main
//
$oCtx = new ContextTag(ContextTag::TAG_REST);

$sVersion = utils::ReadParam('version', null, false, 'raw_data');
$sOperation = utils::ReadParam('operation', null);

//read json_data parameter via as a string (standard behaviour)
$sJsonString = utils::ReadParam('json_data', null, false, 'raw_data');

if (empty($sJsonString)){
	//N °3455: read json_data parameter via a file passed by http protocol
	if(isset($_FILES['json_data']['tmp_name']))
	{
		$sTmpFilePath = $_FILES['json_data']['tmp_name'];
		if (is_file($sTmpFilePath)){
			$sValue = file_get_contents($sTmpFilePath);
			unlink($sTmpFilePath);
			if (! empty($sValue)){
				$sJsonString = utils::Sanitize($sValue, null, 'raw_data');
			}
		}
	}
}

$sProvider = '';

$oKPI = new ExecutionKPI();
try
{
	utils::UseParamFile();
        
	$oKPI->ComputeAndReport('Data model loaded');

    // N°6358 - force credentials for REST calls
    LoginWebPage::ResetSession(true);
	$iRet = LoginWebPage::DoLogin(false, false, LoginWebPage::EXIT_RETURN);
    $oKPI->ComputeAndReport('User login');

    if ($iRet == LoginWebPage::EXIT_CODE_OK)
	{
		// Extra validation of the profile
		if ((MetaModel::GetConfig()->Get('secure_rest_services') == true) && !UserRights::HasProfile('REST Services User'))
		{
			// Web services access is limited to the users with the profile REST Web Services
			$iRet = LoginWebPage::EXIT_CODE_NOTAUTHORIZED;
		}
	}
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
				
			case LoginWebPage::EXIT_CODE_NOTAUTHORIZED:
			throw new Exception("This user is not authorized to use the web services. (The profile REST Services User is required to access the REST web services)", RestResult::UNAUTHORIZED);
			break;
				
			default:
			throw new Exception("Unknown authentication error (retCode=$iRet)", RestResult::UNAUTHORIZED);
		}
	}

	if ($sVersion == null)
	{
		throw new Exception("Missing parameter 'version' (e.g. '1.0')", RestResult::MISSING_VERSION);
	}

	if ($sJsonString == null)
	{
		throw new Exception("Missing parameter 'json_data'", RestResult::MISSING_JSON);
	}

	if (is_string($sJsonString))
	{
        $aJsonData = @json_decode($sJsonString);
    }
	elseif(is_array($sJsonString))
    {
        $aJsonData = (object) $sJsonString;
        $sJsonString = json_encode($aJsonData);
    }
	else
    {
        $aJsonData = null;
    }

    if ($aJsonData == null)
    {
        throw new Exception('Parameter json_data is not a valid JSON structure', RestResult::INVALID_JSON);
    }

	$oKPI->ComputeAndReport('Parameters validated');


	/** @var iRestServiceProvider[] $aProviders */
	$oKPI = new ExecutionKPI();
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
	/** @var iRestServiceProvider $oRestSP */
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
	$oKPI->ComputeAndReport('iRestServiceProvider loaded with operations');

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
		/** @var iRestServiceProvider $oRS */
		$oRS = $aOpToRestService[$sOperation]['service_provider'];
		$sProvider = get_class($oRS);
	
		CMDBObject::SetTrackOrigin('webservice-rest');
		$oResult = $oRS->ExecOperation($sVersion, $sOperation, $aJsonData);
	}
	$oKPI->ComputeAndReport('Operation finished');
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
	$oKPI->ComputeAndReport('Exception catched');
}

// Output the results
//
$sResponse = json_encode($oResult);

if ($sResponse === false)
{
	$oJsonIssue = new RestResult();
	$oJsonIssue->code = RestResult::INTERNAL_ERROR;
	$oJsonIssue->message = 'json encoding failed with message: '.json_last_error_msg().'. Full response structure for debugging purposes (print_r+bin2hex): '.bin2hex(print_r($oResult, true));
	$sResponse = json_encode($oJsonIssue);
}


$sCallback = utils::ReadParam('callback', null);
if ($sCallback == null)
{
	$oP = new JsonPage();
}
else
{
	$oP = new JsonPPage($sCallback);
}
$oP->add_header('Access-Control-Allow-Origin: *');
$oP->SetData(json_decode($sResponse, true));
$oP->SetOutputDataOnly(true);
$oP->Output();

// Log usage
//
if (MetaModel::GetConfig()->Get('log_rest_service'))
{
	$oLog = new EventRestService();
	$oLog->SetTrim('userinfo', UserRights::GetUser());
	$oLog->Set('version', $sVersion);
	$oLog->Set('operation', $sOperation);
	$oLog->SetTrim('json_input', $sJsonString);

	$oLog->Set('provider', $sProvider);
	$sMessage = $oResult->message;
	if (empty($oResult->message))
	{
		$sMessage = 'Ok';
	}
	$oLog->SetTrim('message', $sMessage);
	$oLog->Set('code', $oResult->code);
	$oLog->SetTrim('json_output', $sResponse);

	$oLog->DBInsertNoReload();
}