<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\PropertyType\Serializer\XMLFormat\AbstractXMLFormat;
use Combodo\iTop\PropertyType\Serializer\XMLFormat\XMLFormatFactory;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;
use Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType;
use Combodo\iTop\PropertyType\ValueType\Leaf\AbstractLeafValueType;
use Combodo\iTop\PropertyType\ValueType\ValueTypeFactory;

class ValueTypeCollectionOfValues extends AbstractLeafValueType
{
	private string $sFormBlockClass;
	private AbstractXMLFormat $oXMLFormat;
	private AbstractValueType $oRealValueType;

	public function GetFormBlockClass(): string
	{
		return $this->sFormBlockClass;
	}

	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		$oNode = $oDomNode->GetUniqueElement('value-type');
		$this->oRealValueType = ValueTypeFactory::GetInstance()->CreateValueTypeFromDomNode($oNode, $oParent);
		$this->sFormBlockClass = $this->oRealValueType->getFormBlockClass();

		if (is_a($this->sFormBlockClass, ChoiceFormBlock::class, true)) {
			$this->oRealValueType->aFormBlockOptionsForPHP['multiple'] = 'true';
		}

		$oNode = $oDomNode->GetUniqueElement('xml-format');
		$this->oXMLFormat = XMLFormatFactory::GetInstance()->CreateXMLFormatFromDomNode($oNode);

		parent::InitFromDomNode($oDomNode, $oParent);

		$this->oRealValueType->sLabel = $this->sLabel;
		$this->oRealValueType->sRelevanceCondition = $this->sRelevanceCondition;
		$this->oRealValueType->sId = $this->sId;
		$this->oRealValueType->sIdWithPath = $this->sIdWithPath;
	}

	public function ToPHPFormBlock(array &$aPHPFragments = []): string
	{
		return $this->oRealValueType->ToPHPFormBlock($aPHPFragments);
	}

	public function SerializeToDOMNode(?string $sPropertyName, mixed $value, DesignElement $oDOMNode): void
	{
		$this->oXMLFormat->SerializeToDOMNode($sPropertyName, $value, $oDOMNode, $this);
	}

	public function DeserializeFromDOMNode(DesignElement $oDOMNode): mixed
	{
		return $this->oXMLFormat->DeserializeFromDOMNode($oDOMNode, $this);
	}
}
