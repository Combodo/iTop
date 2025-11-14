<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\IO\Format\RawFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Exception;
use MetaModel;

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

	// Outputs
	public const OUTPUT_VALUE = 'value';

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('multiple', true);
		$oOptionsRegister->SetOptionArrayValue('attr', 'size', 5);
		$oOptionsRegister->SetOptionArrayValue('attr', 'style', 'height: auto;');
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
		$oIORegister->AddInput(self::INPUT_ATTRIBUTE, AttributeIOFormat::class);
		$oIORegister->AddOutput(self::OUTPUT_VALUE, RawFormat::class);
	}

	/** @inheritdoc  */
	public function UpdateOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::UpdateOptions($oOptionsRegister);

		$oClassName = $this->GetInputValue(self::INPUT_CLASS_NAME);
		$oAttribute = $this->GetInputValue(self::INPUT_ATTRIBUTE);

		try{
			$oAttDef = MetaModel::GetAttributeDef(strval($oClassName), strval($oAttribute));
			$aValues = $oAttDef->GetAllowedValues();

			$oOptionsRegister->SetOption('choices', array_flip($aValues ?? []));
		}
		catch(Exception){}
	}

}