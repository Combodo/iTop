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

use Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryObjectAdapter;
use Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryAdapterInterface;
use Combodo\iTop\FormSDK\Symfony\SymfonyBridge;
use DBObject;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Form factory.
 *
 * Build and manipulate forms.
 *
 * @package FormSDK
 * @since 3.X.0
 */
final class FormFactory
{
	/** @var array $aFieldsDescriptions form fields descriptions */
	private array $aFieldsDescriptions = [];

	/** @var mixed $oFieldsData form fields data */
	private mixed $oFieldsData = [];

	/** @var array $aLayoutDescription layout description */
	private array $aLayoutDescription = [];

	/** @var \Combodo\iTop\FormSDK\Service\FactoryAdapter\FormFactoryAdapterInterface[] $aAdapters list of adapters */
	private array $aAdapters = [];

	/** description builder */
	use FormFactoryBuilderTrait;

	/**
	 * Constructor.
	 *
	 * @param \Combodo\iTop\FormSDK\Symfony\SymfonyBridge $oSymfonyBridge
	 * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $oRouter
	 */
	public function __construct(
		private readonly SymfonyBridge $oSymfonyBridge,
		private readonly UrlGeneratorInterface $oRouter
	)
	{

	}

	/**
	 * Return fields descriptions.
	 *
	 * @return array{fields_descriptions:array, layout_description:array}
	 */
	public function GetFieldsDescriptions() : array
	{
		// prepare data
		$aResult = $this->aFieldsDescriptions;

		// merge each adapter data...
		foreach ($this->GetAllAdapters() as $oAdapter){
			$aResult = array_merge($aResult, $oAdapter->GetFieldsDescriptions());
		}

		return $aResult;
	}

	/**
	 * Set form data.
	 *
	 * @param mixed $oData
	 *
	 * @return void
	 */
	public function SetData(mixed $oData) : void
	{
		$this->oFieldsData = $oData;
	}

	/***
	 * Get form data.
	 *
	 * @return array
	 */
	public function GetData() : mixed
	{
		$aData = $this->oFieldsData;

		foreach ($this->GetAllAdapters() as $adapter){
			$aData = array_merge($aData, $adapter->GetFieldsData());
		}

		return $aData;
	}

	/**
	 * Set layout description.
	 *
	 * @param array $aLayoutDescription
	 *
	 * @return $this
	 */
	public function SetLayoutDescription(array $aLayoutDescription) : FormFactory
	{
		$this->aLayoutDescription = $aLayoutDescription;
		return $this;
	}

	/**
	 * Return layout description.
	 *
	 * @return array
	 */
	public function GetLayoutDescription() : array
	{
		return $this->aLayoutDescription;
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
	 * Create form.
	 *
	 * @param string|null $sName
	 * @return mixed
	 */
	public function CreateForm(?string $sName = null) : mixed
	{
		return $this->oSymfonyBridge->CreateForm($this->GetFieldsDescriptions(), $this->GetData(), $sName, $this->GetLayoutDescription());
	}

}