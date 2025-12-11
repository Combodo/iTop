<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\DesignElement;
use Combodo\iTop\PropertyTree\ValueType\ValueTypeFactory;
use Expression;
use utils;

/**
 * @since 3.3.0
 */
class Property extends AbstractProperty
{
	private ?string $sRelevanceCondition = null;

	/**
	 * @inheritDoc
	 */
	public function InitFromDomNode(DesignElement $oDomNode, string $sParentId = ''): void
	{
		parent::InitFromDomNode($oDomNode);

		$oValueTypeNode = $oDomNode->GetOptionalElement('value-type');
		if ($oValueTypeNode) {
			$this->oValueType = ValueTypeFactory::GetInstance()->CreateValueTypeFromDomNode($oValueTypeNode);
		}

		$this->sRelevanceCondition = $oDomNode->GetChildText('relevance-condition');
	}

	public function ToPHPFormBlock(&$aPHPFragments = []): string
	{
		$sFormBlockClass = $this->oValueType->GetFormBlockClass();

		$sInputs = '';
		$sRelevanceCondition = '';
		$sBinding = null;
		if (!is_null($this->sRelevanceCondition)) {
			$oExpression = Expression::FromOQL($this->sRelevanceCondition);
			$aFieldsToResolve = $oExpression->ListRequiredFields();
			foreach ($aFieldsToResolve as $sFieldToResolve) {
				if (preg_match('/(?<node>\w+)\.(?<output>\w+)/', $sFieldToResolve, $aMatches) === 1) {
					$sNode = $aMatches['node'];
					$sOutput = $aMatches['output'];
					$sBinding .= "\n			->AddInputDependsOn('{$sNode}.$sOutput', '$sNode', '$sOutput')";
				} else {
					// TODO Erreur field sans alias
				}
			}

			$sRelevanceCondition = <<<PHP
\$this->Add('{$this->sId}_relevance_condition', 'Combodo\iTop\Forms\Block\Expression\BooleanExpressionFormBlock', [
			'expression' => "{$this->sRelevanceCondition}",
		]){$sBinding};

		
PHP;

			$sInputs .= "\n			->InputDependsOn('visible', '{$this->sId}_relevance_condition', 'result')";
		}

		foreach ($this->oValueType->GetInputs() as $sInput => $sValue) {
			if (preg_match("/^{{(?<node>\w+)\.(?<output>\w+)}}$/", $sValue, $aMatches) === 1) {
				$sInputs .= "\n			->InputDependsOn('$sInput', '{$aMatches['node']}', '{$aMatches['output']}')";
			} else {
				$sInputs .= "\n			->SetInputValue('$sInput', '$sValue')";
			}
		}

		return <<<PHP
		{$sRelevanceCondition}\$this->Add('$this->sId', '$sFormBlockClass', [
			'label' => '$this->sLabel',
		]){$sInputs};

PHP;
	}
}
