<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
		return utils::IsNotNullOrEmptyString($this->sMainColor);
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
		$this->sMainColor = utils::IsNullOrEmptyString($sMainColor) ? null : $sMainColor;
		return $this;
	}

	/**
	 * @see static::$sComplementaryColor
	 * @return bool
	 */
	public function HasComplementaryColor(): bool
	{
		return utils::IsNotNullOrEmptyString($this->sComplementaryColor);
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
		$this->sComplementaryColor = utils::IsNullOrEmptyString($sComplementaryColor) ? null : $sComplementaryColor;
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
		return utils::IsNotNullOrEmptyString($this->sStyleClass);
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
		$this->sStyleClass = utils::IsNullOrEmptyString($sStyleClass) ? null : $sStyleClass;
		return $this;
	}

	/**
	 * @see static::$sAltStyleClass
	 * @return bool
	 */
	public function HasAltStyleClass(): bool
	{
		return utils::IsNotNullOrEmptyString($this->sAltStyleClass);
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
		$this->sAltStyleClass = utils::IsNullOrEmptyString($sAltStyleClass) ? null : $sAltStyleClass;
		return $this;
	}

	/**
	 * @see static::$sDecorationClasses
	 * @return bool
	 */
	public function HasDecorationClasses(): bool
	{
		return utils::IsNotNullOrEmptyString($this->sDecorationClasses);
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
		$this->sDecorationClasses = utils::IsNullOrEmptyString($sDecorationClasses) ? null : $sDecorationClasses;
		return $this;
	}

	/**
	 * @see static::$sIcon
	 * @return bool
	 */
	public function HasIcon(): bool
	{
		return utils::IsNotNullOrEmptyString($this->sIcon);
	}

	/**
	 * @param string|null $sIcon
	 *
	 * @return $this
	 */
	public function SetIcon(?string $sIcon)
	{
		$this->sIcon = utils::IsNullOrEmptyString($sIcon) ? null : $sIcon;
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