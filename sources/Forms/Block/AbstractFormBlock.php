<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\Block\IO\FormInput;
use Combodo\iTop\Forms\Block\IO\FormOutput;

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
	/** @var string form block name */
	private string $sName;

	/** @var array form block options */
	private array $aOptions = [];

	/** @var array form sub blocks */
	private array $aSubFormBlocks = [];

	/** @var array form block inputs */
	private array $aFormInputs = [];

	/** @var array form block outputs */
	private array $aFormOutputs = [];

	/** @var bool flag */
	private bool $bIsAdded = false;

	/**
	 * Constructor.
	 *
	 * @param string $sName
	 * @param array $aOptions
	 */
	public function __construct(string $sName, array $aOptions = [])
	{
		$this->sName = $sName;
		$this->aOptions = $aOptions;

		$this->aOptions['form_block'] = $this;

		$this->InitInputs();
		$this->InitOutputs();
		$this->aOptions = array_merge($this->aOptions, $this->InitOptions());
		$this->BuildForm();
	}

	abstract protected function BuildForm();

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
	 * Add a sub form.
	 *
	 * @param AbstractFormBlock $oSubFormBlock
	 *
	 * @return $this
	 */
	public function AddSubFormBlock(AbstractFormBlock $oSubFormBlock): AbstractFormBlock
	{
		$this->aSubFormBlocks[] = $oSubFormBlock;

		return $this;
	}

	/**
	 * Get the sub forms.
	 *
	 * @return array
	 */
	public function GetSubFormBlocks(): array
	{
		return $this->aSubFormBlocks;
	}

	/**
	 * Add an input declaration.
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
	 * Get an input declaration.
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
	 * Add an output declaration.
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
	 * Get an output declaration.
	 *
	 * @param string $sName
	 *
	 * @return FormOutput
	 * @throws FormBlockException
	 */
	public function GetOutput(string $sName): FormOutput
	{
		if (!array_key_exists($sName, $this->aFormOutputs)) {
			throw new FormBlockException('Missing ouput '.$sName.' for '.$this->sName);
		}

		return $this->aFormOutputs[$sName];
	}

	public function GetOutputs(): array
	{
		return $this->aFormOutputs;
	}

	/**
	 * Attach an input to a block output.
	 *
	 * @param string $sInputName
	 * @param FormBlock $oOutputBlock
	 * @param string $sOutputName
	 *
	 * @return $this
	 * @throws FormBlockException
	 */
	public function DependsOn(string $sInputName, FormBlock $oOutputBlock, string $sOutputName): AbstractFormBlock
	{
		$oFormInput = $this->GetInput($sInputName);
		$oFormOutput = $oOutputBlock->GetOutput($sOutputName);
		$oFormInput->Bind($oFormOutput);

		return $this;
	}

	public function DependsOnParent(string $sInputName, FormBlock $oParentBlock, string $sParentInputName): AbstractFormBlock
	{
		$oFormInput = $this->GetInput($sInputName);
		$oParentFormInput = $oParentBlock->GetInput($sParentInputName);
		$oFormInput->Bind($oParentFormInput);

		return $this;
	}

	public function HasConnections(): bool
	{
		foreach ($this->aFormInputs as $oFormInput) {
			if ($oFormInput->IsBound()) {
				return true;
			}
		}

		return false;
	}

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

	public function IsInputsReady(string $sEventType): bool
	{
		foreach ($this->aFormInputs as $oFormInput) {
			if ($oFormInput->IsBound()) {
				if (!$oFormInput->IsDataReady($sEventType)) {
					return false;
				}
			}
		}

		return true;
	}

	public function IsAdded(): bool
	{
		return $this->bIsAdded;
	}

	public function SetAdded(bool $bIsAdded): void
	{
		$this->bIsAdded = $bIsAdded;
	}

	/**
	 * Return the form type.
	 *
	 * @return string
	 */
	abstract public function GetFormType(): string;

	/**
	 * Initialize inputs.
	 *
	 * @return void
	 */
	abstract public function InitInputs(): void;

	/**
	 * Initialize outputs.
	 *
	 * @return void
	 */
	abstract public function InitOutputs(): void;

	/**
	 * Initialize options.
	 *
	 * @return array
	 */
	abstract public function InitOptions(): array;
}