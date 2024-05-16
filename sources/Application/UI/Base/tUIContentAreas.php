<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base;

use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Dict;

/**
 * Trait tUIContentAreas
 *
 * This brings the ability to a UIBlock to have several content areas, each having its own UIBlocks.
 * It allows the dev. to easily put/remove blocks in a specific part of a UIBlock (eg. main/sides part of a page, header/body of a panel, ...)
 *
 * @package Combodo\iTop\Application\UI
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @internal
 * @since 3.0.0
 */
trait tUIContentAreas {
	/** @var iUIContentBlock[] Blocks for the different content parts of the block */
	protected $aContentAreasBlocks;

	/**
	 * Return the content areas IDs
	 *
	 * @return array
	 * @see static::ENUM_CONTENT_AREA_MAIN, ...
	 */
	protected function EnumContentAreas(): array {
		return array_keys($this->aContentAreasBlocks);
	}

	/**
	 * Return an array of all the content areas
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock[]
	 */
	protected function GetContentAreas(): array {
		return $this->aContentAreasBlocks;
	}

	/**
	 * Return true if the $sAreaId content area exists
	 *
	 * @param string $sAreaId
	 *
	 * @return bool
	 */
	protected function IsExistingContentArea(string $sAreaId): bool {
		return isset($this->aContentAreasBlocks[$sAreaId]);
	}

	/**
	 * Set all block for a content area at once, replacing all existing ones.
	 *
	 * @param string                                  $sAreaId
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	protected function SetContentAreaBlocks(string $sAreaId, array $aBlocks)
	{
		if (!isset($this->aContentAreasBlocks[$sAreaId])) {
			$this->aContentAreasBlocks[$sAreaId] = new UIContentBlock($sAreaId);
		}

		$this->aContentAreasBlocks[$sAreaId]->SetSubBlocks($aBlocks);

		return $this;
	}

	/**
	 * Set all block for a content area at once, replacing all existing ones.
	 *
	 * @param string $sAreaId
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	protected function SetContentAreaDeferredBlocks(string $sAreaId, array $aBlocks)
	{
		if (!isset($this->aContentAreasBlocks[$sAreaId])) {
			$this->aContentAreasBlocks[$sAreaId] = new UIContentBlock($sAreaId);
		}

		$this->aContentAreasBlocks[$sAreaId]->SetDeferredBlocks($aBlocks);

		return $this;
	}

	/**
	 * Return all blocks from the $sAreaId content area
	 *
	 * @param string $sAreaId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 * @throws \Combodo\iTop\Application\UI\Base\UIException
	 */
	protected function GetContentAreaBlocks(string $sAreaId): array
	{
		if (!array_key_exists($sAreaId, $this->aContentAreasBlocks)) {
			throw new UIException($this, Dict::Format('UIBlock:Error:CannotGetBlocks', $sAreaId, $this->GetId()));
		}

		return $this->aContentAreasBlocks[$sAreaId]->GetSubBlocks();
	}

	/**
	 * Add $oBlock to the $sAreaId content area.
	 * Note that if the area doesn't exist yet, it is created. Also if a block with the same ID already exists, it will be replaced.
	 *
	 * @param string                                $sAreaId
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 *
	 * @return $this
	 */
	protected function AddBlockToContentArea(string $sAreaId, iUIBlock $oBlock)
	{
		if (!array_key_exists($sAreaId, $this->aContentAreasBlocks)) {
			$this->aContentAreasBlocks[$sAreaId] = new UIContentBlock($sAreaId);
		}

		$this->aContentAreasBlocks[$sAreaId]->AddSubBlock($oBlock);

		return $this;
	}

	/**
	 * Add all $aBlocks to the $sAreaId content area
	 *
	 * @param string $sAreaId
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 * @uses static::AddBlockToContentArea()
	 *
	 */
	protected function AddBlocksToContentArea(string $sAreaId, array $aBlocks)
	{
		foreach ($aBlocks as $oBlock) {
			$this->AddBlockToContentArea($sAreaId, $oBlock);
		}

		return $this;
	}

	/**
	 * Add $oBlock as deferred to the $sAreaId content area.
	 * Note that if the area doesn't exist yet, it is created. Also if a block with the same ID already exists, it will be replaced.
	 *
	 * @param string $sAreaId
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 *
	 * @return $this
	 */
	protected function AddDeferredBlockToContentArea(string $sAreaId, iUIBlock $oBlock)
	{
		if (!array_key_exists($sAreaId, $this->aContentAreasBlocks)) {
			$this->aContentAreasBlocks[$sAreaId] = new UIContentBlock($sAreaId);
		}

		$this->aContentAreasBlocks[$sAreaId]->AddDeferredBlock($oBlock);

		return $this;
	}

	/**
	 * Add all $aBlocks as deferred to the $sAreaId content area
	 *
	 * @param string $sAreaId
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 * @uses static::AddDeferredBlockToContentArea()
	 *
	 */
	protected function AddDeferredBlocksToContentArea(string $sAreaId, array $aBlocks)
	{
		foreach ($aBlocks as $oBlock) {
			$this->AddDeferredBlockToContentArea($sAreaId, $oBlock);
		}

		return $this;
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
	protected function RemoveBlockFromContentArea(string $sAreaId, string $sBlockId) {
		if (array_key_exists($sAreaId, $this->aContentAreasBlocks)) {
			$this->aContentAreasBlocks[$sAreaId]->RemoveSubBlock($sBlockId);
		}

		return $this;
	}
}
