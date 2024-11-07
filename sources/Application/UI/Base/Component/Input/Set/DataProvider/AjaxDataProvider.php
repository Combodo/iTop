<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

namespace Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider;

/**
 * Class AjaxDataProvider
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Input\Set\DataProvider
 * @since 3.1.0
 */
class AjaxDataProvider extends SimpleDataProvider
{
	/** @var int DEFAULT_MAX_RESULTS maximum results fetched */
	const DEFAULT_MAX_RESULTS = 25;

	/**
	 * @see \Combodo\iTop\Service\Router\Router
	 * @var string $sAjaxRoute Router route name
	 */
	private string $sRoute;

	/** @var array $aParams Query string params */
	private array $aParams = [];

	/** @var array $aPostParams Post params */
	private array $aPostParams = [];

	/** @var int $iMaxResults Maximum entries */
	private int $iMaxResults = AjaxDataProvider::DEFAULT_MAX_RESULTS;


	/**
	 * Constructor.
	 *
	 * @param string $sRoute Router route name
	 * @param array $aParams Query string params
	 * @param array $aPostParams Post params
	 */
	public function __construct(string $sRoute, array $aParams = [], array $aPostParams = [])
	{
		parent::__construct();

		// Retrieve parameters
		$this->sRoute = $sRoute;
		$this->aParams = $aParams;
		$this->aPostParams = $aPostParams;
	}

	/** @inheritDoc */
	public function GetType(): string
	{
		return iDataProvider::TYPE_AJAX_PROVIDER;
	}

	/**
	 * SetParam.
	 *
	 * @param string $sName
	 * @param string $sValue
	 *
	 * @return $this
	 */
	public function SetParam(string $sName, string $sValue): AjaxDataProvider
	{
		$this->aParams[$sName] = $sValue;

		return $this;
	}

	/**
	 * GetParam.
	 *
	 * @param string $sName
	 *
	 * @return string
	 */
	public function GetParam(string $sName): string
	{
		return $this->aParams[$sName];
	}

	/**
	 * GetParams.
	 *
	 * @return array
	 */
	public function GetParams(): array
	{
		return $this->aParams;
	}

	/**
	 * GetParamsAsQueryString.
	 *
	 * @return string
	 */
	public function GetParamsAsQueryString(): string
	{
		$aFlattened = $this->aParams;
		array_walk($aFlattened, function (&$sValue, $key) {
			$sValue = "{$key}={$sValue}";
		});

		return '&'.implode('&', $aFlattened);
	}

	/**
	 * GetPostParamsAsJsonString.
	 *
	 * @return string
	 */
	public function GetPostParamsAsJsonString(): string
	{
		return json_encode($this->aPostParams);
	}

	/**
	 * SetPostParam.
	 *
	 * @param string $sName
	 * @param $oValue
	 *
	 * @return $this
	 */
	public function SetPostParam(string $sName, $oValue): AjaxDataProvider
	{
		$this->aPostParams[$sName] = $oValue;

		return $this;
	}

	/**
	 * GetRoute.
	 *
	 * @return void
	 */
	public function GetRoute(): string
	{
		return $this->sRoute;
	}

	/**
	 * Return maximum results count.
	 *
	 * @return int
	 */
	public function GetMaxResults(): int
	{
		return $this->iMaxResults;
	}

}