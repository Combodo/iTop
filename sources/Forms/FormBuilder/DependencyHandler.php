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
class DependencyHandler
{
	/** @var FormBuilder form builder */
	private FormBuilder $oFormBuilder;

	/** @var array dependant blocks */
	private array $aDependentBlocks = [];

	/** @var array dependencies map */
	private array $aDependenciesMap = [];

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
			$oForm = $event->getForm();
			$this->InitializeDependenciesMap($oForm);
		});
	}

	/**
	 * Initialize dependencies map.
	 *
	 * @param FormInterface $oForm
	 *
	 * @return void
	 */
	private function InitializeDependenciesMap(FormInterface $oForm): void
	{
		// Connections from output pov
		$aDependenciesMap = [];

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
					if (!isset($aDependenciesMap[$sOutputBlockName])) {
						$aDependenciesMap[$sOutputBlockName] = [];
					}
					if (!isset($aDependenciesMap[$sOutputBlockName][$sOutputName])) {
						$aDependenciesMap[$sOutputBlockName][$sOutputName] = [];
					}

					// add to map
					$aDependenciesMap[$sOutputBlockName][$sOutputName][] = ['input_block' => $oDependentBlock, 'input_name' => $sInputName];
				}
			}
		}

		// store the dependencies map
		$this->aDependenciesMap = $aDependenciesMap;

		/** Iterate throw output blocks */
		foreach (array_keys($aDependenciesMap) as $sOutputBlockName) {

			// Listen the output block POST_SET_DATA & POST_SUBMIT
			$this->oFormBuilder->get($sOutputBlockName)->addEventListener(FormEvents::POST_SET_DATA, $this->GetEventListeningCallback());
			$this->oFormBuilder->get($sOutputBlockName)->addEventListener(FormEvents::POST_SUBMIT, $this->GetEventListeningCallback());
		}

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
			$sEventType = $this->GetEventType($oEvent);

			// Get the form
			$oForm = $oEvent->getForm();

			/** Iterate throw dependencies map... */
			$sOutputBlockName = $oForm->getName();
			$oOutputBlock = $this->oFormBuilder->GetFormBlock($sOutputBlockName);
			foreach (array_keys($this->aDependenciesMap[$sOutputBlockName]) as $sOutputName) {
				$oOutput = $oOutputBlock->GetOutput($sOutputName);
				$oOutput->UpdateOutputValue($oEvent->getData(), $sEventType);
			}

			return;
		};

	}

	/**
	 * Get the event type.
	 *
	 * @param FormEvent $event
	 *
	 * @return string
	 * @throws FormBuilderException
	 */
	private function GetEventType(FormEvent $event): string
	{
		if ($event instanceof PostSetDataEvent) {
			return FormEvents::POST_SET_DATA;
		} else if ($event instanceof PostSubmitEvent) {
			return FormEvents::POST_SUBMIT;
		}

		throw new FormBuilderException(sprintf('Unknown event type %s', get_class($event)));
	}
}