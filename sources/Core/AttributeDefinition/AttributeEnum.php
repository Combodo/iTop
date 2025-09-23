<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use Combodo\iTop\Application\UI\Base\Component\FieldBadge\FieldBadgeUIBlockFactory;
use Combodo\iTop\Renderer\BlockRenderer;
use DBObject;
use Dict;
use MetaModel;
use MySQLException;
use ormStyle;

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
	 * @throws Exception
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
	 * @return ormStyle|null
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
	 *
	 * @param bool $bFullSpec
	 * @param bool $bIncludeActualValues
	 * @param string $sSQLTableName The table where to look for the actual values (may be useful for data synchro tables)
	 *
	 * @return string
	 * @since 3.0.0
	 */
	protected function GetSQLColHelper($bFullSpec = false, $bIncludeActualValues = false, $sSQLTableName = null)
	{
		$oValDef = $this->GetValuesDef();
		if ($oValDef) {
			$aValues = CMDBSource::Quote(array_keys($oValDef->GetValues(array(), "")), true);
		} else {
			$aValues = array();
		}

		// Preserve the values already present in the database to ease migrations
		if ($bIncludeActualValues) {
			if ($sSQLTableName == null) {
				// No SQL table given, use the one of the attribute
				$sHostClass = $this->GetHostClass();
				$sSQLTableName = MetaModel::DBGetTable($sHostClass, $this->GetCode());
			}
			$aValues = array_unique(array_merge($aValues, $this->GetActualValuesInDB($sSQLTableName)));
		}

		if (count($aValues) > 0) {
			// The syntax used here do matters
			// In particular, I had to remove unnecessary spaces to
			// make sure that this string will match the field type returned by the DB
			// (used to perform a comparison between the current DB format and the data model)
			return "ENUM(".implode(",", $aValues).")"
				.CMDBSource::GetSqlStringColumnDefinition()
				.($bFullSpec ? $this->GetSQLColSpec() : '');
		} else {
			return "VARCHAR(255)"
				.CMDBSource::GetSqlStringColumnDefinition()
				.($bFullSpec ? " DEFAULT ''" : ""); // ENUM() is not an allowed syntax!
		}
	}

	/**
	 * @see AttributeDefinition::GetImportColumns()
	 * @since 3.0.0
	 * {@inheritDoc}
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
	 *
	 * @return string[]
	 * @since 3.0.0
	 */
	protected function GetActualValuesInDB(string $sDBTable)
	{
		$aValues = array();
		try {
			$sSQL = "SELECT DISTINCT `".$this->GetSQLExpr()."` AS value FROM `$sDBTable`;";
			$aValuesInDB = CMDBSource::QueryToArray($sSQL);
			foreach ($aValuesInDB as $aRow) {
				if ($aRow['value'] !== null) {
					$aValues[] = $aRow['value'];
				}
			}
		}
		catch (MySQLException $e) {
			// Never mind, maybe the table does not exist yet (new installation from scratch)
			// It seems more efficient to try and ignore errors than to test if the table & column really exists
		}

		return CMDBSource::Quote($aValues);
	}

	protected function GetSQLColSpec()
	{
		$default = $this->ScalarToSQL($this->GetDefaultValue());
		if (is_null($default)) {
			$sRet = '';
		} else {
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
		if ($this->IsNull($value)) {
			return null;
		} else {
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
		if (is_null($sValue)) {
			// Unless a specific label is defined for the null value of this enum, use a generic "undefined" label
			$sLabel = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue,
				Dict::S('Enum:Undefined'));
		} else {
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, null, true /*user lang*/);
			if (is_null($sLabel)) {
				$sDefault = str_replace('_', ' ', $sValue);
				// Browse the hierarchy again, accepting default (english) translations
				$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, $sDefault, false);
			}
		}

		return $sLabel;
	}

	public function GetValueDescription($sValue)
	{
		if (is_null($sValue)) {
			// Unless a specific label is defined for the null value of this enum, use a generic "undefined" label
			$sDescription = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+',
				Dict::S('Enum:Undefined'));
		} else {
			$sDescription = Dict::S('Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+',
				'', true /* user language only */);
			if (strlen($sDescription) == 0) {
				$sParentClass = MetaModel::GetParentClass($this->m_sHostClass);
				if ($sParentClass) {
					if (MetaModel::IsValidAttCode($sParentClass, $this->m_sCode)) {
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
		} else {
			$sRes = parent::GetAsHtml($sValue, $oHostObject, $bLocalize);
		}

		return $sRes;
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_null($value)) {
			$sFinalValue = '';
		} elseif ($bLocalize) {
			$sFinalValue = $this->GetValueLabel($value);
		} else {
			$sFinalValue = $value;
		}
		$sRes = parent::GetAsXML($sFinalValue, $oHostObject, $bLocalize);

		return $sRes;
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	)
	{
		if (is_null($sValue)) {
			$sFinalValue = '';
		} elseif ($bLocalize) {
			$sFinalValue = $this->GetValueLabel($sValue);
		} else {
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
		if ($oFormField === null) {
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
		if (is_null($sValue)) {
			return '';
		} else {
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
		if (is_null($aRawValues)) {
			return null;
		}
		$aLocalizedValues = array();
		foreach ($aRawValues as $sKey => $sValue) {
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
	)
	{
		if ($bLocalizedValue) {
			// Lookup for the value matching the input
			//
			$sFoundValue = null;
			$aRawValues = parent::GetAllowedValues();
			if (!is_null($aRawValues)) {
				foreach ($aRawValues as $sKey => $sValue) {
					$sRefValue = $this->GetValueLabel($sKey);
					if ($sProposedValue == $sRefValue) {
						$sFoundValue = $sKey;
						break;
					}
				}
			}
			if (is_null($sFoundValue)) {
				return null;
			}

			return $this->MakeRealValue($sFoundValue, null);
		} else {
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
		if ($proposedValue == '') {
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