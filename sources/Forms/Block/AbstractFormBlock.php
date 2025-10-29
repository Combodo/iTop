<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\Block\IO\Converter\AbstractConverter;
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
	/** @var null|FormBlock */
	private ?FormBlock $oParent = null;

	/** @var array form block inputs */
	private array $aFormInputs = [];

	/** @var array form block outputs */
	private array $aFormOutputs = [];

	/** @var bool flag indicating the form insertion */
	private bool $bIsAddedToForm = false;

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
	public function __construct(private readonly string $sName, protected array $aOptions = [])
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
	 * Set the parent block.
	 *
	 * @param FormBlock $oParent
	 *
	 * @return void
	 */
	public function SetParent(FormBlock $oParent): void
	{
		$this->oParent = $oParent;
	}

	/**
	 * Get the parent block.
	 *
	 * @return FormBlock
	 */
	public function GetParent(): FormBlock
	{
		return $this->oParent;
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
	 * @param string $sName the input name
	 * @param string $sType the type of the input
	 *
	 * @return void
	 */
	public function AddInput(string $sName, string $sType): void
	{
		$oFormInput = new FormInput($sName, $sType);
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
		if (!array_key_exists($sName, $this->aFormInputs)) {
			throw new FormBlockException('Missing input '.$sName.' for '.$this->sName);
		}

		return $this->aFormInputs[$sName];
	}

	/**
	 * Add an output.
	 *
	 * @param string $sName
	 * @param string $sType
	 * @param AbstractConverter|null $oConverter
	 *
	 * @return void
	 */
	public function AddOutput(string $sName, string $sType, AbstractConverter $oConverter = null): void
	{
		$oFormOutput = new FormOutput($sName, $sType, $oConverter);
		$oFormOutput->SetOwnerBlock($this);
		$this->aFormOutputs[$oFormOutput->GetName()] = $oFormOutput;
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
		if (!array_key_exists($sName, $this->aFormOutputs)) {
			throw new FormBlockException('Missing output '.$sName.' for '.$this->sName);
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
	 * @param string $sInputName the input name
	 * @param string $sOutputBlockName the dependency block name
	 * @param string $sOutputName the dependency output name
	 *
	 * @return $this
	 * @throws FormBlockException
	 */
	public function DependsOn(string $sInputName, string $sOutputBlockName, string $sOutputName): AbstractFormBlock
	{

		$oOutputBlock = $this->GetParent()->Get($sOutputBlockName);
		$oFormInput = $this->GetInput($sInputName);
		$oFormOutput = $oOutputBlock->GetOutput($sOutputName);
		$oFormOutput->BindToInput($oFormInput);

		return $this;
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
	 */
	public function DependsOnParent(string $sInputName, string $sParentInputName): AbstractFormBlock
	{
		$oFormInput = $this->GetInput($sInputName);
		$oParentFormInput = $this->GetParent()->GetInput($sParentInputName);
		$oParentFormInput->BindToInput($oFormInput);

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
	public function ImpactParent(string $sOutputName, string $sParentOutputName): AbstractFormBlock
	{
		$oFormOutput = $this->GetOutput($sOutputName);
		$oParentFormOutput = $this->GetParent()->GetOutput($sParentOutputName);
		$oFormOutput->BindToOutput($oParentFormOutput);

		return $this;
	}

	/**
	 * Check existence of one or more dependencies.
	 *
	 * @return bool
	 */
	public function HasDependenciesBlocks(): bool
	{
		foreach ($this->aFormInputs as $oFormInput) {
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
		foreach ($this->aFormOutputs as $oFormOutput) {
			if (count($oFormOutput->GetBindings()) > 0) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get bindings on inputs.
	 *
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
	 * Get bindings on outputs.
	 *
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
	 * Inputs data ready.
	 *
	 * @return bool
	 */
	public function IsInputsDataReady(): bool
	{
		foreach ($this->aFormInputs as $oFormInput) {
			if ($oFormInput->IsBound()) {
				if (!$oFormInput->IsDataReady()) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * The block has been added to its parent.
	 *
	 * @return bool
	 */
	public function IsAdded(): bool
	{
		return $this->bIsAddedToForm;
	}

	/**
	 * Indicate that the block has been added to its parent.
	 *
	 * @param bool $bIsAdded
	 *
	 * @return void
	 */
	public function SetAdded(bool $bIsAdded): void
	{
		$this->bIsAddedToForm = $bIsAdded;
	}

	public function ListBlockNames(): array
	{
		$aNames = [];
		foreach ($this->aSubFormBlocks as $oSubFormBlock) {
			$aNames[] = $oSubFormBlock->GetName();
		}

		return $aNames;
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
		foreach ($this->aFormOutputs as $oFormOutput) {

			try{
				// Compute the output value
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