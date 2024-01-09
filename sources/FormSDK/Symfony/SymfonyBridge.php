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

use Combodo\iTop\FormSDK\Field\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration;
use Combodo\iTop\FormSDK\Symfony\Type\Compound\FieldsetType;
use Combodo\iTop\FormSDK\Symfony\Type\Layout\ColumnType;
use Combodo\iTop\FormSDK\Symfony\Type\Layout\RowType;
use LogAPI;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

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

			case FormFieldTypeEnumeration::FIELDSET:
				$aOptions = $oFormDescription->GetOptions();
				$aFields = [];
				foreach ($aOptions['fields'] as $oChildFormDescription){
					$aSymfony = $this->ToSymfonyFormType($oChildFormDescription);
					$aFields[$oChildFormDescription->GetName()] = $aSymfony;
				}
				$aOptions['fields'] = $aFields;
				return [
					'name' => $oFormDescription->GetName(),
					'type' => FieldsetType::class,
					'options' => $aOptions
				];

			case FormFieldTypeEnumeration::NUMBER:
				return [
					'name' => $oFormDescription->GetName(),
					'type' => NumberType::class,
					'options' => $oFormDescription->GetOptions()
				];

			case FormFieldTypeEnumeration::DURATION:
				return [
					'name' => $oFormDescription->GetName(),
					'type' => DateIntervalType::class,
					'options' => $oFormDescription->GetOptions()
				];

			default:
				return null;
		}
	}

	/**
	 * Create Symfony form.
	 *
	 * @param array $aDescriptions
	 * @param mixed $oData
	 * @param string|null $sName
	 * @param array|null $aLayout
	 *
	 * @return \Symfony\Component\Form\FormInterface
	 */
	public function CreateForm(array $aDescriptions, mixed $oData, ?string $sName = null, array $aLayout = []): FormInterface
	{
		// create Symfony form builder
		if($sName !== null){
			$oFormBuilder = $this->oFormFactory->createNamedBuilder($sName, FormType::class, $oData);
		}
		else{
			$oFormBuilder = $this->oFormFactory->createBuilder(FormType::class, $oData);
		}

		// transform fields descriptions...
		$aSymfonyTypesDeclaration = [];
		foreach ($aDescriptions as $sKey => $oFormDescription){
			$aSymfonyTypesDeclaration[$sKey] = $this->ToSymfonyFormType($oFormDescription);
		}

		// prepare fieldset types layout...
		foreach ($aSymfonyTypesDeclaration as &$aSymfonyTypeDeclaration){
			if($aSymfonyTypeDeclaration['type'] === FieldsetType::class && isset($aSymfonyTypeDeclaration['options']['layout'])){
				['types' => $aItems]  = $this->CreateLayoutTypes($aSymfonyTypeDeclaration['options']['layout'], $oFormBuilder, $aSymfonyTypeDeclaration['options']['fields']);
				$aSymfonyTypeDeclaration['options']['fields'] = array_merge($aItems, $aSymfonyTypeDeclaration['options']['fields']);
				$aTest = 'test';
			}
		}

		// prepare general layout types
		['types' => $aItems]  = $this->CreateLayoutTypes($aLayout, $oFormBuilder, $aSymfonyTypesDeclaration);
		$aSymfonyTypesDeclaration = array_merge($aItems, $aSymfonyTypesDeclaration);

		// add symfony types to builder...
		foreach ($aSymfonyTypesDeclaration as $oSymfonyTypeDeclaration){

			// add type to form
			$oFormBuilder->add(
				$oSymfonyTypeDeclaration['name'],
				$oSymfonyTypeDeclaration['type'],
				$oSymfonyTypeDeclaration['options']
			);

			/**
			 * Allow choices to be loaded client side via ajax.
			 *  without this, field value needs to be part of initial choices that may be empty.
			 * Need reflexion because, value can be hacked with invalid value without validation.
			 * @see https://symfony.com/doc/current/reference/forms/types/choice.html#choice-loader
			 * @see https://itecnote.com/tecnote/php-disable-backend-validation-for-choice-field-in-symfony-2-type/
			 */
			if($oSymfonyTypeDeclaration['type'] === ChoiceType::class){
				$oFormBuilder->get($oSymfonyTypeDeclaration['name'])->resetViewTransformers();
			}

		}

		return $oFormBuilder->getForm();
	}

	/**
	 * @param $aLayout
	 * @param $oFormBuilder
	 * @param array $aDescriptions
	 *
	 * @return array
	 */
	private function CreateLayoutTypes($aLayout, $oFormBuilder, array &$aDescriptions){

		$aResult = [];
		$sClasses = '';

		foreach ($aLayout as $sKey => $oLayoutElement)
		{
			if($sKey === 'css_classes'){
				$sClasses = $oLayoutElement;
			}
			else if(str_starts_with($sKey, 'row__')){
				$aResult[$sKey] = $this->CreateLayoutContainerType($oLayoutElement, $oFormBuilder, $sKey, RowType::class, $aDescriptions);
			}
			else if(str_starts_with($sKey, 'column__')){
				$aResult[$sKey] = $this->CreateLayoutContainerType($oLayoutElement, $oFormBuilder, $sKey, ColumnType::class, $aDescriptions);
			}
			else if(str_starts_with($sKey, 'fieldset__')){
				$aResult[$sKey] = $this->CreateLayoutContainerType($oLayoutElement, $oFormBuilder, $sKey, FieldsetType::class, $aDescriptions);
			}
			else {
				if (array_key_exists($oLayoutElement, $aDescriptions)) {
					$aResult[$oLayoutElement] = $aDescriptions[$oLayoutElement];
					unset($aDescriptions[$oLayoutElement]);
				}
			}
		}

		return [
			'types' => $aResult,
			'css_classes' => $sClasses
		];
	}

	private function CreateLayoutContainerType($oLayoutElement, $oFormBuilder, $sKey, $oType, &$aDescriptions)
	{
		['types' => $aItems, 'css_classes' => $sCssClasses] = $this->CreateLayoutTypes($oLayoutElement, $oFormBuilder, $aDescriptions);

		return [
			'name' => $sKey,
			'type' => $oType,
			'options' => [
				'fields' => $aItems,
				'attr' => [
					'class' => $sCssClasses
				],
				'inherit_data' => true
			],
		];
	}


}