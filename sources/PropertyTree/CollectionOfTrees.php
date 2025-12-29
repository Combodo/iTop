<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\CollectionBlock;
use Combodo\iTop\PropertyTree\CollectionType\AbstractCollectionType;
use Combodo\iTop\PropertyTree\CollectionType\CollectionTypeFactory;
use Combodo\iTop\PropertyTree\ValueType\AbstractValueType;
use Combodo\iTop\PropertyTree\ValueType\ValueTypeFactory;

/**
 * @since 3.3.0
 */
class CollectionOfTrees extends AbstractProperty
{
	protected ?string $sButtonLabel;
	protected ?AbstractCollectionType $oCollectionType;

	/**
	 * @inheritDoc
	 */
	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractProperty $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);
		$oPropertyTreeFactory = PropertyTreeFactory::GetInstance();

		$oCollectionTypeNode = $oDomNode->GetOptionalElement('collection-type');
		if ($oCollectionTypeNode) {
			$this->oCollectionType = CollectionTypeFactory::GetInstance()->CreateCollectionTypeFromDomNode($oCollectionTypeNode);
		} else {
			throw new PropertyTreeException("Node: {$this->sId}, missing collection-type in node specification");
		}

		// read child properties
		foreach ($oDomNode->GetUniqueElement('prototype')->childNodes as $oNode) {
			if ($oNode instanceof DesignElement) {
				$this->AddChild($oPropertyTreeFactory->CreateNodeFromDom($oNode, $this));
			}
		}
	}

	public function ToPHPFormBlock(&$aPHPFragments = []): string
	{
		$sFormBlockClass = $this->oCollectionType->GetFormBlockClass();

		$sSubTreeClass = 'SubFormFor__'.$this->sIdWithPath;

		// Create the collection node
		$sLocalPHP = <<<PHP
		\$this->Add('$this->sId', '$sFormBlockClass', [
			'label' => '$this->sLabel',
			'button_label' => '$this->sButtonLabel',
			'block_entry_type' => '$sSubTreeClass',
		]);

PHP;

		$sSubClassPHP = <<<PHP
		class $sSubTreeClass extends Combodo\iTop\Forms\Block\Base\FormBlock
		{
			protected function BuildForm(): void
			{
		PHP;

		foreach ($this->aChildren as $oProperty) {
			$sSubClassPHP .= "\n".$oProperty->ToPHPFormBlock($aPHPFragments);
		}

		$sSubClassPHP .= <<<PHP
			}
		}

		PHP;

		$aPHPFragments[] = $sSubClassPHP;

		return $sLocalPHP;
	}
}
