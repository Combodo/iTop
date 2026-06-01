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

		$sModuleFilePath = $this->GetTemporaryFilePath();
		$sModuleFilePath2 = $this->GetTemporaryFilePath();

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

		$oExtension = $this->GivenExtensionWithModule('id2', '2', $sModuleFilePath2);
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

	public function testIsModuleInExtensionList_NoRemovedExtension()
	{
		$this->assertFalse($this->InvokeNonPublicStaticMethod(ModuleDiscovery::class, "IsModuleInExtensionList", [[], 'module_name', '123', []]));
	}

	public function testIsModuleInExtensionList_ModuleWithAnotherVersionIncludedInRemoveExtension()
	{
		$sModuleFilePath = $this->GetTemporaryFilePath();
		$aExtensionList = [$this->GivenExtensionWithModule('module_name', '456', $sModuleFilePath)];
		$this->AssertModuleIsPartOfRemovedExtension($aExtensionList, 'module_name', '123', $sModuleFilePath, false);
	}

	public function testIsModuleInExtensionList_AnotherModuleWithSameVersionIncludedInRemoveExtension()
	{
		$sModuleFilePath = $this->GetTemporaryFilePath();
		$aExtensionList = [$this->GivenExtensionWithModule('module_name', '456', $sModuleFilePath)];
		$this->AssertModuleIsPartOfRemovedExtension($aExtensionList, 'another_module_name', '456', $sModuleFilePath, false);
	}

	public function testIsModuleInExtensionList_SameExtensionComingFromAnotherLocation()
	{
		$sModuleFilePath = $this->GetTemporaryFilePath();
		$sModuleFilePath2 = $this->GetTemporaryFilePath();
		$aExtensionList = [$this->GivenExtensionWithModule('module_name', '456', $sModuleFilePath2)];
		$this->AssertModuleIsPartOfRemovedExtension($aExtensionList, 'module_name', '456', $sModuleFilePath, false);
	}

	public function testIsModuleInExtensionList_ModuleShouldBeExcluded()
	{
		$sModuleFilePath = $this->GetTemporaryFilePath();
		$aExtensionList = [$this->GivenExtensionWithModule('module_name', '456', $sModuleFilePath)];
		$this->AssertModuleIsPartOfRemovedExtension($aExtensionList, 'module_name', '456', $sModuleFilePath, true);
	}

	public function AssertModuleIsPartOfRemovedExtension($aExtensionList, $sModuleName, $sModuleVersion, $sModuleFilePath, $bExpected)
	{
		$aCurrentModuleInfo = [
			ModuleFileReader::MODULE_FILE_PATH => $sModuleFilePath,
		];
		$this->assertEquals(
			$bExpected,
			$this->InvokeNonPublicStaticMethod(ModuleDiscovery::class, "IsModuleInExtensionList", [$aExtensionList, $sModuleName, $sModuleVersion, $aCurrentModuleInfo])
		);
	}

	private function GivenExtensionWithModule(string $sModuleName, string $sVersion, bool|string $sModuleFilePath): iTopExtension
	{
		$oExt = new iTopExtension();
		$oExt->aModuleVersion[$sModuleName] = $sVersion;
		$oExt->aModuleInfo[$sModuleName] = [
			ModuleFileReader::MODULE_FILE_PATH => $sModuleFilePath,
		];
		return $oExt;
	}
}
