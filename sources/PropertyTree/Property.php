<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyTree\ValueType\ValueTypeFactory;

/**
 * @since 3.3.0
 */
class Property extends AbstractProperty
{
	/**
	 * @inheritDoc
	 */
	public function InitFromDomNode(DesignElement $oDomNode, string $sParentId = ''): void
	{
		parent::InitFromDomNode($oDomNode);

		$oValueTypeNode = $oDomNode->GetOptionalElement('value-type');
		if ($oValueTypeNode) {
			$this->oValueType = ValueTypeFactory::GetInstance()->CreateValueTypeFromDomNode($oValueTypeNode);
		}
	}

	public function ToPHPFormBlock(&$aPHPFragments = []): string
	{
		$sFormBlockClass = $this->oValueType->GetFormBlockClass();
		return <<<PHP
		\$this->Add('$this->sId', '$sFormBlockClass', [
			'label' => '$this->sLabel',
		]);

PHP;
	}
}
