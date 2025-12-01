<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * A block to manage a text input.
 * This block exposes a single text output.
 *
 * @package Combodo\iTop\Forms\Block\Base
 * @since 3.3.0
 */
class TextFormBlock extends AbstractTypeFormBlock
{
	// Outputs
	public const OUTPUT_TEXT = "text";

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return TextType::class;
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_TEXT, StringIOFormat::class);
	}
}
