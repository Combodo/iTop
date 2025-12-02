<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBChangeOpSetAttributeScalar;
use Combodo\iTop\Form\Field\SelectField;
use DBObject;
use Dict;

/**
 * Map a boolean column to an attribute
 *
 * @package     iTopORM
 */
class AttributeBoolean extends AttributeInteger
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

	public static function ListExpectedParams()
	{
		return parent::ListExpectedParams();
		//return array_merge(parent::ListExpectedParams(), array());
	}

	public function GetEditClass()
	{
		return "Integer";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "TINYINT(1)".($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) {
			return null;
		}
		if ($proposedValue === '') {
			return null;
		}
		if ((int)$proposedValue) {
			return true;
		}

		return false;
	}

	public function ScalarToSQL($value)
	{
		if ($value) {
			return 1;
		}

		return 0;
	}

	public function GetValueLabel($bValue)
	{
		if (is_null($bValue)) {
			$sLabel = Dict::S('Core:'.get_class($this).'/Value:null');
		} else {
			$sValue = $bValue ? 'yes' : 'no';
			$sDefault = Dict::S('Core:'.get_class($this).'/Value:'.$sValue);
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, $sDefault, true /*user lang*/);
		}

		return $sLabel;
	}

	public function GetValueDescription($bValue)
	{
		if (is_null($bValue)) {
			$sDescription = Dict::S('Core:'.get_class($this).'/Value:null+');
		} else {
			$sValue = $bValue ? 'yes' : 'no';
			$sDefault = Dict::S('Core:'.get_class($this).'/Value:'.$sValue.'+');
			$sDescription = $this->SearchLabel(
				'/Attribute:'.$this->m_sCode.'/Value:'.$sValue.'+',
				$sDefault,
				true /*user lang*/
			);
		}

		return $sDescription;
	}

	public function GetAsHTML($bValue, $oHostObject = null, $bLocalize = true)
	{
		if (is_null($bValue)) {
			$sRes = '';
		} elseif ($bLocalize) {
			$sLabel = $this->GetValueLabel($bValue);
			$sDescription = $this->GetValueDescription($bValue);
			// later, we could imagine a detailed description in the title
			$sRes = "<span title=\"$sDescription\">".parent::GetAsHtml($sLabel)."</span>";
		} else {
			$sRes = $bValue ? 'yes' : 'no';
		}

		return $sRes;
	}

	public function GetAsXML($bValue, $oHostObject = null, $bLocalize = true)
	{
		if (is_null($bValue)) {
			$sFinalValue = '';
		} elseif ($bLocalize) {
			$sFinalValue = $this->GetValueLabel($bValue);
		} else {
			$sFinalValue = $bValue ? 'yes' : 'no';
		}
		$sRes = parent::GetAsXML($sFinalValue, $oHostObject, $bLocalize);

		return $sRes;
	}

	public function GetAsCSV(
		$bValue,
		$sSeparator = ',',
		$sTextQualifier = '"',
		$oHostObject = null,
		$bLocalize = true,
		$bConvertToPlainText = false
	) {
		if (is_null($bValue)) {
			$sFinalValue = '';
		} elseif ($bLocalize) {
			$sFinalValue = $this->GetValueLabel($bValue);
		} else {
			$sFinalValue = $bValue ? 'yes' : 'no';
		}
		$sRes = parent::GetAsCSV($sFinalValue, $sSeparator, $sTextQualifier, $oHostObject, $bLocalize);

		return $sRes;
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\SelectField';
	}

	/**
	 * @param DBObject $oObject
	 * @param SelectField $oFormField
	 *
	 * @return SelectField
	 * @throws CoreException
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		$oFormField->SetChoices(['yes' => $this->GetValueLabel(true), 'no' => $this->GetValueLabel(false)]);
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	public function GetEditValue($value, $oHostObj = null)
	{
		if (is_null($value)) {
			return '';
		} else {
			return $this->GetValueLabel($value);
		}
	}

	public function GetForJSON($value)
	{
		return (bool)$value;
	}

	public function MakeValueFromString(
		$sProposedValue,
		$bLocalizedValue = false,
		$sSepItem = null,
		$sSepAttribute = null,
		$sSepValue = null,
		$sAttributeQualifier = null
	) {
		$sInput = mb_strtolower(trim($sProposedValue));
		if ($bLocalizedValue) {
			switch ($sInput) {
				case '1': // backward compatibility
				case $this->GetValueLabel(true):
					$value = true;
					break;
				case '0': // backward compatibility
				case 'no':
				case $this->GetValueLabel(false):
					$value = false;
					break;
				default:
					$value = null;
			}
		} else {
			switch ($sInput) {
				case '1': // backward compatibility
				case 'yes':
					$value = true;
					break;
				case '0': // backward compatibility
				case 'no':
					$value = false;
					break;
				default:
					$value = null;
			}
		}

		return $value;
	}

	public function RecordAttChange(DBObject $oObject, $original, $value): void
	{
		parent::RecordAttChange($oObject, $original ? 1 : 0, $value ? 1 : 0);
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeScalar::class;
	}

	public function GetAllowedValues($aArgs = [], $sContains = ''): array
	{
		return [
			0 => $this->GetValueLabel(false),
			1 => $this->GetValueLabel(true),
		];
	}

	public function GetDisplayStyle()
	{
		return $this->GetOptional('display_style', 'select');
	}
}
