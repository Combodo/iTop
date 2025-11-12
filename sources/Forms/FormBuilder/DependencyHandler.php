<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Exception;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Dependencies handler.
 *
 */
class DependencyHandler
{
	public static array $aDependencyHandlers = [];

	/** @var DependencyMap dependencies map */
	private DependencyMap $oDependenciesMap;

	/** @var array events */
	private array $aEvents = [];
	private readonly string $sName;
	private readonly AbstractFormBlock $oFormBlock;
	private readonly FormBuilder $oFormBuilder;
	private readonly array $aSubBlocks;
	private readonly array $aDependentBlocks;

	/**
	 * Constructor.
	 *
	 * @param string $sName The name
	 * @param AbstractFormBlock $oFormBlock The attached form block
	 * @param FormBuilder $oFormBuilder The form builder
	 * @param array $aSubBlocks Sub blocks
	 * @param array $aDependentBlocks Dependants blocks
	 */
	public function __construct(string $sName, AbstractFormBlock $oFormBlock, FormBuilder $oFormBuilder, array $aSubBlocks, array $aDependentBlocks)
	{
		$this->aDependentBlocks = $aDependentBlocks;
		$this->aSubBlocks = $aSubBlocks;
		$this->oFormBuilder = $oFormBuilder;
		$this->oFormBlock = $oFormBlock;
		$this->sName = $sName;
		// dependencies map
		$this->oDependenciesMap = new DependencyMap($aDependentBlocks);

		// Add form ready listener
		$this->AddFormReadyListener();

		// Check the dependencies (handle internal binding)
		$this->CheckDependencies($this->oFormBuilder);

		// Store the dependency handler (debug purpose)
		self::$aDependencyHandlers[] = $this;
	}

	/**
	 * Return the form block.
	 *
	 * @return AbstractFormBlock
	 */
	public function GetFormBlock(): AbstractFormBlock
	{
		return $this->oFormBlock;
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

			/** Iterate throw listened blocks */
			foreach ($this->oDependenciesMap->GetInitialBoundOutputBlockNames() as $sOutputBlockName) {

				// inner binding
				if ($sOutputBlockName === $this->oFormBlock->getName()) {
					continue;
				}

				// Add event
				$this->AddEvent('form.listen', $sOutputBlockName);

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

			// Add event
			$this->AddEvent($sEventType, $oEvent->getForm()->getName(), $oEvent->getData());

			// Get the form
			$oForm = $oEvent->getForm();

			// Get the form block
			$oFormBlock = $this->aSubBlocks[$oForm->getName()];

			// Compute the block outputs with the data
			try{
				$oFormBlock->ComputeOutputs($sEventType, $oForm->getData());
			}
			catch(Exception $e){
				$oForm->addError(new FormError($e->getMessage()));
			}

			// Check dependencies
			$this->CheckDependencies($oForm->getParent(), $oForm->getName(), $sEventType);
		};

	}


	/**
	 * @param FormInterface|FormBuilderInterface $oForm
	 * @param string|null $sOutputBlock
	 * @param string|null $sEventType
	 *
	 * @return void
	 */
	private function CheckDependencies(FormInterface|FormBuilderInterface $oForm, string $sOutputBlock = null, string $sEventType = null): void
	{
		$aImpactedBlocks = $this->aDependentBlocks;
		if($sOutputBlock !== null){
			$aImpactedBlocks = $this->oDependenciesMap->GetBlocksDependingOn($sOutputBlock);
		}

		if($aImpactedBlocks === null){
			return;
		}

		/** Iterate throw dependencies... @var AbstractFormBlock $oDependentBlock */
		foreach ($aImpactedBlocks as $oDependentBlock) {
			if (!$oDependentBlock instanceof AbstractTypeFormBlock) {
				continue;
			}

			// When dependencies met, add the dependent field if not already done or options changed
			if ($oDependentBlock->IsVisible($sEventType) && $oDependentBlock->IsInputsDataReady($sEventType)) {

				// Get the dependent field options
				$aBefore = $oDependentBlock->GetOptionsMergedWithDynamic();
				$oDependentBlock->UpdateDynamicOptions($sEventType);
				$aOptions = $oDependentBlock->GetOptionsMergedWithDynamic($sEventType);

				// Options changed flag
				$bOptionsChanged = FormHelper::CompareArrayValues($aBefore, $aOptions);

				// Add the listener callback to the dependent field if it is also a dependency for another field
				if ($this->oDependenciesMap->IsTheBlockInDependencies($oDependentBlock->getName())) {

					// Pass the listener call back to be registered by the dependency form builder
					$aOptions = array_merge($aOptions, [
						'builder_listener' => $this->GetEventListeningCallback(),
					]);
				}

				if ( (!$oDependentBlock->IsAdded() || $bOptionsChanged)  && $oDependentBlock->AllowAdd($sEventType)) {

					// Add events
					$this->AddEvent('form.add', $oDependentBlock->getName());
					if (array_key_exists('builder_listener', $aOptions)) {
						$this->AddEvent('form.listen.after', $oDependentBlock->getName());
					}

					// Mark the dependency as added
					$oDependentBlock->SetAdded(true);

					// Add the dependent field to the form
					$oForm->add($oDependentBlock->GetName(), $oDependentBlock->GetFormType(), $aOptions);

				}

			}

			if ($oDependentBlock->IsAdded() && (!$oDependentBlock->IsVisible($sEventType) || !$oDependentBlock->IsInputsDataReady($sEventType))) {
				$oForm->remove($oDependentBlock->GetName());
				$oDependentBlock->SetAdded(false);

				// Add event
				$this->AddEvent('form.remove', $oDependentBlock->getName());
			}

		}

	}

	/**
	 * Get the debug data.
	 *
	 * @return array
	 */
	public function GetDebugData(): array
	{
		return $this->aEvents;
	}

	public function GetMap(): DependencyMap
	{
		return $this->oDependenciesMap;
	}


	public function GetName(): string
	{
		return $this->sName;
	}

	private function AddEvent(string $sEvent, string $sForm, mixed $oValue = 'NA'): void
	{

		$this->aEvents[] = [
			'builder' => $this->oFormBuilder->getName(),
			'event'   => $sEvent,
			'form'    => $sForm,
			'value'   => $oValue,
		];
	}
}