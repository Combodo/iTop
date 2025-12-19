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
	public function SerializeToDOMNode($value, $oDOMNode, AbstractValueType $oValueType): void
	{
		$sXmlValue = implode(',', $value);
		$oTextNode = $oDOMNode->ownerDocument->createTextNode($sXmlValue);
		$oDOMNode->appendChild($oTextNode);
	}

	public function UnserializeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed
	{
		$sValue = $oDOMNode->GetText('');
		return explode(',', $sValue);
	}
}
