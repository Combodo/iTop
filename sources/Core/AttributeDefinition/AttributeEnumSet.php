<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use Dict;
use MetaModel;
use ormSet;
use ValueSetEnumPadded;

/**
 * @since 2.7.0 N°985
 */
class AttributeEnumSet extends AttributeSet
{
	public const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_TAG_SET;

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), ['possible_values', 'is_null_allowed', 'max_items']);
	}

	public function GetMaxSize()
	{
		$aRawValues = $this->GetRawPossibleValues();
		$iMaxItems = $this->GetMaxItems();
		$aLengths = [];
		foreach (array_keys($aRawValues) as $sKey) {
			$aLengths[] = strlen($sKey);
		}
		rsort($aLengths, SORT_NUMERIC);
		$iMaxSize = 2;
		for ($i = 0; $i < min($iMaxItems, count($aLengths)); $i++) {
			$iMaxSize += $aLengths[$i] + 1;
		}

		return max(255, $iMaxSize);
	}

	private function GetRawPossibleValues($aArgs = [], $sContains = '')
	{
		/** @var ValueSetEnumPadded $oValSetDef */
		$oValSetDef = $this->Get('possible_values');
		if (!$oValSetDef) {
			return [];
		}

		return $oValSetDef->GetValues($aArgs, $sContains);
	}

	public function GetPossibleValues($aArgs = [], $sContains = '')
	{
		$aRawValues = $this->GetRawPossibleValues($aArgs, $sContains);
		$aLocalizedValues = [];
		foreach ($aRawValues as $sKey => $sValue) {
			$aLocalizedValues[$sKey] = $this->GetValueLabel($sKey);
		}

		return $aLocalizedValues;
	}

	public function GetValueLabel($sValue)
	{
		if ($sValue instanceof ormSet) {
			$sValue = implode(', ', $sValue->GetValues());
		}

		$aValues = $this->GetRawPossibleValues();
		if (is_array($aValues) && is_string($sValue) && isset($aValues[$sValue])) {
			$sValue = $aValues[$sValue];
		}

		if (is_null($sValue)) {
			// Unless a specific label is defined for the null value of this enum, use a generic "undefined" label
			$sLabel = Dict::S(
				'Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue,
				Dict::S('Enum:Undefined')
			);
		} else {
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, null, true /*user lang*/);
			if (is_null($sLabel)) {
				// Browse the hierarchy again, accepting default (english) translations
				$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sValue, null, false);
				if (is_null($sLabel)) {
					$sDefault = trim(str_replace('_', ' ', $sValue));
					// Browse the hierarchy again, accepting default (english) translations
					$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/Value:'.$sDefault, $sDefault, false);
				}
			}
		}

		return $sLabel;
	}

	public function GetValueDescription($sValue)
	{
		if (is_null($sValue)) {
			// Unless a specific label is defined for the null value of this enum, use a generic "undefined" label
			$sDescription = Dict::S(
				'Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+',
				Dict::S('Enum:Undefined')
			);
		} else {
			$sDescription = Dict::S(
				'Class:'.$this->GetHostClass().'/Attribute:'.$this->GetCode().'/Value:'.$sValue.'+',
				'',
				true /* user language only */
			);
			if (strlen($sDescription) == 0) {
				$sParentClass = MetaModel::GetParentClass($this->m_sHostClass);
				if ($sParentClass) {
					if (MetaModel::IsValidAttCode($sParentClass, $this->m_sCode)) {
						$oAttDef = MetaModel::GetAttributeDef($sParentClass, $this->m_sCode);
						$sDescription = $oAttDef->GetValueDescription($sValue);
					}
				}
			}
		}

		return $sDescription;
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if ($bLocalize) {
			if ($value instanceof ormSet) {
				$sRes = $this->GenerateViewHtmlForValues($value->GetValues());
			} else {
				$sLabel = $this->GetValueLabel($value);
				$sDescription = $this->GetValueDescription($value);
				$sRes = "<span title=\"$sDescription\">".parent::GetAsHtml($sLabel)."</span>";
			}
		} else {
			$sRes = parent::GetAsHtml($value, $oHostObject, $bLocalize);
		}

		return $sRes;
	}

	/**
	 * @param ormSet $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return mixed|string
	 * @throws Exception
	 */
	public function GetAsCSV($value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true, $bConvertToPlainText = false)
	{
		$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
		if (is_object($value) && ($value instanceof ormSet)) {
			$aValues = $value->GetValues();
			if ($bLocalize) {
				$aLocalizedValues = [];
				foreach ($aValues as $sValue) {
					$aLocalizedValues[] = $this->GetValueLabel($sValue);
				}
				$aValues = $aLocalizedValues;
			}
			$sRes = implode($sSepItem, $aValues);
		} else {
			$sRes = '';
		}

		return "{$sTextQualifier}{$sRes}{$sTextQualifier}";
	}

	/**
	 * Get the value from a given string (plain text, CSV import)
	 *
	 * @param string $sProposedValue
	 * @param bool $bLocalizedValue
	 * @param string $sSepItem
	 * @param string $sSepAttribute
	 * @param string $sSepValue
	 * @param string $sAttributeQualifier
	 *
	 * @return mixed null if no match could be found
	 * @throws Exception
	 */
	public function MakeValueFromString($sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null, $sAttributeQualifier = null)
	{
		if ($bLocalizedValue) {
			// Lookup for the values matching the input
			//
			$aValues = $this->FromStringToArray($sProposedValue);
			$aFoundValues = [];
			$aRawValues = $this->GetPossibleValues();
			foreach ($aValues as $sValue) {
				$bFound = false;
				foreach ($aRawValues as $sCode => $sRawValue) {
					if ($sValue == $sRawValue) {
						$aFoundValues[] = $sCode;
						$bFound = true;
						break;
					}
				}
				if (!$bFound) {
					// Not found, break the import
					return null;
				}
			}

			return $this->MakeRealValue(implode(',', $aFoundValues), null);
		} else {
			return $this->MakeRealValue($sProposedValue, null, false);
		}
	}

	/**
	 * @param string $proposedValue Search string used for MATCHES
	 *
	 * @param string $sDefaultSepItem word separator to extract items
	 *
	 * @return array of EnumSet codes
	 * @throws Exception
	 */
	public function FromStringToArray($proposedValue, $sDefaultSepItem = ',')
	{
		$aValues = [];
		if (!empty($proposedValue)) {
			$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
			// convert also other separators
			if ($sSepItem !== $sDefaultSepItem) {
				$proposedValue = str_replace($sDefaultSepItem, $sSepItem, $proposedValue);
			}
			foreach (explode($sSepItem, $proposedValue) as $sCode) {
				$sValue = trim($sCode);
				if (strlen($sValue) > 2) {
					$sLabel = $this->GetValueLabel($sValue);
					$aValues[$sLabel] = $sValue;
				}
			}
		}

		return $aValues;
	}

	public function Equals($val1, $val2)
	{
		return $val1->Equals($val2);
	}
}
