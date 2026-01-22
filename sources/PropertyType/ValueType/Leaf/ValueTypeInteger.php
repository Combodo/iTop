<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\IntegerFormBlock;

/**
 * @since 3.3.0
 */
class ValueTypeInteger extends AbstractLeafValueType
{
	public function GetFormBlockClass(): string
	{
		return IntegerFormBlock::class;
	}

	public function DeserializeFromDOMNode(DesignElement $oDOMNode): mixed
	{
		$value = parent::DeserializeFromDOMNode($oDOMNode);

		return intval($value);
	}

	public function SerializeToDOMNode(?string $sPropertyName, mixed $value, DesignElement $oDOMNode): void
	{
		parent::SerializeToDOMNode($sPropertyName, "$value", $oDOMNode);
	}
}
