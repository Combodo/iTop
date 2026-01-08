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

	abstract public function Normalize($value, AbstractValueType $oValueType): mixed;

	abstract public function EncodeToDOMNode(mixed $normalizedValue, DesignElement $oDOMNode, AbstractValueType $oValueType): void;

	abstract public function DecodeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed;

	abstract public function Denormalize($normalizedValue, AbstractValueType $oValueType): mixed;
}
