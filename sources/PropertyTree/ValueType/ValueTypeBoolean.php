<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\Forms\Block\Base\CheckboxFormBlock;
use Combodo\iTop\PropertyTree\ValueType\AbstractValueType;

class ValueTypeBoolean extends AbstractValueType
{
	public function GetFormBlockClass(): string
	{
		return CheckboxFormBlock::class;
	}
}
