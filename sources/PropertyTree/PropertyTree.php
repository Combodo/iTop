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
	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractProperty $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);
		$oPropertyTreeFactory = PropertyTreeFactory::GetInstance();

		// read child properties
		foreach ($oDomNode->GetUniqueElement('nodes')->childNodes as $oNode) {
			if ($oNode instanceof DesignElement) {
				$this->AddChild($oPropertyTreeFactory->CreateNodeFromDom($oNode, $this));
			}
		}
	}

	public function ToPHPFormBlock(array &$aPHPFragments = []): string
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
			//namespace Combodo\iTop\Forms\Block\Generated;
			//
			//PHP;

			return implode("\n", $aPHPFragments);
		}

		return '';
	}
}
