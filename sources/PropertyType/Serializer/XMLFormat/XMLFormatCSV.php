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
	public function SerializeToDOMNode(?string $sPropertyName, mixed $value, DesignElement $oDOMNode, AbstractValueType $oValueType): void
	{
		if (is_null($sPropertyName)) {
			$oTextNode = $oDOMNode->ownerDocument->createTextNode(implode(',', $value));
			$oDOMNode->appendChild($oTextNode);
		} else {
			$oPropertyNode = $oDOMNode->ownerDocument->createElement($sPropertyName, implode(',', $value));
			$oDOMNode->appendChild($oPropertyNode);
		}
	}

	public function DeserializeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed
	{
		$value = $oDOMNode->GetText('');
		return explode(',', $value);
	}
}
