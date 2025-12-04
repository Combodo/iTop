<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\Forms\Block\DataModel\AttributeChoiceFormBlock;

/**
 * @since 3.3.0
 */
class ValueTypeClassAttribute extends AbstractValueType
{
	public function getFormBlockClass(): string
	{
		return AttributeChoiceFormBlock::class;
	}
}
