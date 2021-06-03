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
	/** @var string ENUM_COLOR_PRIMARY */
	public const ENUM_COLOR_PRIMARY = 'primary';
	/** @var string ENUM_COLOR_SECONDARY */
	public const ENUM_COLOR_SECONDARY = 'secondary';

	/** @var string ENUM_COLOR_NEUTRAL */
	public const ENUM_COLOR_NEUTRAL = 'neutral';
	/** @var string ENUM_COLOR_INFORMATION */
	public const ENUM_COLOR_INFORMATION = 'information';
	/** @var string ENUM_COLOR_SUCCESS */
	public const ENUM_COLOR_SUCCESS = 'success';
	/** @var string ENUM_COLOR_FAILURE */
	public const ENUM_COLOR_FAILURE = 'failure';
	/** @var string ENUM_COLOR_WARNING */
	public const ENUM_COLOR_WARNING = 'warning';
	/** @var string ENUM_COLOR_DANGER */
	public const ENUM_COLOR_DANGER = 'danger';

	/** @var string ENUM_COLOR_GREY */
	public const ENUM_COLOR_GREY = 'grey';
	/** @var string ENUM_COLOR_BLUEGREY */
	public const ENUM_COLOR_BLUEGREY = 'blue-grey';
	/** @var string ENUM_COLOR_BLUE */
	public const ENUM_COLOR_BLUE = 'blue';
	/** @var string ENUM_COLOR_CYAN */
	public const ENUM_COLOR_CYAN = 'cyan';
	/** @var string ENUM_COLOR_GREEN */
	public const ENUM_COLOR_GREEN = 'green';
	/** @var string ENUM_COLOR_ORANGE */
	public const ENUM_COLOR_ORANGE = 'orange';
	/** @var string ENUM_COLOR_RED */
	public const ENUM_COLOR_RED = 'red';
	/** @var string ENUM_COLOR_PINK */
	public const ENUM_COLOR_PINK = 'pink';

	/** @var string DEFAULT_COLOR */
	public const DEFAULT_COLOR = self::ENUM_COLOR_NEUTRAL;
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
	 * @param string $sColor
	 * @param string|null $sId
	 */
	public function __construct(string $sTitle = '', string $sContent = '', string $sColor = self::DEFAULT_COLOR, ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sTitle = $sTitle;
		$this->sColor = $sColor;
		$this->bIsClosable = static::DEFAULT_IS_CLOSABLE;
		$this->bIsCollapsible = static::DEFAULT_IS_COLLAPSIBLE;
		$this->bIsOpenedByDefault = static::DEFAULT_IS_OPENED_BY_DEFAULT;
		if (!empty($sContent)) {
			$this->AddSubBlock(new Html($sContent));
		}
	}

	/**
	 * @param $sSectionStateStorageKey
	 *
	 * @return self
	 */
	public function EnableSaveCollapsibleState($sSectionStateStorageKey)
	{
		$this->bIsSaveCollapsibleStateEnabled = true;
		$this->sSectionStateStorageKey = 'UI-Collapsible__'.$sSectionStateStorageKey;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetTitle()
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
	public function GetContent()
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

	public function IsSaveCollapsibleStateEnabled(): bool
	{
		return $this->bIsSaveCollapsibleStateEnabled;
	}

	public function GetSessionCollapsibleStateStorageKey(): string
	{
		return $this->sSectionStateStorageKey;
	}

}