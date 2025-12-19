<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * A block to manage a textarea.
 *
 * @package Combodo\iTop\Forms\Block\Base
 * @since 3.3.0
 */
class TextAreaFormBlock extends AbstractTypeFormBlock
{
	/** @inheritdoc */
	public function GetFormType(): string
	{
		return TextareaType::class;
	}

}
