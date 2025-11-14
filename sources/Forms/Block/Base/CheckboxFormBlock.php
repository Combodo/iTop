<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\IO\Converter\StringToBooleanConverter;
use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Form block for checkbox.
 *
 */
class CheckboxFormBlock extends AbstractTypeFormBlock
{
	// outputs
	public const OUTPUT_CHECKED = 'checked';

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return CheckboxType::class;
	}

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('required', false);
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_CHECKED, BooleanIOFormat::class, new StringToBooleanConverter());
	}
}