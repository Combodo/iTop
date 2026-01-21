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

	public function SerializeToDOMNode(?string $sPropertyName, $value, $oDOMNode, AbstractValueType $oValueType): void
	{
		if (!is_null($sPropertyName)) {
			$oPropertyNode = $oDOMNode->ownerDocument->createElement($sPropertyName);
			$oDOMNode->appendChild($oPropertyNode);
		} else {
			$oPropertyNode = $oDOMNode;
		}
		foreach ($value as $item) {
			$oChildNode = $oDOMNode->ownerDocument->createElement($this->sTagName);
			$oChildNode->setAttribute('id', "$item");
			$oPropertyNode->appendChild($oChildNode);
		}
	}

	public function DeserializeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed
	{
		$aResult = [];

		foreach ($oDOMNode->childNodes as $oNode) {
			if (!$oNode instanceof DesignElement) {
				continue;
			}
			if ($oNode->tagName !== $this->sTagName) {
				continue;
			}
			$sValue = $oNode->getAttribute('id');
			$aResult[] = $sValue;
		}

		return $aResult;
	}
}
