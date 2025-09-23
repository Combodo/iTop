<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use ApplicationContext;
use cmdbAbstractObject;
use CMDBChangeOpSetAttributeTagSet;
use CMDBSource;
use CoreException;
use CoreUnexpectedValue;
use DBObject;
use DBSearch;
use Exception;
use MetaModel;
use ormSet;
use utils;

/**
 * An unordered multi values attribute
 * Allowed values are mandatory for this attribute to be modified
 *
 * Class AttributeSet
 */
abstract class AttributeSet extends AttributeDBFieldVoid
{
	const SEARCH_WIDGET_TYPE       = self::SEARCH_WIDGET_TYPE_RAW;
	const EDITABLE_INPUT_ID_SUFFIX = '-setwidget-values'; // used client side, see js/jquery.itop-set-widget.js
	protected $bDisplayLink; // Display search link in readonly mode

	public function __construct($sCode, array $aParams)
	{
		parent::__construct($sCode, $aParams);
		$this->aCSSClasses[] = 'attribute-set';
		$this->bDisplayLink = true;
	}

	/**
	 * @param bool $bDisplayLink
	 */
	public function setDisplayLink($bDisplayLink)
	{
		$this->bDisplayLink = $bDisplayLink;
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('is_null_allowed', 'max_items'));
	}

	/**
	 * Allowed different values for the set values are mandatory for this attribute to be modified
	 *
	 * @param array $aArgs
	 * @param string $sContains
	 *
	 * @return array|null
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function GetPossibleValues($aArgs = array(), $sContains = '')
	{
		return $this->GetAllowedValues($aArgs, $sContains);
	}

	/**
	 * @param \ormSet $oValue
	 *
	 * @param $aArgs
	 *
	 * @return string JSON to be used in the itop.set_widget JQuery widget
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function GetJsonForWidget($oValue, $aArgs = array())
	{
		$aJson = array();

		// possible_values
		$aAllowedValues = $this->GetPossibleValues($aArgs);
		$aSetKeyValData = array();
		foreach ($aAllowedValues as $sCode => $sLabel) {
			$aSetKeyValData[] = [
				'code'  => $sCode,
				'label' => $sLabel,
			];
		}
		$aJson['possible_values'] = $aSetKeyValData;
		$aRemoved = array();
		if (is_null($oValue)) {
			$aJson['partial_values'] = array();
			$aJson['orig_value'] = array();
		} else {
			$aPartialValues = $oValue->GetModified();
			foreach ($aPartialValues as $key => $value) {
				if (!isset($aAllowedValues[$value])) {
					unset($aPartialValues[$key]);
				}
			}
			$aJson['partial_values'] = array_values($aPartialValues);
			$aOrigValues = array_merge($oValue->GetValues(), $oValue->GetModified());
			foreach ($aOrigValues as $key => $value) {
				if (!isset($aAllowedValues[$value])) {
					// Remove unwanted values
					$aRemoved[] = $value;
					unset($aOrigValues[$key]);
				}
			}
			$aJson['orig_value'] = array_values($aOrigValues);
		}
		$aJson['added'] = array();
		$aJson['removed'] = $aRemoved;

		$iMaxTags = $this->GetMaxItems();
		$aJson['max_items_allowed'] = $iMaxTags;

		return json_encode($aJson);
	}

	public function RequiresIndex()
	{
		return true;
	}

	public function RequiresFullTextIndex()
	{
		return true;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return null;
	}

	public function IsNullAllowed()
	{
		return $this->Get("is_null_allowed");
	}

	public function GetEditClass()
	{
		return "Set";
	}

	public function GetEditValue($value, $oHostObj = null)
	{
		if (is_string($value)) {
			return $value;
		}
		if ($value instanceof ormSet) {
			$value = $value->GetValues();
		}
		if (is_array($value)) {
			return implode(', ', $value);
		}

		return '';
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		$iLen = $this->GetMaxSize();

		return "VARCHAR($iLen)"
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}

	public function GetMaxSize()
	{
		return 255;
	}

	public function FromStringToArray($proposedValue, $sDefaultSepItem = ',')
	{
		$aValues = array();
		if (!empty($proposedValue)) {
			$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
			// convert also , separated strings
			if ($sSepItem !== $sDefaultSepItem) {
				$proposedValue = str_replace($sDefaultSepItem, $sSepItem, $proposedValue);
			}
			foreach (explode($sSepItem, $proposedValue) as $sCode) {
				$sValue = trim($sCode);
				if ($sValue !== '') {
					$aValues[] = $sValue;
				}
			}
		}

		return $aValues;
	}

	/**
	 * @param array $aCols
	 * @param string $sPrefix
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$sValue = $aCols["$sPrefix"];

		return $this->MakeRealValue($sValue, null, true);
	}

	/**
	 * force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!
	 *
	 * @param $proposedValue
	 * @param DBObject $oHostObj
	 *
	 * @param bool $bIgnoreErrors
	 *
	 * @return mixed
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 */
	public function MakeRealValue($proposedValue, $oHostObj, $bIgnoreErrors = false)
	{
		$oSet = new ormSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		$aAllowedValues = $this->GetPossibleValues();
		if (is_string($proposedValue) && !empty($proposedValue)) {
			$proposedValue = trim("$proposedValue");
			$aValues = $this->FromStringToArray($proposedValue);
			foreach ($aValues as $i => $sValue) {
				if (!isset($aAllowedValues[$sValue])) {
					unset($aValues[$i]);
				}
			}
			$oSet->SetValues($aValues);
		} elseif ($proposedValue instanceof ormSet) {
			$oSet = $proposedValue;
		}

		return $oSet;
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
		return $this->MakeRealValue($sProposedValue, null);
	}

	/**
	 * @return null|ormSet
	 * @throws CoreException
	 * @throws Exception
	 */
	public function GetNullValue()
	{
		return new ormSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
	}

	public function IsNull($proposedValue)
	{
		if (empty($proposedValue)) {
			return true;
		}

		/** @var ormSet $proposedValue */
		return $proposedValue->Count() == 0;
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		if (false === ($proposedValue instanceof ormSet)) {
			return parent::HasAValue($proposedValue);
		}

		return $proposedValue->Count() > 0;
	}

	/**
	 * To be overloaded for localized enums
	 *
	 * @param $sValue
	 *
	 * @return string label corresponding to the given value (in plain text)
	 * @throws Exception
	 */
	public function GetValueLabel($sValue)
	{
		if ($sValue instanceof ormSet) {
			$sValue = $sValue->GetValues();
		}
		if (is_array($sValue)) {
			return implode(', ', $sValue);
		}

		return $sValue;
	}

	/**
	 * @param string $sValue
	 * @param null $oHostObj
	 *
	 * @return string
	 * @throws Exception
	 */
	public function GetAsPlainText($sValue, $oHostObj = null)
	{
		return $this->GetValueLabel($sValue);
	}

	/**
	 * @param string $value
	 *
	 * @return string
	 */
	public function ScalarToSQL($value)
	{
		if (empty($value)) {
			return '';
		}
		if ($value instanceof ormSet) {
			$value = $value->GetValues();
		}
		if (is_array($value)) {
			$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
			$sRes = implode($sSepItem, $value);
			if (!empty($sRes)) {
				$value = "{$sSepItem}{$sRes}{$sSepItem}";
			} else {
				$value = '';
			}
		}

		return $value;
	}

	/**
	 * @param $value
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string|null
	 *
	 * @throws \Exception
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if ($value instanceof ormSet) {
			$aValues = $value->GetValues();

			return $this->GenerateViewHtmlForValues($aValues);
		}
		if (is_array($value)) {
			return implode(', ', $value);
		}

		return $value;
	}

	/**
	 * HTML representation of a list of values (read-only)
	 * accept a list of strings
	 *
	 * @param array $aValues
	 * @param string $sCssClass
	 * @param bool $bWithLink if true will generate a link, otherwise just a "a" tag without href
	 *
	 * @return string
	 * @throws CoreException
	 * @throws OQLException
	 */
	public function GenerateViewHtmlForValues($aValues, $sCssClass = '', $bWithLink = true)
	{
		if (empty($aValues)) {
			return '';
		}
		$sHtml = '<span class="'.$sCssClass.' '.implode(' ', $this->aCSSClasses).'">';
		foreach ($aValues as $sValue) {
			$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode());
			$sAttCode = $this->GetCode();
			$sLabel = utils::EscapeHtml($this->GetValueLabel($sValue));
			$sDescription = utils::EscapeHtml($this->GetValueDescription($sValue));
			$oFilter = DBSearch::FromOQL("SELECT $sClass WHERE $sAttCode MATCHES '$sValue'");
			$oAppContext = new ApplicationContext();
			$sContext = $oAppContext->GetForLink(true);
			$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($oFilter->GetClass());
			$sFilter = rawurlencode($oFilter->serialize());
			$sLink = '';
			if ($bWithLink && $this->bDisplayLink) {
				$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/$sUIPage?operation=search&filter=".$sFilter.$sContext;
				$sLink = ' href="'.$sUrl.'"';
			}

			// Prepare tooltip
			if (empty($sDescription)) {
				$sTooltipContent = $sLabel;
				$sTooltipHtmlEnabled = 'false';
			} else {
				$sTooltipContent = <<<HTML
<h4>$sLabel</h4>
<div>$sDescription</div>
HTML;
				$sTooltipHtmlEnabled = 'true';
			}
			$sTooltipContent = utils::EscapeHtml($sTooltipContent);

			$sHtml .= '<a'.$sLink.' class="attribute-set-item attribute-set-item-'.$sValue.'" data-code="'.$sValue.'" data-label="'.$sLabel.'" data-description="'.$sDescription.'" data-tooltip-content="'.$sTooltipContent.'" data-tooltip-html-enabled="'.$sTooltipHtmlEnabled.'">'.$sLabel.'</a>';
		}
		$sHtml .= '</span>';

		return $sHtml;
	}

	/**
	 * @param $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return mixed|string
	 */
	public function GetAsCSV($value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true, $bConvertToPlainText = false)
	{
		$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
		if (is_object($value) && ($value instanceof ormSet)) {
			if ($bLocalize) {
				$aValues = $value->GetLabels();
			} else {
				$aValues = $value->GetValues();
			}
			$sRes = implode($sSepItem, $aValues);
		} else {
			$sRes = '';
		}

		return "{$sTextQualifier}{$sRes}{$sTextQualifier}";
	}

	public function GetMaxItems()
	{
		return $this->Get('max_items');
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\SetField';
	}

	public function RecordAttChange(DBObject $oObject, $original, $value): void
	{
		/** @var \ormSet $original */
		/** @var \ormSet $value */
		parent::RecordAttChange($oObject,
			implode(' ', $original->GetValues()),
			implode(' ', $value->GetValues())
		);
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeTagSet::class;
	}
}