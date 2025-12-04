<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;

/**
 * @since 3.3.0
 */
class ValueTypeChoice extends AbstractValueType
{
	public function getFormBlockClass(): string
	{
		return ChoiceFormBlock::class;
	}
}
