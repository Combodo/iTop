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

namespace Combodo\iTop\Application\UI\Component\Alert;


use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class Alert
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\Alert
 * @since 2.8.0
 */
class Alert extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-alert';
	public const HTML_TEMPLATE_REL_PATH = 'components/alert/layout';
	public const JS_TEMPLATE_REL_PATH = 'components/alert/layout';

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

	/** @var string $sTitle */
	protected $sTitle;
	/** @var string $sContent The raw HTML content, must be already sanitized */
	protected $sContent;
	/** @var string $sColor */
	protected $sColor;

	/**
	 * Alert constructor.
	 *
	 * @param string $sTitle
	 * @param string $sContent
	 * @param string $sColor
	 * @param string|null $sId
	 */
	public function __construct($sTitle = '', $sContent = '', $sColor = self::DEFAULT_COLOR, $sId = null)
	{
		$this->sTitle = $sTitle;
		$this->sContent = $sContent;
		$this->sColor = $sColor;
		parent::__construct($sId);
	}

	/**
	 * @return string
	 */
	public function GetTitle()
	{
		return $this->sTitle;
	}

	/**
	 * @param string $sTitle
	 * @return $this
	 */
	public function SetTitle($sTitle)
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
	 * @param string $sContent
	 *
	 * @return $this
	 */
	public function SetContent($sContent)
	{
		$this->sContent = $sContent;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetColor()
	{
		return $this->sColor;
	}

	/**
	 * @param string $sColor
	 * @return $this
	 */
	public function SetColor($sColor)
	{
		$this->sColor = $sColor;
		return $this;
	}
}