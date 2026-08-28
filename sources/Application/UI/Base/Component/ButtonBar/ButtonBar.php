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

namespace Combodo\iTop\Application\UI\Base\Component\ButtonBar;

use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonJS;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonSeparator;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroup;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItemFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use InvalidArgumentException;
use utils;

/**
 * A button bar that moves overflowing entries into a popover menu.
 *
 * @api
 * @since 3.3.0
 */
class ButtonBar extends UIContentBlock
{
	public const BLOCK_CODE = 'ibo-button-bar';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/button-bar/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/components/button-bar.js',
	];

	private const OVERFLOW_SECTION_ID = 'overflow';
	public const OVERFLOW_MODE_FIT = 'fit';
	public const OVERFLOW_MODE_COUNT = 'count';
	public const OVERFLOW_MODE_AFTER_MARKER = 'after-marker';

	/** @var string|null More button tooltip text */
	protected ?string $sMoreTooltipText;

	/** @var PopoverMenu Popover menu for overflow */
	protected PopoverMenu $oPopoverMenu;

	/** @var ButtonJS Button for overflow */
	protected ButtonJS $oOverflowTogglerButton;

	/** @var array Button bar buttons */
	private array $aButtons = [];

	/** @var string Overflow mode used by the custom element */
	private string $sOverflowMode = self::OVERFLOW_MODE_FIT;

	/** @var int Number of visible items before overflow in count mode */
	private int $iOverflowCount = 3;

	/** @var string|null Button id used as overflow marker in after-marker mode */
	private ?string $sOverflowStartAfterButtonId = null;

	public function __construct(array $aSubBlocks = [], ?string $sMoreButtonTooltipText = null, ?string $sId = null)
	{
		parent::__construct($sId);

		$this->sMoreTooltipText = $sMoreButtonTooltipText;
		$this->oOverflowTogglerButton = ButtonUIBlockFactory::MakeIconAction(
			'fas fa-ellipsis-v',
			$this->sMoreTooltipText ?: '',
			null,
			null,
			false,
			$this->GetOverflowTogglerId()
		)
			->SetActionType(Button::ENUM_ACTION_TYPE_ALTERNATIVE)
			->SetColor(Button::ENUM_COLOR_SCHEME_NEUTRAL);

		$this->oPopoverMenu = (new PopoverMenu($this->GetId().'--menu'))
			->SetTogglerFromId($this->GetOverflowTogglerId())
			->SetContainer(PopoverMenu::ENUM_CONTAINER_BODY)
			->SetHorizontalPosition(PopoverMenu::ENUM_HORIZONTAL_POSITION_ALIGN_INNER_RIGHT)
			->SetVerticalPosition(PopoverMenu::ENUM_VERTICAL_POSITION_BELOW)
			->AddCSSClass('ibo-button-bar--popover')
			->AddSection(static::OVERFLOW_SECTION_ID);

		$this->RefreshOverflowDataAttributes();

		$this->SetButtons($aSubBlocks);
	}

	public function SetMoreButtonTooltipText(string $sMoreButtonTooltipText): self
	{
		$this->sMoreTooltipText = $sMoreButtonTooltipText;
		$this->oOverflowTogglerButton->SetTooltip($sMoreButtonTooltipText);

		return $this;
	}

	public function GetMoreButtonTooltipText(): string
	{
		return $this->sMoreTooltipText;
	}

	public function GetOverflowTogglerId(): string
	{
		return $this->GetId().'--toggler';
	}

	public function GetOverflowTogglerButton(): Button
	{
		return $this->oOverflowTogglerButton;
	}

	public function GetPopoverMenu(): PopoverMenu
	{
		return $this->oPopoverMenu;
	}

	/**
	 * @return string One of self::OVERFLOW_MODE_*
	 */
	public function GetOverflowMode(): string
	{
		return $this->sOverflowMode;
	}

	/**
	 * @param string $sOverflowMode One of self::OVERFLOW_MODE_*
	 *
	 * @return $this
	 */
	public function SetOverflowMode(string $sOverflowMode): self
	{
		$aAllowedModes = [
			self::OVERFLOW_MODE_FIT,
			self::OVERFLOW_MODE_COUNT,
			self::OVERFLOW_MODE_AFTER_MARKER,
		];

		if (!in_array($sOverflowMode, $aAllowedModes, true)) {
			throw new InvalidArgumentException(sprintf('Unsupported overflow mode "%s" for %s', $sOverflowMode, static::class));
		}

		$this->sOverflowMode = $sOverflowMode;
		$this->RefreshOverflowDataAttributes();

		return $this;
	}

	public function GetOverflowCount(): int
	{
		return $this->iOverflowCount;
	}

	public function SetOverflowCount(int $iOverflowCount): self
	{
		if ($iOverflowCount < 0) {
			throw new InvalidArgumentException(sprintf('Overflow count must be >= 0, got "%d"', $iOverflowCount));
		}

		$this->iOverflowCount = $iOverflowCount;
		$this->RefreshOverflowDataAttributes();

		return $this;
	}

	public function GetOverflowStartAfterButtonId(): ?string
	{
		return $this->sOverflowStartAfterButtonId;
	}

	public function SetOverflowStartAfterButtonId(?string $sButtonId): self
	{
		$this->sOverflowStartAfterButtonId = $sButtonId;
		$this->RefreshOverflowDataAttributes();

		return $this;
	}

	public function IsOverflowStartAfterButton(string $sButtonId): bool
	{
		return $this->sOverflowStartAfterButtonId !== null && $this->sOverflowStartAfterButtonId === $sButtonId;
	}

	public function AddButton(Button|ButtonSeparator|ButtonGroup $oButton): self
	{
		$this->aButtons[$oButton->GetId()] = $oButton;

		$this->RebuildPopoverItems();

		return $this;
	}

	public function RemoveButton(string $sId): self
	{
		if ($this->HasButton($sId)) {
			unset($this->aButtons[$sId]);
		}

		$this->RebuildPopoverItems();

		return $this;
	}

	public function GetButtons(): array
	{
		return $this->aButtons;
	}

	public function SetButtons(array $aButtons): self
	{
		$this->aButtons = $aButtons;

		$this->RebuildPopoverItems();

		return $this;
	}

	public function HasButton(string $sId): bool
	{
		return array_key_exists($sId, $this->aButtons);
	}

	public function GetOverflowItemIdFromBlockId(string $sBlockId): string
	{
		return $this->GetId().'--item--'.utils::GetSafeId($sBlockId);
	}

	protected function RebuildPopoverItems(): void
	{
		$this->oPopoverMenu->ClearSection(static::OVERFLOW_SECTION_ID);

		foreach ($this->GetButtons() as $oBlock) {
			if (!($oBlock instanceof Button) && !($oBlock instanceof ButtonSeparator)) {
				continue;
			}

			$sBlockId = $oBlock->GetId();
			$sUid = $this->GetId().'--menu-item--'.utils::GetSafeId($sBlockId);

			$oPopoverMenuItem = PopoverMenuItemFactory::MakeApplicationPopupMenuItemFromButton($oBlock, $sUid);

			if ($oPopoverMenuItem !== null) {
				$oPopoverMenuItem
					->SetDataAttributes([
						'overflow-item-id' => $this->GetOverflowItemIdFromBlockId($sBlockId),
					]);
				$this->oPopoverMenu->AddItem(static::OVERFLOW_SECTION_ID, $oPopoverMenuItem);
			}
		}
	}

	protected function RefreshOverflowDataAttributes(): void
	{
		$aDataAttributes = $this->GetDataAttributes();
		$aDataAttributes['overflow-mode'] = $this->GetOverflowMode();
		$aDataAttributes['overflow-count'] = (string) $this->GetOverflowCount();

		if ($this->GetOverflowStartAfterButtonId() !== null && $this->GetOverflowStartAfterButtonId() !== '') {
			$aDataAttributes['overflow-start-after-button-id'] = $this->GetOverflowStartAfterButtonId();
		} else {
			unset($aDataAttributes['overflow-start-after-button-id']);
		}

		$this->SetDataAttributes($aDataAttributes);
	}

	public function GetSubBlocks(): array
	{
		$aSubBlocks = [];
		$aSubBlocks[$this->GetOverflowTogglerButton()->GetId()] = $this->GetOverflowTogglerButton();
		$aSubBlocks[$this->GetPopoverMenu()->GetId()] = $this->GetPopoverMenu();

		foreach ($this->GetButtons() as $oButton) {
			$aSubBlocks[$oButton->GetId()] = $oButton;
		}

		return $aSubBlocks;
	}

}
