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

namespace Combodo\iTop\Application\UI\Base\Component\Dialog;


use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Dialog
 * @since 3.1.0
 */
class Dialog extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE                            = 'ibo-dialog';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH        = 'base/components/dialog/layout';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/dialog/layout';
	public const DEFAULT_JS_FILES_REL_PATH             = [
		'js/components/dialog.js',
	];

	/** @var string $sTitle */
	protected $sTitle;
	/** @var string $sColor */
	protected $sColor;
	/** @var bool Whether the alert can be closed or not */
	protected $bIsClosable;
	/** @var bool Whether the alert can be collapsed or not */
	protected $bIsCollapsible;
	/** @var bool Whether the alert is opened by default or not, only works when $bIsCollapsible set to true */
	protected $bIsOpenedByDefault;
	/** @var boolean if true will store collapsible state */
	protected $bIsSaveCollapsibleStateEnabled = false;
	/** @var string localStorage key used to store collapsible state */
	protected $sSectionStateStorageKey;

	/**
	 * Alert constructor.
	 *
	 * @param string $sTitle
	 * @param string $sContent
	 * @param string $sColorScheme
	 * @param string|null $sId
	 */
	public function __construct(string $sTitle = '', string $sContent = '', string $sColorScheme = self::DEFAULT_COLOR_SCHEME, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sTitle = $sTitle;
		$this->sColor = $sColorScheme;
		$this->bIsClosable = static::DEFAULT_IS_CLOSABLE;
		$this->bIsCollapsible = static::DEFAULT_IS_COLLAPSIBLE;
		$this->bIsOpenedByDefault = static::DEFAULT_IS_OPENED_BY_DEFAULT;
		if (!empty($sContent)) {
			$this->AddSubBlock(new Html($sContent));
		}
	}

	/**
	 * @param string $sSectionStateStorageKey
	 *
	 * @return self
	 */
	public function EnableSaveCollapsibleState(string $sSectionStateStorageKey)
	{
		$this->bIsSaveCollapsibleStateEnabled = true;
		$this->sSectionStateStorageKey = 'UI-Collapsible__'.$sSectionStateStorageKey;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetTitle(): string
	{
		return $this->sTitle;
	}

	/**
	 * @param string $sTitle Title of the alert
	 *
	 * @return $this
	 */
	public function SetTitle(string $sTitle)
	{
		$this->sTitle = $sTitle;

		return $this;
	}

	/**
	 * Return the raw HTML content, should be already sanitized.
	 *
	 * @return string
	 */
	public function GetContent(): string
	{
		return $this->sContent;
	}

	/**
	 * Set the raw HTML content, must be already sanitized.
	 *
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return $this
	 */
	public function SetContent(string $sContent)
	{
		$this->sContent = $sContent;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetColor(): string
	{
		return $this->sColor;
	}

	/**
	 * @param string $sColor Color of the alert (check CSS classes ibo-is-<color> for colors)
	 *
	 * @return $this
	 */
	public function SetColor(string $sColor)
	{
		$this->sColor = $sColor;

		return $this;
	}

	/**
	 * @see self::$bIsClosable
	 * @return bool
	 */
	public function IsClosable(): bool
	{
		return $this->bIsClosable;
	}

	/**
	 * @see self::$bIsClosable
	 *
	 * @param bool $bIsClosable Indicates if the user can remove the alert from the screen
	 *
	 * @return $this
	 */
	public function SetIsClosable(bool $bIsClosable)
	{
		$this->bIsClosable = $bIsClosable;

		return $this;
	}

	/**
	 * @see self::$bIsCollapsible
	 * @return bool
	 */
	public function IsCollapsible(): bool
	{
		if (empty($this->sTitle)) {
			return false;
		}

		return $this->bIsCollapsible;
	}

	/**
	 * @see self::$bIsCollapsible
	 *
	 * @param bool $bIsCollapsible Indicates if the user can collapse the alert to display only the title
	 *
	 * @return $this
	 */
	public function SetIsCollapsible(bool $bIsCollapsible)
	{
		$this->bIsCollapsible = $bIsCollapsible;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsOpenedByDefault(): bool
	{
		if ($this->IsCollapsible()) {
			return $this->bIsOpenedByDefault;
		} else {
			return true;
		}
	}

	/**
	 * @param bool $bIsOpenedByDefault Indicates if the alert is collapsed or not by default
	 *
	 * @return $this
	 */
	public function SetOpenedByDefault(bool $bIsOpenedByDefault)
	{
		$this->bIsOpenedByDefault = $bIsOpenedByDefault;

		return $this;
	}

	/**
	 * @see static::$bIsSaveCollapsibleStateEnabled
	 * @return bool
	 */
	public function IsSaveCollapsibleStateEnabled(): bool
	{
		return $this->bIsSaveCollapsibleStateEnabled;
	}

	/**
	 * @see static::$sSectionStateStorageKey
	 * @return string
	 */
	public function GetSessionCollapsibleStateStorageKey(): string
	{
		return $this->sSectionStateStorageKey;
	}

}