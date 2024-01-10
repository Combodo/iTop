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

namespace Combodo\iTop\FormSDK\Service;

use Combodo\iTop\FormSDK\Field\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Form factory builder.
 *
 *
 * @package FormSDK
 * @since 3.X.0
 */
trait FormFactoryBuilderTrait
{

	/**
	 * Add text field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddTextField(string $sKey, array $aOptions) : FormFactory
	{
		// test widget for regex constraint
		if(array_key_exists('constraints', $aOptions)){
			$oConstraint = $aOptions['constraints'];
			if($oConstraint instanceof Regex){
				$aWidgetOptions = [
					'pattern' => $oConstraint->getHtmlPattern(),
				];
				$aOptions = array_merge([
					'attr' => [
						'data-widget' => 'TextWidget',
						'data-pattern' => $oConstraint->pattern,
						'data-widget-options' => json_encode($aWidgetOptions)
					]
				], $aOptions);
			}
		}

		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::TEXT, $aOptions);

		return $this;
	}

	/**
	 * Add number field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddNumberField(string $sKey, array $aOptions) : FormFactory
	{
		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::NUMBER, $aOptions);

		return $this;
	}

	/**
	 * Add area field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddAreaField(string $sKey, array $aOptions) : FormFactory
	{
		$aOptions = array_merge([
			'attr' => [
				'data-widget' => 'AreaWidget',
				'data-widget-options' => json_encode([])
			]
		], $aOptions);

		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::AREA, $aOptions);

		return $this;
	}

	/**
	 * Add date field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddDateField(string $sKey, array $aOptions) : FormFactory
	{
		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::DATE, $aOptions);

		return $this;
	}

	/**
	 * Add duration field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddDurationField(string $sKey, array $aOptions) : FormFactory
	{
		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::DURATION, $aOptions);

		return $this;
	}

	/**
	 * Add select field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddSelectField(string $sKey, array $aOptions) : FormFactory
	{
		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::SELECT, $aOptions);

		return $this;
	}

	/**
	 * Add dynamic ajax select field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param array $aAjaxOptions
	 * @param array $aAjaxData
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddSelectAjaxField(string $sKey, array $aOptions, array $aAjaxOptions, array $aAjaxData = []) : FormFactory
	{
		// merge ajax options
		$aAjaxOptions = array_merge([
			'url' => '',
			'query_parameter' => 'query',
			'value_field' => 'value',
			'label_field' => 'label',
			'search_field' => 'search',
			'preload' => false,
			'threshold' => -1,
			'configuration' => 'AJAX'
		], $aAjaxOptions);

		// merge options
		$aOptions = array_merge([
			'placeholder' => 'Select...',
			'attr' => [
				'data-widget' => 'SelectWidget',
				'data-ajax-query-type' => $aAjaxOptions['configuration'],
				'data-widget-options' => json_encode($aAjaxOptions)
			],
			//			'choice_loader' => new CallbackChoiceLoader(function() use ($aAjaxOptions, $aAjaxData): array {
			//				$curl_data = utils::DoPostRequest($aAjaxOptions['url'], []);
			//				$response_data = json_decode($curl_data);
			//				if(count($response_data->items) > $aAjaxOptions['threshold']) return [];
			//				$result = [];
			//				foreach ($response_data->items as $e) {
			//					$result[$e->breed] = $e->breed;
			//				}
			//				return $result;
			//			}),
		], $aOptions);

		return $this->AddSelectField($sKey, $aOptions);
	}


	/**
	 * Add dynamic OQL select field.
	 *
	 * @param string $sKey field key
	 * @param array $aOptions array of field options
	 * @param string $sObjectClass class the query should return
	 * @param string $sOql OQL query
	 * @param array $aAttributesToLoad class attributes to load
	 * @param string $sSearch search string
	 * @param int $iAjaxThreshold threshold for select ajax activation
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 */
	public function AddSelectOqlField(string $sKey, array $aOptions, string $sObjectClass, string $sOql, array $aAttributesToLoad, string $sSearch, int $iAjaxThreshold) : FormFactory
	{
		$aAjaxData = [
			'class' => $sObjectClass,
			'oql' => $sOql,
			'fields' => '{'.implode($aAttributesToLoad).'}',
		];
		$sUrl = 'http://localhost' . $this->oRouter->generate('formSDK_object_search') . '?' . http_build_query($aAjaxData);
		$aAjaxOptions = [
			'url' => $sUrl,
			'query_parameter' => 'search',
			'value_field' => 'key',
			'label_field' => 'friendlyname',
			'search_field' => 'friendlyname',
			'threshold' => $iAjaxThreshold,
			'configuration' => 'OQL'
		];
		return $this->AddSelectAjaxField($sKey, $aOptions, $aAjaxOptions, $aAjaxData);
	}

	/**
	 * Add switch field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddSwitchField(string $sKey, array $aOptions) : FormFactory
	{
		$aOptions = array_merge([
			'label_attr' => ['class' => 'checkbox-switch'],
		], $aOptions);

		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::SWITCH, $aOptions);

		return $this;
	}


	/**
	 * Add fieldset.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddFieldSet(string $sKey, array $aOptions) : FormFactory
	{
		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::FIELDSET, $aOptions);

		return $this;
	}

	/**
	 * Add collection field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddCollectionField(string $sKey, array $aOptions) : FormFactory
	{
		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::COLLECTION, $aOptions);

		return $this;
	}

	/**
	 * Add file field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \Exception
	 */
	public function AddFileField(string $sKey, array $aOptions) : FormFactory
	{
		$this->aFieldsDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::FILE, $aOptions);

		return $this;
	}
}