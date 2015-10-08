<?php
// Copyright (C) 2010-2015 Combodo SARL
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

require_once(APPROOT.'core/modulehandler.class.inc.php');
require_once(APPROOT.'core/querybuildercontext.class.inc.php');
require_once(APPROOT.'core/querymodifier.class.inc.php');
require_once(APPROOT.'core/metamodelmodifier.inc.php');
require_once(APPROOT.'core/computing.inc.php');
require_once(APPROOT.'core/relationgraph.class.inc.php');

/**
 * Metamodel
 *
 * @copyright   Copyright (C) 2010-2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
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
define('ENUM_PARENT_CLASSES_EXCLUDELEAF', 1);
/**
 * add some description here... 
 *
 * @package     iTopORM
 */
define('ENUM_PARENT_CLASSES_ALL', 2);

/**
 * Specifies that this attribute is visible/editable.... normal (default config) 
 *
 * @package     iTopORM
 */
define('OPT_ATT_NORMAL', 0);
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
 * Specifies that the attribute is in 'slave' mode compared to one data exchange task:
 * it should not be edited inside iTop anymore
 *
 * @package     iTopORM
 */
define('OPT_ATT_SLAVE', 32);

/**
 * DB Engine -should be moved into CMDBSource 
 *
 * @package     iTopORM
 */
define('MYSQL_ENGINE', 'innodb');
//define('MYSQL_ENGINE', 'myisam');



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

	private static $m_bTraceSourceFiles = false;
	private static $m_aClassToFile = array();

	public static function GetClassFiles()
	{
		return self::$m_aClassToFile;
	}

	// Purpose: workaround the following limitation = PHP5 does not allow to know the class (derived from the current one)
	// from which a static function is called (__CLASS__ and self are interpreted during parsing)
	private static function GetCallersPHPClass($sExpectedFunctionName = null, $bRecordSourceFile = false)
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
		if ($bRecordSourceFile)
		{
			self::$m_aClassToFile[$aBacktrace[2]["class"]] = $aBacktrace[1]["file"];
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
			throw new CoreException("Unknown class '$sClass'");
		}
	}

	public static function static_var_dump()
	{
		var_dump(get_class_vars(__CLASS__));
	}

	private static $m_oConfig = null;
	protected static $m_aModulesParameters = array();

	private static $m_bSkipCheckToWrite = false;
	private static $m_bSkipCheckExtKeys = false;

	private static $m_bUseAPCCache = false;
	
	private static $m_bLogIssue = false;
	private static $m_bLogNotification = false;
	private static $m_bLogWebService = false;

	public static function SkipCheckToWrite()
	{
		return self::$m_bSkipCheckToWrite;
	}

	public static function SkipCheckExtKeys()
	{
		return self::$m_bSkipCheckExtKeys;
	}

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
	private static $m_aHighlightScales = array(); // array of ("classname" => array of highlightscale information)

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

	final static public function GetName($sClass)
	{
		self::_check_subclass($sClass);
		$sStringCode = 'Class:'.$sClass;
		return Dict::S($sStringCode, str_replace('_', ' ', $sClass));
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
	final static public function GetClassFromLabel($sClassLabel, $bCaseSensitive = true)
	{
		foreach(self::GetClasses() as $sClass)
		{
			if ($bCaseSensitive)
			{
				if (self::GetName($sClass) == $sClassLabel)
				{
					return $sClass;
				}
			}
			else
			{
				if (strcasecmp(self::GetName($sClass), $sClassLabel) == 0)
				{
					return $sClass;
				}				
			}
		}
		return null;
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
	final static public function GetClassIcon($sClass, $bImgTag = true, $sMoreStyles = '')
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
				return self::GetClassIcon($sParentClass, $bImgTag, $sMoreStyles);
			}
		}
		$sIcon = str_replace('/modules/', '/env-'.utils::GetCurrentEnvironment().'/', $sIcon); // Support of pre-2.0 modules
		if ($bImgTag && ($sIcon != ''))
		{
			$sIcon = "<img src=\"$sIcon\" style=\"vertical-align:middle;$sMoreStyles\"/>";
		}
		return $sIcon;
	}
	final static public function IsAutoIncrementKey($sClass)
	{
		self::_check_subclass($sClass);	
		return (self::$m_aClassParams[$sClass]["key_type"] == "autoincrement");
	}
	final static public function GetNameSpec($sClass)
	{
		self::_check_subclass($sClass);
		$nameRawSpec = self::$m_aClassParams[$sClass]["name_attcode"];
		if (is_array($nameRawSpec))
		{
			$sFormat = Dict::S("Class:$sClass/Name", '');
			if (strlen($sFormat) == 0)
			{
				// Default to "%1$s %2$s..."
				for($i = 1 ; $i <= count($nameRawSpec) ; $i++)
				{
					if (empty($sFormat))
					{					
						$sFormat .= '%'.$i.'$s';
					}
					else
					{
						$sFormat .= ' %'.$i.'$s';
					}
				}
			}
			return array($sFormat, $nameRawSpec);
		}
		elseif (empty($nameRawSpec))
		{
			//return array($sClass.' %s', array('id'));
			return array($sClass, array());
		}
		else
		{
			// string -> attcode
			return array('%1$s', array($nameRawSpec));
		}
	}

	/**
	 * Get the friendly name expression for a given class
	 */	 	
	final static public function GetNameExpression($sClass)
	{
		$aNameSpec = self::GetNameSpec($sClass);
		$sFormat = $aNameSpec[0];
		$aAttributes = $aNameSpec[1];                                                     

		$aPieces = preg_split('/%([0-9])\\$s/', $sFormat, -1, PREG_SPLIT_DELIM_CAPTURE);
		$aExpressions = array();
		foreach($aPieces as $i => $sPiece)
		{
			if ($i & 1)
			{
				// $i is ODD - sPiece is a delimiter
				//
				$iReplacement = (int)$sPiece - 1;

		      if (isset($aAttributes[$iReplacement]))
		      {
		      	$sAttCode = $aAttributes[$iReplacement];
					$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
					if ($oAttDef->IsExternalField() || ($oAttDef instanceof AttributeFriendlyName))
					{
						$sKeyAttCode = $oAttDef->GetKeyAttCode();
						$sClassOfAttribute = self::GetAttributeOrigin($sClass, $sKeyAttCode);
					}
					else
					{
			      	$sClassOfAttribute = self::GetAttributeOrigin($sClass, $sAttCode);
			      }
					$aExpressions[] = new FieldExpression($sAttCode, $sClassOfAttribute);
				}
			}
			else
			{
				// $i is EVEN - sPiece is a literal
				//
				if (strlen($sPiece) > 0)
				{
	    			$aExpressions[] = new ScalarExpression($sPiece);
				}
			}
		}

		$oNameExpr = new CharConcatExpression($aExpressions);
		return $oNameExpr;
	}

	/**
	 * Returns the friendly name IIF it is equivalent to a single attribute	
	 */	
	final static public function GetFriendlyNameAttributeCode($sClass)
	{
		$aNameSpec = self::GetNameSpec($sClass);
		$sFormat = trim($aNameSpec[0]);
		$aAttributes = $aNameSpec[1];                                                     
		if (($sFormat != '') && ($sFormat != '%1$s'))
		{
			return null;
		}
		if (count($aAttributes) > 1)
		{
			return null;
		}
		return reset($aAttributes);
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

	final static public function GetOrderByDefault($sClass, $bOnlyDeclared = false)
	{
		self::_check_subclass($sClass);	
		$aOrderBy = array_key_exists("order_by_default", self::$m_aClassParams[$sClass]) ? self::$m_aClassParams[$sClass]["order_by_default"]: array();
		if ($bOnlyDeclared)
		{
			// Used to reverse engineer the declaration of the data model
			return $aOrderBy;
		}
		else
		{
			if (count($aOrderBy) == 0)
			{
				$aOrderBy['friendlyname'] = true;
			}
			return $aOrderBy;
		}
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
		
		// Even non-writable attributes (like ExternalFields) can now have Prerequisites
		return $oAtt->GetPrerequisiteAttributes();
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
				// If the format changes here, do not forget to update the setup index page (detection of installed modules)
				return self::$m_sTablePrefix.$sTableRaw;
			}
		}
		// This attribute has been inherited (compound objects)
		return self::DBGetTable(self::$m_aAttribOrigins[$sClass][$sAttCode]);
	}

	final static public function DBGetView($sClass)
	{
		return self::$m_sTablePrefix."view_".$sClass;
	}

	final static public function DBEnumTables()
	{
		// This API does not rely on our capability to query the DB and retrieve
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

	final static public function DBGetIndexes($sClass)
	{
		self::_check_subclass($sClass);
		if (isset(self::$m_aClassParams[$sClass]['indexes']))
		{
			return self::$m_aClassParams[$sClass]['indexes'];
		}
		else
		{
			return array();
		}
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
	private static $m_aIgnoredAttributes = array(); //array of ("classname" => array of ("attcode")

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
	final static public function IsValidAttCode($sClass, $sAttCode, $bExtended = false)
	{
		if (!array_key_exists($sClass, self::$m_aAttribDefs)) return false;

		if ($bExtended)
		{
			if (($iPos = strpos($sAttCode, '->')) === false)
			{
				$bRes = array_key_exists($sAttCode, self::$m_aAttribDefs[$sClass]);
			}
			else
			{
				$sExtKeyAttCode = substr($sAttCode, 0, $iPos);
				$sRemoteAttCode = substr($sAttCode, $iPos + 2);
				if (MetaModel::IsValidAttCode($sClass, $sExtKeyAttCode))
				{
					$oKeyAttDef = MetaModel::GetAttributeDef($sClass, $sExtKeyAttCode);
					$sRemoteClass = $oKeyAttDef->GetTargetClass();
					$bRes = MetaModel::IsValidAttCode($sRemoteClass, $sRemoteAttCode, true);
				}
				else
				{
					$bRes = false;
				}
			}
		}
		else
		{
			$bRes = array_key_exists($sAttCode, self::$m_aAttribDefs[$sClass]);
		}
		
		return $bRes;
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
		if (isset(self::$m_aAttribDefs[$sClass][$sAttCode]))
		{
			return self::$m_aAttribDefs[$sClass][$sAttCode];
		}
		elseif (($iPos = strpos($sAttCode, '->')) !== false)
		{
			$sExtKeyAttCode = substr($sAttCode, 0, $iPos);
			$sRemoteAttCode = substr($sAttCode, $iPos + 2);
			$oKeyAttDef = self::GetAttributeDef($sClass, $sExtKeyAttCode);
			$sRemoteClass = $oKeyAttDef->GetTargetClass();
			return self::GetAttributeDef($sRemoteClass, $sRemoteAttCode);
		}
		else
		{
			throw new Exception("Unknown attribute $sAttCode from class $sClass");
		}
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
		static $aExtFields = array();
		if (!isset($aExtFields[$sClass][$sKeyAttCode]))
		{
			$aExtFields[$sClass][$sKeyAttCode] = array();
			foreach (self::ListAttributeDefs($sClass) as $sAttCode => $oAtt)
			{
				if ($oAtt->IsExternalField() && ($oAtt->GetKeyAttCode() == $sKeyAttCode))
				{
					$aExtFields[$sClass][$sKeyAttCode][$oAtt->GetExtAttCode()] = $oAtt;
				}
			}
		}
		return $aExtFields[$sClass][$sKeyAttCode];
	}

	final static public function FindExternalField($sClass, $sKeyAttCode, $sRemoteAttCode)
	{
		$aExtFields = self::GetExternalFields($sClass, $sKeyAttCode);
		if (isset($aExtFields[$sRemoteAttCode]))
		{
			return $aExtFields[$sRemoteAttCode];
		}
		else
		{
			return null;
		}
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

	protected static $m_aTrackForwardCache = array();
	/**
	 * List external keys for which there is a LinkSet (direct or indirect) on the other end
	 * For those external keys, a change will have a special meaning on the other end
	 * in term of change tracking	 	 	 
	 */	 	
	final static public function GetTrackForwardExternalKeys($sClass)
	{
		if (!isset(self::$m_aTrackForwardCache[$sClass]))
		{
			$aRes = array();
			foreach (MetaModel::GetExternalKeys($sClass) as $sAttCode => $oAttDef)
			{
				$sRemoteClass = $oAttDef->GetTargetClass();
				foreach (MetaModel::ListAttributeDefs($sRemoteClass) as $sRemoteAttCode => $oRemoteAttDef)
				{
					if (!$oRemoteAttDef->IsLinkSet()) continue;
					if (!is_subclass_of($sClass, $oRemoteAttDef->GetLinkedClass()) && $oRemoteAttDef->GetLinkedClass() != $sClass) continue;
					if ($oRemoteAttDef->GetExtKeyToMe() != $sAttCode) continue;
					$aRes[$sAttCode] = $oRemoteAttDef;
				}
			}
			self::$m_aTrackForwardCache[$sClass] = $aRes;
		}
		return self::$m_aTrackForwardCache[$sClass];
	}


	/**
	 * Get the attribute label
	 * @param string sClass	Persistent class
	 * @param string sAttCodeEx Extended attribute code: attcode[->attcode]	 
	 * @param bool $bShowMandatory If true, add a star character (at the end or before the ->) to show that the field is mandatory
	 * @return string A user friendly format of the string: AttributeName or AttributeName->ExtAttributeName
	 */	 	
	public static function GetLabel($sClass, $sAttCodeEx, $bShowMandatory = false)
	{
		$sLabel = '';
		if (($iPos = strpos($sAttCodeEx, '->')) === false)
		{
			if ($sAttCodeEx == 'id')
			{
				$sLabel = Dict::S('UI:CSVImport:idField');
			}
			else
			{
				$oAttDef = self::GetAttributeDef($sClass, $sAttCodeEx);
				$sMandatory = ($bShowMandatory && !$oAttDef->IsNullAllowed()) ? '*' : '';
				$sLabel = $oAttDef->GetLabel().$sMandatory;
			}
		}
		else
		{
			$sExtKeyAttCode = substr($sAttCodeEx, 0, $iPos);
			$sRemoteAttCode = substr($sAttCodeEx, $iPos + 2);
			$oKeyAttDef = MetaModel::GetAttributeDef($sClass, $sExtKeyAttCode);
			$sRemoteClass = $oKeyAttDef->GetTargetClass();
			// Recurse
			$sLabel = self::GetLabel($sClass, $sExtKeyAttCode).'->'.self::GetLabel($sRemoteClass, $sRemoteAttCode);
		}
		return $sLabel;
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
		if (!array_key_exists($sFilterCode, self::$m_aFilterDefs[$sClass]))
		{
			throw new CoreException("Unknown filter code '$sFilterCode' for class '$sClass'");
		}
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
		$aZList = self::FlattenZlist(self::GetZListItems($sClass, $sListCode));
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

	/**
	 * TO BE DEPRECATED: use EnumRelationsEx instead
	 * @param string $sClass
	 * @return multitype:string unknown |Ambigous <string, multitype:>
	 */
	public static function EnumRelations($sClass = '')
	{
		$aResult = array_keys(self::$m_aRelationInfos);
		if (!empty($sClass))
		{
			// Return only the relations that have a meaning (i.e. for which at least one query is defined)
			// for the specified class
			$aClassRelations = array();
			foreach($aResult as $sRelCode)
			{	
				$aQueriesDown = self::EnumRelationQueries($sClass, $sRelCode);
				if (count($aQueriesDown) > 0)
				{
					$aClassRelations[] = $sRelCode;
				}
				// Temporary patch: until the impact analysis GUI gets rewritten,
				// let's consider that "depends on" is equivalent to "impacts/up"
				// The current patch has been implemented in DBObject and MetaModel
				if ($sRelCode == 'impacts')
				{
					$aQueriesUp = self::EnumRelationQueries($sClass, 'impacts', false);
					if (count($aQueriesUp) > 0)
					{
						$aClassRelations[] = 'depends on';
					}
				}
			}

 			return $aClassRelations;
		}

		// Temporary patch: until the impact analysis GUI gets rewritten,
		// let's consider that "depends on" is equivalent to "impacts/up"
		// The current patch has been implemented in DBObject and MetaModel
		if (in_array('impacts', $aResult))
		{
			$aResult[] = 'depends on';
		}

		return $aResult;
	}

	public static function EnumRelationsEx($sClass)
	{
		$aRelationInfo = array_keys(self::$m_aRelationInfos);
		// Return only the relations that have a meaning (i.e. for which at least one query is defined)
		// for the specified class
		$aClassRelations = array();
		foreach($aRelationInfo as $sRelCode)
		{
			$aQueriesDown = self::EnumRelationQueries($sClass, $sRelCode, true /* Down */);
			if (count($aQueriesDown) > 0)
			{
				$aClassRelations[$sRelCode]['down'] = self::GetRelationLabel($sRelCode, true);
			}
				
			$aQueriesUp = self::EnumRelationQueries($sClass, $sRelCode, false /* Up */);
			if (count($aQueriesUp) > 0)
			{
				$aClassRelations[$sRelCode]['up'] = self::GetRelationLabel($sRelCode, false);
			}
		}
	
		return $aClassRelations;
	}
	
	final static public function GetRelationDescription($sRelCode)
	{
		return Dict::S("Relation:$sRelCode/Description");
	}

	final static public function GetRelationLabel($sRelCode, $bDown = true)
	{
		if ($bDown)
		{
		// The legacy convention is confusing with regard to the way we have conceptualized the relations:
		// In the former representation, the main stream was named after "up"
		// Now, the relation from A to B says that something is transmitted from A to B, thus going DOWNstream as described in a petri net.
		$sKey = "Relation:$sRelCode/DownStream";
		$sLegacy = Dict::S("Relation:$sRelCode/VerbUp", $sKey);
		}
		else
		{
			$sKey = "Relation:$sRelCode/UpStream";
			$sLegacy = Dict::S("Relation:$sRelCode/VerbDown", $sKey);
		}
		$sRet = Dict::S($sKey, $sLegacy);
		return $sRet;
	}

	protected static function ComputeRelationQueries($sRelCode)
	{
		$bHasLegacy = false;
		$aQueries = array();
		foreach (self::GetClasses() as $sClass)
		{
			$aQueries[$sClass]['down'] = array();
			if (!array_key_exists('up', $aQueries[$sClass]))
			{
				$aQueries[$sClass]['up'] = array();
			}

			$aNeighboursDown = call_user_func_array(array($sClass, 'GetRelationQueriesEx'), array($sRelCode));
	
			// Translate attributes into queries (new style of spec only)
			foreach($aNeighboursDown as $sNeighbourId => $aNeighbourData)
			{
				$aNeighbourData['sFromClass'] = $aNeighbourData['sDefinedInClass'];
				try
				{
					if (strlen($aNeighbourData['sQueryDown']) == 0)
					{
						$oAttDef = self::GetAttributeDef($sClass, $aNeighbourData['sAttribute']);
						if ($oAttDef instanceof AttributeExternalKey)
						{
							$sTargetClass = $oAttDef->GetTargetClass();
							$aNeighbourData['sToClass'] = $sTargetClass;
							$aNeighbourData['sQueryDown'] = 'SELECT '.$sTargetClass.' AS o WHERE o.id = :this->'.$aNeighbourData['sAttribute'];
							$aNeighbourData['sQueryUp'] = 'SELECT '.$aNeighbourData['sFromClass'].' AS o WHERE o.'.$aNeighbourData['sAttribute'].' = :this->id';
						}
						elseif ($oAttDef instanceof AttributeLinkedSet)
						{
							$sLinkedClass = $oAttDef->GetLinkedClass();
							$sExtKeyToMe = $oAttDef->GetExtKeyToMe();
							if ($oAttDef->IsIndirect())
							{
								$sExtKeyToRemote = $oAttDef->GetExtKeyToRemote();
								$oRemoteAttDef = self::GetAttributeDef($sLinkedClass, $sExtKeyToRemote);
								$sRemoteClass = $oRemoteAttDef->GetTargetClass();
		
								$aNeighbourData['sToClass'] = $sRemoteClass;
								$aNeighbourData['sQueryDown'] = "SELECT $sRemoteClass AS o JOIN $sLinkedClass AS lnk ON lnk.$sExtKeyToRemote = o.id WHERE lnk.$sExtKeyToMe = :this->id";
								$aNeighbourData['sQueryUp'] = "SELECT ".$aNeighbourData['sFromClass']." AS o JOIN $sLinkedClass AS lnk ON lnk.$sExtKeyToMe = o.id WHERE lnk.$sExtKeyToRemote = :this->id";
							}
							else
							{
								$aNeighbourData['sToClass'] = $sLinkedClass;
								$aNeighbourData['sQueryDown'] = "SELECT $sLinkedClass AS o WHERE o.$sExtKeyToMe = :this->id";
								$aNeighbourData['sQueryUp'] = "SELECT ".$aNeighbourData['sFromClass']." AS o WHERE o.id = :this->$sExtKeyToMe";
							}
						}
						else
						{
							throw new Exception("Unexpected attribute type for '{$aNeighbourData['sAttribute']}'. Expecting a link set or external key.");
						}
					}
					else
					{
						$oSearch = DBObjectSearch::FromOQL($aNeighbourData['sQueryDown']);
						$aNeighbourData['sToClass'] = $oSearch->GetClass();
					}
				}
				catch (Exception $e)
				{
					throw new Exception("Wrong definition for the relation $sRelCode/{$aNeighbourData['sDefinedInClass']}/{$aNeighbourData['sNeighbour']}: ".$e->getMessage());
				}

				if ($aNeighbourData['sDirection'] == 'down')
				{
					$aNeighbourData['sQueryUp'] = null;
				}

				$sArrowId = $aNeighbourData['sDefinedInClass'].'_'.$sNeighbourId;
				$aQueries[$sClass]['down'][$sArrowId] = $aNeighbourData;

				// Compute the reverse index
				if ($aNeighbourData['sDefinedInClass'] == $sClass)
				{
					if ($aNeighbourData['sDirection'] == 'both')
					{
						$sFromClass = $aNeighbourData['sFromClass'];
						$sToClass = $aNeighbourData['sToClass'];
						foreach (self::EnumChildClasses($sToClass, ENUM_CHILD_CLASSES_ALL) as $sSubClass)
						{
							$aQueries[$sSubClass]['up'][$sArrowId] = $aNeighbourData;
						}
					}
				}
			}

			// Read legacy definitions
			// The up/down queries have to be reconcilied, which can only be done later when all the classes have been browsed
			//
			// The keys used to store a query (up or down) into the array are built differently between the modern and legacy made data:
			// Modern way: aQueries[sClass]['up'|'down'][sArrowId], where sArrowId is made of the source class + neighbour id (XML def)
			// Legacy way: aQueries[sClass]['up'|'down'][sRemoteClass]
			// The modern way does allow for several arrows between two classes
			// The legacy way aims at simplifying the transformation (reconciliation between up and down)
			if ($sRelCode == 'impacts')
			{
				$sRevertCode = 'depends on';

				$aLegacy = call_user_func_array(array($sClass, 'GetRelationQueries'), array($sRelCode));
				foreach($aLegacy as $sId => $aLegacyEntry)
				{
					$bHasLegacy = true;
					
					$oFilter = DBObjectSearch::FromOQL($aLegacyEntry['sQuery']);
					$sRemoteClass = $oFilter->GetClass();

					// Determine wether the query is inherited from a parent or not
					$bInherited = false;
					foreach (self::EnumParentClasses($sClass) as $sParent)
					{
						if (!isset($aQueries[$sParent]['down'][$sRemoteClass])) continue;
						if ($aLegacyEntry['sQuery'] == $aQueries[$sParent]['down'][$sRemoteClass]['sQueryDown'])
						{
							$bInherited = true;
							$aQueries[$sClass]['down'][$sRemoteClass] = $aQueries[$sParent]['down'][$sRemoteClass];
							break;
						}
					}

					if (!$bInherited)
					{
						$aQueries[$sClass]['down'][$sRemoteClass] = array(
							'_legacy_' => true,
							'sDefinedInClass' => $sClass,
							'sFromClass' => $sClass,
							'sToClass' => $sRemoteClass,
							'sDirection' => 'down',
							'sQueryDown' => $aLegacyEntry['sQuery'],
							'sQueryUp' => null,
							'sNeighbour' => $sRemoteClass // Normalize the neighbour id
						);
					}
				}

				$aLegacy = call_user_func_array(array($sClass, 'GetRelationQueries'), array($sRevertCode));
				foreach($aLegacy as $sId => $aLegacyEntry)
				{
					$bHasLegacy = true;

					$oFilter = DBObjectSearch::FromOQL($aLegacyEntry['sQuery']);
					$sRemoteClass = $oFilter->GetClass();

					// Determine wether the query is inherited from a parent or not
					$bInherited = false;
					foreach (self::EnumParentClasses($sClass) as $sParent)
					{
						if (!isset($aQueries[$sParent]['up'][$sRemoteClass])) continue;
						if ($aLegacyEntry['sQuery'] == $aQueries[$sParent]['up'][$sRemoteClass]['sQueryUp'])
						{
							$bInherited = true;
							$aQueries[$sClass]['up'][$sRemoteClass] = $aQueries[$sParent]['up'][$sRemoteClass];
							break;
						}
					}

					if (!$bInherited)
					{
						$aQueries[$sClass]['up'][$sRemoteClass] = array(
							'_legacy_' => true,
							'sDefinedInClass' => $sRemoteClass,
							'sFromClass' => $sRemoteClass,
							'sToClass' => $sClass,
							'sDirection' => 'both',
							'sQueryDown' => null,
							'sQueryUp' => $aLegacyEntry['sQuery'],
							'sNeighbour' => $sClass// Normalize the neighbour id
						);
					}
				}
			}
			else
			{
				// Cannot take the legacy system into account... simply ignore it
			}
		} // foreach class

		// Perform the up/down reconciliation for the legacy definitions
		if ($bHasLegacy)
		{
			foreach (self::GetClasses() as $sClass)
			{
				// Foreach "up" legacy query, update its "down" counterpart
				if (isset($aQueries[$sClass]['up']))
				{
					foreach ($aQueries[$sClass]['up'] as $sNeighbourId => $aNeighbourData)
					{
						if (!array_key_exists('_legacy_', $aNeighbourData))
						{
							continue;
						}
						if (!$aNeighbourData['_legacy_']) continue; // Skip modern definitions

						$sLocalClass = $aNeighbourData['sToClass'];
						foreach (self::EnumChildClasses($aNeighbourData['sFromClass'], ENUM_CHILD_CLASSES_ALL) as $sRemoteClass)
						{
							if (isset($aQueries[$sRemoteClass]['down'][$sLocalClass]))
							{
								$aQueries[$sRemoteClass]['down'][$sLocalClass]['sQueryUp'] = $aNeighbourData['sQueryUp'];
								$aQueries[$sRemoteClass]['down'][$sLocalClass]['sDirection'] = 'both';
							}
							// Be silent in order to transparently support legacy data models where the counterpart query does not always exist
							//else
							//{
							//	throw new Exception("Legacy definition of the relation '$sRelCode/$sRevertCode', defined on $sLocalClass (relation: $sRevertCode, inherited to $sClass), missing the counterpart query on class $sRemoteClass ($sRelCode)");
							//}
						}
					}
				}
				// Foreach "down" legacy query, update its "up" counterpart (if any)
				foreach ($aQueries[$sClass]['down'] as $sNeighbourId => $aNeighbourData)
				{
					if (!$aNeighbourData['_legacy_']) continue; // Skip modern definitions

					$sLocalClass = $aNeighbourData['sFromClass'];
					foreach (self::EnumChildClasses($aNeighbourData['sToClass'], ENUM_CHILD_CLASSES_ALL) as $sRemoteClass)
					{
						if (isset($aQueries[$sRemoteClass]['up'][$sLocalClass]))
						{
							$aQueries[$sRemoteClass]['up'][$sLocalClass]['sQueryDown'] = $aNeighbourData['sQueryDown'];
						}
					}
				}
			}
		}
		return $aQueries;
	}

	public static function EnumRelationQueries($sClass, $sRelCode, $bDown = true)
	{
		static $aQueries = array();
		if (!isset($aQueries[$sRelCode]))
		{
			$aQueries[$sRelCode] = self::ComputeRelationQueries($sRelCode);
		}
		$sDirection = $bDown ? 'down' : 'up';
		if (isset($aQueries[$sRelCode][$sClass][$sDirection]))
		{
			return $aQueries[$sRelCode][$sClass][$sDirection];
		}
		else
		{
			return array();
		}
	}

	/**
	 * Compute the "RelatedObjects" for a whole set of DBObjects
	 * 
	 * @param string $sRelCode The code of the relation to use for the computation
	 * @param array $asourceObjects The objects to start with
	 * @param int $iMaxDepth
	 * @param boolean $bEnableReduncancy
	 * @param array $aUnreachable Array of objects to be considered as 'unreachable'
	 * 
	 * @return RelationGraph The graph of all the related objects
	 */
	static public function GetRelatedObjectsDown($sRelCode, $aSourceObjects, $iMaxDepth = 99, $bEnableRedundancy = true, $aUnreachable = array(), $aContexts = array())
	{
		$oGraph = new RelationGraph();
		foreach ($aSourceObjects as $oObject)
		{
			$oGraph->AddSourceObject($oObject);
		}
		foreach($aContexts as $key => $sOQL)
		{
			$oGraph->AddContextQuery($key, $sOQL);
		}
		$oGraph->ComputeRelatedObjectsDown($sRelCode, $iMaxDepth, $bEnableRedundancy, $aUnreachable);
		return $oGraph;
	}

	/**
	 * Compute the "RelatedObjects" in the reverse way
	 * 
	 * @param string $sRelCode The code of the relation to use for the computation
	 * @param array $asourceObjects The objects to start with
	 * @param int $iMaxDepth
	 * @param boolean $bEnableReduncancy
	 * 
	 * @return RelationGraph The graph of all the related objects
	 */
	static public function GetRelatedObjectsUp($sRelCode, $aSourceObjects, $iMaxDepth = 99, $bEnableRedundancy = true, $aContexts = array())
	{
		$oGraph = new RelationGraph();
		foreach ($aSourceObjects as $oObject)
		{
			$oGraph->AddSinkObject($oObject);
		}
		foreach($aContexts as $key => $sOQL)
		{
			$oGraph->AddContextQuery($key, $sOQL);
		}
		$oGraph->ComputeRelatedObjectsUp($sRelCode, $iMaxDepth, $bEnableRedundancy);
		return $oGraph;
	}

	//
	// Object lifecycle model
	//
	private static $m_aStates = array(); // array of ("classname" => array of "statecode"=>array('label'=>..., attribute_inherit=> attribute_list=>...))
	private static $m_aStimuli = array(); // array of ("classname" => array of ("stimuluscode"=>array('label'=>...)))
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

	/*
	* Enumerate all possible initial states, including the default one
	*/
	public static function EnumInitialStates($sClass)
	{
		if (array_key_exists($sClass, self::$m_aStates))
		{
			$aRet = array();
			// Add the states for which the flag 'is_initial_state' is set to <true>
			foreach(self::$m_aStates[$sClass] as $aStateCode => $aProps)
			{
				if (isset($aProps['initial_state_path']))
				{
					$aRet[$aStateCode] = $aProps['initial_state_path'];
				}
			}
			// Add the default initial state
			$sMainInitialState = self::GetDefaultState($sClass);
			if (!isset($aRet[$sMainInitialState]))
			{
				$aRet[$sMainInitialState] = array();
			}
			return $aRet;
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
		$oAttDef = self::GetAttributeDef($sClass, $sStateAttrCode);
		return $oAttDef->GetValueLabel($sStateValue);
	}
	public static function GetStateDescription($sClass, $sStateValue)
	{
		$sStateAttrCode = self::GetStateAttributeCode($sClass);
		$oAttDef = self::GetAttributeDef($sClass, $sStateAttrCode);
		return $oAttDef->GetValueDescription($sStateValue);
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
	
	/**
	 * Combines the flags from the all states that compose the initial_state_path
	 */
	public static function GetInitialStateAttributeFlags($sClass, $sState, $sAttCode)
	{
		$iFlags = self::GetAttributeFlags($sClass, $sState, $sAttCode); // Be default set the same flags as the 'target' state
		$sStateAttCode = self::GetStateAttributeCode($sClass);
		if (!empty($sStateAttCode))
		{
			$aStates = MetaModel::EnumInitialStates($sClass);
			if (array_key_exists($sState, $aStates))
			{
				$bReadOnly = (($iFlags & OPT_ATT_READONLY) == OPT_ATT_READONLY);
				$bHidden = (($iFlags & OPT_ATT_HIDDEN) == OPT_ATT_HIDDEN);
				foreach($aStates[$sState] as $sPrevState)
				{
					$iPrevFlags = self::GetAttributeFlags($sClass, $sPrevState, $sAttCode);
					if (($iPrevFlags & OPT_ATT_HIDDEN) != OPT_ATT_HIDDEN)
					{
						$bReadOnly = $bReadOnly && (($iPrevFlags & OPT_ATT_READONLY) == OPT_ATT_READONLY); // if it is/was not readonly => then it's not
					}
					$bHidden = $bHidden && (($iPrevFlags & OPT_ATT_HIDDEN) == OPT_ATT_HIDDEN); // if it is/was not hidden => then it's not
				}
				if ($bReadOnly)
				{
					$iFlags = $iFlags | OPT_ATT_READONLY;
				}
				else
				{
					$iFlags = $iFlags & ~OPT_ATT_READONLY;
				}
				if ($bHidden)
				{
					$iFlags = $iFlags | OPT_ATT_HIDDEN;
				}
				else
				{
					$iFlags = $iFlags & ~OPT_ATT_HIDDEN;
				}
			}
		}
		return $iFlags;
	}
	//
	// Allowed values
	//

	public static function GetAllowedValues_att($sClass, $sAttCode, $aArgs = array(), $sContains = '')
	{
		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
		return $oAttDef->GetAllowedValues($aArgs, $sContains);
	}

	public static function GetAllowedValues_flt($sClass, $sFltCode, $aArgs = array(), $sContains = '')
	{
		$oFltDef = self::GetClassFilterDef($sClass, $sFltCode);
		return $oFltDef->GetAllowedValues($aArgs, $sContains);
	}

	public static function GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs = array(), $sContains = '')
	{
		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
		return $oAttDef->GetAllowedValuesAsObjectSet($aArgs, $sContains);
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

		// Build the list of available extensions
		//
		$aInterfaces = array('iApplicationUIExtension', 'iApplicationObjectExtension', 'iQueryModifier', 'iOnClassInitialization', 'iPopupMenuExtension', 'iPageUIExtension');
		foreach($aInterfaces as $sInterface)
		{
			self::$m_aExtensionClasses[$sInterface] = array();
		}

		foreach(get_declared_classes() as $sPHPClass)
		{
			$oRefClass = new ReflectionClass($sPHPClass);
			$oExtensionInstance = null;
			foreach($aInterfaces as $sInterface)
			{
				if ($oRefClass->implementsInterface($sInterface))
				{
					if (is_null($oExtensionInstance))
					{
						$oExtensionInstance = new $sPHPClass;
					}
					self::$m_aExtensionClasses[$sInterface][$sPHPClass] = $oExtensionInstance;
				}
			}
		}

		// Initialize the classes (declared attributes, etc.)
		//
		foreach(get_declared_classes() as $sPHPClass)
		{
			if (is_subclass_of($sPHPClass, 'DBObject'))
			{
				$sParent = get_parent_class($sPHPClass);
				if (array_key_exists($sParent, self::$m_aIgnoredAttributes))
				{
					// Inherit info about attributes to ignore
					self::$m_aIgnoredAttributes[$sPHPClass] = self::$m_aIgnoredAttributes[$sParent];
				}
				try
				{
					$oMethod = new ReflectionMethod($sPHPClass, 'Init');
					if ($oMethod->getDeclaringClass()->name == $sPHPClass)
					{
						call_user_func(array($sPHPClass, 'Init'));
						foreach (MetaModel::EnumPlugins('iOnClassInitialization') as $sPluginClass => $oClassInit)
						{
							$oClassInit->OnAfterClassInitialization($sPHPClass);
						}
					}
				}
				catch (ReflectionException $e)
				{
					// This class is only implementing methods, ignore it from the MetaModel perspective
				}
			}
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
				self::$m_aClassParams[$sRootClass]["db_finalclass_field"] = 'finalclass';
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

		// Prepare external fields and filters
		// Add final class to external keys
		//
		foreach (self::GetClasses() as $sClass)
		{
			// Create the friendly name attribute
			$sFriendlyNameAttCode = 'friendlyname'; 
			$oFriendlyName = new AttributeFriendlyName($sFriendlyNameAttCode, 'id');
			$oFriendlyName->SetHostClass($sClass);
			self::$m_aAttribDefs[$sClass][$sFriendlyNameAttCode] = $oFriendlyName;
			self::$m_aAttribOrigins[$sClass][$sFriendlyNameAttCode] = $sClass;
			$oFriendlyNameFlt = new FilterFromAttribute($oFriendlyName);
			self::$m_aFilterDefs[$sClass][$sFriendlyNameAttCode] = $oFriendlyNameFlt;
			self::$m_aFilterOrigins[$sClass][$sFriendlyNameAttCode] = $sClass;

			self::$m_aExtKeyFriends[$sClass] = array();
			foreach (self::$m_aAttribDefs[$sClass] as $sAttCode => $oAttDef)
			{
				// Compute the filter codes
				//
				foreach ($oAttDef->GetFilterDefinitions() as $sFilterCode => $oFilterDef)
				{
					self::$m_aFilterDefs[$sClass][$sFilterCode] = $oFilterDef;

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
				}
		
				// Compute the fields that will be used to display a pointer to another object
				//
				if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE))
				{
					// oAttDef is either
					// - an external KEY / FIELD (direct),
					// - an external field pointing to an external KEY / FIELD
					// - an external field pointing to an external field pointing to....
					$sRemoteClass = $oAttDef->GetTargetClass();

					if ($oAttDef->IsExternalField())
					{
						// This is a key, but the value comes from elsewhere
						// Create an external field pointing to the remote friendly name attribute
						$sKeyAttCode = $oAttDef->GetKeyAttCode();
						$sRemoteAttCode = $oAttDef->GetExtAttCode()."_friendlyname";
						$sFriendlyNameAttCode = $sAttCode.'_friendlyname';
						// propagate "is_null_allowed" ? 
						$oFriendlyName = new AttributeExternalField($sFriendlyNameAttCode, array("allowed_values"=>null, "extkey_attcode"=>$sKeyAttCode, "target_attcode"=>$sRemoteAttCode, "depends_on"=>array()));
						$oFriendlyName->SetHostClass($sClass);
						self::$m_aAttribDefs[$sClass][$sFriendlyNameAttCode] = $oFriendlyName;
						self::$m_aAttribOrigins[$sClass][$sFriendlyNameAttCode] = $sRemoteClass;
						$oFriendlyNameFlt = new FilterFromAttribute($oFriendlyName);
						self::$m_aFilterDefs[$sClass][$sFriendlyNameAttCode] = $oFriendlyNameFlt;
						self::$m_aFilterOrigins[$sClass][$sFriendlyNameAttCode] = $sRemoteClass;
					}
					else
					{
						// Create the friendly name attribute
						$sFriendlyNameAttCode = $sAttCode.'_friendlyname'; 
						$oFriendlyName = new AttributeFriendlyName($sFriendlyNameAttCode, $sAttCode);
						$oFriendlyName->SetHostClass($sClass);
						self::$m_aAttribDefs[$sClass][$sFriendlyNameAttCode] = $oFriendlyName;
						self::$m_aAttribOrigins[$sClass][$sFriendlyNameAttCode] = $sRemoteClass;
						$oFriendlyNameFlt = new FilterFromAttribute($oFriendlyName);
						self::$m_aFilterDefs[$sClass][$sFriendlyNameAttCode] = $oFriendlyNameFlt;
						self::$m_aFilterOrigins[$sClass][$sFriendlyNameAttCode] = $sRemoteClass;

						if (self::HasChildrenClasses($sRemoteClass))
						{
							// First, create an external field attribute, that gets the final class
							$sClassRecallAttCode = $sAttCode.'_finalclass_recall'; 
							$oClassRecall = new AttributeExternalField($sClassRecallAttCode, array(
									"allowed_values"=>null,
									"extkey_attcode"=>$sAttCode,
									"target_attcode"=>"finalclass",
									"is_null_allowed"=>true,
									"depends_on"=>array()
							));
							$oClassRecall->SetHostClass($sClass);
							self::$m_aAttribDefs[$sClass][$sClassRecallAttCode] = $oClassRecall;
							self::$m_aAttribOrigins[$sClass][$sClassRecallAttCode] = $sRemoteClass;

							$oClassFlt = new FilterFromAttribute($oClassRecall);
							self::$m_aFilterDefs[$sClass][$sClassRecallAttCode] = $oClassFlt;
							self::$m_aFilterOrigins[$sClass][$sClassRecallAttCode] = $sRemoteClass;

							// Add it to the ZLists where the external key is present
							//foreach(self::$m_aListData[$sClass] as $sListCode => $aAttributes)
							$sListCode = 'list';
							if (isset(self::$m_aListData[$sClass][$sListCode]))
							{
								$aAttributes = self::$m_aListData[$sClass][$sListCode];
								// temporary.... no loop
								{
									if (in_array($sAttCode, $aAttributes))
									{
										$aNewList = array();
										foreach($aAttributes as $iPos => $sAttToDisplay)
										{
											if (is_string($sAttToDisplay) && ($sAttToDisplay == $sAttCode))
											{
												// Insert the final class right before
												$aNewList[] = $sClassRecallAttCode;
											}
											$aNewList[] = $sAttToDisplay;
										}
										self::$m_aListData[$sClass][$sListCode] = $aNewList;
									}
								}
							}
						}
					}

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
			"name_attcode" => "define wich attribute is the class name, may be an array of attributes (format specified in the dictionary as 'Class:myclass/Name' => '%1\$s %2\$s...'",
			"state_attcode" => "define wich attribute is representing the state (object lifecycle)",
			"reconc_keys" => "define the attributes that will 'almost uniquely' identify an object in batch processes",
			"db_table" => "database table",
			"db_key_field" => "database field which is the key",
			"db_finalclass_field" => "database field wich is the reference to the actual class of the object, considering that this will be a compound class",
		);		

		$sClass = self::GetCallersPHPClass("Init", self::$m_bTraceSourceFiles);

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
			foreach(self::$m_aAttribDefs[$sTargetClass] as $sAttCode => $oAttDef)
			{
				$oAttDef->SetHostClass($sTargetClass);
			}
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

	protected static function Init_IsKnownClass($sClass)
	{
		// Differs from self::IsValidClass()
		// because it is being called before all the classes have been initialized
		if (!class_exists($sClass)) return false;
		if (!is_subclass_of($sClass, 'DBObject')) return false;
		return true;
	}

	public static function Init_AddAttribute(AttributeDefinition $oAtt, $sTargetClass = null)
	{
		if (!$sTargetClass)
		{
			$sTargetClass = self::GetCallersPHPClass("Init");
		}

		$sAttCode = $oAtt->GetCode();
		if ($sAttCode == 'finalclass')
		{
			throw new Exception("Declaration of $sTargetClass: using the reserved keyword '$sAttCode' in attribute declaration");
		}
		if ($sAttCode == 'friendlyname')
		{
			throw new Exception("Declaration of $sTargetClass: using the reserved keyword '$sAttCode' in attribute declaration");
		}
		if (array_key_exists($sAttCode, self::$m_aAttribDefs[$sTargetClass]))
		{
			throw new Exception("Declaration of $sTargetClass: attempting to redeclare the inherited attribute '$sAttCode', originaly declared in ".self::$m_aAttribOrigins[$sTargetClass][$sAttCode]);
		}
	
		// Set the "host class" as soon as possible, since HierarchicalKeys use it for their 'target class' as well
		// and this needs to be know early (for Init_IsKnowClass 19 lines below)		
		$oAtt->SetHostClass($sTargetClass);

		// Some attributes could refer to a class
		// declared in a module which is currently not installed/active
		// We simply discard those attributes
		//
		if ($oAtt->IsLinkSet())
		{
			$sRemoteClass = $oAtt->GetLinkedClass();
			if (!self::Init_IsKnownClass($sRemoteClass))
			{
				self::$m_aIgnoredAttributes[$sTargetClass][$oAtt->GetCode()] = $sRemoteClass;
				return;
			}
		}
		elseif($oAtt->IsExternalKey())
		{
			$sRemoteClass = $oAtt->GetTargetClass();
			if (!self::Init_IsKnownClass($sRemoteClass))
			{
				self::$m_aIgnoredAttributes[$sTargetClass][$oAtt->GetCode()] = $sRemoteClass;
				return;
			}
		}
		elseif($oAtt->IsExternalField())
		{
			$sExtKeyAttCode = $oAtt->GetKeyAttCode();
			if (isset(self::$m_aIgnoredAttributes[$sTargetClass][$sExtKeyAttCode]))
			{
				// The corresponding external key has already been ignored
				self::$m_aIgnoredAttributes[$sTargetClass][$oAtt->GetCode()] = self::$m_aIgnoredAttributes[$sTargetClass][$sExtKeyAttCode];
				return;
			}
			// #@# todo - Check if the target attribute is still there
			// this is not simple to implement because is involves
			// several passes (the load order has a significant influence on that)
		}

		self::$m_aAttribDefs[$sTargetClass][$oAtt->GetCode()] = $oAtt;
		self::$m_aAttribOrigins[$sTargetClass][$oAtt->GetCode()] = $sTargetClass;
		// Note: it looks redundant to put targetclass there, but a mix occurs when inheritance is used		
	}

	public static function Init_SetZListItems($sListCode, $aItems, $sTargetClass = null)
	{
		MyHelpers::CheckKeyInArray('list code', $sListCode, self::$m_aListInfos);

		if (!$sTargetClass)
		{
			$sTargetClass = self::GetCallersPHPClass("Init");
		}

		// Discard attributes that do not make sense
		// (missing classes in the current module combination, resulting in irrelevant ext key or link set)
		//
		self::Init_CheckZListItems($aItems, $sTargetClass);
		self::$m_aListData[$sTargetClass][$sListCode] = $aItems;
	}
	
	protected static function Init_CheckZListItems(&$aItems, $sTargetClass)
	{
		foreach($aItems as $iFoo => $attCode)
		{
			if (is_array($attCode))
			{
				// Note: to make sure that the values will be updated recursively,
				//  do not pass $attCode, but $aItems[$iFoo] instead
				self::Init_CheckZListItems($aItems[$iFoo], $sTargetClass);
				if (count($aItems[$iFoo]) == 0)
				{
					unset($aItems[$iFoo]);
				}
			}
			else if (isset(self::$m_aIgnoredAttributes[$sTargetClass][$attCode]))
			{
				unset($aItems[$iFoo]);
			}
		}
	}

	public static function FlattenZList($aList)
	{
		$aResult = array();
		foreach($aList as $value)
		{
			if (!is_array($value))
			{
				$aResult[] = $value;
			}
			else
			{
				$aResult = array_merge($aResult, self::FlattenZList($value));
			}
		}
		return $aResult;
	}
	
	public static function Init_DefineState($sStateCode, $aStateDef)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		if (is_null($aStateDef['attribute_list'])) $aStateDef['attribute_list'] = array(); 

		$sParentState = $aStateDef['attribute_inherit'];
		if (!empty($sParentState))
		{
			// Inherit from the given state (must be defined !)
			//
			$aToInherit = self::$m_aStates[$sTargetClass][$sParentState];

			// Reset the constraint when it was mandatory to set the value at the previous state
			//
			foreach ($aToInherit['attribute_list'] as $sState => $iFlags)
			{
				$iFlags = $iFlags & ~OPT_ATT_MUSTPROMPT;
				$iFlags = $iFlags & ~OPT_ATT_MUSTCHANGE;
				$aToInherit['attribute_list'][$sState] = $iFlags;
			}
			
			// The inherited configuration could be overriden
			$aStateDef['attribute_list'] = array_merge($aToInherit['attribute_list'], $aStateDef['attribute_list']);
		}

		foreach($aStateDef['attribute_list'] as $sAttCode => $iFlags)
		{
			if (isset(self::$m_aIgnoredAttributes[$sTargetClass][$sAttCode]))
			{
				unset($aStateDef['attribute_list'][$sAttCode]);
			}
		}

		self::$m_aStates[$sTargetClass][$sStateCode] = $aStateDef;

		// by default, create an empty set of transitions associated to that state
		self::$m_aTransitions[$sTargetClass][$sStateCode] = array();
	}

	public static function Init_DefineHighlightScale($aHighlightScale)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		self::$m_aHighlightScales[$sTargetClass] = $aHighlightScale;
	}
	
	public static function GetHighlightScale($sTargetClass)
	{
		$aScale = array();
		$aParentScale = array();
		$sParentClass = self::GetParentPersistentClass($sTargetClass);
		if (!empty($sParentClass))
		{
			// inherit the scale from the parent class
			$aParentScale = self::GetHighlightScale($sParentClass);
		}
		if (array_key_exists($sTargetClass, self::$m_aHighlightScales))
		{
			$aScale = self::$m_aHighlightScales[$sTargetClass];
		}
		return array_merge($aParentScale, $aScale); // Merge both arrays, the values from the last one have precedence
	}
	
	public static function GetHighlightCode($sTargetClass, $sStateCode)
	{
		$sCode = '';
		if ( array_key_exists($sTargetClass, self::$m_aStates)
			 && array_key_exists($sStateCode, self::$m_aStates[$sTargetClass])
			 && array_key_exists('highlight', self::$m_aStates[$sTargetClass][$sStateCode]) )
		{
			$sCode = self::$m_aStates[$sTargetClass][$sStateCode]['highlight']['code'];
		}
		else
		{
			// Check the parent's definition
			$sParentClass = self::GetParentPersistentClass($sTargetClass);
			if (!empty($sParentClass))
			{
				$sCode = self::GetHighlightCode($sParentClass, $sStateCode);
			}
		}
		return $sCode;
	}
	
	public static function Init_OverloadStateAttribute($sStateCode, $sAttCode, $iFlags)
	{
		// Warning: this is not sufficient: the flags have to be copied to the states that are inheriting from this state
		$sTargetClass = self::GetCallersPHPClass("Init");
		self::$m_aStates[$sTargetClass][$sStateCode]['attribute_list'][$sAttCode] = $iFlags;
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
		self::$m_aStates[$sTargetClass] = self::$m_aStates[$sSourceClass];
		// #@# Note: the aim is to clone the data, could be an issue if the simuli objects are changed
		self::$m_aStimuli[$sTargetClass] = self::$m_aStimuli[$sSourceClass];
		self::$m_aTransitions[$sTargetClass] = self::$m_aTransitions[$sSourceClass];
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
	public static function GetParentClass($sClass)
	{
		if (count(self::$m_aParentClasses[$sClass]) == 0)
		{
			return null;
		}
		else
		{
			return end(self::$m_aParentClasses[$sClass]);
		}
	}
	public static function GetLowestCommonAncestor($aClasses)
	{
		$sAncestor = null;
		foreach($aClasses as $sClass)
		{
			if (is_null($sAncestor))
			{
				// first loop
				$sAncestor = $sClass;
			}
			elseif ($sClass == $sAncestor)
			{
				// remains the same
			}
			elseif (self::GetRootClass($sClass) != self::GetRootClass($sAncestor))
			{
				$sAncestor = null;
				break;
			}
			else
			{
				$sAncestor = self::LowestCommonAncestor($sAncestor, $sClass);
			}
		}
		return $sAncestor;
	}
	/**
	 * Note: assumes that class A and B have a common ancestor	
	 */	
	protected static function LowestCommonAncestor($sClassA, $sClassB)
	{
		if ($sClassA == $sClassB)
		{
			$sRet = $sClassA;
		}
		elseif (is_subclass_of($sClassA, $sClassB))
		{
			$sRet = $sClassB;
		}
		elseif (is_subclass_of($sClassB, $sClassA))
		{
			$sRet = $sClassA;
		}
		else
		{
			// Recurse
			$sRet = self::LowestCommonAncestor($sClassA, self::GetParentClass($sClassB));
		}
		return $sRet;
	}

	/**
	 * Tells if a class contains a hierarchical key, and if so what is its AttCode
	 * @return mixed String = sAttCode or false if the class is not part of a hierarchy
	 */
	public static function IsHierarchicalClass($sClass)
	{
		$sHierarchicalKeyCode = false;
		foreach (self::ListAttributeDefs($sClass) as $sAttCode => $oAtt)
		{
			if ($oAtt->IsHierarchicalKey())
			{
				$sHierarchicalKeyCode = $sAttCode; // Found the hierarchical key, no need to continue
				break;
			}
		}
		return $sHierarchicalKeyCode;
	}
	public static function EnumRootClasses()
	{
		return array_unique(self::$m_aRootClasses);
	}
	public static function EnumParentClasses($sClass, $iOption = ENUM_PARENT_CLASSES_EXCLUDELEAF, $bRootFirst = true)
	{
		self::_check_subclass($sClass);	
		if ($bRootFirst)
		{
			$aRes = self::$m_aParentClasses[$sClass];
		}
		else
		{
			$aRes = array_reverse(self::$m_aParentClasses[$sClass], true);
		}
		if ($iOption != ENUM_PARENT_CLASSES_EXCLUDELEAF)
		{
			if ($bRootFirst)
			{
				// Leaf class at the end
				$aRes[] = $sClass;
			}
			else
			{
				// Leaf class on top
				array_unshift($aRes, $sClass);
			}
		}
		return $aRes;
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
	public static function HasChildrenClasses($sClass)
	{
		return (count(self::$m_aChildClasses[$sClass]) > 0);
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
		foreach(self::$m_aClassParams as $sSubClass => $foo)
		{
			if (is_subclass_of($sSubClass, $sClass))
			{
				$aSubClasses[] = $sSubClass;
			}
		}
		return $aSubClasses;
	}
	public static function GetClasses($sCategories = '', $bStrict = false)
	{
		$aCategories = explode(',', $sCategories);
		$aClasses = array();
		foreach($aCategories as $sCategory)
		{
			$sCategory = trim($sCategory);
			if (strlen($sCategory) == 0)
			{
				return array_keys(self::$m_aClassParams);
			}

			if (array_key_exists($sCategory, self::$m_Category2Class))
			{
				$aClasses = array_merge($aClasses, self::$m_Category2Class[$sCategory]);
			}
			elseif ($bStrict)
			{
				throw new CoreException("unkown class category '$sCategory', expecting a value in {".implode(', ', array_keys(self::$m_Category2Class))."}");
			}
		}
		
		return array_unique($aClasses);
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

	public static function PrepareQueryArguments($aArgs)
	{
		// Translate any object into scalars
		//
		$aScalarArgs = array();
		foreach($aArgs as $sArgName => $value)
		{
			if (self::IsValidObject($value))
			{
				if (strpos($sArgName, '->object()') === false)
				{
					// Lazy syntax - develop the object contextual parameters
					$aScalarArgs = array_merge($aScalarArgs, $value->ToArgsForQuery($sArgName));
				}
				else
				{
					// Leave as is
					$aScalarArgs[$sArgName] = $value;
				}
			}
			else
			{
				if (is_scalar($value))
				{
					$aScalarArgs[$sArgName] = (string) $value;
				}
				elseif (is_null($value))
				{
					$aScalarArgs[$sArgName] = null;
				}
			}
		}
		// Add standard contextual arguments
		//
		$aScalarArgs['current_contact_id'] = UserRights::GetContactId();
		return $aScalarArgs;
	}

	public static function MakeModifierProperties($oFilter)
	{
		// Compute query modifiers properties (can be set in the search itself, by the context, etc.)
		//
		$aModifierProperties = array();
		foreach (MetaModel::EnumPlugins('iQueryModifier') as $sPluginClass => $oQueryModifier)
		{
			// Lowest precedence: the application context
			$aPluginProps = ApplicationContext::GetPluginProperties($sPluginClass);
			// Highest precedence: programmatically specified (or OQL)
			foreach($oFilter->GetModifierProperties($sPluginClass) as $sProp => $value)
			{
				$aPluginProps[$sProp] = $value;
			}
			if (count($aPluginProps) > 0)
			{
				$aModifierProperties[$sPluginClass] = $aPluginProps;
			}
		}
		return $aModifierProperties;
	}


	/**
	 * Special processing for the hierarchical keys stored as nested sets
	 * @param $iId integer The identifier of the parent
	 * @param $oAttDef AttributeDefinition The attribute corresponding to the hierarchical key
	 * @param $stable string The name of the database table containing the hierarchical key 
	 */
	public static function HKInsertChildUnder($iId, $oAttDef, $sTable)
	{
		// Get the parent id.right value
		if ($iId == 0)
		{
			// No parent, insert completely at the right of the tree
			$sSQL = "SELECT max(`".$oAttDef->GetSQLRight()."`) AS max FROM `$sTable`";
			$aRes = CMDBSource::QueryToArray($sSQL);
			if (count($aRes) == 0)
			{
				$iMyRight = 1;
			}
			else
			{
				$iMyRight = $aRes[0]['max']+1;
			}
		}
		else
		{
			$sSQL = "SELECT `".$oAttDef->GetSQLRight()."` FROM `$sTable` WHERE id=".$iId;
			$iMyRight = CMDBSource::QueryToScalar($sSQL);
			$sSQLUpdateRight = "UPDATE `$sTable` SET `".$oAttDef->GetSQLRight()."` = `".$oAttDef->GetSQLRight()."` + 2 WHERE `".$oAttDef->GetSQLRight()."` >= $iMyRight";
			CMDBSource::Query($sSQLUpdateRight);
			$sSQLUpdateLeft = "UPDATE `$sTable` SET `".$oAttDef->GetSQLLeft()."` = `".$oAttDef->GetSQLLeft()."` + 2 WHERE `".$oAttDef->GetSQLLeft()."` > $iMyRight";
			CMDBSource::Query($sSQLUpdateLeft);
		}
		return array($oAttDef->GetSQLRight() =>  $iMyRight+1, $oAttDef->GetSQLLeft() => $iMyRight);
	}

	/**
	 * Special processing for the hierarchical keys stored as nested sets: temporary remove the branch
	 * @param $iId integer The identifier of the parent
	 * @param $oAttDef AttributeDefinition The attribute corresponding to the hierarchical key
	 * @param $sTable string The name of the database table containing the hierarchical key 
	 */
	public static function HKTemporaryCutBranch($iMyLeft, $iMyRight, $oAttDef, $sTable)
	{
		$iDelta = $iMyRight - $iMyLeft + 1;
		$sSQL = "UPDATE `$sTable` SET `".$oAttDef->GetSQLRight()."` = $iMyLeft - `".$oAttDef->GetSQLRight()."`, `".$oAttDef->GetSQLLeft()."` = $iMyLeft - `".$oAttDef->GetSQLLeft();
		$sSQL .= "` WHERE  `".$oAttDef->GetSQLLeft()."`> $iMyLeft AND `".$oAttDef->GetSQLRight()."`< $iMyRight";
		CMDBSource::Query($sSQL);
		$sSQL = "UPDATE `$sTable` SET `".$oAttDef->GetSQLLeft()."` = `".$oAttDef->GetSQLLeft()."` - $iDelta WHERE `".$oAttDef->GetSQLLeft()."` > $iMyRight";
		CMDBSource::Query($sSQL);
		$sSQL = "UPDATE `$sTable` SET `".$oAttDef->GetSQLRight()."` = `".$oAttDef->GetSQLRight()."` - $iDelta WHERE `".$oAttDef->GetSQLRight()."` > $iMyRight";
		CMDBSource::Query($sSQL);
	}

	/**
	 * Special processing for the hierarchical keys stored as nested sets: replug the temporary removed branch
	 * @param $iId integer The identifier of the parent
	 * @param $oAttDef AttributeDefinition The attribute corresponding to the hierarchical key
	 * @param $sTable string The name of the database table containing the hierarchical key 
	 */
	public static function HKReplugBranch($iNewLeft, $iNewRight, $oAttDef, $sTable)
	{
		$iDelta = $iNewRight - $iNewLeft + 1;
		$sSQL = "UPDATE `$sTable` SET `".$oAttDef->GetSQLLeft()."` = `".$oAttDef->GetSQLLeft()."` + $iDelta WHERE `".$oAttDef->GetSQLLeft()."` > $iNewLeft";
		CMDBSource::Query($sSQL);
		$sSQL = "UPDATE `$sTable` SET `".$oAttDef->GetSQLRight()."` = `".$oAttDef->GetSQLRight()."` + $iDelta WHERE `".$oAttDef->GetSQLRight()."` >= $iNewLeft";
		CMDBSource::Query($sSQL);
		$sSQL = "UPDATE `$sTable` SET `".$oAttDef->GetSQLRight()."` = $iNewLeft - `".$oAttDef->GetSQLRight()."`, `".$oAttDef->GetSQLLeft()."` = $iNewLeft - `".$oAttDef->GetSQLLeft()."` WHERE `".$oAttDef->GetSQLRight()."`< 0";
		CMDBSource::Query($sSQL);
	}

	/**
	 * Check (and updates if needed) the hierarchical keys
	 * @param $bDiagnosticsOnly boolean If true only a diagnostic pass will be run, returning true or false
	 * @param $bVerbose boolean Displays some information about what is done/what needs to be done	 
	 * @param $bForceComputation boolean If true, the _left and _right parameters will be recomputed even if some values already exist in the DB	 
	 */	 	
	public static function CheckHKeys($bDiagnosticsOnly = false, $bVerbose = false, $bForceComputation = false)
	{
		$bChangeNeeded = false;
		foreach (self::GetClasses() as $sClass)
		{
			if (!self::HasTable($sClass)) continue;

			foreach(self::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
			{
				// Check (once) all the attributes that are hierarchical keys
				if((self::GetAttributeOrigin($sClass, $sAttCode) == $sClass) && $oAttDef->IsHierarchicalKey())
				{
					if ($bVerbose)
					{
						echo "The attribute $sAttCode from $sClass is a hierarchical key.\n";				
					}
					$bResult = self::HKInit($sClass, $sAttCode, $bDiagnosticsOnly, $bVerbose, $bForceComputation);
					$bChangeNeeded |= $bResult;
					if ($bVerbose && !$bResult)
					{
						echo "Ok, the attribute $sAttCode from class $sClass seems up to date.\n";				
					}
				}
			}
		}
		return $bChangeNeeded;
	}

	/**
	 * Initializes (i.e converts) a hierarchy stored using a 'parent_id' external key
	 * into a hierarchy stored with a HierarchicalKey, by initializing the _left and _right values
	 * to correspond to the existing hierarchy in the database
	 * @param $sClass string Name of the class to process
	 * @param $sAttCode string Code of the attribute to process
	 * @param $bDiagnosticsOnly boolean If true only a diagnostic pass will be run, returning true or false
	 * @param $bVerbose boolean Displays some information about what is done/what needs to be done	 
	 * @param $bForceComputation boolean If true, the _left and _right parameters will be recomputed even if some values already exist in the DB
	 * @return true if an update is needed (diagnostics only) / was performed	 
	 */
	public static function HKInit($sClass, $sAttCode, $bDiagnosticsOnly = false, $bVerbose = false, $bForceComputation = false)
	{
		$idx = 1;
		$bUpdateNeeded = $bForceComputation;
		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
		$sTable = self::DBGetTable($sClass, $sAttCode);
		if ($oAttDef->IsHierarchicalKey())
		{
			// Check if some values already exist in the table for the _right value, if so, do nothing
			$sRight = $oAttDef->GetSQLRight();
			$sSQL = "SELECT MAX(`$sRight`) AS MaxRight FROM `$sTable`";
			$iMaxRight = CMDBSource::QueryToScalar($sSQL);
			$sSQL = "SELECT COUNT(*) AS Count FROM `$sTable`"; // Note: COUNT(field) returns zero if the given field contains only NULLs
			$iCount = CMDBSource::QueryToScalar($sSQL);
			if (!$bForceComputation && ($iCount != 0) && ($iMaxRight == 0))
			{
				$bUpdateNeeded = true;
				if ($bVerbose)
				{
					echo "The table '$sTable' must be updated to compute the fields $sRight and ".$oAttDef->GetSQLLeft()."\n";
				}
			}
			if ($bForceComputation && !$bDiagnosticsOnly)
			{
				echo "Rebuilding the fields $sRight and ".$oAttDef->GetSQLLeft()." from table '$sTable'...\n";
			}
			if ($bUpdateNeeded && !$bDiagnosticsOnly)
			{
				try
				{
					CMDBSource::Query('START TRANSACTION');
					self::HKInitChildren($sTable, $sAttCode, $oAttDef, 0, $idx);
					CMDBSource::Query('COMMIT');
					if ($bVerbose)
					{
						echo "Ok, table '$sTable' successfully updated.\n";
					}
				}
				catch(Exception $e)
				{
					CMDBSource::Query('ROLLBACK');
					throw new Exception("An error occured (".$e->getMessage().") while initializing the hierarchy for ($sClass, $sAttCode). The database was not modified.");
				}
			}
		}
		return $bUpdateNeeded;
	}
	
	/**
	 * Recursive helper function called by HKInit
	 */
	protected static function HKInitChildren($sTable, $sAttCode, $oAttDef, $iId, &$iCurrIndex)
	{
		$sSQL = "SELECT id FROM `$sTable` WHERE `$sAttCode` = $iId";
		$aRes = CMDBSource::QueryToArray($sSQL);
		$aTree = array();
		$sLeft = $oAttDef->GetSQLLeft();
		$sRight = $oAttDef->GetSQLRight();
		foreach($aRes as $aValues)
		{
			$iChildId = $aValues['id'];
			$iLeft = $iCurrIndex++;
			$aChildren = self::HKInitChildren($sTable, $sAttCode, $oAttDef, $iChildId, $iCurrIndex);
			$iRight = $iCurrIndex++;
			$sSQL = "UPDATE `$sTable` SET `$sLeft` = $iLeft, `$sRight` = $iRight WHERE id= $iChildId";
			CMDBSource::Query($sSQL);
		}
	}
	
	public static function CheckDataSources($bDiagnostics, $bVerbose)
	{
		$sOQL = 'SELECT SynchroDataSource';
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
		$bFixNeeded = false;
		if ($bVerbose && $oSet->Count() == 0)
		{
			echo "There are no Data Sources in the database.\n";
		}
		while($oSource = $oSet->Fetch())
		{
			if ($bVerbose)
			{
				echo "Checking Data Source '".$oSource->GetName()."'...\n";
				$bFixNeeded = $bFixNeeded | $oSource->CheckDBConsistency($bDiagnostics, $bVerbose);
			}
		}
		if (!$bFixNeeded && $bVerbose)
		{
			echo "Ok.\n";
		}
		return $bFixNeeded;	
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

	public static function CheckDefinitions($bExitOnError = true)
	{
		if (count(self::GetClasses()) == 0)
		{
			throw new CoreException("MetaModel::InitClasses() has not been called, or no class has been declared ?!?!");
		}

		$aErrors = array();
		$aSugFix = array();
		foreach (self::GetClasses() as $sClass)
		{
			$sTable = self::DBGetTable($sClass);
			$sTableLowercase = strtolower($sTable);
			if ($sTableLowercase != $sTable)
			{
				$aErrors[$sClass][] = "Table name '".$sTable."' has upper case characters. You might encounter issues when moving your installation between Linux and Windows.";
				$aSugFix[$sClass][] = "Use '$sTableLowercase' instead. Step 1: If already installed, then rename manually in the DB: RENAME TABLE `$sTable` TO `{$sTableLowercase}_tempname`, `{$sTableLowercase}_tempname` TO `$sTableLowercase`; Step 2: Rename the table in the datamodel and compile the application. Note: the MySQL statement provided in step 1 has been designed to be compatible with Windows or Linux.";
			}

			$aNameSpec = self::GetNameSpec($sClass);
			foreach($aNameSpec[1] as $i => $sAttCode)
			{
				if(!self::IsValidAttCode($sClass, $sAttCode))
				{
					$aErrors[$sClass][] = "Unknown attribute code '".$sAttCode."' for the name definition";
					$aSugFix[$sClass][] = "Expecting a value in ".implode(", ", self::GetAttributesList($sClass));
				}
			}				

			foreach(self::GetReconcKeys($sClass) as $sReconcKeyAttCode)
			{
				if (!empty($sReconcKeyAttCode) && !self::IsValidAttCode($sClass, $sReconcKeyAttCode))
				{
					$aErrors[$sClass][] = "Unknown attribute code '".$sReconcKeyAttCode."' in the list of reconciliation keys";
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
						$aErrors[$sClass][] = "Unknown class '".$oAttDef->GetTargetClass()."' for the external key '$sAttCode'";
						$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetClasses())."}";
					}
				}
				elseif ($oAttDef->IsExternalField())
				{
					$sKeyAttCode = $oAttDef->GetKeyAttCode();
					if (!self::IsValidAttCode($sClass, $sKeyAttCode) || !self::IsValidKeyAttCode($sClass, $sKeyAttCode))
					{
						$aErrors[$sClass][] = "Unknown key attribute code '".$sKeyAttCode."' for the external field $sAttCode";
						$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetKeysList($sClass))."}";
					}
					else
					{
						$oKeyAttDef = self::GetAttributeDef($sClass, $sKeyAttCode);
						$sTargetClass = $oKeyAttDef->GetTargetClass();
						$sExtAttCode = $oAttDef->GetExtAttCode();
						if (!self::IsValidAttCode($sTargetClass, $sExtAttCode))
						{
							$aErrors[$sClass][] = "Unknown key attribute code '".$sExtAttCode."' for the external field $sAttCode";
							$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetKeysList($sTargetClass))."}";
						}
					}
				}
				else if ($oAttDef->IsLinkSet())
				{
					// Do nothing...
				}
				else if ($oAttDef instanceof AttributeStopWatch)
				{
					$aThresholds = $oAttDef->ListThresholds();
					if (is_array($aThresholds))
					{
						foreach($aThresholds as $iPercent => $aDef)
						{
							if (array_key_exists('highlight', $aDef))
							{
								if(!array_key_exists('code', $aDef['highlight']))
								{
									$aErrors[$sClass][] = "The 'code' element is missing for the 'highlight' property of the $iPercent% threshold in the attribute: '$sAttCode'.";
									$aSugFix[$sClass][] = "Add a 'code' entry specifying the value of the highlight code for this threshold.";
								}
								else
								{
									$aScale = self::GetHighlightScale($sClass);
									if (!array_key_exists($aDef['highlight']['code'], $aScale))
									{
										$aErrors[$sClass][] = "'{$aDef['highlight']['code']}' is not a valid value for the 'code' element of the $iPercent% threshold in the attribute: '$sAttCode'.";
										$aSugFix[$sClass][] = "The possible highlight codes for this class are: ".implode(', ', array_keys($aScale)).".";
									}
								}
							}
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
							if (is_string($sDefaultValue) && !array_key_exists($sDefaultValue, $aAllowedValues))
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
							$aErrors[$sClass][] = "Unknown attribute code '".$sDependOnAttCode."' in the list of prerequisite attributes";
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
					$aErrors[$sClass][] = "Unknown attribute code '".$sStateAttCode."' for the state definition";
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
	
					// Lifecycle - check that the action handlers are defined
					foreach (self::EnumStates($sClass) as $sStateCode => $aStateDef)
					{
						foreach(self::EnumTransitions($sClass, $sStateCode) as $sStimulusCode => $aTransitionDef)
						{
							foreach ($aTransitionDef['actions'] as $actionHandler)
							{
								if (is_string($actionHandler))
								{
									if (!method_exists($sClass, $actionHandler))
									{
										$aErrors[$sClass][] = "Unknown function '$sActionHandler' in transition [$sStateCode/$sStimulusCode] for state attribute '$sStateAttCode'";
										$aSugFix[$sClass][] = "Specify a function which prototype is in the form [public function $sActionHandler(\$sStimulusCode){return true;}]";
									}
								}
								else // if(is_array($actionHandler))
								{
									$sActionHandler = $actionHandler['verb'];
									if (!method_exists($sClass, $sActionHandler))
									{
										$aErrors[$sClass][] = "Unknown function '$sActionHandler' in transition [$sStateCode/$sStimulusCode] for state attribute '$sStateAttCode'";
										$aSugFix[$sClass][] = "Specify a function which prototype is in the form [public function $sActionHandler(...){return true;}]";
									}
								}
							}
						}
						if (array_key_exists('highlight', $aStateDef))
						{
							if(!array_key_exists('code', $aStateDef['highlight']))
							{
								$aErrors[$sClass][] = "The 'code' element is missing for the 'highlight' property of state: '$sStateCode'.";
								$aSugFix[$sClass][] = "Add a 'code' entry specifying the value of the highlight code for this state.";
							}
							else
							{
								$aScale = self::GetHighlightScale($sClass);
								if (!array_key_exists($aStateDef['highlight']['code'], $aScale))
								{
									$aErrors[$sClass][] = "'{$aStateDef['highlight']['code']}' is not a valid value for the 'code' element in the 'highlight' property of state: '$sStateCode'.";
									$aSugFix[$sClass][] = "The possible highlight codes for this class are: ".implode(', ', array_keys($aScale)).".";
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
				foreach (self::FlattenZList(self::GetZListItems($sClass, $sListCode)) as $sMyAttCode)
				{
					if (!self::IsValidAttCode($sClass, $sMyAttCode))
					{
						$aErrors[$sClass][] = "Unknown attribute code '".$sMyAttCode."' from ZList '$sListCode'";
						$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetAttributesList($sClass))."}";
					}
				}
			}

			// Check unicity of the SQL columns
			//
			if (self::HasTable($sClass))
			{
				$aTableColumns = array(); // array of column => attcode (the column is used by this attribute)
				$aTableColumns[self::DBGetKey($sClass)] = 'id';
				
				// Check that SQL columns are declared only once
				//
				foreach(self::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
				{
					// Skip this attribute if not originaly defined in this class
					if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass) continue;
	
					foreach($oAttDef->GetSQLColumns() as $sField => $sDBFieldType)
					{
						if (array_key_exists($sField, $aTableColumns))
						{
							$aErrors[$sClass][] = "Column '$sField' declared for attribute $sAttCode, but already used for attribute ".$aTableColumns[$sField];
							$aSugFix[$sClass][] = "Please find another name for the SQL column";
						}
						else
						{
							$aTableColumns[$sField] = $sAttCode;
						}
					}
				}
			}
		} // foreach class
		
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
			if ($bExitOnError) echo "<p>Aborting...</p>\n";
			echo "</div>\n";
			if ($bExitOnError) exit;
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
		echo "   <input type=\"hidden\" name=\"$sSQLStatementArgName\" value=\"".htmlentities(implode("##SEP##", $aCleanFixes), ENT_QUOTES, 'UTF-8')."\">\n";
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


	public static function DBCreate($aCallback = null)
	{
		// Note: we have to check if the DB does exist, because we may share the DB
		//       with other applications (in which case the DB does exist, not the tables with the given prefix)
		if (!CMDBSource::IsDB(self::$m_sDBName))
		{
			CMDBSource::CreateDB(self::$m_sDBName);
		}
		self::DBCreateTables($aCallback);
		self::DBCreateViews();
	}

	protected static function DBCreateTables($aCallback = null)
	{
		list($aErrors, $aSugFix, $aCondensedQueries) = self::DBCheckFormat();

		//$sSQL = implode('; ', $aCondensedQueries); Does not work - multiple queries not allowed
		foreach($aCondensedQueries as $sQuery)
		{
			$fStart = microtime(true);
			CMDBSource::CreateTable($sQuery);
			$fDuration = microtime(true) - $fStart;
			if ($aCallback != null)
			{
				call_user_func($aCallback, $sQuery, $fDuration);
			}
		}
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

	/*
	* Determines wether the target DB is frozen or not
	*/		
	public static function DBIsReadOnly()
	{
		// Improvement: check the mySQL variable -> Read-only

		if (UserRights::IsAdministrator())
		{
			return (!self::DBHasAccess(ACCESS_ADMIN_WRITE));
		}
		else
		{
			return (!self::DBHasAccess(ACCESS_USER_WRITE));
		}
	}

	public static function DBHasAccess($iRequested = ACCESS_FULL)
	{
		$iMode = self::$m_oConfig->Get('access_mode');
		if (($iMode & $iRequested) == 0) return false;
		return true;
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
		$sRes .= htmlentities("// Class:<class_name>\n", ENT_QUOTES, 'UTF-8');
		$sRes .= htmlentities("// Class:<class_name>+\n", ENT_QUOTES, 'UTF-8');
		$sRes .= htmlentities("// Class:<class_name>/Attribute:<attribute_code>\n", ENT_QUOTES, 'UTF-8');
		$sRes .= htmlentities("// Class:<class_name>/Attribute:<attribute_code>+\n", ENT_QUOTES, 'UTF-8');
		$sRes .= htmlentities("// Class:<class_name>/Attribute:<attribute_code>/Value:<value>\n", ENT_QUOTES, 'UTF-8');
		$sRes .= htmlentities("// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+\n", ENT_QUOTES, 'UTF-8');
		$sRes .= htmlentities("// Class:<class_name>/Stimulus:<stimulus_code>\n", ENT_QUOTES, 'UTF-8');
		$sRes .= htmlentities("// Class:<class_name>/Stimulus:<stimulus_code>+\n", ENT_QUOTES, 'UTF-8');
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

		// A new way of representing things to be done - quicker to execute !
		$aCreateTable = array(); // array of <table> => <table options>
		$aCreateTableItems = array(); // array of <table> => array of <create definition>
		$aAlterTableItems = array(); // array of <table> => <alter specification>
		
		foreach (self::GetClasses() as $sClass)
		{
			if (!self::HasTable($sClass)) continue;

			// Check that the table exists
			//
			$sTable = self::DBGetTable($sClass);
			$sKeyField = self::DBGetKey($sClass);
			$sAutoIncrement = (self::IsAutoIncrementKey($sClass) ? "AUTO_INCREMENT" : "");
			$sKeyFieldDefinition = "`$sKeyField` INT(11) NOT NULL $sAutoIncrement PRIMARY KEY";
			if (!CMDBSource::IsTable($sTable))
			{
				$aErrors[$sClass]['*'][] = "table '$sTable' could not be found into the DB";
				$aSugFix[$sClass]['*'][] = "CREATE TABLE `$sTable` ($sKeyFieldDefinition) ENGINE = ".MYSQL_ENGINE." CHARACTER SET utf8 COLLATE utf8_unicode_ci";
				$aCreateTable[$sTable] = "ENGINE = ".MYSQL_ENGINE." CHARACTER SET utf8 COLLATE utf8_unicode_ci";
				$aCreateTableItems[$sTable][$sKeyField] = $sKeyFieldDefinition;
			}
			// Check that the key field exists
			//
			elseif (!CMDBSource::IsField($sTable, $sKeyField))
			{
				$aErrors[$sClass]['id'][] = "key '$sKeyField' (table $sTable) could not be found";
				$aSugFix[$sClass]['id'][] = "ALTER TABLE `$sTable` ADD $sKeyFieldDefinition";
				if (!array_key_exists($sTable, $aCreateTable))
				{
					$aAlterTableItems[$sTable][$sKeyField] = "ADD $sKeyFieldDefinition";
				}
			}
			else
			{
				// Check the key field properties
				//
				if (!CMDBSource::IsKey($sTable, $sKeyField))
				{
					$aErrors[$sClass]['id'][] = "key '$sKeyField' is not a key for table '$sTable'";
					$aSugFix[$sClass]['id'][] = "ALTER TABLE `$sTable`, DROP PRIMARY KEY, ADD PRIMARY key(`$sKeyField`)";
					if (!array_key_exists($sTable, $aCreateTable))
					{
						$aAlterTableItems[$sTable][$sKeyField] = "CHANGE `$sKeyField` $sKeyFieldDefinition";
					}
				}
				if (self::IsAutoIncrementKey($sClass) && !CMDBSource::IsAutoIncrement($sTable, $sKeyField))
				{
					$aErrors[$sClass]['id'][] = "key '$sKeyField' (table $sTable) is not automatically incremented";
					$aSugFix[$sClass]['id'][] = "ALTER TABLE `$sTable` CHANGE `$sKeyField` $sKeyFieldDefinition";
					if (!array_key_exists($sTable, $aCreateTable))
					{
						$aAlterTableItems[$sTable][$sKeyField] = "CHANGE `$sKeyField` $sKeyFieldDefinition";
					}
				}
			}
			
			// Check that any defined field exists
			//
			$aTableInfo = CMDBSource::GetTableInfo($sTable);
			$aTableInfo['Fields'][$sKeyField]['used'] = true;
			foreach(self::ListAttributeDefs($sClass) as $sAttCode=>$oAttDef)
			{
				// Skip this attribute if not originaly defined in this class
				if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass) continue;

				foreach($oAttDef->GetSQLColumns(true) as $sField => $sDBFieldSpec)
				{
					// Keep track of columns used by iTop
					$aTableInfo['Fields'][$sField]['used'] = true;

					$bIndexNeeded = $oAttDef->RequiresIndex();

					$sFieldDefinition = "`$sField` $sDBFieldSpec";
					if (!CMDBSource::IsField($sTable, $sField))
					{
						$aErrors[$sClass][$sAttCode][] = "field '$sField' could not be found in table '$sTable'";
						$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` ADD $sFieldDefinition";
						if ($bIndexNeeded)
						{
							$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` ADD INDEX (`$sField`)";
						}
						if (array_key_exists($sTable, $aCreateTable))
						{
							$aCreateTableItems[$sTable][$sField] = $sFieldDefinition;
							if ($bIndexNeeded)
							{
								$aCreateTableItems[$sTable][] = "INDEX (`$sField`)";
							}
						}
						else
						{
							$aAlterTableItems[$sTable][$sField] = "ADD $sFieldDefinition";
							if ($bIndexNeeded)
							{
								$aAlterTableItems[$sTable][] = "ADD INDEX (`$sField`)";
							}
						}
					}
					else
					{
						// The field already exists, does it have the relevant properties?
						//
						$bToBeChanged = false;
						$sActualFieldSpec = CMDBSource::GetFieldSpec($sTable, $sField);
						if (strcasecmp($sDBFieldSpec, $sActualFieldSpec) != 0)
						{
							$bToBeChanged  = true;
							$aErrors[$sClass][$sAttCode][] = "field '$sField' in table '$sTable' has a wrong type: found '$sActualFieldSpec' while expecting '$sDBFieldSpec'";
						} 
						if ($bToBeChanged)
						{
							$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` CHANGE `$sField` $sFieldDefinition";
							$aAlterTableItems[$sTable][$sField] = "CHANGE `$sField` $sFieldDefinition";
						}

						// Create indexes (external keys only... so far)
						//
						if ($bIndexNeeded && !CMDBSource::HasIndex($sTable, $sField, array($sField)))
						{
							$aErrors[$sClass][$sAttCode][] = "Foreign key '$sField' in table '$sTable' should have an index";
							if (CMDBSource::HasIndex($sTable, $sField))
							{
								$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` DROP INDEX `$sField`, ADD INDEX (`$sField`)";
								$aAlterTableItems[$sTable][] = "DROP INDEX `$sField`";
								$aAlterTableItems[$sTable][] = "ADD INDEX (`$sField`)";
							}
							else
							{
								$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` ADD INDEX (`$sField`)";
								$aAlterTableItems[$sTable][] = "ADD INDEX (`$sField`)";
							}
						}
					}
				}
			}
			
			// Check indexes
			foreach (self::DBGetIndexes($sClass) as $aColumns)
			{
				$sIndexId = implode('_', $aColumns);

				if(!CMDBSource::HasIndex($sTable, $sIndexId, $aColumns))
				{
					$sColumns = "`".implode("`, `", $aColumns)."`";
					if (CMDBSource::HasIndex($sTable, $sIndexId))
					{
						$aErrors[$sClass]['*'][] = "Wrong index '$sIndexId' ($sColumns) in table '$sTable'";
						$aSugFix[$sClass]['*'][] = "ALTER TABLE `$sTable` DROP INDEX `$sIndexId`, ADD INDEX `$sIndexId` ($sColumns)";
					}
					else
					{
						$aErrors[$sClass]['*'][] = "Missing index '$sIndexId' ($sColumns) in table '$sTable'";
						$aSugFix[$sClass]['*'][] = "ALTER TABLE `$sTable` ADD INDEX `$sIndexId` ($sColumns)";
					}
					if (array_key_exists($sTable, $aCreateTable))
					{
						$aCreateTableItems[$sTable][] = "INDEX `$sIndexId` ($sColumns)";
					}
					else
					{
						if (CMDBSource::HasIndex($sTable, $sIndexId))
						{
							$aAlterTableItems[$sTable][] = "DROP INDEX `$sIndexId`";
						}
						$aAlterTableItems[$sTable][] = "ADD INDEX `$sIndexId` ($sColumns)";
					}
				}
			}
			
			// Find out unused columns
			//
			foreach($aTableInfo['Fields'] as $sField => $aFieldData)
			{
				if (!isset($aFieldData['used']) || !$aFieldData['used'])
				{
					$aErrors[$sClass]['*'][] = "Column '$sField' in table '$sTable' is not used";
					if (!CMDBSource::IsNullAllowed($sTable, $sField))
					{
						// Allow null values so that new record can be inserted
						// without specifying the value of this unknown column
						$sFieldDefinition = "`$sField` ".CMDBSource::GetFieldType($sTable, $sField).' NULL';
						$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` CHANGE `$sField` $sFieldDefinition";
						$aAlterTableItems[$sTable][$sField] = "CHANGE `$sField` $sFieldDefinition";
					}
				}
			}
		}

		$aCondensedQueries = array();
		foreach($aCreateTable as $sTable => $sTableOptions)
		{
			$sTableItems = implode(', ', $aCreateTableItems[$sTable]);
			$aCondensedQueries[] = "CREATE TABLE `$sTable` ($sTableItems) $sTableOptions";
		}
		foreach($aAlterTableItems as $sTable => $aChangeList)
		{
			$sChangeList = implode(', ', $aChangeList);
			$aCondensedQueries[] = "ALTER TABLE `$sTable` $sChangeList";
		}

		return array($aErrors, $aSugFix, $aCondensedQueries);
	}

	public static function DBCheckViews()
	{
		$aErrors = array();
		$aSugFix = array();

		// Reporting views (must be created after any other table)
		//
		foreach (self::GetClasses('bizmodel') as $sClass)
		{
			$sView = self::DBGetView($sClass);
			if (CMDBSource::IsTable($sView))
			{
				// Check that the view is complete
				//
				// Note: checking the list of attributes is not enough because the columns can be stable while the SELECT is not stable
				//       Example: new way to compute the friendly name
				//       The correct comparison algorithm is to compare the queries,
				//       by using "SHOW CREATE VIEW" (MySQL 5.0.1 required) or to look into INFORMATION_SCHEMA/views
				//       both requiring some privileges
				// Decision: to simplify, let's consider the views as being wrong anytime
				if (true)
				{
					// Rework the view
					//
					$oFilter = new DBObjectSearch($sClass, '');
					$oFilter->AllowAllData();
					$sSQL = $oFilter->MakeSelectQuery();
					$aErrors[$sClass]['*'][] = "Redeclare view '$sView' (systematic - to support an eventual change in the friendly name computation)";
					$aSugFix[$sClass]['*'][] = "ALTER VIEW `$sView` AS $sSQL";
				}
			}
			else
			{
				// Create the view
				//
				$oFilter = new DBObjectSearch($sClass, '');
				$oFilter->AllowAllData();
				$sSQL = $oFilter->MakeSelectQuery();
				$aErrors[$sClass]['*'][] = "Missing view for class: $sClass";
				$aSugFix[$sClass]['*'][] = "DROP VIEW IF EXISTS `$sView`";
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

					$aCols = $oAttDef->GetSQLExpressions(); // Workaround a PHP bug: sometimes issuing a Notice if invoking current(somefunc())
					$sExtKeyField = current($aCols); // get the first column for an external key

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
	
						$aCols = $oAttDef->GetSQLExpressions(); // Workaround a PHP bug: sometimes issuing a Notice if invoking current(somefunc())
						$sMyAttributeField = current($aCols); // get the first column for the moment
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

	public static function Startup($config, $bModelOnly = false, $bAllowCache = true, $bTraceSourceFiles = false)
	{
		if (!defined('MODULESROOT'))
		{
			define('MODULESROOT', APPROOT.'env-'.utils::GetCurrentEnvironment().'/');
	
			self::$m_bTraceSourceFiles = $bTraceSourceFiles;
	
			// $config can be either a filename, or a Configuration object (volatile!)
			if ($config instanceof Config)
			{
				self::LoadConfig($config, $bAllowCache);
			}
			else
			{
				self::LoadConfig(new Config($config), $bAllowCache);
			}
	
			if ($bModelOnly) return;
		}
		
		CMDBSource::SelectDB(self::$m_sDBName);

		foreach(get_declared_classes() as $sPHPClass)
		{
			if (is_subclass_of($sPHPClass, 'ModuleHandlerAPI'))
			{
				$aCallSpec = array($sPHPClass, 'OnMetaModelStarted');
				call_user_func_array($aCallSpec, array());
			}
		}

		if (false)
		{
			echo "Debug<br/>\n";
			self::static_var_dump();
		}
	}

	public static function LoadConfig($oConfiguration, $bAllowCache = false)
	{
		self::$m_oConfig = $oConfiguration;

		// Set log ASAP
		if (self::$m_oConfig->GetLogGlobal())
		{
			if (self::$m_oConfig->GetLogIssue())
			{
				self::$m_bLogIssue = true;
				IssueLog::Enable(APPROOT.'log/error.log');
			}
			self::$m_bLogNotification = self::$m_oConfig->GetLogNotification();
			self::$m_bLogWebService = self::$m_oConfig->GetLogWebService();

			ToolsLog::Enable(APPROOT.'log/tools.log');
		}
		else
		{
			self::$m_bLogIssue = false;
			self::$m_bLogNotification = false;
			self::$m_bLogWebService = false;
		}

		ExecutionKPI::EnableDuration(self::$m_oConfig->Get('log_kpi_duration'));
		ExecutionKPI::EnableMemory(self::$m_oConfig->Get('log_kpi_memory'));
		ExecutionKPI::SetAllowedUser(self::$m_oConfig->Get('log_kpi_user_id'));

		self::$m_bSkipCheckToWrite = self::$m_oConfig->Get('skip_check_to_write');
		self::$m_bSkipCheckExtKeys = self::$m_oConfig->Get('skip_check_ext_keys');

		self::$m_bUseAPCCache = $bAllowCache
										&& self::$m_oConfig->Get('apc_cache.enabled')
										&& function_exists('apc_fetch')
										&& function_exists('apc_store');

		DBSearch::EnableQueryCache(self::$m_oConfig->GetQueryCacheEnabled(), self::$m_bUseAPCCache, self::$m_oConfig->Get('apc_cache.query_ttl'));
		DBSearch::EnableQueryTrace(self::$m_oConfig->GetLogQueries());
		DBSearch::EnableQueryIndentation(self::$m_oConfig->Get('query_indentation_enabled'));
		DBSearch::EnableOptimizeQuery(self::$m_oConfig->Get('query_optimization_enabled'));

		// PHP timezone first...
		//
		$sPHPTimezone = self::$m_oConfig->Get('timezone');
		if ($sPHPTimezone == '')
		{
			// Leave as is... up to the admin to set a value somewhere...
			//$sPHPTimezone = date_default_timezone_get();
		}
		else
		{
			date_default_timezone_set($sPHPTimezone);
		}

		// Note: load the dictionary as soon as possible, because it might be
		//       needed when some error occur
		$sAppIdentity = 'itop-'.MetaModel::GetEnvironmentId();
		$bDictInitializedFromData = false;
		if (!self::$m_bUseAPCCache || !Dict::InCache($sAppIdentity))
		{
			$bDictInitializedFromData = true;
			foreach (self::$m_oConfig->GetDictionaries() as $sModule => $sToInclude)
			{
				self::IncludeModule('dictionaries', $sToInclude);
			}
		}		
		// Set the language... after the dictionaries have been loaded!
		Dict::SetDefaultLanguage(self::$m_oConfig->GetDefaultLanguage());

		// Romain: this is the only way I've found to cope with the fact that
		//         classes have to be derived from cmdbabstract (to be editable in the UI)
		require_once(APPROOT.'/application/cmdbabstract.class.inc.php');

		foreach (self::$m_oConfig->GetAppModules() as $sModule => $sToInclude)
		{
			self::IncludeModule('application', $sToInclude);
		}
		foreach (self::$m_oConfig->GetDataModels() as $sModule => $sToInclude)
		{
			self::IncludeModule('business', $sToInclude);
		}
		foreach (self::$m_oConfig->GetWebServiceCategories() as $sModule => $sToInclude)
		{
			self::IncludeModule('webservice', $sToInclude);
		}
		foreach (self::$m_oConfig->GetAddons() as $sModule => $sToInclude)
		{
			self::IncludeModule('addons', $sToInclude);
		}

		$sServer = self::$m_oConfig->GetDBHost();
		$sUser = self::$m_oConfig->GetDBUser();
		$sPwd = self::$m_oConfig->GetDBPwd();
		$sSource = self::$m_oConfig->GetDBName();
		$sTablePrefix = self::$m_oConfig->GetDBSubname();
		$sCharacterSet = self::$m_oConfig->GetDBCharacterSet();
		$sCollation = self::$m_oConfig->GetDBCollation();

		if (self::$m_bUseAPCCache)
		{
			$oKPI = new ExecutionKPI();
			// Note: For versions of APC older than 3.0.17, fetch() accepts only one parameter
			//
			$sOqlAPCCacheId = 'itop-'.MetaModel::GetEnvironmentId().'-metamodel';
			$result = apc_fetch($sOqlAPCCacheId);

			if (is_array($result))
			{
				// todo - verifier que toutes les classes mentionnees ici sont chargees dans InitClasses()
				self::$m_aExtensionClasses = $result['m_aExtensionClasses'];
				self::$m_Category2Class = $result['m_Category2Class'];
				self::$m_aRootClasses = $result['m_aRootClasses'];
				self::$m_aParentClasses = $result['m_aParentClasses']; 
				self::$m_aChildClasses = $result['m_aChildClasses'];
				self::$m_aClassParams = $result['m_aClassParams'];
				self::$m_aAttribDefs = $result['m_aAttribDefs'];
				self::$m_aAttribOrigins = $result['m_aAttribOrigins'];
				self::$m_aExtKeyFriends = $result['m_aExtKeyFriends'];
				self::$m_aIgnoredAttributes = $result['m_aIgnoredAttributes'];
				self::$m_aFilterDefs = $result['m_aFilterDefs'];
				self::$m_aFilterOrigins = $result['m_aFilterOrigins'];
				self::$m_aListInfos = $result['m_aListInfos'];
				self::$m_aListData = $result['m_aListData'];
				self::$m_aRelationInfos = $result['m_aRelationInfos'];
				self::$m_aStates = $result['m_aStates'];
				self::$m_aStimuli = $result['m_aStimuli'];
				self::$m_aTransitions = $result['m_aTransitions'];
				self::$m_aHighlightScales = $result['m_aHighlightScales'];
			}
			$oKPI->ComputeAndReport('Metamodel APC (fetch + read)');
		}

      if (count(self::$m_aAttribDefs) == 0)
      {
			// The includes have been included, let's browse the existing classes and
			// develop some data based on the proposed model
			$oKPI = new ExecutionKPI();

			self::InitClasses($sTablePrefix);

			$oKPI->ComputeAndReport('Initialization of Data model structures');
			if (self::$m_bUseAPCCache)
			{
				$oKPI = new ExecutionKPI();

				$aCache = array();
				$aCache['m_aExtensionClasses'] = self::$m_aExtensionClasses;
				$aCache['m_Category2Class'] = self::$m_Category2Class;
				$aCache['m_aRootClasses'] = self::$m_aRootClasses; // array of "classname" => "rootclass"
				$aCache['m_aParentClasses'] = self::$m_aParentClasses; // array of ("classname" => array of "parentclass") 
				$aCache['m_aChildClasses'] = self::$m_aChildClasses; // array of ("classname" => array of "childclass")
				$aCache['m_aClassParams'] = self::$m_aClassParams; // array of ("classname" => array of class information)
				$aCache['m_aAttribDefs'] = self::$m_aAttribDefs; // array of ("classname" => array of attributes)
				$aCache['m_aAttribOrigins'] = self::$m_aAttribOrigins; // array of ("classname" => array of ("attcode"=>"sourceclass"))
				$aCache['m_aExtKeyFriends'] = self::$m_aExtKeyFriends; // array of ("classname" => array of ("indirect ext key attcode"=> array of ("relative ext field")))
				$aCache['m_aIgnoredAttributes'] = self::$m_aIgnoredAttributes; //array of ("classname" => array of ("attcode")
				$aCache['m_aFilterDefs'] = self::$m_aFilterDefs; // array of ("classname" => array filterdef)
				$aCache['m_aFilterOrigins'] = self::$m_aFilterOrigins; // array of ("classname" => array of ("attcode"=>"sourceclass"))
				$aCache['m_aListInfos'] = self::$m_aListInfos; // array of ("listcode" => various info on the list, common to every classes)
				$aCache['m_aListData'] = self::$m_aListData; // array of ("classname" => array of "listcode" => list)
				$aCache['m_aRelationInfos'] = self::$m_aRelationInfos; // array of ("relcode" => various info on the list, common to every classes)
				$aCache['m_aStates'] = self::$m_aStates; // array of ("classname" => array of "statecode"=>array('label'=>..., attribute_inherit=> attribute_list=>...))
				$aCache['m_aStimuli'] = self::$m_aStimuli; // array of ("classname" => array of ("stimuluscode"=>array('label'=>...)))
				$aCache['m_aTransitions'] = self::$m_aTransitions; // array of ("classname" => array of ("statcode_from"=>array of ("stimuluscode" => array('target_state'=>..., 'actions'=>array of handlers procs, 'user_restriction'=>TBD)))
				$aCache['m_aHighlightScales'] = self::$m_aHighlightScales; // array of ("classname" => array of higlightcodes)))
				apc_store($sOqlAPCCacheId, $aCache);
				$oKPI->ComputeAndReport('Metamodel APC (store)');
			}
		}

		if (self::$m_bUseAPCCache && $bDictInitializedFromData)
		{
			Dict::InitCache($sAppIdentity);
		}
		
		self::$m_sDBName = $sSource;
		self::$m_sTablePrefix = $sTablePrefix;

		CMDBSource::Init($sServer, $sUser, $sPwd); // do not select the DB (could not exist)
		CMDBSource::SetCharacterSet($sCharacterSet, $sCollation);
		// Later when timezone implementation is correctly done: CMDBSource::SetTimezone($sDBTimezone);
	}

	public static function GetModuleSetting($sModule, $sProperty, $defaultvalue = null)
	{
		return self::$m_oConfig->GetModuleSetting($sModule, $sProperty, $defaultvalue);
	}

	public static function GetModuleParameter($sModule, $sProperty, $defaultvalue = null)
	{
		$value = $defaultvalue;
		if (!array_key_exists($sModule, self::$m_aModulesParameters))
		{
			
		}
		if (!self::$m_aModulesParameters[$sModule] == null)
		{
			$value = self::$m_aModulesParameters[$sModule]->Get($sProperty, $defaultvalue);
		}
		return $value;
	}
	
	public static function GetConfig()
	{
		return self::$m_oConfig;
	}

	public static function GetEnvironmentId()
	{
		return md5(APPROOT).'-'.utils::GetCurrentEnvironment();
	}

	protected static $m_aExtensionClasses = array();

	protected static function IncludeModule($sModuleType, $sToInclude)
	{
		$sFirstChar = substr($sToInclude, 0, 1);
		$sSecondChar = substr($sToInclude, 1, 1);
		if (($sFirstChar != '/') && ($sFirstChar != '\\') && ($sSecondChar != ':'))
		{
			// It is a relative path, prepend APPROOT
			if (substr($sToInclude, 0, 3) == '../')
			{
				// Preserve compatibility with config files written before 1.0.1
				// Replace '../' by '<root>/'
				$sFile = APPROOT.'/'.substr($sToInclude, 3);
			}
			else
			{
				$sFile = APPROOT.'/'.$sToInclude;			
			}
		}
		else
		{
			// Leave as is - should be an absolute path
			$sFile = $sToInclude;
		}
		if (!file_exists($sFile))
		{
			$sConfigFile = self::$m_oConfig->GetLoadedFile();
			if (strlen($sConfigFile) > 0)
			{
				throw new CoreException('Include: wrong file name in configuration file', array('config file' => $sConfigFile, 'section' => $sModuleType, 'filename' => $sFile));
			}
			else
			{
				// The configuration is in memory only
				throw new CoreException('Include: wrong file name in configuration file (in memory)', array('section' => $sModuleType, 'filename' => $sFile));
			}
		}

		// Note: We do not expect the modules to output characters while loading them.
		//       Therefore, and because unexpected characters can corrupt the output,
		//       they must be trashed here.
		//       Additionnaly, pages aiming at delivering data in their output can call WebPage::TrashUnexpectedOutput()
		//       to get rid of chars that could be generated during the execution of the code
		ob_start();
		require_once($sFile);
		$sPreviousContent = ob_get_clean();
		if (self::$m_oConfig->Get('debug_report_spurious_chars'))
		{
			if ($sPreviousContent != '')
			{
				IssueLog::Error("Spurious characters injected by $sModuleType/$sToInclude");
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
		foreach(self::$aQueryCacheGetObjectHits as $sClassSign => $iHits)
		{
			$aRes[] = "$sClassSign: $iHits";
			$iTotalHits += $iHits;
		}
		return $iTotalHits.' ('.implode(', ', $aRes).')';
	}

	public static function MakeSingleRow($sClass, $iKey, $bMustBeFound = true, $bAllowAllData = false, $aModifierProperties = null)
	{
		// Build the query cache signature
		//
		$sQuerySign = $sClass;
		if($bAllowAllData)
		{
			$sQuerySign .= '_all_';
		}
		if (count($aModifierProperties))
		{
			array_multisort($aModifierProperties);
			$sModifierProperties = json_encode($aModifierProperties);
			$sQuerySign .= '_all_'.md5($sModifierProperties);
		}

		if (!array_key_exists($sQuerySign, self::$aQueryCacheGetObject))
		{
			// NOTE: Quick and VERY dirty caching mechanism which relies on
			//       the fact that the string '987654321' will never appear in the
			//       standard query
			//       This could be simplified a little, relying solely on the query cache,
			//       but this would slow down -by how much time?- the application
			$oFilter = new DBObjectSearch($sClass);
			$oFilter->AddCondition('id', 987654321, '=');
			if ($aModifierProperties)
			{
				foreach ($aModifierProperties as $sPluginClass => $aProperties)
				{
					foreach ($aProperties as $sProperty => $value)
					{
						$oFilter->SetModifierProperty($sPluginClass, $sProperty, $value);
					}
				}
			}
			if ($bAllowAllData)
			{
				$oFilter->AllowAllData();
			}
	
			$sSQL = $oFilter->MakeSelectQuery();
			self::$aQueryCacheGetObject[$sQuerySign] = $sSQL;
			self::$aQueryCacheGetObjectHits[$sQuerySign] = 0;
		}
		else
		{
			$sSQL = self::$aQueryCacheGetObject[$sQuerySign];
			self::$aQueryCacheGetObjectHits[$sQuerySign] += 1;
//			echo " -load $sClass/$iKey- ".self::$aQueryCacheGetObjectHits[$sQuerySign]."<br/>\n";
		}
		$sSQL = str_replace(CMDBSource::Quote(987654321), CMDBSource::Quote($iKey), $sSQL);
		$res = CMDBSource::Query($sSQL);
		
		$aRow = CMDBSource::FetchArray($res);
		CMDBSource::FreeResult($res);
		if ($bMustBeFound && empty($aRow))
		{
			throw new CoreException("No result for the single row query: '$sSQL'");
		}
		return $aRow;
	}

	public static function GetObjectByRow($sClass, $aRow, $sClassAlias = '', $aAttToLoad = null, $aExtendedDataSpec = null)
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
		return new $sClass($aRow, $sClassAlias, $aAttToLoad, $aExtendedDataSpec);
	}

	public static function GetObject($sClass, $iKey, $bMustBeFound = true, $bAllowAllData = false, $aModifierProperties = null)
	{
		self::_check_subclass($sClass);	
		$aRow = self::MakeSingleRow($sClass, $iKey, $bMustBeFound, $bAllowAllData, $aModifierProperties);
		if (empty($aRow))
		{
			return null;
		}
		return self::GetObjectByRow($sClass, $aRow);
	}

	public static function GetObjectByName($sClass, $sName, $bMustBeFound = true)
	{
		self::_check_subclass($sClass);	

		$oObjSearch = new DBObjectSearch($sClass);
		$oObjSearch->AddNameCondition($sName);
		$oSet = new DBObjectSet($oObjSearch);
		if ($oSet->Count() != 1)
		{
			if ($bMustBeFound) throw new CoreException('Failed to get an object by its name', array('class'=>$sClass, 'name'=>$sName));
			return null;
		}
		$oObj = $oSet->fetch();
		return $oObj;
	}

	static protected $m_aCacheObjectByColumn = array();

	public static function GetObjectByColumn($sClass, $sAttCode, $value, $bMustBeFoundUnique = true)
	{
		if (!isset(self::$m_aCacheObjectByColumn[$sClass][$sAttCode][$value]))
		{
			self::_check_subclass($sClass);	
	
			$oObjSearch = new DBObjectSearch($sClass);
			$oObjSearch->AddCondition($sAttCode, $value, '=');
			$oSet = new DBObjectSet($oObjSearch);
			if ($oSet->Count() == 1)
			{
				self::$m_aCacheObjectByColumn[$sClass][$sAttCode][$value] = $oSet->fetch();
			}
			else
			{
				if ($bMustBeFoundUnique) throw new CoreException('Failed to get an object by column', array('class'=>$sClass, 'attcode'=>$sAttCode, 'value'=>$value, 'matches' => $oSet->Count()));
				self::$m_aCacheObjectByColumn[$sClass][$sAttCode][$value] = null;
			}
		}

		return self::$m_aCacheObjectByColumn[$sClass][$sAttCode][$value];
	}

	public static function GetObjectFromOQL($sQuery, $aParams = null, $bAllowAllData = false)
	{
		$oFilter = DBObjectSearch::FromOQL($sQuery, $aParams);
		if ($bAllowAllData)
		{
			$oFilter->AllowAllData();
		}
		$oSet = new DBObjectSet($oFilter);
		$oObject = $oSet->Fetch();
		return $oObject;
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
			// Whatever we are looking for, the root class is the key to search for
			$sRootClass = self::GetRootClass($sTargetClass);
			$oSearch = DBObjectSearch::FromOQL('SELECT CMDBChangeOpDelete WHERE objclass = :objclass AND objkey = :objkey', array('objclass' => $sRootClass, 'objkey' => $iKey));
			$oSet = new DBObjectSet($oSearch);
			$oRecord = $oSet->Fetch();
			// An empty fname is obtained with iTop < 2.0
			if (is_null($oRecord) || (strlen(trim($oRecord->Get('fname'))) == 0))
			{
				$sName = Dict::Format('Core:UnknownObjectLabel', $sTargetClass, $iKey);
				$sTitle = Dict::S('Core:UnknownObjectTip');
			}
			else
			{
				$sName = $oRecord->Get('fname');
				$sTitle = Dict::Format('Core:DeletedObjectTip', $oRecord->Get('date'), $oRecord->Get('userinfo'));
			}
			return '<span class="itop-deleted-object" title="'.htmlentities($sTitle, ENT_QUOTES, 'UTF-8').'">'.htmlentities($sName, ENT_QUOTES, 'UTF-8').'</span>';
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

	/**
	 * Deletion of records, bypassing DBObject::DBDelete !!!
	 * It is NOT recommended to use this shortcut
	 * In particular, it will not work	 
	 *  - if the class is not a final class
	 *  - if the class has a hierarchical key (need to rebuild the indexes)
	 *  - if the class overload DBDelete !	 
	 * Todo: protect it against forbidden usages (in such a case, delete objects one by one)
	 */	 	
	public static function BulkDelete(DBObjectSearch $oFilter)
	{
		$sSQL = $oFilter->MakeDeleteQuery();
		if (!self::DBIsReadOnly())
		{
			CMDBSource::Query($sSQL);
		}
	}

	public static function BulkUpdate(DBObjectSearch $oFilter, array $aValues)
	{
		// $aValues is an array of $sAttCode => $value
		$sSQL = $oFilter->MakeUpdateQuery($aValues);
		if (!self::DBIsReadOnly())
		{
			CMDBSource::Query($sSQL);
		}
	}

	/**
	 * Helper to remove selected objects without calling any handler
	 * Surpasses BulkDelete as it can handle abstract classes, but has the other limitation as it bypasses standard objects handlers 
	 * 	 
	 * @param string $oFilter Scope of objects to wipe out
	 * @return The count of deleted objects
	 */	 	
	public static function PurgeData($oFilter)
	{
		$sTargetClass = $oFilter->GetClass();
		$oSet = new DBObjectSet($oFilter);
		$oSet->OptimizeColumnLoad(array($sTargetClass => array('finalclass')));
		$aIdToClass = $oSet->GetColumnAsArray('finalclass', true);

		$aIds = array_keys($aIdToClass);
		if (count($aIds) > 0)
		{
			$aQuotedIds = CMDBSource::Quote($aIds);
			$sIdList = implode(',', $aQuotedIds);
			$aTargetClasses = array_merge(
				self::EnumChildClasses($sTargetClass, ENUM_CHILD_CLASSES_ALL),
				self::EnumParentClasses($sTargetClass, ENUM_PARENT_CLASSES_EXCLUDELEAF)
			);
			foreach ($aTargetClasses as $sSomeClass)
			{
				$sTable = MetaModel::DBGetTable($sSomeClass);
				$sPKField = MetaModel::DBGetKey($sSomeClass);
		
				$sDeleteSQL = "DELETE FROM `$sTable` WHERE `$sPKField` IN ($sIdList)";
				CMDBSource::DeleteFrom($sDeleteSQL);
			}
		}
		return count($aIds);
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

	/**
	 * It is not recommended to use this function: call GetLinkClasses instead
	 * Return classes having at least to external keys (thus too many classes as compared to GetLinkClasses)	 
	 * The only difference with EnumLinkingClasses is the output format
	 */	 	
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
	/**
	 * It is not recommended to use this function: call GetLinkClasses instead
	 * Return classes having at least to external keys (thus too many classes as compared to GetLinkClasses)	 
	 * The only difference with EnumLinksClasses is the output format
	 */	 	
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

	/**
	 * This function has two siblings that will be soon deprecated:
	 * EnumLinkingClasses and EnumLinkClasses
	 * 	 
	 * Using GetLinkClasses is the recommended way to determine if a class is
	 * actually an N-N relation because it is based on the decision made by the
	 * designer the data model
	 */	 	
	public static function GetLinkClasses()
	{
		$aRet = array();
		foreach(self::GetClasses() as $sClass)
		{
			if (isset(self::$m_aClassParams[$sClass]["is_link"]))
			{
				if (self::$m_aClassParams[$sClass]["is_link"])
				{
					$aExtKeys = array();
					foreach (self::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
					{
						if ($oAttDef->IsExternalKey())
						{
							$aExtKeys[$sAttCode] = $oAttDef->GetTargetClass();
						}
					}
					$aRet[$sClass] = $aExtKeys;
				}
			}
		}
		return $aRet;
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
	static public function ApplyParams($sInput, $aParams)
	{
		// Declare magic parameters
		$aParams['APP_URL'] = utils::GetAbsoluteUrlAppRoot();
		$aParams['MODULES_URL'] = utils::GetAbsoluteUrlModulesRoot();

		$aSearches = array();
		$aReplacements = array();
		foreach($aParams as $sSearch => $replace)
		{
			// Some environment parameters are objects, we just need scalars
			if (is_object($replace))
			{
				$iPos = strpos($sSearch, '->object()');
				if ($iPos !== false)
				{
					// Expand the parameters for the object
					$sName = substr($sSearch, 0, $iPos);
					if (preg_match_all('/\\$'.$sName.'->([^\\$]+)\\$/', $sInput, $aMatches))
					{
						foreach($aMatches[1] as $sPlaceholderAttCode)
						{
							try
							{
								$sReplacement = $replace->GetForTemplate($sPlaceholderAttCode);
								if ($sReplacement !== null)
								{
									$aReplacements[] = $sReplacement;
									$aSearches[] = '$'.$sName.'->'.$sPlaceholderAttCode.'$';
								}
							}
							catch(Exception $e)
							{
								// No replacement will occur
							}
						}
					}
				}
				else
				{
					continue; // Ignore this non-scalar value
				}
			}

			$aSearches[] = '$'.$sSearch.'$';
			$aReplacements[] = (string) $replace;
		}
		return str_replace($aSearches, $aReplacements, $sInput);
	}

	/**
	 * Returns an array of classes=>instance implementing the given interface
	 */
	public static function EnumPlugins($sInterface)
	{
		if (array_key_exists($sInterface, self::$m_aExtensionClasses))
		{
			return self::$m_aExtensionClasses[$sInterface];
		}
		else
		{
			return array();
		}
	}

	/**
	 * Returns the instance of the specified plug-ins for the given interface
	 */
	public static function GetPlugins($sInterface, $sClassName)
	{
		$oInstance = null;
		if (array_key_exists($sInterface, self::$m_aExtensionClasses))
		{
			if (array_key_exists($sClassName, self::$m_aExtensionClasses[$sInterface]))
			{
				return self::$m_aExtensionClasses[$sInterface][$sClassName];
			}
		}
		return $oInstance;
	}

	public static function GetCacheEntries($sEnvironment = null)
	{
		if (!function_exists('apc_cache_info')) return array();
		if (is_null($sEnvironment))
		{
			$sEnvironment = MetaModel::GetEnvironmentId();
		}
		$aEntries = array();
		if (extension_loaded('apcu'))
		{
			// Beware: APCu behaves slightly differently from APC !!
			$aCacheUserData = @apc_cache_info();
		}
		else
		{
			$aCacheUserData = @apc_cache_info('user');
		}
		if (is_array($aCacheUserData) && isset($aCacheUserData['cache_list']))
		{ 
			$sPrefix = 'itop-'.$sEnvironment.'-';
	
			foreach($aCacheUserData['cache_list'] as $i => $aEntry)
			{
				$sEntryKey = array_key_exists('info', $aEntry) ? $aEntry['info'] : $aEntry['key'];
				if (strpos($sEntryKey, $sPrefix) === 0)
				{
					$sCleanKey = substr($sEntryKey, strlen($sPrefix));
					$aEntries[$sCleanKey] = $aEntry;
					$aEntries[$sCleanKey]['info'] = $sEntryKey;
				}
			}
		}
		return $aEntries;
	}

	public static function ResetCache($sEnvironmentId = null)
	{
		if (!function_exists('apc_delete')) return;
		if (is_null($sEnvironmentId))
		{
			$sEnvironmentId = MetaModel::GetEnvironmentId();
		}

		$sAppIdentity = 'itop-'.$sEnvironmentId;
		Dict::ResetCache($sAppIdentity);

		foreach(self::GetCacheEntries($sEnvironmentId) as $sKey => $aAPCInfo)
		{
			$sAPCKey = $aAPCInfo['info'];
			apc_delete($sAPCKey);
		}
	}
	
	/**
	 * Given a field spec, get the most relevant (unique) representation
	 * Examples for a user request:
	 * - friendlyname => ref
	 * - org_name => org_id->name
	 * - org_id_friendlyname => org_id=>name
	 * - caller_name => caller_id->name
	 * - caller_id_friendlyname => caller_id->friendlyname
	 * @param string $sClass
	 * @param string $sField
	 * @return string
	 */
	public static function NormalizeFieldSpec($sClass, $sField)
	{
		$sRet = $sField;
	
		if ($sField == 'id')
		{
			$sRet = 'id';
		}
		elseif ($sField == 'friendlyname')
		{
			$sFriendlyNameAttCode = static::GetFriendlyNameAttributeCode($sClass);
			if (!is_null($sFriendlyNameAttCode))
			{
				// The friendly name is made of a single attribute
				$sRet = $sFriendlyNameAttCode;
			}
		}
		else
		{
			$oAttDef = static::GetAttributeDef($sClass, $sField);
			if ($oAttDef instanceof AttributeFriendlyName)
			{
				$oKeyAttDef = MetaModel::GetAttributeDef($sClass, $oAttDef->GetKeyAttCode());
				$sRemoteClass = $oKeyAttDef->GetTargetClass();
				$sFriendlyNameAttCode = static::GetFriendlyNameAttributeCode($sRemoteClass);
				if (is_null($sFriendlyNameAttCode))
				{
					// The friendly name is made of several attributes
					$sRet = $oAttDef->GetKeyAttCode().'->friendlyname';
				}
				else
				{
					// The friendly name is made of a single attribute
					$sRet = $oAttDef->GetKeyAttCode().'->'.$sFriendlyNameAttCode;
				}
			}
			elseif ($oAttDef->IsExternalField())
			{
				$sRet = $oAttDef->GetKeyAttCode().'->'.$oAttDef->GetExtAttCode();
			}
		}
		return $sRet;
	}
} // class MetaModel


// Standard attribute lists
MetaModel::RegisterZList("noneditable", array("description"=>"non editable fields", "type"=>"attributes"));

MetaModel::RegisterZList("details", array("description"=>"All attributes to be displayed for the 'details' of an object", "type"=>"attributes"));
MetaModel::RegisterZList("list", array("description"=>"All attributes to be displayed for a list of objects", "type"=>"attributes"));
MetaModel::RegisterZList("preview", array("description"=>"All attributes visible in preview mode", "type"=>"attributes"));

MetaModel::RegisterZList("standard_search", array("description"=>"List of criteria for the standard search", "type"=>"filters"));
MetaModel::RegisterZList("advanced_search", array("description"=>"List of criteria for the advanced search", "type"=>"filters"));
