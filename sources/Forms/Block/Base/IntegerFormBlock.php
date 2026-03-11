<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\IO\Format\IntegerIOFormat;
use Combodo\iTop\Forms\IO\Format\NumberIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * A block to manage an integer
 * This block exposes a single output: the integer value.
 *
 * @package Combodo\iTop\Forms\Block\Base
 * @since 3.3.0
 */
class IntegerFormBlock extends AbstractTypeFormBlock
{
	public const OUTPUT_INTEGER = 'integer';

	public function GetFormType(): string
	{
		return IntegerType::class;
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_INTEGER, IntegerIOFormat::class);
	}
}
