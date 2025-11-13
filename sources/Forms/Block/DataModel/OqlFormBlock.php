<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\TextAreaFormBlock;
use Combodo\iTop\Forms\Block\FormType\OqlFormType;
use Combodo\iTop\Forms\Block\IO\Converter\OqlToClassConverter;
use Combodo\iTop\Forms\Block\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Block\IO\Format\StringIOFormat;

/**
 * Form block for oql expression.
 *
 * @package DataModel
 */
class OqlFormBlock extends TextAreaFormBlock
{
	// outputs
	public const OUTPUT_SELECTED_CLASS = 'selected_class';
	public const OUTPUT_OQL = 'oql';

	/** @inheritdoc */
	public function GetFormType(): string
	{
		return OqlFormType::class;
	}

	/** @inheritdoc */
	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(self::OUTPUT_SELECTED_CLASS, ClassIOFormat::class, new OqlToClassConverter());
		$this->AddOutput(self::OUTPUT_OQL, StringIOFormat::class);
	}

	/** @inheritdoc */
	public function InitBlockOptions(array &$aUserOptions): void
	{
		parent::InitBlockOptions($aUserOptions);
		$aUserOptions['with_ai_button'] = true;
	}
}