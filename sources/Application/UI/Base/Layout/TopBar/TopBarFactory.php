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

namespace Combodo\iTop\Application\UI\Base\Layout\TopBar;

use Combodo\iTop\Application\UI\Base\Component\Breadcrumbs\Breadcrumbs;
use Combodo\iTop\Application\UI\Base\Component\GlobalSearch\GlobalSearchFactory;
use Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreateFactory;
use Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBarAction\TopBarQuickActionFactory;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use iPopupMenuExtension;
use utils;

/**
 * Class TopBarFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\TopBar
 * @internal
 * @since 3.0.0
 */
class TopBarFactory
{
	/**
	 * Make a standard TopBar layout for backoffice pages
	 *
	 * @param array|null $aBreadcrumbsEntry Current breadcrumbs entry to add
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBar
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function MakeStandard(?array $aBreadcrumbsEntry = null)
	{
		$oTopBar = new TopBar(TopBar::BLOCK_CODE);

		if (utils::GetConfig()->Get('quick_create.enabled') === true) {
			$oTopBar->SetQuickCreate(QuickCreateFactory::MakeFromUserHistory());
		}

		if (utils::GetConfig()->Get('global_search.enabled') === true) {
			$oTopBar->SetGlobalSearch(GlobalSearchFactory::MakeFromUserHistory());
		}

		/** @var \iPopupMenuExtension $oExtensionInstance */
		foreach (InterfaceDiscovery::GetInstance()->FindItopClasses('iPopupMenuExtension') as $oExtensionInstance) {
			foreach ($oExtensionInstance::EnumItems(iPopupMenuExtension::MENU_TOPBAR_ACTIONS, []) as $oMenuItem) {
				$oTopBar->AddAction(TopBarQuickActionFactory::MakeFromApplicationPopupItem($oMenuItem));
			}
		}

		if (utils::GetConfig()->Get('breadcrumb.enabled') === true) {
			$oBreadcrumbs = new Breadcrumbs($aBreadcrumbsEntry, Breadcrumbs::BLOCK_CODE);

			$oTopBar->SetBreadcrumbs($oBreadcrumbs);
		}

		return $oTopBar;
	}
}
