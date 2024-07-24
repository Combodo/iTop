<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Router;

use Combodo\iTop\Controller\iController;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Combodo\iTop\Service\Router\Exception\RouteNotFoundException;
use ReflectionClass;
use ReflectionMethod;
use utils;
use SetupUtils;

/**
 * Class Router
 *
 * Service to find the corresponding controller / method for a given "route" parameter.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Service\Router
 * @since 3.1.0
 * @api
 */
class Router
{
	/** @var \Combodo\iTop\Service\Router\Router|null Singleton instance */
	protected static ?Router $oSingleton = null;

	/**
	 * @api
	 * @return $this The singleton instance of the router
	 */
	public static function GetInstance(): Router
	{
		if (null === static::$oSingleton) {
			static::$oSingleton = new static();
		}

		return static::$oSingleton;
	}

	/**
	 * @var bool $bUseCache
	 */
	protected $bUseCache = null;

	/**********************/
	/* Non-static methods */
	/**********************/

	/**
	 * Singleton pattern, can't use the constructor. Use {@see \Combodo\iTop\Service\Router\Router::GetInstance()} instead.
	 *
	 * @return void
	 */
	protected function __construct()
	{
		// Don't do anything, we don't want to be initialized
	}

	/**
	 * @param bool|null $bUseCache Force cache usage for testing purposes, or leave it null for the default behavior
	 */
	public function SetUseCache(?bool $bUseCache): void
	{
		$this->bUseCache = $bUseCache;
	}

	/**
	 * Generate a complete URL for a specific route and optional parameters
	 *
	 * @api
	 * @param string $sRoute Code of the route to generate the URL for (eg. "object.modify" => "https://itop/pages/UI.php?route=object.modify")
	 * @param array $aParams Parameters to add to the URL query string, they will be URL-encoded automatically.
	 *      Note that only scalars and arrays are supported.
	 *      (eg. ["foo" => "bar", "some_array" => [1, 2, 3]] will be append to the URL as "&foo=bar&some_array[]=1&some_array[]=2&some_array[]=3")
	 * @param bool $bAbsoluteUrl Whether the URL should be absolute (include the app root URL) or not
	 *
	 * @return string Absolute or relative URL to access $sRoute
	 * @throws \Exception
	 */
	public function GenerateUrl(string $sRoute, array $aParams = [], bool $bAbsoluteUrl = true): string
	{
		// Stop if route cannot be found, it will ease DX and troubleshooting
		if (false === $this->CanDispatchRoute($sRoute)) {
			throw new RouteNotFoundException('Could not find route "'.$sRoute.'"');
		}

		// Prepare base URL
		$sUrl = $bAbsoluteUrl ? utils::GetAbsoluteUrlAppRoot() : '';

		// Add route URL
		$sUrl .=  'pages/UI.php?route=' . $sRoute;

		// Add parameters and url encode them
		if (count($aParams) > 0) {
			$sUrl .= '&' . http_build_query($aParams);
		}

		return $sUrl;
	}

	/**
	 * @param string $sRoute
	 *
	 * @return bool True if there is a matching handler for $sRoute
	 */
	public function CanDispatchRoute(string $sRoute): bool
	{
		return $this->GetDispatchSpecsForRoute($sRoute) !== null;
	}

	/**
	 * Dispatch the current request to the matching handler for $sRoute
	 *
	 * @param string $sRoute
	 *
	 * @return mixed Response from the route's handler, can be anything.
	 *               Even though it can be anything, in most cases, response will either be:
	 *               - A WebPage for usual backoffice operations
	 *               - null for TwigBase backoffice operations
	 */
	public function DispatchRoute(string $sRoute)
	{
		$aMethodSpecs = $this->GetDispatchSpecsForRoute($sRoute);
		$mResponse = call_user_func_array([new $aMethodSpecs[0](), $aMethodSpecs[1]], []);

		return $mResponse;
	}

	/**
	 * @return array{0: string, 1: array{
	 *          namespace: string,
	 *          operation: string,
	 *          controller: string,
	 *          description: string
	 *      }
	 * } Array of available routes and their corresponding controllers (eg. [
	 *      'object.modify' => [            // Complete route code
	 *          'namespace' => 'object',    // Route namespace
	 *          'operation' => 'modify',    // Route operation
	 *          'controller' => '\Combodo\iTop\Controller\Base\Layout\ObjectController::OperationModify',   // FQCN of the controller/method that handle the route
	 *          'description' => 'Handles display of a modification form for a datamodel object'            // Text description of the route
	 *      ],
	 *      ...
	 *  ])
	 * @throws \ReflectionException
	 */
	public function GetRoutes(): array
	{
		$aRoutes = [];
		$bUseCache = is_null($this->bUseCache) ? (false === utils::IsDevelopmentEnvironment()) : $this->bUseCache;
		$bMustWriteCache = false;
		$sCacheFilePath = $this->GetCacheFileAbsPath();

		// Try to read from cache
		if ($bUseCache) {
			if (is_file($sCacheFilePath)) {
				$aCachedRoutes = include $sCacheFilePath;

				// N°6618 - Protection against corrupted cache returning `1` instead of an array of routes
				if (is_array($aCachedRoutes)) {
					$aRoutes = $aCachedRoutes;
				} else {
					// Invalid cache force re-generation
					// Note that even if it is re-generated corrupted again, this protection should prevent crashes
					$bMustWriteCache = true;
				}
			} else {
				$bMustWriteCache = true;
			}
		}

		// If no cache, force to re-scan for routes
		if (count($aRoutes) === 0) {
			foreach (InterfaceDiscovery::GetInstance()->FindItopClasses(iController::class) as $sControllerFQCN) {
				$sRouteNamespace = $sControllerFQCN::ROUTE_NAMESPACE;
				// Ignore controller with no namespace
				if (is_null($sRouteNamespace)) {
					continue;
				}

				$oReflectionClass = new ReflectionClass($sControllerFQCN);
				foreach ($oReflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $oReflectionMethod) {
					// Ignore non "operation" methods
					$sPrefix = 'Operation';
					$iPos = stripos($oReflectionMethod->name, $sPrefix);
					if ($iPos !== 0) {
						continue;
					}

					// eg. "OperationDoSomething"
					$sMethodName = $oReflectionMethod->name;
					// eg. "do_something"
					$sRouteOperation = utils::ToSnakeCase(substr($oReflectionMethod->name, $iPos + strlen($sPrefix)));

					$aRoutes[$sRouteNamespace . '.' . $sRouteOperation] = [
						'namespace' => $sRouteNamespace,
						'operation' => $sRouteOperation,
						'controller' => $sControllerFQCN . '::' . $sMethodName,
						'description' => $oReflectionMethod->getDocComment(),
					];
				}
			}
		}

		// Save to cache if it doesn't exist already
		if ($bMustWriteCache) {
			$sCacheContent = "<?php\n\nreturn ".var_export($aRoutes, true).";";
			SetupUtils::builddir(dirname($sCacheFilePath));
			file_put_contents($sCacheFilePath, $sCacheContent, LOCK_EX);
		}

		return $aRoutes;
	}

	/**
	 * @param string $sRoute
	 *
	 * @return array{sControllerFQCN, sOperationMethodName}|null The FQCN controller and operation method matching $sRoute, null if no matching handler
	 */
	protected function GetDispatchSpecsForRoute(string $sRoute)
	{
		$aRouteParts = $this->GetRouteParts($sRoute);
		if (is_null($aRouteParts)) {
			return null;
		}

		$sRouteHandlerFQCN = $this->FindHandlerFromRoute($sRoute);
		if (utils::IsNullOrEmptyString($sRouteHandlerFQCN)) {
			return null;
		}

		// Extract controller and method names
		$aParts = explode('::', $sRouteHandlerFQCN);
		if (count($aParts) !== 2) {
			return null;
		}

		return [$aParts[0], $aParts[1]];
	}

	/**
	 * @param string $sRoute
	 *
	 * @return array{namespace: string, operation: string}|null Route parts (namespace and operation) if route can be parsed, null otherwise
	 */
	protected function GetRouteParts(string $sRoute)
	{
		if (utils::IsNullOrEmptyString($sRoute)) {
			return null;
		}

		$sRouteNamespace = $this->GetRouteNamespace($sRoute);
		$sRouteOperation = $this->GetRouteOperation($sRoute);
		if (utils::IsNullOrEmptyString($sRouteNamespace) || utils::IsNullOrEmptyString($sRouteOperation)) {
			return null;
		}

		return ['namespace' => $sRouteNamespace, 'operation' => $sRouteOperation];
	}

	/**
	 * @param string $sRoute
	 *
	 * @return string|null Namespace of the route (eg. "object" for "object.modify") if route can be parsed null otherwise
	 */
	protected function GetRouteNamespace(string $sRoute): ?string
	{
		$mSeparatorPos = strripos($sRoute, '.', -1);
		if (false === $mSeparatorPos) {
			return null;
		}

		return substr($sRoute, 0, $mSeparatorPos);
	}

	/**
	 * @param string $sRoute
	 *
	 * @return string|null Operation of the route (eg. "modify" for "object.modify") if route can be parsed null otherwise
	 */
	protected function GetRouteOperation(string $sRoute): ?string
	{
		$mSeparatorPos = strripos($sRoute, '.', -1);
		if (false === $mSeparatorPos) {
			return null;
		}

		return substr($sRoute, $mSeparatorPos + 1);
	}

	/**
	 * @param string $sRouteToFind Route (eg. 'object.modify') to find the matching controler for
	 *
	 * @return string|null The FQCN of the handler (controller class + operation, eg. "\Combodo\iTop\Controller\Base\Layout\ObjectController::OperationModify) matching $sRouteNamespace, null if none matching.
	 */
	protected function FindHandlerFromRoute(string $sRouteToFind): ?string
	{
		foreach ($this->GetRoutes() as $sRoute => $aRouteData) {
			if ($sRoute === $sRouteToFind) {
				return $aRouteData['controller'];
			}
		}

		return null;
	}

	protected function GetCacheFileAbsPath(): string
	{
		return utils::GetCachePath().'router/available-routes.php';
	}
}