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
	/** @var string */
	protected $sDecorationClasses;
	/** @var string */
	protected $sIcon;

	/**
	 * ormStyle constructor.
	 *
	 * @param string $sMainColor
	 * @param string $sComplementaryColor
	 * @param string $sDecorationClasses
	 * @param string $sIcon
	 */
	public function __construct(string $sMainColor = '#2B6CB0', string $sComplementaryColor = '#FFFFFF', string $sDecorationClasses = '', string $sIcon = '')
	{
		$this->sMainColor = $sMainColor;
		$this->sComplementaryColor = $sComplementaryColor;
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
	 * @return ormStyle
	 */
	public function SetMainColor(string $sMainColor): ormStyle
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
	 * @return ormStyle
	 */
	public function SetComplementaryColor(string $sComplementaryColor): ormStyle
	{
		$this->sComplementaryColor = $sComplementaryColor;
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
	 * @return ormStyle
	 */
	public function SetDecorationClasses(string $sDecorationClasses): ormStyle
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
	 * @return ormStyle
	 */
	public function SetIcon(string $sIcon): ormStyle
	{
		$this->sIcon = $sIcon;
		return $this;
	}

}