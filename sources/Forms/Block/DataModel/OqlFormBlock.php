<?php

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\FormBlock;
use Combodo\iTop\Forms\Block\FormOutput;

class OqlFormBlock extends FormBlock
{


	public function __construct(array $aOptions = [])
	{
		parent::__construct($aOptions);

		$this->AddOutput(new FormOutput('selected_class', 'string'));
	}



}