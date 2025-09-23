<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CoreException;
use CoreUnexpectedValue;
use CoreWarning;
use DBObject;
use DBSearch;
use Dict;
use Exception;
use MetaModel;
use OQLException;
use ormTagSet;
use TagSetFieldData;
use utils;

/**
 * Multi value list of tags
 *
 * @see TagSetFieldData
 * @since 2.6.0 N°931 tag fields
 */
class AttributeTagSet extends AttributeSet
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_TAG_SET;

	public function __construct($sCode, array $aParams)
	{
		parent::__construct($sCode, $aParams);
		$this->aCSSClasses[] = 'attribute-tag-set';
	}

	public function GetEditClass()
	{
		return 'TagSet';
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array('tag_code_max_len'));
	}

	/**
	 * @param ormTagSet $oValue
	 *
	 * @param $aArgs
	 *
	 * @return string JSON to be used in the itop.tagset_widget JQuery widget
	 */
	public function GetJsonForWidget($oValue, $aArgs = array())
	{
		$aJson = array();

		// possible_values
		$aTagSetObjectData = $this->GetAllowedValues($aArgs);
		$aTagSetKeyValData = array();
		foreach ($aTagSetObjectData as $sTagCode => $sTagLabel) {
			$aTagSetKeyValData[] = [
				'code'  => $sTagCode,
				'label' => $sTagLabel,
			];
		}
		$aJson['possible_values'] = $aTagSetKeyValData;

		if (is_null($oValue)) {
			$aJson['partial_values'] = array();
			$aJson['orig_value'] = array();
			$aJson['added'] = array();
			$aJson['removed'] = array();
		} else {
			$aJson['orig_value'] = array_merge($oValue->GetValues(), $oValue->GetModified());
			$aJson['added'] = $oValue->GetAdded();
			$aJson['removed'] = $oValue->GetRemoved();

			if ($oValue->DisplayPartial()) {
				// For bulk updates
				$aJson['partial_values'] = $oValue->GetModified();
			} else {
				// For simple updates
				$aJson['partial_values'] = array();
			}
		}


		$iMaxTags = $this->GetMaxItems();
		$aJson['max_items_allowed'] = $iMaxTags;

		return json_encode($aJson);
	}

	public function FromStringToArray($proposedValue, $sDefaultSepItem = ',')
	{
		$aValues = array();
		if (!empty($proposedValue)) {
			foreach (explode(' ', $proposedValue) as $sCode) {
				$sValue = trim($sCode);
				$aValues[] = $sValue;
			}
		}

		return $aValues;
	}

	/**
	 * Extract all existing tags from a string and ignore bad tags
	 *
	 * @param $sValue
	 * @param bool $bNoLimit : don't apply the maximum tag limit
	 *
	 * @return ormTagSet
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 */
	public function GetExistingTagsFromString($sValue, $bNoLimit = false)
	{
		$aTagCodes = $this->FromStringToArray("$sValue");
		$sAttCode = $this->GetCode();
		$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $sAttCode);
		if ($bNoLimit) {
			$oTagSet = new ormTagSet($sClass, $sAttCode, 0);
		} else {
			$oTagSet = new ormTagSet($sClass, $sAttCode, $this->GetMaxItems());
		}
		$aGoodTags = array();
		foreach ($aTagCodes as $sTagCode) {
			if ($sTagCode === '') {
				continue;
			}
			if ($oTagSet->IsValidTag($sTagCode)) {
				$aGoodTags[] = $sTagCode;
				if (!$bNoLimit && (count($aGoodTags) === $this->GetMaxItems())) {
					// extra and bad tags are ignored
					break;
				}
			}
		}
		$oTagSet->SetValues($aGoodTags);

		return $oTagSet;
	}

	public function GetTagCodeMaxLength()
	{
		return $this->Get('tag_code_max_len');
	}

	public function GetEditValue($value, $oHostObj = null)
	{
		if (empty($value)) {
			return '';
		}
		if ($value instanceof ormTagSet) {
			$aValues = $value->GetValues();

			return implode(' ', $aValues);
		}

		return '';
	}

	public function GetMaxSize()
	{
		return max(255, ($this->GetMaxItems() * $this->GetTagCodeMaxLength()) + 1);
	}

	public function Equals($val1, $val2)
	{
		if (($val1 instanceof ormTagSet) && ($val2 instanceof ormTagSet)) {
			return $val1->Equals($val2);
		}

		return ($val1 == $val2);
	}

	public function GetAllowedValues($aArgs = array(), $sContains = '')
	{
		$sAttCode = $this->GetCode();
		$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $sAttCode);
		$aAllowedTags = TagSetFieldData::GetAllowedValues($sClass, $sAttCode);
		$aAllowedValues = array();
		foreach ($aAllowedTags as $oAllowedTag) {
			$aAllowedValues[$oAllowedTag->Get('code')] = $oAllowedTag->Get('label');
		}

		return $aAllowedValues;
	}

	/**
	 * @param array $aCols
	 * @param string $sPrefix
	 *
	 * @return mixed
	 * @throws CoreException
	 * @throws Exception
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$sValue = $aCols["$sPrefix"];

		return $this->GetExistingTagsFromString($sValue);
	}

	/**
	 * force an allowed value (type conversion and possibly forces a value as mySQL would do upon writing!
	 *
	 * @param $proposedValue
	 * @param $oHostObj
	 *
	 * @param bool $bIgnoreErrors
	 *
	 * @return mixed
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 */
	public function MakeRealValue($proposedValue, $oHostObj, $bIgnoreErrors = false)
	{
		$oTagSet = new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		if (is_string($proposedValue) && !empty($proposedValue)) {
			$sJsonFromWidget = json_decode($proposedValue, true);
			if (is_null($sJsonFromWidget)) {
				$proposedValue = trim("$proposedValue");
				$aTagCodes = $this->FromStringToArray($proposedValue);
				$oTagSet->SetValues($aTagCodes);
			}
		} elseif ($proposedValue instanceof ormTagSet) {
			$oTagSet = $proposedValue;
		}

		return $oTagSet;
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
		if (is_null($sSepItem) || empty($sSepItem)) {
			$sSepItem = MetaModel::GetConfig()->Get('tag_set_item_separator');
		}
		if (!empty($sProposedValue)) {
			$oTagSet = new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()),
				$this->GetCode(), $this->GetMaxItems());
			$aLabels = explode($sSepItem, $sProposedValue);
			$aCodes = array();
			foreach ($aLabels as $sTagLabel) {
				if (!empty($sTagLabel)) {
					$aCodes[] = ($bLocalizedValue) ? $oTagSet->GetTagFromLabel($sTagLabel) : $sTagLabel;
				}
			}
			$sProposedValue = implode(' ', $aCodes);
		}

		return $this->MakeRealValue($sProposedValue, null);
	}

	public function GetNullValue()
	{
		return new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		$oTagSet = new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode(), $this->GetMaxItems());
		$oTagSet->SetValues([]);

		return $oTagSet;
	}

	public function IsNull($proposedValue)
	{
		if (is_null($proposedValue)) {
			return true;
		}

		/** @var ormTagSet $proposedValue */
		return count($proposedValue->GetValues()) == 0;
	}

	/**
	 * To be overloaded for localized enums
	 *
	 * @param $sValue
	 *
	 * @return string label corresponding to the given value (in plain text)
	 * @throws CoreWarning
	 * @throws Exception
	 */
	public function GetValueLabel($sValue)
	{
		if (empty($sValue)) {
			return '';
		}
		if (is_string($sValue)) {
			$sValue = $this->GetExistingTagsFromString($sValue);
		}
		if ($sValue instanceof ormTagSet) {
			$aValues = $sValue->GetLabels();

			return implode(', ', $aValues);
		}
		throw new CoreWarning('Expected the attribute value to be a TagSet', array(
			'found_type' => gettype($sValue),
			'value'      => $sValue,
			'class'      => $this->GetHostClass(),
			'attribute'  => $this->GetCode(),
		));
	}

	/**
	 * @param $value
	 *
	 * @return string
	 * @throws CoreWarning
	 */
	public function ScalarToSQL($value)
	{
		if (empty($value)) {
			return '';
		}
		if ($value instanceof ormTagSet) {
			$aValues = $value->GetValues();

			return implode(' ', $aValues);
		}
		throw new CoreWarning('Expected the attribute value to be a TagSet', array(
			'found_type' => gettype($value),
			'value'      => $value,
			'class'      => $this->GetHostClass(),
			'attribute'  => $this->GetCode(),
		));
	}

	/**
	 * @param $value
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string|null
	 *
	 * @throws CoreException
	 * @throws Exception
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if ($value instanceof ormTagSet) {
			if ($bLocalize) {
				$aValues = $value->GetTags();
			} else {
				$aValues = $value->GetValues();
			}
			if (empty($aValues)) {
				return '';
			}

			return $this->GenerateViewHtmlForValues($aValues);
		}
		if (is_string($value)) {
			try {
				$oValue = $this->MakeRealValue($value, $oHostObject);

				return $this->GetAsHTML($oValue, $oHostObject, $bLocalize);
			}
			catch (Exception $e) {
				// unknown tags are present display the code instead
			}
			$aTagCodes = $this->FromStringToArray($value);
			$aValues = array();
			$oTagSet = new ormTagSet(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()),
				$this->GetCode(), $this->GetMaxItems());
			foreach ($aTagCodes as $sTagCode) {
				try {
					$oTagSet->Add($sTagCode);
				}
				catch (Exception $e) {
					$aValues[] = $sTagCode;
				}
			}
			$sHTML = '';
			if (!empty($aValues)) {
				$sHTML .= $this->GenerateViewHtmlForValues($aValues, 'attribute-set-item-undefined');
			}
			$aValues = $oTagSet->GetTags();
			if (!empty($aValues)) {
				$sHTML .= $this->GenerateViewHtmlForValues($aValues);
			}

			return $sHTML;
		}

		return parent::GetAsHTML($value, $oHostObject, $bLocalize);
	}

	// Do not display friendly names in the history of change
	public function DescribeChangeAsHTML($sOldValue, $sNewValue, $sLabel = null)
	{
		$sResult = Dict::Format('Change:AttName_Changed', $this->GetLabel()).", ";

		$aNewValues = $this->FromStringToArray($sNewValue);
		$aOldValues = $this->FromStringToArray($sOldValue);

		$aDelta['removed'] = array_diff($aOldValues, $aNewValues);
		$aDelta['added'] = array_diff($aNewValues, $aOldValues);

		$aAllowedTags = TagSetFieldData::GetAllowedValues(MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode()), $this->GetCode());

		if (!empty($aDelta['removed'])) {
			$aRemoved = array();
			foreach ($aDelta['removed'] as $idx => $sTagCode) {
				if (empty($sTagCode)) {
					continue;
				}
				$sTagLabel = $sTagCode;
				foreach ($aAllowedTags as $oTag) {
					if ($sTagCode === $oTag->Get('code')) {
						$sTagLabel = $oTag->Get('label');
					}
				}
				$aRemoved[] = $sTagLabel;
			}

			$sRemoved = $this->GenerateViewHtmlForValues($aRemoved, 'history-removed');
			if (!empty($sRemoved)) {
				$sResult .= Dict::Format('Change:LinkSet:Removed', $sRemoved);
			}
		}

		if (!empty($aDelta['added'])) {
			if (!empty($sRemoved)) {
				$sResult .= ', ';
			}

			$aAdded = array();
			foreach ($aDelta['added'] as $idx => $sTagCode) {
				if (empty($sTagCode)) {
					continue;
				}
				$sTagLabel = $sTagCode;
				foreach ($aAllowedTags as $oTag) {
					if ($sTagCode === $oTag->Get('code')) {
						$sTagLabel = $oTag->Get('label');
					}
				}
				$aAdded[] = $sTagLabel;
			}

			$sAdded = $this->GenerateViewHtmlForValues($aAdded, 'history-added');
			if (!empty($sAdded)) {
				$sResult .= Dict::Format('Change:LinkSet:Added', $sAdded);
			}
		}

		return $sResult;
	}

	/**
	 * HTML representation of a list of tags (read-only)
	 * accept a list of strings or a list of TagSetFieldData
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
		foreach ($aValues as $oTag) {
			if ($oTag instanceof TagSetFieldData) {
				$sClass = MetaModel::GetAttributeOrigin($this->GetHostClass(), $this->GetCode());
				$sAttCode = $this->GetCode();
				$sTagCode = $oTag->Get('code');
				$sTagLabel = $oTag->Get('label');
				$sTagDescription = $oTag->Get('description');
				$oFilter = DBSearch::FromOQL("SELECT $sClass WHERE $sAttCode MATCHES '$sTagCode'");
				$oAppContext = new ApplicationContext();
				$sContext = $oAppContext->GetForLink(true);
				$sUIPage = cmdbAbstractObject::ComputeStandardUIPage($oFilter->GetClass());
				$sFilter = rawurlencode($oFilter->serialize());

				$sLink = '';
				if ($bWithLink && $this->bDisplayLink) {
					$sUrl = utils::GetAbsoluteUrlAppRoot()."pages/$sUIPage?operation=search&filter=".$sFilter.$sContext;
					$sLink = ' href="'.$sUrl.'"';
				}

				$sLabelForHtml = utils::EscapeHtml($sTagLabel);
				$sDescriptionForHtml = utils::EscapeHtml($sTagDescription);
				if (empty($sTagDescription)) {
					$sTooltipContent = $sTagLabel;
					$sTooltipHtmlEnabled = 'false';
				} else {
					$sTagLabelEscaped = utils::EscapeHtml($sTagLabel);
					$sTooltipContent = <<<HTML
<h4>$sTagLabelEscaped</h4>
<div>$sTagDescription</div>
HTML;
					$sTooltipHtmlEnabled = 'true';
				}
				$sTooltipContent = utils::HtmlEntities($sTooltipContent);

				$sHtml .= '<a'.$sLink.' class="attribute-set-item attribute-set-item-'.$sTagCode.'" data-code="'.$sTagCode.'" data-label="'.$sLabelForHtml.'" data-description="'.$sDescriptionForHtml.'" data-tooltip-content="'.$sTooltipContent.'" data-tooltip-html-enabled="'.$sTooltipHtmlEnabled.'">'.$sLabelForHtml.'</a>';
			} else {
				$sHtml .= '<span class="attribute-set-item">'.utils::EscapeHtml($oTag).'</span>';
			}
		}
		$sHtml .= '</span>';

		return $sHtml;
	}

	/**
	 * @param $value
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 *
	 */
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value) && ($value instanceof ormTagSet)) {
			$sRes = "<Set>\n";
			if ($bLocalize) {
				$aValues = $value->GetLabels();
			} else {
				$aValues = $value->GetValues();
			}
			if (!empty($aValues)) {
				$sRes .= '<Tag>'.implode('</Tag><Tag>', $aValues).'</Tag>';
			}
			$sRes .= "</Set>\n";
		} else {
			$sRes = '';
		}

		return $sRes;
	}

	/**
	 * List the available verbs for 'GetForTemplate'
	 */
	public function EnumTemplateVerbs()
	{
		return array(
			''     => 'Plain text representation',
			'html' => 'HTML representation (unordered list)',
		);
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 *
	 * @param mixed $value The current value of the field
	 * @param string $sVerb The verb specifying the representation of the value
	 * @param DBObject $oHostObject The object
	 * @param bool $bLocalize Whether or not to localize the value
	 *
	 * @return string
	 * @throws Exception
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value) && ($value instanceof ormTagSet)) {
			if ($bLocalize) {
				$aValues = $value->GetLabels();
				$sSep = ', ';
			} else {
				$aValues = $value->GetValues();
				$sSep = ' ';
			}

			switch ($sVerb) {
				case '':
					return implode($sSep, $aValues);

				case 'html':
					return '<ul><li>'.implode("</li><li>", $aValues).'</li></ul>';

				default:
					throw new Exception("Unknown verb '$sVerb' for attribute ".$this->GetCode().' in class '.get_class($oHostObject));
			}
		}
		throw new CoreUnexpectedValue("Bad value '$value' for attribute ".$this->GetCode().' in class '.get_class($oHostObject));
	}

	/**
	 * @inheritDoc
	 *
	 * @param ormTagSet $value
	 *
	 * @return array
	 */
	public function GetForJSON($value)
	{
		$aRet = array();
		if (is_object($value) && ($value instanceof ormTagSet)) {
			$aRet = $value->GetValues();
		}

		return $aRet;
	}

	/**
	 * @inheritDoc
	 *
	 * @return ormTagSet
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws Exception
	 */
	public function FromJSONToValue($json)
	{
		$oSet = new ormTagSet($this->GetHostClass(), $this->GetCode(), $this->GetMaxItems());
		$oSet->SetValues($json);

		return $oSet;
	}

	/**
	 * The part of the current attribute in the object's signature, for the supplied value
	 *
	 * @param mixed $value The value of this attribute for the object
	 *
	 * @return string The "signature" for this field/attribute
	 */
	public function Fingerprint($value)
	{
		if ($value instanceof ormTagSet) {
			$aValues = $value->GetValues();

			return implode(' ', $aValues);
		}

		return parent::Fingerprint($value);
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\TagSetField';
	}
}