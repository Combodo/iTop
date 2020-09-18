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

namespace Combodo\iTop\Application\UI\Layout\TabContainer\Tab;


use Combodo\iTop\Application\UI\iUIBlock;
use Combodo\iTop\Application\UI\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\UIException;
use Dict;
use TabManager;

/**
 * Class AjaxTab
 *
 * @package Combodo\iTop\Application\UI\Layout\TabContainer\Tab
 */
class AjaxTab extends Tab
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-ajaxtab';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/tabcontainer/tab/ajaxtab';
	public const JS_TEMPLATE_REL_PATH = 'layouts/tabcontainer/tab/ajaxtab';

	protected const TAB_TYPE = TabManager::ENUM_TAB_TYPE_AJAX;

	/** @var string */
	private $sURL;
	/** @var bool */
	private $bCache;

	/**
	 * @param string $sHtml
	 *
	 * @return \Combodo\iTop\Application\UI\iUIBlock
	 * @throws \Combodo\iTop\Application\UI\UIException
	 */
	public function AddHtml(string $sHtml): iUIBlock
	{
		throw new UIException($this, Dict::Format('UIBlock:Error:AddBlockForbidden', $this->GetId()));
	}

	/**
	 * @param \Combodo\iTop\Application\UI\iUIBlock $oSubBlock
	 *
	 * @return iUIContentBlock
	 * @throws \Combodo\iTop\Application\UI\UIException
	 */
	public function AddSubBlock(iUIBlock $oSubBlock): iUIContentBlock
	{
		throw new UIException($this, Dict::Format('UIBlock:Error:AddBlockForbidden', $this->GetId()));
	}

	/**
	 * @return array|\Combodo\iTop\Application\UI\iUIBlock[]
	 */
	public function GetSubBlocks(): array
	{
		return [];
	}

	/**
	 * @param mixed $sURL
	 *
	 * @return AjaxTab
	 */
	public function SetURL(string $sURL): self
	{
		$this->sURL = $sURL;
		return $this;
	}

	/**
	 * @param bool $bCache
	 *
	 * @return AjaxTab
	 */
	public function SetCache(bool $bCache): self
	{
		$this->bCache = $bCache;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetURL(): string
	{
		return $this->sURL;
	}

	/**
	 * @return string
	 */
	public function GetCache(): string
	{
		return $this->bCache ? 'true' : 'false';
	}

	public function GetParameters(): array
	{
		$aParams = parent::GetParameters();

		$aParams['sURL'] = $this->GetURL();
		$aParams['sCache'] = $this->GetCache() ? 'true' : 'false';

		return $aParams;
	}
}
