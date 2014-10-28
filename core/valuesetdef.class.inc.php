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
 * Value set definitions (from a fixed list or from a query, etc.)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once('MyHelpers.class.inc.php');

/**
 * ValueSetDefinition
 * value sets API and implementations
 *
 * @package     iTopORM
 */
abstract class ValueSetDefinition
{
	protected $m_bIsLoaded = false;
	protected $m_aValues = array();


	// Displayable description that could be computed out of the std usage context
	public function GetValuesDescription()
	{
		$aValues = $this->GetValues(array(), '');
		$aDisplayedValues = array();
		foreach($aValues as $key => $value)
		{
			$aDisplayedValues[] = "$key => $value";
		}
		$sAllowedValues = implode(', ', $aDisplayedValues);
		return $sAllowedValues;
	}


	public function GetValues($aArgs, $sContains = '')
	{
		if (!$this->m_bIsLoaded)
		{
			$this->LoadValues($aArgs);
			$this->m_bIsLoaded = true;
		}
		if (strlen($sContains) == 0)
		{
			// No filtering
			$aRet = $this->m_aValues;
		}
		else
		{
			// Filter on results containing the needle <sContain>
			$aRet = array();
			foreach ($this->m_aValues as $sKey=>$sValue)
			{
				if (stripos($sValue, $sContains) !== false)
				{
					$aRet[$sKey] = $sValue;
				}
			}
		}
		// Sort on the display value
		asort($aRet);
		return $aRet;
	}

	abstract protected function LoadValues($aArgs);
}


/**
 * Set of existing values for an attribute, given a search filter 
 *
 * @package     iTopORM
 */
class ValueSetObjects extends ValueSetDefinition
{
	protected $m_sContains;
	protected $m_sFilterExpr; // in OQL
	protected $m_sValueAttCode;
	protected $m_aOrderBy;
	protected $m_aExtraConditions;
	private $m_bAllowAllData;
	private $m_aModifierProperties;

	/**
	 * @param hash $aOrderBy Array of '[<classalias>.]attcode' => bAscending
	 */	
	public function __construct($sFilterExp, $sValueAttCode = '', $aOrderBy = array(), $bAllowAllData = false, $aModifierProperties = array())
	{
		$this->m_sContains = '';
		$this->m_sFilterExpr = $sFilterExp;
		$this->m_sValueAttCode = $sValueAttCode;
		$this->m_aOrderBy = $aOrderBy;
		$this->m_bAllowAllData = $bAllowAllData;
		$this->m_aModifierProperties = $aModifierProperties;
		$this->m_aExtraConditions = array();
	}

	public function SetModifierProperty($sPluginClass, $sProperty, $value)
	{
		$this->m_aModifierProperties[$sPluginClass][$sProperty] = $value;
	}

	public function AddCondition(DBObjectSearch $oFilter)
	{
		$this->m_aExtraConditions[] = $oFilter;		
	}

	public function ToObjectSet($aArgs = array(), $sContains = '')
	{
		if ($this->m_bAllowAllData)
		{
			$oFilter = DBObjectSearch::FromOQL_AllData($this->m_sFilterExpr);
		}
		else
		{
			$oFilter = DBObjectSearch::FromOQL($this->m_sFilterExpr);
		}
		foreach($this->m_aExtraConditions as $oExtraFilter)
		{
			$oFilter->MergeWith($oExtraFilter);
		}
		foreach($this->m_aModifierProperties as $sPluginClass => $aProperties)
		{
			foreach ($aProperties as $sProperty => $value)
			{
				$oFilter->SetModifierProperty($sPluginClass, $sProperty, $value);
			}
		}

		return new DBObjectSet($oFilter, $this->m_aOrderBy, $aArgs);
	}

	public function GetValues($aArgs, $sContains = '')
	{
		if (!$this->m_bIsLoaded || ($sContains != $this->m_sContains))
		{
			$this->LoadValues($aArgs, $sContains);
			$this->m_bIsLoaded = true;
		}
		// The results are already filtered and sorted (on friendly name)
		$aRet = $this->m_aValues;
		return $aRet;
	}

	protected function LoadValues($aArgs, $sContains = '')
	{
		$this->m_sContains = $sContains;

		$this->m_aValues = array();
		
		if ($this->m_bAllowAllData)
		{
			$oFilter = DBObjectSearch::FromOQL_AllData($this->m_sFilterExpr);
		}
		else
		{
			$oFilter = DBObjectSearch::FromOQL($this->m_sFilterExpr);
		}
		if (!$oFilter) return false;
		foreach($this->m_aExtraConditions as $oExtraFilter)
		{
			$oFilter->MergeWith($oExtraFilter);
		}
		foreach($this->m_aModifierProperties as $sPluginClass => $aProperties)
		{
			foreach ($aProperties as $sProperty => $value)
			{
				$oFilter->SetModifierProperty($sPluginClass, $sProperty, $value);
			}
		}

		$oValueExpr = new ScalarExpression('%'.$sContains.'%');
		$oNameExpr = new FieldExpression('friendlyname', $oFilter->GetClassAlias());
		$oNewCondition = new BinaryExpression($oNameExpr, 'LIKE', $oValueExpr);
		$oFilter->AddConditionExpression($oNewCondition);

		$oObjects = new DBObjectSet($oFilter, $this->m_aOrderBy, $aArgs);
		while ($oObject = $oObjects->Fetch())
		{
			if (empty($this->m_sValueAttCode))
			{
				$this->m_aValues[$oObject->GetKey()] = $oObject->GetName();
			}
			else
			{
				$this->m_aValues[$oObject->GetKey()] = $oObject->Get($this->m_sValueAttCode);
			}
		}
		return true;
	}
	
	public function GetValuesDescription()
	{
		return 'Filter: '.$this->m_sFilterExpr;
	}

	public function GetFilterExpression()
	{
		return $this->m_sFilterExpr;
	}
}


/**
 * Set of existing values for a link set attribute, given a relation code 
 *
 * @package     iTopORM
 */
class ValueSetRelatedObjectsFromLinkSet extends ValueSetDefinition
{
	protected $m_sLinkSetAttCode;
	protected $m_sExtKeyToRemote;
	protected $m_sRelationCode;
	protected $m_iMaxDepth;
	protected $m_sTargetClass;
	protected $m_sTargetExtKey;
//	protected $m_aOrderBy;

	public function __construct($sLinkSetAttCode, $sExtKeyToRemote, $sRelationCode, $iMaxDepth, $sTargetClass, $sTargetLinkClass, $sTargetExtKey)
	{
		$this->m_sLinkSetAttCode = $sLinkSetAttCode;
		$this->m_sExtKeyToRemote = $sExtKeyToRemote;
		$this->m_sRelationCode = $sRelationCode;
		$this->m_iMaxDepth = $iMaxDepth;
		$this->m_sTargetClass = $sTargetClass;
		$this->m_sTargetLinkClass = $sTargetLinkClass;
		$this->m_sTargetExtKey = $sTargetExtKey;
//		$this->m_aOrderBy = $aOrderBy;
	}

	protected function LoadValues($aArgs)
	{
		$this->m_aValues = array();

		if (!array_key_exists('this', $aArgs))
		{
			throw new CoreException("Missing 'this' in arguments", array('args' => $aArgs));
		}		

		$oTarget = $aArgs['this->object()'];

		// Nodes from which we will start the search for neighbourhood
		$oNodes = DBObjectSet::FromLinkSet($oTarget, $this->m_sLinkSetAttCode, $this->m_sExtKeyToRemote);

		// Neighbours, whatever their class
		$aRelated = $oNodes->GetRelatedObjects($this->m_sRelationCode, $this->m_iMaxDepth);

		$sRootClass = MetaModel::GetRootClass($this->m_sTargetClass);
		if (array_key_exists($sRootClass, $aRelated))
		{
			$aLinksToCreate = array();
			foreach($aRelated[$sRootClass] as $iKey => $oObject)
			{
				if (MetaModel::IsParentClass($this->m_sTargetClass, get_class($oObject)))
				{
					$oNewLink = MetaModel::NewObject($this->m_sTargetLinkClass);
					$oNewLink->Set($this->m_sTargetExtKey, $iKey);
					//$oNewLink->Set('role', 'concerned by an impacted CI');

					$aLinksToCreate[] = $oNewLink;
				}
			}
			// #@# or AddObjectArray($aObjects) ?
			$oSetToCreate = DBObjectSet::FromArray($this->m_sTargetLinkClass, $aLinksToCreate);
			$this->m_aValues[$oObject->GetKey()] = $oObject->GetName();
		}

		return true;
	}
	
	public function GetValuesDescription()
	{
		return 'Filter: '.$this->m_sFilterExpr;
	}
}


/**
 * Fixed set values (could be hardcoded in the business model) 
 *
 * @package     iTopORM
 */
class ValueSetEnum extends ValueSetDefinition
{
	protected $m_values;

	public function __construct($Values)
	{
		$this->m_values = $Values;
	}

	// Helper to export the datat model
	public function GetValueList()
	{
		$this->LoadValues($aArgs = array());
		return $this->m_aValues;
	}

	protected function LoadValues($aArgs)
	{
		if (is_array($this->m_values))
		{
			$aValues = $this->m_values;
		}
		elseif (is_string($this->m_values) && strlen($this->m_values) > 0)
		{
			$aValues = array();
			foreach (explode(",", $this->m_values) as $sVal)
			{
				$sVal = trim($sVal);
				$sKey = $sVal; 
				$aValues[$sKey] = $sVal;
			}
		}
		else
		{
			$aValues = array();
		}
		$this->m_aValues = $aValues;
		return true;
	}
}

/**
 * Fixed set values, defined as a range: 0..59 (with an optional increment)
 *
 * @package     iTopORM
 */
class ValueSetRange extends ValueSetDefinition
{
	protected $m_iStart;
	protected $m_iEnd;

	public function __construct($iStart, $iEnd, $iStep = 1)
	{
		$this->m_iStart = $iStart;
		$this->m_iEnd = $iEnd;
		$this->m_iStep = $iStep;
	}

	protected function LoadValues($aArgs)
	{
		$iValue = $this->m_iStart;
		for($iValue = $this->m_iStart; $iValue <= $this->m_iEnd; $iValue += $this->m_iStep)
		{
			$this->m_aValues[$iValue] = $iValue;
		}
		return true;
	}
}


/**
 * Data model classes 
 *
 * @package     iTopORM
 */
class ValueSetEnumClasses extends ValueSetEnum
{
	protected $m_sCategories;

	public function __construct($sCategories = '', $sAdditionalValues = '')
	{
		$this->m_sCategories = $sCategories;
		parent::__construct($sAdditionalValues);
	}

	protected function LoadValues($aArgs)
	{
		// Call the parent to parse the additional values...
		parent::LoadValues($aArgs);
		
		// Translate the labels of the additional values
		foreach($this->m_aValues as $sClass => $void)
		{
			if (MetaModel::IsValidClass($sClass))
			{
				$this->m_aValues[$sClass] = MetaModel::GetName($sClass);
			}
			else
			{
				unset($this->m_aValues[$sClass]);
			}
		}

		// Then, add the classes from the category definition
		foreach (MetaModel::GetClasses($this->m_sCategories) as $sClass)
		{
			if (MetaModel::IsValidClass($sClass))
			{
				$this->m_aValues[$sClass] = MetaModel::GetName($sClass);
			}
			else
			{
				unset($this->m_aValues[$sClass]);
			}
		}

		return true;
	}
}
