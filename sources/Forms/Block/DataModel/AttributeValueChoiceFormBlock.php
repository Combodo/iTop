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
	public function InitOptions(array &$aOptions = []): array
	{
		$aOptions['multiple'] = true;
		$aOptions['required'] = false;
		$aOptions['attr'] = [
			'size' => 10,
			'style' => 'height: auto;'
		];

		return $aOptions;
	}

	/** @inheritdoc  */
	public function InitInputs(): void
	{
		parent::InitInputs();
		$this->AddInput(new FormInput(self::INPUT_CLASS_NAME, ClassIOFormat::class));
		$this->AddInput(new FormInput(self::INPUT_ATTRIBUTE, AttributeIOFormat::class));
	}

	public function UpdateOptions(): array
	{
		$aOptions = parent::UpdateOptions();

		$oBindingClassName = $this->GetInput(self::INPUT_CLASS_NAME)->GetBinding();
		if($oBindingClassName->oOutput->Value() === null || $oBindingClassName->oOutput->Value() == "")
			return $aOptions;
		$oClassName = $oBindingClassName->oOutput->Value();

		$oBindingAttribute = $this->GetInput(self::INPUT_ATTRIBUTE)->GetBinding();
		if($oBindingAttribute->oOutput->Value() === null || $oBindingAttribute->oOutput->Value() == "")
			return $aOptions;
		$oAttribute = $oBindingAttribute->oOutput->Value();

		$oAttDef = \MetaModel::GetAttributeDef(strval($oClassName), strval($oAttribute));
		$aValues = $oAttDef->GetAllowedValues();

		$aOptions['choices'] = array_flip($aValues);

		return $aOptions;
	}

}