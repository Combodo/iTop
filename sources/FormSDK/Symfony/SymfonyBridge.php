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
use Combodo\iTop\FormSDK\Field\Description\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\Description\FormFieldTypeEnumeration;
use LogAPI;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
	 * @param \Combodo\iTop\FormSDK\Field\Description\FormFieldDescription $oFormDescription
	 *
	 * @return array|null
	 */
	public function ToSymfonyFormType(FormFieldDescription $oFormDescription) : ?array
	{
		switch($oFormDescription->GetType()){
			case FormFieldTypeEnumeration::TEXT:
				return [
					'path' => $oFormDescription->GetPath(),
					'type' => TextType::class,
					'options' => $oFormDescription->GetOptions()
				];

			case FormFieldTypeEnumeration::SELECT:
				return [
					'path' => $oFormDescription->GetPath(),
					'type' => ChoiceType::class,
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
				$aOptions['inherit_data'] = true;
				return [
					'path' => $oFormDescription->GetPath(),
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
	 *
	 * @return \Symfony\Component\Form\FormInterface
	 */
	public function GetForm(array $aDescriptions, array $aData): FormInterface
	{
		// create Symfony form builder
		$oFormBuilder = $this->oFormFactory->createBuilder(FormType::class, $aData);

		// iterate throw descriptions...
		foreach ($aDescriptions as $oFormDescription){

			$aSymfony = $this->ToSymfonyFormType($oFormDescription);

			$oFormBuilder->add(
				$aSymfony['path'],
				$aSymfony['type'],
				$aSymfony['options']
			);
		}

		return $oFormBuilder->getForm();
	}

}