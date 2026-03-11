<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyType\ValueType\AbstractValueType;

/**
 * A property type is a definition of properties (organized in tree)
 * used for dashlet for example.
 * @since 3.3.0
 */
class PropertyType
{
	private string $sParentType = '';
	private string $sId;
	private AbstractValueType $oValueType;

	/**
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 *
	 * @return void
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function InitFromDomNode(DesignElement $oDomNode): void
	{
		$this->sId = $oDomNode->getAttribute('id');
		$this->sParentType = $oDomNode->GetChildText('extends', '');

		$oDefinitionNode = $oDomNode->GetUniqueElement('definition');
		$sDefinitionNodeType = $oDefinitionNode->getAttribute('xsi:type');

		if (!is_a($sDefinitionNodeType, AbstractValueType::class, true)) {
			throw new PropertyTypeException('Unsupported xsi:type '.json_encode($sDefinitionNodeType), $oDomNode);
		}

		$this->oValueType = new $sDefinitionNodeType();
		$this->oValueType->SetRootId($this->sId);
		$this->oValueType->InitFromDomNode($oDefinitionNode);
	}

	public function ToPHPFormBlock(): string
	{
		$aPHPFragments = [];

		if ($this->oValueType->IsLeaf()) {
			$sFormBlockClass = $this->oValueType->GetFormBlockClass();

			$sLocalPHP = <<<PHP
class FormFor__$this->sId extends $sFormBlockClass
{
}
PHP;
			$aPHPFragments[] = $sLocalPHP;
		} else {
			$this->oValueType->ToPHPFormBlock($aPHPFragments);
		}

		return implode("\n\n", $aPHPFragments);
	}

	public function GetParentType(): string
	{
		return $this->sParentType;
	}

	public function SerializeToDOMNode(mixed $value, DesignElement$oDOMNode): void
	{
		$this->oValueType->SerializeToDOMNode($value, $oDOMNode);
	}

	public function UnserializeFromDOMNode(DesignElement $oDOMNode): mixed
	{
		return $this->oValueType->UnserializeFromDOMNode($oDOMNode);
	}
}
