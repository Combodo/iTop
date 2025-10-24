<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\StringFormBlock;
use Combodo\iTop\Forms\Block\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\Block\IO\FormOutput;
use Combodo\iTop\Forms\Converter\OqlToClassName;

/**
 * Form block for oql expression.
 *
 * @package DataModel
 */
class OqlFormBlock extends StringFormBlock
{
	// outputs
	public const OUTPUT_SELECTED_CLASS = 'selected_class';

	/** @inheritdoc  */
	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(new FormOutput(self::OUTPUT_SELECTED_CLASS, ClassIOFormat::class, new OqlToClassName()));
	}

}