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

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 08/03/2018
 * Time: 11:25
 */

namespace Combodo\iTop\Application\Search;


use Combodo\iTop\Application\Search\CriterionConversion\CriterionToOQL;
use DBObjectSearch;
use Expression;
use IssueLog;
use OQLException;

class CriterionParser
{

	/**
	 * @param $sBaseOql
	 * @param $aCriterion
	 * @param $sHiddenCriteria
	 *
	 * @return \DBSearch
	 */
	public static function Parse($sBaseOql, $aCriterion, $sHiddenCriteria = null)
	{
		try
		{
			$oSearch = DBObjectSearch::FromOQL($sBaseOql);

			$aExpression = array();
			$aOr = $aCriterion['or'];
			foreach($aOr as $aAndList)
			{

				$sExpression = self::ParseAndList($oSearch, $aAndList['and']);
				if (!empty($sExpression))
				{
					$aExpression[] = $sExpression;
				}
			}

			if (!empty($sHiddenCriteria))
			{
				$oHiddenCriteriaExpression = Expression::FromOQL($sHiddenCriteria);
				$oSearch->AddConditionExpression($oHiddenCriteriaExpression);
			}

			if (empty($aExpression))
			{
				return $oSearch;
			}

			$oExpression = Expression::FromOQL(implode(" OR ", $aExpression));
			$oSearch->AddConditionExpression($oExpression);

			return $oSearch;
		} catch (OQLException $e)
		{
			IssueLog::Error($e->getMessage());
		}
		return null;
	}

	private static function ParseAndList($oSearch, $aAnd)
	{
		$aExpression = array();
		foreach($aAnd as $aCriteria)
		{

			$sExpression = CriterionToOQL::Convert($oSearch, $aCriteria);
			if ($sExpression !== '1')
			{
				$aExpression[] = $sExpression;
			}
		}

		if (empty($aExpression))
		{
			return '1';
		}

		return '('.implode(" AND ", $aExpression).')';
	}
}