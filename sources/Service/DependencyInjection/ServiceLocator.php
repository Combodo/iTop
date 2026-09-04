<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\DependencyInjection;

use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompilerException;
use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Config;
use DOMDocument;
use Psr\Container\ContainerInterface;

class ServiceLocator implements ContainerInterface
{
	private array $aServices = [];
	private array $aClasses = [];

	public const DEFAULT_PRODUCTION_SERVICES = __DIR__.DIRECTORY_SEPARATOR.'ServiceLocator.xml';

	public function __construct()
	{
	}

	final public function InitFromConfig(Config $oConfig): void
	{
		$this->InitFromFile($oConfig->Get('service_locator.services.production') ?? self::DEFAULT_PRODUCTION_SERVICES);
	}

	/**
	 * Init ServiceLocator from an XML file
	 *
	 * @param string $sFileName
	 *
	 * @return void
	 * @throws \Exception
	 */
	final public function InitFromFile(string $sFileName): void
	{
		$this->aServices = [];
		$this->aClasses = [];

		$this->AddClassesFromFile($sFileName);

		// Service locator has been (re)initialized
		EventService::FireEvent(new EventData(sEvent: \EVENT_SERVICE_LOCATOR_INITIALIZED, aEventData: ['file_name' => $sFileName]));
	}

	/**
	 * Add or replace classes from an XML file
	 *
	 * @param string $sFileName
	 *
	 * @return void
	 */
	final public function AddClassesFromFile(string $sFileName): void
	{
		$oDoc = new DOMDocument();
		libxml_clear_errors();
		$oDoc->loadXML(file_get_contents($sFileName));
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0) {
			throw new PropertyTypeCompilerException('Property types definition not correctly formatted!');
		}
		$oNode = $oDoc->firstChild;
		$oNode = $oNode->getElementsByTagName('services')->item(0);
		foreach ($oNode->getElementsByTagName('service') as $oServiceElement) {
			$sServiceName = $oServiceElement->getAttribute('id');
			$oClassNode = $oNode->getElementsByTagName('class')->item(0);
			if (!$oClassNode) {
				continue;
			}
			$sClassName = $oClassNode->getAttribute('id');

			$this->RegisterClass($sServiceName, $sClassName);
		}
	}

	/**
	 * Register a Service with the instance of the service
	 *
	 * @param string $sServiceName
	 * @param string $sClassName
	 *
	 * @return void
	 */
	final public function RegisterClass(string $sServiceName, string $sClassName): void
	{
		$this->aClasses[$sServiceName] = $sClassName;
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
		if (isset($this->aServices[$id])) {
			return $this->aServices[$id];
		}

		// Search in classes
		if (!isset($this->aClasses[$id])) {
			throw new DIException("Service ".json_encode($id)." not found");
		}
		$oService = new $this->aClasses[$id]();
		$this->RegisterService($id, $oService);

		return $oService;
	}

	public function has(string $id): bool
	{
		return isset($this->aServices[$id]) || isset($this->aClasses[$id]);
	}
}
