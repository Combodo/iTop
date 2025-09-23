<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use DBObject;
use MetaModel;
use Str;

/**
 * The attribute dedicated to the finalclass automatic attribute
 *
 * @package     iTopORM
 */
class AttributeFinalClass extends AttributeString
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;
	public $m_sValue;

	public function __construct($sCode, $aParams)
	{
		$this->m_sCode = $sCode;
		$aParams["allowed_values"] = null;
		parent::__construct($sCode, $aParams);

		$this->m_sValue = $this->Get("default_value");
	}

	public function IsWritable()
	{
		return false;
	}

	public function IsMagic()
	{
		return true;
	}

	public function RequiresIndex()
	{
		return true;
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
		if (empty($sValue)) {
			return '';
		}
		if ($bLocalize) {
			return MetaModel::GetName($sValue);
		} else {
			return $sValue;
		}
	}

	/**
	 * An enum can be localized
	 *
	 * @param string $sProposedValue
	 * @param bool $bLocalizedValue
	 * @param string $sSepItem
	 * @param string $sSepAttribute
	 * @param string $sSepValue
	 * @param string $sAttributeQualifier
	 *
	 * @return mixed|null|string
	 * @throws CoreException
	 * @throws OQLException
	 */
	public function MakeValueFromString(
		$sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null,
		$sAttributeQualifier = null
	)
	{
		if ($bLocalizedValue) {
			// Lookup for the value matching the input
			//
			$sFoundValue = null;
			$aRawValues = self::GetAllowedValues();
			if (!is_null($aRawValues)) {
				foreach ($aRawValues as $sKey => $sValue) {
					if ($sProposedValue == $sValue) {
						$sFoundValue = $sKey;
						break;
					}
				}
			}
			if (is_null($sFoundValue)) {
				return null;
			}

			return $this->MakeRealValue($sFoundValue, null);
		} else {
			return parent::MakeValueFromString($sProposedValue, $bLocalizedValue, $sSepItem, $sSepAttribute, $sSepValue,
				$sAttributeQualifier);
		}
	}


	// Because this is sometimes used to get a localized/string version of an attribute...
	public function GetEditValue($sValue, $oHostObj = null)
	{
		if (empty($sValue)) {
			return '';
		}

		return MetaModel::GetName($sValue);
	}

	public function GetForJSON($value)
	{
		// JSON values are NOT localized
		return $value;
	}

	/**
	 * @param $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return string
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 */
	public function GetAsCSV(
		$value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	)
	{
		if ($bLocalize && $value != '') {
			$sRawValue = MetaModel::GetName($value);
		} else {
			$sRawValue = $value;
		}

		return parent::GetAsCSV($sRawValue, $sSeparator, $sTextQualifier, null, false, $bConvertToPlainText);
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (empty($value)) {
			return '';
		}
		if ($bLocalize) {
			$sRawValue = MetaModel::GetName($value);
		} else {
			$sRawValue = $value;
		}

		return Str::pure2xml($sRawValue);
	}

	public function GetBasicFilterLooseOperator()
	{
		return '=';
	}

	public function GetValueLabel($sValue)
	{
		if (empty($sValue)) {
			return '';
		}

		return MetaModel::GetName($sValue);
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$aRawValues = MetaModel::EnumChildClasses($this->GetHostClass(), ENUM_CHILD_CLASSES_ALL);
		$aLocalizedValues = array();
		foreach ($aRawValues as $sClass) {
			$aLocalizedValues[$sClass] = MetaModel::GetName($sClass);
		}

		return $aLocalizedValues;
	}

	/**
	 * @return bool
	 * @since 2.7.0 N°2272 OQL perf finalclass in all intermediary tables
	 */
	public function CopyOnAllTables()
	{
		$sClass = self::GetHostClass();
		if (MetaModel::IsLeafClass($sClass)) {
			// Leaf class, no finalclass
			return false;
		}

		return true;
	}
}