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


use Combodo\iTop\Application\Search\CriterionConversionAbstract;

class CriterionToOQL extends CriterionConversionAbstract
{

	public static function Convert($aCriteria)
	{
		if (!empty($aCriteria['oql']))
		{
			return $aCriteria['oql'];
		}

		$aRef = explode('.', $aCriteria['ref']);
		for($i = 0; $i < count($aRef); $i++)
		{
			$aRef[$i] = '`'.$aRef[$i].'`';
		}
		$sRef = implode('.', $aRef);

		$sOperator = $aCriteria['operator'];

		$aMappedOperators = array(
			self::OP_CONTAINS => 'ContainsToOql',
			self::OP_STARTS_WITH => 'StartsWithToOql',
			self::OP_ENDS_WITH => 'EndsWithToOql',
			self::OP_EMPTY => 'EmptyToOql',
			self::OP_NOT_EMPTY => 'NotEmptyToOql',
		);

		if (array_key_exists($sOperator, $aMappedOperators))
		{
			$sFct = $aMappedOperators[$sOperator];

			return self::$sFct($sRef, $sOperator, $aCriteria['values']);
		}

		$sValue = $aCriteria['values'][0]['value'];

		return "({$sRef} {$sOperator} '{$sValue}')";
	}

	protected static function ContainsToOql($sRef, $sOperator, $aValues)
	{
		$sValue = $aValues[0]['value'];

		return "({$sRef} LIKE '%{$sValue}%')";
	}

	protected static function StartsWithToOql($sRef, $sOperator, $aValues)
	{
		$sValue = $aValues[0]['value'];

		return "({$sRef} LIKE '{$sValue}%')";
	}

	protected static function EndsWithToOql($sRef, $sOperator, $aValues)
	{
		$sValue = $aValues[0]['value'];

		return "({$sRef} LIKE '%{$sValue}')";
	}

	protected static function EmptyToOql($sRef, $sOperator, $aValues)
	{
		return "({$sRef} = '')";
	}

	protected static function NotEmptyToOql($sRef, $sOperator, $aValues)
	{
		return "({$sRef} != '')";
	}

}