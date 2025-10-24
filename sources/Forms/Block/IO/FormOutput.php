<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

use Combodo\iTop\Forms\Converter\AbstractOutputConverter;

class FormOutput
{
	private string $sName;

	private string $sType;

	private null|AbstractOutputConverter $oConverter;
	private array $aValues;

	public function __construct(string $sName, string $sType, AbstractOutputConverter $oConverter = null)
	{
		$this->sName = $sName;
		$this->sType = $sType;
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

	public function ConvertValue(mixed $oData): mixed
	{
		if (is_null($this->oConverter)) {
			return $oData;
		}
		return $this->oConverter->Convert($oData);
	}

	public function UpdateOutputValue(mixed $oData, string $sEventType): void
	{
		$this->aValues[$sEventType] = $this->ConvertValue($oData);
	}

	public function GetValue(string $sEventType): mixed
	{
		return $this->aValues[$sEventType] ?? null;
	}


}