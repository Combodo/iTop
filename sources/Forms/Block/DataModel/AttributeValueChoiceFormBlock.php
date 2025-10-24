<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\DataModel;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\FormInput;

class AttributeValueChoiceFormBlock extends ChoiceFormBlock
{

	public const INPUT_CLASS_NAME = 'class_name';
	public const INPUT_ATTRIBUTE  = 'attribute';

	public function __construct(string $sName, array $aOptions = [])
	{
		$aOptions['multiple'] = true;
		parent::__construct($sName, $aOptions);
	}

	public function InitInputs(): void
	{
		$this->AddInput(new FormInput(self::INPUT_CLASS_NAME, 'string'));
		$this->AddInput(new FormInput(self::INPUT_ATTRIBUTE, 'string'));
	}


}