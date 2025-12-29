<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ChoiceType form type.
 *
 * @package Combodo\iTop\Forms\FormType\Base
 * @since 3.3.0
 */
class ChoiceFormType extends AbstractType
{
	private bool $bErrorAdded = false;

	/** @inheritdoc */
	public function getParent(): string
	{
		return ChoiceType::class;
	}

	/** @inheritdoc */
	public function configureOptions(OptionsResolver $resolver): void
	{
		parent::configureOptions($resolver);

		// options to control the inline display of choices
		$resolver->setDefault('inline_display', true);
		$resolver->setDefault('max_items_selected', '');
		$resolver->setDefault('disable_auto_complete', true);
	}

	/** @inheritdoc */
	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		parent::buildView($view, $form, $options);

		// pass options to the view
		$view->vars['inline_display'] = $options['inline_display'];
		$view->vars['max_items_selected'] = $options['max_items_selected'];
		$view->vars['disable_auto_complete'] = $options['disable_auto_complete'];
	}

	/** @inheritdoc  */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		// on preset data
		$builder->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $oEvent) use ($options) {
			$this->InitializeValue($oEvent, $options);

			// reset value if not in available choices
			if (!empty($oEvent->getData()) && !$this->CheckValue($oEvent->getData(), $options)) {
				if (!$this->bErrorAdded) {
					$oEvent->getForm()->addError(new FormError("The value has been reset because it is not part of the available choices anymore."));
				}
				$oEvent->setData(null);
				$this->bErrorAdded = true;
			}
		});

		// on pre submit (prior)
		$builder->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $oEvent) use ($options) {

			// reset value if not in available choices
			if (!empty($oEvent->getData()) && !$this->CheckValue($oEvent->getData(), $options)) {
				$oEvent->getForm()->addError(new FormError("The value has been reset because it is not part of the available choices anymore."));
				$oEvent->setData(null);
			}

		}, 1); // priority 1 to be executed before the default validation (priority 0)

		// on pre submit
		$builder->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $oEvent) use ($options) {
			$this->InitializeValue($oEvent, $options);
		});

	}

	/**
	 * Initialize the value of the choice field.
	 *
	 * @param PreSetDataEvent|PreSubmitEvent $oEvent
	 * @param array $options
	 *
	 * @return void
	 */
	private function InitializeValue(PreSetDataEvent|PreSubmitEvent $oEvent, array $options): void
	{
		if ($options['multiple'] === false && $options['required'] === true) {
			if ($oEvent->getData() === null) {
				$oFirstElement = array_shift($options['choices']);
				if ($oFirstElement !== null) {
					$oEvent->setData(strval($oFirstElement));
				}
			}
		}
	}

	/**
	 * Check if the value(s) are part of the available choices.
	 *
	 * @param $oValue
	 * @param $options
	 *
	 * @return bool
	 */
	private function CheckValue($oValue, $options): bool
	{
		// Check multi selection values
		if ($options['multiple'] === true) {
			foreach ($oValue as $v) {
				if (!in_array($v, $options['choices'])) {
					return false;
				}
			}
		} // Check single selection values
		else {
			if (!in_array($oValue, $options['choices'])) {
				return false;
			}
		}

		return true;
	}

}
