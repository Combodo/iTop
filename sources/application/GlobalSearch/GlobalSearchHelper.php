<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Application\GlobalSearch;


use appUserPreferences;
use utils;

/**
 * Class GlobalSearchHelper
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\GlobalSearch
 * @since 2.8.0
 */
class GlobalSearchHelper
{
	const MAX_HISTORY_SIZE = 10;
	const USER_PREF_CODE = 'global_search_history';

	/**
	 * Add $sQuery to the history. History is limited to the static::MAX_HISTORY_SIZE last queries.
	 *
	 * @param string $sQuery Raw search query
	 * @param string|null $sIconRelUrl Relative URL of the icon
	 * @param string|null $sLabelAsHtml Alternate label for the query (eg. more human readable or with highlights), MUST be html entities otherwise there can be XSS flaws
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function AddQueryToHistory($sQuery, $sIconRelUrl = null, $sLabelAsHtml = null)
	{
		$aNewQuery = [
			'query' => $sQuery,
		];

		// Set icon only when necessary
		if(!empty($sIconRelUrl))
		{
			//Ensure URL is relative to limit space in the preferences and avoid broken links in case app_root_url changes
			$aNewQuery['icon_url'] = str_replace(utils::GetAbsoluteUrlAppRoot(), '', $sIconRelUrl);
		}

		// Set label only when necessary to avoid unnecessary space filling of the preferences in the DB
		if(!empty($sLabelAsHtml))
		{
			$aNewQuery['label_html'] = $sLabelAsHtml;
		}

		/** @var array $aQueriesHistory */
		$aQueriesHistory = appUserPreferences::GetPref(static::USER_PREF_CODE, []);

		// Remove same query from history to avoid duplicates
		for($iIdx = 0; $iIdx < count($aQueriesHistory); $iIdx++)
		{
			if($aQueriesHistory[$iIdx]['query'] === $sQuery)
			{
				unset($aQueriesHistory[$iIdx]);
			}
		}

		// Add new query
		array_unshift($aQueriesHistory, $aNewQuery);

		// Truncate history
		if(count($aQueriesHistory) > static::MAX_HISTORY_SIZE)
		{
			$aQueriesHistory = array_slice($aQueriesHistory, 0, static::MAX_HISTORY_SIZE);
		}

		appUserPreferences::SetPref(static::USER_PREF_CODE, $aQueriesHistory);
	}

	/**
	 * Return an array of pasted queries, including the query itself and its HTML label
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function GetLastQueries()
	{
		/** @var array $aLastQueries */
		$aLastQueries = appUserPreferences::GetPref(static::USER_PREF_CODE, []);

		// Add HTML label if missing
		for($iIdx = 0; $iIdx < count($aLastQueries); $iIdx++)
		{
			if(!isset($aLastQueries[$iIdx]['label_html']))
			{
				$aLastQueries[$iIdx]['label_html'] = utils::HtmlEntities($aLastQueries[$iIdx]['query']);
			}
		}

		return $aLastQueries;
	}
}