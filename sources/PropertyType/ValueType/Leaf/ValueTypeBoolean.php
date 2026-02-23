<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\Forms\Block\Base\CheckboxFormBlock;

/**
 * @since 3.3.0
 */
class ValueTypeBoolean extends AbstractLeafValueType
{
	public function GetFormBlockClass(): string
	{
		return CheckboxFormBlock::class;
	}
}
