<?php
/**
 * Copyright (C) 2013-2022 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Base\Component\Template;


use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\tUIContentAreas;

/**
 * Class Template
 *
 * @author Benjamin Dalsass<benjamin.dalsass@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Template
 * @since 3.1.0
 */
class Template extends UIContentBlock
{
	use tUIContentAreas;

	// Overloaded constants
	public const BLOCK_CODE                     = 'ibo-template';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/template/layout';

	// Specific constants
	/** @var string ENUM_CONTENT_AREA_MAIN The main content area (template body) */
	public const ENUM_CONTENT_AREA_MAIN = 'main';

	/**
	 * Template constructor.
	 *
	 * @param string $sId template identifier
	 */
	public function __construct(string $sId = '')
	{
		parent::__construct($sId);

		$this->SetMainBlocks([]);
	}

	//-------------------------------
	// iUIContentBlock implementation
	//-------------------------------

	/**
	 * Set all main blocks at once.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	public function SetMainBlocks(array $aBlocks)
	{
		$this->SetContentAreaBlocks(static::ENUM_CONTENT_AREA_MAIN, $aBlocks);

		return $this;
	}

	/**
	 * Return all the main blocks
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 * @throws \Exception
	 */
	public function GetMainBlocks(): array
	{
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
	 * @inheritDoc
	 */
	public function AddHtml(string $sHtml)
	{
		$oBlock = new Html($sHtml);
		$this->AddMainBlock($oBlock);

		return $this;
	}

	/**
	 * Add the $oSubBlock directly in the main area
	 *
	 * @inheritDoc
	 */
	public function AddSubBlock(?iUIBlock $oSubBlock)
	{
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
	public function RemoveSubBlock(string $sId)
	{
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
	public function HasSubBlock(string $sId): bool
	{
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
	public function GetSubBlock(string $sId): ?iUIBlock
	{
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

}
