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


use AttributeDefinition;
use Combodo\iTop\Application\Search\CriterionConversionAbstract;

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
			return strcmp($a['ref'], $b['ref']);
		});

		return $aAndCriterion;
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

		switch (true)
		{
			case ($sOperator == 'NOT IN'):
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
				break;
		}

		return $aCriteria;
	}
}