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

use Combodo\iTop\FormSDK\Helper\SelectHelper;
use Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryObjectPlugin;
use Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryPluginInterface;
use Combodo\iTop\FormSDK\Symfony\SymfonyBridge;
use Combodo\iTop\FormSDK\Field\Description\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\Description\FormFieldTypeEnumeration;
use DBObject;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use utils;

/**
 * Form factory service.
 *
 * @package FormSDK
 * @since 3.2.0
 */
class FormFactory
{
	/** @var \Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryPluginInterface[] $aPlugins  */
	private array $aPlugins = [];

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

		// append plugin data
		foreach ($this->GetAllPlugins() as $oPlugin){
			$aResult['descriptions'] = array_merge($aResult['descriptions'], $oPlugin->GetFormDescriptions());
			$aResult['data'] = array_merge($aResult['data'], $oPlugin->GetFormData());
		}

		return $aResult;
	}

	/**
	 * Create an object plugin.
	 *
	 * @param \DBObject $oDBObject
	 * @param bool $bGroup
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryObjectPlugin
	 */
	public function CreateObjectPlugin(DBObject $oDBObject, bool $bGroup = true) : FormFactoryObjectPlugin
	{
		$oObjectBuilder = new FormFactoryObjectPlugin($oDBObject, $bGroup);
		$this->AddPlugin(get_class($oDBObject) . '_' . $oDBObject->GetKey(), $oObjectBuilder);
		return $oObjectBuilder;
	}

	/**
	 * Add a plugin.
	 *
	 * @param string $sKey
	 * @param \Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryPluginInterface $oPlugin
	 *
	 * @return $this
	 */
	public function AddPlugin(string $sKey, FormFactoryPluginInterface $oPlugin) : FormFactory
	{
		$this->aPlugins[$sKey] = $oPlugin;
		return $this;
	}

	/**
	 * Get all plugins.
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryPluginInterface[]
	 */
	public function GetAllPlugins() : array
	{
		return $this->aPlugins;
	}

	/**
	 * Get form.
	 *
	 * @return \Symfony\Component\Form\FormInterface
	 */
	public function GetForm(): FormInterface
	{
		['descriptions' => $aDescriptions, 'data' => $aData] = $this->GetFormDescriptionsAndData();
		return $this->oSymfonyBridge->GetForm($aDescriptions, $aData);
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
		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::TEXT, $aOptions);
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
	 * @param string $sAjaxUrl
	 * @param array $aAjaxData
	 * @param string $sValueField
	 * @param string $sLabelField
	 * @param string $sSearchField
	 * @param int $iAjaxThershold
	 * @param mixed $oData
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 */
	public function AddSelectAjaxField(string $sKey, array $aOptions, string $sAjaxUrl, array $aAjaxData, string $sValueField, string $sLabelField, string $sSearchField, int $iAjaxThershold, mixed $oData = null) : FormFactory
	{
		// ajax loader options
		$aAjaxLoaderOptions = [
			'ajax_url' => $sAjaxUrl,
			'valueField' => $sValueField ? $sValueField : 'value',
			'labelField' => $sLabelField ? $sLabelField : 'label',
			'searchField' => $sSearchField ? $sSearchField : 'search',
			'preload' => false,
		];

		// merge options
		$aOptions = array_merge([
			'placeholder' => 'Select a value...',
			'attr' => [
				'data-widget' => 'SelectWidget',
				'data-widget-options' => json_encode($aAjaxLoaderOptions)
			],
			'choice_loader' => new CallbackChoiceLoader(function() use ($sAjaxUrl, $aAjaxData, $iAjaxThershold): array {
				$curl_data = utils::DoPostRequest($sAjaxUrl, []);
				$response_data = json_decode($curl_data);
				if(count($response_data->items) > $iAjaxThershold) return [];
				$result = [];
				foreach ($response_data->items as $e) {
					$result[$e->breed] = $e->breed;
				}
				return $result;
			}),
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
//		$sFieldsToLoad = implode($aFieldsToLoad);
		$aData = [
			'class' => $sObjectClass,
			'oql' => $sOql,
			'fields' => json_encode($aFieldsToLoad),
			'search' => $sSearch
		];
		$sUrl = 'http://localhost' . $this->oRouter->generate('formSDK_object_search') . '?' . http_build_query($aData);
		return $this->AddSelectAjaxField($sKey, $aOptions, $sUrl, $aData, 'id', 'friendlyname', 'friendlyname', $iAjaxThershold, $oData);
	}
}