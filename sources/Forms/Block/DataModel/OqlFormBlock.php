<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\TextAreaFormBlock;
use Combodo\iTop\Forms\FormType\DataModel\OqlFormType;
use Combodo\iTop\Forms\IO\Converter\OqlToClassConverter;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;

/**
 * A block to manage OQL expression input.
 * This block exposes an output providing the selected class from the OQL.
 *
 * @package Combodo\iTop\Forms\Block\DataModel
 * @since 3.3.0
 */
class OqlFormBlock extends TextAreaFormBlock
{
	// outputs
	public const OUTPUT_SELECTED_CLASS = 'selected_class';

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return OqlFormType::class;
	}

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('with_ai_button', false);
		$oOptionsRegister->SetOptionArrayValue('attr', 'placeholder', 'SELECT Contact');
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_SELECTED_CLASS, ClassIOFormat::class, false, new OqlToClassConverter());
	}

}
