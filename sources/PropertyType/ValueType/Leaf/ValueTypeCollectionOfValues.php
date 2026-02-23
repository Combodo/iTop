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
use Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType;
use Combodo\iTop\PropertyType\ValueType\Leaf\AbstractLeafValueType;
use Combodo\iTop\PropertyType\ValueType\ValueTypeFactory;

class ValueTypeCollectionOfValues extends AbstractLeafValueType
{
	private string $sFormBlockClass;
	private AbstractXMLFormat $oXMLFormat;

	public function GetFormBlockClass(): string
	{
		return $this->sFormBlockClass;
	}

	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		$oNode = $oDomNode->GetUniqueElement('value-type');
		$oRealValueType = ValueTypeFactory::GetInstance()->CreateValueTypeFromDomNode($oNode, $oParent);
		$this->sFormBlockClass = $oRealValueType->getFormBlockClass();

		if (is_a($this->sFormBlockClass, ChoiceFormBlock::class, true)) {
			$this->aFormBlockOptionsForPHP['multiple'] = 'true';
		}

		$oNode = $oDomNode->GetUniqueElement('xml-format');
		$this->oXMLFormat = XMLFormatFactory::GetInstance()->CreateXMLFormatFromDomNode($oNode);

		parent::InitFromDomNode($oDomNode, $oParent);
	}

	public function SerializeToDOMNode(mixed $value, DesignElement $oDOMNode): void
	{
		$this->oXMLFormat->SerializeToDOMNode($value, $oDOMNode, $this);
	}

	public function UnserializeFromDOMNode(DesignElement $oDOMNode): mixed
	{
		return $this->oXMLFormat->UnserializeFromDOMNode($oDOMNode, $this);
	}
}
