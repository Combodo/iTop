<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Badge;


use Combodo\iTop\Application\UI\Layout\UIContentBlock;

class Badge extends UIContentBlock
{
	/** @var string */
	protected $sColor;

	public function __construct(string $sColor)
	{
		parent::__construct(null, "ibo-badge ibo-badge-is-{$sColor}");
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
	 * @return Badge
	 */
	public function SetColor(string $sColor): Badge
	{
		$this->sColor = $sColor;
		return $this;
	}
}