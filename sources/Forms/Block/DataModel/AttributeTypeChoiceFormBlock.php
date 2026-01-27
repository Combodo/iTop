<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\IO\Format\AttributeTypeIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;

/**
 * A block to choose an attribute type.
 *
 * @api
 * @package FormBlock
 * @since 3.3.0
 */
class AttributeTypeChoiceFormBlock extends ChoiceFormBlock
{
	// outputs
	/**
	 * String output of the selected attribute type
	 * @api
	 */
	public const OUTPUT_ATTRIBUTE_TYPE = 'output_type';

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('placeholder', 'Select a type...');
		$oOptionsRegister->SetOption('choices', [
			'numeric'   => 'numeric',
			'groupable' => 'groupable',
			'date'      => 'date',
			'enum'      => 'enum',
			'link'      => 'link',
			'string'    => 'string',
			'all'       => 'all',
		]);

	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddOutput(self::OUTPUT_ATTRIBUTE_TYPE, AttributeTypeIOFormat::class);
	}

}
