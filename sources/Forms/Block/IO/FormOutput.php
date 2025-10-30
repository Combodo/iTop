<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\IO\Converter\AbstractConverter;

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

		$oDestinationIO->Attach($oBinding);

		return $oBinding;
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
}