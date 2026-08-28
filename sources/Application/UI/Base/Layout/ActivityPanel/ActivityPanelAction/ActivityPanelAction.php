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

use Combodo\iTop\Application\UI\Base\Common\Action\tActionCommon;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\UIBlock;
use utils;

/**
 * Class ActivityPanelAction
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelAction
 * @internal
 * @since 3.3.0
 */
abstract class ActivityPanelAction extends UIBlock
{
	use tActionCommon;
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-activity-panel-action';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/activity-panel-action/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/activity-panel-action/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
	];
	public const DEFAULT_CSS_FILES_REL_PATH = [
	];

	/** @var array<string, array<\ApplicationPopupMenuItem>> */
	protected array $aPopupItems = [];
	protected ?PopoverMenu $oPopoverMenu = null;

	public function __construct(string $sLabel, string $sIconClass, ?string $sTooltip, string $sUid)
	{
		parent::__construct(utils::GetSafeId($sUid));
		$this->sUid = $sUid;
		$this->sLabel = $sLabel;
		$this->sIconClass = $sIconClass;
		$this->sTooltip = $sTooltip;
	}

	public function SetPopupItems(array $aPopupItems): static
	{
		$this->aPopupItems = $aPopupItems;
		return $this;
	}

	public function AddPopupItem(string $sSectionId, \ApplicationPopupMenuItem $oPopupItem): static
	{
		if (!array_key_exists($sSectionId, $this->aPopupItems)) {
			$this->aPopupItems[$sSectionId] = [];
		}

		$this->aPopupItems[$sSectionId][] = $oPopupItem;
		return $this;
	}

	public function GetPopupItems(): array
	{
		return $this->aPopupItems;
	}

	public function SetPopoverMenu(PopoverMenu $oPopoverMenu): static
	{
		$this->oPopoverMenu = $oPopoverMenu;
		return $this;
	}

	public function GetPopoverMenu(): ?PopoverMenu
	{
		return $this->oPopoverMenu;
	}

	public function HasPopoverMenu(): bool
	{
		return $this->oPopoverMenu !== null;
	}

	public function GetAriaAttributes(): array
	{
		$aDefaultValues = [];
		// Default value for aria-label is the tooltip
		if (utils::IsNotNullOrEmptyString($this->sTooltip)) {
			$aDefaultValues['label'] = $this->sTooltip;
		}

		return array_merge($aDefaultValues, parent::GetAriaAttributes());
	}

	public function HasAriaAttributes(): bool
	{
		return  !empty($this->GetAriaAttributes());
	}

	public function GetSubBlocks(): array
	{
		if (false === $this->HasPopoverMenu()) {
			return [];
		}

		return [$this->oPopoverMenu->GetId() => $this->oPopoverMenu];
	}
}
