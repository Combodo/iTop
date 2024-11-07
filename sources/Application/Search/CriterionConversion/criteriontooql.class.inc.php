<?php
/**
 * Copyright (C) 2010-2024 Combodo SAS
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


use AttributeDate;
use AttributeDateTime;
use AttributeDefinition;
use AttributeEnum;
use AttributeExternalKey;
use Combodo\iTop\Application\Search\AjaxSearchException;
use Combodo\iTop\Application\Search\CriterionConversionAbstract;
use Combodo\iTop\Application\Search\SearchForm;
use DBObjectSearch;
use Exception;
use Expression;
use MetaModel;
use utils;

class CriterionToOQL extends CriterionConversionAbstract
{

	public static function Convert($oSearch, $aCriteria)
	{
		if (!empty($aCriteria['oql']))
		{
			return $aCriteria['oql'];
		}

		$aRef = explode('.', $aCriteria['ref']);
		$aCriteria['code'] = $aRef[1];
		for($i = 0; $i < count($aRef); $i++)
		{
			$aRef[$i] = '`'.$aRef[$i].'`';
		}
		$sRef = implode('.', $aRef);

		if (isset($aCriteria['operator']))
		{
			$sOperator = $aCriteria['operator'];
		}
		else
		{
			$sOperator = self::OP_ALL;
		}

		$aMappedOperators = array(
			self::OP_CONTAINS => 'ContainsToOql',
			self::OP_EQUALS => 'EqualsToOql',
			self::OP_STARTS_WITH => 'StartsWithToOql',
			self::OP_ENDS_WITH => 'EndsWithToOql',
			self::OP_EMPTY => 'EmptyToOql',
			self::OP_NOT_EMPTY => 'NotEmptyToOql',
			self::OP_BETWEEN_DATES => 'BetweenDatesToOql',
			self::OP_BETWEEN => 'BetweenToOql',
			self::OP_REGEXP => 'RegexpToOql',
			self::OP_IN => 'InToOql',
			self::OP_MATCHES => 'MatchesToOql',
			self::OP_ALL => 'AllToOql',
		);

		if (array_key_exists($sOperator, $aMappedOperators))
		{
			$sFct = $aMappedOperators[$sOperator];

			return self::$sFct($oSearch, $sRef, $aCriteria);
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

		return addslashes($aValues[$iIndex]['value']);
	}

	protected static function ContainsToOql($oSearch, $sRef, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		if (!utils::StrLen($sValue)) {
			return "1";
		}

		return "({$sRef} LIKE '%{$sValue}%')";
	}

	protected static function StartsWithToOql($oSearch, $sRef, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		if (!utils::StrLen($sValue)) {
			return "1";
		}

		return "({$sRef} LIKE '{$sValue}%')";
	}

	protected static function EndsWithToOql($oSearch, $sRef, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		if (!utils::StrLen($sValue)) {
			return "1";
		}

		return "({$sRef} LIKE '%{$sValue}')";
	}

	protected static function EqualsToOql($oSearch, $sRef, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);
		if (($aCriteria['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_NUMERIC) && ($sValue === '0'))
		{
			return "({$sRef} = '0')";
		}

		if (!utils::StrLen($sValue) && (!(isset($aCriteria['has_undefined'])) || !($aCriteria['has_undefined']))) {
			return "1";
		}

		return "({$sRef} = '{$sValue}')";
	}

	protected static function RegexpToOql($oSearch, $sRef, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$sValue = self::GetValue($aValues, 0);

		if (!utils::StrLen($sValue)) {
			return "1";
		}

		return "({$sRef} REGEXP '{$sValue}')";
	}

	protected static function MatchesToOql($oSearch, $sRef, $aCriteria)
	{
		$aValues = self::GetValues($aCriteria);
		$aRawValues = array();
		$bHasUnDefined = isset($aCriteria['has_undefined']) ? $aCriteria['has_undefined'] : false;
		for($i = 0; $i < count($aValues); $i++)
		{
			$sRawValue = self::GetValue($aValues, $i);
			if (!utils::StrLen($sRawValue)) {
				$bHasUnDefined = true;
			} else {
				$aRawValues[] = $sRawValue;
			}
		}
		// This allow to search for complete words
		if (!empty($aRawValues))
		{
			$sValue = implode(' ', $aRawValues).' _';
		}
		else
		{
			if ($bHasUnDefined)
			{
				return "({$sRef} = '')";
			}
			return "1";
		}

		if ($bHasUnDefined)
		{
			return "((({$sRef} MATCHES '{$sValue}') OR ({$sRef} = '')) AND 1)";
		}
		return "({$sRef} MATCHES '{$sValue}')";
	}

	protected static function EmptyToOql($oSearch, $sRef, $aCriteria)
	{
		if (isset($aCriteria['widget']))
		{
			switch ($aCriteria['widget'])
			{
				case AttributeDefinition::SEARCH_WIDGET_TYPE_NUMERIC:
				case AttributeDefinition::SEARCH_WIDGET_TYPE_EXTERNAL_FIELD:
				case AttributeDefinition::SEARCH_WIDGET_TYPE_DATE:
				case AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME:
					return "ISNULL({$sRef})";
			}
		}

		return "({$sRef} = '')";
	}

	protected static function NotEmptyToOql($oSearch, $sRef, $aCriteria)
	{
		if (isset($aCriteria['widget']))
		{
			switch ($aCriteria['widget'])
			{
				case AttributeDefinition::SEARCH_WIDGET_TYPE_NUMERIC:
				case AttributeDefinition::SEARCH_WIDGET_TYPE_EXTERNAL_FIELD:
				case AttributeDefinition::SEARCH_WIDGET_TYPE_DATE:
				case AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME:
					return "ISNULL({$sRef}) = 0";
			}
		}

		return "({$sRef} != '')";
	}

	/**
	 * @param \DBObjectSearch $oSearch
	 * @param string $sRef
	 * @param array $aCriteria
	 *
	 * @return mixed|string
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	protected static function InToOql($oSearch, $sRef, $aCriteria)
	{
		$sAttCode = $aCriteria['code'];
		$sClass = $aCriteria['class'];
		$aValues = self::GetValues($aCriteria);

		if (count($aValues) == 0)
		{
			// Ignore when nothing is selected
			return "1";
		}

		$oAttDef = null;
		try
		{
			$aAttributeDefs = MetaModel::ListAttributeDefs($sClass);
		} catch (\CoreException $e)
		{
			return "1";
		}
		if (array_key_exists($sAttCode, $aAttributeDefs))
		{
			$oAttDef = $aAttributeDefs[$sAttCode];
		}

		// Hierarchical keys
		$sHierarchicalKeyCode = false;
		$sTargetClass = '';
		if (isset($oAttDef) && $oAttDef->IsExternalKey() && ($aCriteria['widget'] == AttributeDefinition::SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY))
		{
			if ($oAttDef instanceof AttributeExternalKey)
			{
				$sTargetClass = $oAttDef->GetTargetClass();
			}
			else
			{
				/** @var AttributeExternalKey $oFinalAttDef */
				$oFinalAttDef = $oAttDef->GetFinalAttDef();
				$sTargetClass = $oFinalAttDef->GetTargetClass();
			}

			try
			{
				$sHierarchicalKeyCode = MetaModel::IsHierarchicalClass($sTargetClass);
			} catch (\CoreException $e)
			{
			}
		}

		$sFilterOnUndefined = '';
		if ($oAttDef instanceof AttributeEnum)
		{
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
				for($i = 0; $i < count($aValues); $i++)
				{
					$aValue = $aValues[$i];
					if (isset($aValue['value']) && ($aValue['value'] === 'null'))
					{
						$sFilterOnUndefined = "ISNULL({$sRef})";
						unset($aValues[$i]);
						break;
					}
				}
			}
		}

		if ($sHierarchicalKeyCode !== false)
		{
			// search for "undefined"
			for($i = 0; $i < count($aValues); $i++)
			{
				$aValue = $aValues[$i];
				if (isset($aValue['value']) && ($aValue['value'] === '0'))
				{
					$sFilterOnUndefined = "({$sRef} = '0')";
					unset($aValues[$i]);
					break;
				}
			}
		}

		$aInValues = array();
		foreach($aValues as $aValue)
		{
			$aInValues[] = $aValue['value'];
		}
		$sInList = implode("','", $aInValues);

		$sCondition = '1';
		if (count($aInValues) == 1)
		{
			$sCondition = "({$sRef} = '$sInList')";
		}
		elseif (count($aInValues) > 1)
		{
			$sCondition = "({$sRef} IN ('$sInList'))";
		}

		// Hierarchical keys
		try
		{
			if (($sHierarchicalKeyCode !== false) && ($oSearch instanceof DBObjectSearch))
			{
				// NOTE: The hierarchy does not work for unions for now. It'll be done with the full support of unions in search.
				// Add all the joins for hierarchical key
				$oFilter = new DBObjectSearch($sTargetClass);
				$sFilterAlias = $oFilter->GetClassAlias();
				// Filter on hierarchy
				$sCondition = str_replace("$sRef", $sFilterAlias.'.id', $sCondition);
				$oCondition = Expression::FromOQL($sCondition);
				$oFilter->AddConditionExpression($oCondition);

				$oHKFilter = new DBObjectSearch($sTargetClass);
				$oHKFilter->AddCondition_PointingTo($oFilter, $sHierarchicalKeyCode, TREE_OPERATOR_BELOW);
				// Use the 'below' operator by default
				$oSearch->AddCondition_PointingTo($oHKFilter, $sAttCode);
				$oCriteria = $oSearch->GetCriteria();
				$aArgs = MetaModel::PrepareQueryArguments(array(), $oSearch->GetInternalParams(), $oSearch->GetExpectedArguments() );
				$oSearch->ResetCondition();
				$sCondition = $oCriteria->RenderExpression(false, $aArgs);
			}
		} catch (Exception $e)
		{
		}

		if (!empty($sFilterOnUndefined))
		{
			if (count($aValues) === 0)
			{
				$sCondition = $sFilterOnUndefined;
			}
			else
			{
				// Add 'AND 1' to group the 'OR' inside an AND list for OQL parsing
				$sCondition = "(({$sCondition} OR {$sFilterOnUndefined}) AND 1)";
			}
		}

		return $sCondition;
	}

	protected static function BetweenDatesToOql($oSearch, $sRef, $aCriteria)
	{
		$aOQL = array();

		$aValues = self::GetValues($aCriteria);
		if (count($aValues) != 2)
		{
			return "1";
		}

		$sWidget = $aCriteria['widget'];
		if ($sWidget == AttributeDefinition::SEARCH_WIDGET_TYPE_DATE_TIME)
		{
			$sAttributeClass = AttributeDateTime::class;
		}
		else
		{
			$sAttributeClass = AttributeDate::class;
		}
		$oFormat = $sAttributeClass::GetFormat();

		$sStartDate = $aValues[0]['value'];
		if (!empty($sStartDate))
		{
			try
			{
				$oDate = $oFormat->parse($sStartDate);
				$sStartDate = $oDate->format($sAttributeClass::GetSQLFormat());
				$aOQL[] = "({$sRef} >= '$sStartDate')";
			} catch (Exception $e)
			{
			}
		}

		$sEndDate = $aValues[1]['value'];
		if (!empty($sEndDate))
		{
			try
			{
				$oDate = $oFormat->parse($sEndDate);
				$sEndDate = $oDate->format($sAttributeClass::GetSQLFormat());
				$aOQL[] = "({$sRef} <= '$sEndDate')";
			} catch (Exception $e)
			{
			}
		}

		$sOQL = implode(' AND ', $aOQL);

		if (empty($sOQL))
		{
			$sOQL = "1";
		}

		return $sOQL;
	}

	/**
	 * @param DBObjectSearch $oSearch
	 * @param $sRef
	 * @param $aCriteria
	 *
	 * @return string
	 * @throws \Combodo\iTop\Application\Search\AjaxSearchException
	 */
	protected static function BetweenToOql($oSearch, $sRef, $aCriteria)
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


	protected static function AllToOql($oSearch, $sRef, $aCriteria)
	{
		return "1";
	}

}
