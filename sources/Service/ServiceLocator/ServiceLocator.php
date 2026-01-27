<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\ServiceLocator;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * @since 3.3.0
 */
class ServiceLocator implements ContainerInterface
{
	private array $aServices;

	public function __construct()
	{
		$this->aServices = [];
	}

	/**
	 * Register a service by name
	 *
	 * @param string $sName Name of the service to register
	 * @param mixed $oService Service to register
	 *
	 * @return void
	 */
	final protected function RegisterService(string $sName, mixed $oService): void
	{
		$this->aServices[$sName] = $oService;
	}

	/**
	 * Get a previously registered service
	 *
	 * @param string $id Service id to search for
	 *
	 * @return mixed The service object
	 * @throws \Combodo\iTop\Service\ServiceLocator\NotFoundException When the service is not configured
	 * @throws \Combodo\iTop\Service\ServiceLocator\ServiceLocatorException If error during service instanciation
	 */
	public function get(string $id): mixed
	{
		if (!$this->has($id)) {
			throw new NotFoundException("Service ".json_encode($id)." not found");
		}

		try {
			if (is_string($this->aServices[$id])) {
				$oService = new $this->aServices[$id]();
				$this->aServices[$id] = $oService;
			}
		} catch (\Throwable $t) {
			throw new ServiceLocatorException("Service ".json_encode($id)." not found", $t);
		}

		return $this->aServices[$id];
	}

	public function has(string $id): bool
	{
		return isset($this->aServices[$id]);
	}

	/**
	 * Init Service locator for a configuration file corresponding to the category
	 *
	 * @see conf/production/service-locator-runtime-config.php
	 *
	 * @param string|null $sRelativeConfigFileName config file name relative to APPROOT
	 *
	 * @return void
	 */
	final public function Init(string $sRelativeConfigFileName = null): void
	{
		if (is_null($sRelativeConfigFileName)) {
			$sConfigFile = APPROOT."sources/Service/ServiceLocator/service-locator-runtime.php";
		} else {
			$sConfigFile = APPROOT.$sRelativeConfigFileName;
		}
		if (!file_exists($sConfigFile)) {
			return;
		}

		$this->aServices = include($sConfigFile);
	}
}
