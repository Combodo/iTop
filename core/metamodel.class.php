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
 * Metamodel
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */



// #@# todo: change into class const (see Doctrine)
// Doctrine example
// class toto
// {
//    /**
//     * VERSION
//     */
//    const VERSION                   = '1.0.0';
// }

/**
 * add some description here... 
 *
 * @package     iTopORM
 */
define('ENUM_CHILD_CLASSES_EXCLUDETOP', 1);
/**
 * add some description here... 
 *
 * @package     iTopORM
 */
define('ENUM_CHILD_CLASSES_ALL', 2);

/**
 * Specifies that this attribute is hidden in that state 
 *
 * @package     iTopORM
 */
define('OPT_ATT_HIDDEN', 1);
/**
 * Specifies that this attribute is not editable in that state 
 *
 * @package     iTopORM
 */
define('OPT_ATT_READONLY', 2);
/**
 * Specifieds that the attribute must be set (different than default value?) when arriving into that state 
 *
 * @package     iTopORM
 */
define('OPT_ATT_MANDATORY', 4);
/**
 * Specifies that the attribute must change when arriving into that state 
 *
 * @package     iTopORM
 */
define('OPT_ATT_MUSTCHANGE', 8);
/**
 * Specifies that the attribute must be proposed when arriving into that state 
 *
 * @package     iTopORM
 */
define('OPT_ATT_MUSTPROMPT', 16);




/**
 * (API) The objects definitions as well as their mapping to the database 
 *
 * @package     iTopORM
 */
abstract class MetaModel
{
	///////////////////////////////////////////////////////////////////////////
	//
	// STATIC Members
	//
	///////////////////////////////////////////////////////////////////////////

	// Purpose: workaround the following limitation = PHP5 does not allow to know the class (derived from the current one)
	// from which a static function is called (__CLASS__ and self are interpreted during parsing)
	private static function GetCallersPHPClass($sExpectedFunctionName = null)
	{
		//var_dump(debug_backtrace());
		$aBacktrace = debug_backtrace();
		// $aBacktrace[0] is where we are
		// $aBacktrace[1] is the caller of GetCallersPHPClass
		// $aBacktrace[1] is the info we want
		if (!empty($sExpectedFunctionName))
		{
			assert('$aBacktrace[2]["function"] == $sExpectedFunctionName');
		}
		return $aBacktrace[2]["class"];
	}

	// Static init -why and how it works
	//
	// We found the following limitations:
	//- it is not possible to define non scalar constants
	//- it is not possible to declare a static variable as '= new myclass()'
	// Then we had do propose this model, in which a derived (non abstract)
	// class should implement Init(), to call InheritAttributes or AddAttribute.

	private static function _check_subclass($sClass)
	{
		// See also IsValidClass()... ???? #@#
		// class is mandatory
		// (it is not possible to guess it when called as myderived::...)
		if (!array_key_exists($sClass, self::$m_aClassParams))
		{
			throw new CoreException("Unknown class '$sClass', expected a value in {".implode(', ', array_keys(self::$m_aClassParams))."}");
		}
	}

	public static function static_var_dump()
	{
		var_dump(get_class_vars(__CLASS__));
	}

	private static $m_bDebugQuery = false;
	private static $m_iStackDepthRef = 0;

	public static function StartDebugQuery()
	{
		$aBacktrace = debug_backtrace();
		self::$m_iStackDepthRef = count($aBacktrace);
		self::$m_bDebugQuery = true;
	}
	public static function StopDebugQuery()
	{
		self::$m_bDebugQuery = false;
	}
	public static function DbgTrace($value)
	{
		if (!self::$m_bDebugQuery) return;
		$aBacktrace = debug_backtrace();
		$iCallStackPos = count($aBacktrace) - self::$m_bDebugQuery;
		$sIndent = ""; 
		for ($i = 0 ; $i < $iCallStackPos ; $i++)
		{
			$sIndent .= " .-=^=-. ";
		}
		$aCallers = array();
		foreach($aBacktrace as $aStackInfo)
		{
			$aCallers[] = $aStackInfo["function"];
		}
		$sCallers = "Callstack: ".implode(', ', $aCallers);
		$sFunction = "<b title=\"$sCallers\">".$aBacktrace[1]["function"]."</b>";

		if (is_string($value))
		{
			echo "$sIndent$sFunction: $value<br/>\n";
		}
		else if (is_object($value))
		{
			echo "$sIndent$sFunction:\n<pre>\n";
			print_r($value);
			echo "</pre>\n";
		}
		else
		{
			echo "$sIndent$sFunction: $value<br/>\n";
		}
	}

	private static $m_bTraceQueries = true;
	private static $m_aQueriesLog = array();
	
	private static $m_bLogIssue = false;
	private static $m_bLogNotification = false;
	private static $m_bLogWebService = false;

	public static function IsLogEnabledIssue()
	{
		return self::$m_bLogIssue;
	}
	public static function IsLogEnabledNotification()
	{
		return self::$m_bLogNotification;
	}
	public static function IsLogEnabledWebService()
	{
		return self::$m_bLogWebService;
	}

	private static $m_sDBName = "";
	private static $m_sTablePrefix = ""; // table prefix for the current application instance (allow several applications on the same DB)
	private static $m_Category2Class = array();
	private static $m_aRootClasses = array(); // array of "classname" => "rootclass"
	private static $m_aParentClasses = array(); // array of ("classname" => array of "parentclass") 
	private static $m_aChildClasses = array(); // array of ("classname" => array of "childclass")

	private static $m_aClassParams = array(); // array of ("classname" => array of class information)

	static public function GetParentPersistentClass($sRefClass)
	{
		$sClass = get_parent_class($sRefClass);
		if (!$sClass) return '';

		if ($sClass == 'DBObject') return ''; // Warning: __CLASS__ is lower case in my version of PHP

		// Note: the UI/business model may implement pure PHP classes (intermediate layers)
		if (array_key_exists($sClass, self::$m_aClassParams))
		{
			return $sClass;
		}
		return self::GetParentPersistentClass($sClass);
	}

	static public function IsReadOnlyClass($sClass)
	{
		$bReadOnly = call_user_func(array($sClass, 'IsReadOnly'));
		return $bReadOnly;
	}

	final static public function GetName($sClass)
	{
		self::_check_subclass($sClass);
		$sStringCode = 'Class:'.$sClass;
		return Dict::S($sStringCode, $sClass);
	}
	final static public function GetName_Obsolete($sClass)
	{
		// Written for compatibility with a data model written prior to version 0.9.1
		self::_check_subclass($sClass);
		if (array_key_exists('name', self::$m_aClassParams[$sClass]))
		{
			return self::$m_aClassParams[$sClass]['name'];
		}
		else
		{
			return self::GetName($sClass);
		}
	}
	final static public function GetCategory($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aClassParams[$sClass]["category"];
	}
	final static public function HasCategory($sClass, $sCategory)
	{
		self::_check_subclass($sClass);	
		return (strpos(self::$m_aClassParams[$sClass]["category"], $sCategory) !== false); 
	}
	final static public function GetClassDescription($sClass)
	{
		self::_check_subclass($sClass);	
		$sStringCode = 'Class:'.$sClass.'+';
		return Dict::S($sStringCode, '');
	}
	final static public function GetClassDescription_Obsolete($sClass)
	{
		// Written for compatibility with a data model written prior to version 0.9.1
		self::_check_subclass($sClass);
		if (array_key_exists('description', self::$m_aClassParams[$sClass]))
		{
			return self::$m_aClassParams[$sClass]['description'];
		}
		else
		{
			return self::GetClassDescription($sClass);
		}
	}
	final static public function GetClassIcon($sClass)
	{
		self::_check_subclass($sClass);

		$sIcon = '';
		if (array_key_exists('icon', self::$m_aClassParams[$sClass]))
		{
			$sIcon = self::$m_aClassParams[$sClass]['icon'];
		}
		if (strlen($sIcon) == 0)
		{
			$sParentClass = self::GetParentPersistentClass($sClass);
			if (strlen($sParentClass) > 0)
			{
				return self::GetClassIcon($sParentClass);
			}
		}
		return $sIcon;
	}
	final static public function IsAutoIncrementKey($sClass)
	{
		self::_check_subclass($sClass);	
		return (self::$m_aClassParams[$sClass]["key_type"] == "autoincrement");
	}
	final static public function GetKeyLabel($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aClassParams[$sClass]["key_label"];
	}
	final static public function GetNameAttributeCode($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aClassParams[$sClass]["name_attcode"];
	}
	final static public function GetStateAttributeCode($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aClassParams[$sClass]["state_attcode"];
	}
	final static public function GetDefaultState($sClass)
	{
		$sDefaultState = '';
		$sStateAttrCode = self::GetStateAttributeCode($sClass);
		if (!empty($sStateAttrCode))
		{
			$oStateAttrDef = self::GetAttributeDef($sClass, $sStateAttrCode);
			$sDefaultState = $oStateAttrDef->GetDefaultValue();
		}
		return $sDefaultState;
	}
	final static public function GetReconcKeys($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aClassParams[$sClass]["reconc_keys"];
	}
	final static public function GetDisplayTemplate($sClass)
	{
		self::_check_subclass($sClass);	
		return array_key_exists("display_template", self::$m_aClassParams[$sClass]) ? self::$m_aClassParams[$sClass]["display_template"]: '';
	}
	final static public function GetAttributeOrigin($sClass, $sAttCode)
	{
		self::_check_subclass($sClass);
		return self::$m_aAttribOrigins[$sClass][$sAttCode];
	}
	final static public function GetPrequisiteAttributes($sClass, $sAttCode)
	{
		self::_check_subclass($sClass);
		$oAtt = self::GetAttributeDef($sClass, $sAttCode);
		// Temporary implementation: later, we might be able to compute
		// the dependencies, based on the attributes definition
		// (allowed values and default values) 
		if ($oAtt->IsWritable())
		{
			return $oAtt->GetPrerequisiteAttributes();
		}
		else
		{
			return array();
		}
	}
	/**
	 * Find all attributes that depend on the specified one (reverse of GetPrequisiteAttributes)
	 * @param string $sClass Name of the class
	 * @param string $sAttCode Code of the attributes
	 * @return Array List of attribute codes that depend on the given attribute, empty array if none.
	 */
	final static public function GetDependentAttributes($sClass, $sAttCode)
	{
		$aResults = array();
		self::_check_subclass($sClass);
		foreach (self::ListAttributeDefs($sClass) as $sDependentAttCode=>$void)
		{
			$aPrerequisites = self::GetPrequisiteAttributes($sClass, $sDependentAttCode);
			if (in_array($sAttCode, $aPrerequisites))
			{
				$aResults[] = $sDependentAttCode;
			}
		}
		return $aResults;
	}
	// #@# restore to private ?
	final static public function DBGetTable($sClass, $sAttCode = null)
	{
		self::_check_subclass($sClass);
		if (empty($sAttCode) || ($sAttCode == "id"))
		{
			$sTableRaw = self::$m_aClassParams[$sClass]["db_table"];
			if (empty($sTableRaw))
			{
				// return an empty string whenever the table is undefined, meaning that there is no table associated to this 'abstract' class
				return '';
			}
			else
			{
				return self::$m_sTablePrefix.$sTableRaw;
			}
		}
		// This attribute has been inherited (compound objects)
		return self::DBGetTable(self::$m_aAttribOrigins[$sClass][$sAttCode]);
	}

	final static protected function DBEnumTables()
	{
		// This API do not rely on our capability to query the DB and retrieve
		// the list of existing tables
		// Rather, it uses the list of expected tables, corresponding to the data model
		$aTables = array();
		foreach (self::GetClasses() as $sClass)
		{
			if (!self::HasTable($sClass)) continue;
			$sTable = self::DBGetTable($sClass);

			// Could be completed later with all the classes that are using a given table 
			if (!array_key_exists($sTable, $aTables))
			{
				$aTables[$sTable] = array();
			}
			$aTables[$sTable][] = $sClass;
		}
		return $aTables;
	} 

	final static public function DBGetKey($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aClassParams[$sClass]["db_key_field"];
	}
	final static public function DBGetClassField($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aClassParams[$sClass]["db_finalclass_field"];
	}
	final static public function IsStandaloneClass($sClass)
	{
		self::_check_subclass($sClass);

		if (count(self::$m_aChildClasses[$sClass]) == 0)
		{
			if (count(self::$m_aParentClasses[$sClass]) == 0)
			{
				return true;
			}
		}
		return false;
	}
	final static public function IsParentClass($sParentClass, $sChildClass)
	{
		self::_check_subclass($sChildClass);
		self::_check_subclass($sParentClass);
		if (in_array($sParentClass, self::$m_aParentClasses[$sChildClass])) return true;
		if ($sChildClass == $sParentClass) return true;
		return false;
	}
	final static public function IsSameFamilyBranch($sClassA, $sClassB)
	{
		self::_check_subclass($sClassA);	
		self::_check_subclass($sClassB);	
		if (in_array($sClassA, self::$m_aParentClasses[$sClassB])) return true;
		if (in_array($sClassB, self::$m_aParentClasses[$sClassA])) return true;
		if ($sClassA == $sClassB) return true;
		return false;
	}
	final static public function IsSameFamily($sClassA, $sClassB)
	{
		self::_check_subclass($sClassA);	
		self::_check_subclass($sClassB);	
		return (self::GetRootClass($sClassA) == self::GetRootClass($sClassB));
	}

	// Attributes of a given class may contain attributes defined in a parent class
	// - Some attributes are a copy of the definition
	// - Some attributes correspond to the upper class table definition (compound objects)
	// (see also filters definition)
	private static $m_aAttribDefs = array(); // array of ("classname" => array of attributes)
	private static $m_aAttribOrigins = array(); // array of ("classname" => array of ("attcode"=>"sourceclass"))
	private static $m_aExtKeyFriends = array(); // array of ("classname" => array of ("indirect ext key attcode"=> array of ("relative ext field")))
	final static public function ListAttributeDefs($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aAttribDefs[$sClass];
	}

	final public static function GetAttributesList($sClass)
	{
		self::_check_subclass($sClass);	
		return array_keys(self::$m_aAttribDefs[$sClass]);
	}

	final public static function GetFiltersList($sClass)
	{
		self::_check_subclass($sClass);	
		return array_keys(self::$m_aFilterDefs[$sClass]);
	}

	final public static function GetKeysList($sClass)
	{
		self::_check_subclass($sClass);
		$aExtKeys = array();
		foreach(self::$m_aAttribDefs[$sClass] as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsExternalKey())
			{
				$aExtKeys[] = $sAttCode;
			}
		}	
		return $aExtKeys;
	}
	
	final static public function IsValidKeyAttCode($sClass, $sAttCode)
	{
		if (!array_key_exists($sClass, self::$m_aAttribDefs)) return false;
		if (!array_key_exists($sAttCode, self::$m_aAttribDefs[$sClass])) return false;
		return (self::$m_aAttribDefs[$sClass][$sAttCode]->IsExternalKey());
	}
	final static public function IsValidAttCode($sClass, $sAttCode)
	{
		if (!array_key_exists($sClass, self::$m_aAttribDefs)) return false;
		return (array_key_exists($sAttCode, self::$m_aAttribDefs[$sClass]));
	}
	final static public function IsAttributeOrigin($sClass, $sAttCode)
	{
		return (self::$m_aAttribOrigins[$sClass][$sAttCode] == $sClass);
	}

	final static public function IsValidFilterCode($sClass, $sFilterCode)
	{
		if (!array_key_exists($sClass, self::$m_aFilterDefs)) return false;
		return (array_key_exists($sFilterCode, self::$m_aFilterDefs[$sClass]));
	}
	public static function IsValidClass($sClass)
	{
		return (array_key_exists($sClass, self::$m_aAttribDefs));
	}

	public static function IsValidObject($oObject)
	{
		if (!is_object($oObject)) return false;
		return (self::IsValidClass(get_class($oObject)));
	}

	public static function IsReconcKey($sClass, $sAttCode)
	{
		return (in_array($sAttCode, self::GetReconcKeys($sClass)));
	}

	final static public function GetAttributeDef($sClass, $sAttCode)
	{
		self::_check_subclass($sClass);	
		return self::$m_aAttribDefs[$sClass][$sAttCode];
	}

	final static public function GetExternalKeys($sClass)
	{
		$aExtKeys = array();
		foreach (self::ListAttributeDefs($sClass) as $sAttCode => $oAtt)
		{
			if ($oAtt->IsExternalKey())
			{
				$aExtKeys[$sAttCode] = $oAtt;
			}
		}
		return $aExtKeys;
	}

	final static public function GetLinkedSets($sClass)
	{
		$aLinkedSets = array();
		foreach (self::ListAttributeDefs($sClass) as $sAttCode => $oAtt)
		{
			if (is_subclass_of($oAtt, 'AttributeLinkedSet'))
			{
				$aLinkedSets[$sAttCode] = $oAtt;
			}
		}
		return $aLinkedSets;
	}

	final static public function GetExternalFields($sClass, $sKeyAttCode)
	{
		$aExtFields = array();
		foreach (self::ListAttributeDefs($sClass) as $sAttCode => $oAtt)
		{
			if ($oAtt->IsExternalField() && ($oAtt->GetKeyAttCode() == $sKeyAttCode))
			{
				$aExtFields[] = $oAtt;
			}
		}
		return $aExtFields;
	}

	final static public function GetExtKeyFriends($sClass, $sExtKeyAttCode)
	{
		if (array_key_exists($sExtKeyAttCode, self::$m_aExtKeyFriends[$sClass]))
		{
			return self::$m_aExtKeyFriends[$sClass][$sExtKeyAttCode];
		}
		else
		{
			return array();
		}
	}

	public static function GetLabel($sClass, $sAttCode)
	{
		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
		if ($oAttDef) return $oAttDef->GetLabel();
		return "";
	}

	public static function GetDescription($sClass, $sAttCode)
	{
		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
		if ($oAttDef) return $oAttDef->GetDescription();
		return "";
	}

	// Filters of a given class may contain filters defined in a parent class
	// - Some filters are a copy of the definition
	// - Some filters correspond to the upper class table definition (compound objects)
	// (see also attributes definition)
	private static $m_aFilterDefs = array(); // array of ("classname" => array filterdef)
	private static $m_aFilterOrigins = array(); // array of ("classname" => array of ("attcode"=>"sourceclass"))

	public static function GetClassFilterDefs($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aFilterDefs[$sClass];
	}

	final static public function GetClassFilterDef($sClass, $sFilterCode)
	{
		self::_check_subclass($sClass);	
		return self::$m_aFilterDefs[$sClass][$sFilterCode];
	}

	public static function GetFilterLabel($sClass, $sFilterCode)
	{
		$oFilter = self::GetClassFilterDef($sClass, $sFilterCode);
		if ($oFilter) return $oFilter->GetLabel();
		return "";
	}

	public static function GetFilterDescription($sClass, $sFilterCode)
	{
		$oFilter = self::GetClassFilterDef($sClass, $sFilterCode);
		if ($oFilter) return $oFilter->GetDescription();
		return "";
	}

	// returns an array of opcode=>oplabel (e.g. "differs from")
	public static function GetFilterOperators($sClass, $sFilterCode)
	{
		$oFilter = self::GetClassFilterDef($sClass, $sFilterCode);
		if ($oFilter) return $oFilter->GetOperators();
		return array();
	}

	// returns an opcode
	public static function GetFilterLooseOperator($sClass, $sFilterCode)
	{
		$oFilter = self::GetClassFilterDef($sClass, $sFilterCode);
		if ($oFilter) return $oFilter->GetLooseOperator();
		return array();
	}

	public static function GetFilterOpDescription($sClass, $sFilterCode, $sOpCode)
	{
		$oFilter = self::GetClassFilterDef($sClass, $sFilterCode);
		if ($oFilter) return $oFilter->GetOpDescription($sOpCode);
		return "";
	}

	public static function GetFilterHTMLInput($sFilterCode)
	{
		return "<INPUT name=\"$sFilterCode\">";
	}

	// Lists of attributes/search filters
	//
	private static $m_aListInfos = array(); // array of ("listcode" => various info on the list, common to every classes)
	private static $m_aListData = array(); // array of ("classname" => array of "listcode" => list)
	// list may be an array of attcode / fltcode
	// list may be an array of "groupname" => (array of attcode / fltcode) 

	public static function EnumZLists()
	{
		return array_keys(self::$m_aListInfos);
	}

	final static public function GetZListInfo($sListCode)
	{
		return self::$m_aListInfos[$sListCode];
	}

	public static function GetZListItems($sClass, $sListCode)
	{
		if (array_key_exists($sClass, self::$m_aListData))
		{
			if (array_key_exists($sListCode, self::$m_aListData[$sClass]))
			{
				return self::$m_aListData[$sClass][$sListCode];
			}
		}
		$sParentClass = self::GetParentPersistentClass($sClass);
		if (empty($sParentClass)) return array(); // nothing for the mother of all classes
		// Dig recursively
		return self::GetZListItems($sParentClass, $sListCode);
	}

	public static function IsAttributeInZList($sClass, $sListCode, $sAttCodeOrFltCode, $sGroup = null)
	{
		$aZList = self::GetZListItems($sClass, $sListCode);
		if (!$sGroup)
		{
			return (in_array($sAttCodeOrFltCode, $aZList));
		}
		return (in_array($sAttCodeOrFltCode, $aZList[$sGroup]));
	}

	//
	// Relations
	//
	private static $m_aRelationInfos = array(); // array of ("relcode" => various info on the list, common to every classes)

	public static function EnumRelations()
	{
		return array_keys(self::$m_aRelationInfos);
	}

	public static function EnumRelationProperties($sRelCode)
	{
		MyHelpers::CheckKeyInArray('relation code', $sRelCode, self::$m_aRelationInfos);
		return self::$m_aRelationInfos[$sRelCode];
	}

	final static public function GetRelationDescription($sRelCode)
	{
		return Dict::S("Relation:$sRelCode/Description");
	}

	final static public function GetRelationVerbUp($sRelCode)
	{
		return Dict::S("Relation:$sRelCode/VerbUp");
	}

	final static public function GetRelationVerbDown($sRelCode)
	{
		return Dict::S("Relation:$sRelCode/VerbDown");
	}

	public static function EnumRelationQueries($sClass, $sRelCode)
	{
		MyHelpers::CheckKeyInArray('relation code', $sRelCode, self::$m_aRelationInfos);
		return call_user_func_array(array($sClass, 'GetRelationQueries'), array($sRelCode));
	}

	//
	// Object lifecycle model
	//
	private static $m_aStates = array(); // array of ("classname" => array of "statecode"=>array('label'=>..., 'description'=>..., attribute_inherit=> attribute_list=>...))
	private static $m_aStimuli = array(); // array of ("classname" => array of ("stimuluscode"=>array('label'=>..., 'description'=>...)))
	private static $m_aTransitions = array(); // array of ("classname" => array of ("statcode_from"=>array of ("stimuluscode" => array('target_state'=>..., 'actions'=>array of handlers procs, 'user_restriction'=>TBD)))

	public static function EnumStates($sClass)
	{
		if (array_key_exists($sClass, self::$m_aStates))
		{
			return self::$m_aStates[$sClass];
		}
		else
		{
			return array();
		}
	}

	public static function EnumStimuli($sClass)
	{
		if (array_key_exists($sClass, self::$m_aStimuli))
		{
			return self::$m_aStimuli[$sClass];
		}
		else
		{
			return array();
		}
	}

	public static function GetStateLabel($sClass, $sStateValue)
	{
		$sStateAttrCode = self::GetStateAttributeCode($sClass);
		return Dict::S("Class:$sClass/Attribute:$sStateAttrCode/Value:$sStateValue", $sStateValue);

		// I've decided the current implementation, because I need
		// to get the description as well -GetAllowedValues does not render the description,
		// so far...
		// Could have been implemented the following way (not tested
		// $oStateAttrDef = self::GetAttributeDef($sClass, $sStateAttrCode);
		// $aAllowedValues = $oStateAttrDef->GetAllowedValues();
		// return $aAllowedValues[$sStateValue];
	}
	public static function GetStateDescription($sClass, $sStateValue)
	{
		$sStateAttrCode = self::GetStateAttributeCode($sClass);
		return Dict::S("Class:$sClass/Attribute:$sStateAttrCode/Value:$sStateValue+", '');
	}

	public static function EnumTransitions($sClass, $sStateCode)
	{
		if (array_key_exists($sClass, self::$m_aTransitions))
		{
			if (array_key_exists($sStateCode, self::$m_aTransitions[$sClass]))
			{
				return self::$m_aTransitions[$sClass][$sStateCode];
			}
		}
		return array();
	}
	public static function GetAttributeFlags($sClass, $sState, $sAttCode)
	{
		$iFlags = 0; // By default (if no life cycle) no flag at all
		$sStateAttCode = self::GetStateAttributeCode($sClass);
		if (!empty($sStateAttCode))
		{
			$aStates = MetaModel::EnumStates($sClass);
			if (!array_key_exists($sState, $aStates))
			{
				throw new CoreException("Invalid state '$sState' for class '$sClass', expecting a value in {".implode(', ', array_keys($aStates))."}");
			}
			$aCurrentState = $aStates[$sState];
			if ( (array_key_exists('attribute_list', $aCurrentState)) && (array_key_exists($sAttCode, $aCurrentState['attribute_list'])) )
			{
				$iFlags = $aCurrentState['attribute_list'][$sAttCode];
			}
		}
		return $iFlags;
	}
	
	//
	// Allowed values
	//

	public static function GetAllowedValues_att($sClass, $sAttCode, $aArgs = array(), $sBeginsWith = '')
	{
		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
		return $oAttDef->GetAllowedValues($aArgs, $sBeginsWith);
	}

	public static function GetAllowedValues_flt($sClass, $sFltCode, $aArgs = array(), $sBeginsWith = '')
	{
		$oFltDef = self::GetClassFilterDef($sClass, $sFltCode);
		return $oFltDef->GetAllowedValues($aArgs, $sBeginsWith);
	}

	//
	// Businezz model declaration verbs (should be static)
	//

	public static function RegisterZList($sListCode, $aListInfo)
	{
		// Check mandatory params
		$aMandatParams = array(
			"description" => "detailed (though one line) description of the list",
			"type" => "attributes | filters",
		);		
		foreach($aMandatParams as $sParamName=>$sParamDesc)
		{
			if (!array_key_exists($sParamName, $aListInfo))
			{
				throw new CoreException("Declaration of list $sListCode - missing parameter $sParamName");
			}
		}
		
		self::$m_aListInfos[$sListCode] = $aListInfo;
	}

	public static function RegisterRelation($sRelCode)
	{
		// Each item used to be an array of properties...
		self::$m_aRelationInfos[$sRelCode] = $sRelCode;
	}

	// Must be called once and only once...
	public static function InitClasses($sTablePrefix)
	{
		if (count(self::GetClasses()) > 0)
		{
			throw new CoreException("InitClasses should not be called more than once -skipped");
			return;
		}

		self::$m_sTablePrefix = $sTablePrefix;

		foreach(get_declared_classes() as $sPHPClass) {
			if (is_subclass_of($sPHPClass, 'DBObject'))
			{
				if (method_exists($sPHPClass, 'Init'))
				{
					call_user_func(array($sPHPClass, 'Init'));
				}
			}
		}
		foreach (self::GetClasses() as $sClass)
		{
			self::$m_aExtKeyFriends[$sClass] = array();
			foreach (self::$m_aAttribDefs[$sClass] as $sAttCode => $oAttDef)
			{
				// Compute the filter codes
				//
				foreach ($oAttDef->GetFilterDefinitions() as $sFilterCode => $oFilterDef)
				{
					self::$m_aFilterDefs[$sClass][$sFilterCode] = $oFilterDef;
				}
		
				if ($oAttDef->IsExternalField())
				{
					$sKeyAttCode = $oAttDef->GetKeyAttCode();
					$oKeyDef = self::GetAttributeDef($sClass, $sKeyAttCode);
					self::$m_aFilterOrigins[$sClass][$sFilterCode] = $oKeyDef->GetTargetClass();
				}
				else
				{
					self::$m_aFilterOrigins[$sClass][$sFilterCode] = self::$m_aAttribOrigins[$sClass][$sAttCode];
				}
				// Compute the fields that will be used to display a pointer to another object
				//
				if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE))
				{
					// oAttDef is either
					// - an external KEY / FIELD (direct),
					// - an external field pointing to an external KEY / FIELD
					// - an external field pointing to an external field pointing to....

					// Get the real external key attribute
					// It will be our reference to determine the other ext fields related to the same ext key
					$oFinalKeyAttDef = $oAttDef->GetKeyAttDef(EXTKEY_ABSOLUTE);

					self::$m_aExtKeyFriends[$sClass][$sAttCode] = array();
					foreach (self::GetExternalFields($sClass, $oAttDef->GetKeyAttCode($sAttCode)) as $oExtField)
					{
						// skip : those extfields will be processed as external keys
						if ($oExtField->IsExternalKey(EXTKEY_ABSOLUTE)) continue;

						// Note: I could not compare the objects by the mean of '==='
						// because they are copied for the inheritance, and the internal references are NOT updated
						if ($oExtField->GetKeyAttDef(EXTKEY_ABSOLUTE) == $oFinalKeyAttDef)
						{
							self::$m_aExtKeyFriends[$sClass][$sAttCode][$oExtField->GetCode()] = $oExtField;
						}
					}
				}
			}

			// Add a 'id' filter
			//
			if (array_key_exists('id', self::$m_aAttribDefs[$sClass]))
			{
				throw new CoreException("Class $sClass, 'id' is a reserved keyword, it cannot be used as an attribute code");
			}
			if (array_key_exists('id', self::$m_aFilterDefs[$sClass]))
			{
				throw new CoreException("Class $sClass, 'id' is a reserved keyword, it cannot be used as a filter code");
			}
			$oFilter = new FilterPrivateKey('id', array('id_field' => self::DBGetKey($sClass)));
			self::$m_aFilterDefs[$sClass]['id'] = $oFilter;
			self::$m_aFilterOrigins[$sClass]['id'] = $sClass;

			// Define defaults values for the standard ZLists
			//
			//foreach (self::$m_aListInfos as $sListCode => $aListConfig)
			//{
			//	if (!isset(self::$m_aListData[$sClass][$sListCode]))
			//	{
			//		$aAllAttributes = array_keys(self::$m_aAttribDefs[$sClass]);
			//		self::$m_aListData[$sClass][$sListCode] = $aAllAttributes;
			//		//echo "<p>$sClass: $sListCode (".count($aAllAttributes)." attributes)</p>\n";
			//	}
			//}
		}

		// Add a 'class' attribute/filter to the root classes and their children
		//
		foreach(self::EnumRootClasses() as $sRootClass)
		{
			if (self::IsStandaloneClass($sRootClass)) continue;
			
			$sDbFinalClassField = self::DBGetClassField($sRootClass);
			if (strlen($sDbFinalClassField) == 0)
			{
				$sDbFinalClassField = 'finalclass';
				self::$m_aClassParams[$sClass]["db_finalclass_field"] = 'finalclass';
			}
			$oClassAtt = new AttributeFinalClass('finalclass', array(
					"sql"=>$sDbFinalClassField,
					"default_value"=>$sRootClass,
					"is_null_allowed"=>false,
					"depends_on"=>array()
			));
			$oClassAtt->SetHostClass($sRootClass);
			self::$m_aAttribDefs[$sRootClass]['finalclass'] = $oClassAtt;
			self::$m_aAttribOrigins[$sRootClass]['finalclass'] = $sRootClass;

			$oClassFlt = new FilterFromAttribute($oClassAtt);
			self::$m_aFilterDefs[$sRootClass]['finalclass'] = $oClassFlt;
			self::$m_aFilterOrigins[$sRootClass]['finalclass'] = $sRootClass;

			foreach(self::EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_EXCLUDETOP) as $sChildClass)
			{
				if (array_key_exists('finalclass', self::$m_aAttribDefs[$sChildClass]))
				{
					throw new CoreException("Class $sChildClass, 'finalclass' is a reserved keyword, it cannot be used as an attribute code");
				}
				if (array_key_exists('finalclass', self::$m_aFilterDefs[$sChildClass]))
				{
					throw new CoreException("Class $sChildClass, 'finalclass' is a reserved keyword, it cannot be used as a filter code");
				}
				$oCloned = clone $oClassAtt;
				$oCloned->SetFixedValue($sChildClass);
				self::$m_aAttribDefs[$sChildClass]['finalclass'] = $oCloned;
				self::$m_aAttribOrigins[$sChildClass]['finalclass'] = $sRootClass;

				$oClassFlt = new FilterFromAttribute($oClassAtt);
				self::$m_aFilterDefs[$sChildClass]['finalclass'] = $oClassFlt;
				self::$m_aFilterOrigins[$sChildClass]['finalclass'] = self::GetRootClass($sChildClass);
			}
		}
	}

	// To be overriden, must be called for any object class (optimization)
	public static function Init()
	{
		// In fact it is an ABSTRACT function, but this is not compatible with the fact that it is STATIC (error in E_STRICT interpretation)
	}
	// To be overloaded by biz model declarations
	public static function GetRelationQueries($sRelCode)
	{
		// In fact it is an ABSTRACT function, but this is not compatible with the fact that it is STATIC (error in E_STRICT interpretation)
		return array();
	}

	public static function Init_Params($aParams)
	{
		// Check mandatory params
		$aMandatParams = array(
			"category" => "group classes by modules defining their visibility in the UI",
			"key_type" => "autoincrement | string",
			"key_label" => "if set, then display the key as an attribute",
			"name_attcode" => "define wich attribute is the class name, may be an inherited attribute",
			"state_attcode" => "define wich attribute is representing the state (object lifecycle)",
			"reconc_keys" => "define the attributes that will 'almost uniquely' identify an object in batch processes",
			"db_table" => "database table",
			"db_key_field" => "database field which is the key",
			"db_finalclass_field" => "database field wich is the reference to the actual class of the object, considering that this will be a compound class",
		);		

		$sClass = self::GetCallersPHPClass("Init");

		foreach($aMandatParams as $sParamName=>$sParamDesc)
		{
			if (!array_key_exists($sParamName, $aParams))
			{
				throw new CoreException("Declaration of class $sClass - missing parameter $sParamName");
			}
		}
		
		$aCategories = explode(',', $aParams['category']);
		foreach ($aCategories as $sCategory)
		{
			self::$m_Category2Class[$sCategory][] = $sClass;
		}
		self::$m_Category2Class[''][] = $sClass; // all categories, include this one
		

		self::$m_aRootClasses[$sClass] = $sClass; // first, let consider that I am the root... updated on inheritance
		self::$m_aParentClasses[$sClass] = array();
		self::$m_aChildClasses[$sClass] = array();

		self::$m_aClassParams[$sClass]= $aParams;

		self::$m_aAttribDefs[$sClass] = array();
		self::$m_aAttribOrigins[$sClass] = array();
		self::$m_aExtKeyFriends[$sClass] = array();
		self::$m_aFilterDefs[$sClass] = array();
		self::$m_aFilterOrigins[$sClass] = array();
	}

	protected static function object_array_mergeclone($aSource1, $aSource2)
	{
		$aRes = array();
		foreach ($aSource1 as $key=>$object)
		{
			$aRes[$key] = clone $object;
		}
		foreach ($aSource2 as $key=>$object)
		{
			$aRes[$key] = clone $object;
		}
		return $aRes;
	}

	public static function Init_InheritAttributes($sSourceClass = null)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		if (empty($sSourceClass))
		{
			// Default: inherit from parent class
			$sSourceClass = self::GetParentPersistentClass($sTargetClass);
			if (empty($sSourceClass)) return; // no attributes for the mother of all classes
		}
		if (isset(self::$m_aAttribDefs[$sSourceClass]))
		{
			if (!isset(self::$m_aAttribDefs[$sTargetClass]))
			{
				self::$m_aAttribDefs[$sTargetClass] = array();
				self::$m_aAttribOrigins[$sTargetClass] = array();
			}
			self::$m_aAttribDefs[$sTargetClass] = self::object_array_mergeclone(self::$m_aAttribDefs[$sTargetClass], self::$m_aAttribDefs[$sSourceClass]);
			self::$m_aAttribOrigins[$sTargetClass] = array_merge(self::$m_aAttribOrigins[$sTargetClass], self::$m_aAttribOrigins[$sSourceClass]);
		}
		// Build root class information
		if (array_key_exists($sSourceClass, self::$m_aRootClasses))
		{
			// Inherit...
			self::$m_aRootClasses[$sTargetClass] = self::$m_aRootClasses[$sSourceClass];
		}
		else
		{
			// This class will be the root class
			self::$m_aRootClasses[$sSourceClass] = $sSourceClass;
			self::$m_aRootClasses[$sTargetClass] = $sSourceClass;
		}
		self::$m_aParentClasses[$sTargetClass] += self::$m_aParentClasses[$sSourceClass];
		self::$m_aParentClasses[$sTargetClass][] = $sSourceClass;
		// I am the child of each and every parent...
		foreach(self::$m_aParentClasses[$sTargetClass] as $sAncestorClass)
		{
			self::$m_aChildClasses[$sAncestorClass][] = $sTargetClass;
		}
	}
	public static function Init_OverloadAttributeParams($sAttCode, $aParams)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		
		if (!self::IsValidAttCode($sTargetClass, $sAttCode))
		{
			throw new CoreException("Could not overload '$sAttCode', expecting a code from {".implode(", ", self::GetAttributesList($sTargetClass))."}");
		}
		self::$m_aAttribDefs[$sTargetClass][$sAttCode]->OverloadParams($aParams);
	}
	public static function Init_AddAttribute(AttributeDefinition $oAtt)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		self::$m_aAttribDefs[$sTargetClass][$oAtt->GetCode()] = $oAtt;
		self::$m_aAttribOrigins[$sTargetClass][$oAtt->GetCode()] = $sTargetClass;
		// Note: it looks redundant to put targetclass there, but a mix occurs when inheritance is used
		
		// Specific case of external fields:
		// I wanted to simplify the syntax of the declaration of objects in the biz model
		// Therefore, the reference to the host class is set there 
		$oAtt->SetHostClass($sTargetClass);
	}

	public static function Init_SetZListItems($sListCode, $aItems)
	{
		MyHelpers::CheckKeyInArray('list code', $sListCode, self::$m_aListInfos);

		$sTargetClass = self::GetCallersPHPClass("Init");
		self::$m_aListData[$sTargetClass][$sListCode] = $aItems;
	}

	public static function Init_DefineState($sStateCode, $aStateDef)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		if (is_null($aStateDef['attribute_list'])) $aStateDef['attribute_list'] = array(); 

		$sParentState = $aStateDef['attribute_inherit'];
		if (!empty($sParentState))
		{
			// Inherit from the given state (must be defined !)
			$aToInherit = self::$m_aStates[$sTargetClass][$sParentState];
			// The inherited configuration could be overriden
			$aStateDef['attribute_list'] = array_merge($aToInherit, $aStateDef['attribute_list']);
		}
		self::$m_aStates[$sTargetClass][$sStateCode] = $aStateDef;

		// by default, create an empty set of transitions associated to that state
		self::$m_aTransitions[$sTargetClass][$sStateCode] = array();
	}

	public static function Init_DefineStimulus($oStimulus)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		self::$m_aStimuli[$sTargetClass][$oStimulus->GetCode()] = $oStimulus;

		// I wanted to simplify the syntax of the declaration of objects in the biz model
		// Therefore, the reference to the host class is set there 
		$oStimulus->SetHostClass($sTargetClass);
	}

	public static function Init_DefineTransition($sStateCode, $sStimulusCode, $aTransitionDef)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		if (is_null($aTransitionDef['actions'])) $aTransitionDef['actions'] = array(); 
		self::$m_aTransitions[$sTargetClass][$sStateCode][$sStimulusCode] = $aTransitionDef;
	}

	public static function Init_InheritLifecycle($sSourceClass = '')
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		if (empty($sSourceClass))
		{
			// Default: inherit from parent class
			$sSourceClass = self::GetParentPersistentClass($sTargetClass);
			if (empty($sSourceClass)) return; // no attributes for the mother of all classes
		}

		self::$m_aClassParams[$sTargetClass]["state_attcode"] = self::$m_aClassParams[$sSourceClass]["state_attcode"];
		self::$m_aStates[$sTargetClass] = clone self::$m_aStates[$sSourceClass];
		self::$m_aStimuli[$sTargetClass] = clone self::$m_aStimuli[$sSourceClass];
		self::$m_aTransitions[$sTargetClass] = clone self::$m_aTransitions[$sSourceClass];
	}

	//
	// Static API
	//

	public static function GetRootClass($sClass = null)
	{
		self::_check_subclass($sClass);
		return self::$m_aRootClasses[$sClass];
	}
	public static function IsRootClass($sClass)
	{
		self::_check_subclass($sClass);
		return (self::GetRootClass($sClass) == $sClass);
	}
	public static function EnumRootClasses()
	{
		return array_unique(self::$m_aRootClasses);
	}
	public static function EnumParentClasses($sClass)
	{
		self::_check_subclass($sClass);	
		return self::$m_aParentClasses[$sClass];
	}
	public static function EnumChildClasses($sClass, $iOption = ENUM_CHILD_CLASSES_EXCLUDETOP)
	{
		self::_check_subclass($sClass);

		$aRes = self::$m_aChildClasses[$sClass];
		if ($iOption != ENUM_CHILD_CLASSES_EXCLUDETOP)
		{
			// Add it to the list
			$aRes[] = $sClass;
		}
		return $aRes;
	}

	public static function EnumCategories()
	{
		return array_keys(self::$m_Category2Class);
	}

	// Note: use EnumChildClasses to take the compound objects into account
	public static function GetSubclasses($sClass)
	{
		self::_check_subclass($sClass);	
		$aSubClasses = array();
		foreach(get_declared_classes() as $sSubClass) {
			if (is_subclass_of($sSubClass, $sClass))
			{
				$aSubClasses[] = $sSubClass;
			}
		}
		return $aSubClasses;
	}
	public static function GetClasses($sCategory = '')
	{
		if (array_key_exists($sCategory, self::$m_Category2Class))
		{
			return self::$m_Category2Class[$sCategory];
		}

		if (count(self::$m_Category2Class) > 0)
		{
			throw new CoreException("unkown class category '$sCategory', expecting a value in {".implode(', ', array_keys(self::$m_Category2Class))."}");
		}
		return array();
	}

	public static function HasTable($sClass)
	{
		if (strlen(self::DBGetTable($sClass)) == 0) return false;
		return true;
	}

	public static function IsAbstract($sClass)
	{
		$oReflection = new ReflectionClass($sClass);
		return $oReflection->isAbstract();
	}

	protected static $m_aQueryStructCache = array();

	protected static function PrepareQueryArguments($aArgs)
	{
		// Translate any object into scalars
		//
		$aScalarArgs = array();
		foreach($aArgs as $sArgName => $value)
		{
			if (self::IsValidObject($value))
			{
				$aScalarArgs = array_merge($aScalarArgs, $value->ToArgs($sArgName));
			}
			else
			{
				$aScalarArgs[$sArgName] = (string) $value;
			}
		}
		// Add standard contextual arguments
		//
		$aScalarArgs['current_contact_id'] = UserRights::GetContactId();
		
		return $aScalarArgs;
	}

	public static function MakeSelectQuery(DBObjectSearch $oFilter, $aOrderBy = array(), $aArgs = array())
	{
		// Query caching
		//
		$bQueryCacheEnabled = true;
		$aParams = array();
		$sOqlQuery = $oFilter->ToOql($aParams); // Render with arguments in clear
		if ($bQueryCacheEnabled)
		{
			if (array_key_exists($sOqlQuery, self::$m_aQueryStructCache))
			{
				// hit!
				$oSelect = clone self::$m_aQueryStructCache[$sOqlQuery];
			}
		}

		if (!isset($oSelect))
		{
			$aTranslation = array();
			$aClassAliases = array();
			$aTableAliases = array();
			$oConditionTree = $oFilter->GetCriteria();

			$oSelect = self::MakeQuery($oFilter->GetSelectedClasses(), $oConditionTree, $aClassAliases, $aTableAliases, $aTranslation, $oFilter);

			self::$m_aQueryStructCache[$sOqlQuery] = clone $oSelect;
		}

		// Check the order by specification, and prefix with the class alias
		//
		$aOrderSpec = array();
		foreach ($aOrderBy as $sFieldAlias => $bAscending)
		{
			MyHelpers::CheckValueInArray('field name in ORDER BY spec', $sFieldAlias, self::GetAttributesList($oFilter->GetFirstJoinedClass()));
			if (!is_bool($bAscending))
			{
				throw new CoreException("Wrong direction in ORDER BY spec, found '$bAscending' and expecting a boolean value");
			}
			$aOrderSpec[$oFilter->GetFirstJoinedClassAlias().$sFieldAlias] = $bAscending;
		}
		// By default, force the name attribute to be the ordering key
		//
		if (empty($aOrderSpec))
		{
			foreach ($oFilter->GetSelectedClasses() as $sSelectedAlias => $sSelectedClass)
			{
				$sNameAttCode = self::GetNameAttributeCode($sSelectedClass);
				if (!empty($sNameAttCode))
				{
					// By default, simply order on the "name" attribute, ascending
					$aOrderSpec[$sSelectedAlias.$sNameAttCode] = true;
				}
			}
		}
		
		// Go
		//
		$aScalarArgs = array_merge(self::PrepareQueryArguments($aArgs), $oFilter->GetInternalParams());

		try
		{
			$sRes = $oSelect->RenderSelect($aOrderSpec, $aScalarArgs);
		}
		catch (MissingQueryArgument $e)
		{
			// Add some information...
			$e->addInfo('OQL', $sOqlQuery);
			throw $e;
		}

		if (self::$m_bTraceQueries)
		{
			$aParams = array();
			if (!array_key_exists($sOqlQuery, self::$m_aQueriesLog))
			{
				self::$m_aQueriesLog[$sOqlQuery] = array(
					'sql' => array(),
					'count' => 0,
				);
			}
			self::$m_aQueriesLog[$sOqlQuery]['count']++;
			self::$m_aQueriesLog[$sOqlQuery]['sql'][] = $sRes;
		}

		return $sRes;
	}

	public static function ShowQueryTrace()
	{
		$iTotal = 0;
		foreach (self::$m_aQueriesLog as $sOql => $aQueryData)
		{
			echo "<h2>$sOql</h2>\n";
			$iTotal += $aQueryData['count'];
			echo '<p>'.$aQueryData['count'].'</p>';
			echo '<p>Example: '.$aQueryData['sql'][0].'</p>';
		}
		echo "<h2>Total</h2>\n";
		echo "<p>Count of executed queries: $iTotal</p>";
		echo "<p>Count of built queries: ".count(self::$m_aQueriesLog)."</p>";
	}

	public static function MakeDeleteQuery(DBObjectSearch $oFilter, $aArgs = array())
	{
		$aTranslation = array();
		$aClassAliases = array();
		$aTableAliases = array();
		$oConditionTree = $oFilter->GetCriteria();
		$oSelect = self::MakeQuery($oFilter->GetSelectedClasses(), $oConditionTree, $aClassAliases, $aTableAliases, $aTranslation, $oFilter);
		$aScalarArgs = array_merge(self::PrepareQueryArguments($aArgs), $oFilter->GetInternalParams());
		return $oSelect->RenderDelete($aScalarArgs);
	}

	public static function MakeUpdateQuery(DBObjectSearch $oFilter, $aValues, $aArgs = array())
	{
		// $aValues is an array of $sAttCode => $value
		$aTranslation = array();
		$aClassAliases = array();
		$aTableAliases = array();
		$oConditionTree = $oFilter->GetCriteria();
		$oSelect = self::MakeQuery($oFilter->GetSelectedClasses(), $oConditionTree, $aClassAliases, $aTableAliases, $aTranslation, $oFilter, array(), $aValues);
		$aScalarArgs = array_merge(self::PrepareQueryArguments($aArgs), $oFilter->GetInternalParams());
		return $oSelect->RenderUpdate($aScalarArgs);
	}

	private static function MakeQuery($aSelectedClasses, &$oConditionTree, &$aClassAliases, &$aTableAliases, &$aTranslation, DBObjectSearch $oFilter, $aExpectedAtts = array(), $aValues = array())
	{
		// Note: query class might be different than the class of the filter
		// -> this occurs when we are linking our class to an external class (referenced by, or pointing to)
		// $aExpectedAtts is an array of sAttCode=>array of columns
		$sClass = $oFilter->GetFirstJoinedClass();
		$sClassAlias = $oFilter->GetFirstJoinedClassAlias();

		$bIsOnQueriedClass = array_key_exists($sClassAlias, $aSelectedClasses);
		if ($bIsOnQueriedClass)
		{
			$aClassAliases = array_merge($aClassAliases, $oFilter->GetJoinedClasses());
		}

		self::DbgTrace("Entering: ".$oFilter->ToOQL().", ".($bIsOnQueriedClass ? "MAIN" : "SECONDARY").", expectedatts=".count($aExpectedAtts).": ".implode(",", array_keys($aExpectedAtts)));

		$sRootClass = self::GetRootClass($sClass);
		$sKeyField = self::DBGetKey($sClass);

		if ($bIsOnQueriedClass)
		{
			// default to the whole list of attributes + the very std id/finalclass
			$aExpectedAtts['id'][] = $sClassAlias.'id';
			foreach (self::GetAttributesList($sClass) as $sAttCode)
			{
				$aExpectedAtts[$sAttCode][] = $sClassAlias.$sAttCode; // alias == class and attcode
			}
		}

		// Compute a clear view of external keys, and external attributes
		// Build the list of external keys:
		// -> ext keys required by a closed join ???
		// -> ext keys mentionned in a 'pointing to' condition
		// -> ext keys required for an external field
		//
		$aExtKeys = array(); // array of sTableClass => array of (sAttCode (keys) => array of (sAttCode (fields)=> oAttDef))
		//
		// Optimization: could be computed once for all (cached)
		// Could be done in MakeQuerySingleTable ???
		//  

		if ($bIsOnQueriedClass)
		{
			// Get all Ext keys for the queried class (??)
			foreach(self::GetKeysList($sClass) as $sKeyAttCode)
			{
				$sKeyTableClass = self::$m_aAttribOrigins[$sClass][$sKeyAttCode];
				$aExtKeys[$sKeyTableClass][$sKeyAttCode] = array();
			}
		}
		// Get all Ext keys used by the filter
		foreach ($oFilter->GetCriteria_PointingTo() as $sKeyAttCode => $trash)
		{
			$sKeyTableClass = self::$m_aAttribOrigins[$sClass][$sKeyAttCode];
			$aExtKeys[$sKeyTableClass][$sKeyAttCode] = array();
		}
		// Add the ext fields used in the select (eventually adds an external key)
		foreach(self::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
		{
			if ($oAttDef->IsExternalField())
			{
				$sKeyAttCode = $oAttDef->GetKeyAttCode();
				if (array_key_exists($sAttCode, $aExpectedAtts) || $oConditionTree->RequiresField($sClassAlias, $sAttCode))
				{
					// Add the external attribute
					$sKeyTableClass = self::$m_aAttribOrigins[$sClass][$sKeyAttCode];
					$aExtKeys[$sKeyTableClass][$sKeyAttCode][$sAttCode] = $oAttDef;
				}
			}
		}

		// First query built upon on the leaf (ie current) class
		//
		self::DbgTrace("Main (=leaf) class, call MakeQuerySingleTable()");
		if (self::HasTable($sClass))
		{
			$oSelectBase = self::MakeQuerySingleTable($aSelectedClasses, $oConditionTree, $aClassAliases, $aTableAliases, $aTranslation, $oFilter, $sClass, $aExpectedAtts, $aExtKeys, $aValues);
		}
		else
		{
			$oSelectBase = null;

			// As the join will not filter on the expected classes, we have to specify it explicitely
			$sExpectedClasses = implode("', '", self::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL));
			$oFinalClassRestriction = Expression::FromOQL("`$sClassAlias`.finalclass IN ('$sExpectedClasses')");
			$oConditionTree = $oConditionTree->LogAnd($oFinalClassRestriction);
		}

		// Then we join the queries of the eventual parent classes (compound model)
		foreach(self::EnumParentClasses($sClass) as $sParentClass)
		{
			if (self::DBGetTable($sParentClass) == "") continue;
			self::DbgTrace("Parent class: $sParentClass... let's call MakeQuerySingleTable()");
			$oSelectParentTable = self::MakeQuerySingleTable($aSelectedClasses, $oConditionTree, $aClassAliases, $aTableAliases, $aTranslation, $oFilter, $sParentClass, $aExpectedAtts, $aExtKeys, $aValues);
			if (is_null($oSelectBase))
			{
				$oSelectBase = $oSelectParentTable;
			}
			else
			{
				$oSelectBase->AddInnerJoin($oSelectParentTable, $sKeyField, self::DBGetKey($sParentClass));
			}
		}

		// Filter on objects referencing me
		foreach ($oFilter->GetCriteria_ReferencedBy() as $sForeignClass => $aKeysAndFilters)
		{
			foreach ($aKeysAndFilters as $sForeignKeyAttCode => $oForeignFilter)
			{
				$oForeignKeyAttDef = self::GetAttributeDef($sForeignClass, $sForeignKeyAttCode);
	
				// We don't want any attribute from the foreign class, just filter on an inner join
				$aExpAtts = array();
	
				self::DbgTrace("Referenced by foreign key: $sForeignKeyAttCode... let's call MakeQuery()");
				//self::DbgTrace($oForeignFilter);
				//self::DbgTrace($oForeignFilter->ToOQL());
				//self::DbgTrace($oSelectForeign);
				//self::DbgTrace($oSelectForeign->RenderSelect(array()));
				$oSelectForeign = self::MakeQuery($aSelectedClasses, $oConditionTree, $aClassAliases, $aTableAliases, $aTranslation, $oForeignFilter, $aExpAtts);

				$sForeignClassAlias = $oForeignFilter->GetFirstJoinedClassAlias();
				$sForeignKeyTable = $aTranslation[$sForeignClassAlias][$sForeignKeyAttCode][0];
				$sForeignKeyColumn = $aTranslation[$sForeignClassAlias][$sForeignKeyAttCode][1];
				$oSelectBase->AddInnerJoin($oSelectForeign, $sKeyField, $sForeignKeyColumn, $sForeignKeyTable);
			}
		}

		// Filter on related objects
		//
		foreach ($oFilter->GetCriteria_RelatedTo() as $aCritInfo)
		{
			$oSubFilter = $aCritInfo['flt'];
			$sRelCode = $aCritInfo['relcode'];
			$iMaxDepth = $aCritInfo['maxdepth'];

			// Get the starting point objects
			$oStartSet = new CMDBObjectSet($oSubFilter);

			// Get the objects related to those objects... recursively...
			$aRelatedObjs = $oStartSet->GetRelatedObjects($sRelCode, $iMaxDepth);
			$aRestriction = array_key_exists($sRootClass, $aRelatedObjs) ? $aRelatedObjs[$sRootClass] : array();

			// #@# todo - related objects and expressions...
			// Create condition
			if (count($aRestriction) > 0)
			{
				$oSelectBase->AddCondition($sKeyField.' IN ('.implode(', ', CMDBSource::Quote(array_keys($aRestriction), true)).')');
			}
			else
			{
				// Quick N'dirty -> generate an empty set
				$oSelectBase->AddCondition('false');
			}
		}

		// Translate the conditions... and go
		//
		if ($bIsOnQueriedClass)
		{
			$oConditionTranslated = $oConditionTree->Translate($aTranslation);
			$oSelectBase->SetCondition($oConditionTranslated);
		}

		// That's all... cross fingers and we'll get some working query

		//MyHelpers::var_dump_html($oSelectBase, true);
		//MyHelpers::var_dump_html($oSelectBase->RenderSelect(), true);
		if (self::$m_bDebugQuery) $oSelectBase->DisplayHtml();
		return $oSelectBase;
	}

	protected static function MakeQuerySingleTable($aSelectedClasses, &$oConditionTree, &$aClassAliases, &$aTableAliases, &$aTranslation, $oFilter, $sTableClass, $aExpectedAtts, $aExtKeys, $aValues)
	{
		// $aExpectedAtts is an array of sAttCode=>sAlias
		// $aExtKeys is an array of sTableClass => array of (sAttCode (keys) => array of sAttCode (fields))

		// Prepare the query for a single table (compound objects)
		// Ignores the items (attributes/filters) that are not on the target table
		// Perform an (inner or left) join for every external key (and specify the expected fields)
		//
		// Returns an SQLQuery
		//
		$sTargetClass = $oFilter->GetFirstJoinedClass();
		$sTargetAlias = $oFilter->GetFirstJoinedClassAlias();
		$sTable = self::DBGetTable($sTableClass);
		$sTableAlias = self::GenerateUniqueAlias($aTableAliases, $sTargetAlias.'_'.$sTable, $sTable);

		$bIsOnQueriedClass = array_key_exists($sTargetAlias, $aSelectedClasses);
		
		self::DbgTrace("Entering: tableclass=$sTableClass, filter=".$oFilter->ToOQL().", ".($bIsOnQueriedClass ? "MAIN" : "SECONDARY").", expectedatts=".count($aExpectedAtts).": ".implode(",", array_keys($aExpectedAtts)));

		// 1 - SELECT and UPDATE
		//
		// Note: no need for any values nor fields for foreign Classes (ie not the queried Class)
		//
		$aSelect = array();
		$aUpdateValues = array();

		// 1/a - Get the key
		//
		if ($bIsOnQueriedClass)
		{
			$aSelect[$aExpectedAtts['id'][0]] = new FieldExpression(self::DBGetKey($sTableClass), $sTableAlias);
		}
		// We need one pkey to be the key, let's take the one corresponding to the root class
		// (used to be based on the leaf, but it may happen that this one has no table defined)
		$sRootClass = self::GetRootClass($sTargetClass);
		if ($sTableClass == $sRootClass)
		{
			$aTranslation[$sTargetAlias]['id'] = array($sTableAlias, self::DBGetKey($sTableClass));
		}
	
		// 1/b - Get the other attributes
		// 
		foreach(self::ListAttributeDefs($sTableClass) as $sAttCode=>$oAttDef)
		{
			// Skip this attribute if not defined in this table
			if (self::$m_aAttribOrigins[$sTargetClass][$sAttCode] != $sTableClass) continue;

			// Skip this attribute if not writable (means that it does not correspond 
			if (count($oAttDef->GetSQLExpressions()) == 0) continue;

			// Update...
			//
			if ($bIsOnQueriedClass && array_key_exists($sAttCode, $aValues))
			{
				assert ($oAttDef->IsDirectField());
				foreach ($oAttDef->GetSQLValues($aValues[$sAttCode]) as $sColumn => $sValue)
				{
					$aUpdateValues[$sColumn] = $sValue;
				}
			}

			// Select...
			//
			// Skip, if a list of fields has been specified and it is not there
			if (!array_key_exists($sAttCode, $aExpectedAtts)) continue;

			if ($oAttDef->IsExternalField())
			{
				// skip, this will be handled in the joined tables
			}
			else
			{
				// standard field, or external key
				// add it to the output
				foreach ($oAttDef->GetSQLExpressions() as $sColId => $sSQLExpr)
				{
					foreach ($aExpectedAtts[$sAttCode] as $sAttAlias)
					{
						$aSelect[$sAttAlias.$sColId] = new FieldExpression($sSQLExpr, $sTableAlias);
					} 
				}
			}
		}

		// 2 - WHERE
		//
		foreach(self::$m_aFilterDefs[$sTargetClass] as $sFltCode => $oFltAtt)
		{
			// Skip this filter if not defined in this table
			if (self::$m_aFilterOrigins[$sTargetClass][$sFltCode] != $sTableClass) continue;

			// #@# todo - aller plus loin... a savoir que la table de translation doit contenir une "Expression"
			foreach($oFltAtt->GetSQLExpressions() as $sColID => $sFltExpr)
			{
				// Note: I did not test it with filters relying on several expressions...
				//       as long as sColdID is empty, this is working, otherwise... ?
				$aTranslation[$sTargetAlias][$sFltCode.$sColID] = array($sTableAlias, $sFltExpr);
			}
		}

		// #@# todo - See what a full text search condition should be
		// 2' - WHERE / Full text search condition
		//
		if ($bIsOnQueriedClass)
		{
			$aFullText = $oFilter->GetCriteria_FullText();
		}
		else
		{
			// Pourquoi ???
			$aFullText = array();
		}

		// 3 - The whole stuff, for this table only
		//
		$oSelectBase = new SQLQuery($sTable, $sTableAlias, $aSelect, null, $aFullText, $bIsOnQueriedClass, $aUpdateValues);

		// 4 - The external keys -> joins...
		//
		if (array_key_exists($sTableClass, $aExtKeys))
		{
			foreach ($aExtKeys[$sTableClass] as $sKeyAttCode => $aExtFields)
			{
				$oKeyAttDef = self::GetAttributeDef($sTargetClass, $sKeyAttCode);

				$oExtFilter = $oFilter->GetCriteria_PointingTo($sKeyAttCode);

				// In case the join was not explicitely defined in the filter,
				// we need to do it now
				if (empty($oExtFilter))
				{
					$sKeyClass =  $oKeyAttDef->GetTargetClass();
					$sKeyClassAlias = self::GenerateUniqueAlias($aClassAliases, $sKeyClass.'_'.$sKeyAttCode, $sKeyClass);
					$oExtFilter = new DBObjectSearch($sKeyClass, $sKeyClassAlias);
				}
				else
				{
					// The aliases should not conflict because normalization occured while building the filter
					$sKeyClass =  $oExtFilter->GetFirstJoinedClass();
					$sKeyClassAlias = $oExtFilter->GetFirstJoinedClassAlias();
					
					// Note: there is no search condition in $oExtFilter, because normalization did merge the condition onto the top of the filter tree 
				}

				// Specify expected attributes for the target class query
				// ... and use the current alias !
				$aExpAtts = array();
				$aIntermediateTranslation = array();
				foreach($aExtFields as $sAttCode => $oAtt)
				{

					$sExtAttCode = $oAtt->GetExtAttCode();
					if (array_key_exists($sAttCode, $aExpectedAtts))
					{
						// Request this attribute... transmit the alias !
						$aExpAtts[$sExtAttCode] = $aExpectedAtts[$sAttCode];
					}
					// Translate mainclass.extfield => remoteclassalias.remotefieldcode
					$oRemoteAttDef = self::GetAttributeDef($sKeyClass, $sExtAttCode);
					foreach ($oRemoteAttDef->GetSQLExpressions() as $sColID => $sRemoteAttExpr)
					{
						$aIntermediateTranslation[$sTargetAlias.$sColID][$sAttCode] = array($sKeyClassAlias, $sRemoteAttExpr);
					}
					//#@# debug - echo "<p>$sTargetAlias.$sAttCode to $sKeyClassAlias.$sRemoteAttExpr (class: $sKeyClass)</p>\n";
				}
				$oConditionTree = $oConditionTree->Translate($aIntermediateTranslation, false);

				self::DbgTrace("External key $sKeyAttCode (class: $sKeyClass), call MakeQuery()");
				$oSelectExtKey = self::MakeQuery($aSelectedClasses, $oConditionTree, $aClassAliases, $aTableAliases, $aTranslation, $oExtFilter, $aExpAtts);

				$sLocalKeyField = current($oKeyAttDef->GetSQLExpressions()); // get the first column for an external key
				$sExternalKeyField = self::DBGetKey($sKeyClass);
				self::DbgTrace("External key $sKeyAttCode, Join on $sLocalKeyField = $sExternalKeyField");
				if ($oKeyAttDef->IsNullAllowed())
				{
					$oSelectBase->AddLeftJoin($oSelectExtKey, $sLocalKeyField, $sExternalKeyField);
				}
				else
				{
					$oSelectBase->AddInnerJoin($oSelectExtKey, $sLocalKeyField, $sExternalKeyField);
				}
			}
		}

		//MyHelpers::var_dump_html($oSelectBase->RenderSelect());
		return $oSelectBase;
	}

	public static function GenerateUniqueAlias(&$aAliases, $sNewName, $sRealName)
	{
		if (!array_key_exists($sNewName, $aAliases))
		{
			$aAliases[$sNewName] = $sRealName;
			return $sNewName;
		}

		for ($i = 1 ; $i < 100 ; $i++)
		{
			$sAnAlias = $sNewName.$i;
			if (!array_key_exists($sAnAlias, $aAliases))
			{
				// Create that new alias
				$aAliases[$sAnAlias] = $sRealName;
				return $sAnAlias;
			}
		}
		throw new CoreException('Failed to create an alias', array('aliases' => $aAliases, 'new'=>$sNewName));
	}

	public static function CheckDefinitions()
	{
		if (count(self::GetClasses()) == 0)
		{
			throw new CoreException("MetaModel::InitClasses() has not been called, or no class has been declared ?!?!");
		}

		$aErrors = array();
		$aSugFix = array();
		foreach (self::GetClasses() as $sClass)
		{
			$sNameAttCode = self::GetNameAttributeCode($sClass);
			if (empty($sNameAttCode))
			{
			//  let's try this !!!
				// $aErrors[$sClass][] = "Missing value for name definition: the framework will (should...) replace it by the id";
				// $aSugFix[$sClass][] = "Expecting a value in ".implode(", ", self::GetAttributesList($sClass));
			}
			else if(!self::IsValidAttCode($sClass, $sNameAttCode))
			{
				$aErrors[$sClass][] = "Unkown attribute code '".$sNameAttCode."' for the name definition";
				$aSugFix[$sClass][] = "Expecting a value in ".implode(", ", self::GetAttributesList($sClass));
			}

			foreach(self::GetReconcKeys($sClass) as $sReconcKeyAttCode)
			{
				if (!empty($sReconcKeyAttCode) && !self::IsValidAttCode($sClass, $sReconcKeyAttCode))
				{
					$aErrors[$sClass][] = "Unkown attribute code '".$sReconcKeyAttCode."' in the list of reconciliation keys";
					$aSugFix[$sClass][] = "Expecting a value in ".implode(", ", self::GetAttributesList($sClass));
				}
			}

			$bHasWritableAttribute = false;
			foreach(self::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
			{
				// It makes no sense to check the attributes again and again in the subclasses
				if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass) continue;

				if ($oAttDef->IsExternalKey())
				{
					if (!self::IsValidClass($oAttDef->GetTargetClass()))
					{
						$aErrors[$sClass][] = "Unkown class '".$oAttDef->GetTargetClass()."' for the external key '$sAttCode'";
						$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetClasses())."}";
					}
				}
				elseif ($oAttDef->IsExternalField())
				{
					$sKeyAttCode = $oAttDef->GetKeyAttCode();
					if (!self::IsValidAttCode($sClass, $sKeyAttCode) || !self::IsValidKeyAttCode($sClass, $sKeyAttCode))
					{
						$aErrors[$sClass][] = "Unkown key attribute code '".$sKeyAttCode."' for the external field $sAttCode";
						$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetKeysList($sClass))."}";
					}
					else
					{
						$oKeyAttDef = self::GetAttributeDef($sClass, $sKeyAttCode);
						$sTargetClass = $oKeyAttDef->GetTargetClass();
						$sExtAttCode = $oAttDef->GetExtAttCode();
						if (!self::IsValidAttCode($sTargetClass, $sExtAttCode))
						{
							$aErrors[$sClass][] = "Unkown key attribute code '".$sExtAttCode."' for the external field $sAttCode";
							$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetKeysList($sTargetClass))."}";
						}
					}
				}
				else // standard attributes
				{
					// Check that the default values definition is a valid object!
					$oValSetDef = $oAttDef->GetValuesDef();
					if (!is_null($oValSetDef) && !$oValSetDef instanceof ValueSetDefinition)
					{
							$aErrors[$sClass][] = "Allowed values for attribute $sAttCode is not of the relevant type";
							$aSugFix[$sClass][] = "Please set it as an instance of a ValueSetDefinition object.";
					}
					else
					{
						// Default value must be listed in the allowed values (if defined)
						$aAllowedValues = self::GetAllowedValues_att($sClass, $sAttCode);
						if (!is_null($aAllowedValues))
						{
							$sDefaultValue = $oAttDef->GetDefaultValue();
							if (!array_key_exists($sDefaultValue, $aAllowedValues))
							{
								$aErrors[$sClass][] = "Default value '".$sDefaultValue."' for attribute $sAttCode is not an allowed value";
								$aSugFix[$sClass][] = "Please pickup the default value out of {'".implode(", ", array_keys($aAllowedValues))."'}";
							}
						}
					}
				}
				// Check dependencies
				if ($oAttDef->IsWritable())
				{
					$bHasWritableAttribute = true;
					foreach ($oAttDef->GetPrerequisiteAttributes() as $sDependOnAttCode)
					{
						if (!self::IsValidAttCode($sClass, $sDependOnAttCode))
						{
							$aErrors[$sClass][] = "Unkown attribute code '".$sDependOnAttCode."' in the list of prerequisite attributes";
							$aSugFix[$sClass][] = "Expecting a value in ".implode(", ", self::GetAttributesList($sClass));
						}
					}
				}
			}
			foreach(self::GetClassFilterDefs($sClass) as $sFltCode=>$oFilterDef)
			{
				if (method_exists($oFilterDef, '__GetRefAttribute'))
				{ 
					$oAttDef = $oFilterDef->__GetRefAttribute();
					if (!self::IsValidAttCode($sClass, $oAttDef->GetCode()))
					{
						$aErrors[$sClass][] = "Wrong attribute code '".$oAttDef->GetCode()."' (wrong class) for the \"basic\" filter $sFltCode";
						$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetAttributesList($sClass))."}";
					}
				}
			}

			// Lifecycle
			//
			$sStateAttCode = self::GetStateAttributeCode($sClass);
			if (strlen($sStateAttCode) > 0)
			{
				// Lifecycle - check that the state attribute does exist as an attribute
				if (!self::IsValidAttCode($sClass, $sStateAttCode))
				{
					$aErrors[$sClass][] = "Unkown attribute code '".$sStateAttCode."' for the state definition";
					$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetAttributesList($sClass))."}";
				}
				else
				{
					// Lifecycle - check that there is a value set constraint on the state attribute
					$aAllowedValuesRaw = self::GetAllowedValues_att($sClass, $sStateAttCode);
					$aStates = array_keys(self::EnumStates($sClass));
					if (is_null($aAllowedValuesRaw))
					{
						$aErrors[$sClass][] = "Attribute '".$sStateAttCode."' will reflect the state of the object. It must be restricted to a set of values";
						$aSugFix[$sClass][] = "Please define its allowed_values property as [new ValueSetEnum('".implode(", ", $aStates)."')]";
					}
					else
					{
						$aAllowedValues = array_keys($aAllowedValuesRaw);
	
						// Lifecycle - check the the state attribute allowed values are defined states
						foreach($aAllowedValues as $sValue)
						{
							if (!in_array($sValue, $aStates))
							{
								$aErrors[$sClass][] = "Attribute '".$sStateAttCode."' (object state) has an allowed value ($sValue) which is not a known state";
								$aSugFix[$sClass][] = "You may define its allowed_values property as [new ValueSetEnum('".implode(", ", $aStates)."')], or reconsider the list of states";
							}
						}
	
						// Lifecycle - check that defined states are allowed values
						foreach($aStates as $sStateValue)
						{
							if (!in_array($sStateValue, $aAllowedValues))
							{
								$aErrors[$sClass][] = "Attribute '".$sStateAttCode."' (object state) has a state ($sStateValue) which is not an allowed value";
								$aSugFix[$sClass][] = "You may define its allowed_values property as [new ValueSetEnum('".implode(", ", $aStates)."')], or reconsider the list of states";
							}
						}
					}
	
					// Lifcycle - check that the action handlers are defined
					foreach (self::EnumStates($sClass) as $sStateCode => $aStateDef)
					{
						foreach(self::EnumTransitions($sClass, $sStateCode) as $sStimulusCode => $aTransitionDef)
						{
							foreach ($aTransitionDef['actions'] as $sActionHandler)
							{
								if (!method_exists($sClass, $sActionHandler))
								{
									$aErrors[$sClass][] = "Unknown function '$sActionHandler' in transition [$sStateCode/$sStimulusCode] for state attribute '$sStateAttCode'";
									$aSugFix[$sClass][] = "Specify a function which prototype is in the form [public function $sActionHandler(\$sStimulusCode){return true;}]";
								}
							}
						}
					}
				}
			}

			if ($bHasWritableAttribute)
			{
				if (!self::HasTable($sClass))
				{
					$aErrors[$sClass][] = "No table has been defined for this class";
					$aSugFix[$sClass][] = "Either define a table name or move the attributes elsewhere";
				}
			}


			// ZList
			//
			foreach(self::EnumZLists() as $sListCode)
			{
				foreach (self::GetZListItems($sClass, $sListCode) as $sMyAttCode)
				{
					if (!self::IsValidAttCode($sClass, $sMyAttCode))
					{
						$aErrors[$sClass][] = "Unkown attribute code '".$sMyAttCode."' from ZList '$sListCode'";
						$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetAttributesList($sClass))."}";
					}
				}
			}
		}
		if (count($aErrors) > 0)
		{
			echo "<div style=\"width:100%;padding:10px;background:#FFAAAA;display:;\">";
			echo "<h3>Business model inconsistencies have been found</h3>\n";
			// #@# later -> this is the responsibility of the caller to format the output
			foreach ($aErrors as $sClass => $aMessages)
			{
				echo "<p>Wrong declaration for class <b>$sClass</b></p>\n";
				echo "<ul class=\"treeview\">\n";
				$i = 0;
				foreach ($aMessages as $sMsg)
				{
					echo "<li>$sMsg ({$aSugFix[$sClass][$i]})</li>\n";
					$i++;
				}
				echo "</ul>\n";
			}
			echo "<p>Aborting...</p>\n";
			echo "</div>\n";
			exit;
		}
	}

	public static function DBShowApplyForm($sRepairUrl, $sSQLStatementArgName, $aSQLFixes)
	{
		if (empty($sRepairUrl)) return;

		// By design, some queries might be blank, we have to ignore them
		$aCleanFixes = array();
		foreach($aSQLFixes as $sSQLFix)
		{
			if (!empty($sSQLFix))
			{
				$aCleanFixes[] = $sSQLFix;
			}
		}
		if (count($aCleanFixes) == 0) return;

		echo "<form action=\"$sRepairUrl\" method=\"POST\">\n";
		echo "   <input type=\"hidden\" name=\"$sSQLStatementArgName\" value=\"".htmlentities(implode("##SEP##", $aCleanFixes))."\">\n";
		echo "   <input type=\"submit\" value=\" Apply changes (".count($aCleanFixes)." queries) \">\n";
		echo "</form>\n";
	}

	public static function DBExists($bMustBeComplete = true)
	{
		// returns true if at least one table exists
		//

		if (!CMDBSource::IsDB(self::$m_sDBName))
		{
			return false;
		}
		CMDBSource::SelectDB(self::$m_sDBName);

		$aFound = array();
		$aMissing = array();
		foreach (self::DBEnumTables() as $sTable => $aClasses)
		{
			if (CMDBSource::IsTable($sTable))
			{
				$aFound[] = $sTable;
			}
			else
			{
				$aMissing[] = $sTable;
			}
		}

		if (count($aFound) == 0)
		{
			// no expected table has been found
			return false;
		}
		else
		{
			if (count($aMissing) == 0)
			{
				// the database is complete (still, could be some fields missing!)
				return true;
			}
			else
			{
				// not all the tables, could be an older version
				if ($bMustBeComplete)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		}
	}

	public static function DBDrop()
	{
		$bDropEntireDB = true;

		if (!empty(self::$m_sTablePrefix))
		{
			// Do drop only tables corresponding to the sub-database (table prefix)
			//           then possibly drop the DB itself (if no table remain)
			foreach (CMDBSource::EnumTables() as $sTable)
			{
				// perform a case insensitive test because on Windows the table names become lowercase :-(
				if (strtolower(substr($sTable, 0, strlen(self::$m_sTablePrefix))) == strtolower(self::$m_sTablePrefix))
				{
					CMDBSource::DropTable($sTable);
				}
				else
				{
					// There is at least one table which is out of the scope of the current application
					$bDropEntireDB = false;
				}
			}
		}

		if ($bDropEntireDB)
		{
			CMDBSource::DropDB(self::$m_sDBName);
		}
	}


	public static function DBCreate()
	{
		// Note: we have to check if the DB does exist, because we may share the DB
		//       with other applications (in which case the DB does exist, not the tables with the given prefix)
		if (!CMDBSource::IsDB(self::$m_sDBName))
		{
			CMDBSource::CreateDB(self::$m_sDBName);
		}
		self::DBCreateTables();
		self::DBCreateViews();
	}

	protected static function DBCreateTables()
	{
		list($aErrors, $aSugFix) = self::DBCheckFormat();

		$aSQL = array();
		foreach ($aSugFix as $sClass => $aTarget)
		{
			foreach ($aTarget as $aQueries)
			{
				foreach ($aQueries as $sQuery)
				{
					if (!empty($sQuery))
					{
						//$aSQL[] = $sQuery;
						// forces a refresh of cached information
						CMDBSource::CreateTable($sQuery);
					}
				}
			}
		}
		// does not work -how to have multiple statements in a single query?
		// $sDoCreateAll = implode(" ; ", $aSQL);
	}

	protected static function DBCreateViews()
	{
		list($aErrors, $aSugFix) = self::DBCheckViews();

		$aSQL = array();
		foreach ($aSugFix as $sClass => $aTarget)
		{
			foreach ($aTarget as $aQueries)
			{
				foreach ($aQueries as $sQuery)
				{
					if (!empty($sQuery))
					{
						//$aSQL[] = $sQuery;
						// forces a refresh of cached information
						CMDBSource::CreateTable($sQuery);
					}
				}
			}
		}
	}

	public static function DBDump()
	{
		$aDataDump = array();
		foreach (self::DBEnumTables() as $sTable => $aClasses)
		{
			$aRows = CMDBSource::DumpTable($sTable);
			$aDataDump[$sTable] = $aRows;
		}
		return $aDataDump;
	}

	protected static function MakeDictEntry($sKey, $sValueFromOldSystem, $sDefaultValue, &$bNotInDico)
	{
		$sValue = Dict::S($sKey, 'x-no-nothing');
		if ($sValue == 'x-no-nothing')
		{
			$bNotInDico = true;
			$sValue = $sValueFromOldSystem;
			if (strlen($sValue) == 0)
			{
				$sValue = $sDefaultValue;
			}
		}
		return "	'$sKey' => '".str_replace("'", "\\'", $sValue)."',\n";
	}

	public static function MakeDictionaryTemplate($sModules = '', $sOutputFilter = 'NotInDictionary')
	{
		$sRes = '';

		$sRes .= "// Dictionnay conventions\n";
		$sRes .= htmlentities("// Class:<class_name>\n");
		$sRes .= htmlentities("// Class:<class_name>+\n");
		$sRes .= htmlentities("// Class:<class_name>/Attribute:<attribute_code>\n");
		$sRes .= htmlentities("// Class:<class_name>/Attribute:<attribute_code>+\n");
		$sRes .= htmlentities("// Class:<class_name>/Attribute:<attribute_code>/Value:<value>\n");
		$sRes .= htmlentities("// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+\n");
		$sRes .= htmlentities("// Class:<class_name>/Stimulus:<stimulus_code>\n");
		$sRes .= htmlentities("// Class:<class_name>/Stimulus:<stimulus_code>+\n");
		$sRes .= "\n";

		// Note: I did not use EnumCategories(), because a given class maybe found in several categories
		// Need to invent the "module", to characterize the origins of a class
		if (strlen($sModules) == 0)
		{
			$aModules = array('bizmodel', 'core/cmdb', 'gui' , 'application', 'addon/userrights');
		}
		else
		{
			$aModules = explode(', ', $sModules);
		}

		$sRes .= "//////////////////////////////////////////////////////////////////////\n";
		$sRes .= "// Note: The classes have been grouped by categories: ".implode(', ', $aModules)."\n";
		$sRes .= "//////////////////////////////////////////////////////////////////////\n";

		foreach ($aModules as $sCategory)
		{
			$sRes .= "//////////////////////////////////////////////////////////////////////\n";
			$sRes .= "// Classes in '<em>$sCategory</em>'\n";
			$sRes .= "//////////////////////////////////////////////////////////////////////\n";
			$sRes .= "//\n";
			$sRes .= "\n";
			foreach (self::GetClasses($sCategory) as $sClass)
			{
				if (!self::HasTable($sClass)) continue;
	
				$bNotInDico = false;

				$sClassRes = "//\n";
				$sClassRes .= "// Class: $sClass\n";
				$sClassRes .= "//\n";
				$sClassRes .= "\n";
				$sClassRes .= "Dict::Add('EN US', 'English', 'English', array(\n";
				$sClassRes .= self::MakeDictEntry("Class:$sClass", self::GetName_Obsolete($sClass), $sClass, $bNotInDico);
				$sClassRes .= self::MakeDictEntry("Class:$sClass+", self::GetClassDescription_Obsolete($sClass), '', $bNotInDico);
				foreach(self::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
				{
					// Skip this attribute if not originaly defined in this class
					if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass) continue;
	
					$sClassRes .= self::MakeDictEntry("Class:$sClass/Attribute:$sAttCode", $oAttDef->GetLabel_Obsolete(), $sAttCode, $bNotInDico);
					$sClassRes .= self::MakeDictEntry("Class:$sClass/Attribute:$sAttCode+", $oAttDef->GetDescription_Obsolete(), '', $bNotInDico);
					if ($oAttDef instanceof AttributeEnum)
					{
						if (self::GetStateAttributeCode($sClass) == $sAttCode)
						{
							foreach (self::EnumStates($sClass) as $sStateCode => $aStateData)
							{
								if (array_key_exists('label', $aStateData))
								{
									$sValue = $aStateData['label'];
								}
								else
								{
									$sValue = MetaModel::GetStateLabel($sClass, $sStateCode);
								}
								if (array_key_exists('description', $aStateData))
								{
									$sValuePlus = $aStateData['description'];
								}
								else
								{
									$sValuePlus = MetaModel::GetStateDescription($sClass, $sStateCode);
								}
								$sClassRes .= self::MakeDictEntry("Class:$sClass/Attribute:$sAttCode/Value:$sStateCode", $sValue, '', $bNotInDico);
								$sClassRes .= self::MakeDictEntry("Class:$sClass/Attribute:$sAttCode/Value:$sStateCode+", $sValuePlus, '', $bNotInDico);
							}
						}
						else
						{
							foreach ($oAttDef->GetAllowedValues() as $sKey => $value)
							{
								$sClassRes .= self::MakeDictEntry("Class:$sClass/Attribute:$sAttCode/Value:$sKey", $value, '', $bNotInDico);
								$sClassRes .= self::MakeDictEntry("Class:$sClass/Attribute:$sAttCode/Value:$sKey+", $value, '', $bNotInDico);
							}
						}
					}
				}
				foreach(self::EnumStimuli($sClass) as $sStimulusCode => $oStimulus)
				{
					$sClassRes .= self::MakeDictEntry("Class:$sClass/Stimulus:$sStimulusCode", $oStimulus->GetLabel_Obsolete(), '', $bNotInDico);
					$sClassRes .= self::MakeDictEntry("Class:$sClass/Stimulus:$sStimulusCode+", $oStimulus->GetDescription_Obsolete(), '', $bNotInDico);
				}
	
				$sClassRes .= "));\n";
				$sClassRes .= "\n";

				if ($bNotInDico  || ($sOutputFilter != 'NotInDictionary'))
				{
					$sRes .= $sClassRes;
				}
			}
		}
		return $sRes;
	}

	public static function DBCheckFormat()
	{
		$aErrors = array();
		$aSugFix = array();
		foreach (self::GetClasses() as $sClass)
		{
			if (!self::HasTable($sClass)) continue;

			// Check that the table exists
			//
			$sTable = self::DBGetTable($sClass);
			$sKeyField = self::DBGetKey($sClass);
			$sAutoIncrement = (self::IsAutoIncrementKey($sClass) ? "AUTO_INCREMENT" : "");
			if (!CMDBSource::IsTable($sTable))
			{
				$aErrors[$sClass]['*'][] = "table '$sTable' could not be found into the DB";
				$aSugFix[$sClass]['*'][] = "CREATE TABLE `$sTable` (`$sKeyField` INT(11) NOT NULL $sAutoIncrement PRIMARY KEY) ENGINE = innodb CHARACTER SET utf8 COLLATE utf8_unicode_ci";
			}
			// Check that the key field exists
			//
			elseif (!CMDBSource::IsField($sTable, $sKeyField))
			{
				$aErrors[$sClass]['id'][] = "key '$sKeyField' (table $sTable) could not be found";
				$aSugFix[$sClass]['id'][] = "ALTER TABLE `$sTable` ADD `$sKeyField` INT(11) NOT NULL $sAutoIncrement PRIMARY KEY";
			}
			else
			{
				// Check the key field properties
				//
				if (!CMDBSource::IsKey($sTable, $sKeyField))
				{
					$aErrors[$sClass]['id'][] = "key '$sKeyField' is not a key for table '$sTable'";
					$aSugFix[$sClass]['id'][] = "ALTER TABLE `$sTable`, DROP PRIMARY KEY, ADD PRIMARY key(`$sKeyField`)";
				}
				if (self::IsAutoIncrementKey($sClass) && !CMDBSource::IsAutoIncrement($sTable, $sKeyField))
				{
					$aErrors[$sClass]['id'][] = "key '$sKeyField' (table $sTable) is not automatically incremented";
					$aSugFix[$sClass]['id'][] = "ALTER TABLE `$sTable` CHANGE `$sKeyField` `$sKeyField` INT(11) NOT NULL AUTO_INCREMENT";
				}
			}
			
			// Check that any defined field exists
			//
			$aTableInfo = CMDBSource::GetTableInfo($sTable);

			foreach(self::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
			{
				// Skip this attribute if not originaly defined in this class
				if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass) continue;

				foreach($oAttDef->GetSQLColumns() as $sField => $sDBFieldType)
				{
					$bIndexNeeded = $oAttDef->RequiresIndex();
					$sFieldSpecs = $oAttDef->IsNullAllowed() ? "$sDBFieldType NULL" : "$sDBFieldType NOT NULL";
					if (!CMDBSource::IsField($sTable, $sField))
					{
						$aErrors[$sClass][$sAttCode][] = "field '$sField' could not be found in table '$sTable'";
						$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` ADD `$sField` $sFieldSpecs";
						if ($bIndexNeeded)
						{
							$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` ADD INDEX (`$sField`)";
						}
					}
					else
					{
						// The field already exists, does it have the relevant properties?
						//
						$bToBeChanged = false;
						if ($oAttDef->IsNullAllowed() != CMDBSource::IsNullAllowed($sTable, $sField))
						{
							$bToBeChanged  = true;
							if ($oAttDef->IsNullAllowed())
							{
								$aErrors[$sClass][$sAttCode][] = "field '$sField' in table '$sTable' could be NULL";
							}
							else
							{
								$aErrors[$sClass][$sAttCode][] = "field '$sField' in table '$sTable' could NOT be NULL";
							}
						}
						$sActualFieldType = CMDBSource::GetFieldType($sTable, $sField);
						if (strcasecmp($sDBFieldType, $sActualFieldType) != 0)
						{
							$bToBeChanged  = true;
							$aErrors[$sClass][$sAttCode][] = "field '$sField' in table '$sTable' has a wrong type: found '$sActualFieldType' while expecting '$sDBFieldType'";
						} 
						if ($bToBeChanged)
						{
							$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` CHANGE `$sField` `$sField` $sFieldSpecs";
						}

						// Create indexes (external keys only... so far)
						//
						if ($bIndexNeeded && !CMDBSource::HasIndex($sTable, $sField))
						{
							$aErrors[$sClass][$sAttCode][] = "Foreign key '$sField' in table '$sTable' should have an index";
							$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` ADD INDEX (`$sField`)";
						}
					}
				}
			}
		}
		return array($aErrors, $aSugFix);
	}

	public static function DBCheckViews()
	{
		$aErrors = array();
		$aSugFix = array();

		// Reporting views (must be created after any other table)
		//
		foreach (self::GetClasses('bizmodel') as $sClass)
		{
			$sView = "view_$sClass";
			if (CMDBSource::IsTable($sView))
			{
				// Check that the view is complete
				//
				$bIsComplete = true;
				foreach(self::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
				{
					foreach($oAttDef->GetSQLExpressions() as $sSuffix => $sTrash)
					{
						$sCol = $sAttCode.$sSuffix;
						if (!CMDBSource::IsField($sView, $sCol))
						{
							$bIsComplete = false;
							$aErrors[$sClass][$sAttCode][] = "field '$sCol' could not be found in view '$sView'";
							$aSugFix[$sClass][$sAttCode][] = "";
						}
					}
				}
				if (!$bIsComplete)
				{
					// Rework the view
					//
					$oFilter = new DBObjectSearch($sClass, '');
					$sSQL = self::MakeSelectQuery($oFilter);
					$aErrors[$sClass]['*'][] = "View '$sView' is currently not complete";
					$aSugFix[$sClass]['*'][] = "ALTER VIEW `$sView` AS $sSQL";
				}
			}
			else
			{
				// Create the view
				//
				$oFilter = new DBObjectSearch($sClass, '');
				$sSQL = self::MakeSelectQuery($oFilter);
				$aErrors[$sClass]['*'][] = "Missing view for class: $sClass";
				$aSugFix[$sClass]['*'][] = "CREATE VIEW `$sView` AS $sSQL";
			}
		}
		return array($aErrors, $aSugFix);
	}

	private static function DBCheckIntegrity_Check2Delete($sSelWrongRecs, $sErrorDesc, $sClass, &$aErrorsAndFixes, &$iNewDelCount, &$aPlannedDel, $bProcessingFriends = false)
	{
		$sRootClass = self::GetRootClass($sClass);
		$sTable = self::DBGetTable($sClass);
		$sKeyField = self::DBGetKey($sClass);

		if (array_key_exists($sTable, $aPlannedDel) && count($aPlannedDel[$sTable]) > 0)
		{
			$sSelWrongRecs .= " AND maintable.`$sKeyField` NOT IN ('".implode("', '", $aPlannedDel[$sTable])."')";
		}
		$aWrongRecords = CMDBSource::QueryToCol($sSelWrongRecs, "id");
		if (count($aWrongRecords) == 0) return;

		if (!array_key_exists($sRootClass, $aErrorsAndFixes)) $aErrorsAndFixes[$sRootClass] = array();
		if (!array_key_exists($sTable, $aErrorsAndFixes[$sRootClass])) $aErrorsAndFixes[$sRootClass][$sTable] = array();

		foreach ($aWrongRecords as $iRecordId)
		{
			if (array_key_exists($iRecordId, $aErrorsAndFixes[$sRootClass][$sTable]))
			{
				switch ($aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action'])
				{
				case 'Delete':
					// Already planned for a deletion
					// Let's concatenate the errors description together
					$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Reason'] .= ', '.$sErrorDesc;
					break;

				case 'Update':
					// Let's plan a deletion
					break;
				}
			}
			else
			{
				$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Reason'] = $sErrorDesc;
			}

			if (!$bProcessingFriends)
			{
				if (!array_key_exists($sTable, $aPlannedDel) || !in_array($iRecordId, $aPlannedDel[$sTable]))
				{
					// Something new to be deleted...
					$iNewDelCount++;
				}
			}

			$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action'] = 'Delete';
			$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action_Details'] = array();
			$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Pass'] = 123;
			$aPlannedDel[$sTable][] = $iRecordId;
		}

		// Now make sure that we would delete the records of the other tables for that class
		//
		if (!$bProcessingFriends)
		{
			$sDeleteKeys = "'".implode("', '", $aWrongRecords)."'";
			foreach (self::EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_ALL) as $sFriendClass)
			{
				$sFriendTable = self::DBGetTable($sFriendClass);
				$sFriendKey = self::DBGetKey($sFriendClass);
	
				// skip the current table
				if ($sFriendTable == $sTable) continue; 
	
				$sFindRelatedRec = "SELECT DISTINCT maintable.`$sFriendKey` AS id FROM `$sFriendTable` AS maintable WHERE maintable.`$sFriendKey` IN ($sDeleteKeys)";
				self::DBCheckIntegrity_Check2Delete($sFindRelatedRec, "Cascading deletion of record in friend table `<em>$sTable</em>`", $sFriendClass, $aErrorsAndFixes, $iNewDelCount, $aPlannedDel, true);
			}
		}
	}

	private static function DBCheckIntegrity_Check2Update($sSelWrongRecs, $sErrorDesc, $sColumn, $sNewValue, $sClass, &$aErrorsAndFixes, &$iNewDelCount, &$aPlannedDel)
	{
		$sRootClass = self::GetRootClass($sClass);
		$sTable = self::DBGetTable($sClass);
		$sKeyField = self::DBGetKey($sClass);

		if (array_key_exists($sTable, $aPlannedDel) && count($aPlannedDel[$sTable]) > 0)
		{
			$sSelWrongRecs .= " AND maintable.`$sKeyField` NOT IN ('".implode("', '", $aPlannedDel[$sTable])."')";
		}
		$aWrongRecords = CMDBSource::QueryToCol($sSelWrongRecs, "id");
		if (count($aWrongRecords) == 0) return;

		if (!array_key_exists($sRootClass, $aErrorsAndFixes)) $aErrorsAndFixes[$sRootClass] = array();
		if (!array_key_exists($sTable, $aErrorsAndFixes[$sRootClass])) $aErrorsAndFixes[$sRootClass][$sTable] = array();

		foreach ($aWrongRecords as $iRecordId)
		{
			if (array_key_exists($iRecordId, $aErrorsAndFixes[$sRootClass][$sTable]))
			{
				switch ($aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action'])
				{
				case 'Delete':
				// No need to update, the record will be deleted!
				break;

				case 'Update':
				// Already planned for an update
				// Add this new update spec to the list
				$bFoundSameSpec = false;
				foreach ($aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action_Details'] as $aUpdateSpec)
				{
					if (($sColumn == $aUpdateSpec['column']) && ($sNewValue == $aUpdateSpec['newvalue']))
					{
						$bFoundSameSpec = true;
					}
				}
				if (!$bFoundSameSpec)
				{
					$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action_Details'][] = (array('column' => $sColumn, 'newvalue'=>$sNewValue));
					$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Reason'] .= ', '.$sErrorDesc;
				}
				break;
				}
			}
			else
			{
				$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Reason'] = $sErrorDesc;
				$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action'] = 'Update';
				$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action_Details'] = array(array('column' => $sColumn, 'newvalue'=>$sNewValue));
				$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Pass'] = 123;
			}

		}
	}

	// returns the count of records found for deletion
	public static function DBCheckIntegrity_SinglePass(&$aErrorsAndFixes, &$iNewDelCount, &$aPlannedDel)
	{
		foreach (self::GetClasses() as $sClass)
		{
			if (!self::HasTable($sClass)) continue;
			$sRootClass = self::GetRootClass($sClass);
			$sTable = self::DBGetTable($sClass);
			$sKeyField = self::DBGetKey($sClass);

			if (!self::IsStandaloneClass($sClass))
			{
				if (self::IsRootClass($sClass))
				{
					// Check that the final class field contains the name of a class which inherited from the current class
					//
					$sFinalClassField = self::DBGetClassField($sClass);
	
					$aAllowedValues = self::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL);
					$sAllowedValues = implode(",", CMDBSource::Quote($aAllowedValues, true));
	
					$sSelWrongRecs = "SELECT DISTINCT maintable.`$sKeyField` AS id FROM `$sTable` AS maintable WHERE `$sFinalClassField` NOT IN ($sAllowedValues)";
					self::DBCheckIntegrity_Check2Delete($sSelWrongRecs, "final class (field `<em>$sFinalClassField</em>`) is wrong (expected a value in {".$sAllowedValues."})", $sClass, $aErrorsAndFixes, $iNewDelCount, $aPlannedDel);
				}
				else
				{
					$sRootTable = self::DBGetTable($sRootClass);
					$sRootKey = self::DBGetKey($sRootClass);
					$sFinalClassField = self::DBGetClassField($sRootClass);
	
					$aExpectedClasses = self::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL);
					$sExpectedClasses = implode(",", CMDBSource::Quote($aExpectedClasses, true));
	
					// Check that any record found here has its counterpart in the root table
					// and which refers to a child class
					//
					$sSelWrongRecs = "SELECT DISTINCT maintable.`$sKeyField` AS id FROM `$sTable` as maintable LEFT JOIN `$sRootTable` ON maintable.`$sKeyField` = `$sRootTable`.`$sRootKey` AND `$sRootTable`.`$sFinalClassField` IN ($sExpectedClasses) WHERE `$sRootTable`.`$sRootKey` IS NULL";
					self::DBCheckIntegrity_Check2Delete($sSelWrongRecs, "Found a record in `<em>$sTable</em>`, but no counterpart in root table `<em>$sRootTable</em>` (inc. records pointing to a class in {".$sExpectedClasses."})", $sClass, $aErrorsAndFixes, $iNewDelCount, $aPlannedDel);
	
					// Check that any record found in the root table and referring to a child class
					// has its counterpart here (detect orphan nodes -root or in the middle of the hierarchy)
					//
					$sSelWrongRecs = "SELECT DISTINCT maintable.`$sRootKey` AS id FROM `$sRootTable` AS maintable LEFT JOIN `$sTable` ON maintable.`$sRootKey` = `$sTable`.`$sKeyField` WHERE `$sTable`.`$sKeyField` IS NULL AND maintable.`$sFinalClassField` IN ($sExpectedClasses)";
					self::DBCheckIntegrity_Check2Delete($sSelWrongRecs, "Found a record in root table `<em>$sRootTable</em>`, but no counterpart in table `<em>$sTable</em>` (root records pointing to a class in {".$sExpectedClasses."})", $sRootClass, $aErrorsAndFixes, $iNewDelCount, $aPlannedDel);
				}
			}

			foreach(self::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
			{
				// Skip this attribute if not defined in this table
				if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass) continue;

				if ($oAttDef->IsExternalKey())
				{
					// Check that any external field is pointing to an existing object
					//
					$sRemoteClass = $oAttDef->GetTargetClass();
					$sRemoteTable = self::DBGetTable($sRemoteClass);
					$sRemoteKey = self::DBGetKey($sRemoteClass);

					$sExtKeyField = current($oAttDef->GetSQLExpressions()); // get the first column for an external key

					// Note: a class/table may have an external key on itself
					$sSelBase = "SELECT DISTINCT maintable.`$sKeyField` AS id, maintable.`$sExtKeyField` AS extkey FROM `$sTable` AS maintable LEFT JOIN `$sRemoteTable` ON maintable.`$sExtKeyField` = `$sRemoteTable`.`$sRemoteKey`";

					$sSelWrongRecs = $sSelBase." WHERE `$sRemoteTable`.`$sRemoteKey` IS NULL";
					if ($oAttDef->IsNullAllowed())
					{
						// Exclude the records pointing to 0/null from the errors
						$sSelWrongRecs .= " AND maintable.`$sExtKeyField` IS NOT NULL";
						$sSelWrongRecs .= " AND maintable.`$sExtKeyField` != 0";
						self::DBCheckIntegrity_Check2Update($sSelWrongRecs, "Record pointing to (external key '<em>$sAttCode</em>') non existing objects", $sExtKeyField, 'null', $sClass, $aErrorsAndFixes, $iNewDelCount, $aPlannedDel);
					}
					else
					{
						self::DBCheckIntegrity_Check2Delete($sSelWrongRecs, "Record pointing to (external key '<em>$sAttCode</em>') non existing objects", $sClass, $aErrorsAndFixes, $iNewDelCount, $aPlannedDel);
					}

					// Do almost the same, taking into account the records planned for deletion
					if (array_key_exists($sRemoteTable, $aPlannedDel) && count($aPlannedDel[$sRemoteTable]) > 0)
					{
						// This could be done by the mean of a 'OR ... IN (aIgnoreRecords)
						// but in that case you won't be able to track the root cause (cascading)
						$sSelWrongRecs = $sSelBase." WHERE maintable.`$sExtKeyField` IN ('".implode("', '", $aPlannedDel[$sRemoteTable])."')";
						if ($oAttDef->IsNullAllowed())
						{
							// Exclude the records pointing to 0/null from the errors
							$sSelWrongRecs .= " AND maintable.`$sExtKeyField` IS NOT NULL";
							$sSelWrongRecs .= " AND maintable.`$sExtKeyField` != 0";
							self::DBCheckIntegrity_Check2Update($sSelWrongRecs, "Record pointing to (external key '<em>$sAttCode</em>') a record planned for deletion", $sExtKeyField, 'null', $sClass, $aErrorsAndFixes, $iNewDelCount, $aPlannedDel);
						}
						else
						{
							self::DBCheckIntegrity_Check2Delete($sSelWrongRecs, "Record pointing to (external key '<em>$sAttCode</em>') a record planned for deletion", $sClass, $aErrorsAndFixes, $iNewDelCount, $aPlannedDel);
						}
					}
				}
				else if ($oAttDef->IsDirectField())
				{
					// Check that the values fit the allowed values
					//
					$aAllowedValues = self::GetAllowedValues_att($sClass, $sAttCode);
					if (!is_null($aAllowedValues) && count($aAllowedValues) > 0)
					{
						$sExpectedValues = implode(",", CMDBSource::Quote(array_keys($aAllowedValues), true));
	
						$sMyAttributeField = current($oAttDef->GetSQLExpressions()); // get the first column for the moment
						$sDefaultValue = $oAttDef->GetDefaultValue();
						$sSelWrongRecs = "SELECT DISTINCT maintable.`$sKeyField` AS id FROM `$sTable` AS maintable WHERE maintable.`$sMyAttributeField` NOT IN ($sExpectedValues)";
						self::DBCheckIntegrity_Check2Update($sSelWrongRecs, "Record having a column ('<em>$sAttCode</em>') with an unexpected value", $sMyAttributeField, CMDBSource::Quote($sDefaultValue), $sClass, $aErrorsAndFixes, $iNewDelCount, $aPlannedDel);
					}
				}
			}
		}
	}

	public static function DBCheckIntegrity($sRepairUrl = "", $sSQLStatementArgName = "")
	{
		// Records in error, and action to be taken: delete or update
		// by RootClass/Table/Record
		$aErrorsAndFixes = array();

		// Records to be ignored in the current/next pass
		// by Table = array of RecordId
		$aPlannedDel = array();
	
		// Count of errors in the next pass: no error means that we can leave...
		$iErrorCount = 0;
		// Limit in case of a bug in the algorythm
		$iLoopCount = 0;

		$iNewDelCount = 1; // startup...
		while ($iNewDelCount > 0)
		{
			$iNewDelCount = 0;
			self::DBCheckIntegrity_SinglePass($aErrorsAndFixes, $iNewDelCount, $aPlannedDel);
			$iErrorCount += $iNewDelCount;

			// Safety net #1 - limit the planned deletions
			//
			$iMaxDel = 1000;
			$iPlannedDel = 0;
			foreach ($aPlannedDel as $sTable => $aPlannedDelOnTable)
			{
				$iPlannedDel += count($aPlannedDelOnTable);
			}
			if ($iPlannedDel > $iMaxDel)
			{
				throw new CoreWarning("DB Integrity Check safety net - Exceeding the limit of $iMaxDel planned record deletion");
				break;
			}
			// Safety net #2 - limit the iterations
			//
			$iLoopCount++;
			$iMaxLoops = 10;
			if ($iLoopCount > $iMaxLoops)
			{
				throw new CoreWarning("DB Integrity Check safety net - Reached the limit of $iMaxLoops loops");
				break;
			}
		}

		// Display the results
		//
		$iIssueCount = 0;
		$aFixesDelete = array();
		$aFixesUpdate = array();

		foreach ($aErrorsAndFixes as $sRootClass => $aTables)
		{
			foreach ($aTables as $sTable => $aRecords)
			{
				foreach ($aRecords as $iRecord => $aError)
				{
					$sAction = $aError['Action'];
					$sReason = $aError['Reason'];
					$iPass = $aError['Pass'];

					switch ($sAction)
					{
						case 'Delete':
						$sActionDetails = "";
						$aFixesDelete[$sTable][] = $iRecord;
						break;

						case 'Update':
						$aUpdateDesc = array();
						foreach($aError['Action_Details'] as $aUpdateSpec)
						{
							$aUpdateDesc[] = $aUpdateSpec['column']." -&gt; ".$aUpdateSpec['newvalue'];
							$aFixesUpdate[$sTable][$aUpdateSpec['column']][$aUpdateSpec['newvalue']][] = $iRecord;
						}
						$sActionDetails = "Set ".implode(", ", $aUpdateDesc);

						break;

						default:
						$sActionDetails = "bug: unknown action '$sAction'";
					}
					$aIssues[] = "$sRootClass / $sTable / $iRecord / $sReason / $sAction / $sActionDetails";
 					$iIssueCount++;
				}
			}
		}

		if ($iIssueCount > 0)
		{
			// Build the queries to fix in the database
			//
			// First step, be able to get class data out of the table name
			// Could be optimized, because we've made the job earlier... but few benefits, so...
			$aTable2ClassProp = array();
			foreach (self::GetClasses() as $sClass)
			{
				if (!self::HasTable($sClass)) continue;

				$sRootClass = self::GetRootClass($sClass);
				$sTable = self::DBGetTable($sClass);
				$sKeyField = self::DBGetKey($sClass);
	
				$aErrorsAndFixes[$sRootClass][$sTable] = array();
				$aTable2ClassProp[$sTable] = array('rootclass'=>$sRootClass, 'class'=>$sClass, 'keyfield'=>$sKeyField);
			}
			// Second step, build a flat list of SQL queries
			$aSQLFixes = array();
			$iPlannedUpdate = 0;
			foreach ($aFixesUpdate as $sTable => $aColumns)
			{
				foreach ($aColumns as $sColumn => $aNewValues)
				{
					foreach ($aNewValues as $sNewValue => $aRecords)
					{
						$iPlannedUpdate += count($aRecords);
						$sWrongRecords = "'".implode("', '", $aRecords)."'";
						$sKeyField = $aTable2ClassProp[$sTable]['keyfield'];

						$aSQLFixes[] = "UPDATE `$sTable` SET `$sColumn` = $sNewValue WHERE `$sKeyField` IN ($sWrongRecords)";
					}
				}
			}
			$iPlannedDel = 0;
			foreach ($aFixesDelete as $sTable => $aRecords)
			{
				$iPlannedDel += count($aRecords);
				$sWrongRecords = "'".implode("', '", $aRecords)."'";
				$sKeyField = $aTable2ClassProp[$sTable]['keyfield'];

				$aSQLFixes[] = "DELETE FROM `$sTable` WHERE `$sKeyField` IN ($sWrongRecords)";
			}

			// Report the results
			//
			echo "<div style=\"width:100%;padding:10px;background:#FFAAAA;display:;\">";
			echo "<h3>Database corruption error(s): $iErrorCount issues have been encountered. $iPlannedDel records will be deleted, $iPlannedUpdate records will be updated:</h3>\n";
			// #@# later -> this is the responsibility of the caller to format the output
			echo "<ul class=\"treeview\">\n";
			foreach ($aIssues as $sIssueDesc)
			{
				echo "<li>$sIssueDesc</li>\n";
			}
			echo "</ul>\n";
			self::DBShowApplyForm($sRepairUrl, $sSQLStatementArgName, $aSQLFixes);
			echo "<p>Aborting...</p>\n";
			echo "</div>\n";
			exit;
		}
	}

	public static function Startup($sConfigFile, $bAllowMissingDB = false)
	{
		self::LoadConfig($sConfigFile);
		if (self::DBExists())
		{
			CMDBSource::SelectDB(self::$m_sDBName);

			// Some of the init could not be done earlier (requiring classes to be declared and DB to be accessible)
			self::InitPlugins();
		}
		else
		{
			if (!$bAllowMissingDB)
			{
				throw new CoreException('Database not found, check your configuration file', array('config_file'=>$sConfigFile, 'db_name'=>self::$m_sDBName));
			}
		}
	}

	public static function LoadConfig($sConfigFile)
	{
		$oConfig = new Config($sConfigFile);

		// Set log ASAP
		if ($oConfig->GetLogGlobal())
		{
			if ($oConfig->GetLogIssue())
			{
				self::$m_bLogIssue = true;
				IssueLog::Enable('../error.log');
			}
			self::$m_bLogNotification = $oConfig->GetLogNotification();
			self::$m_bLogWebService = $oConfig->GetLogWebService();
		}
		else
		{
			self::$m_bLogIssue = false;
			self::$m_bLogNotification = false;
			self::$m_bLogWebService = false;
		}

		// Note: load the dictionary as soon as possible, because it might be
		//       needed when some error occur
		foreach ($oConfig->GetDictionaries() as $sModule => $sToInclude)
		{
			self::Plugin($sConfigFile, 'dictionaries', $sToInclude);
		}
		// Set the language... after the dictionaries have been loaded!
		Dict::SetDefaultLanguage($oConfig->GetDefaultLanguage());

		foreach ($oConfig->GetAppModules() as $sModule => $sToInclude)
		{
			self::Plugin($sConfigFile, 'application', $sToInclude);
		}
		foreach ($oConfig->GetDataModels() as $sModule => $sToInclude)
		{
			self::Plugin($sConfigFile, 'business', $sToInclude);
		}
		foreach ($oConfig->GetAddons() as $sModule => $sToInclude)
		{
			self::Plugin($sConfigFile, 'addons', $sToInclude);
		}

		$sServer = $oConfig->GetDBHost();
		$sUser = $oConfig->GetDBUser();
		$sPwd = $oConfig->GetDBPwd();
		$sSource = $oConfig->GetDBName();
		$sTablePrefix = $oConfig->GetDBSubname();

		// The include have been included, let's browse the existing classes and
		// develop some data based on the proposed model
		self::InitClasses($sTablePrefix);

		self::$m_sDBName = $sSource;
		self::$m_sTablePrefix = $sTablePrefix;

		CMDBSource::Init($sServer, $sUser, $sPwd); // do not select the DB (could not exist)
	}

	protected static $m_aPlugins = array();
	public static function RegisterPlugin($sType, $sName, $aInitCallSpec = array())
	{
		self::$m_aPlugins[$sName] = array(
			'type' => $sType,
			'init' => $aInitCallSpec,
		);
	}

	protected static function Plugin($sConfigFile, $sModuleType, $sToInclude)
	{
		if (!file_exists($sToInclude))
		{
			throw new CoreException('Wrong filename in configuration file', array('file' => $sConfigFile, 'module' => $sModuleType, 'filename' => $sToInclude));
		}
		require_once($sToInclude);
	}

	protected static function InitPlugins()
	{
		foreach(self::$m_aPlugins as $sName => $aData)
		{
			$aCallSpec = @$aData['init'];
			if (count($aCallSpec) == 2)
			{
				if (!is_callable($aCallSpec))
				{
					throw new CoreException('Wrong declaration in plugin', array('plugin' => $aData['name'], 'type' => $aData['type'], 'class' => $aData['class'], 'init' => $aData['init'])); 
				}
				call_user_func($aCallSpec);
			}
		}
	}

	// Building an object
	//
	//
	private static $aQueryCacheGetObject = array();
	private static $aQueryCacheGetObjectHits = array();
	public static function GetQueryCacheStatus()
	{
		$aRes = array();
		$iTotalHits = 0;
		foreach(self::$aQueryCacheGetObjectHits as $sClass => $iHits)
		{
			$aRes[] = "$sClass: $iHits";
			$iTotalHits += $iHits;
		}
		return $iTotalHits.' ('.implode(', ', $aRes).')';
	}

	public static function MakeSingleRow($sClass, $iKey, $bMustBeFound = true)
	{
		if (!array_key_exists($sClass, self::$aQueryCacheGetObject))
		{
			// NOTE: Quick and VERY dirty caching mechanism which relies on
			//       the fact that the string '987654321' will never appear in the
			//       standard query
			//       This will be replaced for sure with a prepared statement
			//       or a view... next optimization to come! 
			$oFilter = new DBObjectSearch($sClass);
			$oFilter->AddCondition('id', 987654321, '=');
	
			$sSQL = self::MakeSelectQuery($oFilter);
			self::$aQueryCacheGetObject[$sClass] = $sSQL;
			self::$aQueryCacheGetObjectHits[$sClass] = 0;
		}
		else
		{
			$sSQL = self::$aQueryCacheGetObject[$sClass];
			self::$aQueryCacheGetObjectHits[$sClass] += 1;
//			echo " -load $sClass/$iKey- ".self::$aQueryCacheGetObjectHits[$sClass]."<br/>\n";
		}
		$sSQL = str_replace('987654321', CMDBSource::Quote($iKey), $sSQL);
		$res = CMDBSource::Query($sSQL);
		
		$aRow = CMDBSource::FetchArray($res);
		CMDBSource::FreeResult($res);
		if ($bMustBeFound && empty($aRow))
		{
			throw new CoreException("No result for the single row query: '$sSQL'");
		}
		return $aRow;
	}

	public static function GetObjectByRow($sClass, $aRow, $sClassAlias = '')
	{
		self::_check_subclass($sClass);	

		if (strlen($sClassAlias) == 0)
		{
			$sClassAlias = $sClass;
		}

		// Compound objects: if available, get the final object class
		//
		if (!array_key_exists($sClassAlias."finalclass", $aRow))
		{
			// Either this is a bug (forgot to specify a root class with a finalclass field
			// Or this is the expected behavior, because the object is not made of several tables
		}
		elseif (empty($aRow[$sClassAlias."finalclass"]))
		{
			// The data is missing in the DB
			// @#@ possible improvement: check that the class is valid !
			$sRootClass = self::GetRootClass($sClass);
			$sFinalClassField = self::DBGetClassField($sRootClass);
			throw new CoreException("Empty class name for object $sClass::{$aRow["id"]} (root class '$sRootClass', field '{$sFinalClassField}' is empty)");
		}
		else
		{
			// do the job for the real target class
			$sClass = $aRow[$sClassAlias."finalclass"];
		}
		return new $sClass($aRow, $sClassAlias);
	}

	public static function GetObject($sClass, $iKey, $bMustBeFound = true)
	{
		self::_check_subclass($sClass);	
		$aRow = self::MakeSingleRow($sClass, $iKey, $bMustBeFound);
		if (empty($aRow))
		{
			return null;
		}
		return self::GetObjectByRow($sClass, $aRow);
	}

	public static function GetHyperLink($sTargetClass, $iKey)
	{
		if ($iKey < 0)
		{
			return "$sTargetClass: $iKey (invalid value)";
		}
		$oObj = self::GetObject($sTargetClass, $iKey, false);
		if (is_null($oObj))
		{
			return "$sTargetClass: $iKey (not found)";
		}
		return $oObj->GetHyperLink();
	}

	public static function NewObject($sClass)
	{
		self::_check_subclass($sClass);
		return new $sClass();
	}	

	public static function GetNextKey($sClass)
	{
		$sRootClass = MetaModel::GetRootClass($sClass);
		$sRootTable = MetaModel::DBGetTable($sRootClass);
		$iNextKey = CMDBSource::GetNextInsertId($sRootTable);
		return $iNextKey;
	}

	public static function BulkDelete(DBObjectSearch $oFilter)
	{
		$sSQL = self::MakeDeleteQuery($oFilter);
		CMDBSource::Query($sSQL);
	}

	public static function BulkUpdate(DBObjectSearch $oFilter, array $aValues)
	{
		// $aValues is an array of $sAttCode => $value
		$sSQL = self::MakeUpdateQuery($oFilter, $aValues);
		CMDBSource::Query($sSQL);
	}

	// Links
	//
	//
	public static function EnumReferencedClasses($sClass)
	{
		self::_check_subclass($sClass);	

		// 1-N links (referenced by my class), returns an array of sAttCode=>sClass
		$aResult = array();
		foreach(self::$m_aAttribDefs[$sClass] as $sAttCode=>$oAttDef)
		{
			if ($oAttDef->IsExternalKey())
			{
				$aResult[$sAttCode] = $oAttDef->GetTargetClass();
			}
		}
		return $aResult;
	}
	public static function EnumReferencingClasses($sClass, $bSkipLinkingClasses = false, $bInnerJoinsOnly = false)
	{
		self::_check_subclass($sClass);	

		if ($bSkipLinkingClasses)
		{
			$aLinksClasses = self::EnumLinksClasses();
		}

		// 1-N links (referencing my class), array of sClass => array of sAttcode
		$aResult = array();
		foreach (self::$m_aAttribDefs as $sSomeClass=>$aClassAttributes)
		{
			if ($bSkipLinkingClasses && in_array($sSomeClass, $aLinksClasses)) continue;

			$aExtKeys = array();
			foreach ($aClassAttributes as $sAttCode=>$oAttDef)
			{
				if (self::$m_aAttribOrigins[$sSomeClass][$sAttCode] != $sSomeClass) continue;
				if ($oAttDef->IsExternalKey() && (self::IsParentClass($oAttDef->GetTargetClass(), $sClass)))
				{
					if ($bInnerJoinsOnly && $oAttDef->IsNullAllowed()) continue;
					// Ok, I want this one
					$aExtKeys[$sAttCode] = $oAttDef;
				}
			}
			if (count($aExtKeys) != 0)
			{
				$aResult[$sSomeClass] = $aExtKeys;
			}
		}
		return $aResult;
	}
	public static function EnumLinksClasses()
	{
		// Returns a flat array of classes having at least two external keys
		$aResult = array();
		foreach (self::$m_aAttribDefs as $sSomeClass=>$aClassAttributes)
		{
			$iExtKeyCount = 0;
			foreach ($aClassAttributes as $sAttCode=>$oAttDef)
			{
				if (self::$m_aAttribOrigins[$sSomeClass][$sAttCode] != $sSomeClass) continue;
				if ($oAttDef->IsExternalKey())
				{
					$iExtKeyCount++;
				}
			}
			if ($iExtKeyCount >= 2)
			{
				$aResult[] = $sSomeClass;
			}
		}
		return $aResult;
	}
	public static function EnumLinkingClasses($sClass = "")
	{
		// N-N links, array of sLinkClass => (array of sAttCode=>sClass)
		$aResult = array();
		foreach (self::EnumLinksClasses() as $sSomeClass)
		{
			$aTargets = array();
			$bFoundClass = false;
			foreach (self::ListAttributeDefs($sSomeClass) as $sAttCode=>$oAttDef)
			{
				if (self::$m_aAttribOrigins[$sSomeClass][$sAttCode] != $sSomeClass) continue;
				if ($oAttDef->IsExternalKey())
				{
					$sRemoteClass = $oAttDef->GetTargetClass();
					if (empty($sClass))
					{
						$aTargets[$sAttCode] = $sRemoteClass;
					}
					elseif ($sClass == $sRemoteClass)
					{
						$bFoundClass = true;
					}
					else
					{
						$aTargets[$sAttCode] = $sRemoteClass;
					}
				}
			}
			if (empty($sClass) || $bFoundClass)
			{
				$aResult[$sSomeClass] = $aTargets;
			}
		}
		return $aResult;
	}

	public static function GetLinkLabel($sLinkClass, $sAttCode)
	{
		self::_check_subclass($sLinkClass);	

		// e.g. "supported by" (later: $this->GetLinkLabel(), computed on link data!)
		return self::GetLabel($sLinkClass, $sAttCode);
	}

	/**
	 * Replaces all the parameters by the values passed in the hash array
	 */
	static public function ApplyParams($aInput, $aParams)
	{
		$aSearches = array();
		$aReplacements = array();
		foreach($aParams as $sSearch => $sReplace)
		{
			$aSearches[] = '$'.$sSearch.'$';
			$aReplacements[] = $sReplace;
		}
		return str_replace($aSearches, $aReplacements, $aInput);
	}

} // class MetaModel


// Standard attribute lists
MetaModel::RegisterZList("noneditable", array("description"=>"non editable fields", "type"=>"attributes"));

MetaModel::RegisterZList("details", array("description"=>"All attributes to be displayed for the 'details' of an object", "type"=>"attributes"));
MetaModel::RegisterZList("list", array("description"=>"All attributes to be displayed for a list of objects", "type"=>"attributes"));
MetaModel::RegisterZList("preview", array("description"=>"All attributes visible in preview mode", "type"=>"attributes"));

MetaModel::RegisterZList("standard_search", array("description"=>"List of criteria for the standard search", "type"=>"filters"));
MetaModel::RegisterZList("advanced_search", array("description"=>"List of criteria for the advanced search", "type"=>"filters"));


?>
