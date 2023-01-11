<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Router\Router;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * Class RouterTest
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 3.1.0
 * @covers \Combodo\iTop\Router\Router
 */
class RouterTest extends ItopTestCase
{
	/**
	 * @dataProvider CanDispatchRouteProvider
	 * @covers \Combodo\iTop\Router\Router::CanDispatchRoute
	 *
	 * @param string $sRoute
	 * @param $bExpectedResult
	 *
	 * @return void
	 */
//	public function testCanDispatchRoute(string $sRoute, $bExpectedResult): void
//	{
//		$oRouter = Router::GetInstance();
//		$bTestedResult = $oRouter->CanDispatchRoute($sRoute);
//
//		$sRouteNamespace = $oRouter->GetRouteNamespace($sRoute);
//		$sRouteOperation = $oRouter->GetRouteOperation($sRoute);
//		$aRouteParts = $oRouter->GetRouteParts($sRoute);
//		$sControllerFQCN = $this->InvokeNonPublicMethod(get_class($oRouter), 'FindControllerFromRouteNamespace', $oRouter, ['object']);
//		$sMethodName = $this->InvokeNonPublicMethod(get_class($oRouter), 'MakeOperationMethodNameFromOperation', $oRouter, ['modify']);
//		$aDispatchSpecs = $oRouter->GetDispatchSpecsForRoute($sRoute);
//
//$this->debug($sRoute);
//$this->debug($sRouteNamespace);
//$this->debug($sRouteOperation);
//$this->debug($aRouteParts);
//$this->debug($sControllerFQCN);
//$this->debug($sMethodName);
//$this->debug(is_callable([$sControllerFQCN, $sMethodName]) ? 'true' : 'false');
//$this->debug($aDispatchSpecs);
//$this->debug($bTestedResult);
//		$this->assertEquals($bExpectedResult, $bTestedResult, "Dispatch capability for '$sRoute' was not the expected one. Got ".var_export($bTestedResult, true).", expected ".var_export($bExpectedResult, true));
//	}

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
	 * @dataProvider GetRouteNamespaceProvider
	 * @covers \Combodo\iTop\Router\Router::GetRouteNamespace
	 *
	 * @param string $sRoute
	 * @param string|null $sExpectedNamespace
	 *
	 * @return void
	 */
	public function testGetRouteNamespace(string $sRoute, ?string $sExpectedNamespace): void
	{
		$oRouter = Router::GetInstance();
		$sTestedNamespace = $oRouter->GetRouteNamespace($sRoute);

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
	 * @covers \Combodo\iTop\Router\Router::GetRouteOperation
	 *
	 * @param string $sRoute
	 * @param string|null $sExpectedOperation
	 *
	 * @return void
	 */
	public function testGetRouteOperation(string $sRoute, ?string $sExpectedOperation): void
	{
		$oRouter = Router::GetInstance();
		$sTestedOperation = $oRouter->GetRouteOperation($sRoute);

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
	 * @dataProvider FindControllerFromRouteNamespaceProvider
	 * @covers \Combodo\iTop\Router\Router::FindControllerFromRouteNamespace
	 *
	 * @param string $sRouteNamespace
	 * @param string $sExpectedControllerFQCN
	 *
	 * @return void
	 */
	public function testFindControllerFromRouteNamespace(string $sRoute, ?string $sExpectedControllerFQCN): void
	{
		$oRouter = Router::GetInstance();
		$sRouteNamespace = $oRouter->GetRouteNamespace($sRoute);

		$sTestedControllerFQCN = $this->InvokeNonPublicMethod(get_class($oRouter), 'FindControllerFromRouteNamespace', $oRouter, [$sRouteNamespace]);

		$this->assertEquals($sExpectedControllerFQCN, $sTestedControllerFQCN, "Controller found for '$sRouteNamespace' was not the expected one. Got '$sTestedControllerFQCN', expected '$sExpectedControllerFQCN'.");
	}

	public function FindControllerFromRouteNamespaceProvider(): array
	{
		return [
			'Object controller' => [
				'object.modify',
				'Combodo\iTop\Controller\Base\Layout\ObjectController',
			],
			'Unknown controller' => [
				'something_that_should_not_exist_in_the_default_package.foo',
				null,
			],
		];
	}

	/**
	 * @dataProvider GetOperationMethodNameFromRouteOperationProvider
	 * @covers \Combodo\iTop\Router\Router::MakeOperationMethodNameFromOperation
	 *
	 * @param string $sRoute
	 * @param string $sExpectedMethodName
	 *
	 * @return void
	 */
	public function testGetOperationMethodNameFromRouteOperation(string $sRoute, string $sExpectedMethodName): void
	{
		$oRouter = Router::GetInstance();
		$aRouteParts = $oRouter->GetRouteParts($sRoute);

		$sTestedMethodName = $this->InvokeNonPublicMethod(get_class($oRouter), 'MakeOperationMethodNameFromOperation', $oRouter, [$aRouteParts[1]]);

		$this->assertEquals($sExpectedMethodName, $sTestedMethodName, "Operation method name '$aRouteParts[1]' was not matching the expected one. Got '$sTestedMethodName', expected '$sExpectedMethodName'.");
	}

	public function GetOperationMethodNameFromRouteOperationProvider(): array
	{
		return [
			'Simple operation' => [
				'object.modify',
				'OperationModify',
			],
			'Operation with an underscore' => [
				'object.apply_modify',
				'OperationApplyModify',
			],
		];
	}
}