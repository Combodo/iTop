<?php

namespace Combodo\iTop\Forms\FormBuilder;

use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class FormHelper
{
	/**
	 * Get the event type.
	 *
	 * @param FormEvent $event
	 *
	 * @return string
	 * @throws FormBuilderException
	 */
	public static function GetEventType(FormEvent $event): string
	{
		if ($event instanceof PostSetDataEvent) {
			return FormEvents::POST_SET_DATA;
		} else if ($event instanceof PostSubmitEvent) {
			return FormEvents::POST_SUBMIT;
		}
		else if ($event instanceof PreSubmitEvent) {
			return FormEvents::PRE_SUBMIT;
		}

		throw new FormBuilderException(sprintf('Unknown event type %s', get_class($event)));
	}
}