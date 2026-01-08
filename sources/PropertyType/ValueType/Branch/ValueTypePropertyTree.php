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

	public function SerializeToDOMNode(mixed $value, DesignElement $oDOMNode): void
	{
		foreach ($this->aChildren as $oChild) {
			$sId = $oChild->sId;
			if (isset($value[$sId])) {
				/** @var DesignElement $oChildNode */
				$oChildNode = $oDOMNode->ownerDocument->createElement($sId);
				$oDOMNode->appendChild($oChildNode);
				$oChild->SerializeToDOMNode($value[$sId], $oChildNode);
			}
		}
	}

	public function UnserializeFromDOMNode(DesignElement $oDOMNode): mixed
	{
		$aResults = [];

		foreach ($this->aChildren as $oChild) {
			$sId = $oChild->sId;
			$oChildNode = $oDOMNode->GetOptionalElement($sId);
			if ($oChildNode) {
				$aResults[$sId] = $oChild->UnserializeFromDOMNode($oChildNode);
			}
		}

		return $aResults;
	}

	public function Normalize(mixed $value): mixed
	{
		$aNormalizedValues = [];

		foreach ($this->aChildren as $oChild) {
			$sId = $oChild->sId;
			if (isset($value[$sId])) {
				$aNormalizedValues[$sId] = $oChild->Normalize($value[$sId]);
			}
		}

		return $aNormalizedValues;
	}

	public function EncodeToDOMNode(mixed $normalizedValue, DesignElement $oDOMNode): void
	{
		foreach ($this->aChildren as $oChild) {
			$sId = $oChild->sId;
			if (isset($normalizedValue[$sId])) {
				/** @var DesignElement $oChildNode */
				$oChildNode = $oDOMNode->ownerDocument->createElement($sId);
				$oDOMNode->appendChild($oChildNode);
				$oChild->EncodeToDOMNode($normalizedValue[$sId], $oChildNode);
			}
		}
	}

	public function DecodeFromDomNode(DesignElement $oDOMNode): mixed
	{
		$aNormalizedValue = [];

		foreach ($this->aChildren as $oChild) {
			$sId = $oChild->sId;
			$oChildNode = $oDOMNode->GetOptionalElement($sId);
			if ($oChildNode) {
				$aNormalizedValue[$sId] = $oChild->DecodeFromDomNode($oChildNode);
			}
		}

		return $aNormalizedValue;
	}

	public function Denormalize(mixed $normalizedValue): mixed
	{
		$aValues = [];

		foreach ($this->aChildren as $oChild) {
			$sId = $oChild->sId;
			if (isset($normalizedValue[$sId])) {
				$aValues[$sId] = $oChild->Denormalize($normalizedValue[$sId]);
			}
		}

		return $aValues;
	}
}
