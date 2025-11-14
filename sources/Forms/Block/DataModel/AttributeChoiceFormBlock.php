<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\IO\Converter\StringToAttributeConverter;
use Combodo\iTop\Forms\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;
use MetaModel;

/**
 * Form block for choice of class attributes.
 *
 * @package DataModel
 */
class AttributeChoiceFormBlock extends ChoiceFormBlock
{
	// inputs
	public const INPUT_CLASS_NAME = 'class_name';

	// outputs
	public const OUTPUT_ATTRIBUTE = 'attribute';

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('placeholder', 'Select an attribute...');
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
		$oIORegister->AddOutput(self::OUTPUT_ATTRIBUTE, AttributeIOFormat::class, new StringToAttributeConverter());
	}

	/** @inheritdoc  */
	public function UpdateOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::UpdateOptions($oOptionsRegister);

		$oClass = $this->GetInputValue(self::INPUT_CLASS_NAME);

		if($oClass === null){
			$oOptionsRegister->SetOption('choices', []);
			return;
		}

		$aAttributeCodes = MetaModel::GetAttributesList($oClass);

		$aAttributes = [];
		foreach ($aAttributeCodes as $sAttributeCode){
			$oAttribute = MetaModel::GetAttributeDef(strval($oClass), $sAttributeCode);
			$aAttributes[$oAttribute->GetLabel()] = $sAttributeCode;
		}

		$oOptionsRegister->SetOption('choices', $aAttributes);
	}

}