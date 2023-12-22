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
use Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryObjectAddon;
use Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryAddonInterface;
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
	/** @var \Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryAddonInterface[] $aAddons  */
	private array $aAddons = [];

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
		foreach ($this->GetAllAddons() as $oPlugin){
			$aResult['descriptions'] = array_merge($aResult['descriptions'], $oPlugin->GetFormDescriptions());
			$aResult['data'] = array_merge($aResult['data'], $oPlugin->GetFormData());
		}

		return $aResult;
	}

	/**
	 * Create an object addon.
	 *
	 * @param \DBObject $oDBObject
	 * @param bool $bGroup
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryObjectAddon
	 */
	public function CreateObjectAddon(DBObject $oDBObject, bool $bGroup = true) : FormFactoryObjectAddon
	{
		$oObjectBuilder = new FormFactoryObjectAddon($oDBObject, $bGroup);
		$this->AddAddon(get_class($oDBObject) . '_' . $oDBObject->GetKey(), $oObjectBuilder);
		return $oObjectBuilder;
	}

	/**
	 * Add an addon.
	 *
	 * @param string $sKey
	 * @param \Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryAddonInterface $oPlugin
	 *
	 * @return $this
	 */
	public function AddAddon(string $sKey, FormFactoryAddonInterface $oPlugin) : FormFactory
	{
		$this->aAddons[$sKey] = $oPlugin;
		return $this;
	}

	/**
	 * Get all addons.
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryAddonInterface[]
	 */
	public function GetAllAddons() : array
	{
		return $this->aAddons;
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
		// ajax options
		array_merge([
			'url' => '',
			'query_parameter' => 'query',
			'value_field' => 'value',
			'label_field' => 'label',
			'search_field' => 'search',
			'preload' => false,
			'threshold' => -1
		], $aAjaxOptions);

		// merge options
		$aOptions = array_merge([
			'placeholder' => 'Select...',
			'attr' => [
				'data-widget' => 'SelectWidget',
				'data-widget-options' => json_encode($aAjaxOptions)
			],
			'choice_loader' => new CallbackChoiceLoader(function() use ($aAjaxOptions, $aAjaxData): array {
				$curl_data = utils::DoPostRequest($aAjaxOptions['url'], []);
				$response_data = json_decode($curl_data);
				if(count($response_data->items) > $aAjaxOptions['threshold']) return [];
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
			'threshold' => $iAjaxThershold
		];
		return $this->AddSelectAjaxField($sKey, $aOptions, $aAjaxOptions, $aAjaxData, $oData);
	}
}