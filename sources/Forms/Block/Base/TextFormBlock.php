<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Form block for string.
 *
 */
class TextFormBlock extends AbstractFormBlock
{
	/** @inheritdoc */
	public function GetFormType(): string
	{
		return TextType::class;
	}


}