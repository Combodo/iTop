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
 * Typology for the attributes
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


require_once('MyHelpers.class.inc.php');
require_once('ormdocument.class.inc.php');
require_once('ormpassword.class.inc.php');

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
 */
abstract class AttributeDefinition
{
	abstract public function GetType();
	abstract public function GetTypeDesc();
	abstract public function GetEditClass();

	protected $m_sCode;
	private $m_aParams = array();
	protected $m_sHostClass = '!undefined!';
	protected function Get($sParamName) {return $this->m_aParams[$sParamName];}
	protected function IsParam($sParamName) {return (array_key_exists($sParamName, $this->m_aParams));}

	protected function GetOptional($sParamName, $default)
	{
		if (array_key_exists($sParamName, $this->m_aParams))
		{
			return $this->m_aParams[$sParamName];
		}
		else
		{
			return $default;
		}
	}
	
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
				throw new CoreException("Unknown attribute definition parameter '$sParam', please select a value in {".implode(", ", array_keys($this->m_aParams))."}");
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
	public function GetLabel() {return Dict::S('Class:'.$this->m_sHostClass.'/Attribute:'.$this->m_sCode, $this->m_sCode);}
	public function GetLabel_Obsolete()
	{
		// Written for compatibility with a data model written prior to version 0.9.1
		if (array_key_exists('label', $this->m_aParams))
		{
			return $this->m_aParams['label'];
		}
		else
		{
			return $this->GetLabel();
		}
	}
	public function GetDescription() {return Dict::S('Class:'.$this->m_sHostClass.'/Attribute:'.$this->m_sCode.'+', '');} 
	public function GetHelpOnEdition() {return Dict::S('Class:'.$this->m_sHostClass.'/Attribute:'.$this->m_sCode.'?', '');} 
	public function GetDescription_Obsolete()
	{
		// Written for compatibility with a data model written prior to version 0.9.1
		if (array_key_exists('description', $this->m_aParams))
		{
			return $this->m_aParams['description'];
		}
		else
		{
			return $this->GetDescription();
		}
	}
	public function GetValuesDef() {return null;} 
	public function GetPrerequisiteAttributes() {return array();} 

	public function GetNullValue() {return null;} 
	public function IsNull($proposedValue) {return is_null($proposedValue);} 

	public function MakeRealValue($proposedValue) {return $proposedValue;} // force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!)

	public function GetSQLExpressions() {return array();} // returns suffix/expression pairs (1 in most of the cases), for READING (Select)
	public function FromSQLToValue($aCols, $sPrefix = '') {return null;} // returns a value out of suffix/value pairs, for SELECT result interpretation
	public function GetSQLColumns() {return array();} // returns column/spec pairs (1 in most of the cases), for STRUCTURING (DB creation)
	public function GetSQLValues($value) {return array();} // returns column/value pairs (1 in most of the cases), for WRITING (Insert, Update)
	public function RequiresIndex() {return false;}

	public function GetValidationPattern()
	{
		return '';
	}
	
	public function CheckFormat($value)
	{
		return true;
	}
	 
	public function GetMaxSize()
	{
		return null;
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

	public function GetFilterDefinitions()
	{
		return array();
	}

	public function GetEditValue($sValue)
	{
		return (string)$sValue;
	}

	public function GetAsHTML($sValue)
	{
		return Str::pure2html((string)$sValue);
	}

	public function GetAsXML($sValue)
	{
		return Str::pure2xml((string)$sValue);
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"')
	{
		return (string)$sValue;
	}

	public function GetAllowedValues($aArgs = array(), $sBeginsWith = '')
	{
		$oValSetDef = $this->GetValuesDef();
		if (!$oValSetDef) return null;
		return $oValSetDef->GetValues($aArgs, $sBeginsWith);
	}
	
	/**
	 * Parses a string to find some smart search patterns and build the corresponding search/OQL condition
	 * Each derived class is reponsible for defining and processing their own smart patterns, the base class
	 * does nothing special, and just calls the default (loose) operator
	 * @param string $sSearchText The search string to analyze for smart patterns
	 * @param FieldExpression The FieldExpression representing the atttribute code in this OQL query
	 * @param Hash $aParams Values of the query parameters
	 * @return Expression The search condition to be added (AND) to the current search
	 */
	public function GetSmartConditionExpression($sSearchText, FieldExpression $oField, &$aParams)
	{
		$sParamName = $oField->GetParent().'_'.$oField->GetName();
		$oRightExpr = new VariableExpression($sParamName);
		$sOperator = $this->GetBasicFilterLooseOperator();
		switch ($sOperator)
		{
			case 'Contains':
			$aParams[$sParamName] = "%$sSearchText%";
			$sSQLOperator = 'LIKE';
			break;
			
			default:
			$sSQLOperator = $sOperator;
			$aParams[$sParamName] = $sSearchText;
		}
		$oNewCondition = new BinaryExpression($oField, $sSQLOperator, $oRightExpr);
		return $oNewCondition;
	}
}

/**
 * Set of objects directly linked to an object, and being part of its definition  
 *
 * @package     iTopORM
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
	public function IsIndirect() {return false;} 

	public function GetValuesDef() {return $this->Get("allowed_values");} 
	public function GetPrerequisiteAttributes() {return $this->Get("depends_on");} 
	public function GetDefaultValue($aArgs = array())
	{
		// Note: so far, this feature is a prototype,
		//       later, the argument 'this' should always be present in the arguments
		//       
		if (($this->IsParam('default_value')) && array_key_exists('this', $aArgs))
		{
			$aValues = $this->Get('default_value')->GetValues($aArgs);
			$oSet = DBObjectSet::FromArray($this->Get('linked_class'), $aValues);
			return $oSet;
		}
		else
		{
			return DBObjectSet::FromScratch($this->Get('linked_class'));
		}
	}

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

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"')
	{
		return "ERROR: LIST OF OBJECTS";
	}
}

/**
 * Set of objects linked to an object (n-n), and being part of its definition  
 *
 * @package     iTopORM
 */
class AttributeLinkedSetIndirect extends AttributeLinkedSet
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("ext_key_to_remote"));
	}
	public function IsIndirect() {return true;} 
	public function GetExtKeyToRemote() { return $this->Get('ext_key_to_remote'); }
	public function GetEditClass() {return "LinkedSet";}
}

/**
 * Abstract class implementing default filters for a DB column  
 *
 * @package     iTopORM
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
	public function GetDefaultValue() {return $this->MakeRealValue("");}
	public function IsNullAllowed() {return false;}

	// 
	protected function ScalarToSQL($value) {return $value;} // format value as a valuable SQL literal (quoted outside)

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

	public function GetFilterDefinitions()
	{
		return array($this->GetCode() => new FilterFromAttribute($this));
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
 */
class AttributeDBField extends AttributeDBFieldVoid
{	
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("default_value", "is_null_allowed"));
	}
	public function GetDefaultValue() {return $this->MakeRealValue($this->Get("default_value"));}
	public function IsNullAllowed() {return $this->Get("is_null_allowed");}
}

/**
 * Map an integer column to an attribute 
 *
 * @package     iTopORM
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
	protected function GetSQLCol() {return "INT(11)";}
	
	public function GetValidationPattern()
	{
		return "^[0-9]+$";
	}

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

	public function GetNullValue()
	{
		return null;
	} 
	public function IsNull($proposedValue)
	{
		return is_null($proposedValue);
	} 

	public function MakeRealValue($proposedValue)
	{
		if (is_null($proposedValue)) return null;
		if ($proposedValue == '') return null;
		return (int)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		assert(is_numeric($value) || is_null($value));
		return $value; // supposed to be an int
	}
}

/**
 * Map a boolean column to an attribute 
 *
 * @package     iTopORM
 */
class AttributeBoolean extends AttributeInteger
{
	static protected function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetType() {return "Boolean";}
	public function GetTypeDesc() {return "Boolean";}
	public function GetEditClass() {return "Integer";}
	protected function GetSQLCol() {return "TINYINT(1)";}
	
	public function MakeRealValue($proposedValue)
	{
		if (is_null($proposedValue)) return null;
		if ($proposedValue == '') return null;
		if ((int)$proposedValue) return true;
		return false;
	}

	public function ScalarToSQL($value)
	{
		if ($value) return 1;
		return 0;
	}
}

/**
 * Map a varchar column (size < ?) to an attribute 
 *
 * @package     iTopORM
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

	public function CheckFormat($value)
	{
		$sRegExp = $this->GetValidationPattern();
		if (empty($sRegExp))
		{
			return true;
		}
		else
		{
			$sRegExp = str_replace('/', '\\/', $sRegExp);
			return preg_match("/$sRegExp/", $value);
		}
	}

	public function GetMaxSize()
	{
		return 255;
	}

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

	public function GetNullValue()
	{
		return '';
	} 

	public function IsNull($proposedValue)
	{
		return ($proposedValue == '');
	} 

	public function MakeRealValue($proposedValue)
	{
		if (is_null($proposedValue)) return '';
		return (string)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		if (!is_string($value) && !is_null($value))
		{
			throw new CoreWarning('Expected the attribute value to be a string', array('found_type' => gettype($value), 'value' => $value, 'class' => $this->GetCode(), 'attribute' => $this->GetHostClass()));
		}
		return $value;
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"')
	{
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);
		return '"'.$sEscaped.'"';
	}
}

/**
 * An attibute that matches an object class 
 *
 * @package     iTopORM
 */
class AttributeClass extends AttributeString
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("class_category", "more_values"));
	}

	public function __construct($sCode, $aParams)
	{
		$this->m_sCode = $sCode;
		$aParams["allowed_values"] = new ValueSetEnumClasses($aParams['class_category'], $aParams['more_values']);
		parent::__construct($sCode, $aParams);
	}

	public function GetDefaultValue()
	{
		$sDefault = parent::GetDefaultValue();
		if (!$this->IsNullAllowed() && $this->IsNull($sDefault))
		{
			// For this kind of attribute specifying null as default value
			// is authorized even if null is not allowed
			
			// Pick the first one...
			$aClasses = $this->GetAllowedValues();
			$sDefault = key($aClasses);
		}
		return $sDefault;
	}

	public function GetAsHTML($sValue)
	{
		if (empty($sValue)) return '';
		return MetaModel::GetName($sValue);
	}

	public function RequiresIndex()
	{
		return true;
	}
}

/**
 * An attibute that matches one of the language codes availables in the dictionnary 
 *
 * @package     iTopORM
 */
class AttributeApplicationLanguage extends AttributeString
{
	static protected function ListExpectedParams()
	{
		return parent::ListExpectedParams();
	}

	public function __construct($sCode, $aParams)
	{
		$this->m_sCode = $sCode;
		$aAvailableLanguages = Dict::GetLanguages();
		$aLanguageCodes = array();
		foreach($aAvailableLanguages as $sLangCode => $aInfo)
		{
			$aLanguageCodes[$sLangCode] = $aInfo['description'].' ('.$aInfo['localized_description'].')';
		}
		$aParams["allowed_values"] = new ValueSetEnum($aLanguageCodes);
		parent::__construct($sCode, $aParams);
	}

	public function RequiresIndex()
	{
		return true;
	}
}

/**
 * The attribute dedicated to the finalclass automatic attribute 
 *
 * @package     iTopORM
 */
class AttributeFinalClass extends AttributeString
{
	public function __construct($sCode, $aParams)
	{
		$this->m_sCode = $sCode;
		$aParams["allowed_values"] = null;
		parent::__construct($sCode, $aParams);

		$this->m_sValue = $this->Get("default_value");
	}

	public function IsWritable()
	{
		return false;
	}

	public function RequiresIndex()
	{
		return true;
	}

	public function SetFixedValue($sValue)
	{
		$this->m_sValue = $sValue;
	}
	public function GetDefaultValue()
	{
		return $this->m_sValue;
	}

	public function GetAsHTML($sValue)
	{
		if (empty($sValue)) return '';
		return MetaModel::GetName($sValue);
	}
}

/**
 * Map a varchar column (size < ?) to an attribute that must never be shown to the user 
 *
 * @package     iTopORM
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

	public function GetMaxSize()
	{
		return 64;
	}

	public function GetFilterDefinitions()
	{
	// Note: due to this, you will get an error if a password is being declared as a search criteria (see ZLists)
		// not allowed to search on passwords!
		return array();
	}

	public function GetAsHTML($sValue)
	{
		if (strlen($sValue) == 0)
		{
			return '';
		}
		else
		{
			return '******';
		}
	}
}

/**
 * Map a text column (size < 255) to an attribute that is encrypted in the database
 * The encryption is based on a key set per iTop instance. Thus if you export your
 * database (in SQL) to someone else without providing the key at the same time
 * the encrypted fields will remain encrypted
 *
 * @package     iTopORM
 */
class AttributeEncryptedString extends AttributeString
{
	static $sKey = null; // Encryption key used for all encrypted fields

	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
		if (self::$sKey == null)
		{
			self::$sKey = MetaModel::GetConfig()->GetEncryptionKey();
		}
	}

	protected function GetSQLCol() {return "TINYBLOB";}	

	public function GetMaxSize()
	{
		return 255;
	}

	public function GetFilterDefinitions()
	{
		// Note: due to this, you will get an error if a an encrypted field is declared as a search criteria (see ZLists)
		// not allowed to search on encrypted fields !
		return array();
	}

	public function MakeRealValue($proposedValue)
	{
		if (is_null($proposedValue)) return null;
		return (string)$proposedValue;
	}

	/**
	 * Decrypt the value when reading from the database
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
 		$oSimpleCrypt = new SimpleCrypt();
 		$sValue = $oSimpleCrypt->Decrypt(self::$sKey, $aCols[$sPrefix]);
		return $sValue;
	}

	/**
	 * Encrypt the value before storing it in the database
	 */
	public function GetSQLValues($value)
	{
 		$oSimpleCrypt = new SimpleCrypt();
 		$encryptedValue = $oSimpleCrypt->Encrypt(self::$sKey, $value);

		$aValues = array();
		$aValues[$this->Get("sql")] = $encryptedValue;
		return $aValues;
	}
}

/**
 * Map a text column (size > ?) to an attribute 
 *
 * @package     iTopORM
 */
class AttributeText extends AttributeString
{
	public function GetType() {return "Text";}
	public function GetTypeDesc() {return "Multiline character string";}
	public function GetEditClass() {return "Text";}
	protected function GetSQLCol() {return "TEXT";}

	public function GetMaxSize()
	{
		// Is there a way to know the current limitation for mysql?
		// See mysql_field_len()
		return 65535;
	}

	public function GetAsHTML($sValue)
	{
		return str_replace("\n", "<br>\n", parent::GetAsHTML($sValue));
	}

	public function GetAsXML($value)
	{
		return Str::pure2xml($value);
	}
}

/**
 * Specialization of a string: email 
 *
 * @package     iTopORM
 */
class AttributeEmailAddress extends AttributeString
{
	public function GetTypeDesc() {return "Email address(es)";}

	public function GetValidationPattern()
	{
		return "^([0-9a-zA-Z]([-.\\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\\w]*[0-9a-zA-Z]\\.)+[a-zA-Z]{2,9})$";
	}
}

/**
 * Specialization of a string: IP address 
 *
 * @package     iTopORM
 */
class AttributeIPAddress extends AttributeString
{
	public function GetTypeDesc() {return "IP address";}

	public function GetValidationPattern()
	{
		$sNum = '(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])';
		return "^($sNum\\.$sNum\\.$sNum\\.$sNum)$";
	}
}

/**
 * Specialization of a string: OQL expression 
 *
 * @package     iTopORM
 */
class AttributeOQL extends AttributeText
{
	public function GetTypeDesc() {return "OQL expression";}
}

/**
 * Specialization of a string: template 
 *
 * @package     iTopORM
 */
class AttributeTemplateString extends AttributeString
{
	public function GetTypeDesc() {return "Template string";}
}

/**
 * Specialization of a text: template 
 *
 * @package     iTopORM
 */
class AttributeTemplateText extends AttributeText
{
	public function GetTypeDesc() {return "Multiline template string";}
}


/**
 * Specialization of a text: wiki formatting 
 *
 * @package     iTopORM
 */
class AttributeWikiText extends AttributeText
{
	public function GetTypeDesc() {return "Multiline string with special formatting such as links to objects";}

	public function GetAsHTML($value)
	{
		// [SELECT xxxx.... [label]] => hyperlink to a result list
		// {SELECT xxxx.... [label]} => result list displayed inline
		// [myclass/nnn [label]] => hyperlink to an object
		// {myclass/nnn/attcode} => attribute displayed inline
		// etc.
		return parent::GetAsHTML($value);
	}
}

/**
 * Map a enum column to an attribute 
 *
 * @package     iTopORM
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
			$aValues = CMDBSource::Quote(array_keys($oValDef->GetValues(array(), "")), true);
		}
		else
		{
			$aValues = array();
		}
		if (count($aValues) > 0)
		{
			// The syntax used here do matters
			// In particular, I had to remove unnecessary spaces to
			// make sure that this string will match the field type returned by the DB
			// (used to perform a comparison between the current DB format and the data model)
			return "ENUM(".implode(",", $aValues).")";
		}
		else
		{
			return "VARCHAR(255)"; // ENUM() is not an allowed syntax!
		}
	}

	public function ScalarToSQL($value)
	{
		// Note: for strings, the null value is an empty string and it is recorded as such in the DB
		//       but that wasn't working for enums, because '' is NOT one of the allowed values
		//       that's why a null value must be forced to a real null
		$value = parent::ScalarToSQL($value);
		if ($this->IsNull($value))
		{
			return null;
		}
		else
		{
			return $value;
		}
	}

	public function RequiresIndex()
	{
		return false;
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

	public function GetAsHTML($sValue)
	{
		$sLabel = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue, $sValue);
		$sDescription = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+', $sValue);
		// later, we could imagine a detailed description in the title
		return "<span title=\"$sDescription\">".parent::GetAsHtml($sLabel)."</span>";
	}

	public function GetEditValue($sValue)
	{
		$sLabel = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue, $sValue);
		return $sLabel;
	}

	public function GetAllowedValues($aArgs = array(), $sBeginsWith = '')
	{
		$aRawValues = parent::GetAllowedValues($aArgs, $sBeginsWith);
		if (is_null($aRawValues)) return null;
		$aLocalizedValues = array();
		foreach ($aRawValues as $sKey => $sValue)
		{
			$aLocalizedValues[$sKey] = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sKey, $sKey);
		}
  		return $aLocalizedValues;
  }
}

/**
 * Map a date+time column to an attribute 
 *
 * @package     iTopORM
 */
class AttributeDateTime extends AttributeDBField
{
	//const MYDATETIMEZONE = "UTC";
	const MYDATETIMEZONE = "Europe/Paris";
	static protected $const_TIMEZONE = null; // set once for all upon object construct 

	static public function InitStatics()
	{
		// Init static constant once for all (remove when PHP allows real static const)
		self::$const_TIMEZONE = new DateTimeZone(self::MYDATETIMEZONE);

		// #@# Init default timezone -> do not get a notice... to be improved !!!
		// duplicated in the email test page (the mail function does trigger a notice as well)
		date_default_timezone_set(self::MYDATETIMEZONE);
	}

	static protected function GetDateFormat()
	{
		return "Y-m-d H:i:s";
	}

	static protected function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetType() {return "Date";}
	public function GetTypeDesc() {return "Date and time";}
	public function GetEditClass() {return "DateTime";}
	protected function GetSQLCol() {return "TIMESTAMP";}
	public static function GetAsUnixSeconds($value)
	{
		$oDeadlineDateTime = new DateTime($value, self::$const_TIMEZONE);
		$iUnixSeconds = $oDeadlineDateTime->format('U');
		return $iUnixSeconds;
		
	}

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
				$default = date(self::GetDateFormat());
			}
		}

		return $default;
	}
	// END OF THE WORKAROUND
	///////////////////////////////////////////////////////////////

	public function GetValidationPattern()
	{
		return "^([0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30))))( (0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])){0,1}|0000-00-00 00:00:00|0000-00-00$";
	}

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
		if (is_null($proposedValue))
		{
			return null;
		}
		if (is_string($proposedValue) && ($proposedValue == "") && $this->IsNullAllowed())
		{
			return null;
		}
		if (!is_numeric($proposedValue))
		{
			return $proposedValue;
		}

		return date(self::GetDateFormat(), $proposedValue);
	}

	public function ScalarToSQL($value)
	{
		if (is_null($value))
		{	
			return null;
		}
		elseif (empty($value))
		{
			// Make a valid date for MySQL. TO DO: support NULL as a literal value for fields that can be null.
			return '0000-00-00 00:00:00';
		}
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

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"')
	{
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);
		return '"'.$sEscaped.'"';
	}
	
	/**
	 * Parses a string to find some smart search patterns and build the corresponding search/OQL condition
	 * Each derived class is reponsible for defining and processing their own smart patterns, the base class
	 * does nothing special, and just calls the default (loose) operator
	 * @param string $sSearchText The search string to analyze for smart patterns
	 * @param FieldExpression The FieldExpression representing the atttribute code in this OQL query
	 * @param Hash $aParams Values of the query parameters
	 * @return Expression The search condition to be added (AND) to the current search
	 */
	public function GetSmartConditionExpression($sSearchText, FieldExpression $oField, &$aParams)
	{
		// Possible smart patterns
		$aPatterns = array(
			'between' => array('pattern' => '/^\[(.*),(.*)\]$/', 'operator' => 'n/a'),
			'greater than or equal' => array('pattern' => '/^>=(.*)$/', 'operator' => '>='),
			'greater than' => array('pattern' => '/^>(.*)$/', 'operator' => '>'),
			'less than or equal' => array('pattern' => '/^<=(.*)$/', 'operator' => '<='),
			'less than' =>  array('pattern' => '/^<(.*)$/', 'operator' => '<'),
		);
		
		$sPatternFound = '';
		$aMatches = array();
		foreach($aPatterns as $sPatName => $sPattern)
		{
			if (preg_match($sPattern['pattern'], $sSearchText, $aMatches))
			{
				$sPatternFound = $sPatName;
				break;
			}			
		}
		
		switch($sPatternFound)
		{
			case 'between':
			
			$sParamName1 = $oField->GetParent().'_'.$oField->GetName().'_1';
			$oRightExpr = new VariableExpression($sParamName1);
			$aParams[$sParamName1] = $aMatches[1];
			$oCondition1 = new BinaryExpression($oField, '>=', $oRightExpr);

			$sParamName2 = $oField->GetParent().'_'.$oField->GetName().'_2';
			$oRightExpr = new VariableExpression($sParamName2);
			$sOperator = $this->GetBasicFilterLooseOperator();
			$aParams[$sParamName2] = $aMatches[2];
			$oCondition2 = new BinaryExpression($oField, '<=', $oRightExpr);
			
			$oNewCondition = new BinaryExpression($oCondition1, 'AND', $oCondition2);
			break;
			
			case 'greater than':
			case 'greater than or equal':
			case 'less than':
			case 'less than or equal':
			$sSQLOperator = $aPatterns[$sPatternFound]['operator'];
			$sParamName = $oField->GetParent().'_'.$oField->GetName();
			$oRightExpr = new VariableExpression($sParamName);
			$aParams[$sParamName] = $aMatches[1];
			$oNewCondition = new BinaryExpression($oField, $sSQLOperator, $oRightExpr);
			
			break;
						
			default:
			$oNewCondition = parent::GetSmartConditionExpression($sSearchText, $oField, $aParams);
		}

		return $oNewCondition;
	}
}

/**
 * Map a date+time column to an attribute 
 *
 * @package     iTopORM
 */
class AttributeDate extends AttributeDateTime
{
	const MYDATEFORMAT = "Y-m-d";

	static protected function GetDateFormat()
	{
		return "Y-m-d";
	}

	static public function InitStatics()
	{
		// Nothing to do...
	}

	static protected function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetType() {return "Date";}
	public function GetTypeDesc() {return "Date";}
	public function GetEditClass() {return "Date";}
	protected function GetSQLCol() {return "DATE";}

	public function GetValidationPattern()
	{
		return "^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$";
	}
}

// Init static constant once for all (remove when PHP allows real static const)
AttributeDate::InitStatics();

/**
 * A dead line stored as a date & time
 * The only difference with the DateTime attribute is the display:
 * relative to the current time
 */
class AttributeDeadline extends AttributeDateTime
{
	public function GetAsHTML($value)
	{
		$sResult = '';
		if ($value !== null)
		{
			$value = AttributeDateTime::GetAsUnixSeconds($value);
			$difference = $value - time();
	
			if ($difference >= 0)
			{
				$sResult = self::FormatDuration($difference);
			}
			else
			{
				$sResult = Dict::Format('UI:DeadlineMissedBy_duration', self::FormatDuration(-$difference));
			}
		}
		return $sResult;
	}

	static function FormatDuration($duration)
	{
		$days = floor($duration / 86400);
		$hours = floor(($duration - (86400*$days)) / 3600);
		$minutes = floor(($duration - (86400*$days + 3600*$hours)) / 60);
		$sResult = '';
		
		if ($duration < 60)
		{
			// Less than 1 min
			$sResult =Dict::S('UI:Deadline_LessThan1Min');			
		}
		else if ($duration < 3600)
		{
			// less than 1 hour, display it in minutes
			$sResult =Dict::Format('UI:Deadline_Minutes', $minutes);			
		}
		else if ($duration < 86400)
		{
			// Less that 1 day, display it in hours/minutes	
			$sResult =Dict::Format('UI:Deadline_Hours_Minutes', $hours, $minutes);			
		}
		else
		{
			// Less that 1 day, display it in hours/minutes	
			$sResult =Dict::Format('UI:Deadline_Days_Hours_Minutes', $days, $hours, $minutes);			
		}
		return $sResult;
	}
}
// Init static constant once for all (remove when PHP allows real static const)
AttributeDateTime::InitStatics();


/**
 * Map a foreign key to an attribute 
 *  AttributeExternalKey and AttributeExternalField may be an external key
 *  the difference is that AttributeExternalKey corresponds to a column into the defined table
 *  where an AttributeExternalField corresponds to a column into another table (class)
 *
 * @package     iTopORM
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
	protected function GetSQLCol() {return "INT(11)";}
	public function RequiresIndex()
	{
		return true;
	}

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
			$oValSetDef = new ValueSetObjects('SELECT '.$this->GetTargetClass());
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
			$oValSetDef = new ValueSetObjects('SELECT '.$this->GetTargetClass());
			return $oValSetDef->GetValues($aArgs, $sBeginsWith);
		}
	}

	public function GetDeletionPropagationOption()
	{
		return $this->Get("on_target_delete");
	}

	public function GetNullValue()
	{
		return 0;
	} 

	public function IsNull($proposedValue)
	{
		return ($proposedValue == 0);
	} 

	public function MakeRealValue($proposedValue)
	{
		if (is_null($proposedValue)) return 0;
		if (MetaModel::IsValidObject($proposedValue)) return $proposedValue->GetKey();
		return (int)$proposedValue;
	}
}

/**
 * An attribute which corresponds to an external key (direct or indirect) 
 *
 * @package     iTopORM
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

	public function GetLabel()
	{
		$oRemoteAtt = $this->GetExtAttDef();
		$sDefault = $oRemoteAtt->GetLabel();
		return Dict::S('Class:'.$this->m_sHostClass.'/Attribute:'.$this->m_sCode, $sDefault);
	}
	public function GetDescription()
	{
		$oRemoteAtt = $this->GetExtAttDef();
		$sDefault = $oRemoteAtt->GetDescription();
		return Dict::S('Class:'.$this->m_sHostClass.'/Attribute:'.$this->m_sCode.'+', $sDefault);
	} 
	public function GetHelpOnEdition()
	{
		$oRemoteAtt = $this->GetExtAttDef();
		$sDefault = $oRemoteAtt->GetHelpOnEdition();
		return Dict::S('Class:'.$this->m_sHostClass.'/Attribute:'.$this->m_sCode.'?', $sDefault);
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
		if (!is_object($oExtAttDef)) throw new CoreException("Invalid external field ".$this->GetCode()." in class ".$this->GetHostClass().". The class ".$oKeyAttDef->Get("targetclass")." has no attribute ".$this->Get("target_attcode"));
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

	public function IsScalar()
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->IsScalar(); 
	} 

	public function GetFilterDefinitions()
	{
		return array($this->GetCode() => new FilterFromAttribute($this));
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

	public function GetNullValue()
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetNullValue();
	} 

	public function IsNull($proposedValue)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->IsNull($proposedValue);
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
	public function GetAsCSV($value, $sSeparator = ',', $sTestQualifier = '"')
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetAsCSV($value, $sSeparator, $sTestQualifier);
	}
}

/**
 * Map a varchar column to an URL (formats the ouput in HMTL) 
 *
 * @package     iTopORM
 */
class AttributeURL extends AttributeString
{
	static protected function ListExpectedParams()
	{
		//return parent::ListExpectedParams();
		return array_merge(parent::ListExpectedParams(), array("target"));
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

	public function GetValidationPattern()
	{
		return "^(http|https|ftp)\://[a-zA-Z0-9\-\.]+(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*$";
	}
}

/**
 * A blob is an ormDocument, it is stored as several columns in the database  
 *
 * @package     iTopORM
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
	public function IsNullAllowed() {return $this->GetOptional("is_null_allowed", false);}


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
		if ($value instanceOf ormDocument)
		{
			$aValues = array();
			$aValues[$this->GetCode().'_data'] = $value->GetData();
			$aValues[$this->GetCode().'_mimetype'] = $value->GetMimeType();
			$aValues[$this->GetCode().'_filename'] = $value->GetFileName();
		}
		else
		{
			$aValues = array();
			$aValues[$this->GetCode().'_data'] = '';
			$aValues[$this->GetCode().'_mimetype'] = '';
			$aValues[$this->GetCode().'_filename'] = '';
		}
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

	public function GetFilterDefinitions()
	{
		return array();
		// still not working... see later...
		return array(
			$this->GetCode().'->filename' => new FilterFromAttribute($this, '_filename'),
			$this->GetCode().'_mimetype' => new FilterFromAttribute($this, '_mimetype'),
			$this->GetCode().'_mimetype' => new FilterFromAttribute($this, '_mimetype')
		);
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
		if (is_object($value))
		{
			return $value->GetAsHTML();
		}
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"')
	{
		return ''; // Not exportable in CSV !
	}
	
	public function GetAsXML($value)
	{
		return ''; // Not exportable in XML, or as CDATA + some subtags ??
	}
}
/**
 * One way encrypted (hashed) password
 */
class AttributeOneWayPassword extends AttributeDefinition
{
	static protected function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("depends_on"));
	}

	public function GetType() {return "One Way Password";}
	public function GetTypeDesc() {return "One Way Password";}
	public function GetEditClass() {return "One Way Password";}
	
	public function IsDirectField() {return true;} 
	public function IsScalar() {return true;} 
	public function IsWritable() {return true;} 
	public function GetDefaultValue() {return "";}
	public function IsNullAllowed() {return $this->GetOptional("is_null_allowed", false);}

	// Facilitate things: allow the user to Set the value from a string or from an ormPassword (already encrypted)
	public function MakeRealValue($proposedValue)
	{
		$oPassword = $proposedValue;
		if (!is_object($oPassword))
		{
			$oPassword = new ormPassword('', '');
			$oPassword->SetPassword($proposedValue);
		}
		return $oPassword;
	}

	public function GetSQLExpressions()
	{
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $this->GetCode().'_hash';
		$aColumns['_salt'] = $this->GetCode().'_salt';
		return $aColumns;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		if (!isset($aCols[$sPrefix]))
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		} 
		$hashed = $aCols[$sPrefix];

		if (!isset($aCols[$sPrefix.'_salt'])) 
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_salt' from {$sAvailable}");
		} 
		$sSalt = $aCols[$sPrefix.'_salt'];

		$value = new ormPassword($hashed, $sSalt);
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
		if ($value instanceOf ormPassword)
		{
			$aValues = array();
			$aValues[$this->GetCode().'_hash'] = $value->GetHash();
			$aValues[$this->GetCode().'_salt'] = $value->GetSalt();
		}
		else
		{
			$aValues = array();
			$aValues[$this->GetCode().'_hash'] = '';
			$aValues[$this->GetCode().'_salt'] = '';
		}
		return $aValues;
	}

	public function GetSQLColumns()
	{
		$aColumns = array();
		$aColumns[$this->GetCode().'_hash'] = 'TINYBLOB';
		$aColumns[$this->GetCode().'_salt'] = 'TINYBLOB';
		return $aColumns;
	}

	public function GetFilterDefinitions()
	{
		return array();
		// still not working... see later...
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
		if (is_object($value))
		{
			return $value->GetAsHTML();
		}
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"')
	{
		return ''; // Not exportable in CSV
	}
	
	public function GetAsXML($value)
	{
		return ''; // Not exportable in XML
	}
}

// Indexed array having two dimensions
class AttributeTable extends AttributeText
{
	public function GetType() {return "Table";}
	public function GetTypeDesc() {return "Array with 2 dimensions";}
	public function GetEditClass() {return "Text";}
	protected function GetSQLCol() {return "TEXT";}

	public function GetMaxSize()
	{
		return null;
	}

	// Facilitate things: allow the user to Set the value from a string
	public function MakeRealValue($proposedValue)
	{
		if (!is_array($proposedValue))
		{
			return array(0 => array(0 => $proposedValue));
		}
		return $proposedValue;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		try
		{
			$value = @unserialize($aCols[$sPrefix.'']);
			if ($value === false)
			{
				$value = $this->MakeRealValue($aCols[$sPrefix.'']);
			}
		}
		catch(Exception $e)
		{
			$value = $this->MakeRealValue($aCols[$sPrefix.'']);
		}

		return $value;
	}

	public function GetSQLValues($value)
	{
		$aValues = array();
		$aValues[$this->Get("sql")] = serialize($value);
		return $aValues;
	}

	public function GetAsHTML($value)
	{
		if (!is_array($value))
		{
			throw new CoreException('Expecting an array', array('found' => get_class($value)));
		}
		if (count($value) == 0)
		{
			return "";
		}

		$sRes = "<TABLE class=\"listResults\">";
		$sRes .= "<TBODY>";
		foreach($value as $iRow => $aRawData)
		{
			$sRes .= "<TR>";
			foreach ($aRawData as $iCol => $cell)
			{
				$sCell = str_replace("\n", "<br>\n", Str::pure2html((string)$cell));
				$sRes .= "<TD>$sCell</TD>";
			}
			$sRes .= "</TR>";
		}
		$sRes .= "</TBODY>";
		$sRes .= "</TABLE>";
		return $sRes;
	}
}

// The PHP value is a hash array, it is stored as a TEXT column
class AttributePropertySet extends AttributeTable
{
	public function GetType() {return "PropertySet";}
	public function GetTypeDesc() {return "List of properties (name and value)";}
	public function GetEditClass() {return "Text";}
	protected function GetSQLCol() {return "TEXT";}

	// Facilitate things: allow the user to Set the value from a string
	public function MakeRealValue($proposedValue)
	{
		if (!is_array($proposedValue))
		{
			return array('?' => (string)$proposedValue);
		}
		return $proposedValue;
	}

	public function GetAsHTML($value)
	{
		if (!is_array($value))
		{
			throw new CoreException('Expecting an array', array('found' => get_class($value)));
		}
		if (count($value) == 0)
		{
			return "";
		}

		$sRes = "<TABLE class=\"listResults\">";
		$sRes .= "<TBODY>";
		foreach($value as $sProperty => $sValue)
		{
			$sRes .= "<TR>";
			$sCell = str_replace("\n", "<br>\n", Str::pure2html((string)$sValue));
			$sRes .= "<TD class=\"label\">$sProperty</TD><TD>$sCell</TD>";
			$sRes .= "</TR>";
		}
		$sRes .= "</TBODY>";
		$sRes .= "</TABLE>";
		return $sRes;
	}
}

?>
