<?php

require_once('MyHelpers.class.inc.php');
require_once('ormdocument.class.inc.php');

/**
 * MissingColumnException - sent if an attribute is being created but the column is missing in the row 
 *
 * @package     iTopORM
 */
class MissingColumnException extends Exception
{}

/**
 * add some description here... 
 *
 * @package     iTopORM
 */
define('EXTKEY_RELATIVE', 1);

/**
 * add some description here... 
 *
 * @package     iTopORM
 */
define('EXTKEY_ABSOLUTE', 2);

/**
 * Propagation of the deletion through an external key - ask the user to delete the referencing object 
 *
 * @package     iTopORM
 */
define('DEL_MANUAL', 1);

/**
 * Propagation of the deletion through an external key - ask the user to delete the referencing object 
 *
 * @package     iTopORM
 */
define('DEL_AUTO', 2);


/**
 * Attribute definition API, implemented in and many flavours (Int, String, Enum, etc.) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @author      Denis Flaven <denisflave@free.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     1.1.1.1 $
 */
abstract class AttributeDefinition
{
	abstract public function GetType();
	abstract public function GetTypeDesc();
	abstract public function GetEditClass();

	protected $m_sCode;
	private $m_aParams = array();
	private $m_sHostClass = array();
	protected function Get($sParamName) {return $this->m_aParams[$sParamName];}
	
	public function __construct($sCode, $aParams)
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
				throw new CoreException("Unknown attribute definition parameter '$sParam', please select a value in {".implode(", ", $this->m_aParams)."}");
			}
			else
			{
				$this->m_aParams[$sParam] = $value;
			}
		}
	}
	public function SetHostClass($sHostClass)
	{
		$this->m_sHostClass = $sHostClass;
	}
	public function GetHostClass()
	{
		return $this->m_sHostClass;
	}

	// Note: I could factorize this code with the parameter management made for the AttributeDef class
	// to be overloaded
	static protected function ListExpectedParams()
	{
		return array("label", "description");
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
				throw new Exception("ERROR missing parameter '$sParamName' in ".get_class($this)." declaration for class $sTargetClass ($sCodeInfo)");
			}
		}
	} 

	// table, key field, name field
	public function ListDBJoins()
	{
		return "";
		// e.g: return array("Site", "infrid", "name");
	} 
	public function IsDirectField() {return false;} 
	public function IsScalar() {return false;} 
	public function IsLinkSet() {return false;} 
	public function IsExternalKey($iType = EXTKEY_RELATIVE) {return false;} 
	public function IsExternalField() {return false;} 
	public function IsWritable() {return false;} 
	public function IsNullAllowed() {return true;} 
	public function GetCode() {return $this->m_sCode;} 
	public function GetLabel() {return $this->Get("label");} 
	public function GetDescription() {return $this->Get("description");} 
	public function GetValuesDef() {return null;} 
	public function GetPrerequisiteAttributes() {return array();} 
	//public function IsSearchableStd() {return $this->Get("search_std");} 
	//public function IsSearchableGlobal() {return $this->Get("search_global");} 
	//public function IsMandatory() {return $this->Get("is_mandatory");} 
	//public function GetMinVal() {return $this->Get("min");} 
	//public function GetMaxVal() {return $this->Get("max");} 
	//public function GetSize() {return $this->Get("size");} 
	//public function GetCheckRegExp() {return $this->Get("regexp");} 
	//public function GetCheckFunc() {return $this->Get("checkfunc");} 

	public function MakeRealValue($proposedValue) {return $proposedValue;} // force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!)

	public function GetSQLExpressions() {return array();} // returns suffix/expression pairs (1 in most of the cases), for READING (Select)
	public function FromSQLToValue($aCols, $sPrefix = '') {return null;} // returns a value out of suffix/value pairs, for SELECT result interpretation
	public function GetSQLColumns() {return array();} // returns column/spec pairs (1 in most of the cases), for STRUCTURING (DB creation)
	public function GetSQLValues($value) {return array();} // returns column/value pairs (1 in most of the cases), for WRITING (Insert, Update)

	public function GetJSCheckFunc()
	{
		$sRegExp = $this->Get("regexp");
		if (empty($sRegExp)) return 'return true;';

		return "return regexp('$sRegExp', myvalue);";
	} 
	public function CheckValue($value)
	{
		$sRegExp = $this->Get("regexp");
		if (empty($sRegExp)) return true;
		
		return preg_match(preg_escape($this->Get("regexp")), $value);
	}
	 
	public function MakeValue()
	{
		$sComputeFunc = $this->Get("compute_func");
		if (empty($sComputeFunc)) return null;

		return call_user_func($sComputeFunc);
	}
	
	abstract public function GetDefaultValue();

	//
	// To be overloaded in subclasses
	//
	
	abstract public function GetBasicFilterOperators(); // returns an array of "opCode"=>"description"
	abstract public function GetBasicFilterLooseOperator(); // returns an "opCode"
	//abstract protected GetBasicFilterHTMLInput();
	abstract public function GetBasicFilterSQLExpr($sOpCode, $value); 

	public function GetAsHTML($sValue)
	{
		return Str::pure2html((string)$sValue);
	}

	public function GetAsXML($sValue)
	{
		return Str::pure2xml((string)$sValue);
	}

	public function GetAsCSV($sValue, $sSeparator = ';', $sSepEscape = ',')
	{
		return str_replace($sSeparator, $sSepEscape, (string)$sValue);
	}

	public function GetAllowedValues($aArgs = array(), $sBeginsWith = '')
	{
		$oValSetDef = $this->GetValuesDef();
		if (!$oValSetDef) return null;
		return $oValSetDef->GetValues($aArgs, $sBeginsWith);
	}
}

/**
 * Set of objects directly linked to an object, and being part of its definition  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeLinkedSet extends AttributeDefinition
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("allowed_values", "depends_on", "linked_class", "ext_key_to_me", "count_min", "count_max"));
	}

	public function GetType() {return "Array of objects";}
	public function GetTypeDesc() {return "Any kind of objects [subclass] of the same class";}
	public function GetEditClass() {return "List";}

	public function IsWritable() {return true;} 
	public function IsLinkSet() {return true;} 

	public function GetValuesDef() {return $this->Get("allowed_values");} 
	public function GetPrerequisiteAttributes() {return $this->Get("depends_on");} 
	public function GetDefaultValue() {return DBObjectSet::FromScratch($this->Get('linked_class'));}

	public function GetLinkedClass() {return $this->Get('linked_class');}
	public function GetExtKeyToMe() {return $this->Get('ext_key_to_me');}

	public function GetBasicFilterOperators() {return array();}
	public function GetBasicFilterLooseOperator() {return '';}
	public function GetBasicFilterSQLExpr($sOpCode, $value) {return '';}

	public function GetAsHTML($sValue)
	{
		return "ERROR: LIST OF OBJECTS";
	}

	public function GetAsXML($sValue)
	{
		return "ERROR: LIST OF OBJECTS";
	}

	public function GetAsCSV($sValue, $sSeparator = ';', $sSepEscape = ',')
	{
		return "ERROR: LIST OF OBJECTS";
	}
}

/**
 * Set of objects linked to an object (n-n), and being part of its definition  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeLinkedSetIndirect extends AttributeLinkedSet
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("ext_key_to_remote"));
	}
	public function GetExtKeyToRemote() { return $this->Get('ext_key_to_remote'); }
}

/**
 * Abstract class implementing default filters for a DB column  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeDBFieldVoid extends AttributeDefinition
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("allowed_values", "depends_on", "sql"));
	}

	// To be overriden, used in GetSQLColumns
	protected function GetSQLCol() {return "VARCHAR(255)";}

	public function GetType() {return "Void";}
	public function GetTypeDesc() {return "Any kind of value, from the DB";}
	public function GetEditClass() {return "String";}
	
	public function GetValuesDef() {return $this->Get("allowed_values");} 
	public function GetPrerequisiteAttributes() {return $this->Get("depends_on");} 

	public function IsDirectField() {return true;} 
	public function IsScalar() {return true;} 
	public function IsWritable() {return true;} 
	public function GetSQLExpr()    {return $this->Get("sql");}
	public function GetDefaultValue() {return "";}
	public function IsNullAllowed() {return false;}

	protected function ScalarToSQL($value) {return $value;} // format value as a valuable SQL literal (quoted outside)
	protected function SQLToScalar($value) {return $value;} // take the result of a fetch... and make it a PHP variable

	public function GetSQLExpressions()
	{
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determine by the existence of one column with an empty suffix
		$aColumns[''] = $this->Get("sql");
		return $aColumns;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$value = $this->MakeRealValue($aCols[$sPrefix.'']);
		return $value;
	}
	public function GetSQLValues($value)
	{
		$aValues = array();
		$aValues[$this->Get("sql")] = $this->ScalarToSQL($value);
		return $aValues;
	}

	public function GetSQLColumns()
	{
		$aColumns = array();
		$aColumns[$this->Get("sql")] = $this->GetSQLCol();
		return $aColumns;
	}

	public function GetBasicFilterOperators()
	{
		return array("="=>"equals", "!="=>"differs from");
	}
	public function GetBasicFilterLooseOperator()
	{
		return "=";
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		$sQValue = CMDBSource::Quote($value);
		switch ($sOpCode)
		{
		case '!=':
			return $this->GetSQLExpr()." != $sQValue";
			break;
		case '=':
		default:
			return $this->GetSQLExpr()." = $sQValue";
		}
	} 
}

/**
 * Base class for all kind of DB attributes, with the exception of external keys 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeDBField extends AttributeDBFieldVoid
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("default_value", "is_null_allowed"));
	}
	public function GetDefaultValue() {return $this->Get("default_value");}
	public function IsNullAllowed() {return strtolower($this->Get("is_null_allowed"));}
}

/**
 * Map an integer column to an attribute 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeInteger extends AttributeDBField
{
	static protected function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetType() {return "Integer";}
	public function GetTypeDesc() {return "Numeric value (could be negative)";}
	public function GetEditClass() {return "String";}
	protected function GetSQLCol() {return "INT";}
	
	public function GetBasicFilterOperators()
	{
		return array(
			"!="=>"differs from",
			"="=>"equals",
			">"=>"greater (strict) than",
			">="=>"greater than",
			"<"=>"less (strict) than",
			"<="=>"less than",
			"in"=>"in"
		);
	}
	public function GetBasicFilterLooseOperator()
	{
		// Unless we implement an "equals approximately..." or "same order of magnitude"
		return "=";
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		$sQValue = CMDBSource::Quote($value);
		switch ($sOpCode)
		{
		case '!=':
			return $this->GetSQLExpr()." != $sQValue";
			break;
		case '>':
			return $this->GetSQLExpr()." > $sQValue";
			break;
		case '>=':
			return $this->GetSQLExpr()." >= $sQValue";
			break;
		case '<':
			return $this->GetSQLExpr()." < $sQValue";
			break;
		case '<=':
			return $this->GetSQLExpr()." <= $sQValue";
			break;
		case 'in':
			if (!is_array($value)) throw new CoreException("Expected an array for argument value (sOpCode='$sOpCode')");
			return $this->GetSQLExpr()." IN ('".implode("', '", $value)."')"; 
			break;

		case '=':
		default:
			return $this->GetSQLExpr()." = \"$value\"";
		}
	} 

	public function MakeRealValue($proposedValue)
	{
		//return intval($proposedValue); could work as well
		return (int)$proposedValue;
	}
	public function ScalarToSQL($value)
	{
		assert(is_numeric($value));
		return $value; // supposed to be an int
	}
	public function SQLToScalar($value)
	{
		// Use cast (int) or intval() ?
		return (int)$value;
		
	}
}

/**
 * Map a varchar column (size < ?) to an attribute 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeString extends AttributeDBField
{
	static protected function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetType() {return "String";}
	public function GetTypeDesc() {return "Alphanumeric string";}
	public function GetEditClass() {return "String";}
	protected function GetSQLCol() {return "VARCHAR(255)";}

	public function GetBasicFilterOperators()
	{
		return array(
			"="=>"equals",
			"!="=>"differs from",
			"Like"=>"equals (no case)",
			"NotLike"=>"differs from (no case)",
			"Contains"=>"contains",
			"Begins with"=>"begins with",
			"Finishes with"=>"finishes with"
		);
	}
	public function GetBasicFilterLooseOperator()
	{
		return "Contains";
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		$sQValue = CMDBSource::Quote($value);
		switch ($sOpCode)
		{
		case '=':
		case '!=':
			return $this->GetSQLExpr()." $sOpCode $sQValue";
		case 'Begins with':
			return $this->GetSQLExpr()." LIKE ".CMDBSource::Quote("$value%");
		case 'Finishes with':
			return $this->GetSQLExpr()." LIKE ".CMDBSource::Quote("%$value");
		case 'Contains':
			return $this->GetSQLExpr()." LIKE ".CMDBSource::Quote("%$value%");
		case 'NotLike':
			return $this->GetSQLExpr()." NOT LIKE $sQValue";
		case 'Like':
		default:
			return $this->GetSQLExpr()." LIKE $sQValue";
		}
	} 

	public function MakeRealValue($proposedValue)
	{
		return (string)$proposedValue;
		// if (!settype($proposedValue, "string"))
		// {
		// 	throw new CoreException("Failed to change the type of '$proposedValue' to a string");
		// }
	}
	public function ScalarToSQL($value)
	{
		if (!is_string($value))
		{
			throw new CoreWarning('Expected the attribute value to be a string', array('found_type' => gettype($value), 'value' => $value, 'class' => $this->GetCode(), 'attribute' => $this->GetHostClass()));
		}
		return $value;
	}
	public function SQLToScalar($value)
	{
		return $value;
	}
}


/**
 * Map a varchar column (size < ?) to an attribute that must never be shown to the user 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributePassword extends AttributeString
{
	static protected function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass() {return "Password";}
	protected function GetSQLCol() {return "VARCHAR(64)";}
}

/**
 * Map a text column (size > ?) to an attribute 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeText extends AttributeString
{
	public function GetType() {return "Text";}
	public function GetTypeDesc() {return "Multiline character string";}
	public function GetEditClass() {return "Text";}
	protected function GetSQLCol() {return "TEXT";}

	public function GetAsHTML($sValue)
	{
		return str_replace("\n", "<br>\n", parent::GetAsHTML($sValue));
	}

	public function GetAsXML($value)
	{
		return Str::pure2xml($value);
	}

	public function GetAsCSV($value, $sSeparator = ';', $sSepEscape = ',')
	{
		return str_replace("\n", "[newline]", parent::GetAsCSV($sValue, $sSeparator, $sSepEscape));
	}
}

/**
 * Map a enum column to an attribute 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeEnum extends AttributeString
{
	static protected function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetType() {return "Enum";}
	public function GetTypeDesc() {return "List of predefined alphanumeric strings";}
	public function GetEditClass() {return "String";}
	protected function GetSQLCol()
	{
		$oValDef = $this->GetValuesDef();
		if ($oValDef)
		{
			$aValues = CMDBSource::Quote($oValDef->GetValues(array(), ""), true);
		}
		else
		{
			$aValues = array();
		}
		if (count($aValues) > 0)
		{
			return "ENUM(".implode(", ", $aValues).")";
		}
		else
		{
			return "VARCHAR(255)"; // ENUM() is not an allowed syntax!
		}
	}

	public function GetBasicFilterOperators()
	{
		return parent::GetBasicFilterOperators();
	}
	public function GetBasicFilterLooseOperator()
	{
		return parent::GetBasicFilterLooseOperator();
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return parent::GetBasicFilterSQLExpr($sOpCode, $value);
	} 
}

/**
 * Map a date+time column to an attribute 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeDate extends AttributeDBField
{
	const MYDATEFORMAT = "Y-m-d H:i:s";
	//const MYDATETIMEZONE = "UTC";
	const MYDATETIMEZONE = "Europe/Paris";
	static protected $const_TIMEZONE = null; // set once for all upon object construct 

	static public function InitStatics()
	{
		// Init static constant once for all (remove when PHP allows real static const)
		self::$const_TIMEZONE = new DateTimeZone(self::MYDATETIMEZONE);

		// #@# Init default timezone -> do not get a notice... to be improved !!!
		date_default_timezone_set(self::MYDATETIMEZONE);
	}

	static protected function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetType() {return "Date";}
	public function GetTypeDesc() {return "Date and time";}
	public function GetEditClass() {return "Date";}
	protected function GetSQLCol() {return "TIMESTAMP";}

	// #@# THIS HAS TO REVISED
	// Having null not allowed was interpreted by mySQL
	// which was creating the field with the flag 'ON UPDATE CURRENT_TIMESTAMP'
	// Then, on each update of the record, the field was modified.
	// We will have to specify the default value if we want to restore this option
	// In fact, we could also have more verbs dedicated to the DB:
	// GetDBDefaultValue()... or GetDBFieldCreationStatement()....
	public function IsNullAllowed() {return true;}
	public function GetDefaultValue()
	{
		$default = parent::GetDefaultValue();

		if (!parent::IsNullAllowed())
		{
			if (empty($default))
			{
				$default = date("Y-m-d H:i");
			}
		}

		return $default;
	}
	// END OF THE WORKAROUND
	///////////////////////////////////////////////////////////////

	public function GetBasicFilterOperators()
	{
		return array(
			"="=>"equals",
			"!="=>"differs from",
			"<"=>"before",
			"<="=>"before",
			">"=>"after (strictly)",
			">="=>"after",
			"SameDay"=>"same day (strip time)",
			"SameMonth"=>"same year/month",
			"SameYear"=>"same year",
			"Today"=>"today",
			">|"=>"after today + N days",
			"<|"=>"before today + N days",
			"=|"=>"equals today + N days",
		);
	}
	public function GetBasicFilterLooseOperator()
	{
		// Unless we implement a "same xxx, depending on given precision" !
		return "=";
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		$sQValue = CMDBSource::Quote($value);

		switch ($sOpCode)
		{
		case '=':
		case '!=':
		case '<':
		case '<=':
		case '>':
		case '>=':
			return $this->GetSQLExpr()." $sOpCode $sQValue";
		case 'SameDay':
			return "DATE(".$this->GetSQLExpr().") = DATE($sQValue)";
		case 'SameMonth':
			return "DATE_FORMAT(".$this->GetSQLExpr().", '%Y-%m') = DATE_FORMAT($sQValue, '%Y-%m')";
		case 'SameYear':
			return "MONTH(".$this->GetSQLExpr().") = MONTH($sQValue)";
		case 'Today':
			return "DATE(".$this->GetSQLExpr().") = CURRENT_DATE()";
		case '>|':
			return "DATE(".$this->GetSQLExpr().") > DATE_ADD(CURRENT_DATE(), INTERVAL $sQValue DAY)";
		case '<|':
			return "DATE(".$this->GetSQLExpr().") < DATE_ADD(CURRENT_DATE(), INTERVAL $sQValue DAY)";
		case '=|':
			return "DATE(".$this->GetSQLExpr().") = DATE_ADD(CURRENT_DATE(), INTERVAL $sQValue DAY)";
		default:
			return $this->GetSQLExpr()." = $sQValue";
		}
	}
	
	public function MakeRealValue($proposedValue)
	{
		if (!is_numeric($proposedValue))
		{
			return $proposedValue;
		}
		else
		{
			return date("Y-m-d H:i:s", $proposedValue);
		}
		throw new CoreException("Invalid type for a date (found ".gettype($proposedValue)." and accepting string/int/DateTime)");
		return null;
	}
	public function ScalarToSQL($value)
	{
		if (empty($value))
		{
			// Make a valid date for MySQL. TO DO: support NULL as a literal value for fields that can be null.
			return '0000-00-00 00:00:00';
		}
		return $value;
	}
	public function SQLToScalar($value)
	{
		return $value;
	}

	public function GetAsHTML($value)
	{
		return Str::pure2html($value);
	}

	public function GetAsXML($value)
	{
		return Str::pure2xml($value);
	}

	public function GetAsCSV($value, $sSeparator = ';', $sSepEscape = ',')
	{
		return str_replace($sSeparator, $sSepEscape, $value);
	}
}

// Init static constant once for all (remove when PHP allows real static const)
AttributeDate::InitStatics();


/**
 * Map a foreign key to an attribute 
 *  AttributeExternalKey and AttributeExternalField may be an external key
 *  the difference is that AttributeExternalKey corresponds to a column into the defined table
 *  where an AttributeExternalField corresponds to a column into another table (class)
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeExternalKey extends AttributeDBFieldVoid
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("targetclass", "is_null_allowed", "on_target_delete"));
	}

	public function GetType() {return "Extkey";}
	public function GetTypeDesc() {return "Link to another object";}
	public function GetEditClass() {return "ExtKey";}
	protected function GetSQLCol() {return "INT";}

	public function IsExternalKey($iType = EXTKEY_RELATIVE) {return true;}
	public function GetTargetClass($iType = EXTKEY_RELATIVE) {return $this->Get("targetclass");}
	public function GetKeyAttDef($iType = EXTKEY_RELATIVE){return $this;}
	public function GetKeyAttCode() {return $this->GetCode();} 
	

	public function GetDefaultValue() {return 0;}
	public function IsNullAllowed() {return $this->Get("is_null_allowed");}

	public function GetBasicFilterOperators()
	{
		return parent::GetBasicFilterOperators();
	}
	public function GetBasicFilterLooseOperator()
	{
		return parent::GetBasicFilterLooseOperator();
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return parent::GetBasicFilterSQLExpr($sOpCode, $value);
	} 

	// overloaded here so that an ext key always have the answer to
	// "what are your possible values?"
	public function GetValuesDef()
	{
		$oValSetDef = $this->Get("allowed_values");
		if (!$oValSetDef)
		{
			// Let's propose every existing value
			$oValSetDef = new ValueSetObjects($this->GetTargetClass());
		}
		return $oValSetDef;
	}

	public function GetAllowedValues($aArgs = array(), $sBeginsWith = '')
	{
		try
		{
			return parent::GetAllowedValues($aArgs, $sBeginsWith);
		}
		catch (MissingQueryArgument $e)
		{
			// Some required arguments could not be found, enlarge to any existing value
			$oValSetDef = new ValueSetObjects($this->GetTargetClass());
			return $oValSetDef->GetValues($aArgs, $sBeginsWith);
		}
	}

	public function GetDeletionPropagationOption()
	{
		return $this->Get("on_target_delete");
	}
}

/**
 * An attribute which corresponds to an external key (direct or indirect) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeExternalField extends AttributeDefinition
{

	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("extkey_attcode", "target_attcode"));
	}

	public function GetType() {return "ExtkeyField";}
	public function GetTypeDesc() {return "Field of an object pointed to by the current object";}
	public function GetEditClass() {return "ExtField";}
	protected function GetSQLCol()
	{
		// throw new CoreException("external attribute: does it make any sense to request its type ?");  
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetSQLCol(); 
	}

	public function IsExternalKey($iType = EXTKEY_RELATIVE)
	{
		switch($iType)
		{
		case EXTKEY_ABSOLUTE:
			// see further
			$oRemoteAtt = $this->GetExtAttDef();
			return $oRemoteAtt->IsExternalKey($iType);

		case EXTKEY_RELATIVE:
			return false;

		default:
			throw new CoreException("Unexpected value for argument iType: '$iType'");
		}
	}

	public function GetTargetClass($iType = EXTKEY_RELATIVE)
	{
		return $this->GetKeyAttDef($iType)->GetTargetClass();
	}

	public function IsExternalField() {return true;} 
	public function GetKeyAttCode() {return $this->Get("extkey_attcode");} 
	public function GetExtAttCode() {return $this->Get("target_attcode");} 

	public function GetKeyAttDef($iType = EXTKEY_RELATIVE)
	{
		switch($iType)
		{
		case EXTKEY_ABSOLUTE:
			// see further
			$oRemoteAtt = $this->GetExtAttDef();
			if ($oRemoteAtt->IsExternalField())
			{
				return $oRemoteAtt->GetKeyAttDef(EXTKEY_ABSOLUTE);
			}
			else if ($oRemoteAtt->IsExternalKey())
			{
				return $oRemoteAtt;
			}
			return $this->GetKeyAttDef(EXTKEY_RELATIVE); // which corresponds to the code hereafter !

		case EXTKEY_RELATIVE:
			return MetaModel::GetAttributeDef($this->GetHostClass(), $this->Get("extkey_attcode"));

		default:
			throw new CoreException("Unexpected value for argument iType: '$iType'");
		}
	}

	public function GetExtAttDef()
	{
		$oKeyAttDef = $this->GetKeyAttDef();
		$oExtAttDef = MetaModel::GetAttributeDef($oKeyAttDef->Get("targetclass"), $this->Get("target_attcode"));
		return $oExtAttDef;
	}

	public function GetSQLExpr()
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetSQLExpr(); 
	} 

	public function GetDefaultValue()
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetDefaultValue(); 
	}
	public function IsNullAllowed()
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->IsNullAllowed(); 
	}

	public function GetBasicFilterOperators()
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetBasicFilterOperators(); 
	}
	public function GetBasicFilterLooseOperator()
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetBasicFilterLooseOperator(); 
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetBasicFilterSQLExpr($sOpCode, $value);
	} 

	public function MakeRealValue($proposedValue)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->MakeRealValue($proposedValue);
	}
	public function ScalarToSQL($value)
	{
		// This one could be used in case of filtering only
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->ScalarToSQL($value);
	}
	public function SQLToScalar($value)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->SQLToScalar($value);
	}


	// Do not overload GetSQLExpression here because this is handled in the joins
	//public function GetSQLExpressions() {return array();}

	// Here, we get the data...
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->FromSQLToValue($aCols, $sPrefix);
	}

	public function GetAsHTML($value)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetAsHTML($value);
	}
	public function GetAsXML($value)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetAsXML($value);
	}
	public function GetAsCSV($value, $sSeparator = ';', $sSepEscape = ',')
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetAsCSV($value);
	}
}

/**
 * Map a varchar column to an URL (formats the ouput in HMTL) 
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeURL extends AttributeString
{
	static protected function ListExpectedParams()
	{
		//return parent::ListExpectedParams();
		return array_merge(parent::ListExpectedParams(), array("target", "label"));
	}

	public function GetType() {return "Url";}
	public function GetTypeDesc() {return "Absolute or relative URL as a text string";}
	public function GetEditClass() {return "String";}

	public function GetAsHTML($sValue)
	{
		$sTarget = $this->Get("target");
		if (empty($sTarget)) $sTarget = "_blank";
		$sLabel = Str::pure2html($sValue);
		if (strlen($sLabel) > 40)
		{
			// Truncate the length to about 40 characters, by removing the middle
			$sLabel = substr($sLabel, 0, 25).'...'.substr($sLabel, -15);
		}
		return "<a target=\"$sTarget\" href=\"$sValue\">$sLabel</a>";
	}
}

/**
 * Data column, consisting in TWO columns in the DB  
 *
 * @package     iTopORM
 * @author      Romain Quetiez <romainquetiez@yahoo.fr>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.itop.com
 * @since       1.0
 * @version     $itopversion$
 */
class AttributeBlob extends AttributeDefinition
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("depends_on"));
	}

	public function GetType() {return "Blob";}
	public function GetTypeDesc() {return "Document";}
	public function GetEditClass() {return "Document";}
	
	public function IsDirectField() {return true;} 
	public function IsScalar() {return true;} 
	public function IsWritable() {return true;} 
	public function GetDefaultValue() {return "";}
	public function IsNullAllowed() {return false;}

	// Facilitate things: allow the user to Set the value from a string
	public function MakeRealValue($proposedValue)
	{
		if (!is_object($proposedValue))
		{
			return new ormDocument($proposedValue, 'text/plain');
		}
		return $proposedValue;
	}

	public function GetSQLExpressions()
	{
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $this->GetCode().'_mimetype';
		$aColumns['_data'] = $this->GetCode().'_data';
		$aColumns['_filename'] = $this->GetCode().'_filename';
		return $aColumns;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		if (!isset($aCols[$sPrefix]))
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		} 
		$sMimeType = $aCols[$sPrefix];

		if (!isset($aCols[$sPrefix.'_data'])) 
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_data' from {$sAvailable}");
		} 
		$data = $aCols[$sPrefix.'_data'];

		if (!isset($aCols[$sPrefix.'_filename'])) 
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_filename' from {$sAvailable}");
		} 
		$sFileName = $aCols[$sPrefix.'_filename'];

		$value = new ormDocument($data, $sMimeType, $sFileName);
		return $value;
	}

	public function GetSQLValues($value)
	{
		// #@# Optimization: do not load blobs anytime
		//     As per mySQL doc, selecting blob columns will prevent mySQL from
		//     using memory in case a temporary table has to be created
		//     (temporary tables created on disk)
		//     We will have to remove the blobs from the list of attributes when doing the select
		//     then the use of Get() should finalize the load
		$aValues = array();
		$aValues[$this->GetCode().'_data'] = $value->GetData();
		$aValues[$this->GetCode().'_mimetype'] = $value->GetMimeType();
		$aValues[$this->GetCode().'_filename'] = $value->GetFileName();
		return $aValues;
	}

	public function GetSQLColumns()
	{
		$aColumns = array();
		$aColumns[$this->GetCode().'_data'] = 'LONGBLOB'; // 2^32 (4 Gb)
		$aColumns[$this->GetCode().'_mimetype'] = 'VARCHAR(255)';
		$aColumns[$this->GetCode().'_filename'] = 'VARCHAR(255)';
		return $aColumns;
	}

	public function GetBasicFilterOperators()
	{
		return array();
	}
	public function GetBasicFilterLooseOperator()
	{
		return '=';
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return 'true';
	} 

	public function GetAsHTML($value)
	{
		return $value->GetAsHTML();
	}
}


?>
