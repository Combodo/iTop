<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use AttributeClass;
use Combodo\iTop\Core\AttributeDefinition\AttributeBoolean;
use Combodo\iTop\Core\AttributeDefinition\AttributeDate;
use Combodo\iTop\Core\AttributeDefinition\AttributeEnum;
use Combodo\iTop\Core\AttributeDefinition\AttributeString;
use Combodo\iTop\Forms\IO\Format\AttributeTypeIOFormat;
use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;

/**
 * A block to choose an attribute type.
 *
 * @package Combodo\iTop\Forms\Block\DataModel
 * @since 3.3.0
 */
class AttributeTypeChoiceFormBlock extends ChoiceFormBlock
{
	// outputs
	public const OUTPUT_ATTRIBUTE_TYPE = 'output_type';

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('placeholder', 'Select a type...');
		$oOptionsRegister->SetOption('choices', [
			'enum'    => AttributeEnum::class,
			'string'  => AttributeString::class,
			'date'    => AttributeDate::class,
			'boolean' => AttributeBoolean::class,
			'class'   => AttributeClass::class,
		]);

	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_ATTRIBUTE_TYPE, AttributeTypeIOFormat::class);
	}

}
