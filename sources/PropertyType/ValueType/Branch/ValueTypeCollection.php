<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Branch;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\CollectionBlock;
use Combodo\iTop\PropertyType\Serializer\XMLFormat\AbstractXMLFormat;
use Combodo\iTop\PropertyType\Serializer\XMLFormat\XMLFormatFactory;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;
use Combodo\iTop\PropertyType\ValueType\ValueTypeFactory;
use utils;

/**
 * @since 3.3.0
 */
class ValueTypeCollection extends ValueTypePropertyTree
{
	private AbstractXMLFormat $oXMLFormat;

	/**
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 * @param \Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType|null $oParent
	 *
	 * @return void
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);
		$this->aFormBlockOptionsForPHP['button_label'] = utils::QuoteForPHP('UI:AddSubTree');
		$this->sSubTreeClass = 'SubFormFor__'.$this->sIdWithPath;
		$this->aFormBlockOptionsForPHP['block_entry_type'] = utils::QuoteForPHP($this->sSubTreeClass);

		$oNode = $oDomNode->GetUniqueElement('xml-format');
		$this->oXMLFormat = XMLFormatFactory::GetInstance()->CreateXMLFormatFromDomNode($oNode);

		// read child properties
		foreach ($oDomNode->GetUniqueElement('prototype')->childNodes as $oNode) {
			if ($oNode instanceof DesignElement) {
				$this->AddChild(ValueTypeFactory::GetInstance()->CreateValueTypeFromDomNode($oNode, $this));
			}
		}
	}

	public function GetFormBlockClass(): string
	{
		return CollectionBlock::class;
	}

	/**
	 * @param array $aPHPFragments
	 *
	 * @return string
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 */
	public function ToPHPFormBlock(array &$aPHPFragments = []): string
	{
		$sSubClassPHP = <<<PHP
		class $this->sSubTreeClass extends Combodo\iTop\Forms\Block\Base\FormBlock
		{
			protected function BuildForm(): void
			{
		PHP;

		foreach ($this->GetChildren() as $oProperty) {
			$sSubClassPHP .= "\n".$oProperty->ToPHPFormBlock($aPHPFragments);
		}

		$sSubClassPHP .= <<<PHP
			}
		}
		PHP;

		$aPHPFragments[] = $sSubClassPHP;

		return $this->GetLocalPHPForValueType();
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
