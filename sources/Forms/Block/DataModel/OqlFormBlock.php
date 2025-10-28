<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\TextAreaFormBlock;
use Combodo\iTop\Forms\Block\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Block\IO\Converter\OqlToClassConverter;
use Combodo\iTop\Forms\Block\FormType\OqlFormType;

/**
 * Form block for oql expression.
 *
 * @package DataModel
 */
class OqlFormBlock extends TextAreaFormBlock
{
	// outputs
	public const OUTPUT_SELECTED_CLASS = 'selected_class';

	/** @inheritdoc  */
	public function GetFormType(): string
	{
		return OqlFormType::class;
	}

	/** @inheritdoc  */
	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(self::OUTPUT_SELECTED_CLASS, ClassIOFormat::class, new OqlToClassConverter());
	}

	/** @inheritdoc  */
	public function InitOptions(): array
	{
		$aOptions = parent::InitOptions();
		$aOptions['with_ai_button'] = true;
		return $aOptions;
	}



}