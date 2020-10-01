<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Component\Panel;


use Combodo\iTop\Application\UI\Component\Html\Html;
use Combodo\iTop\Application\UI\iUIBlock;
use Combodo\iTop\Application\UI\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\tUIContentAreas;

/**
 * Class Panel
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\Panel
 * @since 2.8.0
 */
class Panel extends UIContentBlock
{
	use tUIContentAreas;

	// Overloaded constants
	public const BLOCK_CODE = 'ibo-panel';
	public const HTML_TEMPLATE_REL_PATH = 'components/panel/layout';

	// Specific constants
	/** @var string ENUM_COLOR_PRIMARY */
	public const ENUM_COLOR_PRIMARY = 'primary';
	/** @var string ENUM_COLOR_SECONDARY */
	public const ENUM_COLOR_SECONDARY = 'secondary';

	/** @var string ENUM_COLOR_NEUTRAL */
	public const ENUM_COLOR_NEUTRAL = 'neutral';
	/** @var string ENUM_COLOR_INFORMATION */
	public const ENUM_COLOR_INFORMATION = 'information';
	/** @var string ENUM_COLOR_SUCCESS */
	public const ENUM_COLOR_SUCCESS = 'success';
	/** @var string ENUM_COLOR_FAILURE */
	public const ENUM_COLOR_FAILURE = 'failure';
	/** @var string ENUM_COLOR_WARNING */
	public const ENUM_COLOR_WARNING = 'warning';
	/** @var string ENUM_COLOR_DANGER */
	public const ENUM_COLOR_DANGER = 'danger';

	/** @var string ENUM_COLOR_GREY */
	public const ENUM_COLOR_GREY = 'grey';
	/** @var string ENUM_COLOR_BLUEGREY */
	public const ENUM_COLOR_BLUEGREY = 'blue-grey';
	/** @var string ENUM_COLOR_BLUE */
	public const ENUM_COLOR_BLUE = 'blue';
	/** @var string ENUM_COLOR_CYAN */
	public const ENUM_COLOR_CYAN = 'cyan';
	/** @var string ENUM_COLOR_GREEN */
	public const ENUM_COLOR_GREEN = 'green';
	/** @var string ENUM_COLOR_ORANGE */
	public const ENUM_COLOR_ORANGE = 'orange';
	/** @var string ENUM_COLOR_RED */
	public const ENUM_COLOR_RED = 'red';
	/** @var string ENUM_COLOR_PINK */
	public const ENUM_COLOR_PINK = 'pink';

	/** @var string ENUM_CONTENT_AREA_MAIN The main content area (panel body) */
	public const ENUM_CONTENT_AREA_MAIN = 'main';
	/** @var string ENUM_CONTENT_AREA_TOOLBAR The toolbar content area (for actions) */
	public const ENUM_CONTENT_AREA_TOOLBAR = 'toolbar';

	/** @var string DEFAULT_COLOR */
	public const DEFAULT_COLOR = self::ENUM_COLOR_NEUTRAL;

	/** @var string $sTitle */
	protected $sTitle;
	/** @var array $aSubBlocks */
	protected $aSubBlocks;
	/** @var string $sColor */
	protected $sColor;

	/**
	 * Panel constructor.
	 *
	 * @param string $sTitle
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aSubBlocks
	 * @param string $sColor
	 * @param string|null $sId
	 */
	public function __construct(string $sTitle = '', array $aSubBlocks = [], string $sColor = self::DEFAULT_COLOR, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sTitle = $sTitle;
		$this->aSubBlocks = $aSubBlocks;
		$this->sColor = $sColor;
		$this->SetMainBlocks([]);
		$this->SetToolBlocks([]);
	}

	/**
	 * @return string
	 */
	public function GetTitle()
	{
		return $this->sTitle;
	}

	/**
	 * @param string $sTitle
	 *
	 * @return $this
	 */
	public function SetTitle(string $sTitle)
	{
		$this->sTitle = $sTitle;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetColor()
	{
		return $this->sColor;
	}

	/**
	 * @param string $sColor
	 *
	 * @return $this
	 */
	public function SetColor(string $sColor)
	{
		$this->sColor = $sColor;

		return $this;
	}

	//----------------------
	// Specific content area
	//----------------------

	/**
	 * Set all main blocks at once.
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	public function SetMainBlocks(array $aBlocks) {
		$this->SetContentAreaBlocks(static::ENUM_CONTENT_AREA_MAIN, $aBlocks);

		return $this;
	}

	/**
	 * Return all the main blocks
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock[]
	 * @throws \Exception
	 */
	public function GetMainBlocks(): array {
		return $this->GetContentAreaBlocks(static::ENUM_CONTENT_AREA_MAIN);
	}

	/**
	 * Add the $oBlock to the main blocks.
	 * Note that if a block with the same ID already exists, it will be replaced.
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oBlock
	 *
	 * @return $this
	 */
	public function AddMainBlock(iUIBlock $oBlock) {
		$this->AddBlockToContentArea(static::ENUM_CONTENT_AREA_MAIN, $oBlock);

		return $this;
	}

	/**
	 * Remove the main block identified by $sBlockId.
	 * Note that if no block with that ID exists, it will proceed silently.
	 *
	 * @param string $sBlockId
	 *
	 * @return $this
	 */
	public function RemoveMainBlock(string $sBlockId) {
		$this->RemoveBlockFromContentArea(static::ENUM_CONTENT_AREA_MAIN, $sBlockId);

		return $this;
	}

	/**
	 * Set all toolbar blocks at once.
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	public function SetToolBlocks(array $aBlocks) {
		$this->SetContentAreaBlocks(static::ENUM_CONTENT_AREA_TOOLBAR, $aBlocks);

		return $this;
	}

	/**
	 * Return all the toolbar blocks
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock[]
	 * @throws \Exception
	 */
	public function GetToolbarBlocks(): array {
		return $this->GetContentAreaBlocks(static::ENUM_CONTENT_AREA_TOOLBAR);
	}

	/**
	 * Add the $oBlock to the toolbar blocks.
	 * Note that if a block with the same ID already exists, it will be replaced.
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oBlock
	 *
	 * @return $this
	 */
	public function AddToolbarBlock(iUIBlock $oBlock) {
		$this->AddBlockToContentArea(static::ENUM_CONTENT_AREA_TOOLBAR, $oBlock);

		return $this;
	}

	/**
	 * Remove the toolbar block identified by $sBlockId.
	 * Note that if no block with that ID exists, it will proceed silently.
	 *
	 * @param string $sBlockId
	 *
	 * @return $this
	 */
	public function RemoveToolbarBlock(string $sBlockId) {
		$this->RemoveBlockFromContentArea(static::ENUM_CONTENT_AREA_TOOLBAR, $sBlockId);

		return $this;
	}

	//-------------------------------
	// iUIContentBlock implementation
	//-------------------------------

	/**
	 * @inheritDoc
	 */
	public function AddHtml(string $sHtml) {
		$oBlock = new Html($sHtml);
		$this->AddMainBlock($oBlock);

		return $this;
	}

	/**
	 * Add the $oSubBlock directly in the main area
	 *
	 * @inheritDoc
	 */
	public function AddSubBlock(iUIBlock $oSubBlock) {
		$this->AddMainBlock($oSubBlock);

		return $this;
	}

	/**
	 * Remove a specified subBlock from all the areas
	 *
	 * @param string $sId
	 *
	 * @return $this
	 */
	public function RemoveSubBlock(string $sId) {
		foreach ($this->GetContentAreas() as $oContentArea) {
			$oContentArea->RemoveSubBlock($sId);
		}

		return $this;
	}

	/**
	 * Check if the specified subBlock is within one of all the areas
	 *
	 * @param string $sId
	 *
	 * @return bool
	 */
	public function HasSubBlock(string $sId): bool {
		foreach ($this->GetContentAreas() as $oContentArea) {
			if ($oContentArea->HasSubBlock($sId)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get a specific subBlock within all the areas
	 *
	 * @inheritDoc
	 */
	public function GetSubBlock(string $sId): ?iUIBlock {
		foreach ($this->GetContentAreas() as $oContentArea) {
			$oSubBlock = $oContentArea->GetSubBlock($sId);
			if (!is_null($oSubBlock)) {
				return $oSubBlock;
			}
		}

		return null;
	}

	/**
	 * Set the MAIN AREA subBlocks
	 *
	 * @inheritDoc
	 * @return $this|\Combodo\iTop\Application\UI\Layout\iUIContentBlock
	 */
	public function SetSubBlocks(array $aSubBlocks): iUIContentBlock {
		$this->SetMainBlocks($aSubBlocks);

		return $this;
	}

	/**
	 * Get ALL the blocks in all the areas
	 *
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array {
		$aSubBlocks = [];
		foreach ($this->GetContentAreas() as $oContentArea) {
			$aSubBlocks = array_merge($aSubBlocks, $oContentArea->GetSubBlocks());
		}

		return $aSubBlocks;
	}
}
