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

	public function Normalize($value, AbstractValueType $oValueType): mixed
	{
		return $value;
	}

	public function EncodeToDOMNode(mixed $normalizedValue, DesignElement $oDOMNode, AbstractValueType $oValueType): void
	{
		if (!$oValueType instanceof ValueTypeCollection) {
			throw new SerializerException('XMLFormatFlatArray is allowed only in ValueTypeCollection nodes');
		}

		foreach ($normalizedValue as $sItemId => $aValues) {
			/** @var DesignElement $oNode */
			$oNode = $oDOMNode->ownerDocument->createElement($this->sTagName);
			$oNode->setAttribute('id', $sItemId);
			$oDOMNode->appendChild($oNode);
			foreach ($oValueType->GetChildren() as $oChild) {
				$sPropertyId = $oChild->GetId();
				if (isset($aValues[$sPropertyId])) {
					/** @var DesignElement $oSubNode */
					$oSubNode = $oDOMNode->ownerDocument->createElement($sPropertyId);
					$oNode->appendChild($oSubNode);
					$oChild->EncodeToDOMNode($aValues[$sPropertyId], $oSubNode);
				}
			}
		}
	}

	public function DecodeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed
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
				$aSubArray[$oChild->GetId()] = $oChild->DecodeFromDomNode($oNode->GetUniqueElement($oChild->GetId()));
			}
			$aNormalizedValues[$sItemId] = $aSubArray;
		}

		return $aNormalizedValues;
	}

	public function Denormalize($normalizedValue, AbstractValueType $oValueType): mixed
	{
		return $normalizedValue;
	}
}
