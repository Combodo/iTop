<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO;

/**
 *
 */
class FormBinding
{
	public readonly AbstractFormIO $oSourceIO;
	public readonly AbstractFormIO $oDestinationIO;

	/**
	 * @param AbstractFormIO $oSourceIO
	 * @param AbstractFormIO $oDestinationIO
	 */
	public function __construct(AbstractFormIO $oSourceIO, AbstractFormIO $oDestinationIO)
	{
		$this->oDestinationIO = $oDestinationIO;
		$this->oSourceIO = $oSourceIO;
	}

	/**
	 * Propagate binding values.
	 *
	 * @return void
	 */
	public function PropagateValues(): void
	{
		$this->oDestinationIO->SetValues($this->oSourceIO->GetValues());
		$this->oDestinationIO->GetOwnerBlock()->BindingReceivedEvent($this->oDestinationIO);
	}
}