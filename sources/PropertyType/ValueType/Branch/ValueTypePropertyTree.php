<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Branch;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Combodo\iTop\PropertyType\ValueType\ValueTypeFactory;

/**
 * @since 3.3.0
 */
class ValueTypePropertyTree extends AbstractBranchValueType
{
	protected string $sSubTreeClass;

	public function GetFormBlockClass(): string
	{
		return FormBlock::class;
	}

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

		// read child properties
		$oNodes = $oDomNode->GetOptionalElement('nodes');
		if (!is_null($oNodes)) {
			foreach ($oNodes->childNodes as $oNode) {
				if ($oNode instanceof DesignElement) {
					$this->AddChild(ValueTypeFactory::GetInstance()->CreateValueTypeFromDomNode($oNode, $this));
				}
			}
		}
	}

	public function ToPHPFormBlock(array &$aPHPFragments = []): string
	{
		if ($this->IsRoot()) {
			$this->sSubTreeClass = 'FormFor__'.$this->sId;
		} else {
			$this->sSubTreeClass = 'SubFormFor__'.$this->sIdWithPath;
		}

		$sLocalPHP = <<<PHP
class $this->sSubTreeClass extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
PHP;

		foreach ($this->aChildren as $oChild) {
			$sLocalPHP .= "\n".$oChild->ToPHPFormBlock($aPHPFragments);
		}

		$sLocalPHP .= <<<PHP
	}
}
PHP;

		$aPHPFragments[] = $sLocalPHP;

		return $this->GetLocalPHPForValueType($this->sSubTreeClass);
	}

	public function SerializeToDOMNode(?string $sPropertyName, mixed $value, DesignElement $oDOMNode): void
	{
		if (!is_null($sPropertyName)) {
			$oPropertyNode = $oDOMNode->ownerDocument->createElement($sPropertyName);
			$oDOMNode->appendChild($oPropertyNode);
		} else {
			$oPropertyNode = $oDOMNode;
		}
		foreach ($this->aChildren as $oChild) {
			$sId = $oChild->sId;
			if (isset($value[$sId])) {
				$oChild->SerializeToDOMNode($sId, $value[$sId], $oPropertyNode);
			}
		}
	}

	public function DeserializeFromDOMNode(DesignElement $oDOMNode): mixed
	{
		$aResults = [];

		foreach ($this->aChildren as $oChild) {
			$sId = $oChild->sId;
			$oChildNode = $oDOMNode->GetOptionalElement($sId);
			if ($oChildNode) {
				$aResults[$sId] = $oChild->DeserializeFromDOMNode($oChildNode);
			} elseif (is_a($oChild, 'Combodo-ValueType-Collection')) {
				// For flat arrays, no node with $sId is present
				$aResults[$sId] = $oChild->DeserializeFromDOMNode($oDOMNode);
			} else {
				$aResults[$sId] = '';
			}
		}

		return $aResults;
	}
}
