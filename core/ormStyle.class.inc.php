<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Class ormStyle
 *
 * @since 3.0.0
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
	/** @var string Relative path (from current environment) to the icon */
	protected $sIcon;

	/**
	 * ormStyle constructor.
	 *
	 * @param string $sStyleClass
	 * @param string $sAltStyleClass
	 * @param string|null $sMainColor
	 * @param string|null $sComplementaryColor
	 * @param string|null $sDecorationClasses
	 * @param string|null $sIcon
	 */
	public function __construct(string $sStyleClass, string $sAltStyleClass, string $sMainColor = null, string $sComplementaryColor = null, string $sDecorationClasses = null, string $sIcon = null)
	{
		$this->sMainColor = $sMainColor;
		$this->sComplementaryColor = $sComplementaryColor;
		$this->sStyleClass = $sStyleClass;
		$this->sAltStyleClass = $sAltStyleClass;
		$this->sDecorationClasses = $sDecorationClasses;
		$this->SetIcon($sIcon);
	}

	/**
	 * @return string
	 */
	public function GetMainColor(): ?string
	{
		return $this->sMainColor;
	}

	/**
	 * @param string|null $sMainColor
	 *
	 * @return $this
	 */
	public function SetMainColor(?string $sMainColor)
	{
		$this->sMainColor = $sMainColor;
		return $this;
	}

	/**
	 * @return string
	 */
	public function GetComplementaryColor(): ?string
	{
		return $this->sComplementaryColor;
	}

	/**
	 * @param string|null $sComplementaryColor
	 *
	 * @return $this
	 */
	public function SetComplementaryColor(?string $sComplementaryColor)
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
	public function GetDecorationClasses(): ?string
	{
		return $this->sDecorationClasses;
	}

	/**
	 * @param string|null $sDecorationClasses
	 *
	 * @return $this
	 */
	public function SetDecorationClasses(?string $sDecorationClasses)
	{
		$this->sDecorationClasses = $sDecorationClasses;
		return $this;
	}

	/**
	 * @param string|null $sIcon
	 *
	 * @return $this
	 */
	public function SetIcon(?string $sIcon)
	{
		$this->sIcon = (strlen($sIcon) === 0) ? null : $sIcon;
		return $this;
	}

	/**
	 * @see static::$sIcon
	 * @return string|null Relative path (from the current environment) of the icon
	 */
	public function GetIconAsRelPath(): ?string
	{
		return $this->sIcon;
	}

	/**
	 * @see static::$sIcon
	 * @return string|null Absolute URL of the icon
	 * @throws \Exception
	 */
	public function GetIconAsAbsUrl(): ?string
	{
		if (is_null($this->sIcon)) {
			return null;
		}

		return utils::GetAbsoluteUrlModulesRoot().$this->sIcon;
	}
}