<?php

namespace Combodo\iTop\Forms\Block;

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

	public function Connect(string $sOutputBlockName, string $sOutputName)
	{
		$this->aConnections[] = ['block' => $sOutputBlockName, 'output' => $sOutputName];
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