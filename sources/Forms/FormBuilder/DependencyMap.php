<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

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
	/** @var array output to outputs map map */
	private array $aOutputToInputsMap = [];

	/** @var array input to inputs map map */
	private array $aInputToInputsMap = [];

	/** @var array output to outputs */
	private array $aOutputToOutputsMap = [];
	private readonly array $aDependentBlocks;

	/**
	 * Constructor.
	 *
	 * @param array $aDependentBlocks
	 */
	public function __construct(array $aDependentBlocks)
	{
		$this->aDependentBlocks = $aDependentBlocks;
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
		/** Iterate throw blocks with dependencies... @var FormBlock $oDependentBlock */
		foreach ($this->aDependentBlocks as $sBlockName => $oDependentBlock) {

			/** Iterate throw the block inputs bindings... @var FormBinding $oBinding**/
			foreach ($oDependentBlock->GetInputsBindings() as $oBinding) {

				// Output to inputs map
				if($oBinding->oSourceIO instanceof FormOutput
				&& $oBinding->oDestinationIO instanceof FormInput){
					$this->AddBindingToMap($this->aOutputToInputsMap, $oBinding);
				}
				// Input to inputs map
				if($oBinding->oSourceIO instanceof FormInput
				&& $oBinding->oDestinationIO instanceof FormInput){
					$this->AddBindingToMap($this->aInputToInputsMap, $oBinding);
				}

			}

			/** Iterate throw the block inputs connections... @var FormBinding $oBinding**/
			foreach ($oDependentBlock->GetOutputBindings() as $oBinding) {

				// Output to outputs map
				if($oBinding->oSourceIO instanceof FormOutput
					&& $oBinding->oDestinationIO instanceof FormOutput){
					$this->AddBindingToMap($this->aOutputToOutputsMap, $oBinding);
				}

			}
		}

		return;
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
	public function GetListenedOutputBlockNames(): array
	{
		$aResult = [];

		foreach(array_keys($this->aOutputToInputsMap) as $sOutputBlockName) {
			if(!array_key_exists($sOutputBlockName, $this->aDependentBlocks)){
				$aResult[] = $sOutputBlockName;
			}
		}

		return $aResult;
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return bool
	 */
	public function IsBlockHasOutputs(string $sBlockName): bool
	{
		return array_key_exists($sBlockName, $this->aOutputToInputsMap);
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return array
	 */
	public function GetOutputsForBlock(string $sBlockName): array
	{
		return array_keys($this->aOutputToInputsMap[$sBlockName]);
	}

	public function GetOutputsDependenciesForBlock(string $sOutputBlockName): array
	{
		return $this->aOutputToInputsMap[$sOutputBlockName];
	}

	public function IsTheBlockInDependencies(string $sBlockName): bool
	{
		foreach ($this->aDependentBlocks as $oDependentBlock)
		{
			if($oDependentBlock->getName() === $sBlockName) {
				return true;
			}
		}

		return false;
	}

	public function GetOutputToInputs(): array
	{
		return $this->aOutputToInputsMap;
	}

	public function GetInputToInputs(): array
	{
		return $this->aInputToInputsMap;
	}

	public function GetOutputToOutputs(): array
	{
		return $this->aOutputToOutputsMap;
	}
}