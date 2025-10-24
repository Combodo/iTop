<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Combodo\iTop\Forms\Block\FormBlock;
use Combodo\iTop\Forms\Block\IO\FormInput;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Dependencies handler.
 *
 */
class DependencyHandler
{
	/** @var FormBuilder form builder */
	private FormBuilder $oFormBuilder;

	/** @var array dependant blocks */
	private array $aDependentBlocks;

	/** @var DependencyMap dependencies map */
	private DependencyMap $aDependenciesMap;

	/**
	 * Constructor.
	 *
	 * @param FormBuilder $oFormBuilder
	 * @param array $aDependentBlocks
	 */
	public function __construct(FormBuilder $oFormBuilder, array $aDependentBlocks)
	{
		$this->aDependentBlocks = $aDependentBlocks;
		$this->oFormBuilder = $oFormBuilder;

		// add form ready listener
		$this->AddFormReadyListener();
	}

	/**
	 * Add form ready listener.
	 *
	 * Listen the form PRE_SET_DATA
	 * First event from Symfony framework, we know that the form is built at this step.
	 *
	 * @return void
	 */
	private function AddFormReadyListener(): void
	{
		// Initialize the dependencies listeners once the form is built
		$this->oFormBuilder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

			// dependencies map
			$this->aDependenciesMap = new DependencyMap($this->aDependentBlocks);

			/** Iterate throw listened blocks */
			foreach ($this->aDependenciesMap->GetListenedBlockNames() as $sOutputBlockName) {

				// Listen the output block POST_SET_DATA & POST_SUBMIT
				$this->oFormBuilder->get($sOutputBlockName)->addEventListener(FormEvents::POST_SET_DATA, $this->GetEventListeningCallback());
				$this->oFormBuilder->get($sOutputBlockName)->addEventListener(FormEvents::POST_SUBMIT, $this->GetEventListeningCallback());
			}
		});
	}


	/**
	 * Get the listening callback.
	 *
	 * @return callable
	 */
	private function GetEventListeningCallback(): callable
	{
		return function (FormEvent $oEvent) {

			// Get the event type
			$sEventType = FormHelper::GetEventType($oEvent);

			// Get the form
			$oForm = $oEvent->getForm();

			/** Iterate throw dependencies map... */
			$sOutputBlockName = $oForm->getName();
			if($this->aDependenciesMap->IsBlockHasOutputs($sOutputBlockName)){
				$oOutputBlock = $this->oFormBuilder->GetFormBlock($sOutputBlockName);
				foreach ($this->aDependenciesMap->GetOutputsForBlock($sOutputBlockName) as $sOutputName) {
					$oOutput = $oOutputBlock->GetOutput($sOutputName);
					$oOutput->UpdateOutputValue($oEvent->getData(), $sEventType);
				}
			}



			foreach ($this->aDependentBlocks as $oDependentBlock)
			{

				// When dependencies met, add the dependent field if not already done
				if(!$oDependentBlock->IsAdded() && $oDependentBlock->IsInputsReady($sEventType)) {

					// Get the dependent field options
					$aOptions = $oDependentBlock->GetOptions();

					// Add the listener callback to the dependent field if it is also a dependency for another field
					if($this->aDependenciesMap->IsTheBlockInDependencies($oDependentBlock->getName())) {
						$aOptions = array_merge($aOptions, [
							'listener_callback' => $this->GetEventListeningCallback(),
						]);
					}

					// Mark the dependency as added
					$oDependentBlock->SetAdded(true);

					// Add the dependent field to the form
					$oForm->getParent()->add($oDependentBlock->GetName(), $oDependentBlock->GetFormType(), $aOptions);


				}

			}


		};

	}


}