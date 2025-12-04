<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * A block to manage a hidden field
 *
 * @package Combodo\iTop\Forms\Block\Base
 * @since 3.3.0
 */
class HiddenFormBlock extends AbstractTypeFormBlock
{
	/**
	 * @inheritDoc
	 */
	public function GetFormType(): string
	{
		return HiddenType::class;
	}
}
