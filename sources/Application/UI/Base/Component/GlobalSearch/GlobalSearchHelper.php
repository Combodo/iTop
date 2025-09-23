<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Application\UI\Base\Component\GlobalSearch;


use appUserPreferences;
use MetaModel;
use utils;

/**
 * Class GlobalSearchHelper
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\GlobalSearch
 * @internal
 * @since 3.0.0
 */
class GlobalSearchHelper
{
	/** @var string */
	public const USER_PREF_CODE = 'global_search_history';

	/**
	 * Add $sQuery to the history. History is limited to the static::MAX_HISTORY_SIZE last queries.
	 *
	 * @param string $sQuery Raw search query
	 * @param string|null $sIconRelUrl Relative URL of the icon
	 * @param string|null $sLabelAsHtml Alternate label for the query (eg. more human readable or with highlights), MUST be html entities
	 *     otherwise there can be XSS flaws
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 * @noinspection PhpUnused Called by /pages/UI.php and extensions overloading the global search
	 */
	public static function AddQueryToHistory(string $sQuery, ?string $sIconRelUrl = null, ?string $sLabelAsHtml = null)
	{
		$aNewEntry = [
			'query' => $sQuery,
		];

		// Set icon only when necessary
		if (!empty($sIconRelUrl))
		{
			//Ensure URL is relative to limit space in the preferences and avoid broken links in case app_root_url changes
			$aNewEntry['icon_url'] = str_replace(utils::GetAbsoluteUrlAppRoot(), '', $sIconRelUrl);
		}

		// Set label only when necessary to avoid unnecessary space filling of the preferences in the DB
		if(!empty($sLabelAsHtml))
		{
			$aNewEntry['label_html'] = $sLabelAsHtml;
		}

		/** @var array $aHistoryEntries */
		$aHistoryEntries = appUserPreferences::GetPref(static::USER_PREF_CODE, []);

		// Remove same query from history to avoid duplicates
		for($iIdx = 0; $iIdx < count($aHistoryEntries); $iIdx++)
		{
			if($aHistoryEntries[$iIdx]['query'] === $sQuery)
			{
				unset($aHistoryEntries[$iIdx]);
			}
		}

		// Add new entry
		array_unshift($aHistoryEntries, $aNewEntry);

		// Truncate history
		static::TruncateHistory($aHistoryEntries);

		appUserPreferences::SetPref(static::USER_PREF_CODE, $aHistoryEntries);
	}

	/**
	 * Return an array of past queries, including the query itself and its HTML label
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public static function GetLastQueries()
	{
		/** @var array $aHistoryEntries */
		$aHistoryEntries = appUserPreferences::GetPref(static::USER_PREF_CODE, []);
		static::TruncateHistory($aHistoryEntries);

		for($iIdx = 0; $iIdx < count($aHistoryEntries); $iIdx++){
			$sRawQuery = $aHistoryEntries[$iIdx]['query'];

			// Make icon URL absolute
			if(isset($aHistoryEntries[$iIdx]['icon_url'])){
				$aHistoryEntries[$iIdx]['icon_url'] = utils::GetAbsoluteUrlAppRoot().$aHistoryEntries[$iIdx]['icon_url'];
			}

			// Add HTML label if missing
			if(!isset($aHistoryEntries[$iIdx]['label_html'])) {
				$aHistoryEntries[$iIdx]['label_html'] = utils::EscapeHtml($sRawQuery);
			}

			// Add URL
			if(!isset($aHistoryEntries[$iIdx]['target_url'])){
				$aHistoryEntries[$iIdx]['target_url'] = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=full_text&text='.urlencode($sRawQuery);
			}
		}

		return $aHistoryEntries;
	}

	/**
	 * Truncate $aHistoryEntries to 'global_search.max_history_results' entries
	 *
	 * @param array $aHistoryEntries
	 */
	protected static function TruncateHistory(array &$aHistoryEntries): void
	{
		$iMaxHistoryResults = (int) MetaModel::GetConfig()->Get('global_search.max_history_results');
		if(count($aHistoryEntries) > $iMaxHistoryResults)
		{
			$aHistoryEntries = array_slice($aHistoryEntries, 0, $iMaxHistoryResults);
		}
	}
}