<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Router;

use Combodo\iTop\Router\Exception\RouteNotFoundException;
use ReflectionClass;
use ReflectionMethod;
use utils;

/**
 * Class Router
 *
 * Service to find the corresponding controller / method for a given "route" parameter
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Router
 * @since 3.1.0
 * @internal
 */
class Router
{
	/** @var \Combodo\iTop\Router\Router|null Singleton instance */
	protected static ?Router $oSingleton = null;

	/**
	 * @return $this The singleton instance of the router
	 */
	public static function GetInstance()
	{
		if (null === static::$oSingleton) {
			static::$oSingleton = new static();
		}

		return static::$oSingleton;
	}

	/**
	 * @return array{0: string, 1: string} Array of available routes namespaces and their corresponding controllers (eg. ['object' => '\Combodo\iTop\Controller\Base\Layout\ObjectController', ...])
	 */
	public static function GetRoutesNamespaces(): array
	{
		$aRoutesNamespaces = [];
		foreach (utils::GetClassesForInterface('Combodo\iTop\Controller\iController', '', ['[\\\\/]lib[\\\\/]', '[\\\\/]node_modules[\\\\/]', '[\\\\/]test[\\\\/]']) as $sControllerFQCN) {
			$aRoutesNamespaces[$sControllerFQCN::ROUTE_NAMESPACE] = $sControllerFQCN;
		}

		return $aRoutesNamespaces;
	}

	/**
	 * @return array{0: string, 1: string} Array of available routes and their corresponding controllers (eg. ['object.modify' => '\Combodo\iTop\Controller\Base\Layout\ObjectController::OperationModify', ...])
	 * @throws \ReflectionException
	 */
	public static function GetRoutes(): array
	{
		$aRoutes = [];
		foreach (static::GetRoutesNamespaces() as $sRouteNamespace => $sRouteControllerFQCN) {
			$oReflectionClass = new ReflectionClass($sRouteControllerFQCN);
			foreach ($oReflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $oReflectionMethod) {
				// Ignore non "operation" methods
				$sPrefix = 'Operation';
				$iPos = stripos($oReflectionMethod->name, $sPrefix);
				if ($iPos !== 0) {
					continue;
				}

				$sOperationName = substr($oReflectionMethod->name, $iPos + strlen($sPrefix));
				$aRoutes[$sRouteNamespace.'.'.utils::ToSnakeCase($sOperationName)] = $sRouteControllerFQCN.'::'.$oReflectionMethod->name;
			}
		}

		return $aRoutes;
	}

	/**********************/
	/* Non-static methods */
	/**********************/

	/**
	 * Singleton pattern, can't use the constructor. Use {@see \Combodo\iTop\Router\Router::GetInstance()} instead.
	 *
	 * @return void
	 */
	private function __construct()
	{
		// Don't do anything, we don't want to be initialized
	}

	/**
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
	 *               - A \WebPage for usual backoffice operations
	 *               - null for TwigBase backoffice operations
	 */
	public function DispatchRoute(string $sRoute)
	{
		$aMethodSpecs = $this->GetDispatchSpecsForRoute($sRoute);
		$mResponse = call_user_func_array([new $aMethodSpecs[0](), $aMethodSpecs[1]], []);

		return $mResponse;
	}

	/**
	 * @param string $sRoute
	 *
	 * @return array{sControllerFQCN, sOperationMethodName}|null The FQCN controller and operation method matching $sRoute, null if no matching handler
	 */
	public function GetDispatchSpecsForRoute(string $sRoute)
	{
		$aRouteParts = $this->GetRouteParts($sRoute);
		if (is_null($aRouteParts)) {
			return null;
		}

		$sRouteNamespace = $aRouteParts['namespace'];
		$sRouteOperation = $aRouteParts['operation'];
		$sControllerFQCN = $this->FindControllerFromRouteNamespace($sRouteNamespace);
		if (utils::IsNullOrEmptyString($sControllerFQCN)) {
			return null;
		}

		$sOperationMethodName = $this->MakeOperationMethodNameFromOperation($sRouteOperation);
		if (false === method_exists($sControllerFQCN, $sOperationMethodName)) {
			return null;
		}

		return [$sControllerFQCN, $sOperationMethodName];
	}

	/**
	 * @param string $sRoute
	 *
	 * @return array{namespace: string, operation: string}|null Route parts (namespace and operation) if route can be parsed, null otherwise
	 */
	public function GetRouteParts(string $sRoute)
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
	public function GetRouteNamespace(string $sRoute): ?string
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
	public function GetRouteOperation(string $sRoute): ?string
	{
		$mSeparatorPos = strripos($sRoute, '.', -1);
		if (false === $mSeparatorPos) {
			return null;
		}

		return substr($sRoute, $mSeparatorPos + 1);
	}

	/**
	 * @param string $sRouteNamespace {@see static::$sRouteNamespace}
	 *
	 * @return string|null The FQCN of the controller matching the $sRouteNamespace, null if none matching.
	 */
	protected function FindControllerFromRouteNamespace(string $sRouteNamespace): ?string
	{
		foreach (static::GetRoutesNamespaces() as $sControllerRouteNamespace => $sControllerFQCN) {
			if ($sControllerRouteNamespace === $sRouteNamespace) {
				return $sControllerFQCN;
			}
		}

		return null;
	}

	/**
	 * @param string $sOperation
	 *
	 * @return string The method name for the $sOperation regarding the convention
	 */
	protected function MakeOperationMethodNameFromOperation(string $sOperation): string
	{
		return 'Operation'.utils::ToCamelCase($sOperation);
	}
}