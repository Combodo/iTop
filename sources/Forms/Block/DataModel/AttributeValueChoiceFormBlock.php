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
use Combodo\iTop\Forms\FormType\AttributeValueFormType;
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
	public const OUTPUT_VALUE  = 'value';

	/** @inheritdoc  */
	public function InitOptions(array &$aOptions = []): array
	{
		$aOptions['multiple'] = true;
		$aOptions['attr'] = [
			'size' => 5,
			'style' => 'height: auto;'
		];

		return $aOptions;
	}

	/** @inheritdoc  */
	public function InitInputs(): void
	{
		parent::InitInputs();
		$this->AddInput(self::INPUT_CLASS_NAME, ClassIOFormat::class);
		$this->AddInput(self::INPUT_ATTRIBUTE, AttributeIOFormat::class);
	}

	/** @inheritdoc  */
	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(self::OUTPUT_VALUE, RawFormat::class);
	}

	/** @inheritdoc
	 * @throws FormBlockException
	 * @throws Exception
	 */
	public function AllowAdd(): bool
	{
		$bDependentOk = $this->GetInput(self::INPUT_CLASS_NAME)->Value() != '' && $this->GetInput(self::INPUT_ATTRIBUTE)->Value() != '';

		if($bDependentOk) {
			$oAttDef = MetaModel::GetAttributeDef($this->GetInput(self::INPUT_CLASS_NAME)->Value()->__toString(), $this->GetInput(self::INPUT_ATTRIBUTE)->Value()->__toString());
			$aValues = $oAttDef->GetAllowedValues();
			return $aValues != null;
		}
		else{
			return false;
		}
	}

	/** @inheritdoc
	 * @throws Exception
	 */
	public function UpdateOptions(): array
	{
		$aOptions = parent::GetOptions();

		$oClassName = $this->GetInput(self::INPUT_CLASS_NAME)->Value();
		if($oClassName == '')
			return $aOptions;

		$oAttribute = $this->GetInput(self::INPUT_ATTRIBUTE)->Value();
		if($oAttribute == '')
			return $aOptions;

		$oAttDef = MetaModel::GetAttributeDef(strval($oClassName), strval($oAttribute));
		$aValues = $oAttDef->GetAllowedValues();

		if($aValues === null)
			return $aOptions;

		$aOptions['choices'] = array_flip($aValues);

		return $aOptions;
	}

}