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

namespace Combodo\iTop\Application\UI\Base\Component\PopoverMenu;


use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Exception;

/**
 * Class PopoverMenu
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\PopoverMenu
 * @internal
 * @since 3.0.0
 */
class PopoverMenu extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-popover-menu';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/popover-menu/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/popover-menu/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/components/popover-menu.js',
	];

	// Specific constants
	/** @see static::$sContainer */
	public const ENUM_CONTAINER_BODY = 'body';
	/** @see static::$sContainer */
	public const ENUM_CONTAINER_PARENT = 'parent';
	/** @see static::$sTargetForPositionJSSelector */
	public const ENUM_TARGET_FOR_POSITION_TOGGLER = 'toggler';
	/** @see static::$sVerticalPosition */
	public const ENUM_VERTICAL_POSITION_ABOVE = 'above';
	/** @see static::$sVerticalPosition */
	public const ENUM_VERTICAL_POSITION_BELOW = 'below';
	/**
	 * @see static::$sHorizontalPosition
	 *
	 *                          +--------+
	 *                          | Target |
	 *                          +--------+-----------+
	 *                          |                    |
	 *                          |        Menu        |
	 *                          |                    |
	 *                          +--------------------+
	 */
	public const ENUM_HORIZONTAL_POSITION_ALIGN_INNER_LEFT = 'align_inner_left';
	/**
	 * @see static::$sHorizontalPosition
	 *
	 *                          +--------+
	 *                          | Target |
	 *     +--------------------+--------+
	 *     |                    |
	 *     |        Menu        |
	 *     |                    |
	 *     +--------------------+
	 */
	public const ENUM_HORIZONTAL_POSITION_ALIGN_OUTER_LEFT = 'align_outer_left';
	/**
	 * @see static::$sHorizontalPosition
	 *
	 *                          +--------+
	 *                          | Target |
	 *              +-----------+--------+
	 *              |                    |
	 *              |        Menu        |
	 *              |                    |
	 *              +--------------------+
	 */
	public const ENUM_HORIZONTAL_POSITION_ALIGN_INNER_RIGHT = 'align_inner_right';
	/**
	 * @see static::$sHorizontalPosition
	 *
	 *                          +--------+
	 *                          | Target |
	 *                          +--------+--------------------+
	 *                                   |                    |
	 *                                   |        Menu        |
	 *                                   |                    |
	 *                                   +--------------------+
	 */
	public const ENUM_HORIZONTAL_POSITION_ALIGN_OUTER_RIGHT = 'align_outer_right';

	/** @see static::$sContainer */
	public const DEFAULT_CONTAINER = self::ENUM_CONTAINER_PARENT;
	/** @see static::$sTargetForPositionJSSelector */
	public const DEFAULT_TARGET_FOR_POSITION = self::ENUM_TARGET_FOR_POSITION_TOGGLER;
	/** @see static::$sVerticalPosition */
	public const DEFAULT_VERTICAL_POSITION = self::ENUM_VERTICAL_POSITION_BELOW;
	/** @see static::$sHorizontalPosition */
	public const DEFAULT_HORIZONTAL_POSITION = self::ENUM_HORIZONTAL_POSITION_ALIGN_INNER_RIGHT;

	/** @var string JS selector for the DOM element that should trigger the menu open/close */
	protected $sTogglerJSSelector;
	/** @var bool Whether the menu should add a visual hint (caret down) on the toggler to help the user understand that clicking on the toggler won't do something right away, but will open a menu instead */
	protected $bAddVisualHintToToggler;
	/** @var string Container element of the menu. Can be either:
	 *  * static::ENUM_CONTAINER_PARENT (default, better performance)
	 *  * static::ENUM_CONTAINER_BODY (use it if the menu gets cut by the hidden overflow of its parent)
	 */
	protected $sContainer;
	/**
	 * @var string JS selector for the DOM element the menu should be positioned relatively to.
	 * * static::ENUM_TARGET_FOR_POSITION_TOGGLER (default, a shortcut pointing to the toggler)
	 * * A JS selector
	 */
	protected $sTargetForPositionJSSelector;
	/** @var string Relative vertical position of the menu from the target. Value can be:
	 *  * static::ENUM_VERTICAL_POSITION_BELOW for the menu to be directly below the target
	 *  * static::ENUM_VERTICAL_POSITION_ABOVE for the menu to be directly above the target
	 *  * A JS expression to be evaluated that must return pixels (eg. (oTargetPos.top + oTarget.outerHeight(true)) + 'px')
	 */
	protected $sVerticalPosition;
	/** @var string Relative horizontal position of the menu from the target. Value can be:
	 *  * static::ENUM_HORIZONTAL_POSITION_ALIGN_INNER_LEFT for the menu to be aligned with the target's left side
	 *  * static::ENUM_HORIZONTAL_POSITION_ALIGN_INNER_RIGHT for the menu to be aligned with the target's right side
	 *  * A JS expression to be evaluated that must return pixels (eg. (oTargetPos.left + oTarget.outerWidth(true) - popover.width()) + 'px')
	 */
	protected $sHorizontalPosition;
	/** @var array */
	protected $aSections;

	/**
	 * PopoverMenu constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->sTogglerJSSelector = '';
		$this->bAddVisualHintToToggler = false;
		$this->sContainer = static::DEFAULT_CONTAINER;
		$this->sTargetForPositionJSSelector = static::DEFAULT_TARGET_FOR_POSITION;
		$this->sVerticalPosition = static::DEFAULT_VERTICAL_POSITION;
		$this->sHorizontalPosition = static::DEFAULT_HORIZONTAL_POSITION;
		$this->aSections = [];
	}

	/**
	 * @param string $sSelector
	 *
	 * @return $this
	 * @uses static::$sTogglerJSSelector
	 */
	public function SetTogglerJSSelector(string $sSelector)
	{
		$this->sTogglerJSSelector = $sSelector;

		return $this;
	}

	/**
	 * Shortcut to avoid passing the '#' in static::SetTogglerJSSelector().
	 *
	 * @param string $sId
	 *
	 * @return $this
	 */
	public function SetTogglerFromId(string $sId)
	{
		$this->SetTogglerJSSelector('#'.$sId);

		return $this;
	}

	/**
	 * Shortcut to get the toggler JS selector directly from the block
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 *
	 * @return $this
	 */
	public function SetTogglerFromBlock(iUIBlock $oBlock)
	{
		$this->SetTogglerFromId($oBlock->GetId());

		return $this;
	}

	/**
	 * @return string
	 * @uses static::$sTogglerJSSelector
	 */
	public function GetTogglerJSSelector(): string
	{
		return $this->sTogglerJSSelector;
	}

	/**
	 * @return bool
	 * @uses static::$sTogglerJSSelector
	 */
	public function HasToggler(): bool
	{
		return !empty($this->sTogglerJSSelector);
	}

	/**
	 * @return $this
	 * @uses static::$bAddVisualHintToToggler
	 */
	public function AddVisualHintToToggler()
	{
		$this->bAddVisualHintToToggler = true;

		return $this;
	}

	/**
	 * @return bool
	 * @uses static::$bAddVisualHintToToggler
	 */
	public function HasVisualHintToAddToToggler(): bool
	{
		return $this->bAddVisualHintToToggler;
	}

	/**
	 * @param string $sContainer
	 *
	 * @return $this
	 * @uses static::$sContainer
	 */
	public function SetContainer(string $sContainer)
	{
		$this->sContainer = $sContainer;

		return $this;
	}

	/**
	 * @return string
	 * @uses static::$sContainer
	 */
	public function GetContainer(): string
	{
		return $this->sContainer;
	}

	/**
	 * @param string $sJSSelector
	 *
	 * @return $this
	 * @uses static::$sTargetForPositionJSSelector
	 */
	public function SetTargetForPositionJSSelector(string $sJSSelector)
	{
		$this->sTargetForPositionJSSelector = $sJSSelector;

		return $this;
	}

	/**
	 * @return string
	 * @uses static::$sTargetForPositionJSSelector
	 */
	public function GetTargetForPositionJSSelector(): string
	{
		return $this->sTargetForPositionJSSelector;
	}

	/**
	 * @param string $sPosition
	 *
	 * @return $this
	 * @uses static::$sVerticalPosition
	 */
	public function SetVerticalPosition(string $sPosition)
	{
		$this->sVerticalPosition = $sPosition;

		return $this;
	}

	/**
	 * @return string
	 * @uses static::$sVerticalPosition
	 */
	public function GetVerticalPosition(): string
	{
		return $this->sVerticalPosition;
	}

	/**
	 * @param string $sPosition
	 *
	 * @return $this
	 * @uses static::$sHorizontalPosition
	 */
	public function SetHorizontalPosition(string $sPosition)
	{
		$this->sHorizontalPosition = $sPosition;

		return $this;
	}

	/**
	 * @return string
	 * @uses static::$sHorizontalPosition
	 */
	public function GetHorizontalPosition(): string
	{
		return $this->sHorizontalPosition;
	}

	/**
	 * Add a section $sId if not already existing.
	 * Important: It does not reset the section.
	 *
	 * @param string $sId
	 *
	 * @return $this
	 */
	public function AddSection(string $sId)
	{
		if (false === $this->HasSection($sId))
		{
			$this->aSections[$sId] = [
				'aItems' => [],
			];
		}

		return $this;
	}

	/**
	 * Remove the $sId section.
	 * Note: If the section does not exist, we silently proceed anyway.
	 *
	 * @param string $sId
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function RemoveSection(string $sId)
	{
		if (true === $this->HasSection($sId))
		{
			unset($this->aSections[$sId]);
		}

		return $this;
	}

	/**
	 * Return true if the $sId section exists
	 *
	 * @param string $sId
	 *
	 * @return bool
	 */
	public function HasSection(string $sId): bool
	{
		return array_key_exists($sId, $this->aSections);
	}

	/**
	 * Clear the $sId section from all its items.
	 *
	 * @param string $sId
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function ClearSection(string $sId)
	{
		if (false === $this->HasSection($sId))
		{
			throw new Exception('Could not clear section "'.$sId.'" as it does not exist in the "'.$this->GetId().'" menu');
		}

		$this->aSections[$sId]['aItems'] = [];

		return $this;
	}

	/**
	 * Return the sections
	 *
	 * @return array
	 */
	public function GetSections(): array
	{
		return $this->aSections;
	}

	/**
	 * @return bool Whether there are some sections, even if they have no items.
	 * @uses static::$aSections
	 */
	public function HasSections(): bool
	{
		return !empty($this->aSections);
	}

	/**
	 * Add the $oItem in the $sSectionId. If an item with the same ID already exists it will be overwritten.
	 *
	 * @param string $sSectionId
	 * @param \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenuItem\PopoverMenuItem $oItem
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function AddItem(string $sSectionId, PopoverMenuItem $oItem)
	{
		if (false === $this->HasSection($sSectionId)) {
			$this->AddSection($sSectionId);
		}

		$this->aSections[$sSectionId]['aItems'][$oItem->GetId()] = $oItem;

		return $this;
	}

	/**
	 * Remove the $sItemId from the $sSectionId.
	 * Note: If the item is not in the section, we proceed silently.
	 *
	 * @param string $sSectionId
	 * @param string $sItemId
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function RemoveItem(string $sSectionId, string $sItemId)
	{
		if (false === $this->HasSection($sSectionId))
		{
			throw new Exception('Could not remove en item from the "'.$sSectionId.'" as it does not seem to exist in the "'.$this->GetId().'" menu.');
		}

		if (array_key_exists($sItemId, $this->aSections[$sSectionId]['aItems']))
		{
			unset($this->aSections[$sSectionId]['aItems'][$sItemId]);
		}

		return $this;
	}

	/**
	 * Add all $aItems to the $sSectionId after the existing items
	 *
	 * @param string $sSectionId
	 * @param PopoverMenuItem[] $aItems
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function AddItems(string $sSectionId, array $aItems)
	{
		foreach($aItems as $oItem){
			$this->AddItem($sSectionId, $oItem);
		}

		return $this;
	}

	/**
	 * Set all $aItems at once in the $sSectionId, overwriting all existing.
	 *
	 * @param string $sSectionId
	 * @param PopoverMenuItem[] $aItems
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function SetItems(string $sSectionId, array $aItems)
	{
		if (false === $this->HasSection($sSectionId)) {
			throw new Exception('Could not set items to the "'.$sSectionId.'" section has it does not seem to exist in the "'.$this->GetId().'" menu.');
		}

		$this->aSections[$sSectionId]['aItems'] = $aItems;

		return $this;
	}

	/**
	 * @return bool Whether there is at least 1 section with some items
	 * @uses static::$aSections
	 */
	public function HasItems(): bool
	{
		$bResult = false;

		foreach ($this->GetSections() as $sId => $aData) {
			if (!empty($aData['aItems'])) {
				$bResult = true;
				break;
			}
		}

		return $bResult;
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array
	{
		$aSubBlocks = [];

		foreach ($this->aSections as $sSectionId => $aSectionData) {
			foreach($aSectionData['aItems'] as $sItemId => $oItem)
			{
				$aSubBlocks[$sItemId] = $oItem;
			}
		}

		return $aSubBlocks;
	}
}