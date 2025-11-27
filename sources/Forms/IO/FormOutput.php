<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\IO\Converter\AbstractConverter;

/**
 *
 */
class FormOutput extends AbstractFormIO
{
	/** @var AbstractConverter|null */
	private null|AbstractConverter $oConverter;

	/** @var array */
	private array $aBindingsToOutputs = [];

	/**
	 * Constructor.
	 *
	 * @param string $sName
	 * @param string $sType
	 * @param AbstractConverter|null $oConverter
	 */
	public function __construct(string $sName, string $sType, AbstractFormBlock $oOwnerBlock, AbstractConverter $oConverter = null)
	{
		parent::__construct($sName, $sType, $oOwnerBlock);
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
			$sType = $this->GetDataType();
			return $oData !== null ? new $sType($oData) : null;
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
	 * Bind to output.
	 *
	 * @param FormOutput $oDestinationIO
	 *
	 * @return FormBinding
	 * @throws \Combodo\iTop\Forms\IO\FormBlockIOException
	 */
	public function BindToOutput(FormOutput $oDestinationIO): FormBinding
	{
		$oBinding = new FormBinding($this, $oDestinationIO);
		$this->aBindingsToOutputs[] = $oBinding;

		return $oBinding;
	}

	/**
	 * @return array
	 */
	public function GetBindingsToOutputs(): array
	{
		return $this->aBindingsToOutputs;
	}

	public function HasBindingOut(): bool
	{
		if (parent::HasBindingOut()) {
			return true; // has bindings to inputs
		}

		return count($this->aBindingsToOutputs) > 0;
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

	public function HasBindings(): bool
	{
		return count($this->aBindingsToInputs) > 0;
	}

	public function SetValue(string $sEventType, mixed $oValue): AbstractFormIO
	{
		parent::SetValue($sEventType, $oValue);

		// propagate the bindings values
		$this->PropagateBindingsValues();

		return $this;
	}
}
