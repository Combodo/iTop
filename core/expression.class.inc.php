<?php

/**
 * General definition of an expression tree (could be OQL, SQL or whatever) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
abstract class Expression
{
	// recursive translation of identifiers
	abstract public function Translate($aTranslationData, $bMatchAll = true);

	// recursive rendering
	abstract public function Render();

	// recursively builds an array of class => fieldname
	abstract public function ListRequiredFields();

	public function RequiresField($sClass, $sFieldName)
	{
		// #@# todo - optimize : this is called quite often when building a single query !
		$aRequired = $this->ListRequiredFields();
		if (!in_array($sClass.'.'.$sFieldName, $aRequired)) return false;
		return true;
	}

	public function serialize()
	{
		return base64_encode($this->Render());
	}
	
	static public function unserialize($sValue)
	{
		return self::FromOQL(base64_decode($sValue));
	}

	static public function FromOQL($sConditionExpr)
	{
		$oOql = new OqlInterpreter($sConditionExpr);
		$oExpression = $oOql->ParseExpression();
		
		return $oExpression;
	}

	public function LogAnd($oExpr)
	{
		return new BinaryExpression($this, 'AND', $oExpr);
	}

	public function LogOr($oExpr)
	{
		return new BinaryExpression($this, 'OR', $oExpr);
	}

}


class BinaryExpression extends Expression
{
	protected $m_oLeftExpr; // filter code or an SQL expression (later?)
	protected $m_oRightExpr;
	protected $m_sOperator;

	public function __construct($oLeftExpr, $sOperator, $oRightExpr)
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
		$this->m_oLeftExpr  = $oLeftExpr;
		$this->m_oRightExpr = $oRightExpr;
		$this->m_sOperator  = $sOperator;
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

	// recursive rendering
	public function Render()
	{
		$sOperator = $this->GetOperator();
		$sLeft = $this->GetLeftExpr()->Render();
		$sRight = $this->GetRightExpr()->Render();
		return "($sLeft $sOperator $sRight)";
	}

	public function Translate($aTranslationData, $bMatchAll = true)
	{
		$oLeft = $this->GetLeftExpr()->Translate($aTranslationData, $bMatchAll);
		$oRight = $this->GetRightExpr()->Translate($aTranslationData, $bMatchAll);
		return new BinaryExpression($oLeft, $this->GetOperator(), $oRight);
	}

	public function ListRequiredFields()
	{
		$aLeft = $this->GetLeftExpr()->ListRequiredFields();
		$aRight = $this->GetRightExpr()->ListRequiredFields();
		return array_merge($aLeft, $aRight);
	}
}


class UnaryExpression extends Expression
{
	protected $m_value;

	public function __construct($value)
	{
		$this->m_value = $value;
	}

	public function GetValue()
	{
		return $this->m_value;
	} 

	// recursive rendering
	public function Render()
	{
		return CMDBSource::Quote($this->m_value);
	}

	public function Translate($aTranslationData, $bMatchAll = true)
	{
		return clone $this;
	}

	public function ListRequiredFields()
	{
		return array();
	}
}

class ScalarExpression extends UnaryExpression
{
	public function __construct($value)
	{
		if (!is_scalar($value))
		{
			throw new CoreException('Attempt to create a scalar expression from a non scalar', array('var_type'=>gettype($value)));
		}
		parent::__construct($value);
	}
}

class TrueExpression extends ScalarExpression
{
	public function __construct()
	{
		parent::__construct(1);
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

	public function GetParent() {return $this->m_sParent;}
	public function GetName() {return $this->m_sName;}

	// recursive rendering
	public function Render()
	{
		if (empty($this->m_sParent))
		{
			return "`{$this->m_sName}`";
		}
		return "`{$this->m_sParent}`.`{$this->m_sName}`";
	}

	public function Translate($aTranslationData, $bMatchAll = true)
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
		}
		else
		{
			$sNewParent = $aTranslationData[$this->m_sParent][$this->m_sName][0];
			$sNewName = $aTranslationData[$this->m_sParent][$this->m_sName][1];
		}
		return new FieldExpression($sNewName, $sNewParent);
	}

	public function ListRequiredFields()
	{
		return array($this->m_sParent.'.'.$this->m_sName);
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

	public function GetItems()
	{
		return $this->m_aExpressions;
	}

	// recursive rendering
	public function Render()
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes[] = $oExpr->Render();
		}
		return '('.implode(', ', $aRes).')';
	}

	public function Translate($aTranslationData, $bMatchAll = true)
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes[] = $oExpr->Translate($aTranslationData, $bMatchAll);
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

	public function GetVerb()
	{
		return $this->m_sVerb;
	}

	public function GetArgs()
	{
		return $this->m_aArgs;
	}

	// recursive rendering
	public function Render()
	{
		$aRes = array();
		foreach ($this->m_aArgs as $oExpr)
		{
			$aRes[] = $oExpr->Render();
		}
		return $this->m_sVerb.'('.implode(', ', $aRes).')';
	}

	public function Translate($aTranslationData, $bMatchAll = true)
	{
		$aRes = array();
		foreach ($this->m_aArgs as $oExpr)
		{
			$aRes[] = $oExpr->Translate($aTranslationData, $bMatchAll);
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
}

class IntervalExpression extends Expression
{
	protected $m_oValue; // expression
	protected $m_sUnit;

	public function __construct($oValue, $sUnit)
	{
		$this->m_oValue = $oValue;
		$this->m_sUnit = $sUnit;
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
	public function Render()
	{
		return 'INTERVAL '.$this->m_oValue->Render().' '.$this->m_sUnit;
	}

	public function Translate($aTranslationData, $bMatchAll = true)
	{
		return new IntervalExpression($this->m_oValue->Translate($aTranslationData, $bMatchAll), $this->m_sUnit);
	}

	public function ListRequiredFields()
	{
		return array();
	}
}

class CharConcatExpression extends Expression
{
	protected $m_aExpressions;

	public function __construct($aExpressions)
	{
		$this->m_aExpressions = $aExpressions;
	}

	public function GetItems()
	{
		return $this->m_aExpressions;
	}

	// recursive rendering
	public function Render()
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$sCol = $oExpr->Render();
			// Concat will be globally NULL if one single argument is null ! 
			$aRes[] = "COALESCE($sCol, '')";
		}
		return "CAST(CONCAT(".implode(', ', $aRes).") AS CHAR)";
	}

	public function Translate($aTranslationData, $bMatchAll = true)
	{
		$aRes = array();
		foreach ($this->m_aExpressions as $oExpr)
		{
			$aRes[] = $oExpr->Translate($aTranslationData, $bMatchAll);
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
}

?>
