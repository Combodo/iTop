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

namespace Combodo\iTop\Application\UI\Component\Button;


use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class Button
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\Button
 * @since 2.8.0
 */
class Button extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-button';
	public const HTML_TEMPLATE_REL_PATH = 'components/button/layout';
	public const JS_TEMPLATE_REL_PATH = 'components/button/layout';

	// Specific constants
	/** @var string ENUM_TYPE_BUTTON */
	public const ENUM_TYPE_BUTTON = 'button';
	/** @var string ENUM_TYPE_SUBMIT */
	public const ENUM_TYPE_SUBMIT = 'submit';
	/** @var string ENUM_TYPE_RESET */
	public const ENUM_TYPE_RESET = 'reset';
	/** @var string DEFAULT_TYPE */
	public const DEFAULT_TYPE = self::ENUM_TYPE_BUTTON;

	/** @var string ENUM_ACTION_TYPE_REGULAR */
	public const ENUM_ACTION_TYPE_REGULAR = 'regular';
	/** @var string ENUM_ACTION_TYPE_ALTERNATIVE */
	public const ENUM_ACTION_TYPE_ALTERNATIVE = 'alternative';
	/** @var string DEFAULT_ACTION_TYPE */
	public const DEFAULT_ACTION_TYPE = self::ENUM_ACTION_TYPE_REGULAR;

	/** @var string ENUM_COLOR_NEUTRAL */
	public const ENUM_COLOR_NEUTRAL = 'neutral';
	/** @var string ENUM_COLOR_VALIDATION */
	public const ENUM_COLOR_VALIDATION = 'green';
	/** @var string ENUM_COLOR_DESTRUCTIVE */
	public const ENUM_COLOR_DESTRUCTIVE = 'red';
	/** @var string ENUM_COLOR_PRIMARY */
	public const ENUM_COLOR_PRIMARY = 'primary';
	/** @var string ENUM_COLOR_SECONDARY */
	public const ENUM_COLOR_SECONDARY = 'secondary';
	/** @var string ENUM_COLOR_GREEN */
	public const ENUM_COLOR_GREEN = 'green';
	/** @var string ENUM_COLOR_RED */
	public const ENUM_COLOR_RED = 'red';
	/** @var string ENUM_COLOR_CYAN */
	public const ENUM_COLOR_CYAN = 'cyan';
	/** @var string DEFAULT_COLOR */
	public const DEFAULT_COLOR = self::ENUM_COLOR_NEUTRAL;

	/** @var string $sLabel */
	protected $sLabel;
	/** @var string $sType The HTML type of the button (eg. 'submit', 'button', ...) */
	protected $sType;
	/** @var string $sName The HTML name of the button, used by forms */
	protected $sName;
	/** @var string $sValue The HTML value of the button, used by forms */
	protected $sValue;
	/** @var string $sTooltip */
	protected $sTooltip;
	/** @var string $sIconClass */
	protected $sIconClass;
	/** @var string $sActionType The type of action, a 'regular' action or a 'misc' action */
	protected $sActionType;
	/** @var string $sColor */
	protected $sColor;
	/** @var bool $bIsDisabled */
	protected $bIsDisabled;
	/** @var string $sJsCode */
	protected $sJsCode;
	/** @var string $sOnClickJsCode */
	protected $sOnClickJsCode;
	/** @var array */
	protected $aAdditionalCSSClasses;

	/**
	 * Button constructor.
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
		string $sActionType = self::DEFAULT_ACTION_TYPE, string $sColor = self::DEFAULT_COLOR, string $sJsCode = '',
		string $sOnClickJsCode = ''
	) {
		$this->sLabel = $sLabel;
		$this->sName = $sName;
		$this->sValue = $sValue;
		$this->sType = $sType;
		$this->sTooltip = $sTooltip;
		$this->sIconClass = $sIconClass;
		$this->sActionType = $sActionType;
		$this->sColor = $sColor;
		$this->sJsCode = $sJsCode;
		$this->sOnClickJsCode = $sOnClickJsCode;
		$this->bIsDisabled = false;
		$this->aAdditionalCSSClasses = [];

		parent::__construct($sId);
	}

	/**
	 * @return string
	 */
	public function GetLabel()
	{
		return $this->sLabel;
	}

	/**
	 * @param string $sLabel
	 *
	 * @return $this
	 */
	public function SetLabel(string $sLabel)
	{
		$this->sLabel = $sLabel;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetType()
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
	public function GetName()
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
	public function GetValue()
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

	/**
	 * @return string
	 */
	public function GetTooltip()
	{
		return $this->sTooltip;
	}

	/**
	 * @param string $sTooltip
	 *
	 * @return $this
	 */
	public function SetTooltip(string $sTooltip)
	{
		$this->sTooltip = $sTooltip;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetIconClass()
	{
		return $this->sIconClass;
	}

	/**
	 * @param string $sIconClass
	 *
	 * @return $this
	 */
	public function SetIconClass(string $sIconClass)
	{
		$this->sIconClass = $sIconClass;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetActionType()
	{
		return $this->sActionType;
	}

	/**
	 * @param string $sActionType
	 *
	 * @return $this
	 */
	public function SetActionType(string $sActionType)
	{
		$this->sActionType = $sActionType;

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
	 *
	 * @return $this
	 */
	public function SetColor(string $sColor)
	{
		$this->sColor = $sColor;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function IsDisabled()
	{
		return $this->bIsDisabled;
	}

	/**
	 * @param bool $bIsDisabled
	 *
	 * @return $this
	 */
	public function SetIsDisabled(bool $bIsDisabled)
	{
		$this->bIsDisabled = $bIsDisabled;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetOnClickJsCode()
	{
		return $this->sOnClickJsCode;
	}

	/**
	 * @param string $sOnClickJsCode
	 *
	 * @return $this
	 */
	public function SetOnClickJsCode(string $sOnClickJsCode)
	{
		$this->sOnClickJsCode = $sOnClickJsCode;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetJsCode()
	{
		return $this->sJsCode;
	}

	/**
	 * @param string $sJsCode
	 *
	 * @return $this
	 */
	public function SetJsCode(string $sJsCode)
	{
		$this->sJsCode = $sJsCode;

		return $this;
	}

	/**
	 * @return string
	 */
	public function GetAdditionalCSSClass(): string
	{
		return implode(' ', $this->aAdditionalCSSClasses);
	}

	public function AddCSSClasses(string $sCSSClasses): self
	{
		foreach (explode(' ', $sCSSClasses) as $sCSSClass) {
			if (!empty($sCSSClass)) {
				$this->aAdditionalCSSClasses[$sCSSClass] = $sCSSClass;
			}
		}
		return $this;
	}


}