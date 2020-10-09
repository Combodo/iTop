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

namespace Combodo\iTop\Application\UI\Component\PopoverMenu;



use Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Dict;
use JSPopupMenuItem;
use MetaModel;
use URLPopupMenuItem;
use UserRights;
use utils;

/**
 * Class PopoverMenuFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\PopoverMenu
 * @internal
 * @since 2.8.0
 */
class PopoverMenuFactory
{
	/**
	 * Make a standard NavigationMenu layout for backoffice pages
	 *
	 * @return \Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenu
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function MakeUserMenuForNavigationMenu()
	{
		$oMenu = new PopoverMenu('ibo-navigation-menu--user-menu');

		// Allowed portals
		$aAllowedPortalsItems = static::PrepareAllowedPortalsItemsForUserMenu();
		if(!empty($aAllowedPortalsItems))
		{
			$oMenu->AddSection('allowed_portals')
				->SetItems('allowed_portals', $aAllowedPortalsItems);
		}

		// User related pages
		$oMenu->AddSection('user_related')
			->SetItems('user_related', static::PrepareUserRelatedItemsForUserMenu());

		// Misc links
		$oMenu->AddSection('misc')
			->SetItems('misc', static::PrepareMiscItemsForUserMenu());

		return $oMenu;
	}
	
	/**
	 * Return the allowed portals items for the current user
	 *
	 * @return \Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem[]
	 */
	protected static function PrepareAllowedPortalsItemsForUserMenu()
	{
		$aItems = [];
		foreach (UserRights::GetAllowedPortals() as $aAllowedPortal)
		{
			if ($aAllowedPortal['id'] !== 'backoffice')
			{
				$oPopupMenuItem = new URLPopupMenuItem(
					'portal:'.$aAllowedPortal['id'],
					Dict::S($aAllowedPortal['label']),
					$aAllowedPortal['url'],
					'_blank'
				);
				$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem($oPopupMenuItem);
			}
		}

		return $aItems;
	}

	/**
	 * Return the user related items (preferences, change password, log off, ...)
	 *
	 * @return \Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem[]
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected static function PrepareUserRelatedItemsForUserMenu()
	{
		$aItems = [];

		// Preferences
		$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
			new URLPopupMenuItem(
				'UI:Preferences',
				Dict::S('UI:Preferences'),
				utils::GetAbsoluteUrlAppRoot().'pages/preferences.php'
			)
		);

		// Archive mode
		if(true === utils::IsArchiveMode())
		{
			$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new JSPopupMenuItem(
					'UI:ArchiveModeOff',
					Dict::S('UI:ArchiveModeOff'),
					'return ArchiveMode(false);'
				)
			);
		}
		elseif(UserRights::CanBrowseArchive())
		{
			$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new JSPopupMenuItem(
					'UI:ArchiveModeOn',
					Dict::S('UI:ArchiveModeOn'),
					'return ArchiveMode(true);'
				)
			);
		}

		// Logoff
		if(utils::CanLogOff())
		{
			$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new URLPopupMenuItem(
					'UI:LogOffMenu',
					Dict::S('UI:LogOffMenu'),
					utils::GetAbsoluteUrlAppRoot().'pages/logoff.php?operation=do_logoff'
				)
			);
		}

		// Change password
		if (UserRights::CanChangePassword())
		{
			$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new URLPopupMenuItem(
					'UI:ChangePwdMenu',
					Dict::S('UI:ChangePwdMenu'),
					utils::GetAbsoluteUrlAppRoot().'pages/UI.php?loginop=change_pwd'
				)
			);
		}

		// TODO: iPopupMenuExtension::MENU_USER_ACTIONS
		// Legacy code: utils::GetPopupMenuItems($this, iPopupMenuExtension::MENU_USER_ACTIONS, null, $aActions);

		return $aItems;
	}

	/**
	 * Return the misc. items for the user menu (online doc., about box)
	 *
	 * @return \Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem[]
	 */
	protected static function PrepareMiscItemsForUserMenu()
	{
		$aItems = [];

		// Online documentation
		$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
			new URLPopupMenuItem(
				'UI:Help',
				Dict::S('UI:Help'),
				MetaModel::GetConfig()->Get('online_help'),
				'_blank'
			)
		);

		// About box
		$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
			new JSPopupMenuItem(
				'UI:AboutBox',
				Dict::S('UI:AboutBox'),
				'return ShowAboutBox();'
			)
		);

		return $aItems;
	}

	public static function MakeMenuForActions(string $sId, array $aMenuItems): PopoverMenu
	{
		$oMenu = new PopoverMenu($sId);

		$bFirst = true;
		foreach ($aMenuItems as $sSection => $aActions) {
			$aItems = [];

			if (!$bFirst) {
				$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
					new \SeparatorPopupMenuItem()
				);
			}

			foreach ($aActions as $aAction) {
				if (!empty($aAction['on_click'])) {
					// JS
					$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
						new JSPopupMenuItem(
							$aAction['uid'],
							$aAction['label'],
							$aAction['on_click'])
					);
				} else {
					// URL
					$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
						new URLPopupMenuItem(
							$aAction['uid'],
							$aAction['label'],
							$aAction['url'],
							$aAction['target'])
					);
				}
				if (!empty($aAction['css_classes'])) {
					$oPopoverMenuItem->SetCssClasses($aAction['css_classes']);
				}
				$aItems[] = $oPopoverMenuItem;
			}

			$oMenu->AddSection($sSection)
				->SetItems($sSection, $aItems);
			$bFirst = false;
		}

		return $oMenu;
	}

	public static function MakeMenuForActivityNewEntryFormSubmit(array $aCaseLogs): PopoverMenu
	{
		$oMenu = new PopoverMenu();
		$sMenuId = $oMenu->GetId();

		$aItems = [];
		foreach ($aCaseLogs as $sCaseLogAttCode => $sCaseLogLabel) {
			// JS
			$aItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new JSPopupMenuItem(
					$sCaseLogAttCode,
					$sCaseLogLabel,
					<<<JS
$(this).parents('[data-role="ibo-activity-new-entry-form--action-buttons--right-actions"]').trigger('submit', ['caselog', '$sCaseLogAttCode']);
JS
				));
		}

		$oMenu->AddSection('ibo-activity-new-entry-new-entry--submit--caselogs')
			->SetItems('ibo-activity-new-entry-new-entry--submit--caselogs', $aItems);

		return $oMenu;
	}
}