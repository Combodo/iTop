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
	/** @var string */
	protected $sColor;

	public function __construct(string $sColor)
	{
		parent::__construct(null, ["ibo-pill ibo-pill-is-{$sColor}"]);
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
}