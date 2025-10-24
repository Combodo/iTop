<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Block\IO\FormInput;

/**
 * Form block for choice of class attributes.
 *
 * @package DataModel
 */
class AttributeChoiceFormBlock extends ChoiceFormBlock
{
	// inputs
	public const INPUT_CLASS_NAME = 'class_name';

	/** @inheritdoc  */
	public function InitInputs(): void
	{
		parent::InitInputs();
		$this->AddInput(new FormInput(self::INPUT_CLASS_NAME, ClassIOFormat::class));
	}

}