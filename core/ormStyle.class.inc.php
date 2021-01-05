<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class ormStyle
{
	/** @var string */
	protected $sMainColor;
	/** @var string */
	protected $sComplementaryColor;
	/** @var string CSS class with color and background-color */
	protected $sStyleClass;
	/** @var string CSS class with only color */
	protected $sAltStyleClass;
	/** @var string */
	protected $sDecorationClasses;
	/** @var string */
	protected $sIcon;

	/**
	 * ormStyle constructor.
	 *
	 * @param string $sStyleClass
	 * @param string $sAltStyleClass
	 * @param string $sMainColor
	 * @param string $sComplementaryColor
	 * @param string $sDecorationClasses
	 * @param string $sIcon
	 */
	public function __construct(string $sStyleClass = '', string $sAltStyleClass = '', string $sMainColor = '#2B6CB0', string $sComplementaryColor = '#FFFFFF', string $sDecorationClasses = '', string $sIcon = '')
	{
		$this->sMainColor = $sMainColor;
		$this->sComplementaryColor = $sComplementaryColor;
		$this->sStyleClass = $sStyleClass;
		$this->sAltStyleClass = $sAltStyleClass;
		$this->sDecorationClasses = $sDecorationClasses;
		$this->sIcon = $sIcon;
	}

	/**
	 * @return string
	 */
	public function GetMainColor(): string
	{
		return $this->sMainColor;
	}

	/**
	 * @param string $sMainColor
	 *
	 * @return $this
	 */
	public function SetMainColor(string $sMainColor)
	{
		$this->sMainColor = $sMainColor;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetComplementaryColor(): string
	{
		return $this->sComplementaryColor;
	}

	/**
	 * @param string $sComplementaryColor
	 *
	 * @return $this
	 */
	public function SetComplementaryColor(string $sComplementaryColor)
	{
		$this->sComplementaryColor = $sComplementaryColor;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetStyleClass(): string
	{
		return $this->sStyleClass;
	}

	/**
	 * @param string $sStyleClass
	 *
	 * @return $this
	 */
	public function SetStyleClass(string $sStyleClass)
	{
		$this->sStyleClass = $sStyleClass;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetAltStyleClass(): string
	{
		return $this->sAltStyleClass;
	}

	/**
	 * @param string $sAltStyleClass
	 *
	 * @return $this
	 */
	public function SetAltStyleClass(string $sAltStyleClass)
	{
		$this->sAltStyleClass = $sAltStyleClass;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetDecorationClasses(): string
	{
		return $this->sDecorationClasses;
	}

	/**
	 * @param string $sDecorationClasses
	 *
	 * @return $this
	 */
	public function SetDecorationClasses(string $sDecorationClasses)
	{
		$this->sDecorationClasses = $sDecorationClasses;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetIcon(): string
	{
		return $this->sIcon;
	}

	/**
	 * @param string $sIcon
	 *
	 * @return $this
	 */
	public function SetIcon(string $sIcon)
	{
		$this->sIcon = $sIcon;
		return $this;
	}

}