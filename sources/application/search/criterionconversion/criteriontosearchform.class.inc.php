<?php
/**
 * Copyright (C) 2010-2018 Combodo SARL
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

/**
 * Convert structures from OQL expressions into structure for the search form
 */
namespace Combodo\iTop\Application\Search\CriterionConversion;


use AttributeDateTime;
use AttributeDefinition;
use Combodo\iTop\Application\Search\CriterionConversionAbstract;
use DateInterval;
use DateTime;
use Dict;

class CriterionToSearchForm extends CriterionConversionAbstract
{

	/**
	 * @param array $aAndCriterionRaw
	 * @param array $aFieldsByCategory
	 *
	 * @return array
	 */
	public static function Convert($aAndCriterionRaw, $aFieldsByCategory)
	{
		$aAllFields = array();
		foreach($aFieldsByCategory as $aFields)
		{
			foreach($aFields as $aField)
			{
				$sAlias = $aField['class_alias'];
				$sCode = $aField['code'];
				$aAllFields["$sAlias.$sCode"] = $aField;
			}
		}
		$aAndCriterion = array();
		$aMappingOperatorToFunction = array(
			AttributeDefinition::SEARCH_WIDGET_TYPE_STRING => 'TextToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_ENUM => 'EnumToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_DATE => 'DateToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME => 'DateTimeToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_NUMERIC => 'NumericToSearchForm',
			AttributeDefinition::SEARCH_WIDGET_TYPE_EXTERNAL_KEY => 'ExternalKeyToSearchForm',
		);

		foreach($aAndCriterionRaw as $aCriteria)
		{
			if (array_key_exists('widget', $aCriteria))
			{
				if (array_key_exists($aCriteria['widget'], $aMappingOperatorToFunction))
				{
					$sFct = $aMappingOperatorToFunction[$aCriteria['widget']];
					$aAndCriterion[] = self::$sFct($aCriteria, $aAllFields);
				}
				else
				{
					$aAndCriterion[] = $aCriteria;
				}
			}
		}

		// Regroup criterion by variable name (no ref first)
		usort($aAndCriterion, function ($a, $b) {
			if (array_key_exists('ref', $a) || array_key_exists('ref', $b))
			{
				if (array_key_exists('ref', $a) && array_key_exists('ref', $b))
				{
					$iRefCmp = strcmp($a['ref'], $b['ref']);
					if ($iRefCmp != 0) return $iRefCmp;

					return strcmp($a['operator'], $b['operator']);
				}
				if (array_key_exists('ref', $a))
				{
					return 1;
				}

				return -1;
			}
			if (array_key_exists('oql', $a) && array_key_exists('oql', $b))
			{
				return strcmp($a['oql'], $b['oql']);
			}

			return 0;
		});

		$aMergeFctByWidget = array(
			AttributeDefinition::SEARCH_WIDGET_TYPE_DATE => 'MergeDate',
			AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME => 'MergeDateTime',
		);

		$aPrevCriterion = null;
		$aMergedCriterion = array();
		foreach($aAndCriterion as $aCurrCriterion)
		{
			if (!is_null($aPrevCriterion))
			{
				if (array_key_exists('ref', $aPrevCriterion))
				{
					// If previous has ref, the current has ref as the array is sorted with all without ref first
					if (strcmp($aPrevCriterion['ref'], $aCurrCriterion['ref']) == 0)
					{
						// Same attribute, try to merge
						if (array_key_exists('widget', $aCurrCriterion))
						{
							if (array_key_exists($aCurrCriterion['widget'], $aMergeFctByWidget))
							{
								$sFct = $aMergeFctByWidget[$aCurrCriterion['widget']];
								$aPrevCriterion = self::$sFct($aPrevCriterion, $aCurrCriterion, $aMergedCriterion);
								continue;
							}
						}
					}
				}
				$aMergedCriterion[] = $aPrevCriterion;
			}

			$aPrevCriterion = $aCurrCriterion;
		}
		if (!is_null($aPrevCriterion))
		{
			$aMergedCriterion[] = $aPrevCriterion;
		}

		return $aMergedCriterion;
	}

	/**
	 * @param $aPrevCriterion
	 * @param $aCurrCriterion
	 * @param $aMergedCriterion
	 *
	 * @return null
	 * @throws \Exception
	 */
	protected static function MergeDate($aPrevCriterion, $aCurrCriterion, &$aMergedCriterion)
	{
		$sPrevOperator = $aPrevCriterion['operator'];
		$sCurrOperator = $aCurrCriterion['operator'];
		if ((($sPrevOperator != '<') && ($sPrevOperator != '<=')) || (($sCurrOperator != '>') && ($sCurrOperator != '>=')))
		{
			$aMergedCriterion[] = $aPrevCriterion;

			return $aCurrCriterion;
		}

		// Merge into 'between' operation.
		// The ends of the interval are included
		$aCurrCriterion['operator'] = 'between_days';
		$sFormat = AttributeDateTime::GetFormat()->ToMomentJS();
		$sLastDate = $aPrevCriterion['values'][0]['value'];
		if ($sPrevOperator == '<')
		{
			// previous day to include ends
			$oDate = new DateTime($sLastDate);
			$oDate->sub(DateInterval::createFromDateString('1 day'));
			$sLastDate = $oDate->format($sFormat);
		}

		$sFirstDate = $aCurrCriterion['values'][0]['value'];
		if ($sCurrOperator == '>')
		{
			// next day to include ends
			$oDate = new DateTime($sFirstDate);
			$oDate->add(DateInterval::createFromDateString('1 day'));
			$sFirstDate = $oDate->format($sFormat);
		}

		$aCurrCriterion['values'] = array();
		$aCurrCriterion['values'][] = array('value' => $sFirstDate, 'label' => $sFirstDate);
		$aCurrCriterion['values'][] = array('value' => $sLastDate, 'label' => $sLastDate);

		$aCurrCriterion['oql'] = "({$aPrevCriterion['oql']} AND {$aCurrCriterion['oql']})";
		$aCurrCriterion['label'] = $aPrevCriterion['label'].' '.Dict::S('Expression:Operator:AND', 'AND').' '.$aCurrCriterion['label'];

		$aMergedCriterion[] = $aCurrCriterion;

		return null;
	}

	protected static function MergeDateTime($aPrevCriterion, $aCurrCriterion, &$aMergedCriterion)
	{
		$sPrevOperator = $aPrevCriterion['operator'];
		$sCurrOperator = $aCurrCriterion['operator'];
		if ((($sPrevOperator != '<') && ($sPrevOperator != '<=')) || (($sCurrOperator != '>') && ($sCurrOperator != '>=')))
		{
			$aMergedCriterion[] = $aPrevCriterion;

			return $aCurrCriterion;
		}

		// Merge into 'between' operation.
		// The ends of the interval are included
		$sLastDate = $aPrevCriterion['values'][0]['value'];
		$sFirstDate = $aCurrCriterion['values'][0]['value'];
		$oDate = new DateTime($sLastDate);
		if ((strpos($sFirstDate, '00:00:00') != false) && (strpos($sLastDate, '00:00:00') != false))
		{
			$aCurrCriterion['operator'] = 'between_days';
			$sInterval = '1 day';
		}
		else
		{
			$aCurrCriterion['operator'] = 'between_hours';
			$sInterval = '1 second';
		}

		if ($sPrevOperator == '<')
		{
			// previous day/second to include ends
			$oDate->sub(DateInterval::createFromDateString($sInterval));
		}
		$sLastDate = $oDate->format(AttributeDateTime::GetSQLFormat());
		$sLastDate = AttributeDateTime::GetFormat()->Format($sLastDate);

		$oDate = new DateTime($sFirstDate);
		if ($sCurrOperator == '>')
		{
			// next day/second to include ends
			$oDate->add(DateInterval::createFromDateString($sInterval));
		}
		$sFirstDate = $oDate->format(AttributeDateTime::GetSQLFormat());
		$sFirstDate = AttributeDateTime::GetFormat()->Format($sFirstDate);

		$aCurrCriterion['values'] = array();
		$aCurrCriterion['values'][] = array('value' => $sFirstDate, 'label' => $sFirstDate);
		$aCurrCriterion['values'][] = array('value' => $sLastDate, 'label' => $sLastDate);

		$aCurrCriterion['oql'] = "({$aPrevCriterion['oql']} AND {$aCurrCriterion['oql']})";
		$aCurrCriterion['label'] = $aPrevCriterion['label'].' '.Dict::S('Expression:Operator:AND', 'AND').' '.$aCurrCriterion['label'];

		$aMergedCriterion[] = $aCurrCriterion;

		return null;
	}


	protected static function TextToSearchForm($aCriteria, $aFields)
	{
		$sOperator = $aCriteria['operator'];
		$sValue = $aCriteria['values'][0]['value'];

		$bStartWithPercent = substr($sValue, 0, 1) == '%' ? true : false;
		$bEndWithPercent = substr($sValue, -1) == '%' ? true : false;

		switch (true)
		{
			case ('' == $sValue and ($sOperator == '=' or $sOperator == 'LIKE')):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_EMPTY;
				break;
			case ('' == $sValue and $sOperator == '!='):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_NOT_EMPTY;
				break;
			case ($sOperator == 'LIKE' && $bStartWithPercent && $bEndWithPercent):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_CONTAINS;
				$sValue = substr($sValue, 1, -1);
				$aCriteria['values'][0]['value'] = $sValue;
				$aCriteria['values'][0]['label'] = $sValue;
				break;
			case ($sOperator == 'LIKE' && $bStartWithPercent):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_ENDS_WITH;
				$sValue = substr($sValue, 1);
				$aCriteria['values'][0]['value'] = $sValue;
				$aCriteria['values'][0]['label'] = $sValue;
				break;
			case ($sOperator == 'LIKE' && $bEndWithPercent):
				$aCriteria['operator'] = CriterionConversionAbstract::OP_STARTS_WITH;
				$sValue = substr($sValue, 0, -1);
				$aCriteria['values'][0]['value'] = $sValue;
				$aCriteria['values'][0]['label'] = $sValue;
				break;
		}

		return $aCriteria;
	}

	protected static function EnumToSearchForm($aCriteria, $aFields)
	{
		return self::ExternalKeyToSearchForm($aCriteria, $aFields);
	}

	protected static function DateToSearchForm($aCriteria, $aFields)
	{
		return DateTimeToSearchForm($aCriteria, $aFields);
	}

	protected static function DateTimeToSearchForm($aCriteria, $aFields)
	{
		if (!array_key_exists('is_relative', $aCriteria) || !$aCriteria['is_relative'])
		{
			return $aCriteria;
		}

		if (isset($aCriteria['values'][0]['value']))
		{
			$sLabel = $aCriteria['values'][0]['value'];
			if (isset($aCriteria['verb']))
			{
				switch ($aCriteria['verb'])
				{
					case 'DATE_SUB':
						$sLabel = '-'.$sLabel;
						break;
					case 'DATE_ADD':
						$sLabel = '+'.$sLabel;
						break;
				}
			}
			if (isset($aCriteria['unit']))
			{
				$sLabel .= Dict::S('Expression:Unit:Short:'.$aCriteria['unit'], $aCriteria['unit']);
			}
			$aCriteria['values'][0]['label'] = $sLabel;
		}

		// Temporary until the JS widget support relative dates
		$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;

		return $aCriteria;
	}

	protected static function NumericToSearchForm($aCriteria, $aFields)
	{
		if ($aCriteria['operator'] == 'ISNULL')
		{
			$aCriteria['operator'] = CriterionConversionAbstract::OP_EMPTY;
		}

		return $aCriteria;
	}


	protected static function ExternalKeyToSearchForm($aCriteria, $aFields)
	{
		$sOperator = $aCriteria['operator'];
		switch ($sOperator)
		{
			case '=':
				if (!isset($aCriteria['values'][0]))
				{
					$aCriteria['operator'] = CriterionConversionAbstract::OP_EMPTY;
				}
				else
				{
					$aCriteria['operator'] = CriterionConversionAbstract::OP_IN;
				}
				break;
			case '!=':
				if (!isset($aCriteria['values'][0]))
				{
					$aCriteria['operator'] = CriterionConversionAbstract::OP_NOT_EMPTY;
				}
				else
				{
					// Same as NOT IN
					$aCriteria = self::RevertValues($aCriteria, $aFields);
				}
				break;
			case 'NOT IN':
				$aCriteria = self::RevertValues($aCriteria, $aFields);
				break;
			case 'IN':
				break;
			default:
				// Unknown operator
				$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;
				break;
		}

		return $aCriteria;
	}

	/**
	 * @param $aCriteria
	 * @param $aFields
	 *
	 * @return mixed
	 */
	protected static function RevertValues($aCriteria, $aFields)
	{
		$sRef = $aCriteria['ref'];
		$aValues = $aCriteria['values'];
		if (array_key_exists($sRef, $aFields))
		{
			$aField = $aFields[$sRef];
			if (array_key_exists('allowed_values', $aField) && array_key_exists('values', $aField['allowed_values']))
			{
				$aAllowedValues = $aField['allowed_values']['values'];
			}
			else
			{
				// Can't obtain the list of allowed values, just set as unknown
				$aCriteria['widget'] = AttributeDefinition::SEARCH_WIDGET_TYPE_RAW;
			}
		}

		if (isset($aAllowedValues))
		{
			foreach($aValues as $aValue)
			{
				$sValue = $aValue['value'];
				unset($aAllowedValues[$sValue]);
			}
			$aCriteria['values'] = array();

			foreach($aAllowedValues as $sValue => $sLabel)
			{
				$aValue = array('value' => $sValue, 'label' => $sLabel);
				$aCriteria['values'][] = $aValue;
			}
			$aCriteria['operator'] = 'IN';
		}

		return $aCriteria;
	}

}