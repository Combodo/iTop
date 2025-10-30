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
use Combodo\iTop\Forms\Block\IO\Format\RawFormat;
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
	public function InitBlockOptions(array &$aUserOptions): void
	{
		parent::InitBlockOptions($aUserOptions);
		$aUserOptions['multiple'] = true;
		$aUserOptions['attr'] = [
			'size'  => 5,
			'style' => 'height: auto;',
		];
	}

	/** @inheritdoc */
	public function InitInputs(): void
	{
		parent::InitInputs();
		$this->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
		$this->AddInput(self::INPUT_ATTRIBUTE, AttributeIOFormat::class);
	}

	/** @inheritdoc */
	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(self::OUTPUT_VALUE, RawFormat::class);
	}

	/** @inheritdoc  */
	public function UpdateDynamicOptions(string $sEventType = null): void
	{
		$oClassName = $this->GetInput(self::INPUT_CLASS_NAME)->GetValue($sEventType);
		$oAttribute = $this->GetInput(self::INPUT_ATTRIBUTE)->GetValue($sEventType);

		try{
			$oAttDef = MetaModel::GetAttributeDef(strval($oClassName), strval($oAttribute));
			$aValues = $oAttDef->GetAllowedValues();
			$this->aDynamicOptions['choices'] = array_flip($aValues ?? []);
		}
		catch(Exception){}
	}

}