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
use Combodo\iTop\PropertyType\ValueType\Branch\ValueTypeCollection;

class XMLFormatFlatArray extends AbstractXMLFormat
{
	private string $sTagFormat;
	private string $sCountTag;

	public function InitFromDomNode(DesignElement $oDomNode): void
	{
		$sTagFormat = $oDomNode->GetChildText('tag-format');
		if (is_null($sTagFormat)) {
			throw new SerializerException('Missing <tag-name> element', $oDomNode);
		}
		$this->sTagFormat = $sTagFormat;

		$sCountTag = $oDomNode->GetChildText('count-tag');
		if (is_null($sCountTag)) {
			throw new SerializerException('Missing <count-tag> element', $oDomNode);
		}
		$this->sCountTag = $sCountTag;
	}

	public function SerializeToDOMNode($value, $oDOMNode, AbstractValueType $oValueType): void
	{
		if (!$oValueType instanceof ValueTypeCollection) {
			throw new SerializerException('XMLFormatFlatArray is allowed only in ValueTypeCollection nodes');
		}

		$oCountNode = $oDOMNode->ownerDocument->createElement($this->sCountTag, count($value));
		$oDOMNode->appendChild($oCountNode);
		foreach ($value as $iRank => $aValues) {
			foreach ($oValueType->GetChildren() as $oChild) {
				$sId = $oChild->GetId();
				if (isset($aValues[$sId])) {
					$sTagName = \MetaModel::ApplyParams($this->sTagFormat, ['rank' => $iRank, 'id' => $sId]);
					$oChildNode = $oDOMNode->ownerDocument->createElement($sTagName);
					$oDOMNode->appendChild($oChildNode);
					$oChild->SerializeToDOMNode($aValues[$sId], $oChildNode);
				}
			}
		}
	}

	public function UnserializeFromDOMNode(DesignElement $oDOMNode, AbstractValueType $oValueType): mixed
	{
		$aResults = [];

		if (!$oValueType instanceof ValueTypeCollection) {
			throw new SerializerException('XMLFormatFlatArray is allowed only in ValueTypeCollection nodes');
		}

		$iCount = $oDOMNode->GetUniqueElement($this->sCountTag)->GetText(0);
		for ($iRank = 0; $iRank < $iCount; $iRank++) {
			foreach ($oValueType->GetChildren() as $oChild) {
				$sId = $oChild->GetId();
				$sTagName = \MetaModel::ApplyParams($this->sTagFormat, ['rank' => $iRank, 'id' => $sId]);
				$oChildNode = $oDOMNode->GetOptionalElement($sTagName);
				if ($oChildNode) {
					$aResults[$iRank][$sId] = $oChildNode->GetText('');
				}
			}
		}

		return $aResults;
	}
}
