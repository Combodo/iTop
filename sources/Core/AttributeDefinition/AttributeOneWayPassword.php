<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBChangeOp;
use CMDBChangeOpSetAttributeOneWayPassword;
use CMDBSource;
use DBObject;
use Exception;
use ormPassword;
use utils;

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
	 * @throws Exception
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
		if (is_object($oPassword)) {
			$oPassword = clone $proposedValue;
		} else {
			$oPassword = new ormPassword('', '');
			$oPassword->SetPassword($proposedValue);
		}

		return $oPassword;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '') {
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
		if (!array_key_exists($sPrefix, $aCols)) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		}
		$hashed = isset($aCols[$sPrefix]) ? $aCols[$sPrefix] : '';

		if (!array_key_exists($sPrefix.'_salt', $aCols)) {
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
		if ($value instanceof ormPassword) {
			$aValues = array();
			$aValues[$this->GetCode().'_hash'] = $value->GetHash();
			$aValues[$this->GetCode().'_salt'] = $value->GetSalt();
		} else {
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
		if (!isset($aCols[$sPrefix])) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		}
		$sClearPwd = $aCols[$sPrefix];

		$oPassword = new ormPassword('', '');
		$oPassword->SetPassword($sClearPwd);

		return $oPassword;
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
		if (is_object($value)) {
			return $value->GetAsHTML();
		}

		return '';
	}

	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	)
	{
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