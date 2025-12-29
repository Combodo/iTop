<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyTree;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Expression\BooleanExpressionFormBlock;
use Combodo\iTop\Forms\Block\Expression\NumberExpressionFormBlock;
use Combodo\iTop\Forms\Block\Expression\StringExpressionFormBlock;
use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\IO\Format\NumberIOFormat;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\PropertyTree\ValueType\ValueTypeFactory;
use Exception;
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
	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractProperty $oParent = null): void
	{
		parent::InitFromDomNode($oDomNode, $oParent);

		$oValueTypeNode = $oDomNode->GetOptionalElement('value-type');
		if ($oValueTypeNode) {
			$this->oValueType = ValueTypeFactory::GetInstance()->CreateValueTypeFromDomNode($oValueTypeNode, $this);
		} else {
			throw new PropertyTreeException("Node: {$this->sId}, missing value-type in node specification");
		}

		$this->sRelevanceCondition = $oDomNode->GetChildText('relevance-condition');
	}

	/**
	 * @param $aPHPFragments
	 *
	 * @return string
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 */
	public function ToPHPFormBlock(array &$aPHPFragments = []): string
	{
		$sFormBlockClass = $this->oValueType->GetFormBlockClass();

		$sInputs = '';
		$sPrerequisiteExpressions = '';
		if (!is_null($this->sRelevanceCondition)) {
			$this->GenerateInputs('visible', $this->sRelevanceCondition, $sPrerequisiteExpressions, $sInputs);
		}

		foreach ($this->oValueType->GetInputValues() as $sInputName => $sValue) {
			$this->GenerateInputs($sInputName, $sValue, $sPrerequisiteExpressions, $sInputs);
		}

		foreach ($this->oValueType->GetDynamicInputValues() as $sInputName => $sValue) {
			$this->GenerateInputs($sInputName, $sValue, $sPrerequisiteExpressions, $sInputs, true);
		}

		$sLabel = utils::QuoteForPHP($this->sLabel);
		$aOptions = [
			'label' => $sLabel,
		];
		$aOptions += $this->oValueType->GetFormBlockOptions();
		$sOptions = '';
		foreach ($aOptions as $sOption => $sValue) {
			$sOptions .= "\t\t\t".utils::QuoteForPHP($sOption)." => $sValue,\n";
		}
		$this->oValueType->UpdatePHPFragmentsList($aPHPFragments);
		return <<<PHP
		{$sPrerequisiteExpressions}\$this->Add('$this->sId', '$sFormBlockClass', [
$sOptions\t\t]){$sInputs};

PHP;
	}

	private function GenerateInputs(string $sInputName, string $sValue, string &$sPrerequisiteExpressions, string &$sInputs, bool $bIsDynamic = false): void
	{
		if (preg_match('/^{{(?<node>\w+)\.(?<output>\w+)}}$/', $sValue, $aMatches) === 1) {
			$sVerb = $bIsDynamic ? 'AddInputDependsOn' : 'InputDependsOn';
			$sInputs .= "\n			->$sVerb('$sInputName', '{$aMatches['node']}', '{$aMatches['output']}')";
		} elseif (preg_match('/^{{(?<expression>.*)}}$/', $sValue, $aMatches) === 1) {
			$sExpression = $aMatches['expression'];
			$sBindings = '';
			try {
				$oExpression = Expression::FromOQL($sExpression);
			} catch (Exception $e) {
				throw new PropertyTreeException("Node: {$this->sId}, invalid syntax in condition: ".$e->getMessage());
			}
			$aFieldsToResolve = array_unique($oExpression->ListRequiredFields());
			foreach ($aFieldsToResolve as $sFieldToResolve) {
				if (preg_match('/(?<node>\w+)\.(?<output>\w+)/', $sFieldToResolve, $aMatches) === 1) {
					$sNode = $aMatches['node'];
					$oSibling = $this->GetSibling($sNode);
					if (is_null($oSibling)) {
						// Search in collection
						if (is_a($this->oParent?->oValueType ?? null, 'Combodo-ValueType-Collection')) {
							$bSourceNodeFound = false;
							$aSiblings = $this->oParent->oValueType->GetChildren();
							foreach ($aSiblings as $oSibling) {
								if ($oSibling->sId == $sNode) {
									$bSourceNodeFound = true;
									break;
								}
							}
							if (!$bSourceNodeFound) {
								throw new PropertyTreeException("node: {$this->sId}, source: $sNode not found in collection: {$this->oParent->sId}");
							}
						} else {
							throw new PropertyTreeException("Node: {$this->sId}, invalid source in condition: $sNode");
						}
					}
					$sOutput = $aMatches['output'];
					if (!in_array($sOutput, $oSibling->oValueType->GetOutputs())) {
						throw new PropertyTreeException("Node: {$this->sId}, invalid output in condition: $sFieldToResolve");
					}
					$sBindings .= "\n			->AddInputDependsOn('{$sNode}.$sOutput', '$sNode', '$sOutput')";
				} else {
					throw new PropertyTreeException("Node: {$this->sId}, missing output or source in condition: $sFieldToResolve");
				}
			}

			$sExpressionClass = match ($this->oValueType->GetInputType($sInputName)) {
				BooleanIOFormat::class => BooleanExpressionFormBlock::class,
				StringIOFormat::class, ClassIOFormat::class => StringExpressionFormBlock::class,
				NumberIOFormat::class => NumberExpressionFormBlock::class,
				default => throw new PropertyTreeException("Node: {$this->sId}, unsupported expression for input type: $sInputName"),
			};

			$sExpression = utils::QuoteForPHP($sExpression);
			$sPrerequisiteExpressions = <<<PHP
\$this->Add('{$this->sId}_{$sInputName}_expression', '$sExpressionClass', [
			'expression' => $sExpression,
		]){$sBindings};

		
PHP;
			$sVerb = $bIsDynamic ? 'AddInputDependsOn' : 'InputDependsOn';
			$sInputs .= "\n			->$sVerb('$sInputName', '{$this->sId}_{$sInputName}_expression', 'result')";
		} else {
			$sInputs .= "\n			->SetInputValue('$sInputName', ".utils::QuoteForPHP($sValue).")";
		}
	}
}
