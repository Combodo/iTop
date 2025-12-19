<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType\Leaf;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Base\ChoiceFormBlock;
use Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType;
use Combodo\iTop\PropertyType\ValueType\Branch\ValueTypePropertyTree;
use utils;

/**
 * @since 3.3.0
 */
class ValueTypeChoice extends AbstractLeafValueType
{
	public function GetFormBlockClass(): string
	{
		return ChoiceFormBlock::class;
	}

	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);

		$sChoices = "[\n";
		foreach ($oDomNode->GetNodes('values/value') as $oValueNode) {
			/** @var DesignElement $oValueNode */
			$sValue = utils::QuoteForPHP($oValueNode->GetAttribute('id'));
			$sLabel = utils::QuoteForPHP($oValueNode->GetChildText('label'));
			$sChoices .= <<<PHP
				\Dict::S($sLabel) => $sValue,

PHP;
		}
		$sChoices .= "\t\t\t]";

		$this->aFormBlockOptionsForPHP['choices'] = $sChoices;
	}
}
