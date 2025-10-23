<?php

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\FormBlock;
use Combodo\iTop\Forms\Block\FormInput;

class AttributeChoiceFormBlock extends FormBlock
{


	public function __construct(array $aOptions = [])
	{
		parent::__construct($aOptions);

		$this->AddInput(new FormInput('class_name', 'string'));
	}


}