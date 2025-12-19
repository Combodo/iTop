<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\Forms\Block\DataModel\Dashlet\ClassAttributeGroupByFormBlock;

/**
 * @since 3.3.0
 */
class ValueTypeClassAttributeGroupBy extends AbstractLeafValueType
{
	public function GetFormBlockClass(): string
	{
		return ClassAttributeGroupByFormBlock::class;
	}
}
