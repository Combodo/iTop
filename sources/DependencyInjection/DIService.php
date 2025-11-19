<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DependencyInjection;

class DIService
{
	private static DIService $oInstance;
	private array $aServices = [];

	protected function __construct()
	{
	}

	final public static function GetInstance(): DIService
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new DIService();
		}

		return static::$oInstance;
	}

	/**
	 * Register a service by name
	 *
	 * @api
	 *
	 * @param string $sName Name of the service to register
	 * @param mixed $oService Service to register
	 *
	 * @return void
	 */
	final public function RegisterService(string $sName, mixed $oService): void
	{
		$this->aServices[$sName] = $oService;
	}

	/**
	 * Get a previously registered service
	 *
	 * @api
	 *
	 * @param string $sName name of the service to get
	 *
	 * @return mixed
	 * @throws \Combodo\iTop\DependencyInjection\DIException
	 */
	final public function GetService(string $sName): mixed
	{
		if (!isset($this->aServices[$sName])) {
			throw new DIException("Service ".json_encode($sName)." not found");
		}

		return $this->aServices[$sName];
	}
}
