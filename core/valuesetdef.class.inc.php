<?php
// Copyright (C) 2010-2017 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2017 Combodo SARL
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


	public function GetValues($aArgs, $sContains = '', $sOperation = 'contains')
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
	protected $m_sOperation;
	protected $m_sFilterExpr; // in OQL
	protected $m_sValueAttCode;
	protected $m_aOrderBy;
	protected $m_oExtraCondition;
	private $m_bAllowAllData;
	private $m_aModifierProperties;
	private $m_bSort;
	private $m_iLimit;


	/**
	 * @param hash $aOrderBy Array of '[<classalias>.]attcode' => bAscending
	 */	
	public function __construct($sFilterExp, $sValueAttCode = '', $aOrderBy = array(), $bAllowAllData = false, $aModifierProperties = array())
	{
		$this->m_sContains = '';
		$this->m_sOperation = '';
		$this->m_sFilterExpr = $sFilterExp;
		$this->m_sValueAttCode = $sValueAttCode;
		$this->m_aOrderBy = $aOrderBy;
		$this->m_bAllowAllData = $bAllowAllData;
		$this->m_aModifierProperties = $aModifierProperties;
		$this->m_oExtraCondition = null;
		$this->m_bSort = true;
		$this->m_iLimit = 0;
	}

	public function SetModifierProperty($sPluginClass, $sProperty, $value)
	{
		$this->m_aModifierProperties[$sPluginClass][$sProperty] = $value;
		$this->m_bIsLoaded = false;
	}

	/**
	 * @param \DBSearch $oFilter
	 * @deprecated use SetCondition
	 */
	public function AddCondition(DBSearch $oFilter)
	{
		$this->SetCondition($oFilter);
	}

	public function SetCondition(DBSearch $oFilter)
	{
		$this->m_oExtraCondition = $oFilter;
		$this->m_bIsLoaded = false;
	}

	public function ToObjectSet($aArgs = array(), $sContains = '', $iAdditionalValue = null)
	{
		if ($this->m_bAllowAllData)
		{
			$oFilter = DBObjectSearch::FromOQL_AllData($this->m_sFilterExpr);
		}
		else
		{
			$oFilter = DBObjectSearch::FromOQL($this->m_sFilterExpr);
		}
		if (!is_null($this->m_oExtraCondition))
		{
			$oFilter = $oFilter->Intersect($this->m_oExtraCondition);
		}
		foreach($this->m_aModifierProperties as $sPluginClass => $aProperties)
		{
			foreach ($aProperties as $sProperty => $value)
			{
				$oFilter->SetModifierProperty($sPluginClass, $sProperty, $value);
			}
		}
		if ($iAdditionalValue > 0)
		{
			$oSearchAdditionalValue = new DBObjectSearch($oFilter->GetClass());
			$oSearchAdditionalValue->AddConditionExpression( new BinaryExpression(
			    new FieldExpression('id', $oSearchAdditionalValue->GetClassAlias()),
                '=',
                new VariableExpression('current_extkey_id'))
            );
			$oSearchAdditionalValue->AllowAllData();
			$oSearchAdditionalValue->SetArchiveMode(true);
			$oSearchAdditionalValue->SetInternalParams( array('current_extkey_id' => $iAdditionalValue) );

			$oFilter = new DBUnionSearch(array($oFilter, $oSearchAdditionalValue));
		}

		return new DBObjectSet($oFilter, $this->m_aOrderBy, $aArgs);
	}

    /**
     * @param        $aArgs
     * @param string $sContains
     * @param string $sOperation for the values @see self::LoadValues()
     *
     * @return array
     * @throws CoreException
     * @throws OQLException
     */
    public function GetValues($aArgs, $sContains = '', $sOperation = 'contains')
	{
		if (!$this->m_bIsLoaded || ($sContains != $this->m_sContains) || ($sOperation != $this->m_sOperation))
		{
			$this->LoadValues($aArgs, $sContains, $sOperation);
			$this->m_bIsLoaded = true;
		}
		// The results are already filtered and sorted (on friendly name)
		$aRet = $this->m_aValues;
		return $aRet;
	}

	/**
	 * @param $aArgs
	 * @param string $sContains
	 * @param string $sOperation 'contains' or 'equals_start_with'
	 *
	 * @return bool
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	protected function LoadValues($aArgs, $sContains = '', $sOperation = 'contains')
	{
		$this->m_sContains = $sContains;
		$this->m_sOperation = $sOperation;

		$this->m_aValues = array();
		
		if ($this->m_bAllowAllData)
		{
			$oFilter = DBObjectSearch::FromOQL_AllData($this->m_sFilterExpr);
		}
		else
		{
			$oFilter = DBObjectSearch::FromOQL($this->m_sFilterExpr);
			$oFilter->SetShowObsoleteData(utils::ShowObsoleteData());
		}
		if (!$oFilter) return false;
		if (!is_null($this->m_oExtraCondition))
		{
			$oFilter = $oFilter->Intersect($this->m_oExtraCondition);
		}
		foreach($this->m_aModifierProperties as $sPluginClass => $aProperties)
		{
			foreach ($aProperties as $sProperty => $value)
			{
				$oFilter->SetModifierProperty($sPluginClass, $sProperty, $value);
			}
		}

		$oExpression = DBObjectSearch::GetPolymorphicExpression($oFilter->GetClass(), 'friendlyname');
		$aFields = $oExpression->ListRequiredFields();
		$sClass = $oFilter->GetClass();
		foreach($aFields as $sField)
		{
			$aFieldItems = explode('.', $sField);
			if ($aFieldItems[0] != $sClass)
			{
				$sOperation = 'contains';
				break;
			}
		}

		switch ($sOperation)
		{
			case 'equals':
				$aAttributes = MetaModel::GetFriendlyNameAttributeCodeList($sClass);
				$sClassAlias = $oFilter->GetClassAlias();
				$aFilters = array();
				$oValueExpr = new ScalarExpression($sContains);
				foreach($aAttributes as $sAttribute)
				{
					$oNewFilter = $oFilter->DeepClone();
					$oNameExpr = new FieldExpression($sAttribute, $sClassAlias);
					$oCondition = new BinaryExpression($oNameExpr, '=', $oValueExpr);
					$oNewFilter->AddConditionExpression($oCondition);
					$aFilters[] = $oNewFilter;
				}
                // Unions are much faster than OR conditions
				$oFilter = new DBUnionSearch($aFilters);
				break;
            case 'start_with':
                $aAttributes = MetaModel::GetFriendlyNameAttributeCodeList($sClass);
                $sClassAlias = $oFilter->GetClassAlias();
                $aFilters = array();
                $oValueExpr = new ScalarExpression($sContains.'%');
                foreach($aAttributes as $sAttribute)
                {
                    $oNewFilter = $oFilter->DeepClone();
                    $oNameExpr = new FieldExpression($sAttribute, $sClassAlias);
                    $oCondition = new BinaryExpression($oNameExpr, 'LIKE', $oValueExpr);
                    $oNewFilter->AddConditionExpression($oCondition);
                    $aFilters[] = $oNewFilter;
                }
                // Unions are much faster than OR conditions
                $oFilter = new DBUnionSearch($aFilters);
                break;

			default:
				$oValueExpr = new ScalarExpression('%'.$sContains.'%');
				$oNameExpr = new FieldExpression('friendlyname', $oFilter->GetClassAlias());
				$oNewCondition = new BinaryExpression($oNameExpr, 'LIKE', $oValueExpr);
				$oFilter->AddConditionExpression($oNewCondition);
				break;
		}

		$oObjects = new DBObjectSet($oFilter, $this->m_aOrderBy, $aArgs, null, $this->m_iLimit, 0, $this->m_bSort);
		if (empty($this->m_sValueAttCode))
		{
			$aAttToLoad = array($oFilter->GetClassAlias() => array('friendlyname'));
		}
		else
		{
			$aAttToLoad = array($oFilter->GetClassAlias() => array($this->m_sValueAttCode));
		}
		$oObjects->OptimizeColumnLoad($aAttToLoad);
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

	/**
	 * @param $iLimit
	 */
	public function SetLimit($iLimit)
	{
		$this->m_iLimit = $iLimit;
	}

	/**
	 * @param $bSort
	 */
	public function SetSort($bSort)
	{
		$this->m_bSort = $bSort;
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

	// Helper to export the data model
	public function GetValueList()
	{
		$this->LoadValues(null);
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

class ValueSetEnumPadded extends ValueSetEnum
{
	public function __construct($Values)
	{
		parent::__construct($Values);
		if (is_string($Values))
		{
			$this->LoadValues(null);
		}
		else
		{
			$this->m_aValues = $Values;
		}
		$aPaddedValues = array();
		foreach ($this->m_aValues as $sKey => $sVal)
		{
			$sKey = str_pad($sKey, 3, '_', STR_PAD_LEFT);
			$aPaddedValues[$sKey] = $sVal;
		}
		$this->m_values = $aPaddedValues;
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
