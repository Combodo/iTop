<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\Forms\IO\AbstractFormIO;
use Combodo\iTop\Forms\IO\Converter\AbstractConverter;
use Combodo\iTop\Forms\IO\FormBlockIOException;
use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\Forms\IO\FormOutput;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Combodo\iTop\Forms\Register\RegisterException;
use Combodo\iTop\Forms\Block\Base\CollectionBlock;

/**
 * Abstract form block.
 *
 * @package Combodo\iTop\Forms\Block
 * @since 3.3.0
 */
abstract class AbstractFormBlock implements IFormBlock
{
	/** @var null|FormBlock|CollectionBlock */
	private FormBlock|CollectionBlock|null $oParent = null;

	/** @var OptionsRegister */
	private OptionsRegister $oOptionsRegister;

	/** @var IORegister */
	private IORegister $oIORegister;

	/**
	 * Constructor.
	 *
	 * @param string $sName
	 * @param array $aOptions
	 */
	public function __construct(private readonly string $sName, array $aOptions = [])
	{
		// Register IO
		$this->RegisterIO($this->oIORegister = new IORegister($this));
		$this->AfterIORegistered($this->oIORegister);

		// Register options
		$this->RegisterOptions($this->oOptionsRegister = new OptionsRegister());
		$this->SetOptions($aOptions);
		$this->AfterOptionsRegistered($this->oOptionsRegister);
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
	 * Set the parent block.
	 *
	 * @param FormBlock|CollectionBlock $oParent
	 *
	 * @return void
	 */
	public function SetParent(FormBlock|CollectionBlock $oParent): void
	{
		$this->oParent = $oParent;
	}

	/**
	 * Get the parent block.
	 *
	 * @return FormBlock|CollectionBlock|null
	 */
	public function GetParent(): FormBlock|CollectionBlock|null
	{
		return $this->oParent;
	}

	/**
	 * Return true if this block is root.
	 *
	 * @return bool
	 */
	public function IsRootBlock(): bool
	{
		return $this->oParent === null;
	}

	/**
	 * Register options.
	 *
	 * @param OptionsRegister $oOptionsRegister
	 *
	 * @throws RegisterException
	 */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		$oOptionsRegister->SetOption('form_block', $this);
	}

	/**
	 * @param array $aOptions
	 *
	 * @return void
	 */
	private function SetOptions(array $aOptions): void
	{
		foreach ($aOptions as $sOptionName => $mOptionValue) {
			$this->oOptionsRegister->SetOption($sOptionName, $mOptionValue);
		}
	}

	/**
	 * @param OptionsRegister $oOptionsRegister
	 *
	 * @return void
	 */
	protected function AfterOptionsRegistered(OptionsRegister $oOptionsRegister): void
	{

	}

	/**
	 * Return the form block options.
	 * Options will be passed to FormType for building.
	 *
	 * @return array
	 */
	public function GetOptions(): array
	{
		return $this->oOptionsRegister->GetOptions();
	}

	/**
	 * @param string $sOption
	 *
	 * @return mixed
	 */
	public function GetOption(string $sOption): mixed
	{
		return $this->oOptionsRegister->GetOption($sOption);
	}

	/**
	 * @param OptionsRegister $oOptionsRegister
	 *
	 * @return void
	 */
	public function UpdateOptions(OptionsRegister $oOptionsRegister): void
	{

	}

	/**
	 * @return array
	 */
	public function GetBoundInputsBindings(): array
	{
		return $this->oIORegister->GetBoundInputsBindings();
	}

	/**
	 * @return array
	 */
	public function GetBoundOutputBindings(): array
	{
		return $this->oIORegister->GetBoundOutputBindings();
	}

	/**
	 * Add an input.
	 *
	 * @param string $sName the input name
	 * @param string $sType the type of the input
	 *
	 * @return AbstractFormBlock
	 * @throws FormBlockIOException
	 */
	public function AddInput(string $sName, string $sType): AbstractFormBlock
	{
		$this->oIORegister->AddInput($sName, $sType);
		return $this;
	}

	/**
	 * Add an input connected to another block.
	 *
	 * @param string $sName the input name
	 * @param string $sOutputBlockName
	 * @param string $sOutputName
	 *
	 * @return AbstractFormBlock
	 * @throws FormBlockException
	 */
	public function AddInputDependsOn(string $sName, string $sOutputBlockName, string $sOutputName): AbstractFormBlock
	{
		$this->oIORegister->AddInputDependsOn($sName, $sOutputBlockName, $sOutputName);
		return $this;
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
		return $this->oIORegister->GetInput($sName);
	}

	/**
	 * Get an input value.
	 *
	 * @param string $sName
	 *
	 * @return mixed
	 * @throws FormBlockException
	 */
	public function GetInputValue(string $sName): mixed
	{
		return $this->oIORegister->GetInput($sName)->GetValue();
	}

	/**
	 * Add an output.
	 *
	 * @param string $sName
	 * @param string $sType
	 * @param AbstractConverter|null $oConverter
	 *
	 * @return AbstractFormBlock
	 */
	public function AddOutput(string $sName, string $sType, AbstractConverter $oConverter = null): AbstractFormBlock
	{
		$this->oIORegister->AddOutput($sName, $sType, $oConverter);
		return $this;
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
		return $this->oIORegister->GetOutput($sName);
	}

	/**
	 * Return the inputs.
	 *
	 * @return array
	 */
	public function GetInputs(): array
	{
		return $this->oIORegister->GetInputs();
	}

	/**
	 * @return array
	 */
	public function GetBoundInputs(): array
	{
		return $this->oIORegister->GetBoundInputs();
	}

	/**
	 * @return array
	 */
	public function GetBoundOutputs(): array
	{
		return $this->oIORegister->GetBoundOutputs();
	}

	/**
	 * Return the outputs.
	 *
	 * @return array
	 */
	public function GetOutputs(): array
	{
		return $this->oIORegister->GetOutputs();
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
		$this->oIORegister->DependsOn($sInputName, $sOutputBlockName, $sOutputName);
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
	 */
	public function DependsOnParent(string $sInputName, string $sParentInputName): AbstractFormBlock
	{
		$this->oIORegister->DependsOnParent($sInputName, $sParentInputName);
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
		$this->oIORegister->ImpactParent($sOutputName, $sParentOutputName);
		return $this;
	}

	/**
	 * Check existence of one or more dependencies.
	 *
	 * @return bool
	 */
	public function HasDependenciesBlocks(): bool
	{
		return $this->oIORegister->HasDependenciesBlocks();
	}

	/**
	 * Check existence of one or more dependents blocks.
	 *
	 * @return bool
	 */
	public function ImpactDependentsBlocks(): bool
	{
		return $this->oIORegister->ImpactDependentsBlocks();
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
		return $this->oIORegister->IsInputsDataReady($sType);
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
		$this->oIORegister->ComputeOutputs($sEventType, $oData);
	}

	/**
	 * Register IO.
	 *
	 * @param IORegister $oIORegister
	 *
	 * @return void
	 */
	protected function RegisterIO(IORegister $oIORegister): void
	{
	}

	/**
	 * @param IORegister $oIORegister
	 *
	 * @return void
	 */
	protected function AfterIORegistered(IORegister $oIORegister): void
	{

	}

	/**
	 * Called when a binding value has been transmitted.
	 *
	 * @param AbstractFormIO $oBlockIO
	 *
	 * @return void
	 */
	public function BindingReceivedEvent(AbstractFormIO $oBlockIO): void
	{
		if ($this->IsInputsDataReady()) {
			$this->UpdateOptions($this->oOptionsRegister);
			$this->AllInputsReadyEvent();
		}
	}

	/**
	 * Called when all inputs are ready.
	 *
	 * @return void
	 */
	public function AllInputsReadyEvent(): void
	{

	}
}
