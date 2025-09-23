<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use CoreException;
use utils;

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
	 * @throws Exception
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
			"="  => "equals",
			">"  => "greater (strict) than",
			">=" => "greater than",
			"<"  => "less (strict) than",
			"<=" => "less than",
			"in" => "in",
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
		switch ($sOpCode) {
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
				if (!is_array($value)) {
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
		if (is_null($proposedValue)) {
			return null;
		}
		if ($proposedValue === '') {
			return null;
		}

		return $this->ScalarToSQL($proposedValue);
	}

	public function ScalarToSQL($value)
	{
		assert(is_null($value) || preg_match('/'.$this->GetValidationPattern().'/', $value));

		if (!is_null($value) && ($value !== '')) {
			$value = sprintf("%1.".$this->Get('decimals')."F", $value);
		}

		return $value; // null or string
	}
}