<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Form block for date.
 *
 */
class DateFormBlock extends AbstractFormBlock
{
	/** @inheritdoc */
	public function GetFormType(): string
	{
		return DateType::class;
	}

	public function InitBlockOptions(array &$aUserOptions): void
	{
		parent::InitBlockOptions($aUserOptions);
		$aUserOptions['widget'] = 'single_text';
	}
}