<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

use Combodo\iTop\Forms\Block\FormBlockIOException;

class FormInput extends AbstractFormIO
{
	private FormBinding|null $oBinding = null;

	private array $aBindingsToInputs = [];

	/**
	 * @throws FormBlockIOException
	 */
	public function BindFromOutput(FormOutput $oSourceIO): void
	{
		if($this->GetDataType() !== $oSourceIO->GetDataType()){
			throw new FormBlockIOException('Cannot connect input types incompatibles ' . $this->GetName() . ' from ' . $oSourceIO->GetOwnerBlock()->GetName() . ' ' . $oSourceIO->GetName());
		}

		$this->oBinding = $oSourceIO->BindToInput($this);
	}

	/**
	 * @throws FormBlockIOException
	 */
	public function BindFromInput(FormInput $oSourceIO): void
	{
		if($this->GetDataType() !== $oSourceIO->GetDataType()){
			throw new FormBlockIOException('Cannot connect input types incompatibles ' . $this->GetName() . ' from ' . $oSourceIO->GetOwnerBlock()->GetName() . ' ' . $oSourceIO->GetName());
		}

		$this->oBinding = $oSourceIO->BindToInput($this);
	}

	/**
	 * @throws FormBlockIOException
	 */
	public function BindToInput(FormInput $oDestinationIO): FormBinding
	{
		if($this->GetDataType() !== $oDestinationIO->GetDataType()){
			throw new FormBlockIOException('Cannot connect input types incompatibles ' . $this->GetName() . ' from ' . $oDestinationIO->GetOwnerBlock()->GetName() . ' ' . $oDestinationIO->GetName());
		}

		$oBinding = new FormBinding($this, $oDestinationIO);

		$this->aBindingsToInputs[] = $oBinding;

		return $oBinding;
	}


	public function GetBinding(): ?FormBinding
	{
		return $this->oBinding;
	}

	public function IsDataReady(): bool
	{
		return $this->HasValue();
	}

	public function IsBound(): bool
	{
		return $this->oBinding !== null;
	}

	public function SetValues(array $aValues): AbstractFormIO
	{
		parent::SetValues($aValues);

		$this->PropagateBindingsValues();

		return $this;
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
	}
}