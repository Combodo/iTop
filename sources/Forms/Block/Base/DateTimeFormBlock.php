<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Combodo\iTop\Forms\Register\OptionsRegister;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * Form block for date time.
 *
 */
class DateTimeFormBlock extends AbstractTypeFormBlock
{
	/** @inheritdoc */
	public function GetFormType(): string
	{
		return DateTimeType::class;
	}

	/** @inheritdoc */
	protected function RegisterOptions(OptionsRegister $oOptionsRegister): void
	{
		parent::RegisterOptions($oOptionsRegister);
		$oOptionsRegister->SetOption('widget', 'single_text');
	}
}
