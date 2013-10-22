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
 * Object set management
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * A set of persistent objects, could be heterogeneous 
 *
 * @package     iTopORM
 */
class DBObjectSet
{
	private $m_oFilter;
	private $m_aAddedIds; // Ids of objects added (discrete lists)
	private $m_aOrderBy;
	public $m_bLoaded;
	private $m_aData;
	private $m_aId2Row;
	private $m_iCurrRow;

	public function __construct(DBObjectSearch $oFilter, $aOrderBy = array(), $aArgs = array(), $aExtendedDataSpec = null, $iLimitCount = 0, $iLimitStart = 0)
	{
		$this->m_oFilter = $oFilter->DeepClone();
		$this->m_aAddedIds = array();
		$this->m_aOrderBy = $aOrderBy;
		$this->m_aArgs = $aArgs;
		$this->m_aAttToLoad = null;
		$this->m_aExtendedDataSpec = $aExtendedDataSpec;
		$this->m_iLimitCount = $iLimitCount;
		$this->m_iLimitStart = $iLimitStart;

		$this->m_iCount = null; // null if unknown yet
		$this->m_bLoaded = false; // true when the filter has been used OR the set is built step by step (AddObject...)
		$this->m_aData = array(); // array of (row => array of (classalias) => object/null)
		$this->m_aId2Row = array(); // array of (pkey => index in m_aData)
		$this->m_iCurrRow = 0;
	}

	public function __destruct()
	{
	}

	public function __toString()
	{
		$sRet = '';
		$this->Rewind();
		$sRet .= "Set (".$this->m_oFilter->ToOQL().")<br/>\n";
		$sRet .= "Query: <pre style=\"font-size: smaller; display:inline;\">".MetaModel::MakeSelectQuery($this->m_oFilter, array()).")</pre>\n";
		
		$sRet .= $this->Count()." records<br/>\n";
		if ($this->Count() > 0)
		{
			$sRet .= "<ul class=\"treeview\">\n";
			while ($oObj = $this->Fetch())
			{
				$sRet .= "<li>".$oObj->__toString()."</li>\n";
			}
			$sRet .= "</ul>\n";
		}
		return $sRet;
	}

	public function OptimizeColumnLoad($aAttToLoad)
	{
		if (is_null($aAttToLoad))
		{
			$this->m_aAttToLoad = null;
		}
		else
		{
			// Complete the attribute list with the attribute codes
			$aAttToLoadWithAttDef = array();
			foreach($aAttToLoad as $sClassAlias => $aAttList)
			{
				$aSelectedClasses = $this->m_oFilter->GetSelectedClasses();
				$sClass = $aSelectedClasses[$sClassAlias];
				foreach($aAttList as $sAttToLoad)
				{
					$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttToLoad);
					$aAttToLoadWithAttDef[$sClassAlias][$sAttToLoad] = $oAttDef;
					if ($oAttDef->IsExternalKey())
					{
						// Add the external key friendly name anytime
						$oFriendlyNameAttDef = MetaModel::GetAttributeDef($sClass, $sAttToLoad.'_friendlyname');
						$aAttToLoadWithAttDef[$sClassAlias][$sAttToLoad.'_friendlyname'] = $oFriendlyNameAttDef;
					}
				}
				// Add the friendly name anytime
				$oFriendlyNameAttDef = MetaModel::GetAttributeDef($sClass, 'friendlyname');
				$aAttToLoadWithAttDef[$sClassAlias]['friendlyname'] = $oFriendlyNameAttDef;

				// Make sure that the final class is requested anytime, whatever the specification (needed for object construction!)
				if (!MetaModel::IsStandaloneClass($sClass) && !array_key_exists('finalclass', $aAttList))
				{
					$aAttToLoadWithAttDef[$sClassAlias]['finalclass'] = MetaModel::GetAttributeDef($sClass, 'finalclass');
				}
			}

			$this->m_aAttToLoad = $aAttToLoadWithAttDef;
		}
	}

	static public function FromObject($oObject)
	{
		$oRetSet = self::FromScratch(get_class($oObject));
		$oRetSet->AddObject($oObject);
		return $oRetSet;
	}

	static public function FromScratch($sClass)
	{
		$oFilter = new DBObjectSearch($sClass);
		$oFilter->AddConditionExpression(new FalseExpression());
		$oRetSet = new self($oFilter);
		$oRetSet->m_bLoaded = true; // no DB load
		return $oRetSet;
	} 

	// create an object set ex nihilo
	// input = array of objects
	static public function FromArray($sClass, $aObjects)
	{
		$oRetSet = self::FromScratch($sClass);
		$oRetSet->AddObjectArray($aObjects, $sClass);
		return $oRetSet;
	} 

	// create an object set ex nihilo
	// aClasses = array of (alias => class)
	// input = array of (array of (classalias => object))
	static public function FromArrayAssoc($aClasses, $aObjects)
	{
		// In a perfect world, we should create a complete tree of DBObjectSearch,
		// but as we lack most of the information related to the objects,
		// let's create one search definition
		$sClass = reset($aClasses);
		$sAlias = key($aClasses);
		$oFilter = new CMDBSearchFilter($sClass, $sAlias);

		$oRetSet = new self($oFilter);
		$oRetSet->m_bLoaded = true; // no DB load

		foreach($aObjects as $rowIndex => $aObjectsByClassAlias)
		{
			$oRetSet->AddObjectExtended($aObjectsByClassAlias);
		}
		return $oRetSet;
	} 

	static public function FromLinkSet($oObject, $sLinkSetAttCode, $sExtKeyToRemote)
	{
		$oLinkAttCode = MetaModel::GetAttributeDef(get_class($oObject), $sLinkSetAttCode);
		$oExtKeyAttDef = MetaModel::GetAttributeDef($oLinkAttCode->GetLinkedClass(), $sExtKeyToRemote);
		$sTargetClass = $oExtKeyAttDef->GetTargetClass();

		$oLinkSet = $oObject->Get($sLinkSetAttCode);
		$aTargets = array();
		while ($oLink = $oLinkSet->Fetch())
		{
			$aTargets[] = MetaModel::GetObject($sTargetClass, $oLink->Get($sExtKeyToRemote));
		}

		return self::FromArray($sTargetClass, $aTargets);
	} 

	public function ToArray($bWithId = true)
	{
		$aRet = array();
		$this->Rewind();
		while ($oObject = $this->Fetch())
		{
			if ($bWithId)
			{
				$aRet[$oObject->GetKey()] = $oObject;
			}
			else
			{
				$aRet[] = $oObject;
			}
		}
		return $aRet;
	} 

	public function ToArrayOfValues()
	{
		if (!$this->m_bLoaded) $this->Load();

		$aSelectedClasses = $this->m_oFilter->GetSelectedClasses();

		$aRet = array();
		foreach($this->m_aData as $iRow => $aObjects)
		{
			foreach($aObjects as $sClassAlias => $oObject)
			{
				if (is_null($oObject))
				{
					$aRet[$iRow][$sClassAlias.'.'.'id'] = null;
				}
				else
				{
					$aRet[$iRow][$sClassAlias.'.'.'id'] = $oObject->GetKey();
				} 
				if (is_null($oObject))
				{
					$sClass = $aSelectedClasses[$sClassAlias];
				}
				else
				{
					$sClass = get_class($oObject);
				}
				foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
				{
					if ($oAttDef->IsScalar())
					{
						$sAttName = $sClassAlias.'.'.$sAttCode;
						if (is_null($oObject))
						{
							$aRet[$iRow][$sAttName] = null;
						}
						else
						{
							$aRet[$iRow][$sAttName] = $oObject->Get($sAttCode);
						}
					}
				}
			}
		}
		return $aRet;
	} 

	public function GetColumnAsArray($sAttCode, $bWithId = true)
	{
		$aRet = array();
		$this->Rewind();
		while ($oObject = $this->Fetch())
		{
			if ($bWithId)
			{
				$aRet[$oObject->GetKey()] = $oObject->Get($sAttCode);
			}
			else
			{
				$aRet[] = $oObject->Get($sAttCode);
			}
		}
		return $aRet;
	}

	public function GetFilter()
	{
		// Make sure that we carry on the parameters of the set with the filter
		$oFilter = $this->m_oFilter->DeepClone();
		// Note: the arguments found within a set can be object (but not in a filter)
		// That's why PrepareQueryArguments must be invoked there
		$oFilter->SetInternalParams(array_merge($oFilter->GetInternalParams(), MetaModel::PrepareQueryArguments($this->m_aArgs)));
		
		if (count($this->m_aAddedIds) == 0)
		{
			return $oFilter;
		}
		else
		{
			$oIdListExpr = ListExpression::FromScalars(array_keys($this->m_aAddedIds));
			$oIdExpr = new FieldExpression('id', $oFilter->GetClassAlias());
			$oIdInList = new BinaryExpression($oIdExpr, 'IN', $oIdListExpr);
			$oFilter->MergeConditionExpression($oIdInList);
			return $oFilter;
		}
	}

	public function GetClass()
	{
		return $this->m_oFilter->GetClass();
	}

	public function GetClassAlias()
	{
		return $this->m_oFilter->GetClassAlias();
	}

	public function GetSelectedClasses()
	{
		return $this->m_oFilter->GetSelectedClasses();
	}

	public function GetRootClass()
	{
		return MetaModel::GetRootClass($this->GetClass());
	}

	public function GetArgs()
	{
		return $this->m_aArgs;
	}

	public function SetLimit($iLimitCount, $iLimitStart = 0)
	{
		$this->m_iLimitCount = $iLimitCount;
		$this->m_iLimitStart = $iLimitStart;
	}

	public function SetOrderBy($aOrderBy)
	{
		if ($this->m_aOrderBy != $aOrderBy)
		{
			$this->m_aOrderBy = $aOrderBy;
			if ($this->m_bLoaded)
			{
				$this->m_bLoaded = false;
				$this->Load();
			}
		}
	}

	public function GetLimitCount()
	{
		return $this->m_iLimitCount;
	}

	public function GetLimitStart()
	{
		return $this->m_iLimitStart;
	}

	public function GetRealSortOrder()
	{
		// Get the class default sort order if not specified with the API
		//
		if (empty($this->m_aOrderBy))
		{
			return MetaModel::GetOrderByDefault($this->m_oFilter->GetClass());
		}
		else
		{
			return $this->m_aOrderBy;
		}
	}

	public function Load()
	{
		if ($this->m_bLoaded) return;
		// Note: it is mandatory to set this value now, to protect against reentrance
		$this->m_bLoaded = true;

		if ($this->m_iLimitCount > 0)
		{
			$sSQL = MetaModel::MakeSelectQuery($this->m_oFilter, $this->GetRealSortOrder(), $this->m_aArgs, $this->m_aAttToLoad, $this->m_aExtendedDataSpec, $this->m_iLimitCount, $this->m_iLimitStart);
		}
		else
		{
			$sSQL = MetaModel::MakeSelectQuery($this->m_oFilter, $this->GetRealSortOrder(), $this->m_aArgs, $this->m_aAttToLoad, $this->m_aExtendedDataSpec);
		}
		$resQuery = CMDBSource::Query($sSQL);
		if (!$resQuery) return;

		$sClass = $this->m_oFilter->GetClass();
		while ($aRow = CMDBSource::FetchArray($resQuery))
		{
			$aObjects = array();
			foreach ($this->m_oFilter->GetSelectedClasses() as $sClassAlias => $sClass)
			{
				if (is_null($aRow[$sClassAlias.'id']))
				{
					$oObject = null;
				}
				else
				{
					$oObject = MetaModel::GetObjectByRow($sClass, $aRow, $sClassAlias, $this->m_aAttToLoad, $this->m_aExtendedDataSpec);
				}

				$aObjects[$sClassAlias] = $oObject;
			}
			$this->AddObjectExtended($aObjects, true /* internal load */);
		}
		CMDBSource::FreeResult($resQuery);
	}

	public function Count()
	{
		if ($this->m_bLoaded && ($this->m_iLimitCount == 0) && ($this->m_iLimitStart == 0))
		{
			return count($this->m_aData);
		}
		else
		{
			if (is_null($this->m_iCount))
			{
				$sSQL = MetaModel::MakeSelectQuery($this->m_oFilter, array(), $this->m_aArgs, null, null, 0, 0, true);
				$resQuery = CMDBSource::Query($sSQL);
				if (!$resQuery) return 0;
		
				$aRow = CMDBSource::FetchArray($resQuery);
				CMDBSource::FreeResult($resQuery);
				$this->m_iCount = $aRow['COUNT'];
			}
			return $this->m_iCount;
		}
	}

	public function Fetch($sClassAlias = '')
	{
		if (!$this->m_bLoaded) $this->Load();

		if ($this->m_iCurrRow >= count($this->m_aData))
		{
			return null;
		}
	
		if (strlen($sClassAlias) == 0)
		{
			$sClassAlias = $this->m_oFilter->GetClassAlias();
		}
		$oRetObj = $this->m_aData[$this->m_iCurrRow][$sClassAlias];
		$this->m_iCurrRow++;
		return $oRetObj;
	}

	// Return the whole line if several classes have been specified in the query
	//
	public function FetchAssoc()
	{
		if (!$this->m_bLoaded) $this->Load();

		if ($this->m_iCurrRow >= count($this->m_aData))
		{
			return null;
		}
	
		$aRetObjects = $this->m_aData[$this->m_iCurrRow];
		$this->m_iCurrRow++;
		return $aRetObjects;
	}

	public function Rewind()
	{
		if ($this->m_bLoaded)
		{
			$this->Seek(0);
		}
	}

	public function Seek($iRow)
	{
		if (!$this->m_bLoaded) $this->Load();

		$this->m_iCurrRow = min($iRow, count($this->m_aData));
		return $this->m_iCurrRow;
	}

	public function AddObject($oObject, $sClassAlias = '')
	{
		if (!$this->m_bLoaded) $this->Load();

		if (strlen($sClassAlias) == 0)
		{
			$sClassAlias = $this->m_oFilter->GetClassAlias();
		}

		$iNextPos = count($this->m_aData);
		$this->m_aData[$iNextPos][$sClassAlias] = $oObject;
		if (!is_null($oObject))
		{
			$this->m_aId2Row[$sClassAlias][$oObject->GetKey()] = $iNextPos;
			$this->m_aAddedIds[$oObject->GetKey()] = true;
		}
	}

	protected function AddObjectExtended($aObjectArray, $bInternalLoad = false)
	{
		if (!$this->m_bLoaded) $this->Load();

		$iNextPos = count($this->m_aData);

		foreach ($aObjectArray as $sClassAlias => $oObject)
		{
			$this->m_aData[$iNextPos][$sClassAlias] = $oObject;
			if (!is_null($oObject))
			{
				$this->m_aId2Row[$sClassAlias][$oObject->GetKey()] = $iNextPos;
				if (!$bInternalLoad)
				{
					$this->m_aAddedIds[$oObject->GetKey()] = true;
				}
			}
		}
	}

	public function AddObjectArray($aObjects, $sClassAlias = '')
	{
		if (!$this->m_bLoaded) $this->Load();

		// #@# todo - add a check on the object class ?
		foreach ($aObjects as $oObj)
		{
			$this->AddObject($oObj, $sClassAlias);
		}
	}

	public function Merge($oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			throw new CoreException("Could not merge two objects sets if they don't have the same root class");
		}
		if (!$this->m_bLoaded) $this->Load();

		$oObjectSet->Seek(0);
		while ($oObject = $oObjectSet->Fetch())
		{
			$this->AddObject($oObject);
		}
	}

	public function CreateIntersect($oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			throw new CoreException("Could not 'intersect' two objects sets if they don't have the same root class");
		}
		if (!$this->m_bLoaded) $this->Load();

		$oNewSet = DBObjectSet::FromScratch($this->GetClass());

		$sClassAlias = $this->m_oFilter->GetClassAlias();
		$oObjectSet->Seek(0);
		while ($oObject = $oObjectSet->Fetch())
		{
			if (array_key_exists($oObject->GetKey(), $this->m_aId2Row[$sClassAlias]))
			{
				$oNewSet->AddObject($oObject);
			}
		}
		return $oNewSet;
	}

	// Note: This verb works only with objects existing in the database
	//
	public function HasSameContents($oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			return false;
		}
		if (!$this->m_bLoaded) $this->Load();

		if ($this->Count() != $oObjectSet->Count())
		{
			return false;
		}
		$sClassAlias = $this->m_oFilter->GetClassAlias();
		$oObjectSet->Rewind();
		while ($oObject = $oObjectSet->Fetch())
		{
			$iObjectKey = $oObject->GetKey();
			if ($iObjectKey < 0)
			{
				return false;
			}
			if (!array_key_exists($iObjectKey, $this->m_aId2Row[$sClassAlias]))
			{
				return false;
			}
			$iRow = $this->m_aId2Row[$sClassAlias][$iObjectKey];
			$oSibling = $this->m_aData[$iRow][$sClassAlias];
			if (!$oObject->Equals($oSibling))
			{
				return false;
			}
		}
		return true;
	}

	public function CreateDelta($oObjectSet)
	{
		if ($this->GetRootClass() != $oObjectSet->GetRootClass())
		{
			throw new CoreException("Could not 'delta' two objects sets if they don't have the same root class");
		}
		if (!$this->m_bLoaded) $this->Load();

		$oNewSet = DBObjectSet::FromScratch($this->GetClass());

		$sClassAlias = $this->m_oFilter->GetClassAlias();
		$oObjectSet->Seek(0);
		while ($oObject = $oObjectSet->Fetch())
		{
			if (!array_key_exists($oObject->GetKey(), $this->m_aId2Row[$sClassAlias]))
			{
				$oNewSet->AddObject($oObject);
			}
		}
		return $oNewSet;
	}

	public function GetRelatedObjects($sRelCode, $iMaxDepth = 99)
	{
		$aRelatedObjs = array();

		$aVisited = array(); // optimization for consecutive calls of MetaModel::GetRelatedObjects
		$this->Seek(0);
		while ($oObject = $this->Fetch())
		{
			$aMore = $oObject->GetRelatedObjects($sRelCode, $iMaxDepth, $aVisited);
			foreach ($aMore as $sClass => $aRelated)
			{
				foreach ($aRelated as $iObj => $oObj)
				{
					if (!isset($aRelatedObjs[$sClass][$iObj]))
					{
						$aRelatedObjs[$sClass][$iObj] = $oObj;
					}
				}
			}
		}
		return $aRelatedObjs;
	}
	
	/**
	 * Builds an object that contains the values that are common to all the objects
	 * in the set. If for a given attribute, objects in the set have various values
	 * then the resulting object will contain null for this value.
	 * @param $aValues Hash Output: the distribution of the values, in the set, for each attribute
	 * @return Object
	 */
	public function ComputeCommonObject(&$aValues)
	{
		$sClass = $this->GetClass();
		$aList = MetaModel::ListAttributeDefs($sClass);
		$aValues = array();
		foreach($aList as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsScalar())
			{
				$aValues[$sAttCode] = array();
			}
		}
		$this->Rewind();
		while($oObj = $this->Fetch())
		{
			foreach($aList as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
				{
					$currValue = $oObj->Get($sAttCode);
					if (is_object($currValue)) continue; // Skip non scalar values...
					if(!array_key_exists($currValue, $aValues[$sAttCode]))
					{
						$aValues[$sAttCode][$currValue] = array('count' => 1, 'display' => $oObj->GetAsHTML($sAttCode)); 
					}
					else
					{
						$aValues[$sAttCode][$currValue]['count']++; 
					}
				}
			}
		}
		
		foreach($aValues as $sAttCode => $aMultiValues)
		{
			if (count($aMultiValues) > 1)
			{
				uasort($aValues[$sAttCode], 'HashCountComparison');
			}
		}
						
		
		// Now create an object that has values for the homogenous values only				
		$oCommonObj = new $sClass(); // @@ What if the class is abstract ?
		$aComments = array();

		$iFormId = cmdbAbstractObject::GetNextFormId(); // Identifier that prefixes all the form fields
		$sReadyScript = '';
		$aDependsOn = array();
		$sFormPrefix = '2_';
		foreach($aList as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsScalar() && $oAttDef->IsWritable())
			{
				if ($oAttDef->GetEditClass() == 'One Way Password')
				{
					$oCommonObj->Set($sAttCode, null);
				}
				else
				{
					$iCount = count($aValues[$sAttCode]);
					if ($iCount == 1)
					{
						// Homogenous value
						reset($aValues[$sAttCode]);
						$aKeys = array_keys($aValues[$sAttCode]);
						$currValue = $aKeys[0]; // The only value is the first key
						$oCommonObj->Set($sAttCode, $currValue);
					}
					else
					{
						// Non-homogenous value
						$oCommonObj->Set($sAttCode, null);
					}
				}
			}
		}
		$this->Rewind();
		return $oCommonObj;
	}

	/**
	 * List the constant fields (and their value) in the given query
	 * @return Hash [Alias][AttCode] => value
	 */
	public function ListConstantFields()
	{
		$aScalarArgs = $this->ExpandArgs();
		$aConst = $this->m_oFilter->ListConstantFields();
				
		foreach($aConst as $sClassAlias => $aVals)
		{
			foreach($aVals as $sCode => $oExpr)
			{
				if ($oExpr instanceof ScalarExpression)
				{
					$aConst[$sClassAlias][$sCode] = $oExpr->GetValue();
				}
				else //Variable
				{
					$aConst[$sClassAlias][$sCode] = $aScalarArgs[$oExpr->GetName()];
				}
			}
		}
		return $aConst;		
	}
	
	protected function ExpandArgs()
	{
		$aScalarArgs = $this->m_oFilter->GetInternalParams();
		foreach($this->m_aArgs as $sArgName => $value)
		{
			if (MetaModel::IsValidObject($value))
			{
				if (strpos($sArgName, '->object()') === false)
				{
					// Lazy syntax - develop the object contextual parameters
					$aScalarArgs = array_merge($aScalarArgs, $value->ToArgsForQuery($sArgName));
				}
				else
				{
					// Leave as is
					$aScalarArgs[$sArgName] = $value;
				}
			}
			else
			{
				$aScalarArgs[$sArgName] = (string) $value;
			}
		}
		$aScalarArgs['current_contact_id'] = UserRights::GetContactId();
		return $aScalarArgs;		
	}
	
	public function ApplyParameters()
	{
		$aScalarArgs = $this->ExpandArgs();
		$this->m_oFilter->ApplyParameters($aScalarArgs);
	}
}

/**
 * Helper function to perform a custom sort of a hash array
 */
function HashCountComparison($a, $b) // Sort descending on 'count'
{
    if ($a['count'] == $b['count'])
    {
        return 0;
    }
    return ($a['count'] > $b['count']) ? -1 : 1;
}

?>
