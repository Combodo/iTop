<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use DBObject;

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
		if (is_null($default)) {
			$sRet = '';
		} else {
			if (is_numeric($default)) {
				// Though it is a string in PHP, it will be considered as a numeric value in MySQL
				// Then it must not be quoted here, to preserve the compatibility with the value returned by CMDBSource::GetFieldSpec
				$sRet = " DEFAULT $default";
			} else {
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
		switch ($sOpCode) {
			case '!=':
				return $this->GetSQLExpr()." != $sQValue";
				break;
			case '=':
			default:
				return $this->GetSQLExpr()." = $sQValue";
		}
	}
}