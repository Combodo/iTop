<?php
// Copyright (c) 2010-2018 Combodo SARL
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
//

class MissingQueryArgument extends CoreException
{
}


/**
 * @method Check($oModelReflection, array $aAliases, $sSourceQuery)
 */
abstract class Expression
{
	/**
	 * Perform a deep clone (as opposed to "clone" which does copy a reference to the underlying objects)
	 **/
	public function DeepClone()
	{
		return unserialize(serialize($this));
	}

	// recursive translation of identifiers
	abstract public function GetUnresolvedFields($sAlias, &$aUnresolved);

	/**
	 * @param array $aTranslationData
	 * @param bool $bMatchAll
	 * @param bool $bMarkFieldsAsResolved
	 *
	 * @return Expression Translated expression
	 */
	abstract public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true);

	/**
	 * recursive rendering
	 *
	 * @deprecated use RenderExpression
	 *
	 * @param array $aArgs used as input by default, or used as output if bRetrofitParams set to True
	 * @param bool $bRetrofitParams
	 *
	 * @return array|string
	 * @throws \MissingQueryArgument
	 */
	public function Render(&$aArgs = null, $bRetrofitParams = false)
	{
		return $this->RenderExpression(false, $aArgs, $bRetrofitParams);
	}

	/**
	 * recursive rendering
	 *
	 * @param bool $bForSQL generates code for OQL if false, for SQL otherwise
	 * @param array $aArgs used as input by default, or used as output if bRetrofitParams set to True
	 * @param bool $bRetrofitParams
	 *
	 * @return array|string
	 * @throws \MissingQueryArgument
	 */
	abstract public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false);

	/**
	 * @param DBObjectSearch $oSearch
	 * @param array $aArgs
	 * @param AttributeDefinition $oAttDef
	 *
	 * @param array $aCtx
	 *
	 * @return array parameters for the search form
	 */
	public function Display($oSearch, &$aArgs = null, $oAttDef = null, &$aCtx = array())
	{
		return $this->RenderExpression(false, $aArgs);
	}

	public function GetAttDef($aClasses = array())
	{
		return null;
	}

	/**
	 * Recursively browse the expression tree
	 * @param Closure $callback
	 * @return mixed
	 */
	abstract public function Browse(Closure $callback);

	abstract public function ApplyParameters($aArgs);

	// recursively builds an array of class => fieldname
	abstract public function ListRequiredFields();

	// recursively list field parents ($aTable = array of sParent => dummy)
	abstract public function CollectUsedParents(&$aTable);

	abstract public function IsTrue();

	// recursively builds an array of [classAlias][fieldName] => value
	abstract public function ListConstantFields();

	public function RequiresField($sClass, $sFieldName)
	{
		// #@# todo - optimize : this is called quite often when building a single query !
		$aRequired = $this->ListRequiredFields();
		if (!in_array($sClass.'.'.$sFieldName, $aRequired)) return false;
		return true;
	}

	public function serialize()
	{
		return base64_encode($this->RenderExpression(false));
	}

	/**
	 * @param $sValue
	 *
	 * @return Expression
	 * @throws OQLException
	 */
	static public function unserialize($sValue)
	{
		return self::FromOQL(base64_decode($sValue));
	}

	/**
	 * @param $sConditionExpr
	 * @return Expression
	 */
	static public function FromOQL($sConditionExpr)
	{
		static $aCache = array();
		if (array_key_exists($sConditionExpr, $aCache))
		{
			return unserialize($aCache[$sConditionExpr]);
		}
		$oOql = new OqlInterpreter($sConditionExpr);
		$oExpression = $oOql->ParseExpression();
		$aCache[$sConditionExpr] = serialize($oExpression);

		return $oExpression;
	}

	static public function FromSQL($sSQL)
	{
		$oSql = new SQLExpression($sSQL);
		return $oSql;
	}

	/**
	 * @param Expression $oExpr
	 * @return Expression
	 */
	public function LogAnd(Expression $oExpr)
	{
		if ($this->IsTrue()) return clone $oExpr;
		if ($oExpr->IsTrue()) return clone $this;
		return new BinaryExpression($this, 'AND', $oExpr);
	}

	/**
	 * @param Expression $oExpr
	 * @return Expression
	 */
	public function LogOr(Expression $oExpr)
	{
		return new BinaryExpression($this, 'OR', $oExpr);
	}

	abstract public function RenameParam($sOldName, $sNewName);
	abstract public function RenameAlias($sOldName, $sNewName);

	/**
	 * Make the most relevant label, given the value of the expression
	 *
	 * @param DBSearch oFilter The context in which this expression has been used
	 * @param string sValue The value returned by the query, for this expression
	 * @param string sDefault The default value if no relevant label could be computed
	 *
	 * @return string label
	 */
	public function MakeValueLabel($oFilter, $sValue, $sDefault)
	{
		return $sDefault;
	}

	public function GetCriterion($oSearch, &$aArgs = null, $bRetrofitParams = false, $oAttDef = null)
	{
		return array(
			'widget' => AttributeDefinition::SEARCH_WIDGET_TYPE_RAW,
			'oql' => $this->RenderExpression(false, $aArgs, $bRetrofitParams),
			'label' => $this->Display($oSearch, $aArgs, $oAttDef),
			'source' => get_class($this),
		);
	}

	/**
	 * Split binary expression on given operator
	 *
	 * @param Expression $oExpr
	 * @param string $sOperator
	 * @param array $aAndExpr
	 *
	 * @return array of expressions
	 */
	public static function Split($oExpr, $sOperator = 'AND', &$aAndExpr = array())
	{
		if (($oExpr instanceof BinaryExpression) && ($oExpr->GetOperator() == $sOperator))
		{
			static::Split($oExpr->GetLeftExpr(), $sOperator, $aAndExpr);
			static::Split($oExpr->GetRightExpr(), $sOperator, $aAndExpr);
		}
		else
		{
			$aAndExpr[] = $oExpr;
		}

		return $aAndExpr;
	}
}

class SQLExpression extends Expression
{
	protected $m_sSQL;

	public function __construct($sSQL)
	{
		$this->m_sSQL  = $sSQL;
	}

	public function IsTrue()
	{
		return false;
	}

	// recursive rendering
	public function RenderExpression($bForSql = false, &$aArgs = null, $bRetrofitParams = false)
	{
		return $this->m_sSQL;
	}

	public function Browse(Closure $callback)
	{
		$callback($this);
	}

	public function ApplyParameters($aArgs)
	{
	}

	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		return clone $this;
	}

	public function ListRequiredFields()
	{
		return array();
	}

	public function CollectUsedParents(&$aTable)
	{
	}

	public function ListConstantFields()
	{
		return array();
	}

	public function RenameParam($sOldName, $sNewName)
	{
		// Do nothing, since there is nothing to rename
	}

	public function RenameAlias($sOldName, $sNewName)
	{
		// Do nothing, since there is nothing to rename
	}
}



class BinaryExpression extends Expression
{
	protected $m_oLeftExpr; // filter code or an SQL expression (later?)
	protected $m_oRightExpr;
	protected $m_sOperator;

	/**
	 * @param \Expression $oLeftExpr
	 * @param string $sOperator
	 * @param \Expression $oRightExpr
	 *
	 * @throws \CoreException
	 */
	public function __construct($oLeftExpr, $sOperator, $oRightExpr)
	{
		$this->ValidateConstructorParams($oLeftExpr, $sOperator, $oRightExpr);

		$this->m_oLeftExpr  = $oLeftExpr;
		$this->m_oRightExpr = $oRightExpr;
		$this->m_sOperator  = $sOperator;
	}

	/**
	 * @param $oLeftExpr
	 * @param $sOperator
	 * @param $oRightExpr
	 *
	 * @throws \CoreException if one of the parameter is invalid
	 */
	protected function ValidateConstructorParams($oLeftExpr, $sOperator, $oRightExpr)
	{
		if (!is_object($oLeftExpr))
		{
			throw new CoreException('Expecting an Expression object on the left hand', array('found_type' => gettype($oLeftExpr)));
		}
		if (!is_object($oRightExpr))
		{
			throw new CoreException('Expecting an Expression object on the right hand', array('found_type' => gettype($oRightExpr)));
		}
		if (!$oLeftExpr instanceof Expression)
		{
			throw new CoreException('Expecting an Expression object on the left hand', array('found_class' => get_class($oLeftExpr)));
		}
		if (!$oRightExpr instanceof Expression)
		{
			throw new CoreException('Expecting an Expression object on the right hand', array('found_class' => get_class($oRightExpr)));
		}
		if ((($sOperator == "IN") || ($sOperator == "NOT IN")) && !($oRightExpr instanceof ListExpression))
		{
			throw new CoreException("Expecting a List Expression object on the right hand for operator $sOperator",
				array('found_class' => get_class($oRightExpr)));
		}
	}

	public function IsTrue()
	{
		// return true if we are certain that it will be true
		if ($this->m_sOperator == 'AND')
		{
			if ($this->m_oLeftExpr->IsTrue() && $this->m_oRightExpr->IsTrue()) return true;
		}
		elseif ($this->m_sOperator == 'OR')
		{
			if ($this->m_oLeftExpr->IsTrue() || $this->m_oRightExpr->IsTrue()) return true;
		}
		return false;
	}

	public function GetLeftExpr()
	{
		return $this->m_oLeftExpr;
	}

	public function GetRightExpr()
	{
		return $this->m_oRightExpr;
	}

	public function GetOperator()
	{
		return $this->m_sOperator;
	}

	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		$sOperator = $this->GetOperator();
		$sLeft = $this->GetLeftExpr()->RenderExpression($bForSQL, $aArgs, $bRetrofitParams);
		$sRight = $this->GetRightExpr()->RenderExpression($bForSQL, $aArgs, $bRetrofitParams);
		return "($sLeft $sOperator $sRight)";
	}

	public function Browse(Closure $callback)
	{
		$callback($this);
		$this->m_oLeftExpr->Browse($callback);
		$this->m_oRightExpr->Browse($callback);
	}

	public function ApplyParameters($aArgs)
	{
		if ($this->m_oLeftExpr instanceof VariableExpression)
		{
			$this->m_oLeftExpr = $this->m_oLeftExpr->GetAsScalar($aArgs);
		}
		else //if ($this->m_oLeftExpr instanceof Expression)
		{
			$this->m_oLeftExpr->ApplyParameters($aArgs);
		}
		if ($this->m_oRightExpr instanceof VariableExpression)
		{
			$this->m_oRightExpr = $this->m_oRightExpr->GetAsScalar($aArgs);
		}
		else //if ($this->m_oRightExpr instanceof Expression)
		{
			$this->m_oRightExpr->ApplyParameters($aArgs);
		}
	}

	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
		$this->GetLeftExpr()->GetUnresolvedFields($sAlias, $aUnresolved);
		$this->GetRightExpr()->GetUnresolvedFields($sAlias, $aUnresolved);
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		$oLeft = $this->GetLeftExpr()->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		$oRight = $this->GetRightExpr()->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		return new BinaryExpression($oLeft, $this->GetOperator(), $oRight);
	}

	public function ListRequiredFields()
	{
		$aLeft = $this->GetLeftExpr()->ListRequiredFields();
		$aRight = $this->GetRightExpr()->ListRequiredFields();
		return array_merge($aLeft, $aRight);
	}

	public function CollectUsedParents(&$aTable)
	{
		$this->GetLeftExpr()->CollectUsedParents($aTable);
		$this->GetRightExpr()->CollectUsedParents($aTable);
	}

	public function GetAttDef($aClasses = array())
	{
		$oAttDef = $this->GetLeftExpr()->GetAttDef($aClasses);
		if (!is_null($oAttDef)) return $oAttDef;

		return $this->GetRightExpr()->GetAttDef($aClasses);
	}

	/**
	 * List all constant expression of the form <field> = <scalar> or <field> = :<variable>
	 * Could be extended to support <field> = <function><constant_expression>
	 */
	public function ListConstantFields()
	{
		$aResult = array();
		if ($this->m_sOperator == '=')
		{
			if (($this->m_oLeftExpr instanceof FieldExpression) && ($this->m_oRightExpr instanceof ScalarExpression))
			{
				$aResult[$this->m_oLeftExpr->GetParent()][$this->m_oLeftExpr->GetName()] = $this->m_oRightExpr;
			}
			else if (($this->m_oRightExpr instanceof FieldExpression) && ($this->m_oLeftExpr instanceof ScalarExpression))
			{
				$aResult[$this->m_oRightExpr->GetParent()][$this->m_oRightExpr->GetName()] = $this->m_oLeftExpr;
			}
			else if (($this->m_oLeftExpr instanceof FieldExpression) && ($this->m_oRightExpr instanceof VariableExpression))
			{
				$aResult[$this->m_oLeftExpr->GetParent()][$this->m_oLeftExpr->GetName()] = $this->m_oRightExpr;
			}
			else if (($this->m_oRightExpr instanceof FieldExpression) && ($this->m_oLeftExpr instanceof VariableExpression))
			{
				$aResult[$this->m_oRightExpr->GetParent()][$this->m_oRightExpr->GetName()] = $this->m_oLeftExpr;
			}
		}
		else if ($this->m_sOperator == 'AND')
		{
			// Strictly, this should be done only for the AND operator
			$aResult = array_merge_recursive($this->m_oRightExpr->ListConstantFields(), $this->m_oLeftExpr->ListConstantFields());
		}
		return $aResult;
	}

	public function RenameParam($sOldName, $sNewName)
	{
		$this->GetLeftExpr()->RenameParam($sOldName, $sNewName);
		$this->GetRightExpr()->RenameParam($sOldName, $sNewName);
	}

	public function RenameAlias($sOldName, $sNewName)
	{
		$this->GetLeftExpr()->RenameAlias($sOldName, $sNewName);
		$this->GetRightExpr()->RenameAlias($sOldName, $sNewName);
	}

	// recursive rendering
	public function Display($oSearch, &$aArgs = null, $oAttDef = null, &$aCtx = array())
	{
		$bReverseOperator = false;
		if (method_exists($oSearch, 'GetJoinedClasses'))
		{
			$aClasses = $oSearch->GetJoinedClasses();
		}
		else
		{
			$aClasses = array($oSearch->GetClass());
		}
		$oLeftExpr = $this->GetLeftExpr();
		if ($oLeftExpr instanceof FieldExpression)
		{
			$oAttDef = $oLeftExpr->GetAttDef($aClasses);
		}
		$oRightExpr = $this->GetRightExpr();
		if ($oRightExpr instanceof FieldExpression)
		{
			$oAttDef = $oRightExpr->GetAttDef($aClasses);
			$bReverseOperator = true;
		}


		if ($bReverseOperator)
		{
			$sRight = $oRightExpr->Display($oSearch, $aArgs, $oAttDef, $aCtx);
			$sLeft = $oLeftExpr->Display($oSearch, $aArgs, $oAttDef, $aCtx);

			// switch left and right expressions so reverse the operator
			// Note that the operation is the same so < becomes > and not >=
			switch ($this->GetOperator())
			{
				case '>':
					$sOperator = '<';
					break;
				case '<':
					$sOperator = '>';
					break;
				case '>=':
					$sOperator = '<=';
					break;
				case '<=':
					$sOperator = '>=';
					break;
				default:
					$sOperator = $this->GetOperator();
					break;
			}
			$sOperator = $this->OperatorToNaturalLanguage($sOperator, $oAttDef);

			return "({$sRight}{$sOperator}{$sLeft})";
		}

		$sLeft = $oLeftExpr->Display($oSearch, $aArgs, $oAttDef, $aCtx);
		$sRight = $oRightExpr->Display($oSearch, $aArgs, $oAttDef, $aCtx);

		$sOperator = $this->GetOperator();
		$sOperator = $this->OperatorToNaturalLanguage($sOperator, $oAttDef);

		return "({$sLeft}{$sOperator}{$sRight})";
	}

	private function OperatorToNaturalLanguage($sOperator, $oAttDef)
	{
		if ($oAttDef instanceof AttributeDateTime)
		{
			return Dict::S('Expression:Operator:Date:'.$sOperator, " $sOperator ");
		}

		return Dict::S('Expression:Operator:'.$sOperator, " $sOperator ");
	}

	/**
	 * @param DBSearch $oSearch
	 * @param null $aArgs
	 * @param bool $bRetrofitParams
	 * @param null $oAttDef
	 *
	 * @return array
	 * @throws \MissingQueryArgument
	 */
	public function GetCriterion($oSearch, &$aArgs = null, $bRetrofitParams = false, $oAttDef = null)
	{
		$bReverseOperator = false;
		$oLeftExpr = $this->GetLeftExpr();
		$oRightExpr = $this->GetRightExpr();

		if (method_exists($oSearch, 'GetJoinedClasses'))
		{
			$aClasses = $oSearch->GetJoinedClasses();
		}
		else
		{
			$aClasses = array($oSearch->GetClass());
		}

		$oAttDef = $oLeftExpr->GetAttDef($aClasses);
		if (is_null($oAttDef))
		{
			$oAttDef = $oRightExpr->GetAttDef($aClasses);
			$bReverseOperator = true;
		}

		if (is_null($oAttDef))
		{
			return parent::GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef);
		}


		if ($bReverseOperator)
		{
			$aCriteriaRight = $oRightExpr->GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef);
			// $oAttDef can be different now
			$oAttDef = $oRightExpr->GetAttDef($aClasses);
			$aCriteriaLeft = $oLeftExpr->GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef);

			// switch left and right expressions so reverse the operator
			// Note that the operation is the same so < becomes > and not >=
			switch ($this->GetOperator())
			{
				case '>':
					$sOperator = '<';
					break;
				case '<':
					$sOperator = '>';
					break;
				case '>=':
					$sOperator = '<=';
					break;
				case '<=':
					$sOperator = '>=';
					break;
				default:
					$sOperator = $this->GetOperator();
					break;
			}
			$aCriteria = self::MergeCriteria($aCriteriaRight, $aCriteriaLeft, $sOperator);
		}
		else
		{
			$aCriteriaLeft = $oLeftExpr->GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef);
			// $oAttDef can be different now
			$oAttDef = $oLeftExpr->GetAttDef($aClasses);
			$aCriteriaRight = $oRightExpr->GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef);

			$aCriteria = self::MergeCriteria($aCriteriaLeft, $aCriteriaRight, $this->GetOperator());
		}
		$aCriteria['oql'] = $this->RenderExpression(false, $aArgs, $bRetrofitParams);
		$aCriteria['label'] = $this->Display($oSearch, $aArgs, $oAttDef);

		if (isset($aCriteriaLeft['ref']) && isset($aCriteriaRight['ref']) && ($aCriteriaLeft['ref'] != $aCriteriaRight['ref']))
		{
			// Only one Field is supported in the expressions
			$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;
		}

		return $aCriteria;
	}

	protected static function MergeCriteria($aCriteriaLeft, $aCriteriaRight, $sOperator)
	{
		$aCriteriaOverride = array();
		$aCriteriaOverride['operator'] = $sOperator;
		if ($sOperator == 'OR')
		{
			if (isset($aCriteriaLeft['ref']) && isset($aCriteriaRight['ref']) && ($aCriteriaLeft['ref'] == $aCriteriaRight['ref']))
			{
				if (isset($aCriteriaLeft['widget']) && isset($aCriteriaRight['widget']) && ($aCriteriaLeft['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY) && ($aCriteriaRight['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY))
				{
					$aCriteriaOverride['operator'] = 'IN';
					$aCriteriaOverride['is_hierarchical'] = true;

					if (isset($aCriteriaLeft['values']) && isset($aCriteriaRight['values']))
					{
						$aCriteriaOverride['values'] = array_merge($aCriteriaLeft['values'], $aCriteriaRight['values']);
					}
				}
			}
			if (isset($aCriteriaLeft['widget']) && isset($aCriteriaRight['widget']) && ($aCriteriaLeft['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_TAG_SET) && ($aCriteriaRight['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_TAG_SET))
			{
				$aCriteriaOverride['operator'] = 'MATCHES';
			}
		}

		return array_merge($aCriteriaLeft, $aCriteriaRight, $aCriteriaOverride);
	}
}


/**
 * @since 2.6 N°931 tag fields
 */
class MatchExpression extends BinaryExpression
{
	/** @var \FieldExpression */
	protected $m_oLeftExpr;
	/** @var \ScalarExpression */
	protected $m_oRightExpr;

	/**
	 * MatchExpression constructor.
	 *
	 * @param \FieldExpression $oLeftExpr
	 * @param \ScalarExpression $oRightExpr
	 *
	 * @throws \CoreException
	 */
	public function __construct(FieldExpression $oLeftExpr, ScalarExpression $oRightExpr)
	{
		parent::__construct($oLeftExpr, 'MATCHES', $oRightExpr);
	}

	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		$sLeft = $this->GetLeftExpr()->RenderExpression($bForSQL, $aArgs, $bRetrofitParams);
		$sRight = $this->GetRightExpr()->RenderExpression($bForSQL, $aArgs, $bRetrofitParams);

		if ($bForSQL)
		{
			$sRet = "MATCH ($sLeft) AGAINST ($sRight IN BOOLEAN MODE)";
		}
		else
		{
			$sRet = "$sLeft MATCHES $sRight";
		}

		return $sRet;
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		/** @var \FieldExpression $oLeft */
		$oLeft = $this->GetLeftExpr()->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		/** @var \ScalarExpression $oRight */
		$oRight = $this->GetRightExpr()->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);

		return new static($oLeft, $oRight);
	}
}


class UnaryExpression extends Expression
{
	protected $m_value;

	public function __construct($value)
	{
		$this->m_value = $value;
	}

	public function IsTrue()
	{
		// return true if we are certain that it will be true
		return ($this->m_value == 1);
	}

	public function GetValue()
	{
		return $this->m_value;
	}

	// recursive rendering
	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		return CMDBSource::Quote($this->m_value);
	}

	public function Browse(Closure $callback)
	{
		$callback($this);
	}

	public function ApplyParameters($aArgs)
	{
	}

	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		return clone $this;
	}

	public function ListRequiredFields()
	{
		return array();
	}

	public function CollectUsedParents(&$aTable)
	{
	}

	public function ListConstantFields()
	{
		return array();
	}

	public function RenameParam($sOldName, $sNewName)
	{
		// Do nothing
		// really ? what about :param{$iParamIndex} ??
	}

	public function RenameAlias($sOldName, $sNewName)
	{
		// Do nothing
	}
}

class ScalarExpression extends UnaryExpression
{
	public function __construct($value)
	{
		if (!is_scalar($value) && !is_null($value) && (!$value instanceof OqlHexValue))
		{
			throw new CoreException('Attempt to create a scalar expression from a non scalar', array('var_type'=>gettype($value)));
		}
		parent::__construct($value);
	}

	/**
	 * @param array $oSearch
	 * @param array $aArgs
	 * @param AttributeDefinition $oAttDef
	 *
	 * @param array $aCtx
	 *
	 * @return array|string
	 * @throws \Exception
	 */
	public function Display($oSearch, &$aArgs = null, $oAttDef = null, &$aCtx = array())
	{
		if (!is_null($oAttDef))
		{
			if ($oAttDef->IsExternalKey())
			{
				try
				{
					/** @var AttributeExternalKey $oAttDef */
					$sTarget = $oAttDef->GetTargetClass();
					$oObj = MetaModel::GetObject($sTarget, $this->m_value, false);
					if (empty($oObj))
					{
						return Dict::S('Enum:Undefined');
					}

					return $oObj->Get("friendlyname");
				} catch (CoreException $e)
				{
				}
			}

			if (!($oAttDef instanceof AttributeDateTime))
			{
				return $oAttDef->GetAsPlainText($this->m_value);
			}
		}

		if (strpos($this->m_value, '%') === 0)
		{
			return '';
		}

		if (isset($aCtx['date_display']))
		{
			return $aCtx['date_display']->MakeValueLabel($oSearch, $this->m_value, $this->m_value);
		}

		return $this->RenderExpression(false, $aArgs);
	}

	// recursive rendering
	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		if (is_null($this->m_value))
		{
			$sRet = 'NULL';
		}
		else
		{
			$sRet = CMDBSource::Quote($this->m_value);
		}
		return $sRet;
	}

	public function GetAsScalar($aArgs)
	{
		return clone $this;
	}

	public function GetCriterion($oSearch, &$aArgs = null, $bRetrofitParams = false, $oAttDef = null)
	{
		$aCriterion = array();
		switch ((string)($this->m_value))
		{
			case '%Y-%m-%d':
				$aCriterion['unit'] = 'DAY';
				break;
			case '%Y-%m':
				$aCriterion['unit'] = 'MONTH';
				break;
			case '%w':
				$aCriterion['unit'] = 'WEEKDAY';
				break;
			case '%H':
				$aCriterion['unit'] = 'HOUR';
				break;
			default:
				$aValue = array();
				if (!is_null($oAttDef))
				{
					switch (true)
					{
						case ($oAttDef instanceof AttributeExternalField):
							try
							{
								$oFinalAttDef = $oAttDef->GetFinalAttDef();
								if($oFinalAttDef instanceof  AttributeExternalKey)
								{
									if ($this->GetValue() !== 0)
									{
										/** @var AttributeExternalKey $oFinalAttDef */
										$sTarget = $oFinalAttDef->GetTargetClass();
										$oObj = MetaModel::GetObject($sTarget, $this->GetValue());
										$aValue['label'] = $oObj->Get("friendlyname");
										$aValue['value'] = $this->GetValue();
									}
									else
									{
										$aValue['label'] = Dict::S('Enum:Undefined');
										$aValue['value'] = $this->GetValue();
									}
								}
								else
								{
									$aValue['label'] = $this->GetValue();
									$aValue['value'] = $this->GetValue();
								}
								$aCriterion['values'] = array($aValue);
							}
							catch (Exception $e)
							{
								IssueLog::Error($e->getMessage());
							}
							break;
						case ($oAttDef instanceof AttributeTagSet):
							try
							{
								if (!empty($this->GetValue()))
								{
									$aValues = array();
									$oValue = $this->GetValue();
									if (is_string($oValue))
									{
										$oValue = $oAttDef->GetExistingTagsFromString($oValue, true);
									}
									/** @var \ormTagSet $oValue */
									$aTags = $oValue->GetTags();
									foreach($aTags as $oTag)
									{
										$aValue['label'] = $oTag->Get('label');
										$aValue['value'] = $oTag->Get('code');
										$aValues[] = $aValue;
									}
									$aCriterion['values'] = $aValues;
								}
								else
								{
									$aCriterion['has_undefined'] = true;
								}
							} catch (Exception $e)
							{
								IssueLog::Error($e->getMessage());
							}
							break;
						case $oAttDef->IsExternalKey():
							try
							{
								if ($this->GetValue() != 0)
								{
									/** @var AttributeExternalKey $oAttDef */
									$sTarget = $oAttDef->GetTargetClass();
									$oObj = MetaModel::GetObject($sTarget, $this->GetValue(), true, true);
									$aValue['label'] = $oObj->Get("friendlyname");
									$aValue['value'] = $this->GetValue();
									$aCriterion['values'] = array($aValue);
								}
								else
								{
									$aValue['label'] = Dict::S('Enum:Undefined');
									$aValue['value'] = $this->GetValue();
									$aCriterion['values'] = array($aValue);
								}
							} catch (Exception $e)
							{
								// This object cannot be seen... ignore
							}
							break;
						default:
							try
							{
								$aValue['label'] = $oAttDef->GetAsPlainText($this->GetValue());
								$aValue['value'] = $this->GetValue();
								$aCriterion['values'] = array($aValue);
							} catch (Exception $e)
							{
								$aValue['label'] = $this->GetValue();
								$aValue['value'] = $this->GetValue();
								$aCriterion['values'] = array($aValue);
							}
							break;
					}
				}
				break;
		}
		$aCriterion['oql'] = $this->RenderExpression(false, $aArgs, $bRetrofitParams);

		return $aCriterion;
	}

}

class TrueExpression extends ScalarExpression
{
	public function __construct()
	{
		parent::__construct(1);
	}

	public function IsTrue()
	{
		return true;
	}
}

class FalseExpression extends ScalarExpression
{
	public function __construct()
	{
		parent::__construct(0);
	}

	public function IsTrue()
	{
		return false;
	}
}

class FieldExpression extends UnaryExpression
{
	protected $m_sParent;
	protected $m_sName;

	public function __construct($sName, $sParent = '')
	{
		parent::__construct("$sParent.$sName");

		$this->m_sParent = $sParent;
		$this->m_sName = $sName;
	}

	public function IsTrue()
	{
		// return true if we are certain that it will be true
		return false;
	}

	public function GetParent() {return $this->m_sParent;}
	public function GetName() {return $this->m_sName;}

	public function SetParent($sParent)
	{
		$this->m_sParent = $sParent;
		$this->m_value = $sParent.'.'.$this->m_sName;
	}

	private function GetClassName($aClasses = array())
	{
		if (isset($aClasses[$this->m_sParent]))
		{
			return $aClasses[$this->m_sParent];
		}
		else
		{
			return $this->m_sParent;
		}
	}

	/**
	 * @param DBObjectSearch $oSearch
	 * @param array $aArgs
	 * @param AttributeDefinition $oAttDef
	 *
	 * @param array $aCtx
	 *
	 * @return array|string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function Display($oSearch, &$aArgs = null, $oAttDef = null, &$aCtx = array())
	{
		if (empty($this->m_sParent))
		{
			return "`{$this->m_sName}`";
		}
		if (method_exists($oSearch, 'GetJoinedClasses'))
		{
			$aClasses = $oSearch->GetJoinedClasses();
		}
		else
		{
			$aClasses = array($oSearch->GetClass());
		}
		$sClass = $this->GetClassName($aClasses);
		$sAttName = MetaModel::GetLabel($sClass, $this->m_sName);
		if ($sClass != $oSearch->GetClass())
		{
			$sAttName = MetaModel::GetName($sClass).':'.$sAttName;
		}

		return $sAttName;
	}

	// recursive rendering
	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		if (empty($this->m_sParent))
		{
			return "`{$this->m_sName}`";
		}
		return "`{$this->m_sParent}`.`{$this->m_sName}`";
	}

	public function GetAttDef($aClasses = array())
	{
		if (!empty($this->m_sParent))
		{
			$sClass = $this->GetClassName($aClasses);
			$aAttDefs = MetaModel::ListAttributeDefs($sClass);
			if (isset($aAttDefs[$this->m_sName]))
			{
				return $aAttDefs[$this->m_sName];
			}
			else
			{
				if ($this->m_sName == 'id')
				{
					$aParams = array(
						'default_value' => 0,
						'is_null_allowed' => false,
						'allowed_values' => null,
						'depends_on' => null,
						'sql' => 'id',
					);

					return new AttributeInteger($this->m_sName, $aParams);
				}
			}
		}

		return null;
	}


	public function ListRequiredFields()
	{
		return array($this->m_sParent.'.'.$this->m_sName);
	}

	public function CollectUsedParents(&$aTable)
	{
		$aTable[$this->m_sParent] = true;
	}

	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
		if ($this->m_sParent == $sAlias)
		{
			// Add a reference to the field
			$aUnresolved[$this->m_sName] = $this;
		}
		elseif ($sAlias == '')
		{
			// An empty alias means "any alias"
			// In such a case, the results are indexed differently
			$aUnresolved[$this->m_sParent][$this->m_sName] = $this;
		}
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		if (!array_key_exists($this->m_sParent, $aTranslationData))
		{
			if ($bMatchAll) throw new CoreException('Unknown parent id in translation table', array('parent_id' => $this->m_sParent, 'translation_table' => array_keys($aTranslationData)));

			return clone $this;
		}
		if (!array_key_exists($this->m_sName, $aTranslationData[$this->m_sParent]))
		{
			if (!array_key_exists('*', $aTranslationData[$this->m_sParent]))
			{
				// #@# debug - if ($bMatchAll) MyHelpers::var_dump_html($aTranslationData, true);
				if ($bMatchAll) throw new CoreException('Unknown name in translation table', array('name' => $this->m_sName, 'parent_id' => $this->m_sParent, 'translation_table' => array_keys($aTranslationData[$this->m_sParent])));
				return clone $this;
			}
			$sNewParent = $aTranslationData[$this->m_sParent]['*'];
			$sNewName = $this->m_sName;
			if ($bMarkFieldsAsResolved)
			{
				$oRet = new FieldExpressionResolved($sNewName, $sNewParent);
			}
			else
			{
				$oRet = new FieldExpression($sNewName, $sNewParent);
			}
		}
		else
		{
			$oRet = $aTranslationData[$this->m_sParent][$this->m_sName];
		}
		return $oRet;
	}

	/**
	 * Make the most relevant label, given the value of the expression
	 *
	 * @param DBSearch oFilter The context in which this expression has been used
	 * @param string sValue The value returned by the query, for this expression
	 * @param string sDefault The default value if no relevant label could be computed
	 *
	 * @return string label
	 * @throws \CoreException
	 */
	public function MakeValueLabel($oFilter, $sValue, $sDefault)
	{
		$sAttCode = $this->GetName();
		$sParentAlias = $this->GetParent();

		$aSelectedClasses = $oFilter->GetSelectedClasses();
		$sClass = $aSelectedClasses[$sParentAlias];

		$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		// Set a default value for the general case
		$sRes = $oAttDef->GetAsHtml($sValue);

		// Exceptions...
		if ($oAttDef->IsExternalKey())
		{
			/** @var AttributeExternalKey $oAttDef */
			$sObjClass = $oAttDef->GetTargetClass();
			$iObjKey = (int)$sValue;
			if ($iObjKey > 0)
			{
				$oObject = MetaModel::GetObjectWithArchive($sObjClass, $iObjKey, true, true);
				$sRes = $oObject->GetHyperlink();
			}
			else
			{
				// Undefined
				$sRes = DBObject::MakeHyperLink($sObjClass, 0);
			}
		}
		elseif ($oAttDef->IsExternalField())
		{
			if (is_null($sValue))
			{
				$sRes = Dict::S('UI:UndefinedObject');
			}
		}
		return $sRes;
	}

	public function RenameAlias($sOldName, $sNewName)
	{
		if ($this->m_sParent == $sOldName)
		{
			$this->m_sParent = $sNewName;
		}
	}

	private function GetJoinedFilters($oSearch, $iOperatorCodeTarget)
	{
		$aFilters = array();
		$aPointingToByKey = $oSearch->GetCriteria_PointingTo();
		foreach ($aPointingToByKey as $sExtKey => $aPointingTo)
		{
			foreach($aPointingTo as $iOperatorCode => $aFilter)
			{
				if ($iOperatorCode == $iOperatorCodeTarget)
				{
					foreach($aFilter as $oExtFilter)
					{
						$aFilters[$sExtKey] = $oExtFilter;
					}
				}
			}
		}
		return $aFilters;
	}

	/**
	 * @param DBObjectSearch $oSearch
	 * @param null $aArgs
	 * @param bool $bRetrofitParams
	 * @param AttributeDefinition $oAttDef
	 *
	 * @return array
	 */
	public function GetCriterion($oSearch, &$aArgs = null, $bRetrofitParams = false, $oAttDef = null)
	{
		$aCriteria = array();
		$aCriteria['is_hierarchical'] = false;
		// Replace BELOW joins by the corresponding external key for the search
		// Try to detect hierarchical links
		if ($this->m_sName == 'id')
		{
			if (method_exists($oSearch, 'GetCriteria_PointingTo'))
			{
				$aFilters = $this->GetJoinedFilters($oSearch, TREE_OPERATOR_EQUALS);
				if (!empty($aFilters))
				{
					foreach($aFilters as $sExtKey => $oFilter)
					{
						$aSubFilters = $this->GetJoinedFilters($oFilter, TREE_OPERATOR_BELOW);
						foreach($aSubFilters as $oSubFilter)
						{
							/** @var \DBObjectSearch $oSubFilter */
							$sClassAlias = $oSubFilter->GetClassAlias();
							if ($sClassAlias == $this->m_sParent)
							{
								// Hierarchical link detected
								// replace current field with the corresponding external key
								$this->m_sName = $sExtKey;
								$this->m_sParent = $oSearch->GetClassAlias();
								$aCriteria['is_hierarchical'] = true;
							}
						}
					}
				}
			}
		}

		if (method_exists($oSearch, 'GetJoinedClasses'))
		{
			$oAttDef = $this->GetAttDef($oSearch->GetJoinedClasses());
		}
		else
		{
			$oAttDef = $this->GetAttDef($oSearch->GetSelectedClasses());
		}
		if (!is_null($oAttDef))
		{
			$sSearchType = $oAttDef->GetSearchType();
			try
			{
				if ($sSearchType == AttributeDefinition::SEARCH_WIDGET_TYPE_EXTERNAL_KEY)
				{
					if (MetaModel::IsHierarchicalClass($oAttDef->GetTargetClass()))
					{
						$sSearchType = AttributeDefinition::SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY;
					}
				}
			}
			catch (CoreException $e)
			{
			}
		}
		else
		{
			$sSearchType = AttributeDefinition::SEARCH_WIDGET_TYPE;
		}

		$aCriteria['widget'] = $sSearchType;
		$aCriteria['ref'] = $this->GetParent().'.'.$this->GetName();
		$aCriteria['class_alias'] = $this->GetParent();

		return $aCriteria;
	}
}

// Has been resolved into an SQL expression
class FieldExpressionResolved extends FieldExpression
{
	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		return clone $this;
	}
}

class VariableExpression extends UnaryExpression
{
	protected $m_sName;

	public function __construct($sName)
	{
		parent::__construct($sName);

		$this->m_sName = $sName;
	}

	public function IsTrue()
	{
		// return true if we are certain that it will be true
		return false;
	}

	public function GetName()
	{
		return $this->m_sName;
	}

	public function Display($oSearch, &$aArgs = null, $oAttDef = null, &$aCtx = array())
	{
		$sValue = $this->m_value;
		if (!is_null($aArgs) && (array_key_exists($this->m_sName, $aArgs)))
		{
			$sValue = $aArgs[$this->m_sName];
		}
		elseif (($iPos = strpos($this->m_sName, '->')) !== false)
		{
			$sParamName = substr($this->m_sName, 0, $iPos);
			$oObj = null;
			$sAttCode = 'id';
			if (array_key_exists($sParamName.'->object()', $aArgs))
			{
				$sAttCode = substr($this->m_sName, $iPos + 2);
				$oObj = $aArgs[$sParamName.'->object()'];
			}
			elseif (array_key_exists($sParamName, $aArgs))
			{
				$sAttCode = substr($this->m_sName, $iPos + 2);
				$oObj = $aArgs[$sParamName];
			}
			if (!is_null($oObj))
			{
				if ($sAttCode == 'id')
				{
					$sValue = $oObj->Get("friendlyname");
				}
				else
				{
					$sValue = $oObj->Get($sAttCode);
				}

				return $sValue;
			}
		}
		if (!is_null($oAttDef))
		{
			if ($oAttDef->IsExternalKey())
			{
				try
				{
					/** @var AttributeExternalKey $oAttDef */
					$sTarget = $oAttDef->GetTargetClass();
					$oObj = MetaModel::GetObject($sTarget, $sValue);

					return $oObj->Get("friendlyname");
				} catch (CoreException $e)
				{
				}
			}

			return $oAttDef->GetAsPlainText($sValue);
		}

		return $this->RenderExpression(false, $aArgs);
	}

	/**
	 * @param bool $bForSQL
	 * @param array $aArgs
	 * @param bool $bRetrofitParams
	 *
	 * @return array|string
	 * @throws \MissingQueryArgument
	 */
	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		if (is_null($aArgs))
		{
			return ':'.$this->m_sName;
		}
		elseif (array_key_exists($this->m_sName, $aArgs))
		{
			$res = CMDBSource::Quote($aArgs[$this->m_sName]);
			if (is_array($res))
			{
				$res = implode(', ', $res);
			}
			return $res;
		}
		elseif (($iPos = strpos($this->m_sName, '->')) !== false)
		{
			$sParamName = substr($this->m_sName, 0, $iPos);
			if (array_key_exists($sParamName.'->object()', $aArgs))
			{
				$sAttCode = substr($this->m_sName, $iPos + 2);
				$oObj = $aArgs[$sParamName.'->object()'];
				if ($sAttCode == 'id')
				{
					return CMDBSource::Quote($oObj->GetKey());
				}
				return CMDBSource::Quote($oObj->Get($sAttCode));
			}
		}

		if ($bRetrofitParams)
		{
			$aArgs[$this->m_sName] = null;
			return ':'.$this->m_sName;
		}
		else
		{
			throw new MissingQueryArgument('Missing query argument', array('expecting'=>$this->m_sName, 'available'=>array_keys($aArgs)));
		}
	}

	public function RenameParam($sOldName, $sNewName)
	{
		if ($this->m_sName == $sOldName)
		{
			$this->m_sName = $sNewName;
		}
	}

	public function GetAsScalar($aArgs)
	{
		$oRet = null;
		if (array_key_exists($this->m_sName, $aArgs))
		{
			$oRet = new ScalarExpression($aArgs[$this->m_sName]);
		}
		elseif (($iPos = strpos($this->m_sName, '->')) !== false)
		{
			$sParamName = substr($this->m_sName, 0, $iPos);
			if (array_key_exists($sParamName.'->object()', $aArgs))
			{
				$sAttCode = substr($this->m_sName, $iPos + 2);
				$oObj = $aArgs[$sParamName.'->object()'];
				if ($sAttCode == 'id')
				{
					$oRet = new ScalarExpression($oObj->GetKey());
				}
				elseif (MetaModel::IsValidAttCode(get_class($oObj), $sAttCode))
				{
					$oRet = new ScalarExpression($oObj->Get($sAttCode));
				}
				else
				{
					throw new CoreException("Query argument {$this->m_sName} not matching any attribute of class ".get_class($oObj));
				}
			}
		}
		if (is_null($oRet))
		{
			throw new MissingQueryArgument('Missing query argument', array('expecting'=>$this->m_sName, 'available'=>array_keys($aArgs)));
		}
		return $oRet;
	}
}

// Temporary, until we implement functions and expression casting!
// ... or until we implement a real full text search based in the MATCH() expression
class ListExpression extends Expression
{
	protected $m_aExpressions;

	public function __construct($aExpressions)
	{
		$this->m_aExpressions = $aExpressions;
	}

	public static function FromScalars($aScalars)
	{
		$aExpressions = array();
		foreach($aScalars as $value)
		{
			$aExpressions[] = new ScalarExpression($value);
		}
		return new ListExpression($aExpressions);
	}

	public function IsTrue()
	{
		// return true if we are certain that it will be true
		return false;
	}

	public function GetItems()
	{
		return $this->m_aExpressions;
	}

	// recursive rendering
	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes[] = $oExpr->RenderExpression($bForSQL, $aArgs, $bRetrofitParams);
		}
		return '('.implode(', ', $aRes).')';
	}

	public function Browse(Closure $callback)
	{
		$callback($this);
		foreach ($this->m_aExpressions as $oExpr)
		{
			$oExpr->Browse($callback);
		}
	}

	public function ApplyParameters($aArgs)
	{
		foreach ($this->m_aExpressions as $idx => $oExpr)
		{
			if ($oExpr instanceof VariableExpression)
			{
				$this->m_aExpressions[$idx] = $oExpr->GetAsScalar($aArgs);
			}
			else
			{
				$oExpr->ApplyParameters($aArgs);
			}
		}
	}

	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
		foreach ($this->m_aExpressions as $oExpr)
		{
			$oExpr->GetUnresolvedFields($sAlias, $aUnresolved);
		}
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes[] = $oExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}
		return new ListExpression($aRes);
	}

	public function ListRequiredFields()
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes = array_merge($aRes, $oExpr->ListRequiredFields());
		}
		return $aRes;
	}

	public function CollectUsedParents(&$aTable)
	{
		foreach ($this->m_aExpressions as $oExpr)
		{
			$oExpr->CollectUsedParents($aTable);
		}
	}

	public function ListConstantFields()
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes = array_merge($aRes, $oExpr->ListConstantFields());
		}
		return $aRes;
	}

	public function RenameParam($sOldName, $sNewName)
	{
		foreach ($this->m_aExpressions as $key => $oExpr)
		{
			$this->m_aExpressions[$key] = $oExpr->RenameParam($sOldName, $sNewName);
		}
	}

	public function RenameAlias($sOldName, $sNewName)
	{
		foreach ($this->m_aExpressions as $key => $oExpr)
		{
			$oExpr->RenameAlias($sOldName, $sNewName);
		}
	}

	public function GetAttDef($aClasses = array())
	{
		foreach($this->m_aExpressions as $oExpression)
		{
			$oAttDef = $oExpression->GetAttDef($aClasses);
			if (!is_null($oAttDef)) return $oAttDef;
		}

		return null;
	}

	public function GetCriterion($oSearch, &$aArgs = null, $bRetrofitParams = false, $oAttDef = null)
	{
		$aValues = array();

		foreach($this->m_aExpressions as $oExpression)
		{
			$aCrit = $oExpression->GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef);
			if (array_key_exists('values', $aCrit))
			{
				$aValues = array_merge($aValues, $aCrit['values']);
			}
		}

		return array('values' => $aValues);
	}
}


class FunctionExpression extends Expression
{
	protected $m_sVerb;
	protected $m_aArgs; // array of expressions

	public function __construct($sVerb, $aArgExpressions)
	{
		$this->m_sVerb = $sVerb;
		$this->m_aArgs = $aArgExpressions;
	}

	public function IsTrue()
	{
		// return true if we are certain that it will be true
		return false;
	}

	public function GetVerb()
	{
		return $this->m_sVerb;
	}

	public function GetArgs()
	{
		return $this->m_aArgs;
	}

	// recursive rendering
	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		$aRes = array();
		foreach ($this->m_aArgs as $iPos => $oExpr)
		{
			$aRes[] = $oExpr->RenderExpression($bForSQL, $aArgs, $bRetrofitParams);
		}
		return $this->m_sVerb.'('.implode(', ', $aRes).')';
	}

	public function Browse(Closure $callback)
	{
		$callback($this);
		foreach ($this->m_aArgs as $iPos => $oExpr)
		{
			$oExpr->Browse($callback);
		}
	}

	public function ApplyParameters($aArgs)
	{
		foreach ($this->m_aArgs as $idx => $oExpr)
		{
			if ($oExpr instanceof VariableExpression)
			{
				$this->m_aArgs[$idx] = $oExpr->GetAsScalar($aArgs);
			}
			else
			{
				$oExpr->ApplyParameters($aArgs);
			}
		}
	}

	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
		foreach ($this->m_aArgs as $oExpr)
		{
			$oExpr->GetUnresolvedFields($sAlias, $aUnresolved);
		}
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		$aRes = array();
		foreach ($this->m_aArgs as $oExpr)
		{
			$aRes[] = $oExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}
		return new FunctionExpression($this->m_sVerb, $aRes);
	}

	public function ListRequiredFields()
	{
		$aRes = array();
		foreach ($this->m_aArgs as $oExpr)
		{
			$aRes = array_merge($aRes, $oExpr->ListRequiredFields());
		}
		return $aRes;
	}

	public function CollectUsedParents(&$aTable)
	{
		foreach ($this->m_aArgs as $oExpr)
		{
			$oExpr->CollectUsedParents($aTable);
		}
	}

	public function ListConstantFields()
	{
		$aRes = array();
		foreach ($this->m_aArgs as $oExpr)
		{
			$aRes = array_merge($aRes, $oExpr->ListConstantFields());
		}
		return $aRes;
	}

	public function RenameParam($sOldName, $sNewName)
	{
		foreach ($this->m_aArgs as $key => $oExpr)
		{
			$this->m_aArgs[$key] = $oExpr->RenameParam($sOldName, $sNewName);
		}
	}

	public function RenameAlias($sOldName, $sNewName)
	{
		foreach ($this->m_aArgs as $key => $oExpr)
		{
			$oExpr->RenameAlias($sOldName, $sNewName);
		}
	}

	public function GetAttDef($aClasses = array())
	{
		foreach($this->m_aArgs as $oExpression)
		{
			$oAttDef = $oExpression->GetAttDef($aClasses);
			if (!is_null($oAttDef)) return $oAttDef;
		}

		return null;
	}

	/**
	 * Make the most relevant label, given the value of the expression
	 *
	 * @param DBSearch oFilter The context in which this expression has been used
	 * @param string sValue The value returned by the query, for this expression
	 * @param string sDefault The default value if no relevant label could be computed
	 *
	 * @return string label
	 */
	public function MakeValueLabel($oFilter, $sValue, $sDefault)
	{
		static $aWeekDayToString = null;
		if (is_null($aWeekDayToString))
		{
			// Init the correspondance table
			$aWeekDayToString = array(
				0 => Dict::S('DayOfWeek-Sunday'),
				1 => Dict::S('DayOfWeek-Monday'),
				2 => Dict::S('DayOfWeek-Tuesday'),
				3 => Dict::S('DayOfWeek-Wednesday'),
				4 => Dict::S('DayOfWeek-Thursday'),
				5 => Dict::S('DayOfWeek-Friday'),
				6 => Dict::S('DayOfWeek-Saturday')
			);
		}
		static $aMonthToString = null;
		if (is_null($aMonthToString))
		{
			// Init the correspondance table
			$aMonthToString = array(
				1 => Dict::S('Month-01'),
				2 => Dict::S('Month-02'),
				3 => Dict::S('Month-03'),
				4 => Dict::S('Month-04'),
				5 => Dict::S('Month-05'),
				6 => Dict::S('Month-06'),
				7 => Dict::S('Month-07'),
				8 => Dict::S('Month-08'),
				9 => Dict::S('Month-09'),
				10 => Dict::S('Month-10'),
				11 => Dict::S('Month-11'),
				12 => Dict::S('Month-12'),
			);
		}

		$sRes = $sDefault;
		if (strtolower($this->m_sVerb) == 'date_format')
		{
			$oFormatExpr = $this->m_aArgs[1];
			if ($oFormatExpr->Render() == "'%w'")
			{
				if (isset($aWeekDayToString[(int)$sValue]))
				{
					$sRes = $aWeekDayToString[(int)$sValue];
				}
			}
			elseif ($oFormatExpr->Render() == "'%Y-%m'")
			{
				// yyyy-mm => "yyyy month"
				$iMonth = (int) substr($sValue, -2); // the two last chars
				$sRes = substr($sValue, 0, 4).' '.$aMonthToString[$iMonth];
			}
			elseif ($oFormatExpr->Render() == "'%Y-%m-%d'")
			{
				// yyyy-mm-dd => "month d"
				$iMonth = (int) substr($sValue, 5, 2);
				$sRes = $aMonthToString[$iMonth].' '.(int)substr($sValue, -2);
			}
			elseif ($oFormatExpr->Render() == "'%H'")
			{
				// H => "H Hour(s)"
				$sRes = $sValue.':00';
			}
		}
		return $sRes;
	}

	public function Display($oSearch, &$aArgs = null, $oAttDef = null, &$aCtx = array())
	{
		$sOperation = '';
		$sVerb = '';
		switch ($this->m_sVerb)
		{
			case 'ISNULL':
			case 'NOW':
				$sVerb = $this->VerbToNaturalLanguage();
				break;
			case 'DATE_SUB':
				$sVerb = ' -';
				break;
			case 'DATE_ADD':
				$sVerb = ' +';
				break;
			case 'DATE_FORMAT':
				$aCtx['date_display'] = $this;
				break;
			default:
				return $this->RenderExpression(false, $aArgs);
		}

		foreach($this->m_aArgs as $oExpression)
		{
			if ($oExpression instanceof IntervalExpression)
			{
				$sOperation .= $sVerb;
				$sVerb = '';
			}
			$sOperation .= $oExpression->Display($oSearch, $aArgs, $oAttDef, $aCtx);
		}

		if (!empty($sVerb))
		{
			$sOperation .= $sVerb;
		}
		return '('.$sOperation.')';
	}

	private function VerbToNaturalLanguage()
	{
		return Dict::S('Expression:Verb:'.$this->m_sVerb, " {$this->m_sVerb} ");
	}

	public function GetCriterion($oSearch, &$aArgs = null, $bRetrofitParams = false, $oAttDef = null)
	{
		$aCriteria = array();
		switch ($this->m_sVerb)
		{
			case 'ISNULL':
				$aCriteria['operator'] = $this->m_sVerb;
				foreach($this->m_aArgs as $oExpression)
				{
					$aCriteria = array_merge($oExpression->GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef), $aCriteria);
				}
				$aCriteria['has_undefined'] = true;
				$aCriteria['oql'] = $this->RenderExpression(false, $aArgs, $bRetrofitParams);
				break;

			case 'NOW':
				$aCriteria = array('widget' => 'date_time');
				$aCriteria['is_relative'] = true;
				$aCriteria['verb'] = $this->m_sVerb;
				break;

			case 'DATE_ADD':
			case 'DATE_SUB':
			case 'DATE_FORMAT':
				$aCriteria = array('widget' => 'date_time');
				foreach($this->m_aArgs as $oExpression)
				{
					$aCriteria = array_merge($oExpression->GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef), $aCriteria);
				}
				$aCriteria['verb'] = $this->m_sVerb;
				break;

			default:
				return parent::GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef);
		}

		return $aCriteria;
	}
}

/**
 * @see https://dev.mysql.com/doc/refman/5.7/en/date-and-time-functions.html
 */
class IntervalExpression extends Expression
{
	protected $m_oValue; // expression
	protected $m_sUnit;

	public function __construct($oValue, $sUnit)
	{
		$this->m_oValue = $oValue;
		$this->m_sUnit = $sUnit;
	}

	public function IsTrue()
	{
		// return true if we are certain that it will be true
		return false;
	}

	public function GetValue()
	{
		return $this->m_oValue;
	}

	public function GetUnit()
	{
		return $this->m_sUnit;
	}

	// recursive rendering
	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		return 'INTERVAL '.$this->m_oValue->RenderExpression($bForSQL, $aArgs, $bRetrofitParams).' '.$this->m_sUnit;
	}

	public function Browse(Closure $callback)
	{
		$callback($this);
		$this->m_oValue->Browse($callback);
	}

	public function ApplyParameters($aArgs)
	{
		if ($this->m_oValue instanceof VariableExpression)
		{
			$this->m_oValue = $this->m_oValue->GetAsScalar($aArgs);
		}
		else
		{
			$this->m_oValue->ApplyParameters($aArgs);
		}
	}

	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
		$this->m_oValue->GetUnresolvedFields($sAlias, $aUnresolved);
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		return new IntervalExpression($this->m_oValue->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved), $this->m_sUnit);
	}

	public function ListRequiredFields()
	{
		return array();
	}

	public function CollectUsedParents(&$aTable)
	{
	}

	public function ListConstantFields()
	{
		return array();
	}

	public function RenameParam($sOldName, $sNewName)
	{
		$this->m_oValue->RenameParam($sOldName, $sNewName);
	}

	public function RenameAlias($sOldName, $sNewName)
	{
		$this->m_oValue->RenameAlias($sOldName, $sNewName);
	}

	public function GetCriterion($oSearch, &$aArgs = null, $bRetrofitParams = false, $oAttDef = null)
	{
		$aCriteria = $this->m_oValue->GetCriterion($oSearch, $aArgs, $bRetrofitParams, $oAttDef);
		$aCriteria['unit'] = $this->m_sUnit;

		return $aCriteria;
	}

	public function Display($oSearch, &$aArgs = null, $oAttDef = null, &$aCtx = array())
	{
		return $this->m_oValue->RenderExpression(false, $aArgs).' '.Dict::S('Expression:Unit:Long:'.$this->m_sUnit, $this->m_sUnit);
	}
}

class CharConcatExpression extends Expression
{
	protected $m_aExpressions;

	public function __construct($aExpressions)
	{
		$this->m_aExpressions = $aExpressions;
	}

	public function IsTrue()
	{
		// return true if we are certain that it will be true
		return false;
	}

	public function GetItems()
	{
		return $this->m_aExpressions;
	}

	// recursive rendering
	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$sCol = $oExpr->RenderExpression($bForSQL, $aArgs, $bRetrofitParams);
			// Concat will be globally NULL if one single argument is null !
			$aRes[] = "COALESCE($sCol, '')";
		}
		return "CAST(CONCAT(".implode(', ', $aRes).") AS CHAR)";
	}

	public function Browse(Closure $callback)
	{
		$callback($this);
		foreach ($this->m_aExpressions as $oExpr)
		{
			$oExpr->Browse($callback);
		}
	}

	public function ApplyParameters($aArgs)
	{
		foreach ($this->m_aExpressions as $idx => $oExpr)
		{
			if ($oExpr instanceof VariableExpression)
			{
				$this->m_aExpressions[$idx] = $oExpr->GetAsScalar($aArgs);
			}
			else
			{
				$this->m_aExpressions->ApplyParameters($aArgs);
			}
		}
	}

	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
		foreach ($this->m_aExpressions as $oExpr)
		{
			$oExpr->GetUnresolvedFields($sAlias, $aUnresolved);
		}
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes[] = $oExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}
		return new CharConcatExpression($aRes);
	}

	public function ListRequiredFields()
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes = array_merge($aRes, $oExpr->ListRequiredFields());
		}
		return $aRes;
	}

	public function CollectUsedParents(&$aTable)
	{
		foreach ($this->m_aExpressions as $oExpr)
		{
			$oExpr->CollectUsedParents($aTable);
		}
	}

	public function ListConstantFields()
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes = array_merge($aRes, $oExpr->ListConstantFields());
		}
		return $aRes;
	}

	public function RenameParam($sOldName, $sNewName)
	{
		foreach ($this->m_aExpressions as $key => $oExpr)
		{
			$this->m_aExpressions[$key] = $oExpr->RenameParam($sOldName, $sNewName);
		}
	}

	public function RenameAlias($sOldName, $sNewName)
	{
		foreach ($this->m_aExpressions as $key => $oExpr)
		{
			$oExpr->RenameAlias($sOldName, $sNewName);
		}
	}
}


class CharConcatWSExpression extends CharConcatExpression
{
	protected $m_separator;

	public function __construct($separator, $aExpressions)
	{
		$this->m_separator = $separator;
		parent::__construct($aExpressions);
	}

	// recursive rendering
	public function RenderExpression($bForSQL = false, &$aArgs = null, $bRetrofitParams = false)
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$sCol = $oExpr->RenderExpression($bForSQL, $aArgs, $bRetrofitParams);
			// Concat will be globally NULL if one single argument is null !
			$aRes[] = "COALESCE($sCol, '')";
		}
		$sSep = CMDBSource::Quote($this->m_separator);
		return "CAST(CONCAT_WS($sSep, ".implode(', ', $aRes).") AS CHAR)";
	}

	public function Browse(Closure $callback)
	{
		$callback($this);
		foreach ($this->m_aExpressions as $oExpr)
		{
			$oExpr->Browse($callback);
		}
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes[] = $oExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}
		return new CharConcatWSExpression($this->m_separator, $aRes);
	}
}


class QueryBuilderExpressions
{
	/**
	 * @var Expression
	 */
	protected $m_oConditionExpr;
	/**
	 * @var Expression[]
	 */
	protected $m_aSelectExpr;
	/**
	 * @var Expression[]
	 */
	protected $m_aGroupByExpr;
	/**
	 * @var Expression[]
	 */
	protected $m_aJoinFields;
	/**
	 * @var string[]
	 */
	protected $m_aClassIds;

	public function __construct(DBObjectSearch $oSearch, $aGroupByExpr = null, $aSelectExpr = null)
	{
		$this->m_oConditionExpr = $oSearch->GetCriteria();
		if (!$oSearch->GetShowObsoleteData())
		{
			foreach ($oSearch->GetSelectedClasses() as $sAlias => $sClass)
			{
				if (MetaModel::IsObsoletable($sClass))
				{
					$oNotObsolete = new BinaryExpression(new FieldExpression('obsolescence_flag', $sAlias), '=', new ScalarExpression(0));
					$this->m_oConditionExpr = $this->m_oConditionExpr->LogAnd($oNotObsolete);
				}
			}
		}
		$this->m_aSelectExpr = is_null($aSelectExpr) ? array() : $aSelectExpr;
		$this->m_aGroupByExpr = $aGroupByExpr;
		$this->m_aJoinFields = array();

		$this->m_aClassIds = array();
		foreach($oSearch->GetJoinedClasses() as $sClassAlias => $sClass)
		{
			$this->m_aClassIds[$sClassAlias] = new FieldExpression('id', $sClassAlias);
		}
	}

	public function GetSelect()
	{
		return $this->m_aSelectExpr;
	}

	public function GetGroupBy()
	{
		return $this->m_aGroupByExpr;
	}

	public function GetCondition()
	{
		return $this->m_oConditionExpr;
	}

	/**
	 * @return Expression|mixed
	 */
	public function PopJoinField()
	{
		return array_pop($this->m_aJoinFields);
	}

	/**
	 * @param string $sAttAlias
	 * @param Expression $oExpression
	 */
	public function AddSelect($sAttAlias, Expression $oExpression)
	{
		$this->m_aSelectExpr[$sAttAlias] = $oExpression;
	}

	/**
	 * @param Expression $oExpression
	 */
	public function AddCondition(Expression $oExpression)
	{
		$this->m_oConditionExpr = $this->m_oConditionExpr->LogAnd($oExpression);
	}

	/**
	 * @param Expression $oExpression
	 */
	public function PushJoinField(Expression $oExpression)
	{
		array_push($this->m_aJoinFields, $oExpression);
	}

	/**
	 * Get tables representing the queried objects
	 * Could be further optimized: when the first join is an outer join, then the rest can be omitted
	 * @param array $aTables
	 * @return array
	 */
	public function GetMandatoryTables(&$aTables = null)
	{
		if (is_null($aTables)) $aTables = array();

		foreach($this->m_aClassIds as $sClass => $oExpression)
		{
			$oExpression->CollectUsedParents($aTables);
		}

		return $aTables;
	}

	public function GetUnresolvedFields($sAlias, &$aUnresolved)
	{
		$this->m_oConditionExpr->GetUnresolvedFields($sAlias, $aUnresolved);
		foreach($this->m_aSelectExpr as $sColAlias => $oExpr)
		{
			$oExpr->GetUnresolvedFields($sAlias, $aUnresolved);
		}
		if ($this->m_aGroupByExpr)
		{
			foreach($this->m_aGroupByExpr as $sColAlias => $oExpr)
			{
				$oExpr->GetUnresolvedFields($sAlias, $aUnresolved);
			}
		}
		foreach($this->m_aJoinFields as $oExpression)
		{
			$oExpression->GetUnresolvedFields($sAlias, $aUnresolved);
		}
	}

	public function Translate($aTranslationData, $bMatchAll = true, $bMarkFieldsAsResolved = true)
	{
		$this->m_oConditionExpr = $this->m_oConditionExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		foreach($this->m_aSelectExpr as $sColAlias => $oExpr)
		{
			$this->m_aSelectExpr[$sColAlias] = $oExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}
		if ($this->m_aGroupByExpr)
		{
			foreach($this->m_aGroupByExpr as $sColAlias => $oExpr)
			{
				$this->m_aGroupByExpr[$sColAlias] = $oExpr->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
			}
		}
		foreach($this->m_aJoinFields as $index => $oExpression)
		{
			$this->m_aJoinFields[$index] = $oExpression->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}

		foreach($this->m_aClassIds as $sClass => $oExpression)
		{
			$this->m_aClassIds[$sClass] = $oExpression->Translate($aTranslationData, $bMatchAll, $bMarkFieldsAsResolved);
		}
	}

	public function RenameParam($sOldName, $sNewName)
	{
		$this->m_oConditionExpr->RenameParam($sOldName, $sNewName);
		foreach($this->m_aSelectExpr as $sColAlias => $oExpr)
		{
			$this->m_aSelectExpr[$sColAlias] = $oExpr->RenameParam($sOldName, $sNewName);
		}
		if ($this->m_aGroupByExpr)
		{
			foreach($this->m_aGroupByExpr as $sColAlias => $oExpr)
			{
				$this->m_aGroupByExpr[$sColAlias] = $oExpr->RenameParam($sOldName, $sNewName);
			}
		}
		foreach($this->m_aJoinFields as $index => $oExpression)
		{
			$this->m_aJoinFields[$index] = $oExpression->RenameParam($sOldName, $sNewName);
		}
	}
}