<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Pill;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * Class Pill
 *
 * @internal
 * @author Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 * @package Combodo\iTop\Application\UI\Base\Component\Pill
 */
class Pill extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-pill';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/pill/layout';

	/** @var string */
	protected $sColor;
	/** @var null|string URL to go to if the pill is clicked */
	protected $sUrl;
	/** @var null|string Text to display as a tooltip */
	protected $sTooltip;

	/**
	 * Pill constructor.
	 *
	 * @param string $sColor
	 */
	public function __construct(string $sColor)
	{
		parent::__construct();
		$this->SetColor($sColor);
	}

	/**
	 * @return string
	 */
	public function GetColor(): ?string
	{
		return $this->sColor;
	}

	/**
	 * @param string $sColor
	 *
	 * @return Pill
	 */
	public function SetColor(string $sColor): Pill
	{
		$this->sColor = $sColor;

		return $this;
	}

	/**
	 * @see static::$sUrl
	 * @return null|string
	 */
	public function GetUrl(): ?string
	{
		return $this->sUrl;
	}

	/**
	 * @param string $sUrl
	 *
	 * @see static::$sUrl
	 * @return $this
	 */
	public function SetUrl(string $sUrl)
	{
		$this->sUrl = $sUrl;

		return $this;
	}

	/**
	 * @see static::$sUrl
	 * @return bool
	 */
	public function HasUrl(): bool
	{
		return !empty($this->sUrl);
	}

	/**
	 * @see static::$sTooltip
	 * @return string|null
	 */
	public function GetTooltip(): ?string
	{
		return $this->sTooltip;
	}

	/**
	 * @param string $sTooltip
	 *
	 * @see static::$sTooltip
	 * @return $this
	 */
	public function SetTooltip(string $sTooltip)
	{
		$this->sTooltip = $sTooltip;

		return $this;
	}

	/**
	 * @see static::$sTooltip
	 * @return bool
	 */
	public function HasTooltip(): bool
	{
		return !empty($this->sTooltip);
	}
}