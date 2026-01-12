<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use utils;

/**
 * @since 3.3.0
 */
class ValueTypeIcon extends AbstractLeafValueType
{
	public function GetFormBlockClass(): string
	{
		return ChoiceFormBlock::class;
	}

	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);

		// Search icons in iTop and extensions
		/** @var \ModelReflection $oModelReflection */
		$oModelReflection = ServiceLocator::GetInstance()->get('ModelReflection');

		$sChoices = "[\n";
		$aIcons = $oModelReflection->GetAvailableIcons();
		foreach ($aIcons as $aIcon) {
			$sValue = utils::QuoteForPHP($aIcon['label']);
			$sCode = utils::QuoteForPHP($aIcon['value']);
			$sChoices .= <<<PHP
\t\t\t\t$sCode => $sValue,\n
PHP;
		}
		$sChoices .= "\t\t\t]";

		$this->aFormBlockOptionsForPHP['choices'] = $sChoices;
	}
}
