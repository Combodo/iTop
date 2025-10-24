<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\FormBlock;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Form block for choices.
 *
 */
class ChoiceFormBlock extends FormBlock
{
	/** @inheritdoc  */
	public function GetFormType(): string
	{
		return ChoiceType::class;
	}
}