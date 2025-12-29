<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree\ValueType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\Base\TextFormBlock;
use Combodo\iTop\PropertyTree\AbstractProperty;
use Combodo\iTop\PropertyTree\ValueType\AbstractValueType;
use Combodo\iTop\Service\DependencyInjection\DIService;
use utils;

class ValueTypeClass extends AbstractValueType
{
	protected array $aCategories = [];

	public function GetFormBlockClass(): string
	{
		return ChoiceFormBlock::class;
	}

	public function InitFromDomNode(DesignElement $oDomNode, AbstractProperty $oParent): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);

		$sCategories = $oDomNode->GetChildText('categories-csv');
		/** @var \ModelReflection $oModelReflection */
		$oModelReflection = DIService::GetInstance()->GetService('ModelReflection');

		$sChoices = "[\n";
		$aClasses = $oModelReflection->GetClasses($sCategories, true);
		sort($aClasses);
		foreach ($aClasses as $sClass) {
			if ($oModelReflection->IsAbstract($sClass)) {
				continue;
			}
			$sValue = utils::QuoteForPHP($sClass);
			$sChoices .= <<<PHP
				\Dict::S('Class:$sClass') => $sValue,

PHP;
		}
		$sChoices .= "\t\t\t]";

		$this->aFormBlockOptionsForPHP['choices'] = $sChoices;
	}

}
