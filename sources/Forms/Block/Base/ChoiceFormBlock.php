<?php

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