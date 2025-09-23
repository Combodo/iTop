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

/**
 * Class PageContentWithSideContent
 *
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\PageContent
 * @internal
 * @since   3.0.0
 */
class PageContentWithSideContent extends PageContent {
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-page-content-with-side-content';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/page-content/with-side-content';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/layouts/page-content/with-side-content';

	// Specific constants
	public const ENUM_CONTENT_AREA_SIDE = 'side';

	/**
	 * PageContentWithSideContent constructor.
	 *
	 * @param string|null $sId
	 */
	public function __construct(?string $sId = null) {
		parent::__construct($sId);

		$this->SetSideBlocks([]);
	}

	/**
	 * Set all side blocks at once.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aBlocks
	 *
	 * @return $this
	 */
	public function SetSideBlocks(array $aBlocks) {
		$this->SetContentAreaBlocks(static::ENUM_CONTENT_AREA_SIDE, $aBlocks);

		return $this;
	}

	/**
	 * Return all the side blocks
	 *
	 * @return \Combodo\iTop\Application\UI\Base\iUIBlock[]
	 * @throws \Exception
	 */
	public function GetSideBlocks(): array {
		return $this->GetContentAreaBlocks(static::ENUM_CONTENT_AREA_SIDE);
	}

	/**
	 * Add the $oBlock to the side blocks.
	 * Note that if a block with the same ID already exists, it will be replaced.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock $oBlock
	 *
	 * @return $this
	 */
	public function AddSideBlock(iUIBlock $oBlock) {
		$this->AddBlockToContentArea(static::ENUM_CONTENT_AREA_SIDE, $oBlock);

		return $this;
	}

	/**
	 * Add $sHtml to the side blocks
	 *
	 * @param string $sHtml
	 *
	 * @return $this
	 */
	public function AddSideHtml(string $sHtml) {
		$this->AddBlockToContentArea(static::ENUM_CONTENT_AREA_SIDE, new Html($sHtml));

		return $this;
	}

	/**
	 * Remove the side block identified by $sBlockId.
	 * Note that if no block with that ID exists, it will proceed silently.
	 *
	 * @param string $sBlockId
	 *
	 * @return $this
	 */
	public function RemoveSideBlock(string $sBlockId)
	{
		$this->RemoveBlockFromContentArea(static::ENUM_CONTENT_AREA_SIDE, $sBlockId);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function HasSubBlocks(): bool
	{
		return parent::HasSubBlocks() || !empty($this->GetSideBlocks());
	}
}