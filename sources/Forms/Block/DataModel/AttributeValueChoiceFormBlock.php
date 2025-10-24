<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\Block\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Block\IO\FormInput;

/**
 * Form block for choice of class attribute values.
 *
 * @package DataModel
 */
class AttributeValueChoiceFormBlock extends ChoiceFormBlock
{
	// inputs
	public const INPUT_CLASS_NAME = 'class_name';
	public const INPUT_ATTRIBUTE  = 'attribute';

	/** @inheritdoc  */
	public function InitOptions(array &$aOptions = []): void
	{
		$aOptions['multiple'] = true;
	}

	/** @inheritdoc  */
	public function InitInputs(): void
	{
		parent::InitInputs();
		$this->AddInput(new FormInput(self::INPUT_CLASS_NAME, ClassIOFormat::class));
		$this->AddInput(new FormInput(self::INPUT_ATTRIBUTE, AttributeIOFormat::class));
	}

}