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
 * Created by PhpStorm.
 * User: Eric
 * Date: 08/03/2018
 * Time: 11:25
 */

namespace Combodo\iTop\Application\Search;


use Combodo\iTop\Application\Search\CriterionConversion\CriterionToOQL;
use DBObjectSearch;
use IssueLog;
use OQLException;

class CriterionParser
{

	/**
	 * @param $sBaseOql
	 * @param $aCriterion
	 *
	 * @return \DBSearch
	 */
	public static function Parse($sBaseOql, $aCriterion)
	{
		$aExpression = array();
		$aOr = $aCriterion['or'];
		foreach($aOr as $aAndList)
		{

			$sExpression = self::ParseAndList($aAndList['and']);
			if (!empty($sExpression))
			{
				$aExpression[] = $sExpression;
			}
		}

		try
		{
			$oSearch = DBObjectSearch::FromOQL($sBaseOql);
			if (empty($aExpression))
			{
				return $oSearch;
			}

			$oExpression = \Expression::FromOQL(implode(" OR ", $aExpression));
			$oSearch->AddConditionExpression($oExpression);
			
			return $oSearch;
		} catch (OQLException $e)
		{
			IssueLog::Error($e->getMessage());
		}
		return null;
	}

	private static function ParseAndList($aAnd)
	{
		$aExpression = array();
		foreach($aAnd as $aCriteria)
		{
			$aExpression[] = CriterionToOQL::Convert($aCriteria);
		}

		if (empty($aExpression))
		{
			return '';
		}

		return '('.implode(" AND ", $aExpression).')';
	}
}