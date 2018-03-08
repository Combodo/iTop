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


namespace Combodo\iTop\Application\Search\CriterionConversion;


use AttributeString;
use Combodo\iTop\Application\Search\CriterionConversionAbstract;

class CriterionToSearchForm extends CriterionConversionAbstract
{

	public static function Convert($aAndCriterionRaw)
	{
		$aAndCriterion = array();
		$aMappingOperatorToFunction = array(
			AttributeString::SEARCH_WIDGET_TYPE => 'TextToSearchForm',
		);

		foreach($aAndCriterionRaw as $aCriteria)
		{
			if (array_key_exists($aCriteria['widget'], $aMappingOperatorToFunction))
			{
				$sFct = $aMappingOperatorToFunction[$aCriteria['widget']];
				$aAndCriterion[] = self::$sFct($aCriteria);
			}
		}

		return $aAndCriterion;
	}

	protected static function TextToSearchForm($aCriteria)
	{
		$sRef = $aCriteria['ref'];
		$sOperator = $aCriteria['operator'];
		$aValues = $aCriteria['values'];


		$value = $aValues[0]['value'];
		if ('' == $value)
		{
			$aCriteria['operator'] = CriterionConversionAbstract::OP_EMPTY;

			return $aCriteria;
		}
		$bStartWithPercent = $value{0} == '%' ? true : false;
		$bEndWithPercent = $value{0} == '%' ? true : false;

		return $aCriteria;
	}
}