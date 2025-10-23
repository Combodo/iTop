<?php

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\FormInput;

class AttributeChoiceFormBlock extends ChoiceFormBlock
{


	public function InitInputs(): void
	{
		$this->AddInput(new FormInput('class_name', 'string'));
	}


}