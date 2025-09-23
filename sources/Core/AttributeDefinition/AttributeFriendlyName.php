<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use DBObject;
use Dict;
use MetaModel;
use Str;

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
		if ($sPrefix == '') {
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
		if (strlen($sLabel) == 0) {
			$sLabel = Dict::S('Core:FriendlyName-Label');
		}

		return $sLabel;
	}

	public function GetDescription($sDefault = null)
	{
		$sLabel = parent::GetDescription('');
		if (strlen($sLabel) == 0) {
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
	)
	{
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
		if ($oFormField === null) {
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
		switch ($sOpCode) {
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