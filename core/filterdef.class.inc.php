<?php


require_once('MyHelpers.class.inc.php');


/**
 * Definition of a filter (could be made out of an existing attribute, or from an expression) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
abstract class FilterDefinition
{
	abstract public function GetType();
	abstract public function GetTypeDesc();

	protected $m_sCode;
	private $m_aParams = array();
	protected function Get($sParamName) {return $this->m_aParams[$sParamName];}
	
	public function __construct($sCode, $aParams = array())
	{
		$this->m_sCode = $sCode;
		$this->m_aParams = $aParams;
		$this->ConsistencyCheck();
	}

	public function OverloadParams($aParams)
	{
		foreach ($aParams as $sParam => $value)
		{
			if (!array_key_exists($sParam, $this->m_aParams))
			{
				trigger_error("Unknown attribute definition parameter '$sParam', please select a value in {".implode(", ", $this->m_aParams)."}");
			}
			else
			{
				$this->m_aParams[$sParam] = $value;
			}
		}
	}

	// to be overloaded
	static protected function ListExpectedParams()
	{
		return array();
	}

	private function ConsistencyCheck()
	{
		// Check that any mandatory param has been specified
		//
		$aExpectedParams = $this->ListExpectedParams();
		foreach($aExpectedParams as $sParamName)
		{
			if (!array_key_exists($sParamName, $this->m_aParams))
			{
				$aBacktrace = debug_backtrace();
				$sTargetClass = $aBacktrace[2]["class"];
				$sCodeInfo = $aBacktrace[1]["file"]." - ".$aBacktrace[1]["line"];
				trigger_error("ERROR missing parameter '$sParamName' in ".get_class($this)." declaration for class $sTargetClass ($sCodeInfo)</br>\n", E_USER_ERROR);
			}
		}
	} 

	public function GetCode() {return $this->m_sCode;} 
	abstract public function GetLabel(); 
	abstract public function GetValuesDef(); 

	// returns an array of opcode=>oplabel (e.g. "differs from")
	abstract public function GetOperators();
	// returns an opcode
	abstract public function GetLooseOperator();
	abstract public function GetFilterSQLExpr($sOpCode, $value);
	abstract public function TemporaryGetSQLCol();

	// Wrapper - no need for overloading this one
	public function GetOpDescription($sOpCode)
	{
		$aOperators = $this->GetOperators();
		if (!array_key_exists($sOpCode, $aOperators))
		{
			trigger_error("Unknown operator '$sOpCode'", E_USER_ERROR);
		}
		
		return $aOperators[$sOpCode];
	}
}

/**
 * Match against the object unique identifier 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class FilterPrivateKey extends FilterDefinition
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("pkey_field"));
	}

	public function GetType() {return "PKey";}
	public function GetTypeDesc() {return "Match against object identifier";}

	public function GetLabel()
	{
		return "Object Private Key";
	} 

	public function GetValuesDef()
	{
		return null;
	}

	public function GetOperators()
	{
		return array(
			"="=>"equals",
			"!="=>"differs from",
			"IN"=>"in",
			"NOTIN"=>"not in"
		);
	}
	public function GetLooseOperator()
	{
		return "IN";
	}

	public function GetFilterSQLExpr($sOpCode, $value)
	{
		$sFieldName = $this->Get("pkey_field");
		// #@# not obliged to quote... these are numbers !!!
		$sQValue = CMDBSource::Quote($value);
		switch($sOpCode)
		{
			case "IN":
				if (!is_array($sQValue)) trigger_error("Expected an array for argument value (sOpCode='$sOpCode')");
				return "$sFieldName IN (".implode(", ", $sQValue).")"; 

			case "NOTIN":
				if (!is_array($sQValue)) trigger_error("Expected an array for argument value (sOpCode='$sOpCode')");
				return "$sFieldName NOT IN (".implode(", ", $sQValue).")"; 

			case "!=":
				return $sFieldName." != ".$sQValue;

			case "=":
			default:
				return $sFieldName." = ".$sQValue;
		}
	}
	public function TemporaryGetSQLCol()
	{
		return $this->Get("pkey_field");
	}
}

/**
 * Match against an existing attribute (the attribute type will determine the available operators) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class FilterFromAttribute extends FilterDefinition
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("refattribute"));
	}

	public function __construct($oRefAttribute, $aParam = array())
	{
		// In this very specific case, the code is the one of the attribute
		 // (this to get a very very simple syntax upon declaration)
		$aParam["refattribute"] = $oRefAttribute;
		parent::__construct($oRefAttribute->GetCode(), $aParam);
	}

	public function GetType() {return "Basic";}
	public function GetTypeDesc() {return "Match against field contents";}

	public function __GetRefAttribute() // for checking purposes only !!!
	{
		return $oAttDef = $this->Get("refattribute");
	}

	public function GetLabel()
	{
		$oAttDef = $this->Get("refattribute");
		return $oAttDef->GetLabel();
	} 

	public function GetValuesDef()
	{
		$oAttDef = $this->Get("refattribute");
		return $oAttDef->GetValuesDef();
	} 

	public function GetOperators()
	{
		$oAttDef = $this->Get("refattribute");
		return $oAttDef->GetBasicFilterOperators();
	}
	public function GetLooseOperator()
	{
		$oAttDef = $this->Get("refattribute");
		return $oAttDef->GetBasicFilterLooseOperator();
	}

	public function GetFilterSQLExpr($sOpCode, $value)
	{
		$oAttDef = $this->Get("refattribute");
		return $oAttDef->GetBasicFilterSQLExpr($sOpCode, $value);
	}

	public function TemporaryGetSQLCol()
	{
		$oAttDef = $this->Get("refattribute");
		return $oAttDef->GetSQLExpr();
	}
}

/**
 * Match against a given column (experimental -to be cleaned up later) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class FilterDBValues extends FilterDefinition
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("dbfield"));
	}

	public function GetType() {return "Values from DB";}
	public function GetTypeDesc() {return "Match against the existing values in a field";}

	public function GetLabel()
	{
		return "enum de valeurs DB";
	} 

	public function GetValuesDef()
	{
		return null;
	}

	public function GetOperators()
	{
		return array(
			"IN"=>"in",
		);
	}
	public function GetLooseOperator()
	{
		return "IN";
	}

	public function GetFilterSQLExpr($sOpCode, $value)
	{
		$sFieldName = $this->Get("dbfield");
		if (is_array($value) && !empty($value))
		{
			$sValueList = "'".implode("', '", $value)."'";
			return "$sFieldName IN ($sValueList)";
		}
		return "1=1";
	}

	public function TemporaryGetSQLCol()
	{
		return $this->Get("dbfield");
	}
}

?>
