<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\Serializer\XMLFormat;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\Serializer\SerializerException;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;
use Combodo\iTop\PropertyType\ValueType\Branch\ValueTypeCollection;

class XMLFormatCollectionWithId extends AbstractXMLFormat
{
	private ?string $sTagName;

	public function InitFromDomNode(DesignElement $oDomNode): void
	{
		parent::InitFromDomNode($oDomNode);
		$this->sTagName = $oDomNode->GetChildText('tag-name');
	}

	public function SerializeToDOMNode(?string $sPropertyName, mixed $value, DesignElement $oDOMNode, AbstractValueType $oValueType): void
	{
		if (!$oValueType instanceof ValueTypeCollection) {
			throw new SerializerException('XMLFormatFlatArray is allowed only in ValueTypeCollection nodes');
		}

		if (!is_null($sPropertyName)) {
			$oPropertyNode = $oDOMNode->ownerDocument->createElement($sPropertyName);
			$oDOMNode->appendChild($oPropertyNode);
		} else {
			$oPropertyNode = $oDOMNode;
		}

		foreach ($value as $sItemId => $aValues) {
			/** @var DesignElement $oItemNode */
			$oItemNode = $oPropertyNode->ownerDocument->createElement($this->sTagName);
			$oItemNode->setAttribute('id', $sItemId);
			$oPropertyNode->appendChild($oItemNode);
			foreach ($oValueType->GetChildren() as $oChild) {
				$sPropertyId = $oChild->GetId();
				if (isset($aValues[$sPropertyId])) {
					$oChild->SerializeToDOMNode($sPropertyId, $aValues[$sPropertyId], $oItemNode);
				}
			}
		}
	}

	public function DeserializeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed
	{
		if (!$oValueType instanceof ValueTypeCollection) {
			throw new SerializerException('XMLFormatFlatArray is allowed only in ValueTypeCollection nodes');
		}

		$aNormalizedValues = [];

		/** @var DesignElement $oNode */
		foreach ($oDOMNode->GetNodes($this->sTagName) as $oNode) {
			$sItemId = $oNode->getAttribute('id');
			$aSubArray = [];
			foreach ($oValueType->GetChildren() as $oChild) {
				$aSubArray[$oChild->GetId()] = $oChild->DeserializeFromDOMNode($oNode->GetUniqueElement($oChild->GetId()));
			}
			$aNormalizedValues[$sItemId] = $aSubArray;
		}

		return $aNormalizedValues;
	}
}
