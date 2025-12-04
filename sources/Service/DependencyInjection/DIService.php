<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\DependencyInjection;

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
	 * @param bool $bMustBeFound if true a DIException is thrown when the service is not found
	 *
	 * @return mixed The service or null when the service is not found and $bMustBeFound is false
	 * @throws \Combodo\iTop\Service\DependencyInjection\DIException
	 */
	final public function GetService(string $sName, bool $bMustBeFound = true): mixed
	{
		if (!isset($this->aServices[$sName])) {
			if ($bMustBeFound) {
				throw new DIException("Service ".json_encode($sName)." not found");
			}

			return null;
		}

		return $this->aServices[$sName];
	}
}
