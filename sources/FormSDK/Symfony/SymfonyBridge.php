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
use Combodo\iTop\FormSDK\Symfony\Type\Compound\TableCollectionType;
use Combodo\iTop\FormSDK\Symfony\Type\Compound\FieldsetType;
use Combodo\iTop\FormSDK\Symfony\Type\Layout\ColumnType;
use Combodo\iTop\FormSDK\Symfony\Type\Layout\RowType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Symfony implementation bridge.
 *
 * @package FormSDK
 * @since 3.X.0
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
		return [
			'name' => $oFormDescription->GetName(),
			'type' => $this->ToSymfonyFormTypeClass($oFormDescription->GetType()),
			'options' => $this->ToSymfonyFormTypeOptions($oFormDescription->GetType(), $oFormDescription->GetOptions())
		];
	}

	/**
	 * Transform type to Symfony form type class.
	 *
	 * @param \Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration $oType
	 *
	 * @return string|null
	 */
	public function ToSymfonyFormTypeClass(FormFieldTypeEnumeration $oType) : ?string
	{
		return match ($oType) {
			FormFieldTypeEnumeration::TEXT => TextType::class,
			FormFieldTypeEnumeration::AREA => TextareaType::class,
			FormFieldTypeEnumeration::DATE => DateType::class,
			FormFieldTypeEnumeration::SELECT => ChoiceType::class,
			FormFieldTypeEnumeration::SWITCH => CheckboxType::class,
			FormFieldTypeEnumeration::FIELDSET => FieldsetType::class,
			FormFieldTypeEnumeration::NUMBER => IntegerType::class,
			FormFieldTypeEnumeration::DURATION => DateIntervalType::class,
			FormFieldTypeEnumeration::COLLECTION => TableCollectionType::class,
			FormFieldTypeEnumeration::FILE => FileType::class,
			default => null,
		};
	}

	/**
	 *
	 * @param \Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration $oType
	 * @param array $aOptions
	 *
	 * @return array
	 */
	public function ToSymfonyFormTypeOptions(FormFieldTypeEnumeration $oType, array $aOptions) : array
	{
		return match ($oType) {
			FormFieldTypeEnumeration::DURATION => $this->TransformDurationOptions($aOptions),
			FormFieldTypeEnumeration::FIELDSET => $this->TransformFieldsetOptions($aOptions),
			FormFieldTypeEnumeration::COLLECTION => $this->TransformCollectionOptions($aOptions),
			default => $aOptions,
		};
	}

	/**
	 * Transform duration field options.
	 *
	 * @param array $aOptions
	 *
	 * @return array
	 */
	private function TransformDurationOptions(array $aOptions) : array
	{
		$aOptions['input'] = 'array';
//		$aOptions['widget'] = 'integer';

		return $aOptions;
	}

	/**
	 * Transform fieldset field options.
	 *
	 * @param array $aOptions
	 *
	 * @return array
	 */
	private function TransformFieldsetOptions(array $aOptions) : array
	{
		$aOptions['types_declarations'] = [];
		foreach ($aOptions['fields'] as $oChildFormDescription){
			$aSymfonyTypeDeclaration = $this->ToSymfonyFormType($oChildFormDescription);
			$aOptions['types_declarations'][$oChildFormDescription->GetName()] = $aSymfonyTypeDeclaration;
		}

		unset($aOptions['fields']);

		return $aOptions;
	}

	/**
	 * Transform collection field options.
	 *
	 * @param array $aOptions
	 *
	 * @return array
	 */
	private function TransformCollectionOptions(array &$aOptions) : array
	{
		$aOptions['entry_type'] = $this->ToSymfonyFormTypeClass($aOptions['element_type']);

		$aOptions['entry_options'] = $this->ToSymfonyFormTypeOptions($aOptions['element_type'], $aOptions['element_options']);

		$aOptions['types_labels'] = $aOptions['fields_labels'];

		unset($aOptions['element_type']);
		unset($aOptions['element_options']);
		unset($aOptions['fields_labels']);

		return $aOptions;
	}

	/**
	 * Create Symfony form.
	 *
	 * @param array $aFieldsDescriptions
	 * @param mixed $oData
	 * @param string|null $sName
	 * @param array $aLayout
	 *
	 * @return \Symfony\Component\Form\FormInterface
	 * @throws \Exception
	 */
	public function CreateForm(array $aFieldsDescriptions, mixed $oData, ?string $sName = null, array $aLayout = []): FormInterface
	{
		// create Symfony form builder (with or without name, name is by default `form`)
		if($sName !== null){
			$oFormBuilder = $this->oFormFactory->createNamedBuilder($sName, FormType::class, $oData);
		}
		else{
			$oFormBuilder = $this->oFormFactory->createBuilder(FormType::class, $oData);
		}

		// transform fields descriptions to Symfony types...
		$aSymfonyTypesDeclaration = [];
		foreach ($aFieldsDescriptions as $sKey => $oFormFieldDescription){
			$aSymfonyTypesDeclaration[$sKey] = $this->ToSymfonyFormType($oFormFieldDescription);
		}

		// handle fieldset types layouts...
		foreach ($aSymfonyTypesDeclaration as &$aSymfonyTypeDeclaration){
			if($aSymfonyTypeDeclaration['type'] === FieldsetType::class
				&& isset($aSymfonyTypeDeclaration['options']['layout'])){
				['types_declarations' => $aLayoutSymfonyTypesDeclarations]  = $this->CreateLayoutTypes($aSymfonyTypeDeclaration['options']['layout'], $oFormBuilder, $aSymfonyTypeDeclaration['options']['types_declarations']);
				$aSymfonyTypeDeclaration['options']['types_declarations'] = array_merge($aLayoutSymfonyTypesDeclarations, $aSymfonyTypeDeclaration['options']['types_declarations']);
			}
		}

		// handle global layout types
		['types_declarations' => $aLayoutSymfonyTypesDeclaration]  = $this->CreateLayoutTypes($aLayout, $oFormBuilder, $aSymfonyTypesDeclaration);
		$aSymfonyTypesDeclaration = array_merge($aLayoutSymfonyTypesDeclaration, $aSymfonyTypesDeclaration);

		// add Symfony types to builder...
		foreach ($aSymfonyTypesDeclaration as $oSymfonyTypeDeclaration){

			// add Symfony type to form
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
	 * Parse layout description and create layout container types to group fields.
	 * Note: fields grouped in layout types are removed from descriptions array.
	 *
	 * @param array $aLayout layout description
	 * @param \Symfony\Component\Form\FormBuilderInterface $oFormBuilder Symfony form builder
	 * @param array $aDescriptions array of descriptions
	 *
	 * @return array created layout types
	 * @throws \Exception
	 */
	private function CreateLayoutTypes(array $aLayout, FormBuilderInterface $oFormBuilder, array &$aDescriptions) : array
	{
		// variables
		$aResult = [];
		$aCssClasses = [];
		$sLabel = null;
		$iRank = -1;
		$aRanks = [];

		// scan layout hierarchy...
		foreach ($aLayout as $sKey => $oLayoutElement)
		{
			// properties
			if(str_starts_with($sKey, '@')){

				if($sKey === '@css_classes'){
					$aCssClasses = $oLayoutElement;
				}
				else if($sKey === '@label'){
					$sLabel = $oLayoutElement;
				}
				else if($sKey === '@rank'){
					$iRank = intval($oLayoutElement);
				}

			}

			// layout elements
			else if(str_starts_with($sKey, ':')){

				// layout type
				if(str_starts_with($sKey, ':row_')){
					$sType = RowType::class;
				}
				else if(str_starts_with($sKey, ':column_')){
					$sType = ColumnType::class;
				}
				else if(str_starts_with($sKey, ':fieldset_')){
					$sType = FieldsetType::class;
				}
				else{
					throw new Exception('invalid layout type');
				}

				// create layout type
				['type' => $aType, 'rank' => $iTypeRank] = $this->CreateLayoutContainerType($oLayoutElement, $oFormBuilder, substr($sKey, 1), $sType, $aDescriptions);
				$aRanks[$sKey] = $iTypeRank;
				$aResult[$sKey] = $aType;

			}


			// fields
			else {

				// field with information
				if (array_key_exists($sKey, $aDescriptions)) {

					$aRanks[$sKey] = -1;
					if(isset($oLayoutElement['@rank'])){
						$aRanks[$sKey] = intval($oLayoutElement['@rank']);
					}

					// create field type
					$aResult[$sKey] = $aDescriptions[$sKey];

					// remove description
					unset($aDescriptions[$sKey]);
				}

				// only field
				else if (array_key_exists($oLayoutElement, $aDescriptions)) {

					$aRanks[$oLayoutElement] = -1;

					// create field type
					$aResult[$oLayoutElement] = $aDescriptions[$oLayoutElement];

					// remove description
					unset($aDescriptions[$oLayoutElement]);
				}
			}
		}

		// order fields by rank
		uksort($aResult, function($a, $b) use($aRanks){
			return  ( $aRanks[$a] > $aRanks[$b] ) ? 1 : -1;
		});

		return [
			'types_declarations' => $aResult,
			'label' => $sLabel,
			'css_classes' => $aCssClasses,
			'rank' => $iRank
		];
	}

	/**
	 * Create a layout container type.
	 *
	 * @param array $aLayout layout description
	 * @param \Symfony\Component\Form\FormBuilderInterface $oFormBuilder Symfony form builder
	 * @param string $sKey type declaration key
	 * @param string $sTypeClassName type class name
	 * @param array $aDescriptions child fields
	 *
	 * @return array created layout type
	 */
	private function CreateLayoutContainerType(array $aLayout, FormBuilderInterface $oFormBuilder, string $sKey, string $sTypeClassName, array &$aDescriptions) : array
	{
		['types_declarations' => $aTypesDeclarations,'label' => $sLabel, 'css_classes' => $aCssClasses, 'rank' => $iRank] = $this->CreateLayoutTypes($aLayout, $oFormBuilder, $aDescriptions);

		return ['type' => [
			'name' => $sKey,
			'type' => $sTypeClassName,
			'options' => [
				'label' => $sLabel !== null ? $sLabel : false,
				'types_declarations' => $aTypesDeclarations,
				'attr' => [
					'class' => implode(' ', $aCssClasses)
				],
				'inherit_data' => true
			],
		],
		'rank' => $iRank];
	}

}