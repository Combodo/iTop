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

namespace Combodo\iTop\Application\UI\Base\Component\Alert;


use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * Alerts are the main component to give feedback to the user or communicate page specific to system wide messages.
 * Alerts are a rectangular component displaying a title and a message.
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Alert
 * @since 3.0.0
 */
class Alert extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-alert';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/alert/layout';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/alert/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/components/alert.js',
	];

	// Specific constants
	/** @var string ENUM_COLOR_SCHEME_PRIMARY */
	public const ENUM_COLOR_SCHEME_PRIMARY = 'primary';
	/** @var string ENUM_COLOR_SCHEME_SECONDARY */
	public const ENUM_COLOR_SCHEME_SECONDARY = 'secondary';

	/** @var string ENUM_COLOR_SCHEME_NEUTRAL */
	public const ENUM_COLOR_SCHEME_NEUTRAL = 'neutral';
	/** @var string ENUM_COLOR_SCHEME_INFORMATION */
	public const ENUM_COLOR_SCHEME_INFORMATION = 'information';
	/** @var string ENUM_COLOR_SCHEME_SUCCESS */
	public const ENUM_COLOR_SCHEME_SUCCESS = 'success';
	/** @var string ENUM_COLOR_SCHEME_FAILURE */
	public const ENUM_COLOR_SCHEME_FAILURE = 'failure';
	/** @var string ENUM_COLOR_SCHEME_WARNING */
	public const ENUM_COLOR_SCHEME_WARNING = 'warning';
	/** @var string ENUM_COLOR_SCHEME_DANGER */
	public const ENUM_COLOR_SCHEME_DANGER = 'danger';

	/** @var string ENUM_COLOR_SCHEME_GREY */
	public const ENUM_COLOR_SCHEME_GREY = 'grey';
	/** @var string ENUM_COLOR_SCHEME_BLUEGREY */
	public const ENUM_COLOR_SCHEME_BLUEGREY = 'blue-grey';
	/** @var string ENUM_COLOR_SCHEME_BLUE */
	public const ENUM_COLOR_SCHEME_BLUE = 'blue';
	/** @var string ENUM_COLOR_SCHEME_CYAN */
	public const ENUM_COLOR_SCHEME_CYAN = 'cyan';
	/** @var string ENUM_COLOR_SCHEME_GREEN */
	public const ENUM_COLOR_SCHEME_GREEN = 'green';
	/** @var string ENUM_COLOR_SCHEME_ORANGE */
	public const ENUM_COLOR_SCHEME_ORANGE = 'orange';
	/** @var string ENUM_COLOR_SCHEME_RED */
	public const ENUM_COLOR_SCHEME_RED = 'red';
	/** @var string ENUM_COLOR_SCHEME_PINK */
	public const ENUM_COLOR_SCHEME_PINK = 'pink';

	/** @var string DEFAULT_COLOR_SCHEME */
	public const DEFAULT_COLOR_SCHEME = self::ENUM_COLOR_SCHEME_NEUTRAL;
	/** @var bool Default value for static::$bIsClosable */
	public const DEFAULT_IS_CLOSABLE = true;
	/** @var bool Default value for static::$bIsCollapsible */
	public const DEFAULT_IS_COLLAPSIBLE = true;
	/** @var bool Default value for static::$bIsOpenedByDefault */
	public const DEFAULT_IS_OPENED_BY_DEFAULT = true;

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
		if($this->IsCollapsible()) {
			return $this->bIsOpenedByDefault;
		}
		else {
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
	 * @return bool
	 * @see static::$bIsSaveCollapsibleStateEnabled
	 */
	public function IsSaveCollapsibleStateEnabled(): bool
	{
		return $this->bIsSaveCollapsibleStateEnabled;
	}

	/**
	 * @return string
	 * @see static::$sSectionStateStorageKey
	 */
	public function GetSessionCollapsibleStateStorageKey(): string
	{
		return $this->sSectionStateStorageKey;
	}

}