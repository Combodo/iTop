<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\Forms\Block\Base\ChoiceImageFormBlock;
use Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType;
use Combodo\iTop\Service\ServiceLocator\ServiceLocator;
use utils;

/**
 * @since 3.3.0
 */
class ValueTypeIcon extends AbstractLeafValueType
{
	public function GetFormBlockClass(): string
	{
		return ChoiceImageFormBlock::class;
	}

	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);

		// Search icons in iTop and extensions
		/** @var \ModelReflection $oModelReflection */
		$oModelReflection = \MetaModel::GetService('ModelReflection');

		$sChoices = "[\n";
		$sChoicesAttImages = "[\n";
		$aIcons = $oModelReflection->GetAvailableIcons();
		foreach ($aIcons as $aIcon) {
			$sLabel = utils::QuoteForPHP($aIcon['label']);
			$sIcon = utils::QuoteForPHP($aIcon['icon']);
			$sCode = utils::QuoteForPHP($aIcon['value']);
			$sChoices .= <<<PHP
$sLabel => $sCode,\n
PHP;
			$sChoicesAttImages .= <<<PHP
$sLabel => ["data-image" => $sIcon],
PHP;
		}
		$sChoices .= "\t\t\t]";
		$sChoicesAttImages .= "\t\t\t]";

		$this->aFormBlockOptionsForPHP['choices'] = $sChoices;

		$this->aFormBlockOptionsForPHP['choice_attr'] = $sChoicesAttImages;

		$this->aFormBlockOptionsForPHP['required'] = 'false';
	}
}
