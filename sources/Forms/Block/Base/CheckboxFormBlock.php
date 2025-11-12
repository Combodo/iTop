<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\Block\IO\Converter\StringToBooleanConverter;
use Combodo\iTop\Forms\Block\IO\Format\BooleanIOFormat;
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
	function InitBlockOptions(array &$aUserOptions): void
	{
		parent::InitBlockOptions($aUserOptions);
		$aUserOptions['required'] = false;
	}

	/** @inheritdoc */
	function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(self::OUTPUT_CHECKED, BooleanIOFormat::class, new StringToBooleanConverter());
	}
}