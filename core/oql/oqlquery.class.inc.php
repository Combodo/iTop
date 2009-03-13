<?

class OqlJoinSpec
{
	protected $m_sClass;
	protected $m_sClassAlias;
	protected $m_oLeftField;
	protected $m_oRightField;

	protected $m_oNextJoinspec;

	public function __construct($sClass, $sClassAlias, BinaryExpression $oExpression)
	{
		$this->m_sClass = $sClass;
		$this->m_sClassAlias = $sClassAlias;
		$this->m_oLeftField = $oExpression->GetLeftExpr();
		$this->m_oRightField = $oExpression->GetRightExpr();
	}

	public function GetClass()
	{
		return $this->m_sClass;
	}
	public function GetClassAlias()
	{
		return $this->m_sClassAlias;
	}
	public function GetLeftField()
	{
		return $this->m_oLeftField;
	}
	public function GetRightField()
	{
		return $this->m_oRightField;
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
	protected $m_iPosition; // position in the source string
	
	public function __construct($iPosition, $sName, $sParent = '')
	{
		$this->m_iPosition = $iPosition;
		parent::__construct($sName, $sParent);
	}

	public function GetPosition()
	{
		return $this->m_iPosition;
	} 
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
class OqlQuery
{
	protected $m_sClass;
	protected $m_sClassAlias;
	protected $m_aJoins; // array of OqlJoinSpec
	protected $m_oCondition; // condition tree (expressions)

	public function __construct($sClass, $sClassAlias = '', $oCondition = null, $aJoins = null)
	{
		$this->m_sClass = $sClass;
		$this->m_sClassAlias = $sClassAlias;
		$this->m_aJoins = $aJoins;
		$this->m_oCondition = $oCondition;
	}

	public function GetClass()
	{
		return $this->m_sClass;
	}
	public function GetClassAlias()
	{
		return $this->m_sClassAlias;
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

?>
