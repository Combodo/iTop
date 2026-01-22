<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Register\OptionsRegister;
use utils;

/**
 * A block to manage a list of images.
 * This block expose two outputs: the label and the value of the selected choice.
 *
 * @package Combodo\iTop\Forms\Block\Base
 * @since 3.3.0
 */
class ChoiceImageFormBlock extends ChoiceFormBlock
{
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);

		$oOptionsRegister->SetOption('disable_auto_complete', true);

		$oOptionsRegister->SetOption('plugins', [
			[
				'name'    => 'image_renderer',
				'options' => [
				],
			],
		]);

		$oOptionsRegister->SetOption('choice_attr');
		$oOptionsRegister->SetOption('placeholder', 'UI:InputFile:SelectImage');
	}

}
