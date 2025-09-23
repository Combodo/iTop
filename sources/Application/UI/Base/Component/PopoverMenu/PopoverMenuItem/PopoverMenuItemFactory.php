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
	 * Make a Pop*over*MenuItem (3.0 UI) from a Pop*up*MenuItem (Extensions API)
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

	/**
	 * Make a PopoverMenuItem from an action data as return by {@see ApplicationPopupMenuItem::GetMenuItem()}
	 *
	 * @param string $sActionId
	 * @param array $aActionData
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem
	 * @throws \Exception
	 */
	public static function MakeFromApplicationPopupMenuItemData(string $sActionId, array $aActionData)
	{
		$aRefactoredItem = [
			'uid' => $sActionId,
			'css_classes' => isset($aActionData['css_classes']) ? $aActionData['css_classes'] : [],
			'on_click' => isset($aActionData['onclick']) ? $aActionData['onclick'] : '',
			'target' => isset($aActionData['target']) ? $aActionData['target'] : '',
			'url' => $aActionData['url'],
			'label' => $aActionData['label'],
			'icon_class' => isset($aActionData['icon_class']) ? $aActionData['icon_class'] : '',
			'tooltip' => isset($aActionData['tooltip']) ? $aActionData['tooltip'] : '',
		];

		// Avoid meaningless tooltips which are identical to the label
		if ($aRefactoredItem['tooltip'] == $aRefactoredItem['label']) {
			$aRefactoredItem['tooltip'] = '';
		}

		if (!empty($aRefactoredItem['on_click'])) {
			// JS
			$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new JSPopupMenuItem(
					$aRefactoredItem['uid'],
					$aRefactoredItem['label'],
					$aRefactoredItem['on_click'])
			);
		} elseif (!empty($aRefactoredItem['url'])) {
			// URL
			$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new URLPopupMenuItem(
					$aRefactoredItem['uid'],
					$aRefactoredItem['label'],
					$aRefactoredItem['url'],
					$aRefactoredItem['target'])
			);
		} else {
			// Separator
			$oPopoverMenuItem = PopoverMenuItemFactory::MakeSeparator();
		}

		if (!empty($aRefactoredItem['css_classes'])) {
			$oPopoverMenuItem->SetCssClasses($aRefactoredItem['css_classes']);
		}
		if (!empty($aRefactoredItem['icon_class'])) {
			$oPopoverMenuItem->SetIconClass($aRefactoredItem['icon_class']);
		}
		if (!empty($aRefactoredItem['tooltip'])) {
			$oPopoverMenuItem->SetTooltip($aRefactoredItem['tooltip']);
		}

		return $oPopoverMenuItem;
	}

	/**
	 * Make a separator item for the popover menu
	 *
	 * Note: You don't need to add separators manually if you put the items in dedicated sections of the menu
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\SeparatorPopoverMenuItem
	 * @since 3.0.0
	 */
	public static function MakeSeparator()
	{
		return new SeparatorPopoverMenuItem(new SeparatorPopupMenuItem());
	}
}