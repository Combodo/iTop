<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\Block\FormType\ChoiceFormType;
use Combodo\iTop\Forms\Block\IO\Converter\ChoiceValueToLabelConverter;
use Combodo\iTop\Forms\Block\IO\Format\StringIOFormat;

/**
 * Form block for choices.
 *
 */
class ChoiceFormBlock extends AbstractTypeFormBlock
{
	// Outputs
	public const OUTPUT_LABEL = 'label';

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return ChoiceFormType::class;
	}

	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(self::OUTPUT_LABEL, StringIOFormat::class, new ChoiceValueToLabelConverter($this));
	}
}