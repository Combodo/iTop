<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\FormType\ChoiceFormType;

/**
 * Form block for choices.
 *
 */
class ChoiceFormBlock extends AbstractFormBlock
{
	/** @inheritdoc */
	public function GetFormType(): string
	{
		return ChoiceFormType::class;
	}

	/** @inheritdoc */
	public function InitOptions(): array
	{
		return [
		];
	}
}