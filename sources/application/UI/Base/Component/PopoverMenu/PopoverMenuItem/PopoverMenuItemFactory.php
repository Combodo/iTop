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

namespace Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem;



use ApplicationPopupMenuItem;
use JSPopupMenuItem;
use SeparatorPopupMenuItem;
use URLPopupMenuItem;

/**
 * Class PopupMenuItemFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem
 * @internal
 * @since 3.0.0
 */
class PopoverMenuItemFactory
{
	/**
	 * Make a standard NavigationMenu layout for backoffice pages
	 *
	 * @param \ApplicationPopupMenuItem $oItem
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem
	 */
	public static function MakeFromApplicationPopupMenuItem(ApplicationPopupMenuItem $oItem)
	{
		$sNamespace = 'Combodo\\iTop\\Application\\UI\\Base\\Component\\PopoverMenu\\PopoverMenuItem\\';
		switch(true)
		{
			case $oItem instanceof URLPopupMenuItem:
				$sTargetClass = 'UrlPopoverMenuItem';
				break;
			case $oItem instanceof JSPopupMenuItem:
				$sTargetClass = 'JsPopoverMenuItem';
				break;			
			case $oItem instanceof SeparatorPopupMenuItem:
				$sTargetClass = 'SeparatorPopoverMenuItem';
				break;
			default:
				$sTargetClass = 'PopoverMenuItem';
				break;
		}
		$sTargetClass = $sNamespace.$sTargetClass;

		return new $sTargetClass($oItem);
	}
}