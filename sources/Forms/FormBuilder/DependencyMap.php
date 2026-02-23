<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\Expression\AbstractExpressionFormBlock;
use Combodo\iTop\Forms\Block\FormBlock;
use Combodo\iTop\Forms\IO\FormBinding;
use Combodo\iTop\Forms\IO\FormInput;
use Combodo\iTop\Forms\IO\FormOutput;

/**
 * Map containing information of form block dependencies.
 *
 * @package Combodo\iTop\Forms\FormBuilder
 * @since 3.3.0
 */
class DependencyMap
{
	/** @var array array of blocks impacted by dependence */
	private array $aBlocksImpactedBy = [];

	/** @var array array of binding  */
	private array $aBindings = [];

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
			foreach ($oDependentBlock->GetBoundInputsBindings() as $oBinding) {

				// OUT > IN
				if ($oBinding->oSourceIO instanceof FormOutput
				&& $oBinding->oDestinationIO instanceof FormInput) {
					$this->AddBindingToMap($this->aBindingsOutputToInput, $oBinding);
					$this->AddToBlockImpactedBy($oBinding->oSourceIO->GetOwnerBlock()->GetName(), $oDependentBlock);
				}

				// IN > IN
				if ($oBinding->oSourceIO instanceof FormInput
				&& $oBinding->oDestinationIO instanceof FormInput) {
					$this->AddBindingToMap($this->aBindingsInputToInput, $oBinding);
				}

			}

			/** Iterate throw the block inputs connections... @var FormBinding $oBinding**/
			foreach ($oDependentBlock->GetBoundOutputBindings() as $oBinding) {

				// OUT > OUT
				if ($oBinding->oSourceIO instanceof FormOutput
					&& $oBinding->oDestinationIO instanceof FormOutput) {
					$this->AddBindingToMap($this->aBindingsOutputToOutputs, $oBinding);
				}

			}
		}

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
		$this->aBindings[] = $oBinding;
	}

	/**
	 * @param string $sDependsOnName
	 * @param AbstractFormBlock $oImpactedBlock
	 *
	 * @return void
	 */
	private function AddToBlockImpactedBy(string $sDependsOnName, AbstractFormBlock $oImpactedBlock): void
	{
		// Initialize array for this dependence
		if (!array_key_exists($sDependsOnName, $this->aBlocksImpactedBy)) {
			$this->aBlocksImpactedBy[$sDependsOnName] = [];
		}

		// Add the block
		$this->aBlocksImpactedBy[$sDependsOnName][$oImpactedBlock->GetName()] = $oImpactedBlock;

		// TODO
		if ($oImpactedBlock instanceof AbstractExpressionFormBlock) {
			foreach ($oImpactedBlock->GetOutputs() as $oOutput) {
				foreach ($oOutput->GetBindings() as $oBinding) {
					$this->AddToBlockImpactedBy($sDependsOnName, $oBinding->oDestinationIO->GetOwnerBlock());
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function GetImpactingBlocksWithoutDependencies(): array
	{
		$aResult = [];

		// Iterate throw binding OUT > IN
		foreach (array_keys($this->aBindingsOutputToInput) as $sOutputBlockName) {

			// Exclude block containing dependencies
			if (!array_key_exists($sOutputBlockName, $this->aBlocksWithDependencies)) {
				$aResult[] = $sOutputBlockName;
			}
		}

		return $aResult;
	}

	/**
	 * Get block impacted by a given block.
	 * The blocks can be filtered using a callable.
	 *
	 * @param string $sBlockName
	 * @param callable|null $oFilter
	 *
	 * @return array|null
	 */
	public function GetBlocksImpactedBy(string $sBlockName, callable $oFilter = null): ?array
	{
		if (!array_key_exists($sBlockName, $this->aBlocksImpactedBy)) {
			return null;
		}
		$aBlocks = $this->aBlocksImpactedBy[$sBlockName];

		// Filtering
		if ($oFilter !== null) {
			$aBlocks = array_filter($aBlocks, $oFilter);
		}

		return $aBlocks;
	}

	/**
	 * Check if a block impacts other blocks.
	 *
	 * @param string $sBlockName
	 *
	 * @return bool
	 */
	public function HasBlocksImpactedBy(string $sBlockName): bool
	{
		return $this->GetBlocksImpactedBy($sBlockName) !== null;
	}

	/**
	 * Get bindings OUT > IN.
	 *
	 * @return array
	 */
	public function GetOutputToInputs(): array
	{
		return $this->aBindingsOutputToInput;
	}

	/**
	 * Get bindings IN > IN.
	 *
	 * @return array
	 */
	public function GetInputToInputs(): array
	{
		return $this->aBindingsInputToInput;
	}

	/**
	 * Get bindings OUT > OUT.
	 *
	 * @return array
	 */
	public function GetOutputToOutputs(): array
	{
		return $this->aBindingsOutputToOutputs;
	}

	/**
	 * Get all bindings.
	 *
	 * @return array
	 */
	public function GetAllBindings()
	{
		return $this->aBindings;
	}
}
