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

class CriterionToSearchForm extends CriterionConversionAbstract
{

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

		// Regroup criterion by variable name
		usort($aAndCriterion, function ($a, $b) {
			$iRefCmp = strcmp($a['ref'], $b['ref']);
			if ($iRefCmp != 0) return $iRefCmp;
			$iOpCmp = strcmp($a['operator'], $b['operator']);

			return $iOpCmp;
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
		$sOperator = $aCriteria['operator'];
		if ($sOperator == '=')
		{
			$aCriteria['operator'] = 'IN';
		}
		if ($sOperator != 'NOT IN')
		{
			return $aCriteria;
		}
		$sRef = $aCriteria['ref'];
		$aValues = $aCriteria['values'];
		if (array_key_exists($sRef, $aFields))
		{
			$aField = $aFields[$sRef];
			if (array_key_exists('allowed_values', $aField) && array_key_exists('values', $aField['allowed_values']))
			{
				$aAllowedValues = $aField['allowed_values']['values'];
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