<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\CollectionBlock;
use Combodo\iTop\PropertyTree\AbstractProperty;
use Combodo\iTop\PropertyTree\PropertyTreeFactory;
use utils;

class ValueTypeCollection extends AbstractValueType
{
	private array $aChildren = [];
	private string $sSubTreeClass = '';

	public function InitFromDomNode(DesignElement $oDomNode, AbstractProperty $oParent): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);
		$oPropertyTreeFactory = PropertyTreeFactory::GetInstance();
		$this->aFormBlockOptionsForPHP['button_label'] = utils::QuoteForPHP('UI:AddSubTree');
		$this->sSubTreeClass = 'SubFormFor__'.$oParent->GetIdWithPath();
		$this->aFormBlockOptionsForPHP['block_entry_type'] = utils::QuoteForPHP($this->sSubTreeClass);

		// read child properties
		foreach ($oDomNode->GetUniqueElement('prototype')->childNodes as $oNode) {
			if ($oNode instanceof DesignElement) {
				$this->AddChild($oPropertyTreeFactory->CreateNodeFromDom($oNode, $oParent));
			}
		}
	}

	public function GetFormBlockClass(): string
	{
		return CollectionBlock::class;
	}
	public function AddChild(AbstractProperty $oValueType): void
	{
		$this->aChildren[] = $oValueType;
	}

	/**
	 * @return AbstractProperty[]
	 */
	public function GetChildren(): array
	{
		return $this->aChildren;
	}

	public function UpdatePHPFragmentsList(array &$aPHPFragments): void
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
	}
}
