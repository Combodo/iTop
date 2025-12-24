<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\DataModel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * OQL expression form type.
 *
 * @package Combodo\iTop\Forms\FormType\DataModel
 * @since 3.3.0
 */
class OqlFormType extends AbstractType
{
	/** @inheritdoc  */
	public function getParent(): string
	{
		return TextareaType::class;
	}

	/** @inheritdoc  */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefined('with_ai_button');
	}

	/** @inheritdoc  */
	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		parent::buildView($view, $form, $options);

		$view->vars['with_ai_button'] = $options['with_ai_button'];
	}
}
