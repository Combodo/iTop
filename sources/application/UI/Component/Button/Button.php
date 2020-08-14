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

namespace Combodo\iTop\Application\UI\Component\Button\Button;



use Combodo\iTop\Application\UI\UIBlock;

/**
 * Class JsPopoverMenuItem
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Component\Button\Button
 * @since 2.8.0
 */
class Button extends UIBlock
{
	// Overloaded constants
	const BLOCK_CODE = 'ibo-button';
	const HTML_TEMPLATE_REL_PATH = 'components/button/layout';
	const JS_TEMPLATE_REL_PATH = 'components/button/layout';

	/** @var string $sLabel */
	protected $sLabel;
	/** @var string $sType */
	protected $sType;
	/** @var string $sName */
	protected $sName;
	/** @var string $sValue */
	protected $sValue;
	/** @var string $sTooltip */
	protected $sTooltip;
	/** @var string $sIconClass */
	protected $sIconClass;
	/** @var string $sActionType */
	protected $sActionType;
	/** @var string $sColor */
	protected $sColor;
	/** @var string $sJsCode */
	protected $sJsCode;
	/** @var string $sOnClickJsCode */
	protected $sOnClickJsCode;

	/**
	 * Button constructor.
	 *
	 * @param string $sId
	 * @param string $sLabel
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
	public function __construct($sId, $sLabel, $sName, $sValue, $sType = '', $sTooltip = '', $sIconClass = '', $sActionType = 'primary', $sColor = 'secondary', $sJsCode = '', $sOnClickJsCode = '')
	{
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
		
		parent::__construct($sId);
	}

	/**
	 * @return string
	 */
	public function GetLabel(): string
	{
		return $this->sLabel;
	}

	/**
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
	public function SetOnClickJsCode($sOnClickJsCode)
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
	public function SetJsCode($sJsCode)
	{
		$this->sJsCode = $sJsCode;
		return $this;
	}
}