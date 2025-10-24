<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Combodo\iTop\Forms\Block\FormBlock;
use Combodo\iTop\Forms\Block\IO\FormBinding;

/**
 * Dependencies handler.
 *
 */
class DependencyMap
{
	/** @var array dependencies map */
	private array $aDependenciesMap;

	/**
	 * Constructor.
	 *
	 * @param array $aDependentBlocks
	 */
	public function __construct(private readonly array $aDependentBlocks)
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
		/** iterate throw dependents blocks... @var FormBlock $oDependentBlock */
		foreach ($this->aDependentBlocks as $oDependentBlock) {

			/** iterate throw the block inputs connections... @var FormBinding $oBinding**/
			foreach ($oDependentBlock->GetInputsBindings() as $sInputName => $oBinding) {

				// connection information
				$sOutputBlockName = $oBinding->oOutput->GetOwnerBlock()->GetName();
				$sOutputName = $oBinding->oOutput->GetName();

				// initialize map
				if (!isset($this->aDependenciesMap[$sOutputBlockName])) {
					$this->aDependenciesMap[$sOutputBlockName] = [];
				}
				if (!isset($this->aDependenciesMap[$sOutputBlockName][$sOutputName])) {
					$this->aDependenciesMap[$sOutputBlockName][$sOutputName] = [];
				}

				// add to map
				$this->aDependenciesMap[$sOutputBlockName][$sOutputName][] = $oBinding;

			}
		}

	}

	/**
	 * @return array
	 */
	public function GetListenedBlockNames(): array
	{
		return array_keys($this->aDependenciesMap);
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return bool
	 */
	public function IsBlockHasOutputs(string $sBlockName): bool
	{
		return array_key_exists($sBlockName, $this->aDependenciesMap);
	}

	/**
	 * @param string $sBlockName
	 *
	 * @return array
	 */
	public function GetOutputsForBlock(string $sBlockName): array
	{
		return array_keys($this->aDependenciesMap[$sBlockName]);
	}

	public function GetOutputsDependenciesForBlock(string $sOutputBlockName): array
	{
		return $this->aDependenciesMap[$sOutputBlockName];
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
}