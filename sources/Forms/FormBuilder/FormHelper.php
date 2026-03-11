<?php

namespace Combodo\iTop\Forms\FormBuilder;

use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Form helper.
 *
 * @package Combodo\iTop\Forms\FormBuilder
 * @since 3.3.0
 */
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
		} elseif ($event instanceof PostSubmitEvent) {
			return FormEvents::POST_SUBMIT;
		} elseif ($event instanceof PreSubmitEvent) {
			return FormEvents::PRE_SUBMIT;
		}

		throw new FormBuilderException(sprintf('Unknown event type %s', get_class($event)));
	}

	public static function CompareArrayValues($mValue1, $mValue2): int
	{
		if (is_array($mValue1) && is_array($mValue2)) {
			if (count($mValue1) !== count($mValue2)) {
				return 1;
			}
			$aDiff = array_udiff_assoc($mValue1, $mValue2, [FormHelper::class, 'CompareArrayValues']);

			return count($aDiff);
		}

		if ($mValue1 === $mValue2) {
			return 0;
		}

		return 1;
	}
}
