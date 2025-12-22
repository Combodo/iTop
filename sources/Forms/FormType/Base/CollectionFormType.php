<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormType\Base;

use Dict;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Collection form type.
 *
 * @package Combodo\iTop\Forms\FormType\Base
 * @since 3.3.0
 */
class CollectionFormType extends AbstractType
{
	/** @inheritdoc */
	public function getParent(): string
	{
		return CollectionType::class;
	}

	/** @inheritdoc  */
	public function configureOptions(OptionsResolver $resolver): void
	{
		parent::configureOptions($resolver);

		$resolver->setDefaults([
			'button_label' => Dict::S('UI:Links:Add:Button'),
			'allow_ordering' => false,
		]);
	}

	/** @inheritdoc  */
	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		parent::buildView($view, $form, $options);
		if (\utils::IsNotNullOrEmptyString($options['button_label'])) {
			$view->vars['button_label'] = $options['button_label'];
		} else {
			$view->vars['button_label'] = Dict::S('UI:Links:Add:Button');
		}
		$view->vars['allow_ordering'] = $options['allow_ordering'];
	}

}
