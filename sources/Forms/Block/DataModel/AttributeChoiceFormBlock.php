<?php

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\FormInput;

class AttributeChoiceFormBlock extends ChoiceFormBlock
{
	public const INPUT_CLASS_NAME = 'class_name';

	public function InitInputs(): void
	{
		$this->AddInput(new FormInput(self::INPUT_CLASS_NAME, 'string'));
	}


}