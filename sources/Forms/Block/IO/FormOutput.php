<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

use Combodo\iTop\Forms\Block\FormBlockIOException;
use Combodo\iTop\Forms\Block\IO\Converter\AbstractConverter;

class FormOutput extends AbstractFormIO
{
	/** @var AbstractConverter|null  */
	private null|AbstractConverter $oConverter;

	private FormBinding|null $oBinding = null;

	/** @var array  */
	private array $aBindingsToInputs = [];

	/** @var array  */
	private array $aBindingsToOutputs = [];

	/**
	 * Constructor.
	 *
	 * @param string $sName
	 * @param string $sType
	 * @param AbstractConverter|null $oConverter
	 */
	public function __construct(string $sName, string $sType, AbstractConverter $oConverter = null)
	{
		parent::__construct($sName, $sType);
		$this->oConverter = $oConverter;
	}

	/**
	 * Convert the value.
	 *
	 * @param mixed $oData
	 *
	 * @return mixed
	 */
	public function ConvertValue(mixed $oData): mixed
	{
		if (is_null($this->oConverter)) {
			return $oData;
		}
		return $this->oConverter->Convert($oData);
	}

	/**
	 * Compute the value.
	 *
	 * @param string $sEventType
	 * @param mixed $oData
	 *
	 * @return void
	 */
	public function ComputeValue(string $sEventType, mixed $oData): void
	{
		$this->SetValue($sEventType, $this->ConvertValue($oData));

		// propagate the bindings values
		$this->PropagateBindingsValues();
	}

	/**
	 * Propagate the bindings values.
	 *
	 * @return void
	 */
	public function PropagateBindingsValues(): void
	{
		// propagate the value
		foreach ($this->aBindingsToInputs as $oBinding) {
			$oBinding->PropagateValues();
		}

		// propagate the value
		foreach ($this->aBindingsToOutputs as $oBinding) {
			$oBinding->PropagateValues();
		}
	}

	/**
	 * Bind to input.
	 *
	 * @param FormInput $oDestinationIO
	 *
	 * @return FormBinding
	 */
	public function BindToInput(FormInput $oDestinationIO): FormBinding
	{
		$oBinding = new FormBinding($this, $oDestinationIO);

		$this->aBindingsToInputs[] = $oBinding;

		return $oBinding;
	}

	/**
	 * Bind to output.
	 *
	 * @param FormOutput $oDestinationIO
	 *
	 * @return FormBinding
	 */
	public function BindToOutput(FormOutput $oDestinationIO): FormBinding
	{
		$oBinding = new FormBinding($this, $oDestinationIO);

		$this->aBindingsToOutputs[] = $oBinding;

		return $oBinding;
	}

	public function GetBinding(): ?FormBinding
	{
		return $this->oBinding;
	}

	/**
	 * @throws FormBlockIOException
	 */
	public function BindFromOutput(FormOutput $oSourceIO): void
	{
		if($this->GetDataType() !== $oSourceIO->GetDataType()){
			throw new FormBlockIOException('Cannot connect input types incompatibles ' . $this->GetName() . ' from ' . $oSourceIO->GetOwnerBlock()->GetName() . ' ' . $oSourceIO->GetName());
		}

		$this->oBinding = $oSourceIO->BindToOutput($this);
	}

	public function IsBound(): bool
	{
		return $this->oBinding !== null;
	}

	/**
	 * Get the bindings.
	 *
	 * @return array
	 */
	public function GetBindings(): array
	{
		return $this->aBindingsToInputs;
	}
}