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

namespace Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBarAction;

use JSPopupMenuItem;
use SeparatorPopupMenuItem;
use URLPopupMenuItem;

/**
 * Class TopBarActionFactory
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\TopBar\TopBarAction
 * @internal
 * @since 3.0.0
 */
class TopBarQuickActionFactory
{
	public static function MakeFromApplicationPopupItem($oApplicationPopupItem): ?TopBarQuickAction
	{
		if ($oApplicationPopupItem instanceof JSPopupMenuItem) {
			$oTopBarAction = new TopBarQuickActionJS(
				$oApplicationPopupItem->GetLabel(),
				$oApplicationPopupItem->GetIconClass(),
				$oApplicationPopupItem->GetTooltip(),
				$oApplicationPopupItem->GetUID()
			);
			$oTopBarAction->SetJsCode($oApplicationPopupItem->GetJsCode());
			$oTopBarAction->SetUrl($oApplicationPopupItem->GetUrl());
			$oTopBarAction->AddMultipleJsFilesRelPaths($oApplicationPopupItem->GetLinkedScripts());
			$oTopBarAction->SetAriaAttributes($oApplicationPopupItem->GetAriaAttributes()->GetAttributes());
			$oTopBarAction->SetDataAttributes($oApplicationPopupItem->GetDataAttributes()->GetAttributes());
			return $oTopBarAction;
		} elseif ($oApplicationPopupItem instanceof URLPopupMenuItem) {
			$oTopBarAction = new TopBarQuickActionURL(
				$oApplicationPopupItem->GetLabel(),
				$oApplicationPopupItem->GetIconClass(),
				$oApplicationPopupItem->GetTooltip(),
				$oApplicationPopupItem->GetUID()
			);
			$oTopBarAction->SetUrl($oApplicationPopupItem->GetUrl());
			$oTopBarAction->SetTarget($oApplicationPopupItem->GetTarget());
			$oTopBarAction->SetAriaAttributes($oApplicationPopupItem->GetAriaAttributes()->GetAttributes());
			$oTopBarAction->SetDataAttributes($oApplicationPopupItem->GetDataAttributes()->GetAttributes());
			return $oTopBarAction;
		} elseif ($oApplicationPopupItem instanceof SeparatorPopupMenuItem) {
			$oTopBarAction = new TopBarQuickActionSeparator($oApplicationPopupItem->GetUID());
			$oTopBarAction->SetCSSClasses($oApplicationPopupItem->GetCssClasses());
			$oTopBarAction->SetAriaAttributes($oApplicationPopupItem->GetAriaAttributes()->GetAttributes());
			$oTopBarAction->SetDataAttributes($oApplicationPopupItem->GetDataAttributes()->GetAttributes());
			return $oTopBarAction;
		}

		// Should never happen, but just in case a new type is added, return null
		return null;
	}
}
