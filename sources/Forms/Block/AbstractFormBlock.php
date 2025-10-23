<?php

namespace Combodo\iTop\Forms\Block;

abstract class AbstractFormBlock
{

	private array $aOptions = [];

	private array $aSubFormBlocks = [];

	private array $aFormInputs = [];

	private array $aFormOutputs = [];

	public function __construct(array $aOptions = [])
	{
		$this->aOptions = $aOptions;
	}

	public function GetOptions(): array
	{
		return $this->aOptions;
	}

	public function AddSubFormBlock(AbstractFormBlock $oSubFormBlock): void
	{
		$this->aSubFormBlocks[] = $oSubFormBlock;
	}

	public function GetSubFormBlocks(): array
	{
		return $this->aSubFormBlocks;
	}

	public function AddInput(FormInput $oFormInput): void
	{
		$this->aFormInputs[$oFormInput->GetName()] = $oFormInput;
	}

	public function GetInput(string $sName): FormInput
	{
		return $this->aFormInputs[$sName];
	}

	public function AddOutput(FormOutput $oFormOutput): void
	{
		$this->aFormOutputs[$oFormOutput->GetName()] = $oFormOutput;
	}

	public function GetOutput(string $sName): FormOutput
	{
		return $this->aFormOutputs[$sName];
	}


}