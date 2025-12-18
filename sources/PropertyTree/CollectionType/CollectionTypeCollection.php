<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\CollectionType;

use Combodo\iTop\Forms\Block\Base\CollectionBlock;

class CollectionTypeCollection extends AbstractCollectionType
{
	public function GetFormBlockClass(): string
	{
		return CollectionBlock::class;
	}
}
