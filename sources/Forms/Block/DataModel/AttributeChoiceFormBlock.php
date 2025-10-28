<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\Block\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\Block\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Block\IO\FormInput;
use Combodo\iTop\Forms\Block\IO\FormOutput;
use Combodo\iTop\Forms\Block\IO\Converter\StringToAttributeConverter;
use Combodo\iTop\Forms\FormType\AttributeFormType;
use CoreException;
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

	/** @inheritdoc  */
	public function InitOptions(): array
	{
		$aOptions = parent::InitOptions();
		$aOptions['placeholder'] = 'Select an attribute...';
		return $aOptions;
	}

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

	/** @inheritdoc
	 * @throws FormBlockException
	 */
	public function AllowAdd(): bool
	{
		return $this->GetInput(self::INPUT_CLASS_NAME)->Value() != '';
	}

	/** @inheritdoc
	 * @throws FormBlockException
	 * @throws CoreException
	 */
	public function UpdateOptions(): array
	{
		$aOptions = parent::GetOptions();

		$oValue = $this->GetInput(self::INPUT_CLASS_NAME)->Value();
		if($oValue == '')
			return $aOptions;

		$aAttributeCodes = MetaModel::GetAttributesList($oValue);
		$aAttributeCodes = array_combine($aAttributeCodes, $aAttributeCodes) ;
		$aOptions['choices'] = $aAttributeCodes;

		return $aOptions;
	}

	/** @inheritdoc  */
	public function GetFormType(): string
	{
		return AttributeFormType::class;
	}
}