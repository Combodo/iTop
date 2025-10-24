<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block;

use Combodo\iTop\Forms\Block\IO\FormOutput;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Complex form type.
 *
 */
class FormBlock extends AbstractFormBlock
{
	public const OUTPUT_VALUE = 'value';

	/** @inheritdoc  */
	public function GetFormType(): string
	{
		return FormType::class;
	}

	/** @inheritdoc  */
	public function InitInputs(): void
	{
	}

	/** @inheritdoc  */
	public function InitOutputs(): void
	{
		$this->AddOutput(new FormOutput(self::OUTPUT_VALUE, 'string'));
	}

	/** @inheritdoc  */
	public function InitOptions(): void
	{

	}
}