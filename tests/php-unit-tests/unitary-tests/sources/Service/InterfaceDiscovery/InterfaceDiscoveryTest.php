<?php

namespace Combodo\iTop\Test\UnitTest\Service\InterfaceDiscovery;

use Combodo\iTop\Application\UI\Base\iUIBlockFactory;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class InterfaceDiscoveryTest extends ItopDataTestCase
{
	protected function tearDown(): void
	{
		$this->SetNonPublicProperty(InterfaceDiscovery::GetInstance(), 'aForcedClassMap', null);
		parent::tearDown();
	}

	public function testShouldSelectTheRequestedItopClasses()
	{
		$oInterfaceDiscoveryService = InterfaceDiscovery::GetInstance();

		$this->GivenClassMap($oInterfaceDiscoveryService, [
			'Combodo\iTop\Application\UI\Base\Component\Alert\Alert' => APPROOT . '/sources/Application/UI/Base/Component/Alert/Alert.php',
			'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory' => APPROOT . '/sources/Application/UI/Base/Component/Alert/AlertUIBlockFactory.php',
			'Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory' => APPROOT . '/sources/Application/UI/Base/Component/ButtonGroup/ButtonGroupUIBlockFactory.php',
		]);

		$this->AssertArraysHaveSameItems(
			[
				'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory',
				'Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory',
			],
			$oInterfaceDiscoveryService->FindItopClasses(iUIBlockFactory::class)
		);
	}

	public function testShouldExcludeSpecifiedDirectories()
	{
		$oInterfaceDiscoveryService = InterfaceDiscovery::GetInstance();

		$this->GivenClassMap($oInterfaceDiscoveryService, [
			'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory' => APPROOT . '/sources/Application/UI/Base/Component/Alert/AlertUIBlockFactory.php',
			'Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory' => APPROOT . '/sources/Application/UI/Base/Component/ButtonGroup/ButtonGroupUIBlockFactory.php',
		]);

		$this->AssertArraysHaveSameItems(
			[],
			$oInterfaceDiscoveryService->FindItopClasses(iUIBlockFactory::class, ['Component/ButtonGroup', '/Alert/'])
		);
	}
	public function testShouldExcludeAliases()
	{
		$oInterfaceDiscoveryService = InterfaceDiscovery::GetInstance();

		$this->GivenClassMap($oInterfaceDiscoveryService, [
			'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory' => APPROOT . '/sources/Application/UI/Base/Component/Alert/AlertUIBlockFactory.php',
			'AlbertIsBlockingTheFactory' => APPROOT . '/sources/Application/UI/Base/Component/Alert/AlertUIBlockFactory.php',
		]);

		class_alias('Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory', 'AlbertIsBlockingTheFactory');

		$this->AssertArraysHaveSameItems(
			[
				'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory',
			],
			$oInterfaceDiscoveryService->FindItopClasses(iUIBlockFactory::class)
		);
	}

	private function GivenClassMap(InterfaceDiscovery $oInterfaceDiscoveryService, array $aClassMap): void
	{
		$this->SetNonPublicProperty($oInterfaceDiscoveryService, 'aForcedClassMap', $aClassMap);
	}
}
