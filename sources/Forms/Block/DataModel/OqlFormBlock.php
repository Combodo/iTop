<?php

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\StringFormBlock;
use Combodo\iTop\Forms\Block\FormOutput;

class OqlFormBlock extends StringFormBlock
{

	public function InitOutputs(): void
	{
		$this->AddOutput(new FormOutput('selected_class', 'string'));
	}

}