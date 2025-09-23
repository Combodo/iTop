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

namespace Combodo\iTop\Application\UI\Base\Component\Button;


/**
 * Class Button
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Button
 * @since 3.0.0
 */
class ButtonJS extends Button
{
	// Overloaded constants
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/button/buttonjs';
	public const REQUIRES_ANCESTORS_DEFAULT_JS_FILES = true;

	// Specific constants
	/** @var string ENUM_TYPE_BUTTON */
	public const ENUM_TYPE_BUTTON = 'button';
	/** @var string ENUM_TYPE_SUBMIT */
	public const ENUM_TYPE_SUBMIT = 'submit';
	/** @var string ENUM_TYPE_RESET */
	public const ENUM_TYPE_RESET = 'reset';
	/** @var string DEFAULT_TYPE */
	public const DEFAULT_TYPE = self::ENUM_TYPE_BUTTON;

	/** @var string The HTML type of the button (eg. 'submit', 'button', ...) */
	protected $sType;
	/** @var string The HTML name of the button, used by forms */
	protected $sName;
	/** @var string The HTML value of the button, used by forms */
	protected $sValue;

	/**
	 * ButtonJS constructor.
	 *
	 * @param string $sLabel
	 * @param string|null $sId
	 * @param string $sName
	 * @param string $sValue
	 * @param string $sType
	 * @param string $sTooltip
	 * @param string $sIconClass
	 * @param string $sActionType
	 * @param string $sColor
	 * @param string $sJsCode
	 * @param string $sOnClickJsCode
	 */
	public function __construct(
		string $sLabel, string $sId = null, string $sName = '', string $sValue = '', string $sType = self::DEFAULT_TYPE,
		string $sTooltip = '', string $sIconClass = '',
		string $sActionType = self::DEFAULT_ACTION_TYPE, string $sColor = self::DEFAULT_COLOR_SCHEME, string $sJsCode = '',
		string $sOnClickJsCode = ''
	) {
		parent::__construct( $sLabel,$sId, $sTooltip, $sIconClass,
		$sActionType, $sColor, $sJsCode, $sOnClickJsCode);

		$this->sName = $sName;
		$this->sValue = $sValue;
		$this->sType = $sType;
	}
	
	/**
	 * @return string
	 */
	public function GetType(): string
	{
		return $this->sType;
	}

	/**
	 * @param string $sType
	 *
	 * @return $this
	 */
	public function SetType(string $sType)
	{
		$this->sType = $sType;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetName(): string
	{
		return $this->sName;
	}

	/**
	 * @param string $sName
	 *
	 * @return $this
	 */
	public function SetName(string $sName)
	{
		$this->sName = $sName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetValue(): string
	{
		return $this->sValue;
	}

	/**
	 * @param string $sValue
	 *
	 * @return $this
	 */
	public function SetValue(string $sValue)
	{
		$this->sValue = $sValue;
		return $this;
	}
}