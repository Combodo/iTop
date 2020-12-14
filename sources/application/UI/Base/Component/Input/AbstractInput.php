<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Input;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * @package Combodo\iTop\Application\UI\Base\Component\Input
 */
class AbstractInput extends UIBlock
{
	/** @var string */
	protected $sName;
	/** @var string */
	protected $sValue;

	public function GetName(): string
	{
		return $this->sName;
	}

	public function SetName(string $sName): AbstractInput
	{
		$this->sName = $sName;

		return $this;
	}

	public function GetValue(): ?string
	{
		return $this->sValue;
	}

	public function SetValue(?string $sValue): AbstractInput
	{
		$this->sValue = $sValue;

		return $this;
	}
}