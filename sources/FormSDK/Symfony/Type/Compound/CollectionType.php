<?php
/*
 * Copyright (C) 2013-2023 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\FormSDK\Symfony\Type\Compound;

use Combodo\iTop\FormSDK\Field\FormFieldDescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Type representing a collection;
 *
 * @package FormSDK
 * @since 3.2.0
 */
class CollectionType extends AbstractType
{

	/** @inheritdoc  */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'fields_labels' => [],
		]);
	}

	/** @inheritdoc  */
	public function getParent(): string
	{
		return \Symfony\Component\Form\Extension\Core\Type\CollectionType::class;
	}

	/** @inheritdoc  */
	public function buildView(FormView $view, FormInterface $form, array $options): void
	{
		// pass the form type option directly to the template
		$view->vars['fields_labels'] = $options['fields_labels'];
	}

	/** @inheritdoc  */
	public function getBlockPrefix()
	{
		return 'itop_collection';
	}
}