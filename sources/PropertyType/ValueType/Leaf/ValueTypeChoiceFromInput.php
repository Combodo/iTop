<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\ChoiceFromInputsBlock;
use Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType;
use Combodo\iTop\PropertyType\ValueType\Branch\ValueTypePropertyTree;
use utils;

/**
 * @since 3.3.0
 */
class ValueTypeChoiceFromInput extends AbstractLeafValueType
{
	public function GetFormBlockClass(): string
	{
		return ChoiceFromInputsBlock::class;
	}

	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);

		foreach ($oDomNode->GetNodes('values/value') as $oValueNode) {
			/** @var DesignElement $oValueNode */
			$sValue = $oValueNode->GetAttribute('id');
			$sLabel = $oValueNode->GetChildText('label');
			$this->aDynamicInputValues[$sValue] = $sLabel;
		}
	}
}
