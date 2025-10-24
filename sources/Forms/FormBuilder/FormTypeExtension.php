<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormTypeExtension extends AbstractTypeExtension
{

	public static function getExtendedTypes(): iterable
	{
		return [
			FormType::class
		];
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefined([
			'form_block',
			'listener_callback',
		]);
	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		if(array_key_exists('listener_callback', $options)) {
			$builder->addEventListener(FormEvents::POST_SET_DATA, $options['listener_callback']);
			$builder->addEventListener(FormEvents::POST_SUBMIT, $options['listener_callback']);
		}

	}


}