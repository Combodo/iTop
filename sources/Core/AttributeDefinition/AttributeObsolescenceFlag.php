<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use DBObject;
use Dict;
use MetaModel;

class AttributeObsolescenceFlag extends AttributeBoolean
{
	public function __construct($sCode)
	{
		parent::__construct($sCode, [
			"allowed_values"  => null,
			"sql"             => $sCode,
			"default_value"   => "",
			"is_null_allowed" => false,
			"depends_on"      => [],
		]);
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
		return [];
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		return [];
	} // returns column/spec pairs (1 in most of the cases), for STRUCTURING (DB creation)

	public function GetSQLValues($value)
	{
		return [];
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
		$aAttributes = $this->GetOptional("depends_on", []);
		$oExpression = $this->GetOQLExpression();
		foreach ($oExpression->ListRequiredFields() as $sClass => $sAttCode) {
			if (!in_array($sAttCode, $aAttributes)) {
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
