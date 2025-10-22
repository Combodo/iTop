<?php

namespace Combodo\iTop\Forms\Dependency;

use Exception;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class DependencyHandler
{
	/** @var array dépendencies descriptions stored on builder add */
	private array $aDependenciesDescription = [];

	/** @var array dependencies map computed on form pre set data */
	private array $aDependenciesMap = [];

	/**
	 * Constructor.
	 *
	 * @param FormBuilderInterface $builder
	 */
	public function __construct(public FormBuilderInterface $builder)
	{
		// Initialize the dependencies listeners once the form is built
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
			$oForm = $event->getForm();
			$this->InitializeDependenciesMap($oForm);
		});
	}

	/**
	 *Initialize the dependencies map and register listeners on the dependencies inputs.
	 *
	 * @param FormInterface $oForm
	 *
	 * @return void
	 */
	private function InitializeDependenciesMap(FormInterface $oForm): void
	{

		/** iterate throw dependencies descriptions... @var DependencyDescription $oDependencyDescription */
		foreach ($this->aDependenciesDescription as $oDependencyDescription) {

			/** iterate throw dependencies items... */
			foreach ($oDependencyDescription->aDependencies as $sInput => $aData) {

				// get the dependency field name
				$sDependency = $aData['source'];

				// get the field input name
				$sOutput = array_key_exists('output', $aData) ? $aData['output'] : null;

				// add the dependency to the map
				if(!array_key_exists($sDependency, $this->aDependenciesMap)){
					$this->aDependenciesMap[$sDependency] = [];
				}
				$this->aDependenciesMap[$sDependency][] = ['description' => $oDependencyDescription, 'input' => $sInput, 'output' => $sOutput];
			}
		}

		/** iterate throw dependencies... */
		foreach (array_keys($this->aDependenciesMap) as $sDependency){

			// Listen the dependency
			$this->builder->get($sDependency)->addEventListener(FormEvents::POST_SET_DATA, $this->GetEventListeningCallback());
			$this->builder->get($sDependency)->addEventListener(FormEvents::POST_SUBMIT, $this->GetEventListeningCallback());
		}

	}

	/**
	 * Add a dependency description.
	 *
	 * @param DependencyDescription $oDependencyDescription
	 *
	 * @return void
	 */
	public function AddDependencyDescription(DependencyDescription $oDependencyDescription): void
	{
		$this->aDependenciesDescription[] = $oDependencyDescription;
	}

	/**
	 * The event handling callback.
	 *
	 * @return callable
	 */
	private function GetEventListeningCallback(): callable
	{
		return function (FormEvent $event){

			// Get the event type
			$sEventType = $this->GetEventType($event);

			// Get the form
			$oForm = $event->getForm();

			/** Iterate throw dependencies map... */
			foreach ($this->aDependenciesMap[$event->getForm()->getName()] as $aData){

				// Get the map data
				$oDependencyDescription = $aData['description'];
				$sInput = $aData['input'];
				$sOutput = $aData['output'];

				// Compute output value
				$oValue = $event->getData();
				if(array_key_exists('outputs', $event->getForm()->getConfig()->getOptions())){
					$aOutputs = $event->getForm()->getConfig()->getOptions()['outputs'];
					if(array_key_exists($sOutput, $aOutputs)){
						$oValue = $aOutputs[$sOutput]($oValue);
					}
				}

				// Store the input value
				$oDependencyDescription->SetData($sEventType, $sInput, $oValue);

				// When dependencies met, add the dependent field if not already done
				if(!$oDependencyDescription->IsAdded() && $oDependencyDescription->IsDataReady($sEventType)) {

					// Get the dependent field options
					$aOptions = $oDependencyDescription->options;

					// Add the listener callback to the dependent field if it is also a dependency for another field
					if(is_string($oDependencyDescription->child) && array_key_exists($oDependencyDescription->child, $this->aDependenciesMap)) {
						$aOptions = array_merge($aOptions, [
							'listener_callback' => $this->GetEventListeningCallback(),
						]);
					}

					// Add the dependent field to the form
					$oForm->getParent()->add($oDependencyDescription->child, $oDependencyDescription->type, array_merge($aOptions, $oDependencyDescription->type::GetOptionsFromInputs($oDependencyDescription->GetData($sEventType))));

					// Mark the dependency as added
					$oDependencyDescription->SetAdded(true);
				}
			}

		};

	}

	/**
	 * Get the event type.
	 *
	 * @param FormEvent $event
	 *
	 * @return string
	 * @throws Exception
	 */
	private function GetEventType(FormEvent $event): string
	{
		if($event instanceof PostSetDataEvent) {
			return FormEvents::POST_SET_DATA;
		}
		else if($event instanceof PostSubmitEvent) {
			return FormEvents::POST_SUBMIT;
		}

		throw new Exception(sprintf("Unknown event type %s", get_class($event)));
	}






}