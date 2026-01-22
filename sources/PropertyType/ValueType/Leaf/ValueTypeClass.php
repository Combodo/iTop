<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\ChoiceImageFormBlock;
use Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use utils;

/**
 * @since 3.3.0
 */
class ValueTypeClass extends AbstractLeafValueType
{
	protected array $aCategories = [];

	public function GetFormBlockClass(): string
	{
		return ChoiceImageFormBlock::class;
	}

	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);

		$sCategories = $oDomNode->GetChildText('categories-csv', '');
		/** @var \ModelReflection $oModelReflection */
		$oModelReflection = ServiceLocator::GetInstance()->get('ModelReflection');

		$sChoices = "[\n";
		$sChoicesAttImages = "[\n";
		$aClasses = $oModelReflection->GetClasses($sCategories, true);
		sort($aClasses);
		foreach ($aClasses as $sClass) {
			$sValue = utils::QuoteForPHP($sClass);
			$sClassIcon = utils::QuoteForPHP($oModelReflection->GetClassIcon($sClass, false));
			$sChoices .= <<<PHP
				\Dict::S('Class:$sClass') => $sValue,
PHP;
			$sChoicesAttImages .= <<<PHP
				\Dict::S('Class:$sClass') => ["data-image" => $sClassIcon],
PHP;
		}
		$sChoices .= "\t\t\t]";
		$sChoicesAttImages .= "\t\t\t]";

		$this->aFormBlockOptionsForPHP['choices'] = $sChoices;

		$this->aFormBlockOptionsForPHP['choice_attr'] = $sChoicesAttImages;
	}

}
