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

namespace Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\WebPage\TabManager;
use utils;

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
	 * @var string|null Text description of the tab and its content, will be used to display hint to the user
	 * @since 3.1.0 N°5920
	 */
	protected ?string $sDescription;

	/**
	 * Tab constructor.
	 *
	 * @param string $sTabCode
	 * @param string $sTitle
	 * @param string|null $sDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 *
	 * @since 3.1.0 N°5920 Add $sDescription argument
	 */
	public function __construct(string $sTabCode, string $sTitle, ?string $sDescription = null)
	{
		parent::__construct($sTabCode);
		$this->sTitle = $sTitle;
		$this->sDescription = $sDescription;
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

	/**
	 * @return string|null {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 * @since 3.1.0
	 */
	public function GetDescription(): ?string
	{
		return $this->sDescription;
	}

	/**
	 * @return bool
	 * @since 3.1.0
	 */
	public function HasDescription(): bool
	{
		return utils::IsNotNullOrEmptyString($this->sDescription);
	}

	/**
	 * @param string $sDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 *
	 * @return void
	 * @since 3.1.0
	 */
	public function SetDescription(string $sDescription)
	{
		$this->sDescription = $sDescription;
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
