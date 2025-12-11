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
	 * @inheritdoc
	 */
	public function InitFromDomNode(DesignElement $oDomNode, string $sParentId = ''): void
	{
		parent::InitFromDomNode($oDomNode, $sParentId);
		$oPropertyTreeFactory = PropertyTreeFactory::GetInstance();

		// read child properties
		foreach ($oDomNode->GetUniqueElement('nodes')->childNodes as $oNode) {
			if ($oNode instanceof DesignElement) {
				$this->AddChild($oPropertyTreeFactory->CreateNodeFromDom($oNode, $this->sId));
			}
		}
	}

	public function ToPHPFormBlock(&$aPHPFragments = []): string
	{
		$bIsRoot = (count($aPHPFragments) === 0);
		$sLocalPHP = <<<PHP
class FormFor__$this->sId extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
PHP;

		foreach ($this->aChildren as $oProperty) {
			$sLocalPHP .= "\n".$oProperty->ToPHPFormBlock($aPHPFragments);
		}

		$sLocalPHP .= <<<PHP
	}
}
PHP;

		$aPHPFragments[] = $sLocalPHP;

		if ($bIsRoot) {
			//			$sOutputPHP = <<<PHP
			//namesapace Combodo\iTop\Forms\Block\Generated;
			//
			//PHP;

			return implode("\n", $aPHPFragments);
		}

		return '';
	}
}
