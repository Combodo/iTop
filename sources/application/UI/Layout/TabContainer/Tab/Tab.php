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


use Combodo\iTop\Application\UI\Layout\UIContentBlock;
use TabManager;

/**
 * Class Tab
 *
 * @package Combodo\iTop\Application\UI\Layout\TabContainer\Tab
 */
class Tab extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-tab';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/tabcontainer/tab/tab';
	public const JS_TEMPLATE_REL_PATH = 'layouts/tabcontainer/tab/tab';

	protected const TAB_TYPE = TabManager::ENUM_TAB_TYPE_HTML;

	protected $sTitle;

	public function __construct(string $sTabCode, string $sTitle)
	{
		parent::__construct($sTabCode);
		$this->sTitle = $sTitle;
	}

	public function GetType(): string
	{
		return static::TAB_TYPE;
	}

	public function GetTitle(): string
	{
		return $this->sTitle;
	}
}
