<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\FormBlock;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChoiceFormBlock extends FormBlock
{
	public function GetFormType(): string
	{
		return ChoiceType::class;
	}
}