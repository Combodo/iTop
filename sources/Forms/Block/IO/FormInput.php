<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

/**
 *
 */
class FormInput extends AbstractFormIO
{
	/**
	 * @return bool
	 */
	public function IsDataReady(): bool
	{
		return $this->HasValue();
	}


	/**
	 * Set the values of the input.
	 *
	 * @param array $aValues
	 *
	 * @return AbstractFormIO
	 */
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