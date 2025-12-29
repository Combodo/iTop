<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\ChoiceFromInputsBlock;
use Combodo\iTop\PropertyTree\AbstractProperty;
use Combodo\iTop\PropertyTree\ValueType\AbstractValueType;
use utils;

class ValueTypeChoiceFromInput extends AbstractValueType
{
	public function GetFormBlockClass(): string
	{
		return ChoiceFromInputsBlock::class;
	}

	public function InitFromDomNode(DesignElement $oDomNode, AbstractProperty $oParent): void
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
