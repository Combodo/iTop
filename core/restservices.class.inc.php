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
			$oObjRes->class = get_class($oObject);
			foreach ($aFields as $sAttCode)
			{
				$oObjRes->AddField($oObject, $sAttCode);
			}
		}

		$this->objects[] = $oObjRes;
	}
}

class RestResultWithRelations extends RestResultWithObjects
{
	public $relations;
	
	public function __construct()
	{
		parent::__construct();
		$this->relations = array();
	}
	
	public function AddRelation($sSrcKey, $sDestKey)
	{
		if (!array_key_exists($sSrcKey, $this->relations))
		{
			$this->relations[$sSrcKey] = array();
		}
		$this->relations[$sSrcKey][] = $sDestKey;
	}
}

/**
 * Deletion result codes for a target object (either deleted or updated)
 *
 * @package     Extensibility
 * @api
 * @since 2.0.1  
 */
class RestDelete
{
	/**
	 * Result: Object deleted as per the initial request
	 */
	const OK = 0;
	/**
	 * Result: general issue (user rights or ... ?) 
	 */
	const ISSUE = 1;
	/**
	 * Result: Must be deleted to preserve database integrity 
	 */
	const AUTO_DELETE = 2;
	/**
	 * Result: Must be deleted to preserve database integrity, but that is NOT possible 
	 */
	const AUTO_DELETE_ISSUE = 3;
	/**
	 * Result: Must be deleted to preserve database integrity, but this must be requested explicitely 
	 */
	const REQUEST_EXPLICITELY = 4;
	/**
	 * Result: Must be updated to preserve database integrity
	 */
	const AUTO_UPDATE = 5;
	/**
	 * Result: Must be updated to preserve database integrity, but that is NOT possible
	 */
	const AUTO_UPDATE_ISSUE = 6;
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
			$aOps[] = array(
				'verb' => 'core/delete',
				'description' => 'Delete objects'
			);
			$aOps[] = array(
				'verb' => 'core/get_related',
				'description' => 'Get related objects through the specified relation'
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

		case 'core/delete':
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$bSimulate = RestUtils::GetOptionalParam($aParams, 'simulate', false);
	
			$oObjectSet = RestUtils::GetObjectSetFromKey($sClass, $key);
			$aObjects = $oObjectSet->ToArray();
			$this->DeleteObjects($oResult, $aObjects, $bSimulate);
			break;

		case 'core/get_related':
			$oResult = new RestResultWithRelations();
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$sRelation = RestUtils::GetMandatoryParam($aParams, 'relation');
			$iMaxRecursionDepth = RestUtils::GetOptionalParam($aParams, 'depth', 20 /* = MAX_RECUSTION_DEPTH */);
			$aShowFields = RestUtils::GetFieldList($sClass, $aParams, 'output_fields');
	
			$oObjectSet = RestUtils::GetObjectSetFromKey($sClass, $key);
			while ($oObject = $oObjectSet->Fetch())
			{
				$aRelated = array();
				$aGraph = array();
				$oResult->AddObject(0, '', $oObject, $aShowFields);
				$this->GetRelatedObjects($oObject, $sRelation, $iMaxRecursionDepth, $aRelated, $aGraph);
	
				foreach($aRelated as $sClass => $aObjects)
				{
					foreach($aObjects as $oRelatedObj)
					{
						$oResult->AddObject(0, '', $oRelatedObj, $aShowFields);
					}				
				}
				foreach($aGraph as $sSrcKey => $sDestKey)
				{
					$oResult->AddRelation($sSrcKey, $sDestKey);
				}
			}		
			$oResult->message = "Found: ".$oObjectSet->Count();
			break;
			
		default:
			// unknown operation: handled at a higher level
		}
		return $oResult;
	}

	/**
	 * Helper for object deletion	
	 */
	public function DeleteObjects($oResult, $aObjects, $bSimulate)
	{
		$oDeletionPlan = new DeletionPlan();
		foreach($aObjects as $oObj)
		{
			if ($bSimulate)
			{
				$oObj->CheckToDelete($oDeletionPlan);
			}
			else
			{
				$oObj->DBDelete($oDeletionPlan);
			}
		}

		foreach ($oDeletionPlan->ListDeletes() as $sTargetClass => $aDeletes)
		{
			foreach ($aDeletes as $iId => $aData)
			{
				$oToDelete = $aData['to_delete'];
				$bAutoDel = (($aData['mode'] == DEL_SILENT) || ($aData['mode'] == DEL_AUTO));
				if (array_key_exists('issue', $aData))
				{
					if ($bAutoDel)
					{
						if (isset($aData['requested_explicitely'])) // i.e. in the initial list of objects to delete
						{
							$iCode = RestDelete::ISSUE;
							$sPlanned = 'Cannot be deleted: '.$aData['issue'];
						}
						else
						{
							$iCode = RestDelete::AUTO_DELETE_ISSUE;
							$sPlanned = 'Should be deleted automatically... but: '.$aData['issue'];
						}
					}
					else
					{
						$iCode = RestDelete::REQUEST_EXPLICITELY;
						$sPlanned = 'Must be deleted explicitely... but: '.$aData['issue'];
					}
				}
				else
				{
					if ($bAutoDel)
					{
						if (isset($aData['requested_explicitely']))
						{
							$iCode = RestDelete::OK;
		               $sPlanned = '';
						}
						else
						{
							$iCode = RestDelete::AUTO_DELETE;
							$sPlanned = 'Deleted automatically';
						}
					}
					else
					{
						$iCode = RestDelete::REQUEST_EXPLICITELY;
						$sPlanned = 'Must be deleted explicitely';
					}
				}
				$oResult->AddObject($iCode, $sPlanned, $oToDelete, array('id', 'friendlyname'));
			}
		}
		foreach ($oDeletionPlan->ListUpdates() as $sRemoteClass => $aToUpdate)
		{
			foreach ($aToUpdate as $iId => $aData)
			{
				$oToUpdate = $aData['to_reset'];
				if (array_key_exists('issue', $aData))
				{
					$iCode = RestDelete::AUTO_UPDATE_ISSUE;
					$sPlanned = 'Should be updated automatically... but: '.$aData['issue'];
				}
				else
				{
					$iCode = RestDelete::AUTO_UPDATE;
					$sPlanned = 'Reset external keys: '.$aData['attributes_list'];
				}
				$oResult->AddObject($iCode, $sPlanned, $oToUpdate, array('id', 'friendlyname'));
			}
		}
		
		if ($oDeletionPlan->FoundStopper())
		{
			if ($oDeletionPlan->FoundSecurityIssue())
			{
				$iRes = RestResult::UNAUTHORIZED;
				$sRes = 'Deletion not allowed on some objects';
			}
			elseif ($oDeletionPlan->FoundManualOperation())
			{
				$iRes = RestResult::UNSAFE; 
				$sRes = 'The deletion requires that other objects be deleted/updated, and those operations must be requested explicitely';
			}
			else
			{
				$iRes = RestResult::INTERNAL_ERROR; 
				$sRes = 'Some issues have been encountered. See the list of planned changes for more information about the issue(s).';
			}		
		}
		else
		{
			$iRes = RestResult::OK; 
			$sRes = 'Deleted: '.count($aObjects);
			$iIndirect = $oDeletionPlan->GetTargetCount() - count($aObjects);
			if ($iIndirect > 0)
			{
				$sRes .= ' plus (for DB integrity) '.$iIndirect;
			}
		}
		$oResult->code = $iRes;
		if ($bSimulate)
		{
			$oResult->message = 'SIMULATING: '.$sRes;
		}
		else
		{
			$oResult->message = $sRes;
		}
	}
	
	/**
	 * Helper function to get the related objects up to the given depth along with the "graph" of the relation
	 * @param DBObject $oObject Starting point of the computation
	 * @param string $sRelation Code of the relation (i.e; 'impact', 'depends on'...)
	 * @param integer $iMaxRecursionDepth Maximum level of recursion
	 * @param Hash $aRelated Two dimensions hash of the already related objects: array( 'class' => array(key => ))
	 * @param Hash	$aGraph Hash array for the topology of the relation: source => related: array('class:key' => array( DBObjects ))
	 * @param integer $iRecursionDepth Current level of recursion
	 */
	protected function GetRelatedObjects(DBObject $oObject, $sRelation, $iMaxRecursionDepth, &$aRelated, &$aGraph, $iRecursionDepth = 1)
	{
		// Avoid loops
		if ((array_key_exists(get_class($oObject), $aRelated)) && (array_key_exists($oObject->GetKey(), $aRelated[get_class($oObject)]))) return;
		// Stop at maximum recursion level
		if ($iRecursionDepth > $iMaxRecursionDepth) return;
		
		$sSrcKey = get_class($oObject).'::'.$oObject->GetKey();
		$aNewRelated = array();
		$oObject->GetRelatedObjects($sRelation, 1, $aNewRelated);
		foreach($aNewRelated as $sClass => $aObjects)
		{
			if (!array_key_exists($sSrcKey, $aGraph))
			{
				$aGraph[$sSrcKey] = array();
			}
			foreach($aObjects as $oRelatedObject)
			{
				$aRelated[$sClass][$oRelatedObject->GetKey()] = $oRelatedObject;
				$aGraph[$sSrcKey][] = get_class($oRelatedObject).'::'.$oRelatedObject->GetKey();
				$this->GetRelatedObjects($oRelatedObject, $sRelation, $iMaxRecursionDepth, $aRelated, $aGraph, $iRecursionDepth+1);
			}
		}
	}
}
