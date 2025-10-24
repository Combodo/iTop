<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

use Combodo\iTop\Forms\Block\FormBlockIOException;

class FormInput extends AbstractFormIO
{
	private string $sName;

	private string $sType;

	private FormBinding|null $oBinding = null;

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

	public function Bind(FormOutput $oFormOutput): void
	{
		if($this->sType !== $oFormOutput->GetType()){
			throw new FormBlockIOException('Cannot connect input types incompatibles ' . $this->sName . ' to ' . $sOutputBlock->GetName() . ' ' . $sOutputName);
		}

		$this->oBinding = new FormBinding($this, $oFormOutput);
	}

	public function GetBinding(): FormBinding
	{
		return $this->oBinding;
	}

	public function IsDataReady(string $sEventType): bool
	{
		return $this->oBinding->oOutput->HasValue($sEventType);
	}

	public function IsBound(): bool
	{
		return $this->oBinding !== null;
	}

}