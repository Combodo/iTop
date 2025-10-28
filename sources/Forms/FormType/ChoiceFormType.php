<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;

/**
 *
 */
class ChoiceFormType extends AbstractType
{
	/** @inheritdoc  */
	public function getParent(): string
	{
		return ChoiceType::class;
	}

	/** @inheritdoc  */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		// on pre submit
		$builder->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event) use ($options){

			// reset value if not in available choices
			if(!empty($event->getData()) && !$this->CheckValue($event->getData(), $options)){
				$event->getForm()->addError(new FormError("The value has been reset because it is not part of the available choices anymore."));
				$event->setData(null);
			}

		}, 1);
	}

	/**
	 * @param $oValue
	 * @param $options
	 *
	 * @return bool
	 */
	private function CheckValue($oValue, $options): bool
	{
		// Check multi selection values
		if(is_array($oValue)){
			foreach ($oValue as $v){
				if(!in_array($v, $options['choices'])){
					return false;
				}
			}
		}
		// Check single selection values
		else{
			if(!in_array($oValue, $options['choices'])){
				return false;
			}
		}

		return true;
	}



}