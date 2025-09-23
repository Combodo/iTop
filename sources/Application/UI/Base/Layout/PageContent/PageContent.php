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

namespace Combodo\iTop\Application\UI\Base\Layout\PageContent;


use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Base\tUIContentAreas;
use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class PageContent
 *
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\PageContent
 * @internal
 * @since   3.0.0
 */
class PageContent extends UIBlock implements iUIContentBlock {
	use tUIContentAreas;

	// Overloaded constants
	public const BLOCK_CODE = 'ibo-page-content';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/page-content/layout';

	/** @var string ENUM_CONTENT_AREA_MAIN The main content area */
	public const ENUM_CONTENT_AREA_MAIN = 'main';

	/** @var string $sExtraHtmlContent HTML content that do not come from blocks and will be output as-is by the component */
	protected $sExtraHtmlContent;

	/**
	 * PageContent constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct(?string $sId = null) {
		parent::__construct($sId);

		$this->SetMainBlocks([]);
	}

	//----------------------
	// Specific content area
	//----------------------

	/**
	 * Set all main blocks at once.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aBlocks
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
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 * @throws \Exception
	 */
	public function GetMainBlocks() {
		return $this->GetContentAreaBlocks(static::ENUM_CONTENT_AREA_MAIN);
	}

	/**
	 * Add the $oBlock to the main blocks.
	 * Note that if a block with the same ID already exists, it will be replaced.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 *
	 * @return $this
	 */
	public function AddMainBlock(iUIBlock $oBlock)
	{
		$this->AddBlockToContentArea(static::ENUM_CONTENT_AREA_MAIN, $oBlock);

		return $this;
	}

	/**
	 * Add all $aBlocks to the main content area
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 * @uses static::AddBlocksToContentArea()
	 */
	public function AddMainBlocks(array $aBlocks)
	{
		$this->AddBlocksToContentArea(static::ENUM_CONTENT_AREA_MAIN, $aBlocks);

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
	 * Set the extra HTML content
	 *
	 * @param string $sExtraHtmlContent
	 *
	 * @return $this
	 */
	public function SetExtraHtmlContent(string $sExtraHtmlContent) {
		$this->sExtraHtmlContent = $sExtraHtmlContent;

		return $this;
	}

	/**
	 * Get the extra HTML content as-is, no processing is done on it
	 *
	 * @return string
	 */
	public function GetExtraHtmlContent() {
		return $this->sExtraHtmlContent;
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
	public function AddSubBlock(?iUIBlock $oSubBlock) {
		if ($oSubBlock) {
			$this->AddMainBlock($oSubBlock);
		}
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
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function HasSubBlocks(): bool
	{
		return !empty($this->GetMainBlocks());
	}

	/**
	 * Set the MAIN AREA subBlocks
	 *
	 * @inheritDoc
	 * @return $this|\Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock
	 */
	public function SetSubBlocks(array $aSubBlocks): iUIContentBlock
	{
		$this->SetMainBlocks($aSubBlocks);

		return $this;
	}

	/**
	 * Get ALL the blocks in all the areas
	 *
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array
	{
		$aSubBlocks = [];
		foreach ($this->GetContentAreas() as $oContentArea) {
			$aSubBlocks = array_merge($aSubBlocks, $oContentArea->GetSubBlocks());
		}

		return $aSubBlocks;
	}

	public function GetDeferredBlocks(): array
	{
		$aSubBlocks = [];
		foreach ($this->GetContentAreas() as $oContentArea) {
			$aSubBlocks = array_merge($aSubBlocks, $oContentArea->GetDeferredBlocks());
		}

		return $aSubBlocks;
	}

	/**
	 * Add the $oDeferredBlock directly in the main area
	 *
	 * @inheritDoc
	 */
	public function AddDeferredBlock(iUIBlock $oDeferredBlock)
	{
		$this->AddDeferredBlockToContentArea(static::ENUM_CONTENT_AREA_MAIN, $oDeferredBlock);

		return $this;
	}

	/**
	 * Remove a specified subBlock from all the areas
	 *
	 * @param string $sId
	 *
	 * @return $this
	 */
	public function RemoveDeferredBlock(string $sId)
	{
		foreach ($this->GetContentAreas() as $oContentArea) {
			$oContentArea->RemoveDeferredBlock($sId);
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
	public function HasDeferredBlock(string $sId): bool
	{
		foreach ($this->GetContentAreas() as $oContentArea) {
			if ($oContentArea->HasDeferredBlock($sId)) {
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
	public function GetDeferredBlock(string $sId): ?iUIBlock
	{
		foreach ($this->GetContentAreas() as $oContentArea) {
			$oDeferredBlock = $oContentArea->GetDeferredBlock($sId);
			if (!is_null($oDeferredBlock)) {
				return $oDeferredBlock;
			}
		}

		return null;
	}

	/**
	 * Set the MAIN AREA subBlocks
	 *
	 * @inheritDoc
	 * @return $this|\Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock
	 */
	public function SetDeferredBlocks(array $aDeferredBlocks): iUIContentBlock
	{
		$this->SetContentAreaDeferredBlocks(self::ENUM_CONTENT_AREA_MAIN, $aDeferredBlocks);

		return $this;
	}
}
