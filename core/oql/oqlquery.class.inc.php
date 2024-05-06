<?php
// Copyright (C) 2010-2024 Combodo SAS
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
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

define('TREE_OPERATOR_EQUALS', 0);
define('TREE_OPERATOR_BELOW', 1);
define('TREE_OPERATOR_BELOW_STRICT', 2);
define('TREE_OPERATOR_NOT_BELOW', 3);
define('TREE_OPERATOR_NOT_BELOW_STRICT', 4);
define('TREE_OPERATOR_ABOVE', 5);
define('TREE_OPERATOR_ABOVE_STRICT', 6);
define('TREE_OPERATOR_NOT_ABOVE', 7);
define('TREE_OPERATOR_NOT_ABOVE_STRICT', 8);

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

class MatchOqlExpression extends MatchExpression implements CheckableExpression
{
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		$this->m_oLeftExpr->Check($oModelReflection, $aAliases, $sSourceQuery);
		$this->m_oRightExpr->Check($oModelReflection, $aAliases, $sSourceQuery);

		// Only field MATCHES scalar is allowed
		if (!$this->m_oLeftExpr instanceof FieldExpression)
		{
			throw new OqlNormalizeException('Only "field MATCHES string" syntax is allowed', $sSourceQuery, new OqlName($this->m_oLeftExpr->RenderExpression(true), 0));
		}
		// Only field MATCHES scalar is allowed
		if (!$this->m_oRightExpr instanceof ScalarExpression && !$this->m_oRightExpr instanceof VariableOqlExpression)
		{
			throw new OqlNormalizeException('Only "field MATCHES string" syntax is allowed', $sSourceQuery, new OqlName($this->m_oRightExpr->RenderExpression(true), 0));
		}
	}
}

class ScalarOqlExpression extends ScalarExpression implements CheckableExpression
{
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		// a scalar is always fine
	}
}

class NestedQueryOqlExpression extends NestedQueryExpression implements CheckableExpression
{
	/** @var OQLObjectQuery */
	private $m_oOQLObjectQuery;

	/**
	 * NestedQueryOqlExpression constructor.
	 *
	 * @param OQLObjectQuery $oOQLObjectQuery
	 */
	public function __construct($oOQLObjectQuery )
	{
		parent::__construct($oOQLObjectQuery->ToDBSearch(""));
		$this->m_oOQLObjectQuery = $oOQLObjectQuery;
	}

	/**
	 * Recursively check the validity of the expression with regard to the data model
	 * and the query in which it is used
	 *
	 * @param ModelReflection $oModelReflection MetaModel to consider
	 * @param array $aAliases
	 * @param string $sSourceQuery
	 *
	 * @throws \OqlNormalizeException
	 */
	public function Check(ModelReflection $oModelReflection, $aAliases, $sSourceQuery)
	{
		$this->m_oOQLObjectQuery->Check($oModelReflection, "", $aAliases);
	}

	public function GetOQLObjectQuery()
	{
		return $this->m_oOQLObjectQuery;
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
	public function __construct()
	{
	}

	/**
	 * Check the validity of the expression with regard to the data model
	 * and the query in which it is used
	 *
	 * @param ModelReflection $oModelReflection MetaModel to consider
	 * @param string $sSourceQuery
	 */
	abstract public function Check(ModelReflection $oModelReflection, $sSourceQuery);

	/**
	 * Determine the class
	 *
	 * @param ModelReflection $oModelReflection MetaModel to consider
	 * @return string
	 * @throws Exception
	 */
	abstract public function GetClass(ModelReflection $oModelReflection);

	/**
	 * Determine the class alias
	 *
	 * @return string
	 * @throws Exception
	 */
	abstract public function GetClassAlias();
}

class OqlObjectQuery extends OqlQuery
{
	protected $m_aSelect; // array of selected classes
	protected $m_oClass;
	protected $m_oClassAlias;
	protected $m_aJoins; // array of OqlJoinSpec
	protected $m_oCondition; // condition tree (expressions)

	public function __construct($oClass, $oClassAlias, $oCondition = null, $aJoins = null, $aSelect = null)
	{
		$this->m_aSelect = $aSelect;
		$this->m_oClass = $oClass;
		$this->m_oClassAlias = $oClassAlias;
		$this->m_aJoins = $aJoins;
		$this->m_oCondition = $oCondition;

		parent::__construct();
	}

	public function GetSelectedClasses()
	{
		return $this->m_aSelect;
	}

	/**
	 * Determine the class
	 *
	 * @param ModelReflection $oModelReflection MetaModel to consider
	 * @return string
	 * @throws Exception
	 */
	public function GetClass(ModelReflection $oModelReflection)
	{
		return $this->m_oClass->GetValue();
	}

	/**
	 * Determine the class alias
	 *
	 * @return string
	 * @throws Exception
	 */
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

	public function GetJoins()
	{
		return $this->m_aJoins;
	}
	public function GetCondition()
	{
		return $this->m_oCondition;
	}

	/**
	 * Recursively check the validity of the expression with regard to the data model
	 * and the query in which it is used
	 * 	 	 
	 * @param ModelReflection $oModelReflection MetaModel to consider	 	
	 * @throws OqlNormalizeException
	 */	 	
	public function Check(ModelReflection $oModelReflection, $sSourceQuery, $aParentAliases = array())
	{
		$sClass = $this->GetClass($oModelReflection);
		$sClassAlias = $this->GetClassAlias();

		if (!$oModelReflection->IsValidClass($sClass))
		{
			throw new UnknownClassOqlException($sSourceQuery, $this->GetClassDetails(), $oModelReflection->GetClasses());
		}

		$aAliases = array_merge(array($sClassAlias => $sClass),$aParentAliases);

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
				$aObjKeys = $oModelReflection->ListAttributes($aAliases[$sFromClass], 'AttributeObjectKey');
				$aAllKeys = array_merge($aExtKeys, $aObjKeys);
				if (!array_key_exists($sExtKeyAttCode, $aAllKeys))
				{
					throw new OqlNormalizeException('Unknown key in join condition (left expression)', $sSourceQuery, $oLeftField->GetNameDetails(), array_keys($aAllKeys));
				}

				if ($sFromClass == $sJoinClassAlias)
				{
					if (array_key_exists($sExtKeyAttCode, $aExtKeys)) // Skip that check for object keys
					{
						$sTargetClass = $oModelReflection->GetAttributeProperty($aAliases[$sFromClass], $sExtKeyAttCode, 'targetclass');
						if(!$oModelReflection->IsSameFamilyBranch($aAliases[$sToClass], $sTargetClass))
						{
							throw new OqlNormalizeException("The joined class ($aAliases[$sFromClass]) is not compatible with the external key, which is pointing to $sTargetClass", $sSourceQuery, $oLeftField->GetNameDetails());
						}
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
					if (array_key_exists($sExtKeyAttCode, $aExtKeys)) // Skip that check for object keys
					{
						$sTargetClass = $oModelReflection->GetAttributeProperty($aAliases[$sFromClass], $sExtKeyAttCode, 'targetclass');
						if(!$oModelReflection->IsSameFamilyBranch($aAliases[$sToClass], $sTargetClass))
						{
							throw new OqlNormalizeException("The joined class ($aAliases[$sToClass]) is not compatible with the external key, which is pointing to $sTargetClass", $sSourceQuery, $oLeftField->GetNameDetails());
						}
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
		foreach ($this->GetSelectedClasses() as $oClassDetails)
		{
			$sClassToSelect = $oClassDetails->GetValue();
			if (!array_key_exists($sClassToSelect, $aAliases))
			{
				throw new OqlNormalizeException('Unknown class [alias]', $sSourceQuery, $oClassDetails, array_keys($aAliases));
			}
		}

		// Check the condition tree
		//
		if ($this->m_oCondition instanceof Expression)
		{
			$this->m_oCondition->Check($oModelReflection, $aAliases, $sSourceQuery);
		}
	}

	/**
	 * Make the relevant DBSearch instance (FromOQL)
	 */	 	
	public function ToDBSearch($sQuery)
	{
		$sClass = $this->GetClass(new ModelReflectionRuntime());
		$sClassAlias = $this->GetClassAlias();

		$oSearch = new DBObjectSearch($sClass, $sClassAlias);
		$oSearch->InitFromOqlQuery($this, $sQuery);
		return $oSearch;
	}
}

class OqlUnionQuery extends OqlQuery
{
	protected $aQueries;

	public function __construct(OqlObjectQuery $oLeftQuery, OqlQuery $oRightQueryOrUnion)
	{
		parent::__construct();
		$this->aQueries[] = $oLeftQuery;
		if ($oRightQueryOrUnion instanceof OqlUnionQuery)
		{
			foreach ($oRightQueryOrUnion->GetQueries() as $oSingleQuery)
			{
				$this->aQueries[] = $oSingleQuery;
			}
		}
		else
		{
			$this->aQueries[] = $oRightQueryOrUnion;
		}
	}
	
	public function GetQueries()
	{
		return $this->aQueries;
	}

	/**
	 * Check the validity of the expression with regard to the data model
	 * and the query in which it is used
	 * 	 	 
	 * @param ModelReflection $oModelReflection MetaModel to consider	 	
	 * @throws OqlNormalizeException
	 */	 	
	public function Check(ModelReflection $oModelReflection, $sSourceQuery)
	{
		$aColumnToClasses = array();
		foreach ($this->aQueries as $iQuery => $oQuery)
		{
			$oQuery->Check($oModelReflection, $sSourceQuery);

			$aAliasToClass = array($oQuery->GetClassAlias() => $oQuery->GetClass($oModelReflection));
			$aJoinSpecs = $oQuery->GetJoins();
			if (is_array($aJoinSpecs))
			{
				foreach ($aJoinSpecs as $oJoinSpec)
				{
					$aAliasToClass[$oJoinSpec->GetClassAlias()] = $oJoinSpec->GetClass();
				}
			}

			$aSelectedClasses = $oQuery->GetSelectedClasses();
			if ($iQuery != 0)
			{
				if (count($aSelectedClasses) < count($aColumnToClasses))
				{
					$oLastClass = end($aSelectedClasses);
					throw new OqlNormalizeException('Too few selected classes in the subquery', $sSourceQuery, $oLastClass);
				}
				if (count($aSelectedClasses) > count($aColumnToClasses))
				{
					$oLastClass = end($aSelectedClasses);
					throw new OqlNormalizeException('Too many selected classes in the subquery', $sSourceQuery, $oLastClass);
				}
			}
			foreach ($aSelectedClasses as $iColumn => $oClassDetails)
			{
				$sAlias = $oClassDetails->GetValue();
				$sClass = $aAliasToClass[$sAlias];
				$aColumnToClasses[$iColumn][] = array(
					'alias' => $sAlias,
					'class' => $sClass,
					'class_name' => $oClassDetails,
				);
			}
		}
		foreach ($aColumnToClasses as $iColumn => $aClasses)
		{
			$sRootClass = null;
			foreach ($aClasses as $iQuery => $aData)
			{
				if ($iQuery == 0)
				{
					// Establish the reference
					$sRootClass = $oModelReflection->GetRootClass($aData['class']);
				}
				else
				{
					if ($oModelReflection->GetRootClass($aData['class']) != $sRootClass)
					{
						$aSubclasses = $oModelReflection->EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_ALL);
						throw new OqlNormalizeException('Incompatible classes: could not find a common ancestor', $sSourceQuery, $aData['class_name'], $aSubclasses);
					}
				}
			}
		}
	}

	/**
	 * Determine the class
	 *
	 * @param ModelReflection $oModelReflection MetaModel to consider
	 * @return string
	 * @throws Exception
	 */
	public function GetClass(ModelReflection $oModelReflection)
	{
		$aFirstColClasses = array();
		foreach ($this->aQueries as $iQuery => $oQuery)
		{
			$aFirstColClasses[] = $oQuery->GetClass($oModelReflection);
		}
		$sClass = self::GetLowestCommonAncestor($oModelReflection, $aFirstColClasses);
		if (is_null($sClass))
		{
			throw new Exception('Could not determine the class of the union query. This issue should have been detected earlier by calling OqlQuery::Check()');
		}
		return $sClass;
	}

	/**
	 * Determine the class alias
	 *
	 * @return string
	 * @throws Exception
	 */
	public function GetClassAlias()
	{
		$sAlias = $this->aQueries[0]->GetClassAlias();
		return $sAlias;
	}

	/**
	 * Check the validity of the expression with regard to the data model
	 * and the query in which it is used
	 *
	 * @param ModelReflection $oModelReflection MetaModel to consider
	 * @param array $aClasses Flat list of classes
	 * @return string the lowest common ancestor amongst classes, null if none has been found
	 * @throws Exception
	 */
	public static function GetLowestCommonAncestor(ModelReflection $oModelReflection, $aClasses)
	{
		$sAncestor = null;
		foreach($aClasses as $sClass)
		{
			if (is_null($sAncestor))
			{
				// first loop
				$sAncestor = $sClass;
			}
			elseif ($oModelReflection->GetRootClass($sClass) != $oModelReflection->GetRootClass($sAncestor))
			{
				$sAncestor = null;
				break;
			}
			else
			{
				$sAncestor = self::LowestCommonAncestor($oModelReflection, $sAncestor, $sClass);
			}
		}
		return $sAncestor;
	}

	/**
	 * Note: assumes that class A and B have a common ancestor
	 */
	protected static function LowestCommonAncestor(ModelReflection $oModelReflection, $sClassA, $sClassB)
	{
		if ($sClassA == $sClassB)
		{
			$sRet = $sClassA;
		}
		elseif (in_array($sClassA, $oModelReflection->EnumChildClasses($sClassB)))
		{
			$sRet = $sClassB;
		}
		elseif (in_array($sClassB, $oModelReflection->EnumChildClasses($sClassA)))
		{
			$sRet = $sClassA;
		}
		else
		{
			// Recurse
			$sRet = self::LowestCommonAncestor($oModelReflection, $sClassA, $oModelReflection->GetParentClass($sClassB));
		}
		return $sRet;
	}
	/**
	 * Make the relevant DBSearch instance (FromOQL)
	 */	 	
	public function ToDBSearch($sQuery)
	{
		$aSearches = array();
		foreach ($this->aQueries as $oQuery)
		{
			$aSearches[] = $oQuery->ToDBSearch($sQuery);
		}

		$oSearch = new DBUnionSearch($aSearches);
		return $oSearch;
	}
}
