<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

class FormInput
{
	private string $sName;

	private string $sType;

	private array $aConnections = [];

	public function __construct(string $sName, string $sType)
	{
		$this->sName = $sName;
		$this->sType = $sName;
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

	public function Connect(string $sOutputBlockName, string $sOutputName): void
	{
		$this->aConnections[] = ['output_block' => $sOutputBlockName, 'output' => $sOutputName];
	}

	public function GetConnections(): array
	{
		return $this->aConnections;
	}

	public function HasConnections(): bool
	{
		return count($this->aConnections) > 0;
	}
}