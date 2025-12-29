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
 * Form type extension for common initialization.
 *
 * @package Combodo\iTop\Forms\FormBuilder
 * @since 3.3.0
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
			'form_block_class',
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
			$view->vars['form_block_class'] = $options['form_block_class'];

			$oFormBlock = $options['form_block'];
			$view->vars['trigger_form_submit_on_modify'] = $oFormBlock->IsImpactingBlocks();
			$view->vars['impacted_by'] = array_keys($oFormBlock->GetImpactedBlocks());
		}
	}
}
