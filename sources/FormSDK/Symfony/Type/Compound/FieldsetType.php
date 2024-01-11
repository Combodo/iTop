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
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Type representing a fieldset;
 *
 * @package FormSDK
 * @since 3.X.0
 */
class FieldsetType extends AbstractType
{

	/** @inheritdoc  */
	public function buildForm(FormBuilderInterface $builder, array $options) : void
	{
		// add each types to form...
		foreach ($options['types_declarations'] as $aTypeDeclaration){
			$builder->add($aTypeDeclaration['name'], $aTypeDeclaration['type'], $aTypeDeclaration['options']);
		}
	}

	/** @inheritdoc  */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'types_declarations' => [],
			'layout' => []
		]);
	}

	/** @inheritdoc  */
	public function getParent(): string
	{
		return FormType::class;
	}

}