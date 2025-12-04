<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\Forms\Block\DataModel\OqlFormBlock;

/**
 * @since 3.3.0
 */
class ValueTypeOQL extends AbstractValueType
{
	public function getFormBlockClass(): string
	{
		return OqlFormBlock::class;
	}
}
