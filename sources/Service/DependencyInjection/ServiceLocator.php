<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\DependencyInjection;

use Psr\Container\ContainerInterface;

class ServiceLocator implements ContainerInterface
{
	private static ServiceLocator $oInstance;
	private array $aServices = [];

	protected function __construct()
	{
	}

	final public static function GetInstance(): ServiceLocator
	{
		if (!isset(static::$oInstance)) {
			static::$oInstance = new ServiceLocator();
		}

		return static::$oInstance;
	}

	/**
	 * Register a service by name
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
	 * @param string $id Service id to search for
	 *
	 * @return mixed The service or null when the service is not found and $bMustBeFound is false
	 * @throws \Combodo\iTop\Service\DependencyInjection\DIException
	 */
	public function get(string $id): mixed
	{
		if (!isset($this->aServices[$id])) {
			throw new DIException("Service ".json_encode($id)." not found");
		}

		return $this->aServices[$id];
	}

	public function has(string $id): bool
	{
		return isset($this->aServices[$id]);
	}
}
