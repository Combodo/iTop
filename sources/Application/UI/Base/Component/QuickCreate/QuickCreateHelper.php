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

namespace Combodo\iTop\Application\UI\Base\Component\QuickCreate;


use appUserPreferences;
use DBObject;
use MetaModel;
use utils;

/**
 * Class QuickCreateHelper
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\QuickCreate
 * @internal
 * @since 3.0.0
 */
class QuickCreateHelper
{
	/** @var string */
	public const USER_PREF_CODE = 'quick_create_history';

	/**
	 * Add $sQuery to the history. History is limited to the static::MAX_HISTORY_SIZE last classes.
	 *
	 * @param string $sClass Class of the created object
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public static function AddClassToHistory(string $sClass)
	{
		$aNewEntry = [
			'class' => $sClass,
		];

		/** @var array $aHistoryEntries */
		$aHistoryEntries = appUserPreferences::GetPref(static::USER_PREF_CODE, []);

		// Remove same entry from history to avoid duplicates
		for ($iIdx = 0; $iIdx < count($aHistoryEntries); $iIdx++)
		{
			if ($aHistoryEntries[$iIdx]['class'] === $sClass)
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
	 * Return an array of past created object classes
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function GetLastClasses()
	{
		/** @var array $aHistoryEntries */
		$aHistoryEntries = appUserPreferences::GetPref(static::USER_PREF_CODE, []);
		static::TruncateHistory($aHistoryEntries);

		for($iIdx = 0; $iIdx < count($aHistoryEntries); $iIdx++)
		{
			$sClass = $aHistoryEntries[$iIdx]['class'];

			if (!MetaModel::IsValidClass($sClass)) {
				continue;
			}
			// Add class icon
			if(!isset($aHistoryEntries[$iIdx]['icon_url']))
			{
				$sClassIconUrl = MetaModel::GetClassIcon($sClass, false);
				// Mind that some classes don't have an icon
				if(!empty($sClassIconUrl))
				{
					$aHistoryEntries[$iIdx]['icon_url'] = $sClassIconUrl;
				}
			}

			// Add class label
			if(!isset($aHistoryEntries[$iIdx]['label_html']))
			{
				$aHistoryEntries[$iIdx]['label_html'] = utils::EscapeHtml(MetaModel::GetName($sClass));
			}

			// Add URL
			if(!isset($aHistoryEntries[$iIdx]['target_url']))
			{
				$aHistoryEntries[$iIdx]['target_url'] = DBObject::ComputeStandardUIPage($sClass).'?operation=new&class='.$sClass;
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
		$iMaxHistoryResults = (int) MetaModel::GetConfig()->Get('quick_create.max_history_results');
		if(count($aHistoryEntries) > $iMaxHistoryResults)
		{
			$aHistoryEntries = array_slice($aHistoryEntries, 0, $iMaxHistoryResults);
		}
	}
}