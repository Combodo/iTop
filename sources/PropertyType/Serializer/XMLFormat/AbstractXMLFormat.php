<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\Serializer\XMLFormat;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;

abstract class AbstractXMLFormat
{
	public function InitFromDomNode(DesignElement $oDomNode): void
	{
	}

	abstract public function SerializeToDOMNode(mixed $value, DesignElement $oDOMNode, AbstractValueType $oValueType): void;

	abstract public function UnserializeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed;
}
