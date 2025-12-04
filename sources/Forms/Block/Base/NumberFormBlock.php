<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\IO\Format\NumberIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 * A block to manage a number input.
 * This block exposes a single output: the number value.
 *
 * @package Combodo\iTop\Forms\Block\Base
 * @since 3.3.0
 */
class NumberFormBlock extends AbstractTypeFormBlock
{
	// Outputs
	public const OUTPUT_NUMBER = "number";

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return NumberType::class;
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_NUMBER, NumberIOFormat::class);
	}
}
