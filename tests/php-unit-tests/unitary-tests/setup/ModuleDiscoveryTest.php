<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Setup\ModuleDiscovery\ModuleFileReader;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use iTopExtension;
use MissingDependencyException;
use ModuleDiscovery;

class ModuleDiscoveryTest extends ItopTestCase
{
	public function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/runtimeenv.class.inc.php');
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
	}

	protected function tearDown(): void
	{
		parent::tearDown();
		ModuleDiscovery::DeclareRemovedExtensions([]);
	}

	public function testOrderModulesByDependencies_RealExample()
	{
		$aModules = json_decode(file_get_contents(__DIR__.'/ressources/reallife_discovered_modules.json'), true);

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true);

		$aExpected = json_decode(file_get_contents(__DIR__.'/ressources/reallife_expected_ordered_modules.json'), true);
		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_LoadOnlyChoosenModules()
	{
		$aChoices = ['id1', 'id2'];

		$aModules = [
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id2/2" => [
				'dependencies' => [],
				'label' => 'label2',
			],
			"id3/3" => [
				'dependencies' => [],
				'label' => 'label3',
			],
		];

		$aResult = ModuleDiscovery::OrderModulesByDependencies($aModules, true, $aChoices);

		$aExpected = [
			"id2/2",
			"id1/1",
		];
		$this->assertEquals($aExpected, array_keys($aResult));
	}

	public function testOrderModulesByDependencies_FailWhenChoosenModuleDependsOnUnchoosenModule()
	{
		$aChoices = ['id1'];

		$aModules = [
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
			],
			"id2/2" => [
				'dependencies' => [],
				'label' => 'label2',
			],
		];

		$sExpectedMessage = <<<TXT
The following modules have unmet dependencies:
label1 (id: id1/1) depends on: ❌ id2/2
TXT;
		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sExpectedMessage);

		ModuleDiscovery::OrderModulesByDependencies($aModules, true, $aChoices);
	}

	public function testOrderModulesByDependencies_FailWhenChoosenModuleDependsOnRemovedExtensionModule()
	{
		$aChoices = ['id1', 'id2'];

		$sModuleFilePath = tempnam(sys_get_temp_dir(), 'discovery_test');
		$this->aFileToClean[] = $sModuleFilePath;
		$sModuleFilePath2 = tempnam(sys_get_temp_dir(), 'discovery_test');
		$this->aFileToClean[] = $sModuleFilePath2;

		$aModules = [
			"id1/1" => [
				'dependencies' => [ 'id2/2'],
				'label' => 'label1',
				ModuleFileReader::MODULE_FILE_PATH => $sModuleFilePath,
			],
			"id2/2" => [
				'dependencies' => [],
				'label' => 'label2',
				ModuleFileReader::MODULE_FILE_PATH => $sModuleFilePath2,
			],
		];

		$oExtension = $this->CreateExtensionWithModule('id2', '2', $sModuleFilePath2);
		ModuleDiscovery::DeclareRemovedExtensions([$oExtension]);

		$sExpectedMessage = <<<TXT
The following modules have unmet dependencies:
label1 (id: id1/1) depends on: ❌ id2/2
TXT;
		$this->expectException(MissingDependencyException::class);
		$this->expectExceptionMessage($sExpectedMessage);

		ModuleDiscovery::OrderModulesByDependencies($aModules, true, $aChoices);
	}

	public function GetModuleNameProvider()
	{
		return [
			'nominal' => [
				'sModuleId' => 'a/1.2.3',
				'name' => 'a',
				'version' => '1.2.3',
			],
			'develop' => [
				'sModuleId' => 'a/1.2.3-dev',
				'name' => 'a',
				'version' => '1.2.3-dev',
			],
			'missing version => 1.0.0' => [
				'sModuleId' => 'a/',
				'name' => 'a',
				'version' => '1.0.0',
			],
			'missing everything except name' => [
				'sModuleId' => 'a',
				'name' => 'a',
				'version' => '1.0.0',
			],
		];
	}

	/**
	 * @dataProvider GetModuleNameProvider
	 */
	public function testGetModuleName($sModuleId, $expectedName, $expectedVersion)
	{
		$this->assertEquals([$expectedName, $expectedVersion], ModuleDiscovery::GetModuleName($sModuleId));
	}

	public function testIsModulePartOfRemovedExtension_NoRemovedExtension()
	{
		ModuleDiscovery::DeclareRemovedExtensions([]);
		$this->assertFalse($this->InvokeNonPublicStaticMethod(ModuleDiscovery::class, "IsModulePartOfRemovedExtension", ['module_name', '123', []]));
	}

	public function testIsModulePartOfRemovedExtension_ModuleWithAnotherVersionIncludedInRemoveExtension()
	{
		$sModuleFilePath = tempnam(sys_get_temp_dir(), 'discovery_test');
		$this->ValidateIsModulePartOfRemovedExtension('module_name', '123', $sModuleFilePath, $sModuleFilePath, false);
	}

	public function testIsModulePartOfRemovedExtension_AnotherModuleWithSameVersionIncludedInRemoveExtension()
	{
		$sModuleFilePath = tempnam(sys_get_temp_dir(), 'discovery_test');
		$this->ValidateIsModulePartOfRemovedExtension('another_module_name', '456', $sModuleFilePath, $sModuleFilePath, false);
	}

	public function testIsModulePartOfRemovedExtension_SameExtensionComingFromAnotherLocation()
	{
		$sModuleFilePath = tempnam(sys_get_temp_dir(), 'discovery_test');
		$sModuleFilePath2 = tempnam(sys_get_temp_dir(), 'discovery_test');
		$this->ValidateIsModulePartOfRemovedExtension('module_name', '456', $sModuleFilePath, $sModuleFilePath2, false);
	}

	public function testIsModulePartOfRemovedExtension_ModuleShouldBeExcluded()
	{
		$sModuleFilePath = tempnam(sys_get_temp_dir(), 'discovery_test');
		$this->ValidateIsModulePartOfRemovedExtension('module_name', '456', $sModuleFilePath, $sModuleFilePath, true);
	}

	public function ValidateIsModulePartOfRemovedExtension($sModuleName, $sModuleVersion, $sModuleFilePath1, $sModuleFilePath2, $bExpected)
	{
		$this->aFileToClean[] = $sModuleFilePath1;
		$this->aFileToClean[] = $sModuleFilePath2;

		$oExtension = $this->CreateExtensionWithModule('module_name', '456', $sModuleFilePath2);
		ModuleDiscovery::DeclareRemovedExtensions([$oExtension]);
		$aCurrentModuleInfo = [
			ModuleFileReader::MODULE_FILE_PATH => $sModuleFilePath1,
		];
		$this->assertEquals(
			$bExpected,
			$this->InvokeNonPublicStaticMethod(ModuleDiscovery::class, "IsModulePartOfRemovedExtension", [$sModuleName, $sModuleVersion, $aCurrentModuleInfo])
		);
	}

	private function CreateExtensionWithModule(string $sModuleName, string $sVersion, bool|string $sModuleFilePath): iTopExtension
	{
		$oExt = new iTopExtension();
		$oExt->aModuleVersion[$sModuleName] = $sVersion;
		$oExt->aModuleInfo[$sModuleName] = [
			ModuleFileReader::MODULE_FILE_PATH => $sModuleFilePath,
		];
		return $oExt;
	}
}
