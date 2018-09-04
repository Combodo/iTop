<?php
// Copyright (C) 2013-2015 Combodo SARL
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
	public $class;
	public $key;
	public $fields;
	
	/**
	 * Default constructor
	 */
	public function __construct($sClass = null, $iId = null)
	{
		$this->code = RestResult::OK;
		$this->message = '';
		$this->class = $sClass;
		$this->key = $iId;
		$this->fields = array();
	}

	/**
	 * Helper to make an output value for a given attribute
	 * 	 
	 * @param DBObject $oObject The object being reported
	 * @param string $sAttCode The attribute code (must be valid)
	 * @param boolean $bExtendedOutput Output all of the link set attributes ?
	 * @return string A scalar representation of the value
	 */
	protected function MakeResultValue(DBObject $oObject, $sAttCode, $bExtendedOutput = false)
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
				// Iterate on the set and build an array of array of attcode=>value
				$oSet = $oObject->Get($sAttCode);
				$value = array();
				while ($oLnk = $oSet->Fetch())
				{
					$sLnkRefClass = $bExtendedOutput ? get_class($oLnk) : $oAttDef->GetLinkedClass();

					$aLnkValues = array();
					foreach (MetaModel::ListAttributeDefs($sLnkRefClass) as $sLnkAttCode => $oLnkAttDef)
					{
						// Skip attributes pointing to the current object (redundant data)
						if ($sLnkAttCode == $oAttDef->GetExtKeyToMe())
						{
							continue;
						}
						// Skip any attribute of the link that points to the current object
						$oLnkAttDef = MetaModel::GetAttributeDef($sLnkRefClass, $sLnkAttCode);
						if (method_exists($oLnkAttDef, 'GetKeyAttCode'))
						{
							if ($oLnkAttDef->GetKeyAttCode() == $oAttDef->GetExtKeyToMe())
							{
								continue;
							}
						}
						
						$aLnkValues[$sLnkAttCode] = $this->MakeResultValue($oLnk, $sLnkAttCode, $bExtendedOutput);
					}
					$value[] = $aLnkValues;
				}
			}
			else
			{
				$value = $oAttDef->GetForJSON($oObject->Get($sAttCode));
			}
		}
		return $value;
	}

	/**
	 * Report the value for the given object attribute
	 * 	 
	 * @param DBObject $oObject The object being reported
	 * @param string $sAttCode The attribute code (must be valid)
	 * @param boolean $bExtendedOutput Output all of the link set attributes ?
	 * @return void
	 */
	public function AddField(DBObject $oObject, $sAttCode, $bExtendedOutput = false)
	{
		$this->fields[$sAttCode] = $this->MakeResultValue($oObject, $sAttCode, $bExtendedOutput);
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
	 * @param array $aFieldSpec An array of class => attribute codes (Cf. RestUtils::GetFieldList). List of the attributes to be reported.
	 * @param boolean $bExtendedOutput Output all of the link set attributes ?
	 * @return void
	 */
	public function AddObject($iCode, $sMessage, $oObject, $aFieldSpec = null, $bExtendedOutput = false)
	{
		$sClass = get_class($oObject);
		$oObjRes = new ObjectResult($sClass, $oObject->GetKey());
		$oObjRes->code = $iCode;
		$oObjRes->message = $sMessage;

		$aFields = null;
		if (!is_null($aFieldSpec))
		{
			// Enum all classes in the hierarchy, starting with the current one
			foreach (MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL, false) as $sRefClass)
			{
				if (array_key_exists($sRefClass, $aFieldSpec))
				{
					$aFields = $aFieldSpec[$sRefClass];
					break;
				}
			}
		}
		if (is_null($aFields))
		{
			// No fieldspec given, or not found...
			$aFields = array('id', 'friendlyname');
		}

		foreach ($aFields as $sAttCode)
		{
			$oObjRes->AddField($oObject, $sAttCode, $bExtendedOutput);
		}

		$sObjKey = get_class($oObject).'::'.$oObject->GetKey();
		$this->objects[$sObjKey] = $oObjRes;
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
		$this->relations[$sSrcKey][] = array('key' => $sDestKey);
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
		// 1.3 - iTop 2.2.0, Verb 'get_related': added the options 'redundancy' and 'direction' to take into account the redundancy in the impact analysis
		// 1.2 - was documented in the wiki but never released ! Same as 1.3
		// 1.1 - In the reply, objects have a 'key' entry so that it is no more necessary to split class::key programmaticaly
		// 1.0 - Initial implementation in iTop 2.0.1
		//
		$aOps = array();
		if (in_array($sVersion, array('1.0', '1.1', '1.2', '1.3'))) 
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
			$aOps[] = array(
				'verb' => 'core/check_credentials',
				'description' => 'Check user credentials'
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
			$bExtendedOutput = (RestUtils::GetOptionalParam($aParams, 'output_fields', '*') == '*+');

			if (UserRights::IsActionAllowed($sClass, UR_ACTION_MODIFY) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for creating data of class $sClass";
			}
			elseif (UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_MODIFY) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for massively creating data of class $sClass";
			}
			else
			{
				$oObject = RestUtils::MakeObjectFromFields($sClass, $aFields);
				$oObject->DBInsert();
				$oResult->AddObject(0, 'created', $oObject, $aShowFields, $bExtendedOutput);
			}
			break;
	
		case 'core/update':
			RestUtils::InitTrackingComment($aParams);
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$aFields = RestUtils::GetMandatoryParam($aParams, 'fields');
			$aShowFields = RestUtils::GetFieldList($sClass, $aParams, 'output_fields');
			$bExtendedOutput = (RestUtils::GetOptionalParam($aParams, 'output_fields', '*') == '*+');
	
			// Note: the target class cannot be based on the result of FindObjectFromKey, because in case the user does not have read access, that function already fails with msg 'Nothing found'
			$sTargetClass = RestUtils::GetObjectSetFromKey($sClass, $key)->GetFilter()->GetClass();
			if (UserRights::IsActionAllowed($sTargetClass, UR_ACTION_MODIFY) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for modifying data of class $sTargetClass";
			}
			elseif (UserRights::IsActionAllowed($sTargetClass, UR_ACTION_MODIFY) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for massively modifying data of class $sTargetClass";
			}
			else
			{
				$oObject = RestUtils::FindObjectFromKey($sClass, $key);
				RestUtils::UpdateObjectFromFields($oObject, $aFields);
				$oObject->DBUpdate();
				$oResult->AddObject(0, 'updated', $oObject, $aShowFields, $bExtendedOutput);
			}
			break;
	
		case 'core/apply_stimulus':
			RestUtils::InitTrackingComment($aParams);
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$aFields = RestUtils::GetMandatoryParam($aParams, 'fields');
			$aShowFields = RestUtils::GetFieldList($sClass, $aParams, 'output_fields');
			$bExtendedOutput = (RestUtils::GetOptionalParam($aParams, 'output_fields', '*') == '*+');
			$sStimulus = RestUtils::GetMandatoryParam($aParams, 'stimulus');
	
			// Note: the target class cannot be based on the result of FindObjectFromKey, because in case the user does not have read access, that function already fails with msg 'Nothing found'
			$sTargetClass = RestUtils::GetObjectSetFromKey($sClass, $key)->GetFilter()->GetClass();
			if (UserRights::IsActionAllowed($sTargetClass, UR_ACTION_MODIFY) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for modifying data of class $sTargetClass";
			}
			elseif (UserRights::IsActionAllowed($sTargetClass, UR_ACTION_BULK_MODIFY) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for massively modifying data of class $sTargetClass";
			}
			else
			{
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
							$oResult->AddObject(0, 'updated', $oObject, $aShowFields, $bExtendedOutput);
						}
					}
					else
					{
						// Missing mandatory attributes for the transition
						$oResult->code = RestResult::INTERNAL_ERROR;
						$oResult->message = 'Missing mandatory attribute(s) for applying the stimulus: '.implode(', ', $aMissingMandatory).'.';
					}
				}
			}	
			break;
	
		case 'core/get':
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$aShowFields = RestUtils::GetFieldList($sClass, $aParams, 'output_fields');
			$bExtendedOutput = (RestUtils::GetOptionalParam($aParams, 'output_fields', '*') == '*+');

			$oObjectSet = RestUtils::GetObjectSetFromKey($sClass, $key);
			$sTargetClass = $oObjectSet->GetFilter()->GetClass();
	
			if (UserRights::IsActionAllowed($sTargetClass, UR_ACTION_READ) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for reading data of class $sTargetClass";
			}
			elseif (UserRights::IsActionAllowed($sTargetClass, UR_ACTION_BULK_READ) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for exporting data of class $sTargetClass";
			}
			else
			{
				while ($oObject = $oObjectSet->Fetch())
				{
					$oResult->AddObject(0, '', $oObject, $aShowFields, $bExtendedOutput);
				}
				$oResult->message = "Found: ".$oObjectSet->Count();
			}
			break;

		case 'core/delete':
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$bSimulate = RestUtils::GetOptionalParam($aParams, 'simulate', false);
	
			$oObjectSet = RestUtils::GetObjectSetFromKey($sClass, $key);
			$sTargetClass = $oObjectSet->GetFilter()->GetClass();
	
			if (UserRights::IsActionAllowed($sTargetClass, UR_ACTION_DELETE) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for deleting data of class $sTargetClass";
			}
			elseif (UserRights::IsActionAllowed($sTargetClass, UR_ACTION_DELETE) != UR_ALLOWED_YES)
			{
				$oResult->code = RestResult::UNAUTHORIZED;
				$oResult->message = "The current user does not have enough permissions for massively deleting data of class $sTargetClass";
			}
			else
			{
				$aObjects = $oObjectSet->ToArray();
				$this->DeleteObjects($oResult, $aObjects, $bSimulate);
			}
			break;

		case 'core/get_related':
			$oResult = new RestResultWithRelations();
			$sClass = RestUtils::GetClass($aParams, 'class');
			$key = RestUtils::GetMandatoryParam($aParams, 'key');
			$sRelation = RestUtils::GetMandatoryParam($aParams, 'relation');
			$iMaxRecursionDepth = RestUtils::GetOptionalParam($aParams, 'depth', 20 /* = MAX_RECURSION_DEPTH */);
			$sDirection = RestUtils::GetOptionalParam($aParams, 'direction', null);
			$bEnableRedundancy = RestUtils::GetOptionalParam($aParams, 'redundancy', false);
			$bReverse = false;

			if (is_null($sDirection) && ($sRelation == 'depends on'))
			{
				// Legacy behavior, consider "depends on" as a forward relation
				$sRelation = 'impacts';
				$sDirection = 'up'; 
				$bReverse = true; // emulate the legacy behavior by returning the edges
			}
			else if(is_null($sDirection))
			{
				$sDirection = 'down';
			}
	
			$oObjectSet = RestUtils::GetObjectSetFromKey($sClass, $key);
			if ($sDirection == 'down')
			{
				$oRelationGraph = $oObjectSet->GetRelatedObjectsDown($sRelation, $iMaxRecursionDepth, $bEnableRedundancy);
			}
			else if ($sDirection == 'up')
			{
				$oRelationGraph = $oObjectSet->GetRelatedObjectsUp($sRelation, $iMaxRecursionDepth, $bEnableRedundancy);
			}
			else
			{
				$oResult->code = RestResult::INTERNAL_ERROR;
				$oResult->message = "Invalid value: '$sDirection' for the parameter 'direction'. Valid values are 'up' and 'down'";
				return $oResult;
				
			}
			
			if ($bEnableRedundancy)
			{
				// Remove the redundancy nodes from the output
				$oIterator = new RelationTypeIterator($oRelationGraph, 'Node');
				foreach($oIterator as $oNode)
				{
					if ($oNode instanceof RelationRedundancyNode)
					{
						$oRelationGraph->FilterNode($oNode);
					}
				}
			}
			
			$aIndexByClass = array();
			$oIterator = new RelationTypeIterator($oRelationGraph);
			foreach($oIterator as $oElement)
			{
				if ($oElement instanceof RelationObjectNode)
				{
					$oObject = $oElement->GetProperty('object');
					if ($oObject)
					{
						if ($bEnableRedundancy)
						{
							// Add only the "reached" objects
							if ($oElement->GetProperty('is_reached'))
							{
								$aIndexByClass[get_class($oObject)][$oObject->GetKey()] = null;
								$oResult->AddObject(0, '', $oObject);
							}
						}
						else
						{
							$aIndexByClass[get_class($oObject)][$oObject->GetKey()] = null;
							$oResult->AddObject(0, '', $oObject);
						}
					}
				}
				else if ($oElement instanceof RelationEdge)
				{
					$oSrcObj = $oElement->GetSourceNode()->GetProperty('object');
					$oDestObj = $oElement->GetSinkNode()->GetProperty('object');
					$sSrcKey = get_class($oSrcObj).'::'.$oSrcObj->GetKey();
					$sDestKey = get_class($oDestObj).'::'.$oDestObj->GetKey();
					if ($bEnableRedundancy)
					{
						// Add only the edges where both source and destination are "reached"
						if ($oElement->GetSourceNode()->GetProperty('is_reached') && $oElement->GetSinkNode()->GetProperty('is_reached'))
						{
							if ($bReverse)
							{
								$oResult->AddRelation($sDestKey, $sSrcKey);
							}
							else
							{
								$oResult->AddRelation($sSrcKey, $sDestKey);
							}
						}
					}
					else
					{
						if ($bReverse)
						{
							$oResult->AddRelation($sDestKey, $sSrcKey);
						}
						else
						{
							$oResult->AddRelation($sSrcKey, $sDestKey);
						}
					}
				}
			}

			if (count($aIndexByClass) > 0)
			{
				$aStats = array();
				$aUnauthorizedClasses = array();
				foreach ($aIndexByClass as $sClass => $aIds)
				{
					if (UserRights::IsActionAllowed($sClass, UR_ACTION_BULK_READ) != UR_ALLOWED_YES)
					{
						$aUnauthorizedClasses[$sClass] = true;
					}
					$aStats[] = $sClass.'= '.count($aIds);
				}
				if (count($aUnauthorizedClasses) > 0)
				{
					$sClasses = implode(', ', array_keys($aUnauthorizedClasses));
					$oResult = new RestResult();
					$oResult->code = RestResult::UNAUTHORIZED;
					$oResult->message = "The current user does not have enough permissions for exporting data of class(es): $sClasses";
				}
				else
				{
					$oResult->message = "Scope: ".$oObjectSet->Count()."; Related objects: ".implode(', ', $aStats);
				}
			}
			else
			{
				$oResult->message = "Nothing found";
			}
			break;
			
		case 'core/check_credentials':
			$oResult = new RestResult();
			$sUser = RestUtils::GetMandatoryParam($aParams, 'user');
			$sPassword = RestUtils::GetMandatoryParam($aParams, 'password');

			if (UserRights::CheckCredentials($sUser, $sPassword) !== true)
			{
				$oResult->authorized = false;
			}
			else
			{
				$oResult->authorized = true;
			}
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
				$oResult->AddObject($iCode, $sPlanned, $oToDelete);
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
				$oResult->AddObject($iCode, $sPlanned, $oToUpdate);
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
}
