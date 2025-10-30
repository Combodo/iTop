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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Extension for form types.
 *
 */
class FormTypeExtension extends AbstractTypeExtension
{

	/** @inheritdoc */
	public static function getExtendedTypes(): iterable
	{
		return [
			FormType::class,
		];
	}

	/** @inheritdoc */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefined([
			'form_block',
			'builder_listener',
			'prevent_form_build',
		]);
	}

	/** @inheritdoc */
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		if (array_key_exists('builder_listener', $options)) {
			$builder->addEventListener(FormEvents::POST_SET_DATA, $options['builder_listener']);
			$builder->addEventListener(FormEvents::POST_SUBMIT, $options['builder_listener']);
		}

	}

	/** @inheritdoc */
	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		if (array_key_exists('form_block', $options)) {
			$view->vars['form_block'] = $options['form_block'];

			$oFormBlock = $options['form_block'];
			$view->vars['trigger_form_submit_on_modify'] = $oFormBlock->ImpactDependentsBlocks();
		}

	}

}