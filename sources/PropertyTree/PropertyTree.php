<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\DesignElement;

/**
 * @since 3.3.0
 */
class PropertyTree extends AbstractProperty
{
	/**
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 *
	 * @return void
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 * @throws \DOMFormatException
	 */
	public function InitFromDomNode(DesignElement $oDomNode): void
	{
		parent::InitFromDomNode($oDomNode);
		$oPropertyTreeService = PropertyTreeFactory::GetInstance();

		// read child properties
		foreach ($oDomNode->GetUniqueElement('nodes')->childNodes as $oNode) {
			if ($oNode instanceof DesignElement) {
				$this->AddChild($oPropertyTreeService->CreateNodeFromDom($oNode));
			}
		}
	}

	public function ToPHP(&$aPHPFragments = []): string
	{
		$bIsRoot = (count($aPHPFragments) === 0);
		$sLocalPHP = <<<PHP
class FormFor$this->sId extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
PHP;

		foreach ($this->aChildren as $oProperty) {
			$sLocalPHP .= "\n".$oProperty->ToPHP($aPHPFragments);
		}

		$sLocalPHP .= <<<PHP
	}
}
PHP;

		$aPHPFragments[] = $sLocalPHP;

		if ($bIsRoot) {
			return implode("\n", $aPHPFragments);
		}

		return '';
	}
}
