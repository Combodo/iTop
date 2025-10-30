<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Block\Base\FormBlock;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
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

	/** @var array Debug data */
	private array $aDebugData = [];
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

		// Check the dependencies
		$this->CheckDependencies($this->oFormBuilder);

		// Store the dependency handler
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
			foreach ($this->oDependenciesMap->GetListenedOutputBlockNames() as $sOutputBlockName) {

				// inner binding
				if ($sOutputBlockName === $this->oFormBlock->getName()) {
					continue;
				}

				$this->aDebugData[] = [
					'builder' => $this->oFormBuilder->getName(),
					'event'   => 'form.listen',
					'form'    => $sOutputBlockName,
					'value'   => 'NA',
				];

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

			$this->aDebugData[] = [
				'builder' => $this->oFormBuilder->getName(),
				'event'   => $sEventType,
				'form'    => $oEvent->getForm()->getName(),
				'value'   => $oEvent->getData(),
			];

			// Get the form
			$oForm = $oEvent->getForm();

			// Get the form block
			$oFormBlock = $this->aSubBlocks[$oForm->getName()];

			// Compute the block outputs with the data
			if (!$oFormBlock instanceof FormBlock) {
				$oFormBlock->ComputeOutputs($sEventType, $oEvent->getData());
			}

			// Check dependencies
			$this->CheckDependencies($oForm->getParent());
		};

	}


	/**
	 * @param FormInterface|FormBuilderInterface $oForm
	 *
	 * @return void
	 */
	private function CheckDependencies(FormInterface|FormBuilderInterface $oForm): void
	{
		/** Iterate throw dependencies... @var AbstractFormBlock $oDependentBlock */
		foreach ($this->aDependentBlocks as $qBlockName => $oDependentBlock) {
			// When dependencies met, add the dependent field if not already done
			if (!$oDependentBlock->IsAdded() && $oDependentBlock->IsInputsDataReady()) {

				// Get the dependent field options
				$aOptions = $oDependentBlock->UpdateOptions();

				// Add the listener callback to the dependent field if it is also a dependency for another field
				if ($this->oDependenciesMap->IsTheBlockInDependencies($oDependentBlock->getName())) {

					// Pass the listener call back to be registered by the dependency form builder
					$aOptions = array_merge($aOptions, [
						'builder_listener' => $this->GetEventListeningCallback(),
					]);
				}

				if ($oDependentBlock->AllowAdd()) {

					$this->aDebugData[] = [
						'builder' => $this->oFormBuilder->getName(),
						'event'   => 'form.add',
						'form'    => $oDependentBlock->getName(),
						'value'   => 'NA',
					];

					if (array_key_exists('builder_listener', $aOptions)) {
						$this->aDebugData[] = [
							'builder' => $this->oFormBuilder->getName(),
							'event'   => 'form.listen',
							'form'    => $oDependentBlock->getName(),
							'value'   => 'NA',
						];
					}

					// Mark the dependency as added
					$oDependentBlock->SetAdded(true);

					// Add the dependent field to the form
					$oForm->add($oDependentBlock->GetName(), $oDependentBlock->GetFormType(), $aOptions);

				}

			}

			if ($oDependentBlock->IsAdded() && !$oDependentBlock->IsInputsDataReady()) {
				$oForm->add($oDependentBlock->GetName(), HiddenType::class, [
					'form_block'         => $oDependentBlock,
					'prevent_form_build' => true,
				]);
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
		return $this->aDebugData;
	}

	public function GetMap(): DependencyMap
	{
		return $this->oDependenciesMap;
	}

	public function GetSubBlocks(): array
	{
		return $this->aSubBlocks;
	}

	public function GetName(): string
	{
		return $this->sName;
	}
}