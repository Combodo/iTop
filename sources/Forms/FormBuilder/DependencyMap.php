<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Combodo\iTop\Forms\Block\FormBlock;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Dependencies handler.
 *
 */
class DependencyMap
{
	/** @var array dependant blocks */
	private array $aDependentBlocks = [];

	/** @var array dependencies map */
	private array $aDependenciesMap = [];

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

	private function Init(): void
	{
		/** iterate throw dependents blocks... @var FormBlock $oDependentBlock */
		foreach ($this->aDependentBlocks as $oDependentBlock) {

			/** iterate throw the block inputs connections... **/
			foreach ($oDependentBlock->GetInputsConnections() as $sInputName => $aConnections) {

				/** Iterate throw the input connections... */
				foreach ($aConnections as $aConnection) {

					// connection information
					$sOutputBlock = $aConnection['output_block'];
					$sOutputBlockName = $sOutputBlock->getName();
					$sOutputName = $aConnection['output'];

					// initialize map
					if (!isset($this->aDependenciesMap[$sOutputBlockName])) {
						$this->aDependenciesMap[$sOutputBlockName] = [];
					}
					if (!isset($this->aDependenciesMap[$sOutputBlockName][$sOutputName])) {
						$this->aDependenciesMap[$sOutputBlockName][$sOutputName] = [];
					}

					// add to map
					$this->aDependenciesMap[$sOutputBlockName][$sOutputName][] = ['input_block' => $oDependentBlock, 'input_name' => $sInputName];
				}
			}
		}

	}

	public function GetListenedBlockNames(): array
	{
		return array_keys($this->aDependenciesMap);
	}

	public function GetOutputsForBlock(string $sBlockName): array
	{
		return array_keys($this->aDependenciesMap[$sBlockName]);
	}
}