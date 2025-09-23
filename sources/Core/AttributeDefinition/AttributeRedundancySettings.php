<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use Combodo\iTop\Application\WebPage\WebPage;
use CoreException;
use DBObject;
use DictExceptionMissingString;
use Exception;
use MetaModel;
use utils;

/**
 * Holds the setting for the redundancy on a specific relation
 * Its value is a string, containing either:
 * - 'disabled'
 * - 'n', where n is a positive integer value giving the minimum count of items upstream
 * - 'n%', where n is a positive integer value, giving the minimum as a percentage of the total count of items upstream
 *
 * @package     iTopORM
 */
class AttributeRedundancySettings extends AttributeDBField
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
		return array(
			'sql',
			'relation_code',
			'from_class',
			'neighbour_id',
			'enabled',
			'enabled_mode',
			'min_up',
			'min_up_type',
			'min_up_mode',
		);
	}

	public function GetValuesDef()
	{
		return null;
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		return array();
	}

	public function GetEditClass()
	{
		return "RedundancySetting";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "VARCHAR(20)"
			.CMDBSource::GetSqlStringColumnDefinition()
			.($bFullSpec ? $this->GetSQLColSpec() : '');
	}


	public function GetValidationPattern()
	{
		return "^[0-9]{1,3}|[0-9]{1,2}%|disabled$";
	}

	public function GetMaxSize()
	{
		return 20;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		$sRet = 'disabled';
		if ($this->Get('enabled')) {
			if ($this->Get('min_up_type') == 'count') {
				$sRet = (string)$this->Get('min_up');
			} else // percent
			{
				$sRet = $this->Get('min_up').'%';
			}
		}

		return $sRet;
	}

	public function IsNullAllowed()
	{
		return false;
	}

	public function GetNullValue()
	{
		return '';
	}

	public function IsNull($proposedValue)
	{
		return ($proposedValue == '');
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (is_null($proposedValue)) {
			return '';
		}

		return (string)$proposedValue;
	}

	public function ScalarToSQL($value)
	{
		if (!is_string($value)) {
			throw new CoreException('Expected the attribute value to be a string', array(
				'found_type' => gettype($value),
				'value'      => $value,
				'class'      => $this->GetHostClass(),
				'attribute'  => $this->GetCode(),
			));
		}

		return $value;
	}

	public function GetRelationQueryData()
	{
		foreach (MetaModel::EnumRelationQueries($this->GetHostClass(), $this->Get('relation_code'),
			false) as $sDummy => $aQueryInfo) {
			if ($aQueryInfo['sFromClass'] == $this->Get('from_class')) {
				if ($aQueryInfo['sNeighbour'] == $this->Get('neighbour_id')) {
					return $aQueryInfo;
				}
			}
		}

		return array();
	}

	/**
	 * Find the user option label
	 *
	 * @param string $sUserOption possible values : disabled|cout|percent
	 * @param string $sDefault
	 *
	 * @return string
	 * @throws Exception
	 */
	public function GetUserOptionFormat($sUserOption, $sDefault = null)
	{
		$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/'.$sUserOption, null, true /*user lang*/);
		if (is_null($sLabel)) {
			// If no default value is specified, let's define the most relevant one for developping purposes
			if (is_null($sDefault)) {
				$sDefault = str_replace('_', ' ', $this->m_sCode.':'.$sUserOption.'(%1$s)');
			}
			// Browse the hierarchy again, accepting default (english) translations
			$sLabel = $this->SearchLabel('/Attribute:'.$this->m_sCode.'/'.$sUserOption, $sDefault, false);
		}

		return $sLabel;
	}

	/**
	 * Override to display the value in the GUI
	 *
	 * @param string $sValue
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 */
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$sCurrentOption = $this->GetCurrentOption($sValue);
		$sClass = $oHostObject ? get_class($oHostObject) : $this->m_sHostClass;

		return sprintf($this->GetUserOptionFormat($sCurrentOption), $this->GetMinUpValue($sValue),
			MetaModel::GetName($sClass));
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

	/**
	 * Helper to interpret the value, given the current settings and string representation of the attribute
	 */
	public function IsEnabled($sValue)
	{
		if ($this->get('enabled_mode') == 'fixed') {
			$bRet = $this->get('enabled');
		} else {
			$bRet = ($sValue != 'disabled');
		}

		return $bRet;
	}

	/**
	 * Helper to interpret the value, given the current settings and string representation of the attribute
	 */
	public function GetMinUpType($sValue)
	{
		if ($this->get('min_up_mode') == 'fixed') {
			$sRet = $this->get('min_up_type');
		} else {
			$sRet = 'count';
			if (substr(trim($sValue), -1, 1) == '%') {
				$sRet = 'percent';
			}
		}

		return $sRet;
	}

	/**
	 * Helper to interpret the value, given the current settings and string representation of the attribute
	 */
	public function GetMinUpValue($sValue)
	{
		if ($this->get('min_up_mode') == 'fixed') {
			$iRet = (int)$this->Get('min_up');
		} else {
			$sRefValue = $sValue;
			if (substr(trim($sValue), -1, 1) == '%') {
				$sRefValue = substr(trim($sValue), 0, -1);
			}
			$iRet = (int)trim($sRefValue);
		}

		return $iRet;
	}

	/**
	 * Helper to determine if the redundancy can be viewed/edited by the end-user
	 */
	public function IsVisible()
	{
		$bRet = false;
		if ($this->Get('enabled_mode') == 'fixed') {
			$bRet = $this->Get('enabled');
		} elseif ($this->Get('enabled_mode') == 'user') {
			$bRet = true;
		}

		return $bRet;
	}

	public function IsWritable()
	{
		if (($this->Get('enabled_mode') == 'fixed') && ($this->Get('min_up_mode') == 'fixed')) {
			return false;
		}

		return true;
	}

	/**
	 * Returns an HTML form that can be read by ReadValueFromPostedForm
	 */
	public function GetDisplayForm($sCurrentValue, $oPage, $bEditMode = false, $sFormPrefix = '')
	{
		$sRet = '';
		$aUserOptions = $this->GetUserOptions($sCurrentValue);
		if (count($aUserOptions) < 2) {
			$bEditOption = false;
		} else {
			$bEditOption = $bEditMode;
		}
		$sCurrentOption = $this->GetCurrentOption($sCurrentValue);
		foreach ($aUserOptions as $sUserOption) {
			$bSelected = ($sUserOption == $sCurrentOption);
			$sRet .= '<div>';
			$sRet .= $this->GetDisplayOption($sCurrentValue, $oPage, $sFormPrefix, $bEditOption, $sUserOption,
				$bSelected);
			$sRet .= '</div>';
		}

		return $sRet;
	}

	const USER_OPTION_DISABLED        = 'disabled';
	const USER_OPTION_ENABLED_COUNT   = 'count';
	const USER_OPTION_ENABLED_PERCENT = 'percent';

	/**
	 * Depending on the xxx_mode parameters, build the list of options that are allowed to the end-user
	 */
	protected function GetUserOptions($sValue)
	{
		$aRet = array();
		if ($this->Get('enabled_mode') == 'user') {
			$aRet[] = self::USER_OPTION_DISABLED;
		}

		if ($this->Get('min_up_mode') == 'user') {
			$aRet[] = self::USER_OPTION_ENABLED_COUNT;
			$aRet[] = self::USER_OPTION_ENABLED_PERCENT;
		} else {
			if ($this->GetMinUpType($sValue) == 'count') {
				$aRet[] = self::USER_OPTION_ENABLED_COUNT;
			} else {
				$aRet[] = self::USER_OPTION_ENABLED_PERCENT;
			}
		}

		return $aRet;
	}

	/**
	 * Convert the string representation into one of the existing options
	 */
	protected function GetCurrentOption($sValue)
	{
		$sRet = self::USER_OPTION_DISABLED;
		if ($this->IsEnabled($sValue)) {
			if ($this->GetMinUpType($sValue) == 'count') {
				$sRet = self::USER_OPTION_ENABLED_COUNT;
			} else {
				$sRet = self::USER_OPTION_ENABLED_PERCENT;
			}
		}

		return $sRet;
	}

	/**
	 * Display an option (form, or current value)
	 *
	 * @param string $sCurrentValue
	 * @param WebPage $oPage
	 * @param string $sFormPrefix
	 * @param bool $bEditMode
	 * @param string $sUserOption
	 * @param bool $bSelected
	 *
	 * @return string
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 * @throws Exception
	 */
	protected function GetDisplayOption(
		$sCurrentValue, $oPage, $sFormPrefix, $bEditMode, $sUserOption, $bSelected = true
	)
	{
		$sRet = '';

		$iCurrentValue = $this->GetMinUpValue($sCurrentValue);
		if ($bEditMode) {
			$sValue = null;
			$sHtmlNamesPrefix = 'rddcy_'.$this->Get('relation_code').'_'.$this->Get('from_class').'_'.$this->Get('neighbour_id');
			switch ($sUserOption) {
				case self::USER_OPTION_DISABLED:
					$sValue = ''; // Empty placeholder
					break;

				case self::USER_OPTION_ENABLED_COUNT:
					if ($bEditMode) {
						$sName = $sHtmlNamesPrefix.'_min_up_count';
						$sEditValue = $bSelected ? $iCurrentValue : '';
						$sValue = '<input class="redundancy-min-up-count" type="string" size="3" name="'.$sName.'" value="'.$sEditValue.'">';
						// To fix an issue on Firefox: focus set to the option (because the input is within the label for the option)
						$oPage->add_ready_script("\$('[name=\"$sName\"]').on('click', function(){var me=this; setTimeout(function(){\$(me).trigger('focus');}, 100);});");
					} else {
						$sValue = $iCurrentValue;
					}
					break;

				case self::USER_OPTION_ENABLED_PERCENT:
					if ($bEditMode) {
						$sName = $sHtmlNamesPrefix.'_min_up_percent';
						$sEditValue = $bSelected ? $iCurrentValue : '';
						$sValue = '<input class="redundancy-min-up-percent" type="string" size="3" name="'.$sName.'" value="'.$sEditValue.'">';
						// To fix an issue on Firefox: focus set to the option (because the input is within the label for the option)
						$oPage->add_ready_script("\$('[name=\"$sName\"]').on('click', function(){var me=this; setTimeout(function(){\$(me).trigger('focus');}, 100);});");
					} else {
						$sValue = $iCurrentValue;
					}
					break;
			}
			$sLabel = sprintf($this->GetUserOptionFormat($sUserOption), $sValue,
				MetaModel::GetName($this->GetHostClass()));

			$sOptionName = $sHtmlNamesPrefix.'_user_option';
			$sOptionId = $sOptionName.'_'.$sUserOption;
			$sChecked = $bSelected ? 'checked' : '';
			$sRet = '<input type="radio" name="'.$sOptionName.'" id="'.$sOptionId.'" value="'.$sUserOption.'" '.$sChecked.'> <label for="'.$sOptionId.'">'.$sLabel.'</label>';
		} else {
			// Read-only: display only the currently selected option
			if ($bSelected) {
				$sRet = sprintf($this->GetUserOptionFormat($sUserOption), $iCurrentValue,
					MetaModel::GetName($this->GetHostClass()));
			}
		}

		return $sRet;
	}

	/**
	 * Makes the string representation out of the values given by the form defined in GetDisplayForm
	 */
	public function ReadValueFromPostedForm($sFormPrefix)
	{
		$sHtmlNamesPrefix = 'rddcy_'.$this->Get('relation_code').'_'.$this->Get('from_class').'_'.$this->Get('neighbour_id');

		$iMinUpCount = (int)utils::ReadPostedParam($sHtmlNamesPrefix.'_min_up_count', null, 'raw_data');
		$iMinUpPercent = (int)utils::ReadPostedParam($sHtmlNamesPrefix.'_min_up_percent', null, 'raw_data');
		$sSelectedOption = utils::ReadPostedParam($sHtmlNamesPrefix.'_user_option', null, 'raw_data');
		switch ($sSelectedOption) {
			case self::USER_OPTION_ENABLED_COUNT:
				$sRet = $iMinUpCount;
				break;

			case self::USER_OPTION_ENABLED_PERCENT:
				$sRet = $iMinUpPercent.'%';
				break;

			case self::USER_OPTION_DISABLED:
			default:
				$sRet = 'disabled';
				break;
		}

		return $sRet;
	}
}