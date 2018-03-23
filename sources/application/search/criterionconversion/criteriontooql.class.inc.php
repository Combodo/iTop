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


use AttributeDateTime;
use AttributeDefinition;
use AttributeExternalKey;
use Combodo\iTop\Application\Search\AjaxSearchException;
use Combodo\iTop\Application\Search\CriterionConversionAbstract;
use Combodo\iTop\Application\Search\SearchForm;
use DateInterval;

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
			self::OP_BETWEEN_DATES => 'BetweenDatesToOql',
			self::OP_BETWEEN => 'BetweenToOql',
			self::OP_IN => 'InToOql',
			self::OP_ALL => 'AllToOql',
		);

		if (array_key_exists($sOperator, $aMappedOperators))
		{
			$sFct = $aMappedOperators[$sOperator];

			return self::$sFct($sRef, $aCriteria);
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

	protected static function ContainsToOql($sRef, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		return "({$sRef} LIKE '%{$sValue}%')";
	}

	protected static function StartsWithToOql($sRef, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		return "({$sRef} LIKE '{$sValue}%')";
	}

	protected static function EndsWithToOql($sRef, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		return "({$sRef} LIKE '%{$sValue}')";
	}

	protected static function EmptyToOql($sRef, $aCriteria)
	{
		if (isset($aCriteria['widget']) && ($aCriteria['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_NUMERIC))
		{
			return "ISNULL({$sRef})";
		}

		return "({$sRef} = '')";
	}

	protected static function NotEmptyToOql($sRef, $aCriteria)
	{
		return "({$sRef} != '')";
	}

	protected static function InToOql($sRef, $aCriteria)
	{
		$sAttCode = $aCriteria['code'];
		$sClass = $aCriteria['class'];
		$aValues = self::GetValues($aCriteria);

		if (count($aValues) == 0)
		{
			// Ignore when nothing is selected
			return "1";
		}

		$bFilterOnUndefined = false;
		$sFilterOnUndefined = '';
		try
		{
			$aAttributeDefs = \MetaModel::ListAttributeDefs($sClass);
			if (array_key_exists($sAttCode, $aAttributeDefs))
			{
				$oAttDef = $aAttributeDefs[$sAttCode];
				$aAllowedValues = SearchForm::GetFieldAllowedValues($oAttDef);
				if (array_key_exists('values', $aAllowedValues))
				{
					// Can't invert the test if NULL is allowed
					if (!$oAttDef->IsNullAllowed())
					{
						$aAllowedValues = $aAllowedValues['values'];
						if (count($aValues) == count($aAllowedValues))
						{
							// All entries are selected
							return "1";
						}
						// more selected values than remaining so use NOT IN
						else
						{
							if (count($aValues) > (count($aAllowedValues) / 2))
							{
								foreach($aValues as $aValue)
								{
									unset($aAllowedValues[$aValue['value']]);
								}
								$sInList = implode("','", array_keys($aAllowedValues));

								return "({$sRef} NOT IN ('$sInList'))";
							}
						}
					}
					// search for "undefined"
					for ($i = 0; $i < count($aValues); $i++)
					{
						$aValue = $aValues[$i];
						if (isset($aValue['value']) && ($aValue['value'] === 'null'))
						{
							if ($oAttDef instanceof AttributeExternalKey)
							{
								$sFilterOnUndefined = "({$sRef} = 0)";
							}
							else
							{
								$sFilterOnUndefined = "ISNULL({$sRef})";
							}
							$bFilterOnUndefined = true;
							unset($aValues[$i]);
							break;
						}
					}
				}
			}
		} catch (\CoreException $e)
		{
		}

		$aInValues = array();
		foreach($aValues as $aValue)
		{
			$aInValues[] = $aValue['value'];
		}
		$sInList = implode("','", $aInValues);

		if ($bFilterOnUndefined)
		{
			if (count($aValues) === 0)
			{
				return "{$sFilterOnUndefined}";
			}

			if (count($aInValues) == 1)
			{
				return "(({$sRef} = '$sInList') OR {$sFilterOnUndefined})";
			}

			return "({$sRef} IN ('$sInList') OR {$sFilterOnUndefined})";
		}

		if (count($aInValues) == 1)
		{
			return "({$sRef} = '$sInList')";
		}

		return "({$sRef} IN ('$sInList'))";
	}

	protected static function BetweenDatesToOql($sRef, $aCriteria)
	{
		$aOQL = array();

		$aValues = self::GetValues($aCriteria);
		if (count($aValues) != 2)
		{
			return "1";
		}

		$oFormat = AttributeDateTime::GetFormat();

		$sStartDate = $aValues[0]['value'];
		if (!empty($sStartDate))
		{
			$oDate = $oFormat->parse($sStartDate);
			$sStartDate = $oDate->format(AttributeDateTime::GetSQLFormat());
			$aOQL[] = "({$sRef} >= '$sStartDate')";
		}

		$sEndDate = $aValues[1]['value'];
		if (!empty($sEndDate))
		{
			$oDate = $oFormat->parse($sEndDate);
			$oDate->add(DateInterval::createFromDateString('1 second'));
			$sEndDate = $oDate->format(AttributeDateTime::GetSQLFormat());
			$aOQL[] = "({$sRef} < '$sEndDate')";
		}

		$sOQL = implode(' AND ', $aOQL);

		if (empty($sOQL))
		{
			$sOQL = "1";
		}

		return $sOQL;
	}

	/**
	 * @param $sRef
	 * @param $aCriteria
	 *
	 * @return string
	 * @throws \Combodo\iTop\Application\Search\AjaxSearchException
	 */
	protected static function BetweenToOql($sRef, $aCriteria)
	{
		$aOQL = array();

		$aValues = self::GetValues($aCriteria);
		if (count($aValues) != 2)
		{
			return "1";
		}

		if (isset($aValues[0]['value']))
		{
			$sStartNum = trim($aValues[0]['value']);
			if (is_numeric($sStartNum))
			{
				$aOQL[] = "({$sRef} >= '$sStartNum')";
			}
			else
			{
				if (!empty($sStartNum))
				{
					throw new AjaxSearchException("'$sStartNum' is not a numeric value", 400);
				}
			}
		}

		if (isset($aValues[1]['value']))
		{
			$sEndNum = trim($aValues[1]['value']);
			if (is_numeric($sEndNum))
			{
				$aOQL[] = "({$sRef} <= '$sEndNum')";
			}
			else
			{
				if (!empty($sEndNum))
				{
					throw new AjaxSearchException("'$sEndNum' is not a numeric value", 400);
				}
			}
		}

		$sOQL = implode(' AND ', $aOQL);

		if (empty($sOQL))
		{
			$sOQL = "1";
		}

		return $sOQL;
	}


	protected static function AllToOql($sRef, $aCriteria)
	{
		return "1";
	}

}