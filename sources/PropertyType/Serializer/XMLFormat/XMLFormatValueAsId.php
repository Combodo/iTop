<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\Serializer\XMLFormat;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\Serializer\SerializerException;
use Combodo\iTop\PropertyType\Serializer\XMLFormat\AbstractXMLFormat;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;

class XMLFormatValueAsId extends AbstractXMLFormat
{
	private string $sTagName;

	public function InitFromDomNode(DesignElement $oDomNode): void
	{
		$sTagName = $oDomNode->GetChildText('tag-name');
		if (is_null($sTagName)) {
			throw new SerializerException("Missing <tag-name> element", $oDomNode);
		}
		$this->sTagName = $sTagName;
	}

	public function SerializeToDOMNode($value, $oDOMNode, AbstractValueType $oValueType): void
	{
		foreach ($value as $item) {
			$oChildNode = $oDOMNode->ownerDocument->createElement($this->sTagName);
			$oChildNode->setAttribute('id', "$item");
			$oDOMNode->appendChild($oChildNode);
		}
	}

	public function UnserializeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed
	{
		$aResult = [];

		foreach ($oDOMNode->getElementsByTagName($this->sTagName) as $oNode) {
			$sValue = $oNode->getAttribute('id');
			$aResult[] = $sValue;
		}

		return $aResult;
	}
}
