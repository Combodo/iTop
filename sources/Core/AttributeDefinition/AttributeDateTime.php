<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use BinaryExpression;
use CMDBSource;
use CoreUnexpectedValue;
use DateTime;
use DateTimeFormat;
use DateTimeImmutable;
use DBObject;
use Dict;
use Exception;
use Expression;
use FieldExpression;
use IssueLog;
use MetaModel;
use Str;
use utils;
use VariableExpression;

/**
 * Map a date+time column to an attribute
 *
 * @package     iTopORM
 */
class AttributeDateTime extends AttributeDBField
{
	public const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_DATE_TIME;

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
	 * @throws Exception
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
		if (self::$oFormat == null) {
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

		$sFullFormat = str_replace(['$date', '$time'], [$sDateFormat, $sTimeFormat], $sDateAndTimeFormat);

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
		try {
			$oDateTime = $this->GetFormat()->Parse($sSearchString);
			$sSearchString = $oDateTime->format($this->GetInternalFormat());
		} catch (Exception $e) {
			$sFormatString = '!'.(string)AttributeDate::GetFormat(); // BEWARE: ! is needed to set non-parsed fields to zero !!!
			$oDateTime = DateTime::createFromFormat($sFormatString, $sSearchString);
			if ($oDateTime !== false) {
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
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		$oFormField->SetPHPDateTimeFormat((string)$this->GetFormat());
		$oFormField->SetJSDateTimeFormat($this->GetFormat()->ToMomentJS());

		$oFormField = parent::MakeFormField($oObject, $oFormField);

		// After call to the parent as it sets the current value
		$oValue = $oObject->Get($this->GetCode());
		if ($oValue === $this->GetNullValue()) {
			$oValue = $this->GetDefaultValue($oObject);
		}
		$oFormField->SetCurrentValue($this->GetFormat()->Format($oValue));

		return $oFormField;
	}

	/**
	 * @inheritdoc
	 */
	public function EnumTemplateVerbs()
	{
		return [
			''    => 'Formatted representation',
			'raw' => 'Not formatted representation',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		switch ($sVerb) {
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
		$aColumns = [];
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
		$sDefaultValue = $this->Get('default_value');
		if (utils::IsNotNullOrEmptyString($sDefaultValue)) {
			try {
				$sDefaultDate = Expression::FromOQL($sDefaultValue)->Evaluate([]);
			} catch (Exception $e) {
				try {
					$sDefaultDate = Expression::FromOQL('"'.$sDefaultValue.'"')->Evaluate([]);
				} catch (Exception $e) {
					IssueLog::Error("Invalid default value '$sDefaultValue' for field '{$this->GetCode()}' on class '{$this->GetHostClass()}', defaulting to null");

					return $this->GetNullValue();
				}
			}
			try {
				$oDate = new DateTimeImmutable($sDefaultDate);
			} catch (Exception $e) {
				IssueLog::Error("Invalid default value '$sDefaultValue' for field '{$this->GetCode()}' on class '{$this->GetHostClass()}', defaulting to null");

				return $this->GetNullValue();
			}

			return $oDate->format($this->GetInternalFormat());
		}

		return $this->GetNullValue();
	}

	public function GetValidationPattern()
	{
		return static::GetFormat()->ToRegExpr();
	}

	public function GetBasicFilterOperators()
	{
		return [
			"="         => "equals",
			"!="        => "differs from",
			"<"         => "before",
			"<="        => "before",
			">"         => "after (strictly)",
			">="        => "after",
			"SameDay"   => "same day (strip time)",
			"SameMonth" => "same year/month",
			"SameYear"  => "same year",
			"Today"     => "today",
			">|"        => "after today + N days",
			"<|"        => "before today + N days",
			"=|"        => "equals today + N days",
		];
	}

	public function GetBasicFilterLooseOperator()
	{
		// Unless we implement a "same xxx, depending on given precision" !
		return "=";
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		$sQValue = CMDBSource::Quote($value);

		switch ($sOpCode) {
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
	 * @throws CoreUnexpectedValue if invalid value type or the string passed cannot be converted
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) {
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
		if (empty($value)) {
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
		$sValue,
		$sSeparator = ',',
		$sTextQualifier = '"',
		$oHostObject = null,
		$bLocalize = true,
		$bConvertToPlainText = false
	) {
		if (empty($sValue) || ($sValue === '0000-00-00 00:00:00') || ($sValue === '0000-00-00')) {
			return '';
		} else {
			if ((string)static::GetFormat() !== static::GetInternalFormat()) {
				// Format conversion
				$oDate = new DateTime($sValue);
				if ($oDate !== false) {
					$sValue = static::GetFormat()->format($oDate);
				}
			}
		}
		$sFrom = ["\r\n", $sTextQualifier];
		$sTo = ["\n", $sTextQualifier.$sTextQualifier];
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
	 * @throws CoreException
	 */
	public function GetSmartConditionExpression(
		$sSearchText,
		FieldExpression $oField,
		&$aParams,
		$bParseSearchString = false
	) {
		// Possible smart patterns
		$aPatterns = [
			'between'               => ['pattern' => '/^\[(.*),(.*)\]$/', 'operator' => 'n/a'],
			'greater than or equal' => ['pattern' => '/^>=(.*)$/', 'operator' => '>='],
			'greater than'          => ['pattern' => '/^>(.*)$/', 'operator' => '>'],
			'less than or equal'    => ['pattern' => '/^<=(.*)$/', 'operator' => '<='],
			'less than'             => ['pattern' => '/^<(.*)$/', 'operator' => '<'],
		];

		$sPatternFound = '';
		$aMatches = [];
		foreach ($aPatterns as $sPatName => $sPattern) {
			if (preg_match($sPattern['pattern'], $sSearchText, $aMatches)) {
				$sPatternFound = $sPatName;
				break;
			}
		}

		switch ($sPatternFound) {
			case 'between':

				$sParamName1 = $oField->GetParent().'_'.$oField->GetName().'_1';
				$oRightExpr = new VariableExpression($sParamName1);
				if ($bParseSearchString) {
					$aParams[$sParamName1] = $this->ParseSearchString($aMatches[1]);
				} else {
					$aParams[$sParamName1] = $aMatches[1];
				}
				$oCondition1 = new BinaryExpression($oField, '>=', $oRightExpr);

				$sParamName2 = $oField->GetParent().'_'.$oField->GetName().'_2';
				$oRightExpr = new VariableExpression($sParamName2);
				if ($bParseSearchString) {
					$aParams[$sParamName2] = $this->ParseSearchString($aMatches[2]);
				} else {
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
				if ($bParseSearchString) {
					$aParams[$sParamName] = $this->ParseSearchString($aMatches[1]);
				} else {
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

		return vsprintf($sDict, [$oFormat->ToPlaceholder(), $sExample]);
	}
}
