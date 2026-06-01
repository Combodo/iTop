<?php

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class iTopExtensionTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/setup/unattended-install/InstallationFileService.php');
		ModuleDiscovery::ResetCache();
	}

	public function testCanBeUninstalledDefaultValueIsTrue()
	{
		$oExtension = new iTopExtension();
		$this->assertTrue($oExtension->CanBeUninstalled(), 'An extension should be uninstallable by default.');
	}

	public function testCanBeUninstalledReturnTrueWhenAllModulesCanBeUninstalled()
	{
		$oExtension = new iTopExtension();
		$oExtension->aModuleInfo['combodo-test-yes1'] = [
			'uninstallable' => 'yes',
		];
		$oExtension->aModuleInfo['combodo-test-yes2'] = [
			'uninstallable' => 'yes',
		];
		$this->assertTrue($oExtension->CanBeUninstalled(), 'An extension should be considered uninstallable if all of its modules are uninstallable.');
	}

	public function testCanBeUninstalledReturnFalseWhenAtLeastOneModuleCannotBeUninstalled()
	{
		$oExtension = new iTopExtension();
		$oExtension->aModuleInfo['combodo-test-yes'] = [
			'uninstallable' => 'yes',
		];
		$oExtension->aModuleInfo['combodo-test-no'] = [
			'uninstallable' => 'no',
		];
		$this->assertFalse($oExtension->CanBeUninstalled(), 'An extension should be considered non-uninstallable if at least one of its modules is not uninstallable.');
	}

	public function testCanBeUninstalledAnyValueDifferentThanYesIsConsideredFalse()
	{
		$oExtension = new iTopExtension();
		$oExtension->aModuleInfo['combodo-test-maybe'] = [
			'uninstallable' => 'maybe',
		];
		$this->assertFalse($oExtension->CanBeUninstalled(), 'Any value in the uninstallable flag different than yes should be considered false.');
	}

	public function testCanBeUninstalledExtensionValueOverwriteModulesValue()
	{
		$oExtension = new iTopExtension();
		$oExtension->bCanBeUninstalled = true;
		$oExtension->aModuleInfo['combodo-test-no'] = [
			'uninstallable' => 'no',
		];
		$this->assertTrue($oExtension->CanBeUninstalled(), 'The uninstallable flag provided in the extension should prevail over those defined in the modules.');

		$oExtension = new iTopExtension();
		$oExtension->bCanBeUninstalled = false;
		$oExtension->aModuleInfo['combodo-test-yes'] = [
			'uninstallable' => 'yes',
		];
		$this->assertFalse($oExtension->CanBeUninstalled(), 'The uninstallable flag provided in the extension should prevail over those defined in the modules.');
	}
}
