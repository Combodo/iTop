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

namespace Combodo\iTop\Application\UI\Base\Component\PopoverMenu\NewsroomMenu;

use appUserPreferences;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use iNewsroomProvider;
use MetaModel;
use UserRights;
use utils;

/**
* Class NewsroomMenuFactory
*
* @author Stephen Abello <stephen.abello@combodo.com>
* @package Combodo\iTop\Application\UI\Base\Component\NewsroomMenu
* @internal
* @since 3.0.0
*/
class NewsroomMenuFactory
{
	/**
	 * Make a standard NewsroomMenu layout for backoffice pages
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\NewsroomMenu\NewsroomMenu
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
		/** @var iNewsroomProvider[] $aProviders */
		$aProviders = InterfaceDiscovery::GetInstance()->FindItopClasses(iNewsroomProvider::class);
		foreach($aProviders as $cProvider) {
			$oProvider = new $cProvider();
			$oConfig = MetaModel::GetConfig();
			$oProvider->SetConfig($oConfig);
			$bProviderEnabled = appUserPreferences::GetPref('newsroom_provider_'.get_class($oProvider), true);
			if ($bProviderEnabled && $oProvider->IsApplicable($oUser)) {
				$aProviderParams[] = array(
					'label' => $oProvider->GetLabel(),
					'fetch_url' => $oProvider->GetFetchURL(),
					'target' => utils::StartsWith($oProvider->GetFetchURL(), $oConfig->Get('app_root_url')) ? '_self' : '_blank',
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
			'no_message_icon' => file_get_contents(APPROOT.'images/illustrations/undraw_social_serenity.svg'),
			'placeholder_image_icon' => $sPlaceholderImageUrl,
			'cache_uuid' => 'itop-newsroom-'.UserRights::GetUserId().'-'.md5(APPROOT),
			'providers' => $aProviderParams,
			'display_limit' => (int)appUserPreferences::GetPref('newsroom_display_size', 7),
			'labels' => array(
				'no_notification' => 'UI:Newsroom:NoNewMessage',
				'x_notifications' => 'UI:Newsroom:XNewMessage',
				'mark_all_as_read' => 'UI:Newsroom:MarkAllAsRead',
				'view_all' => 'UI:Newsroom:ViewAllMessages'
			),
		);
		return $aParams;
	}
}