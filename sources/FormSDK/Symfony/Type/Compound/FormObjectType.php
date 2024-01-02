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
 * Type representing an iTop object;
 *
 * @package FormSDK
 * @since 3.2.0
 */
class FormObjectType extends AbstractType
{

	/*
	 * View Definition.
	 *
	 * [
	 *      'row' => [
	 *          'col' => [
	 *              'description' => [
	 *                  'label' => '',
	 *                  'css_classes' => ''
	 *               ],
	 *              'items' => ['name', 'birthday']
	 *              'fieldset' => ['address', 'city', 'country']
	 *          ]
	 *      ]
	 * ]
	 *
	 *
	 */

	/** @inheritdoc  */
	public function buildForm(FormBuilderInterface $builder, array $options) : void
	{
		foreach ($options['view'] as $oItem) {

			if($oItem === 'row'){
				$this->handleRow();
			}
			else if($oItem === 'col'){
				$this->handleColumn();
			}
			else{

			}

		}

		foreach ($options['fields'] as $oField){
			$builder->add($oField['name'], $oField['type'], $oField['options']);
		}
	}

	private function handleRow(FormBuilderInterface $builder, array $aData){

	}

	private function handleColumn(FormBuilderInterface $builder, array $aData){

	}

	/** @inheritdoc  */
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'fields' => [],
			'view' => [],
			'attr' => [
				'class' => ''
			]
		]);
	}

	/** @inheritdoc  */
	public function getParent(): string
	{
		return FormType::class;
	}

}