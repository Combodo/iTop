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
use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonJS;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonSeparator;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonURL;
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
		switch (true) {
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
			'data_attributes' => isset($aActionData['data_attributes']) && is_array($aActionData['data_attributes']) ? $aActionData['data_attributes'] : [],
			'aria_attributes' => isset($aActionData['aria_attributes']) && is_array($aActionData['aria_attributes']) ? $aActionData['aria_attributes'] : [],
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
					$aRefactoredItem['on_click']
				)
			);
		} elseif (!empty($aRefactoredItem['url'])) {
			// URL
			$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new URLPopupMenuItem(
					$aRefactoredItem['uid'],
					$aRefactoredItem['label'],
					$aRefactoredItem['url'],
					$aRefactoredItem['target']
				)
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
		if (!empty($aRefactoredItem['data_attributes'])) {
			$oPopoverMenuItem->SetDataAttributes($aRefactoredItem['data_attributes']);
		}
		if (!empty($aRefactoredItem['aria_attributes'])) {
			$oPopoverMenuItem->SetAriaAttributes($aRefactoredItem['aria_attributes']);
		}

		return $oPopoverMenuItem;
	}

	public static function MakeApplicationPopupMenuItemFromButton(Button|ButtonSeparator $oButton, string $sUid): PopoverMenuItem|SeparatorPopupMenuItem
	{
		$sLabel = '';
		if ($oButton instanceof Button) {
			$sLabel = $oButton->GetLabel();
			if ($sLabel === '' && $oButton->GetTooltip() !== '') {
				// Fallback for icon-only buttons: show the tooltip text in the dropdown item.
				$sLabel = $oButton->GetTooltip();
			}
		}

		if ($oButton instanceof ButtonSeparator) {
			$oPopoverMenuItem = PopoverMenuItemFactory::MakeSeparator();
		} elseif ($oButton instanceof ButtonURL) {
			$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new URLPopupMenuItem($sUid, $sLabel, $oButton->GetURL(), $oButton->GetTarget())
			);
		} elseif ($oButton instanceof ButtonJS) {
			$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new JSPopupMenuItem($sUid, $sLabel, $oButton->GetOnClickJsCode())
			);
		} elseif ($oButton->GetOnClickJsCode() !== '') {
			$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new JSPopupMenuItem($sUid, $sLabel, $oButton->GetOnClickJsCode())
			);
		} else {
			$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem(
				new URLPopupMenuItem($sUid, $sLabel, '#')
			);
		}

		if ($oButton instanceof Button) {
			if ($oButton->GetIconClass() !== '') {
				$oPopoverMenuItem->SetIconClass($oButton->GetIconClass());
			}
			if ($oButton->GetTooltip() !== '' && $oButton->GetTooltip() !== $sLabel) {
				$oPopoverMenuItem->SetTooltip($oButton->GetTooltip());
			}
			if ($oButton->HasDataAttributes()) {
				$oPopoverMenuItem->SetDataAttributes($oButton->GetDataAttributes());
			}
			if ($oButton->HasAriaAttributes()) {
				$oPopoverMenuItem->SetAriaAttributes($oButton->GetAriaAttributes());
			}
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
