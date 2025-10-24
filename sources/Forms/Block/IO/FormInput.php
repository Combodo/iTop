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


	public function Bind(AbstractFormIO $oSourceIO): void
	{
		if($this->GetType() !== $oSourceIO->GetType()){
			throw new FormBlockIOException('Cannot connect input types incompatibles ' . $this->GetName() . ' from ' . $oSourceIO->GetOwnerBlock()->GetName() . ' ' . $oSourceIO->GetName());
		}

		$this->oBinding = new FormBinding($this, $oSourceIO);
	}

	public function GetBinding(): FormBinding
	{
		return $this->oBinding;
	}

	public function IsDataReady(string $sEventType): bool
	{
		return $this->oBinding->oSourceIO->HasValue($sEventType);
	}

	public function IsBound(): bool
	{
		return $this->oBinding !== null;
	}

}