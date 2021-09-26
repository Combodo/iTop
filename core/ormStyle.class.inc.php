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
	/** @var string|null */
	protected $sMainColor;
	/** @var string|null */
	protected $sComplementaryColor;
	/** @var string|null CSS class with color and background-color */
	protected $sStyleClass;
	/** @var string|null CSS class with only color */
	protected $sAltStyleClass;
	/** @var string|null */
	protected $sDecorationClasses;
	/** @var string|null Relative path (from current environment) to the icon */
	protected $sIcon;

	/**
	 * ormStyle constructor.
	 *
	 * @param string|null $sStyleClass
	 * @param string|null $sAltStyleClass
	 * @param string|null $sMainColor
	 * @param string|null $sComplementaryColor
	 * @param string|null $sDecorationClasses
	 * @param string|null $sIcon
	 */
	public function __construct(?string $sStyleClass = null, ?string $sAltStyleClass = null, ?string $sMainColor = null, ?string $sComplementaryColor = null, ?string $sDecorationClasses = null, ?string $sIcon = null)
	{
		$this->SetMainColor($sMainColor);
		$this->SetComplementaryColor($sComplementaryColor);
		$this->SetStyleClass($sStyleClass);
		$this->SetAltStyleClass($sAltStyleClass);
		$this->SetDecorationClasses($sDecorationClasses);
		$this->SetIcon($sIcon);
	}

	/**
	 * @see static::$sMainColor
	 * @return bool
	 */
	public function HasMainColor(): bool
	{
		return strlen($this->sMainColor) > 0;
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
		$this->sMainColor = (strlen($sMainColor) === 0) ? null : $sMainColor;
		return $this;
	}

	/**
	 * @see static::$sComplementaryColor
	 * @return bool
	 */
	public function HasComplementaryColor(): bool
	{
		return strlen($this->sComplementaryColor) > 0;
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
		$this->sComplementaryColor = (strlen($sComplementaryColor) === 0) ? null : $sComplementaryColor;
		return $this;
	}

	/**
	 * @see static::$sMainColor
	 * @see static::$sComplementaryColor
	 * @return bool
	 */
	public function HasAtLeastOneColor(): bool
	{
		return $this->HasMainColor() || $this->HasComplementaryColor();
	}

	/**
	 * @see static::$sStyleClass
	 * @return bool
	 */
	public function HasStyleClass(): bool
	{
		return strlen($this->sStyleClass) > 0;
	}

	/**
	 * @return string
	 */
	public function GetStyleClass(): ?string
	{
		return $this->sStyleClass;
	}

	/**
	 * @param string $sStyleClass
	 *
	 * @return $this
	 */
	public function SetStyleClass(?string $sStyleClass)
	{
		$this->sStyleClass = (strlen($sStyleClass) === 0) ? null : $sStyleClass;
		return $this;
	}

	/**
	 * @see static::$sAltStyleClass
	 * @return bool
	 */
	public function HasAltStyleClass(): bool
	{
		return strlen($this->sAltStyleClass) > 0;
	}

	/**
	 * @return string
	 */
	public function GetAltStyleClass(): ?string
	{
		return $this->sAltStyleClass;
	}

	/**
	 * @param string $sAltStyleClass
	 *
	 * @return $this
	 */
	public function SetAltStyleClass(?string $sAltStyleClass)
	{
		$this->sAltStyleClass = (strlen($sAltStyleClass) === 0) ? null : $sAltStyleClass;
		return $this;
	}

	/**
	 * @see static::$sDecorationClasses
	 * @return bool
	 */
	public function HasDecorationClasses(): bool
	{
		return strlen($this->sDecorationClasses) > 0;
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
		$this->sDecorationClasses = (strlen($sDecorationClasses) === 0) ? null : $sDecorationClasses;
		return $this;
	}

	/**
	 * @see static::$sIcon
	 * @return bool
	 */
	public function HasIcon(): bool
	{
		return strlen($this->sIcon) > 0;
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