<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\DesignElement;

/**
 * @since 3.3.0
 */
abstract class AbstractValueType
{
	abstract public function GetFormBlockClass(): string;

	public function InitFromDomNode(DesignElement $oDomNode): void
	{

	}
}
