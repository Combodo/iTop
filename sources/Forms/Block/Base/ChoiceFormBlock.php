<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\FormType\Base\ChoiceFormType;
use Combodo\iTop\Forms\IO\Converter\ChoiceValueToLabelConverter;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\Register\IORegister;

/**
 * Form block for choices.
 *
 */
class ChoiceFormBlock extends AbstractTypeFormBlock
{
	// Outputs
	public const OUTPUT_LABEL = 'label';
	public const OUTPUT_VALUE = 'value';

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return ChoiceFormType::class;
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_LABEL, StringIOFormat::class, new ChoiceValueToLabelConverter($this));
		$oIORegister->AddOutput(self::OUTPUT_VALUE, StringIOFormat::class);
	}
}
