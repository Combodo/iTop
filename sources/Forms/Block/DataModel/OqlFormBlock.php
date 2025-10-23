<?php

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\StringFormBlock;
use Combodo\iTop\Forms\Block\FormOutput;

class OqlFormBlock extends StringFormBlock
{

	public const OUTPUT_SELECTED_CLASS = 'selected_class';

	public function InitOutputs(): void
	{
		parent::InitOutputs();
		$this->AddOutput(new FormOutput(self::OUTPUT_SELECTED_CLASS, 'string'));
	}

}