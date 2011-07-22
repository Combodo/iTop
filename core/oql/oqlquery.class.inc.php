<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Classes defined for lexical analyze (see oql-parser.y)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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

class BinaryOqlExpression extends BinaryExpression
{
}

class ScalarOqlExpression extends ScalarExpression
{
}

class FieldOqlExpression extends FieldExpression
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
}

class VariableOqlExpression extends VariableExpression
{
}

class ListOqlExpression extends ListExpression
{
}

class FunctionOqlExpression extends FunctionExpression
{
}

class IntervalOqlExpression extends IntervalExpression
{
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
}

?>
