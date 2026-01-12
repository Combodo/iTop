<?php

namespace Combodo\iTop\Application\UI\Base\Component\Badge;
use Combodo\iTop\Application\UI\Base\UIBlock;

class Badge extends UIBlock{
	public const BLOCK_CODE = 'ibo-badge';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/badge/layout';

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
	private string $sLabel;
	private string $sColor;
	private string $sTooltip;


	public function __construct(string $sLabel, string $sColor = self::DEFAULT_COLOR_SCHEME,string $sTooltip = '', string $sId = null)
	{
		parent::__construct($sId);

		$this->sLabel = $sLabel;
		$this->sColor = $sColor;
		$this->sTooltip = $sTooltip;
	}

	/**
	 * @return string
	 */
	public function GetTooltip(): string {
		return $this->sTooltip;
	}

	/**
	 * @param string $sTooltip
	 */
	public function SetTooltip(string $sTooltip) {
		$this->sTooltip = $sTooltip;
		return $this;
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
}