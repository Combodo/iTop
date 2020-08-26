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


use Combodo\iTop\Application\UI\iUIBlock;
use Combodo\iTop\Application\UI\UIBlock;
use Exception;

/**
 * Class PageContent
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\PageContent
 * @internal
 * @since 2.8.0
 */
class PageContent extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-page-content';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/page-content/layout';

	/** @var string ENUM_CONTENT_AREA_MAIN The main content area */
	public const ENUM_CONTENT_AREA_MAIN = 'main';

	/** @var \Combodo\iTop\Application\UI\iUIBlock[][] $aContentAreasBlocks Blocks for the different content parts of the layout */
	protected $aContentAreasBlocks;
	/** @var string $sExtraHtmlContent HTML content that do not come from blocks and will be output as-is by the component */
	protected $sExtraHtmlContent;

	/**
	 * PageContent constructor.
	 *
	 * @param string $sId
	 */
	public function __construct($sId = null)
	{
		parent::__construct($sId);

		$this->SetMainBlocks([]);
	}

	/**
	 * Set all block for a content area at once, replacing all existing ones.
	 *
	 * @param string $sAreaId
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	protected function SetContentAreaBlocks($sAreaId, $aBlocks)
	{
		$this->aContentAreasBlocks[$sAreaId] = $aBlocks;
		return $this;
	}

	/**
	 * Return all blocks from the $sAreaId content area
	 *
	 * @param string $sAreaId
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock[]
	 * @throws \Exception
	 */
	protected function GetContentAreaBlocks($sAreaId)
	{
		if(!array_key_exists($sAreaId, $this->aContentAreasBlocks))
		{
			throw new Exception('Could not retrieve blocks from content area "'.$sAreaId.'" as it does seem to exists for page content "'.$this->GetId().'"');
		}

		return $this->aContentAreasBlocks[$sAreaId];
	}

	/**
	 * Return true if the $sAreaId content area exists
	 *
	 * @param string $sAreaId
	 *
	 * @return bool
	 */
	protected function IsExistingContentArea($sAreaId)
	{
		return isset($this->aContentAreasBlocks[$sAreaId]);
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
	protected function AddBlockToContentArea($sAreaId, iUIBlock $oBlock)
	{
		if(!array_key_exists($sAreaId, $this->aContentAreasBlocks))
		{
			$this->aContentAreasBlocks[$sAreaId] = [];
		}

		$this->aContentAreasBlocks[$sAreaId][$oBlock->GetId()] = $oBlock;
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
	protected function RemoveBlockFromContentArea($sAreaId, $sBlockId)
	{
		if(array_key_exists($sAreaId, $this->aContentAreasBlocks) && array_key_exists($sBlockId, $this->aContentAreasBlocks[$sAreaId]))
		{
			unset($this->aContentAreasBlocks[$sAreaId][$sBlockId]);
		}

		return $this;
	}

	/**
	 * Return the content areas IDs
	 *
	 * @see static::ENUM_CONTENT_AREA_MAIN, ...
	 * @return array
	 */
	protected function EnumContentAreas()
	{
		return array_keys($this->aContentAreasBlocks);
	}

	/**
	 * Set all main blocks at once.
	 *
	 * @param \Combodo\iTop\Application\UI\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	public function SetMainBlocks($aBlocks)
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
	public function RemoveMainBlock($sBlockId)
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
	public function SetExtraHtmlContent($sExtraHtmlContent)
	{
		$this->sExtraHtmlContent = $sExtraHtmlContent;
		return $this;
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
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function GetSubBlocks()
	{
		$aSubBlocks = [];
		foreach($this->EnumContentAreas() as $sAreaId)
		{
			foreach($this->GetContentAreaBlocks($sAreaId) as $oBlock)
			{
				$aSubBlocks[$oBlock->GetId()] = $oBlock;
			}
		}

		return $aSubBlocks;
	}
}