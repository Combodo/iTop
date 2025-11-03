<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\FormBlock;
use Combodo\iTop\Forms\Block\IO\FormBinding;
use Combodo\iTop\Forms\Block\IO\FormInput;
use Combodo\iTop\Forms\Block\IO\FormOutput;

/**
 * Dependencies handler.
 *
 */
class DependencyMap
{
	/** @var array array of blocks with dependencies group by dependence */
	private array $aBlocksWithDependenciesGroupByDependence = [];

	/** @var array array of binding (OUT > OUT) grouped by block and output name  */
	private array $aBindingsOutputToInput = [];

	/** @var array array of binding (IN > IN) grouped by block and output name  */
	private array $aBindingsInputToInput = [];

	/** @var array array of binding (OUT > OUT) grouped by block and output name  */
	private array $aBindingsOutputToOutputs = [];

	/**
	 * Constructor.
	 *
	 * @param array $aBlocksWithDependencies
	 */
	public function __construct(private readonly array $aBlocksWithDependencies)
	{
		// Initialization
		$this->Init();
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	private function Init(): void
	{
		/** Iterate throw blocks with dependencies... @var AbstractFormBlock $oDependentBlock */
		foreach ($this->aBlocksWithDependencies as $oDependentBlock) {

			/** Iterate throw the block inputs bindings... @var FormBinding $oBinding**/
			foreach ($oDependentBlock->GetInputsBindings() as $oBinding) {

				// OUT > IN
				if($oBinding->oSourceIO instanceof FormOutput
				&& $oBinding->oDestinationIO instanceof FormInput){
					$this->AddBindingToMap($this->aBindingsOutputToInput, $oBinding);
					$this->AddToBlockWithDependenciesMap($oBinding->oSourceIO->GetOwnerBlock()->GetName(), $oDependentBlock);
				}

				// IN > IN
				if($oBinding->oSourceIO instanceof FormInput
				&& $oBinding->oDestinationIO instanceof FormInput){
					$this->AddBindingToMap($this->aBindingsInputToInput, $oBinding);
				}

			}

			/** Iterate throw the block inputs connections... @var FormBinding $oBinding**/
			foreach ($oDependentBlock->GetOutputBindings() as $oBinding) {

				// OUT > OUT
				if($oBinding->oSourceIO instanceof FormOutput
					&& $oBinding->oDestinationIO instanceof FormOutput){
					$this->AddBindingToMap($this->aBindingsOutputToOutputs, $oBinding);
				}

			}
		}

	}

	/**
	 * @param string $sDependenceBlockName
	 * @param AbstractFormBlock $oBlockWithDependencies
	 *
	 * @return void
	 */
	private function AddToBlockWithDependenciesMap(string $sDependenceBlockName, AbstractFormBlock $oBlockWithDependencies): void
	{
		// Initialize array for this dependence
		if(!array_key_exists($sDependenceBlockName, $this->aBlocksWithDependenciesGroupByDependence)){
			$this->aBlocksWithDependenciesGroupByDependence[$sDependenceBlockName] = [];
		}

		// Add the block
		$this->aBlocksWithDependenciesGroupByDependence[$sDependenceBlockName][$oBlockWithDependencies->GetName()] = $oBlockWithDependencies;
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return array|null
	 */
	public function GetBlocksDependingOn(string $sBlockName): ?array
	{
		if(!array_key_exists($sBlockName,$this->aBlocksWithDependenciesGroupByDependence)){
			return null;
		}
		return $this->aBlocksWithDependenciesGroupByDependence[$sBlockName] ?? null;
	}

	/**
	 * Add a binding to a map.
	 *
	 * @param array $map
	 * @param FormBinding $oBinding
	 *
	 * @return void
	 */
	private function AddBindingToMap(array &$map, FormBinding $oBinding): void
	{
		// Binding information
		$sBlockName = $oBinding->oSourceIO->GetOwnerBlock()->GetName();
		$sIOName = $oBinding->oSourceIO->GetName();

		// initialize map
		if (!isset($map[$sBlockName])) {
			$map[$sBlockName] = [];
		}
		if (!isset($map[$sBlockName][$sIOName])) {
			$map[$sBlockName][$sIOName] = [];
		}

		// add to map
		$map[$sBlockName][$sIOName][] = $oBinding;
	}

	/**
	 * @return array
	 */
	public function GetInitialBoundOutputBlockNames(): array
	{
		$aResult = [];

		// Iterate throw binding OUT > IN
		foreach(array_keys($this->aBindingsOutputToInput) as $sOutputBlockName) {

			// Exclude block containing dependencies
			if(!array_key_exists($sOutputBlockName, $this->aBlocksWithDependencies)){
				$aResult[] = $sOutputBlockName;
			}
		}

		return $aResult;
	}

	public function GetImpacted(string $sBlockName): array
	{
		$aImpacted = [];
		if (array_key_exists($sBlockName, $this->aOutputToInputsMap)) {
			foreach ($this->aOutputToInputsMap[$sBlockName] as $aBindings) {
				foreach ($aBindings as $oBinding) {
					$oDestBlock = $oBinding->oDestinationIO->GetOwnerBlock();
					$aImpacted[] = $oDestBlock;
				}
			}
		}

		return $aImpacted;
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return bool
	 */
	public function IsBlockHasOutputs(string $sBlockName): bool
	{
		return array_key_exists($sBlockName, $this->aBindingsOutputToInput);
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return array
	 */
	public function GetOutputsForBlock(string $sBlockName): array
	{
		return array_keys($this->aBindingsOutputToInput[$sBlockName]);
	}

	public function GetOutputsDependenciesForBlock(string $sOutputBlockName): array
	{
		return $this->aBindingsOutputToInput[$sOutputBlockName];
	}

	public function IsTheBlockInDependencies(string $sBlockName): bool
	{
//		foreach ($this->aDependentBlocks as $oDependentBlock)
//		{
//			if($oDependentBlock->getName() === $sBlockName) {
//				return true;
//			}
//		}
//
//		return false;
		return $this->GetBlocksDependingOn($sBlockName) !== null;
	}

	public function GetOutputToInputs(): array
	{
		return $this->aBindingsOutputToInput;
	}

	public function GetInputToInputs(): array
	{
		return $this->aBindingsInputToInput;
	}

	public function GetOutputToOutputs(): array
	{
		return $this->aBindingsOutputToOutputs;
	}
}