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
require_once(APPROOT.'/application/clipage.class.inc.php');
require_once(APPROOT.'/application/startup.inc.php');



class RestServices
{
	public function InitTrackingComment($oData)
	{
		$sComment = $this->GetMandatoryParam($oData, 'comment');
		CMDBObject::SetTrackInfo($sComment);
	}


	public function GetMandatoryParam($oData, $sParamName)
	{
		if (isset($oData->$sParamName))
		{
			return $oData->$sParamName;
		}
		else
		{
			throw new Exception("Missing parameter '$sParamName'");
		}
	}


	public function GetOptionalParam($oData, $sParamName, $default)
	{
		if (isset($oData->$sParamName))
		{
			return $oData->$sParamName;
		}
		else
		{
			return $default;
		}
	}


	public function GetClass($oData, $sParamName)
	{
		$sClass = $this->GetMandatoryParam($oData, $sParamName);
		if (!MetaModel::IsValidClass($sClass))
		{
			throw new Exception("$sParamName: '$sClass' is not a valid class'");
		}
		return $sClass;
	}


	public function GetFieldList($sClass, $oData, $sParamName)
	{
		$sFields = $this->GetOptionalParam($oData, $sParamName, '*');
		$aShowFields = array();
		if ($sFields == '*')
		{
			foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				$aShowFields[] = $sAttCode;
			}
		}
		else
		{
			foreach(explode(',', $sFields) as $sAttCode)
			{
				$sAttCode = trim($sAttCode);
				if (($sAttCode != 'id') && (!MetaModel::IsValidAttCode($sClass, $sAttCode)))
				{
					throw new Exception("$sParamName: invalid attribute code '$sAttCode'");
				}
				$aShowFields[] = $sAttCode;
			}
		}
		return $aShowFields;
	}

	protected function FindObjectFromCriteria($sClass, $oCriteria)
	{
		$aCriteriaReport = array();
		if (isset($oCriteria->finalclass))
		{
			$sClass = $oCriteria->finalclass;
			if (!MetaModel::IsValidClass($sClass))
			{
				throw new Exception("finalclass: Unknown class '$sClass'");
			}
		}
		$oSearch = new DBObjectSearch($sClass);
		foreach ($oCriteria as $sAttCode => $value)
		{
			$realValue = $this->MakeValue($sClass, $sAttCode, $value);
			$oSearch->AddCondition($sAttCode, $realValue);
			$aCriteriaReport[] = "$sAttCode: $value ($realValue)";
		}
		$oSet = new DBObjectSet($oSearch);
		$iCount = $oSet->Count();
		if ($iCount == 0)
		{
			throw new Exception("No item found for criteria: ".implode(', ', $aCriteriaReport));
		}
		elseif ($iCount > 1)
		{
			throw new Exception("Several items found ($iCount) for criteria: ".implode(', ', $aCriteriaReport));
		}
		$res = $oSet->Fetch();
		return $res;
	}


	public function FindObjectFromKey($sClass, $key)
	{
		if (is_object($key))
		{
			$res = $this->FindObjectFromCriteria($sClass, $key);
		}
		elseif (is_numeric($key))
		{
			$res = MetaModel::GetObject($sClass, $key);
		}
		elseif (is_string($key))
		{
			// OQL
			$oSearch = DBObjectSearch::FromOQL($key);
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			if ($iCount == 0)
			{
				throw new Exception("No item found for query: $key");
			}
			elseif ($iCount > 1)
			{
				throw new Exception("Several items found ($iCount) for query: $key");
			}
			$res = $oSet->Fetch();
		}
		else
		{
			throw new Exception("Wrong format for key");
		}
		return $res;
	}


	public function GetObjectSetFromKey($sClass, $key)
	{
		if (is_object($key))
		{
			if (isset($oCriteria->finalclass))
			{
				$sClass = $oCriteria->finalclass;
				if (!MetaModel::IsValidClass($sClass))
				{
					throw new Exception("finalclass: Unknown class '$sClass'");
				}
			}
		
			$oSearch = new DBObjectSearch($sClass);
			foreach ($key as $sAttCode => $value)
			{
				$realValue = $this->MakeValue($sClass, $sAttCode, $value);
				$oSearch->AddCondition($sAttCode, $realValue);
			}
		}
		elseif (is_numeric($key))
		{
			$oSearch = new DBObjectSearch($sClass);
			$oSearch->AddCondition('id', $key);
		}
		elseif (is_string($key))
		{
			// OQL
			$oSearch = DBObjectSearch::FromOQL($key);
			$oObjectSet = new DBObjectSet($oSearch);
		}
		else
		{
			throw new Exception("Wrong format for key");
		}
		$oObjectSet = new DBObjectSet($oSearch);
		return $oObjectSet;
	}


	protected function MakeValue($sClass, $sAttCode, $value)
	{
		try
		{
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode))
			{
				throw new Exception("Unknown attribute");
			}
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			if ($oAttDef instanceof AttributeExternalKey)
			{
				$oExtKeyObject = $this->FindObjectFromKey($oAttDef->GetTargetClass(), $value);
				$value = $oExtKeyObject->GetKey();
			}
			elseif ($oAttDef instanceof AttributeLinkedSet)
			{
				if (!is_array($value))
				{
					throw new Exception("A link set must be defined by an array of objects");
				}
				$sLnkClass = $oAttDef->GetLinkedClass();
				$aLinks = array();
				foreach($value as $oValues)
				{
					$oLnk = $this->MakeObjectFromFields($sLnkClass, $oValues);
					$aLinks[] = $oLnk;
				}
				$value = DBObjectSet::FromArray($sLnkClass, $aLinks);
			}
		}
		catch (Exception $e)
		{
			throw new Exception("$sAttCode: ".$e->getMessage());
		}
		return $value;
	}


	public function MakeObjectFromFields($sClass, $aFields)
	{
		$oObject = MetaModel::NewObject($sClass);
		foreach ($aFields as $sAttCode => $value)
		{
			$realValue = $this->MakeValue($sClass, $sAttCode, $value);
			$oObject->Set($sAttCode, $realValue);
		}
		return $oObject;
	}


	public function UpdateObjectFromFields($oObject, $aFields)
	{
		$sClass = get_class($oObject);
		foreach ($aFields as $sAttCode => $value)
		{
			$realValue = $this->MakeValue($sClass, $sAttCode, $value);
			$oObject->Set($sAttCode, $realValue);
		}
		return $oObject;
	}
}

class FieldResult
{
	protected $value;
	
	public function __construct()
	{
	}

	public function GetValue()
	{
	}
}

class ObjectResult
{
	public $code;
	public $message;
	public $fields;
	
	public function __construct()
	{
		$this->code = 0;
		$this->message = '';
		$this->fields = array();
	}

	protected function MakeResultValue($oObject, $sAttCode)
	{
		if ($sAttCode == 'id')
		{
			$value = $oObject->GetKey();
		}
		else
		{
			$sClass = get_class($oObject);
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			if ($oAttDef instanceof AttributeLinkedSet)
			{
				$value = array();

				// Make the list of required attributes
				// - Skip attributes pointing to the current object (redundant data)
				// - Skip link sets refering to the current data (infinite recursion!)
				$aRelevantAttributes = array();
				$sLnkClass = $oAttDef->GetLinkedClass();
				foreach (MetaModel::ListAttributeDefs($sLnkClass) as $sLnkAttCode => $oLnkAttDef)
				{
					// Skip any attribute of the link that points to the current object
					//
					if ($sLnkAttCode == $oAttDef->GetExtKeyToMe()) continue;
					if (method_exists($oLnkAttDef, 'GetKeyAttCode'))
					{
						if ($oLnkAttDef->GetKeyAttCode() ==$oAttDef->GetExtKeyToMe()) continue;
					}

					$aRelevantAttributes[] = $sLnkAttCode;
				}

				// Iterate on the set and build an array of array of attcode=>value
				$oSet = $oObject->Get($sAttCode);
				while ($oLnk = $oSet->Fetch())
				{
					$aLnkValues = array();
					foreach ($aRelevantAttributes as $sLnkAttCode)
					{
						$aLnkValues[$sLnkAttCode] = $this->MakeResultValue($oLnk, $sLnkAttCode);
					}
					$value[] = $aLnkValues;
				}
			}
			else
			{
				$value = $oObject->GetEditValue($sAttCode);
			}
		}
		return $value;
	}

	public function AddField($oObject, $sAttCode)
	{
		$this->fields[$sAttCode] = $this->MakeResultValue($oObject, $sAttCode);
	}
}

class RestResult
{
	public function __construct()
	{
	}

	public $code;
	public $message;
	public $objects;

	public function AddObject($iCode, $sMessage, $oObject = null, $aFields = null)
	{
		$oObjRes = new ObjectResult();
		$oObjRes->code = $iCode;
		$oObjRes->message = $sMessage;

		if ($oObject)
		{
			foreach ($aFields as $sAttCode)
			{
				$oObjRes->AddField($oObject, $sAttCode);
			}
		}

		$this->objects[] = $oObjRes;
	}
}



////////////////////////////////////////////////////////////////////////////////
//
// Main
//
$oP = new CLIPage("iTop - REST");
$oResult = new RestResult();

try
{
	utils::UseParamFile();

	$sAuthUser = utils::ReadPostedParam('auth_user', null, 'raw_data');
	$sAuthPwd = utils::ReadPostedParam('auth_pwd', null, 'raw_data');
	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
	{
		UserRights::Login($sAuthUser); // Login & set the user's language
	}
	else
	{
		throw new Exception("Invalid login '$sAuthUser'");
	}
	
	$aJsonData = json_decode(utils::ReadPostedParam('json_data', null, 'raw_data'));
	if ($aJsonData == null)
	{
		throw new Exception('Parameter json_data is not a valid JSON structure');
	}

	$oRS = new RestServices();

	$sOperation = $oRS->GetMandatoryParam($aJsonData, 'operation');
	switch ($sOperation)
	{
	case 'object_create':
		$oRS->InitTrackingComment($aJsonData);
		$sClass = $oRS->GetClass($aJsonData, 'class');
		$aFields = $oRS->GetMandatoryParam($aJsonData, 'fields');
		$aShowFields = $oRS->GetFieldList($sClass, $aJsonData, 'results');

		$oObject = $oRS->MakeObjectFromFields($sClass, $aFields);
		$oObject->DBInsert();

		$oResult->AddObject(0, 'created', $oObject, $aShowFields);
		break;

	case 'object_update':
		$oRS->InitTrackingComment($aJsonData);
		$sClass = $oRS->GetClass($aJsonData, 'class');
		$key = $oRS->GetMandatoryParam($aJsonData, 'key');
		$aFields = $oRS->GetMandatoryParam($aJsonData, 'fields');
		$aShowFields = $oRS->GetFieldList($sClass, $aJsonData, 'results');

		$oObject = $oRS->FindObjectFromKey($sClass, $key);
		$oRS->UpdateObjectFromFields($oObject, $aFields);
		$oObject->DBUpdate();

		$oResult->AddObject(0, 'updated', $oObject, $aShowFields);
		break;

	case 'object_get':
		$sClass = $oRS->GetClass($aJsonData, 'class');
		$key = $oRS->GetMandatoryParam($aJsonData, 'key');
		$aShowFields = $oRS->GetFieldList($sClass, $aJsonData, 'results');

		$oObjectSet = $oRS->GetObjectSetFromKey($sClass, $key);
		while ($oObject = $oObjectSet->Fetch())
		{
			$oResult->AddObject(0, '', $oObject, $aShowFields);
		}
		$oResult->message = "Found: ".$oObjectSet->Count();
		break;

	default:
		throw new Exception("Uknown operation '$sOperation'");
	}
}
catch(Exception $e)
{
	$oResult->code = 1234;
	$oResult->message = "Error: ".$e->GetMessage();
}

$oP->add(json_encode($oResult));
$oP->Output();
?>