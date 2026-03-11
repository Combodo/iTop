<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType\ValueType;

use Combodo\iTop\DesignElement;
use Combodo\iTop\Forms\Block\Expression\BooleanExpressionFormBlock;
use Combodo\iTop\Forms\Block\Expression\NumberExpressionFormBlock;
use Combodo\iTop\Forms\Block\Expression\StringExpressionFormBlock;
use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Forms\IO\Format\ClassIOFormat;
use Combodo\iTop\Forms\IO\Format\NumberIOFormat;
use Combodo\iTop\Forms\IO\Format\StringIOFormat;
use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\PropertyType\PropertyTypeException;
use Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType;
use Combodo\iTop\PropertyType\ValueType\Branch\ValueTypePropertyTree;
use Exception;
use Expression;
use utils;

/**
 * @since 3.3.0
 */
abstract class AbstractValueType
{
	protected ?AbstractBranchValueType $oParent;
	protected string $sIdWithPath;

	/** @var FormInput[] */
	protected array $aInputs = [];
	protected array $aOutputs = [];
	protected array $aInputValues = [];
	protected array $aDynamicInputValues = [];
	protected array $aFormBlockOptionsForPHP = [];

	protected string $sId;
	protected ?string $sRelevanceCondition = null;
	protected ?string $sLabel;

	abstract public function GetFormBlockClass(): string;

	/**
	 * @param \Combodo\iTop\DesignElement $oDomNode
	 * @param \Combodo\iTop\PropertyType\ValueType\Branch\AbstractBranchValueType|null $oParent Parent node (used for trees)
	 *
	 * @return void
	 * @throws \DOMFormatException
	 */
	public function InitFromDomNode(DesignElement $oDomNode, ?AbstractBranchValueType $oParent = null): void
	{
		$this->oParent = $oParent;
		// id can already be set for the definition root node
		$this->sId = $this->sId ?? $oDomNode->getAttribute('id');
		if (is_null($oParent)) {
			$this->sIdWithPath = $this->sId;
		} else {
			$this->sIdWithPath = $oParent->sIdWithPath.'__'.$this->sId;
		}
		$this->sLabel = $oDomNode->GetChildText('label');

		$this->sRelevanceCondition = $oDomNode->GetChildText('relevance-condition');
		$sBlockNodeClass = $this->GetFormBlockClass();
		$oBlockNode = new $sBlockNodeClass('foo');
		foreach ($oBlockNode->GetInputs() as $oInput) {
			$sInputName = $oInput->GetName();
			$this->aInputs[$sInputName] = $oInput;
			$sInputValue = $oDomNode->GetChildText($sInputName);
			if (utils::IsNotNullOrEmptyString($sInputValue)) {
				$this->aInputValues[$sInputName] = $sInputValue;
			}
		}
		foreach ($oBlockNode->GetOutputs() as $oOutput) {
			$this->aOutputs[] = $oOutput->GetName();
		}
	}

	public function GetFormBlockOptions(): array
	{
		return $this->aFormBlockOptionsForPHP;
	}

	public function GetInputValues(): array
	{
		return $this->aInputValues;
	}

	public function GetInputType(string $sInputName): string
	{
		return $this->aInputs[$sInputName]->GetDataType();
	}

	public function GetDynamicInputValues(): array
	{
		return $this->aDynamicInputValues;
	}

	public function GetOutputs(): array
	{
		return $this->aOutputs;
	}

	public function SetRootId(string $sId): void
	{
		$this->sId = $sId;
		$this->sIdWithPath = $sId;
	}

	abstract public function IsLeaf(): bool;

	abstract public function ToPHPFormBlock(array &$aPHPFragments = []): string;

	/**
	 * @param array $aPHPFragments
	 *
	 * @return string
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 */
	protected function GetLocalPHPForValueType(?string $sFormBlockClass = null): string
	{
		$sFormBlockClass = $sFormBlockClass ?? $this->GetFormBlockClass();
		$sInputs = '';
		$sPrerequisiteExpressions = '';
		if (!is_null($this->sRelevanceCondition)) {
			$this->GenerateInputs('visible', $this->sRelevanceCondition, $sPrerequisiteExpressions, $sInputs);
		}

		foreach ($this->GetInputValues() as $sInputName => $sValue) {
			$this->GenerateInputs($sInputName, $sValue, $sPrerequisiteExpressions, $sInputs);
		}

		foreach ($this->GetDynamicInputValues() as $sInputName => $sValue) {
			$this->GenerateInputs($sInputName, $sValue, $sPrerequisiteExpressions, $sInputs, true);
		}

		$sLabel = utils::QuoteForPHP($this->sLabel ?? '');
		$aOptions = [
			'label' => $sLabel,
		];
		$aOptions += $this->GetFormBlockOptions();
		$sOptions = '';
		foreach ($aOptions as $sOption => $sValue) {
			$sOptions .= "\t\t\t".utils::QuoteForPHP($sOption)." => $sValue,\n";
		}

		return <<<PHP
\t\t{$sPrerequisiteExpressions}\$this->Add('$this->sId', '$sFormBlockClass', [
$sOptions\t\t]){$sInputs};\n
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
				throw new PropertyTypeException("Node: {$this->sId}, invalid syntax in condition: ".$e->getMessage());
			}
			$aFieldsToResolve = array_unique($oExpression->ListRequiredFields());
			foreach ($aFieldsToResolve as $sFieldToResolve) {
				if (preg_match('/(?<node>\w+)\.(?<output>\w+)/', $sFieldToResolve, $aMatches) === 1) {
					$sNode = $aMatches['node'];
					$oSibling = $this->GetSibling($sNode);
					if (is_null($oSibling)) {
						// Search in collection
						if (is_a($this->oParent ?? null, 'Combodo-ValueType-Collection')) {
							$bSourceNodeFound = false;
							$aSiblings = $this->oParent->GetChildren();
							foreach ($aSiblings as $oSibling) {
								if ($oSibling->sId == $sNode) {
									$bSourceNodeFound = true;
									break;
								}
							}
							if (!$bSourceNodeFound) {
								throw new PropertyTypeException("node: {$this->sId}, source: $sNode not found in collection: {$this->oParent->sId}");
							}
						} else {
							throw new PropertyTypeException("Node: {$this->sId}, invalid source in condition: $sNode");
						}
					}
					$sOutput = $aMatches['output'];
					if (!in_array($sOutput, $oSibling->GetOutputs())) {
						throw new PropertyTypeException("Node: {$this->sId}, invalid output in condition: $sFieldToResolve");
					}
					$sBindings .= "\n\t\t\t->AddInputDependsOn('{$sNode}.$sOutput', '$sNode', '$sOutput')";
				} else {
					throw new PropertyTypeException("Node: {$this->sId}, missing output or source in condition: $sFieldToResolve");
				}
			}

			$sExpressionClass = match ($this->GetInputType($sInputName)) {
				BooleanIOFormat::class => BooleanExpressionFormBlock::class,
				StringIOFormat::class, ClassIOFormat::class => StringExpressionFormBlock::class,
				NumberIOFormat::class => NumberExpressionFormBlock::class,
				default => throw new PropertyTypeException("Node: {$this->sId}, unsupported expression for input type: $sInputName"),
			};

			$sExpression = utils::QuoteForPHP($sExpression);
			$sPrerequisiteExpressions = <<<PHP
\$this->Add('{$this->sId}_{$sInputName}_expression', '$sExpressionClass', [
			'expression' => $sExpression,
		]){$sBindings};\n\n\t\t
PHP;
			$sVerb = $bIsDynamic ? 'AddInputDependsOn' : 'InputDependsOn';
			$sInputs .= "\n\t\t\t->$sVerb('$sInputName', '{$this->sId}_{$sInputName}_expression', 'result')";
		} else {
			$sInputs .= "\n\t\t\t->SetInputValue('$sInputName', ".utils::QuoteForPHP($sValue).')';
		}
	}

	public function GetId(): string
	{
		return $this->sId;
	}

	protected function GetSibling(string $sId): ?AbstractValueType
	{
		if (is_null($this->oParent)) {
			return null;
		}

		return $this->oParent->GetChild($sId);
	}

	public function SerializeToDOMNode(mixed $value, DesignElement $oDOMNode): void
	{
		$sXmlValue = $value;
		$oTextNode = $oDOMNode->ownerDocument->createTextNode($sXmlValue);
		$oDOMNode->appendChild($oTextNode);
	}

	/**
	 * @param $oDOMNode
	 *
	 * @return mixed
	 */
	public function UnserializeFromDOMNode(DesignElement $oDOMNode): mixed
	{
		return $oDOMNode->GetText();
	}
}
