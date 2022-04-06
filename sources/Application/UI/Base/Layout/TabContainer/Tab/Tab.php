<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use TabManager;

/**
 * Class Tab
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab
 * @internal
 * @since 3.0.0
 */
class Tab extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-tab';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/tab-container/tab/layout';

	/** @var string */
	public const TAB_TYPE = TabManager::ENUM_TAB_TYPE_HTML;

	/** @var string */
	protected $sTitle;

	/**
	 * Tab constructor.
	 *
	 * @param string $sTabCode
	 * @param string $sTitle
	 */
	public function __construct(string $sTabCode, string $sTitle)
	{
		parent::__construct($sTabCode);
		$this->sTitle = $sTitle;
	}

	/**
	 * @return string
	 */
	public function GetType(): string
	{
		return static::TAB_TYPE;
	}

	/**
	 * @return string
	 */
	public function GetTitle(): string
	{
		return $this->sTitle;
	}

	//-------------------------------
	// iUIBlock implementation
	//-------------------------------

	/**
	 * @inheritDoc
	 */
	public function GetParameters(): array
	{
		return [
			'sBlockId' => $this->GetId(),
			'sTitle' => $this->GetTitle(),
			'sType' => $this->GetType(),
			'oBlock' => $this,
		];
	}
}
