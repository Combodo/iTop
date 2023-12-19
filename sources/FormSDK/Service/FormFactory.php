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

use Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryObjectPlugin;
use Combodo\iTop\FormSDK\Service\FactoryPlugin\FormFactoryPluginInterface;
use Combodo\iTop\FormSDK\Symfony\SymfonyBridge;
use Combodo\iTop\FormSDK\Field\Description\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\Description\FormFieldTypeEnumeration;
use DBObject;
use Symfony\Component\Form\FormInterface;

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
	 */
	public function __construct(
		protected SymfonyBridge $oSymfonyBridge
	)
	{

	}

	/**
	 * Add text.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddText(string $sKey, array $aOptions, mixed $oData) : FormFactory
	{
		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::TEXT, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
	}

	/**
	 * Add select.
	 *
	 * @param string $sKey
	 * @param array $aOptions
	 * @param mixed $oData
	 *
	 * @return $this
	 */
	public function AddSelect(string $sKey, array $aOptions, mixed $oData) : FormFactory
	{
		$this->aDescriptions[$sKey] = new FormFieldDescription($sKey, FormFieldTypeEnumeration::SELECT, $aOptions);
		$this->aData[$sKey] = $oData;

		return $this;
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


}