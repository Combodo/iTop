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
 * Classes defined for lexical analyze (see oql-parser.y)
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// Position a string within an OQL query
// This is a must if we want to be able to pinpoint an error at any stage of the query interpretation
// In particular, the normalization phase requires this
class OqlName
{
	protected $m_sValue;
	protected $m_iPos;

	public function __construct($sValue, $iPos)
	{
		$this->m_iPos = $iPos;
		$this->m_sValue = $sValue;
	}

	public function GetValue()
	{
		return $this->m_sValue;
	}

	public function GetPos()
	{
		return $this->m_iPos;
	}
	
	public function __toString()
	{
		return $this->m_sValue;
	} 
}

/**
 * 
 * Store hexadecimal values as strings so that we can support 64-bit values
 *
 */
class OqlHexValue
{
	protected $m_sValue;

	public function __construct($sValue)
	{
		$this->m_sValue = $sValue;
	}
	
	public function __toString()
	{
		return $this->m_sValue;
	}
	
}

class OqlJoinSpec
{
	protected $m_oClass;
	protected $m_oClassAlias;
	protected $m_oLeftField;
	protected $m_oRightField;
	protected $m_sOperator;

	protected $m_oNextJoinspec;

	public function __construct($oClass, $oClassAlias, BinaryExpression $oExpression)
	{
		$this->m_oClass = $oClass;
		$this->m_oClassAlias = $oClassAlias;
		$this->m_oLeftField = $oExpression->GetLeftExpr();
		$this->m_oRightField = $oExpression->GetRightExpr();
		$this->m_oRightField = $oExpression->GetRightExpr();
		$this->m_sOperator = $oExpression->GetOperator();
	}

	public function GetClass()
	{
		return $this->m_oClass->GetValue();
	}
	public function GetClassAlias()
	{
		return $this->m_oClassAlias->GetValue();
	}

	public function GetClassDetails()
	{
		return $this->m_oClass;
	}
	public function GetClassAliasDetails()
	{
		return $this->m_oClassAlias;
	}

	public function GetLeftField()
	{
		return $this->m_oLeftField;
	}
	public function GetRightField()
	{
		return $this->m_oRightField;
	}
	public function GetOperator()
	{
		return $this->m_sOperator;
	}
}

interface CheckableExpression
{
	/**
	 * Check the validity of the expression with regard to the data model
	 * and the query in which it is used
	 *
	 * @param ModelReflection $oModelReflection MetaModel to consider
	 * @param array $aAliases Aliases to class names (for the current query)
	 * @param string $sSourceQuery For the reporting
	 * @throws OqlNormalizeException
	 */	 	
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery);
}

class BinaryOqlExpression extends BinaryExpression implements CheckableExpression
{
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		$this->m_oLeftExpr->Check($oModelReflection, $aAliases, $sSourceQuery);
		$this->m_oRightExpr->Check($oModelReflection, $aAliases, $sSourceQuery);
	}
}

class ScalarOqlExpression extends ScalarExpression implements CheckableExpression
{
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		// a scalar is always fine
	}
}

class FieldOqlExpression extends FieldExpression implements CheckableExpression
{
	protected $m_oParent;
	protected $m_oName;

	public function __construct($oName, $oParent = null)
	{
		if (is_null($oParent))
		{
			$oParent = new OqlName('', 0);
		}
		$this->m_oParent = $oParent;
		$this->m_oName = $oName;

		parent::__construct($oName->GetValue(), $oParent->GetValue());
	}

	public function GetParentDetails()
	{
		return $this->m_oParent;
	}

	public function GetNameDetails()
	{
		return $this->m_oName;
	}

	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		$sClassAlias = $this->GetParent();
		$sFltCode = $this->GetName();
		if (empty($sClassAlias))
		{
			// Try to find an alias
			// Build an array of field => array of aliases
			$aFieldClasses = array();
			foreach($aAliases as $sAlias => $sReal)
			{
				foreach($oModelReflection->GetFiltersList($sReal) as $sAnFltCode)
				{
					$aFieldClasses[$sAnFltCode][] = $sAlias;
				}
			}
			if (!array_key_exists($sFltCode, $aFieldClasses))
			{
				throw new OqlNormalizeException('Unknown filter code', $sSourceQuery, $this->GetNameDetails(), array_keys($aFieldClasses));
			}
			if (count($aFieldClasses[$sFltCode]) > 1)
			{
				throw new OqlNormalizeException('Ambiguous filter code', $sSourceQuery, $this->GetNameDetails());
			}
			$sClassAlias = $aFieldClasses[$sFltCode][0];
		}
		else
		{
			if (!array_key_exists($sClassAlias, $aAliases))
			{
				throw new OqlNormalizeException('Unknown class [alias]', $sSourceQuery, $this->GetParentDetails(), array_keys($aAliases));
			}
			$sClass = $aAliases[$sClassAlias];
			if (!$oModelReflection->IsValidFilterCode($sClass, $sFltCode))
			{
				throw new OqlNormalizeException('Unknown filter code', $sSourceQuery, $this->GetNameDetails(), $oModelReflection->GetFiltersList($sClass));
			}
		}
	}
}

class VariableOqlExpression extends VariableExpression implements CheckableExpression
{
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		// a scalar is always fine
	}
}

class ListOqlExpression extends ListExpression implements CheckableExpression
{
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		foreach ($this->GetItems() as $oItemExpression)
		{
			$oItemExpression->Check($oModelReflection, $aAliases, $sSourceQuery);
		}
	}
}

class FunctionOqlExpression extends FunctionExpression implements CheckableExpression
{
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		foreach ($this->GetArgs() as $oArgExpression)
		{
			$oArgExpression->Check($oModelReflection, $aAliases, $sSourceQuery);
		}
	}
}

class IntervalOqlExpression extends IntervalExpression implements CheckableExpression
{
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		// an interval is always fine (made of a scalar and unit)
	}
}

abstract class OqlQuery
{
	protected $m_aJoins; // array of OqlJoinSpec
	protected $m_oCondition; // condition tree (expressions)

	public function __construct($oCondition = null, $aJoins = null)
	{
		$this->m_aJoins = $aJoins;
		$this->m_oCondition = $oCondition;
	}

	public function GetJoins()
	{
		return $this->m_aJoins;
	}
	public function GetCondition()
	{
		return $this->m_oCondition;
	}
}

class OqlObjectQuery extends OqlQuery
{
	protected $m_aSelect; // array of selected classes
	protected $m_oClass;
	protected $m_oClassAlias;

	public function __construct($oClass, $oClassAlias, $oCondition = null, $aJoins = null, $aSelect = null)
	{
		$this->m_aSelect = $aSelect;
		$this->m_oClass = $oClass;
		$this->m_oClassAlias = $oClassAlias;
		parent::__construct($oCondition, $aJoins);
	}

	public function GetSelectedClasses()
	{
		return $this->m_aSelect;
	}
	public function GetClass()
	{
		return $this->m_oClass->GetValue();
	}
	public function GetClassAlias()
	{
		return $this->m_oClassAlias->GetValue();
	}

	public function GetClassDetails()
	{
		return $this->m_oClass;
	}
	public function GetClassAliasDetails()
	{
		return $this->m_oClassAlias;
	}

	/**
	 * Recursively check the validity of the expression with regard to the data model
	 * and the query in which it is used
	 * 	 	 
	 * @param ModelReflection $oModelReflection MetaModel to consider	 	
	 * @throws OqlNormalizeException
	 */	 	
	public function Check(ModelReflection $oModelReflection, $sSourceQuery)
	{
		$sClass = $this->GetClass();
		$sClassAlias = $this->GetClassAlias();

		if (!$oModelReflection->IsValidClass($sClass))
		{
			throw new UnknownClassOqlException($sSourceQuery, $this->GetClassDetails(), $oModelReflection->GetClasses());
		}

		$aAliases = array($sClassAlias => $sClass);

		$aJoinSpecs = $this->GetJoins();
		if (is_array($aJoinSpecs))
		{
			foreach ($aJoinSpecs as $oJoinSpec)
			{
				$sJoinClass = $oJoinSpec->GetClass();
				$sJoinClassAlias = $oJoinSpec->GetClassAlias();
				if (!$oModelReflection->IsValidClass($sJoinClass))
				{
					throw new UnknownClassOqlException($sSourceQuery, $oJoinSpec->GetClassDetails(), $oModelReflection->GetClasses());
				}
				if (array_key_exists($sJoinClassAlias, $aAliases))
				{
					if ($sJoinClassAlias != $sJoinClass)
					{
						throw new OqlNormalizeException('Duplicate class alias', $sSourceQuery, $oJoinSpec->GetClassAliasDetails());
					}
					else
					{
						throw new OqlNormalizeException('Duplicate class name', $sSourceQuery, $oJoinSpec->GetClassDetails());
					}
				} 

				// Assumption: ext key on the left only !!!
				// normalization should take care of this
				$oLeftField = $oJoinSpec->GetLeftField();
				$sFromClass = $oLeftField->GetParent();
				$sExtKeyAttCode = $oLeftField->GetName();

				$oRightField = $oJoinSpec->GetRightField();
				$sToClass = $oRightField->GetParent();
				$sPKeyDescriptor = $oRightField->GetName();
				if ($sPKeyDescriptor != 'id')
				{
					throw new OqlNormalizeException('Wrong format for Join clause (right hand), expecting an id', $sSourceQuery, $oRightField->GetNameDetails(), array('id'));
				}

				$aAliases[$sJoinClassAlias] = $sJoinClass;

				if (!array_key_exists($sFromClass, $aAliases))
				{
					throw new OqlNormalizeException('Unknown class in join condition (left expression)', $sSourceQuery, $oLeftField->GetParentDetails(), array_keys($aAliases));
				}
				if (!array_key_exists($sToClass, $aAliases))
				{
					throw new OqlNormalizeException('Unknown class in join condition (right expression)', $sSourceQuery, $oRightField->GetParentDetails(), array_keys($aAliases));
				}
				$aExtKeys = $oModelReflection->ListAttributes($aAliases[$sFromClass], 'AttributeExternalKey');
				if (!array_key_exists($sExtKeyAttCode, $aExtKeys))
				{
					throw new OqlNormalizeException('Unknown external key in join condition (left expression)', $sSourceQuery, $oLeftField->GetNameDetails(), array_keys($aExtKeys));
				}

				if ($sFromClass == $sJoinClassAlias)
				{
					$sTargetClass = $oModelReflection->GetAttributeProperty($aAliases[$sFromClass], $sExtKeyAttCode, 'targetclass');
					if(!$oModelReflection->IsSameFamilyBranch($aAliases[$sToClass], $sTargetClass))
					{
						throw new OqlNormalizeException("The joined class ($aAliases[$sFromClass]) is not compatible with the external key, which is pointing to $sTargetClass", $sSourceQuery, $oLeftField->GetNameDetails());
					}
				}
				else
				{
					$sOperator = $oJoinSpec->GetOperator();
					switch($sOperator)
					{
						case '=':
						$iOperatorCode = TREE_OPERATOR_EQUALS;
						break;
						case 'BELOW':
						$iOperatorCode = TREE_OPERATOR_BELOW;
						break;
						case 'BELOW_STRICT':
						$iOperatorCode = TREE_OPERATOR_BELOW_STRICT;
						break;
						case 'NOT_BELOW':
						$iOperatorCode = TREE_OPERATOR_NOT_BELOW;
						break;
						case 'NOT_BELOW_STRICT':
						$iOperatorCode = TREE_OPERATOR_NOT_BELOW_STRICT;
						break;
						case 'ABOVE':
						$iOperatorCode = TREE_OPERATOR_ABOVE;
						break;
						case 'ABOVE_STRICT':
						$iOperatorCode = TREE_OPERATOR_ABOVE_STRICT;
						break;
						case 'NOT_ABOVE':
						$iOperatorCode = TREE_OPERATOR_NOT_ABOVE;
						break;
						case 'NOT_ABOVE_STRICT':
						$iOperatorCode = TREE_OPERATOR_NOT_ABOVE_STRICT;
						break;
					}
					$sTargetClass = $oModelReflection->GetAttributeProperty($aAliases[$sFromClass], $sExtKeyAttCode, 'targetclass');
					if(!$oModelReflection->IsSameFamilyBranch($aAliases[$sToClass], $sTargetClass))
					{
						throw new OqlNormalizeException("The joined class ($aAliases[$sToClass]) is not compatible with the external key, which is pointing to $sTargetClass", $sSourceQuery, $oLeftField->GetNameDetails());
					}
					$aAttList = $oModelReflection->ListAttributes($aAliases[$sFromClass]);
					$sAttType = $aAttList[$sExtKeyAttCode];
					if(($iOperatorCode != TREE_OPERATOR_EQUALS) && !is_subclass_of($sAttType, 'AttributeHierarchicalKey') && ($sAttType != 'AttributeHierarchicalKey'))
					{
						throw new OqlNormalizeException("The specified tree operator $sOperator is not applicable to the key", $sSourceQuery, $oLeftField->GetNameDetails());
					}
				}
			}
		}

		// Check the select information
		//
		$aSelected = array();
		foreach ($this->GetSelectedClasses() as $oClassDetails)
		{
			$sClassToSelect = $oClassDetails->GetValue();
			if (!array_key_exists($sClassToSelect, $aAliases))
			{
				throw new OqlNormalizeException('Unknown class [alias]', $sSourceQuery, $oClassDetails, array_keys($aAliases));
			}
			$aSelected[$sClassToSelect] = $aAliases[$sClassToSelect];
		}

		// Check the condition tree
		//
		if ($this->m_oCondition instanceof Expression)
		{
			$this->m_oCondition->Check($oModelReflection, $aAliases, $sSourceQuery);
		}
	}
}

?>
