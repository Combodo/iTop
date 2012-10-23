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
 * Class WizardHelper
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'/application/uiwizard.class.inc.php');

class WizardHelper
{
	protected $m_aData;
	
	public function __construct()
	{
	}
	/**
	 * Constructs the PHP target object from the parameters sent to the web page by the wizard
	 * @param boolean $bReadUploadedFiles True to also ready any uploaded file (for blob/document fields)
	 * @return object
	 */	 	 	 	
	public function GetTargetObject($bReadUploadedFiles = false)
	{
		if (isset($this->m_aData['m_oCurrentValues']['id']))
		{
			$oObj = MetaModel::GetObject($this->m_aData['m_sClass'], $this->m_aData['m_oCurrentValues']['id']);
		}
		else
		{
			$oObj = MetaModel::NewObject($this->m_aData['m_sClass']);
		}
		foreach($this->m_aData['m_oCurrentValues'] as $sAttCode => $value)
		{
			// Because this is stored in a Javascript array, unused indexes
			// are filled with null values and unused keys (stored as strings) contain $$NULL$$
			if ( ($sAttCode !='id') && ($sAttCode !== false) && ($value !== null) && ($value !== '$$NULL$$'))
			{
				$oAttDef = MetaModel::GetAttributeDef($this->m_aData['m_sClass'], $sAttCode);
				if (($oAttDef->IsLinkSet()) && ($value != '') )
				{
					// special handling for lists
					// assumes this is handled as an array of objects
					// thus encoded in json like: [ { name:'link1', 'id': 123}, { name:'link2', 'id': 124}...]
					$aData = json_decode($value, true); // true means decode as a hash array (not an object)
					// Check what are the meaningful attributes
					$aFields = $this->GetLinkedWizardStructure($oAttDef);
					$sLinkedClass = $oAttDef->GetLinkedClass();
					$aLinkedObjectsArray = array();
					if (!is_array($aData))
					{
						echo ("aData: '$aData' (value: '$value')\n");
					}
					foreach($aData as $aLinkedObject)
					{
						$oLinkedObj = MetaModel::NewObject($sLinkedClass);
						foreach($aFields as $sLinkedAttCode)
						{
							if ( isset($aLinkedObject[$sLinkedAttCode]) && ($aLinkedObject[$sLinkedAttCode] !== null) )
							{
								$sLinkedAttDef = MetaModel::GetAttributeDef($sLinkedClass, $sLinkedAttCode);
								if (($sLinkedAttDef->IsExternalKey()) && ($aLinkedObject[$sLinkedAttCode] != '') && ($aLinkedObject[$sLinkedAttCode] > 0) )
								{
									// For external keys: load the target object so that external fields
									// get filled too
									$oTargetObj = MetaModel::GetObject($sLinkedAttDef->GetTargetClass(), $aLinkedObject[$sLinkedAttCode]);
									$oLinkedObj->Set($sLinkedAttCode, $oTargetObj);
								}
								else
								{
									$oLinkedObj->Set($sLinkedAttCode, $aLinkedObject[$sLinkedAttCode]);
								}
							}
						}
						$aLinkedObjectsArray[] = $oLinkedObj;
					}
					$oSet = DBObjectSet::FromArray($sLinkedClass, $aLinkedObjectsArray);
					$oObj->Set($sAttCode, $oSet);
				}
				else if ( $oAttDef->GetEditClass() == 'Document' )
				{
					if ($bReadUploadedFiles)
					{
						$oDocument = utils::ReadPostedDocument('attr_'.$sAttCode, 'fcontents');
						$oObj->Set($sAttCode, $oDocument);
					}
					else
					{
						// Create a new empty document, just for displaying the file name
						$oDocument = new ormDocument(null, '', $value);
						$oObj->Set($sAttCode, $oDocument);
					}
				}
				else if (($oAttDef->IsExternalKey()) && (!empty($value)) && ($value > 0) )
				{
					// For external keys: load the target object so that external fields
					// get filled too
					$oTargetObj = MetaModel::GetObject($oAttDef->GetTargetClass(), $value);
					$oObj->Set($sAttCode, $oTargetObj);
				}
				else
				{
					$oObj->Set($sAttCode, $value);
				}	
			}
		}
		if (isset($this->m_aData['m_sState']) && !empty($this->m_aData['m_sState']))
		{
			$oObj->Set(MetaModel::GetStateAttributeCode($this->m_aData['m_sClass']), $this->m_aData['m_sState']);
		}
		$oObj->DoComputeValues();
		return $oObj;
	}
	
	public function GetFieldsForDefaultValue()
	{
		return $this->m_aData['m_aDefaultValueRequested'];
	}
	
	public function SetDefaultValue($sAttCode, $value)
	{
		// Protect against a request for a non existing field
		if (isset($this->m_aData['m_oFieldsMap'][$sAttCode]))
		{
			$oAttDef = MetaModel::GetAttributeDef($this->m_aData['m_sClass'], $sAttCode);
			if ($oAttDef->GetEditClass() == 'List')
			{
				// special handling for lists
				// this as to be handled as an array of objects
				// thus encoded in json like: [ { name:'link1', 'id': 123}, { name:'link2', 'id': 124}...]
				// NOT YET IMPLEMENTED !!
				$sLinkedClass = $oAttDef->GetLinkedClass();
				$oSet = $value;
				$aData = array();
				$aFields = $this->GetLinkedWizardStructure($oAttDef);
				while($oSet->fetch())
				{
					foreach($aFields as $sLinkedAttCode)
					{
						$aRow[$sAttCode] = $oLinkedObj->Get($sLinkedAttCode);
					}
					$aData[] = $aRow;
				}
				$this->m_aData['m_oDefaultValue'][$sAttCode] = json_encode($aData);
				
			}
			else
			{
				// Normal handling for all other scalar attributes
				$this->m_aData['m_oDefaultValue'][$sAttCode] = $value;
			}
		}
	}
	
	public function GetFieldsForAllowedValues()
	{
		return $this->m_aData['m_aAllowedValuesRequested'];
	}
	
	public function SetAllowedValuesHtml($sAttCode, $sHtml)
	{
		// Protect against a request for a non existing field
		if (isset($this->m_aData['m_oFieldsMap'][$sAttCode]))
		{
			$this->m_aData['m_oAllowedValues'][$sAttCode] = $sHtml;
		}
	}
	
	public function ToJSON()
	{
		return json_encode($this->m_aData);
	}
	
	static public function FromJSON($sJSON)
	{
		$oWizHelper = new WizardHelper();
		if (get_magic_quotes_gpc())
		{
			$sJSON = stripslashes($sJSON);
		}
		$aData = json_decode($sJSON, true); // true means hash array instead of object
		$oWizHelper->m_aData = $aData;
		return $oWizHelper;
	}
	
	protected function GetLinkedWizardStructure($oAttDef)
	{
		$oWizard = new UIWizard(null, $oAttDef->GetLinkedClass());
		$aWizardSteps = $oWizard->GetWizardStructure();
		$aFields = array();
		$sExtKeyToMeCode = $oAttDef->GetExtKeyToMe();
		// Retrieve as a flat list, all the attributes that are needed to create
		// an object of the linked class and put them into a flat array, except
		// the attribute 'ext_key_to_me' which is a constant in our case
		foreach($aWizardSteps as $sDummy => $aMainSteps)
		{
			// 2 entries: 'mandatory' and 'optional'
			foreach($aMainSteps as $aSteps)
			{
				// One entry for each step of the wizard
				foreach($aSteps as $sAttCode)
				{
					if ($sAttCode != $sExtKeyToMeCode)
					{
						$aFields[] = $sAttCode;
					}
				}
			}
		}
		return $aFields;
	}
	
	public function GetTargetClass()
	{
		return $this->m_aData['m_sClass'];
	}
	
	public function GetFormPrefix()
	{
		return $this->m_aData['m_sFormPrefix'];
	}
	
	public function GetIdForField($sFieldName)
	{
		$sResult = '';
		// It may happen that the field we'd like to update does not
		// exist in the form. For example, if the field should be hidden/read-only
		// in the current state of the object
		if (isset($this->m_aData['m_oFieldsMap'][$sFieldName]))
		{
			$sResult = $this->m_aData['m_oFieldsMap'][$sFieldName];
		}
		return $sResult;
	}
	
	static function ParseJsonSet($oMe, $sLinkClass, $sExtKeyToMe, $sJsonSet)
	{
		$aSet = json_decode($sJsonSet, true); // true means hash array instead of object
		$oSet = CMDBObjectSet::FromScratch($sLinkClass);
		foreach($aSet as $aLinkObj)
		{
			$oLink = MetaModel::NewObject($sLinkClass);
			foreach($aLinkObj as $sAttCode => $value)
			{
				$oAttDef = MetaModel::GetAttributeDef($sLinkClass, $sAttCode);
				if (($oAttDef->IsExternalKey()) && ($value != '')  && ($value > 0))
				{
					// For external keys: load the target object so that external fields
					// get filled too
					$oTargetObj = MetaModel::GetObject($oAttDef->GetTargetClass(), $value);
					$oLink->Set($sAttCode, $oTargetObj);
				}
				$oLink->Set($sAttCode, $value);
			}
			$oLink->Set($sExtKeyToMe, $oMe->GetKey());
			$oSet->AddObject($oLink);
		}
		return $oSet;
	}
}
?>
