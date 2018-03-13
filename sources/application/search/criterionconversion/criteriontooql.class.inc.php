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
use Combodo\iTop\Application\Search\SearchForm;

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
			self::OP_IN => 'InToOql',
			self::OP_ALL => 'AllToOql',
		);

		if (array_key_exists($sOperator, $aMappedOperators))
		{
			$sFct = $aMappedOperators[$sOperator];

			return self::$sFct($sRef, $sOperator, $aCriteria);
		}

		$sValue = self::GetValue(self::GetValues($aCriteria), 0);

		return "({$sRef} {$sOperator} '{$sValue}')";
	}

	private static function GetValues($aCriteria)
	{
		if (!array_key_exists('values', $aCriteria))
		{
			return array();
		}
		return $aCriteria['values'];
	}

	private static function GetValue($aValues, $iIndex)
	{
		if (!array_key_exists($iIndex, $aValues))
		{
			return null;
		}
		if (!array_key_exists('value', $aValues[$iIndex]))
		{
			return null;
		}
		return $aValues[$iIndex]['value'];
	}

	protected static function ContainsToOql($sRef, $sOperator, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		return "({$sRef} LIKE '%{$sValue}%')";
	}

	protected static function StartsWithToOql($sRef, $sOperator, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		return "({$sRef} LIKE '{$sValue}%')";
	}

	protected static function EndsWithToOql($sRef, $sOperator, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		return "({$sRef} LIKE '%{$sValue}')";
	}

	protected static function EmptyToOql($sRef, $sOperator, $aCriteria)
	{
		return "({$sRef} = '')";
	}

	protected static function NotEmptyToOql($sRef, $sOperator, $aCriteria)
	{
		return "({$sRef} != '')";
	}

	protected static function InToOql($sRef, $sOperator, $aCriteria)
	{
		$sAttCode = $aCriteria['code'];
		$sClass = $aCriteria['class'];
		$aValues = $aCriteria['values'];

		$aAttributeDefs = MetaModel::ListAttributeDefs($sClass);
		if (array_key_exists($sAttCode, $aAttributeDefs))
		{
			$oAttDef = $aAttributeDefs[$sAttCode];
			$aAllowedValues = SearchForm::GetFieldAllowedValues($oAttDef);
			if (array_key_exists('values', $aAllowedValues))
			{
				$aAllowedValues = $aAllowedValues['values'];
				// more selected values than remaining so use NOT IN
				if (count($aValues) > (count($aAllowedValues) / 2))
				{
					foreach($aValues as $aValue)
					{
						unset($aAllowedValues[$aValue['value']]);
					}
					$sOQL = "({$sRef} NOT IN (";
					$s .= implode(',', array_keys($aAllowedValues));

				}
			}
		}

		return "({$sRef} != '')";
	}

	protected static function AllToOql($sRef, $sOperator, $aCriteria)
	{
		return "1";
	}

}