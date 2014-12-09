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
 * Typology for the attributes
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


require_once('MyHelpers.class.inc.php');
require_once('ormdocument.class.inc.php');
require_once('ormstopwatch.class.inc.php');
require_once('ormpassword.class.inc.php');
require_once('ormcaselog.class.inc.php');

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
 * Fully silent delete... not yet implemented
 */
define('DEL_SILENT', 2);
/**
 * For HierarchicalKeys only: move all the children up one level automatically
 */
define('DEL_MOVEUP', 3);


/**
 * For Link sets: tracking_level
 *
 * @package     iTopORM
 */
define('ATTRIBUTE_TRACKING_NONE', 0); // Do not track changes of the attribute
define('ATTRIBUTE_TRACKING_ALL', 3); // Do track all changes of the attribute
define('LINKSET_TRACKING_NONE', 0); // Do not track changes in the link set
define('LINKSET_TRACKING_LIST', 1); // Do track added/removed items
define('LINKSET_TRACKING_DETAILS', 2); // Do track modified items
define('LINKSET_TRACKING_ALL', 3); // Do track added/removed/modified items

define('LINKSET_EDITMODE_NONE', 0); // The linkset cannot be edited at all from inside this object
define('LINKSET_EDITMODE_ADDONLY', 1); // The only possible action is to open a new window to create a new object
define('LINKSET_EDITMODE_ACTIONS', 2); // Show the usual 'Actions' popup menu
define('LINKSET_EDITMODE_INPLACE', 3); // The "linked" objects can be created/modified/deleted in place
define('LINKSET_EDITMODE_ADDREMOVE', 4); // The "linked" objects can be added/removed in place


/**
 * Attribute definition API, implemented in and many flavours (Int, String, Enum, etc.) 
 *
 * @package     iTopORM
 */
abstract class AttributeDefinition
{
	public function GetType()
	{
		return Dict::S('Core:'.get_class($this));
	}
	public function GetTypeDesc()
	{
		return Dict::S('Core:'.get_class($this).'+');
	}

	abstract public function GetEditClass();

	protected $m_sCode;
	private $m_aParams = array();
	protected $m_sHostClass = '!undefined!';
	public function Get($sParamName) {return $this->m_aParams[$sParamName];}
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

	public function GetParams()
	{
		return $this->m_aParams;
	}

	public function SetHostClass($sHostClass)
	{
		$this->m_sHostClass = $sHostClass;
	}
	public function GetHostClass()
	{
		return $this->m_sHostClass;
	}

	public function ListSubItems()
	{
		$aSubItems = array();
		foreach(MetaModel::ListAttributeDefs($this->m_sHostClass) as $sAttCode => $oAttDef)
		{
			if ($oAttDef instanceof AttributeSubItem)
			{
				if ($oAttDef->Get('target_attcode') == $this->m_sCode)
				{
					$aSubItems[$sAttCode] = $oAttDef;
				}
			}
		}
		return $aSubItems;
	}

	// Note: I could factorize this code with the parameter management made for the AttributeDef class
	// to be overloaded
	static public function ListExpectedParams()
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
	public function GetFinalAttDef()
	{
		return $this;
	}
	public function IsDirectField() {return false;} 
	public function IsScalar() {return false;} 
	public function IsLinkSet() {return false;} 
	public function IsExternalKey($iType = EXTKEY_RELATIVE) {return false;} 
	public function IsHierarchicalKey() {return false;}
	public function IsExternalField() {return false;} 
	public function IsWritable() {return false;} 
	public function LoadInObject() {return true;}
	public function AlwaysLoadInTables() {return $this->GetOptional('always_load_in_tables', false);}
	public function GetValue($oHostObject){return null;} // must return the value if LoadInObject returns false
	public function IsNullAllowed() {return true;} 
	public function GetCode() {return $this->m_sCode;} 

	/**
	 * Helper to browse the hierarchy of classes, searching for a label
	 */	 	
	protected function SearchLabel($sDictEntrySuffix, $sDefault, $bUserLanguageOnly)
	{
		$sLabel = Dict::S('Class:'.$this->m_sHostClass.$sDictEntrySuffix, '', $bUserLanguageOnly);
		if (strlen($sLabel) == 0)
		{
			// Nothing found: go higher in the hierarchy (if possible)
			//
			$sLabel = $sDefault;
			$sParentClass = MetaModel::GetParentClass($this->m_sHostClass);
			if ($sParentClass)
			{
				if (MetaModel::IsValidAttCode($sParentClass, $this->m_sCode))
				{
					$oAttDef = MetaModel::GetAttributeDef($sParentClass, $this->m_sCode);
					$sLabel = $oAttDef->SearchLabel($sDictEntrySuffix, $sDefault, $bUserLanguageOnly);
				}
			}
		}
		return $sLabel;
	}

	public function GetLabel($sDefault = null)
	{
		$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode, null, true /*user lang*/);
		if (is_null($sLabel))
		{
			// If no default value is specified, let's define the most relevant one for developping purposes
			if (is_null($sDefault))
			{
				$sDefault = str_replace('_', ' ', $this->m_sCode);
			}
			// Browse the hierarchy again, accepting default (english) translations
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode, $sDefault, false);
		}
		return $sLabel;
	}

	/**
	 * Get the label corresponding to the given value (in plain text)
	 * To be overloaded for localized enums
	 */
	public function GetValueLabel($sValue)
	{
		return $sValue;
	}

	/**
	 * Get the value from a given string (plain text, CSV import)
	 * Return null if no match could be found	 
	 */
	public function MakeValueFromString($sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null, $sAttributeQualifier = null)
	{
		return $this->MakeRealValue($sProposedValue, null);
	}

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

	public function GetDescription($sDefault = null)
	{
		$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'+', null, true /*user lang*/);
		if (is_null($sLabel))
		{
			// If no default value is specified, let's define the most relevant one for developping purposes
			if (is_null($sDefault))
			{
				$sDefault = '';
			}
			// Browse the hierarchy again, accepting default (english) translations
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'+', $sDefault, false);
		}
		return $sLabel;
	}

	public function GetHelpOnEdition($sDefault = null)
	{
		$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'?', null, true /*user lang*/);
		if (is_null($sLabel))
		{
			// If no default value is specified, let's define the most relevant one for developping purposes
			if (is_null($sDefault))
			{
				$sDefault = '';
			}
			// Browse the hierarchy again, accepting default (english) translations
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'?', $sDefault, false);
		}
		return $sLabel;
	} 

	public function GetHelpOnSmartSearch()
	{
		$aParents = array_merge(array(get_class($this) => get_class($this)), class_parents($this));
		foreach ($aParents as $sClass)
		{
			$sHelp = Dict::S("Core:$sClass?SmartSearch", '-missing-');
			if ($sHelp != '-missing-')
			{
				return $sHelp;
			}
		} 
		return '';
	} 

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

	public function GetTrackingLevel()
	{
		return $this->GetOptional('tracking_level', ATTRIBUTE_TRACKING_ALL);
	}

	public function GetValuesDef() {return null;} 
	public function GetPrerequisiteAttributes() {return array();} 

	public function GetNullValue() {return null;} 
	public function IsNull($proposedValue) {return is_null($proposedValue);} 

	public function MakeRealValue($proposedValue, $oHostObj) {return $proposedValue;} // force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!)
	public function Equals($val1, $val2) {return ($val1 == $val2);}

	public function GetSQLExpressions($sPrefix = '') {return array();} // returns suffix/expression pairs (1 in most of the cases), for READING (Select)
	public function FromSQLToValue($aCols, $sPrefix = '') {return null;} // returns a value out of suffix/value pairs, for SELECT result interpretation
	public function GetSQLColumns() {return array();} // returns column/spec pairs (1 in most of the cases), for STRUCTURING (DB creation)
	public function GetSQLValues($value) {return array();} // returns column/value pairs (1 in most of the cases), for WRITING (Insert, Update)
	public function RequiresIndex() {return false;}

	public function GetOrderBySQLExpressions($sClassAlias)
	{
		// Note: This is the responsibility of this function to place backticks around column aliases
		return array('`'.$sClassAlias.$this->GetCode().'`');
	}
	
	public function GetOrderByHint()
	{
		return '';
	}

   // Import - differs slightly from SQL input, but identical in most cases
   //
	public function GetImportColumns() {return $this->GetSQLColumns();}
	public function FromImportToValue($aCols, $sPrefix = '')
	{
		$aValues = array();
		foreach ($this->GetSQLExpressions($sPrefix) as $sAlias => $sExpr)
		{
			// This is working, based on the assumption that importable fields
			// are not computed fields => the expression is the name of a column
			$aValues[$sPrefix.$sAlias] = $aCols[$sExpr];
		}
		return $this->FromSQLToValue($aValues, $sPrefix);
	}

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

	public function GetEditValue($sValue, $oHostObj = null)
	{
		return (string)$sValue;
	}

	/**
	 * Helper to get a value that will be JSON encoded
	 * The operation is the opposite to FromJSONToValue	 
	 */	 	
	public function GetForJSON($value)
	{
		// In most of the cases, that will be the expected behavior...
		return $this->GetEditValue($value);
	}

	/**
	 * Helper to form a value, given JSON decoded data
	 * The operation is the opposite to GetForJSON	 
	 */	 	
	public function FromJSONToValue($json)
	{
		// Passthrough in most of the cases
		return $json;
	}

	/**
	 * Override to display the value in the GUI
	 */	
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2html((string)$sValue);
	}

	/**
	 * Override to export the value in XML	
	 */	
	public function GetAsXML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2xml((string)$sValue);
	}

	/**
	 * Override to escape the value when read by DBObject::GetAsCSV()	
	 */	
	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		return (string)$sValue;
	}

	/**
	 * Override to differentiate a value displayed in the UI or in the history
	 */	
	public function GetAsHTMLForHistory($sValue, $oHostObject = null, $bLocalize = true)
	{
		return $this->GetAsHTML($sValue, $oHostObject, $bLocalize);
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$oValSetDef = $this->GetValuesDef();
		if (!$oValSetDef) return null;
		return $oValSetDef->GetValues($aArgs, $sContains);
	}

	/**
	 * Explain the change of the attribute (history)
	 */	
	public function DescribeChangeAsHTML($sOldValue, $sNewValue, $sLabel = null)
	{
		if (is_null($sLabel))
		{
			$sLabel = $this->GetLabel();
		}

		$sNewValueHtml = $this->GetAsHTMLForHistory($sNewValue);
		$sOldValueHtml = $this->GetAsHTMLForHistory($sOldValue);

		if($this->IsExternalKey())
		{
			$sTargetClass = $this->GetTargetClass();
			$sOldValueHtml = (int)$sOldValue ? MetaModel::GetHyperLink($sTargetClass, (int)$sOldValue) : null;
			$sNewValueHtml = (int)$sNewValue ? MetaModel::GetHyperLink($sTargetClass, (int)$sNewValue) : null;
		}
		if ( (($this->GetType() == 'String') || ($this->GetType() == 'Text')) &&
			 (strlen($sNewValue) > strlen($sOldValue)) )
		{
			// Check if some text was not appended to the field
			if (substr($sNewValue,0, strlen($sOldValue)) == $sOldValue) // Text added at the end
			{
				$sDelta = $this->GetAsHTML(substr($sNewValue, strlen($sOldValue)));
				$sResult = Dict::Format('Change:Text_AppendedTo_AttName', $sDelta, $sLabel);
			}
			else if (substr($sNewValue, -strlen($sOldValue)) == $sOldValue)   // Text added at the beginning
			{
				$sDelta = $this->GetAsHTML(substr($sNewValue, 0, strlen($sNewValue) - strlen($sOldValue)));
				$sResult = Dict::Format('Change:Text_AppendedTo_AttName', $sDelta, $sLabel);
			}
			else
			{
				if (strlen($sOldValue) == 0)
				{
					$sResult = Dict::Format('Change:AttName_SetTo', $sLabel, $sNewValueHtml);
				}
				else
				{
					if (is_null($sNewValue))
					{
						$sNewValueHtml = Dict::S('UI:UndefinedObject');
					}
					$sResult = Dict::Format('Change:AttName_SetTo_NewValue_PreviousValue_OldValue', $sLabel, $sNewValueHtml, $sOldValueHtml);
				}
			}
		}
		else
		{
			if (strlen($sOldValue) == 0)
			{
				$sResult = Dict::Format('Change:AttName_SetTo', $sLabel, $sNewValueHtml);
			}
			else
			{
				if (is_null($sNewValue))
				{
					$sNewValueHtml = Dict::S('UI:UndefinedObject');
				}
				$sResult = Dict::Format('Change:AttName_SetTo_NewValue_PreviousValue_OldValue', $sLabel, $sNewValueHtml, $sOldValueHtml);
			}
		}
		return $sResult;
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
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("allowed_values", "depends_on", "linked_class", "ext_key_to_me", "count_min", "count_max"));
	}

	public function GetEditClass() {return "LinkedSet";}

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

	public function GetTrackingLevel()
	{
		return $this->GetOptional('tracking_level', MetaModel::GetConfig()->Get('tracking_level_linked_set_default'));
	}

	public function GetEditMode()
	{
		return $this->GetOptional('edit_mode', LINKSET_EDITMODE_ACTIONS);
	}
	
	public function GetLinkedClass() {return $this->Get('linked_class');}
	public function GetExtKeyToMe() {return $this->Get('ext_key_to_me');}

	public function GetBasicFilterOperators() {return array();}
	public function GetBasicFilterLooseOperator() {return '';}
	public function GetBasicFilterSQLExpr($sOpCode, $value) {return '';}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($sValue) && ($sValue instanceof DBObjectSet))
		{
			$sValue->Rewind();
			$aItems = array();
			while ($oObj = $sValue->Fetch())
			{
				// Show only relevant information (hide the external key to the current object)
				$aAttributes = array();
				foreach(MetaModel::ListAttributeDefs($this->GetLinkedClass()) as $sAttCode => $oAttDef)
				{
					if ($sAttCode == $this->GetExtKeyToMe()) continue;
					if ($oAttDef->IsExternalField()) continue;
					$sAttValue = $oObj->GetAsHTML($sAttCode);
					if (strlen($sAttValue) > 0)
					{
						$aAttributes[] = $sAttValue;
					}
				}
				$sAttributes = implode(', ', $aAttributes);
				$aItems[] = $sAttributes;
			}
			return implode('<br/>', $aItems);
		}
		return null;
	}

	public function GetAsXML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return "Sorry, no yet implemented";
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		$sSepItem = MetaModel::GetConfig()->Get('link_set_item_separator');
		$sSepAttribute = MetaModel::GetConfig()->Get('link_set_attribute_separator');
		$sSepValue = MetaModel::GetConfig()->Get('link_set_value_separator');
		$sAttributeQualifier = MetaModel::GetConfig()->Get('link_set_attribute_qualifier');

		if (is_object($sValue) && ($sValue instanceof DBObjectSet))
		{
			$sValue->Rewind();
			$aItems = array();
			while ($oObj = $sValue->Fetch())
			{
				$sObjClass = get_class($oObj);
				// Show only relevant information (hide the external key to the current object)
				$aAttributes = array();
				foreach(MetaModel::ListAttributeDefs($sObjClass) as $sAttCode => $oAttDef)
				{
					if ($sAttCode == 'finalclass')
					{
						if ($sObjClass == $this->GetLinkedClass())
						{
							// Simplify the output if the exact class could be determined implicitely 
							continue;
						}
					}
					if ($sAttCode == $this->GetExtKeyToMe()) continue;
					if ($oAttDef->IsExternalField()) continue;
					if (!$oAttDef->IsDirectField()) continue;
					if (!$oAttDef->IsScalar()) continue;
					$sAttValue = $oObj->GetAsCSV($sAttCode, $sSepValue, '');
					if (strlen($sAttValue) > 0)
					{
						$sAttributeData = str_replace($sAttributeQualifier, $sAttributeQualifier.$sAttributeQualifier, $sAttCode.$sSepValue.$sAttValue);
						$aAttributes[] = $sAttributeQualifier.$sAttributeData.$sAttributeQualifier;
					}
				}
				$sAttributes = implode($sSepAttribute, $aAttributes);
				$aItems[] = $sAttributes;
			}
			$sRes = implode($sSepItem, $aItems);
		}
		else
		{
			$sRes = '';
		}
		$sRes = str_replace($sTextQualifier, $sTextQualifier.$sTextQualifier, $sRes);
		$sRes = $sTextQualifier.$sRes.$sTextQualifier;
		return $sRes;
	}

	public function DuplicatesAllowed() {return false;} // No duplicates for 1:n links, never

	public function GetImportColumns()
	{
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'TEXT';
		return $aColumns;
	}

	public function MakeValueFromString($sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null, $sAttributeQualifier = null)
	{
		if (is_null($sSepItem) || empty($sSepItem))
		{
			$sSepItem = MetaModel::GetConfig()->Get('link_set_item_separator');
		}
		if (is_null($sSepAttribute) || empty($sSepAttribute))
		{
			$sSepAttribute = MetaModel::GetConfig()->Get('link_set_attribute_separator');
		}
		if (is_null($sSepValue) || empty($sSepValue))
		{
			$sSepValue = MetaModel::GetConfig()->Get('link_set_value_separator');
		}
		if (is_null($sAttributeQualifier) || empty($sAttributeQualifier))
		{
			$sAttributeQualifier = MetaModel::GetConfig()->Get('link_set_attribute_qualifier');
		}

		$sTargetClass = $this->Get('linked_class');

		$sInput = str_replace($sSepItem, "\n", $sProposedValue);
		$oCSVParser = new CSVParser($sInput, $sSepAttribute, $sAttributeQualifier);

		$aInput = $oCSVParser->ToArray(0 /* do not skip lines */);

		$aLinks = array();
		foreach($aInput as $aRow)
		{
			// 1st - get the values, split the extkey->searchkey specs, and eventually get the finalclass value
			$aExtKeys = array();
			$aValues = array();
			foreach($aRow as $sCell)
			{
				$iSepPos = strpos($sCell, $sSepValue);
				if ($iSepPos === false)
				{
					// Houston...
					throw new CoreException('Wrong format for link attribute specification', array('value' => $sCell));
				}

				$sAttCode = trim(substr($sCell, 0, $iSepPos));
				$sValue = substr($sCell, $iSepPos + strlen($sSepValue));

				if (preg_match('/^(.+)->(.+)$/', $sAttCode, $aMatches))
				{
					$sKeyAttCode = $aMatches[1];
					$sRemoteAttCode = $aMatches[2];
					$aExtKeys[$sKeyAttCode][$sRemoteAttCode] = $sValue;
					if (!MetaModel::IsValidAttCode($sTargetClass, $sKeyAttCode))
					{
						throw new CoreException('Wrong attribute code for link attribute specification', array('class' => $sTargetClass, 'attcode' => $sKeyAttCode));
					}
					$oKeyAttDef = MetaModel::GetAttributeDef($sTargetClass, $sKeyAttCode);
					$sRemoteClass = $oKeyAttDef->GetTargetClass();
					if (!MetaModel::IsValidAttCode($sRemoteClass, $sRemoteAttCode))
					{
						throw new CoreException('Wrong attribute code for link attribute specification', array('class' => $sRemoteClass, 'attcode' => $sRemoteAttCode));
					}
				}
				else
				{
					if(!MetaModel::IsValidAttCode($sTargetClass, $sAttCode))
					{
						throw new CoreException('Wrong attribute code for link attribute specification', array('class' => $sTargetClass, 'attcode' => $sAttCode));
					}
					$aValues[$sAttCode] = $sValue;
				}
			}

			// 2nd - Instanciate the object and set the value
			if (isset($aValues['finalclass']))
			{
				$sLinkClass = $aValues['finalclass'];
				if (!is_subclass_of($sLinkClass, $sTargetClass))
				{
					throw new CoreException('Wrong class for link attribute specification', array('requested_class' => $sLinkClass, 'expected_class' => $sTargetClass));
				}
			}
			elseif (MetaModel::IsAbstract($sTargetClass))
			{
					throw new CoreException('Missing finalclass for link attribute specification');
			}
			else
			{
				$sLinkClass = $sTargetClass;
			}

			$oLink = MetaModel::NewObject($sLinkClass);
			foreach ($aValues as $sAttCode => $sValue)
			{
				$oLink->Set($sAttCode, $sValue);
			}

			// 3rd - Set external keys from search conditions
			foreach ($aExtKeys as $sKeyAttCode => $aReconciliation)
			{
				$oKeyAttDef = MetaModel::GetAttributeDef($sTargetClass, $sKeyAttCode);
				$sKeyClass = $oKeyAttDef->GetTargetClass();
				$oExtKeyFilter = new CMDBSearchFilter($sKeyClass);
				$aReconciliationDesc = array();
				foreach($aReconciliation as $sRemoteAttCode => $sValue)
				{
					$oExtKeyFilter->AddCondition($sRemoteAttCode, $sValue, '=');
					$aReconciliationDesc[] = "$sRemoteAttCode=$sValue";
				}
				$oExtKeySet = new CMDBObjectSet($oExtKeyFilter);
				switch($oExtKeySet->Count())
				{
				case 0:
					$sReconciliationDesc = implode(', ', $aReconciliationDesc);
					throw new CoreException("Found no match", array('ext_key' => $sKeyAttCode, 'reconciliation' => $sReconciliationDesc));
					break;
				case 1:
					$oRemoteObj = $oExtKeySet->Fetch();
					$oLink->Set($sKeyAttCode, $oRemoteObj->GetKey());
					break;
				default:
					$sReconciliationDesc = implode(', ', $aReconciliationDesc);
					throw new CoreException("Found several matches", array('ext_key' => $sKeyAttCode, 'reconciliation' => $sReconciliationDesc));
					// Found several matches, ambiguous
				}
			}

			// Check (roughly) if such a link is valid
			$aErrors = array();
			foreach(MetaModel::ListAttributeDefs($sTargetClass) as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsExternalKey())
				{
					if (($oAttDef->GetTargetClass() == $this->GetHostClass()) || (is_subclass_of($this->GetHostClass(), $oAttDef->GetTargetClass())))
					{
						continue; // Don't check the key to self
					}
				}
				
				if ($oAttDef->IsWritable() && $oAttDef->IsNull($oLink->Get($sAttCode)) && !$oAttDef->IsNullAllowed())
				{
					$aErrors[] = $sAttCode;
				}
			}
			if (count($aErrors) > 0)
			{
				throw new CoreException("Missing value for mandatory attribute(s): ".implode(', ', $aErrors));
			}

			$aLinks[] = $oLink;
		}
		$oSet = DBObjectSet::FromArray($sTargetClass, $aLinks);
		return $oSet;
	}

	public function Equals($val1, $val2)
	{
		if ($val1 === $val2) return true;

		if (is_object($val1) != is_object($val2))
		{
			return false;
		}
		if (!is_object($val1))
		{
			// string ?
			// todo = implement this case ?
			return false;
		}

		// Both values are Object sets
		return $val1->HasSameContents($val2);
	}
}

/**
 * Set of objects linked to an object (n-n), and being part of its definition  
 *
 * @package     iTopORM
 */
class AttributeLinkedSetIndirect extends AttributeLinkedSet
{
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("ext_key_to_remote"));
	}
	public function IsIndirect() {return true;} 
	public function GetExtKeyToRemote() { return $this->Get('ext_key_to_remote'); }
	public function GetEditClass() {return "LinkedSet";}
	public function DuplicatesAllowed() {return $this->GetOptional("duplicates", false);} // The same object may be linked several times... or not...

	public function GetTrackingLevel()
	{
		return $this->GetOptional('tracking_level', MetaModel::GetConfig()->Get('tracking_level_linked_set_indirect_default'));
	}
}

/**
 * Abstract class implementing default filters for a DB column  
 *
 * @package     iTopORM
 */
class AttributeDBFieldVoid extends AttributeDefinition
{	
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("allowed_values", "depends_on", "sql"));
	}

	// To be overriden, used in GetSQLColumns
	protected function GetSQLCol() {return "VARCHAR(255)";}

	public function GetEditClass() {return "String";}
	
	public function GetValuesDef() {return $this->Get("allowed_values");} 
	public function GetPrerequisiteAttributes() {return $this->Get("depends_on");} 

	public function IsDirectField() {return true;} 
	public function IsScalar() {return true;} 
	public function IsWritable() {return true;} 
	public function GetSQLExpr()    {return $this->Get("sql");}
	public function GetDefaultValue() {return $this->MakeRealValue("", null);}
	public function IsNullAllowed() {return false;}

	// 
	protected function ScalarToSQL($value) {return $value;} // format value as a valuable SQL literal (quoted outside)

	public function GetSQLExpressions($sPrefix = '')
	{
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $this->Get("sql");
		return $aColumns;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$value = $this->MakeRealValue($aCols[$sPrefix.''], null);
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
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("default_value", "is_null_allowed"));
	}
	public function GetDefaultValue() {return $this->MakeRealValue($this->Get("default_value"), null);}
	public function IsNullAllowed() {return $this->Get("is_null_allowed");}
}

/**
 * Map an integer column to an attribute 
 *
 * @package     iTopORM
 */
class AttributeInteger extends AttributeDBField
{
	static public function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

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

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) return null;
		if ($proposedValue === '') return null; // 0 is transformed into '' !
		return (int)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		assert(is_numeric($value) || is_null($value));
		return $value; // supposed to be an int
	}
}

/**
 * Display an integer between 0 and 100 as a percentage / horizontal bar graph 
 *
 * @package     iTopORM
 */
class AttributePercentage extends AttributeInteger
{
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$iWidth = 5; // Total width of the percentage bar graph, in em...
		$iValue = (int)$sValue;
		if ($iValue > 100)
		{
			$iValue = 100;
		}
		else if ($iValue < 0)
		{
			$iValue = 0;
		}
		if ($iValue > 90)
		{
			$sColor = "#cc3300";
		}
		else if ($iValue > 50)
		{
			$sColor = "#cccc00";
		}
		else
		{
			$sColor = "#33cc00";
		}
		$iPercentWidth = ($iWidth * $iValue) / 100;
		return "<div style=\"width:{$iWidth}em;-moz-border-radius: 3px;-webkit-border-radius: 3px;border-radius: 3px;display:inline-block;border: 1px #ccc solid;\"><div style=\"width:{$iPercentWidth}em; display:inline-block;background-color:$sColor;\">&nbsp;</div></div>&nbsp;$sValue %";
	}
}

/**
 * Map a decimal value column (suitable for financial computations) to an attribute
 * internally in PHP such numbers are represented as string. Should you want to perform
 * a calculation on them, it is recommended to use the BC Math functions in order to
 * retain the precision
 *
 * @package     iTopORM
 */
class AttributeDecimal extends AttributeDBField
{
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('digits', 'decimals' /* including precision */));
	}

	public function GetEditClass() {return "String";}
	protected function GetSQLCol() {return "DECIMAL(".$this->Get('digits').",".$this->Get('decimals').")";}
	
	public function GetValidationPattern()
	{
		$iNbDigits = $this->Get('digits');
		$iPrecision = $this->Get('decimals');
		$iNbIntegerDigits = $iNbDigits - $iPrecision - 1; // -1 because the first digit is treated separately in the pattern below
		return "^[-+]?[0-9]\d{0,$iNbIntegerDigits}(\.\d{0,$iPrecision})?$";
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

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) return null;
		if ($proposedValue === '') return null;
		return (string)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		assert(is_null($value) || preg_match('/'.$this->GetValidationPattern().'/', $value));
		return $value; // null or string
	}
}

/**
 * Map a boolean column to an attribute 
 *
 * @package     iTopORM
 */
class AttributeBoolean extends AttributeInteger
{
	static public function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass() {return "Integer";}
	protected function GetSQLCol() {return "TINYINT(1)";}
	
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) return null;
		if ($proposedValue === '') return null;
		if ((int)$proposedValue) return true;
		return false;
	}

	public function ScalarToSQL($value)
	{
		if ($value) return 1;
		return 0;
	}

	public function GetAsXML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return $sValue ? '1' : '0';
	}
	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		return $sValue ? '1' : '0';
	}
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return $sValue ? '1' : '0';
	}
	/**
	 * Helper to get a value that will be JSON encoded
	 * The operation is the opposite to FromJSONToValue	 
	 */	 	
	public function GetForJSON($value)
	{
		return $value ? '1' : '0';
	}
}

/**
 * Map a varchar column (size < ?) to an attribute 
 *
 * @package     iTopORM
 */
class AttributeString extends AttributeDBField
{
	static public function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass() {return "String";}
	protected function GetSQLCol() {return "VARCHAR(255)";}

	public function GetValidationPattern()
	{
		$sPattern = $this->GetOptional('validation_pattern', '');
		if (empty($sPattern))
		{
			return parent::GetValidationPattern();
		}
		else
		{
			return $sPattern;
		}
	}

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

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) return '';
		return (string)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		if (!is_string($value) && !is_null($value))
		{
			throw new CoreWarning('Expected the attribute value to be a string', array('found_type' => gettype($value), 'value' => $value, 'class' => $this->GetHostClass(), 'attribute' => $this->GetCode()));
		}
		return $value;
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);
		return $sTextQualifier.$sEscaped.$sTextQualifier;
	}

	public function GetDisplayStyle()
	{
		return $this->GetOptional('display_style', 'select');
	}
}

/**
 * An attibute that matches an object class 
 *
 * @package     iTopORM
 */
class AttributeClass extends AttributeString
{
	static public function ListExpectedParams()
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

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (empty($sValue)) return '';
		return MetaModel::GetName($sValue);
	}

	public function RequiresIndex()
	{
		return true;
	}
	
	public function GetBasicFilterLooseOperator()
	{
		return '=';
	}
	
}

/**
 * An attibute that matches one of the language codes availables in the dictionnary 
 *
 * @package     iTopORM
 */
class AttributeApplicationLanguage extends AttributeString
{
	static public function ListExpectedParams()
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
	
	public function GetBasicFilterLooseOperator()
	{
		return '=';
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

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (empty($sValue)) return '';
		return MetaModel::GetName($sValue);
	}

	public function GetBasicFilterLooseOperator()
	{
		return '=';
	}
	
	public function GetValueLabel($sValue)
	{
		if (empty($sValue)) return '';
		return MetaModel::GetName($sValue);
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$aRawValues = MetaModel::EnumChildClasses($this->GetHostClass(), ENUM_CHILD_CLASSES_ALL);
		$aLocalizedValues = array();
		foreach ($aRawValues as $sClass)
		{
			$aLocalizedValues[$sClass] = MetaModel::GetName($sClass);
		}
  		return $aLocalizedValues;
  	}
}


/**
 * Map a varchar column (size < ?) to an attribute that must never be shown to the user 
 *
 * @package     iTopORM
 */
class AttributePassword extends AttributeString
{
	static public function ListExpectedParams()
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

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
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
	/**
	 * When the attribute definitions are stored in APC cache:
	 * 1) The static class variable $sKey is NOT serialized
	 * 2) The object's constructor is NOT called upon wakeup
	 * 3) mcrypt may crash the server if passed an empty key !!
	 * 
	 * So let's restore the key (if needed) when waking up
	 **/
	public function __wakeup()
	{
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

	public function MakeRealValue($proposedValue, $oHostObj)
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


// Wiki formatting - experimental
//
// [[<objClass>:<objName>]]
// Example: [[Server:db1.tnut.com]]
define('WIKI_OBJECT_REGEXP', '/\[\[(.+):(.+)\]\]/U');


/**
 * Map a text column (size > ?) to an attribute 
 *
 * @package     iTopORM
 */
class AttributeText extends AttributeString
{
	public function GetEditClass() {return "Text";}
	protected function GetSQLCol() {return "TEXT";}

	public function GetMaxSize()
	{
		// Is there a way to know the current limitation for mysql?
		// See mysql_field_len()
		return 65535;
	}

	static public function RenderWikiHtml($sText)
	{
		$sPattern = '/'.str_replace('/', '\/', utils::GetConfig()->Get('url_validation_pattern')).'/i';
		if (preg_match_all($sPattern, $sText, $aAllMatches, PREG_SET_ORDER /* important !*/ |PREG_OFFSET_CAPTURE /* important ! */))
		{
			$aUrls = array();
			$i = count($aAllMatches);
			// Replace the URLs by an actual hyperlink <a href="...">...</a>
			// Let's do it backwards so that the initial positions are not modified by the replacement
			// This works if the matches are captured: in the order they occur in the string  AND
			// with their offset (i.e. position) inside the string
			while($i > 0)
			{
				$i--;
				$sUrl = $aAllMatches[$i][0][0]; // String corresponding to the main pattern
				$iPos = $aAllMatches[$i][0][1]; // Position of the main pattern
				$sText = substr_replace($sText, "<a href=\"$sUrl\">$sUrl</a>", $iPos, strlen($sUrl));
				
			}
		}
		if (preg_match_all(WIKI_OBJECT_REGEXP, $sText, $aAllMatches, PREG_SET_ORDER))
		{
			foreach($aAllMatches as $iPos => $aMatches)
			{
				$sClass = $aMatches[1];
				$sName = $aMatches[2];
				
				if (MetaModel::IsValidClass($sClass))
				{
					$oObj = MetaModel::GetObjectByName($sClass, $sName, false /* MustBeFound */);
					if (is_object($oObj))
					{
						// Propose a std link to the object
						$sText = str_replace($aMatches[0], $oObj->GetHyperlink(), $sText);
					}
					else
					{
						// Propose a std link to the object
						$sClassLabel = MetaModel::GetName($sClass);
						$sText = str_replace($aMatches[0], "<span class=\"wiki_broken_link\">$sClassLabel:$sName</span>", $sText);
						// Later: propose a link to create a new object
						// Anyhow... there is no easy way to suggest default values based on the given FRIENDLY name
						//$sText = preg_replace('/\[\[(.+):(.+)\]\]/', '<a href="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=new&class='.$sClass.'&default[att1]=xxx&default[att2]=yyy">'.$sName.'</a>', $sText);
					}
				}
			}
		}
		return $sText;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$sValue = parent::GetAsHTML($sValue, $oHostObject, $bLocalize);
		$sValue = self::RenderWikiHtml($sValue);
		$aStyles = array();
		if ($this->GetWidth() != '')
		{
			$aStyles[] = 'width:'.$this->GetWidth();
		}
		if ($this->GetHeight() != '')
		{
			$aStyles[] = 'height:'.$this->GetHeight();
		}
		$sStyle = '';
		if (count($aStyles) > 0)
		{
			$aStyles[] = 'overflow:auto';
			$sStyle = 'style="'.implode(';', $aStyles).'"';
		}
		return "<div $sStyle>".str_replace("\n", "<br>\n", $sValue).'</div>';
	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		if (preg_match_all(WIKI_OBJECT_REGEXP, $sValue, $aAllMatches, PREG_SET_ORDER))
		{
			foreach($aAllMatches as $iPos => $aMatches)
			{
				$sClass = $aMatches[1];
				$sName = $aMatches[2];
				
				if (MetaModel::IsValidClass($sClass))
				{
					$sClassLabel = MetaModel::GetName($sClass);
					$sValue = str_replace($aMatches[0], "[[$sClassLabel:$sName]]", $sValue);
				}
			}
		}
		return $sValue;
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		$sValue = $proposedValue;
		if (preg_match_all(WIKI_OBJECT_REGEXP, $sValue, $aAllMatches, PREG_SET_ORDER))
		{
			foreach($aAllMatches as $iPos => $aMatches)
			{
				$sClassLabel = $aMatches[1];
				$sName = $aMatches[2];
				
				if (!MetaModel::IsValidClass($sClassLabel))
				{
					$sClass = MetaModel::GetClassFromLabel($sClassLabel);
					if ($sClass)
					{
						$sValue = str_replace($aMatches[0], "[[$sClass:$sName]]", $sValue);
					}
				}
			}
		}
		return $sValue;
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2xml($value);
	}
	
	public function GetWidth()
	{
		return $this->GetOptional('width', '');		
	}
	
	public function GetHeight()
	{
		return $this->GetOptional('height', '');		
	}
}

/**
 * Map a log to an attribute 
 *
 * @package     iTopORM
 */
class AttributeLongText extends AttributeText
{
	protected function GetSQLCol() {return "LONGTEXT";}

	public function GetMaxSize()
	{
		// Is there a way to know the current limitation for mysql?
		// See mysql_field_len()
		return 65535*1024; // Limited... still 64 Mb!
	}
}

/**
 * An attibute that stores a case log (i.e journal) 
 *
 * @package     iTopORM
 */
class AttributeCaseLog extends AttributeLongText
{
	public function GetNullValue()
	{
		return '';
	} 

	public function IsNull($proposedValue)
	{
		if (!($proposedValue instanceof ormCaseLog))
		{
			return ($proposedValue == '');
		}
		return ($proposedValue->GetText() == '');
	} 

	public function ScalarToSQL($value)
	{
		if (!is_string($value) && !is_null($value))
		{
			throw new CoreWarning('Expected the attribute value to be a string', array('found_type' => gettype($value), 'value' => $value, 'class' => $this->GetCode(), 'attribute' => $this->GetHostClass()));
		}
		return $value;
	}
	public function GetEditClass() {return "CaseLog";}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		if (!($sValue instanceOf ormCaseLog))
		{
			return '';
		}
		return $sValue->GetModifiedEntry();
	}
	
	public function GetDefaultValue() {return new ormCaseLog();}
	public function Equals($val1, $val2) {return ($val1->GetText() == $val2->GetText());}
	

	// Facilitate things: allow the user to Set the value from a string
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if ($proposedValue instanceof ormCaseLog)
		{
			// Passthrough
			$ret = $proposedValue;
		}
		else
		{
			// Append the new value if an instance of the object is supplied
			//
			$oPreviousLog = null;
			if ($oHostObj != null)
			{
				$oPreviousLog = $oHostObj->Get($this->GetCode());
				if (!is_object($oPreviousLog))
				{
					$oPreviousLog = $oHostObj->GetOriginal($this->GetCode());;
				}
				
			}
			if (is_object($oPreviousLog))
			{
				$oCaseLog = clone($oPreviousLog);
			}
			else
			{
				$oCaseLog = new ormCaseLog();
			}

			if ($proposedValue instanceof stdClass)
			{
				$oCaseLog->AddLogEntryFromJSON($proposedValue);
			}
			else
			{
				if (strlen($proposedValue) > 0)
				{
					$oCaseLog->AddLogEntry(parent::MakeRealValue($proposedValue, $oHostObj));
				}
			}
			$ret = $oCaseLog;
		}
		return $ret;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			$sPrefix = $this->GetCode();
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix;
		$aColumns['_index'] = $sPrefix.'_index';
		return $aColumns;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		if (!array_key_exists($sPrefix, $aCols))
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		} 
		$sLog = $aCols[$sPrefix];

		if (isset($aCols[$sPrefix.'_index'])) 
		{
			$sIndex = $aCols[$sPrefix.'_index'];
		}
		else
		{
			// For backward compatibility, allow the current state to be: 1 log, no index
			$sIndex = '';
		}

		if (strlen($sIndex) > 0)
		{ 
			$aIndex = unserialize($sIndex);
			$value = new ormCaseLog($sLog, $aIndex);
		}
		else
		{
			$value = new ormCaseLog($sLog);
		}
		return $value;
	}

	public function GetSQLValues($value)
	{
		if (!($value instanceOf ormCaseLog))
		{
			$value = new ormCaseLog('');
		}
		$aValues = array();
		$aValues[$this->GetCode()] = $value->GetText();
		$aValues[$this->GetCode().'_index'] = serialize($value->GetIndex());

		return $aValues;
	}

	public function GetSQLColumns()
	{
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'LONGTEXT'; // 2^32 (4 Gb)
		$aColumns[$this->GetCode().'_index'] = 'BLOB';
		return $aColumns;
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if ($value instanceOf ormCaseLog)
		{
			$sContent = $value->GetAsHTML(null, false, array(__class__, 'RenderWikiHtml'));
		}
		else
		{
			$sContent = '';
		}
		$aStyles = array();
		if ($this->GetWidth() != '')
		{
			$aStyles[] = 'width:'.$this->GetWidth();
		}
		if ($this->GetHeight() != '')
		{
			$aStyles[] = 'height:'.$this->GetHeight();
		}
		$sStyle = '';
		if (count($aStyles) > 0)
		{
			$sStyle = 'style="'.implode(';', $aStyles).'"';
		}
		return "<div class=\"caselog\" $sStyle>".$sContent.'</div>';	}


	public function GetAsCSV($value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		if ($value instanceOf ormCaseLog)
		{
			return parent::GetAsCSV($value->GetText(), $sSeparator, $sTextQualifier, $oHostObject, $bLocalize);
		}
		else
		{
			return '';
		}
	}
	
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if ($value instanceOf ormCaseLog)
		{
			return parent::GetAsXML($value->GetText(), $oHostObject, $bLocalize);
		}
		else
		{
			return '';
		}
	}

	/**
	 * Helper to get a value that will be JSON encoded
	 * The operation is the opposite to FromJSONToValue	 
	 */	 	
	public function GetForJSON($value)
	{
		return $value->GetForJSON();
	}

	/**
	 * Helper to form a value, given JSON decoded data
	 * The operation is the opposite to GetForJSON	 
	 */	 	
	public function FromJSONToValue($json)
	{
		if (is_string($json))
		{
			// Will be correctly handled in MakeRealValue
			$ret = $json;
		}
		else
		{
			if (isset($json->add_item))
			{
				// Will be correctly handled in MakeRealValue
				$ret = $json->add_item;
				if (!isset($ret->message))
				{
					throw new Exception("Missing mandatory entry: 'message'");
				}
			}
			else
			{
				$ret = ormCaseLog::FromJSON($json);
			}
		}
		return $ret;
	}
}

/**
 * Map a text column (size > ?), containing HTML code, to an attribute 
 *
 * @package     iTopORM
 */
class AttributeHTML extends AttributeLongText
{
	public function GetEditClass() {return "HTML";}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return $sValue;
	}
}

/**
 * Specialization of a string: email 
 *
 * @package     iTopORM
 */
class AttributeEmailAddress extends AttributeString
{
	public function GetValidationPattern()
	{
		return $this->GetOptional('validation_pattern', '^'.utils::GetConfig()->Get('email_validation_pattern').'$');
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (empty($sValue)) return '';
		return '<a class="mailto" href="mailto:'.$sValue.'">'.parent::GetAsHTML($sValue).'</a>';
	}
}

/**
 * Specialization of a string: IP address 
 *
 * @package     iTopORM
 */
class AttributeIPAddress extends AttributeString
{
	public function GetValidationPattern()
	{
		$sNum = '(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])';
		return "^($sNum\\.$sNum\\.$sNum\\.$sNum)$";
	}

	public function GetOrderBySQLExpressions($sClassAlias)
	{
		// Note: This is the responsibility of this function to place backticks around column aliases
		return array('INET_ATON(`'.$sClassAlias.$this->GetCode().'`)');
	}
}

/**
 * Specialization of a string: OQL expression 
 *
 * @package     iTopORM
 */
class AttributeOQL extends AttributeText
{
	public function GetEditClass() {return "OQLExpression";}
}

/**
 * Specialization of a string: template (contains iTop placeholders like $current_contact_id$ or $this->name$) 
 *
 * @package     iTopORM
 */
class AttributeTemplateString extends AttributeString
{
}

/**
 * Specialization of a text: template (contains iTop placeholders like $current_contact_id$ or $this->name$)
 *
 * @package     iTopORM
 */
class AttributeTemplateText extends AttributeText
{
}

/**
 * Specialization of a HTML: template (contains iTop placeholders like $current_contact_id$ or $this->name$)
 *
 * @package     iTopORM
 */
class AttributeTemplateHTML extends AttributeText
{
	public function GetEditClass() {return "HTML";}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return $sValue;
	}
}


/**
 * Map a enum column to an attribute 
 *
 * @package     iTopORM
 */
class AttributeEnum extends AttributeString
{
	static public function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

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
		return '=';
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return parent::GetBasicFilterSQLExpr($sOpCode, $value);
	} 

	public function GetValueLabel($sValue)
	{
		if (is_null($sValue))
		{
			// Unless a specific label is defined for the null value of this enum, use a generic "undefined" label		
			$sLabel = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue, Dict::S('Enum:Undefined'));
		}
		else
		{
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, null, true /*user lang*/);
			if (is_null($sLabel))
			{
				$sDefault = str_replace('_', ' ', $sValue);
				// Browse the hierarchy again, accepting default (english) translations
				$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, $sDefault, false);
			}
		}
		return $sLabel;
	}

	public function GetValueDescription($sValue)
	{
		if (is_null($sValue))
		{
			// Unless a specific label is defined for the null value of this enum, use a generic "undefined" label		
			$sDescription = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+', Dict::S('Enum:Undefined'));
		}
		else
		{
			$sDescription = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+', '', true /* user language only */);
			if (strlen($sDescription) == 0)
			{
				$sParentClass = MetaModel::GetParentClass($this->m_sHostClass);
				if ($sParentClass)
				{
					if (MetaModel::IsValidAttCode($sParentClass, $this->m_sCode))
					{
						$oAttDef = MetaModel::GetAttributeDef($sParentClass, $this->m_sCode);
						$sDescription = $oAttDef->GetValueDescription($sValue);
					}
				}
			}
		}
		return $sDescription;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if ($bLocalize)
		{
			$sLabel = $this->GetValueLabel($sValue);
			$sDescription = $this->GetValueDescription($sValue);
			// later, we could imagine a detailed description in the title
			$sRes = "<span title=\"$sDescription\">".parent::GetAsHtml($sLabel)."</span>";
		}
		else
		{
			$sRes = parent::GetAsHtml($sValue, $oHostObject, $bLocalize);
		}
		return $sRes;
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_null($value))
		{
			$sFinalValue = '';
		}
		elseif ($bLocalize)
		{
			$sFinalValue = $this->GetValueLabel($value);
		}
		else
		{
			$sFinalValue = $value;
		}
		$sRes = parent::GetAsXML($sFinalValue, $oHostObject, $bLocalize);
		return $sRes;
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		if (is_null($sValue))
		{
			$sFinalValue = '';
		}
		elseif ($bLocalize)
		{
			$sFinalValue = $this->GetValueLabel($sValue);
		}
		else
		{
			$sFinalValue = $sValue;
		}
		$sRes = parent::GetAsCSV($sFinalValue, $sSeparator, $sTextQualifier, $oHostObject, $bLocalize);
		return $sRes;
	}


	public function GetEditValue($sValue, $oHostObj = null)
	{
		if (is_null($sValue))
		{
			return '';
		}
		else
		{
			return $this->GetValueLabel($sValue);
		}
	}

	/**
	 * Helper to get a value that will be JSON encoded
	 * The operation is the opposite to FromJSONToValue	 
	 */	 	
	public function GetForJSON($value)
	{
		return $value;
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$aRawValues = parent::GetAllowedValues($aArgs, $sContains);
		if (is_null($aRawValues)) return null;
		$aLocalizedValues = array();
		foreach ($aRawValues as $sKey => $sValue)
		{
			$aLocalizedValues[$sKey] = Str::pure2html($this->GetValueLabel($sKey));
		}
  		return $aLocalizedValues;
  	}
  	
	/**
	 * An enum can be localized
	 */	 	
	public function MakeValueFromString($sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null, $sAttributeQualifier = null)
	{
		if ($bLocalizedValue)
		{
			// Lookup for the value matching the input
			//
			$sFoundValue = null;
			$aRawValues = parent::GetAllowedValues();
			if (!is_null($aRawValues))
			{
				foreach ($aRawValues as $sKey => $sValue)
				{
					$sRefValue = $this->GetValueLabel($sKey);
					if ($sProposedValue == $sRefValue)
					{
						$sFoundValue = $sKey;
						break;
					}
				}
			}
			if (is_null($sFoundValue))
			{
				return null;
			}
	  		return $this->MakeRealValue($sFoundValue, null);
		}
		else
		{
			return parent::MakeValueFromString($sProposedValue, $bLocalizedValue, $sSepItem, $sSepAttribute, $sSepValue, $sAttributeQualifier);
		}
	}

  	/**
  	 * Processes the input value to align it with the values supported
  	 * by this type of attribute. In this case: turns empty strings into nulls
  	 * @param mixed $proposedValue The value to be set for the attribute
  	 * @return mixed The actual value that will be set
  	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if ($proposedValue == '') return null;
		return parent::MakeRealValue($proposedValue, $oHostObj);
	}
	
	public function GetOrderByHint()
	{
		$aValues = $this->GetAllowedValues();
	
		return Dict::Format('UI:OrderByHint_Values', implode(', ', $aValues));
	}
}

/**
 * Map a date+time column to an attribute 
 *
 * @package     iTopORM
 */
class AttributeDateTime extends AttributeDBField
{
	static public function GetDateFormat()
	{
		return "Y-m-d H:i:s";
	}

	static public function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass() {return "DateTime";}

	protected function GetSQLCol() {return "DATETIME";}
	public static function GetAsUnixSeconds($value)
	{
		$oDeadlineDateTime = new DateTime($value);
		$iUnixSeconds = $oDeadlineDateTime->format('U');
		return $iUnixSeconds;
	}

	// This has been done at the time when itop was using TIMESTAMP columns,
	// now that iTop is using DATETIME columns, it seems possible to have IsNullAllowed returning false... later when this is needed
	public function IsNullAllowed() {return true;}
	public function GetDefaultValue()
	{
		$default = parent::GetDefaultValue();

		if (!parent::IsNullAllowed())
		{
			if (empty($default))
			{
				$default = date($this->GetDateFormat());
			}
		}

		return $default;
	}
	// END OF THE WORKAROUND
	///////////////////////////////////////////////////////////////

	public function GetValidationPattern()
	{
		return "^(([0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30))))( (0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])(:([0-5][0-9])){0,1}){0,1}|0000-00-00 00:00:00|0000-00-00)$";
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
	
	public function MakeRealValue($proposedValue, $oHostObj)
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

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2html($value);
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2xml($value);
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);
		return $sTextQualifier.$sEscaped.$sTextQualifier;
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
 * Store a duration as a number of seconds 
 *
 * @package     iTopORM
 */
class AttributeDuration extends AttributeInteger
{
	public function GetEditClass() {return "Duration";}
	protected function GetSQLCol() {return "INT(11) UNSIGNED";}

	public function GetNullValue() {return '0';}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) return null;
		if (!is_numeric($proposedValue)) return null;
		if ( ((int)$proposedValue) < 0) return null;

		return (int)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		if (is_null($value))
		{	
			return null;
		}
		return $value;
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2html(self::FormatDuration($value));
	}

	public static function FormatDuration($duration)
	{
		$aDuration = self::SplitDuration($duration);
		$sResult = '';
		
		if ($duration < 60)
		{
			// Less than 1 min
			$sResult = Dict::Format('Core:Duration_Seconds', $aDuration['seconds']);			
		}
		else if ($duration < 3600)
		{
			// less than 1 hour, display it in minutes/seconds
			$sResult = Dict::Format('Core:Duration_Minutes_Seconds', $aDuration['minutes'], $aDuration['seconds']);			
		}
		else if ($duration < 86400)
		{
			// Less than 1 day, display it in hours/minutes/seconds	
			$sResult = Dict::Format('Core:Duration_Hours_Minutes_Seconds', $aDuration['hours'], $aDuration['minutes'], $aDuration['seconds']);			
		}
		else
		{
			// more than 1 day, display it in days/hours/minutes/seconds
			$sResult = Dict::Format('Core:Duration_Days_Hours_Minutes_Seconds', $aDuration['days'], $aDuration['hours'], $aDuration['minutes'], $aDuration['seconds']);			
		}
		return $sResult;
	}
	
	static function SplitDuration($duration)
	{
		$duration = (int) $duration;
		$days = floor($duration / 86400);
		$hours = floor(($duration - (86400*$days)) / 3600);
		$minutes = floor(($duration - (86400*$days + 3600*$hours)) / 60);
		$seconds = ($duration % 60); // modulo
		return array( 'days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds );		
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

	static public function GetDateFormat()
	{
		return "Y-m-d";
	}

	static public function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass() {return "Date";}
	protected function GetSQLCol() {return "DATE";}

	public function GetValidationPattern()
	{
		return "^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$";
	}
}

/**
 * A dead line stored as a date & time
 * The only difference with the DateTime attribute is the display:
 * relative to the current time
 */
class AttributeDeadline extends AttributeDateTime
{
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		$sResult = self::FormatDeadline($value);
		return $sResult;
	}

	public static function FormatDeadline($value)
	{
		$sResult = '';
		if ($value !== null)
		{
			$iValue = AttributeDateTime::GetAsUnixSeconds($value);
			$sDate = $value;
			$difference = $iValue - time();
	
			if ($difference >= 0)
			{
				$sDifference = self::FormatDuration($difference);
			}
			else
			{
				$sDifference = Dict::Format('UI:DeadlineMissedBy_duration', self::FormatDuration(-$difference));
			}
			$sFormat = MetaModel::GetConfig()->Get('deadline_format', '$difference$');
			$sResult = str_replace(array('$date$', '$difference$'), array($sDate, $sDifference), $sFormat);
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
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("targetclass", "is_null_allowed", "on_target_delete"));
	}

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
	public function GetDisplayStyle() { return $this->GetOptional('display_style', 'select'); }
	

	public function GetDefaultValue() {return 0;}
	public function IsNullAllowed()
	{
		if (MetaModel::GetConfig()->Get('disable_mandatory_ext_keys'))
		{
			return true;
		}
		return $this->Get("is_null_allowed");
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

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		//throw new Exception("GetAllowedValues on ext key has been deprecated");
		try
		{
			return parent::GetAllowedValues($aArgs, $sContains);
		}
		catch (MissingQueryArgument $e)
		{
			// Some required arguments could not be found, enlarge to any existing value
			$oValSetDef = new ValueSetObjects('SELECT '.$this->GetTargetClass());
			return $oValSetDef->GetValues($aArgs, $sContains);
		}
	}

	public function GetAllowedValuesAsObjectSet($aArgs = array(), $sContains = '')
	{
		$oValSetDef = $this->GetValuesDef();
		$oSet = $oValSetDef->ToObjectSet($aArgs, $sContains);
		return $oSet;
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

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) return 0;
		if ($proposedValue === '') return 0;
		if (MetaModel::IsValidObject($proposedValue)) return $proposedValue->GetKey();
		return (int)$proposedValue;
	}
	
	public function GetMaximumComboLength()
	{
		return $this->GetOptional('max_combo_length', MetaModel::GetConfig()->Get('max_combo_length'));
	}
	
	public function GetMinAutoCompleteChars()
	{
		return $this->GetOptional('min_autocomplete_chars', MetaModel::GetConfig()->Get('min_autocomplete_chars'));
	}
	
	public function AllowTargetCreation()
	{
		return $this->GetOptional('allow_target_creation', MetaModel::GetConfig()->Get('allow_target_creation'));
	}
}

/**
 * Special kind of External Key to manage a hierarchy of objects
 */
class AttributeHierarchicalKey extends AttributeExternalKey
{
	protected $m_sTargetClass;

	static public function ListExpectedParams()
	{
		$aParams = parent::ListExpectedParams();
		$idx = array_search('targetclass', $aParams);
		unset($aParams[$idx]);
		$idx = array_search('jointype', $aParams);
		unset($aParams[$idx]);
		return $aParams; // TODO: mettre les bons parametres ici !!
	}

	public function GetEditClass() {return "ExtKey";}
	public function RequiresIndex()
	{
		return true;
	}

	/*
	*  The target class is the class for which the attribute has been defined first
	*/	
	public function SetHostClass($sHostClass)
	{
		if (!isset($this->m_sTargetClass))
		{
			$this->m_sTargetClass = $sHostClass;
		}
		parent::SetHostClass($sHostClass);
	}

	public function IsHierarchicalKey() {return true;}
	public function GetTargetClass($iType = EXTKEY_RELATIVE) {return $this->m_sTargetClass;}
	public function GetKeyAttDef($iType = EXTKEY_RELATIVE){return $this;}
	public function GetKeyAttCode() {return $this->GetCode();}

	public function GetBasicFilterOperators()
	{
		return parent::GetBasicFilterOperators();
	}
	public function GetBasicFilterLooseOperator()
	{
		return parent::GetBasicFilterLooseOperator();
	}

	public function GetSQLColumns()
	{
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'INT(11)';
		$aColumns[$this->GetSQLLeft()] = 'INT(11)';
		$aColumns[$this->GetSQLRight()] = 'INT(11)';
		return $aColumns;
	}
	public function GetSQLRight()
	{
		return $this->GetCode().'_right';
	}
	public function GetSQLLeft()
	{
		return $this->GetCode().'_left';
	}

	public function GetSQLValues($value)
	{
		if (!is_array($value))
		{
			$aValues[$this->GetCode()] = $value;
		}
		else
		{
			$aValues = array();
			$aValues[$this->GetCode()] = $value[$this->GetCode()];
			$aValues[$this->GetSQLRight()] = $value[$this->GetSQLRight()];
			$aValues[$this->GetSQLLeft()] = $value[$this->GetSQLLeft()];
		}
		return $aValues;
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		if (array_key_exists('this', $aArgs))
		{
			// Hierarchical keys have one more constraint: the "parent value" cannot be
			// "under" themselves
			$iRootId = $aArgs['this']->GetKey();
			if ($iRootId > 0) // ignore objects that do no exist in the database...
			{
				$oValSetDef = $this->GetValuesDef();
				$sClass = $this->m_sTargetClass;
				$oFilter = DBObjectSearch::FromOQL("SELECT $sClass AS node JOIN $sClass AS root ON node.".$this->GetCode()." NOT BELOW root.id WHERE root.id = $iRootId");
				$oValSetDef->AddCondition($oFilter);
			}
		}
		else
		{
			return parent::GetAllowedValues($aArgs, $sContains);
		}
	}

	public function GetAllowedValuesAsObjectSet($aArgs = array(), $sContains = '')
	{
		$oValSetDef = $this->GetValuesDef();
		if (array_key_exists('this', $aArgs))
		{
			// Hierarchical keys have one more constraint: the "parent value" cannot be
			// "under" themselves
			$iRootId = $aArgs['this']->GetKey();
			if ($iRootId > 0) // ignore objects that do no exist in the database...
			{
				$aValuesSetDef = $this->GetValuesDef();
				$sClass = $this->m_sTargetClass;
				$oFilter = DBObjectSearch::FromOQL("SELECT $sClass AS node JOIN $sClass AS root ON node.".$this->GetCode()." NOT BELOW root.id WHERE root.id = $iRootId");
				$oValSetDef->AddCondition($oFilter);
			}
		}
		$oSet = $oValSetDef->ToObjectSet($aArgs, $sContains);
		return $oSet;
	}
}

/**
 * An attribute which corresponds to an external key (direct or indirect) 
 *
 * @package     iTopORM
 */
class AttributeExternalField extends AttributeDefinition
{
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("extkey_attcode", "target_attcode"));
	}

	public function GetEditClass() {return "ExtField";}

	public function GetFinalAttDef()
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetFinalAttDef(); 
	}

	protected function GetSQLCol()
	{
		// throw new CoreException("external attribute: does it make any sense to request its type ?");  
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetSQLCol(); 
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			return array('' => $this->GetCode());
		}
		else
		{
			return $sPrefix;
		} 
	}

	public function GetLabel($sDefault = null)
	{
		$sLabel = parent::GetLabel('');
		if (strlen($sLabel) == 0)
		{
			$oRemoteAtt = $this->GetExtAttDef();
			$sLabel = $oRemoteAtt->GetLabel($this->m_sCode);
		}
		return $sLabel;
	}
	public function GetDescription($sDefault = null)
	{
		$sLabel = parent::GetDescription('');
		if (strlen($sLabel) == 0)
		{
			$oRemoteAtt = $this->GetExtAttDef();
			$sLabel = $oRemoteAtt->GetDescription('');
		}
		return $sLabel;
	} 
	public function GetHelpOnEdition($sDefault = null)
	{
		$sLabel = parent::GetHelpOnEdition('');
		if (strlen($sLabel) == 0)
		{
			$oRemoteAtt = $this->GetExtAttDef();
			$sLabel = $oRemoteAtt->GetHelpOnEdition('');
		}
		return $sLabel;
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
	
	public function GetPrerequisiteAttributes()
	{
		return array($this->Get("extkey_attcode"));
	} 
	

	public function GetExtAttDef()
	{
		$oKeyAttDef = $this->GetKeyAttDef();
		$oExtAttDef = MetaModel::GetAttributeDef($oKeyAttDef->GetTargetClass(), $this->Get("target_attcode"));
		if (!is_object($oExtAttDef)) throw new CoreException("Invalid external field ".$this->GetCode()." in class ".$this->GetHostClass().". The class ".$oKeyAttDef->GetTargetClass()." has no attribute ".$this->Get("target_attcode"));
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

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->MakeRealValue($proposedValue, $oHostObj);
	}

	public function ScalarToSQL($value)
	{
		// This one could be used in case of filtering only
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->ScalarToSQL($value);
	}


	// Do not overload GetSQLExpression here because this is handled in the joins
	//public function GetSQLExpressions($sPrefix = '') {return array();}

	// Here, we get the data...
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->FromSQLToValue($aCols, $sPrefix);
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetAsHTML($value, null, $bLocalize);
	}
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetAsXML($value, null, $bLocalize);
	}
	public function GetAsCSV($value, $sSeparator = ',', $sTestQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		$oExtAttDef = $this->GetExtAttDef();
		return $oExtAttDef->GetAsCSV($value, $sSeparator, $sTestQualifier, null, $bLocalize);
	}
}

/**
 * Map a varchar column to an URL (formats the ouput in HMTL) 
 *
 * @package     iTopORM
 */
class AttributeURL extends AttributeString
{
	static public function ListExpectedParams()
	{
		//return parent::ListExpectedParams();
		return array_merge(parent::ListExpectedParams(), array("target"));
	}

	public function GetEditClass() {return "String";}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$sTarget = $this->Get("target");
		if (empty($sTarget)) $sTarget = "_blank";
		$sLabel = Str::pure2html($sValue);
		if (strlen($sLabel) > 255)
		{
			// Truncate the length to 128 characters, by removing the middle
			$sLabel = substr($sLabel, 0, 100).'.....'.substr($sLabel, -20);
		}
		return "<a target=\"$sTarget\" href=\"$sValue\">$sLabel</a>";
	}

	public function GetValidationPattern()
	{
		return $this->GetOptional('validation_pattern', '^'.utils::GetConfig()->Get('url_validation_pattern').'$');
	}
}

/**
 * A blob is an ormDocument, it is stored as several columns in the database  
 *
 * @package     iTopORM
 */
class AttributeBlob extends AttributeDefinition
{
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("depends_on"));
	}

	public function GetEditClass() {return "Document";}
	
	public function IsDirectField() {return true;} 
	public function IsScalar() {return true;} 
	public function IsWritable() {return true;} 
	public function GetDefaultValue() {return "";}
	public function IsNullAllowed() {return $this->GetOptional("is_null_allowed", false);}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		return '';
	}


	// Facilitate things: allow administrators to upload a document
	// from a CSV by specifying its path/URL
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (!is_object($proposedValue))
		{
			if (file_exists($proposedValue) && UserRights::IsAdministrator())
			{
				$sContent = file_get_contents($proposedValue);
				$sExtension = strtolower(pathinfo($proposedValue, PATHINFO_EXTENSION));
				$sMimeType = "application/x-octoet-stream";
				$aKnownExtensions = array(
						'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
						'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
						'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
						'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
						'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
						'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
						'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
						'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
						'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
						'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12.xlsx',
						'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
						'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
						'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
						'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
						'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
						'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
						'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
						'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
						'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
						'jpg' => 'image/jpeg',
						'jpeg' => 'image/jpeg',
						'gif' => 'image/gif',
						'png' => 'image/png',
						'pdf' => 'application/pdf',
						'doc' => 'application/msword',
						'dot' => 'application/msword',
						'xls' => 'application/vnd.ms-excel',
						'ppt' => 'application/vnd.ms-powerpoint',
						'vsd' => 'application/x-visio',
						'vdx' => 'application/visio.drawing', 
						'odt' => 'application/vnd.oasis.opendocument.text',
						'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
						'odp' => 'application/vnd.oasis.opendocument.presentation',
						'zip' => 'application/zip',
						'txt' => 'text/plain',
						'htm' => 'text/html',
						'html' => 'text/html', 
						'exe' => 'application/octet-stream'
					);

				if (!array_key_exists($sExtension, $aKnownExtensions) && extension_loaded('fileinfo'))
				{
					$finfo = new finfo(FILEINFO_MIME);
					$sMimeType = $finfo->file($proposedValue);
				}
				return new ormDocument($sContent, $sMimeType);			
			}
			else
			{
				return new ormDocument($proposedValue, 'text/plain');
			}
		}
		return $proposedValue;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			$sPrefix = $this->GetCode();
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix.'_mimetype';
		$aColumns['_data'] = $sPrefix.'_data';
		$aColumns['_filename'] = $sPrefix.'_filename';
		return $aColumns;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		if (!array_key_exists($sPrefix, $aCols))
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		} 
		$sMimeType = isset($aCols[$sPrefix]) ? $aCols[$sPrefix] : '';

		if (!array_key_exists($sPrefix.'_data', $aCols)) 
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_data' from {$sAvailable}");
		} 
		$data = isset($aCols[$sPrefix.'_data']) ? $aCols[$sPrefix.'_data'] : null;

		if (!array_key_exists($sPrefix.'_filename', $aCols)) 
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_filename' from {$sAvailable}");
		} 
		$sFileName =  isset($aCols[$sPrefix.'_filename']) ? $aCols[$sPrefix.'_filename'] : '';

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

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value))
		{
			return $value->GetAsHTML();
		}
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		return ''; // Not exportable in CSV !
	}
	
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return ''; // Not exportable in XML, or as CDATA + some subtags ??
	}

	/**
	 * Helper to get a value that will be JSON encoded
	 * The operation is the opposite to FromJSONToValue	 
	 */	 	
	public function GetForJSON($value)
	{
		if ($value instanceOf ormDocument)
		{
			$aValues = array();
			$aValues['data'] = base64_encode($value->GetData());
			$aValues['mimetype'] = $value->GetMimeType();
			$aValues['filename'] = $value->GetFileName();
		}
		else
		{
			$aValues = null;
		}
		return $aValues;
	}

	/**
	 * Helper to form a value, given JSON decoded data
	 * The operation is the opposite to GetForJSON	 
	 */	 	
	public function FromJSONToValue($json)
	{
		if (isset($json->data))
		{
			$data = base64_decode($json->data);
			$value = new ormDocument($data, $json->mimetype, $json->filename);
		}
		else
		{
			$value = null;
		}
		return $value;
	}
}

/**
 * A stop watch is an ormStopWatch object, it is stored as several columns in the database  
 *
 * @package     iTopORM
 */
class AttributeStopWatch extends AttributeDefinition
{
	static public function ListExpectedParams()
	{
		// The list of thresholds must be an array of iPercent => array of 'option' => value
		return array_merge(parent::ListExpectedParams(), array("states", "goal_computing", "working_time_computing", "thresholds"));
	}

	public function GetEditClass() {return "StopWatch";}
	
	public function IsDirectField() {return true;} 
	public function IsScalar() {return true;} 
	public function IsWritable() {return false;} 
	public function GetDefaultValue() {return $this->NewStopWatch();}

	public function GetEditValue($value, $oHostObj = null)
	{
		return $value->GetTimeSpent();
	}

	public function GetStates()
	{
		return $this->Get('states');
	}

	/**
	 * Construct a brand new (but configured) stop watch
	 */	 	
	public function NewStopWatch()
	{
		$oSW = new ormStopWatch();
		foreach ($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$oSW->DefineThreshold($iThreshold);
		}
		return $oSW;
	}

	// Facilitate things: allow the user to Set the value from a string
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (!$proposedValue instanceof ormStopWatch)
		{
			return $this->NewStopWatch();
		}
		return $proposedValue;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			$sPrefix = $this->GetCode();
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix.'_timespent';
		$aColumns['_started'] = $sPrefix.'_started';
		$aColumns['_laststart'] = $sPrefix.'_laststart';
		$aColumns['_stopped'] = $sPrefix.'_stopped';
		foreach ($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$sThPrefix = '_'.$iThreshold;
			$aColumns[$sThPrefix.'_deadline'] = $sPrefix.$sThPrefix.'_deadline';
			$aColumns[$sThPrefix.'_passed'] = $sPrefix.$sThPrefix.'_passed';
			$aColumns[$sThPrefix.'_triggered'] = $sPrefix.$sThPrefix.'_triggered';
			$aColumns[$sThPrefix.'_overrun'] = $sPrefix.$sThPrefix.'_overrun';
		}
		return $aColumns;
	}

	public static function DateToSeconds($sDate)
	{
		if (is_null($sDate))
		{
			return null;
		}
		$oDateTime = new DateTime($sDate);
		$iSeconds = $oDateTime->format('U');
		return $iSeconds;
	}

	public static function SecondsToDate($iSeconds)
	{
		if (is_null($iSeconds))
		{
			return null;
		}
		return date("Y-m-d H:i:s", $iSeconds);
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$aExpectedCols = array($sPrefix, $sPrefix.'_started', $sPrefix.'_laststart', $sPrefix.'_stopped');
		foreach ($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$sThPrefix = '_'.$iThreshold;
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_deadline';
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_passed';
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_triggered';
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_overrun';
		}
		foreach ($aExpectedCols as $sExpectedCol)
		{
			if (!array_key_exists($sExpectedCol, $aCols))
			{
				$sAvailable = implode(', ', array_keys($aCols));
				throw new MissingColumnException("Missing column '$sExpectedCol' from {$sAvailable}");
			} 
		}

		$value = new ormStopWatch(
			$aCols[$sPrefix],
			self::DateToSeconds($aCols[$sPrefix.'_started']),
			self::DateToSeconds($aCols[$sPrefix.'_laststart']),
			self::DateToSeconds($aCols[$sPrefix.'_stopped'])
		);

		$aThresholds = array();
		foreach ($this->ListThresholds() as $iThreshold => $aDefinition)
		{
			$sThPrefix = '_'.$iThreshold;
			$value->DefineThreshold(
				$iThreshold,
				self::DateToSeconds($aCols[$sPrefix.$sThPrefix.'_deadline']),
				(bool)($aCols[$sPrefix.$sThPrefix.'_passed'] == 1),
				(bool)($aCols[$sPrefix.$sThPrefix.'_triggered'] == 1),
				$aCols[$sPrefix.$sThPrefix.'_overrun'],
				array_key_exists('highlight', $aDefinition) ? $aDefinition['highlight'] : null
			);
		}

		return $value;
	}

	public function GetSQLValues($value)
	{
		if ($value instanceOf ormStopWatch)
		{
			$aValues = array();
			$aValues[$this->GetCode().'_timespent'] = $value->GetTimeSpent();
			$aValues[$this->GetCode().'_started'] = self::SecondsToDate($value->GetStartDate());
			$aValues[$this->GetCode().'_laststart'] = self::SecondsToDate($value->GetLastStartDate());
			$aValues[$this->GetCode().'_stopped'] = self::SecondsToDate($value->GetStopDate());

			foreach ($this->ListThresholds() as $iThreshold => $aFoo)
			{
				$sPrefix = $this->GetCode().'_'.$iThreshold;
				$aValues[$sPrefix.'_deadline'] = self::SecondsToDate($value->GetThresholdDate($iThreshold));
				$aValues[$sPrefix.'_passed'] = $value->IsThresholdPassed($iThreshold) ? '1' : '0';
				$aValues[$sPrefix.'_triggered'] = $value->IsThresholdTriggered($iThreshold) ? '1' : '0';
				$aValues[$sPrefix.'_overrun'] = $value->GetOverrun($iThreshold);
			}
		}
		else
		{
			$aValues = array();
			$aValues[$this->GetCode().'_timespent'] = '';
			$aValues[$this->GetCode().'_started'] = '';
			$aValues[$this->GetCode().'_laststart'] = '';
			$aValues[$this->GetCode().'_stopped'] = '';
		}
		return $aValues;
	}

	public function GetSQLColumns()
	{
		$aColumns = array();
		$aColumns[$this->GetCode().'_timespent'] = 'INT(11) UNSIGNED';
		$aColumns[$this->GetCode().'_started'] = 'DATETIME';
		$aColumns[$this->GetCode().'_laststart'] = 'DATETIME';
		$aColumns[$this->GetCode().'_stopped'] = 'DATETIME';
		foreach ($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$sPrefix = $this->GetCode().'_'.$iThreshold;
			$aColumns[$sPrefix.'_deadline'] = 'DATETIME';
			$aColumns[$sPrefix.'_passed'] = 'TINYINT(1) UNSIGNED';
			$aColumns[$sPrefix.'_triggered'] = 'TINYINT(1)';
			$aColumns[$sPrefix.'_overrun'] = 'INT(11) UNSIGNED';
		}
		return $aColumns;
	}

	public function GetFilterDefinitions()
	{
		$aRes = array(
			$this->GetCode() => new FilterFromAttribute($this),
			$this->GetCode().'_started' => new FilterFromAttribute($this, '_started'),
			$this->GetCode().'_laststart' => new FilterFromAttribute($this, '_laststart'),
			$this->GetCode().'_stopped' => new FilterFromAttribute($this, '_stopped')
		);
		foreach ($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$sPrefix = $this->GetCode().'_'.$iThreshold;
			$aRes[$sPrefix.'_deadline'] = new FilterFromAttribute($this, '_deadline');
			$aRes[$sPrefix.'_passed'] = new FilterFromAttribute($this, '_passed');
			$aRes[$sPrefix.'_triggered'] = new FilterFromAttribute($this, '_triggered');
			$aRes[$sPrefix.'_overrun'] = new FilterFromAttribute($this, '_overrun');
		}
		return $aRes;
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

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value))
		{
			return $value->GetAsHTML($this, $oHostObject);
		}
	}

	public function GetAsCSV($value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		return $value->GetTimeSpent();
	}
	
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return $value->GetTimeSpent();
	}

	public function ListThresholds()
	{
		return $this->Get('thresholds');
	}

	/**
	 * To expose internal values: Declare an attribute AttributeSubItem
	 * and implement the GetSubItemXXXX verbs
	 */	 	
	public function GetSubItemSQLExpression($sItemCode)
	{
		$sPrefix = $this->GetCode();
		switch($sItemCode)
		{
		case 'timespent':
			return array('' => $sPrefix.'_timespent');
		case 'started':
			return array('' => $sPrefix.'_started');
		case 'laststart':
			return array('' => $sPrefix.'_laststart');
		case 'stopped':
			return array('' => $sPrefix.'_stopped');
		}

		foreach ($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$sThPrefix = $iThreshold.'_';
			if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
			{
				// The current threshold is concerned
				$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
				switch($sThresholdCode)
				{
				case 'deadline':
					return array('' => $sPrefix.'_'.$iThreshold.'_deadline');
				case 'passed':
					return array('' => $sPrefix.'_'.$iThreshold.'_passed');
				case 'triggered':
					return array('' => $sPrefix.'_'.$iThreshold.'_triggered');
				case 'overrun':
					return array('' => $sPrefix.'_'.$iThreshold.'_overrun');
				}
			}
		}
		throw new CoreException("Unknown item code '$sItemCode' for attribute ".$this->GetHostClass().'::'.$this->GetCode());
	}

	public function GetSubItemValue($sItemCode, $value, $oHostObject = null)
	{
		$oStopWatch = $value;
		switch($sItemCode)
		{
		case 'timespent':
			return $oStopWatch->GetTimeSpent();
		case 'started':
			return $oStopWatch->GetStartDate();
		case 'laststart':
			return $oStopWatch->GetLastStartDate();
		case 'stopped':
			return $oStopWatch->GetStopDate();
		}

		foreach ($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$sThPrefix = $iThreshold.'_';
			if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
			{
				// The current threshold is concerned
				$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
				switch($sThresholdCode)
				{
				case 'deadline':
					return $oStopWatch->GetThresholdDate($iThreshold);
				case 'passed':
					return $oStopWatch->IsThresholdPassed($iThreshold);
				case 'triggered':
					return $oStopWatch->IsThresholdTriggered($iThreshold);
				case 'overrun':
					return $oStopWatch->GetOverrun($iThreshold);
				}
			}
		}

		throw new CoreException("Unknown item code '$sItemCode' for attribute ".$this->GetHostClass().'::'.$this->GetCode());
	}

	protected function GetBooleanLabel($bValue)
	{
		$sDictKey = $bValue ? 'yes' : 'no';
		return Dict::S('BooleanLabel:'.$sDictKey, 'def:'.$sDictKey);
	}

	public function GetSubItemAsHTMLForHistory($sItemCode, $sValue)
	{
		switch($sItemCode)
		{
		case 'timespent':
			$sHtml = (int)$sValue ? Str::pure2html(AttributeDuration::FormatDuration($sValue)) : null;
			break;
		case 'started':
		case 'laststart':
		case 'stopped':
			$sHtml = (int)$sValue ? date(self::GetDateFormat(), (int)$sValue) : null;
			break;

		default:
			foreach ($this->ListThresholds() as $iThreshold => $aFoo)
			{
				$sThPrefix = $iThreshold.'_';
				if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
				{
					// The current threshold is concerned
					$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
					switch($sThresholdCode)
					{
					case 'deadline':
						$sHtml = (int)$sValue ? date(self::GetDateFormat(true /*full*/), (int)$sValue) : null;
						break;
					case 'passed':
						$sHtml = $this->GetBooleanLabel((int)$sValue);
						break;
					case 'triggered':
						$sHtml = $this->GetBooleanLabel((int)$sValue);
						break;
					case 'overrun':
						$sHtml = (int)$sValue > 0 ? Str::pure2html(AttributeDuration::FormatDuration((int)$sValue)) : '';
					}
				}
			}
		}
		return $sHtml;
	}

	static protected function GetDateFormat($bFull = false)
	{
		if ($bFull)
		{
			return "Y-m-d H:i:s";
		}
		else
		{
			return "Y-m-d H:i";
		}
	}

	public function GetSubItemAsHTML($sItemCode, $value)
	{
		$sHtml = $value;

		switch($sItemCode)
		{
		case 'timespent':
			$sHtml = Str::pure2html(AttributeDuration::FormatDuration($value));
			break;
		case 'started':
		case 'laststart':
		case 'stopped':
			if (is_null($value))
			{
				$sHtml = ''; // Undefined
			}
			else
			{
				$sHtml = date(self::GetDateFormat(), $value);
			}
			break;

		default:
			foreach ($this->ListThresholds() as $iThreshold => $aFoo)
			{
				$sThPrefix = $iThreshold.'_';
				if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
				{
					// The current threshold is concerned
					$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
					switch($sThresholdCode)
					{
					case 'deadline':
						if ($value)
						{
							$sDate = date(self::GetDateFormat(true /*full*/), $value);
							$sHtml = Str::pure2html(AttributeDeadline::FormatDeadline($sDate));
						}
						else
						{
							$sHtml = '';
						}
						break;
					case 'passed':
					case 'triggered':
						$sHtml = $this->GetBooleanLabel($value);
						break;
					case 'overrun':
						$sHtml = Str::pure2html(AttributeDuration::FormatDuration($value));
						break;
					}
				}
			}
		}
		return $sHtml;
	}

	public function GetSubItemAsCSV($sItemCode, $value, $sSeparator = ',', $sTextQualifier = '"')
	{
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$value);
		$sRet = $sTextQualifier.$sEscaped.$sTextQualifier;

		switch($sItemCode)
		{
		case 'timespent':
		case 'started':
		case 'laststart':
		case 'stopped':
			break;

		default:
			foreach ($this->ListThresholds() as $iThreshold => $aFoo)
			{
				$sThPrefix = $iThreshold.'_';
				if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
				{
					// The current threshold is concerned
					$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
					switch($sThresholdCode)
					{
					case 'deadline':
						break;

					case 'passed':
					case 'triggered':
						$sRet = $sTextQualifier.$this->GetBooleanLabel($value).$sTextQualifier;
						break;

					case 'overrun':
						break;
					}
				}
			}
		}
		return $sRet;
	}

	public function GetSubItemAsXML($sItemCode, $value)
	{
		$sRet = Str::pure2xml((string)$value);

		switch($sItemCode)
		{
		case 'timespent':
		case 'started':
		case 'laststart':
		case 'stopped':
			break;

		default:
			foreach ($this->ListThresholds() as $iThreshold => $aFoo)
			{
				$sThPrefix = $iThreshold.'_';
				if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
				{
					// The current threshold is concerned
					$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
					switch($sThresholdCode)
					{
					case 'deadline':
						break;

					case 'passed':
					case 'triggered':
						$sRet = $this->GetBooleanLabel($value);
						break;

					case 'overrun':
						break;
					}
				}
			}
		}
		return $sRet;
	}

	/**
	 * Implemented for the HTML spreadsheet format!	
	 */	
	public function GetSubItemAsEditValue($sItemCode, $value)
	{
		$sRet = $value;

		switch($sItemCode)
		{
		case 'timespent':
			break;

		case 'started':
		case 'laststart':
		case 'stopped':
			if (is_null($value))
			{
				$sRet = ''; // Undefined
			}
			else
			{
				$sRet = date(self::GetDateFormat(), $value);
			}
			break;

		default:
			foreach ($this->ListThresholds() as $iThreshold => $aFoo)
			{
				$sThPrefix = $iThreshold.'_';
				if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
				{
					// The current threshold is concerned
					$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
					switch($sThresholdCode)
					{
					case 'deadline':
						if ($value)
						{
							$sRet = date(self::GetDateFormat(true /*full*/), $value);
						}
						else
						{
							$sRet = '';
						}
						break;
					case 'passed':
					case 'triggered':
						$sRet = $this->GetBooleanLabel($value);
						break;
					case 'overrun':
						break;
					}
				}
			}
		}
		return $sRet;
	}
}

/**
 * View of a subvalue of another attribute
 * If an attribute implements the verbs GetSubItem.... then it can expose
 * internal values, each of them being an attribute and therefore they
 * can be displayed at different times in the object lifecycle, and used for
 * reporting (as a condition in OQL, or as an additional column in an export)  
 * Known usages: Stop Watches can expose threshold statuses    
 */
class AttributeSubItem extends AttributeDefinition
{
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('target_attcode', 'item_code'));
	}

	public function GetParentAttCode() {return $this->Get("target_attcode");} 

	/**
	 * Helper : get the attribute definition to which the execution will be forwarded	
	 */	
	public function GetTargetAttDef()
	{
		$sClass = $this->GetHostClass();
		$oParentAttDef = MetaModel::GetAttributeDef($sClass, $this->Get('target_attcode'));
		return $oParentAttDef;
	}

	public function GetEditClass() {return "";}
	
	public function GetValuesDef() {return null;} 
	//public function GetPrerequisiteAttributes() {return $this->Get("depends_on");} 

	public function IsDirectField() {return true;} 
	public function IsScalar() {return true;} 
	public function IsWritable() {return false;} 
	public function GetDefaultValue() {return null;}
//	public function IsNullAllowed() {return false;}
	public function LoadInObject() {return false;} // if this verb returns true, then GetValue must be implemented

	// 
//	protected function ScalarToSQL($value) {return $value;} // format value as a valuable SQL literal (quoted outside)

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
	}

	public function GetSQLColumns()
	{
		return array();
	}

	public function GetFilterDefinitions()
	{
		return array($this->GetCode() => new FilterFromAttribute($this));
	}

	public function GetBasicFilterOperators()
	{
		return array();
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

	public function GetSQLExpressions($sPrefix = '')
	{
		$oParent = $this->GetTargetAttDef();
		$res = $oParent->GetSubItemSQLExpression($this->Get('item_code'));
		return $res;
	}

	/**
	 * Used by DBOBject::Get()	
	 */	
	public function GetValue($parentValue, $oHostObject = null)
	{
		$oParent = $this->GetTargetAttDef();
		$res = $oParent->GetSubItemValue($this->Get('item_code'), $parentValue, $oHostObject);
		return $res;
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		$oParent = $this->GetTargetAttDef();
		$res = $oParent->GetSubItemAsHTML($this->Get('item_code'), $value);
		return $res;
	}

	public function GetAsHTMLForHistory($value, $oHostObject = null, $bLocalize = true)
	{
		$oParent = $this->GetTargetAttDef();
		$res = $oParent->GetSubItemAsHTMLForHistory($this->Get('item_code'), $value);
		return $res;
	}

	public function GetAsCSV($value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		$oParent = $this->GetTargetAttDef();
		$res = $oParent->GetSubItemAsCSV($this->Get('item_code'), $value, $sSeparator, $sTextQualifier);
		return $res;
	}
	
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		$oParent = $this->GetTargetAttDef();
		$res = $oParent->GetSubItemAsXML($this->Get('item_code'), $value);
		return $res;
	}

	/**
	 * As of now, this function must be implemented to have the value in spreadsheet format
	 */	 	
	public function GetEditValue($value, $oHostObj = null)
	{
		$oParent = $this->GetTargetAttDef();
		$res = $oParent->GetSubItemAsEditValue($this->Get('item_code'), $value);
		return $res;
	}
}

/**
 * One way encrypted (hashed) password
 */
class AttributeOneWayPassword extends AttributeDefinition
{
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("depends_on"));
	}

	public function GetEditClass() {return "One Way Password";}
	
	public function IsDirectField() {return true;} 
	public function IsScalar() {return true;} 
	public function IsWritable() {return true;} 
	public function GetDefaultValue() {return "";}
	public function IsNullAllowed() {return $this->GetOptional("is_null_allowed", false);}

	// Facilitate things: allow the user to Set the value from a string or from an ormPassword (already encrypted)
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		$oPassword = $proposedValue;
		if (!is_object($oPassword))
		{
			$oPassword = new ormPassword('', '');
			$oPassword->SetPassword($proposedValue);
		}
		return $oPassword;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			$sPrefix = $this->GetCode();
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix.'_hash';
		$aColumns['_salt'] = $sPrefix.'_salt';
		return $aColumns;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		if (!array_key_exists($sPrefix, $aCols))
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		} 
		$hashed = isset($aCols[$sPrefix]) ? $aCols[$sPrefix] : '';

		if (!array_key_exists($sPrefix.'_salt', $aCols)) 
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_salt' from {$sAvailable}");
		} 
		$sSalt = isset($aCols[$sPrefix.'_salt']) ? $aCols[$sPrefix.'_salt'] : '';

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

	public function GetImportColumns()
	{
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'TINYTEXT';
		return $aColumns;
	}

	public function FromImportToValue($aCols, $sPrefix = '')
	{
		if (!isset($aCols[$sPrefix]))
		{
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		} 
		$sClearPwd = $aCols[$sPrefix];

		$oPassword = new ormPassword('', '');
		$oPassword->SetPassword($sClearPwd);
		return $oPassword;
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

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value))
		{
			return $value->GetAsHTML();
		}
	}

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		return ''; // Not exportable in CSV
	}
	
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return ''; // Not exportable in XML
	}
}

// Indexed array having two dimensions
class AttributeTable extends AttributeDBField
{
	public function GetEditClass() {return "Table";}
	protected function GetSQLCol() {return "LONGTEXT";}

	public function GetMaxSize()
	{
		return null;
	}

	public function GetNullValue()
	{
		return array();
	} 

	public function IsNull($proposedValue)
	{
		return (count($proposedValue) == 0);
	} 

	public function GetEditValue($sValue, $oHostObj = null)
	{
		return '';
	}

	// Facilitate things: allow the user to Set the value from a string
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue))
		{
			return array();
		}
		else if (!is_array($proposedValue))
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
				$value = $this->MakeRealValue($aCols[$sPrefix.''], null);
			}
		}
		catch(Exception $e)
		{
			$value = $this->MakeRealValue($aCols[$sPrefix.''], null);
		}

		return $value;
	}

	public function GetSQLValues($value)
	{
		$aValues = array();
		$aValues[$this->Get("sql")] = serialize($value);
		return $aValues;
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
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

	public function GetAsCSV($sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		// Not implemented
		return '';
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (count($value) == 0)
		{
			return "";
		}

		$sRes = "";
		foreach($value as $iRow => $aRawData)
		{
			$sRes .= "<row>";
			foreach ($aRawData as $iCol => $cell)
			{
				$sCell = Str::pure2xml((string)$cell);
				$sRes .= "<cell icol=\"$iCol\">$sCell</cell>";
			}
			$sRes .= "</row>";
		}
		return $sRes;
	}
}

// The PHP value is a hash array, it is stored as a TEXT column
class AttributePropertySet extends AttributeTable
{
	public function GetEditClass() {return "PropertySet";}

	// Facilitate things: allow the user to Set the value from a string
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (!is_array($proposedValue))
		{
			return array('?' => (string)$proposedValue);
		}
		return $proposedValue;
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
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
			if ($sProperty == 'auth_pwd')
			{
				$sValue = '*****';
			}
			$sRes .= "<TR>";
			$sCell = str_replace("\n", "<br>\n", Str::pure2html((string)$sValue));
			$sRes .= "<TD class=\"label\">$sProperty</TD><TD>$sCell</TD>";
			$sRes .= "</TR>";
		}
		$sRes .= "</TBODY>";
		$sRes .= "</TABLE>";
		return $sRes;
	}

	public function GetAsCSV($value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true)
	{
		if (count($value) == 0)
		{
			return "";
		}

		$aRes = array();
		foreach($value as $sProperty => $sValue)
		{
			if ($sProperty == 'auth_pwd')
			{
				$sValue = '*****';
			}
			$sFrom = array(',', '=');
			$sTo = array('\,', '\=');
			$aRes[] = $sProperty.'='.str_replace($sFrom, $sTo, (string)$sValue);
		}
		$sRaw = implode(',', $aRes);

		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, $sRaw);
		return $sTextQualifier.$sEscaped.$sTextQualifier;
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (count($value) == 0)
		{
			return "";
		}

		$sRes = "";
		foreach($value as $sProperty => $sValue)
		{
			if ($sProperty == 'auth_pwd')
			{
				$sValue = '*****';
			}
			$sRes .= "<property id=\"$sProperty\">";
			$sRes .= Str::pure2xml((string)$sValue);
			$sRes .= "</property>";
		}
		return $sRes;
	}
}

/**
 * The attribute dedicated to the friendly name automatic attribute (not written) 
 *
 * @package     iTopORM
 */
class AttributeComputedFieldVoid extends AttributeDefinition
{	
	static public function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass() {return "";}
	
	public function GetValuesDef() {return null;} 
	public function GetPrerequisiteAttributes() {return $this->GetOptional("depends_on", array());} 

	public function IsDirectField() {return true;} 
	public function IsScalar() {return true;} 
	public function IsWritable() {return false;} 
	public function GetSQLExpr()    {return null;}
	public function GetDefaultValue() {return $this->MakeRealValue("", null);}
	public function IsNullAllowed() {return false;}

	// 
//	protected function ScalarToSQL($value) {return $value;} // format value as a valuable SQL literal (quoted outside)

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			$sPrefix = $this->GetCode();
		}
		return array('' => $sPrefix); 
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		return null;
	}
	public function GetSQLValues($value)
	{
		return array();
	}

	public function GetSQLColumns()
	{
		return array();
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
 * The attribute dedicated to the friendly name automatic attribute (not written) 
 *
 * @package     iTopORM
 */
class AttributeFriendlyName extends AttributeComputedFieldVoid
{
	public function __construct($sCode, $sExtKeyAttCode)
	{
		$this->m_sCode = $sCode;
		$aParams = array();
//		$aParams["is_null_allowed"] = false,
		$aParams["default_value"] = '';
		$aParams["extkey_attcode"] = $sExtKeyAttCode;
		parent::__construct($sCode, $aParams);

		$this->m_sValue = $this->Get("default_value");
	}

	public function GetKeyAttCode() {return $this->Get("extkey_attcode");} 

	public function GetExtAttCode() {return 'friendlyname';} 

	public function GetLabel($sDefault = null)
	{
		$sLabel = parent::GetLabel('');
		if (strlen($sLabel) == 0)
		{
			$sKeyAttCode = $this->Get("extkey_attcode");
			if ($sKeyAttCode == 'id')
			{
				return Dict::S('Core:FriendlyName-Label');
			}
			else
			{
				$oExtKeyAttDef = MetaModel::GetAttributeDef($this->GetHostClass(), $sKeyAttCode);
				$sLabel = $oExtKeyAttDef->GetLabel($this->m_sCode);
			}
		}
		return $sLabel;
	}
	public function GetDescription($sDefault = null)
	{
		$sLabel = parent::GetDescription('');
		if (strlen($sLabel) == 0)
		{
			$sKeyAttCode = $this->Get("extkey_attcode");
			if ($sKeyAttCode == 'id')
			{
				return Dict::S('Core:FriendlyName-Description');
			}
			else
			{
				$oExtKeyAttDef = MetaModel::GetAttributeDef($this->GetHostClass(), $sKeyAttCode);
				$sLabel = $oExtKeyAttDef->GetDescription('');
			}
		}
		return $sLabel;
	} 

	// n/a, the friendly name is made of a complex expression (see GetNameSpec)
	protected function GetSQLCol() {return "";}	

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
 		$sValue = $aCols[$sPrefix];
		return $sValue;
	}

	/**
	 * Encrypt the value before storing it in the database
	 */
	public function GetSQLValues($value)
	{
		return array();
	}

	public function IsWritable()
	{
		return false;
	}

	public function IsDirectField()
	{
		return false;
	}

	public function SetFixedValue($sValue)
	{
		$this->m_sValue = $sValue;
	}
	public function GetDefaultValue()
	{
		return $this->m_sValue;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2html((string)$sValue);
	}

	// Do not display friendly names in the history of change
	public function DescribeChangeAsHTML($sOldValue, $sNewValue, $sLabel = null)
	{
		return '';
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
		case 'Contains':
			return $this->GetSQLExpr()." LIKE ".CMDBSource::Quote("%$value%");
		case 'NotLike':
			return $this->GetSQLExpr()." NOT LIKE $sQValue";
		case 'Like':
		default:
			return $this->GetSQLExpr()." LIKE $sQValue";
		}
	} 
}

?>
