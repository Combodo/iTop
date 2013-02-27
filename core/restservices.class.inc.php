<?php
// Copyright (C) 2013 Combodo SARL
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
 * REST/json services
 * 
 * Definition of common structures + the very minimum service provider (manage objects)
 *
 * @package     REST Services
 * @copyright   Copyright (C) 2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 * @api
 */

/**
 * Element of the response formed by RestResultWithObjects
 *
 * @package     REST Services
 */
class ObjectResult
{
	public $code;
	public $message;
	public $fields;
	
	/**
	 * Default constructor
	 */
	public function __construct()
	{
		$this->code = RestResult::OK;
		$this->message = '';
		$this->fields = array();
	}

	/**
	 * Helper to make an output value for a given attribute
	 * 	 
	 * @param DBObject $oObject The object being reported
	 * @param string $sAttCode The attribute code (must be valid)
	 * @return string A scalar representation of the value
	 */
	protected function MakeResultValue(DBObject $oObject, $sAttCode)
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
			elseif ($oAttDef->IsExternalKey())
			{
				$value = $oObject->Get($sAttCode);
			}
			else
			{
				// Still to be refined...
				$value = $oObject->GetEditValue($sAttCode);
			}
		}
		return $value;
	}

	/**
	 * Report the value for the given object attribute
	 * 	 
	 * @param DBObject $oObject The object being reported
	 * @param string $sAttCode The attribute code (must be valid)
	 * @return void
	 */
	public function AddField(DBObject $oObject, $sAttCode)
	{
		$this->fields[$sAttCode] = $this->MakeResultValue($oObject, $sAttCode);
	}
}



/**
 * REST response for services managing objects. Derive this structure to add information and/or constants
 *
 * @package     Extensibility
 * @package     REST Services
 * @api
 */
class RestResultWithObjects extends RestResult
{
	public $objects;

	/**
	 * Report the given object
	 * 	 
	 * @param int An error code (RestResult::OK is no issue has been found)
	 * @param string $sMessage Description of the error if any, an empty string otherwise
	 * @param DBObject $oObject The object being reported
	 * @param array $aFields An array of attribute codes. List of the attributes to be reported.
	 * @return void
	 */
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


/**
 * Implementation of core REST services (create/get/update... objects)
 *
 * @package     Core
 */
class CoreServices implements iRestServiceProvider
{
	/**
	 * Enumerate services delivered by this class
	 * 	 
	 * @param string $sVersion The version (e.g. 1.0) supported by the services
	 * @return array An array of hash 'verb' => verb, 'description' => description
	 */
	public function ListOperations($sVersion)
	{
		$aOps = array();
		if ($sVersion == '1.0')
		{
			$aOps[] = array(
				'verb' => 'core/create',
				'description' => 'Create an object'
			);
			$aOps[] = array(
				'verb' => 'core/update',
				'description' => 'Update an object'
			);
			$aOps[] = array(
				'verb' => 'core/apply_stimulus',
				'description' => 'Apply a stimulus to change the state of an object'
			);
			$aOps[] = array(
				'verb' => 'core/get',
				'description' => 'Search for objects'
			);
		}
		return $aOps;
	}

	/**
	 * Enumerate services delivered by this class
	 * @param string $sVersion The version (e.g. 1.0) supported by the services
	 * @return RestResult The standardized result structure (at least a message)
	 * @throws Exception in case of internal failure.	 
	 */
	public function ExecOperation($sVersion, $sVerb, $aParams)
	{
		$oResult = new RestResultWithObjects();
		switch ($sVerb)
		{
		case 'core/create':
			RestUtils::InitTrackingComment($aParams);
			$sClass = RestUtils::GetClass($aParams, 'class');
			$aFields = RestUtils::GetMandatoryParam($aParams, 'fields');
			$aShowFields = RestUtils::GetFieldList($sClass, $aParams, 'output_fields');
	
			$oObject = RestUtils::MakeObjectFromFields($sClass, $aFields);
			$oObject->DBInsert();
	
			$oResult->AddObject(0, 'created', $oObject, $aShowFields);
			break;
	
		case 'core/update':
			RestUtils::InitTrackingComment($aParams);
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$aFields = RestUtils::GetMandatoryParam($aParams, 'fields');
			$aShowFields = RestUtils::GetFieldList($sClass, $aParams, 'output_fields');
	
			$oObject = RestUtils::FindObjectFromKey($sClass, $key);
			RestUtils::UpdateObjectFromFields($oObject, $aFields);
			$oObject->DBUpdate();
	
			$oResult->AddObject(0, 'updated', $oObject, $aShowFields);
			break;
	
		case 'core/apply_stimulus':
			RestUtils::InitTrackingComment($aParams);
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$aFields = RestUtils::GetMandatoryParam($aParams, 'fields');
			$aShowFields = RestUtils::GetFieldList($sClass, $aParams, 'output_fields');
			$sStimulus = RestUtils::GetMandatoryParam($aParams, 'stimulus');
	
			$oObject = RestUtils::FindObjectFromKey($sClass, $key);
			RestUtils::UpdateObjectFromFields($oObject, $aFields);
		
			$aTransitions = $oObject->EnumTransitions();
			$aStimuli = MetaModel::EnumStimuli(get_class($oObject));
			if (!isset($aTransitions[$sStimulus]))
			{
				// Invalid stimulus
				$oResult->code = RestResult::INTERNAL_ERROR;
				$oResult->message = "Invalid stimulus: '$sStimulus' on the object ".$oObject->GetName()." in state '".$oObject->GetState()."'";
			}
			else
			{
				$aTransition = $aTransitions[$sStimulus];
				$sTargetState = $aTransition['target_state'];
				$aStates = MetaModel::EnumStates($sClass);
				$aTargetStateDef = $aStates[$sTargetState];
				$aExpectedAttributes = $aTargetStateDef['attribute_list'];
				
				$aMissingMandatory = array();
				foreach($aExpectedAttributes as $sAttCode => $iExpectCode)
				{
					if ( ($iExpectCode & OPT_ATT_MANDATORY) && ($oObject->Get($sAttCode) == ''))
					{
						$aMissingMandatory[] = $sAttCode;
					}
				}				
				if (count($aMissingMandatory) == 0)
				{
					// If all the mandatory fields are already present, just apply the transition silently...
					if ($oObject->ApplyStimulus($sStimulus))
					{
						$oObject->DBUpdate();
						$oResult->AddObject(0, 'updated', $oObject, $aShowFields);
					}
				}
				else
				{
					// Missing mandatory attributes for the transition
					$oResult->code = RestResult::INTERNAL_ERROR;
					$oResult->message = 'Missing mandatory attribute(s) for applying the stimulus: '.implode(', ', $aMissingMandatory).'.';
				}
			}	
			break;
	
			case 'core/get':
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$aShowFields = RestUtils::GetFieldList($sClass, $aParams, 'output_fields');
	
			$oObjectSet = RestUtils::GetObjectSetFromKey($sClass, $key);
			while ($oObject = $oObjectSet->Fetch())
			{
				$oResult->AddObject(0, '', $oObject, $aShowFields);
			}
			$oResult->message = "Found: ".$oObjectSet->Count();
			break;
	
		default:
			// unknown operation: handled at a higher level
		}
		return $oResult;
	}
}
