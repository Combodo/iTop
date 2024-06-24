<?php
// Copyright (c) 2010-2024 Combodo SAS
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

use Combodo\iTop\Application\EventRegister\ApplicationEvents;
use Combodo\iTop\Core\MetaModel\FriendlyNameType;
use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;

require_once APPROOT.'core/modulehandler.class.inc.php';
require_once APPROOT.'core/querymodifier.class.inc.php';
require_once APPROOT.'core/metamodelmodifier.inc.php';
require_once APPROOT.'core/computing.inc.php';
require_once APPROOT.'core/relationgraph.class.inc.php';
require_once APPROOT.'core/apc-compat.php';
require_once APPROOT.'core/expressioncache.class.inc.php';


/**
 * We need to have all iLoginFSMExtension/iLoginUIExtension impl loaded ! Cannot use autoloader...
 */
require_once APPROOT.'application/loginform.class.inc.php';
require_once APPROOT.'application/loginbasic.class.inc.php';
require_once APPROOT.'application/logindefault.class.inc.php';
require_once APPROOT.'application/loginexternal.class.inc.php';
require_once APPROOT.'application/loginurl.class.inc.php';

/**
 * Metamodel
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * @package     iTopORM
 */
define('ENUM_PARENT_CLASSES_EXCLUDELEAF', 1);
/**
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
 * Used to be myisam, the switch was made with r798
 *
 * @package     iTopORM
 */
define('MYSQL_ENGINE', 'innodb');


/**
 * The objects definitions as well as their mapping to the database
 *
 * @api
 * @package     iTopORM
 */
abstract class MetaModel
{
	///////////////////////////////////////////////////////////////////////////
	//
	// STATIC Members
	//
	///////////////////////////////////////////////////////////////////////////

	/** @var bool */
	private static $m_bTraceSourceFiles = false;
	/** @var array */
	private static $m_aClassToFile = array();
	/** @var string */
	protected static $m_sEnvironment = 'production';

	/**
	 * Objects currently created/updated.
	 *
	 * if an object is already being updated, then this method will return this object instead of recreating a new one.
	 * At this point the method DBUpdate of a new object with the same class and id won't do anything due to reentrance protection,
	 * so to ensure that the potential modifications are correctly saved, the object currently being updated is returned.
	 * DBUpdate() method then will take care that all the modifications will be saved.
	 *
	 * [class][key] -> object
	 */
	protected static array $m_aReentranceProtection = [];

	/**
	 * MetaModel constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * @return array
	 */
	public static function GetClassFiles()
	{
		return self::$m_aClassToFile;
	}

	//

	/**
	 * Purpose: workaround the following limitation = PHP5 does not allow to know the class (derived
	 * from the current one) from which a static function is called (__CLASS__ and self are
	 * interpreted during parsing)
	 *
	 * @param string $sExpectedFunctionName
	 * @param bool $bRecordSourceFile
	 *
	 * @return string
	 */
	private static function GetCallersPHPClass($sExpectedFunctionName = null, $bRecordSourceFile = false)
	{
		$aBacktrace = debug_backtrace();
		// $aBacktrace[0] is where we are
		// $aBacktrace[1] is the caller of GetCallersPHPClass
		// $aBacktrace[1] is the info we want
		if (!empty($sExpectedFunctionName))
		{
			assert($aBacktrace[2]['function'] == $sExpectedFunctionName);
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

	/**
	 * @param string $sClass
	 *
	 * @throws \CoreException
	 */
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

	/** @var Config m_oConfig */
	private static $m_oConfig = null;
	/** @var array */
	protected static $m_aModulesParameters = array();

	/** @var bool */
	private static $m_bSkipCheckToWrite = false;
	/** @var bool */
	private static $m_bSkipCheckExtKeys = false;

	/** @var bool */
	private static $m_bUseAPCCache = false;

	/** @var bool */
	private static $m_bLogIssue = false;
	/** @var bool */
	private static $m_bLogNotification = false;
	/** @var bool */
	private static $m_bLogWebService = false;

	/**
	 * @return bool the current flag value
	 */
	public static function SkipCheckToWrite()
	{
		return self::$m_bSkipCheckToWrite;
	}

	/**
	 * @return bool the current flag value
	 */
	public static function SkipCheckExtKeys()
	{
		return self::$m_bSkipCheckExtKeys;
	}

	/**
	 * @return bool the current flag value
	 */
	public static function IsLogEnabledIssue()
	{
		return self::$m_bLogIssue;
	}

	/**
	 * @return bool the current flag value
	 */
	public static function IsLogEnabledNotification()
	{
		return self::$m_bLogNotification;
	}

	/**
	 * @return bool the current flag value
	 */
	public static function IsLogEnabledWebService()
	{
		return self::$m_bLogWebService;
	}

	/** @var string */
	private static $m_sDBName = "";
	/**
	 * table prefix for the current application instance (allow several applications on the same DB)
	 *
	 * @var string
	 */
	private static $m_sTablePrefix = "";
	/** @var array */
	private static $m_Category2Class = array();
	/**
	 * array of "classname" => "rootclass"
	 *
	 * @var array
	 */
	private static $m_aRootClasses = array();
	/**
	 * array of ("classname" => array of "parentclass")
	 *
	 * @var array
	 */
	private static $m_aParentClasses = array();
	/**
	 * array of ("classname" => array of "childclass")
	 *
	 * @var array
	 */
	private static $m_aChildClasses = array();

	/**
	 * array of ("classname" => array of class information)
	 *
	 * @var array
	 */
	private static $m_aClassParams = array();
	/**
	 * array of ("classname" => array of highlightscale information)
	 *
	 * @var array
	 */
	private static $m_aHighlightScales = array();

	/**
	 * @param string $sRefClass
	 *
	 * @return string
	 */
	public static function GetParentPersistentClass($sRefClass)
	{
		$sClass = get_parent_class($sRefClass);
		if (!$sClass) {
			return '';
		}

		if ($sClass == 'DBObject') {
			return '';
		} // Warning: __CLASS__ is lower case in my version of PHP

		// Note: the UI/business model may implement pure PHP classes (intermediate layers)
		if (array_key_exists($sClass, self::$m_aClassParams)) {
			return $sClass;
		}

		return self::GetParentPersistentClass($sClass);
	}

	/**
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	final public static function GetName($sClass)
	{
		self::_check_subclass($sClass);

		return call_user_func([$sClass, 'GetClassName'], $sClass);
	}

	/**
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	final public static function GetName_Obsolete($sClass)
	{
		// Written for compatibility with a data model written prior to version 0.9.1
		self::_check_subclass($sClass);
		if (array_key_exists('name', self::$m_aClassParams[$sClass])) {
			return self::$m_aClassParams[$sClass]['name'];
		} else {
			return self::GetName($sClass);
		}
	}

	/**
	 * @param string $sClassLabel
	 * @param bool $bCaseSensitive
	 *
	 * @return null
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	final public static function GetClassFromLabel($sClassLabel, $bCaseSensitive = true)
	{
		foreach (self::GetClasses() as $sClass) {
			if ($bCaseSensitive) {
				if (self::GetName($sClass) == $sClassLabel) {
					return $sClass;
				}
			} else {
				if (strcasecmp(self::GetName($sClass), $sClassLabel) == 0) {
					return $sClass;
				}
			}
		}

		return null;
	}

	/**
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 */
	final public static function GetCategory($sClass)
	{
		self::_check_subclass($sClass);

		return self::$m_aClassParams[$sClass]["category"];
	}

	/**
	 * @param string $sClass
	 * @param string $sCategory
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	final public static function HasCategory($sClass, $sCategory)
	{
		self::_check_subclass($sClass);

		return (strpos(self::$m_aClassParams[$sClass]["category"], $sCategory) !== false);
	}

	/**
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	final public static function GetClassDescription($sClass)
	{
		self::_check_subclass($sClass);

		return call_user_func([$sClass, 'GetClassDescription'], $sClass);
	}

	/**
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	final public static function GetClassDescription_Obsolete($sClass)
	{
		// Written for compatibility with a data model written prior to version 0.9.1
		self::_check_subclass($sClass);
		if (array_key_exists('description', self::$m_aClassParams[$sClass])) {
			return self::$m_aClassParams[$sClass]['description'];
		} else {
			return self::GetClassDescription($sClass);
		}
	}

	/**
	 * @param string $sClass
	 * @param bool $bImgTag Whether to surround the icon URL with an HTML IMG tag or not
	 * @param string $sMoreStyles Additional inline CSS style to add to the IMG tag. Only used if $bImgTag is set to true
	 *
	 * @return string Absolute URL the class icon
	 * @throws \CoreException
	 */
	final public static function GetClassIcon($sClass, $bImgTag = true, $sMoreStyles = '')
	{
		self::_check_subclass($sClass);

		$sIcon = '';
		if (array_key_exists('style', self::$m_aClassParams[$sClass])) {
			/** @var ormStyle $oStyle */
			$oStyle = self::$m_aClassParams[$sClass]['style'];
			$sIcon = $oStyle->GetIconAsAbsUrl();
		}
		if (utils::IsNullOrEmptyString($sIcon)) {
			$sParentClass = self::GetParentPersistentClass($sClass);
			if (strlen($sParentClass) > 0) {
				return self::GetClassIcon($sParentClass, $bImgTag, $sMoreStyles);
			}
		}
		$sIcon = str_replace('/modules/', '/env-'.self::$m_sEnvironment.'/', $sIcon ?? ''); // Support of pre-2.0 modules
		if ($bImgTag && ($sIcon != '')) {
			$sIcon = "<img src=\"$sIcon\" style=\"vertical-align:middle;$sMoreStyles\"/>";
		}

		return $sIcon;
	}

	/**
	 * @param string $sClass
	 *
	 * @return ormStyle|null
	 * @throws \CoreException
	 *
	 * @since 3.0
	 */
	final public static function GetClassStyle($sClass)
	{
		self::_check_subclass($sClass);

		if (array_key_exists('style', self::$m_aClassParams[$sClass])) {
			$oStyle = self::$m_aClassParams[$sClass]['style'];
		} else {
			// Create empty style
			$oStyle = new ormStyle("ibo-class-style--$sClass", "ibo-class-style-alt--$sClass");
		}

		if (utils::IsNotNullOrEmptyString($oStyle->GetMainColor()) && utils::IsNotNullOrEmptyString($oStyle->GetComplementaryColor()) && utils::IsNotNullOrEmptyString($oStyle->GetIconAsRelPath())) {
			// all the parameters are set, no need to search in the parent classes
			return $oStyle;
		}

		// Search missing parameters in the parent classes
		$sParentClass = self::GetParentPersistentClass($sClass);
		while (strlen($sParentClass) > 0) {
			$oParentStyle = self::GetClassStyle($sParentClass);
			if (!is_null($oParentStyle)) {
				if (utils::IsNullOrEmptyString($oStyle->GetMainColor())) {
					$oStyle->SetMainColor($oParentStyle->GetMainColor());
					$oStyle->SetStyleClass($oParentStyle->GetStyleClass());
				}
				if (utils::IsNullOrEmptyString($oStyle->GetComplementaryColor())) {
					$oStyle->SetComplementaryColor($oParentStyle->GetComplementaryColor());
					$oStyle->SetAltStyleClass($oParentStyle->GetAltStyleClass());
				}
				if (utils::IsNullOrEmptyString($oStyle->GetIconAsRelPath())) {
					$oStyle->SetIcon($oParentStyle->GetIconAsRelPath());
				}
				if (utils::IsNotNullOrEmptyString($oStyle->GetMainColor()) && utils::IsNotNullOrEmptyString($oStyle->GetComplementaryColor()) && utils::IsNotNullOrEmptyString($oStyle->GetIconAsRelPath())) {
					// all the parameters are set, no need to search in the parent classes
					return $oStyle;
				}
			}
			$sParentClass = self::GetParentPersistentClass($sParentClass);
		}

		if (utils::IsNullOrEmptyString($oStyle->GetMainColor()) && utils::IsNullOrEmptyString($oStyle->GetComplementaryColor()) && utils::IsNullOrEmptyString($oStyle->GetIconAsRelPath())) {
			return null;
		}

		return $oStyle;
	}

	/**
	 * @param string $sClass
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	final public static function IsAutoIncrementKey($sClass)
	{
		self::_check_subclass($sClass);

		return (self::$m_aClassParams[$sClass]["key_type"] == "autoincrement");
	}

	/**
	 * @param string $sClass
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	final public static function IsArchivable($sClass)
	{
		self::_check_subclass($sClass);

		return self::$m_aClassParams[$sClass]["archive"];
	}

	/**
	 * @param string $sClass
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	final public static function IsObsoletable($sClass)
	{
		self::_check_subclass($sClass);

		return (!is_null(self::$m_aClassParams[$sClass]['obsolescence_expression']));
	}

	/**
	 * @param string $sClass
	 *
	 * @return \Expression
	 * @throws \CoreException
	 */
	final public static function GetObsolescenceExpression($sClass)
	{
		if (self::IsObsoletable($sClass)) {
			self::_check_subclass($sClass);
			$sOql = self::$m_aClassParams[$sClass]['obsolescence_expression'];
			$oRet = Expression::FromOQL("COALESCE($sOql, 0)");
		} else {
			$oRet = Expression::FromOQL("0");
		}

		return $oRet;
	}

	/**
	 * @param string $sClass
	 * @param bool $bClassDefinitionOnly if true then will only return properties defined in the specified class on not the properties
	 *                      from its parent classes
	 *
	 * @return array rule id as key, rule properties as value
	 * @throws \CoreException
	 * @since 2.6.0 N°659 uniqueness constraint
	 * @see #SetUniquenessRuleRootClass that fixes a specific 'root_class' property to know which class is root per rule
	 */
	final public static function GetUniquenessRules($sClass, $bClassDefinitionOnly = false)
	{
		if (!isset(self::$m_aClassParams[$sClass]))
		{
			return array();
		}

		$aCurrentUniquenessRules = array();

		if (array_key_exists('uniqueness_rules', self::$m_aClassParams[$sClass]))
		{
			$aCurrentUniquenessRules = self::$m_aClassParams[$sClass]['uniqueness_rules'];
		}

		if ($bClassDefinitionOnly)
		{
			return $aCurrentUniquenessRules;
		}

		$sParentClass = self::GetParentClass($sClass);
		if ($sParentClass)
		{
			$aParentUniquenessRules = self::GetUniquenessRules($sParentClass);
			foreach ($aParentUniquenessRules as $sUniquenessRuleId => $aParentUniquenessRuleProperties)
			{
				$bCopyDisabledKey = true;
				$bCurrentDisabledValue = null;

				if (array_key_exists($sUniquenessRuleId, $aCurrentUniquenessRules))
				{
					if (self::IsUniquenessRuleContainingOnlyDisabledKey($aCurrentUniquenessRules[$sUniquenessRuleId]))
					{
						$bCopyDisabledKey = false;
					}
					else
					{
						continue;
					}
				}

				$aMergedUniquenessProperties = $aParentUniquenessRuleProperties;
				if (!$bCopyDisabledKey)
				{
					$aMergedUniquenessProperties['disabled'] = $aCurrentUniquenessRules[$sUniquenessRuleId]['disabled'];
				}
				$aCurrentUniquenessRules[$sUniquenessRuleId] = $aMergedUniquenessProperties;
			}
		}

		return $aCurrentUniquenessRules;
	}

	/**
	 * @param string $sRootClass
	 * @param string $sRuleId
	 *
	 * @throws \CoreException
	 * @since 2.6.1 N°1968 (sous les pavés, la plage) initialize in 'root_class' property the class that has the first
	 *         definition of the rule in the hierarchy
	 */
	private static function SetUniquenessRuleRootClass($sRootClass, $sRuleId)
	{
		foreach (self::EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_ALL) as $sClass)
		{
			self::$m_aClassParams[$sClass]['uniqueness_rules'][$sRuleId]['root_class'] = $sClass;
		}
	}

	/**
	 * @param string $sRuleId
	 * @param string $sLeafClassName
	 *
	 * @return string name of the class, null if not present
	 */
	final public static function GetRootClassForUniquenessRule($sRuleId, $sLeafClassName)
	{
		$sFirstClassWithRuleId = null;
		if (isset(self::$m_aClassParams[$sLeafClassName]['uniqueness_rules'][$sRuleId]))
		{
			$sFirstClassWithRuleId = $sLeafClassName;
		}

		$sParentClass = self::GetParentClass($sLeafClassName);
		if ($sParentClass)
		{
			$sParentClassWithRuleId = self::GetRootClassForUniquenessRule($sRuleId, $sParentClass);
			if (!is_null($sParentClassWithRuleId))
			{
				$sFirstClassWithRuleId = $sParentClassWithRuleId;
			}
		}

		return $sFirstClassWithRuleId;
	}

	/**
	 * @param string $sRootClass
	 * @param string $sRuleId
	 *
	 * @return string[] child classes with the rule disabled, and that are concrete classes
	 *
	 * @throws \CoreException
	 * @since 2.6.1 N°1968 (soyez réalistes, demandez l'impossible)
	 */
	final public static function GetChildClassesWithDisabledUniquenessRule($sRootClass, $sRuleId)
	{
		$aClassesWithDisabledRule = array();
		foreach (self::EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_EXCLUDETOP) as $sChildClass)
		{
			if (array_key_exists($sChildClass, $aClassesWithDisabledRule))
			{
				continue;
			}
			if (!array_key_exists('uniqueness_rules', self::$m_aClassParams[$sChildClass]))
			{
				continue;
			}
			if (!array_key_exists($sRuleId, self::$m_aClassParams[$sChildClass]['uniqueness_rules']))
			{
				continue;
			}

			if (self::$m_aClassParams[$sChildClass]['uniqueness_rules'][$sRuleId]['disabled'] === true)
			{
				$aDisabledClassChildren = self::EnumChildClasses($sChildClass, ENUM_CHILD_CLASSES_ALL);
				foreach ($aDisabledClassChildren as $sDisabledClassChild)
				{
					if (!self::IsAbstract($sDisabledClassChild))
					{
						$aClassesWithDisabledRule[] = $sDisabledClassChild;
					}
				}
			}
		}

		return $aClassesWithDisabledRule;
	}

	/**
	 * @param array $aRuleProperties
	 *
	 * @return bool
	 * @since 2.6.0 N°659 uniqueness constraint
	 */
	private static function IsUniquenessRuleContainingOnlyDisabledKey($aRuleProperties)
	{
		$aNonNullRuleProperties = array_filter($aRuleProperties, function ($v) {
			return (!is_null($v));
		});

		return ((count($aNonNullRuleProperties) == 1) && (array_key_exists('disabled', $aNonNullRuleProperties)));
	}


	/**
	 * @param string $sClass
	 * @param string $sType {@see \Combodo\iTop\Core\MetaModel\FriendlyNameType}
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 *
	 * @since 3.0.0 N°580 New $sType parameter
	 */
	final public static function GetNameSpec($sClass, $sType = FriendlyNameType::SHORT)
	{
		self::_check_subclass($sClass);

		switch ($sType) {
			case FriendlyNameType::COMPLEMENTARY:
				if (!isset(self::$m_aClassParams[$sClass]["complementary_name_attcode"])) {
					return [$sClass, []];
				}
				$nameRawSpec = self::$m_aClassParams[$sClass]["complementary_name_attcode"];
				$sDictName = 'ComplementaryName';
				break;
			case FriendlyNameType::LONG:
				$nameRawSpec = self::$m_aClassParams[$sClass]["name_attcode"];
				if (!isset(self::$m_aClassParams[$sClass]["complementary_name_attcode"])) {
					return self::GetNameSpec($sClass, FriendlyNameType::SHORT);
				}
				$complementaryNameRawSpec = self::$m_aClassParams[$sClass]["complementary_name_attcode"];
				if (is_array($nameRawSpec)) {
					if (is_array($complementaryNameRawSpec)) {
						$nameRawSpec = merge($nameRawSpec, $complementaryNameRawSpec);
					} elseif (!empty($nameRawSpec)) {
						$nameRawSpec = merge($nameRawSpec, [$complementaryNameRawSpec]);
					}
				} elseif (empty($nameRawSpec)) {
					$nameRawSpec = $complementaryNameRawSpec;
				} else {
					if (is_array($complementaryNameRawSpec)) {
						$nameRawSpec = merge([$nameRawSpec], $complementaryNameRawSpec);
					} elseif (!empty($nameRawSpec)) {
						$nameRawSpec = [$nameRawSpec, $complementaryNameRawSpec];
					}
				}
				$sDictName = 'LongName';
				break;
			default:
				$nameRawSpec = self::$m_aClassParams[$sClass]["name_attcode"];
				$sDictName = 'Name';
		}

		if (is_array($nameRawSpec)) {
			$sFormat = Dict::S("Class:$sClass/$sDictName", '');
			if (strlen($sFormat) == 0) {
				// Default to "%1$s %2$s..."
				for ($i = 1; $i <= count($nameRawSpec); $i++) {
					if (empty($sFormat)) {
						$sFormat .= '%'.$i.'$s';
					} else {
						$sFormat .= ' %'.$i.'$s';
					}
				}
			}

			return [$sFormat, $nameRawSpec];
		} elseif (empty($nameRawSpec)) {
			return [$sClass, []];
		} else {
			// string -> attcode
			return ['%1$s', [$nameRawSpec]];
		}
	}

	/**
	 *
	 * @param string $sClass
	 * @param bool $bWithAttributeDefinition
	 * @param string $sType {@see \Combodo\iTop\Core\MetaModel\FriendlyNameType}
	 *
	 * @return array of attribute codes used by friendlyname
	 * @throws \CoreException
	 * @since 3.0.0
	 */
	final public static function GetNameAttributes(string $sClass, $bWithAttributeDefinition = false, $sType = FriendlyNameType::SHORT): array
	{
		self::_check_subclass($sClass);
		$aNameAttCodes = [];
		if ($sType == FriendlyNameType::SHORT || FriendlyNameType::LONG) {
			$rawNameAttCodes = self::$m_aClassParams[$sClass]["name_attcode"];
			if (!is_array($rawNameAttCodes)) {
				if (self::IsValidAttCode($sClass, $rawNameAttCodes)) {
					$aNameAttCodes[] = $rawNameAttCodes;
				}
			} else {
				$aNameAttCodes = $rawNameAttCodes;
			}
		}
		if ($sType == FriendlyNameType::COMPLEMENTARY || FriendlyNameType::LONG) {
			$rawNameAttCodes = self::$m_aClassParams[$sClass]["complementary_name_attcode"];
			if (!isEmpty($rawNameAttCodes)) {
				if (!is_array($rawNameAttCodes)) {
					if (self::IsValidAttCode($sClass, $rawNameAttCodes)) {
						$aNameAttCodes[] = array_merge($aNameAttCodes, [$rawNameAttCodes]);
					}
				} else {
					$aNameAttCodes = array_merge($rawNameAttCodes, $rawNameAttCodes);
				}
			}
		}
		if ($bWithAttributeDefinition) {
			$aResults = [];
			foreach ($aNameAttCodes as $sAttCode) {
				$aResults[$sAttCode] = self::GetAttributeDef($sClass, $sAttCode);
			}

			return $aResults;
		}

		return $aNameAttCodes;
	}

	/**
	 * @param string $sClass
	 * @param false $bWithAttributeDefinition
	 *
	 * @return array of attributes to always reload in tables
	 * @throws \CoreException
	 * @since 3.0.0
	 */
	final public static function GetAttributesToAlwaysLoadInTables(string $sClass, $bWithAttributeDefinition = false): array
	{
		$aResults = [];
		foreach (self::GetAttributesList($sClass) as $sAttCode) {
			$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
			if ($oAttDef->AlwaysLoadInTables()) {
				if ($bWithAttributeDefinition) {
					$aResults[$sAttCode] = $oAttDef;
				} else {
					$aResults[] = $sAttCode;
				}
			}
		}

		return $aResults;
	}

	/**
	 * Get the friendly name expression for a given class
	 *
	 * @param string $sClass
	 * @param string $sType {@see \Combodo\iTop\Core\MetaModel\FriendlyNameType}
	 *
	 * @return Expression
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 *
	 * @since 3.0.0 N°580 New $sType parameter
	 */
	final public static function GetNameExpression($sClass, $sType = FriendlyNameType::SHORT)
	{
		$aNameSpec = self::GetNameSpec($sClass, $sType);
		$sFormat = $aNameSpec[0];
		$aAttributes = $aNameSpec[1];

		$aPieces = preg_split('/%([0-9])\\$s/', $sFormat, -1, PREG_SPLIT_DELIM_CAPTURE);
		$aExpressions = array();
		foreach ($aPieces as $i => $sPiece) {
			if ($i & 1) {
				// $i is ODD - sPiece is a delimiter
				//
				$iReplacement = (int)$sPiece - 1;

				if (isset($aAttributes[$iReplacement])) {
					$sAttCode = $aAttributes[$iReplacement];
					$aExpressions[] = new FieldExpression($sAttCode);
				}
			} else
			{
				// $i is EVEN - sPiece is a literal
				//
				if (strlen($sPiece) > 0)
				{
					$aExpressions[] = new ScalarExpression($sPiece);
				}
			}
		}

		return new CharConcatExpression($aExpressions);
	}

	/**
	 * @param string $sClass
	 * @param string $sType {@see \Combodo\iTop\Core\MetaModel\FriendlyNameType}
	 *
	 * @return string The friendly name IIF it is equivalent to a single attribute
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 *
	 * @since 3.0.0 N°580 New $sType parameter
	 */
	final public static function GetFriendlyNameAttributeCode($sClass, $sType = FriendlyNameType::SHORT)
	{
		$aNameSpec = self::GetNameSpec($sClass, $sType);
		$sFormat = trim($aNameSpec[0]);
		$aAttributes = $aNameSpec[1];
		if (($sFormat != '') && ($sFormat != '%1$s')) {
			return null;
		}
		if (count($aAttributes) > 1) {
			return null;
		}

		return reset($aAttributes);
	}

	/**
	 * Returns the list of attributes composing the friendlyname
	 *
	 * @param string $sClass
	 * @param string $sType {@see \Combodo\iTop\Core\MetaModel\FriendlyNameType}
	 *
	 * @return array
	 *
	 * @since 3.0.0 N°580 New $sType parameter
	 */
	final public static function GetFriendlyNameAttributeCodeList($sClass, $sType = FriendlyNameType::SHORT)
	{
		$aNameSpec = self::GetNameSpec($sClass, $sType);
		$aAttributes = $aNameSpec[1];

		return $aAttributes;
	}

	/**
	 * Return true if the $sClass has a state attribute defined.
	 *
	 * Note that having a state attribute does NOT mean having a lifecycle!
	 * - A Person with active/inactive state won't have transitions and therefore no lifecycle
	 * - A UserRequest will have transitions between its states and so a lifecycle
	 *
	 * @see self::HasLifecycle($sClass)
	 * @param string $sClass Datamodel class to check
	 *
	 * @return bool
	 * @throws \CoreException
	 * @since 3.0.0
	 */
	final public static function HasStateAttributeCode(string $sClass)
	{
		return !empty(self::GetStateAttributeCode($sClass));
	}

	/**
	 * Return the code of the attribute carrying the state of the instance of the class
	 *
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 */
	final public static function GetStateAttributeCode(string $sClass)
	{
		self::_check_subclass($sClass);

		return self::$m_aClassParams[$sClass]["state_attcode"];
	}

	/**
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \Exception
	 */
	final public static function GetDefaultState(string $sClass)
	{
		$sDefaultState = '';
		$sStateAttrCode = self::GetStateAttributeCode($sClass);
		if (!empty($sStateAttrCode)) {
			$oStateAttrDef = self::GetAttributeDef($sClass, $sStateAttrCode);
			$sDefaultState = $oStateAttrDef->GetDefaultValue();
		}

		return $sDefaultState;
	}

	/**
	 * Return true if the $sClass has an image attribute defined
	 *
	 * @param string $sClass
	 *
	 * @return bool
	 * @throws \CoreException
	 * @since 3.0.0
	 */
	final public static function HasImageAttributeCode(string $sClass)
	{
		return !empty(self::GetImageAttributeCode($sClass));
	}

	/**
	 * Return the code of the attribute carrying the image representing an instance of the class
	 *
	 * @param string $sClass Datamodel class to get the image attribute code for
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @since 3.0.0
	 */
	final public static function GetImageAttributeCode(string $sClass)
	{
		self::_check_subclass($sClass);

		// image_attcode isn't a mandatory class parameter, so it might not be in the $m_aClassParam array
		return isset(self::$m_aClassParams[$sClass]["image_attcode"]) ? self::$m_aClassParams[$sClass]["image_attcode"] : '';
	}

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 */
	final public static function GetReconcKeys($sClass)
	{
		self::_check_subclass($sClass);

		return self::$m_aClassParams[$sClass]["reconc_keys"];
	}

	/**
	 * @param string $sClass
	 * @param bool $bOnlyDeclared
	 *
	 * @return array
	 * @throws \CoreException
	 */
	final public static function GetOrderByDefault($sClass, $bOnlyDeclared = false)
	{
		self::_check_subclass($sClass);
		$aOrderBy = array_key_exists("order_by_default",
			self::$m_aClassParams[$sClass]) ? self::$m_aClassParams[$sClass]["order_by_default"] : array();
		if ($bOnlyDeclared) {
			// Used to reverse engineer the declaration of the data model
			return $aOrderBy;
		} else {
			if (count($aOrderBy) == 0) {
				$aOrderBy['friendlyname'] = true;
			}

			return $aOrderBy;
		}
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return mixed
	 * @throws \CoreException
	 */
	final public static function GetAttributeOrigin($sClass, $sAttCode)
	{
		self::_check_subclass($sClass);

		return self::$m_aAttribOrigins[$sClass][$sAttCode];
	}

	/**     *
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return mixed
	 * @throws \CoreException
	 */
	final public static function GetFilterCodeOrigin($sClass, $sAttCode)
	{
		if ($sAttCode == 'id') {
			return MetaModel::GetRootClass($sClass);
		}

		return MetaModel::GetAttributeOrigin($sClass, self::$m_aFilterAttribList[$sClass][$sAttCode]);
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \Exception
	 */
	final public static function GetPrerequisiteAttributes($sClass, $sAttCode)
	{
		self::_check_subclass($sClass);
		$oAtt = self::GetAttributeDef($sClass, $sAttCode);

		return $oAtt->GetPrerequisiteAttributes($sClass);
	}

	/**
	 * Find all attributes that depend on the specified one (reverse of GetPrerequisiteAttributes)
	 *
	 * @param string $sClass Name of the class
	 * @param string $sAttCode Code of the attributes
	 *
	 * @return array List of attribute codes that depend on the given attribute, empty array if none.
	 * @throws \CoreException
	 * @throws \Exception
	 */
	final public static function GetDependentAttributes($sClass, $sAttCode)
	{
		$aResults = array();
		self::_check_subclass($sClass);
		foreach (self::ListAttributeDefs($sClass) as $sDependentAttCode => $void) {
			$aPrerequisites = self::GetPrerequisiteAttributes($sClass, $sDependentAttCode);
			if (in_array($sAttCode, $aPrerequisites)) {
				$aResults[] = $sDependentAttCode;
			}
		}

		return $aResults;
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return string
	 * @throws \CoreException
	 */
	final public static function DBGetTable($sClass, $sAttCode = null)
	{
		self::_check_subclass($sClass);
		if (empty($sAttCode) || ($sAttCode == "id")) {
			$sTableRaw = self::$m_aClassParams[$sClass]["db_table"];
			if (empty($sTableRaw)) {
				// return an empty string whenever the table is undefined, meaning that there is no table associated to this 'abstract' class
				return '';
			} else {
				// If the format changes here, do not forget to update the setup index page (detection of installed modules)
				return self::$m_sTablePrefix.$sTableRaw;
			}
		}

		// This attribute has been inherited (compound objects)
		return self::DBGetTable(self::$m_aAttribOrigins[$sClass][$sAttCode]);
	}

	/**
	 * @param string $sClass
	 *
	 * @return string
	 */
	final public static function DBGetView($sClass)
	{
		return self::$m_sTablePrefix."view_".$sClass;
	}

	/**
	 * @return array
	 * @throws \CoreException
	 */
	final public static function DBEnumTables()
	{
		// This API does not rely on our capability to query the DB and retrieve
		// the list of existing tables
		// Rather, it uses the list of expected tables, corresponding to the data model
		$aTables = array();
		foreach (self::GetClasses() as $sClass) {
			if (!self::HasTable($sClass)) {
				continue;
			}
			$sTable = self::DBGetTable($sClass);

			// Could be completed later with all the classes that are using a given table
			if (!array_key_exists($sTable, $aTables)) {
				$aTables[$sTable] = array();
			}
			$aTables[$sTable][] = $sClass;
		}

		return $aTables;
	}

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 */
	final public static function DBGetIndexes($sClass)
	{
		self::_check_subclass($sClass);
		if (isset(self::$m_aClassParams[$sClass]['indexes'])) {
			$aRet = self::$m_aClassParams[$sClass]['indexes'];
		} else {
			$aRet = array();
		}

		return $aRet;
	}


	/**
	 * @param $sClass
	 * @param $aColumns
	 * @param $aTableInfo
	 *
	 * @return array
	 * @throws \CoreException
	 */
	private static function DBGetIndexesLength($sClass, $aColumns, $aTableInfo)
	{
		$aLength = array();
		$aAttDefs = self::ListAttributeDefs($sClass);
		foreach($aColumns as $sAttSqlCode)
		{
			$iLength = null;
			foreach($aAttDefs as $sAttCode => $oAttDef)
			{
				if (($sAttCode == $sAttSqlCode) || ($oAttDef->IsParam('sql') && ($oAttDef->Get('sql') == $sAttSqlCode)))
				{
					$iLength = $oAttDef->GetIndexLength();
					break;
				}
			}
			$aLength[] = $iLength;
		}

		return $aLength;
	}

	/**
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 */
	final public static function DBGetKey($sClass)
	{
		self::_check_subclass($sClass);

		return self::$m_aClassParams[$sClass]["db_key_field"];
	}

	/**
	 * Get "finalclass" DB field name
	 *
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 */
	final public static function DBGetClassField($sClass)
	{
		self::_check_subclass($sClass);

		// Leaf classes have no "finalclass" field.
		// Non Leaf classes have the same field as the root class
		if (!self::IsLeafClass($sClass)) {
			$sClass = MetaModel::GetRootClass($sClass);
		}

		return self::$m_aClassParams[$sClass]["db_finalclass_field"];
	}

	final public static function IsLeafClass($sClass)
	{
		return empty(self::$m_aChildClasses[$sClass]);
	}

	/**
	 * @param string $sClass
	 *
	 * @return boolean true if the class has no parent and no children
	 * @throws \CoreException
	 */
	final public static function IsStandaloneClass($sClass)
	{
		self::_check_subclass($sClass);

		return (empty(self::$m_aChildClasses[$sClass]) && empty(self::$m_aParentClasses[$sClass]));
	}

	/**
	 * @param string $sParentClass
	 * @param string $sChildClass
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	final public static function IsParentClass($sParentClass, $sChildClass)
	{
		self::_check_subclass($sChildClass);
		self::_check_subclass($sParentClass);
		if (in_array($sParentClass, self::$m_aParentClasses[$sChildClass])) {
			return true;
		}
		if ($sChildClass == $sParentClass) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $sClassA
	 * @param string $sClassB
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	final public static function IsSameFamilyBranch($sClassA, $sClassB)
	{
		self::_check_subclass($sClassA);
		self::_check_subclass($sClassB);
		if (in_array($sClassA, self::$m_aParentClasses[$sClassB])) {
			return true;
		}
		if (in_array($sClassB, self::$m_aParentClasses[$sClassA])) {
			return true;
		}
		if ($sClassA == $sClassB) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $sClassA
	 * @param string $sClassB
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	final public static function IsSameFamily($sClassA, $sClassB)
	{
		self::_check_subclass($sClassA);
		self::_check_subclass($sClassB);

		return (self::GetRootClass($sClassA) == self::GetRootClass($sClassB));
	}

	// Attributes of a given class may contain attributes defined in a parent class
	// - Some attributes are a copy of the definition
	// - Some attributes correspond to the upper class table definition (compound objects)
	// (see also filters definition)
	/**
	 * array of ("classname" => array of attributes)
	 *
	 * @var \AttributeDefinition[][]
	 */
	private static $m_aAttribDefs = array();
	/**
	 * array of ("classname" => array of ("attcode"=>"sourceclass"))
	 *
	 * @var array
	 */
	private static $m_aAttribOrigins = array();
	/**
	 * array of ("classname" => array of ("attcode"))
	 *
	 * @var array
	 */
	private static $m_aIgnoredAttributes = array();
	/**
	 * array of  ("classname" => array of ("attcode" => array of ("metaattcode" => oMetaAttDef))
	 *
	 * @var array
	 */
	private static $m_aEnumToMeta = array();

	/**
	 * @param string $sClass
	 *
	 * @return AttributeDefinition[]
	 * @throws \CoreException
	 *
	 * @see GetAttributesList for attcode list
	 */
	final public static function ListAttributeDefs($sClass)
	{
		self::_check_subclass($sClass);
		return self::$m_aAttribDefs[$sClass];
	}

	/**
	 * Return an array of attributes codes for the $sClass. The list can be limited to some attribute types only.
	 *
	 * @param string $sClass
	 * @param string[] $aDesiredAttTypes Array of AttributeDefinition classes to filter the list on
	 * @param string|null $sListCode If provided, attributes will be limited to those in this zlist
	 *
	 * @return string[] list of attcodes
	 * @throws \CoreException
	 *
	 * @see ListAttributeDefs to get AttributeDefinition array instead
	 */
	final public static function GetAttributesList(string $sClass, array $aDesiredAttTypes = [], ?string $sListCode = null)
	{
		self::_check_subclass($sClass);

		$aAttributesToCheck = [];
		if (!is_null($sListCode)) {
			$aAttCodes = self::FlattenZList(self::GetZListItems($sClass, $sListCode));
			foreach ($aAttCodes as $sAttCode) {
				// Important: As $aAttributesToCheck will only be used to check the type of the attribute definition, we considered is was ok to mix strings and objects to lower the memory print.
				$aAttributesToCheck[$sAttCode] = get_class(self::$m_aAttribDefs[$sClass][$sAttCode]);
			}
		} else {
			$aAttributesToCheck = self::$m_aAttribDefs[$sClass];
		}

		if (empty($aDesiredAttTypes)) {
			return array_keys($aAttributesToCheck);
		}

		$aMatchingAttCodes = [];
		/** @var string|AttributeDefinition $mAttDef See how it's built */
		foreach ($aAttributesToCheck as $sAttCode => $mAttDef) {
			foreach ($aDesiredAttTypes as $sDesiredAttType) {
				// Important: Use of a method allowing either an object or a class as a parameter is important
				if (is_a($mAttDef, $sDesiredAttType, true)) {
					$aMatchingAttCodes[] = $sAttCode;
				}
			}
		}

		return $aMatchingAttCodes;
	}

	/**
	 *
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 */
	final public static function GetFiltersList($sClass)
	{
		self::_check_subclass($sClass);

		return array_keys(self::$m_aFilterAttribList[$sClass]);
	}

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 */
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

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return bool
	 */
	final public static function IsValidKeyAttCode($sClass, $sAttCode)
	{
		if (!array_key_exists($sClass, self::$m_aAttribDefs)) {
			return false;
		}
		if (!array_key_exists($sAttCode, self::$m_aAttribDefs[$sClass])) {
			return false;
		}

		return (self::$m_aAttribDefs[$sClass][$sAttCode]->IsExternalKey());
	}

	/**
	 * Check it the given attribute exists in the specified class
	 *
	 * @api
	 *
	 * @param string $sClass Class name
	 * @param string $sAttCode Attribute code
	 * @param bool $bExtended Allow the extended syntax: extkey_id->remote_attcode
	 *
	 * @return bool
	 * @throws \Exception
	 */
	final public static function IsValidAttCode($sClass, $sAttCode, $bExtended = false)
	{
		if (!array_key_exists($sClass, self::$m_aAttribDefs)) {
			return false;
		}

		if ($bExtended) {
			if (($iPos = strpos($sAttCode, '->')) === false) {
				$bRes = array_key_exists($sAttCode, self::$m_aAttribDefs[$sClass]);
			} else {
				$sExtKeyAttCode = substr($sAttCode, 0, $iPos);
				$sRemoteAttCode = substr($sAttCode, $iPos + 2);
				if (MetaModel::IsValidAttCode($sClass, $sExtKeyAttCode)) {
					$oKeyAttDef = MetaModel::GetAttributeDef($sClass, $sExtKeyAttCode);
					$sRemoteClass = $oKeyAttDef->GetTargetClass();
					$bRes = MetaModel::IsValidAttCode($sRemoteClass, $sRemoteAttCode, true);
				} else {
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

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return bool
	 */
	final public static function IsAttributeOrigin($sClass, $sAttCode)
	{
		return (self::$m_aAttribOrigins[$sClass][$sAttCode] == $sClass);
	}

	/**
	 *
	 * @param string $sClass
	 * @param string $sFilterCode
	 *
	 * @return bool
	 */
	final public static function IsValidFilterCode($sClass, $sFilterCode)
	{
		if (!array_key_exists($sClass, self::$m_aFilterAttribList)) {
			return false;
		}

		return (array_key_exists($sFilterCode, self::$m_aFilterAttribList[$sClass]));
	}

	/**
	 * Check if the given class name is actually a persistent class
	 *
	 * @api
	 * @param string $sClass
	 *
	 * @return bool
	 */
	public static function IsValidClass($sClass)
	{
		return (array_key_exists($sClass, self::$m_aAttribDefs));
	}

	/**
	 * @param $oObject
	 *
	 * @return bool
	 */
	public static function IsValidObject($oObject)
	{
		if (!is_object($oObject))
		{
			return false;
		}
		return (self::IsValidClass(get_class($oObject)));
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	public static function IsReconcKey($sClass, $sAttCode)
	{
		return (in_array($sAttCode, self::GetReconcKeys($sClass)));
	}

	/**
	 * @param string $sClass Class name
	 * @param string $sAttCode Attribute code
	 *
	 * @return \AttributeDefinition the AttributeDefinition of the $sAttCode attribute of the $sClass class
	 * @throws Exception
	 */
	final public static function GetAttributeDef($sClass, $sAttCode)
	{
		self::_check_subclass($sClass);
		if (isset(self::$m_aAttribDefs[$sClass][$sAttCode])) {
			return self::$m_aAttribDefs[$sClass][$sAttCode];
		} elseif (($iPos = strpos($sAttCode, '->')) !== false) {
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

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 */
	final public static function GetExternalKeys($sClass)
	{
		$aExtKeys = array();
		foreach (self::ListAttributeDefs($sClass) as $sAttCode => $oAtt) {
			if ($oAtt->IsExternalKey()) {
				$aExtKeys[$sAttCode] = $oAtt;
			}
		}

		return $aExtKeys;
	}

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 */
	final public static function GetLinkedSets($sClass)
	{
		$aLinkedSets = array();
		foreach (self::ListAttributeDefs($sClass) as $sAttCode => $oAtt) {
			// Note: Careful, this will only return SUB-classes, which does NOT include AttributeLinkedset itself! We might want to use "is_a()" instead.
			if (is_subclass_of($oAtt, 'AttributeLinkedSet')) {
				$aLinkedSets[$sAttCode] = $oAtt;
			}
		}

		return $aLinkedSets;
	}

	/**
	 * @param string $sClass
	 * @param string $sKeyAttCode
	 *
	 * @return mixed
	 * @throws \CoreException
	 */
	final public static function GetExternalFields($sClass, $sKeyAttCode)
	{
		static $aExtFields = array();
		if (!isset($aExtFields[$sClass][$sKeyAttCode])) {
			$aExtFields[$sClass][$sKeyAttCode] = array();
			foreach (self::ListAttributeDefs($sClass) as $sAttCode => $oAtt) {
				if ($oAtt->IsExternalField() && ($oAtt->GetKeyAttCode() == $sKeyAttCode)) {
					$aExtFields[$sClass][$sKeyAttCode][$oAtt->GetExtAttCode()] = $oAtt;
				}
			}
		}
		return $aExtFields[$sClass][$sKeyAttCode];
	}

	/**
	 * @param string $sClass
	 * @param string $sKeyAttCode
	 * @param string $sRemoteAttCode
	 *
	 * @return null|string
	 * @throws \CoreException
	 */
	final public static function FindExternalField($sClass, $sKeyAttCode, $sRemoteAttCode)
	{
		$aExtFields = self::GetExternalFields($sClass, $sKeyAttCode);
		if (isset($aExtFields[$sRemoteAttCode])) {
			return $aExtFields[$sRemoteAttCode];
		} else {
			return null;
		}
	}

	/** @var array Cache for caselog attributes of the classes */
	protected static $m_aCaseLogsAttributesCache = [];

	/**
	 * Return an array of attribute codes for the caselogs attributes of $sClass
	 *
	 * @param string $sClass
	 * @param string|null $sListCode If provided, will only return attributes from ths zlist
	 *
	 * @return array
	 * @throws \CoreException
	 * @since 3.0.0
	 */
	final public static function GetCaseLogs(string $sClass, ?string $sListCode = null)
	{
		$sScopeKey = empty($sListCode) ? 'all' : 'zlist:'.$sListCode;
		if (!isset(static::$m_aCaseLogsAttributesCache[$sClass][$sScopeKey])) {
			static::$m_aCaseLogsAttributesCache[$sClass][$sScopeKey] = self::GetAttributesList($sClass, ['AttributeCaseLog'], $sListCode);
		}

		return static::$m_aCaseLogsAttributesCache[$sClass][$sScopeKey];
	}

	/** @var array */
	protected static $m_aTrackForwardCache = array();

	/**
	 * List external keys for which there is a LinkSet (direct or indirect) on the other end
	 *
	 * For those external keys, a change will have a special meaning on the other end
	 * in term of change tracking
	 *
	 * @param string $sClass
	 *
	 * @return mixed
	 * @throws \CoreException
	 */
	final public static function GetTrackForwardExternalKeys($sClass)
	{
		if (!isset(self::$m_aTrackForwardCache[$sClass])) {
			$aRes = array();
			foreach (MetaModel::GetExternalKeys($sClass) as $sAttCode => $oAttDef) {
				$sRemoteClass = $oAttDef->GetTargetClass();
				foreach (MetaModel::ListAttributeDefs($sRemoteClass) as $sRemoteAttCode => $oRemoteAttDef) {
					if (!$oRemoteAttDef->IsLinkSet())
					{
						continue;
					}
					if (!is_subclass_of($sClass, $oRemoteAttDef->GetLinkedClass()) && $oRemoteAttDef->GetLinkedClass() != $sClass)
					{
						continue;
					}
					if ($oRemoteAttDef->GetExtKeyToMe() != $sAttCode)
					{
						continue;
					}
					$aRes[$sAttCode] = $oRemoteAttDef;
				}
			}
			self::$m_aTrackForwardCache[$sClass] = $aRes;
		}

		return self::$m_aTrackForwardCache[$sClass];
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return array
	 */
	final public static function ListMetaAttributes($sClass, $sAttCode)
	{
		if (isset(self::$m_aEnumToMeta[$sClass][$sAttCode])) {
			$aRet = self::$m_aEnumToMeta[$sClass][$sAttCode];
		} else {
			$aRet = array();
		}

		return $aRet;
	}

	/**
	 * Get the attribute label
	 *
	 * @param string sClass Persistent class
	 * @param string sAttCodeEx Extended attribute code: attcode[->attcode]
	 * @param bool $bShowMandatory If true, add a star character (at the end or before the ->) to show that the field
	 *     is mandatory
	 *
	 * @return string A user friendly format of the string: AttributeName or AttributeName->ExtAttributeName
	 * @throws \Exception
	 */
	public static function GetLabel($sClass, $sAttCodeEx, $bShowMandatory = false)
	{
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

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetDescription($sClass, $sAttCode)
	{
		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
		if ($oAttDef) {
			return $oAttDef->GetDescription();
		}

		return "";
	}

	/**
	 * @var array array of (FilterCode => AttributeCode)
	 */
	private static $m_aFilterAttribList = array();

	/**
	 * @deprecated 3.0.0 do not use : dead code, will be removed in the future N°4690 - Deprecate "FilterCodes"
	 * instead of array_keys(MetaModel::GetClassFilterDefs($sClass)); use MetaModel::GetFiltersList($sClass)
	 *
	 * @param string $sClass
	 *
	 * @return mixed
	 * @throws \CoreException
	 */
	public static function GetClassFilterDefs($sClass)
	{
		// cannot notify depreciation for now as this is still MASSIVELY used in iTop core !
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('do not use MetaModel::GetClassFilterDefs: dead code, will be removed in the future. Use MetaModel::GetFiltersList or MetaModel::GetFiltersAttributes');

		return self::$m_aFilterAttribList[$sClass];
	}

	/**
	 *
	 * @param string $sClass
	 *
	 * @return array ($sFilterCode=>$sAttributeCode) + id=>id
	 * @throws \CoreException
	 */
	public static function GetFilterAttribList($sClass)
	{
		return self::$m_aFilterAttribList[$sClass];
	}

	/**
	 * @deprecated 3.0.0 do not use : dead code, will be removed in the future use GetLabel instead N°4690 - Deprecate "FilterCodes"
	 *
	 * @param string $sClass
	 * @param string $sFilterCode
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public static function GetFilterLabel($sClass, $sFilterCode)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('do not use MetaModel::GetFilterLabel : dead code, will be removed in the future. Use MetaModel::GetLabel instead');

		return this::GetLabel($sClass, $sFilterCode);
	}

	/**
	 * @var array array of ("listcode" => various info on the list, common to every classes)
	 */
	private static $m_aListInfos = array();
	/**
	 * array of ("classname" => array of "listcode" => list)
	 * list may be an array of attcode / fltcode
	 * list may be an array of "groupname" => (array of attcode / fltcode)
	 *
	 * @var array
	 */
	private static $m_aListData = array();

	/**
	 * @return array
	 */
	public static function EnumZLists()
	{
		return array_keys(self::$m_aListInfos);
	}

	/**
	 * @param string $sListCode
	 *
	 * @return mixed
	 */
	final static public function GetZListInfo($sListCode)
	{
		return self::$m_aListInfos[$sListCode];
	}

	/**
	 * @param string $sClass
	 * @param string $sListCode
	 *
	 * @return array list of attribute codes
	 */
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
		if (empty($sParentClass))
		{
			return array();
		} // nothing for the mother of all classes
		// Dig recursively
		return self::GetZListItems($sParentClass, $sListCode);
	}

	/**
	 * @param string $sRemoteClass
	 *
	 * @return \AttributeDefinition[] list of attdefs to display by default for the remote class
	 *
	 * @throws \Exception
	 * @uses \MetaModel::GetZListItems 'list' zlist
	 *
	 * @since 3.0.0 N°2334
	 */
	public static function GetZListAttDefsFilteredForIndirectRemoteClass(string $sRemoteClass): array
	{
		$aAttCodesToPrint = [];

		foreach (MetaModel::GetZListItems($sRemoteClass, 'list') as $sFieldCode) {
			//TODO: check the state of the attribute: hidden or visible ?

			$oRemoteAttDef = MetaModel::GetAttributeDef($sRemoteClass, $sFieldCode);
			$aAttCodesToPrint[] = $oRemoteAttDef;
		}

		return $aAttCodesToPrint;
	}

	/**
	 * @param string $sClass left class
	 * @param string $sAttCode AttributeLinkedSetIndirect attcode
	 *
	 * @return \AttributeDefinition[] list of attdefs to display by default for lnk class
	 *
	 * @throws \CoreException
	 * @uses \MetaModel::GetZListItems 'list' zlist
	 *
	 * @since 3.0.0 N°2334
	 */
	public static function GetZListAttDefsFilteredForIndirectLinkClass(string $sClass, string $sAttCode): array
	{
		$aAttCodesToPrint = [];

		$oLinkedSetAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
		$sLinkedClass = $oLinkedSetAttDef->GetLinkedClass();
		$sExtKeyToRemote = $oLinkedSetAttDef->GetExtKeyToRemote();
		$sExtKeyToMe = $oLinkedSetAttDef->GetExtKeyToMe();

		$sStateAttCode = MetaModel::GetStateAttributeCode($sClass);
		$sDefaultState = MetaModel::GetDefaultState($sClass);

		foreach (MetaModel::FlattenZList(MetaModel::GetZListItems($sLinkedClass, 'list')) as $sLnkAttCode)
		{
			$oLnkAttDef = MetaModel::GetAttributeDef($sLinkedClass, $sLnkAttCode);
			if ($sStateAttCode == $sLnkAttCode)
			{
				// State attribute is always hidden from the UI
				continue;
			}
			if (($sLnkAttCode == $sExtKeyToMe)
				|| ($sLnkAttCode == $sExtKeyToRemote)
				|| ($sLnkAttCode == 'finalclass'))
			{
				continue;
			}
			if (!($oLnkAttDef->IsWritable()))
			{
				continue;
			}

			$iFlags = MetaModel::GetAttributeFlags($sLinkedClass, $sDefaultState, $sLnkAttCode);
			if (!($iFlags & OPT_ATT_HIDDEN) && !($iFlags & OPT_ATT_READONLY))
			{
				$aAttCodesToPrint[] = $oLnkAttDef;
			}
		}

		return $aAttCodesToPrint;
	}

	/**
	 * @param string $sObjectClass class of the object containing the AttributeLinkedSetIndirect (eg: Team)
	 * @param string $sObjectLinkedSetIndirectAttCode code of the AttributeLinkedSetIndirect in the sObjectClass (eg: persons_list in the Team class, pointing to lnkPersonToTeam lnk class)
	 * @param string $sRemoteClass remote class pointed by the lnk class (eg: Person pointed by lnkPersonToTeam)
	 * @param string $sLnkExternalKeyToRemoteClassAttCode in the lnk class, external key to the remote class (eg: person_id in lnkPersonToTeam, pointing to a Person instance)
	 *
	 * @return string[] attcodes to display, containing aliases
	 * @throws \CoreException
	 *
	 * @since 3.0.0 N°2334 added code for n-n relations in {@see BlockIndirectLinkSetViewTable::GetAttCodesToDisplay}
	 * @since 3.1.0 N°3200 method creation so that it can be used elsewhere
	 */
	public static function GetAttributeLinkedSetIndirectDatatableAttCodesToDisplay(string $sObjectClass, string $sObjectLinkedSetIndirectAttCode, string $sRemoteClass, string $sLnkExternalKeyToRemoteClassAttCode):array
	{
		$aLnkAttDefsToDisplay = MetaModel::GetZListAttDefsFilteredForIndirectLinkClass($sObjectClass, $sObjectLinkedSetIndirectAttCode);
		$aRemoteAttDefsToDisplay = MetaModel::GetZListAttDefsFilteredForIndirectRemoteClass($sRemoteClass);
		$aLnkAttCodesToDisplay = array_map(
			function ($oLnkAttDef) {
				return ormLinkSet::LINK_ALIAS.'.'.$oLnkAttDef->GetCode();
			},
			$aLnkAttDefsToDisplay
		);
		if (!in_array(ormLinkSet::LINK_ALIAS.'.'.$sLnkExternalKeyToRemoteClassAttCode, $aLnkAttCodesToDisplay)) {
			// we need to display a link to the remote class instance !
			$aLnkAttCodesToDisplay[] = ormLinkSet::LINK_ALIAS.'.'.$sLnkExternalKeyToRemoteClassAttCode;
		}
		$aRemoteAttCodesToDisplay = array_map(
			function ($oRemoteAttDef) {
				return ormLinkSet::REMOTE_ALIAS.'.'.$oRemoteAttDef->GetCode();
			},
			$aRemoteAttDefsToDisplay
		);
		$aAttCodesToDisplay = array_merge($aLnkAttCodesToDisplay, $aRemoteAttCodesToDisplay);

		return $aAttCodesToDisplay;
	}

	/**
	 * @param string $sClass
	 * @param string $sListCode
	 * @param string $sAttCodeOrFltCode
	 * @param string $sGroup
	 *
	 * @return bool
	 */
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
	/**
	 * array of ("relcode" => various info on the list, common to every classes)
	 *
	 * @var array
	 */
	private static $m_aRelationInfos = array();

	/**
	 * @deprecated Use EnumRelationsEx instead
	 *
	 * @param string $sClass
	 *
	 * @return array multitype:string unknown |Ambigous <string, multitype:>
	 * @throws \CoreException
	 * @throws \Exception
	 * @throws \OQLException
	 */
	public static function EnumRelations($sClass = '')
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('Use EnumRelationsEx instead');
		$aResult = array_keys(self::$m_aRelationInfos);
		if (!empty($sClass)) {
			// Return only the relations that have a meaning (i.e. for which at least one query is defined)
			// for the specified class
			$aClassRelations = array();
			foreach ($aResult as $sRelCode) {
				$aQueriesDown = self::EnumRelationQueries($sClass, $sRelCode);
				if (count($aQueriesDown) > 0) {
					$aClassRelations[] = $sRelCode;
				}
				// Temporary patch: until the impact analysis GUI gets rewritten,
				// let's consider that "depends on" is equivalent to "impacts/up"
				// The current patch has been implemented in DBObject and MetaModel
				if ($sRelCode == 'impacts') {
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

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \Exception
	 * @throws \OQLException
	 */
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

	/**
	 * @param string $sRelCode Relation code
	 * @param bool $bDown Relation direction, is it downstream (true) or upstream (false). Default is true.
	 *
	 * @return string
	 * @throws \DictExceptionMissingString
	 */
	final static public function GetRelationDescription($sRelCode, $bDown = true)
	{
		// Legacy convention had only one description describing the relation.
		// Now, as the relation is bidirectional, we have a description for each directions.
		$sLegacy = Dict::S("Relation:$sRelCode/Description");

		if($bDown)
		{
			$sKey = "Relation:$sRelCode/DownStream+";
		}
		else
		{
			$sKey = "Relation:$sRelCode/UpStream+";
		}
		$sRet = Dict::S($sKey, $sLegacy);

		return $sRet;
	}

	/**
	 * @param string $sRelCode Relation code
	 * @param bool $bDown Relation direction, is it downstream (true) or upstream (false). Default is true.
	 *
	 * @return string
	 * @throws \DictExceptionMissingString
	 */
	final public static function GetRelationLabel($sRelCode, $bDown = true)
	{
		if ($bDown) {
			// The legacy convention is confusing with regard to the way we have conceptualized the relations:
			// In the former representation, the main stream was named after "up"
			// Now, the relation from A to B says that something is transmitted from A to B, thus going DOWNstream as described in a petri net.
			$sKey = "Relation:$sRelCode/DownStream";
			$sLegacy = Dict::S("Relation:$sRelCode/VerbUp", $sKey);
		} else
		{
			$sKey = "Relation:$sRelCode/UpStream";
			$sLegacy = Dict::S("Relation:$sRelCode/VerbDown", $sKey);
		}

		return Dict::S($sKey, $sLegacy);
	}

	/**
	 * @param string $sRelCode
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \Exception
	 * @throws \OQLException
	 */
	protected static function ComputeRelationQueries($sRelCode)
	{
		$aQueries = array();
		foreach(self::GetClasses() as $sClass)
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
					if (Utils::StrLen($aNeighbourData['sQueryDown']) == 0) {
						$oAttDef = self::GetAttributeDef($sClass, $aNeighbourData['sAttribute']);
						if ($oAttDef instanceof AttributeExternalKey) {
							$sTargetClass = $oAttDef->GetTargetClass();
							$aNeighbourData['sToClass'] = $sTargetClass;
							$aNeighbourData['sQueryDown'] = 'SELECT '.$sTargetClass.' AS o WHERE o.id = :this->'.$aNeighbourData['sAttribute'];
							$aNeighbourData['sQueryUp'] = 'SELECT '.$aNeighbourData['sFromClass'].' AS o WHERE o.'.$aNeighbourData['sAttribute'].' = :this->id';
						} elseif ($oAttDef instanceof AttributeLinkedSet)
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
						$sToClass = $aNeighbourData['sToClass'];
						foreach(self::EnumChildClasses($sToClass, ENUM_CHILD_CLASSES_ALL) as $sSubClass)
						{
							$aQueries[$sSubClass]['up'][$sArrowId] = $aNeighbourData;
						}
					}
				}
			}
		} // foreach class

		return $aQueries;
	}

	/**
	 * @param string $sClass
	 * @param string $sRelCode
	 * @param bool $bDown
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \Exception
	 * @throws \OQLException
	 */
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
	 * @param array $aSourceObjects The objects to start with
	 * @param int $iMaxDepth
	 * @param boolean $bEnableRedundancy
	 * @param array $aUnreachable Array of objects to be considered as 'unreachable'
	 * @param array $aContexts
	 *
	 * @return RelationGraph The graph of all the related objects
	 * @throws \Exception
	 */
	public static function GetRelatedObjectsDown(
		$sRelCode, $aSourceObjects, $iMaxDepth = 99, $bEnableRedundancy = true, $aUnreachable = array(), $aContexts = array()
	)
	{
		$oGraph = new RelationGraph();
		foreach ($aSourceObjects as $oObject) {
			$oGraph->AddSourceObject($oObject);
		}
		foreach ($aContexts as $key => $sOQL) {
			$oGraph->AddContextQuery($key, $sOQL);
		}
		$oGraph->ComputeRelatedObjectsDown($sRelCode, $iMaxDepth, $bEnableRedundancy, $aUnreachable);
		return $oGraph;
	}

	/**
	 * Compute the "RelatedObjects" in the reverse way
	 *
	 * @param string $sRelCode The code of the relation to use for the computation
	 * @param array $aSourceObjects The objects to start with
	 * @param int $iMaxDepth
	 * @param boolean $bEnableRedundancy
	 * @param array $aContexts
	 *
	 * @return RelationGraph The graph of all the related objects
	 * @throws \Exception
	 */
	public static function GetRelatedObjectsUp($sRelCode, $aSourceObjects, $iMaxDepth = 99, $bEnableRedundancy = true, $aContexts = array())
	{
		$oGraph = new RelationGraph();
		foreach ($aSourceObjects as $oObject) {
			$oGraph->AddSinkObject($oObject);
		}
		foreach ($aContexts as $key => $sOQL) {
			$oGraph->AddContextQuery($key, $sOQL);
		}
		$oGraph->ComputeRelatedObjectsUp($sRelCode, $iMaxDepth, $bEnableRedundancy);
		return $oGraph;
	}

	//
	// Object lifecycle model
	//
	/**
	 * array of ("classname" => array of "statecode"=>array('label'=>..., attribute_inherit=> attribute_list=>...))
	 *
	 * @var array
	 */
	private static $m_aStates = array();
	/**
	 * array of ("classname" => array of ("stimuluscode"=>array('label'=>...)))
	 *
	 * @var array
	 */
	private static $m_aStimuli = array();
	/**
	 * array of ("classname" => array of ("statcode_from"=>array of ("stimuluscode" => array('target_state'=>..., 'actions'=>array of handlers procs, 'user_restriction'=>TBD)))
	 *
	 * @var array
	 */
	private static $m_aTransitions = array();

	/**
	 * @param string $sClass
	 *
	 * @return array
	 */
	public static function EnumStates($sClass)
	{
		if (array_key_exists($sClass, self::$m_aStates))
		{
			return self::$m_aStates[$sClass];
		}
		elseif (self::HasStateAttributeCode($sClass))
		{
			$sStateAttCode = self::GetStateAttributeCode($sClass);
			$oAttDef = self::GetAttributeDef($sClass, $sStateAttCode);

			$aStates = [];
			foreach($oAttDef->GetAllowedValues() as $sStateCode => $sStateLabel)
			{
				$aStates[$sStateCode] = [
					'attribute_inherit' => '',
					'attribute_list' => [],
				];
			}

			return $aStates;
		}
		else
		{
			return array();
		}
	}

	/**
	 * @param string $sClass
	 *
	 * @return array All possible initial states, including the default one
	 * @throws \CoreException
	 * @throws \Exception
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

	/**
	 * @param string $sClass
	 *
	 * @return array
	 */
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

	/**
	 * Return true if $sClass has a lifecycle, which means that it has a state attribute AND stimuli
	 *
	 * @param string $sClass
	 *
	 * @return bool
	 * @throws \CoreException
	 * @since 3.0.0
	 * @see   self::HasStateAttributeCode($sClass)
	 */
	public static function HasLifecycle(string $sClass)
	{
		return self::HasStateAttributeCode($sClass) && !empty(self::EnumStimuli($sClass));
	}

	/**
	 * @param string $sClass
	 * @param string $sStateValue
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function GetStateLabel($sClass, $sStateValue)
	{
		$sStateAttrCode = self::GetStateAttributeCode($sClass);
		$oAttDef = self::GetAttributeDef($sClass, $sStateAttrCode);
		return $oAttDef->GetValueLabel($sStateValue);
	}

	/**
	 * @param string $sClass
	 * @param string $sStateValue
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function GetStateDescription($sClass, $sStateValue)
	{
		$sStateAttrCode = self::GetStateAttributeCode($sClass);
		$oAttDef = self::GetAttributeDef($sClass, $sStateAttrCode);
		return $oAttDef->GetValueDescription($sStateValue);
	}

	/**
	 * @param string $sClass
	 * @param string $sStateCode
	 *
	 * @return array
	 */
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

	/**
	 * Return an hash array of the possible attribute flags (code => value)
	 *
	 * Example:
	 * [
	 *  "read_only" => OPT_ATT_READONLY,
	 *  "mandatory" => OPT_ATT_MANDATORY,
	 *  ...
	 * ]
	 *
	 * @return array
	 * @since 2.7.0
	 */
	public static function EnumPossibleAttributeFlags()
	{
		return $aPossibleAttFlags = array(
			'normal' => OPT_ATT_NORMAL,
			'hidden' => OPT_ATT_HIDDEN,
			'read_only' => OPT_ATT_READONLY,
			'mandatory' => OPT_ATT_MANDATORY,
			'must_change' => OPT_ATT_MUSTCHANGE,
			'must_prompt' => OPT_ATT_MUSTPROMPT,
			'slave' => OPT_ATT_SLAVE
		);
	}

	/**
	 * @param string $sClass
	 * @param string $sState
	 * @param string $sAttCode
	 *
	 * @return int the binary combination of flags (OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY...) for the
	 *     given attribute in the given state of the object
	 * @throws \CoreException
	 *
	 * @see \DBObject::GetAttributeFlags()
	 */
	public static function GetAttributeFlags($sClass, $sState, $sAttCode)
	{
		$iFlags = 0; // By default (if no life cycle) no flag at all
		if (self::HasLifecycle($sClass)) {
			$aStates = MetaModel::EnumStates($sClass);
			if (!array_key_exists($sState, $aStates)) {
				throw new CoreException("Invalid state '$sState' for class '$sClass', expecting a value in {".implode(', ', array_keys($aStates))."}");
			}
			$aCurrentState = $aStates[$sState];
			if ((array_key_exists('attribute_list', $aCurrentState)) && (array_key_exists($sAttCode, $aCurrentState['attribute_list']))) {
				$iFlags = $aCurrentState['attribute_list'][$sAttCode];
			}
		}
		return $iFlags;
	}

	/**
	 * @param string $sClass string
	 * @param string $sState string
	 * @param string $sStimulus string
	 * @param string $sAttCode string
	 *
	 * @return int The $sAttCode flags when $sStimulus is applied on an object of $sClass in the $sState state.
	 * <strong>Note: This does NOT combine flags from the target state</strong>
	 * @throws CoreException
	 */
	public static function GetTransitionFlags($sClass, $sState, $sStimulus, $sAttCode)
	{
		$iFlags = 0; // By default (if no lifecycle) no flag at all
		if (self::HasLifecycle($sClass)) {
			$aTransitions = MetaModel::EnumTransitions($sClass, $sState);
			if (!array_key_exists($sStimulus, $aTransitions)) {
				throw new CoreException("Invalid transition '$sStimulus' for class '$sClass', expecting a value in {".implode(', ', array_keys($aTransitions))."}");
			}

			$aCurrentTransition = $aTransitions[$sStimulus];
			if ((array_key_exists('attribute_list', $aCurrentTransition)) && (array_key_exists($sAttCode, $aCurrentTransition['attribute_list']))) {
				$iFlags = $aCurrentTransition['attribute_list'][$sAttCode];
			}
		}

		return $iFlags;
	}

	/**
	 * @param string $sClass string Object class
	 * @param string $sStimulus string Stimulus code applied
	 * @param string $sOriginState string State the stimulus comes from
	 *
	 * @return array Attribute codes (with their flags) when $sStimulus is applied on an object of $sClass in the $sOriginState state.
	 * <strong>Note: Attributes (and flags) from the target state and the transition are combined</strong>
	 */
	public static function GetTransitionAttributes($sClass, $sStimulus, $sOriginState)
	{
		$aAttributes = array();

		// Retrieving target state
		$aTransitions = MetaModel::EnumTransitions($sClass, $sOriginState);
		$aTransition = $aTransitions[$sStimulus];
		$sTargetState = $aTransition['target_state'];

		// Retrieving attributes from state
		$aStates = MetaModel::EnumStates($sClass);
		$aTargetState = $aStates[$sTargetState];
		$aTargetStateAttributes = $aTargetState['attribute_list'];
		// - Merging with results (only MUST_XXX and MANDATORY)
		foreach($aTargetStateAttributes as $sTargetStateAttCode => $iTargetStateAttFlags)
		{
			$iTmpAttFlags = OPT_ATT_NORMAL;
			if ($iTargetStateAttFlags & OPT_ATT_MUSTPROMPT)
			{
				$iTmpAttFlags = $iTmpAttFlags | OPT_ATT_MUSTPROMPT;
			}
			if ($iTargetStateAttFlags & OPT_ATT_MUSTCHANGE)
			{
				$iTmpAttFlags = $iTmpAttFlags | OPT_ATT_MUSTCHANGE;
			}
			if ($iTargetStateAttFlags & OPT_ATT_MANDATORY)
			{
				$iTmpAttFlags = $iTmpAttFlags | OPT_ATT_MANDATORY;
			}

			$aAttributes[$sTargetStateAttCode] = $iTmpAttFlags;
		}

		// Retrieving attributes from transition
		$aTransitionAttributes = $aTransition['attribute_list'];
		// - Merging with results
		foreach($aTransitionAttributes as $sAttCode => $iAttributeFlags)
		{
			if (array_key_exists($sAttCode, $aAttributes))
			{
				$aAttributes[$sAttCode] = $aAttributes[$sAttCode] | $iAttributeFlags;
			}
			else
			{
				$aAttributes[$sAttCode] = $iAttributeFlags;
			}
		}

		return $aAttributes;
	}

	/**
	 * @param string $sClass
	 * @param string $sState
	 * @param string $sAttCode
	 *
	 * @return int Combines the flags from the all states that compose the initial_state_path
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function GetInitialStateAttributeFlags($sClass, $sState, $sAttCode)
	{
		$iFlags = self::GetAttributeFlags($sClass, $sState, $sAttCode); // Be default set the same flags as the 'target' state
		if (self::HasLifecycle($sClass)) {
			$aStates = MetaModel::EnumInitialStates($sClass);
			if (array_key_exists($sState, $aStates)) {
				$bReadOnly = (($iFlags & OPT_ATT_READONLY) == OPT_ATT_READONLY);
				$bHidden = (($iFlags & OPT_ATT_HIDDEN) == OPT_ATT_HIDDEN);
				foreach($aStates[$sState] as $sPrevState) {
					$iPrevFlags = self::GetAttributeFlags($sClass, $sPrevState, $sAttCode);
					if (($iPrevFlags & OPT_ATT_HIDDEN) != OPT_ATT_HIDDEN) {
						$bReadOnly = $bReadOnly && (($iPrevFlags & OPT_ATT_READONLY) == OPT_ATT_READONLY); // if it is/was not readonly => then it's not
					}
					$bHidden = $bHidden && (($iPrevFlags & OPT_ATT_HIDDEN) == OPT_ATT_HIDDEN); // if it is/was not hidden => then it's not
				}

				if ($bReadOnly) {
					$iFlags = $iFlags | OPT_ATT_READONLY;
				}
				else {
					$iFlags = $iFlags & ~OPT_ATT_READONLY;
				}

				if ($bHidden) {
					$iFlags = $iFlags | OPT_ATT_HIDDEN;
				}
				else {
					$iFlags = $iFlags & ~OPT_ATT_HIDDEN;
				}
			}
		}
		return $iFlags;
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public static function GetAllowedValues_att($sClass, $sAttCode, $aArgs = array(), $sContains = '')
	{
		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
		return $oAttDef->GetAllowedValues($aArgs, $sContains);
	}

	/**
	 * @deprecated 3.1.0 use GetAllowedValues_att  N°4690 - Deprecate "FilterCodes"
	 *
	 * @param string $sClass
	 * @param string $sFltCode
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return mixed
	 * @throws \CoreException
	 */
	public static function GetAllowedValues_flt($sClass, $sFltCode, $aArgs = array(), $sContains = '')
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('do not use MetaModel::GetAllowedValues_flt: dead code, will be removed in the future. Use MetaModel::GetAllowedValues');

		return self::GetAllowedValues_att($sClass, $sFltCode);
	}


	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param array $aArgs
	 * @param string $sContains
	 * @param int $iAdditionalValue
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public static function GetAllowedValuesAsObjectSet($sClass, $sAttCode, $aArgs = array(), $sContains = '', $iAdditionalValue = null)
	{
		/** @var \AttributeExternalKey $oAttDef */
		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);

		return $oAttDef->GetAllowedValuesAsObjectSet($aArgs, $sContains, $iAdditionalValue);
	}



	//
	// Business model declaration verbs (should be static)
	//
	/**
	 * @param string $sListCode
	 * @param array $aListInfo
	 *
	 * @throws \CoreException
	 */
	public static function RegisterZList($sListCode, $aListInfo)
	{
		// Check mandatory params
		$aMandatParams = array(
			"description" => "detailed (though one line) description of the list",
			"type" => "attributes | filters",
		);
		foreach($aMandatParams as $sParamName => $sParamDesc)
		{
			if (!array_key_exists($sParamName, $aListInfo))
			{
				throw new CoreException("Declaration of list $sListCode - missing parameter $sParamName");
			}
		}

		self::$m_aListInfos[$sListCode] = $aListInfo;
	}

	/**
	 * @param string $sRelCode
	 */
	public static function RegisterRelation($sRelCode)
	{
		// Each item used to be an array of properties...
		self::$m_aRelationInfos[$sRelCode] = $sRelCode;
	}

	/**
	 * Helper to correctly add a magic attribute (called from InitClasses)
	 *
	 * @param \AttributeDefinition $oAttribute
	 * @param string $sTargetClass
	 * @param string $sOriginClass
	 */
	private static function AddMagicAttribute(AttributeDefinition $oAttribute, $sTargetClass, $sOriginClass = null)
	{
		$sCode = $oAttribute->GetCode();
		if (is_null($sOriginClass)) {
			$sOriginClass = $sTargetClass;
		}
		$oAttribute->SetHostClass($sTargetClass);
		self::$m_aAttribDefs[$sTargetClass][$sCode] = $oAttribute;
		self::$m_aAttribOrigins[$sTargetClass][$sCode] = $sOriginClass;

		self::$m_aFilterAttribList[$sTargetClass][$sCode] = $sCode;
	}

	/**
	 * Must be called once and only once...
	 *
	 * @param string $sTablePrefix
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function InitClasses($sTablePrefix)
	{
		if (count(self::GetClasses()) > 0)
		{
			throw new CoreException("InitClasses should not be called more than once -skipped");
		}

		self::$m_sTablePrefix = $sTablePrefix;
		self::InitExtensions();

		// Initialize the classes (declared attributes, etc.)
		//
		$aObsoletableRootClasses = array();
		foreach(get_declared_classes() as $sPHPClass)
		{
			if (is_subclass_of($sPHPClass, 'DBObject'))
			{
				$sParent = self::GetParentPersistentClass($sPHPClass);
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

						// Inherit archive flag
						$bParentArchivable = isset(self::$m_aClassParams[$sParent]['archive']) ? self::$m_aClassParams[$sParent]['archive'] : false;
						$bArchivable = isset(self::$m_aClassParams[$sPHPClass]['archive']) ? self::$m_aClassParams[$sPHPClass]['archive'] : null;
						if (!$bParentArchivable && $bArchivable && !self::IsRootClass($sPHPClass))
						{
							throw new Exception("Archivability must be declared on top of the class hierarchy above $sPHPClass (consistency throughout the whole class tree is a must)");
						}
						if ($bParentArchivable && ($bArchivable === false))
						{
							throw new Exception("$sPHPClass must be archivable (consistency throughout the whole class tree is a must)");
						}
						$bReallyArchivable = $bParentArchivable || $bArchivable;
						self::$m_aClassParams[$sPHPClass]['archive'] = $bReallyArchivable;
						$bArchiveRoot = $bReallyArchivable && !$bParentArchivable;
						self::$m_aClassParams[$sPHPClass]['archive_root'] = $bArchiveRoot;
						if ($bReallyArchivable)
						{
							self::$m_aClassParams[$sPHPClass]['archive_root_class'] = $bArchiveRoot ? $sPHPClass : self::$m_aClassParams[$sParent]['archive_root_class'];
						}

						// Inherit obsolescence expression
						$sObsolescence = null;
						if (isset(self::$m_aClassParams[$sPHPClass]['obsolescence_expression']))
						{
							// Defined or overloaded
							$sObsolescence = self::$m_aClassParams[$sPHPClass]['obsolescence_expression'];
							$aObsoletableRootClasses[self::$m_aRootClasses[$sPHPClass]] = true;
						}
						elseif (isset(self::$m_aClassParams[$sParent]['obsolescence_expression']))
						{
							// Inherited
							$sObsolescence = self::$m_aClassParams[$sParent]['obsolescence_expression'];
						}
						self::$m_aClassParams[$sPHPClass]['obsolescence_expression'] = $sObsolescence;

						// Inherit fields semantic
						// - State attribute
						$bParentHasStateAttribute = (isset(self::$m_aClassParams[$sParent]['state_attcode']) && !empty(self::$m_aClassParams[$sParent]['state_attcode']));
						$bHasStateAttribute = (isset(self::$m_aClassParams[$sPHPClass]['state_attcode']) && !empty(self::$m_aClassParams[$sPHPClass]['state_attcode']));
						if($bParentHasStateAttribute && !$bHasStateAttribute) {
							// Set attribute code
							self::$m_aClassParams[$sPHPClass]['state_attcode'] = self::$m_aClassParams[$sParent]['state_attcode'];

							// Note: Don't set self::$m_aStates[$sPHPClass], it has already been done by self::Init_DefineState()
						}
						// - Image attribute
						$bParentHasImageAttribute = (isset(self::$m_aClassParams[$sParent]['image_attcode']) && !empty(self::$m_aClassParams[$sParent]['image_attcode']));
						$bHasImageAttribute = (isset(self::$m_aClassParams[$sPHPClass]['image_attcode']) && !empty(self::$m_aClassParams[$sPHPClass]['image_attcode']));
						if($bParentHasImageAttribute && !$bHasImageAttribute) {
							// Set attribute code
							self::$m_aClassParams[$sPHPClass]['image_attcode'] = self::$m_aClassParams[$sParent]['image_attcode'];
						}

						foreach(MetaModel::EnumPlugins('iOnClassInitialization') as $sPluginClass => $oClassInit)
						{
							$oClassInit->OnAfterClassInitialization($sPHPClass);
						}
					}

					$aCurrentClassUniquenessRules = MetaModel::GetUniquenessRules($sPHPClass, true);
					if (!empty($aCurrentClassUniquenessRules))
					{
						$aClassFields = self::GetAttributesList($sPHPClass);
						foreach ($aCurrentClassUniquenessRules as $sUniquenessRuleId => $aUniquenessRuleProperties)
						{
							$bIsRuleOverride = self::HasSameUniquenessRuleInParent($sPHPClass, $sUniquenessRuleId);
							try
							{
								self::CheckUniquenessRuleValidity($aUniquenessRuleProperties, $bIsRuleOverride, $aClassFields);
							}
							catch (CoreUnexpectedValue $e)
							{
								throw new Exception("Invalid uniqueness rule declaration : class={$sPHPClass}, rule=$sUniquenessRuleId, reason={$e->getMessage()}");
							}

							if (!$bIsRuleOverride)
							{
								self::SetUniquenessRuleRootClass($sPHPClass, $sUniquenessRuleId);
							}
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
			if (self::IsStandaloneClass($sRootClass))
			{
				continue;
			}

			$sDbFinalClassField = self::DBGetClassField($sRootClass);
			if (strlen($sDbFinalClassField) == 0)
			{
				$sDbFinalClassField = 'finalclass';
				self::$m_aClassParams[$sRootClass]["db_finalclass_field"] = 'finalclass';
			}
			$oClassAtt = new AttributeFinalClass('finalclass', array(
				"sql"             => $sDbFinalClassField,
				"default_value"   => $sRootClass,
				"is_null_allowed" => false,
				"depends_on"      => array(),
			));
			self::AddMagicAttribute($oClassAtt, $sRootClass);

			$bObsoletable = array_key_exists($sRootClass, $aObsoletableRootClasses);
			if ($bObsoletable && is_null(self::$m_aClassParams[$sRootClass]['obsolescence_expression'])) {
				self::$m_aClassParams[$sRootClass]['obsolescence_expression'] = '0';
			}


			foreach (self::EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_EXCLUDETOP) as $sChildClass) {
				if (array_key_exists('finalclass', self::$m_aAttribDefs[$sChildClass])) {
					throw new CoreException("Class $sChildClass, 'finalclass' is a reserved keyword, it cannot be used as an attribute code");
				}
				if (array_key_exists('finalclass', self::$m_aFilterAttribList[$sChildClass])) {
					throw new CoreException("Class $sChildClass, 'finalclass' is a reserved keyword, it cannot be used as a filter code");
				}
				$oCloned = clone $oClassAtt;
				$oCloned->SetFixedValue($sChildClass);
				self::AddMagicAttribute($oCloned, $sChildClass, $sRootClass);

				if ($bObsoletable && is_null(self::$m_aClassParams[$sChildClass]['obsolescence_expression'])) {
					self::$m_aClassParams[$sChildClass]['obsolescence_expression'] = '0';
				}
			}
		}

		// Add magic attributes to the classes
		foreach (self::GetClasses() as $sClass) {
			$sRootClass = self::$m_aRootClasses[$sClass];

			// Create the friendly name attribute
			$sFriendlyNameAttCode = 'friendlyname';
			$oFriendlyName = new AttributeFriendlyName($sFriendlyNameAttCode);
			self::AddMagicAttribute($oFriendlyName, $sClass);

			if (self::$m_aClassParams[$sClass]["archive_root"]) {
				// Create archive attributes on top the archivable hierarchy
				$oArchiveFlag = new AttributeArchiveFlag('archive_flag');
				self::AddMagicAttribute($oArchiveFlag, $sClass);

				$oArchiveDate = new AttributeArchiveDate('archive_date', array('magic' => true, "allowed_values" => null, "sql" => 'archive_date', "default_value" => '', "is_null_allowed" => true, "depends_on" => array()));
				self::AddMagicAttribute($oArchiveDate, $sClass);
			} elseif (self::$m_aClassParams[$sClass]["archive"]) {
				$sArchiveRoot = self::$m_aClassParams[$sClass]['archive_root_class'];
				// Inherit archive attributes
				$oArchiveFlag = clone self::$m_aAttribDefs[$sArchiveRoot]['archive_flag'];
				$oArchiveFlag->SetHostClass($sClass);
				self::$m_aAttribDefs[$sClass]['archive_flag'] = $oArchiveFlag;
				self::$m_aAttribOrigins[$sClass]['archive_flag'] = $sArchiveRoot;

				$oArchiveDate = clone self::$m_aAttribDefs[$sArchiveRoot]['archive_date'];
				$oArchiveDate->SetHostClass($sClass);
				self::$m_aAttribDefs[$sClass]['archive_date'] = $oArchiveDate;
				self::$m_aAttribOrigins[$sClass]['archive_date'] = $sArchiveRoot;

			}
			if (!is_null(self::$m_aClassParams[$sClass]['obsolescence_expression'])) {
				$oObsolescenceFlag = new AttributeObsolescenceFlag('obsolescence_flag');
				self::AddMagicAttribute($oObsolescenceFlag, $sClass);

				if (self::$m_aRootClasses[$sClass] == $sClass) {
					$oObsolescenceDate = new AttributeObsolescenceDate('obsolescence_date', array('magic' => true, "allowed_values" => null, "sql" => 'obsolescence_date', "default_value" => '', "is_null_allowed" => true, "depends_on" => array()));
					self::AddMagicAttribute($oObsolescenceDate, $sClass);
				} else {
					$oObsolescenceDate = clone self::$m_aAttribDefs[$sRootClass]['obsolescence_date'];
					$oObsolescenceDate->SetHostClass($sClass);
					self::$m_aAttribDefs[$sClass]['obsolescence_date'] = $oObsolescenceDate;
					self::$m_aAttribOrigins[$sClass]['obsolescence_date'] = $sRootClass;
				}
			}
		}

		// Prepare external fields and filters
		// Add final class to external keys
		// Add magic attributes to external keys (finalclass, friendlyname, archive_flag, obsolescence_flag)
		foreach (self::GetClasses() as $sClass) {
			foreach (self::$m_aAttribDefs[$sClass] as $sAttCode => $oAttDef) {
				// Compute the filter codes
				//
				foreach ($oAttDef->GetFilterDefinitions() as $sFilterCode => $sCode) {
					self::$m_aFilterAttribList[$sClass][$sFilterCode] = $sCode;
				}

				// Compute the fields that will be used to display a pointer to another object
				//
				if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE)) {
					// oAttDef is either
					// - an external KEY / FIELD (direct),
					// - an external field pointing to an external KEY / FIELD
					// - an external field pointing to an external field pointing to....
					$sRemoteClass = $oAttDef->GetTargetClass(EXTKEY_ABSOLUTE);

					if ($oAttDef->IsExternalField()) {
						// This is a key, but the value comes from elsewhere
						// Create an external field pointing to the remote friendly name attribute
						$sKeyAttCode = $oAttDef->GetKeyAttCode();
						$sRemoteAttCode = $oAttDef->GetExtAttCode()."_friendlyname";
						$sFriendlyNameAttCode = $sAttCode.'_friendlyname';
						$oFriendlyName = new AttributeExternalField($sFriendlyNameAttCode, array("allowed_values" => null, "extkey_attcode" => $sKeyAttCode, "target_attcode" => $sRemoteAttCode, "depends_on" => array()));
						self::AddMagicAttribute($oFriendlyName, $sClass, self::$m_aAttribOrigins[$sClass][$sKeyAttCode]);
					} else {
						// Create the friendly name attribute
						$sFriendlyNameAttCode = $sAttCode.'_friendlyname';
						$oFriendlyName = new AttributeExternalField($sFriendlyNameAttCode, array('allowed_values' => null, 'extkey_attcode' => $sAttCode, "target_attcode" => 'friendlyname', 'depends_on' => array()));
						self::AddMagicAttribute($oFriendlyName, $sClass, self::$m_aAttribOrigins[$sClass][$sAttCode]);

						if (self::HasChildrenClasses($sRemoteClass)) {
							// First, create an external field attribute, that gets the final class
							$sClassRecallAttCode = $sAttCode.'_finalclass_recall';
							$oClassRecall = new AttributeExternalField($sClassRecallAttCode, array(
								"allowed_values"  => null,
								"extkey_attcode"  => $sAttCode,
								"target_attcode"  => "finalclass",
								"is_null_allowed" => true,
								"depends_on"      => array(),
							));
							self::AddMagicAttribute($oClassRecall, $sClass, self::$m_aAttribOrigins[$sClass][$sAttCode]);

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

					if (self::IsArchivable($sRemoteClass)) {
						$sCode = $sAttCode.'_archive_flag';
						if ($oAttDef->IsExternalField()) {
							// This is a key, but the value comes from elsewhere
							// Create an external field pointing to the remote attribute
							$sKeyAttCode = $oAttDef->GetKeyAttCode();
							$sRemoteAttCode = $oAttDef->GetExtAttCode().'_archive_flag';
						} else {
							$sKeyAttCode = $sAttCode;
							$sRemoteAttCode = 'archive_flag';
						}
						$oMagic = new AttributeExternalField($sCode, array("allowed_values" => null, "extkey_attcode" => $sKeyAttCode, "target_attcode" => $sRemoteAttCode, "depends_on" => array()));
						self::AddMagicAttribute($oMagic, $sClass, self::$m_aAttribOrigins[$sClass][$sKeyAttCode]);

					}
					if (self::IsObsoletable($sRemoteClass)) {
						$sCode = $sAttCode.'_obsolescence_flag';
						if ($oAttDef->IsExternalField()) {
							// This is a key, but the value comes from elsewhere
							// Create an external field pointing to the remote attribute
							$sKeyAttCode = $oAttDef->GetKeyAttCode();
							$sRemoteAttCode = $oAttDef->GetExtAttCode().'_obsolescence_flag';
						} else {
							$sKeyAttCode = $sAttCode;
							$sRemoteAttCode = 'obsolescence_flag';
						}
						$oMagic = new AttributeExternalField($sCode, array("allowed_values" => null, "extkey_attcode" => $sKeyAttCode, "target_attcode" => $sRemoteAttCode, "depends_on" => array()));
						self::AddMagicAttribute($oMagic, $sClass, self::$m_aAttribOrigins[$sClass][$sKeyAttCode]);
					}
				}
				if ($oAttDef instanceof AttributeMetaEnum) {
					$aMappingData = $oAttDef->GetMapRule($sClass);
					if ($aMappingData != null) {
						$sEnumAttCode = $aMappingData['attcode'];
						self::$m_aEnumToMeta[$sClass][$sEnumAttCode][$sAttCode] = $oAttDef;
					}
				}
			}

			// Add a 'id' filter
			//
			if (array_key_exists('id', self::$m_aAttribDefs[$sClass])) {
				throw new CoreException("Class $sClass, 'id' is a reserved keyword, it cannot be used as an attribute code");
			}
			self::$m_aFilterAttribList[$sClass]['id'] = 'id';
		}
	}

	/**
	 * @param string $sClassName
	 * @param string $sUniquenessRuleId
	 *
	 * @return bool true if one of the parent class (recursive) has the same rule defined
	 * @throws \CoreException
	 */
	private static function HasSameUniquenessRuleInParent($sClassName, $sUniquenessRuleId)
	{
		$sParentClass = self::GetParentClass($sClassName);
		if (empty($sParentClass))
		{
			return false;
		}

		$aParentClassUniquenessRules = self::GetUniquenessRules($sParentClass);
		if (array_key_exists($sUniquenessRuleId, $aParentClassUniquenessRules))
		{
			return true;
		}

		return self::HasSameUniquenessRuleInParent($sParentClass, $sUniquenessRuleId);
	}

	/**
	 * @param array $aUniquenessRuleProperties
	 * @param bool $bRuleOverride if false then control an original declaration validity,
	 *                   otherwise an override validity (can only have the 'disabled' key)
	 * @param string[] $aExistingClassFields if non empty, will check that all fields declared in the rules exists in the class
	 *
	 * @throws \CoreUnexpectedValue if the rule is invalid
	 *
	 * @since 2.6.0 N°659 uniqueness constraint
	 * @since 2.6.1 N°1968 (joli mois de mai...) disallow overrides of 'attributes' properties
	 */
	public static function CheckUniquenessRuleValidity($aUniquenessRuleProperties, $bRuleOverride = true, $aExistingClassFields = array())
	{
		$MANDATORY_ATTRIBUTES = array('attributes');
		$UNIQUENESS_MANDATORY_KEYS_NB = count($MANDATORY_ATTRIBUTES);

		$bHasMissingMandatoryKey = true;
		$iMissingMandatoryKeysNb = $UNIQUENESS_MANDATORY_KEYS_NB;
		/** @var boolean $bHasNonDisabledKeys true if rule contains at least one key that is not 'disabled' */
		$bHasNonDisabledKeys = false;
		$bDisabledKeyValue = null;

		foreach ($aUniquenessRuleProperties as $sUniquenessRuleKey => $aUniquenessRuleProperty)
		{
			if ($sUniquenessRuleKey === 'disabled')
			{
				$bDisabledKeyValue = $aUniquenessRuleProperty;
				if (!is_null($aUniquenessRuleProperty))
				{
					continue;
				}
			}
			if (is_null($aUniquenessRuleProperty))
			{
				continue;
			}

			$bHasNonDisabledKeys = true;

			if (in_array($sUniquenessRuleKey, $MANDATORY_ATTRIBUTES, true)) {
				$iMissingMandatoryKeysNb--;
			}

			if ($sUniquenessRuleKey === 'attributes')
			{
				if (!empty($aExistingClassFields))
				{
					foreach ($aUniquenessRuleProperties[$sUniquenessRuleKey] as $sRuleAttribute)
					{
						if (!in_array($sRuleAttribute, $aExistingClassFields, true))
						{
							throw new CoreUnexpectedValue("Uniqueness rule : non existing field '$sRuleAttribute'");
						}
					}
				}
			}
		}

		if ($iMissingMandatoryKeysNb === 0)
		{
			$bHasMissingMandatoryKey = false;
		}

		if ($bRuleOverride && $bHasNonDisabledKeys)
		{
			throw new CoreUnexpectedValue('Uniqueness rule : only the \'disabled\' key can be overridden');
		}
		if ($bRuleOverride && is_null($bDisabledKeyValue))
		{
			throw new CoreUnexpectedValue('Uniqueness rule : when overriding a rule, value must be set for the \'disabled\' key');
		}
		if (!$bRuleOverride && $bHasMissingMandatoryKey)
		{
			throw new CoreUnexpectedValue('Uniqueness rule : missing mandatory property');
		}
	}

	/**
	 * To be overriden, must be called for any object class (optimization)
	 */
	public static function Init()
	{
		// In fact it is an ABSTRACT function, but this is not compatible with the fact that it is STATIC (error in E_STRICT interpretation)
	}


	/**
	 * @param array $aParams
	 *
	 * @throws \CoreException
	 */
	public static function Init_Params($aParams)
	{
		// Check mandatory params
		// Warning: Do not put image_attcode as a mandatory attribute or it will break all PHP datamodel classes
		$aMandatParams = array(
			"category" => "group classes by modules defining their visibility in the UI",
			"key_type" => "autoincrement | string",
			"name_attcode" => "define which attribute is the class name, may be an array of attributes (format specified in the dictionary as 'Class:myclass/Name' => '%1\$s %2\$s...'",
			"state_attcode" => "define which attribute is representing the state (object lifecycle)",
			"reconc_keys" => "define the attributes that will 'almost uniquely' identify an object in batch processes",
			"db_table" => "database table",
			"db_key_field" => "database field which is the key",
			"db_finalclass_field" => "database field wich is the reference to the actual class of the object, considering that this will be a compound class",
		);

		$sClass = self::GetCallersPHPClass("Init", self::$m_bTraceSourceFiles);

		foreach($aMandatParams as $sParamName => $sParamDesc)
		{
			if (!array_key_exists($sParamName, $aParams))
			{
				throw new CoreException("Declaration of class $sClass - missing parameter $sParamName");
			}
		}

		$aCategories = explode(',', $aParams['category']);
		foreach($aCategories as $sCategory)
		{
			self::$m_Category2Class[$sCategory][] = $sClass;
		}
		self::$m_Category2Class[''][] = $sClass; // all categories, include this one


		self::$m_aRootClasses[$sClass] = $sClass; // first, let consider that I am the root... updated on inheritance
		self::$m_aParentClasses[$sClass] = array();
		self::$m_aChildClasses[$sClass] = array();

		self::$m_aClassParams[$sClass] = $aParams;

		self::$m_aAttribDefs[$sClass] = array();
		self::$m_aAttribOrigins[$sClass] = array();
		self::$m_aFilterAttribList[$sClass] = array();
	}

	/**
	 * @param array $aSource1
	 * @param array $aSource2
	 *
	 * @return array
	 */
	protected static function object_array_mergeclone($aSource1, $aSource2)
	{
		$aRes = array();
		foreach($aSource1 as $key => $object)
		{
			$aRes[$key] = clone $object;
		}
		foreach($aSource2 as $key => $object)
		{
			$aRes[$key] = clone $object;
		}

		return $aRes;
	}

	/**
	 * @param string $sSourceClass
	 */
	public static function Init_InheritAttributes($sSourceClass = null)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		if (empty($sSourceClass)) {
			// Default: inherit from parent class
			$sSourceClass = self::GetParentPersistentClass($sTargetClass);
			if (empty($sSourceClass)) {
				return;
			} // no attributes for the mother of all classes
		}
		if (isset(self::$m_aAttribDefs[$sSourceClass])) {
			if (!isset(self::$m_aAttribDefs[$sTargetClass])) {
				self::$m_aAttribDefs[$sTargetClass] = array();
				self::$m_aAttribOrigins[$sTargetClass] = array();
			}
			self::$m_aAttribDefs[$sTargetClass] = self::object_array_mergeclone(self::$m_aAttribDefs[$sTargetClass], self::$m_aAttribDefs[$sSourceClass]);
			foreach (self::$m_aAttribDefs[$sTargetClass] as $sAttCode => $oAttDef) {
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

	/**
	 * @param string $sClass
	 *
	 * @return bool
	 */
	protected static function Init_IsKnownClass($sClass)
	{
		// Differs from self::IsValidClass()
		// because it is being called before all the classes have been initialized
		if (!class_exists($sClass))
		{
			return false;
		}
		if (!is_subclass_of($sClass, 'DBObject'))
		{
			return false;
		}

		return true;
	}

	/**
	 * @param \AttributeDefinition $oAtt
	 * @param string $sTargetClass
	 *
	 * @throws \Exception
	 */
	public static function Init_AddAttribute(AttributeDefinition $oAtt, $sTargetClass = null)
	{
		if (!$sTargetClass) {
			$sTargetClass = self::GetCallersPHPClass("Init");
		}

		$sAttCode = $oAtt->GetCode();
		if ($sAttCode == 'finalclass') {
			throw new Exception("Declaration of $sTargetClass: using the reserved keyword '$sAttCode' in attribute declaration");
		}
		if ($sAttCode == 'friendlyname') {
			throw new Exception("Declaration of $sTargetClass: using the reserved keyword '$sAttCode' in attribute declaration");
		}
		if (array_key_exists($sAttCode, self::$m_aAttribDefs[$sTargetClass])) {
			throw new Exception("Declaration of $sTargetClass: attempting to redeclare the inherited attribute '$sAttCode', originally declared in ".self::$m_aAttribOrigins[$sTargetClass][$sAttCode]);
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
		elseif ($oAtt->IsExternalKey())
		{
			$sRemoteClass = $oAtt->GetTargetClass();
			if (!self::Init_IsKnownClass($sRemoteClass))
			{
				self::$m_aIgnoredAttributes[$sTargetClass][$oAtt->GetCode()] = $sRemoteClass;
				return;
			}
		}
		elseif ($oAtt->IsExternalField())
		{
			$sExtKeyAttCode = $oAtt->GetKeyAttCode();
			if (isset(self::$m_aIgnoredAttributes[$sTargetClass][$sExtKeyAttCode]))
			{
				// The corresponding external key has already been ignored
				self::$m_aIgnoredAttributes[$sTargetClass][$oAtt->GetCode()] = self::$m_aIgnoredAttributes[$sTargetClass][$sExtKeyAttCode];

				return;
			}
			//TODO Check if the target attribute is still there
			// this is not simple to implement because is involves
			// several passes (the load order has a significant influence on that)
		}

		self::$m_aAttribDefs[$sTargetClass][$oAtt->GetCode()] = $oAtt;
		self::$m_aAttribOrigins[$sTargetClass][$oAtt->GetCode()] = $sTargetClass;
		// Note: it looks redundant to put targetclass there, but a mix occurs when inheritance is used
	}

	/**
	 * @param string $sListCode
	 * @param array $aItems
	 * @param string $sTargetClass
	 */
	public static function Init_SetZListItems($sListCode, $aItems, $sTargetClass = null)
	{
		MyHelpers::CheckKeyInArray('list code', $sListCode, self::$m_aListInfos);

		if (!$sTargetClass) {
			$sTargetClass = self::GetCallersPHPClass("Init");
		}

		// Discard attributes that do not make sense
		// (missing classes in the current module combination, resulting in irrelevant ext key or link set)
		//
		self::Init_CheckZListItems($aItems, $sTargetClass);
		self::$m_aListData[$sTargetClass][$sListCode] = $aItems;
	}

	/**
	 * @param array $aItems
	 * @param string $sTargetClass
	 */
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
			else
			{
				if (isset(self::$m_aIgnoredAttributes[$sTargetClass][$attCode]))
				{
					unset($aItems[$iFoo]);
				}
			}
		}
	}

	/**
	 * @param array $aList
	 *
	 * @return array
	 */
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

	/**
	 * @param string $sStateCode
	 * @param array $aStateDef
	 */
	public static function Init_DefineState($sStateCode, $aStateDef)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		if (is_null($aStateDef['attribute_list']))
		{
			$aStateDef['attribute_list'] = array();
		}

		$sParentState = $aStateDef['attribute_inherit'];
		if (!empty($sParentState))
		{
			// Inherit from the given state (must be defined !)
			//
			$aToInherit = self::$m_aStates[$sTargetClass][$sParentState];

			// Reset the constraint when it was mandatory to set the value at the previous state
			//
			foreach($aToInherit['attribute_list'] as $sState => $iFlags)
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

	/**
	 * @param array $aHighlightScale
	 */
	public static function Init_DefineHighlightScale($aHighlightScale)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		self::$m_aHighlightScales[$sTargetClass] = $aHighlightScale;
	}

	/**
	 * Get the HTML class to apply to the object in the datatables
	 *
	 * @param string $sClass requested for the list (can be abstract)
	 * @param \DBObject $oObject the object to display
	 *
	 * @return string   the class to apply to the object
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 *
	 * @since 3.2.0
	 */
	final public static function GetHilightClass(string $sClass, DBObject $oObject): string
	{
		if (self::IsAbstract($sClass) && self::GetConfig()->Get('list.highlight_abstract_class') === false) {
			return '';
		}

		return $oObject->GetHilightClass();
	}

	/**
	 * @param string $sTargetClass
	 *
	 * @return array
	 */
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

	/**
	 * @param string $sTargetClass
	 * @param string $sStateCode
	 *
	 * @return string
	 */
	public static function GetHighlightCode($sTargetClass, $sStateCode)
	{
		$sCode = '';
		if (array_key_exists($sTargetClass, self::$m_aStates)
			&& array_key_exists($sStateCode, self::$m_aStates[$sTargetClass])
			&& array_key_exists('highlight', self::$m_aStates[$sTargetClass][$sStateCode]))
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

	/**
	 * @param string $sStateCode
	 * @param string $sAttCode
	 * @param int $iFlags
	 */
	public static function Init_OverloadStateAttribute($sStateCode, $sAttCode, $iFlags)
	{
		// Warning: this is not sufficient: the flags have to be copied to the states that are inheriting from this state
		$sTargetClass = self::GetCallersPHPClass("Init");
		self::$m_aStates[$sTargetClass][$sStateCode]['attribute_list'][$sAttCode] = $iFlags;
	}

	/**
	 * @param ObjectStimulus $oStimulus
	 */
	public static function Init_DefineStimulus($oStimulus)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		self::$m_aStimuli[$sTargetClass][$oStimulus->GetCode()] = $oStimulus;

		// I wanted to simplify the syntax of the declaration of objects in the biz model
		// Therefore, the reference to the host class is set there
		$oStimulus->SetHostClass($sTargetClass);
	}

	/**
	 * @param string $sStateCode
	 * @param string $sStimulusCode
	 * @param array $aTransitionDef
	 */
	public static function Init_DefineTransition($sStateCode, $sStimulusCode, $aTransitionDef)
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		if (is_null($aTransitionDef['actions']))
		{
			$aTransitionDef['actions'] = array();
		}
		self::$m_aTransitions[$sTargetClass][$sStateCode][$sStimulusCode] = $aTransitionDef;
	}

	/**
	 * @param string $sSourceClass
	 */
	public static function Init_InheritLifecycle($sSourceClass = '')
	{
		$sTargetClass = self::GetCallersPHPClass("Init");
		if (empty($sSourceClass))
		{
			// Default: inherit from parent class
			$sSourceClass = self::GetParentPersistentClass($sTargetClass);
			if (empty($sSourceClass))
			{
				return;
			} // no attributes for the mother of all classes
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

	/**
	 * @param string $sClass
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public static function GetRootClass($sClass = null)
	{
		self::_check_subclass($sClass);
		return self::$m_aRootClasses[$sClass];
	}

	/**
	 * @param string $sClass
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	public static function IsRootClass($sClass)
	{
		self::_check_subclass($sClass);
		return (self::GetRootClass($sClass) == $sClass);
	}

	/**
	 * @param string $sClass
	 *
	 * @return string
	 */
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

	/**
	 * @param string[] $aClasses
	 *
	 * @return string
	 * @throws \CoreException
	 */
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
	 *
	 * @param string $sClassA
	 * @param string $sClassB
	 *
	 * @return string
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
	 *
	 * @param string $sClass
	 *
	 * @return mixed String = sAttCode or false if the class is not part of a hierarchy
	 * @throws \CoreException
	 */
	public static function IsHierarchicalClass($sClass)
	{
		$sHierarchicalKeyCode = false;
		foreach(self::ListAttributeDefs($sClass) as $sAttCode => $oAtt)
		{
			if ($oAtt->IsHierarchicalKey())
			{
				$sHierarchicalKeyCode = $sAttCode; // Found the hierarchical key, no need to continue
				break;
			}
		}
		return $sHierarchicalKeyCode;
	}

	/**
	 * @return array
	 */
	public static function EnumRootClasses()
	{
		return array_unique(self::$m_aRootClasses);
	}

	/**
	 * @param string $sClass
	 * @param int $iOption
	 * @param bool $bRootFirst
	 *
	 * @return array
	 * @throws \CoreException
	 */
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

	/**
	 * @param string $sClass
	 * @param int $iOption one of ENUM_CHILD_CLASSES_EXCLUDETOP, ENUM_CHILD_CLASSES_ALL
	 * @param bool $bRootFirst Only when $iOption NOT set to ENUM_CHILD_CLASSES_EXCLUDETOP. If true, the $sClass will be the first element of the returned array, otherwise it will be the last (legacy behavior)
	 *
	 * @return array
	 * @throws \CoreException
	 * @since 3.0.0 Added $bRootFirst param.
	 */
	public static function EnumChildClasses($sClass, $iOption = ENUM_CHILD_CLASSES_EXCLUDETOP, $bRootFirst = false)
	{
		self::_check_subclass($sClass);

		$aRes = self::$m_aChildClasses[$sClass];
		if ($iOption != ENUM_CHILD_CLASSES_EXCLUDETOP)
		{
			if ($bRootFirst) {
				// Root class on top
				array_unshift($aRes, $sClass);
			} else {
				// Root class at the end, legacy behavior
				$aRes[] = $sClass;
			}
		}

		return $aRes;
	}

	/**
	 * @return array
	 * @throws \CoreException
	 */
	public static function EnumArchivableClasses()
	{
		$aRes = array();
		foreach(self::GetClasses() as $sClass)
		{
			if (self::IsArchivable($sClass))
			{
				$aRes[] = $sClass;
			}
		}

		return $aRes;
	}

	/**
	 * @param bool $bRootClassesOnly
	 *
	 * @return array
	 * @throws \CoreException
	 */
	public static function EnumObsoletableClasses($bRootClassesOnly = true)
	{
		$aRes = array();
		foreach(self::GetClasses() as $sClass)
		{
			if (self::IsObsoletable($sClass))
			{
				if ($bRootClassesOnly && !static::IsRootClass($sClass))
				{
					continue;
				}
				$aRes[] = $sClass;
			}
		}
		return $aRes;
	}

	/**
	 * @param string $sClass
	 *
	 * @return bool
	 */
	public static function HasChildrenClasses($sClass)
	{
		return (count(self::$m_aChildClasses[$sClass]) > 0);
	}

	/**
	 * @return array
	 */
	public static function EnumCategories()
	{
		return array_keys(self::$m_Category2Class);
	}

	// Note: use EnumChildClasses to take the compound objects into account

	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 */
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

	/**
	 * @param string $sCategories
	 * @param bool $bStrict
	 *
	 * @return array
	 * @throws \CoreException
	 */
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

	/**
	 * @param string $sClass
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	public static function HasTable($sClass)
	{
		if (strlen(self::DBGetTable($sClass)) == 0)
		{
			return false;
		}
		return true;
	}

	/**
	 * @param string $sClass
	 *
	 * @return bool
	 */
	public static function IsAbstract($sClass)
	{
		$oReflection = new ReflectionClass($sClass);
		return $oReflection->isAbstract();
	}

	/**
	 * Normalizes query arguments and adds magic parameters:
	 * - current_contact_id
	 * - current_contact (DBObject)
	 * - current_user (DBObject)
	 *
	 * @param array $aArgs Context arguments (some can be persistent objects)
	 * @param array $aMoreArgs Other query parameters
	 * @param array $aExpectedArgs variables present in the query
	 *
	 * @return array
	 */
	public static function PrepareQueryArguments($aArgs, $aMoreArgs = array(), $aExpectedArgs = null)
	{
		$aScalarArgs = array();
		if (is_null($aExpectedArgs) || count($aExpectedArgs) > 0 || count($aMoreArgs)>0)
		{
			foreach (array_merge($aArgs, $aMoreArgs) as $sArgName => $value)
			{
				if (self::IsValidObject($value)) {
					if (strpos($sArgName, '->object()') === false) {
						// Normalize object arguments
						$aScalarArgs[$sArgName.'->object()'] = $value;
					} else {
						// Leave as is
						$aScalarArgs[$sArgName] = $value;
					}
				} else {
					if (is_scalar($value)) {
						$aScalarArgs[$sArgName] = (string)$value;
					} elseif (is_null($value)) {
						$aScalarArgs[$sArgName] = null;
					} elseif (is_array($value)) {
						$aScalarArgs[$sArgName] = $value;
					}
				}
			}
			return static::AddMagicPlaceholders($aScalarArgs, $aExpectedArgs);
		}
		else
		{
			return array();
		}
	}

	/**
	 * @param array $aPlaceholders The array into which standard placeholders should be added
	 *
	 * @return array of placeholder (or name->object()) => value (or object)
	 */
	public static function AddMagicPlaceholders($aPlaceholders, $aExpectedArgs = null)
	{
		// Add standard magic arguments
		//
		if (is_null($aExpectedArgs))
		{
			$aPlaceholders['current_contact_id'] = UserRights::GetContactId(); // legacy

			$oUser = UserRights::GetUserObject();
			if (!is_null($oUser))
			{
				$aPlaceholders['current_user->object()'] = $oUser;
				$oContact = UserRights::GetContactObject();
				if (!is_null($oContact))
				{
					$aPlaceholders['current_contact->object()'] = $oContact;
				}
			}
		}
		else
		{
			$aCurrentUser = [];
			$aCurrentContact = [];
			foreach ($aExpectedArgs as $expression)
			{
				$aName = explode('->', $expression->GetName());
				if ($aName[0] == 'current_contact_id') {
					$aPlaceholders['current_contact_id'] = UserRights::GetContactId();
				} else if ($aName[0] == 'current_user') {
					array_push($aCurrentUser, $aName[1]);
				} else if ($aName[0] == 'current_contact') {
					array_push($aCurrentContact, $aName[1]);
				}
			}
			if (count($aCurrentUser) > 0) {
				static::FillObjectPlaceholders($aPlaceholders, 'current_user', UserRights::GetUserObject(), $aCurrentUser);
			}
			if (count($aCurrentContact) > 0) {
				static::FillObjectPlaceholders($aPlaceholders, 'current_contact', UserRights::GetContactObject(), $aCurrentContact);
			}
		}

		return $aPlaceholders;
	}

	/**
	 * @since 3.1.1 N°6824
	 * @param array $aPlaceholders
	 * @param string $sPlaceHolderPrefix
	 * @param ?\DBObject $oObject
	 * @param array $aCurrentUser
	 *
	 * @return void
	 *
	 */
	private static function FillObjectPlaceholders(array &$aPlaceholders, string $sPlaceHolderPrefix, ?\DBObject $oObject, array $aCurrentUser) : void {
		$sPlaceHolderKey = $sPlaceHolderPrefix."->object()";
		if (is_null($oObject)){
			$aContext = [
				"current_user_id" => UserRights::GetUserId(),
				"null object type" => $sPlaceHolderPrefix,
				"fields" => $aCurrentUser,
			];
			IssueLog::Warning("Unresolved placeholders due to null object in current context", null,
				$aContext);
			$aPlaceholders[$sPlaceHolderKey] = Dict::Format("Core:Placeholder:CannotBeResolved", $sPlaceHolderKey);
			foreach ($aCurrentUser as $sField) {
				$sPlaceHolderKey = $sPlaceHolderPrefix . "->$sField";
				$aPlaceholders[$sPlaceHolderKey] = Dict::Format("Core:Placeholder:CannotBeResolved", $sPlaceHolderKey);
			}
		} else {
			$aPlaceholders[$sPlaceHolderKey] = $oObject;
			foreach ($aCurrentUser as $sField) {
				$sPlaceHolderKey = $sPlaceHolderPrefix . "->$sField";
				// Mind that the "id" is not viewed as a valid att. code by \MetaModel::IsValidAttCode() so we have to test it manually
				if ($sField !== "id" && false === MetaModel::IsValidAttCode(get_class($oObject), $sField)){
					$aContext = [
						"current_user_id" => UserRights::GetUserId(),
						"obj_class" => get_class($oObject),
						"placeholder" => $sPlaceHolderKey,
						"invalid_field" => $sField,
					];
					IssueLog::Warning("Unresolved placeholder due to invalid attribute", null,
						$aContext);
					$aPlaceholders[$sPlaceHolderKey] = Dict::Format("Core:Placeholder:CannotBeResolved", $sPlaceHolderKey);
					continue;
				}

				$aPlaceholders[$sPlaceHolderKey] = $oObject->Get($sField);
			}
		}
	}

	/**
	 * @param \DBSearch $oFilter
	 *
	 * @return array
	 */
	public static function MakeModifierProperties($oFilter)
	{
		// Compute query modifiers properties (can be set in the search itself, by the context, etc.)
		//
		$aModifierProperties = array();
		/**
		 * @var string $sPluginClass
		 * @var iQueryModifier $oQueryModifier
		 */
		foreach(MetaModel::EnumPlugins('iQueryModifier') as $sPluginClass => $oQueryModifier)
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
	 *
	 * @param int $iId integer The identifier of the parent
	 * @param \AttributeDefinition $oAttDef AttributeDefinition The attribute corresponding to the hierarchical key
	 * @param string The name of the database table containing the hierarchical key
	 *
	 * @throws \MySQLException
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
				$iMyRight = $aRes[0]['max'] + 1;
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
		return array($oAttDef->GetSQLRight() => $iMyRight + 1, $oAttDef->GetSQLLeft() => $iMyRight);
	}

	/**
	 * Special processing for the hierarchical keys stored as nested sets: temporary remove the branch
	 *
	 * @param int $iMyLeft
	 * @param int $iMyRight
	 * @param \AttributeDefinition $oAttDef AttributeDefinition The attribute corresponding to the hierarchical key
	 * @param string The name of the database table containing the hierarchical key
	 *
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
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
	 *
	 * @param integer $iNewLeft
	 * @param integer $iNewRight
	 * @param \AttributeDefinition $oAttDef AttributeDefinition The attribute corresponding to the hierarchical key
	 * @param string $sTable string The name of the database table containing the hierarchical key
	 *
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
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
	 *
	 * @param bool $bDiagnosticsOnly If true only a diagnostic pass will be run, returning true or false
	 * @param bool $bVerbose Displays some information about what is done/what needs to be done
	 * @param bool $bForceComputation If true, the _left and _right parameters will be recomputed even if some
	 *     values already exist in the DB
	 *
	 * @return bool
	 * @throws \CoreException
	 */
	public static function CheckHKeys(bool $bDiagnosticsOnly = false, bool $bVerbose = false, bool $bForceComputation = false)
	{
		$bChangeNeeded = false;
		foreach(self::GetClasses() as $sClass)
		{
			if (!self::HasTable($sClass))
			{
				continue;
			}

			foreach(self::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				// Check (once) all the attributes that are hierarchical keys
				if ((self::GetAttributeOrigin($sClass, $sAttCode) == $sClass) && $oAttDef->IsHierarchicalKey())
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
	 *
	 * @param string $sClass Name of the class to process
	 * @param string $sAttCode Code of the attribute to process
	 * @param boolean $bDiagnosticsOnly If true only a diagnostic pass will be run, returning true or false
	 * @param boolean $bVerbose Displays some information about what is done/what needs to be done
	 * @param boolean $bForceComputation If true, the _left and _right parameters will be recomputed even if some
	 *     values already exist in the DB
	 *
	 * @return boolean true if an update is needed (diagnostics only) / was performed
	 * @throws \Exception
	 * @throws \CoreException
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
				catch (Exception $e)
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
	 *
	 * @param string $sTable
	 * @param string $sAttCode
	 * @param \AttributeDefinition $oAttDef
	 * @param int $iId
	 * @param int $iCurrIndex
	 *
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	protected static function HKInitChildren($sTable, $sAttCode, $oAttDef, $iId, &$iCurrIndex)
	{
		$sSQL = "SELECT id FROM `$sTable` WHERE `$sAttCode` = $iId";
		$aRes = CMDBSource::QueryToArray($sSQL);
		$sLeft = $oAttDef->GetSQLLeft();
		$sRight = $oAttDef->GetSQLRight();
		foreach($aRes as $aValues)
		{
			$iChildId = $aValues['id'];
			$iLeft = $iCurrIndex++;
			self::HKInitChildren($sTable, $sAttCode, $oAttDef, $iChildId, $iCurrIndex);
			$iRight = $iCurrIndex++;
			$sSQL = "UPDATE `$sTable` SET `$sLeft` = $iLeft, `$sRight` = $iRight WHERE id= $iChildId";
			CMDBSource::Query($sSQL);
		}
	}

	/**
	 * Update the meta enums
	 *
	 * @param boolean $bVerbose Displays some information about what is done/what needs to be done
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 *
	 * @see AttributeMetaEnum::MapValue that must be aligned with the above implementation
	 */
	public static function RebuildMetaEnums($bVerbose = false)
	{
		foreach(self::GetClasses() as $sClass)
		{
			if (!self::HasTable($sClass))
			{
				continue;
			}

			foreach(self::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				// Check (once) all the attributes that are hierarchical keys
				if ((self::GetAttributeOrigin($sClass, $sAttCode) == $sClass) && $oAttDef instanceof AttributeEnum)
				{
					if (isset(self::$m_aEnumToMeta[$sClass][$sAttCode]))
					{
						foreach(self::$m_aEnumToMeta[$sClass][$sAttCode] as $sMetaAttCode => $oMetaAttDef)
						{
							$aMetaValues = array(); // array of (metavalue => array of values)
							foreach($oAttDef->GetAllowedValues() as $sCode => $sLabel)
							{
								$aMappingData = $oMetaAttDef->GetMapRule($sClass);
								if ($aMappingData == null)
								{
									$sMetaValue = $oMetaAttDef->GetDefaultValue();
								}
								else
								{
									if (array_key_exists($sCode, $aMappingData['values']))
									{
										$sMetaValue = $aMappingData['values'][$sCode];
									}
									elseif ($oMetaAttDef->GetDefaultValue() != '')
									{
										$sMetaValue = $oMetaAttDef->GetDefaultValue();
									}
									else
									{
										throw new Exception('MetaModel::RebuildMetaEnums(): mapping not found for value "'.$sCode.'"" in '.$sClass.', on attribute '.self::GetAttributeOrigin($sClass, $oMetaAttDef->GetCode()).'::'.$oMetaAttDef->GetCode());
									}
								}
								$aMetaValues[$sMetaValue][] = $sCode;
							}
							foreach($aMetaValues as $sMetaValue => $aEnumValues)
							{
								$sMetaTable = self::DBGetTable($sClass, $sMetaAttCode);
								$sEnumTable = self::DBGetTable($sClass);
								$aColumns = array_keys($oMetaAttDef->GetSQLColumns());
								$sMetaColumn = reset($aColumns);
								$aColumns = array_keys($oAttDef->GetSQLColumns());
								$sEnumColumn = reset($aColumns);
								$sValueList = implode(', ', CMDBSource::Quote($aEnumValues));
								$sSql = "UPDATE `$sMetaTable` JOIN `$sEnumTable` ON `$sEnumTable`.id = `$sMetaTable`.id SET `$sMetaTable`.`$sMetaColumn` = '$sMetaValue' WHERE `$sEnumTable`.`$sEnumColumn` IN ($sValueList) AND `$sMetaTable`.`$sMetaColumn` != '$sMetaValue'";
								if ($bVerbose)
								{
									echo "Executing query: $sSql\n";
								}
								CMDBSource::Query($sSql);
							}
						}
					}
				}
			}
		}
	}


	/**
	 * @param boolean $bDiagnostics
	 * @param boolean $bVerbose
	 *
	 * @return bool
	 * @throws \OQLException
	 */
	public static function CheckDataSources($bDiagnostics, $bVerbose)
	{
		$sOQL = 'SELECT SynchroDataSource';
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL($sOQL));
		$bFixNeeded = false;
		if ($bVerbose && $oSet->Count() == 0)
		{
			echo "There are no Data Sources in the database.\n";
		}
		while ($oSource = $oSet->Fetch())
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

	/**
	 * @param array $aAliases
	 * @param string $sNewName
	 * @param string $sRealName
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public static function GenerateUniqueAlias(&$aAliases, $sNewName, $sRealName)
	{
		if (!array_key_exists($sNewName, $aAliases))
		{
			$aAliases[$sNewName] = $sRealName;
			return $sNewName;
		}

		for($i = 1; $i < 100; $i++)
		{
			$sAnAlias = $sNewName.$i;
			if (!array_key_exists($sAnAlias, $aAliases))
			{
				// Create that new alias
				$aAliases[$sAnAlias] = $sRealName;
				return $sAnAlias;
			}
		}
		throw new CoreException('Failed to create an alias', array('aliases' => $aAliases, 'new' => $sNewName));
	}

	/**
	 * @param bool $bExitOnError
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public static function CheckDefinitions($bExitOnError = true)
	{
		if (count(self::GetClasses()) == 0)
		{
			throw new CoreException("MetaModel::InitClasses() has not been called, or no class has been declared ?!?!");
		}

		$aErrors = array();
		$aSugFix = array();
		foreach(self::GetClasses() as $sClass)
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
				if (!self::IsValidAttCode($sClass, $sAttCode))
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
			foreach(self::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				// It makes no sense to check the attributes again and again in the subclasses
				if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass)
				{
					continue;
				}

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
				else
				{
					if ($oAttDef->IsLinkSet())
					{
						// Do nothing...
					}
					else
					{
						if ($oAttDef instanceof AttributeStopWatch)
						{
							$aThresholds = $oAttDef->ListThresholds();
							if (is_array($aThresholds))
							{
								foreach($aThresholds as $iPercent => $aDef)
								{
									if (array_key_exists('highlight', $aDef))
									{
										if (!array_key_exists('code', $aDef['highlight']))
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
					}
				}
				// Check dependencies
				if ($oAttDef->IsWritable())
				{
					$bHasWritableAttribute = true;
					foreach($oAttDef->GetPrerequisiteAttributes() as $sDependOnAttCode)
					{
						if (!self::IsValidAttCode($sClass, $sDependOnAttCode))
						{
							$aErrors[$sClass][] = "Unknown attribute code '".$sDependOnAttCode."' in the list of prerequisite attributes";
							$aSugFix[$sClass][] = "Expecting a value in ".implode(", ", self::GetAttributesList($sClass));
						}
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
					foreach(self::EnumStates($sClass) as $sStateCode => $aStateDef)
					{
						foreach(self::EnumTransitions($sClass, $sStateCode) as $sStimulusCode => $aTransitionDef)
						{
							foreach($aTransitionDef['actions'] as $actionHandler)
							{
								if (is_string($actionHandler))
								{
									if (!method_exists($sClass, $actionHandler))
									{
										$aErrors[$sClass][] = "Unknown function '$actionHandler' in transition [$sStateCode/$sStimulusCode] for state attribute '$sStateAttCode'";
										$aSugFix[$sClass][] = "Specify a function which prototype is in the form [public function $actionHandler(\$sStimulusCode){return true;}]";
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
							if (!array_key_exists('code', $aStateDef['highlight']))
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
				foreach(self::FlattenZList(self::GetZListItems($sClass, $sListCode)) as $sMyAttCode)
				{
					if (!self::IsValidAttCode($sClass, $sMyAttCode))
					{
						$aErrors[$sClass][] = "Unknown attribute code '".$sMyAttCode."' from ZList '$sListCode'";
						$aSugFix[$sClass][] = "Expecting a value in {".implode(", ", self::GetAttributesList($sClass))."}";
					}
				}
			}

			// Check SQL columns uniqueness
			//
			if (self::HasTable($sClass))
			{
				$aTableColumns = array(); // array of column => attcode (the column is used by this attribute)
				$aTableColumns[self::DBGetKey($sClass)] = 'id';

				// Check that SQL columns are declared only once
				//
				foreach(self::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
				{
					// Skip this attribute if not originally defined in this class
					if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass)
					{
						continue;
					}

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
			foreach($aErrors as $sClass => $aMessages)
			{
				echo "<p>Wrong declaration for class <b>$sClass</b></p>\n";
				echo "<ul >\n";
				$i = 0;
				foreach($aMessages as $sMsg)
				{
					echo "<li>$sMsg ({$aSugFix[$sClass][$i]})</li>\n";
					$i++;
				}
				echo "</ul>\n";
			}
			if ($bExitOnError)
			{
				echo "<p>Aborting...</p>\n";
			}
			echo "</div>\n";
			if ($bExitOnError)
			{
				exit;
			}
		}
	}

	/**
	 * @param string $sRepairUrl
	 * @param string $sSQLStatementArgName
	 * @param string[] $aSQLFixes
	 */
	public static function DBShowApplyForm($sRepairUrl, $sSQLStatementArgName, $aSQLFixes)
	{
		if (empty($sRepairUrl)) {
			return;
		}

		// By design, some queries might be blank, we have to ignore them
		$aCleanFixes = array();
		foreach ($aSQLFixes as $sSQLFix) {
			if (!empty($sSQLFix)) {
				$aCleanFixes[] = $sSQLFix;
			}
		}
		if (count($aCleanFixes) == 0) {
			return;
		}

		echo "<form action=\"$sRepairUrl\" method=\"POST\">\n";
		echo "   <input type=\"hidden\" name=\"$sSQLStatementArgName\" value=\"".utils::EscapeHtml(implode("##SEP##", $aCleanFixes))."\">\n";
		echo "   <input type=\"submit\" value=\" Apply changes (".count($aCleanFixes)." queries) \">\n";
		echo "</form>\n";
	}

	/**
	 * @param bool $bMustBeComplete
	 *
	 * @return bool returns true if at least one table exists
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	public static function DBExists($bMustBeComplete = true)
	{
		if (!CMDBSource::IsDB(self::$m_sDBName))
		{
			return false;
		}
		CMDBSource::SelectDB(self::$m_sDBName);

		$aFound = array();
		$aMissing = array();
		foreach(self::DBEnumTables() as $sTable => $aClasses)
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
				return !$bMustBeComplete;
			}
		}
	}

	/**
	 * Do drop only tables corresponding to the sub-database (table prefix)
	 * then possibly drop the DB itself (if no table remain)
	 */
	public static function DBDrop()
	{
		$bDropEntireDB = true;

		if (!empty(self::$m_sTablePrefix))
		{
			foreach(self::DBEnumTables() as $sTable)
			{
				// perform a case-insensitive test because on Windows the table names become lowercase :-(
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


	/**
	 * @param callable $aCallback
	 *
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \CoreException
	 * @throws \Exception
	 */
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

	/**
	 * @param callable $aCallback
	 *
	 * @throws \CoreException
	 */
	protected static function DBCreateTables($aCallback = null)
	{
		[$aErrors, $aSugFix, $aCondensedQueries] = self::DBCheckFormat();

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

	/**
	 * @throws \CoreException
	 * @throws \Exception
	 * @throws \MissingQueryArgument
	 */
	protected static function DBCreateViews()
	{
		[$aErrors, $aSugFix] = self::DBCheckViews();

		foreach($aSugFix as $sClass => $aTarget)
		{
			foreach($aTarget as $aQueries)
			{
				foreach($aQueries as $sQuery)
				{
					if (!empty($sQuery))
					{
						// forces a refresh of cached information
						CMDBSource::CreateTable($sQuery);
					}
				}
			}
		}
	}

	/**
	 * @return array
	 * @throws \CoreException
	 * @throws \MySQLException
	 */
	public static function DBDump()
	{
		$aDataDump = array();
		foreach(self::DBEnumTables() as $sTable => $aClasses)
		{
			$aRows = CMDBSource::DumpTable($sTable);
			$aDataDump[$sTable] = $aRows;
		}
		return $aDataDump;
	}

	/**
	 * Determines whether the target DB is frozen or not
	 *
	 * @return bool
	 */
	public static function DBIsReadOnly()
	{
		// Improvement: check the mySQL variable -> Read-only

		if (utils::IsArchiveMode())
		{
			return true;
		}
		if (UserRights::IsAdministrator())
		{
			return (!self::DBHasAccess(ACCESS_ADMIN_WRITE));
		}
		else
		{
			return (!self::DBHasAccess(ACCESS_USER_WRITE));
		}
	}

	/**
	 * @param int $iRequested
	 *
	 * @return bool
	 */
	public static function DBHasAccess($iRequested = ACCESS_FULL)
	{
		$iMode = self::$m_oConfig->Get('access_mode');
		if (($iMode & $iRequested) == 0)
		{
			return false;
		}

		return true;
	}

	/**
	 * @param string $sKey
	 * @param string $sValueFromOldSystem
	 * @param string $sDefaultValue
	 * @param boolean $bNotInDico
	 *
	 * @return string
	 * @throws \DictExceptionMissingString
	 */
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

	/**
	 * @param string $sModules
	 * @param string $sOutputFilter
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public static function MakeDictionaryTemplate($sModules = '', $sOutputFilter = 'NotInDictionary')
	{
		$sRes = '';

		$sRes .= "// Dictionnay conventions\n";
		$sRes .= utils::EscapeHtml("// Class:<class_name>\n");
		$sRes .= utils::EscapeHtml("// Class:<class_name>+\n");
		$sRes .= utils::EscapeHtml("// Class:<class_name>/Attribute:<attribute_code>\n");
		$sRes .= utils::EscapeHtml("// Class:<class_name>/Attribute:<attribute_code>+\n");
		$sRes .= utils::EscapeHtml("// Class:<class_name>/Attribute:<attribute_code>/Value:<value>\n");
		$sRes .= utils::EscapeHtml("// Class:<class_name>/Attribute:<attribute_code>/Value:<value>+\n");
		$sRes .= utils::EscapeHtml("// Class:<class_name>/Stimulus:<stimulus_code>\n");
		$sRes .= utils::EscapeHtml("// Class:<class_name>/Stimulus:<stimulus_code>+\n");
		$sRes .= "\n";

		// Note: I did not use EnumCategories(), because a given class maybe found in several categories
		// Need to invent the "module", to characterize the origins of a class
		if (strlen($sModules) == 0) {
			$aModules = array('bizmodel', 'core/cmdb', 'gui', 'application', 'addon/userrights');
		} else {
			$aModules = explode(', ', $sModules);
		}

		$sRes .= "//////////////////////////////////////////////////////////////////////\n";
		$sRes .= "// Note: The classes have been grouped by categories: ".implode(', ', $aModules)."\n";
		$sRes .= "//////////////////////////////////////////////////////////////////////\n";

		foreach ($aModules as $sCategory) {
			$sRes .= "//////////////////////////////////////////////////////////////////////\n";
			$sRes .= "// Classes in '<em>$sCategory</em>'\n";
			$sRes .= "//////////////////////////////////////////////////////////////////////\n";
			$sRes .= "//\n";
			$sRes .= "\n";
			foreach (self::GetClasses($sCategory) as $sClass) {
				if (!self::HasTable($sClass)) {
					continue;
				}

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
					// Skip this attribute if not originally defined in this class
					if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass)
					{
						continue;
					}

					$sClassRes .= self::MakeDictEntry("Class:$sClass/Attribute:$sAttCode", $oAttDef->GetLabel_Obsolete(), $sAttCode, $bNotInDico);
					$sClassRes .= self::MakeDictEntry("Class:$sClass/Attribute:$sAttCode+", $oAttDef->GetDescription_Obsolete(), '', $bNotInDico);
					if ($oAttDef instanceof AttributeEnum)
					{
						if (self::GetStateAttributeCode($sClass) == $sAttCode)
						{
							foreach(self::EnumStates($sClass) as $sStateCode => $aStateData)
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
							foreach($oAttDef->GetAllowedValues() as $sKey => $value)
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

				if ($bNotInDico || ($sOutputFilter != 'NotInDictionary'))
				{
					$sRes .= $sClassRes;
				}
			}
		}

		return $sRes;
	}


	/**
	 * @return array
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function DBCheckFormat()
	{
		$aErrors = array();
		$aSugFix = array();

		$sAlterDBMetaData = CMDBSource::DBCheckCharsetAndCollation();

		// A new way of representing things to be done - quicker to execute !
		$aCreateTable = array(); // array of <table> => <table options>
		$aCreateTableItems = array(); // array of <table> => array of <create definition>
		$aAlterTableMetaData = array();
		$aAlterTableItems = array(); // array of <table> => <alter specification>
		$aPostTableAlteration = array(); // array of <table> => post alteration queries

		foreach(self::GetClasses() as $sClass)
		{
			if (!self::HasTable($sClass))
			{
				continue;
			}

			// Check that the table exists
			//
			$sTable = self::DBGetTable($sClass);
			$aSugFix[$sClass]['*First'] = array();

			$aTableInfo = CMDBSource::GetTableInfo($sTable);

			$bTableToCreate = false;
			$sKeyField = self::DBGetKey($sClass);
			$sDbCharset = DEFAULT_CHARACTER_SET;
			$sDbCollation = DEFAULT_COLLATION;
			$sAutoIncrement = (self::IsAutoIncrementKey($sClass) ? "AUTO_INCREMENT" : "");
			$sKeyFieldDefinition = "`$sKeyField` INT(11) NOT NULL $sAutoIncrement PRIMARY KEY";
			$aTableInfo['Indexes']['PRIMARY']['used'] = true;
			if (!CMDBSource::IsTable($sTable))
			{
				$bTableToCreate = true;
				$aErrors[$sClass]['*'][] = "table '$sTable' could not be found in the DB";
				$aSugFix[$sClass]['*'][] = "CREATE TABLE `$sTable` ($sKeyFieldDefinition) ENGINE = ".MYSQL_ENGINE." CHARACTER SET $sDbCharset COLLATE $sDbCollation";
				$aCreateTable[$sTable] = "ENGINE = ".MYSQL_ENGINE." CHARACTER SET $sDbCharset COLLATE $sDbCollation";
				$aCreateTableItems[$sTable][$sKeyField] = $sKeyFieldDefinition;
			}
			// Check that the key field exists
			//
			elseif (!CMDBSource::IsField($sTable, $sKeyField))
			{
				$aErrors[$sClass]['id'][] = "key '$sKeyField' (table $sTable) could not be found";
				$aSugFix[$sClass]['id'][] = "ALTER TABLE `$sTable` ADD $sKeyFieldDefinition";
				if (!$bTableToCreate)
				{
					$aAlterTableItems[$sTable]['field'][$sKeyField] = "ADD $sKeyFieldDefinition";
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
					if (!$bTableToCreate)
					{
						$aAlterTableItems[$sTable]['field'][$sKeyField] = "CHANGE `$sKeyField` $sKeyFieldDefinition";
					}
				}
				if (self::IsAutoIncrementKey($sClass) && !CMDBSource::IsAutoIncrement($sTable, $sKeyField))
				{
					$aErrors[$sClass]['id'][] = "key '$sKeyField' (table $sTable) is not automatically incremented";
					$aSugFix[$sClass]['id'][] = "ALTER TABLE `$sTable` CHANGE `$sKeyField` $sKeyFieldDefinition";
					if (!$bTableToCreate)
					{
						$aAlterTableItems[$sTable]['field'][$sKeyField] = "CHANGE `$sKeyField` $sKeyFieldDefinition";
					}
				}
			}

			if (!$bTableToCreate)
			{
				$sAlterTableMetaDataQuery = CMDBSource::DBCheckTableCharsetAndCollation($sTable);
				if (!empty($sAlterTableMetaDataQuery))
				{
					$aAlterTableMetaData[$sTable] = $sAlterTableMetaDataQuery;
				}
			}

			// Check that any defined field exists
			//
			$aTableInfo['Fields'][$sKeyField]['used'] = true;
			$aFriendlynameAttcodes = self::GetFriendlyNameAttributeCodeList($sClass);
			foreach(self::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if (!$oAttDef->CopyOnAllTables())
				{
					// Skip this attribute if not originally defined in this class
					if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass)
					{
						continue;
					}
				}
				foreach($oAttDef->GetSQLColumns(true) as $sField => $sDBFieldSpec)
				{
					// Keep track of columns used by iTop
					$aTableInfo['Fields'][$sField]['used'] = true;

					$bIndexNeeded = $oAttDef->RequiresIndex();
					$bFullTextIndexNeeded = false;
					if (!$bIndexNeeded)
					{
						// Add an index on the columns of the friendlyname
						if (in_array($sField, $aFriendlynameAttcodes))
						{
							$bIndexNeeded = true;
						}
					}
					else
					{
						if ($oAttDef->RequiresFullTextIndex())
						{
							$bFullTextIndexNeeded = true;
						}
					}

					$sFieldDefinition = "`$sField` $sDBFieldSpec";
					if (!CMDBSource::IsField($sTable, $sField))
					{
						$aErrors[$sClass][$sAttCode][] = "field '$sField' could not be found in table '$sTable'";
						$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` ADD $sFieldDefinition";

						if ($bTableToCreate)
						{
							$aCreateTableItems[$sTable][$sField] = $sFieldDefinition;
						}
						else
						{
							$aAlterTableItems[$sTable]['field'][$sField] = "ADD $sFieldDefinition";
							$aAdditionalRequests = self::GetAdditionalRequestAfterAlter($sClass, $sTable, $sField);
							if (!empty($aAdditionalRequests))
							{
								foreach ($aAdditionalRequests as $sAdditionalRequest)
								{
									$aPostTableAlteration[$sTable][] = $sAdditionalRequest;
								}
							}
						}

						if ($bIndexNeeded)
						{
							$aTableInfo['Indexes'][$sField]['used'] = true;
							$sIndexName = $sField;
							$sColumns = '`'.$sField.'`';

							if ($bFullTextIndexNeeded)
							{
								$sIndexType = 'FULLTEXT INDEX';
							}
							else
							{
								$sIndexType = 'INDEX';
								$aColumns = array($sField);
								$aLength = self::DBGetIndexesLength($sClass, $aColumns, $aTableInfo);
								if (!is_null($aLength[0]))
								{
									$sColumns .= ' ('.$aLength[0].')';
								}
							}
							$sSugFix = "ALTER TABLE `$sTable` ADD $sIndexType `$sIndexName` ($sColumns)";
							$aSugFix[$sClass][$sAttCode][] = $sSugFix;
							if ($bFullTextIndexNeeded)
							{
								// MySQL does not support multi fulltext index creation in a single query (mysql_errno = 1795)
								$aPostTableAlteration[$sTable][] = $sSugFix;
							}
							elseif ($bTableToCreate)
							{
								$aCreateTableItems[$sTable][] = "$sIndexType `$sIndexName` ($sColumns)";
							}
							else
							{
								$aAlterTableItems[$sTable]['index'][] = "ADD $sIndexType `$sIndexName` ($sColumns)";
							}
						}

					}
					else
					{
						// Create indexes (external keys only... so far)
						// (drop before change, add after change)
						$sSugFixAfterChange = '';
						$sAlterTableItemsAfterChange = '';
						if ($bIndexNeeded)
						{
							$aTableInfo['Indexes'][$sField]['used'] = true;

							if ($bFullTextIndexNeeded)
							{
								$sIndexType = 'FULLTEXT INDEX';
								$aColumns = null;
								$aLength = null;
							}
							else
							{
								$sIndexType = 'INDEX';
								$aColumns = array($sField);
								$aLength = self::DBGetIndexesLength($sClass, $aColumns, $aTableInfo);
							}

							if (!CMDBSource::HasIndex($sTable, $sField, $aColumns, $aLength))
							{
								$sIndexName = $sField;
								$sColumns = '`'.$sField.'`';
								if (isset($aLength[0]))
								{
									$sColumns .= ' ('.$aLength[0].')';
								}

								$aErrors[$sClass][$sAttCode][] = "Foreign key '$sField' in table '$sTable' should have an index";
								if (CMDBSource::HasIndex($sTable, $sField))
								{
									$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` DROP INDEX `$sIndexName`";
									$aAlterTableItems[$sTable]['index'][] = "DROP INDEX `$sIndexName`";
								}
								$sSugFixAfterChange = "ALTER TABLE `$sTable` ADD $sIndexType `$sIndexName` ($sColumns)";
								$sAlterTableItemsAfterChange = "ADD $sIndexType `$sIndexName` ($sColumns)";
							}
						}

						// The field already exists, does it have the relevant properties?
						//
						$sActualFieldSpec = CMDBSource::GetFieldSpec($sTable, $sField);
						if (!CMDBSource::IsSameFieldTypes($sDBFieldSpec, $sActualFieldSpec))
						{
							$aErrors[$sClass][$sAttCode][] = "field '$sField' in table '$sTable' has a wrong type: found <code>$sActualFieldSpec</code> while expecting <code>$sDBFieldSpec</code>";
							$aSugFix[$sClass][$sAttCode][] = "ALTER TABLE `$sTable` CHANGE `$sField` $sFieldDefinition";
							$aAlterTableItems[$sTable]['field'][$sField] = "CHANGE `$sField` $sFieldDefinition";
						}

						// Create indexes (external keys only... so far)
						//
						if (!empty($sSugFixAfterChange))
						{
							$aSugFix[$sClass][$sAttCode][] = $sSugFixAfterChange;
							if ($bFullTextIndexNeeded)
							{
								// MySQL does not support multi fulltext index creation in a single query (mysql_errno = 1795)
								$aPostTableAlteration[$sTable][] = $sSugFixAfterChange;
							}
							else
							{
								$aAlterTableItems[$sTable]['index'][] = $sAlterTableItemsAfterChange;
							}
						}
					}
				}
			}

			// Check indexes
			foreach(self::DBGetIndexes($sClass) as $aColumns)
			{
				$sIndexId = implode('_', $aColumns);

				if (isset($aTableInfo['Indexes'][$sIndexId]['used']) && $aTableInfo['Indexes'][$sIndexId]['used'])
				{
					continue;
				}

				$aLength = self::DBGetIndexesLength($sClass, $aColumns, $aTableInfo);
				$aTableInfo['Indexes'][$sIndexId]['used'] = true;

				if (!CMDBSource::HasIndex($sTable, $sIndexId, $aColumns, $aLength))
				{
					$sColumns = '';

					for ($i = 0; $i < count($aColumns); $i++)
					{
						if (!empty($sColumns))
						{
							$sColumns .= ', ';
						}
						$sColumns .= '`'.$aColumns[$i].'`';
						if (!is_null($aLength[$i]))
						{
							$sColumns .= ' ('.$aLength[$i].')';
						}
					}
					if (CMDBSource::HasIndex($sTable, $sIndexId))
					{
						$aErrors[$sClass]['*'][] = "Wrong index '$sIndexId' ($sColumns) in table '$sTable'";
						$aSugFix[$sClass]['*First'][] = "ALTER TABLE `$sTable` DROP INDEX `$sIndexId`";
						$aSugFix[$sClass]['*'][] = "ALTER TABLE `$sTable` ADD INDEX `$sIndexId` ($sColumns)";
					}
					else
					{
						$aErrors[$sClass]['*'][] = "Missing index '$sIndexId' ($sColumns) in table '$sTable'";
						$aSugFix[$sClass]['*'][] = "ALTER TABLE `$sTable` ADD INDEX `$sIndexId` ($sColumns)";
					}
					if ($bTableToCreate)
					{
						$aCreateTableItems[$sTable][] = "INDEX `$sIndexId` ($sColumns)";
					}
					else
					{
						if (CMDBSource::HasIndex($sTable, $sIndexId))
						{
							// Add the drop before CHARSET alteration
							if (!isset($aAlterTableItems[$sTable]))
							{
								$aAlterTableItems[$sTable] = array();
							}
							if (isset($aAlterTableItems[$sTable]['index']))
							{
								array_unshift($aAlterTableItems[$sTable]['index'], "DROP INDEX `$sIndexId`");
							}
						}
						$aAlterTableItems[$sTable]['index'][] = "ADD INDEX `$sIndexId` ($sColumns)";
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
						$aAlterTableItems[$sTable]['field'][$sField] = "CHANGE `$sField` $sFieldDefinition";
					}
					$aSugFix[$sClass][$sAttCode][] = "-- Recommended action: ALTER TABLE `$sTable` DROP `$sField`";
				}
			}

			// Find out unused indexes
			//
			foreach($aTableInfo['Indexes'] as $sIndexId => $aIndexData)
			{
				if (!isset($aIndexData['used']) || !$aIndexData['used'])
				{
					$aErrors[$sClass]['*'][] = "Index '$sIndexId' in table '$sTable' is not used and will be removed";
					$aSugFix[$sClass]['*First'][] = "ALTER TABLE `$sTable` DROP INDEX `$sIndexId`";
					// Add the drop before CHARSET alteration
					if (!isset($aAlterTableItems[$sTable]))
					{
						$aAlterTableItems[$sTable] = array();
					}
					if (isset($aAlterTableItems[$sTable]['index']))
					{
						array_unshift($aAlterTableItems[$sTable]['index'], "DROP INDEX `$sIndexId`");
					}
				}
			}

			if (empty($aSugFix[$sClass]['*First'])) unset($aSugFix[$sClass]['*First']);
		}

		$aCondensedQueries = array();
		if (!empty($sAlterDBMetaData))
		{
			$aCondensedQueries[] = $sAlterDBMetaData;
		}
		foreach($aCreateTable as $sTable => $sTableOptions)
		{
			$sTableItems = implode(', ', $aCreateTableItems[$sTable]);
			$aCondensedQueries[] = "CREATE TABLE `$sTable` ($sTableItems) $sTableOptions";
			// Add request right after the CREATE TABLE
			if (isset($aPostTableAlteration[$sTable]))
			{
				foreach ($aPostTableAlteration[$sTable] as $sQuery)
				{
					$aCondensedQueries[] = $sQuery;
				}
				unset($aPostTableAlteration[$sTable]);
			}
		}
		foreach ($aAlterTableMetaData as $sTableAlterQuery)
		{
			$aCondensedQueries[] = $sTableAlterQuery;
		}
		foreach ($aAlterTableItems as $sTable => $aChangeList)
		{
			if (isset($aAlterTableItems[$sTable]['field']))
			{
				$sChangeList = implode(', ', $aChangeList['field']);
				$aCondensedQueries[] = "ALTER TABLE `$sTable` $sChangeList";
			}
			if (isset($aAlterTableItems[$sTable]['index']))
			{
				$sChangeList = implode(', ', $aChangeList['index']);
				$aCondensedQueries[] = "ALTER TABLE `$sTable` $sChangeList";
			}
			// Add request right after the ALTER TABLE
			if (isset($aPostTableAlteration[$sTable]))
			{
				foreach ($aPostTableAlteration[$sTable] as $sQuery)
				{
					$aCondensedQueries[] = $sQuery;
				}
				unset($aPostTableAlteration[$sTable]);
            }
		}

		// Add alterations not yet managed
		foreach ($aPostTableAlteration as $aQueries)
		{
			foreach ($aQueries as $sQuery)
			{
				$aCondensedQueries[] = $sQuery;
			}
		}

		return array($aErrors, $aSugFix, $aCondensedQueries);
	}


	/**
	 * @deprecated 2.7.0 N°2369 Method will not be removed any time soon as we still need to drop view if the instance is migrating from an iTop 2.x to an iTop 3.0 or newer, even if they skip iTop 3.0.
	 * @since 3.0.0 Does not recreate SQL views, only drops them. Method has not been renamed to avoid regressions
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \Exception
	 * @throws \MissingQueryArgument
	 */
	public static function DBCheckViews()
	{
		$aErrors = array();
		$aSugFix = array();

		// Reporting views (must be created after any other table)
		//
		foreach(self::GetClasses() as $sClass)
		{
			$sView = self::DBGetView($sClass);
			if (CMDBSource::IsTable($sView))
			{
				// Remove deprecated views
				$aErrors[$sClass]['*'][] = "Remove view '$sView' (deprecated, consider installing combodo-views if needed)";
				$aSugFix[$sClass]['*'][] = "DROP VIEW `$sView`";
			}
		}

		return array($aErrors, $aSugFix);
	}

	/**
	 * @param string $sSelWrongRecs
	 * @param string $sErrorDesc
	 * @param string $sClass
	 * @param array $aErrorsAndFixes
	 * @param int $iNewDelCount
	 * @param array $aPlannedDel
	 * @param bool $bProcessingFriends
	 *
	 * @throws \CoreException
	 */
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
		if (count($aWrongRecords) == 0)
		{
			return;
		}

		if (!array_key_exists($sRootClass, $aErrorsAndFixes))
		{
			$aErrorsAndFixes[$sRootClass] = array();
		}
		if (!array_key_exists($sTable, $aErrorsAndFixes[$sRootClass]))
		{
			$aErrorsAndFixes[$sRootClass][$sTable] = array();
		}

		foreach($aWrongRecords as $iRecordId)
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
			foreach(self::EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_ALL) as $sFriendClass)
			{
				$sFriendTable = self::DBGetTable($sFriendClass);
				$sFriendKey = self::DBGetKey($sFriendClass);

				// skip the current table
				if ($sFriendTable == $sTable)
				{
					continue;
				}

				$sFindRelatedRec = "SELECT DISTINCT maintable.`$sFriendKey` AS id FROM `$sFriendTable` AS maintable WHERE maintable.`$sFriendKey` IN ($sDeleteKeys)";
				self::DBCheckIntegrity_Check2Delete($sFindRelatedRec,
					"Cascading deletion of record in friend table `<em>$sTable</em>`", $sFriendClass, $aErrorsAndFixes,
					$iNewDelCount, $aPlannedDel,
					true);
			}
		}
	}

	/**
	 * @param string $sSelWrongRecs
	 * @param string $sErrorDesc
	 * @param string $sColumn
	 * @param string $sNewValue
	 * @param string $sClass
	 * @param array $aErrorsAndFixes
	 * @param int $iNewDelCount
	 * @param array $aPlannedDel
	 *
	 * @throws \CoreException
	 */
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
		if (count($aWrongRecords) == 0)
		{
			return;
		}

		if (!array_key_exists($sRootClass, $aErrorsAndFixes))
		{
			$aErrorsAndFixes[$sRootClass] = array();
		}
		if (!array_key_exists($sTable, $aErrorsAndFixes[$sRootClass]))
		{
			$aErrorsAndFixes[$sRootClass][$sTable] = array();
		}

		foreach($aWrongRecords as $iRecordId)
		{
			if (array_key_exists($iRecordId, $aErrorsAndFixes[$sRootClass][$sTable]))
			{
				$sAction = $aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action'];

				//if ($sAction == 'Delete')
				//{
					// No need to update, the record will be deleted!
				//}

				if ($sAction == 'Update')
				{
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
						$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action_Details'][] = (array(
							'column' => $sColumn,
							'newvalue' => $sNewValue
						));
						$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Reason'] .= ', '.$sErrorDesc;
					}
				}
			}
			else
			{
				$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Reason'] = $sErrorDesc;
				$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action'] = 'Update';
				$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Action_Details'] = array(array('column' => $sColumn, 'newvalue' => $sNewValue));
				$aErrorsAndFixes[$sRootClass][$sTable][$iRecordId]['Pass'] = 123;
			}

		}
	}

	/**
	 * @param array $aErrorsAndFixes
	 * @param int $iNewDelCount
	 * @param array $aPlannedDel
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function DBCheckIntegrity_SinglePass(&$aErrorsAndFixes, &$iNewDelCount, &$aPlannedDel)
	{
		foreach(self::GetClasses() as $sClass)
		{
			if (!self::HasTable($sClass))
			{
				continue;
			}
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

			foreach(self::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				// Skip this attribute if not defined in this table
				if (self::$m_aAttribOrigins[$sClass][$sAttCode] != $sClass)
				{
					continue;
				}

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
				else
				{
					if ($oAttDef->IsBasedOnDBColumns())
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
	}

	/**
	 * @param string $sRepairUrl
	 * @param string $sSQLStatementArgName
	 *
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \Exception
	 */
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
			foreach($aPlannedDel as $sTable => $aPlannedDelOnTable)
			{
				$iPlannedDel += count($aPlannedDelOnTable);
			}
			if ($iPlannedDel > $iMaxDel)
			{
				throw new CoreWarning("DB Integrity Check safety net - Exceeding the limit of $iMaxDel planned record deletion");
			}
			// Safety net #2 - limit the iterations
			//
			$iLoopCount++;
			$iMaxLoops = 10;
			if ($iLoopCount > $iMaxLoops)
			{
				throw new CoreWarning("DB Integrity Check safety net - Reached the limit of $iMaxLoops loops");
			}
		}

		// Display the results
		//
		$iIssueCount = 0;
		$aFixesDelete = array();
		$aFixesUpdate = array();

		foreach($aErrorsAndFixes as $sRootClass => $aTables)
		{
			foreach($aTables as $sTable => $aRecords)
			{
				foreach($aRecords as $iRecord => $aError)
				{
					$sAction = $aError['Action'];
					$sReason = $aError['Reason'];

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
			foreach(self::GetClasses() as $sClass)
			{
				if (!self::HasTable($sClass))
				{
					continue;
				}

				$sRootClass = self::GetRootClass($sClass);
				$sTable = self::DBGetTable($sClass);
				$sKeyField = self::DBGetKey($sClass);

				$aErrorsAndFixes[$sRootClass][$sTable] = array();
				$aTable2ClassProp[$sTable] = array('rootclass' => $sRootClass, 'class' => $sClass, 'keyfield' => $sKeyField);
			}
			// Second step, build a flat list of SQL queries
			$aSQLFixes = array();
			$iPlannedUpdate = 0;
			foreach($aFixesUpdate as $sTable => $aColumns)
			{
				foreach($aColumns as $sColumn => $aNewValues)
				{
					foreach($aNewValues as $sNewValue => $aRecords)
					{
						$iPlannedUpdate += count($aRecords);
						$sWrongRecords = "'".implode("', '", $aRecords)."'";
						$sKeyField = $aTable2ClassProp[$sTable]['keyfield'];

						$aSQLFixes[] = "UPDATE `$sTable` SET `$sColumn` = $sNewValue WHERE `$sKeyField` IN ($sWrongRecords)";
					}
				}
			}
			$iPlannedDel = 0;
			foreach($aFixesDelete as $sTable => $aRecords)
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
			foreach($aIssues as $sIssueDesc)
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

	/**
	 * @param string|Config $config config file content or {@link Config} object
	 * @param bool $bModelOnly
	 * @param bool $bAllowCache
	 * @param bool $bTraceSourceFiles
	 * @param string $sEnvironment
	 *
	 * @throws \MySQLException
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \Exception
	 */
	public static function Startup($config, $bModelOnly = false, $bAllowCache = true, $bTraceSourceFiles = false, $sEnvironment = 'production')
	{
		// Startup on a new environment is not supported
		static $bStarted = false;
		if ($bStarted) {
			return;
		}
		$bStarted = true;

		self::$m_sEnvironment = $sEnvironment;

		try {
			if (!defined('MODULESROOT')) {
				define('MODULESROOT', APPROOT.'env-'.self::$m_sEnvironment.'/');

				self::$m_bTraceSourceFiles = $bTraceSourceFiles;

				// $config can be either a filename, or a Configuration object (volatile!)
				if ($config instanceof Config) {
					self::LoadConfig($config, $bAllowCache);
				} else {
					self::LoadConfig(new Config($config), $bAllowCache);
				}

				if ($bModelOnly) {
					return;
				}
			}

			CMDBSource::SelectDB(self::$m_sDBName);

			foreach (MetaModel::EnumPlugins('ModuleHandlerApiInterface') as $oPHPClass) {
				$oPHPClass::OnMetaModelStarted();
			}

			ExpressionCache::Warmup();
		}
		finally {
			// Event service must be initialized after the MetaModel startup, otherwise it cannot discover classes implementing the iEventServiceSetup interface
			EventService::InitService();
			EventService::FireEvent(new EventData(ApplicationEvents::APPLICATION_EVENT_METAMODEL_STARTED));
		}
	}

	/**
	 * @param Config $oConfiguration
	 * @param bool $bAllowCache
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \Exception
	 * @throws \MySQLException
	 */
	public static function LoadConfig($oConfiguration, $bAllowCache = false)
	{
		$oKPI = new ExecutionKPI();
		self::$m_oConfig = $oConfiguration;

		// N°2478 utils has his own private attribute
		// @see utils::GetConfig : it always call MetaModel, but to be sure we're doing this extra copy anyway O:)
		utils::InitTimeZone($oConfiguration);
		utils::SetConfig($oConfiguration);

		// Set log ASAP
		if (self::$m_oConfig->GetLogGlobal())
		{
			if (self::$m_oConfig->GetLogIssue()) {
				self::$m_bLogIssue = true;
				IssueLog::Enable(APPROOT.'log/error.log');
			}
			self::$m_bLogNotification = self::$m_oConfig->GetLogNotification();
			self::$m_bLogWebService = self::$m_oConfig->GetLogWebService();

			ToolsLog::Enable(APPROOT.'log/tools.log');
			DeadLockLog::Enable();
			DeprecatedCallsLog::Enable();
			ExceptionLog::Enable();
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
        ExecutionKPI::SetGenerateLegacyReport(self::$m_oConfig->Get('log_kpi_generate_legacy_report'));
        ExecutionKPI::SetSlowQueries(self::$m_oConfig->Get('log_kpi_slow_queries'));

		self::$m_bSkipCheckToWrite = self::$m_oConfig->Get('skip_check_to_write');
		self::$m_bSkipCheckExtKeys = self::$m_oConfig->Get('skip_check_ext_keys');

		self::$m_bUseAPCCache = $bAllowCache
			&& self::$m_oConfig->Get('apc_cache.enabled')
			&& function_exists('apc_fetch')
			&& function_exists('apc_store');

		DBSearch::EnableQueryCache(self::$m_oConfig->GetQueryCacheEnabled(), self::$m_bUseAPCCache, self::$m_oConfig->Get('apc_cache.query_ttl'));
		DBSearch::EnableQueryTrace(self::$m_oConfig->GetLogQueries() || self::$m_oConfig->Get('log_kpi_record_oql'));
		DBSearch::EnableQueryIndentation(self::$m_oConfig->Get('query_indentation_enabled'));
		DBSearch::EnableOptimizeQuery(self::$m_oConfig->Get('query_optimization_enabled'));

		// Note: load the dictionary as soon as possible, because it might be
		//       needed when some error occur
		$sAppIdentity = 'itop-'.MetaModel::GetEnvironmentId();
		if (self::$m_bUseAPCCache)
		{
			Dict::EnableCache($sAppIdentity);
		}
		require_once(APPROOT.'env-'.self::$m_sEnvironment.'/dictionaries/languages.php');

		// Set the default language...
		Dict::SetDefaultLanguage(self::$m_oConfig->GetDefaultLanguage());

		// Romain: this is the only way I've found to cope with the fact that
		//         classes have to be derived from cmdbabstract (to be editable in the UI)
		require_once(APPROOT.'/application/cmdbabstract.class.inc.php');

		if (!defined('MODULESROOT')) {
			define('MODULESROOT', APPROOT.'env-'.self::$m_sEnvironment.'/');
		}

		require_once(APPROOT.'core/autoload.php');
		require_once(APPROOT.'env-'.self::$m_sEnvironment.'/autoload.php');

		foreach (self::$m_oConfig->GetAddons() as $sModule => $sToInclude) {
			self::IncludeModule($sToInclude, 'addons');
		}

		$sSource = self::$m_oConfig->Get('db_name');
		$sTablePrefix = self::$m_oConfig->Get('db_subname');
		$oKPI->ComputeAndReport('Load config');

		if (self::$m_bUseAPCCache) {
			$oKPI = new ExecutionKPI();
			// Note: For versions of APC older than 3.0.17, fetch() accepts only one parameter
			//
			$sOqlAPCCacheId = 'itop-'.MetaModel::GetEnvironmentId().'-metamodel';
			$result = apc_fetch($sOqlAPCCacheId);

			if (is_array($result)) {
				// todo - verifier que toutes les classes mentionnees ici sont chargees dans InitClasses()
				self::$m_aExtensionClassNames = $result['m_aExtensionClassNames'];
				self::$m_Category2Class = $result['m_Category2Class'];
				self::$m_aRootClasses = $result['m_aRootClasses'];
				self::$m_aParentClasses = $result['m_aParentClasses'];
				self::$m_aChildClasses = $result['m_aChildClasses'];
				self::$m_aClassParams = $result['m_aClassParams'];
				self::$m_aAttribDefs = $result['m_aAttribDefs'];
				self::$m_aAttribOrigins = $result['m_aAttribOrigins'];
				self::$m_aIgnoredAttributes = $result['m_aIgnoredAttributes'];
				self::$m_aFilterAttribList = $result['m_aFilterList'];
				self::$m_aListInfos = $result['m_aListInfos'];
				self::$m_aListData = $result['m_aListData'];
				self::$m_aRelationInfos = $result['m_aRelationInfos'];
				self::$m_aStates = $result['m_aStates'];
				self::$m_aStimuli = $result['m_aStimuli'];
				self::$m_aTransitions = $result['m_aTransitions'];
				self::$m_aHighlightScales = $result['m_aHighlightScales'];
				self::$m_aEnumToMeta = $result['m_aEnumToMeta'];
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
				$aCache['m_aExtensionClassNames'] = self::$m_aExtensionClassNames;
				$aCache['m_Category2Class'] = self::$m_Category2Class;
				$aCache['m_aRootClasses'] = self::$m_aRootClasses; // array of "classname" => "rootclass"
				$aCache['m_aParentClasses'] = self::$m_aParentClasses; // array of ("classname" => array of "parentclass")
				$aCache['m_aChildClasses'] = self::$m_aChildClasses; // array of ("classname" => array of "childclass")
				$aCache['m_aClassParams'] = self::$m_aClassParams; // array of ("classname" => array of class information)
				$aCache['m_aAttribDefs'] = self::$m_aAttribDefs; // array of ("classname" => array of attributes)
				$aCache['m_aAttribOrigins'] = self::$m_aAttribOrigins; // array of ("classname" => array of ("attcode"=>"sourceclass"))
				$aCache['m_aIgnoredAttributes'] = self::$m_aIgnoredAttributes; //array of ("classname" => array of ("attcode")
				$aCache['m_aFilterList'] = self::$m_aFilterAttribList; // array of ("classname" => array filterdef)
				$aCache['m_aListInfos'] = self::$m_aListInfos; // array of ("listcode" => various info on the list, common to every classes)
				$aCache['m_aListData'] = self::$m_aListData; // array of ("classname" => array of "listcode" => list)
				$aCache['m_aRelationInfos'] = self::$m_aRelationInfos; // array of ("relcode" => various info on the list, common to every classes)
				$aCache['m_aStates'] = self::$m_aStates; // array of ("classname" => array of "statecode"=>array('label'=>..., attribute_inherit=> attribute_list=>...))
				$aCache['m_aStimuli'] = self::$m_aStimuli; // array of ("classname" => array of ("stimuluscode"=>array('label'=>...)))
				$aCache['m_aTransitions'] = self::$m_aTransitions; // array of ("classname" => array of ("statcode_from"=>array of ("stimuluscode" => array('target_state'=>..., 'actions'=>array of handlers procs, 'user_restriction'=>TBD)))
				$aCache['m_aHighlightScales'] = self::$m_aHighlightScales; // array of ("classname" => array of higlightcodes)))
				$aCache['m_aEnumToMeta'] = self::$m_aEnumToMeta;
				apc_store($sOqlAPCCacheId, $aCache);
				$oKPI->ComputeAndReport('Metamodel APC (store)');
			}
		}

		self::$m_sDBName = $sSource;
		self::$m_sTablePrefix = $sTablePrefix;

		CMDBSource::InitFromConfig(self::$m_oConfig);
		// Later when timezone implementation is correctly done: CMDBSource::SetTimezone($sDBTimezone);
        ExecutionKPI::InitStats();
	}

	/**
	 * @param string $sModule
	 * @param string $sProperty
	 * @param $defaultvalue
	 *
	 * @return mixed
	 */
	public static function GetModuleSetting($sModule, $sProperty, $defaultvalue = null)
	{
		return self::$m_oConfig->GetModuleSetting($sModule, $sProperty, $defaultvalue);
	}

	/**
	 * @param string $sModule
	 * @param string $sProperty
	 * @param $defaultvalue
	 *
	 * @return ??
	 */
	public static function GetModuleParameter($sModule, $sProperty, $defaultvalue = null)
	{
		$value = $defaultvalue;
		if (!self::$m_aModulesParameters[$sModule] == null)
		{
			$value = self::$m_aModulesParameters[$sModule]->Get($sProperty, $defaultvalue);
		}
		return $value;
	}

	/**
	 * @internal Used for resetting the configuration during automated tests

	 * @param \Config $oConfiguration
	 *
	 * @return void
	 * @since 3.0.4 3.1.1 3.2.0
	 */
	public static function SetConfig(Config $oConfiguration)
	{
		self::$m_oConfig = $oConfiguration;
	}

	/**
	 * @return Config
	 */
	public static function GetConfig()
	{
		return self::$m_oConfig;
	}

	/**
	 * @return string The environment in which the model has been loaded (e.g. 'production')
	 */
	public static function GetEnvironment()
	{
		return self::$m_sEnvironment;
	}

	/**
	 * @return string
	 */
	public static function GetEnvironmentId()
	{
		return md5(APPROOT).'-'.self::$m_sEnvironment;
	}

    /** @var array */
    protected static $m_aExtensionClassNames = [];

	/**
	 * @param string $sToInclude
	 * @param string $sModuleType
	 *
	 * @throws \CoreException
	 */
	public static function IncludeModule($sToInclude, $sModuleType = null)
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
			if ($sModuleType == null)
			{
				throw new CoreException("Include: unable to load the file '$sFile'");
			}
			else
			{
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
				IssueLog::Error("Spurious characters injected by '$sFile'");
			}
		}
	}

	// Building an object
	//
	//
	/** @var array */
	private static $aQueryCacheGetObject = array();
	/** @var array */
	private static $aQueryCacheGetObjectHits = array();

	/**
	 * @return string
	 */
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

	/**
	 * @param string $sClass
	 * @param int $iKey
	 * @param bool $bMustBeFound
	 * @param bool $bAllowAllData if true then no rights filtering
	 * @param array $aModifierProperties
	 *
	 * @return string[] column name / value array
	 * @throws CoreException if no result found and $bMustBeFound=true
	 * @throws \Exception
	 *
	 * @see utils::PushArchiveMode() to enable search on archived objects
	 */
	public static function MakeSingleRow($sClass, $iKey, $bMustBeFound = true, $bAllowAllData = false, $aModifierProperties = null)
	{
		// Build the query cache signature
		//
		$sQuerySign = $sClass;
		if ($bAllowAllData)
		{
			$sQuerySign .= '_all_';
		}
		if (is_array($aModifierProperties) && (count($aModifierProperties) > 0))
		{
			array_multisort($aModifierProperties);
			$sModifierProperties = json_encode($aModifierProperties);
			$sQuerySign .= '_all_'.md5($sModifierProperties);
		}
		$sQuerySign .= utils::IsArchiveMode() ? '_arch_' : '';

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
				foreach($aModifierProperties as $sPluginClass => $aProperties)
				{
					foreach($aProperties as $sProperty => $value)
					{
						$oFilter->SetModifierProperty($sPluginClass, $sProperty, $value);
					}
				}
			}
			if ($bAllowAllData)
			{
				$oFilter->AllowAllData();
			}
			$oFilter->NoContextParameters();
			$sSQL = $oFilter->MakeSelectQuery();
			self::$aQueryCacheGetObject[$sQuerySign] = $sSQL;
			self::$aQueryCacheGetObjectHits[$sQuerySign] = 0;
		}
		else
		{
			$sSQL = self::$aQueryCacheGetObject[$sQuerySign];
			self::$aQueryCacheGetObjectHits[$sQuerySign] += 1;
		}
		$sSQL = str_replace(CMDBSource::Quote(987654321), CMDBSource::Quote($iKey), $sSQL);
		$res = CMDBSource::Query($sSQL);

		$aRow = CMDBSource::FetchArray($res);
		CMDBSource::FreeResult($res);

		if ($bMustBeFound && empty($aRow))
		{
			$sNotFoundErrorMessage = "No result for the single row query";
			IssueLog::Info($sNotFoundErrorMessage, LogChannels::CMDB_SOURCE, [
				'class' => $sClass,
				'key' => $iKey,
				'sql_query' => $sSQL,
				]);
			throw new CoreException($sNotFoundErrorMessage);
		}

		return $aRow;
	}

	/**
	 * Converts a column name / value array to a {@link DBObject}
	 *
	 * @param string $sClass
	 * @param string[] $aRow column name / value array
	 * @param string $sClassAlias
	 * @param string[] $aAttToLoad
	 * @param array $aExtendedDataSpec
	 *
	 * @return DBObject
	 * @throws CoreUnexpectedValue if finalClass attribute wasn't specified but is needed
	 * @throws CoreException if finalClass cannot be found
	 */
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
			if (self::IsAbstract($sClass))
			{
				throw new CoreUnexpectedValue("Querying the abstract '$sClass' class without finalClass attribute");
			}
			if (self::HasChildrenClasses($sClass))
			{
				throw new CoreUnexpectedValue("Querying the '$sClass' class without the finalClass attribute, whereas this class has  children");
			}
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
			if (!class_exists($aRow[$sClassAlias."finalclass"]))
			{
				throw new CoreException("Class {$aRow[$sClassAlias."finalclass"]} derived from $sClass does not exist anymore, please remove corresponding tables in the database", array('row' => $aRow));
			}
			$sClass = $aRow[$sClassAlias."finalclass"];
		}

		return new $sClass($aRow, $sClassAlias, $aAttToLoad, $aExtendedDataSpec);
	}

	/**
	 * Instantiate an object already persisted to the Database.
	 *
	 * Note that LinkedSet attributes are not loaded.
	 * DBObject::Reload() will be called when getting a LinkedSet attribute
	 *
	 * @api
	 * @see MetaModel::GetObjectWithArchive to get object even if it's archived
	 * @see utils::PushArchiveMode() to enable search on archived objects
	 *
	 * @param string $sClass
	 * @param int $iKey id value of the object to retrieve
	 * @param bool $bMustBeFound see throws ArchivedObjectException
	 * @param bool $bAllowAllData if true then user rights will be bypassed - use with care!
	 * @param array $aModifierProperties properties for {@see iQueryModifier} impl
	 *
	 * @return \DBObject null if : (the object is not found) or (archive mode disabled and object is archived and
	 *     $bMustBeFound=false)
	 * @throws CoreException if no result found and $bMustBeFound=true
	 * @throws ArchivedObjectException if archive mode disabled and result is archived and $bMustBeFound=true
	 */
	public static function GetObject($sClass, $iKey, $bMustBeFound = true, $bAllowAllData = false, $aModifierProperties = null)
	{
		$oObject = self::GetObjectWithArchive($sClass, $iKey, $bMustBeFound, $bAllowAllData, $aModifierProperties);

		if (empty($oObject)) {
			return null;
		}

		if (!utils::IsArchiveMode() && $oObject->IsArchived()) {
			if ($bMustBeFound) {
				throw new ArchivedObjectException("The object $sClass::$iKey is archived");
			}

			return null;
		}

		return $oObject;
	}

	/**
	 * @param string $sClass
	 * @param int $iKey
	 *
	 * @return bool True if the object of $sClass and $iKey exists in the DB -no matter the current user restrictions-, false otherwise meaning:
	 * - It could be in memory for now and is not persisted yet
	 * - It is neither in memory nor DB
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLQueryHasNoResultException
	 * @since 3.0.0 N°4173
	 */
	public static function IsObjectInDB(string $sClass, int $iKey): bool
	{
		// Note: We take the root class to ensure that there is a corresponding table in the DB
		// as some intermediate classes can have no table in the DB.
		$sRootClass = MetaModel::GetRootClass($sClass);

		$sTable = MetaModel::DBGetTable($sRootClass);
		$sKeyCol = MetaModel::DBGetKey($sRootClass);
		$sEscapedKey = CMDBSource::Quote($iKey);

		$sQuery = "SELECT count(*) FROM `{$sTable}` WHERE `{$sKeyCol}` = {$sEscapedKey}";
		$iCount = (int) CMDBSource::QueryToScalar($sQuery);

		return $iCount === 1;
	}

	public static function GetFinalClassName(string $sClass, int $iKey): string
	{
		if (MetaModel::IsStandaloneClass($sClass)) {
			return $sClass;
		}

		$sRootClass = MetaModel::GetRootClass($sClass);
		$sTable = MetaModel::DBGetTable($sRootClass);
		$sKeyCol = MetaModel::DBGetKey($sRootClass);
		$sEscapedKey = CMDBSource::Quote($iKey);
		$sFinalClassField = Metamodel::DBGetClassField($sRootClass);

		$sQuery = "SELECT `{$sFinalClassField}` FROM `{$sTable}` WHERE `{$sKeyCol}` = {$sEscapedKey}";
		return  CMDBSource::QueryToScalar($sQuery);
	}

	/**
	 * Search for the specified class and id. If the object is archived it will be returned anyway (this is for pre-2.4
	 * module compatibility, see N.1108)
	 *
	 * @param string $sClass
	 * @param int $iKey
	 * @param bool $bMustBeFound
	 * @param bool $bAllowAllData
	 * @param array $aModifierProperties
	 *
	 * @return DBObject|null
	 * @throws CoreException if no result found and $bMustBeFound=true
	 * @throws \Exception
	 *
	 * @since 2.4.0 introduction of the archive functionality
	 *
	 * @see MetaModel::GetObject() same but returns null or ArchivedObjectFoundException if object exists but is
	 *     archived
	 */
	public static function GetObjectWithArchive($sClass, $iKey, $bMustBeFound = true, $bAllowAllData = false, $aModifierProperties = null)
	{
		self::_check_subclass($sClass);

		utils::PushArchiveMode(true);
		try
		{
			$aRow = self::MakeSingleRow($sClass, $iKey, $bMustBeFound, $bAllowAllData, $aModifierProperties);
		}
		catch(Exception $e)
		{
			// In the finally block we will pop the pushed archived mode
			// otherwise the application stays in ArchiveMode true which has caused hazardious behavior!
			throw $e;
		}
		finally
		{
			utils::PopArchiveMode();
		}

		if (empty($aRow))
		{
			return null;
		}

		return self::GetObjectByRow($sClass, $aRow); // null should not be returned, this is handled in the callee
	}

	/**
	 * @param string $sClass
	 * @param string $sName
	 * @param bool $bMustBeFound
	 *
	 * @return \DBObject|null
	 * @throws \CoreException
	 */
	public static function GetObjectByName($sClass, $sName, $bMustBeFound = true)
	{
		self::_check_subclass($sClass);

		$oObjSearch = new DBObjectSearch($sClass);
		$oObjSearch->AddNameCondition($sName);
		$oSet = new DBObjectSet($oObjSearch);
		if ($oSet->Count() != 1)
		{
			if ($bMustBeFound)
			{
				throw new CoreException('Failed to get an object by its name', array('class' => $sClass, 'name' => $sName));
			}
			return null;
		}

		return $oSet->fetch();
	}

	/** @var array */
	static protected $m_aCacheObjectByColumn = array();

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param mixed $value
	 * @param bool $bMustBeFoundUnique
	 * @param bool $bAllowAllData
	 *
	 * @return \DBObject if $bMustBeFoundUnique=true and no object or multiple objects found will throw a CoreException
	 *                  else will return null
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 *
	 * @since 2.7.7 Add new $bAllowAllData parameter
	 */
	public static function GetObjectByColumn($sClass, $sAttCode, $value, $bMustBeFoundUnique = true, $bAllowAllData = false)
	{
		if (!isset(self::$m_aCacheObjectByColumn[$sClass][$sAttCode][$value])) {
			self::_check_subclass($sClass);

			$oObjSearch = new DBObjectSearch($sClass);
			$oObjSearch->AllowAllData($bAllowAllData);
			$oObjSearch->AddCondition($sAttCode, $value, '=');
			$oSet = new DBObjectSet($oObjSearch);
			if ($oSet->Count() == 1)
			{
				self::$m_aCacheObjectByColumn[$sClass][$sAttCode][$value] = $oSet->fetch();
			}
			else
			{
				if ($bMustBeFoundUnique)
				{
					throw new CoreException('Failed to get an object by column', array('class' => $sClass, 'attcode' => $sAttCode, 'value' => $value, 'matches' => $oSet->Count()));
				}
				self::$m_aCacheObjectByColumn[$sClass][$sAttCode][$value] = null;
			}
		}

		return self::$m_aCacheObjectByColumn[$sClass][$sAttCode][$value];
	}

	/**
	 * @param string $sQuery
	 * @param array $aParams
	 * @param bool $bAllowAllData
	 *
	 * @return \DBObject
	 * @throws \OQLException
	 */
	public static function GetObjectFromOQL($sQuery, $aParams = null, $bAllowAllData = false)
	{
		$oFilter = DBObjectSearch::FromOQL($sQuery, $aParams);
		if ($bAllowAllData)
		{
			$oFilter->AllowAllData();
		}
		$oSet = new DBObjectSet($oFilter);

		return $oSet->Fetch();
	}

	/**
	 * @param string $sTargetClass
	 * @param int $iKey
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public static function GetHyperLink($sTargetClass, $iKey)
	{
		if ($iKey < 0) {
			return "$sTargetClass: $iKey (invalid value)";
		}
		$oObj = self::GetObject($sTargetClass, $iKey, false);
		if (is_null($oObj)) {
			// Whatever we are looking for, the root class is the key to search for
			$sRootClass = self::GetRootClass($sTargetClass);
			$oSearch = DBObjectSearch::FromOQL('SELECT CMDBChangeOpDelete WHERE objclass = :objclass AND objkey = :objkey', array('objclass' => $sRootClass, 'objkey' => $iKey));
			$oSet = new DBObjectSet($oSearch);
			$oRecord = $oSet->Fetch();
			// An empty fname is obtained with iTop < 2.0
			if (is_null($oRecord) || (strlen(trim($oRecord->Get('fname'))) == 0)) {
				$sName = Dict::Format('Core:UnknownObjectLabel', $sTargetClass, $iKey);
				$sTitle = Dict::S('Core:UnknownObjectTip');
			} else {
				$sName = $oRecord->Get('fname');
				$sTitle = Dict::Format('Core:DeletedObjectTip', $oRecord->Get('date'), $oRecord->Get('userinfo'));
			}

			return '<span class="itop-deleted-object" title="'.utils::EscapeHtml($sTitle).'">'.utils::EscapeHtml($sName).'</span>';
		}
		return $oObj->GetHyperLink();
	}

	/**
	 * Instantiate a persistable object (not yet persisted)
	 *
	 * @api
	 *
	 * @param string $sClass A persistable class
	 * @param array|null $aValues array of attcode => attribute value to preset
	 *
	 * @return \cmdbAbstractObject
	 * @throws \CoreException
	 */
	public static function NewObject($sClass, $aValues = null)
	{
		self::_check_subclass($sClass);
		$oRet = new $sClass();
		if (is_array($aValues))
		{
			foreach($aValues as $sAttCode => $value)
			{
				$oRet->Set($sAttCode, $value);
			}
		}

		return $oRet;
	}

	/**
	 * @internal
	 *
	 * @param array $aValues array of attcode => value
	 * @param DBObjectSearch $oFilter
	 *
	 * @return int Modified objects
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public static function BulkUpdate(DBObjectSearch $oFilter, array $aValues)
	{
		// $aValues is an array of $sAttCode => $value
		$sSQL = $oFilter->MakeUpdateQuery($aValues);
		if (!self::DBIsReadOnly()) {
			CMDBSource::Query($sSQL);
		}

		return CMDBSource::AffectedRows();
	}

	/**
	 * Helper to remove selected objects without calling any handler
	 * Surpasses BulkDelete as it can handle abstract classes, but has the other limitation as it bypasses standard
	 * objects handlers
	 *
	 * @param \DBSearch $oFilter Scope of objects to wipe out
	 *
	 * @return int The count of deleted objects
	 * @throws \CoreException
	 */
	public static function PurgeData($oFilter)
	{
		$iMaxChunkSize = MetaModel::GetConfig()->Get('purge_data.max_chunk_size');
		$sTargetClass = $oFilter->GetClass();
		$iNbIdsDeleted = 0;
		$bExecuteQuery = true;

		// This loop allows you to delete objects in batches of $iMaxChunkSize elements
		while ($bExecuteQuery) {
			$oSet = new DBObjectSet($oFilter);
			$oSet->SetLimit($iMaxChunkSize);
			$oSet->OptimizeColumnLoad(array($sTargetClass => array('finalclass')));
			$aIdToClass = $oSet->GetColumnAsArray('finalclass', true);

			$aIds = array_keys($aIdToClass);
			$iNbIds = count($aIds);
			if ($iNbIds > 0) {
				$aQuotedIds = CMDBSource::Quote($aIds);
				$sIdList = implode(',', $aQuotedIds);
				$aTargetClasses = array_merge(
					self::EnumChildClasses($sTargetClass, ENUM_CHILD_CLASSES_ALL),
					self::EnumParentClasses($sTargetClass, ENUM_PARENT_CLASSES_EXCLUDELEAF)
				);
				foreach ($aTargetClasses as $sSomeClass) {
					$sTable = MetaModel::DBGetTable($sSomeClass);
					$sPKField = MetaModel::DBGetKey($sSomeClass);

					$sDeleteSQL = "DELETE FROM `$sTable` WHERE `$sPKField` IN ($sIdList)";
					CMDBSource::DeleteFrom($sDeleteSQL);
				}
				$iNbIdsDeleted += $iNbIds;
			}

			// stop loop if query returned fewer objects than  $iMaxChunkSize. In this case, all objects have been deleted.
			if ($iNbIds < $iMaxChunkSize) {
				$bExecuteQuery = false;
			}
		}

		return $iNbIdsDeleted;
	}
	// Links
	//
	//
	/**
	 * @param string $sClass
	 *
	 * @return array
	 * @throws \CoreException
	 */
	public static function EnumReferencedClasses($sClass)
	{
		self::_check_subclass($sClass);

		// 1-N links (referenced by my class), returns an array of sAttCode=>sClass
		$aResult = array();
		foreach(self::$m_aAttribDefs[$sClass] as $sAttCode => $oAttDef)
		{
			if ($oAttDef->IsExternalKey())
			{
				$aResult[$sAttCode] = $oAttDef->GetTargetClass();
			}
		}

		return $aResult;
	}

	/**
	 * @param string $sClass
	 * @param bool $bSkipLinkingClasses
	 * @param bool $bInnerJoinsOnly
	 *
	 * @return array
	 * @throws \CoreException
	 */
	public static function EnumReferencingClasses($sClass, $bSkipLinkingClasses = false, $bInnerJoinsOnly = false)
	{
		self::_check_subclass($sClass);

		if ($bSkipLinkingClasses)
		{
			$aLinksClasses = array_keys(self::GetLinkClasses());
		}

		// 1-N links (referencing my class), array of sClass => array of sAttcode
		$aResult = array();
		foreach(self::$m_aAttribDefs as $sSomeClass => $aClassAttributes)
		{
			if ($bSkipLinkingClasses && in_array($sSomeClass, $aLinksClasses))
			{
				continue;
			}

			$aExtKeys = array();
			foreach($aClassAttributes as $sAttCode => $oAttDef)
			{
				if (self::$m_aAttribOrigins[$sSomeClass][$sAttCode] != $sSomeClass)
				{
					continue;
				}
				if ($oAttDef->IsExternalKey() && (self::IsParentClass($oAttDef->GetTargetClass(), $sClass)))
				{
					if ($bInnerJoinsOnly && $oAttDef->IsNullAllowed())
					{
						continue;
					}
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
	 * Return true if $sClass is a n:n class from the DM.
	 * This is the recommended way to determine if a class is actually a n:n relation because it is based on the decision made by the designer in the datamodel
	 *
	 * @param string $sClass
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public static function IsLinkClass($sClass): bool
	{
		return (isset(self::$m_aClassParams[$sClass]["is_link"]) && self::$m_aClassParams[$sClass]["is_link"]);
	}

	/**
	 * Return an array n:n classes with their external keys / target classes.
	 *
	 * @uses self::IsLinkClass()
	 * @return array (target class => (external key code => target class))
	 * @throws \CoreException
	 */
	public static function GetLinkClasses(): array
	{
		$aRet = array();
		foreach(self::GetClasses() as $sClass) {
			if (self::IsLinkClass($sClass)) {
				$aExtKeys = array();
				foreach(self::ListAttributeDefs($sClass) as $sAttCode => $oAttDef) {
					if ($oAttDef->IsExternalKey()) {
						$aExtKeys[$sAttCode] = $oAttDef->GetTargetClass();
					}
				}
				$aRet[$sClass] = $aExtKeys;
			}
		}

		return $aRet;
	}

	/**
	 * @param string $sLinkClass
	 * @param string $sAttCode
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function GetLinkLabel($sLinkClass, $sAttCode)
	{
		self::_check_subclass($sLinkClass);

		// e.g. "supported by" (later: $this->GetLinkLabel(), computed on link data!)
		return self::GetLabel($sLinkClass, $sAttCode);
	}

	/**
	 * Replaces all the parameters by the values passed in the hash array
	 *
	 * @param string $sInput Can be plain text or HTML. Note that some part may be url-encoded if a placeholder is used in an URL.
	 * @param array $aParams Placeholder descriptor as key, replacement as value. Possible placeholders can be of the forms:
	 *   * foo_bar : Static placeholder
	 *   * foo->bar : Another static placeholder
	 *   * foo->object() : Will be developed into a set of placeholders depending on the attribute of the given \DBObject and their corresponding transformation methods {@see \AttributeDefinition::GetForTemplate()}
	 *                     Example: foo->hyperlink(), foo->name, foo->html(name), foo->head(log), foo->head_html(log), ...
	 *
	 * @link https://www.itophub.io/wiki/page?id=latest:admin:placeholders
	 * @return string
	 *
	 * @throws \Exception
	 */
	public static function ApplyParams($sInput, $aParams)
	{
		$aParams = static::AddMagicPlaceholders($aParams);

		// Declare magic parameters
		$aParams['APP_URL'] = utils::GetAbsoluteUrlAppRoot();
		$aParams['MODULES_URL'] = utils::GetAbsoluteUrlModulesRoot();

		$aSearches = array();
		$aReplacements = array();
		foreach ($aParams as $sSearch => $replace) {
			// Some environment parameters are objects, we just need scalars
			if (is_object($replace)) {
				$iPos = strpos($sSearch, '->object()');
				if ($iPos !== false) {
					// Expand the parameters for the object
					$sName = substr($sSearch, 0, $iPos);
					// Note: Capturing
					// 1 - The delimiter
					// 2 - The arrow
					// 3 - The attribute code
					$aRegExps = array(
						'/(\\$)'.$sName.'-(>|&gt;)([^\\$]+)\\$/', // Support both syntaxes: $this->xxx$ or $this-&gt;xxx$ for HTML compatibility
						'/(%24)'.$sName.'-(>|&gt;)([^%24]+)%24/', // Support for urlencoded in HTML attributes (%20this-&gt;xxx%20)
					);
					foreach ($aRegExps as $sRegExp) {
						if (preg_match_all($sRegExp, $sInput, $aMatches)) {
							foreach ($aMatches[3] as $idx => $sPlaceholderAttCode) {
								try {
									$sReplacement = $replace->GetForTemplate($sPlaceholderAttCode);
									if ($sReplacement !== null) {
										$aReplacements[] = $sReplacement;
										$aSearches[] = $aMatches[1][$idx].$sName.'-'.$aMatches[2][$idx].$sPlaceholderAttCode.$aMatches[1][$idx];
									}
								}
								catch (Exception $e) {
									$aContext = [
										'placeholder'   => $sPlaceholderAttCode,
										'replace class' => get_class($replace),
									];
									if ($replace instanceof DBObject) {
										$aContext['replace id'] = $replace->GetKey();
									}
									IssueLog::Debug(
										'Invalid placeholder in notification, no replacement will occur!',
										LogChannels::NOTIFICATIONS,
										$aContext
									);
								}
							}
						}
					}
				} else {
					continue; // Ignore this non-scalar value
				}
			} else {
				$aRegExps = array(
					'/(\$)'.$sSearch.'\$/',   // Regular placeholders (eg. $APP_URL$) or placeholders with an arrow in plain text (eg. $foo->bar$)
					'/(%24)'.$sSearch.'%24/', // Regular placeholders url-encoded in HTML attributes (eg. %24APP_URL%24)

					'/(\$)'.utils::EscapeHtml($sSearch).'\$/',      // Placeholders with an arrow in HTML (eg. $foo-&gt;bar$)
					'/(%24)'.utils::EscapeHtml($sSearch).'%24/',    // Placeholders with an arrow url-encoded in HTML attributes (eg. %24-&gt;bar%24)
				);
				foreach ($aRegExps as $sRegExp) {
					if (preg_match_all($sRegExp, $sInput, $aMatches)) {
						foreach ($aMatches[1] as $idx => $sDelimiter) {
							try {
								// Regular or plain text
								$aReplacements[] = (string) $replace;
								$aSearches[] = $aMatches[1][$idx].$sSearch.$aMatches[1][$idx];

								// With an arrow in HTML
								$aReplacements[] = (string) $replace;
								$aSearches[] = $aMatches[1][$idx].utils::EscapeHtml($sSearch).$aMatches[1][$idx];
							}
							catch (Exception $e) {
								IssueLog::Debug(
									'Invalid placeholder in notification, no replacement will occur !',
									LogChannels::NOTIFICATIONS,
									[
										'placeholder' => $sPlaceholderAttCode,
										'replace'     => $replace,
									]
								);
							}
						}
					}
				}
			}
		}

		return str_replace($aSearches, $aReplacements, $sInput);
	}

	/**
	 * @param string $sInterface
	 * @param string|null $sFilterInstanceOf [optional] if given, only instance of this string will be returned
	 *
	 * @return array classes=>instance implementing the given interface
	 */
	public static function EnumPlugins($sInterface, $sFilterInstanceOf = null)
	{
		$pluginManager = new PluginManager(self::$m_aExtensionClassNames);

		return $pluginManager->EnumPlugins($sInterface, $sFilterInstanceOf);
	}

	/**
	 * @param string $sInterface
	 * @param string $sClassName
	 *
	 * @return mixed the instance of the specified plug-ins for the given interface
	 */
	public static function GetPlugins($sInterface, $sClassName)
	{
		$pluginManager = new PluginManager(self::$m_aExtensionClassNames);

		return $pluginManager->GetPlugins($sInterface, $sClassName);
	}

	/**
	 * @param string $sEnvironment
	 *
	 * @return array
	 */
	public static function GetCacheEntries($sEnvironment = null)
	{
		if (is_null($sEnvironment))
		{
			$sEnvironment = MetaModel::GetEnvironmentId();
		}
		$aEntries = array();
		$aCacheUserData = apc_cache_info_compat();
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

	/**
	 * @param string $sEnvironmentId
	 */
	public static function ResetCache($sEnvironmentId = null)
	{
		if (is_null($sEnvironmentId))
		{
			$sEnvironmentId = MetaModel::GetEnvironmentId();
		}

		$sAppIdentity = 'itop-'.$sEnvironmentId;
		require_once(APPROOT.'/core/dict.class.inc.php');
		Dict::ResetCache($sAppIdentity);

		if (function_exists('apc_delete'))
		{
			foreach(self::GetCacheEntries($sEnvironmentId) as $sKey => $aAPCInfo)
			{
				$sAPCKey = $aAPCInfo['info'];
				apc_delete($sAPCKey);
			}
		}

		require_once(APPROOT.'core/userrights.class.inc.php');
		UserRights::FlushPrivileges();
	}

	/**
	 * Given a field spec, get the most relevant (unique) representation
	 * Examples for a user request:
	 * - friendlyname => ref
	 * - org_name => org_id->name
	 * - org_id_friendlyname => org_id=>name
	 * - caller_name => caller_id->name
	 * - caller_id_friendlyname => caller_id->friendlyname
	 *
	 * @param string $sClass
	 * @param string $sField
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
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
			if ($oAttDef->IsExternalField())
			{
				if ($oAttDef->IsFriendlyName())
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
				else
				{
					$sRet = $oAttDef->GetKeyAttCode().'->'.$oAttDef->GetExtAttCode();
				}
			}
		}
		return $sRet;
	}

	private static function GetAdditionalRequestAfterAlter($sClass, $sTable, $sField)
	{
		$aRequests = array();

		// Copy finalclass fields from root class to intermediate classes
		if ($sField == self::DBGetClassField($sClass))
		{
			$sRootClass = MetaModel::GetRootClass($sClass);
			$sRootTable = self::DBGetTable($sRootClass);
			$sKey = self::DBGetKey($sClass);
			$sRootKey = self::DBGetKey($sRootClass);
			$sRootField = self::DBGetClassField($sRootClass);
			if ($sTable != $sRootTable) {
				// Copy the finalclass of the root table
				$sRequest = "UPDATE `$sTable`,`$sRootTable` SET  `$sTable`.`$sField` = `$sRootTable`.`$sRootField` WHERE `$sTable`.`$sKey` = `$sRootTable`.`$sRootKey`";
				$aRequests[] = $sRequest;
			}
		}

		return $aRequests;
	}

	/**
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param string|null $sValue Code of the state value, can be null if allowed by the attribute definition
	 *
	 * @return \ormStyle|null
	 * @throws \Exception
	 * @throws \CoreException
	 */
	public static function GetEnumStyle(string $sClass, string $sAttCode, ?string $sValue = ''): ?ormStyle
	{
		if (strlen($sAttCode) === 0) {
			return null;
		}

		$oAttDef = self::GetAttributeDef($sClass, $sAttCode);
		if (!$oAttDef instanceof AttributeEnum) {
			throw new CoreException("MetaModel::GetEnumStyle() Attribute $sAttCode of class $sClass is not an AttributeEnum\n");
		}

		/** @var AttributeEnum $oAttDef */
		return $oAttDef->GetStyle($sValue);
	}

	protected static function GetReentranceObject($sClass, $sKey)
	{
		if (isset(self::$m_aReentranceProtection[$sClass][$sKey])) {
			return self::$m_aReentranceProtection[$sClass][$sKey];
		}
		return false;
	}

	/**
	 * @param \DBObject $oObject
	 *
	 * @return bool true if reentry possible
	 *
	 * @since 3.1.0 N°4756
	 */
	public static function StartReentranceProtection(DBObject $oObject)
	{
		if (isset(self::$m_aReentranceProtection[get_class($oObject)][$oObject->GetKey()])) {
			return false;
		}
		self::$m_aReentranceProtection[get_class($oObject)][$oObject->GetKey()] = $oObject;

		return true;
	}

	/**
	 * @param \DBObject $oObject
	 *
	 * @return void
	 *
	 * @since 3.1.0 N°4756
	 */
	public static function StopReentranceProtection(DBObject $oObject)
	{
		if (isset(self::$m_aReentranceProtection[get_class($oObject)][$oObject->GetKey()])) {
			unset(self::$m_aReentranceProtection[get_class($oObject)][$oObject->GetKey()]);
		}
	}

	/**
	 * For test purpose
	 * @throws \ReflectionException
	 * @since 3.1.0
	 */
	public static function InitExtensions()
	{
		// Build the list of available extensions
		//
		$aInterfaces = [
			'iLoginFSMExtension',
			'iLogoutExtension',
			'iLoginUIExtension',
			'iPreferencesExtension',
			'iApplicationUIExtension',
			'iApplicationObjectExtension',
			'iPopupMenuExtension',
			'iPageUIExtension',
			'iPageUIBlockExtension',
			'iBackofficeLinkedScriptsExtension',
			'iBackofficeEarlyScriptExtension',
			'iBackofficeScriptExtension',
			'iBackofficeInitScriptExtension',
			'iBackofficeReadyScriptExtension',
			'iBackofficeLinkedStylesheetsExtension',
			'iBackofficeStyleExtension',
			'iBackofficeDictEntriesExtension',
			'iBackofficeDictEntriesPrefixesExtension',
			'iPortalUIExtension',
			'iQueryModifier',
			'iOnClassInitialization',
			'iModuleExtension',
			'iKPILoggerExtension',
			'ModuleHandlerApiInterface',
			'iNewsroomProvider',
		];
		foreach ($aInterfaces as $sInterface) {
			self::$m_aExtensionClassNames[$sInterface] = array();
		}

		foreach (get_declared_classes() as $sPHPClass) {
			$oRefClass = new ReflectionClass($sPHPClass);
			$oExtensionInstance = null;
			foreach ($aInterfaces as $sInterface) {
				if ($oRefClass->implementsInterface($sInterface) && $oRefClass->isInstantiable()) {
					self::$m_aExtensionClassNames[$sInterface][$sPHPClass] = $sPHPClass;
				}
			}
		}
	}
}


// Standard attribute lists
MetaModel::RegisterZList("noneditable", array("description" => "non editable fields", "type" => "attributes"));

MetaModel::RegisterZList("details", array("description" => "All attributes to be displayed for the 'details' of an object", "type" => "attributes"));
MetaModel::RegisterZList("summary", array("description" => "All attributes to be displayed for shorter 'details' of an object", "type" => "attributes"));
MetaModel::RegisterZList("list", array("description" => "All attributes to be displayed for a list of objects", "type" => "attributes"));
MetaModel::RegisterZList("preview", array("description" => "All attributes visible in preview mode", "type" => "attributes"));

MetaModel::RegisterZList("standard_search", array("description" => "List of criteria for the standard search", "type" => "filters"));
MetaModel::RegisterZList("advanced_search", array("description" => "List of criteria for the advanced search", "type" => "filters"));
MetaModel::RegisterZList("default_search", array("description" => "List of criteria displayed by default during search", "type" => "filters"));
