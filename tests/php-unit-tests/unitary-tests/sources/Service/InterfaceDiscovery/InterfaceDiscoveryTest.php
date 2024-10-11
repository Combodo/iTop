<?php

namespace Combodo\iTop\Test\UnitTest\Service\InterfaceDiscovery;

use Combodo\iTop\Application\UI\Base\iUIBlockFactory;
use Combodo\iTop\Service\Cache\DataModelDependantCache;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

class InterfaceDiscoveryTest extends ItopDataTestCase
{
	private InterfaceDiscovery $oInterfaceDiscovery;
	private string $sCacheRootDir;
	private DataModelDependantCache $oCacheService;

	protected function setUp(): void
	{
		parent::setUp();
		$this->oInterfaceDiscovery = InterfaceDiscovery::GetInstance();
		$this->sCacheRootDir = self::CreateTmpdir();
		$this->oCacheService = DataModelDependantCache::GetInstance();
		$this->oCacheService->SetStorageRootDir($this->sCacheRootDir);
		$this->oInterfaceDiscovery->SetCacheService($this->oCacheService);
	}

	protected function tearDown(): void
	{
		$this->SetNonPublicProperty(InterfaceDiscovery::GetInstance(), 'aForcedClassMap', null);
		$this->oCacheService->SetStorageRootDir(null);
		self::RecurseRmdir($this->sCacheRootDir);
		parent::tearDown();
	}

	public function testShouldSelectTheRequestedItopClasses()
	{
		$this->GivenClassMap([
			'Combodo\iTop\Application\UI\Base\Component\Alert\Alert' => APPROOT . '/sources/Application/UI/Base/Component/Alert/Alert.php',
			'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory' => APPROOT . '/sources/Application/UI/Base/Component/Alert/AlertUIBlockFactory.php',
			'Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory' => APPROOT . '/sources/Application/UI/Base/Component/ButtonGroup/ButtonGroupUIBlockFactory.php',
		]);

		$this->AssertArraysHaveSameItems(
			[
				'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory',
				'Combodo\iTop\Application\UI\Base\Component\ButtonGroup\ButtonGroupUIBlockFactory',
			],
			$this->oInterfaceDiscovery->FindItopClasses(iUIBlockFactory::class)
		);
	}

	public function testShouldExcludeAliases()
	{
		$this->GivenClassMap([
			'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory' => APPROOT . '/sources/Application/UI/Base/Component/Alert/AlertUIBlockFactory.php',
			'AlbertIsBlockingTheFactory' => APPROOT . '/sources/Application/UI/Base/Component/Alert/AlertUIBlockFactory.php',
		]);

		class_alias('Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory', 'AlbertIsBlockingTheFactory');

		$this->AssertArraysHaveSameItems(
			[
				'Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory',
			],
			$this->oInterfaceDiscovery->FindItopClasses(iUIBlockFactory::class)
		);
	}

	public function testShouldNotProduceCacheForDevelopers()
	{
		DataModelDependantCache::GetInstance()->Clear('InterfaceDiscovery');

		MetaModel::GetConfig()->Set('developer_mode.enabled', true);
		MetaModel::GetConfig()->Set('developer_mode.interface_cache.enabled', false);

		$this->assertGreaterThan(0, count($this->oInterfaceDiscovery->FindItopClasses(iUIBlockFactory::class)));
		$this->assertFileDoesNotExist($this->sCacheRootDir.'/InterfaceDiscovery');
	}

	public function testShouldProduceDynamicCacheForDevelopersWillingTo()
	{
		DataModelDependantCache::GetInstance()->Clear('InterfaceDiscovery');

		MetaModel::GetConfig()->Set('developer_mode.enabled', true);
		MetaModel::GetConfig()->Set('developer_mode.interface_cache.enabled', true);

		$this->assertGreaterThan(0, count($this->oInterfaceDiscovery->FindItopClasses(iUIBlockFactory::class)));
		$this->AssertDirectoryListingEquals([
			'autoload_classmaps.php',
			'310db363d8e32bfcf57cbb3800912ea2_iUIBlockFactory.php'
		],
			$this->sCacheRootDir.'/InterfaceDiscovery');
	}

	public function testShouldProduceStaticCacheForProduction()
	{
		DataModelDependantCache::GetInstance()->Clear('InterfaceDiscovery');

		MetaModel::GetConfig()->Set('developer_mode.enabled', false);

		$this->assertGreaterThan(0, count($this->oInterfaceDiscovery->FindItopClasses(iUIBlockFactory::class)));
		$this->AssertDirectoryListingEquals(['310db363d8e32bfcf57cbb3800912ea2_iUIBlockFactory.php'], $this->sCacheRootDir.'/InterfaceDiscovery');
	}

	private function GivenClassMap(array $aClassMap): void
	{
		$this->SetNonPublicProperty($this->oInterfaceDiscovery, 'aForcedClassMap', $aClassMap);
	}
}
