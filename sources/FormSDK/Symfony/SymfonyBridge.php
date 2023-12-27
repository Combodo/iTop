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

namespace Combodo\iTop\FormSDK\Symfony;

use Combodo\iTop\FormSDK\Symfony\Type\Compound\FormObjectType;
use Combodo\iTop\FormSDK\Field\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration;
use LogAPI;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Symfony implementation bridge.
 *
 * @package FormSDK
 * @since 3.2.0
 */
class SymfonyBridge
{
	/**
	 * Constructor.
	 *
	 * @param \Symfony\Component\Form\FormFactoryInterface $oFormFactory
	 */
	public function __construct(
		protected FormFactoryInterface $oFormFactory,
	)
	{
	}

	/**
	 * Transform description to Symfony description.
	 *
	 * @param \Combodo\iTop\FormSDK\Field\FormFieldDescription $oFormDescription
	 *
	 * @return array|null
	 */
	public function ToSymfonyFormType(FormFieldDescription $oFormDescription) : ?array
	{
		switch($oFormDescription->GetType()){

			case FormFieldTypeEnumeration::TEXT:
				return [
					'name' => $oFormDescription->GetName(),
					'type' => TextType::class,
					'options' => $oFormDescription->GetOptions()
				];

			case FormFieldTypeEnumeration::AREA:
				return [
					'name' => $oFormDescription->GetName(),
					'type' => TextareaType::class,
					'options' => $oFormDescription->GetOptions()
				];

			case FormFieldTypeEnumeration::DATE:
				return [
					'name' => $oFormDescription->GetName(),
					'type' => DateType::class,
					'options' => $oFormDescription->GetOptions()
				];

			case FormFieldTypeEnumeration::SELECT:
				return [
					'name' => $oFormDescription->GetName(),
					'type' => ChoiceType::class,
					'options' => $oFormDescription->GetOptions()
				];

			case FormFieldTypeEnumeration::SWITCH:
				return [
					'name' => $oFormDescription->GetName(),
					'type' => CheckboxType::class,
					'options' => $oFormDescription->GetOptions()
				];

			case FormFieldTypeEnumeration::DB_OBJECT:
				$aOptions = $oFormDescription->GetOptions();
				$aItems = [];
				foreach ($aOptions['descriptions'] as $oChildFormDescription){
					$aSymfony = $this->ToSymfonyFormType($oChildFormDescription);
					$aItems[] = $aSymfony;
				}
				$aOptions['descriptions'] = $aItems;
				return [
					'name' => $oFormDescription->GetName(),
					'type' => FormObjectType::class,
					'options' => $aOptions
				];

			default:
				return null;
		}
	}

	/**
	 * Return Symfony form.
	 *
	 * @param array $aDescriptions
	 * @param array $aData
	 * @param string|null $sName
	 *
	 * @return \Symfony\Component\Form\FormInterface
	 */
	public function GetForm(array $aDescriptions, array $aData, ?string $sName = null): FormInterface
	{
		// create Symfony form builder
		if($sName !== null){
			$oFormBuilder = $this->oFormFactory->createNamedBuilder($sName, FormType::class, $aData);
		}
		else{
			$oFormBuilder = $this->oFormFactory->createBuilder(FormType::class, $aData);
		}

		// iterate throw descriptions...
		foreach ($aDescriptions as $oFormDescription){

			// symfony form type description
			$aSymfony = $this->ToSymfonyFormType($oFormDescription);

			// add type to form
			$oFormBuilder->add(
				$aSymfony['name'],
				$aSymfony['type'],
				$aSymfony['options']
			);

			/**
			 * Allow choices to be loaded client side via ajax.
			*  without this, field value needs to be part of initial choices that may be empty.
			 * Need reflexion because, value can be hacked with invalid value without extra validation.
			 * @see https://symfony.com/doc/current/reference/forms/types/choice.html#choice-loader
			 * @see https://itecnote.com/tecnote/php-disable-backend-validation-for-choice-field-in-symfony-2-type/
			*/
			if($aSymfony['type'] === ChoiceType::class){
				$oFormBuilder->get($aSymfony['name'])->resetViewTransformers();
			}

		}

		return $oFormBuilder->getForm();
	}

}