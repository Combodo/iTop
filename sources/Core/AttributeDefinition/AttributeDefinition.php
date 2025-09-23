<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use ArchivedObjectException;
use BinaryExpression;
use CMDBChangeOp;
use CMDBChangeOpSetAttributeScalar;
use Combodo\iTop\Form\Field\Field;
use CoreCannotSaveObjectException;
use DBObject;
use Dict;
use Exception;
use Expression;
use FieldExpression;
use MetaModel;
use Str;
use utils;
use VariableExpression;

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
 * Propagation of the deletion through an external key - remove linked objects if ext key has is_null_allowed=false
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
 * Do nothing at least automatically
 */
define('DEL_NONE', 4);


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

define('LINKSET_EDITWHEN_NEVER', 0); // The linkset cannot be edited at all from inside this object
define('LINKSET_EDITWHEN_ON_HOST_EDITION', 1); // The only possible action is to open a new window to create a new object
define('LINKSET_EDITWHEN_ON_HOST_DISPLAY', 2); // Show the usual 'Actions' popup menu
define('LINKSET_EDITWHEN_ALWAYS', 3); // Show the usual 'Actions' popup menu


define('LINKSET_DISPLAY_STYLE_PROPERTY', 'property');
define('LINKSET_DISPLAY_STYLE_TAB', 'tab');


/**
 * Wiki formatting - experimental
 *
 * [[<objClass>:<objName|objId>|<label>]]
 * <label> is optional
 *
 * Examples:
 * - [[Server:db1.tnut.com]]
 * - [[Server:123]]
 * - [[Server:db1.tnut.com|Production server]]
 * - [[Server:123|Production server]]
 */
define('WIKI_OBJECT_REGEXP', '/\[\[(.+):(.+)(\|(.+))?\]\]/U');

/**
 * Attribute definition API, implemented in and many flavours (Int, String, Enum, etc.)
 *
 * @package     iTopORM
 */
abstract class AttributeDefinition
{
	const SEARCH_WIDGET_TYPE_RAW              = 'raw';
	const SEARCH_WIDGET_TYPE_STRING           = 'string';
	const SEARCH_WIDGET_TYPE_NUMERIC          = 'numeric';
	const SEARCH_WIDGET_TYPE_ENUM             = 'enum';
	const SEARCH_WIDGET_TYPE_EXTERNAL_KEY     = 'external_key';
	const SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY = 'hierarchical_key';
	const SEARCH_WIDGET_TYPE_EXTERNAL_FIELD   = 'external_field';
	const SEARCH_WIDGET_TYPE_DATE_TIME        = 'date_time';
	const SEARCH_WIDGET_TYPE_DATE             = 'date';
	const SEARCH_WIDGET_TYPE_SET              = 'set';
	const SEARCH_WIDGET_TYPE_TAG_SET          = 'tag_set';


	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	const INDEX_LENGTH = 95;

	protected $aCSSClasses;

	public function GetType()
	{
		return Dict::S('Core:'.get_class($this));
	}

	public function GetTypeDesc()
	{
		return Dict::S('Core:'.get_class($this).'+');
	}

	abstract public function GetEditClass();

	/**
	 * @return array Css classes
	 * @since 3.1.0 N°3190
	 */
	public function GetCssClasses(): array
	{
		return $this->aCSSClasses;
	}

	/**
	 * Return the search widget type corresponding to this attribute
	 *
	 * @return string
	 */
	public function GetSearchType()
	{
		return static::SEARCH_WIDGET_TYPE;
	}

	/**
	 * @return bool
	 */
	public function IsSearchable()
	{
		return $this->GetSearchType() != static::SEARCH_WIDGET_TYPE_RAW;
	}

	/** @var string */
	protected $m_sCode;
	/** @var array */
	protected $m_aParams;
	/** @var string */
	protected $m_sHostClass = '!undefined!';

	public function Get($sParamName)
	{
		return $this->m_aParams[$sParamName];
	}

	public function GetIndexLength()
	{
		$iMaxLength = $this->GetMaxSize();
		if (is_null($iMaxLength)) {
			return null;
		}
		if ($iMaxLength > static::INDEX_LENGTH) {
			return static::INDEX_LENGTH;
		}

		return $iMaxLength;
	}

	public function IsParam($sParamName)
	{
		return (array_key_exists($sParamName, $this->m_aParams));
	}

	protected function GetOptional($sParamName, $default)
	{
		if (array_key_exists($sParamName, $this->m_aParams)) {
			return $this->m_aParams[$sParamName];
		} else {
			return $default;
		}
	}

	/**
	 * AttributeDefinition constructor.
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws Exception
	 */
	public function __construct($sCode, $aParams)
	{
		$this->m_sCode = $sCode;
		$this->m_aParams = $aParams;
		$this->ConsistencyCheck();
		$this->aCSSClasses = array('attribute');
	}

	public function GetParams()
	{
		return $this->m_aParams;
	}

	public function HasParam($sParam)
	{
		return array_key_exists($sParam, $this->m_aParams);
	}

	public function SetHostClass($sHostClass)
	{
		$this->m_sHostClass = $sHostClass;
	}

	public function GetHostClass()
	{
		return $this->m_sHostClass;
	}

	/**
	 * @return array
	 *
	 * @throws \CoreException
	 */
	public function ListSubItems()
	{
		$aSubItems = array();
		foreach (MetaModel::ListAttributeDefs($this->m_sHostClass) as $sAttCode => $oAttDef) {
			if ($oAttDef instanceof AttributeSubItem) {
				if ($oAttDef->Get('target_attcode') == $this->m_sCode) {
					$aSubItems[$sAttCode] = $oAttDef;
				}
			}
		}

		return $aSubItems;
	}

	// Note: I could factorize this code with the parameter management made for the AttributeDef class
	// to be overloaded
	public static function ListExpectedParams()
	{
		return array();
	}

	/**
	 * @throws Exception
	 */
	protected function ConsistencyCheck()
	{
		// Check that any mandatory param has been specified
		//
		$aExpectedParams = static::ListExpectedParams();
		foreach ($aExpectedParams as $sParamName) {
			if (!array_key_exists($sParamName, $this->m_aParams)) {
				$aBacktrace = debug_backtrace();
				$sTargetClass = $aBacktrace[2]["class"];
				$sCodeInfo = $aBacktrace[1]["file"]." - ".$aBacktrace[1]["line"];
				throw new Exception("ERROR missing parameter '$sParamName' in ".get_class($this)." declaration for class $sTargetClass ($sCodeInfo)");
			}
		}
	}

	/**
	 * Check the validity of the given value
	 *
	 * @param \DBObject $oHostObject
	 * @param $value Object error if any, null otherwise
	 *
	 * @return bool|string true for no errors, false or error message otherwise
	 */
	public function CheckValue(DBObject $oHostObject, $value)
	{
		// later: factorize here the cases implemented into DBObject
		return true;
	}

	// table, key field, name field

	public function GetFinalAttDef()
	{
		return $this;
	}

	/**
	 * Deprecated - use IsBasedOnDBColumns instead
	 *
	 * @return bool
	 */
	public function IsDirectField()
	{
		return static::IsBasedOnDBColumns();
	}

	/**
	 * Returns true if the attribute value is built after DB columns
	 *
	 * @return bool
	 */
	public static function IsBasedOnDBColumns()
	{
		return false;
	}

	/**
	 * Returns true if the attribute value is built after other attributes by the mean of an expression (obtained via
	 * GetOQLExpression)
	 *
	 * @return bool
	 */
	public static function IsBasedOnOQLExpression()
	{
		return false;
	}

	/**
	 * Returns true if the attribute value can be shown as a string
	 *
	 * @return bool
	 */
	public static function IsScalar()
	{
		return false;
	}

	/**
	 * Returns true if the attribute can be used in bulk modify.
	 *
	 * @return bool
	 * @since 3.1.0 N°3190
	 *
	 */
	public static function IsBulkModifyCompatible(): bool
	{
		return static::IsScalar();
	}

	/**
	 * Returns true if the attribute value is a set of related objects (1-N or N-N)
	 *
	 * @return bool
	 */
	public static function IsLinkSet()
	{
		return false;
	}

	/**
	 * @param int $iType
	 *
	 * @return bool true if the attribute is an external key, either directly (RELATIVE to the host class), or
	 *     indirectly (ABSOLUTELY)
	 */
	public function IsExternalKey($iType = EXTKEY_RELATIVE)
	{
		return false;
	}

	/**
	 * @return bool true if the attribute value is an external key, pointing to the host class
	 */
	public static function IsHierarchicalKey()
	{
		return false;
	}

	/**
	 * @return bool true if the attribute value is stored on an object pointed to be an external key
	 */
	public static function IsExternalField()
	{
		return false;
	}

	/**
	 * @see \DBObject::IsAttributeReadOnlyForCurrentState() for a specific object instance (depending on its workflow)
	 * @return bool true if the attribute can be written (by essence : metamodel field option)
	 */
	public function IsWritable()
	{
		return false;
	}

	/**
	 * @return bool true if the attribute has been added automatically by the framework
	 */
	public function IsMagic()
	{
		return $this->GetOptional('magic', false);
	}

	/**
	 * @return bool true if the attribute value is kept in the loaded object (in memory)
	 */
	public static function LoadInObject()
	{
		return true;
	}

	/**
	 * @return bool true if the attribute value comes from the database in one way or another
	 */
	public static function LoadFromClassTables()
	{
		return true;
	}

	/**
	 * Write attribute values outside the current class tables
	 *
	 * @param \DBObject $oHostObject
	 *
	 * @return void
	 * @since 3.1.0 Method creation, to offer a generic method for all attributes - before we were calling directly \AttributeCustomFields::WriteValue
	 *
	 * @used-by \DBObject::WriteExternalAttributes()
	 */
	public function WriteExternalValues(DBObject $oHostObject): void
	{
	}

	/**
	 * Read the data from where it has been stored (outside the current class tables).
	 * This verb must be implemented as soon as LoadFromClassTables returns false and LoadInObject returns true
	 *
	 * @param DBObject $oHostObject
	 *
	 * @return mixed|null
	 * @since 3.1.0
	 */
	public function ReadExternalValues(DBObject $oHostObject)
	{
		return null;
	}

	/**
	 * Cleanup data upon object deletion (outside the current class tables)
	 * object id still available here
	 *
	 * @param \DBObject $oHostObject
	 *
	 * @since 3.1.0
	 */
	public function DeleteExternalValues(DBObject $oHostObject): void
	{
	}

	/**
	 * @return bool true if the attribute should be loaded anytime (in addition to the column selected by the user)
	 */
	public function AlwaysLoadInTables()
	{
		return $this->GetOptional('always_load_in_tables', false);
	}

	/**
	 * @param \DBObject $oHostObject
	 *
	 * @return mixed Must return the value if LoadInObject returns false
	 */
	public function GetValue($oHostObject)
	{
		return null;
	}

	/**
	 * Returns true if the attribute must not be stored if its current value is "null" (Cf. IsNull())
	 *
	 * @return bool
	 */
	public function IsNullAllowed()
	{
		return true;
	}

	/**
	 * Returns the attribute code (identifies the attribute in the host class)
	 *
	 * @return string
	 */
	public function GetCode()
	{
		return $this->m_sCode;
	}

	/**
	 * Find the corresponding "link" attribute on the target class, if any
	 *
	 * @return null | AttributeDefinition
	 */
	public function GetMirrorLinkAttribute()
	{
		return null;
	}

	/**
	 * Helper to browse the hierarchy of classes, searching for a label
	 *
	 * @param string $sDictEntrySuffix
	 * @param string $sDefault
	 * @param bool $bUserLanguageOnly
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function SearchLabel($sDictEntrySuffix, $sDefault, $bUserLanguageOnly)
	{
		$sLabel = Dict::S('Class:'.$this->m_sHostClass.$sDictEntrySuffix, '', $bUserLanguageOnly);
		if (strlen($sLabel) == 0) {
			// Nothing found: go higher in the hierarchy (if possible)
			//
			$sLabel = $sDefault;
			$sParentClass = MetaModel::GetParentClass($this->m_sHostClass);
			if ($sParentClass) {
				if (MetaModel::IsValidAttCode($sParentClass, $this->m_sCode)) {
					$oAttDef = MetaModel::GetAttributeDef($sParentClass, $this->m_sCode);
					$sLabel = $oAttDef->SearchLabel($sDictEntrySuffix, $sDefault, $bUserLanguageOnly);
				}
			}
		}

		return $sLabel;
	}

	/**
	 * @param string|null $sDefault if null, will return the attribute code replacing "_" by " "
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function GetLabel($sDefault = null)
	{
		$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode, null, true /*user lang*/);
		if (is_null($sLabel)) {
			// If no default value is specified, let's define the most relevant one for developping purposes
			if (is_null($sDefault)) {
				$sDefault = str_replace('_', ' ', $this->m_sCode);
			}
			// Browse the hierarchy again, accepting default (english) translations
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode, $sDefault, false);
		}

		return $sLabel;
	}

	/**
	 * To be overloaded for localized enums
	 *
	 * @param string $sValue
	 *
	 * @return string label corresponding to the given value (in plain text)
	 */
	public function GetValueLabel($sValue)
	{
		return $sValue;
	}

	/**
	 * Get the value from a given string (plain text, CSV import)
	 *
	 * @param string $sProposedValue
	 * @param bool $bLocalizedValue
	 * @param string $sSepItem
	 * @param string $sSepAttribute
	 * @param string $sSepValue
	 * @param string $sAttributeQualifier
	 *
	 * @return mixed null if no match could be found
	 */
	public function MakeValueFromString(
		$sProposedValue,
		$bLocalizedValue = false,
		$sSepItem = null,
		$sSepAttribute = null,
		$sSepValue = null,
		$sAttributeQualifier = null
	)
	{
		return $this->MakeRealValue($sProposedValue, null);
	}

	/**
	 * Parses a search string coming from user input
	 *
	 * @param string $sSearchString
	 *
	 * @return string
	 */
	public function ParseSearchString($sSearchString)
	{
		return $sSearchString;
	}

	/**
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function GetLabel_Obsolete()
	{
		// Written for compatibility with a data model written prior to version 0.9.1
		if (array_key_exists('label', $this->m_aParams)) {
			return $this->m_aParams['label'];
		} else {
			return $this->GetLabel();
		}
	}

	/**
	 * @param string|null $sDefault
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function GetDescription($sDefault = null)
	{
		$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'+', null, true /*user lang*/);
		if (is_null($sLabel)) {
			// If no default value is specified, let's define the most relevant one for developping purposes
			if (is_null($sDefault)) {
				$sDefault = '';
			}
			// Browse the hierarchy again, accepting default (english) translations
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'+', $sDefault, false);
		}

		return $sLabel;
	}

	/**
	 * @return bool True if the attribute has a description {@see \AttributeDefinition::GetDescription()}
	 * @throws \Exception
	 * @since 3.1.0
	 */
	public function HasDescription(): bool
	{
		return utils::IsNotNullOrEmptyString($this->GetDescription());
	}

	/**
	 * @param string|null $sDefault
	 *
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function GetHelpOnEdition($sDefault = null)
	{
		$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'?', null, true /*user lang*/);
		if (is_null($sLabel)) {
			// If no default value is specified, let's define the most relevant one for developping purposes
			if (is_null($sDefault)) {
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
		foreach ($aParents as $sClass) {
			$sHelp = Dict::S("Core:$sClass?SmartSearch", '-missing-');
			if ($sHelp != '-missing-') {
				return $sHelp;
			}
		}

		return '';
	}

	/**
	 * @return string
	 *
	 * @throws \Exception
	 */
	public function GetDescription_Obsolete()
	{
		// Written for compatibility with a data model written prior to version 0.9.1
		if (array_key_exists('description', $this->m_aParams)) {
			return $this->m_aParams['description'];
		} else {
			return $this->GetDescription();
		}
	}

	public function GetTrackingLevel()
	{
		return $this->GetOptional('tracking_level', ATTRIBUTE_TRACKING_ALL);
	}

	/**
	 * @return \ValueSetObjects
	 */
	public function GetValuesDef()
	{
		return null;
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		return array();
	}

	public function GetNullValue()
	{
		return null;
	}

	public function IsNull($proposedValue)
	{
		return is_null($proposedValue);
	}

	/**
	 * @param mixed $proposedValue
	 *
	 * @return bool True if $proposedValue is an actual value set in the attribute, false is the attribute remains "empty"
	 * @since 3.0.3, 3.1.0 N°5784
	 */
	public function HasAValue($proposedValue): bool
	{
		// Default implementation, we don't really know what type $proposedValue will be
		return !(is_null($proposedValue));
	}

	/**
	 * force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!
	 *
	 * @param mixed $proposedValue
	 * @param \DBObject $oHostObj
	 *
	 * @return mixed
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		return $proposedValue;
	}

	public function Equals($val1, $val2)
	{
		return ($val1 == $val2);
	}

	/**
	 * @param string $sPrefix
	 *
	 * @return array suffix/expression pairs (1 in most of the cases), for READING (Select)
	 */
	public function GetSQLExpressions($sPrefix = '')
	{
		return array();
	}

	/**
	 * @param array $aCols
	 * @param string $sPrefix
	 *
	 * @return mixed a value out of suffix/value pairs, for SELECT result interpretation
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		return null;
	}

	/**
	 * @see \CMDBSource::GetFieldSpec()
	 *
	 * @param bool $bFullSpec
	 *
	 * @return array column/spec pairs (1 in most of the cases), for STRUCTURING (DB creation)
	 */
	public function GetSQLColumns($bFullSpec = false)
	{
		return array();
	}

	/**
	 * @param $value
	 *
	 * @return array column/value pairs (1 in most of the cases), for WRITING (Insert, Update)
	 */
	public function GetSQLValues($value)
	{
		return array();
	}

	public function RequiresIndex()
	{
		return false;
	}

	public function RequiresFullTextIndex()
	{
		return false;
	}

	public function CopyOnAllTables()
	{
		return false;
	}

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
	public function GetImportColumns()
	{
		return $this->GetSQLColumns();
	}

	public function FromImportToValue($aCols, $sPrefix = '')
	{
		$aValues = array();
		foreach ($this->GetSQLExpressions($sPrefix) as $sAlias => $sExpr) {
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

	abstract public function GetDefaultValue(DBObject $oHostObject = null);

	//
	// To be overloaded in subclasses
	//

	abstract public function GetBasicFilterOperators(); // returns an array of "opCode"=>"description"

	abstract public function GetBasicFilterLooseOperator(); // returns an "opCode"

	//abstract protected GetBasicFilterHTMLInput();
	abstract public function GetBasicFilterSQLExpr($sOpCode, $value);

	public function GetMagicFields()
	{
		return [];
	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		return (string)$sValue;
	}

	/**
	 * For fields containing a potential markup, return the value without this markup
	 *
	 * @param string $sValue
	 * @param \DBObject $oHostObj
	 *
	 * @return string
	 */
	public function GetAsPlainText($sValue, $oHostObj = null)
	{
		return (string)$this->GetEditValue($sValue, $oHostObj);
	}

	/**
	 * Helper to get a value that will be JSON encoded
	 *
	 * @see FromJSONToValue for the reverse operation
	 *
	 * @param mixed $value field value
	 *
	 * @return string|array PHP struct that can be properly encoded
	 *
	 */
	public function GetForJSON($value)
	{
		// In most of the cases, that will be the expected behavior...
		return $this->GetEditValue($value);
	}

	/**
	 * Helper to form a value, given JSON decoded data. This way the attribute itself handles the transformation from the JSON structure to the expected data (the one that
	 * needs to be used in the {@see \DBObject::Set()} method).
	 *
	 * Note that for CSV and XML this isn't done yet (no delegation to the attribute but switch/case inside controllers) :/
	 *
	 * @see GetForJSON for the reverse operation
	 *
	 * @param string $json JSON encoded value
	 *
	 * @return mixed JSON decoded data, depending on the attribute type
	 *
	 */
	public function FromJSONToValue($json)
	{
		// Pass-through in most of the cases
		return $json;
	}

	/**
	 * Override to display the value in the GUI
	 *
	 * @param string $sValue
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 */
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2html((string)$sValue);
	}

	/**
	 * Override to export the value in XML
	 *
	 * @param string $sValue
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return mixed
	 */
	public function GetAsXML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2xml((string)$sValue);
	}

	/**
	 * Override to escape the value when read by DBObject::GetAsCSV()
	 *
	 * @param string $sValue
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return string
	 */
	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	)
	{
		return (string)$sValue;
	}

	/**
	 * Override to differentiate a value displayed in the UI or in the history
	 *
	 * @param string $sValue
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 */
	public function GetAsHTMLForHistory($sValue, $oHostObject = null, $bLocalize = true)
	{
		return $this->GetAsHTML($sValue, $oHostObject, $bLocalize);
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\StringField';
	}

	/**
	 * Override to specify Field class
	 *
	 * When called first, $oFormField is null and will be created (eg. Make). Then when the ::parent is called and the
	 * $oFormField is passed, MakeFormField behave more like a Prepare.
	 *
	 * @param DBObject $oObject
	 * @param Field|null $oFormField
	 *
	 * @return Field
	 * @throws CoreException
	 * @throws Exception
	 *
	 * @noinspection PhpMissingReturnTypeInspection
	 * @noinspection PhpMissingParamTypeInspection
	 * @noinspection ReturnTypeCanBeDeclaredInspection
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		// This is a fallback in case the AttributeDefinition subclass has no overloading of this function.
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
			//$oFormField->SetReadOnly(true);
		}

		$oFormField->SetLabel($this->GetLabel());

		// Attributes flags
		// - Retrieving flags for the current object
		if ($oObject->IsNew()) {
			$iFlags = $oObject->GetInitialStateAttributeFlags($this->GetCode());
		} else {
			$iFlags = $oObject->GetAttributeFlags($this->GetCode());
		}

		// - Comparing flags
		if ($this->IsWritable() && (!$this->IsNullAllowed() || (($iFlags & OPT_ATT_MANDATORY) === OPT_ATT_MANDATORY))) {
			$oFormField->SetMandatory(true);
		}
		if ((!$oObject->IsNew() || !$oFormField->GetMandatory()) && (($iFlags & OPT_ATT_READONLY) === OPT_ATT_READONLY)) {
			$oFormField->SetReadOnly(true);
		}

		// CurrentValue
		$oFormField->SetCurrentValue($oObject->Get($this->GetCode()));

		// Validation pattern
		if ($this->GetValidationPattern() !== '') {
			$oFormField->AddValidator(new \Combodo\iTop\Form\Validator\CustomRegexpValidator($this->GetValidationPattern()));
		}

		// Description
		$sAttDescription = $this->GetDescription();
		if (!empty($sAttDescription)) {
			$oFormField->SetDescription($this->GetDescription());
		}

		// Metadata
		$oFormField->AddMetadata('attribute-code', $this->GetCode());
		$oFormField->AddMetadata('attribute-type', get_class($this));
		$oFormField->AddMetadata('attribute-label', $this->GetLabel());
		// - Attribute flags
		$aPossibleAttFlags = MetaModel::EnumPossibleAttributeFlags();
		foreach ($aPossibleAttFlags as $sFlagCode => $iFlagValue) {
			// Note: Skip normal flag as we don't need it.
			if ($sFlagCode === 'normal') {
				continue;
			}
			$sFormattedFlagCode = str_ireplace('_', '-', $sFlagCode);
			$sFormattedFlagValue = (($iFlags & $iFlagValue) === $iFlagValue) ? 'true' : 'false';
			$oFormField->AddMetadata('attribute-flag-'.$sFormattedFlagCode, $sFormattedFlagValue);
		}
		// - Value raw
		if ($this::IsScalar()) {
			$oFormField->AddMetadata('value-raw', (string)$oObject->Get($this->GetCode()));
		}

		// We don't want to invalidate field because of old untouched values that are no longer valid
		$aModifiedAttCodes = $oObject->ListChanges();
		$bAttributeHasBeenModified = array_key_exists($this->GetCode(), $aModifiedAttCodes);
		if (false === $bAttributeHasBeenModified) {
			$oFormField->SetValidationDisabled(true);
		}

		return $oFormField;
	}

	/**
	 * List the available verbs for 'GetForTemplate'
	 */
	public function EnumTemplateVerbs()
	{
		return array(
			''      => 'Plain text (unlocalized) representation',
			'html'  => 'HTML representation',
			'label' => 'Localized representation',
			'text'  => 'Plain text representation (without any markup)',
		);
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 *
	 * @param mixed $value The current value of the field
	 * @param string $sVerb The verb specifying the representation of the value
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize Whether or not to localize the value
	 *
	 * @return mixed|null|string
	 *
	 * @throws Exception
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		if ($this->IsScalar()) {
			switch ($sVerb) {
				case '':
					return $value;

				case 'html':
					return $this->GetAsHtml($value, $oHostObject, $bLocalize);

				case 'label':
					return $this->GetEditValue($value);

				case 'text':
					return $this->GetAsPlainText($value);
					break;

				default:
					throw new Exception("Unknown verb '$sVerb' for attribute ".$this->GetCode().' in class '.get_class($oHostObject));
			}
		}

		return null;
	}

	/**
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return array|null
	 * @throws CoreException
	 * @throws OQLException
	 */
	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$oValSetDef = $this->GetValuesDef();
		if (!$oValSetDef) {
			return null;
		}

		return $oValSetDef->GetValues($aArgs, $sContains);
	}

	/**
	 * GetAllowedValuesForSelect is the same as GetAllowedValues except for field with obsolescence flag
	 *
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return array|null
	 * @throws CoreException
	 * @throws OQLException
	 */
	public function GetAllowedValuesForSelect($aArgs = array(), $sContains = '')
	{
		return $this->GetAllowedValues($aArgs, $sContains);
	}

	/**
	 * Explain the change of the attribute (history)
	 *
	 * @param string $sOldValue
	 * @param string $sNewValue
	 * @param string $sLabel
	 *
	 * @return string
	 * @throws ArchivedObjectException
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 * @throws OQLException
	 * @throws Exception
	 */
	public function DescribeChangeAsHTML($sOldValue, $sNewValue, $sLabel = null)
	{
		if (is_null($sLabel)) {
			$sLabel = $this->GetLabel();
		}

		$sNewValueHtml = $this->GetAsHTMLForHistory($sNewValue);
		$sOldValueHtml = $this->GetAsHTMLForHistory($sOldValue);

		if ($this->IsExternalKey()) {
			/** @var \AttributeExternalKey $this */
			$sTargetClass = $this->GetTargetClass();
			$sOldValueHtml = (int)$sOldValue ? MetaModel::GetHyperLink($sTargetClass, (int)$sOldValue) : null;
			$sNewValueHtml = (int)$sNewValue ? MetaModel::GetHyperLink($sTargetClass, (int)$sNewValue) : null;
		}
		if ((($this->GetType() == 'String') || ($this->GetType() == 'Text')) &&
			(strlen($sNewValue) > strlen($sOldValue))) {
			// Check if some text was not appended to the field
			if (substr($sNewValue, 0, strlen($sOldValue)) == $sOldValue) // Text added at the end
			{
				$sDelta = $this->GetAsHTML(substr($sNewValue, strlen($sOldValue)));
				$sResult = Dict::Format('Change:Text_AppendedTo_AttName', $sDelta, $sLabel);
			} else {
				if (substr($sNewValue, -strlen($sOldValue)) == $sOldValue)   // Text added at the beginning
				{
					$sDelta = $this->GetAsHTML(substr($sNewValue, 0, strlen($sNewValue) - strlen($sOldValue)));
					$sResult = Dict::Format('Change:Text_AppendedTo_AttName', $sDelta, $sLabel);
				} else {
					if (strlen($sOldValue) == 0) {
						$sResult = Dict::Format('Change:AttName_SetTo', $sLabel, $sNewValueHtml);
					} else {
						if (is_null($sNewValue)) {
							$sNewValueHtml = Dict::S('UI:UndefinedObject');
						}
						$sResult = Dict::Format('Change:AttName_SetTo_NewValue_PreviousValue_OldValue', $sLabel,
							$sNewValueHtml, $sOldValueHtml);
					}
				}
			}
		} else {
			if (strlen($sOldValue) == 0) {
				$sResult = Dict::Format('Change:AttName_SetTo', $sLabel, $sNewValueHtml);
			} else {
				if (is_null($sNewValue)) {
					$sNewValueHtml = Dict::S('UI:UndefinedObject');
				}
				$sResult = Dict::Format('Change:AttName_SetTo_NewValue_PreviousValue_OldValue', $sLabel, $sNewValueHtml,
					$sOldValueHtml);
			}
		}

		return $sResult;
	}

	/**
	 * @param DBObject $oObject
	 * @param mixed $original
	 * @param mixed $value
	 *
	 * @throws ArchivedObjectException
	 * @throws CoreCannotSaveObjectException
	 * @throws CoreException if cannot create object
	 * @throws CoreUnexpectedValue
	 * @throws CoreWarning
	 * @throws MySQLException
	 * @throws OQLException
	 *
	 * @uses GetChangeRecordAdditionalData
	 * @uses GetChangeRecordClassName
	 *
	 * @since 3.1.0 N°6042
	 */
	public function RecordAttChange(DBObject $oObject, $original, $value): void
	{
		/** @var CMDBChangeOp $oMyChangeOp */
		$oMyChangeOp = MetaModel::NewObject($this->GetChangeRecordClassName());
		$oMyChangeOp->Set("objclass", get_class($oObject));
		$oMyChangeOp->Set("objkey", $oObject->GetKey());
		$oMyChangeOp->Set("attcode", $this->GetCode());

		$this->GetChangeRecordAdditionalData($oMyChangeOp, $oObject, $original, $value);

		$oMyChangeOp->DBInsertNoReload();
	}

	/**
	 * Add attribute specific information in the {@link \CMDBChangeOp} instance
	 *
	 * @param CMDBChangeOp $oMyChangeOp
	 * @param DBObject $oObject
	 * @param $original
	 * @param $value
	 *
	 * @return void
	 * @used-by RecordAttChange
	 */
	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		$oMyChangeOp->Set("oldvalue", $original);
		$oMyChangeOp->Set("newvalue", $value);
	}

	/**
	 * @return string name of the children of {@link CMDBChangeOp} class to use for the history record
	 * @used-by RecordAttChange
	 */
	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeScalar::class;
	}

	/**
	 * Parses a string to find some smart search patterns and build the corresponding search/OQL condition
	 * Each derived class is reponsible for defining and processing their own smart patterns, the base class
	 * does nothing special, and just calls the default (loose) operator
	 *
	 * @param string $sSearchText The search string to analyze for smart patterns
	 * @param FieldExpression $oField
	 * @param array $aParams Values of the query parameters
	 *
	 * @return Expression The search condition to be added (AND) to the current search
	 *
	 * @throws CoreException
	 */
	public function GetSmartConditionExpression($sSearchText, FieldExpression $oField, &$aParams)
	{
		$sParamName = $oField->GetParent().'_'.$oField->GetName();
		$oRightExpr = new VariableExpression($sParamName);
		$sOperator = $this->GetBasicFilterLooseOperator();
		switch ($sOperator) {
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

	/**
	 * Tells if an attribute is part of the unique fingerprint of the object (used for comparing two objects)
	 * All attributes which value is not based on a value from the object itself (like ExternalFields or LinkedSet)
	 * must be excluded from the object's signature
	 *
	 * @return boolean
	 */
	public function IsPartOfFingerprint()
	{
		return true;
	}

	/**
	 * The part of the current attribute in the object's signature, for the supplied value
	 *
	 * @param mixed $value The value of this attribute for the object
	 *
	 * @return string The "signature" for this field/attribute
	 */
	public function Fingerprint($value)
	{
		return (string)$value;
	}

	/*
	 * return string
	 */
	public function GetRenderForDataTable(string $sClassAlias): string
	{
		$sRenderFunction = "return data;";

		return $sRenderFunction;
	}
}