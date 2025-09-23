<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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

	/** @var null|string CSS class that will be used on the block to define its color scheme */
	protected $sCSSColorClass;
	/** @var null|string URL to go to if the pill is clicked */
	protected $sUrl;
	/** @var null|string Text to display as a tooltip */
	protected $sTooltip;

	/**
	 * Pill constructor.
	 *
	 * @param string|null $sSemanticColor Semantic color code such as "success", "failure", "active", ... {@see css/backoffice/components/_pill.scss}
	 */
	public function __construct(?string $sSemanticColor = null)
	{
		parent::__construct();

		if (is_null($sSemanticColor) === false) {
			$this->SetSemanticColor($sSemanticColor);
		}
	}

	/**
	 * @see static::$sCSSColorClass
	 * @return string
	 */
	public function GetCSSColorClass(): ?string
	{
		return $this->sCSSColorClass;
	}

	/**
	 * @param string $sCSSColorClass
	 *
	 * @see static::$sCSSColorClass
	 * @return $this
	 */
	public function SetCSSColorClass(string $sCSSColorClass)
	{
		$this->sCSSColorClass = $sCSSColorClass;

		return $this;
	}

	/**
	 * @param string $sSemanticColor Semantic color code such as "success", "failure", "active", ... {@see css/backoffice/components/_pill.scss}
	 *
	 * @see static::$sCSSColorClass
	 * @return Pill
	 */
	public function SetSemanticColor(string $sSemanticColor)
	{
		$this->sCSSColorClass = 'ibo-is-'.$sSemanticColor;

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
	 * @see static::$sTooltip
	 *
	 * @param string|null $sTooltip
	 *
	 * @return $this
	 */
	public function SetTooltip(?string $sTooltip)
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