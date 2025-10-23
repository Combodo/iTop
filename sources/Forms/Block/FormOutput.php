<?php

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\Converter\AbstractConverter;

class FormOutput
{
	private string $sName;

	private string $sType;

	private AbstractConverter $oConverter;

	public function __construct(string $sName, string $sType, AbstractConverter $oConverter = null)
	{
		$this->sName = $sName;
		$this->sType = $sName;
		$this->oConverter = $oConverter;
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

	public function GetOutputValue(mixed $oData): mixed
	{
		$this->oConverter->Convert($oData);
	}


}