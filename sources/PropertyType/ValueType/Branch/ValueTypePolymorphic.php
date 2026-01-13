<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Branch;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\PolymorphicFormBlock;
use Combodo\iTop\PropertyType\PropertyTypeService;
use Combodo\iTop\PropertyType\ValueType\ValueTypeFactory;

class ValueTypePolymorphic extends AbstractBranchValueType
{
	public function GetFormBlockClass(): string
	{
		return PolymorphicFormBlock::class;
	}

	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);
	}

	public function ToPHPFormBlock(array &$aPHPFragments = []): string
	{
		return "// ValueTypePolymorphic Block\n";
	}

	public function EncodeToDOMNode(mixed $normalizedValue, DesignElement $oDOMNode): void
	{
		$sType = $normalizedValue['type'];
		$oDOMNode->setAttribute('xsi:type', $sType);

		$oPropertyType = PropertyTypeService::GetInstance()->GetPropertyType($sType);

		$aProperties =  $normalizedValue['properties'];
		$oPropertyType->EncodeToDOMNode($aProperties, $oDOMNode);
	}

	public function DecodeFromDomNode(DesignElement $oDOMNode): mixed
	{
		$sType = $oDOMNode->getAttribute('xsi:type');
		$oPropertyType = PropertyTypeService::GetInstance()->GetPropertyType($sType);

		return [
			'type' => $sType,
			'properties' => $oPropertyType->DecodeFromDOMNode($oDOMNode),
		];
	}
}
