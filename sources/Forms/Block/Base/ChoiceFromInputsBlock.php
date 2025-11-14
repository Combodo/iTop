<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\Forms\Register\OptionsRegister;

/**
 *
 */
class ChoiceFromInputsBlock extends ChoiceFormBlock
{
	/** @inheritdoc  */
	public function UpdateOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::UpdateOptions($oOptionsRegister);

		// Compute options based on inputs values
		$aChoices = [];
		/** @var FormInput $oInput */
		foreach ($this->GetInputs() as $oInput) {
			if($oInput->HasValue()){
				$aChoices[strval($oInput->GetValue())] = $oInput->GetName();
			}
		}
		$oOptionsRegister->SetOption('choices', $aChoices);
	}
}