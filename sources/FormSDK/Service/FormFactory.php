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
use Combodo\iTop\FormSDK\Service\FormFactoryBuilderTrait;
use Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryObjectAdapter;
use Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryAdapterInterface;
use Combodo\iTop\FormSDK\Symfony\SymfonyBridge;
use Combodo\iTop\FormSDK\Field\FormFieldDescription;
use Combodo\iTop\FormSDK\Field\FormFieldTypeEnumeration;
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

	/** builder */
	use FormFactoryBuilderTrait;

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
	 * Return descriptions and data arrays.
	 *
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
	 * @return mixed
	 */
	public function GetForm(?string $sName = null) : mixed
	{
		['descriptions' => $aDescriptions, 'data' => $aData] = $this->GetFormDescriptionsAndData();
		return $this->oSymfonyBridge->GetForm($aDescriptions, $aData, $sName);
	}

}