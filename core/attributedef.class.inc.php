<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\FieldBadge\FieldBadgeUIBlockFactory;
use Combodo\iTop\Application\UI\Links\Set\BlockLinkSetDisplayAsProperty;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Form\Field\LabelField;
use Combodo\iTop\Form\Field\TextAreaField;
use Combodo\iTop\Form\Form;
use Combodo\iTop\Form\Validator\CustomRegexpValidator;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;
use Combodo\iTop\Service\Links\LinkSetModel;

require_once('MyHelpers.class.inc.php');
require_once('ormdocument.class.inc.php');
require_once('ormstopwatch.class.inc.php');
require_once('ormpassword.class.inc.php');
require_once('ormcaselog.class.inc.php');
require_once('ormlinkset.class.inc.php');
require_once('ormset.class.inc.php');
require_once('ormtagset.class.inc.php');
require_once('htmlsanitizer.class.inc.php');
require_once('customfieldshandler.class.inc.php');
require_once('ormcustomfieldsvalue.class.inc.php');
require_once('datetimeformat.class.inc.php');

/**
 * MissingColumnException - sent if an attribute is being created but the column is missing in the row
 *
 * @package     iTopORM
 */
class MissingColumnException extends Exception
{
}

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
 * Attributes implementing this interface won't be accepted as `group by` field
 *
 * @since 2.7.4 N°3473
 */
interface iAttributeNoGroupBy
{
	//no method, just a contract on implement
}

/**
 * Attribute definition API, implemented in and many flavours (Int, String, Enum, etc.)
 *
 * @package     iTopORM
 */
abstract class AttributeDefinition
{
	const SEARCH_WIDGET_TYPE_RAW = 'raw';
	const SEARCH_WIDGET_TYPE_STRING = 'string';
	const SEARCH_WIDGET_TYPE_NUMERIC = 'numeric';
	const SEARCH_WIDGET_TYPE_ENUM = 'enum';
	const SEARCH_WIDGET_TYPE_EXTERNAL_KEY = 'external_key';
	const SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY = 'hierarchical_key';
	const SEARCH_WIDGET_TYPE_EXTERNAL_FIELD = 'external_field';
	const SEARCH_WIDGET_TYPE_DATE_TIME = 'date_time';
	const SEARCH_WIDGET_TYPE_DATE = 'date';
	const SEARCH_WIDGET_TYPE_SET = 'set';
	const SEARCH_WIDGET_TYPE_TAG_SET = 'tag_set';


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
		if (is_null($iMaxLength))
		{
			return null;
		}
		if ($iMaxLength > static::INDEX_LENGTH)
		{
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
		if (array_key_exists($sParamName, $this->m_aParams))
		{
			return $this->m_aParams[$sParamName];
		}
		else
		{
			return $default;
		}
	}

	/**
	 * AttributeDefinition constructor.
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
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
	public static function ListExpectedParams()
	{
		return array();
	}

	/**
	 * @throws \Exception
	 */
	protected function ConsistencyCheck()
	{
		// Check that any mandatory param has been specified
		//
		$aExpectedParams = static::ListExpectedParams();
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

	/**
	 * @return string
	 * @deprecated never used
	 */
	public function ListDBJoins()
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();

		return "";
		// e.g: return array("Site", "infrid", "name");
	}

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
	 * @return bool true if the attribute can be written (by essence : metamodel field option)
	 * @see \DBObject::IsAttributeReadOnlyForCurrentState() for a specific object instance (depending on its workflow)
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
	) {
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
		if (array_key_exists('label', $this->m_aParams))
		{
			return $this->m_aParams['label'];
		}
		else
		{
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
		foreach($aParents as $sClass)
		{
			$sHelp = Dict::S("Core:$sClass?SmartSearch", '-missing-');
			if ($sHelp != '-missing-')
			{
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
	 * @param bool $bFullSpec
	 *
	 * @return array column/spec pairs (1 in most of the cases), for STRUCTURING (DB creation)
	 * @see \CMDBSource::GetFieldSpec()
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
		foreach($this->GetSQLExpressions($sPrefix) as $sAlias => $sExpr)
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

	/**
	 * @return mixed|null
	 * @deprecated never used
	 */
	public function MakeValue()
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();
		$sComputeFunc = $this->Get("compute_func");
		if (empty($sComputeFunc)) {
			return null;
		}

		return call_user_func($sComputeFunc);
	}

	abstract public function GetDefaultValue(DBObject $oHostObject = null);

	//
	// To be overloaded in subclasses
	//

	abstract public function GetBasicFilterOperators(); // returns an array of "opCode"=>"description"

	abstract public function GetBasicFilterLooseOperator(); // returns an "opCode"

	//abstract protected GetBasicFilterHTMLInput();
	abstract public function GetBasicFilterSQLExpr($sOpCode, $value);

	/**
	 *  since 3.1.0 return has changed (N°4690 - Deprecate "FilterCodes")
	 *
	 * @return array filtercode => attributecode
	 */
	public function GetFilterDefinitions()
	{
		return array();
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
	) {
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
	 * @param \DBObject $oObject
	 * @param \Combodo\iTop\Form\Field\Field|null $oFormField
	 *
	 * @return \Combodo\iTop\Form\Field\Field
	 * @throws \CoreException
	 * @throws \Exception
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
			$oFormField->AddValidator(new CustomRegexpValidator($this->GetValidationPattern()));
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
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize Whether or not to localize the value
	 *
	 * @return mixed|null|string
	 *
	 * @throws \Exception
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		if ($this->IsScalar())
		{
			switch ($sVerb)
			{
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
 * @throws \CoreException
 * @throws \OQLException
 */
	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$oValSetDef = $this->GetValuesDef();
		if (!$oValSetDef)
		{
			return null;
		}

		return $oValSetDef->GetValues($aArgs, $sContains);
	}

	/**
	 * GetAllowedValuesForSelect is the same as GetAllowedValues except for field with obsolescence flag
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return array|null
	 * @throws \CoreException
	 * @throws \OQLException
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
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function DescribeChangeAsHTML($sOldValue, $sNewValue, $sLabel = null)
	{
		if (is_null($sLabel))
		{
			$sLabel = $this->GetLabel();
		}

		$sNewValueHtml = $this->GetAsHTMLForHistory($sNewValue);
		$sOldValueHtml = $this->GetAsHTMLForHistory($sOldValue);

		if ($this->IsExternalKey())
		{
			/** @var \AttributeExternalKey $this */
			$sTargetClass = $this->GetTargetClass();
			$sOldValueHtml = (int)$sOldValue ? MetaModel::GetHyperLink($sTargetClass, (int)$sOldValue) : null;
			$sNewValueHtml = (int)$sNewValue ? MetaModel::GetHyperLink($sTargetClass, (int)$sNewValue) : null;
		}
		if ((($this->GetType() == 'String') || ($this->GetType() == 'Text')) &&
			(strlen($sNewValue) > strlen($sOldValue)))
		{
			// Check if some text was not appended to the field
			if (substr($sNewValue, 0, strlen($sOldValue)) == $sOldValue) // Text added at the end
			{
				$sDelta = $this->GetAsHTML(substr($sNewValue, strlen($sOldValue)));
				$sResult = Dict::Format('Change:Text_AppendedTo_AttName', $sDelta, $sLabel);
			}
			else
			{
				if (substr($sNewValue, -strlen($sOldValue)) == $sOldValue)   // Text added at the beginning
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
						$sResult = Dict::Format('Change:AttName_SetTo_NewValue_PreviousValue_OldValue', $sLabel,
							$sNewValueHtml, $sOldValueHtml);
					}
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
				$sResult = Dict::Format('Change:AttName_SetTo_NewValue_PreviousValue_OldValue', $sLabel, $sNewValueHtml,
					$sOldValueHtml);
			}
		}

		return $sResult;
	}

	/**
	 * @param \DBObject $oObject
	 * @param mixed $original
	 * @param mixed $value
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException if cannot create object
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
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
	 * @param \CMDBChangeOp $oMyChangeOp
	 * @param \DBObject $oObject
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
	 * @return string name of the children of {@link \CMDBChangeOp} class to use for the history record
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
	 * @param \FieldExpression $oField
	 * @param array $aParams Values of the query parameters
	 *
	 * @return \Expression The search condition to be added (AND) to the current search
	 *
	 * @throws \CoreException
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
	public function GetRenderForDataTable(string $sClassAlias) :string
	{
		$sRenderFunction = "return data;";
		return $sRenderFunction;
	}
}

class AttributeDashboard extends AttributeDefinition
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(),
			array("definition_file", "is_user_editable"));
	}

	public function GetDashboard()
	{
		$sAttCode = $this->GetCode();
		$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $sAttCode);
		$sFilePath = APPROOT.'env-'.utils::GetCurrentEnvironment().'/'.$this->Get('definition_file');
		return RuntimeDashboard::GetDashboard($sFilePath, $sClass.'__'.$sAttCode);
	}

	public function IsUserEditable()
	{
		return $this->Get('is_user_editable');
	}

	public function IsWritable()
	{
		return false;
	}

	public function GetEditClass()
	{
		return "";
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return null;
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
		return '';
	}

	/**
	 * @inheritdoc
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		return null;
	}

	// if this verb returns false, then GetValue must be implemented
	public static function LoadInObject()
	{
		return false;
	}

	public function GetValue($oHostObject)
	{
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		// Always return false for now, we don't consider a custom version of a dashboard
		return false;
	}
}

/**
 * Set of objects directly linked to an object, and being part of its definition
 *
 * @package     iTopORM
 */
class AttributeLinkedSet extends AttributeDefinition
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
		$this->aCSSClasses[] = 'attribute-set';
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(),
			array("allowed_values", "depends_on", "linked_class", "ext_key_to_me", "count_min", "count_max"));
	}

	public function GetEditClass()
	{
		return "LinkedSet";
	}

	/** @inheritDoc */
	public static function IsBulkModifyCompatible(): bool
	{
		return false;
	}

	public function IsWritable()
	{
		return true;
	}

	public static function IsLinkSet()
	{
		return true;
	}

	public function IsIndirect()
	{
		return false;
	}

	public function GetValuesDef()
	{
		$oValSetDef = $this->Get("allowed_values");
		if (!$oValSetDef) {
			// Let's propose every existing value
			$oValSetDef = new ValueSetObjects('SELECT '.LinkSetModel::GetTargetClass($this));
		}

		return $oValSetDef;
	}

	public function GetEditValue($value, $oHostObj = null)
	{
		/** @var ormLinkSet $value * */
		if ($value->Count() === 0) {
			return '';
		}

		/** Return linked objects key as string **/
		$aValues = $value->GetValues();

		return implode(' ', $aValues);
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		return $this->Get("depends_on");
	}

	/**
	 * @param \DBObject|null $oHostObject
	 *
	 * @return \ormLinkSet
	 *
	 * @throws \Exception
	 * @throws \CoreException
	 * @throws \CoreWarning
	 */
	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		if ($oHostObject === null)
		{
			return null;
		}

		$sLinkClass = $this->GetLinkedClass();
		$sExtKeyToMe = $this->GetExtKeyToMe();

		// The class to target is not the current class, because if this is a derived class,
		// it may differ from the target class, then things start to become confusing
		/** @var \AttributeExternalKey $oRemoteExtKeyAtt */
		$oRemoteExtKeyAtt = MetaModel::GetAttributeDef($sLinkClass, $sExtKeyToMe);
		$sMyClass = $oRemoteExtKeyAtt->GetTargetClass();

		$oMyselfSearch = new DBObjectSearch($sMyClass);
		if ($oHostObject !== null)
		{
			$oMyselfSearch->AddCondition('id', $oHostObject->GetKey(), '=');
		}

		$oLinkSearch = new DBObjectSearch($sLinkClass);
		$oLinkSearch->AddCondition_PointingTo($oMyselfSearch, $sExtKeyToMe);
		if ($this->IsIndirect())
		{
			// Join the remote class so that the archive flag will be taken into account
			/** @var \AttributeLinkedSetIndirect $this */
			$sExtKeyToRemote = $this->GetExtKeyToRemote();
			/** @var \AttributeExternalKey $oExtKeyToRemote */
			$oExtKeyToRemote = MetaModel::GetAttributeDef($sLinkClass, $sExtKeyToRemote);
			$sRemoteClass = $oExtKeyToRemote->GetTargetClass();
			if (MetaModel::IsArchivable($sRemoteClass))
			{
				$oRemoteSearch = new DBObjectSearch($sRemoteClass);
				/** @var \AttributeLinkedSetIndirect $this */
				$oLinkSearch->AddCondition_PointingTo($oRemoteSearch, $this->GetExtKeyToRemote());
			}
		}
		$oLinks = new DBObjectSet($oLinkSearch);
		$oLinkSet = new ormLinkSet($this->GetHostClass(), $this->GetCode(), $oLinks);

		return $oLinkSet;
	}

	public function GetTrackingLevel()
	{
		return $this->GetOptional('tracking_level', MetaModel::GetConfig()->Get('tracking_level_linked_set_default'));
	}

	/**
	 * @return string see LINKSET_EDITMODE_* constants
	 */
	public function GetEditMode()
	{
		return $this->GetOptional('edit_mode', LINKSET_EDITMODE_ACTIONS);
	}	
	
	/**
	 * @return int see LINKSET_EDITWHEN_* constants
	 * @since 3.1.1 3.2.0 N°6385
	 */
	public function GetEditWhen(): int
	{
		return $this->GetOptional('edit_when', LINKSET_EDITWHEN_ALWAYS);
	}

	/**
	 * @return string see LINKSET_DISPLAY_STYLE_* constants
	 * @since 3.1.0 N°3190
	 */
	public function GetDisplayStyle()
	{
		$sDisplayStyle = $this->GetOptional('display_style', LINKSET_DISPLAY_STYLE_TAB);
		if ($sDisplayStyle === '') {
			$sDisplayStyle = LINKSET_DISPLAY_STYLE_TAB;
		}

		return $sDisplayStyle;
	}

	/**
	 * Indicates if the current Attribute has constraints (php constraints or datamodel constraints)
	 * @return bool true if Attribute has constraints
	 * @since 3.1.0 N°6228
	 */
	public function HasPHPConstraint(): bool
	{
		return $this->GetOptional('with_php_constraint', false);
	}

	/**
	 * @return bool true if Attribute has computation (DB_LINKS_CHANGED event propagation, `with_php_computation` attribute xml property), false otherwise
	 * @since 3.1.1 3.2.0 N°6228
	 */
	public function HasPHPComputation(): bool
	{
		return $this->GetOptional('with_php_computation', false);
	}
	
	public function GetLinkedClass()
	{
		return $this->Get('linked_class');
	}

	public function GetExtKeyToMe()
	{
		return $this->Get('ext_key_to_me');
	}

	public function GetBasicFilterOperators()
	{
		return array();
	}

	public function GetBasicFilterLooseOperator()
	{
		return '';
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return '';
	}

	/** @inheritDoc * */
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if($this->GetDisplayStyle() === LINKSET_DISPLAY_STYLE_TAB){
			return $this->GetAsHTMLForTab($sValue, $oHostObject, $bLocalize);
		}
		else{
			return $this->GetAsHTMLForProperty($sValue, $oHostObject, $bLocalize);
		}
	}

	public function GetAsHTMLForTab($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($sValue) && ($sValue instanceof ormLinkSet))
		{
			$sValue->Rewind();
			$aItems = array();
			while ($oObj = $sValue->Fetch())
			{
				// Show only relevant information (hide the external key to the current object)
				$aAttributes = array();
				foreach(MetaModel::ListAttributeDefs($this->GetLinkedClass()) as $sAttCode => $oAttDef)
				{
					if ($sAttCode == $this->GetExtKeyToMe())
					{
						continue;
					}
					if ($oAttDef->IsExternalField())
					{
						continue;
					}
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

	public function GetAsHTMLForProperty($sValue, $oHostObject = null, $bLocalize = true): string
	{
		try {

			/** @var ormLinkSet $sValue */
			if (is_null($sValue) || $sValue->Count() === 0) {
				return '';
			}

			$oLinkSetBlock = new BlockLinkSetDisplayAsProperty($this->GetCode(), $this, $sValue);

			return ConsoleBlockRenderer::RenderBlockTemplates($oLinkSetBlock);
		}
		catch (Exception $e) {
			$sMessage = "Error while displaying attribute {$this->GetCode()}";
			IssueLog::Error($sMessage, IssueLog::CHANNEL_DEFAULT, [
				'host_object_class' => $this->GetHostClass(),
				'host_object_key'   => $oHostObject->GetKey(),
				'attribute'         => $this->GetCode(),
			]);

			return $sMessage;
		}
	}

	/**
	 * @param string $sValue
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 *
	 * @throws \CoreException
	 */
	public function GetAsXML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($sValue) && ($sValue instanceof ormLinkSet))
		{
			$sValue->Rewind();
			$sRes = "<Set>\n";
			while ($oObj = $sValue->Fetch())
			{
				$sObjClass = get_class($oObj);
				$sRes .= "<$sObjClass id=\"".$oObj->GetKey()."\">\n";
				// Show only relevant information (hide the external key to the current object)
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
					if ($sAttCode == $this->GetExtKeyToMe())
					{
						continue;
					}
					if ($oAttDef->IsExternalField())
					{
						/** @var \AttributeExternalField $oAttDef */
						if ($oAttDef->GetKeyAttCode() == $this->GetExtKeyToMe())
						{
							continue;
						}
						/** @var AttributeExternalField $oAttDef */
						if ($oAttDef->IsFriendlyName())
						{
							continue;
						}
					}
					if ($oAttDef instanceof AttributeFriendlyName)
					{
						continue;
					}
					if (!$oAttDef->IsScalar())
					{
						continue;
					}
					$sAttValue = $oObj->GetAsXML($sAttCode, $bLocalize);
					$sRes .= "<$sAttCode>$sAttValue</$sAttCode>\n";
				}
				$sRes .= "</$sObjClass>\n";
			}
			$sRes .= "</Set>\n";
		}
		else
		{
			$sRes = '';
		}

		return $sRes;
	}

	/**
	 * @param $sValue
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return mixed|string
	 * @throws \CoreException
	 */
	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		$sSepItem = MetaModel::GetConfig()->Get('link_set_item_separator');
		$sSepAttribute = MetaModel::GetConfig()->Get('link_set_attribute_separator');
		$sSepValue = MetaModel::GetConfig()->Get('link_set_value_separator');
		$sAttributeQualifier = MetaModel::GetConfig()->Get('link_set_attribute_qualifier');

		if (is_object($sValue) && ($sValue instanceof ormLinkSet))
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
					if ($sAttCode == $this->GetExtKeyToMe())
					{
						continue;
					}
					if ($oAttDef->IsExternalField())
					{
						continue;
					}
					if (!$oAttDef->IsBasedOnDBColumns())
					{
						continue;
					}
					if (!$oAttDef->IsScalar())
					{
						continue;
					}
					$sAttValue = $oObj->GetAsCSV($sAttCode, $sSepValue, '', $bLocalize);
					if (strlen($sAttValue) > 0)
					{
						$sAttributeData = str_replace($sAttributeQualifier, $sAttributeQualifier.$sAttributeQualifier,
							$sAttCode.$sSepValue.$sAttValue);
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

	/**
	 * List the available verbs for 'GetForTemplate'
	 */
	public function EnumTemplateVerbs()
	{
		return array(
			'' => 'Plain text (unlocalized) representation',
			'html' => 'HTML representation (unordered list)',
		);
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 *
	 * @param mixed $value The current value of the field
	 * @param string $sVerb The verb specifying the representation of the value
	 * @param DBObject $oHostObject The object
	 * @param bool $bLocalize Whether or not to localize the value
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		$sRemoteName = $this->IsIndirect() ?
			/** @var \AttributeLinkedSetIndirect $this */
			$this->GetExtKeyToRemote().'_friendlyname' : 'friendlyname';

		$oLinkSet = clone $value; // Workaround/Safety net for Trac #887
		$iLimit = MetaModel::GetConfig()->Get('max_linkset_output');
		$iCount = 0;
		$aNames = array();
		foreach($oLinkSet as $oItem)
		{
			if (($iLimit > 0) && ($iCount == $iLimit))
			{
				$iTotal = $oLinkSet->Count();
				$aNames[] = '... '.Dict::Format('UI:TruncatedResults', $iCount, $iTotal);
				break;
			}
			$aNames[] = $oItem->Get($sRemoteName);
			$iCount++;
		}

		switch ($sVerb)
		{
			case '':
				return implode("\n", $aNames);

			case 'html':
				return '<ul><li>'.implode("</li><li>", $aNames).'</li></ul>';

			default:
				throw new Exception("Unknown verb '$sVerb' for attribute ".$this->GetCode().' in class '.get_class($oHostObject));
		}
	}

	public function DuplicatesAllowed()
	{
		return false;
	} // No duplicates for 1:n links, never

	public function GetImportColumns()
	{
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'TEXT'.CMDBSource::GetSqlStringColumnDefinition();

		return $aColumns;
	}

	/**
	 * @param string $sProposedValue
	 * @param bool $bLocalizedValue
	 * @param string $sSepItem
	 * @param string $sSepAttribute
	 * @param string $sSepValue
	 * @param string $sAttributeQualifier
	 *
	 * @return \DBObjectSet|mixed
	 * @throws \CSVParserException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \Exception
	 */
	public function MakeValueFromString(
		$sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null,
		$sAttributeQualifier = null
	) {
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
						throw new CoreException('Wrong attribute code for link attribute specification',
							array('class' => $sTargetClass, 'attcode' => $sKeyAttCode));
					}
					/** @var \AttributeExternalKey $oKeyAttDef */
					$oKeyAttDef = MetaModel::GetAttributeDef($sTargetClass, $sKeyAttCode);
					$sRemoteClass = $oKeyAttDef->GetTargetClass();
					if (!MetaModel::IsValidAttCode($sRemoteClass, $sRemoteAttCode))
					{
						throw new CoreException('Wrong attribute code for link attribute specification',
							array('class' => $sRemoteClass, 'attcode' => $sRemoteAttCode));
					}
				}
				else
				{
					if (!MetaModel::IsValidAttCode($sTargetClass, $sAttCode))
					{
						throw new CoreException('Wrong attribute code for link attribute specification',
							array('class' => $sTargetClass, 'attcode' => $sAttCode));
					}
					$oAttDef = MetaModel::GetAttributeDef($sTargetClass, $sAttCode);
					$aValues[$sAttCode] = $oAttDef->MakeValueFromString($sValue, $bLocalizedValue, $sSepItem,
						$sSepAttribute, $sSepValue, $sAttributeQualifier);
				}
			}

			// 2nd - Instanciate the object and set the value
			if (isset($aValues['finalclass']))
			{
				$sLinkClass = $aValues['finalclass'];
				if (!is_subclass_of($sLinkClass, $sTargetClass))
				{
					throw new CoreException('Wrong class for link attribute specification',
						array('requested_class' => $sLinkClass, 'expected_class' => $sTargetClass));
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
			foreach($aValues as $sAttCode => $sValue)
			{
				$oLink->Set($sAttCode, $sValue);
			}

			// 3rd - Set external keys from search conditions
			foreach($aExtKeys as $sKeyAttCode => $aReconciliation)
			{
				$oKeyAttDef = MetaModel::GetAttributeDef($sTargetClass, $sKeyAttCode);
				$sKeyClass = $oKeyAttDef->GetTargetClass();
				$oExtKeyFilter = new DBObjectSearch($sKeyClass);
				$aReconciliationDesc = array();
				foreach($aReconciliation as $sRemoteAttCode => $sValue)
				{
					$oExtKeyFilter->AddCondition($sRemoteAttCode, $sValue, '=');
					$aReconciliationDesc[] = "$sRemoteAttCode=$sValue";
				}
				$oExtKeySet = new DBObjectSet($oExtKeyFilter);
				switch ($oExtKeySet->Count())
				{
					case 0:
						$sReconciliationDesc = implode(', ', $aReconciliationDesc);
						throw new CoreException("Found no match",
							array('ext_key' => $sKeyAttCode, 'reconciliation' => $sReconciliationDesc));
						break;
					case 1:
						$oRemoteObj = $oExtKeySet->Fetch();
						$oLink->Set($sKeyAttCode, $oRemoteObj->GetKey());
						break;
					default:
						$sReconciliationDesc = implode(', ', $aReconciliationDesc);
						throw new CoreException("Found several matches",
							array('ext_key' => $sKeyAttCode, 'reconciliation' => $sReconciliationDesc));
					// Found several matches, ambiguous
				}
			}

			// Check (roughly) if such a link is valid
			$aErrors = array();
			foreach(MetaModel::ListAttributeDefs($sTargetClass) as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsExternalKey())
				{
					/** @var \AttributeExternalKey $oAttDef */
					if (($oAttDef->GetTargetClass() == $this->GetHostClass()) || (is_subclass_of($this->GetHostClass(),
							$oAttDef->GetTargetClass())))
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

	/**
	 * @inheritDoc
	 *
	 * @param \ormLinkSet $value
	 */
	public function GetForJSON($value)
	{
		$aRet = array();
		if (is_object($value) && ($value instanceof ormLinkSet))
		{
			$value->Rewind();
			while ($oObj = $value->Fetch())
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
					if ($sAttCode == $this->GetExtKeyToMe())
					{
						continue;
					}
					if ($oAttDef->IsExternalField())
					{
						continue;
					}
					if (!$oAttDef->IsBasedOnDBColumns())
					{
						continue;
					}
					if (!$oAttDef->IsScalar())
					{
						continue;
					}
					$attValue = $oObj->Get($sAttCode);
					$aAttributes[$sAttCode] = $oAttDef->GetForJSON($attValue);
				}
				$aRet[] = $aAttributes;
			}
		}

		return $aRet;
	}

	/**
	 * @inheritDoc
	 *
	 * @return \DBObjectSet
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 */
	public function FromJSONToValue($json)
	{
		$sTargetClass = $this->Get('linked_class');

		$aLinks = array();
		foreach($json as $aValues)
		{
			if (isset($aValues['finalclass']))
			{
				$sLinkClass = $aValues['finalclass'];
				if (!is_subclass_of($sLinkClass, $sTargetClass))
				{
					throw new CoreException('Wrong class for link attribute specification',
						array('requested_class' => $sLinkClass, 'expected_class' => $sTargetClass));
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
			foreach($aValues as $sAttCode => $sValue)
			{
				$oLink->Set($sAttCode, $sValue);
			}

			// Check (roughly) if such a link is valid
			$aErrors = array();
			foreach(MetaModel::ListAttributeDefs($sTargetClass) as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsExternalKey())
				{
					/** @var AttributeExternalKey $oAttDef */
					if (($oAttDef->GetTargetClass() == $this->GetHostClass()) || (is_subclass_of($this->GetHostClass(),
							$oAttDef->GetTargetClass())))
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

	/**
	 * @param $proposedValue
	 * @param $oHostObj
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if ($proposedValue === null)
		{
			$sLinkedClass = $this->GetLinkedClass();
			$aLinkedObjectsArray = array();
			$oSet = DBObjectSet::FromArray($sLinkedClass, $aLinkedObjectsArray);

			return new ormLinkSet(
				get_class($oHostObj),
				$this->GetCode(),
				$oSet
			);
		}

		return $proposedValue;
	}

	/**
	 * @param ormLinkSet $val1
	 * @param ormLinkSet $val2
	 *
	 * @return bool
	 */
	public function Equals($val1, $val2)
	{
		if ($val1 === $val2)
		{
			$bAreEquivalent = true;
		}
		else
		{
			$bAreEquivalent = ($val2->HasDelta() === false);
		}

		return $bAreEquivalent;
	}

	/**
	 * Find the corresponding "link" attribute on the target class, if any
	 *
	 * @return null | AttributeDefinition
	 * @throws \Exception
	 */
	public function GetMirrorLinkAttribute()
	{
		$oRemoteAtt = MetaModel::GetAttributeDef($this->GetLinkedClass(), $this->GetExtKeyToMe());

		return $oRemoteAtt;
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\LinkedSetField';
	}

	/**
	 * @param \DBObject $oObject
	 * @param \Combodo\iTop\Form\Field\LinkedSetField $oFormField
	 *
	 * @return \Combodo\iTop\Form\Field\LinkedSetField
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		// Setting target class
		if (!$this->IsIndirect()) {
			$sTargetClass = $this->GetLinkedClass();
		} else {
			/** @var \AttributeExternalKey $oRemoteAttDef */
			/** @var \AttributeLinkedSetIndirect $this */
			$oRemoteAttDef = MetaModel::GetAttributeDef($this->GetLinkedClass(), $this->GetExtKeyToRemote());
			$sTargetClass = $oRemoteAttDef->GetTargetClass();

			/** @var \AttributeLinkedSetIndirect $this */
			$oFormField->SetExtKeyToRemote($this->GetExtKeyToRemote());
		}
		$oFormField->SetTargetClass($sTargetClass);
		$oFormField->SetLinkedClass($this->GetLinkedClass());
		$oFormField->SetIndirect($this->IsIndirect());
		// Setting attcodes to display
		$aAttCodesToDisplay = MetaModel::FlattenZList(MetaModel::GetZListItems($sTargetClass, 'list'));
		// - Adding friendlyname attribute to the list is not already in it
		$sTitleAttCode = MetaModel::GetFriendlyNameAttributeCode($sTargetClass);
		if (($sTitleAttCode !== null) && !in_array($sTitleAttCode, $aAttCodesToDisplay)) {
			$aAttCodesToDisplay = array_merge(array($sTitleAttCode), $aAttCodesToDisplay);
		}
		// - Adding attribute properties
		$aAttributesToDisplay = array();
		foreach ($aAttCodesToDisplay as $sAttCodeToDisplay) {
			$oAttDefToDisplay = MetaModel::GetAttributeDef($sTargetClass, $sAttCodeToDisplay);
			$aAttributesToDisplay[$sAttCodeToDisplay] = [
				'att_code' => $sAttCodeToDisplay,
				'label'    => $oAttDefToDisplay->GetLabel(),
			];
		}
		$oFormField->SetAttributesToDisplay($aAttributesToDisplay);

		// Append lnk attributes (filtered from zlist)
		if ($this->IsIndirect()) {
			$aLnkAttDefToDisplay = MetaModel::GetZListAttDefsFilteredForIndirectLinkClass($this->m_sHostClass, $this->m_sCode);
			$aLnkAttributesToDisplay = array();
			foreach ($aLnkAttDefToDisplay as $oLnkAttDefToDisplay) {
				$aLnkAttributesToDisplay[$oLnkAttDefToDisplay->GetCode()] = [
					'att_code'  => $oLnkAttDefToDisplay->GetCode(),
					'label'     => $oLnkAttDefToDisplay->GetLabel(),
					'mandatory' => !$oLnkAttDefToDisplay->IsNullAllowed(),
				];
			}
			$oFormField->SetLnkAttributesToDisplay($aLnkAttributesToDisplay);
		}

		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

    public function IsPartOfFingerprint()
    {
        return false;
    }

    /**
     * @inheritDoc
     * @param \ormLinkSet $proposedValue
     */
    public function HasAValue($proposedValue): bool
    {
        // Protection against wrong value type
        if (false === ($proposedValue instanceof ormLinkSet)) {
            return parent::HasAValue($proposedValue);
        }

        // We test if there is at least 1 item in the linkset (new or existing), not if an item is being added to it.
        return $proposedValue->Count() > 0;
    }

    /**
     * SearchSpecificLabel.
     *
     * @param string $sDictEntrySuffix
     * @param string $sDefault
     * @param bool $bUserLanguageOnly
     * @param ...$aArgs
     * @return string
     * @since 3.1.0
     */
    public function SearchSpecificLabel(string $sDictEntrySuffix, string $sDefault, bool $bUserLanguageOnly, ...$aArgs): string
    {
        try {
            $sNextClass = $this->m_sHostClass;

            do {
                $sKey = "Class:{$sNextClass}/Attribute:{$this->m_sCode}/{$sDictEntrySuffix}";
                if (Dict::S($sKey, null, $bUserLanguageOnly) !== $sKey) {
                    return Dict::Format($sKey, ...$aArgs);
                }
                $sNextClass = MetaModel::GetParentClass($sNextClass);
            } while ($sNextClass !== null);

            if (Dict::S($sDictEntrySuffix, null, $bUserLanguageOnly) !== $sKey) {
                return Dict::Format($sDictEntrySuffix, ...$aArgs);
            } else {
                return $sDefault;
            }
        } catch (Exception $e) {
            ExceptionLog::LogException($e);
            return $sDefault;
        }
    }
}

/**
 * Set of objects linked to an object (n-n), and being part of its definition
 *
 * @package     iTopORM
 */
class AttributeLinkedSetIndirect extends AttributeLinkedSet
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("ext_key_to_remote"));
	}

	public function IsIndirect()
	{
		return true;
	}

	public function GetExtKeyToRemote()
	{
		return $this->Get('ext_key_to_remote');
	}

	public function GetEditClass()
	{
		return "LinkedSet";
	}

	public function DuplicatesAllowed()
	{
		return $this->GetOptional("duplicates", false);
	} // The same object may be linked several times... or not...

	public function GetTrackingLevel()
	{
		return $this->GetOptional('tracking_level',
			MetaModel::GetConfig()->Get('tracking_level_linked_set_indirect_default'));
	}

	/**
	 * Find the corresponding "link" attribute on the target class, if any
	 *
	 * @return null | AttributeDefinition
	 * @throws \CoreException
	 */
	public function GetMirrorLinkAttribute()
	{
		$oRet = null;
		/** @var \AttributeExternalKey $oExtKeyToRemote */
		$oExtKeyToRemote = MetaModel::GetAttributeDef($this->GetLinkedClass(), $this->GetExtKeyToRemote());
		$sRemoteClass = $oExtKeyToRemote->GetTargetClass();
		foreach(MetaModel::ListAttributeDefs($sRemoteClass) as $sRemoteAttCode => $oRemoteAttDef) {
			if (!$oRemoteAttDef instanceof AttributeLinkedSetIndirect) {
				continue;
			}
			if ($oRemoteAttDef->GetLinkedClass() != $this->GetLinkedClass()) {
				continue;
			}
			if ($oRemoteAttDef->GetExtKeyToMe() != $this->GetExtKeyToRemote()) {
				continue;
			}
			if ($oRemoteAttDef->GetExtKeyToRemote() != $this->GetExtKeyToMe()) {
				continue;
			}
			$oRet = $oRemoteAttDef;
			break;
		}

		return $oRet;
	}

	/** @inheritDoc */
	public static function IsBulkModifyCompatible(): bool
	{
		return true;
	}

}

/**
 * Abstract class implementing default filters for a DB column
 *
 * @package     iTopORM
 */
class AttributeDBFieldVoid extends AttributeDefinition
{
	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("allowed_values", "depends_on", "sql"));
	}

	// To be overriden, used in GetSQLColumns
	protected function GetSQLCol($bFullSpec = false)
	{
		return 'VARCHAR(255)'
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	protected function GetSQLColSpec()
	{
		$default = $this->ScalarToSQL($this->GetDefaultValue());
		if (is_null($default))
		{
			$sRet = '';
		}
		else
		{
			if (is_numeric($default))
			{
				// Though it is a string in PHP, it will be considered as a numeric value in MySQL
				// Then it must not be quoted here, to preserve the compatibility with the value returned by CMDBSource::GetFieldSpec
				$sRet = " DEFAULT $default";
			}
			else
			{
				$sRet = " DEFAULT ".CMDBSource::Quote($default);
			}
		}

		return $sRet;
	}

	public function GetEditClass()
	{
		return "String";
	}

	public function GetValuesDef()
	{
		return $this->Get("allowed_values");
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		return $this->Get("depends_on");
	}

	public static function IsBasedOnDBColumns()
	{
		return true;
	}

	public static function IsScalar()
	{
		return true;
	}

	public function IsWritable()
	{
		return !$this->IsMagic();
	}

	public function GetSQLExpr()
	{
		return $this->Get("sql");
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return $this->MakeRealValue("", $oHostObject);
	}

	public function IsNullAllowed()
	{
		return false;
	}

	//
	protected function ScalarToSQL($value)
	{
		return $value;
	} // format value as a valuable SQL literal (quoted outside)

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

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->Get("sql")] = $this->GetSQLCol($bFullSpec);

		return $aColumns;
	}

	public function GetFilterDefinitions()
	{
		return array($this->GetCode() => $this->GetCode());
	}

	public function GetBasicFilterOperators()
	{
		return array("=" => "equals", "!=" => "differs from");
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
	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("default_value", "is_null_allowed"));
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return $this->MakeRealValue($this->Get("default_value"), $oHostObject);
	}

	public function IsNullAllowed()
	{
		return $this->Get("is_null_allowed");
	}
}

/**
 * Map an integer column to an attribute
 *
 * @package     iTopORM
 */
class AttributeInteger extends AttributeDBField
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_NUMERIC;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass()
	{
		return "String";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "INT(11)".($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	public function GetValidationPattern()
	{
		return "^[0-9]+$";
	}

	public function GetBasicFilterOperators()
	{
		return array(
			"!=" => "differs from",
			"=" => "equals",
			">" => "greater (strict) than",
			">=" => "greater than",
			"<" => "less (strict) than",
			"<=" => "less than",
			"in" => "in"
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
				if (!is_array($value))
				{
					throw new CoreException("Expected an array for argument value (sOpCode='$sOpCode')");
				}

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

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		return utils::IsNotNullOrEmptyString($proposedValue);
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue))
		{
			return null;
		}
		if ($proposedValue === '')
		{
			return null;
		} // 0 is transformed into '' !

		return (int)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		assert(is_numeric($value) || is_null($value));

		return $value; // supposed to be an int
	}
}

/**
 * An external key for which the class is defined as the value of another attribute
 *
 * @package     iTopORM
 */
class AttributeObjectKey extends AttributeDBFieldVoid
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_EXTERNAL_KEY;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('class_attcode', 'is_null_allowed'));
	}

	public function GetEditClass()
	{
		return "String";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "INT(11)".($bFullSpec ? " DEFAULT 0" : "");
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return 0;
	}

	public function IsNullAllowed()
	{
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

	public function GetNullValue()
	{
		return 0;
	}

	public function IsNull($proposedValue)
	{
		return ($proposedValue == 0);
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		return ((int) $proposedValue) !== 0;
	}

    /**
     * @inheritDoc
     *
     * @param int|DBObject $proposedValue Object key or valid ({@see MetaModel::IsValidObject()}) datamodel object
     */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue))
		{
			return 0;
		}
		if ($proposedValue === '')
		{
			return 0;
		}
		if (MetaModel::IsValidObject($proposedValue))
		{
			return $proposedValue->GetKey();
		}

		return (int)$proposedValue;
	}
}

/**
 * Display an integer between 0 and 100 as a percentage / horizontal bar graph
 *
 * @package     iTopORM
 */
class AttributePercentage extends AttributeInteger
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_NUMERIC;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$iWidth = 5; // Total width of the percentage bar graph, in em...
		$iValue = (int)$sValue;
		if ($iValue > 100)
		{
			$iValue = 100;
		}
		else
		{
			if ($iValue < 0)
			{
				$iValue = 0;
			}
		}
		if ($iValue > 90)
		{
			$sColor = "#cc3300";
		}
		else
		{
			if ($iValue > 50)
			{
				$sColor = "#cccc00";
			}
			else
			{
				$sColor = "#33cc00";
			}
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
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_NUMERIC;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('digits', 'decimals' /* including precision */));
	}

	public function GetEditClass()
	{
		return "String";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "DECIMAL(".$this->Get('digits').",".$this->Get('decimals').")".($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	public function GetValidationPattern()
	{
		$iNbDigits = $this->Get('digits');
		$iPrecision = $this->Get('decimals');
		$iNbIntegerDigits = $iNbDigits - $iPrecision;

		return "^[\-\+]?\d{1,$iNbIntegerDigits}(\.\d{0,$iPrecision})?$";
	}

	/**
	 * @inheritDoc
	 * @since 3.2.0
	 */
	public function CheckFormat($value)
	{
		$sRegExp = $this->GetValidationPattern();
		return preg_match("/$sRegExp/", $value);
	}

	public function GetBasicFilterOperators()
	{
		return array(
			"!=" => "differs from",
			"=" => "equals",
			">" => "greater (strict) than",
			">=" => "greater than",
			"<" => "less (strict) than",
			"<=" => "less than",
			"in" => "in"
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
				if (!is_array($value))
				{
					throw new CoreException("Expected an array for argument value (sOpCode='$sOpCode')");
				}

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

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		return utils::IsNotNullOrEmptyString($proposedValue);
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue))
		{
			return null;
		}
		if ($proposedValue === '')
		{
			return null;
		}

		return $this->ScalarToSQL($proposedValue);
	}

	public function ScalarToSQL($value)
	{
		assert(is_null($value) || preg_match('/'.$this->GetValidationPattern().'/', $value));

		if (!is_null($value) && ($value !== ''))
		{
			$value = sprintf("%1.".$this->Get('decimals')."F", $value);
		}
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
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass()
	{
		return "Integer";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "TINYINT(1)".($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue))
		{
			return null;
		}
		if ($proposedValue === '')
		{
			return null;
		}
		if ((int)$proposedValue)
		{
			return true;
		}

		return false;
	}

	public function ScalarToSQL($value)
	{
		if ($value)
		{
			return 1;
		}

		return 0;
	}

	public function GetValueLabel($bValue)
	{
		if (is_null($bValue))
		{
			$sLabel = Dict::S('Core:'.get_class($this).'/Value:null');
		}
		else
		{
			$sValue = $bValue ? 'yes' : 'no';
			$sDefault = Dict::S('Core:'.get_class($this).'/Value:'.$sValue);
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, $sDefault, true /*user lang*/);
		}

		return $sLabel;
	}

	public function GetValueDescription($bValue)
	{
		if (is_null($bValue))
		{
			$sDescription = Dict::S('Core:'.get_class($this).'/Value:null+');
		}
		else
		{
			$sValue = $bValue ? 'yes' : 'no';
			$sDefault = Dict::S('Core:'.get_class($this).'/Value:'.$sValue.'+');
			$sDescription = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue.'+', $sDefault,
				true /*user lang*/);
		}

		return $sDescription;
	}

	public function GetAsHTML($bValue, $oHostObject = null, $bLocalize = true)
	{
		if (is_null($bValue))
		{
			$sRes = '';
		}
		elseif ($bLocalize)
		{
			$sLabel = $this->GetValueLabel($bValue);
			$sDescription = $this->GetValueDescription($bValue);
			// later, we could imagine a detailed description in the title
			$sRes = "<span title=\"$sDescription\">".parent::GetAsHtml($sLabel)."</span>";
		}
		else
		{
			$sRes = $bValue ? 'yes' : 'no';
		}

		return $sRes;
	}

	public function GetAsXML($bValue, $oHostObject = null, $bLocalize = true)
	{
		if (is_null($bValue))
		{
			$sFinalValue = '';
		}
		elseif ($bLocalize)
		{
			$sFinalValue = $this->GetValueLabel($bValue);
		}
		else
		{
			$sFinalValue = $bValue ? 'yes' : 'no';
		}
		$sRes = parent::GetAsXML($sFinalValue, $oHostObject, $bLocalize);

		return $sRes;
	}

	public function GetAsCSV(
		$bValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		if (is_null($bValue))
		{
			$sFinalValue = '';
		}
		elseif ($bLocalize)
		{
			$sFinalValue = $this->GetValueLabel($bValue);
		}
		else
		{
			$sFinalValue = $bValue ? 'yes' : 'no';
		}
		$sRes = parent::GetAsCSV($sFinalValue, $sSeparator, $sTextQualifier, $oHostObject, $bLocalize);

		return $sRes;
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\SelectField';
	}

	/**
	 * @param \DBObject $oObject
	 * @param \Combodo\iTop\Form\Field\SelectField $oFormField
	 *
	 * @return \Combodo\iTop\Form\Field\SelectField
	 * @throws \CoreException
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		$oFormField->SetChoices(array('yes' => $this->GetValueLabel(true), 'no' => $this->GetValueLabel(false)));
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	public function GetEditValue($value, $oHostObj = null)
	{
		if (is_null($value))
		{
			return '';
		}
		else
		{
			return $this->GetValueLabel($value);
		}
	}

	public function GetForJSON($value)
	{
		return (bool)$value;
	}

	public function MakeValueFromString(
		$sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null,
		$sAttributeQualifier = null
	) {
		$sInput = mb_strtolower(trim($sProposedValue));
		if ($bLocalizedValue)
		{
			switch ($sInput)
			{
				case '1': // backward compatibility
				case $this->GetValueLabel(true):
					$value = true;
					break;
				case '0': // backward compatibility
				case 'no':
				case $this->GetValueLabel(false):
					$value = false;
					break;
				default:
					$value = null;
			}
		}
		else
		{
			switch ($sInput)
			{
				case '1': // backward compatibility
				case 'yes':
					$value = true;
					break;
				case '0': // backward compatibility
				case 'no':
					$value = false;
					break;
				default:
					$value = null;
			}
		}

		return $value;
	}

	public function RecordAttChange(DBObject $oObject, $original, $value): void
	{
		parent::RecordAttChange($oObject, $original ? 1 : 0, $value ? 1 : 0);
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeScalar::class;
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '') : array
	{
		return [
			0 => $this->GetValueLabel(false),
			1 => $this->GetValueLabel(true)
		];
	}

	public function GetDisplayStyle()
	{
		return $this->GetOptional('display_style', 'select');
	}
}

/**
 * Map a varchar column (size < ?) to an attribute
 *
 * @package     iTopORM
 */
class AttributeString extends AttributeDBField
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass()
	{
		return "String";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return 'VARCHAR(255)'
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}

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
			"=" => "equals",
			"!=" => "differs from",
			"Like" => "equals (no case)",
			"NotLike" => "differs from (no case)",
			"Contains" => "contains",
			"Begins with" => "begins with",
			"Finishes with" => "finishes with"
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

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		return utils::IsNotNullOrEmptyString($proposedValue);
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue))
		{
			return '';
		}

		return (string)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		if (!is_string($value) && !is_null($value))
		{
			throw new CoreWarning('Expected the attribute value to be a string', array(
				'found_type' => gettype($value),
				'value' => $value,
				'class' => $this->GetHostClass(),
				'attribute' => $this->GetCode()
			));
		}

		return $value;
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);

		return $sTextQualifier.$sEscaped.$sTextQualifier;
	}

	public function GetDisplayStyle()
	{
		return $this->GetOptional('display_style', 'select');
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\StringField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

}

/**
 * An attribute that matches an object class
 *
 * @package     iTopORM
 */
class AttributeClass extends AttributeString
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_ENUM;

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("class_category", "more_values"));
	}

	public function __construct($sCode, $aParams)
	{
		$this->m_sCode = $sCode;
		$aParams["allowed_values"] = new ValueSetEnumClasses($aParams['class_category'], $aParams['more_values']);
		parent::__construct($sCode, $aParams);
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		$sDefault = parent::GetDefaultValue($oHostObject);
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
		if (empty($sValue))
		{
			return '';
		}

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
 * An attribute that matches a class state
 *
 * @package     iTopORM
 */
class AttributeClassState extends AttributeString
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('class_field'));
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		if (isset($aArgs['this']))
		{
			$oHostObj = $aArgs['this'];
			$sTargetClass = $this->Get('class_field');
			$sClass = $oHostObj->Get($sTargetClass);

			$aAllowedStates = array();
			foreach (MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL) as $sChildClass)
			{
				$aValues = MetaModel::EnumStates($sChildClass);
				foreach (array_keys($aValues) as $sState)
				{
					$aAllowedStates[$sState] = $sState.' ('.MetaModel::GetStateLabel($sChildClass, $sState).')';
				}
			}
			return $aAllowedStates;
		}

		return null;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (empty($sValue))
		{
			return '';
		}

		if (!empty($oHostObject))
		{
			$sTargetClass = $this->Get('class_field');
			$sClass = $oHostObject->Get($sTargetClass);
			foreach (MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL) as $sChildClass)
			{
				$aValues = MetaModel::EnumStates($sChildClass);
				if (in_array($sValue, $aValues))
				{
					$sLabelForHtmlAttribute = utils::EscapeHtml($sValue.' ('.MetaModel::GetStateLabel($sChildClass, $sValue).')');
					$sHTML = '<span class="attribute-set-item" data-code="'.$sValue.'" data-label="'.$sLabelForHtmlAttribute.'" data-description="" data-tooltip-content="'.$sLabelForHtmlAttribute.'">'.$sValue.'</span>';

					return $sHTML;
				}
			}
		}

		return $sValue;
	}

}

/**
 * An attibute that matches one of the language codes availables in the dictionnary
 *
 * @package     iTopORM
 */
class AttributeApplicationLanguage extends AttributeString
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	public static function ListExpectedParams()
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

		// N°6462 This should be sorted directly in \Dict during the compilation but we can't for 2 reasons:
		// - Additional languages can be added on the fly even though it is not recommended
		// - Formatting is done at run time (just above)
		natcasesort($aLanguageCodes);

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
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;
	public $m_sValue;

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

	public function IsMagic()
	{
		return true;
	}

	public function RequiresIndex()
	{
		return true;
	}

	public function SetFixedValue($sValue)
	{
		$this->m_sValue = $sValue;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return $this->m_sValue;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (empty($sValue))
		{
			return '';
		}
		if ($bLocalize)
		{
			return MetaModel::GetName($sValue);
		}
		else
		{
			return $sValue;
		}
	}

	/**
	 * An enum can be localized
	 *
	 * @param string $sProposedValue
	 * @param bool $bLocalizedValue
	 * @param string $sSepItem
	 * @param string $sSepAttribute
	 * @param string $sSepValue
	 * @param string $sAttributeQualifier
	 *
	 * @return mixed|null|string
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function MakeValueFromString(
		$sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null,
		$sAttributeQualifier = null
	) {
		if ($bLocalizedValue)
		{
			// Lookup for the value matching the input
			//
			$sFoundValue = null;
			$aRawValues = self::GetAllowedValues();
			if (!is_null($aRawValues))
			{
				foreach($aRawValues as $sKey => $sValue)
				{
					if ($sProposedValue == $sValue)
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
			return parent::MakeValueFromString($sProposedValue, $bLocalizedValue, $sSepItem, $sSepAttribute, $sSepValue,
				$sAttributeQualifier);
		}
	}


	// Because this is sometimes used to get a localized/string version of an attribute...
	public function GetEditValue($sValue, $oHostObj = null)
	{
		if (empty($sValue))
		{
			return '';
		}

		return MetaModel::GetName($sValue);
	}

	public function GetForJSON($value)
	{
		// JSON values are NOT localized
		return $value;
	}

	/**
	 * @param $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function GetAsCSV(
		$value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		if ($bLocalize && $value != '')
		{
			$sRawValue = MetaModel::GetName($value);
		}
		else
		{
			$sRawValue = $value;
		}

		return parent::GetAsCSV($sRawValue, $sSeparator, $sTextQualifier, null, false, $bConvertToPlainText);
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (empty($value))
		{
			return '';
		}
		if ($bLocalize)
		{
			$sRawValue = MetaModel::GetName($value);
		}
		else
		{
			$sRawValue = $value;
		}

		return Str::pure2xml($sRawValue);
	}

	public function GetBasicFilterLooseOperator()
	{
		return '=';
	}

	public function GetValueLabel($sValue)
	{
		if (empty($sValue))
		{
			return '';
		}

		return MetaModel::GetName($sValue);
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$aRawValues = MetaModel::EnumChildClasses($this->GetHostClass(), ENUM_CHILD_CLASSES_ALL);
		$aLocalizedValues = array();
		foreach($aRawValues as $sClass)
		{
			$aLocalizedValues[$sClass] = MetaModel::GetName($sClass);
		}

		return $aLocalizedValues;
	}

	/**
	 * @return bool
	 * @since 2.7.0 N°2272 OQL perf finalclass in all intermediary tables
	 */
	public function CopyOnAllTables()
	{
		$sClass = self::GetHostClass();
		if (MetaModel::IsLeafClass($sClass))
		{
			// Leaf class, no finalclass
			return false;
		}
		return true;
	}
}


/**
 * Map a varchar column (size < ?) to an attribute that must never be shown to the user
 *
 * @package     iTopORM
 */
class AttributePassword extends AttributeString implements iAttributeNoGroupBy
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass()
	{
		return "Password";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "VARCHAR(64)"
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}

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
		if (utils::IsNullOrEmptyString($sValue))
		{
			return '';
		}
		else
		{
			return '******';
		}
	}

	public function IsPartOfFingerprint()
	{
		return false;
	} // Cannot reliably compare two encrypted passwords since the same password will be encrypted in diffferent manners depending on the random 'salt'
}

/**
 * Map a text column (size < 255) to an attribute that is encrypted in the database
 * The encryption is based on a key set per iTop instance. Thus if you export your
 * database (in SQL) to someone else without providing the key at the same time
 * the encrypted fields will remain encrypted
 *
 * @package     iTopORM
 */
class AttributeEncryptedString extends AttributeString implements iAttributeNoGroupBy
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	protected function GetSQLCol($bFullSpec = false)
	{
		return "TINYBLOB";
	}

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
		if (is_null($proposedValue))
		{
			return null;
		}

		return (string)$proposedValue;
	}

	/**
	 * Decrypt the value when reading from the database
	 *
	 * @param array $aCols
	 * @param string $sPrefix
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$oSimpleCrypt = new SimpleCrypt(MetaModel::GetConfig()->GetEncryptionLibrary());
		$sValue = $oSimpleCrypt->Decrypt(MetaModel::GetConfig()->GetEncryptionKey(), $aCols[$sPrefix]);

		return $sValue;
	}

	/**
	 * Encrypt the value before storing it in the database
	 *
	 * @param $value
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function GetSQLValues($value)
	{
		$oSimpleCrypt = new SimpleCrypt(MetaModel::GetConfig()->GetEncryptionLibrary());
		$encryptedValue = $oSimpleCrypt->Encrypt(MetaModel::GetConfig()->GetEncryptionKey(), $value);

		$aValues = array();
		$aValues[$this->Get("sql")] = $encryptedValue;

		return $aValues;
	}

	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		if (is_null($original)) {
			$original = '';
		}
		$oMyChangeOp->Set("prevstring", $original);
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeEncrypted::class;
	}


}


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
 * Map a text column (size > ?) to an attribute
 *
 * @package     iTopORM
 */
class AttributeText extends AttributeString
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetEditClass()
	{
		return ($this->GetFormat() == 'text') ? 'Text' : "HTML";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "TEXT".CMDBSource::GetSqlStringColumnDefinition();
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->Get('sql')] = $this->GetSQLCol($bFullSpec);
		if ($this->GetOptional('format', null) != null)
		{
			// Add the extra column only if the property 'format' is specified for the attribute
			$aColumns[$this->Get('sql').'_format'] = "ENUM('text','html')".CMDBSource::GetSqlStringColumnDefinition();
			if ($bFullSpec)
			{
				$aColumns[$this->Get('sql').'_format'] .= " DEFAULT 'text'"; // default 'text' is for migrating old records
			}
		}

		return $aColumns;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			$sPrefix = $this->Get('sql');
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix;
		if ($this->GetOptional('format', null) != null)
		{
			// Add the extra column only if the property 'format' is specified for the attribute
			$aColumns['_format'] = $sPrefix.'_format';
		}

		return $aColumns;
	}

	public function GetMaxSize()
	{
		// Is there a way to know the current limitation for mysql?
		// See mysql_field_len()
		return 65535;
	}

	public static function RenderWikiHtml($sText, $bWikiOnly = false)
	{
		if (!$bWikiOnly)
		{
			$sPattern = '/'.str_replace('/', '\/', utils::GetConfig()->Get('url_validation_pattern')).'/i';
			if (preg_match_all($sPattern, $sText, $aAllMatches,
				PREG_SET_ORDER /* important !*/ | PREG_OFFSET_CAPTURE /* important ! */))
			{
				$i = count($aAllMatches);
				// Replace the URLs by an actual hyperlink <a href="...">...</a>
				// Let's do it backwards so that the initial positions are not modified by the replacement
				// This works if the matches are captured: in the order they occur in the string  AND
				// with their offset (i.e. position) inside the string
				while ($i > 0)
				{
					$i--;
					$sUrl = $aAllMatches[$i][0][0]; // String corresponding to the main pattern
					$iPos = $aAllMatches[$i][0][1]; // Position of the main pattern
					$sText = substr_replace($sText, "<a href=\"$sUrl\">$sUrl</a>", $iPos, strlen($sUrl));

				}
			}
		}
		if (preg_match_all(WIKI_OBJECT_REGEXP, $sText, $aAllMatches, PREG_SET_ORDER))
		{
			foreach($aAllMatches as $iPos => $aMatches)
			{
				$sClass = trim($aMatches[1]);
				$sName = trim($aMatches[2]);
				$sLabel = (!empty($aMatches[4])) ? trim($aMatches[4]) : null;

				if (MetaModel::IsValidClass($sClass))
				{
				    $bFound = false;

				    // Try to find by name, then by id
					if (is_object($oObj = MetaModel::GetObjectByName($sClass, $sName, false /* MustBeFound */)))
                    {
                        $bFound = true;
                    }
                    elseif(is_object($oObj = MetaModel::GetObject($sClass, (int) $sName, false /* MustBeFound */, true)))
                    {
                        $bFound = true;
                    }

                    if($bFound === true)
                    {
						// Propose a std link to the object
                        $sHyperlinkLabel = (empty($sLabel)) ? $oObj->GetName() : $sLabel;
                        $sText = str_replace($aMatches[0], $oObj->GetHyperlink(null, true, $sHyperlinkLabel), $sText);
					}
					else
					{
						// Propose a std link to the object
						$sClassLabel = MetaModel::GetName($sClass);
						$sToolTipForHtml = utils::EscapeHtml(Dict::Format('Core:UnknownObjectLabel', $sClass, $sName));
						$sReplacement = "<span class=\"wiki_broken_link ibo-is-broken-hyperlink\" data-tooltip-content=\"$sToolTipForHtml\">$sClassLabel:$sName" . (!empty($sLabel) ? " ($sLabel)" : "") . "</span>";
						$sText = str_replace($aMatches[0], $sReplacement, $sText);
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

		if ($this->GetFormat() == 'text')
		{
			$sValue = parent::GetAsHTML($sValue, $oHostObject, $bLocalize);
			$sValue = self::RenderWikiHtml($sValue);
			$sValue = nl2br($sValue);

			return "<div $sStyle>$sValue</div>";
		}
		else
		{
			$sValue = self::RenderWikiHtml($sValue, true /* wiki only */);

			return "<div class=\"HTML ibo-is-html-content\" $sStyle>".InlineImage::FixUrls($sValue).'</div>';
		}

	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		// N°4517 - PHP 8.1 compatibility: str_replace call with null cause deprecated message
		if ($sValue == null) {
			return '';
		}

		if ($this->GetFormat() == 'text') {
			if (preg_match_all(WIKI_OBJECT_REGEXP, $sValue, $aAllMatches, PREG_SET_ORDER)) {
				foreach ($aAllMatches as $iPos => $aMatches) {
					$sClass = trim($aMatches[1]);
					$sName = trim($aMatches[2]);
					$sLabel = (!empty($aMatches[4])) ? trim($aMatches[4]) : null;

					if (MetaModel::IsValidClass($sClass)) {
						$sClassLabel = MetaModel::GetName($sClass);
						$sReplacement = "[[$sClassLabel:$sName".(!empty($sLabel) ? " | $sLabel" : "")."]]";
						$sValue = str_replace($aMatches[0], $sReplacement, $sValue);
					}
				}
			}
		}

		return $sValue;
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
		if ($this->GetFormat() == 'html')
		{
			return (string)utils::HtmlToText($this->GetEditValue($sValue, $oHostObj));
		}
		else
		{
			return parent::GetAsPlainText($sValue, $oHostObj);
		}
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		$sValue = $proposedValue;

		// N°4517 - PHP 8.1 compatibility: str_replace call with null cause deprecated message
		if ($sValue == null) {
			return '';
		}

		switch ($this->GetFormat()) {
			case 'html':
				if (($sValue !== null) && ($sValue !== '')) {
					$sValue = HTMLSanitizer::Sanitize($sValue);
				}
				break;

			case 'text':
			default:
				if (preg_match_all(WIKI_OBJECT_REGEXP, $sValue, $aAllMatches, PREG_SET_ORDER)) {
					foreach ($aAllMatches as $iPos => $aMatches) {
						$sClassLabel = trim($aMatches[1]);
						$sName = trim($aMatches[2]);
						$sLabel = (!empty($aMatches[4])) ? trim($aMatches[4]) : null;

						if (!MetaModel::IsValidClass($sClassLabel)) {
							$sClass = MetaModel::GetClassFromLabel($sClassLabel);
							if ($sClass) {
								$sReplacement = "[[$sClassLabel:$sName".(!empty($sLabel) ? " | $sLabel" : "")."]]";
								$sValue = str_replace($aMatches[0], $sReplacement, $sValue);
							}
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

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\TextAreaField';
	}

	/**
	 * @param \DBObject $oObject
	 * @param \Combodo\iTop\Form\Field\TextAreaField $oFormField
	 *
	 * @return \Combodo\iTop\Form\Field\TextAreaField
	 * @throws \CoreException
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			/** @var \Combodo\iTop\Form\Field\TextAreaField $oFormField */
			$oFormField = new $sFormFieldClass($this->GetCode(), null, $oObject);
			$oFormField->SetFormat($this->GetFormat());
		}
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	/**
	 * The actual formatting of the field: either text (=plain text) or html (= text with HTML markup)
	 *
	 * @return string
	 */
	public function GetFormat()
	{
		return $this->GetOptional('format', 'text');
	}

	/**
	 * Read the value from the row returned by the SQL query and transorms it to the appropriate
	 * internal format (either text or html)
	 *
	 * @see AttributeDBFieldVoid::FromSQLToValue()
	 *
	 * @param array $aCols
	 * @param string $sPrefix
	 *
	 * @return string
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$value = $aCols[$sPrefix.''];
		if ($this->GetOptional('format', null) != null)
		{
			// Read from the extra column only if the property 'format' is specified for the attribute
			$sFormat = $aCols[$sPrefix.'_format'];
		}
		else
		{
			$sFormat = $this->GetFormat();
		}

		switch ($sFormat)
		{
			case 'text':
				if ($this->GetFormat() == 'html')
				{
					$value = utils::TextToHtml($value);
				}
				break;

			case 'html':
				if ($this->GetFormat() == 'text')
				{
					$value = utils::HtmlToText($value);
				}
				else
				{
					$value = InlineImage::FixUrls((string)$value);
				}
				break;

			default:
				// unknown format ??
		}

		return $value;
	}

	public function GetSQLValues($value)
	{
		$aValues = array();
		$aValues[$this->Get("sql")] = $this->ScalarToSQL($value);
		if ($this->GetOptional('format', null) != null)
		{
			// Add the extra column only if the property 'format' is specified for the attribute
			$aValues[$this->Get("sql").'_format'] = $this->GetFormat();
		}

		return $aValues;
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		switch ($this->GetFormat())
		{
			case 'html':
				if ($bConvertToPlainText)
				{
					$sValue = utils::HtmlToText((string)$sValue);
				}
				$sFrom = array("\r\n", $sTextQualifier);
				$sTo = array("\n", $sTextQualifier.$sTextQualifier);
				$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);

				return $sTextQualifier.$sEscaped.$sTextQualifier;
				break;

			case 'text':
			default:
				return parent::GetAsCSV($sValue, $sSeparator, $sTextQualifier, $oHostObject, $bLocalize,
					$bConvertToPlainText);
		}
	}

	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		/** @noinspection PhpConditionCheckedByNextConditionInspection */
		if (false === is_null($original) && ($original instanceof ormCaseLog)) {
			$original = $original->GetText();
		}
		$oMyChangeOp->Set("prevdata", $original);
	}

	protected function GetChangeRecordClassName(): string
	{
		return ($this->GetFormat() === 'html')
			? CMDBChangeOpSetAttributeHTML::class
			: CMDBChangeOpSetAttributeText::class;
	}
}

/**
 * Map a log to an attribute
 *
 * @package     iTopORM
 */
class AttributeLongText extends AttributeText
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "LONGTEXT".CMDBSource::GetSqlStringColumnDefinition();
	}

	public function GetMaxSize()
	{
		// Is there a way to know the current limitation for mysql?
		// See mysql_field_len()
		return 65535 * 1024; // Limited... still 64 MB!
	}

	protected function GetChangeRecordClassName(): string
	{
		return ($this->GetFormat() === 'html')
			? CMDBChangeOpSetAttributeHTML::class
			: CMDBChangeOpSetAttributeLongText::class;
	}
}

/**
 * An attibute that stores a case log (i.e journal)
 *
 * @package     iTopORM
 */
class AttributeCaseLog extends AttributeLongText
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

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

	/**
	 * @inheritDoc
	 * @param \ormCaseLog $proposedValue
	 */
	public function HasAValue($proposedValue): bool
	{
		// Protection against wrong value type
		if (false === ($proposedValue instanceof ormCaseLog)) {
			return parent::HasAValue($proposedValue);
		}

		// We test if there is at least 1 entry in the log, not if the user is adding one
		return $proposedValue->GetEntryCount() > 0;
	}


	public function ScalarToSQL($value)
	{
		if (!is_string($value) && !is_null($value))
		{
			throw new CoreWarning('Expected the attribute value to be a string', array(
				'found_type' => gettype($value),
				'value' => $value,
				'class' => $this->GetCode(),
				'attribute' => $this->GetHostClass()
			));
		}

		return $value;
	}

	public function GetEditClass()
	{
		return "CaseLog";
	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		if (!($sValue instanceOf ormCaseLog))
		{
			return '';
		}

		return $sValue->GetModifiedEntry();
	}

	/**
	 * For fields containing a potential markup, return the value without this markup
	 *
	 * @param mixed $value
	 * @param \DBObject $oHostObj
	 *
	 * @return string
	 */
	public function GetAsPlainText($value, $oHostObj = null)
	{
		if ($value instanceOf ormCaseLog)
		{
			/** ormCaseLog $value */
			return $value->GetAsPlainText();
		}
		else
		{
			return (string)$value;
		}
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return new ormCaseLog();
	}

	public function Equals($val1, $val2)
	{
		return ($val1->GetText() == $val2->GetText());
	}


	/**
	 * Facilitate things: allow the user to Set the value from a string
	 *
	 * @param $proposedValue
	 * @param \DBObject $oHostObj
	 *
	 * @return mixed|null|\ormCaseLog|string
	 * @throws \Exception
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if ($proposedValue instanceof ormCaseLog)
		{
			// Passthrough
			$ret = clone $proposedValue;
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
					//N°5135 - add impersonation information in caselog
					if (UserRights::IsImpersonated()){
						$sOnBehalfOf = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUserFriendlyName(), UserRights::GetUserFriendlyName());
						$oCaseLog->AddLogEntry($proposedValue, $sOnBehalfOf, UserRights::GetConnectedUserId());
					} else {
						$oCaseLog->AddLogEntry($proposedValue);
					}
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
			$sPrefix = $this->Get('sql');
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix;
		$aColumns['_index'] = $sPrefix.'_index';

		return $aColumns;
	}

	/**
	 * @param array $aCols
	 * @param string $sPrefix
	 *
	 * @return \ormCaseLog
	 * @throws \MissingColumnException
	 */
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

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'LONGTEXT' // 2^32 (4 Gb)
			.CMDBSource::GetSqlStringColumnDefinition();
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

		return "<div class=\"caselog\" $sStyle>".$sContent.'</div>';
	}


	public function GetAsCSV(
		$value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		if ($value instanceOf ormCaseLog)
		{
			return parent::GetAsCSV($value->GetText($bConvertToPlainText), $sSeparator, $sTextQualifier, $oHostObject,
				$bLocalize, $bConvertToPlainText);
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
	 * List the available verbs for 'GetForTemplate'
	 */
	public function EnumTemplateVerbs()
	{
		return array(
			'' => 'Plain text representation of all the log entries',
			'head' => 'Plain text representation of the latest entry',
			'head_html' => 'HTML representation of the latest entry',
			'html' => 'HTML representation of all the log entries',
		);
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 *
	 * @param $value mixed The current value of the field
	 * @param $sVerb string The verb specifying the representation of the value
	 * @param $oHostObject DBObject The object
	 * @param $bLocalize bool Whether or not to localize the value
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		switch ($sVerb)
		{
			case '':
				return $value->GetText(true);

			case 'head':
				return $value->GetLatestEntry('text');

			case 'head_html':
				return $value->GetLatestEntry('html');

			case 'html':
				return $value->GetAsEmailHtml();

			default:
				throw new Exception("Unknown verb '$sVerb' for attribute ".$this->GetCode().' in class '.get_class($oHostObject));
		}
	}

	public function GetForJSON($value)
	{
		return $value->GetForJSON();
	}

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

	public function Fingerprint($value)
	{
		$sFingerprint = '';
		if ($value instanceOf ormCaseLog)
		{
			$sFingerprint = $value->GetText();
		}

		return $sFingerprint;
	}

	/**
	 * The actual formatting of the text: either text (=plain text) or html (= text with HTML markup)
	 *
	 * @return string
	 */
	public function GetFormat()
	{
		return $this->GetOptional('format', 'html'); // default format for case logs is now HTML
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\CaseLogField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		// First we call the parent so the field is build
		$oFormField = parent::MakeFormField($oObject, $oFormField);
		// Then only we set the value
		$oFormField->SetCurrentValue($this->GetEditValue($oObject->Get($this->GetCode())));
		// And we set the entries
		$oFormField->SetEntries($oObject->Get($this->GetCode())->GetAsArray());

		return $oFormField;
	}

	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		/** @var \ormCaseLog $value */
		$oMyChangeOp->Set("lastentry", $value->GetLatestEntryIndex());
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeCaseLog::class;
	}
}

/**
 * Map a text column (size > ?), containing HTML code, to an attribute
 *
 * @package     iTopORM
 */
class AttributeHTML extends AttributeLongText
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->Get('sql')] = $this->GetSQLCol();
		if ($this->GetOptional('format', null) != null)
		{
			// Add the extra column only if the property 'format' is specified for the attribute
			$aColumns[$this->Get('sql').'_format'] = "ENUM('text','html')";
			if ($bFullSpec)
			{
				$aColumns[$this->Get('sql').'_format'] .= " DEFAULT 'html'"; // default 'html' is for migrating old records
			}
		}

		return $aColumns;
	}

	/**
	 * The actual formatting of the text: either text (=plain text) or html (= text with HTML markup)
	 *
	 * @return string
	 */
	public function GetFormat()
	{
		return $this->GetOptional('format', 'html'); // Defaults to HTML
	}
}

/**
 * Specialization of a string: email
 *
 * @package     iTopORM
 */
class AttributeEmailAddress extends AttributeString
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetValidationPattern()
	{
		return $this->GetOptional('validation_pattern', '^'.utils::GetConfig()->Get('email_validation_pattern').'$');
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\EmailField';
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (empty($sValue))
		{
			return '';
		}

		$sUrlDecorationClass = utils::GetConfig()->Get('email_decoration_class');

		return '<a class="mailto" href="mailto:'.$sValue.'"><span class="text_decoration '.$sUrlDecorationClass.'"></span>'.parent::GetAsHTML($sValue).'</a>';
	}
}

/**
 * Specialization of a string: IP address
 *
 * @package     iTopORM
 */
class AttributeIPAddress extends AttributeString
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

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
 * Specialization of a string: phone number
 *
 * @package     iTopORM
 */
class AttributePhoneNumber extends AttributeString
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetValidationPattern()
	{
		return $this->GetOptional('validation_pattern',
			'^'.utils::GetConfig()->Get('phone_number_validation_pattern').'$');
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\PhoneField';
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (empty($sValue))
		{
			return '';
		}

		$sUrlDecorationClass = utils::GetConfig()->Get('phone_number_decoration_class');
		$sUrlPattern = utils::GetConfig()->Get('phone_number_url_pattern');
		$sUrl = sprintf($sUrlPattern, $sValue);

		return '<a class="tel" href="'.$sUrl.'"><span class="text_decoration '.$sUrlDecorationClass.'"></span>'.parent::GetAsHTML($sValue).'</a>';
	}

}

/**
 * Specialization of a string: OQL expression
 *
 * @package     iTopORM
 */
class AttributeOQL extends AttributeText
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetEditClass()
	{
		return "OQLExpression";
	}
}

/**
 * Specialization of a string: template (contains iTop placeholders like $current_contact_id$ or $this->name$)
 *
 * @package     iTopORM
 */
class AttributeTemplateString extends AttributeString
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}
}

/**
 * Specialization of a text: template (contains iTop placeholders like $current_contact_id$ or $this->name$)
 *
 * @package     iTopORM
 */
class AttributeTemplateText extends AttributeText
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}
}

/**
 * Specialization of a HTML: template (contains iTop placeholders like $current_contact_id$ or $this->name$)
 *
 * @package     iTopORM
 */
class AttributeTemplateHTML extends AttributeText
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->Get('sql')] = $this->GetSQLCol();
		if ($this->GetOptional('format', null) != null)
		{
			// Add the extra column only if the property 'format' is specified for the attribute
			$aColumns[$this->Get('sql').'_format'] = "ENUM('text','html')";
			if ($bFullSpec)
			{
				$aColumns[$this->Get('sql').'_format'] .= " DEFAULT 'html'"; // default 'html' is for migrating old records
			}
		}

		return $aColumns;
	}

	/**
	 * The actual formatting of the text: either text (=plain text) or html (= text with HTML markup)
	 *
	 * @return string
	 */
	public function GetFormat()
	{
		return $this->GetOptional('format', 'html'); // Defaults to HTML
	}
}


/**
 * Map a enum column to an attribute
 *
 * @package     iTopORM
 */
class AttributeEnum extends AttributeString
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_ENUM;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array('styled_values'));
	}

	public function GetEditClass()
	{
		return "String";
	}

	/**
	 * @param string|null $sValue
	 *
	 * @return \ormStyle|null
	 */
	public function GetStyle(?string $sValue): ?ormStyle
	{
		if ($this->IsParam('styled_values')) {
			$aStyles = $this->Get('styled_values');
			if (array_key_exists($sValue, $aStyles)) {
				return $aStyles[$sValue];
			}
		}

		if ($this->IsParam('default_style')) {
			return $this->Get('default_style');
		}

		return null;
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		// Get the definition of the column, including the actual values present in the table
		return $this->GetSQLColHelper($bFullSpec, true);
	}

	/**
	 * A more versatile version of GetSQLCol
	 * @since 3.0.0
	 * @param bool $bFullSpec
	 * @param bool $bIncludeActualValues
	 * @param string $sSQLTableName The table where to look for the actual values (may be useful for data synchro tables)
	 * @return string
	*/
	protected function GetSQLColHelper($bFullSpec = false, $bIncludeActualValues = false, $sSQLTableName = null)
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

		// Preserve the values already present in the database to ease migrations
		if ($bIncludeActualValues)
		{
			if ($sSQLTableName == null)
			{
				// No SQL table given, use the one of the attribute
				$sHostClass = $this->GetHostClass();
				$sSQLTableName = MetaModel::DBGetTable($sHostClass, $this->GetCode());
			}
			$aValues = array_unique(array_merge($aValues, $this->GetActualValuesInDB($sSQLTableName)));
		}

		if (count($aValues) > 0)
		{
			// The syntax used here do matters
			// In particular, I had to remove unnecessary spaces to
			// make sure that this string will match the field type returned by the DB
			// (used to perform a comparison between the current DB format and the data model)
			return "ENUM(".implode(",", $aValues).")"
				.CMDBSource::GetSqlStringColumnDefinition()
				.($bFullSpec ? $this->GetSQLColSpec() : '');
		}
		else
		{
			return "VARCHAR(255)"
				.CMDBSource::GetSqlStringColumnDefinition()
				.($bFullSpec ? " DEFAULT ''" : ""); // ENUM() is not an allowed syntax!
		}
	}

	/**
	 * @since 3.0.0
	 * {@inheritDoc}
	 * @see AttributeDefinition::GetImportColumns()
	 */
	public function GetImportColumns()
	{
		// Note: this is used by the Data Synchro to build the "data" table
		// Right now the function is not passed the "target" SQL table, but if we improve this in the future
		// we may call $this->GetSQLColHelper(true, true, $sDBTable); to take into account the actual 'enum' values
		// in this table
		return array($this->GetCode() => $this->GetSQLColHelper(false, false));
	}

	/**
	 * Get the list of the actual 'enum' values present in the database
	 * @since 3.0.0
	 * @return string[]
	 */
	protected function GetActualValuesInDB(string $sDBTable)
	{
		$aValues = array();
		try
		{
			$sSQL = "SELECT DISTINCT `".$this->GetSQLExpr()."` AS value FROM `$sDBTable`;";
			$aValuesInDB = CMDBSource::QueryToArray($sSQL);
			foreach($aValuesInDB as $aRow)
			{
				if ($aRow['value'] !== null)
				{
					$aValues[] = $aRow['value'];
				}
			}
		}
		catch(MySQLException $e)
		{
			// Never mind, maybe the table does not exist yet (new installation from scratch)
			// It seems more efficient to try and ignore errors than to test if the table & column really exists
		}
		return CMDBSource::Quote($aValues);
	}

	protected function GetSQLColSpec()
	{
		$default = $this->ScalarToSQL($this->GetDefaultValue());
		if (is_null($default))
		{
			$sRet = '';
		}
		else
		{
			// ENUMs values are strings so the default value must be a string as well,
			// otherwise MySQL interprets the number as the zero-based index of the value in the list (i.e. the nth value in the list)
			$sRet = " DEFAULT ".CMDBSource::Quote($default);
		}

		return $sRet;
	}

	public function ScalarToSQL($value)
	{
		// Note: for strings, the null value is an empty string and it is recorded as such in the DB
		//	   but that wasn't working for enums, because '' is NOT one of the allowed values
		//	   that's why a null value must be forced to a real null
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
			$sLabel = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue,
				Dict::S('Enum:Undefined'));
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
			$sDescription = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+',
				Dict::S('Enum:Undefined'));
		}
		else
		{
			$sDescription = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+',
				'', true /* user language only */);
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
		if ($bLocalize) {
			$sLabel = $this->GetValueLabel($sValue);
			// $sDescription = $this->GetValueDescription($sValue);
			$oStyle = $this->GetStyle($sValue);
			// later, we could imagine a detailed description in the title
			// $sRes = "<span title=\"$sDescription\">".parent::GetAsHtml($sLabel)."</span>";
			$oBadge = FieldBadgeUIBlockFactory::MakeForField($sLabel, $oStyle);
			$oRenderer = new BlockRenderer($oBadge);
			$sRes = $oRenderer->RenderHtml();
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

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
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

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\SelectField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			// Later : We should check $this->Get('display_style') and create a Radio / Select / ... regarding its value
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		$oFormField->SetChoices($this->GetAllowedValues($oObject->ToArgsForQuery()));
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
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

	public function GetForJSON($value)
	{
		return $value;
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$aRawValues = parent::GetAllowedValues($aArgs, $sContains);
		if (is_null($aRawValues))
		{
			return null;
		}
		$aLocalizedValues = array();
		foreach($aRawValues as $sKey => $sValue)
		{
			$aLocalizedValues[$sKey] = $this->GetValueLabel($sKey);
		}

		// Sort by label only if necessary
		// See N°1646 and {@see \MFCompiler::CompileAttributeEnumValues()} for complete information as for why sort on labels is done at runtime while other sorting are done at compile time
		/** @var \ValueSetEnum $oValueSetDef */
		$oValueSetDef = $this->GetValuesDef();
		if ($oValueSetDef->IsSortedByValues()) {
			asort($aLocalizedValues);
		}

		return $aLocalizedValues;
	}

	public function GetMaxSize()
	{
		return null;
	}

	/**
	 * An enum can be localized
	 */
	public function MakeValueFromString(
		$sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null,
		$sAttributeQualifier = null
	) {
		if ($bLocalizedValue)
		{
			// Lookup for the value matching the input
			//
			$sFoundValue = null;
			$aRawValues = parent::GetAllowedValues();
			if (!is_null($aRawValues))
			{
				foreach($aRawValues as $sKey => $sValue)
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
			return parent::MakeValueFromString($sProposedValue, $bLocalizedValue, $sSepItem, $sSepAttribute, $sSepValue,
				$sAttributeQualifier);
		}
	}

	/**
	 * Processes the input value to align it with the values supported
	 * by this type of attribute. In this case: turns empty strings into nulls
	 *
	 * @param mixed $proposedValue The value to be set for the attribute
	 *
	 * @return mixed The actual value that will be set
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if ($proposedValue == '')
		{
			return null;
		}

		return parent::MakeRealValue($proposedValue, $oHostObj);
	}

	public function GetOrderByHint()
	{
		$aValues = $this->GetAllowedValues();

		return Dict::Format('UI:OrderByHint_Values', implode(', ', $aValues));
	}
}

/**
 * A meta enum is an aggregation of enum from subclasses into an enum of a base class
 * It has been designed is to cope with the fact that statuses must be defined in leaf classes, while it makes sense to
 * have a superstatus available on the root classe(s)
 *
 * @package     iTopORM
 */
class AttributeMetaEnum extends AttributeEnum
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array('allowed_values', 'sql', 'default_value', 'mapping');
	}

	public function IsNullAllowed()
	{
		return false; // Well... this actually depends on the mapping
	}

	public function IsWritable()
	{
		return false;
	}

	public function RequiresIndex()
	{
		return true;
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		if (is_null($sClass))
		{
			$sClass = $this->GetHostClass();
		}
		$aMappingData = $this->GetMapRule($sClass);
		if ($aMappingData == null)
		{
			$aRet = array();
		}
		else
		{
			$aRet = array($aMappingData['attcode']);
		}

		return $aRet;
	}

	/**
	 * Overload the standard so as to leave the data unsorted
	 *
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return array|null
	 */
	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$oValSetDef = $this->GetValuesDef();
		if (!$oValSetDef)
		{
			return null;
		}
		$aRawValues = $oValSetDef->GetValueList();

		if (is_null($aRawValues))
		{
			return null;
		}
		$aLocalizedValues = array();
		foreach($aRawValues as $sKey => $sValue)
		{
			$aLocalizedValues[$sKey] = $this->GetValueLabel($sKey);
		}

		return $aLocalizedValues;
	}

	/**
	 * Returns the meta value for the given object.
	 * See also MetaModel::RebuildMetaEnums() that must be maintained when MapValue changes
	 *
	 * @param $oObject
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function MapValue($oObject)
	{
		$aMappingData = $this->GetMapRule(get_class($oObject));
		if ($aMappingData == null)
		{
			$sRet = $this->GetDefaultValue();
		}
		else
		{
			$sAttCode = $aMappingData['attcode'];
			$value = $oObject->Get($sAttCode);
			if (array_key_exists($value, $aMappingData['values']))
			{
				$sRet = $aMappingData['values'][$value];
			}
			elseif ($this->GetDefaultValue() != '')
			{
				$sRet = $this->GetDefaultValue();
			}
			else
			{
				throw new Exception('AttributeMetaEnum::MapValue(): mapping not found for value "'.$value.'" in '.get_class($oObject).', on attribute '.MetaModel::GetAttributeOrigin($this->GetHostClass(),
						$this->GetCode()).'::'.$this->GetCode());
			}
		}

		return $sRet;
	}

	public function GetMapRule($sClass)
	{
		$aMappings = $this->Get('mapping');
		if (array_key_exists($sClass, $aMappings))
		{
			$aMappingData = $aMappings[$sClass];
		}
		else
		{
			$sParent = MetaModel::GetParentClass($sClass);
			if (is_null($sParent))
			{
				$aMappingData = null;
			}
			else
			{
				$aMappingData = $this->GetMapRule($sParent);
			}
		}

		return $aMappingData;
	}
}

/**
 * Map a date+time column to an attribute
 *
 * @package     iTopORM
 */
class AttributeDateTime extends AttributeDBField
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_DATE_TIME;

	public static $oFormat = null;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	/**
	 *
	 * @return DateTimeFormat
	 */
	public static function GetFormat()
	{
		if (self::$oFormat == null)
		{
			static::LoadFormatFromConfig();
		}

		return self::$oFormat;
	}

	/**
	 * Load the 3 settings: date format, time format and data_time format from the configuration
	 */
	public static function LoadFormatFromConfig()
	{
		$aFormats = MetaModel::GetConfig()->Get('date_and_time_format');
		$sLang = Dict::GetUserLanguage();
		$sDateFormat = isset($aFormats[$sLang]['date']) ? $aFormats[$sLang]['date'] : (isset($aFormats['default']['date']) ? $aFormats['default']['date'] : 'Y-m-d');
		$sTimeFormat = isset($aFormats[$sLang]['time']) ? $aFormats[$sLang]['time'] : (isset($aFormats['default']['time']) ? $aFormats['default']['time'] : 'H:i:s');
		$sDateAndTimeFormat = isset($aFormats[$sLang]['date_time']) ? $aFormats[$sLang]['date_time'] : (isset($aFormats['default']['date_time']) ? $aFormats['default']['date_time'] : '$date $time');

		$sFullFormat = str_replace(array('$date', '$time'), array($sDateFormat, $sTimeFormat), $sDateAndTimeFormat);

		self::SetFormat(new DateTimeFormat($sFullFormat));
		AttributeDate::SetFormat(new DateTimeFormat($sDateFormat));
	}

	/**
	 * Returns the format string used for the date & time stored in memory
	 *
	 * @return string
	 */
	public static function GetInternalFormat()
	{
		return 'Y-m-d H:i:s';
	}

	/**
	 * Returns the format string used for the date & time written to MySQL
	 *
	 * @return string
	 */
	public static function GetSQLFormat()
	{
		return 'Y-m-d H:i:s';
	}

	public static function SetFormat(DateTimeFormat $oDateTimeFormat)
	{
		self::$oFormat = $oDateTimeFormat;
	}

	public static function GetSQLTimeFormat()
	{
		return 'H:i:s';
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
		try
		{
			$oDateTime = $this->GetFormat()->Parse($sSearchString);
			$sSearchString = $oDateTime->format($this->GetInternalFormat());
		} catch (Exception $e)
		{
			$sFormatString = '!'.(string)AttributeDate::GetFormat(); // BEWARE: ! is needed to set non-parsed fields to zero !!!
			$oDateTime = DateTime::createFromFormat($sFormatString, $sSearchString);
			if ($oDateTime !== false)
			{
				$sSearchString = $oDateTime->format($this->GetInternalFormat());
			}
		}

		return $sSearchString;
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\DateTimeField';
	}

	/**
	 * Override to specify Field class
	 *
	 * When called first, $oFormField is null and will be created (eg. Make). Then when the ::parent is called and the
	 * $oFormField is passed, MakeFormField behave more like a Prepare.
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		$oFormField->SetPHPDateTimeFormat((string)$this->GetFormat());
		$oFormField->SetJSDateTimeFormat($this->GetFormat()->ToMomentJS());

		$oFormField = parent::MakeFormField($oObject, $oFormField);

		// After call to the parent as it sets the current value
		$oFormField->SetCurrentValue($this->GetFormat()->Format($oObject->Get($this->GetCode())));

		return $oFormField;
	}

	/**
	 * @inheritdoc
	 */
	public function EnumTemplateVerbs()
	{
		return array(
			'' => 'Formatted representation',
			'raw' => 'Not formatted representation',
		);
	}

	/**
	 * @inheritdoc
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		switch ($sVerb)
		{
			case '':
			case 'text':
				return static::GetFormat()->format($value);
				break;
			case 'html':
				// Note: Not passing formatted value as the method will format it.
				return $this->GetAsHTML($value);
				break;
			case 'raw':
				return $value;
				break;
			default:
				return parent::GetForTemplate($value, $sVerb, $oHostObject, $bLocalize);
				break;
		}
	}

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass()
	{
		return "DateTime";
	}


	public function GetEditValue($sValue, $oHostObj = null)
	{
		return (string)static::GetFormat()->format($sValue);
	}

	public function GetValueLabel($sValue, $oHostObj = null)
	{
		return (string)static::GetFormat()->format($sValue);
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "DATETIME";
	}

	public function GetImportColumns()
	{
		// Allow an empty string to be a valid value (synonym for "reset")
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'VARCHAR(19)'.CMDBSource::GetSqlStringColumnDefinition();

		return $aColumns;
	}

	public static function GetAsUnixSeconds($value)
	{
		$oDeadlineDateTime = new DateTime($value);
		$iUnixSeconds = $oDeadlineDateTime->format('U');

		return $iUnixSeconds;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		if (!$this->IsNullAllowed()) {
			return date($this->GetInternalFormat());
		}
		return $this->GetNullValue();
	}

	public function GetValidationPattern()
	{
		return static::GetFormat()->ToRegExpr();
	}

	public function GetBasicFilterOperators()
	{
		return array(
			"=" => "equals",
			"!=" => "differs from",
			"<" => "before",
			"<=" => "before",
			">" => "after (strictly)",
			">=" => "after",
			"SameDay" => "same day (strip time)",
			"SameMonth" => "same year/month",
			"SameYear" => "same year",
			"Today" => "today",
			">|" => "after today + N days",
			"<|" => "before today + N days",
			"=|" => "equals today + N days",
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

	/**
	 * @inheritDoc
	 *
	 * @param int|DateTime|string $proposedValue possible values :
	 *                      - timestamp ({@see DateTime::getTimestamp())
	 *                      - {@see \DateTime} PHP object
	 *                      - string, following the {@see GetInternalFormat} format.
	 *
	 * @throws \CoreUnexpectedValue if invalid value type or the string passed cannot be converted
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue))
		{
			return null;
		}

		if (is_numeric($proposedValue)) {
			return date(static::GetInternalFormat(), $proposedValue);
		}

		if (is_object($proposedValue) && ($proposedValue instanceof DateTime)) {
			return $proposedValue->format(static::GetInternalFormat());
		}

		if (is_string($proposedValue)) {
			if (($proposedValue === '') && $this->IsNullAllowed()) {
				return null;
			}
			try {
				$oFormat = new DateTimeFormat(static::GetInternalFormat());
				$oFormat->Parse($proposedValue);
			} catch (Exception $e) {
				throw new CoreUnexpectedValue('Wrong format for date attribute '.$this->GetCode().', expecting "'.$this->GetInternalFormat().'" and got "'.$proposedValue.'"');
			}

			return $proposedValue;
		}

		throw new CoreUnexpectedValue('Wrong format for date attribute '.$this->GetCode());
	}

	public function ScalarToSQL($value)
	{
		if (empty($value))
		{
			return null;
		}

		return $value;
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2html(static::GetFormat()->format($value));
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2xml($value);
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		if (empty($sValue) || ($sValue === '0000-00-00 00:00:00') || ($sValue === '0000-00-00'))
		{
			return '';
		}
		else
		{
			if ((string)static::GetFormat() !== static::GetInternalFormat())
			{
				// Format conversion
				$oDate = new DateTime($sValue);
				if ($oDate !== false)
				{
					$sValue = static::GetFormat()->format($oDate);
				}
			}
		}
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);

		return $sTextQualifier.$sEscaped.$sTextQualifier;
	}

	/**
	 * Parses a string to find some smart search patterns and build the corresponding search/OQL condition
	 * Each derived class is reponsible for defining and processing their own smart patterns, the base class
	 * does nothing special, and just calls the default (loose) operator
	 *
	 * @param string $sSearchText The search string to analyze for smart patterns
	 * @param FieldExpression $oField The FieldExpression representing the atttribute code in this OQL query
	 * @param array $aParams Values of the query parameters
	 * @param bool $bParseSearchString
	 *
	 * @return Expression The search condition to be added (AND) to the current search
	 * @throws \CoreException
	 */
	public function GetSmartConditionExpression(
		$sSearchText, FieldExpression $oField, &$aParams, $bParseSearchString = false
	) {
		// Possible smart patterns
		$aPatterns = array(
			'between' => array('pattern' => '/^\[(.*),(.*)\]$/', 'operator' => 'n/a'),
			'greater than or equal' => array('pattern' => '/^>=(.*)$/', 'operator' => '>='),
			'greater than' => array('pattern' => '/^>(.*)$/', 'operator' => '>'),
			'less than or equal' => array('pattern' => '/^<=(.*)$/', 'operator' => '<='),
			'less than' => array('pattern' => '/^<(.*)$/', 'operator' => '<'),
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

		switch ($sPatternFound)
		{
			case 'between':

				$sParamName1 = $oField->GetParent().'_'.$oField->GetName().'_1';
				$oRightExpr = new VariableExpression($sParamName1);
				if ($bParseSearchString)
				{
					$aParams[$sParamName1] = $this->ParseSearchString($aMatches[1]);
				}
				else
				{
					$aParams[$sParamName1] = $aMatches[1];
				}
				$oCondition1 = new BinaryExpression($oField, '>=', $oRightExpr);

				$sParamName2 = $oField->GetParent().'_'.$oField->GetName().'_2';
				$oRightExpr = new VariableExpression($sParamName2);
				if ($bParseSearchString)
				{
					$aParams[$sParamName2] = $this->ParseSearchString($aMatches[2]);
				}
				else
				{
					$aParams[$sParamName2] = $aMatches[2];
				}
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
				if ($bParseSearchString)
				{
					$aParams[$sParamName] = $this->ParseSearchString($aMatches[1]);
				}
				else
				{
					$aParams[$sParamName] = $aMatches[1];
				}
				$oNewCondition = new BinaryExpression($oField, $sSQLOperator, $oRightExpr);

				break;

			default:
				$oNewCondition = parent::GetSmartConditionExpression($sSearchText, $oField, $aParams);

		}

		return $oNewCondition;
	}


	public function GetHelpOnSmartSearch()
	{
		$sDict = parent::GetHelpOnSmartSearch();

		$oFormat = static::GetFormat();
		$sExample = $oFormat->Format(new DateTime('2015-07-19 18:40:00'));

		return vsprintf($sDict, array($oFormat->ToPlaceholder(), $sExample));
	}
}

/**
 * Store a duration as a number of seconds
 *
 * @package     iTopORM
 */
class AttributeDuration extends AttributeInteger
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetEditClass()
	{
		return "Duration";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "INT(11) UNSIGNED";
	}

	public function GetNullValue()
	{
		return '0';
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue))
		{
			return null;
		}
		if (!is_numeric($proposedValue))
		{
			return null;
		}
		if (((int)$proposedValue) < 0)
		{
			return null;
		}

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

		if ($duration < 60)
		{
			// Less than 1 min
			$sResult = Dict::Format('Core:Duration_Seconds', $aDuration['seconds']);
		}
		else
		{
			if ($duration < 3600)
			{
				// less than 1 hour, display it in minutes/seconds
				$sResult = Dict::Format('Core:Duration_Minutes_Seconds', $aDuration['minutes'], $aDuration['seconds']);
			}
			else
			{
				if ($duration < 86400)
				{
					// Less than 1 day, display it in hours/minutes/seconds
					$sResult = Dict::Format('Core:Duration_Hours_Minutes_Seconds', $aDuration['hours'],
						$aDuration['minutes'], $aDuration['seconds']);
				}
				else
				{
					// more than 1 day, display it in days/hours/minutes/seconds
					$sResult = Dict::Format('Core:Duration_Days_Hours_Minutes_Seconds', $aDuration['days'],
						$aDuration['hours'], $aDuration['minutes'], $aDuration['seconds']);
				}
			}
		}

		return $sResult;
	}

	static function SplitDuration($duration)
	{
		$duration = (int)$duration;
		$days = floor($duration / 86400);
		$hours = floor(($duration - (86400 * $days)) / 3600);
		$minutes = floor(($duration - (86400 * $days + 3600 * $hours)) / 60);
		$seconds = ($duration % 60); // modulo

		return array('days' => $days, 'hours' => $hours, 'minutes' => $minutes, 'seconds' => $seconds);
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\DurationField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		parent::MakeFormField($oObject, $oFormField);

		// Note : As of today, this attribute is -by nature- only supported in readonly mode, not edition
		$sAttCode = $this->GetCode();
		$oFormField->SetCurrentValue($oObject->Get($sAttCode));
		$oFormField->SetReadOnly(true);

		return $oFormField;
	}

}

/**
 * Map a date+time column to an attribute
 *
 * @package     iTopORM
 */
class AttributeDate extends AttributeDateTime
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_DATE;

	public static $oDateFormat = null;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function GetFormat()
	{
		if (self::$oDateFormat == null)
		{
			AttributeDateTime::LoadFormatFromConfig();
		}

		return self::$oDateFormat;
	}

	public static function SetFormat(DateTimeFormat $oDateFormat)
	{
		self::$oDateFormat = $oDateFormat;
	}

	/**
	 * Returns the format string used for the date & time stored in memory
	 *
	 * @return string
	 */
	public static function GetInternalFormat()
	{
		return 'Y-m-d';
	}

	/**
	 * Returns the format string used for the date & time written to MySQL
	 *
	 * @return string
	 */
	public static function GetSQLFormat()
	{
		return 'Y-m-d';
	}

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass()
	{
		return "Date";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "DATE";
	}

	public function GetImportColumns()
	{
		// Allow an empty string to be a valid value (synonym for "reset")
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'VARCHAR(10)'.CMDBSource::GetSqlStringColumnDefinition();

		return $aColumns;
	}


	/**
	 * Override to specify Field class
	 *
	 * When called first, $oFormField is null and will be created (eg. Make). Then when the ::parent is called and the
	 * $oFormField is passed, MakeFormField behave more like a Prepare.
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		$oFormField = parent::MakeFormField($oObject, $oFormField);
		$oFormField->SetDateOnly(true);

		return $oFormField;
	}

}

/**
 * A dead line stored as a date & time
 * The only difference with the DateTime attribute is the display:
 * relative to the current time
 */
class AttributeDeadline extends AttributeDateTime
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

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
			$sDate = AttributeDateTime::GetFormat()->Format($value);
			$difference = $iValue - time();

			if ($difference >= 0)
			{
				$sDifference = self::FormatDuration($difference);
			}
			else
			{
				$sDifference = Dict::Format('UI:DeadlineMissedBy_duration', self::FormatDuration(-$difference));
			}
			$sFormat = MetaModel::GetConfig()->Get('deadline_format');
			$sResult = str_replace(array('$date$', '$difference$'), array($sDate, $sDifference), $sFormat);
		}

		return $sResult;
	}

	static function FormatDuration($duration)
	{
		$days = floor($duration / 86400);
		$hours = floor(($duration - (86400 * $days)) / 3600);
		$minutes = floor(($duration - (86400 * $days + 3600 * $hours)) / 60);

		if ($duration < 60)
		{
			// Less than 1 min
			$sResult = Dict::S('UI:Deadline_LessThan1Min');
		}
		else
		{
			if ($duration < 3600)
			{
				// less than 1 hour, display it in minutes
				$sResult = Dict::Format('UI:Deadline_Minutes', $minutes);
			}
			else
			{
				if ($duration < 86400)
				{
					// Less that 1 day, display it in hours/minutes
					$sResult = Dict::Format('UI:Deadline_Hours_Minutes', $hours, $minutes);
				}
				else
				{
					// Less that 1 day, display it in hours/minutes
					$sResult = Dict::Format('UI:Deadline_Days_Hours_Minutes', $days, $hours, $minutes);
				}
			}
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
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_EXTERNAL_KEY;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	/**
	 * Return the search widget type corresponding to this attribute
	 *
	 * @return string
	 */
	public function GetSearchType()
	{
		try
		{
			$oRemoteAtt = $this->GetFinalAttDef();
			$sTargetClass = $oRemoteAtt->GetTargetClass();
			if (MetaModel::IsHierarchicalClass($sTargetClass))
			{
				return self::SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY;
			}

			return self::SEARCH_WIDGET_TYPE_EXTERNAL_KEY;
		} catch (CoreException $e)
		{
		}

		return self::SEARCH_WIDGET_TYPE_RAW;
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("targetclass", "is_null_allowed", "on_target_delete"));
	}

	public function GetEditClass()
	{
		return "ExtKey";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "INT(11)".($bFullSpec ? " DEFAULT 0" : "");
	}

	public function RequiresIndex()
	{
		return true;
	}

	public function IsExternalKey($iType = EXTKEY_RELATIVE)
	{
		return true;
	}

	public function GetTargetClass($iType = EXTKEY_RELATIVE)
	{
		return $this->Get("targetclass");
	}

	public function GetKeyAttDef($iType = EXTKEY_RELATIVE)
	{
		return $this;
	}

	public function GetKeyAttCode()
	{
		return $this->GetCode();
	}

	public function GetDisplayStyle()
	{
		return $this->GetOptional('display_style', 'select');
	}


	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return 0;
	}

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
		} catch (Exception $e)
		{
			// Some required arguments could not be found, enlarge to any existing value
			$oValSetDef = new ValueSetObjects('SELECT '.$this->GetTargetClass());

			return $oValSetDef->GetValues($aArgs, $sContains);
		}
	}

	public function GetAllowedValuesForSelect($aArgs = array(), $sContains = '')
	{
		//$this->GetValuesDef();
		$oValSetDef = new ValueSetObjects('SELECT '.$this->GetTargetClass());
		return $oValSetDef->GetValuesForAutocomplete($aArgs, $sContains);
	}


	public function GetAllowedValuesAsObjectSet($aArgs = array(), $sContains = '', $iAdditionalValue = null)
	{
		$oValSetDef = $this->GetValuesDef();
		$oSet = $oValSetDef->ToObjectSet($aArgs, $sContains, $iAdditionalValue);

		return $oSet;
	}

	public function GetAllowedValuesAsFilter($aArgs = array(), $sContains = '', $iAdditionalValue = null)
	{
		return DBObjectSearch::FromOQL($this->GetValuesDef()->GetFilterExpression());
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

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		return ((int) $proposedValue) !== 0;
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue))
		{
			return 0;
		}
		if ($proposedValue === '')
		{
			return 0;
		}
		if (MetaModel::IsValidObject($proposedValue)) {
			return $proposedValue->GetKey();
		}

		return (int)$proposedValue;
	}

	/** @inheritdoc  @since 3.1 */
	public function WriteExternalValues(DBObject $oHostObject): void
	{
		$sTargetKey = $oHostObject->Get($this->GetCode());
		$oFilter = DBSearch::FromOQL('SELECT `'.TemporaryObjectDescriptor::class.'` WHERE item_class=:class AND item_id=:id');
		$oSet = new DBObjectSet($oFilter, [], ['class' => $this->GetTargetClass(), 'id' => $sTargetKey]);
		while ($oTemporaryObjectDescriptor = $oSet->Fetch()) {
			$oTemporaryObjectDescriptor->Set('host_class', get_class($oHostObject));
			$oTemporaryObjectDescriptor->Set('host_id', $oHostObject->GetKey());
			$oTemporaryObjectDescriptor->Set('host_att_code', $this->GetCode());
			$oTemporaryObjectDescriptor->DBUpdate();
		}
	}

	public function GetMaximumComboLength()
	{
		return $this->GetOptional('max_combo_length', MetaModel::GetConfig()->Get('max_combo_length'));
	}

	public function GetMinAutoCompleteChars()
	{
		return $this->GetOptional('min_autocomplete_chars', MetaModel::GetConfig()->Get('min_autocomplete_chars'));
	}

	/**
	 * @return int
	 * @since 3.0.0
	 */
	public function GetMaxAutoCompleteResults(): int
	{
		return MetaModel::GetConfig()->Get('max_autocomplete_results');
	}

	public function AllowTargetCreation()
	{
		return $this->GetOptional('allow_target_creation', MetaModel::GetConfig()->Get('allow_target_creation'));
	}

	/**
	 * Find the corresponding "link" attribute on the target class, if any
	 *
	 * @return null | AttributeDefinition
	 * @throws \CoreException
	 */
	public function GetMirrorLinkAttribute()
	{
		$oRet = null;
		$sRemoteClass = $this->GetTargetClass();
		foreach(MetaModel::ListAttributeDefs($sRemoteClass) as $sRemoteAttCode => $oRemoteAttDef)
		{
			if (!$oRemoteAttDef->IsLinkSet())
			{
				continue;
			}
			if (!is_subclass_of($this->GetHostClass(),
					$oRemoteAttDef->GetLinkedClass()) && $oRemoteAttDef->GetLinkedClass() != $this->GetHostClass())
			{
				continue;
			}
			if ($oRemoteAttDef->GetExtKeyToMe() != $this->GetCode())
			{
				continue;
			}
			$oRet = $oRemoteAttDef;
			break;
		}

		return $oRet;
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\SelectObjectField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		/** @var \Combodo\iTop\Form\Field\Field $oFormField */
		if ($oFormField === null) {
			// Later : We should check $this->Get('display_style') and create a Radio / Select / ... regarding its value
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		// Setting params
		$oFormField->SetMaximumComboLength($this->GetMaximumComboLength());
		$oFormField->SetMinAutoCompleteChars($this->GetMinAutoCompleteChars());
		$oFormField->SetMaxAutoCompleteResults($this->GetMaxAutoCompleteResults());
		$oFormField->SetHierarchical(MetaModel::IsHierarchicalClass($this->GetTargetClass()));
		// Setting choices regarding the field dependencies
		$aFieldDependencies = $this->GetPrerequisiteAttributes();
		if (!empty($aFieldDependencies)) {
			$oTmpAttDef = $this;
			$oTmpField = $oFormField;
			$oFormField->SetOnFinalizeCallback(function () use ($oTmpField, $oTmpAttDef, $oObject) {
				/** @var $oTmpField \Combodo\iTop\Form\Field\Field */
				/** @var $oTmpAttDef \AttributeDefinition */
				/** @var $oObject \DBObject */

				// We set search object only if it has not already been set (overrided)
				if ($oTmpField->GetSearch() === null)
				{
					$oSearch = DBSearch::FromOQL($oTmpAttDef->GetValuesDef()->GetFilterExpression());
					$oSearch->SetInternalParams(array('this' => $oObject));
					$oTmpField->SetSearch($oSearch);
				}
			});
		}
		else {
			$oSearch = DBSearch::FromOQL($this->GetValuesDef()->GetFilterExpression());
			$oSearch->SetInternalParams(array('this' => $oObject));
			$oFormField->SetSearch($oSearch);
		}

		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (!is_null($oHostObject)) {
			return $oHostObject->GetAsHTML($this->GetCode(), $oHostObject);
		}

		return DBObject::MakeHyperLink($this->GetTargetClass(), $sValue);
	}
}

/**
 * Special kind of External Key to manage a hierarchy of objects
 */
class AttributeHierarchicalKey extends AttributeExternalKey
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY;

	protected $m_sTargetClass;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		$aParams = parent::ListExpectedParams();
		$idx = array_search('targetclass', $aParams);
		unset($aParams[$idx]);
		$idx = array_search('jointype', $aParams);
		unset($aParams[$idx]);

		return $aParams; // Later: mettre les bons parametres ici !!
	}

	public function GetEditClass()
	{
		return "ExtKey";
	}

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

	public static function IsHierarchicalKey()
	{
		return true;
	}

	public function GetTargetClass($iType = EXTKEY_RELATIVE)
	{
		return $this->m_sTargetClass;
	}

	public function GetKeyAttDef($iType = EXTKEY_RELATIVE)
	{
		return $this;
	}

	public function GetKeyAttCode()
	{
		return $this->GetCode();
	}

	public function GetBasicFilterOperators()
	{
		return parent::GetBasicFilterOperators();
	}

	public function GetBasicFilterLooseOperator()
	{
		return parent::GetBasicFilterLooseOperator();
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'INT(11)'.($bFullSpec ? ' DEFAULT 0' : '');
		$aColumns[$this->GetSQLLeft()] = 'INT(11)'.($bFullSpec ? ' DEFAULT 0' : '');
		$aColumns[$this->GetSQLRight()] = 'INT(11)'.($bFullSpec ? ' DEFAULT 0' : '');

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
		$oFilter = $this->GetHierachicalFilter($aArgs, $sContains);
		if ($oFilter)
		{
			$oValSetDef = $this->GetValuesDef();
			$oValSetDef->SetCondition($oFilter);

			return $oValSetDef->GetValues($aArgs, $sContains);
		}
		else
		{
			return parent::GetAllowedValues($aArgs, $sContains);
		}
	}

	public function GetAllowedValuesAsObjectSet($aArgs = array(), $sContains = '', $iAdditionalValue = null)
	{
		$oValSetDef = $this->GetValuesDef();
		$oFilter = $this->GetHierachicalFilter($aArgs, $sContains, $iAdditionalValue);
		if ($oFilter)
		{
			$oValSetDef->SetCondition($oFilter);
		}
		$oSet = $oValSetDef->ToObjectSet($aArgs, $sContains, $iAdditionalValue);

		return $oSet;
	}

	public function GetAllowedValuesAsFilter($aArgs = array(), $sContains = '', $iAdditionalValue = null)
	{
		$oFilter = $this->GetHierachicalFilter($aArgs, $sContains, $iAdditionalValue);
		if ($oFilter)
		{
			return $oFilter;
		}

		return parent::GetAllowedValuesAsFilter($aArgs, $sContains, $iAdditionalValue);
	}

	private function GetHierachicalFilter($aArgs = array(), $sContains = '', $iAdditionalValue = null)
	{
		if (array_key_exists('this', $aArgs))
		{
			// Hierarchical keys have one more constraint: the "parent value" cannot be
			// "under" themselves
			$iRootId = $aArgs['this']->GetKey();
			if ($iRootId > 0) // ignore objects that do no exist in the database...
			{
				$sClass = $this->m_sTargetClass;

				return DBObjectSearch::FromOQL("SELECT $sClass AS node JOIN $sClass AS root ON node.".$this->GetCode()." NOT BELOW root.id WHERE root.id = $iRootId");
			}
		}

		return false;
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
}

/**
 * An attribute which corresponds to an external key (direct or indirect)
 *
 * @package     iTopORM
 */
class AttributeExternalField extends AttributeDefinition
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	/**
	 * Return the search widget type corresponding to this attribute
	 *
	 * @return string
	 * @throws \CoreException
	 */
	public function GetSearchType()
	{
		// Not necessary the external key is already present
		if ($this->IsFriendlyName())
		{
			return self::SEARCH_WIDGET_TYPE_RAW;
		}

		try
		{
			$oRemoteAtt = $this->GetFinalAttDef();
			switch (true)
			{
				case ($oRemoteAtt instanceof AttributeString):
					return self::SEARCH_WIDGET_TYPE_EXTERNAL_FIELD;
				case ($oRemoteAtt instanceof AttributeExternalKey):
					return self::SEARCH_WIDGET_TYPE_EXTERNAL_KEY;
			}
		} catch (CoreException $e)
		{
		}

		return self::SEARCH_WIDGET_TYPE_RAW;
	}

	function IsSearchable()
	{
		if ($this->IsFriendlyName())
		{
			return true;
		}
		return parent::IsSearchable();
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("extkey_attcode", "target_attcode"));
	}

	public function GetEditClass()
	{
		return "ExtField";
	}

	/**
	 * @return \AttributeDefinition
	 * @throws \CoreException
	 */
	public function GetFinalAttDef()
	{
		$oExtAttDef = $this->GetExtAttDef();

		return $oExtAttDef->GetFinalAttDef();
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		// throw new CoreException("external attribute: does it make any sense to request its type ?");
		$oExtAttDef = $this->GetExtAttDef();

		return $oExtAttDef->GetSQLCol($bFullSpec);
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			return array('' => $this->GetCode()); // Warning: Use GetCode() since AttributeExternalField does not have any 'sql' property
		}
		else
		{
			return $sPrefix;
		}
	}

	/**
	 * @param string $sDefault
	 *
	 * @return string dict entry if defined, otherwise :
	 *    <ul>
	 *    <li>if field is a friendlyname then display the label of the ExternalKey
	 *    <li>the class hierarchy -> field name
	 *
	 *    <p>For example, having this :
	 *
	 * <pre>
	 *       +---------------------+     +--------------------+      +--------------+
	 *       | Class A             |     | Class B            |      | Class C      |
	 *       +---------------------+     +--------------------+      +--------------+
	 *       | foo <ExternalField>-------->c_id_friendly_name--------->friendlyname |
	 *       +---------------------+     +--------------------+      +--------------+
	 * </pre>
	 *
	 *       <p>The ExternalField foo points to a magical field that is brought by c_id ExternalKey in class B.
	 *
	 *       <p>In the normal case the foo label would be : B -> C -> friendlyname<br>
	 *       But as foo is a friendlyname its label will be the same as the one on A.b_id field
	 *       This can be overrided with dict key Class:ClassA/Attribute:foo
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function GetLabel($sDefault = null)
	{
		$sLabelDefaultValue = '';
		$sLabel = parent::GetLabel($sLabelDefaultValue);
		if ($sLabelDefaultValue !== $sLabel)
		{
			return $sLabel;
		}

		if ($this->IsFriendlyName() && ($this->Get("target_attcode") === "friendlyname"))
		{
			// This will be used even if we are pointing to a friendlyname in a distance > 1
			// For example we can link to a magic friendlyname (like org_id_friendlyname)
			// If a specific label is needed, use a Dict key !
			// See N°2174
			$sKeyAttCode = $this->Get("extkey_attcode");
			$oExtKeyAttDef = MetaModel::GetAttributeDef($this->GetHostClass(), $sKeyAttCode);
			$sLabel = $oExtKeyAttDef->GetLabel($this->m_sCode);

			return $sLabel;
		}

		$oRemoteAtt = $this->GetExtAttDef();
		$sLabel = $oRemoteAtt->GetLabel($this->m_sCode);
		$oKeyAtt = $this->GetKeyAttDef();
		$sKeyLabel = $oKeyAtt->GetLabel($this->GetKeyAttCode());
		$sLabel = "{$sKeyLabel}->{$sLabel}";

		return $sLabel;
	}

	public function GetLabelForSearchField()
	{
		$sLabel = parent::GetLabel('');
		if (strlen($sLabel) == 0)
		{
			$sKeyAttCode = $this->Get("extkey_attcode");
			$oExtKeyAttDef = MetaModel::GetAttributeDef($this->GetHostClass(), $sKeyAttCode);
			$sLabel = $oExtKeyAttDef->GetLabel($this->m_sCode);

			$oRemoteAtt = $this->GetExtAttDef();
			$sLabel .= '->'.$oRemoteAtt->GetLabel($this->m_sCode);
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
		switch ($iType)
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

	/**
	 * @return bool
	 * @throws \CoreException
	 */
	public function IsFriendlyName()
	{
		$oRemoteAtt = $this->GetExtAttDef();
		if ($oRemoteAtt instanceof AttributeExternalField)
		{
			$bRet = $oRemoteAtt->IsFriendlyName();
		}
		elseif ($oRemoteAtt instanceof AttributeFriendlyName)
		{
			$bRet = true;
		}
		else
		{
			$bRet = false;
		}

		return $bRet;
	}

	public function GetTargetClass($iType = EXTKEY_RELATIVE)
	{
		return $this->GetKeyAttDef($iType)->GetTargetClass();
	}

	public static function IsExternalField()
	{
		return true;
	}

	public function GetKeyAttCode()
	{
		return $this->Get("extkey_attcode");
	}

	public function GetExtAttCode()
	{
		return $this->Get("target_attcode");
	}

	/**
	 * @param int $iType
	 *
	 * @return \AttributeExternalKey
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function GetKeyAttDef($iType = EXTKEY_RELATIVE)
	{
		switch ($iType)
		{
			case EXTKEY_ABSOLUTE:
				// see further
				/** @var \AttributeExternalKey $oRemoteAtt */
				$oRemoteAtt = $this->GetExtAttDef();
				if ($oRemoteAtt->IsExternalField())
				{
					return $oRemoteAtt->GetKeyAttDef(EXTKEY_ABSOLUTE);
				}
				else
				{
					if ($oRemoteAtt->IsExternalKey())
					{
						return $oRemoteAtt;
					}
				}

				return $this->GetKeyAttDef(EXTKEY_RELATIVE); // which corresponds to the code hereafter !

			case EXTKEY_RELATIVE:
				/** @var \AttributeExternalKey $oAttDef */
				$oAttDef = MetaModel::GetAttributeDef($this->GetHostClass(), $this->Get("extkey_attcode"));

				return $oAttDef;

			default:
				throw new CoreException("Unexpected value for argument iType: '$iType'");
		}
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		return array($this->Get("extkey_attcode"));
	}


	/**
	 * @return \AttributeExternalField
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function GetExtAttDef()
	{
		$oKeyAttDef = $this->GetKeyAttDef();
		/** @var \AttributeExternalField $oExtAttDef */
		$oExtAttDef = MetaModel::GetAttributeDef($oKeyAttDef->GetTargetClass(), $this->Get("target_attcode"));
		if (!is_object($oExtAttDef))
		{
			throw new CoreException("Invalid external field ".$this->GetCode()." in class ".$this->GetHostClass().". The class ".$oKeyAttDef->GetTargetClass()." has no attribute ".$this->Get("target_attcode"));
		}

		return $oExtAttDef;
	}

	/**
	 * @return mixed
	 * @throws \CoreException
	 */
	public function GetSQLExpr()
	{
		$oExtAttDef = $this->GetExtAttDef();

		return $oExtAttDef->GetSQLExpr();
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		$oExtAttDef = $this->GetExtAttDef();

		return $oExtAttDef->GetDefaultValue();
	}

	public function IsNullAllowed()
	{
		$oExtAttDef = $this->GetExtAttDef();

		return $oExtAttDef->IsNullAllowed();
	}

	public static function IsScalar()
	{
		return true;
	}

	public function GetFilterDefinitions()
	{
		return array($this->GetCode() => $this->GetCode());
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

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		$oExtAttDef = $this->GetExtAttDef();

		return $oExtAttDef->HasAValue($proposedValue);
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		$oExtAttDef = $this->GetExtAttDef();

		return $oExtAttDef->MakeRealValue($proposedValue, $oHostObj);
	}

	/**
	 * @inheritDoc
	 * @since 3.1.0 N°6271 Delegate to remote attribute to ensure cascading computed values
	 */
	public function GetSQLValues($value)
	{
		$oExtAttDef = $this->GetExtAttDef();

		return $oExtAttDef->GetSQLValues($value);
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

	public function GetAsCSV(
		$value, $sSeparator = ',', $sTestQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		$oExtAttDef = $this->GetExtAttDef();

		return $oExtAttDef->GetAsCSV($value, $sSeparator, $sTestQualifier, null, $bLocalize, $bConvertToPlainText);
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\LabelField';
	}

	/**
	 * @param \DBObject $oObject
	 * @param \Combodo\iTop\Form\Field\Field $oFormField
	 *
	 * @return null
	 * @throws \CoreException
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		// Retrieving AttDef from the remote attribute
		$oRemoteAttDef = $this->GetExtAttDef();

		if ($oFormField === null)
		{
			// ExternalField's FormField are actually based on the FormField from the target attribute.
			// Except for the AttributeExternalKey because we have no OQL and stuff
			if ($oRemoteAttDef instanceof AttributeExternalKey)
			{
				$sFormFieldClass = static::GetFormFieldClass();
			}
			else
			{
				$sFormFieldClass = $oRemoteAttDef::GetFormFieldClass();
			}
			/** @var \Combodo\iTop\Form\Field\Field $oFormField */
			$oFormField = new $sFormFieldClass($this->GetCode());
			switch ($sFormFieldClass)
			{
				case '\Combodo\iTop\Form\Field\SelectField':
					$oFormField->SetChoices($oRemoteAttDef->GetAllowedValues($oObject->ToArgsForQuery()));
					break;
				default:
					break;
			}
		}
		parent::MakeFormField($oObject, $oFormField);
		if ($oFormField instanceof TextAreaField) {
			if (method_exists($oRemoteAttDef, 'GetFormat')) {
				/** @var \Combodo\iTop\Form\Field\TextAreaField $oFormField */
				$oFormField->SetFormat($oRemoteAttDef->GetFormat());
			}
		}

		// Manually setting for remote ExternalKey, otherwise, the id would be displayed.
		if ($oRemoteAttDef instanceof AttributeExternalKey)
		{
			$oFormField->SetCurrentValue($oObject->Get($this->GetCode().'_friendlyname'));
		}

		// Readonly field because we can't update external fields
		$oFormField->SetReadOnly(true);

		return $oFormField;
	}

	public function IsPartOfFingerprint()
	{
		return false;
	}

	public function GetFormat()
	{
		$oRemoteAttDef = $this->GetExtAttDef();
		if (method_exists($oRemoteAttDef, 'GetFormat')) {
			/** @var \Combodo\iTop\Form\Field\TextAreaField $oFormField */
			return $oRemoteAttDef->GetFormat();
		}
		return 'text';
	}
}


/**
 * Map a varchar column to an URL (formats the ouput in HMTL)
 *
 * @package     iTopORM
 */
class AttributeURL extends AttributeString
{
	/**
	 * @var string
	 * SCHEME....... USER....................... PASSWORD.......................... HOST/IP........... PORT.......... PATH......................... GET............................................ ANCHOR..........................
	 * Example: http://User:passWord@127.0.0.1:8888/patH/Page.php?arrayArgument[2]=something:blah20#myAnchor
	 * @link http://www.php.net/manual/fr/function.preg-match.php#93824 regexp source
	 * @since 3.0.1 N°4515 handle Alfresco and Sharepoint URLs
	 * @since 3.0.3 moved from Config to AttributeURL constant
	 */
	public const DEFAULT_VALIDATION_PATTERN = /** @lang RegExp */
		'(https?|ftp)\://([a-zA-Z0-9+!*(),;?&=\$_.-]+(\:[a-zA-Z0-9+!*(),;?&=\$_.-]+)?@)?([a-zA-Z0-9-.]{3,})(\:[0-9]{2,5})?(/([a-zA-Z0-9:%+\$_-]\.?)+)*/?(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:[\]@&%=+/\$_.,-]*)?(#[a-zA-Z0-9_.-][a-zA-Z0-9+\$_.-]*)?';

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		//return parent::ListExpectedParams();
		return array_merge(parent::ListExpectedParams(), array("target"));
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "VARCHAR(2048)"
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	public function GetMaxSize()
	{
		return 2048;
	}

	public function GetEditClass()
	{
		return "String";
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$sTarget = $this->Get("target");
		if (empty($sTarget))
		{
			$sTarget = "_blank";
		}
		$sLabel = Str::pure2html($sValue);
		if (strlen($sLabel) > 128)
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

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\UrlField';
	}

	/**
	 * @param \DBObject $oObject
	 * @param  \Combodo\iTop\Form\Field\UrlField $oFormField
	 *
	 * @return null
	 * @throws \CoreException
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		parent::MakeFormField($oObject, $oFormField);

		$oFormField->SetTarget($this->Get('target'));

		return $oFormField;
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeURL::class;
	}
}

/**
 * A blob is an ormDocument, it is stored as several columns in the database
 *
 * @package     iTopORM
 */
class AttributeBlob extends AttributeDefinition
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("depends_on"));
	}

	public function GetEditClass()
	{
		return "Document";
	}

	public static function IsBasedOnDBColumns()
	{
		return true;
	}

	public static function IsScalar()
	{
		return true;
	}

	public function IsWritable()
	{
		return true;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return new ormDocument('', '', '');
	}

	public function IsNullAllowed(DBObject $oHostObject = null)
	{
		return $this->GetOptional("is_null_allowed", false);
	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		return '';
	}

	/**
     * {@inheritDoc}
     *
     * @param string $proposedValue Can be an URL (including an URL to iTop itself), or a local path (CSV import)
	 *
	 * @see AttributeDefinition::MakeRealValue()
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if ($proposedValue === null)
		{
			return null;
		}

		if (is_object($proposedValue))
		{
			$proposedValue = clone $proposedValue;
		}
		else
		{
			try
			{
				// Read the file from iTop, an URL (or the local file system - for admins only)
				$proposedValue = Utils::FileGetContentsAndMIMEType($proposedValue);
			} catch (Exception $e)
			{
				IssueLog::Warning(get_class($this)."::MakeRealValue - ".$e->getMessage());
				// Not a real document !! store is as text !!! (This was the default behavior before)
				$proposedValue = new ormDocument($e->getMessage()." \n".$proposedValue, 'text/plain');
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
		$aColumns['_downloads_count'] = $sPrefix.'_downloads_count';

		return $aColumns;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		if (!array_key_exists($sPrefix, $aCols)) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		}
		$sMimeType = isset($aCols[$sPrefix]) ? $aCols[$sPrefix] : '';

		if (!array_key_exists($sPrefix.'_data', $aCols)) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_data' from {$sAvailable}");
		}
		$data = isset($aCols[$sPrefix.'_data']) ? $aCols[$sPrefix.'_data'] : null;

		if (!array_key_exists($sPrefix.'_filename', $aCols)) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_filename' from {$sAvailable}");
		}
		$sFileName = isset($aCols[$sPrefix.'_filename']) ? $aCols[$sPrefix.'_filename'] : '';

		if (!array_key_exists($sPrefix.'_downloads_count', $aCols)) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_downloads_count' from {$sAvailable}");
		}
		$iDownloadsCount = isset($aCols[$sPrefix.'_downloads_count']) ? $aCols[$sPrefix.'_downloads_count'] : ormDocument::DEFAULT_DOWNLOADS_COUNT;

		$value = new ormDocument($data, $sMimeType, $sFileName, $iDownloadsCount);

		return $value;
	}

	public function GetSQLValues($value)
	{
		// #@# Optimization: do not load blobs anytime
		//	 As per mySQL doc, selecting blob columns will prevent mySQL from
		//	 using memory in case a temporary table has to be created
		//	 (temporary tables created on disk)
		//	 We will have to remove the blobs from the list of attributes when doing the select
		//	 then the use of Get() should finalize the load
		if ($value instanceOf ormDocument)
		{
			$aValues = array();
			if (!$value->IsEmpty())
			{
				$aValues[$this->GetCode().'_data'] = $value->GetData();
			}
			else
			{
				$aValues[$this->GetCode().'_data'] = '';
			}
			$aValues[$this->GetCode().'_mimetype'] = $value->GetMimeType();
			$aValues[$this->GetCode().'_filename'] = $value->GetFileName();
			$aValues[$this->GetCode().'_downloads_count'] = $value->GetDownloadsCount();
		}
		else
		{
			$aValues = array();
			$aValues[$this->GetCode().'_data'] = '';
			$aValues[$this->GetCode().'_mimetype'] = '';
			$aValues[$this->GetCode().'_filename'] = '';
			$aValues[$this->GetCode().'_downloads_count'] = ormDocument::DEFAULT_DOWNLOADS_COUNT;
		}

		return $aValues;
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->GetCode().'_data'] = 'LONGBLOB'; // 2^32 (4 Gb)
		$aColumns[$this->GetCode().'_mimetype'] = 'VARCHAR(255)'.CMDBSource::GetSqlStringColumnDefinition();
		$aColumns[$this->GetCode().'_filename'] = 'VARCHAR(255)'.CMDBSource::GetSqlStringColumnDefinition();
		$aColumns[$this->GetCode().'_downloads_count'] = 'INT(11) UNSIGNED';

		return $aColumns;
	}

	public function GetFilterDefinitions()
	{
		return array();
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

		return '';
	}

	/**
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
	) {
		$sAttCode = $this->GetCode();
		if ($sValue instanceof ormDocument && !$sValue->IsEmpty())
		{
			return $sValue->GetDownloadURL(get_class($oHostObject), $oHostObject->GetKey(), $sAttCode);
		}

		return ''; // Not exportable in CSV !
	}

	/**
	 * @param $value
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return mixed|string
	 */
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		$sRet = '';
		if (is_object($value))
		{
			/** @var \ormDocument $value */
			if (!$value->IsEmpty())
			{
				$sRet = '<mimetype>'.$value->GetMimeType().'</mimetype>';
				$sRet .= '<filename>'.$value->GetFileName().'</filename>';
				$sRet .= '<data>'.base64_encode($value->GetData()).'</data>';
				$sRet .= '<downloads_count>'.$value->GetDownloadsCount().'</downloads_count>';
			}
		}

		return $sRet;
	}

	public function GetForJSON($value)
	{
		if ($value instanceOf ormDocument)
		{
			$aValues = array();
			$aValues['data'] = base64_encode($value->GetData());
			$aValues['mimetype'] = $value->GetMimeType();
			$aValues['filename'] = $value->GetFileName();
			$aValues['downloads_count'] = $value->GetDownloadsCount();
		}
		else
		{
			$aValues = null;
		}

		return $aValues;
	}

	public function FromJSONToValue($json)
	{
		if (isset($json->data))
		{
			$data = base64_decode($json->data);
			$value = new ormDocument($data, $json->mimetype, $json->filename, $json->downloads_count);
		}
		else
		{
			$value = null;
		}

		return $value;
	}

	public function Fingerprint($value)
	{
		$sFingerprint = '';
		if ($value instanceOf ormDocument)
		{
			$sFingerprint = $value->GetSignature();
		}

		return $sFingerprint;
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\BlobField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		/** @var $oFormField \Combodo\iTop\Form\Field\BlobField */
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		// Note: As of today we want this field to always be read-only
		$oFormField->SetReadOnly(true);

		// Calling parent before so current value is set, then proceed
		parent::MakeFormField($oObject, $oFormField);

		// Setting current value correctly as the default method returns an empty string when there is no file yet.
		/** @var \ormDocument $value */
		$value = $oObject->Get($this->GetCode());
		if(!is_object($value))
		{
			$oFormField->SetCurrentValue(new ormDocument());
		}

		// Generating urls
		if(is_object($value) && !$value->IsEmpty())
		{
			$oFormField->SetDownloadUrl($value->GetDownloadURL(get_class($oObject), $oObject->GetKey(), $this->GetCode()));
			$oFormField->SetDisplayUrl($value->GetDisplayURL(get_class($oObject), $oObject->GetKey(), $this->GetCode()));
		}

		return $oFormField;
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		if (false === ($proposedValue instanceof ormDocument)) {
			return parent::HasAValue($proposedValue);
		}

		// Empty file (no content, just a filename) are supported since PR {@link https://github.com/Combodo/combodo-email-synchro/pull/17}, so we check for both empty content and empty filename to determine that a document has no value
		return utils::IsNotNullOrEmptyString($proposedValue->GetData()) && utils::IsNotNullOrEmptyString($proposedValue->GetFileName());
	}

	/**
	 * @inheritDoc
	 * @param \ormDocument $original
	 * @param \ormDocument $value
	 * @since N°6502
	 */
	public function RecordAttChange(DBObject $oObject, $original, $value): void
	{
		// N°6502 Don't record history if only the download count has changed
		if ((null !== $original) && (null !== $value) && $original->EqualsExceptDownloadsCount($value)) {
			return;
		}

		parent::RecordAttChange($oObject, $original, $value);
	}

	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		if (is_null($original)) {
			$original = new ormDocument();
		}
		$oMyChangeOp->Set("prevdata", $original);
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeBlob::class;
	}
}

/**
 * An image is a specific type of document, it is stored as several columns in the database
 *
 * @package     iTopORM
 */
class AttributeImage extends AttributeBlob
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function Get($sParamName)
	{
		$oParamValue = parent::Get($sParamName);

		if ($sParamName === 'default_image') {
			/** @noinspection NestedPositiveIfStatementsInspection */
			if (!empty($oParamValue)) {
				return utils::GetAbsoluteUrlModulesRoot() . $oParamValue;
			}
		}

		return $oParamValue;
	}

	public function GetEditClass()
	{
		return "Image";
	}

	/**
	 * {@inheritDoc}
	 * @see AttributeBlob::MakeRealValue()
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		$oDoc = parent::MakeRealValue($proposedValue, $oHostObj);

		if (($oDoc instanceof ormDocument)
			&& (false === $oDoc->IsEmpty())
			&& ($oDoc->GetMimeType() === 'image/svg+xml')) {
			$sCleanSvg = HTMLSanitizer::Sanitize($oDoc->GetData(), 'svg_sanitizer');
			$oDoc = new ormDocument($sCleanSvg, $oDoc->GetMimeType(), $oDoc->GetFileName());
		}

		// The validation of the MIME Type is done by CheckFormat below
		return $oDoc;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return new ormDocument('', '', '');
	}

	/**
	 * Check that the supplied ormDocument actually contains an image
	 * {@inheritDoc}
	 *
	 * @see AttributeDefinition::CheckFormat()
	 */
	public function CheckFormat($value)
	{
		if ($value instanceof ormDocument && !$value->IsEmpty())
		{
			return ($value->GetMainMimeType() == 'image');
		}

		return true;
	}

	/**
	 * @param \ormDocument $value
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 *
	 * @see edit_image.js for JS generated markup in form edition
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		$sRet = '';
		$bIsCustomImage = false;

		$iMaxWidth = $this->Get('display_max_width');
		$sMaxWidthPx = $iMaxWidth.'px';
		$iMaxHeight = $this->Get('display_max_height');
		$sMaxHeightPx = $iMaxHeight.'px';

		$sDefaultImageUrl = $this->Get('default_image');
		if ($sDefaultImageUrl !== null) {
			$sRet = $this->GetHtmlForImageUrl($sDefaultImageUrl, $sMaxWidthPx, $sMaxHeightPx);
		}

		$sCustomImageUrl = $this->GetAttributeImageFileUrl($value, $oHostObject);
		if ($sCustomImageUrl !== null) {
			$bIsCustomImage = true;
			$sRet = $this->GetHtmlForImageUrl($sCustomImageUrl, $sMaxWidthPx, $sMaxHeightPx);
		}

		$sCssClasses = 'ibo-input-image--image-view attribute-image';
		$sCssClasses .= ' '.(($bIsCustomImage) ? 'attribute-image-custom' : 'attribute-image-default');

		// Important: If you change this, mind updating edit_image.js as well
		return '<div class="'.$sCssClasses.'" style="max-width: min('.$sMaxWidthPx.',100%); max-height: min('.$sMaxHeightPx.',100%); aspect-ratio: '.$iMaxWidth.' / '.$iMaxHeight.'">'.$sRet.'</div>';
	}

	/**
	 * @param string $sUrl
	 * @param int $iMaxWidthPx
	 * @param int $iMaxHeightPx
	 *
	 * @return string
	 *
	 * @since 2.6.0 new private method
	 * @since 2.7.0 change visibility to protected
	 */
	protected function GetHtmlForImageUrl($sUrl, $iMaxWidthPx, $iMaxHeightPx) {
		return '<img src="'.$sUrl.'" style="max-width: min('.$iMaxWidthPx.',100%); max-height: min('.$iMaxHeightPx.',100%)">';
	}

	/**
	 * @param \ormDocument $value
	 * @param \DBObject $oHostObject
	 *
	 * @return null|string
	 *
	 * @since 2.6.0 new private method
	 * @since 2.7.0 change visibility to protected
	 */
	protected function GetAttributeImageFileUrl($value, $oHostObject) {
		if (!is_object($value)) {
			return null;
		}
		if ($value->IsEmpty()) {
			return null;
		}

		$bExistingImageModified = ($oHostObject->IsModified() && (array_key_exists($this->GetCode(), $oHostObject->ListChanges())));
		if ($oHostObject->IsNew() || ($bExistingImageModified))
		{
			// If the object is modified (or not yet stored in the database) we must serve the content of the image directly inline
			// otherwise (if we just give an URL) the browser will be given the wrong content... and may cache it
			return 'data:'.$value->GetMimeType().';base64,'.base64_encode($value->GetData());
		}

		return $value->GetDisplayURL(get_class($oHostObject), $oHostObject->GetKey(), $this->GetCode());
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\ImageField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		parent::MakeFormField($oObject, $oFormField);

		// Generating urls
		$value = $oObject->Get($this->GetCode());
		if (is_object($value) && !$value->IsEmpty())
		{
			$oFormField->SetDownloadUrl($value->GetDownloadURL(get_class($oObject), $oObject->GetKey(), $this->GetCode()));
			$oFormField->SetDisplayUrl($value->GetDisplayURL(get_class($oObject), $oObject->GetKey(),	$this->GetCode()));
		}
		else
		{
			$oDefaultImage = $this->Get('default_image');
			if (is_object($oDefaultImage) && !$oDefaultImage->IsEmpty()) {
				$oFormField->SetDownloadUrl($oDefaultImage);
				$oFormField->SetDisplayUrl($oDefaultImage);
			}
		}

		return $oFormField;
	}
}

/**
 * A stop watch is an ormStopWatch object, it is stored as several columns in the database
 *
 * @package     iTopORM
 */
class AttributeStopWatch extends AttributeDefinition
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		// The list of thresholds must be an array of iPercent => array of 'option' => value
		return array_merge(parent::ListExpectedParams(),
			array("states", "goal_computing", "working_time_computing", "thresholds"));
	}

	public function GetEditClass()
	{
		return "StopWatch";
	}

	public static function IsBasedOnDBColumns()
	{
		return true;
	}

	public static function IsScalar()
	{
		return true;
	}

	public function IsWritable()
	{
		return true;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return $this->NewStopWatch();
	}

	/**
	 * @param \ormStopWatch $value
	 * @param \DBObject $oHostObj
	 *
	 * @return string
	 */
	public function GetEditValue($value, $oHostObj = null)
	{
		return $value->GetTimeSpent();
	}

	public function GetStates()
	{
		return $this->Get('states');
	}

	public function AlwaysLoadInTables()
	{
		// Each and every stop watch is accessed for computing the highlight code (DBObject::GetHighlightCode())
		return true;
	}

	/**
	 * Construct a brand new (but configured) stop watch
	 */
	public function NewStopWatch()
	{
		$oSW = new ormStopWatch();
		foreach($this->ListThresholds() as $iThreshold => $aFoo)
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
			$sPrefix = $this->GetCode(); // Warning: a stopwatch does not have any 'sql' property, so its SQL column is equal to its attribute code !!
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix.'_timespent';
		$aColumns['_started'] = $sPrefix.'_started';
		$aColumns['_laststart'] = $sPrefix.'_laststart';
		$aColumns['_stopped'] = $sPrefix.'_stopped';
		foreach($this->ListThresholds() as $iThreshold => $aFoo)
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
		foreach($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$sThPrefix = '_'.$iThreshold;
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_deadline';
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_passed';
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_triggered';
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_overrun';
		}
		foreach($aExpectedCols as $sExpectedCol)
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

		foreach($this->ListThresholds() as $iThreshold => $aDefinition)
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

			foreach($this->ListThresholds() as $iThreshold => $aFoo)
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

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->GetCode().'_timespent'] = 'INT(11) UNSIGNED';
		$aColumns[$this->GetCode().'_started'] = 'DATETIME';
		$aColumns[$this->GetCode().'_laststart'] = 'DATETIME';
		$aColumns[$this->GetCode().'_stopped'] = 'DATETIME';
		foreach($this->ListThresholds() as $iThreshold => $aFoo)
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
			$this->GetCode()              => $this->GetCode(),
			$this->GetCode().'_started'   => $this->GetCode(),
			$this->GetCode().'_laststart' => $this->GetCode(),
			$this->GetCode().'_stopped'   => $this->GetCode(),
		);
		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sPrefix = $this->GetCode().'_'.$iThreshold;
			$aRes[$sPrefix.'_deadline'] = $this->GetCode();
			$aRes[$sPrefix.'_passed'] = $this->GetCode();
			$aRes[$sPrefix.'_triggered'] = $this->GetCode();
			$aRes[$sPrefix.'_overrun'] = $this->GetCode();
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

	/**
	 * @param \ormStopWatch $value
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value))
		{
			return $value->GetAsHTML($this, $oHostObject);
		}

		return '';
	}

	/**
	 * @param ormStopWatch $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param null $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return string
	 */
	public function GetAsCSV(
		$value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		return $value->GetTimeSpent();
	}

	/**
	 * @param \ormStopWatch $value
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return mixed
	 */
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return $value->GetTimeSpent();
	}

	public function ListThresholds()
	{
		return $this->Get('thresholds');
	}

	public function Fingerprint($value)
	{
		$sFingerprint = '';
		if (is_object($value))
		{
			$sFingerprint = $value->GetAsHTML($this);
		}

		return $sFingerprint;
	}

	/**
	 * To expose internal values: Declare an attribute AttributeSubItem
	 * and implement the GetSubItemXXXX verbs
	 *
	 * @param string $sItemCode
	 *
	 * @return array
	 * @throws \CoreException
	 */
	public function GetSubItemSQLExpression($sItemCode)
	{
		$sPrefix = $this->GetCode();
		switch ($sItemCode)
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

		foreach($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$sThPrefix = $iThreshold.'_';
			if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
			{
				// The current threshold is concerned
				$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
				switch ($sThresholdCode)
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

	/**
	 * @param string $sItemCode
	 * @param \ormStopWatch $value
	 * @param \DBObject $oHostObject
	 *
	 * @return mixed
	 * @throws \CoreException
	 */
	public function GetSubItemValue($sItemCode, $value, $oHostObject = null)
	{
		$oStopWatch = $value;
		switch ($sItemCode)
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

		foreach($this->ListThresholds() as $iThreshold => $aFoo)
		{
			$sThPrefix = $iThreshold.'_';
			if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
			{
				// The current threshold is concerned
				$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
				switch ($sThresholdCode)
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


    public function GetSubItemSearchType($sItemCode)
    {
        switch ($sItemCode)
        {
            case 'timespent':
                return static::SEARCH_WIDGET_TYPE_NUMERIC;  //seconds
            case 'started':
            case 'laststart':
            case 'stopped':
                return static::SEARCH_WIDGET_TYPE_DATE_TIME; //timestamp
        }

        foreach($this->ListThresholds() as $iThreshold => $aFoo)
        {
            $sThPrefix = $iThreshold.'_';
            if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
            {
                // The current threshold is concerned
                $sThresholdCode = substr($sItemCode, strlen($sThPrefix));
                switch ($sThresholdCode)
                {
                    case 'deadline':
                        return static::SEARCH_WIDGET_TYPE_DATE_TIME; //timestamp
                    case 'passed':
                    case 'triggered':
                        return static::SEARCH_WIDGET_TYPE_ENUM; //booleans, used in conjuction with GetSubItemAllowedValues and IsSubItemNullAllowed
                    case 'overrun':
                        return static::SEARCH_WIDGET_TYPE_NUMERIC; //seconds
                }
            }
        }

        return static::SEARCH_WIDGET_TYPE_RAW;
    }

    public function GetSubItemAllowedValues($sItemCode, $aArgs = array(), $sContains = '')
    {
        foreach($this->ListThresholds() as $iThreshold => $aFoo)
        {
            $sThPrefix = $iThreshold.'_';
            if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
            {
                // The current threshold is concerned
                $sThresholdCode = substr($sItemCode, strlen($sThPrefix));
                switch ($sThresholdCode)
                {
                    case 'passed':
                    case 'triggered':
                        return array(
                            0 => $this->GetBooleanLabel(0),
                            1 => $this->GetBooleanLabel(1),
                        );
                }
            }
        }

        return null;
    }

    public function IsSubItemNullAllowed($sItemCode, $bDefaultValue)
    {
        foreach($this->ListThresholds() as $iThreshold => $aFoo)
        {
            $sThPrefix = $iThreshold.'_';
            if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
            {
                // The current threshold is concerned
                $sThresholdCode = substr($sItemCode, strlen($sThPrefix));
                switch ($sThresholdCode)
                {
                    case 'passed':
                    case 'triggered':
                       return false;
                }
            }
        }

        return $bDefaultValue;
    }

	protected function GetBooleanLabel($bValue)
	{
		$sDictKey = $bValue ? 'yes' : 'no';

		return Dict::S('BooleanLabel:'.$sDictKey, 'def:'.$sDictKey);
	}

	public function GetSubItemAsHTMLForHistory($sItemCode, $sValue)
	{
		$sHtml = null;
		switch ($sItemCode)
		{
			case 'timespent':
				$sHtml = (int)$sValue ? Str::pure2html(AttributeDuration::FormatDuration($sValue)) : null;
				break;
			case 'started':
			case 'laststart':
			case 'stopped':
				$sHtml = (int)$sValue ? date((string)AttributeDateTime::GetFormat(), (int)$sValue) : null;
				break;

			default:
				foreach($this->ListThresholds() as $iThreshold => $aFoo)
				{
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
					{
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode)
						{
							case 'deadline':
								$sHtml = (int)$sValue ? date((string)AttributeDateTime::GetFormat(),
									(int)$sValue) : null;
								break;
							case 'passed':
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

	public function GetSubItemAsPlainText($sItemCode, $value)
	{
		$sRet = $value;

		switch ($sItemCode)
		{
			case 'timespent':
				$sRet = AttributeDuration::FormatDuration($value);
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
					$oDateTime = new DateTime();
					$oDateTime->setTimestamp($value);
					$oDateTimeFormat = AttributeDateTime::GetFormat();
					$sRet = $oDateTimeFormat->Format($oDateTime);
				}
				break;

			default:
				foreach($this->ListThresholds() as $iThreshold => $aFoo)
				{
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
					{
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode)
						{
							case 'deadline':
								if ($value)
								{
									$sDate = date(AttributeDateTime::GetInternalFormat(), $value);
									$sRet = AttributeDeadline::FormatDeadline($sDate);
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
								$sRet = AttributeDuration::FormatDuration($value);
								break;
						}
					}
				}
		}

		return $sRet;
	}

	public function GetSubItemAsHTML($sItemCode, $value)
	{
		$sHtml = $value;

		switch ($sItemCode)
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
					$oDateTime = new DateTime();
					$oDateTime->setTimestamp($value);
					$oDateTimeFormat = AttributeDateTime::GetFormat();
					$sHtml = Str::pure2html($oDateTimeFormat->Format($oDateTime));
				}
				break;

			default:
				foreach($this->ListThresholds() as $iThreshold => $aFoo)
				{
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
					{
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode)
						{
							case 'deadline':
								if ($value)
								{
									$sDate = date(AttributeDateTime::GetInternalFormat(), $value);
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

	public function GetSubItemAsCSV(
		$sItemCode, $value, $sSeparator = ',', $sTextQualifier = '"', $bConvertToPlainText = false
	) {
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$value);
		$sRet = $sTextQualifier.$sEscaped.$sTextQualifier;

		switch ($sItemCode)
		{
			case 'timespent':
				$sRet = $sTextQualifier.AttributeDuration::FormatDuration($value).$sTextQualifier;
				break;
			case 'started':
			case 'laststart':
			case 'stopped':
				if ($value !== null)
				{
					$oDateTime = new DateTime();
					$oDateTime->setTimestamp($value);
					$oDateTimeFormat = AttributeDateTime::GetFormat();
					$sRet = $sTextQualifier.$oDateTimeFormat->Format($oDateTime).$sTextQualifier;
				}
				break;

			default:
				foreach($this->ListThresholds() as $iThreshold => $aFoo)
				{
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
					{
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode)
						{
							case 'deadline':
								if ($value != '')
								{
									$oDateTime = new DateTime();
									$oDateTime->setTimestamp($value);
									$oDateTimeFormat = AttributeDateTime::GetFormat();
									$sRet = $sTextQualifier.$oDateTimeFormat->Format($oDateTime).$sTextQualifier;
								}
								break;

							case 'passed':
							case 'triggered':
								$sRet = $sTextQualifier.$this->GetBooleanLabel($value).$sTextQualifier;
								break;

							case 'overrun':
								$sRet = $sTextQualifier.AttributeDuration::FormatDuration($value).$sTextQualifier;
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

		switch ($sItemCode)
		{
			case 'timespent':
			case 'started':
			case 'laststart':
			case 'stopped':
				break;

			default:
				foreach($this->ListThresholds() as $iThreshold => $aFoo)
				{
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
					{
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode)
						{
							case 'deadline':
							case 'overrun':
								break;

							case 'triggered':
							case 'passed':
								$sRet = $this->GetBooleanLabel($value);
								break;
						}
					}
				}
		}

		return $sRet;
	}

	/**
	 * Implemented for the HTML spreadsheet format!
	 *
	 * @param string $sItemCode
	 * @param \ormStopWatch $value
	 *
	 * @return false|string
	 */
	public function GetSubItemAsEditValue($sItemCode, $value)
	{
		$sRet = $value;

		switch ($sItemCode)
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
					$sRet = date((string)AttributeDateTime::GetFormat(), $value);
				}
				break;

			default:
				foreach($this->ListThresholds() as $iThreshold => $aFoo)
				{
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix)
					{
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode)
						{
							case 'deadline':
								if ($value)
								{
									$sRet = date((string)AttributeDateTime::GetFormat(), $value);
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

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		// A stopwatch always has a value
		return true;
	}

	public function RecordAttChange(DBObject $oObject, $original, $value): void
	{
		// Stop watches - record changes for sub items only (they are visible, the rest is not visible)
		//
		foreach ($this->ListSubItems() as $sSubItemAttCode => $oSubItemAttDef) {
			$item_value = $this->GetSubItemValue($oSubItemAttDef->Get('item_code'), $value, $oObject);
			$item_original = $this->GetSubItemValue($oSubItemAttDef->Get('item_code'), $original, $oObject);

			if ($item_value != $item_original) {
				$oMyChangeOp = MetaModel::NewObject(CMDBChangeOpSetAttributeScalar::class);
				$oMyChangeOp->Set("objclass", get_class($oObject));
				$oMyChangeOp->Set("objkey", $oObject->GetKey());
				$oMyChangeOp->Set("attcode", $sSubItemAttCode);

				$oMyChangeOp->Set("oldvalue", $item_original);
				$oMyChangeOp->Set("newvalue", $item_value);

				$oMyChangeOp->DBInsertNoReload();
			}
		}
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
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	/**
     * Return the search widget type corresponding to this attribute
     * the computation is made by AttributeStopWatch::GetSubItemSearchType
     *
     * @return string
     */
    public function GetSearchType()
    {
        /** @var AttributeStopWatch $oParent */
        $oParent = $this->GetTargetAttDef();

        return $oParent->GetSubItemSearchType($this->Get('item_code'));
    }

    public function GetAllowedValues($aArgs = array(), $sContains = '')
    {
        /** @var AttributeStopWatch $oParent */
        $oParent = $this->GetTargetAttDef();

        return $oParent->GetSubItemAllowedValues($this->Get('item_code'), $aArgs, $sContains);
    }

    public function IsNullAllowed()
    {
        /** @var AttributeStopWatch $oParent */
        $oParent = $this->GetTargetAttDef();

        $bDefaultValue = parent::IsNullAllowed();

        return $oParent->IsSubItemNullAllowed($this->Get('item_code'), $bDefaultValue);
    }

    public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('target_attcode', 'item_code'));
	}

	public function GetParentAttCode()
	{
		return $this->Get("target_attcode");
	}

	/**
	 * Helper : get the attribute definition to which the execution will be forwarded
	 */
	public function GetTargetAttDef()
	{
		$sClass = $this->GetHostClass();
		$oParentAttDef = MetaModel::GetAttributeDef($sClass, $this->Get('target_attcode'));

		return $oParentAttDef;
	}

	public function GetEditClass()
	{
		return "";
	}

	public function GetValuesDef()
	{
		return null;
	}

	public static function IsBasedOnDBColumns()
	{
		return true;
	}

	public static function IsScalar()
	{
		return true;
	}

	public function IsWritable()
	{
		return false;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return null;
	}

//	public function IsNullAllowed() {return false;}

	public static function LoadInObject()
	{
		return false;
	} // if this verb returns false, then GetValues must be implemented

	/**
	 * Used by DBOBject::Get()
	 *
	 * @param \DBObject $oHostObject
	 *
	 * @return \AttributeSubItem
	 * @throws \CoreException
	 */
	public function GetValue($oHostObject)
	{
		/** @var \AttributeStopWatch $oParent */
		$oParent = $this->GetTargetAttDef();
		$parentValue = $oHostObject->GetStrict($oParent->GetCode());
		$res = $oParent->GetSubItemValue($this->Get('item_code'), $parentValue, $oHostObject);

		return $res;
	}

	//
//	protected function ScalarToSQL($value) {return $value;} // format value as a valuable SQL literal (quoted outside)

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		return array();
	}

	public function GetFilterDefinitions()
	{
		return array($this->GetCode() => $this->GetCode());
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

	public function GetAsPlainText($value, $oHostObject = null, $bLocalize = true)
	{
		$oParent = $this->GetTargetAttDef();
		$res = $oParent->GetSubItemAsPlainText($this->Get('item_code'), $value);

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

	public function GetAsCSV(
		$value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		$oParent = $this->GetTargetAttDef();
		$res = $oParent->GetSubItemAsCSV($this->Get('item_code'), $value, $sSeparator, $sTextQualifier,
			$bConvertToPlainText);

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

	public function IsPartOfFingerprint()
	{
		return false;
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\LabelField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		parent::MakeFormField($oObject, $oFormField);

		// Note : As of today, this attribute is -by nature- only supported in readonly mode, not edition
		$sAttCode = $this->GetCode();
		$oFormField->SetCurrentValue(html_entity_decode($oObject->GetAsHTML($sAttCode), ENT_QUOTES, 'UTF-8'));
		$oFormField->SetReadOnly(true);

		return $oFormField;
	}

}

/**
 * One way encrypted (hashed) password
 */
class AttributeOneWayPassword extends AttributeDefinition implements iAttributeNoGroupBy
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("depends_on"));
	}

	public function GetEditClass()
	{
		return "One Way Password";
	}

	public static function IsBasedOnDBColumns()
	{
		return true;
	}

	public static function IsScalar()
	{
		return true;
	}

	public function IsWritable()
	{
		return true;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return "";
	}

	public function IsNullAllowed()
	{
		return $this->GetOptional("is_null_allowed", false);
	}

	// Facilitate things: allow the user to Set the value from a string or from an ormPassword (already encrypted)
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		$oPassword = $proposedValue;
		if (is_object($oPassword))
		{
			$oPassword = clone $proposedValue;
		}
		else
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
			$sPrefix = $this->GetCode(); // Warning: AttributeOneWayPassword does not have any sql property so code = sql !
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
		//	 As per mySQL doc, selecting blob columns will prevent mySQL from
		//	 using memory in case a temporary table has to be created
		//	 (temporary tables created on disk)
		//	 We will have to remove the blobs from the list of attributes when doing the select
		//	 then the use of Get() should finalize the load
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

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->GetCode().'_hash'] = 'TINYBLOB';
		$aColumns[$this->GetCode().'_salt'] = 'TINYBLOB';

		return $aColumns;
	}

	public function GetImportColumns()
	{
		$aColumns = array();
		$aColumns[$this->GetCode()] = 'TINYTEXT'.CMDBSource::GetSqlStringColumnDefinition();

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

		return '';
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		return ''; // Not exportable in CSV
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return ''; // Not exportable in XML
	}

	public function GetValueLabel($sValue, $oHostObj = null)
	{
		// Don't display anything in "group by" reports
		return '*****';
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		// Protection against wrong value type
		if (false === ($proposedValue instanceof ormPassword)) {
			// On object creation, the attribute value is "" instead of an ormPassword...
			if (is_string($proposedValue)) {
				return utils::IsNotNullOrEmptyString($proposedValue);
			}

			return parent::HasAValue($proposedValue);
		}

		return $proposedValue->IsEmpty() === false;
	}

	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		if (is_null($original)) {
			$original = '';
		}
		$oMyChangeOp->Set("prev_pwd", $original);
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeOneWayPassword::class;
	}
}

// Indexed array having two dimensions
class AttributeTable extends AttributeDBField
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetEditClass()
	{
		return "Table";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "LONGTEXT".CMDBSource::GetSqlStringColumnDefinition();
	}

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

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		return count($proposedValue) > 0;
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
		else
		{
			if (!is_array($proposedValue))
			{
				return array(0 => array(0 => $proposedValue));
			}
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
				$value = @json_decode($aCols[$sPrefix.''], true);
				if (is_null($value))
				{
					$value = false;
				}
			}
			if ($value === false)
			{
				$value = $this->MakeRealValue($aCols[$sPrefix.''], null);
			}
		} catch (Exception $e)
		{
			$value = $this->MakeRealValue($aCols[$sPrefix.''], null);
		}

		return $value;
	}

	public function GetSQLValues($value)
	{
		$aValues = array();
		try
		{
			$sSerializedValue = serialize($value);
		}
		catch (Exception $e)
		{
			$sSerializedValue = json_encode($value);
		}
		$aValues[$this->Get("sql")] = $sSerializedValue;

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
			foreach($aRawData as $iCol => $cell)
			{
				// Note: avoid the warning in case the cell is made of an array
				$sCell = @Str::pure2html((string)$cell);
				$sCell = str_replace("\n", "<br>\n", $sCell);
				$sRes .= "<TD>$sCell</TD>";
			}
			$sRes .= "</TR>";
		}
		$sRes .= "</TBODY>";
		$sRes .= "</TABLE>";

		return $sRes;
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		// Not implemented
		return '';
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (!is_array($value) || count($value) == 0)
		{
			return "";
		}

		$sRes = "";
		foreach($value as $iRow => $aRawData)
		{
			$sRes .= "<row>";
			foreach($aRawData as $iCol => $cell)
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
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetEditClass()
	{
		return "PropertySet";
	}

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
			$sCell = str_replace("\n", "<br>\n", Str::pure2html(@(string)$sValue));
			$sRes .= "<TD class=\"label\">$sProperty</TD><TD>$sCell</TD>";
			$sRes .= "</TR>";
		}
		$sRes .= "</TBODY>";
		$sRes .= "</TABLE>";

		return $sRes;
	}

	public function GetAsCSV(
		$value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		if (!is_array($value) || count($value) == 0)
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
		if (!is_array($value) || count($value) == 0)
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
 * An unordered multi values attribute
 * Allowed values are mandatory for this attribute to be modified
 *
 * Class AttributeSet
 */
abstract class AttributeSet extends AttributeDBFieldVoid
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;
	const EDITABLE_INPUT_ID_SUFFIX = '-setwidget-values'; // used client side, see js/jquery.itop-set-widget.js
	protected $bDisplayLink; // Display search link in readonly mode

	public function __construct($sCode, array $aParams)
	{
		parent::__construct($sCode, $aParams);
		$this->aCSSClasses[] = 'attribute-set';
		$this->bDisplayLink = true;
	}

	/**
	 * @param bool $bDisplayLink
	 */
	public function setDisplayLink($bDisplayLink)
	{
		$this->bDisplayLink = $bDisplayLink;
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('is_null_allowed', 'max_items'));
	}

	/**
	 * Allowed different values for the set values are mandatory for this attribute to be modified
	 *
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return array|null
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function GetPossibleValues($aArgs = array(), $sContains = '')
	{
		return $this->GetAllowedValues($aArgs, $sContains);
	}

	/**
	 * @param \ormSet $oValue
	 *
	 * @param $aArgs
	 *
	 * @return string JSON to be used in the itop.set_widget JQuery widget
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function GetJsonForWidget($oValue, $aArgs = array())
	{
		$aJson = array();

		// possible_values
		$aAllowedValues = $this->GetPossibleValues($aArgs);
		$aSetKeyValData = array();
		foreach($aAllowedValues as $sCode => $sLabel)
		{
			$aSetKeyValData[] = [
				'code' => $sCode,
				'label' => $sLabel,
			];
		}
		$aJson['possible_values'] = $aSetKeyValData;
		$aRemoved = array();
		if (is_null($oValue))
		{
			$aJson['partial_values'] = array();
			$aJson['orig_value'] = array();
		}
		else
		{
			$aPartialValues = $oValue->GetModified();
			foreach ($aPartialValues as $key => $value)
			{
				if (!isset($aAllowedValues[$value]))
				{
					unset($aPartialValues[$key]);
				}
			}
			$aJson['partial_values'] = array_values($aPartialValues);
			$aOrigValues = array_merge($oValue->GetValues(), $oValue->GetModified());
			foreach ($aOrigValues as $key => $value)
			{
				if (!isset($aAllowedValues[$value]))
				{
					// Remove unwanted values
					$aRemoved[] = $value;
					unset($aOrigValues[$key]);
				}
			}
			$aJson['orig_value'] = array_values($aOrigValues);
		}
		$aJson['added'] = array();
		$aJson['removed'] = $aRemoved;

		$iMaxTags = $this->GetMaxItems();
		$aJson['max_items_allowed'] = $iMaxTags;

		return json_encode($aJson);
	}

	public function RequiresIndex()
	{
		return true;
	}

	public function RequiresFullTextIndex()
	{
		return true;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return null;
	}

	public function IsNullAllowed()
	{
		return $this->Get("is_null_allowed");
	}

	public function GetEditClass()
	{
		return "Set";
	}

	public function GetEditValue($value, $oHostObj = null)
	{
		if (is_string($value))
		{
			return $value;
		}
		if ($value instanceof ormSet)
		{
			$value = $value->GetValues();
		}
		if (is_array($value))
		{
			return implode(', ', $value);
		}
		return '';
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		$iLen = $this->GetMaxSize();
		return "VARCHAR($iLen)"
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	public function GetMaxSize()
	{
		return 255;
	}

	public function FromStringToArray($proposedValue, $sDefaultSepItem = ',')
	{
		$aValues = array();
		if (!empty($proposedValue))
		{
			$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
			// convert also , separated strings
			if ($sSepItem !== $sDefaultSepItem)
			{
				$proposedValue = str_replace($sDefaultSepItem, $sSepItem, $proposedValue);
			}
			foreach(explode($sSepItem, $proposedValue) as $sCode)
			{
				$sValue = trim($sCode);
				if ($sValue !== '')
				{
					$aValues[] = $sValue;
				}
			}
		}
		return $aValues;
	}

	/**
	 * @param array $aCols
	 * @param string $sPrefix
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$sValue = $aCols["$sPrefix"];

		return $this->MakeRealValue($sValue, null, true);
	}

	/**
	 * force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!
	 *
	 * @param $proposedValue
	 * @param \DBObject $oHostObj
	 *
	 * @param bool $bIgnoreErrors
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function MakeRealValue($proposedValue, $oHostObj, $bIgnoreErrors = false)
	{
		$oSet = new ormSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		$aAllowedValues = $this->GetPossibleValues();
		if (is_string($proposedValue) && !empty($proposedValue))
		{
			$proposedValue = trim("$proposedValue");
			$aValues = $this->FromStringToArray($proposedValue);
			foreach ($aValues as $i => $sValue)
			{
				if (!isset($aAllowedValues[$sValue]))
				{
					unset($aValues[$i]);
				}
			}
			$oSet->SetValues($aValues);
		}
		elseif ($proposedValue instanceof ormSet)
		{
			$oSet = $proposedValue;
		}

		return $oSet;
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
	 * @throws \Exception
	 */
	public function MakeValueFromString($sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null, $sAttributeQualifier = null)
	{
		return $this->MakeRealValue($sProposedValue, null);
	}

	/**
	 * @return null|\ormSet
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function GetNullValue()
	{
		return new ormSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
	}

	public function IsNull($proposedValue)
	{
		if (empty($proposedValue))
		{
			return true;
		}

		/** @var \ormSet $proposedValue */
		return $proposedValue->Count() == 0;
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		if (false === ($proposedValue instanceof ormSet)) {
			return parent::HasAValue($proposedValue);
		}

		return $proposedValue->Count() > 0;
	}

	/**
	 * To be overloaded for localized enums
	 *
	 * @param $sValue
	 *
	 * @return string label corresponding to the given value (in plain text)
	 * @throws \Exception
	 */
	public function GetValueLabel($sValue)
	{
		if ($sValue instanceof ormSet)
		{
			$sValue = $sValue->GetValues();
		}
		if (is_array($sValue))
		{
			return implode(', ', $sValue);
		}
		return $sValue;
	}

	/**
	 * @param string $sValue
	 * @param null $oHostObj
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetAsPlainText($sValue, $oHostObj = null)
	{
		return $this->GetValueLabel($sValue);
	}

	/**
	 * @param string $value
	 *
	 * @return string
	 */
	public function ScalarToSQL($value)
	{
		if (empty($value))
		{
			return '';
		}
		if ($value instanceof ormSet)
		{
			$value = $value->GetValues();
		}
		if (is_array($value))
		{
			$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
			$sRes = implode($sSepItem, $value);
			if (!empty($sRes))
			{
				$value = "{$sSepItem}{$sRes}{$sSepItem}";
			}
			else
			{
				$value = '';
			}
		}
		return $value;
	}

	/**
	 * @param $value
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string|null
	 *
	 * @throws \Exception
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if ($value instanceof ormSet)
		{
			$aValues = $value->GetValues();
			return $this->GenerateViewHtmlForValues($aValues);
		}
		if (is_array($value))
		{
			return implode(', ', $value);
		}
		return $value;
	}

	/**
	 * HTML representation of a list of values (read-only)
	 * accept a list of strings
	 *
	 * @param array $aValues
	 * @param string $sCssClass
	 * @param bool $bWithLink if true will generate a link, otherwise just a "a" tag without href
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function GenerateViewHtmlForValues($aValues, $sCssClass = '', $bWithLink = true)
	{
		if (empty($aValues)) {return '';}
		$sHtml = '<span class="'.$sCssClass.' '.implode(' ', $this->aCSSClasses).'">';
		foreach($aValues as $sValue) {
			$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode());
			$sAttCode = $this->GetCode();
			$sLabel = utils::EscapeHtml($this->GetValueLabel($sValue));
			$sDescription = utils::EscapeHtml($this->GetValueDescription($sValue));
			$oFilter = DBSearch::FromOQL("SELECT $sClass WHERE $sAttCode MATCHES '$sValue'");
			$oAppContext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink();
			$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($oFilter->GetClass());
			$sFilter = rawurlencode($oFilter->serialize());
			$sLink = '';
			if ($bWithLink && $this->bDisplayLink) {
				$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/$sUIPage?operation=search&filter=".$sFilter."&{$sContext}";
				$sLink = ' href="'.$sUrl.'"';
			}

			// Prepare tooltip
			if (empty($sDescription)) {
				$sTooltipContent = $sLabel;
				$sTooltipHtmlEnabled = 'false';
			} else {
				$sTooltipContent = <<<HTML
<h4>$sLabel</h4>
<div>$sDescription</div>
HTML;
				$sTooltipHtmlEnabled = 'true';
			}
			$sTooltipContent = utils::EscapeHtml($sTooltipContent);

			$sHtml .= '<a'.$sLink.' class="attribute-set-item attribute-set-item-'.$sValue.'" data-code="'.$sValue.'" data-label="'.$sLabel.'" data-description="'.$sDescription.'" data-tooltip-content="'.$sTooltipContent.'" data-tooltip-html-enabled="'.$sTooltipHtmlEnabled.'">'.$sLabel.'</a>';
		}
		$sHtml .= '</span>';

		return $sHtml;
	}

	/**
	 * @param $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return mixed|string
	 */
	public function GetAsCSV($value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true, $bConvertToPlainText = false)
	{
		$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
		if (is_object($value) && ($value instanceof ormSet))
		{
			if ($bLocalize)
			{
				$aValues = $value->GetLabels();
			}
			else
			{
				$aValues = $value->GetValues();
			}
			$sRes = implode($sSepItem, $aValues);
		}
		else
		{
			$sRes = '';
		}

		return "{$sTextQualifier}{$sRes}{$sTextQualifier}";
	}

	public function GetMaxItems()
	{
		return $this->Get('max_items');
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\SetField';
	}

	public function RecordAttChange(DBObject $oObject, $original, $value): void
	{
		/** @var \ormSet $original */
		/** @var \ormSet $value */
		parent::RecordAttChange($oObject,
			implode(' ', $original->GetValues()),
			implode(' ', $value->GetValues())
		);
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeTagSet::class;
	}
}

/**
 * @since 2.7.0 N°985
 */
class AttributeEnumSet extends AttributeSet
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_TAG_SET;
	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('possible_values', 'is_null_allowed', 'max_items'));
	}

	public function GetMaxSize()
	{
		$aRawValues = $this->GetRawPossibleValues();
		$iMaxItems = $this->GetMaxItems();
		$aLengths = array();
		foreach (array_keys($aRawValues) as $sKey)
		{
			$aLengths[] = strlen($sKey);
		}
		rsort($aLengths, SORT_NUMERIC);
		$iMaxSize = 2;
		for ($i = 0; $i < min($iMaxItems, count($aLengths)); $i++)
		{
			$iMaxSize += $aLengths[$i] + 1;
		}
		return max(255, $iMaxSize);
	}

	private function GetRawPossibleValues($aArgs = array(), $sContains = '')
	{
		/** @var ValueSetEnumPadded $oValSetDef */
		$oValSetDef = $this->Get('possible_values');
		if (!$oValSetDef)
		{
			return array();
		}

		return $oValSetDef->GetValues($aArgs, $sContains);
	}

	public function GetPossibleValues($aArgs = array(), $sContains = '')
	{
		$aRawValues = $this->GetRawPossibleValues($aArgs, $sContains);
		$aLocalizedValues = array();
		foreach($aRawValues as $sKey => $sValue)
		{
			$aLocalizedValues[$sKey] = $this->GetValueLabel($sKey);
		}

		return $aLocalizedValues;
	}

	public function GetValueLabel($sValue)
	{
		if ($sValue instanceof ormSet)
		{
			$sValue = implode(', ', $sValue->GetValues());
		}

		$aValues = $this->GetRawPossibleValues();
		if (is_array($aValues) && is_string($sValue) && isset($aValues[$sValue]))
		{
			$sValue = $aValues[$sValue];
		}

		if (is_null($sValue))
		{
			// Unless a specific label is defined for the null value of this enum, use a generic "undefined" label
			$sLabel = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue,
				Dict::S('Enum:Undefined'));
		}
		else
		{
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, null, true /*user lang*/);
			if (is_null($sLabel))
			{
				// Browse the hierarchy again, accepting default (english) translations
				$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, null, false);
				if (is_null($sLabel))
				{
					$sDefault = trim(str_replace('_', ' ', $sValue));
					// Browse the hierarchy again, accepting default (english) translations
					$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sDefault, $sDefault, false);
				}
			}
		}

		return $sLabel;
	}

	public function GetValueDescription($sValue)
	{
		if (is_null($sValue))
		{
			// Unless a specific label is defined for the null value of this enum, use a generic "undefined" label
			$sDescription = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+',
				Dict::S('Enum:Undefined'));
		}
		else
		{
			$sDescription = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+',
				'', true /* user language only */);
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

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if ($bLocalize)
		{
			if ($value instanceof ormSet)
			{
				$sRes = $this->GenerateViewHtmlForValues($value->GetValues());
			}
			else
			{
				$sLabel = $this->GetValueLabel($value);
				$sDescription = $this->GetValueDescription($value);
				$sRes = "<span title=\"$sDescription\">".parent::GetAsHtml($sLabel)."</span>";
			}
		}
		else
		{
			$sRes = parent::GetAsHtml($value, $oHostObject, $bLocalize);
		}

		return $sRes;
	}


	/**
	 * @param ormSet $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return mixed|string
	 * @throws \Exception
	 */
	public function GetAsCSV($value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true, $bConvertToPlainText = false)
	{
		$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
		if (is_object($value) && ($value instanceof ormSet))
		{
			$aValues = $value->GetValues();
			if ($bLocalize)
			{
				$aLocalizedValues = array();
				foreach($aValues as $sValue)
				{
					$aLocalizedValues[] = $this->GetValueLabel($sValue);
				}
				$aValues = $aLocalizedValues;
			}
			$sRes = implode($sSepItem, $aValues);
		}
		else
		{
			$sRes = '';
		}

		return "{$sTextQualifier}{$sRes}{$sTextQualifier}";
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
	 * @throws \Exception
	 */
	public function MakeValueFromString($sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null, $sAttributeQualifier = null)
	{
		if ($bLocalizedValue)
		{
			// Lookup for the values matching the input
			//
			$aValues = $this->FromStringToArray($sProposedValue);
			$aFoundValues = array();
			$aRawValues = $this->GetPossibleValues();
			foreach ($aValues as $sValue)
			{
				$bFound = false;
				foreach ($aRawValues as $sCode => $sRawValue)
				{
					if ($sValue == $sRawValue)
					{
						$aFoundValues[] = $sCode;
						$bFound = true;
						break;
					}
				}
				if (!$bFound)
				{
					// Not found, break the import
					return null;
				}
			}

			return $this->MakeRealValue(implode(',', $aFoundValues), null);
		}
		else
		{
			return $this->MakeRealValue($sProposedValue, null, false);
		}
	}

	/**
	 * @param string $proposedValue Search string used for MATCHES
	 *
	 * @param string $sDefaultSepItem word separator to extract items
	 *
	 * @return array of EnumSet codes
	 * @throws \Exception
	 */
	public function FromStringToArray($proposedValue, $sDefaultSepItem = ',')
	{
		$aValues = array();
		if (!empty($proposedValue))
		{
			$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
			// convert also other separators
			if ($sSepItem !== $sDefaultSepItem)
			{
				$proposedValue = str_replace($sDefaultSepItem, $sSepItem, $proposedValue);
			}
			foreach(explode($sSepItem, $proposedValue) as $sCode)
			{
				$sValue = trim($sCode);
				if (strlen($sValue) > 2)
				{
					$sLabel = $this->GetValueLabel($sValue);
					$aValues[$sLabel] = $sValue;
				}
			}
		}
		return $aValues;
	}
	public function Equals($val1, $val2)
	{
		return $val1->Equals($val2);
	}
}


class AttributeClassAttCodeSet extends AttributeSet
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	const DEFAULT_PARAM_INCLUDE_CHILD_CLASSES_ATTRIBUTES = false;

	public function __construct($sCode, array $aParams)
	{
		parent::__construct($sCode, $aParams);
		$this->aCSSClasses[] = 'attribute-class-attcode-set';
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('class_field', 'attribute_definition_list', 'attribute_definition_exclusion_list'));
	}

	public function GetMaxSize()
	{
		return max(255, 15 * $this->GetMaxItems());
	}

	/**
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return array|null
	 * @throws \CoreException
	 */
	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		if (!isset($aArgs['this']))
		{
			return null;
		}

		$oHostObj = $aArgs['this'];
		$sTargetClass = $this->Get('class_field');
		$sRootClass = $oHostObj->Get($sTargetClass);
		$bIncludeChildClasses = $this->GetOptional('include_child_classes_attributes', static::DEFAULT_PARAM_INCLUDE_CHILD_CLASSES_ATTRIBUTES);

		$aExcludeDefs = array();
		$sAttDefExclusionList = $this->Get('attribute_definition_exclusion_list');
		if (!empty($sAttDefExclusionList))
		{
			foreach(explode(',', $sAttDefExclusionList) as $sAttDefName)
			{
				$sAttDefName = trim($sAttDefName);
				$aExcludeDefs[$sAttDefName] = $sAttDefName;
			}
		}

		$aAllowedDefs = array();
		$sAttDefList = $this->Get('attribute_definition_list');
		if (!empty($sAttDefList))
		{
			foreach(explode(',', $sAttDefList) as $sAttDefName)
			{
				$sAttDefName = trim($sAttDefName);
				$aAllowedDefs[$sAttDefName] = $sAttDefName;
			}
		}

		$aAllAttributes = array();
		if (!empty($sRootClass))
		{
			$aClasses = array($sRootClass);
			if($bIncludeChildClasses === true)
			{
				$aClasses = $aClasses + MetaModel::EnumChildClasses($sRootClass, ENUM_CHILD_CLASSES_EXCLUDETOP);
			}

			foreach($aClasses as $sClass)
			{
				foreach(MetaModel::GetAttributesList($sClass) as $sAttCode)
				{
					// Add attribute only if not already there (can be in leaf classes but not the root)
					if(!array_key_exists($sAttCode, $aAllAttributes))
					{
						$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
						$sAttDefClass = get_class($oAttDef);

						// Skip excluded attdefs
						if(isset($aExcludeDefs[$sAttDefClass]))
						{
							continue;
						}
						// Skip not allowed attdefs only if list specified
						if(!empty($aAllowedDefs) && !isset($aAllowedDefs[$sAttDefClass]))
						{
							continue;
						}

						$aAllAttributes[$sAttCode] = array(
							'classes' => array($sClass),
						);
					}
					else
					{
						$aAllAttributes[$sAttCode]['classes'][] = $sClass;
					}
				}
			}
		}

		$aAllowedAttributes = array();
		foreach($aAllAttributes as $sAttCode => $aAttData)
		{
			$iAttClassesCount = count($aAttData['classes']);
			$sAttFirstClass = $aAttData['classes'][0];
			$sAttLabel = MetaModel::GetLabel($sAttFirstClass, $sAttCode);

			if($sAttFirstClass === $sRootClass)
			{
				$sLabel = Dict::Format('Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromClass', $sAttCode, $sAttLabel);
			}
			elseif($iAttClassesCount === 1)
			{
				$sLabel = Dict::Format('Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromOneChildClass', $sAttCode, $sAttLabel, MetaModel::GetName($sAttFirstClass));
			}
			else
			{
				$sLabel = Dict::Format('Core:AttributeClassAttCodeSet:ItemLabel:AttributeFromSeveralChildClasses', $sAttCode, $sAttLabel);
			}
			$aAllowedAttributes[$sAttCode] = $sLabel;
		}
		// N°6460 Always sort on the labels, not on the datamodel definition order
		natcasesort($aAllowedAttributes);

		return $aAllowedAttributes;
	}

	/**
	 * force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!
	 *
	 * @param $proposedValue
	 * @param \DBObject $oHostObj
	 *
	 * @param bool $bIgnoreErrors
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 */
	public function MakeRealValue($proposedValue, $oHostObj, $bIgnoreErrors = false)
	{
		$oSet = new ormSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		$aArgs = array();
		if (!empty($oHostObj))
		{
			$aArgs['this'] = $oHostObj;
		}
		$aAllowedAttributes = $this->GetAllowedValues($aArgs);
		$aInvalidAttCodes = array();
		if (is_string($proposedValue) && !empty($proposedValue))
		{
			$aJsonFromWidget = json_decode($proposedValue, true);
			if (is_null($aJsonFromWidget))
			{
				$proposedValue = trim($proposedValue);
				$aProposedValues = $this->FromStringToArray($proposedValue);
				$aValues = array();
				foreach($aProposedValues as $sValue)
				{
					$sAttCode = trim($sValue);
					if (empty($aAllowedAttributes) || isset($aAllowedAttributes[$sAttCode]))
					{
						$aValues[$sAttCode] = $sAttCode;
					}
					else
					{
						$aInvalidAttCodes[] = $sAttCode;
					}
				}
				$oSet->SetValues($aValues);
			}
		}
		elseif ($proposedValue instanceof ormSet)
		{
			$oSet = $proposedValue;
		}
		if (!empty($aInvalidAttCodes) && !$bIgnoreErrors)
		{
			$sTargetClass = $this->Get('class_field');
			$sClass = $oHostObj->Get($sTargetClass);
			throw new CoreUnexpectedValue("The attribute(s) ".implode(', ', $aInvalidAttCodes)." are invalid for class {$sClass}");
		}

		return $oSet;
	}

	/**
	 * @param $value
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string|null
	 *
	 * @throws \Exception
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if ($value instanceof ormSet)
		{
			$value = $value->GetValues();
		}
		if (is_array($value))
		{
			if (!empty($oHostObject) && $bLocalize)
			{
				$sTargetClass = $this->Get('class_field');
				$sClass = $oHostObject->Get($sTargetClass);

				$aLocalizedValues = array();
				foreach($value as $sAttCode)
				{
					try
					{
						$sAttClass = $sClass;

						// Look for the first class (current or children) that have this attcode
						foreach (MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL) as $sChildClass) {
							if (MetaModel::IsValidAttCode($sChildClass, $sAttCode)) {
								$sAttClass = $sChildClass;
								break;
							}
						}

						$sLabelForHtmlAttribute = utils::HtmlEntities(MetaModel::GetLabel($sAttClass, $sAttCode)." ($sAttCode)");
						$aLocalizedValues[] = '<span class="attribute-set-item" data-code="'.$sAttCode.'" data-label="'.$sLabelForHtmlAttribute.'" data-description="" data-tooltip-content="'.$sLabelForHtmlAttribute.'">'.$sAttCode.'</span>';
					} catch (Exception $e)
					{
						// Ignore bad values
					}
				}
				$value = $aLocalizedValues;
			}
			$value = implode('', $value);
		}
		return '<span class="'.implode(' ', $this->aCSSClasses).'">'.$value.'</span>';
	}

	public function IsNull($proposedValue)
	{
		return (empty($proposedValue));
	}
}

class AttributeQueryAttCodeSet extends AttributeSet
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

	public function __construct($sCode, array $aParams)
	{
		parent::__construct($sCode, $aParams);
		$this->aCSSClasses[] = 'attribute-query-attcode-set';
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('query_field'));
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "TEXT".CMDBSource::GetSqlStringColumnDefinition();
	}

	public function GetMaxSize()
	{
		return 65535;
	}

	/**
	 * Get a class array indexed by alias
	 * @param $oHostObj
	 *
	 * @return array
	 */
	private function GetClassList($oHostObj)
	{
		try
		{
			$sQueryField = $this->Get('query_field');
			$sQuery = $oHostObj->Get($sQueryField);
			if (empty($sQuery))
			{
				return array();
			}
			$oFilter = DBSearch::FromOQL($sQuery);
			return $oFilter->GetSelectedClasses();

		} catch (OQLException $e)
		{
			IssueLog::Warning($e->getMessage());
		}
		return array();
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		if (isset($aArgs['this']))
		{
			$oHostObj = $aArgs['this'];
			$aClasses = $this->GetClassList($oHostObj);

			$aAllowedAttributes = array();
			$aAllAttributes = array();

			if ((count($aClasses) == 1) && (array_keys($aClasses)[0] == array_values($aClasses)[0]))
			{
				$sClass = reset($aClasses);
				$aAttributes = MetaModel::GetAttributesList($sClass);
				foreach($aAttributes as $sAttCode)
				{
					$aAllowedAttributes[$sAttCode] = "$sAttCode (".MetaModel::GetLabel($sClass, $sAttCode).')';
				}
			}
			else
			{
				if (!empty($aClasses))
				{
					ksort($aClasses);
					foreach($aClasses as $sAlias => $sClass)
					{
						$aAttributes = MetaModel::GetAttributesList($sClass);
						foreach($aAttributes as $sAttCode)
						{
							$aAllAttributes[] = array('alias' => $sAlias, 'class' => $sClass, 'att_code' => $sAttCode);
						}
					}
				}
				foreach($aAllAttributes as $aFullAttCode)
				{
					$sAttCode = $aFullAttCode['alias'].'.'.$aFullAttCode['att_code'];
					$sClass = $aFullAttCode['class'];
					$sLabel = "$sAttCode (".MetaModel::GetLabel($sClass, $aFullAttCode['att_code']).')';
					$aAllowedAttributes[$sAttCode] = $sLabel;
				}
			}
			return $aAllowedAttributes;
		}

		return null;
	}

	/**
	 * force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!
	 *
	 * @param $proposedValue
	 * @param \DBObject $oHostObj
	 *
	 * @param bool $bIgnoreErrors
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \OQLException
	 * @throws \Exception
	 */
	public function MakeRealValue($proposedValue, $oHostObj, $bIgnoreErrors = false)
	{
		$oSet = new ormSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		$aArgs = array();
		if (!empty($oHostObj))
		{
			$aArgs['this'] = $oHostObj;
		}
		$aAllowedAttributes = $this->GetAllowedValues($aArgs);
		$aInvalidAttCodes = array();
		if (is_string($proposedValue) && !empty($proposedValue))
		{
			$proposedValue = trim($proposedValue);
			$aProposedValues = $this->FromStringToArray($proposedValue);
			$aValues = array();
			foreach($aProposedValues as $sValue)
			{
				$sAttCode = trim($sValue);
				if (empty($aAllowedAttributes) || isset($aAllowedAttributes[$sAttCode]))
				{
					$aValues[$sAttCode] = $sAttCode;
				}
				else
				{
					$aInvalidAttCodes[] = $sAttCode;
				}
			}
			$oSet->SetValues($aValues);
		}
		elseif ($proposedValue instanceof ormSet)
		{
			$oSet = $proposedValue;
		}
		if (!empty($aInvalidAttCodes) && !$bIgnoreErrors)
		{
			throw new CoreUnexpectedValue("The attribute(s) ".implode(', ', $aInvalidAttCodes)." are invalid");
		}

		return $oSet;
	}

	/**
	 * @param $value
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string|null
	 *
	 * @throws \Exception
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{

		if ($value instanceof ormSet)
		{
			$value = $value->GetValues();
		}
		if (is_array($value))
		{
			if (!empty($oHostObject) && $bLocalize) {
				$aArgs['this'] = $oHostObject;
				$aAllowedAttributes = $this->GetAllowedValues($aArgs);

				$aLocalizedValues = array();
				foreach ($value as $sAttCode) {
					if (isset($aAllowedAttributes[$sAttCode])) {
						$sLabelForHtmlAttribute = utils::HtmlEntities($aAllowedAttributes[$sAttCode]);
						$aLocalizedValues[] = '<span class="attribute-set-item" data-code="'.$sAttCode.'" data-label="'.$sLabelForHtmlAttribute.'" data-description="" data-tooltip-content="'.$sLabelForHtmlAttribute.'">'.$sAttCode.'</span>';
					}
				}
				$value = $aLocalizedValues;
			}
			$value = implode('', $value);
		}

		return '<span class="'.implode(' ', $this->aCSSClasses).'">'.$value.'</span>';
	}
}

/**
 * Multi value list of tags
 *
 * @see TagSetFieldData
 * @since 2.6.0 N°931 tag fields
 */
class AttributeTagSet extends AttributeSet
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_TAG_SET;

	public function __construct($sCode, array $aParams)
	{
		parent::__construct($sCode, $aParams);
		$this->aCSSClasses[] = 'attribute-tag-set';
	}

	public function GetEditClass()
	{
		return 'TagSet';
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('tag_code_max_len'));
	}

	/**
	 * @param \ormTagSet $oValue
	 *
	 * @param $aArgs
	 *
	 * @return string JSON to be used in the itop.tagset_widget JQuery widget
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function GetJsonForWidget($oValue, $aArgs = array())
	{
		$aJson = array();

		// possible_values
		$aTagSetObjectData = $this->GetAllowedValues($aArgs);
		$aTagSetKeyValData = array();
		foreach($aTagSetObjectData as $sTagCode => $sTagLabel)
		{
			$aTagSetKeyValData[] = [
				'code' => $sTagCode,
				'label' => $sTagLabel,
			];
		}
		$aJson['possible_values'] = $aTagSetKeyValData;

		if (is_null($oValue))
		{
			$aJson['partial_values'] = array();
			$aJson['orig_value'] = array();
			$aJson['added'] = array();
			$aJson['removed'] = array();
		}
		else
		{
			$aJson['orig_value'] = array_merge($oValue->GetValues(), $oValue->GetModified());
			$aJson['added'] = $oValue->GetAdded();
			$aJson['removed'] = $oValue->GetRemoved();

			if ($oValue->DisplayPartial())
			{
				// For bulk updates
				$aJson['partial_values'] = $oValue->GetModified();
			}
			else
			{
				// For simple updates
				$aJson['partial_values'] = array();
			}
		}


		$iMaxTags = $this->GetMaxItems();
		$aJson['max_items_allowed'] = $iMaxTags;

		return json_encode($aJson);
	}

	public function FromStringToArray($proposedValue, $sDefaultSepItem = ',')
	{
		$aValues = array();
		if (!empty($proposedValue))
		{
			foreach(explode(' ', $proposedValue) as $sCode)
			{
				$sValue = trim($sCode);
				$aValues[] = $sValue;
			}
		}
		return $aValues;
	}

	/**
	 * Extract all existing tags from a string and ignore bad tags
	 *
	 * @param $sValue
	 * @param bool $bNoLimit : don't apply the maximum tag limit
	 *
	 * @return \ormTagSet
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function GetExistingTagsFromString($sValue, $bNoLimit = false)
	{
		$aTagCodes = $this->FromStringToArray("$sValue");
		$sAttCode = $this->GetCode();
		$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $sAttCode);
		if ($bNoLimit)
		{
			$oTagSet = new ormTagSet($sClass, $sAttCode, 0);
		}
		else
		{
			$oTagSet = new ormTagSet($sClass, $sAttCode, $this->GetMaxItems());
		}
		$aGoodTags = array();
		foreach($aTagCodes as $sTagCode)
		{
			if ($sTagCode === '')
			{
				continue;
			}
			if ($oTagSet->IsValidTag($sTagCode))
			{
				$aGoodTags[] = $sTagCode;
				if (!$bNoLimit && (count($aGoodTags) === $this->GetMaxItems()))
				{
					// extra and bad tags are ignored
					break;
				}
			}
		}
		$oTagSet->SetValues($aGoodTags);

		return $oTagSet;
	}

	public function GetTagCodeMaxLength()
	{
		return $this->Get('tag_code_max_len');
	}

	public function GetEditValue($value, $oHostObj = null)
	{
		if (empty($value))
		{
			return '';
		}
		if ($value instanceof ormTagSet)
		{
			$aValues = $value->GetValues();

			return implode(' ', $aValues);
		}

		return '';
	}

	public function GetMaxSize()
	{
		return max(255, ($this->GetMaxItems() * $this->GetTagCodeMaxLength()) + 1);
	}

	public function Equals($val1, $val2)
	{
		if (($val1 instanceof ormTagSet) && ($val2 instanceof ormTagSet))
		{
			return $val1->Equals($val2);
		}

		return ($val1 == $val2);
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$sAttCode = $this->GetCode();
		$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $sAttCode);
		$aAllowedTags = TagSetFieldData::GetAllowedValues($sClass, $sAttCode);
		$aAllowedValues = array();
		foreach($aAllowedTags as $oAllowedTag)
		{
			$aAllowedValues[$oAllowedTag->Get('code')] = $oAllowedTag->Get('label');
		}

		return $aAllowedValues;
	}

	/**
	 * @param array $aCols
	 * @param string $sPrefix
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$sValue = $aCols["$sPrefix"];

		return $this->GetExistingTagsFromString($sValue);
	}

	/**
	 * force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!
	 *
	 * @param $proposedValue
	 * @param $oHostObj
	 *
	 * @param bool $bIgnoreErrors
	 *
	 * @return mixed
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function MakeRealValue($proposedValue, $oHostObj, $bIgnoreErrors = false)
	{
		$oTagSet = new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		if (is_string($proposedValue) && !empty($proposedValue))
		{
			$sJsonFromWidget = json_decode($proposedValue, true);
			if (is_null($sJsonFromWidget))
			{
				$proposedValue = trim("$proposedValue");
				$aTagCodes = $this->FromStringToArray($proposedValue);
				$oTagSet->SetValues($aTagCodes);
			}
		}
		elseif ($proposedValue instanceof ormTagSet)
		{
			$oTagSet = $proposedValue;
		}

		return $oTagSet;
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
	 * @throws \Exception
	 */
	public function MakeValueFromString($sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null, $sAttributeQualifier = null)
	{
		if (is_null($sSepItem) || empty($sSepItem))
		{
			$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
		}
		if (!empty($sProposedValue))
		{
			$oTagSet = new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()),
				$this->GetCode(), $this->GetMaxItems());
			$aLabels = explode($sSepItem, $sProposedValue);
			$aCodes = array();
			foreach($aLabels as $sTagLabel)
			{
				if (!empty($sTagLabel))
				{
					$aCodes[] = ($bLocalizedValue) ? $oTagSet->GetTagFromLabel($sTagLabel) : $sTagLabel;
				}
			}
			$sProposedValue = implode(' ', $aCodes);
		}

		return $this->MakeRealValue($sProposedValue, null);
	}

	public function GetNullValue()
	{
		return new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		$oTagSet =  new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		$oTagSet->SetValues([]);
		return $oTagSet;
	}

	public function IsNull($proposedValue)
	{
		if (is_null($proposedValue))
		{
			return true;
		}

		/** @var \ormTagSet $proposedValue */
		return count($proposedValue->GetValues()) == 0;
	}

	/**
	 * To be overloaded for localized enums
	 *
	 * @param $sValue
	 *
	 * @return string label corresponding to the given value (in plain text)
	 * @throws \CoreWarning
	 * @throws \Exception
	 */
	public function GetValueLabel($sValue)
	{
		if (empty($sValue))
		{
			return '';
		}
		if (is_string($sValue))
		{
			$sValue = $this->GetExistingTagsFromString($sValue);
		}
		if ($sValue instanceof ormTagSet)
		{
			$aValues = $sValue->GetLabels();

			return implode(', ', $aValues);
		}
		throw new CoreWarning('Expected the attribute value to be a TagSet', array(
			'found_type' => gettype($sValue),
			'value' => $sValue,
			'class' => $this->GetHostClass(),
			'attribute' => $this->GetCode()
		));
	}

	/**
	 * @param $value
	 *
	 * @return string
	 * @throws \CoreWarning
	 */
	public function ScalarToSQL($value)
	{
		if (empty($value))
		{
			return '';
		}
		if ($value instanceof ormTagSet)
		{
			$aValues = $value->GetValues();

			return implode(' ', $aValues);
		}
		throw new CoreWarning('Expected the attribute value to be a TagSet', array(
			'found_type' => gettype($value),
			'value' => $value,
			'class' => $this->GetHostClass(),
			'attribute' => $this->GetCode()
		));
	}

	/**
	 * @param $value
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string|null
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if ($value instanceof ormTagSet)
		{
			if ($bLocalize)
			{
				$aValues = $value->GetTags();
			}
			else
			{
				$aValues = $value->GetValues();
			}
			if (empty($aValues))
			{
				return '';
			}

			return $this->GenerateViewHtmlForValues($aValues);
		}
		if (is_string($value))
		{
			try
			{
				$oValue = $this->MakeRealValue($value, $oHostObject);

				return $this->GetAsHTML($oValue, $oHostObject, $bLocalize);
			} catch (Exception $e)
			{
				// unknown tags are present display the code instead
			}
			$aTagCodes = $this->FromStringToArray($value);
			$aValues = array();
			$oTagSet = new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()),
				$this->GetCode(), $this->GetMaxItems());
			foreach($aTagCodes as $sTagCode)
			{
				try
				{
					$oTagSet->Add($sTagCode);
				} catch (Exception $e)
				{
					$aValues[] = $sTagCode;
				}
			}
			$sHTML = '';
			if (!empty($aValues))
			{
				$sHTML .= $this->GenerateViewHtmlForValues($aValues, 'attribute-set-item-undefined');
			}
			$aValues = $oTagSet->GetTags();
			if (!empty($aValues))
			{
				$sHTML .= $this->GenerateViewHtmlForValues($aValues);
			}

			return $sHTML;
		}

		return parent::GetAsHTML($value, $oHostObject, $bLocalize);
	}

	// Do not display friendly names in the history of change
	public function DescribeChangeAsHTML($sOldValue, $sNewValue, $sLabel = null)
	{
		$sResult = Dict::Format('Change:AttName_Changed', $this->GetLabel()).", ";

		$aNewValues = $this->FromStringToArray($sNewValue);
		$aOldValues = $this->FromStringToArray($sOldValue);

		$aDelta['removed'] = array_diff($aOldValues, $aNewValues);
		$aDelta['added'] = array_diff($aNewValues, $aOldValues);

		$aAllowedTags = TagSetFieldData::GetAllowedValues(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode());

		if (!empty($aDelta['removed']))
		{
			$aRemoved = array();
			foreach($aDelta['removed'] as $idx => $sTagCode)
			{
				if (empty($sTagCode)) {continue;}
				$sTagLabel = $sTagCode;
				foreach($aAllowedTags as $oTag)
				{
					if ($sTagCode === $oTag->Get('code'))
					{
						$sTagLabel = $oTag->Get('label');
					}
				}
				$aRemoved[] = $sTagLabel;
			}

			$sRemoved = $this->GenerateViewHtmlForValues($aRemoved, 'history-removed');
			if (!empty($sRemoved))
			{
				$sResult .= Dict::Format('Change:LinkSet:Removed', $sRemoved);
			}
		}

		if (!empty($aDelta['added']))
		{
			if (!empty($sRemoved))
			{
				$sResult .= ', ';
			}

			$aAdded = array();
			foreach($aDelta['added'] as $idx => $sTagCode)
			{
				if (empty($sTagCode)) {continue;}
				$sTagLabel = $sTagCode;
				foreach($aAllowedTags as $oTag)
				{
					if ($sTagCode === $oTag->Get('code'))
					{
						$sTagLabel = $oTag->Get('label');
					}
				}
				$aAdded[] = $sTagLabel;
			}

			$sAdded = $this->GenerateViewHtmlForValues($aAdded, 'history-added');
			if (!empty($sAdded))
			{
				$sResult .= Dict::Format('Change:LinkSet:Added', $sAdded);
			}
		}

		return $sResult;
	}

	/**
	 * HTML representation of a list of tags (read-only)
	 * accept a list of strings or a list of TagSetFieldData
	 *
	 * @param array $aValues
	 * @param string $sCssClass
	 * @param bool $bWithLink if true will generate a link, otherwise just a "a" tag without href
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function GenerateViewHtmlForValues($aValues, $sCssClass = '', $bWithLink = true)
	{
		if (empty($aValues)) {return '';}
		$sHtml = '<span class="'.$sCssClass.' '.implode(' ', $this->aCSSClasses).'">';
		foreach($aValues as $oTag)
		{
			if ($oTag instanceof TagSetFieldData)
			{
				$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode());
				$sAttCode = $this->GetCode();
				$sTagCode = $oTag->Get('code');
				$sTagLabel = $oTag->Get('label');
				$sTagDescription = $oTag->Get('description');
				$oFilter = DBSearch::FromOQL("SELECT $sClass WHERE $sAttCode MATCHES '$sTagCode'");
				$oAppContext = new ApplicationContext();
				$sContext = $oAppContext->GetForLink();
				$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($oFilter->GetClass());
				$sFilter = rawurlencode($oFilter->serialize());

				$sLink = '';
				if ($bWithLink && $this->bDisplayLink) {
					$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/$sUIPage?operation=search&filter=".$sFilter."&{$sContext}";
					$sLink = ' href="'.$sUrl.'"';
				}

				$sLabelForHtml = utils::EscapeHtml($sTagLabel);
				$sDescriptionForHtml = utils::EscapeHtml($sTagDescription);
				if (empty($sTagDescription)) {
					$sTooltipContent = $sTagLabel;
					$sTooltipHtmlEnabled = 'false';
				} else {
					$sTagLabelEscaped = utils::EscapeHtml($sTagLabel);
					$sTooltipContent = <<<HTML
<h4>$sTagLabelEscaped</h4>
<div>$sTagDescription</div>
HTML;
					$sTooltipHtmlEnabled = 'true';
				}
				$sTooltipContent = utils::HtmlEntities($sTooltipContent);

				$sHtml .= '<a'.$sLink.' class="attribute-set-item attribute-set-item-'.$sTagCode.'" data-code="'.$sTagCode.'" data-label="'.$sLabelForHtml.'" data-description="'.$sDescriptionForHtml.'" data-tooltip-content="'.$sTooltipContent.'" data-tooltip-html-enabled="'.$sTooltipHtmlEnabled.'">'.$sLabelForHtml.'</a>';
			}
			else
			{
				$sHtml .= '<span class="attribute-set-item">'.utils::EscapeHtml($oTag).'</span>';
			}
		}
		$sHtml .= '</span>';

		return $sHtml;
	}

	/**
	 * @param $value
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 *
	 */
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value) && ($value instanceof ormTagSet))
		{
			$sRes = "<Set>\n";
			if ($bLocalize)
			{
				$aValues = $value->GetLabels();
			}
			else
			{
				$aValues = $value->GetValues();
			}
			if (!empty($aValues))
			{
				$sRes .= '<Tag>'.implode('</Tag><Tag>', $aValues).'</Tag>';
			}
			$sRes .= "</Set>\n";
		}
		else
		{
			$sRes = '';
		}

		return $sRes;
	}

	/**
	 * List the available verbs for 'GetForTemplate'
	 */
	public function EnumTemplateVerbs()
	{
		return array(
			'' => 'Plain text representation',
			'html' => 'HTML representation (unordered list)',
		);
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 *
	 * @param mixed $value The current value of the field
	 * @param string $sVerb The verb specifying the representation of the value
	 * @param DBObject $oHostObject The object
	 * @param bool $bLocalize Whether or not to localize the value
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value) && ($value instanceof ormTagSet))
		{
			if ($bLocalize)
			{
				$aValues = $value->GetLabels();
				$sSep = ', ';
			}
			else
			{
				$aValues = $value->GetValues();
				$sSep = ' ';
			}

			switch ($sVerb)
			{
				case '':
					return implode($sSep, $aValues);

				case 'html':
					return '<ul><li>'.implode("</li><li>", $aValues).'</li></ul>';

				default:
					throw new Exception("Unknown verb '$sVerb' for attribute ".$this->GetCode().' in class '.get_class($oHostObject));
			}
		}
		throw new CoreUnexpectedValue("Bad value '$value' for attribute ".$this->GetCode().' in class '.get_class($oHostObject));
	}

	/**
	 * @inheritDoc
	 *
	 * @param \ormTagSet $value
	 *
	 * @return array
	 */
	public function GetForJSON($value)
	{
		$aRet = array();
		if (is_object($value) && ($value instanceof ormTagSet))
		{
			$aRet = $value->GetValues();
		}

		return $aRet;
	}

	/**
	 * @inheritDoc
	 *
	 * @return \ormTagSet
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \Exception
	 */
	public function FromJSONToValue($json)
	{
		$oSet = new ormTagSet($this->GetHostClass(), $this->GetCode(), $this->GetMaxItems());
		$oSet->SetValues($json);

		return $oSet;
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
		if ($value instanceof ormTagSet)
		{
			$aValues = $value->GetValues();

			return implode(' ', $aValues);
		}

		return parent::Fingerprint($value);
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\TagSetField';
	}
}

/**
 * The attribute dedicated to the friendly name automatic attribute (not written)
 *
 * @package     iTopORM
 */
class AttributeFriendlyName extends AttributeDefinition
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;
	public $m_sValue;

	public function __construct($sCode)
	{
		$this->m_sCode = $sCode;
		$aParams = array();
		$aParams["default_value"] = '';
		parent::__construct($sCode, $aParams);

		$this->m_sValue = $this->Get("default_value");
	}

	public function GetEditClass()
	{
		return "";
	}

	public function GetValuesDef()
	{
		return null;
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		// Code duplicated with AttributeObsolescenceFlag
		$aAttributes = $this->GetOptional("depends_on", array());
		$oExpression = $this->GetOQLExpression();
		foreach ($oExpression->ListRequiredFields() as $sAttCode) {
			if (!in_array($sAttCode, $aAttributes)) {
				$aAttributes[] = $sAttCode;
			}
		}

		return $aAttributes;
	}

	public static function IsScalar()
	{
		return true;
	}

	public function IsNullAllowed()
	{
		return false;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '')
		{
			$sPrefix = $this->GetCode(); // Warning AttributeComputedFieldVoid does not have any sql property
		}

		return array('' => $sPrefix);
	}

	public static function IsBasedOnOQLExpression()
	{
		return true;
	}

	public function GetOQLExpression()
	{
		return MetaModel::GetNameExpression($this->GetHostClass());
	}

	public function GetLabel($sDefault = null)
	{
		$sLabel = parent::GetLabel('');
		if (strlen($sLabel) == 0)
		{
			$sLabel = Dict::S('Core:FriendlyName-Label');
		}

		return $sLabel;
	}

	public function GetDescription($sDefault = null)
	{
		$sLabel = parent::GetDescription('');
		if (strlen($sLabel) == 0)
		{
			$sLabel = Dict::S('Core:FriendlyName-Description');
		}

		return $sLabel;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$sValue = $aCols[$sPrefix];

		return $sValue;
	}

	public function IsWritable()
	{
		return false;
	}

	public function IsMagic()
	{
		return true;
	}

	public static function IsBasedOnDBColumns()
	{
		return false;
	}

	public function SetFixedValue($sValue)
	{
		$this->m_sValue = $sValue;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return $this->m_sValue;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2html((string)$sValue);
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);

		return $sTextQualifier.$sEscaped.$sTextQualifier;
	}

	static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\StringField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		$oFormField->SetReadOnly(true);
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	// Do not display friendly names in the history of change
	public function DescribeChangeAsHTML($sOldValue, $sNewValue, $sLabel = null)
	{
		return '';
	}

	public function GetFilterDefinitions()
	{
		return array($this->GetCode() => $this->GetCode());
	}

	public function GetBasicFilterOperators()
	{
		return array("=" => "equals", "!=" => "differs from");
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

	public function IsPartOfFingerprint()
	{
		return false;
	}
}

/**
 * Holds the setting for the redundancy on a specific relation
 * Its value is a string, containing either:
 * - 'disabled'
 * - 'n', where n is a positive integer value giving the minimum count of items upstream
 * - 'n%', where n is a positive integer value, giving the minimum as a percentage of the total count of items upstream
 *
 * @package     iTopORM
 */
class AttributeRedundancySettings extends AttributeDBField
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array(
			'sql',
			'relation_code',
			'from_class',
			'neighbour_id',
			'enabled',
			'enabled_mode',
			'min_up',
			'min_up_type',
			'min_up_mode'
		);
	}

	public function GetValuesDef()
	{
		return null;
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		return array();
	}

	public function GetEditClass()
	{
		return "RedundancySetting";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "VARCHAR(20)"
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}


	public function GetValidationPattern()
	{
		return "^[0-9]{1,3}|[0-9]{1,2}%|disabled$";
	}

	public function GetMaxSize()
	{
		return 20;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		$sRet = 'disabled';
		if ($this->Get('enabled'))
		{
			if ($this->Get('min_up_type') == 'count')
			{
				$sRet = (string)$this->Get('min_up');
			}
			else // percent
			{
				$sRet = $this->Get('min_up').'%';
			}
		}

		return $sRet;
	}

	public function IsNullAllowed()
	{
		return false;
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
		if (is_null($proposedValue))
		{
			return '';
		}

		return (string)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		if (!is_string($value))
		{
			throw new CoreException('Expected the attribute value to be a string', array(
				'found_type' => gettype($value),
				'value' => $value,
				'class' => $this->GetHostClass(),
				'attribute' => $this->GetCode()
			));
		}

		return $value;
	}

	public function GetRelationQueryData()
	{
		foreach(MetaModel::EnumRelationQueries($this->GetHostClass(), $this->Get('relation_code'),
			false) as $sDummy => $aQueryInfo)
		{
			if ($aQueryInfo['sFromClass'] == $this->Get('from_class'))
			{
				if ($aQueryInfo['sNeighbour'] == $this->Get('neighbour_id'))
				{
					return $aQueryInfo;
				}
			}
		}

		return array();
	}

	/**
	 * Find the user option label
	 *
	 * @param string $sUserOption possible values : disabled|cout|percent
	 * @param string $sDefault
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetUserOptionFormat($sUserOption, $sDefault = null)
	{
		$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/'.$sUserOption, null, true /*user lang*/);
		if (is_null($sLabel))
		{
			// If no default value is specified, let's define the most relevant one for developping purposes
			if (is_null($sDefault))
			{
				$sDefault = str_replace('_', ' ', $this->m_sCode.':'.$sUserOption.'(%1$s)');
			}
			// Browse the hierarchy again, accepting default (english) translations
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/'.$sUserOption, $sDefault, false);
		}

		return $sLabel;
	}

	/**
	 * Override to display the value in the GUI
	 *
	 * @param string $sValue
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$sCurrentOption = $this->GetCurrentOption($sValue);
		$sClass = $oHostObject ? get_class($oHostObject) : $this->m_sHostClass;

		return sprintf($this->GetUserOptionFormat($sCurrentOption), $this->GetMinUpValue($sValue),
			MetaModel::GetName($sClass));
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	) {
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);

		return $sTextQualifier.$sEscaped.$sTextQualifier;
	}

	/**
	 * Helper to interpret the value, given the current settings and string representation of the attribute
	 */
	public function IsEnabled($sValue)
	{
		if ($this->get('enabled_mode') == 'fixed')
		{
			$bRet = $this->get('enabled');
		}
		else
		{
			$bRet = ($sValue != 'disabled');
		}

		return $bRet;
	}

	/**
	 * Helper to interpret the value, given the current settings and string representation of the attribute
	 */
	public function GetMinUpType($sValue)
	{
		if ($this->get('min_up_mode') == 'fixed')
		{
			$sRet = $this->get('min_up_type');
		}
		else
		{
			$sRet = 'count';
			if (substr(trim($sValue), -1, 1) == '%')
			{
				$sRet = 'percent';
			}
		}

		return $sRet;
	}

	/**
	 * Helper to interpret the value, given the current settings and string representation of the attribute
	 */
	public function GetMinUpValue($sValue)
	{
		if ($this->get('min_up_mode') == 'fixed')
		{
			$iRet = (int)$this->Get('min_up');
		}
		else
		{
			$sRefValue = $sValue;
			if (substr(trim($sValue), -1, 1) == '%')
			{
				$sRefValue = substr(trim($sValue), 0, -1);
			}
			$iRet = (int)trim($sRefValue);
		}

		return $iRet;
	}

	/**
	 * Helper to determine if the redundancy can be viewed/edited by the end-user
	 */
	public function IsVisible()
	{
		$bRet = false;
		if ($this->Get('enabled_mode') == 'fixed')
		{
			$bRet = $this->Get('enabled');
		}
		elseif ($this->Get('enabled_mode') == 'user')
		{
			$bRet = true;
		}

		return $bRet;
	}

	public function IsWritable()
	{
		if (($this->Get('enabled_mode') == 'fixed') && ($this->Get('min_up_mode') == 'fixed'))
		{
			return false;
		}

		return true;
	}

	/**
	 * Returns an HTML form that can be read by ReadValueFromPostedForm
	 */
	public function GetDisplayForm($sCurrentValue, $oPage, $bEditMode = false, $sFormPrefix = '')
	{
		$sRet = '';
		$aUserOptions = $this->GetUserOptions($sCurrentValue);
		if (count($aUserOptions) < 2)
		{
			$bEditOption = false;
		}
		else
		{
			$bEditOption = $bEditMode;
		}
		$sCurrentOption = $this->GetCurrentOption($sCurrentValue);
		foreach($aUserOptions as $sUserOption)
		{
			$bSelected = ($sUserOption == $sCurrentOption);
			$sRet .= '<div>';
			$sRet .= $this->GetDisplayOption($sCurrentValue, $oPage, $sFormPrefix, $bEditOption, $sUserOption,
				$bSelected);
			$sRet .= '</div>';
		}

		return $sRet;
	}

	const USER_OPTION_DISABLED = 'disabled';
	const USER_OPTION_ENABLED_COUNT = 'count';
	const USER_OPTION_ENABLED_PERCENT = 'percent';

	/**
	 * Depending on the xxx_mode parameters, build the list of options that are allowed to the end-user
	 */
	protected function GetUserOptions($sValue)
	{
		$aRet = array();
		if ($this->Get('enabled_mode') == 'user')
		{
			$aRet[] = self::USER_OPTION_DISABLED;
		}

		if ($this->Get('min_up_mode') == 'user')
		{
			$aRet[] = self::USER_OPTION_ENABLED_COUNT;
			$aRet[] = self::USER_OPTION_ENABLED_PERCENT;
		}
		else
		{
			if ($this->GetMinUpType($sValue) == 'count')
			{
				$aRet[] = self::USER_OPTION_ENABLED_COUNT;
			}
			else
			{
				$aRet[] = self::USER_OPTION_ENABLED_PERCENT;
			}
		}

		return $aRet;
	}

	/**
	 * Convert the string representation into one of the existing options
	 */
	protected function GetCurrentOption($sValue)
	{
		$sRet = self::USER_OPTION_DISABLED;
		if ($this->IsEnabled($sValue))
		{
			if ($this->GetMinUpType($sValue) == 'count')
			{
				$sRet = self::USER_OPTION_ENABLED_COUNT;
			}
			else
			{
				$sRet = self::USER_OPTION_ENABLED_PERCENT;
			}
		}

		return $sRet;
	}

	/**
	 * Display an option (form, or current value)
	 *
	 * @param string $sCurrentValue
	 * @param WebPage $oPage
	 * @param string $sFormPrefix
	 * @param bool $bEditMode
	 * @param string $sUserOption
	 * @param bool $bSelected
	 *
	 * @return string
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 * @throws \Exception
	 */
	protected function GetDisplayOption(
		$sCurrentValue, $oPage, $sFormPrefix, $bEditMode, $sUserOption, $bSelected = true
	) {
		$sRet = '';

		$iCurrentValue = $this->GetMinUpValue($sCurrentValue);
		if ($bEditMode)
		{
			$sValue = null;
			$sHtmlNamesPrefix = 'rddcy_'.$this->Get('relation_code').'_'.$this->Get('from_class').'_'.$this->Get('neighbour_id');
			switch ($sUserOption)
			{
				case self::USER_OPTION_DISABLED:
					$sValue = ''; // Empty placeholder
					break;

				case self::USER_OPTION_ENABLED_COUNT:
					if ($bEditMode)
					{
						$sName = $sHtmlNamesPrefix.'_min_up_count';
						$sEditValue = $bSelected ? $iCurrentValue : '';
						$sValue = '<input class="redundancy-min-up-count" type="string" size="3" name="'.$sName.'" value="'.$sEditValue.'">';
						// To fix an issue on Firefox: focus set to the option (because the input is within the label for the option)
						$oPage->add_ready_script("\$('[name=\"$sName\"]').on('click', function(){var me=this; setTimeout(function(){\$(me).focus();}, 100);});");
					}
					else
					{
						$sValue = $iCurrentValue;
					}
					break;

				case self::USER_OPTION_ENABLED_PERCENT:
					if ($bEditMode)
					{
						$sName = $sHtmlNamesPrefix.'_min_up_percent';
						$sEditValue = $bSelected ? $iCurrentValue : '';
						$sValue = '<input class="redundancy-min-up-percent" type="string" size="3" name="'.$sName.'" value="'.$sEditValue.'">';
						// To fix an issue on Firefox: focus set to the option (because the input is within the label for the option)
						$oPage->add_ready_script("\$('[name=\"$sName\"]').on('click', function(){var me=this; setTimeout(function(){\$(me).focus();}, 100);});");
					}
					else
					{
						$sValue = $iCurrentValue;
					}
					break;
			}
			$sLabel = sprintf($this->GetUserOptionFormat($sUserOption), $sValue,
				MetaModel::GetName($this->GetHostClass()));

			$sOptionName = $sHtmlNamesPrefix.'_user_option';
			$sOptionId = $sOptionName.'_'.$sUserOption;
			$sChecked = $bSelected ? 'checked' : '';
			$sRet = '<input type="radio" name="'.$sOptionName.'" id="'.$sOptionId.'" value="'.$sUserOption.'" '.$sChecked.'> <label for="'.$sOptionId.'">'.$sLabel.'</label>';
		}
		else
		{
			// Read-only: display only the currently selected option
			if ($bSelected)
			{
				$sRet = sprintf($this->GetUserOptionFormat($sUserOption), $iCurrentValue,
					MetaModel::GetName($this->GetHostClass()));
			}
		}

		return $sRet;
	}

	/**
	 * Makes the string representation out of the values given by the form defined in GetDisplayForm
	 */
	public function ReadValueFromPostedForm($sFormPrefix)
	{
		$sHtmlNamesPrefix = 'rddcy_'.$this->Get('relation_code').'_'.$this->Get('from_class').'_'.$this->Get('neighbour_id');

		$iMinUpCount = (int)utils::ReadPostedParam($sHtmlNamesPrefix.'_min_up_count', null, 'raw_data');
		$iMinUpPercent = (int)utils::ReadPostedParam($sHtmlNamesPrefix.'_min_up_percent', null, 'raw_data');
		$sSelectedOption = utils::ReadPostedParam($sHtmlNamesPrefix.'_user_option', null, 'raw_data');
		switch ($sSelectedOption)
		{
			case self::USER_OPTION_ENABLED_COUNT:
				$sRet = $iMinUpCount;
				break;

			case self::USER_OPTION_ENABLED_PERCENT:
				$sRet = $iMinUpPercent.'%';
				break;

			case self::USER_OPTION_DISABLED:
			default:
				$sRet = 'disabled';
				break;
		}

		return $sRet;
	}
}

/**
 * Custom fields managed by an external implementation
 *
 * @package     iTopORM
 */
class AttributeCustomFields extends AttributeDefinition
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("handler_class"));
	}

	public function GetEditClass()
	{
		return "CustomFields";
	}

	public function IsWritable()
	{
		return true;
	}

	public static function LoadFromClassTables()
	{
		return false;
	} // See ReadValue...

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return new ormCustomFieldsValue($oHostObject, $this->GetCode());
	}

	public function GetBasicFilterOperators()
	{
		return array();
	}

	public function GetBasicFilterLooseOperator()
	{
		return '';
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return '';
	}

	/**
	 * @param DBObject $oHostObject
	 * @param array|null $aValues
	 *
	 * @return CustomFieldsHandler
	 */
	public function GetHandler($aValues = null)
	{
		$sHandlerClass = $this->Get('handler_class');
		/** @var \TemplateFieldsHandler $oHandler */
		$oHandler = new $sHandlerClass($this->GetCode());
		if (!is_null($aValues))
		{
			$oHandler->SetCurrentValues($aValues);
		}

		return $oHandler;
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		$sHandlerClass = $this->Get('handler_class');

		return $sHandlerClass::GetPrerequisiteAttributes($sClass);
	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		return $this->GetForTemplate($sValue, '', $oHostObj, true);
	}

	/**
	 * Makes the string representation out of the values given by the form defined in GetDisplayForm
	 */
	public function ReadValueFromPostedForm($oHostObject, $sFormPrefix) {
		$aRawData = json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$this->GetCode()}", '{}', 'raw_data'), true);
		if ($aRawData != null) {
			return new ormCustomFieldsValue($oHostObject, $this->GetCode(), $aRawData);
		}
		else {
			return null;
		}
	}

	public function MakeRealValue($proposedValue, $oHostObject) {
		if (is_object($proposedValue) && ($proposedValue instanceof ormCustomFieldsValue)) {
			if (false === $oHostObject->IsNew()) {
				// In that case we need additional keys : see \TemplateFieldsHandler::DoBuildForm
				$aRequestTemplateValues = $proposedValue->GetValues();
				if (false === array_key_exists('current_template_id', $aRequestTemplateValues)) {
					$aRequestTemplateValues['current_template_id'] = $aRequestTemplateValues['template_id'];
					$aRequestTemplateValues['current_template_data'] = $aRequestTemplateValues['template_data'];
					$proposedValue = new ormCustomFieldsValue($oHostObject, $this->GetCode(), $aRequestTemplateValues);
				}
			}

			if (is_null($proposedValue->GetHostObject())) {
				// the object might not be set : for example in \AttributeCustomFields::FromJSONToValue we don't have the object available :(
				$proposedValue->SetHostObject($oHostObject);
			}

			return $proposedValue;
		}

		if (is_string($proposedValue)) {
			$aValues = json_decode($proposedValue, true);

			return new ormCustomFieldsValue($oHostObject, $this->GetCode(), $aValues);
		}

		if (is_array($proposedValue)) {
			return new ormCustomFieldsValue($oHostObject, $this->GetCode(), $proposedValue);
		}

		if (is_null($proposedValue)) {
			return new ormCustomFieldsValue($oHostObject, $this->GetCode());
		}

		throw new Exception('Unexpected type for the value of a custom fields attribute: '.gettype($proposedValue));
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\SubFormField';
	}

	/**
	 * Override to build the relevant form field
	 *
	 * When called first, $oFormField is null and will be created (eg. Make). Then when the ::parent is called and the
	 * $oFormField is passed, MakeFormField behaves more like a Prepare.
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null)
		{
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
			$oFormField->SetForm($this->GetForm($oObject));
		}
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	/**
	 * @param DBObject $oHostObject
	 * @param null $sFormPrefix
	 *
	 * @return Combodo\iTop\Form\Form
	 * @throws \Exception
	 */
	public function GetForm(DBObject $oHostObject, $sFormPrefix = null)
	{
		try {
			$oValue = $oHostObject->Get($this->GetCode());
			$oHandler = $this->GetHandler($oValue->GetValues());
			$sFormId = utils::IsNullOrEmptyString($sFormPrefix) ? 'cf_'.$this->GetCode() : $sFormPrefix.'_cf_'.$this->GetCode();
			$oHandler->BuildForm($oHostObject, $sFormId);
			$oForm = $oHandler->GetForm();
		}
		catch (Exception $e) {
			$oForm = new Form('');
			$oField = new LabelField('');
			$oField->SetLabel('Custom field error: '.$e->getMessage());
			$oForm->AddField($oField);
			$oForm->Finalize();
		}

		return $oForm;
	}

	/**
	 * Read the data from where it has been stored. This verb must be implemented as soon as LoadFromClassTables returns false
	 * and LoadInObject returns true
	 *
	 * @param DBObject $oHostObject
	 *
	 * @return mixed|null
	 * @since 3.1.0
	 */
	public function ReadExternalValues(DBObject $oHostObject)
	{
		try
		{
			$oHandler = $this->GetHandler();
			$aValues = $oHandler->ReadValues($oHostObject);
			$oRet = new ormCustomFieldsValue($oHostObject, $this->GetCode(), $aValues);
		} catch (Exception $e)
		{
			$oRet = new ormCustomFieldsValue($oHostObject, $this->GetCode());
		}

		return $oRet;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 3.1.0 N°6043 Move code contained in \AttributeCustomFields::WriteValue to this generic method
	 */
	public function WriteExternalValues(DBObject $oHostObject): void
	{
		$oValue = $oHostObject->Get($this->GetCode());
		if (!($oValue instanceof ormCustomFieldsValue)) {
			$oHandler = $this->GetHandler();
			$aValues = array();
		} else {
			// Pass the values through the form to make sure that they are correct
			$oHandler = $this->GetHandler($oValue->GetValues());
			$oHandler->BuildForm($oHostObject, '');
			$oForm = $oHandler->GetForm();
			$aValues = $oForm->GetCurrentValues();
		}

		$oHandler->WriteValues($oHostObject, $aValues);
	}

	/**
	 * The part of the current attribute in the object's signature, for the supplied value
	 *
	 * @param ormCustomFieldsValue $value The value of this attribute for the object
	 *
	 * @return string The "signature" for this field/attribute
	 */
	public function Fingerprint($value)
	{
		$oHandler = $this->GetHandler($value->GetValues());

		return $oHandler->GetValueFingerprint();
	}

	/**
	 * Check the validity of the data
	 *
	 * @param DBObject $oHostObject
	 * @param $value
	 *
	 * @return bool|string true or error message
	 */
	public function CheckValue(DBObject $oHostObject, $value)
	{
		try {
			$oHandler = $this->GetHandler($value->GetValues());
			$oHandler->BuildForm($oHostObject, '');
			$ret = $oHandler->Validate($oHostObject);
		} catch (Exception $e) {
			$ret = $e->getMessage();
		}

		return $ret;
	}

	/**
	 * Cleanup data upon object deletion (object id still available here)
	 *
	 * @param DBObject $oHostObject
	 *
	 * @throws \CoreException
	 * @since 3.1.0
	 */
	public function DeleteExternalValues(DBObject $oHostObject): void
	{
		$oValue = $oHostObject->Get($this->GetCode());
		$oHandler = $this->GetHandler($oValue->GetValues());

		$oHandler->DeleteValues($oHostObject);
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		try
		{
			/** @var \ormCustomFieldsValue $value */
			$sRet = $value->GetAsHTML($bLocalize);
		} catch (Exception $e)
		{
			$sRet = 'Custom field error: '.utils::EscapeHtml($e->getMessage());
		}

		return $sRet;
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		try {
			$sRet = $value->GetAsXML($bLocalize);
		}
		catch (Exception $e) {
			$sRet = Str::pure2xml('Custom field error: '.$e->getMessage());
		}

		return $sRet;
	}

	/**
	 * @param \ormCustomFieldsValue $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param \DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return string
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 */
	public function GetAsCSV(
		$value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	)
	{
		try {
			$sRet = $value->GetAsCSV($sSeparator, $sTextQualifier, $bLocalize, $bConvertToPlainText);
		}
		catch (Exception $e) {
			$sFrom = array("\r\n", $sTextQualifier);
			$sTo = array("\n", $sTextQualifier.$sTextQualifier);
			$sEscaped = str_replace($sFrom, $sTo, 'Custom field error: '.$e->getMessage());
			$sRet = $sTextQualifier.$sEscaped.$sTextQualifier;
		}

		return $sRet;
	}

	/**
	 * List the available verbs for 'GetForTemplate'
	 */
	public function EnumTemplateVerbs()
	{
		$sHandlerClass = $this->Get('handler_class');

		return $sHandlerClass::EnumTemplateVerbs();
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 *
	 * @param $value mixed The current value of the field
	 * @param $sVerb string The verb specifying the representation of the value
	 * @param $oHostObject DBObject The object
	 * @param $bLocalize bool Whether or not to localize the value
	 *
	 * @return string
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		try
		{
			$sRet = $value->GetForTemplate($sVerb, $bLocalize);
		} catch (Exception $e)
		{
			$sRet = 'Custom field error: '.$e->getMessage();
		}

		return $sRet;
	}

	public function MakeValueFromString(
		$sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null,
		$sAttributeQualifier = null
	) {
		return null;
	}

	/**
	 * @inheritDoc
	 *
	 * @param \ormCustomFieldsValue $value
	 *
	 * @return string|array
	 *
	 * @since 3.1.0 N°1150 now returns the value (was always returning null before)
	 */
	public function GetForJSON($value)
	{
		try {
			$sRet = $value->GetForJSON();
		}
		catch (Exception $e) {
			$sRet = 'Custom field error: '.$e->getMessage();
		}

		return $sRet;
	}

	/**
	 * @inheritDoc
	 *
	 * @return ?\ormCustomFieldsValue with empty host object as we don't have it here (most consumers don't have an object in their context, for example in \RestUtils::GetObjectSetFromKey)
	 *                  The host object will be set in {@see MakeRealValue}
	 *                  All the necessary checks will be done in {@see CheckValue}
	 */
	public function FromJSONToValue($json)
	{
		return ormCustomFieldsValue::FromJSONToValue($json, $this);
	}

	public function Equals($val1, $val2)
	{
		try
		{
			$bEquals = $val1->Equals($val2);
		} catch (Exception $e)
		{
			$bEquals = false;
		}

		return $bEquals;
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		// Protection against wrong value type
		if (false === ($proposedValue instanceof ormCustomFieldsValue)) {
			return parent::HasAValue($proposedValue);
		}

		return count($proposedValue->GetValues()) > 0;
	}

	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		$oMyChangeOp->Set("prevdata", json_encode($original->GetValues()));
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeCustomFields::class;
	}
}

class AttributeArchiveFlag extends AttributeBoolean
{
	public function __construct($sCode)
	{
		parent::__construct($sCode, array(
			"allowed_values" => null,
			"sql" => $sCode,
			"default_value" => false,
			"is_null_allowed" => false,
			"depends_on" => array()
		));
	}

	public function RequiresIndex()
	{
		return true;
	}

	public function CopyOnAllTables()
	{
		return true;
	}

	public function IsWritable()
	{
		return false;
	}

	public function IsMagic()
	{
		return true;
	}

	public function GetLabel($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeArchiveFlag/Label', $sDefault);

		return parent::GetLabel($sDefault);
	}

	public function GetDescription($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeArchiveFlag/Label+', $sDefault);

		return parent::GetDescription($sDefault);
	}
}

class AttributeArchiveDate extends AttributeDate
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetLabel($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeArchiveDate/Label', $sDefault);

		return parent::GetLabel($sDefault);
	}

	public function GetDescription($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeArchiveDate/Label+', $sDefault);

		return parent::GetDescription($sDefault);
	}
}

class AttributeObsolescenceFlag extends AttributeBoolean
{
	public function __construct($sCode)
	{
		parent::__construct($sCode, array(
			"allowed_values" => null,
			"sql" => $sCode,
			"default_value" => "",
			"is_null_allowed" => false,
			"depends_on" => array()
		));
	}

	public function IsWritable()
	{
		return false;
	}

	public function IsMagic()
	{
		return true;
	}

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
		return true;
	}

	public function GetOQLExpression()
	{
		return MetaModel::GetObsolescenceExpression($this->GetHostClass());
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		return array();
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		return array();
	} // returns column/spec pairs (1 in most of the cases), for STRUCTURING (DB creation)

	public function GetSQLValues($value)
	{
		return array();
	} // returns column/value pairs (1 in most of the cases), for WRITING (Insert, Update)

	public function GetEditClass()
	{
		return "";
	}

	public function GetValuesDef()
	{
		return null;
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		// Code duplicated with AttributeFriendlyName
		$aAttributes = $this->GetOptional("depends_on", array());
		$oExpression = $this->GetOQLExpression();
		foreach ($oExpression->ListRequiredFields() as $sClass => $sAttCode)
		{
			if (!in_array($sAttCode, $aAttributes))
			{
				$aAttributes[] = $sAttCode;
			}
		}
		return $aAttributes;
	}

	public function IsDirectField()
	{
		return true;
	}

	public static function IsScalar()
	{
		return true;
	}

	public function GetSQLExpr()
	{
		return null;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return $this->MakeRealValue(false, $oHostObject);
	}

	public function IsNullAllowed()
	{
		return false;
	}

	public function GetLabel($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeObsolescenceFlag/Label', $sDefault);

		return parent::GetLabel($sDefault);
	}

	public function GetDescription($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeObsolescenceFlag/Label+', $sDefault);

		return parent::GetDescription($sDefault);
	}
}

class AttributeObsolescenceDate extends AttributeDate
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws \Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetLabel($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeObsolescenceDate/Label', $sDefault);

		return parent::GetLabel($sDefault);
	}

	public function GetDescription($sDefault = null)
	{
		$sDefault = Dict::S('Core:AttributeObsolescenceDate/Label+', $sDefault);

		return parent::GetDescription($sDefault);
	}
}
