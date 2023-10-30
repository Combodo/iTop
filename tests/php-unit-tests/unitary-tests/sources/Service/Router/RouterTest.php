<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Service\Router;

use Combodo\iTop\Service\Router\Exception\RouteNotFoundException;
use Combodo\iTop\Service\Router\Router;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use utils;

/**
 * Class RouterTest
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.1.0
 * @covers \Combodo\iTop\Service\Router\Router
 */
class RouterTest extends ItopDataTestCase
{
	/**
	 * @inheritDoc
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('setup/setuputils.class.inc.php');

		// Speedup test by forcing the use of the cache, even on a development environment
		$oRouter = Router::GetInstance();
		$oRouter->SetUseCache(true);
	}

	/**
	 * @covers \Combodo\iTop\Service\Router\Router::GenerateUrl
	 * @dataProvider GenerateUrlProvider
	 *
	 * @param string $sExpectedUrl URL contains a <APP_ROOT_URL> placeholder that will be replaced with the real app root url at run time
	 * @param bool $bValid
	 * @param string $sRoute
	 * @param array $aParams
	 * @param bool $bAbsoluteUrl
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testGenerateUrl(string $sExpectedUrl, bool $bValid, string $sRoute, array $aParams, bool $bAbsoluteUrl = true): void
	{
		$oRouter = Router::GetInstance();

		if (false === $bValid) {
			$this->expectException(RouteNotFoundException::class);
		}
		$sTestedUrl = $oRouter->GenerateUrl($sRoute, $aParams, $bAbsoluteUrl);
		$sExpectedUrl = str_ireplace('<APP_ROOT_URL>', utils::GetAbsoluteUrlAppRoot(), $sExpectedUrl);

		$this->assertEquals($sTestedUrl, $sExpectedUrl, 'Generated URL does not match');
	}

	public function GenerateUrlProvider(): array
	{
		return [
			'invalid route' => [
				'',
				false,
				'foo.bar',
				[],
				true,
			],
			'relative route with no params' => [
				'pages/UI.php?route=object.modify',
				true,
				'object.modify',
				[],
				false,
			],
			'absolute route with no params' => [
				'<APP_ROOT_URL>pages/UI.php?route=object.modify',
				true,
				'object.modify',
				[],
				true,
			],
			'absolute route with scalar params' => [
				'<APP_ROOT_URL>pages/UI.php?route=object.modify&class=Person&id=123',
				true,
				'object.modify',
				[
					'class' => 'Person',
					'id' => 123
				],
				true,
			],
			'absolute route with 1 dimension array params' => [
				'<APP_ROOT_URL>pages/UI.php?route=object.modify&class=Person&id=123&default%5Bname%5D=Castor&default%5Bfirst_name%5D=P%C3%A8re',
				true,
				'object.modify',
				[
					'class' => 'Person',
					'id' => 123,
					'default' => [
						'name' => 'Castor',
						'first_name' => 'Père',
					],
				],
				true,
			],
			'absolute route with 2 dimensions array params' => [
				'<APP_ROOT_URL>pages/UI.php?route=object.modify&class=Person&id=123&default%5Bname%5D=Castor&default%5Bfirst_name%5D=P%C3%A8re&foo%5Bfirst%5D%5B0%5D=10&foo%5Bfirst%5D%5B1%5D=20&foo%5Bsecond%5D%5B0%5D=30&foo%5Bsecond%5D%5B1%5D=40',
				true,
				'object.modify',
				[
					'class' => 'Person',
					'id' => 123,
					'default' => [
						'name' => 'Castor',
						'first_name' => 'Père',
					],
					'foo' => [
						'first' => ['10', '20'],
						'second' => ['30', '40'],
					],
				],
				true,
			],
		];
	}

	/**
	 * @dataProvider CanDispatchRouteProvider
	 * @covers \Combodo\iTop\Service\Router\Router::CanDispatchRoute
	 *
	 * @param string $sRoute
	 * @param $bExpectedResult
	 *
	 * @return void
	 */
	public function testCanDispatchRoute(string $sRoute, $bExpectedResult): void
	{
		$oRouter = Router::GetInstance();
		$bTestedResult = $oRouter->CanDispatchRoute($sRoute);

		$this->assertEquals($bExpectedResult, $bTestedResult, "Dispatch capability for '$sRoute' was not the expected one. Got ".var_export($bTestedResult, true).", expected ".var_export($bExpectedResult, true));
	}

	public function CanDispatchRouteProvider(): array
	{
		return [
			'Existing handler' => [
				'object.modify',
				true,
			],
			'Existing controller but unknown operation' => [
				'object.modify_me_please',
				false,
			],
			'Unknown controller' => [
				'foo.bar',
				false,
			],
		];
	}

	/**
	 * @dataProvider GetRoutesProvider
	 * @covers \Combodo\iTop\Service\Router\Router::GetRoutes
	 *
	 * @param string $sRoute
	 * @param bool $bShouldBePresent
	 *
	 * @return void
	 * @throws \ReflectionException
	 */
	public function testGetRoutes(string $sRoute, bool $bShouldBePresent): void
	{
		$oRouter = Router::GetInstance();
		$aTestedRoutes = $this->InvokeNonPublicMethod(Router::class, 'GetRoutes', $oRouter, []);

		$bIsPresent = array_key_exists($sRoute, $aTestedRoutes);
		$this->assertEquals($bShouldBePresent, $bIsPresent, "Route '$sRoute' was not expected amongst the available routes.");
	}

	/**
	 * @covers \Combodo\iTop\Service\Router\Router::GetRoutes
	 * @return void
	 *
	 * @since N°6618 Covers that the cache isn't re-generated at each call of the GetRoutes method
	 */
	public function testGetRoutesCacheGeneratedOnlyOnce(): void
	{
		$oRouter = Router::GetInstance();
		$sRoutesCacheFilePath = $this->InvokeNonPublicMethod(Router::class, 'GetCacheFileAbsPath', $oRouter, []);

		// Developer mode must be disabled for the routes cache to be used
		$oConf = utils::GetConfig();
		$mDeveloperModePreviousValue = $oConf->Get('developer_mode.enabled');
		$oConf->Set('developer_mode.enabled', false);

		// Generate cache for first time
		$this->InvokeNonPublicMethod(Router::class, 'GetRoutes', $oRouter, []);

		// Check that file exists and retrieve modification timestamp
		if (false === is_file($sRoutesCacheFilePath)) {
			$this->fail("Cache file was not generated ($sRoutesCacheFilePath)");
		}

		// Set its modification date in the past so that regenerating it will result in a new modification date without any doubt
		$iFirstModificationTimestamp = time() - 2;
		touch($sRoutesCacheFilePath, $iFirstModificationTimestamp);

		// Call GetRoutes() again to see if cache gets re-generated or not
		$this->InvokeNonPublicMethod(Router::class, 'GetRoutes', $oRouter, []);

		// Check that file still exists and that modification timestamp has not changed
		if (false === is_file($sRoutesCacheFilePath)) {
			$this->fail("Cache file is no longer present, that should not happen! ($sRoutesCacheFilePath)");
		}

		clearstatcache();
		$iSecondModificationTimestamp = filemtime($sRoutesCacheFilePath);
		$this->debug("Second timestamp: $iSecondModificationTimestamp");

		$this->assertSame($iFirstModificationTimestamp, $iSecondModificationTimestamp, "Cache file timestamp changed, seems like cache is not working and was re-generated when it should not!");

		// Restore previous value for following tests
		$oConf->Set('developer_mode.enabled', $mDeveloperModePreviousValue);
	}

	/**
	 * @covers \Combodo\iTop\Service\Router\Router::GetRoutes
	 * @return void
	 *
	 * @since N°6618 Covers that the cache is re-generated correctly if corrupted
	 */
	public function testGetRoutesCacheRegeneratedCorrectlyIfCorrupted(): void
	{
		$oRouter = Router::GetInstance();
		$sRoutesCacheFilePath = $this->InvokeNonPublicMethod(Router::class, 'GetCacheFileAbsPath', $oRouter, []);

		// Developer mode must be disabled for the routes cache to be used
		$oConf = utils::GetConfig();
		$mDeveloperModePreviousValue = $oConf->Get('developer_mode.enabled');
		$oConf->Set('developer_mode.enabled', false);

		// Generate corrupted cache manually
		$sFaultyStatement = 'return 1;';
		file_put_contents($sRoutesCacheFilePath, <<<PHP
<?php

{$sFaultyStatement}
PHP
		);

		// Retrieve routes to access / fix cache in the process
		$aRoutes = $this->InvokeNonPublicMethod(Router::class, 'GetRoutes', $oRouter, []);

		// Check that routes are an array
		$this->assertTrue(is_array($aRoutes));

		// Check that file content doesn't contain `return 1`
		clearstatcache();
		$this->assertStringNotContainsString($sFaultyStatement, file_get_contents($sRoutesCacheFilePath), "Cache file still contains the faulty statement ($sFaultyStatement)");

		// Restore previous value for following tests
		$oConf->Set('developer_mode.enabled', $mDeveloperModePreviousValue);
	}

	public function GetRoutesProvider(): array
	{
		return [
			'Valid route' => [
				'object.modify',
				true,
			],
			// eg. a route from a controller that has no ROUTE_NAMESPACE
			'Invalid route' => [
				'.save_state',
				false,
			],
		];
	}

	/**
	 * @dataProvider GetRoutePartsProvider
	 * @covers \Combodo\iTop\Service\Router\Router::GetRouteParts
	 *
	 * @param string $sRoute
	 * @param array|null $aExpectedParts
	 *
	 * @return void
	 */
	public function testGetRouteParts(string $sRoute, ?array $aExpectedParts): void
	{
		$oRouter = Router::GetInstance();
		$aTestedParts = $this->InvokeNonPublicMethod(Router::class, 'GetRouteParts', $oRouter, [$sRoute]);

		$this->assertEquals($aExpectedParts, $aTestedParts, "Parts found for '$sRoute' were not the expected ones. Got '".print_r($aTestedParts, true)."', expected '".print_r($aExpectedParts, true)."'.");
	}

	public function GetRoutePartsProvider(): array
	{
		return [
			'Empty route' => [
				'',
				null,
			],
			// eg. controller implmenting iController but without the ROUTE_NAMESPACE content. This is for BC compatibility
			'Route with no namespace' => [
				'.some_operation',
				null,
			],
			'Route with no operation' => [
				'some_namespace.',
				null,
			],
			'Valid route' => [
				'some_namespace.some_operation',
				['namespace' => 'some_namespace', 'operation' => 'some_operation'],
			],
			'Valid route with deep namespace' => [
				'some.deep.namespace.some_operation',
				['namespace' => 'some.deep.namespace', 'operation' => 'some_operation'],
			],
		];
	}

	/**
	 * @dataProvider GetRouteNamespaceProvider
	 * @covers \Combodo\iTop\Service\Router\Router::GetRouteNamespace
	 *
	 * @param string $sRoute
	 * @param string|null $sExpectedNamespace
	 *
	 * @return void
	 */
	public function testGetRouteNamespace(string $sRoute, ?string $sExpectedNamespace): void
	{
		$oRouter = Router::GetInstance();
		$sTestedNamespace = $this->InvokeNonPublicMethod(Router::class, 'GetRouteNamespace', $oRouter, [$sRoute]);

		$this->assertEquals($sExpectedNamespace, $sTestedNamespace, "Namespace found for '$sRoute' was not the expected one. Got '$sTestedNamespace', expected '$sExpectedNamespace'.");
	}

	public function GetRouteNamespaceProvider(): array
	{
		return [
			'Operation without namespace' => [
				'some_operation',
				null,
			],
			'Operation with namespace' => [
				'some_namespace.some_operation',
				'some_namespace',
			],
			'Operation with multi-levels namespace' => [
				'some.deep.namespace.some_operation',
				'some.deep.namespace',
			],
		];
	}

	/**
	 * @dataProvider GetRouteOperationProvider
	 * @covers \Combodo\iTop\Service\Router\Router::GetRouteOperation
	 *
	 * @param string $sRoute
	 * @param string|null $sExpectedOperation
	 *
	 * @return void
	 */
	public function testGetRouteOperation(string $sRoute, ?string $sExpectedOperation): void
	{
		$oRouter = Router::GetInstance();
		$sTestedOperation = $this->InvokeNonPublicMethod(Router::class, 'GetRouteOperation', $oRouter, [$sRoute]);

		$this->assertEquals($sExpectedOperation, $sTestedOperation, "Operation found for '$sRoute' was not the expected one. Got '$sTestedOperation', expected '$sExpectedOperation'.");
	}

	public function GetRouteOperationProvider(): array
	{
		return [
			'Operation without namespace' => [
				'some_operation',
				null,
			],
			'Operation with namespace' => [
				'some_namespace.some_operation',
				'some_operation',
			],
			'Operation with multi-levels namespace' => [
				'some.deep.namespace.some_operation',
				'some_operation',
			],
		];
	}

	/**
	 * @dataProvider FindHandlerFromRouteProvider
	 * @covers \Combodo\iTop\Service\Router\Router::FindHandlerFromRoute
	 *
	 * @param string $sRouteNamespace
	 * @param string $sExpectedHandlerFQCN
	 *
	 * @return void
	 */
	public function testFindHandlerFromRoute(string $sRoute, ?string $sExpectedHandlerFQCN): void
	{
		$oRouter = Router::GetInstance();
		$sTestedHandlerFQCN = $this->InvokeNonPublicMethod(Router::class, 'FindHandlerFromRoute', $oRouter, [$sRoute]);

		$this->assertEquals($sExpectedHandlerFQCN, $sTestedHandlerFQCN, "Handler found for '$sRoute' was not the expected one. Got '$sTestedHandlerFQCN', expected '$sExpectedHandlerFQCN'.");
	}

	public function FindHandlerFromRouteProvider(): array
	{
		return [
			'Object controller' => [
				'object.modify',
				'Combodo\iTop\Controller\Base\Layout\ObjectController::OperationModify',
			],
			'Unknown controller' => [
				'something_that_should_not_exist_in_the_default_package.foo',
				null,
			],
		];
	}
}