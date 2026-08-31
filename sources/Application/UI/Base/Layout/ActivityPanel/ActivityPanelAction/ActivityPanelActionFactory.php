<?php

/**
 * Copyright (C) 2013-2026 Combodo SAS
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

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelAction;

use ApplicationPopupMenuItem;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use JSPopupMenuItem;
use SeparatorPopupMenuItem;
use URLPopupMenuItem;

/**
 * Class ActivityPanelActionJS
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelAction
 * @internal
 * @since 3.3.0
 */
class ActivityPanelActionFactory
{
	/**
	 * @param array $aPopupItems as follow:
	 * [ 'case-log-attribute-code' => [ ApplicationPopupMenuItem1, ... ], ... ]
	 * or
	 * [ 'case-log-attribute-code' => ['label' => 'Displayed label', 'items' => [ ApplicationPopupMenuItem1, ... ]], ... ]
	 * @return ActivityPanelAction[]
	 * @throws \Exception
	 */
	public static function MakeFromApplicationPopupItems(array $aPopupItems): array
	{
		$aActionsByUid = [];

		foreach ($aPopupItems as $sSectionId => $aSectionData) {
			$sSectionLabel = (string)$sSectionId;
			$aSectionPopupItems = $aSectionData;
			if (is_array($aSectionData) && array_key_exists('items', $aSectionData)) {
				$aSectionPopupItems = $aSectionData['items'];
				if (array_key_exists('label', $aSectionData)) {
					$sSectionLabel = (string)$aSectionData['label'];
				}
			}

			if (!is_array($aSectionPopupItems)) {
				$aSectionPopupItems = [$aSectionPopupItems];
			}

			// Special index as separators aren't meant to be multiple action across different caselogs
			$iSectionSeparatorIndex = 0;
			foreach ($aSectionPopupItems as $oPopupItem) {
				if (!$oPopupItem instanceof ApplicationPopupMenuItem) {
					continue;
				}

				$sUid = $oPopupItem->GetUID();
				if ($oPopupItem instanceof SeparatorPopupMenuItem) {
					$iSectionSeparatorIndex++;
					$sUid = 'ibo-activity-panel--activity-action-separator--'.$iSectionSeparatorIndex;
				}
				if (!isset($aActionsByUid[$sUid])) {
					$oActivityAction = static::MakeFromApplicationPopupItem($oPopupItem);
					if ($oActivityAction === null) {
						continue;
					}

					if (false === ($oActivityAction instanceof ActivityPanelActionSeparator)) {
						$oPopoverMenu = new PopoverMenu($oActivityAction->GetId().'-menu');
						$oPopoverMenu->SetContainer(PopoverMenu::ENUM_CONTAINER_BODY);
						$oPopoverMenu->SetTogglerFromBlock($oActivityAction);
						$oActivityAction->SetPopoverMenu($oPopoverMenu);
					}

					$aActionsByUid[$sUid] = $oActivityAction;
				}

				if ($aActionsByUid[$sUid] instanceof ActivityPanelActionSeparator) {
					continue;
				}

				$oPopupItemForPopoverMenuItem = clone $oPopupItem;
				$oPopupItemForPopoverMenuItem->SetLabel($sSectionLabel);
				$oPopupItemForPopoverMenuItem->SetIconClass('');

				$oPopoverMenuItem = PopoverMenuItemFactory::MakeFromApplicationPopupMenuItem($oPopupItemForPopoverMenuItem);
				$oPopoverMenuItem->AddDataAttribute('caselog-attribute-code', (string)$sSectionId);
				$aActionsByUid[$sUid]->GetPopoverMenu()->AddItem((string)$sSectionId, $oPopoverMenuItem);
			}
		}

		return array_values($aActionsByUid);
	}

	public static function MakeFromApplicationPopupItem($oPopupItem): ?ActivityPanelAction
	{
		if ($oPopupItem instanceof JSPopupMenuItem) {
			$sUid = $oPopupItem->GetUID();
			$oAction = new ActivityPanelActionJS(
				$oPopupItem->GetLabel(),
				$oPopupItem->GetIconClass(),
				$oPopupItem->GetTooltip(),
				$sUid
			);
			$oAction->SetJsCode($oPopupItem->GetJsCode());
			$oAction->SetUrl($oPopupItem->GetUrl());
			$oAction->AddMultipleJsFilesRelPaths($oPopupItem->GetLinkedScripts());
			$oAction->SetAriaAttributes($oPopupItem->GetAriaAttributes()->GetAttributes());
			$oAction->SetDataAttributes($oPopupItem->GetDataAttributes()->GetAttributes());

			return $oAction;
		} elseif ($oPopupItem instanceof URLPopupMenuItem) {
			$sUid = $oPopupItem->GetUID();
			$oAction = new ActivityPanelActionURL(
				$oPopupItem->GetLabel(),
				$oPopupItem->GetIconClass(),
				$oPopupItem->GetTooltip(),
				$sUid
			);
			$oAction->SetUrl($oPopupItem->GetUrl());
			$oAction->SetTarget($oPopupItem->GetTarget());
			$oAction->SetAriaAttributes($oPopupItem->GetAriaAttributes()->GetAttributes());
			$oAction->SetDataAttributes($oPopupItem->GetDataAttributes()->GetAttributes());

			return $oAction;
		} elseif ($oPopupItem instanceof SeparatorPopupMenuItem) {
			return new ActivityPanelActionSeparator($oPopupItem->GetUID());
		}

		// Should never happen, but just in case a new type is added, return null

		return null;
	}
}
