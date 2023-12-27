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

use Combodo\iTop\FormSDK\Helper\SelectDataProvider;
use Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryObjectAdapter;
use Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryAdapterInterface;
use Combodo\iTop\FormSDK\Symfony\SymfonyBridge;
use Combodo\iTop\FormSDK\Field\Description\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\Description\FormFieldTypeEnumeration;
use DBObject;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use utils;

/**
 * Form factory.
 *
 * Build and manipulate forms.
 *
 * @package FormSDK
 * @since 3.2.0
 */
class FormFactory
{
	/** @var \Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryAdapterInterface[] $aAdapters  */
	private array $aAdapters = [];

	/** @var array $aDescriptions form types descriptions */
	private array $aDescriptions = [];

	/** @var array $aData form data */
	private array $aData = [];

	/**
	 * Constructor.
	 *
	 * @param \Combodo\iTop\FormSDK\Symfony\SymfonyBridge $oSymfonyBridge
	 * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $oRouter
	 */
	public function __construct(
		private SymfonyBridge $oSymfonyBridge,
		private UrlGeneratorInterface $oRouter
	)
	{

	}

	/**
	 * @return array{descriptions:array, data:array}
	 */
	public function GetFormDescriptionsAndData() : array
	{
		// prepare data
		$aResult = [
			'descriptions' => $this->aDescriptions,
			'data' => $this->aData,
		];

		// merge each adapter data...
		foreach ($this->GetAllAdapters() as $oAdapter){
			$aResult['descriptions'] = array_merge($aResult['descriptions'], $oAdapter->GetFormDescriptions());
			$aResult['data'] = array_merge($aResult['data'], $oAdapter->GetFormData());
		}

		return $aResult;
	}

	/**
	 * Create an object adapter.
	 *
	 * @param \DBObject $oDBObject
	 * @param bool $bGroup
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryObjectAdapter
	 */
	public function CreateObjectAdapter(DBObject $oDBObject, bool $bGroup = true) : FormFactoryObjectAdapter
	{
		$oObjectBuilder = new FormFactoryObjectAdapter($oDBObject, $bGroup);
		$this->AddAdapter(get_class($oDBObject) . '_' . $oDBObject->GetKey(), $oObjectBuilder);
		return $oObjectBuilder;
	}

	/**
	 * Add an adapter.
	 *
	 * @param string $sKey
	 * @param \Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryAdapterInterface $oAdapter
	 *
	 * @return $this
	 */
	public function AddAdapter(string $sKey, FormFactoryAdapterInterface $oAdapter) : FormFactory
	{
		$this->aAdapters[$sKey] = $oAdapter;
		return $this;
	}

	/**
	 * Get all adapters.
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryAdapterInterface[]
	 */
	public function GetAllAdapters() : array
	{
		return $this->aAdapters;
	}

	/**
	 * Get form.
	 *
	 * @param string|null $sName
	 *
	 * @return \Symfony\Component\Form\FormInterface
	 */
	public function GetForm(?string $sName): FormInterface
	{
		['descriptions' => $aDescriptions, 'data' => $aData] = $this->GetFormDescriptionsAndData();
		return $this->oSymfonyBridge->GetForm($aDescriptions, $aData, $sName);
	}

	/**
	 * Add text field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddTextField(string $sKey, array $aOptions, mixed $oData = null) : FormFactory
	{
		// test widget for regex constraint
		if(array_key_exists('constraints', $aOptions)){
			$oConstraint = $aOptions['constraints'];
			if($oConstraint instanceof Regex){
				$aWidgetOptions = [
					'pattern' => $oConstraint->pattern,
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

		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::TEXT, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}

	/**
	 * Add area field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddAreaField(string $sKey, array $aOptions, mixed $oData = null) : FormFactory
	{
		$aOptions = array_merge([
			'attr' => [
				'data-widget' => 'AreaWidget',
				'data-widget-options' => json_encode([])
			]
		], $aOptions);

		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::AREA, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}

	/**
	 * Add date field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddDateField(string $sKey, array $aOptions, mixed $oData = null) : FormFactory
	{
		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::DATE, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}


	/**
	 * Add select field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddSelectField(string $sKey, array $aOptions, mixed $oData = null) : FormFactory
	{
		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::SELECT, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}

	/**
	 * Add dynamic ajax select field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param array $aAjaxOptions
	 * @param array $aAjaxData
	 * @param mixed $oData
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 */
	public function AddSelectAjaxField(string $sKey, array $aOptions, array $aAjaxOptions, array $aAjaxData = [], mixed $oData = null) : FormFactory
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

		return $this->AddSelectField($sKey, $aOptions, $oData);
	}


	/**
	 * Add dynamic OQL select field.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param string $sObjectClass
	 * @param string $sOql
	 * @param array $aFieldsToLoad
	 * @param string $sSearch
	 * @param int $iAjaxThershold
	 * @param mixed $oData
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 */
	public function AddSelectOqlField(string $sKey, array $aOptions, string $sObjectClass, string $sOql, array $aFieldsToLoad, string $sSearch, int $iAjaxThershold, mixed $oData = null) : FormFactory
	{
		$aAjaxData = [
			'class' => $sObjectClass,
			'oql' => $sOql,
			'fields' => '{'.implode($aFieldsToLoad).'}',
		];
		$sUrl = 'http://localhost' . $this->oRouter->generate('formSDK_object_search') . '?' . http_build_query($aAjaxData);
		$aAjaxOptions = [
			'url' => $sUrl,
			'query_parameter' => 'search',
			'value_field' => 'key',
			'label_field' => 'friendlyname',
			'search_field' => 'friendlyname',
			'threshold' => $iAjaxThershold,
			'configuration' => 'OQL'
		];
		return $this->AddSelectAjaxField($sKey, $aOptions, $aAjaxOptions, $aAjaxData, $oData);
	}
}