<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CoreException;
use Exception;
use Str;

class AttributePropertySet extends AttributeTable
{
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
		return "PropertySet";
	}

	// Facilitate things: allow the user to Set the value from a string
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (!is_array($proposedValue)) {
			return ['?' => (string)$proposedValue];
		}

		return $proposedValue;
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
		foreach ($value as $sProperty => $sValue) {
			if ($sProperty == 'auth_pwd') {
				$sValue = '*****';
			}
			$sRes .= "<TR>";
			$sCell = str_replace("\n", "<br>\n", Str::pure2html(@(string)$sValue));
			$sRes .= "<TD class=\"label\">$sProperty</TD><TD>$sCell</TD>";
			$sRes .= "</TR>";
		}
		$sRes .= "</TBODY>";
		$sRes .= "</TABLE>";

		return $sRes;
	}

	public function GetAsCSV(
		$value,
		$sSeparator = ',',
		$sTextQualifier = '"',
		$oHostObject = null,
		$bLocalize = true,
		$bConvertToPlainText = false
	) {
		if (!is_array($value) || count($value) == 0) {
			return "";
		}

		$aRes = [];
		foreach ($value as $sProperty => $sValue) {
			if ($sProperty == 'auth_pwd') {
				$sValue = '*****';
			}
			$sFrom = [',', '='];
			$sTo = ['\,', '\='];
			$aRes[] = $sProperty.'='.str_replace($sFrom, $sTo, (string)$sValue);
		}
		$sRaw = implode(',', $aRes);

		$sFrom = ["\r\n", $sTextQualifier];
		$sTo = ["\n", $sTextQualifier.$sTextQualifier];
		$sEscaped = str_replace($sFrom, $sTo, $sRaw);

		return $sTextQualifier.$sEscaped.$sTextQualifier;
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (!is_array($value) || count($value) == 0) {
			return "";
		}

		$sRes = "";
		foreach ($value as $sProperty => $sValue) {
			if ($sProperty == 'auth_pwd') {
				$sValue = '*****';
			}
			$sRes .= "<property id=\"$sProperty\">";
			$sRes .= Str::pure2xml((string)$sValue);
			$sRes .= "</property>";
		}

		return $sRes;
	}
}
