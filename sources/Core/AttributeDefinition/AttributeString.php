<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use CoreWarning;
use DBObject;
use Exception;
use utils;

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
		if (empty($sPattern)) {
			return parent::GetValidationPattern();
		} else {
			return $sPattern;
		}
	}

	public function CheckFormat($value)
	{
		$sRegExp = $this->GetValidationPattern();
		if (empty($sRegExp)) {
			return true;
		} else {
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
			"="             => "equals",
			"!="            => "differs from",
			"Like"          => "equals (no case)",
			"NotLike"       => "differs from (no case)",
			"Contains"      => "contains",
			"Begins with"   => "begins with",
			"Finishes with" => "finishes with",
		);
	}

	public function GetBasicFilterLooseOperator()
	{
		return "Contains";
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		$sQValue = CMDBSource::Quote($value);
		switch ($sOpCode) {
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
		if (is_null($proposedValue)) {
			return '';
		}

		return (string)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		if (!is_string($value) && !is_null($value)) {
			throw new CoreWarning('Expected the attribute value to be a string', array(
				'found_type' => gettype($value),
				'value'      => $value,
				'class'      => $this->GetHostClass(),
				'attribute'  => $this->GetCode(),
			));
		}

		return $value;
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	)
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

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\StringField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

}