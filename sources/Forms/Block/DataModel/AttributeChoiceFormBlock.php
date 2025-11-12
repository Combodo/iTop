<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\FormBlockException;
use Combodo\iTop\Forms\Block\IO\Converter\StringToAttributeConverter;
use Combodo\iTop\Forms\Block\IO\Format\AttributeIOFormat;
use Combodo\iTop\Forms\Block\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Block\IO\Format\RawFormat;
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

	/** @inheritdoc */
	public function InitBlockOptions(array &$aUserOptions): void
	{
		parent::InitBlockOptions($aUserOptions);

		$aUserOptions['placeholder'] = 'Select an attribute...';
	}

	/** @inheritdoc */
	public function InitInputs(): void
	{
		parent::InitInputs();
		$this->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
	}

	/** @inheritdoc */
	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(self::OUTPUT_ATTRIBUTE, AttributeIOFormat::class, new StringToAttributeConverter());
	}

	/** @inheritdoc  */
	public function UpdateDynamicOptions(string $sEventType = null): void
	{
		$oClass = $this->GetInput(self::INPUT_CLASS_NAME)->GetValue($sEventType);

		$aAttributeCodes = MetaModel::GetAttributesList($oClass);

		$aAttributes = [];
		foreach ($aAttributeCodes as $sAttributeCode){
			$oAttribute = MetaModel::GetAttributeDef(strval($oClass), $sAttributeCode);
			$aAttributes[$oAttribute->GetLabel()] = $sAttributeCode;
		}

		$this->aDynamicOptions['choices'] = $aAttributes;
	}

}