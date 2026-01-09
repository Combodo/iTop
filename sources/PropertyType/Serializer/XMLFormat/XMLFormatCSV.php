<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\Serializer\XMLFormat;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;

class XMLFormatCSV extends AbstractXMLFormat
{
	public function Normalize($value, AbstractValueType $oValueType): mixed
	{
		return $value;
	}

	public function EncodeToDOMNode(mixed $normalizedValue, DesignElement $oDOMNode, AbstractValueType $oValueType): void
	{
		if (is_array($normalizedValue)) {
			$normalizedValue = implode(',', $normalizedValue);
		}
		$oTextNode = $oDOMNode->ownerDocument->createTextNode($normalizedValue);
		$oDOMNode->appendChild($oTextNode);
	}

	public function DecodeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed
	{
		$value = $oDOMNode->GetText('');
		return explode(',', $value);
	}

	public function Denormalize($normalizedValue, AbstractValueType $oValueType): mixed
	{
		if (is_string($normalizedValue)) {
			return explode(',', $normalizedValue);
		}

		return $normalizedValue;
	}
}
