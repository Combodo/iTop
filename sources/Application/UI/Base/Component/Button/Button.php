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


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class Button
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Button
 * @since 3.0.0
 */
class Button extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-button';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/button/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/button/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/components/button.js',
	];
	public const REQUIRES_ANCESTORS_DEFAULT_JS_FILES = true;
	
	// Specific constants
	/** @var string ENUM_ACTION_TYPE_REGULAR */
	public const ENUM_ACTION_TYPE_REGULAR = 'regular';
	/** @var string ENUM_ACTION_TYPE_ALTERNATIVE */
	public const ENUM_ACTION_TYPE_ALTERNATIVE = 'alternative';
	/** @var string DEFAULT_ACTION_TYPE */
	public const DEFAULT_ACTION_TYPE = self::ENUM_ACTION_TYPE_REGULAR;

	/** @var string ENUM_COLOR_SCHEME_NEUTRAL */
	public const ENUM_COLOR_SCHEME_NEUTRAL = 'neutral';
	/** @var string ENUM_COLOR_SCHEME_VALIDATION */
	public const ENUM_COLOR_SCHEME_VALIDATION = 'success';
	/** @var string ENUM_COLOR_SCHEME_DESTRUCTIVE */
	public const ENUM_COLOR_SCHEME_DESTRUCTIVE = 'danger';
	/** @var string ENUM_COLOR_SCHEME_PRIMARY */
	public const ENUM_COLOR_SCHEME_PRIMARY = 'primary';
	/** @var string ENUM_COLOR_SCHEME_SECONDARY */
	public const ENUM_COLOR_SCHEME_SECONDARY = 'secondary';
	/** @var string ENUM_COLOR_SCHEME_GREEN */
	public const ENUM_COLOR_SCHEME_GREEN = 'green';
	/** @var string ENUM_COLOR_SCHEME_RED */
	public const ENUM_COLOR_SCHEME_RED = 'red';
	/** @var string ENUM_COLOR_SCHEME_CYAN */
	public const ENUM_COLOR_SCHEME_CYAN = 'cyan';
	/** @var string DEFAULT_COLOR_SCHEME */
	public const DEFAULT_COLOR_SCHEME = self::ENUM_COLOR_SCHEME_NEUTRAL;

	/** @var string $sLabel */
	protected $sLabel;
	/** @var string $sTooltip */
	protected $sTooltip;
	/** @var string $sIconClass */
	protected $sIconClass;
	/** @var string $sActionType The type of action, a 'regular' action or a 'misc' action */
	protected $sActionType;
	/** @var string $sColor */
	protected $sColor;
	/** @var string $sJsCode */
	protected $sJsCode;
	/** @var string $sOnClickJsCode */
	protected $sOnClickJsCode;
	/** @var bool $bIsDisabled */
	protected $bIsDisabled;

	/**
	 * Button constructor.
	 *
	 * @param string $sLabel
	 * @param string|null $sId
	 * @param string $sTooltip
	 * @param string $sIconClass
	 * @param string $sActionType
	 * @param string $sColorScheme
	 * @param string $sJsCode
	 * @param string $sOnClickJsCode
	 */
	public function __construct(string $sLabel, string $sId = null, string $sTooltip = '', string $sIconClass = '', string $sActionType = self::DEFAULT_ACTION_TYPE, string $sColorScheme = self::DEFAULT_COLOR_SCHEME, string $sJsCode = '', string $sOnClickJsCode = '')
	{
		// We only use resource ID (not sanitized) on button for now, but this might be reworked back into \UIBlock if needed
		if (!is_null($sId)) {
			$this->AddDataAttribute('resource-id', $sId);
		}

		parent::__construct($sId);

		$this->sLabel = $sLabel;
		$this->sTooltip = $sTooltip;
		$this->sIconClass = $sIconClass;
		$this->sActionType = $sActionType;
		$this->sColor = $sColorScheme;
		$this->sJsCode = $sJsCode;
		$this->sOnClickJsCode = $sOnClickJsCode;
		$this->aDataAttributes = ['role' => 'ibo-button'];
		$this->bIsDisabled = false;
	}

	/**
	 * @return string
	 */
	public function GetLabel(): string
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
	public function GetTooltip(): string
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
	public function GetIconClass(): string
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
	public function GetActionType(): string
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
	public function GetColor(): string
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
	 * @return string
	 */
	public function GetOnClickJsCode(): string
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
	public function GetJsCode(): string
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
	 * @return bool
	 */
	public function IsDisabled(): bool
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
}