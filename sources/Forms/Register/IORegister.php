<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Register;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\Block\FormBlockHelper;
use Combodo\iTop\Forms\FormType\FormTypeHelper;
use Combodo\iTop\Forms\IO\Converter\AbstractConverter;
use Combodo\iTop\Forms\IO\FormBlockIOException;
use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\Forms\IO\FormOutput;

/**
 * IO register.
 *
 * @package Combodo\iTop\Forms\Register
 * @since 3.3.0
 */
class IORegister
{
	/** @var array  */
	private array $aInputs = [];

	/** @var array  */
	private array $aOutputs = [];

	/**
	 * @param AbstractFormBlock $oFormBlock
	 */
	public function __construct(private readonly AbstractFormBlock $oFormBlock)
	{
	}

	/**
	 * @param string $sName
	 * @param string $sType
	 *
	 * @return $this
	 * @throws FormBlockIOException
	 */
	public function AddInput(string $sName, string $sType): self
	{
		$oFormInput = new FormInput($sName, $sType, $this->oFormBlock);
		if (array_key_exists($oFormInput->GetName(), $this->aInputs)) {
			throw new RegisterException('Input already exists '.json_encode($oFormInput->GetName()).' for '.json_encode($this->oFormBlock->GetName()));
		}
		$this->aInputs[$oFormInput->GetName()] = $oFormInput;

		return $this;
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
	 * @throws FormBlockIOException
	 * @throws RegisterException
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
	 * @throws FormBlockIOException
	 * @throws RegisterException
	 */
	public function DependsOn(string $sInputName, string $sOutputBlockName, string $sOutputName): self
	{
		$oOutputBlock = $this->oFormBlock->GetParent()?->Get($sOutputBlockName);
		if (is_null($oOutputBlock)) {
			throw new RegisterException('Output block not found '.json_encode($sOutputBlockName));
		}
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
	 * @throws FormBlockIOException
	 * @throws RegisterException
	 */
	public function ImpactParent(string $sOutputName, string $sParentOutputName): self
	{
		$oFormOutput = $this->GetOutput($sOutputName);
		$oParentFormOutput = $this->oFormBlock->GetParent()->GetOutput($sParentOutputName);
		$oFormOutput->BindToOutput($oParentFormOutput);

		return $this;
	}

	/**
	 * @param string $sName
	 * @param string $sType
	 * @param AbstractConverter|null $oConverter
	 *
	 * @return void
	 * @throws RegisterException
	 */
	public function AddOutput(string $sName, string $sType, AbstractConverter $oConverter = null): void
	{
		$oFormOutput = new FormOutput($sName, $sType, $this->oFormBlock, $oConverter);
		if (array_key_exists($oFormOutput->GetName(), $this->aOutputs)) {
			throw new RegisterException('Output already exists '.json_encode($oFormOutput->GetName()).' for '.json_encode($this->oFormBlock->GetName()).' in block '.FormBlockHelper::GetFormId($this->oFormBlock).' of class '.get_class($this->oFormBlock));
		}
		$this->aOutputs[$oFormOutput->GetName()] = $oFormOutput;
	}

	/**
	 * Get an input.
	 *
	 * @param string $sName
	 *
	 * @return FormInput
	 * @throws RegisterException
	 */
	public function GetInput(string $sName): FormInput
	{
		if (!$this->HasInput($sName)) {
			throw new RegisterException('Missing input '.json_encode($sName).' for '.json_encode($this->oFormBlock->GetName()));
		}

		return $this->aInputs[$sName];
	}

	/**
	 * Test input existence.
	 *
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function HasInput(string $sName): bool
	{
		return array_key_exists($sName, $this->aInputs);
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
	 * @throws RegisterException
	 */
	public function GetOutput(string $sName): FormOutput
	{
		if (!array_key_exists($sName, $this->aOutputs)) {
			throw new RegisterException('Missing output '.json_encode($sName).' for '.json_encode($this->oFormBlock->GetName()));
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
	 * @throws FormBlockIOException
	 * @throws RegisterException
	 */
	public function DependsOnParent(string $sInputName, string $sParentInputName): self
	{
		$oFormInput = $this->GetInput($sInputName);
		$oParentFormInput = $this->oFormBlock->GetParent()->GetInput($sParentInputName);
		$oParentFormInput->BindToInput($oFormInput);

		return $this;
	}
}
