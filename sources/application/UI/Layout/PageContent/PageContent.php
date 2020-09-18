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

namespace Combodo\iTop\Application\UI\Layout\PageContent;


use Combodo\iTop\Application\UI\Component\Html\Html;
use Combodo\iTop\Application\UI\iUIBlock;
use Combodo\iTop\Application\UI\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\UIBlock;
use Combodo\iTop\Application\UI\UIException;
use Dict;

/**
 * Class PageContent
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\PageContent
 * @internal
 * @since 2.8.0
 */
class PageContent extends UIBlock implements iUIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-page-content';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/page-content/layout';

	/** @var string ENUM_CONTENT_AREA_MAIN The main content area */
	public const ENUM_CONTENT_AREA_MAIN = 'main';

	/** @var iUIContentBlock[] $aContentAreasBlocks Blocks for the different content parts of the layout */
	protected $aContentAreasBlocks;

	/** @var string $sExtraHtmlContent HTML content that do not come from blocks and will be output as-is by the component */
	protected $sExtraHtmlContent;

	/**
	 * PageContent constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);

		$this->SetMainBlocks([]);
	}

	/**
	 * Set all main blocks at once.
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	public function SetMainBlocks(array $aBlocks): self
	{
		$this->SetContentAreaBlocks(static::ENUM_CONTENT_AREA_MAIN, $aBlocks);

		return $this;
	}

	/**
	 * Return all the main blocks
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock[]
	 * @throws \Exception
	 */
	public function GetMainBlocks()
	{
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
	public function AddMainBlock(iUIBlock $oBlock)
	{
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
	public function RemoveMainBlock(string $sBlockId)
	{
		$this->RemoveBlockFromContentArea(static::ENUM_CONTENT_AREA_MAIN, $sBlockId);

		return $this;
	}


	/**
	 * Add $oBlock to the $sAreaId content area.
	 * Note that if the area doesn't exist yet, it is created. Also if a block with the same ID already exists, it will be replaced.
	 *
	 * @param string $sAreaId
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oBlock
	 *
	 * @return $this
	 */
	protected function AddBlockToContentArea(string $sAreaId, iUIBlock $oBlock): self
	{
		if (!array_key_exists($sAreaId, $this->aContentAreasBlocks)) {
			$this->aContentAreasBlocks[$sAreaId] = new UIContentBlock($sAreaId);
		}

		$this->aContentAreasBlocks[$sAreaId]->AddSubBlock($oBlock);

		return $this;
	}

	public function AddSubBlock(iUIBlock $oSubBlock): iUIContentBlock
	{
		$this->AddMainBlock($oSubBlock);
		return $this->aContentAreasBlocks[static::ENUM_CONTENT_AREA_MAIN];
	}

	/**
	 * Remove the $sBlockId from the $sAreaId content area.
	 * Note that if the $sBlockId or the $sAreaId do not exist, it proceeds silently.
	 *
	 * @param string $sAreaId
	 * @param string $sBlockId
	 *
	 * @return $this
	 */
	protected function RemoveBlockFromContentArea(string $sAreaId, string $sBlockId)
	{
		if (array_key_exists($sAreaId, $this->aContentAreasBlocks)) {
			$this->aContentAreasBlocks[$sAreaId]->RemoveSubBlock($sBlockId);
		}

		return $this;
	}

	/**
	 * Set all block for a content area at once, replacing all existing ones.
	 *
	 * @param string $sAreaId
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	protected function SetContentAreaBlocks(string $sAreaId, array $aBlocks): self
	{
		if (!isset($this->aContentAreasBlocks[$sAreaId])) {
			$this->aContentAreasBlocks[$sAreaId] = new UIContentBlock($sAreaId);
		}

		$this->aContentAreasBlocks[$sAreaId]->SetSubBlocks($aBlocks);

		return $this;
	}

	/**
	 * Return all blocks from the $sAreaId content area
	 *
	 * @param string $sAreaId
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock[]
	 * @throws \Combodo\iTop\Application\UI\UIException
	 */
	protected function GetContentAreaBlocks(string $sAreaId): array
	{
		if (!array_key_exists($sAreaId, $this->aContentAreasBlocks)) {
			throw new UIException($this, Dict::Format('UIBlock:Error:CannotGetBlocks', $sAreaId, $this->GetId()));
		}

		return $this->aContentAreasBlocks[$sAreaId]->GetSubBlocks();
	}

	/**
	 * Return true if the $sAreaId content area exists
	 *
	 * @param string $sAreaId
	 *
	 * @return bool
	 */
	protected function IsExistingContentArea(string $sAreaId)
	{
		return isset($this->aContentAreasBlocks[$sAreaId]);
	}

	/**
	 * Return the content areas IDs
	 *
	 * @return array
	 * @see static::ENUM_CONTENT_AREA_MAIN, ...
	 */
	protected function EnumContentAreas()
	{
		return array_keys($this->aContentAreasBlocks);
	}


	/**
	 * Set the extra HTML content
	 *
	 * @param string $sExtraHtmlContent
	 *
	 * @return $this
	 */
	public function SetExtraHtmlContent(string $sExtraHtmlContent): self
	{
		$this->sExtraHtmlContent = $sExtraHtmlContent;

		return $this;
	}

	public function AddHtml(string $sHtml): iUIBlock
	{
		$oBlock = new Html($sHtml);
		$this->AddMainBlock($oBlock);

		return $oBlock;
	}

	/**
	 * Get the extra HTML content as-is, no processing is done on it
	 *
	 * @return string
	 */
	public function GetExtraHtmlContent()
	{
		return $this->sExtraHtmlContent;
	}

	/**
	 * Get ALL the blocks in all the areas
	 *
	 * @return array
	 */
	public function GetSubBlocks(): array
	{
		$aSubBlocks = [];
		foreach ($this->aContentAreasBlocks as $oContentArea) {
			$aSubBlocks = array_merge($aSubBlocks, $oContentArea->GetSubBlocks());
		}
		return $aSubBlocks;
	}

	/**
	 * Get a specific subBlock within all the areas
	 *
	 * @param string $sId
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock|null
	 */
	public function GetSubBlock(string $sId): ?iUIBlock
	{
		foreach ($this->aContentAreasBlocks as $oContentArea) {
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
	 * @param array $aSubBlocks
	 *
	 * @return $this|\Combodo\iTop\Application\UI\Layout\iUIContentBlock
	 */
	public function SetSubBlocks(array $aSubBlocks): iUIContentBlock
	{
		$this->SetMainBlocks($aSubBlocks);
		return $this;
	}

	/**
	 * Remove a specified subBlock from all the areas
	 *
	 * @param string $sId
	 *
	 * @return $this|\Combodo\iTop\Application\UI\Layout\iUIContentBlock
	 */
	public function RemoveSubBlock(string $sId): iUIContentBlock
	{
		foreach ($this->aContentAreasBlocks as $oContentArea) {
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
	public function HasSubBlock(string $sId): bool
	{
		foreach ($this->aContentAreasBlocks as $oContentArea) {
			if ($oContentArea->HasSubBlock($sId)) {
				return true;
			}
		}

		return false;
	}
}
