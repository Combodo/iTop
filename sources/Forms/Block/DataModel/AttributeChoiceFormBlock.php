<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Core\AttributeDefinition\AttributeEnum;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\IO\Format\AttributeTypeArrayIOFormat;
use Combodo\iTop\Forms\IO\Format\AttributeTypeIOFormat;
use Combodo\iTop\Forms\Register\RegisterException;
use Combodo\iTop\Service\DependencyInjection\DIException;
use Combodo\iTop\Service\DependencyInjection\DIService;
use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Register\IORegister;
use Combodo\iTop\Forms\Register\OptionsRegister;
use ModelReflection;
use utils;

/**
 * A block to choose an attribute from a given class.
 *
 * @package Combodo\iTop\Forms\Block\DataModel
 * @since 3.3.0
 */
class AttributeChoiceFormBlock extends ChoiceFormBlock
{
	// inputs
	public const INPUT_CLASS_NAME = 'input_class_name';
	public const INPUT_ATTRIBUTE_TYPE = 'input_attribute_type';

	// outputs
	public const OUTPUT_ATTRIBUTE = 'output_attribute';

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('placeholder', 'Select an attribute...');
		$oOptionsRegister->SetOption('category', '', false);
	}

	/** @inheritdoc */
	protected function RegisterIO(IORegister $oIORegister): void
	{
		parent::RegisterIO($oIORegister);
		$oIORegister->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
		$oIORegister->AddInput(self::INPUT_ATTRIBUTE_TYPE, AttributeTypeIOFormat::class);
		$oIORegister->AddOutput(self::OUTPUT_ATTRIBUTE, AttributeIOFormat::class);
	}

	/**
	 * @inheritdoc
	 *
	 * @throws DIException
	 * @throws FormBlockException
	 * @throws RegisterException
	 */
	public function UpdateOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::UpdateOptions($oOptionsRegister);

		// Get class name
		$sClass = strval($this->GetInputValue(self::INPUT_CLASS_NAME));
		$sAttType = $this->GetInputValue(self::INPUT_ATTRIBUTE_TYPE)->sAttributeType;

		// Empty class => no choices
		if (utils::IsNullOrEmptyString($sClass)) {
			$oOptionsRegister->SetOption('choices', []);
			return;
		}

		/** List attributes @var ModelReflection $oModelReflection */
		$oModelReflection = DIService::GetInstance()->GetService('ModelReflection');
		$aAttributeCodes = array_keys($oModelReflection->ListAttributes($sClass, $sAttType));
		$aAttributes = [];
		foreach ($aAttributeCodes as $sAttCode) {
			$sLabel = $oModelReflection->GetLabel($sClass, $sAttCode);
			$aAttributes[$sLabel] = $sAttCode;
		}
		$oOptionsRegister->SetOption('choices', $aAttributes);
	}
}
