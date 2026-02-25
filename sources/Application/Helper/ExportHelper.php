<?php

namespace Combodo\iTop\Application\Helper;

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\InputWithLabel;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Dict;
use utils;

/**
 * Class
 * ExportHelper
 *
 * @internal
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @since 2.7.9 3.0.4 3.1.1 3.2.0
 * @package Combodo\iTop\Application\Helper
 */
class ExportHelper
{
	public const EXCEL_FORMULA_CHARACTERS = ['=', '＝', '+', '＋', '-', '－', '@', '＠', '|', "\t", "\n", "\r"];

	public static function GetAlertForExcelMaliciousInjection()
	{
		$sWikiUrl =  'https://www.itophub.io/wiki/page?id='.utils::GetItopVersionWikiSyntax().'%3Auser%3Alists#excel_export';
		$oAlert = AlertUIBlockFactory::MakeForWarning(Dict::S('UI:Bulk:Export:MaliciousInjection:Alert:Title'), Dict::Format('UI:Bulk:Export:MaliciousInjection:Alert:Message', $sWikiUrl), 'ibo-sanitize-excel-export--alert');
		$oAlert->EnableSaveCollapsibleState(true)
			->SetIsClosable(false);
		return $oAlert;
	}

	/**
	 * @return InputWithLabel
	 *
	 * @since 3.2.3
	 */
	public static function GetInputForSanitizeExcelExport(): UIBlock
	{

		$oSanitizeInput = InputUIBlockFactory::MakeForInputWithLabel(Dict::S('UI:Bulk:Export:MaliciousInjection:Input:Label'), 'sanitize_excel_export', 1, 'ibo-sanitize-excel-export--input', 'checkbox');
		$oSanitizeInput->GetInput()->SetIsChecked(true);
		$oSanitizeInput->SetBeforeInput(false);
		$oSanitizeInput->SetDescription(Dict::S('UI:Bulk:Export:MaliciousInjection:Input:Tooltip'));
		$oSanitizeInput->GetInput()->AddCSSClass('ibo-input-checkbox');

		return $oSanitizeInput;
	}

	/**
	 * @param string $sValue
	 * @return bool
	 *
	 * @since 3.2.3
	 */
	public static function IsValueFormulaCandidate(string $sValue): bool
	{
		// An empty value cannot be a formula, so we can skip all the checks in this case
		if ($sValue === '') {
			return false;
		}

		$bHasFormulaCandidate = false;
		$sFirstChar = mb_substr($sValue, 0, 1);
		$bHasFormulaCandidate |= in_array($sFirstChar, static::EXCEL_FORMULA_CHARACTERS, true);

		// If the string is less than 3 characters long, it cannot start with a url encoded formula character, so we can skip this check in this case
		if (mb_strlen($sValue) < 3) {
			return $bHasFormulaCandidate;
		}

		// Additionally, check if the first three character could be a formula character url encoded
		$sFirstThreeChars = mb_strtoupper(mb_substr($sValue, 0, 3));

		$aUrlEncodedFormulaCharacters = array_map('urlencode', static::EXCEL_FORMULA_CHARACTERS);
		$bHasFormulaCandidate |= in_array($sFirstThreeChars, $aUrlEncodedFormulaCharacters, true);

		return $bHasFormulaCandidate;
	}

	/**
	 * @param string $sField
	 * @param string $sTextQualifier
	 * @return string
	 *
	 * @since 3.2.3
	 */
	public static function SanitizeField(string $sField, string $sTextQualifier = ''): string
	{
		if ($sField === '') {
			return $sField;
		}

		$sQualifier = $sTextQualifier;
		if ($sQualifier !== '' && str_starts_with($sField, $sQualifier)) {
			$sAfterQualifier = substr($sField, strlen($sQualifier));
			if (self::IsValueFormulaCandidate($sAfterQualifier)) {
				return $sQualifier."'".$sAfterQualifier;
			}
		} elseif (self::IsValueFormulaCandidate($sField)) {
			return "'".$sField;
		}

		return $sField;
	}
}
