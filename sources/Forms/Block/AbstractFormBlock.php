<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\Block\IO\FormInput;
use Combodo\iTop\Forms\Block\IO\FormOutput;
use IssueLog;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Abstract form block.
 *
 * A form block describe a form (complex or simple type).
 * A complex form have sub blocks.
 * It defines its inputs and outputs.
 *
 */
abstract class AbstractFormBlock
{
	/** @var array form block inputs */
	private array $aFormInputs = [];

	/** @var array form block outputs */
	private array $aFormOutputs = [];

	/** @var bool flag */
	private bool $bIsAdded = false;

	/**
	 * Return the form type.
	 *
	 * @return string
	 */
	abstract public function GetFormType(): string;

	/**
	 * Initialize options.
	 *
	 * @return array
	 */
	abstract public function InitOptions(): array;

	/**
	 * Constructor.
	 *
	 * @param string $sName
	 * @param array $aOptions
	 */
	public function __construct(private readonly string $sName, private array $aOptions = [])
	{
		// Attach the form block
		$this->aOptions['form_block'] = $this;

		// Compute options
		$this->aOptions = array_merge($this->aOptions, $this->InitOptions());

		// Initialize block inputs
		$this->InitInputs();

		// Initialize block outputs
		$this->InitOutputs();
	}

	/**
	 * Return the form block name.
	 *
	 * @return string
	 */
	public function GetName(): string
	{
		return $this->sName;
	}

	/**
	 * Return the form block options.
	 * Options will be passed to FormType for building.
	 *
	 * @return array
	 */
	public function GetOptions(): array
	{
		return $this->aOptions;
	}

	/**
	 * Return the form block options.
	 * Options will be passed to FormType for building.
	 *
	 * @return array
	 */
	public function UpdateOptions(): array
	{
		return $this->aOptions;
	}

	/**
	 * Add an input.
	 *
	 * @param FormInput $oFormInput
	 *
	 * @return void
	 */
	public function AddInput(FormInput $oFormInput): void
	{
		$oFormInput->SetOwnerBlock($this);
		$this->aFormInputs[$oFormInput->GetName()] = $oFormInput;
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
		if(!array_key_exists($sName, $this->aFormInputs)) {
			throw new FormBlockException('Missing input ' . $sName . ' for ' . $this->sName);
		}

		return $this->aFormInputs[$sName];
	}

	/**
	 * Add an output.
	 *
	 * @param FormOutput $oFormOutput
	 *
	 * @return void
	 */
	public function AddOutput(FormOutput $oFormOutput): void
	{
		$oFormOutput->SetOwnerBlock($this);
		$this->aFormOutputs[$oFormOutput->GetName()] = $oFormOutput;
	}

	/**
	 * Get an output.
	 *
	 * @param string $sName
	 *
	 * @return FormOutput
	 * @throws FormBlockException
	 */
	public function GetOutput(string $sName): FormOutput
	{
		if(!array_key_exists($sName, $this->aFormOutputs)) {
			throw new FormBlockException('Missing ouput ' . $sName . ' for ' . $this->sName);
		}

		return $this->aFormOutputs[$sName];
	}

	/**
	 * Return the inputs.
	 *
	 * @return array
	 */
	public function GetInputs(): array
	{
		return $this->aFormInputs;
	}

	/**
	 * Return the outputs.
	 *
	 * @return array
	 */
	public function GetOutputs(): array
	{
		return $this->aFormOutputs;
	}

	/**
	 * Attach an input to a block output.
	 *
	 * @param string $sInputName
	 * @param AbstractFormBlock $oOutputBlock
	 * @param string $sOutputName
	 *
	 * @return $this
	 * @throws FormBlockException
	 * @throws FormBlockIOException
	 */
	public function DependsOnBlockOutput(string $sInputName, AbstractFormBlock $oOutputBlock, string $sOutputName): AbstractFormBlock
	{
		$oFormInput = $this->GetInput($sInputName);
		$oFormOutput = $oOutputBlock->GetOutput($sOutputName);
		$oFormInput->BindFromOutput($oFormOutput);

		return $this;
	}

	/**
	 * Attach an input to a parent block input.
	 *
	 * @param string $sInputName
	 * @param AbstractFormBlock $oParentBlock
	 * @param string $sParentInputName
	 *
	 * @return $this
	 * @throws FormBlockException
	 * @throws FormBlockIOException
	 */
	public function DependsOnParentBlockInput(string $sInputName, AbstractFormBlock $oParentBlock, string $sParentInputName): AbstractFormBlock
	{
		$oFormInput = $this->GetInput($sInputName);
		$oParentFormInput = $oParentBlock->GetInput($sInputName);
		$oFormInput->BindFromInput($oParentFormInput);

		return $this;
	}

	/**
	 * Attach an output to a parent block outpu.
	 *
	 * @param string $sOutputName
	 * @param AbstractFormBlock $oParentBlock
	 * @param string $sParentInputName
	 *
	 * @return $this
	 * @throws FormBlockException
	 * @throws FormBlockIOException
	 */
	public function BindOutputToParentBlockOutput(string $sOutputName, AbstractFormBlock $oParentBlock, string $sParentOutputName): AbstractFormBlock
	{
		$oFormOutput = $this->GetOutput($sOutputName);
		$oParentFormOutput = $oParentBlock->GetOutput($sParentOutputName);
		$oParentFormOutput->BindFromOutput($oFormOutput);

		return $this;
	}

	/**
	 * @return bool
	 */
	public function HasAtLeastOneBoundInput(): bool
	{
		foreach ($this->aFormInputs as $oFormInput) {
			if ($oFormInput->IsBound()) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return bool
	 */
	public function HasAtLeastOneBoundOutput(): bool
	{
		/** @var FormOutput $oFormOutput */
		foreach ($this->aFormOutputs as $oFormOutput) {
			if (count($oFormOutput->GetBindings()) > 0) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return array
	 */
	public function GetInputsBindings(): array
	{
		$aBindings = [];

		/** @var FormInput $oFormInput */
		foreach ($this->aFormInputs as $oFormInput) {
			if ($oFormInput->IsBound()) {
				$aBindings[$oFormInput->GetName()] = $oFormInput->GetBinding();
			}
		}
		return $aBindings;
	}

	/**
	 * @return array
	 */
	public function GetOutputBindings(): array
	{
		$aBindings = [];

		/** @var FormInput $oFormInput */
		foreach ($this->aFormOutputs as $oFormOutput) {
			if ($oFormOutput->IsBound()) {
				$aBindings[$oFormOutput->GetName()] = $oFormOutput->GetBinding();
			}
		}
		return $aBindings;
	}

	/**
	 * @return bool
	 */
	public function IsInputsReady(): bool
	{
		foreach ($this->aFormInputs as $oFormInput) {
			if ($oFormInput->IsBound()) {
				if(!$oFormInput->IsDataReady()) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function IsAdded(): bool
	{
		return $this->bIsAdded;
	}

	/**
	 * @param bool $bIsAdded
	 *
	 * @return void
	 */
	public function SetAdded(bool $bIsAdded): void
	{
		$this->bIsAdded = $bIsAdded;
	}

	/**
	 * @param string $sEventType
	 * @param mixed $oData
	 *
	 * @return void
	 */
	public function ComputeOutputs(string $sEventType, mixed $oData): void
	{
		/** Iterate throw output @var FormOutput $oFormOutput */
		foreach ($this->aFormOutputs as $oFormOutput) {

			// Compute the output value
			try{
				$oFormOutput->ComputeValue($sEventType, $oData);
			}
			catch(IOException $oException){
				IssueLog::Exception(sprintf('Unable to compute values for output %s of block %s', $oFormOutput->GetName(), $this->GetName()), $oException);
			}

		}
	}

	/**
	 * Propagate inputs values.
	 *
	 * @return void
	 */
	public function PropagateInputsValues(): void
	{
		foreach ($this->aFormInputs as $oFormInput) {
			$oFormInput->PropagateBindingsValues();
		}
	}

	/**
	 * Initialize inputs.
	 *
	 * @return void
	 */
	public function InitInputs(): void
	{

	}

	/**
	 * Initialize outputs.
	 *
	 * @return void
	 */
	public function InitOutputs()
	{

	}


	/**
	 * @return true
	 */
	public function AllowAdd(): bool
	{
		return true;
	}
}