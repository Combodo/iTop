<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class DependencyHandler
{
	private array $aDependentBlocks = [];
	private FormBuilder $oFormBuilder;
	private array $aDependenciesMap = [];

	/**
	 * @param \Combodo\iTop\Forms\FormBuilder\FormBuilder $oFormBuilder
	 * @param array $aDependentBlocks
	 */
	public function __construct(FormBuilder $oFormBuilder, array $aDependentBlocks)
	{
		$this->aDependentBlocks = $aDependentBlocks;
		$this->oFormBuilder = $oFormBuilder;

		// Initialize the dependencies listeners once the form is built
		$oFormBuilder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
			$oForm = $event->getForm();
			$this->InitializeDependenciesMap($oForm);
		});
	}

	private function InitializeDependenciesMap(FormInterface $oForm)
	{
		// Connections from output pov
		$aDependenciesMap = [];

		/** @var \Combodo\iTop\Forms\Block\FormBlock $oDependentBlock */
		foreach ($this->aDependentBlocks as $oDependentBlock) {
			foreach ($oDependentBlock->GetConnections() as $sInputName => $aConnections) {
				foreach ($aConnections as $aConnection) {
					$sOutputBlockName = $aConnection['output_block'];
					$sOutputName = $aConnection['output'];

					if (!isset($aDependenciesMap[$sOutputBlockName])) {
						$aDependenciesMap[$sOutputBlockName] = [];
					}
					if (!isset($aDependenciesMap[$sOutputBlockName][$sOutputName])) {
						$aDependenciesMap[$sOutputBlockName][$sOutputName] = [];
					}

					$aDependenciesMap[$sOutputBlockName][$sOutputName][] = ['input_block' => $oDependentBlock, 'input_name' => $sInputName];
				}
			}
		}

		$this->aDependenciesMap = $aDependenciesMap;

		foreach (array_keys($aDependenciesMap) as $sOutputBlockName) {

			// Listen the dependency
			$this->oFormBuilder->get($sOutputBlockName)->addEventListener(FormEvents::POST_SET_DATA, $this->GetEventListeningCallback());
			$this->oFormBuilder->get($sOutputBlockName)->addEventListener(FormEvents::POST_SUBMIT, $this->GetEventListeningCallback());
		}

	}

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
	 * @throws \Combodo\iTop\Forms\FormBuilder\FormBuilderException
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