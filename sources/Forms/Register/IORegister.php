<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Register;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\IO\Converter\AbstractConverter;
use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\Forms\IO\FormOutput;

/**
 *
 */
class IORegister
{
	/** @var array  */
	private array $aInputs = [];

	/** @var array  */
	private array $aOutputs = [];

	/**
	 * @param \Combodo\iTop\Forms\Block\AbstractFormBlock $oFormBlock
	 */
	public function __construct(private readonly AbstractFormBlock $oFormBlock)
	{
	}

	public function AddInput(string $sName, string $sType): void
	{
		$oFormInput = new FormInput($sName, $sType, $this->oFormBlock);
		$this->aInputs[$oFormInput->GetName()] = $oFormInput;
	}

	/**
	 * Add an input connected to another block.
	 *
	 * @param string $sName the input name
	 * @param string $sOutputBlockName
	 * @param string $sOutputName
	 *
	 * @return $this
	 * @throws FormBlockException
	 */
	public function AddInputDependsOn(string $sName, string $sOutputBlockName, string $sOutputName): self
	{
		$oOutputBlock = $this->oFormBlock->GetParent()->Get($sOutputBlockName);
		$oBlockOutput = $oOutputBlock->GetOutput($sOutputName);

		$this->AddInput($sName, $oBlockOutput->GetDataType());
		$this->DependsOn($sName, $sOutputBlockName, $sOutputName);

		return $this;
	}

	/**
	 * Attach an input to a block output.
	 *
	 * @param string $sInputName the input name
	 * @param string $sOutputBlockName the dependency block name
	 * @param string $sOutputName the dependency output name
	 *
	 * @return $this
	 * @throws FormBlockException
	 */
	public function DependsOn(string $sInputName, string $sOutputBlockName, string $sOutputName): self
	{
		$oOutputBlock = $this->oFormBlock->GetParent()->Get($sOutputBlockName);
		$oFormInput = $this->GetInput($sInputName);
		$oFormOutput = $oOutputBlock->GetOutput($sOutputName);
		$oFormOutput->BindToInput($oFormInput);

		return $this;
	}

	/**
	 * Attach an output to a parent block output.
	 *
	 * @param string $sOutputName output name
	 * @param string $sParentOutputName parent output name
	 *
	 * @return $this
	 * @throws FormBlockException
	 */
	public function ImpactParent(string $sOutputName, string $sParentOutputName): self
	{
		$oFormOutput = $this->GetOutput($sOutputName);
		$oParentFormOutput = $this->oFormBlock->GetParent()->GetOutput($sParentOutputName);
		$oFormOutput->BindToOutput($oParentFormOutput);

		return $this;
	}

	public function AddOutput(string $sName, string $sType, AbstractConverter $oConverter = null): void
	{
		$oFormOutput = new FormOutput($sName, $sType, $this->oFormBlock, $oConverter);
		$this->aOutputs[$oFormOutput->GetName()] = $oFormOutput;
	}

	/**
	 * Get an input.
	 *
	 * @param string $sName
	 *
	 * @return FormInput
	 * @throws FormBlockException
	 */
	public function GetInput(string $sName): FormInput
	{
		if (!array_key_exists($sName, $this->aInputs)) {
			throw new FormBlockException('Missing input '.$sName.' for '.$this->oFormBlock->GetName());
		}

		return $this->aInputs[$sName];
	}

	/**
	 * @return array
	 */
	public function GetInputs(): array
	{
		return $this->aInputs;
	}

	/**
	 * @return array
	 */
	public function GetBoundInputs(): array
	{
		$aInputs = [];

		/** @var FormInput $oFormInput */
		foreach ($this->aInputs as $oFormInput) {
			if ($oFormInput->IsBound() || $oFormInput->HasBindingOut()) {
				$aInputs[] = $oFormInput;
			}
		}

		return $aInputs;
	}

	/**
	 * @return array
	 */
	public function GetBoundOutputs(): array
	{
		$aOutputs = [];

		/** @var FormOutput $oFormOutput */
		foreach ($this->aOutputs as $oFormOutput) {
			if ($oFormOutput->IsBound() || $oFormOutput->HasBindingOut()) {
				$aOutputs[] = $oFormOutput;
			}
		}

		return $aOutputs;
	}

	/**
	 * Get an output.
	 *
	 * @param string $sName output name
	 *
	 * @return FormOutput
	 * @throws FormBlockException
	 */
	public function GetOutput(string $sName): FormOutput
	{
		if (!array_key_exists($sName, $this->aOutputs)) {
			throw new FormBlockException('Missing output '.json_encode($sName).' for '.json_encode($this->oFormBlock->GetName()));
		}

		return $this->aOutputs[$sName];
	}

	/**
	 * @return array
	 */
	public function GetOutputs(): array
	{
		return $this->aOutputs;
	}

	/**
	 * Check existence of one or more dependencies.
	 *
	 * @return bool
	 */
	public function HasDependenciesBlocks(): bool
	{
		foreach ($this->aInputs as $oFormInput) {
			if ($oFormInput->IsBound()) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check existence of one or more dependents blocks.
	 *
	 * @return bool
	 */
	public function ImpactDependentsBlocks(): bool
	{
		/** @var FormOutput $oFormOutput */
		foreach ($this->aOutputs as $oFormOutput) {
			if (count($oFormOutput->GetBindings()) > 0) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get bound inputs bindings.
	 *
	 * @return array
	 */
	public function GetBoundInputsBindings(): array
	{
		$aBindings = [];

		/** @var FormInput $oFormInput */
		foreach ($this->aInputs as $oFormInput) {
			if ($oFormInput->IsBound()) {
				$aBindings[$oFormInput->GetName()] = $oFormInput->GetBinding();
			}
		}

		return $aBindings;
	}

	/**
	 * Get bound outputs bindings.
	 *
	 * @return array
	 */
	public function GetBoundOutputBindings(): array
	{
		$aBindings = [];

		/** @var FormInput $oFormInput */
		foreach ($this->aOutputs as $oFormOutput) {
			if ($oFormOutput->IsBound()) {
				$aBindings[$oFormOutput->GetName()] = $oFormOutput->GetBinding();
			}
		}

		return $aBindings;
	}

	/**
	 * Inputs data ready.
	 *
	 * @param string|null $sType
	 *
	 * @return bool
	 */
	public function IsInputsDataReady(string $sType = null): bool
	{
		foreach ($this->aInputs as $oFormInput) {
			if ($oFormInput->IsBound()) {
				if (!$oFormInput->IsEventDataReady($sType)) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Compute outputs values.
	 *
	 * @param string $sEventType
	 * @param mixed $oData
	 *
	 * @return void
	 */
	public function ComputeOutputs(string $sEventType, mixed $oData): void
	{
		/** Iterate throw output @var FormOutput $oFormOutput */
		foreach ($this->aOutputs as $oFormOutput) {

			// Compute the output value
			$oFormOutput->ComputeValue($sEventType, $oData);

		}

	}

	/**
	 * Attach an input to a parent block input.
	 *
	 * @param string $sInputName input name
	 * @param string $sParentInputName parent input name
	 *
	 * @return $this
	 * @throws FormBlockException
	 */
	public function DependsOnParent(string $sInputName, string $sParentInputName): self
	{
		$oFormInput = $this->GetInput($sInputName);
		$oParentFormInput = $this->oFormBlock->GetParent()->GetInput($sParentInputName);
		$oParentFormInput->BindToInput($oFormInput);

		return $this;
	}
}
