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
class ButtonURL extends Button
{
	// Overloaded constants
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/button/buttonurl';
	public const REQUIRES_ANCESTORS_DEFAULT_JS_FILES = true;

	// Specific constants
	/** @var string ENUM_TARGET_BLANK */
	public const ENUM_TARGET_BLANK = '_blank';
	/** @var string ENUM_TARGET_SELF */
	public const ENUM_TARGET_SELF= '_self';
	/** @var string ENUM_TARGET_PARENT */
	public const ENUM_TARGET_PARENT= '_parent';
	/** @var string ENUM_TARGET_TOP */
	public const ENUM_TARGET_TOP= '_top';
	/** @var string DEFAULT_TARGET */
	public const DEFAULT_TARGET = self::ENUM_TARGET_SELF;


	/** @var string */
	protected $sURL;
	/** @var string */
	protected $sTarget;

	/**
	 * ButtonURL constructor.
	 *
	 * @param string $sLabel
	 * @param string $sURL
	 * @param string|null $sId
	 * @param string $sTarget
	 * @param string $sTooltip
	 * @param string $sIconClass
	 * @param string $sActionType
	 * @param string $sColor
	 * @param string $sJsCode
	 * @param string $sOnClickJsCode
	 */
	public function __construct(
		string $sLabel, string $sURL, string $sId = null, string $sTarget = self::DEFAULT_TARGET,  string $sTooltip = '', string $sIconClass = '',
		string $sActionType = self::DEFAULT_ACTION_TYPE, string $sColor = self::DEFAULT_COLOR_SCHEME, string $sJsCode = '',
		string $sOnClickJsCode = '')
	{
		parent::__construct($sLabel, $sId, $sTooltip, $sIconClass,
			$sActionType, $sColor, $sJsCode, $sOnClickJsCode);
		$this->sURL = $sURL;
		$this->sTarget = $sTarget;
	}

	/**
	 * @return string
	 */
	public function GetURL(): string
	{
		return $this->sURL;
	}

	/**
	 * @param string $sURL
	 * 
	 * @return $this
	 */
	public function SetURL(string $sURL)
	{
		$this->sURL = $sURL;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetTarget(): string
	{
		return $this->sTarget;
	}

	/**
	 * @param string $sTarget
	 * 
	 * @return $this
	 */
	public function SetTarget(string $sTarget)
	{
		$this->sTarget = $sTarget;
		return $this;
	}
}