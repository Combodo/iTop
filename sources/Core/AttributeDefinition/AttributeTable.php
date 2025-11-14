<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use CoreException;
use Exception;
use Str;

class AttributeTable extends AttributeDBField
{
	public const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

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
		return [];
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
		if (is_null($proposedValue)) {
			return [];
		} else {
			if (!is_array($proposedValue)) {
				return [0 => [0 => $proposedValue]];
			}
		}

		return $proposedValue;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		try {
			$value = @unserialize($aCols[$sPrefix.'']);
			if ($value === false) {
				$value = @json_decode($aCols[$sPrefix.''], true);
				if (is_null($value)) {
					$value = false;
				}
			}
			if ($value === false) {
				$value = $this->MakeRealValue($aCols[$sPrefix.''], null);
			}
		} catch (Exception $e) {
			$value = $this->MakeRealValue($aCols[$sPrefix.''], null);
		}

		return $value;
	}

	public function GetSQLValues($value)
	{
		$aValues = [];
		try {
			$sSerializedValue = serialize($value);
		} catch (Exception $e) {
			$sSerializedValue = json_encode($value);
		}
		$aValues[$this->Get("sql")] = $sSerializedValue;

		return $aValues;
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if (!is_array($value)) {
			throw new CoreException('Expecting an array', ['found' => get_class($value)]);
		}
		if (count($value) == 0) {
			return "";
		}

		$sRes = "<TABLE class=\"listResults\">";
		$sRes .= "<TBODY>";
		foreach ($value as $iRow => $aRawData) {
			$sRes .= "<TR>";
			foreach ($aRawData as $iCol => $cell) {
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
		$sValue,
		$sSeparator = ',',
		$sTextQualifier = '"',
		$oHostObject = null,
		$bLocalize = true,
		$bConvertToPlainText = false
	) {
		// Not implemented
		return '';
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (!is_array($value) || count($value) == 0) {
			return "";
		}

		$sRes = "";
		foreach ($value as $iRow => $aRawData) {
			$sRes .= "<row>";
			foreach ($aRawData as $iCol => $cell) {
				$sCell = Str::pure2xml((string)$cell);
				$sRes .= "<cell icol=\"$iCol\">$sCell</cell>";
			}
			$sRes .= "</row>";
		}

		return $sRes;
	}
}
