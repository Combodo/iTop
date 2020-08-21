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

namespace Combodo\iTop\Application\UI\Component\PopoverMenu\NewsroomMenu;

use appUserPreferences;
use Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Component\PopoverMenu\NewsroomMenu\NewsroomMenu;
use Dict;
use MetaModel;
use UserRights;

/**
* Class NewsroomMenuFactory
*
* @author Stephen Abello <stephen.abello@combodo.com>
* @package Combodo\iTop\Application\UI\Component\NewsroomMenu
* @internal
* @since 2.8.0
*/
class NewsroomMenuFactory
{
	/**
	 * Make a standard NewsroomMenu layout for backoffice pages
	 *
	 * @return \Combodo\iTop\Application\UI\Component\PopoverMenu\NewsroomMenu\NewsroomMenu
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function MakeNewsroomMenuForNavigationMenu()
	{
			$oMenu = new NewsroomMenu('ibo-navigation-menu--notifications-menu');
			$oMenu->SetParams(static::PrepareParametersForNewsroomMenu());
			
			return $oMenu;
	}

	/**
	 * Prepare parameters for the newsroom JS widget
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	protected static function PrepareParametersForNewsroomMenu()
	{
		$aProviderParams=[];
		$oUser = UserRights::GetUserObject();
		/**
		 * @var \iNewsroomProvider[] $aProviders
		 */
		$aProviders = MetaModel::EnumPlugins('iNewsroomProvider');
		foreach($aProviders as $oProvider)
		{
			$oProvider->SetConfig(MetaModel::GetConfig());
			$bProviderEnabled = appUserPreferences::GetPref('newsroom_provider_'.get_class($oProvider),true);
			if ($bProviderEnabled && $oProvider->IsApplicable($oUser))
			{
				$aProviderParams[] = array(
					'label' => $oProvider->GetLabel(),
					'fetch_url' => $oProvider->GetFetchURL(),
					'view_all_url' => $oProvider->GetViewAllURL(),
					'mark_all_as_read_url' => $oProvider->GetMarkAllAsReadURL(),
					'placeholders' => $oProvider->GetPlaceholders(),
					'ttl' => $oProvider->GetTTL(),
				);
			}
		}
		$sImageUrl= 'fas fa-comment-dots';
		$sPlaceholderImageUrl= 'far fa-envelope';
		$aParams = array(
			'image_icon' => $sImageUrl,
			'no_message_icon' => file_get_contents(APPROOT.'images/illustrations/undraw_empty.svg'),
			'placeholder_image_icon' => $sPlaceholderImageUrl,
			'cache_uuid' => 'itop-newsroom-'.UserRights::GetUserId().'-'.md5(APPROOT),
			'providers' => $aProviderParams,
			'display_limit' => (int)appUserPreferences::GetPref('newsroom_display_size', 7),
			'labels' => array(
				'no_message' => Dict::S('UI:Newsroom:NoNewMessage'),
				'mark_all_as_read' => Dict::S('UI:Newsroom:MarkAllAsRead'),
				'view_all' => Dict::S('UI:Newsroom:ViewAllMessages'),
			),
		);
		return $aParams;
	}
}