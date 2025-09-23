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
		} // 0 is transformed into '' !

		return (int)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		assert(is_numeric($value) || is_null($value));

		return $value; // supposed to be an int
	}
}