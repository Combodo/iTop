<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO;

/**
 * Form input IO.
 *
 * @package Combodo\iTop\Forms\IO
 * @since 3.3.0
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
	 * @param string|null $sEventType
	 *
	 * @return bool
	 */
	public function IsEventDataReady(string $sEventType = null): bool
	{
		return $this->HasEventValue($sEventType);
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
