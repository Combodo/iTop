<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

use Combodo\iTop\Forms\Block\AbstractFormBlock;

class AbstractFormIO
{

	private AbstractFormBlock $oOwnerBlock;
	private string $sName;
	private string $sType;

	public function __construct(string $sName, string $sType)
	{
		$this->sName = $sName;
		$this->sType = $sType;
	}

	public function GetName(): string
	{
		return $this->sName;
	}

	public function SetName(string $sName): void
	{
		$this->sName = $sName;
	}

	public function GetType(): string
	{
		return $this->sType;
	}

	public function SetType(string $sType): void
	{
		$this->sType = $sType;
	}

	public function GetOwnerBlock(): AbstractFormBlock
	{
		return $this->oOwnerBlock;
	}

	public function SetOwnerBlock(AbstractFormBlock $oOwnerBlock): void
	{
		$this->oOwnerBlock = $oOwnerBlock;
	}

}