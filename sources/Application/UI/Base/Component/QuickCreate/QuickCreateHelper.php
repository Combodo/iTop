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
use UserRights;

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
		static::Truncate($aHistoryEntries, 'quick_create.max_history_results');

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

		static::Truncate($aHistoryEntries, 'quick_create.max_history_results');

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
	 * Return an array of popular object classes
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function GetPopularClasses(): array
	{
		$aHistoryEntries = appUserPreferences::GetPref(static::USER_PREF_CODE, []);
		static::Truncate($aHistoryEntries, 'quick_create.max_history_results');
		$aPopularClassesNames = UserRights::GetAllowedClasses(UR_ACTION_CREATE, array('popular'), false);

		// Prevent classes in both Popular and Recent to appear twice
		for($iIdx = 0; $iIdx < count($aHistoryEntries); $iIdx++)
		{
			if (($key = array_search($aHistoryEntries[$iIdx]['class'], $aPopularClassesNames)) !== false) {
				unset($aPopularClassesNames[$key]);
			}
		}
		//die(var_dump($aPopularClassesNames));
		static::Truncate($aPopularClassesNames, 'quick_create.max_popular_results');
		$aPopularClasses = array();
		foreach($aPopularClassesNames as $sClass)
		{
			if (!MetaModel::IsValidClass($sClass)) {
				continue;
			}

			// Add class icon
			$sClassIconUrl = MetaModel::GetClassIcon($sClass, false);
			// Mind that some classes don't have an icon
			$sClassIconUrl = !empty($sClassIconUrl) ? $sClassIconUrl : null;

			// Add class label
			$sLabelHtml = utils::EscapeHtml(MetaModel::GetName($sClass));

			// Add URL
			$sTargetUrl = DBObject::ComputeStandardUIPage($sClass).'?operation=new&class='.$sClass;

			$aPopularClasses[] =  array(
				'class' => $sClass,
				'icon_url' => $sClassIconUrl,
				'label_html' => $sLabelHtml,
				'target_url' => $sTargetUrl
			);
		}
		return $aPopularClasses;
	}

	/**
	 * Truncate an array to $sMaxEntriesParam
	 *
	 * @param array $aEntries
	 * @param string $sMaxEntriesParam
	 */
	protected static function Truncate(array &$aEntries, string $sMaxEntriesParam = 'global_search.max_history_results'): void
	{
		$iMaxResults = (int) MetaModel::GetConfig()->Get($sMaxEntriesParam);
		if(count($aEntries) > $iMaxResults)
		{
			$aEntries = array_slice($aEntries, 0, $iMaxResults);
		}
	}


}