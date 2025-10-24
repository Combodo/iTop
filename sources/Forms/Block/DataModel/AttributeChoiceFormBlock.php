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
use Combodo\iTop\Forms\Block\IO\FormOutput;
use Combodo\iTop\Forms\Converter\StringToAttributeConverter;
use Combodo\iTop\Forms\FormType\AttributeChoiceType;

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

	/** @inheritdoc  */
	public function InitInputs(): void
	{
		parent::InitInputs();
		$this->AddInput(new FormInput(self::INPUT_CLASS_NAME, ClassIOFormat::class));
	}

	/** @inheritdoc  */
	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(new FormOutput(self::OUTPUT_ATTRIBUTE, AttributeIOFormat::class, new StringToAttributeConverter()));
	}

	/** @inheritdoc  */
	public function UpdateOptions(): array
	{
		$aOptions = parent::UpdateOptions();

		$oBinding = $this->GetInput(self::INPUT_CLASS_NAME)->GetBinding();
		$oConnectionValue = $oBinding->oOutput->Value();

		$aAttributeCodes = \MetaModel::GetAttributesList($oConnectionValue);
		$aAttributeCodes = array_combine($aAttributeCodes, $aAttributeCodes) ;
		$aOptions['choices'] = $aAttributeCodes;

		return $aOptions;
	}

	/** @inheritdoc  */
	public function GetFormType(): string
	{
		return AttributeChoiceType::class;
	}
}