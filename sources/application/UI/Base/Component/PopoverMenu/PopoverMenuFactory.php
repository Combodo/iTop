<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Base\Component\PopoverMenu;



use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Dict;
use iPopupMenuExtension;
use JSPopupMenuItem;
use MetaModel;
use SeparatorPopupMenuItem;
use URLPopupMenuItem;
use UserRights;
use utils;

/**
 * Class PopoverMenuFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\PopoverMenu
 * @internal
 * @since 3.0.0
 */
class PopoverMenuFactory
{
	/**
	 * Make a standard NavigationMenu layout for backoffice pages
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public static function MakeUserMenuForNavigationMenu()
	{
		$oMenu = new PopoverMenu('ibo-navigation-menu--user-menu');
		$oMenu->SetTogglerJSSelector('[data-role="ibo-navigation-menu--user-menu--toggler"]')
			->SetContainer(PopoverMenu::ENUM_CONTAINER_BODY)
			->SetHorizontalPosition(PopoverMenu::ENUM_HORIZONTAL_POSITION_ALIGN_OUTER_RIGHT)
			->SetVerticalPosition(PopoverMenu::ENUM_VERTICAL_POSITION_ABOVE);

		$aUserMenuItems = [];

		// Allowed portals
		$aAllowedPortalsItems = static::PrepareAllowedPortalsItemsForUserMenu();
		self::AddPopoverMenuItems($aAllowedPortalsItems, $aUserMenuItems);

		// User related pages
		self::AddPopoverMenuItems(static::PrepareUserRelatedItemsForUserMenu(), $aUserMenuItems);

		// API: iPopupMenuExtension::MENU_USER_ACTIONS
		$aAPIItems = static::PrepareAPIItemsForUserMenu($oMenu);
		self::AddPopoverMenuItems($aAPIItems, $aUserMenuItems);

		// Misc links
		/*$oMenu->AddSection('misc')
			->SetItems('misc', static::PrepareMiscItemsForUserMenu());*/
		self::AddPopoverMenuItems(static::PrepareMiscItemsForUserMenu(), $aUserMenuItems);

		self::SortPopoverMenuItems($aUserMenuItems);

		$oMenu->AddSection('misc')
			->AddItems('misc', $aUserMenuItems);
		return $oMenu;
	}

	/**
	 * @param PopoverMenuItem[] $aPopoverMenuItem
	 * @param PopoverMenuItem[] $aUserMenuItems
	 *
	 * @return void
	 */
	private static function AddPopoverMenuItems(array $aPopoverMenuItem, array &$aUserMenuItems) : void {
		foreach ($aPopoverMenuItem as $oPopoverMenuItem){
			$aUserMenuItems[$oPopoverMenuItem->GetUID()] = $oPopoverMenuItem;
		}
	}

	/**
	 * @param PopoverMenuItem[] $aPopoverMenuItem
	 * @param PopoverMenuItem[] $aUserMenuItems
	 *
	 * @return void
	 */
	private static function SortPopoverMenuItems(array &$aUserMenuItems) : void {
		$aSortedMenusFromConfig = MetaModel::GetConfig()->Get('navigation_menu.sorted_popup_user_menu_items');
		if (!is_array($aSortedMenusFromConfig) || empty($aSortedMenusFromConfig)){
			return;
		}

		$aSortedMenus = [];
		foreach ($aSortedMenusFromConfig as $sMenuUID){
			if (array_key_exists($sMenuUID, $aUserMenuItems)){
				$aSortedMenus[]=$aUserMenuItems[$sMenuUID];
				unset($aUserMenuItems[$sMenuUID]);
			}
		}

		foreach ($aUserMenuItems as $oMenu){
			$aSortedMenus[]=$oMenu;
		}
		$aUserMenuItems = $aSortedMenus;
	}
	
	/**
	 * Return the allowed portals items for the current user
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem[]
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
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem[]
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

		return $aItems;
	}

	/**
	 * @param \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu $oMenu Here we must pass a block ($oMenu) as the helper will use it to dispatch the external resources (files) if some.
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem[] Return the items from the iPopupMenuExtension::MENU_USER_ACTIONS API
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected static function PrepareAPIItemsForUserMenu(PopoverMenu &$oMenu)
	{
		$aOriginalItems = [];
		utils::GetPopupMenuItemsBlock($oMenu, iPopupMenuExtension::MENU_USER_ACTIONS, null, $aOriginalItems);

		$aTransformedItems = [];
		foreach($aOriginalItems as $sItemID => $aItemData) {
			$aTransformedItems[] = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItemData($sItemID, $aItemData);
		}

		return $aTransformedItems;
	}

	/**
	 * Return the misc. items for the user menu (online doc., about box)
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem[]
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
				'return ShowAboutBox("'.Dict::S('UI:AboutBox').'");'
			)
		);

		return $aItems;
	}

	/**
	 * Make a menu for the $aActions as prepared by \DisplayBlock
	 *
	 * @param string $sId
	 * @param array $aActions
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu
	 * @throws \Exception
	 */
	public static function MakeMenuForActions(string $sId, array $aActions): PopoverMenu
	{
		// Prepare sections and actions
		$iSectionIndex = 0;
		$aMenuItems = [];
		foreach ($aActions as $sActionId => $aAction) {
			// Skip separators as they are "transformed" into sections
			if (empty($aAction['url'])) {
				$iSectionIndex++;
				continue;
			}

			$aMenuItems["{$sId}_section_{$iSectionIndex}"][$sActionId] = $aAction;
		}

		// Prepare actual menu
		$oMenu = new PopoverMenu($sId);

		$bFirst = true;
		foreach ($aMenuItems as $sSection => $aActions) {
			$oMenu->AddSection($sSection);

			if (!$bFirst) {
				$oMenu->AddItem($sSection, PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
					new SeparatorPopupMenuItem()
				));
			}

			foreach ($aActions as $sActionId => $aAction) {
				$oMenu->AddItem($sSection, PopoverMenuItemFactory::MakeFromApplicationPopupMenuItemData($sActionId, $aAction));
			}

			$bFirst = false;
		}

		return $oMenu;
	}
}
