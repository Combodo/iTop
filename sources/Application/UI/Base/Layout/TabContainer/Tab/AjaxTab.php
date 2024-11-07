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


use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\UIException;
use Dict;
use Combodo\iTop\Application\WebPage\TabManager;
use utils;

/**
 * Class AjaxTab
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab
 * @internal
 * @since   3.0.0
 */
class AjaxTab extends Tab
{

	/**
	 * @see static::$sPlaceholderRelPath
	 * @var string
	 */
	public const ENUM_TAB_PLACEHOLDER_LIST = 'images/placeholders/skeleton-list.svg';
	/**
	 * @see static::$sPlaceholderRelPath
	 * @var string
	 */
	public const ENUM_TAB_PLACEHOLDER_DASHBOARD = 'images/placeholders/skeleton-dashboard.svg';
	/**
	 * @see static::$sPlaceholderRelPath
	 * @var string
	 */
	public const ENUM_TAB_PLACEHOLDER_OBJECT = 'images/placeholders/skeleton-object.svg';
	/**
	 * @see static::$sPlaceholderRelPath
	 * @var string
	 */
	public const ENUM_TAB_PLACEHOLDER_MISC = 'images/placeholders/skeleton.svg';

	/** @var string */
	public const DEFAULT_TAB_PLACEHOLDER = self::ENUM_TAB_PLACEHOLDER_MISC;

	// Overloaded constants
	public const BLOCK_CODE = 'ibo-ajax-tab';
	public const TAB_TYPE = TabManager::ENUM_TAB_TYPE_AJAX;

	/** @var string Placeholder displayed before tab is loaded (relative path from the app. root) */
	private $sPlaceholderRelPath;
	/** @var string The target URL to be loaded asynchronously */
	private $sUrl;
	/** @var bool Whether the tab should should be cached by the browser or always refreshed */
	private $bCache;

	/**
	 * Tab constructor.
	 *
	 * @param string $sTabCode
	 * @param string $sTitle
	 * @param string $sPlaceholderRelPath
	 * @param string|null $sDescription {@see \Combodo\iTop\Application\UI\Base\Layout\TabContainer\Tab\Tab::$sDescription}
	 *
	 * @since 3.1.0 N°5920 Add $sDescription argument
	 */
	public function __construct(string $sTabCode, string $sTitle, string $sPlaceholderRelPath = AjaxTab::DEFAULT_TAB_PLACEHOLDER, ?string $sDescription = null)
	{
		parent::__construct($sTabCode, $sTitle, $sDescription);
		$this->sPlaceholderRelPath = $sPlaceholderRelPath;
	}

	/**
	 * @param string $sUrl
	 *
	 * @return $this
	 */
	public function SetUrl(string $sUrl) {
		$this->sUrl = $sUrl;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetUrl(): string {
		return $this->sUrl;
	}

	/**
	 * Set whether the tab should should be cached by the browser or always refreshed
	 *
	 * @param bool $bCache
	 *
	 * @return $this
	 */
	public function SetCache(bool $bCache) {
		$this->bCache = $bCache;

		return $this;
	}

	/**
	 * Return whether the tab should should be cached by the browser or always refreshed
	 *
	 * @return string
	 */
	public function GetCache(): string {
		return $this->bCache ? 'true' : 'false';
	}

	/**
	 *
	 * @param string $sPlaceholderRelPath
	 *
	 * @return $this
	 */
	public function SetPlaceholderRelPath(string $sPlaceholderRelPath) {
		$this->sPlaceholderRelPath = $sPlaceholderRelPath;

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function GetPlaceholderRelPath(): string {
		return $this->sPlaceholderRelPath;
	}

	/**
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetPlaceholderAbsPath(): string {
		return utils::GetAbsoluteUrlAppRoot().$this->sPlaceholderRelPath;
	}

	//-------------------------------
	// iUIBlock implementation
	//-------------------------------

	/**
	 * @inheritDoc
	 */
	public function GetParameters(): array {
		$aParams = parent::GetParameters();

		$aParams['sURL'] = $this->GetUrl();
		$aParams['sCache'] = $this->GetCache() ? 'true' : 'false';

		return $aParams;
	}

	//-------------------------------
	// iUIContentBlock implementation
	//-------------------------------

	/**
	 * @inheritDoc
	 * @throws \Combodo\iTop\Application\UI\Base\UIException
	 */
	public function AddHtml(string $sHtml) {
		throw new UIException($this, Dict::Format('UIBlock:Error:AddBlockForbidden', $this->GetId()));
	}

	/**
	 * @inheritDoc
	 * @throws \Combodo\iTop\Application\UI\Base\UIException
	 */
	public function AddSubBlock(?iUIBlock $oSubBlock) {
		throw new UIException($this, Dict::Format('UIBlock:Error:AddBlockForbidden', $this->GetId()));
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array {
		return [];
	}
}
