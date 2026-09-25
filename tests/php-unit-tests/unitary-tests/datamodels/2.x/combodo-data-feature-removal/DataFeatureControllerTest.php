<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Module\DataFeatureRemoval\Service;

use Combodo\iTop\DataFeatureRemoval\Controller\DataFeatureRemovalController;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use iTopExtension;

/**
 * @see DataFeatureController
 */
class DataFeatureControllerTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('env-production/combodo-data-feature-removal/vendor/autoload.php');
	}

	public function testConvertIntoSetupFormat()
	{
		$oController = new DataFeatureRemovalController();

		$aExtensions = [
			'itop-container-mgmt',
			'combodo-monitoring',
		];
		$expected = '{"0":"itop-container-mgmt","1":"combodo-monitoring"}';
		$this->assertEquals($expected, $this->InvokeNonPublicMethod(DataFeatureRemovalController::class, 'ConvertIntoSetupFormat', $oController, [ $aExtensions]));
	}

	public function testGetBasePackageModules()
	{
		$oController = new DataFeatureRemovalController();
		$aModules = $oController->GetBasePackageModules();
		$this->assertNotEmpty($aModules);
		$this->assertContains('itop-structure', $aModules);
		$this->assertContains('authent-local', $aModules);
		$this->assertContains('itop-backup', $aModules);
		$this->assertContains('itop-tickets', $aModules);

	}

	public function testIsIncludedInPackage()
	{
		$oController = $this->getMockBuilder(DataFeatureRemovalController::class)
			->onlyMethods(['GetBasePackageModules'])
			->getMock();
		$oController->method('GetBasePackageModules')->willReturn([
			'itop-structure',
			'authent-local',
			'itop-backup',
			'itop-tickets',
		]);

		$oIncludedExtension = new iTopExtension();
		$oIncludedExtension->sSource = iTopExtension::SOURCE_MANUAL;
		$oIncludedExtension->aModules = ['itop-structure', 'authent-local'];
		$this->assertTrue($oController->IsIncludedInPackage($oIncludedExtension), 'IsIncludedInPackage should return true if all modules are included in the base package');

		$oNotIncludedExtension = new iTopExtension();
		$oNotIncludedExtension->sSource = iTopExtension::SOURCE_MANUAL;
		$oNotIncludedExtension->aModules = ['itop-structure', 'combodo-non-existing-module'];
		$this->assertFalse($oController->IsIncludedInPackage($oNotIncludedExtension), 'IsIncludedInPackage should return false if at least one of its modules is not included in the base package');

		$oEmptyExtension = new iTopExtension();
		$oEmptyExtension->sSource = iTopExtension::SOURCE_MANUAL;
		$oEmptyExtension->aModules = [];
		$this->assertFalse($oController->IsIncludedInPackage($oEmptyExtension), 'IsIncludedInPackage should return false if the extension has no modules');

		$oPackageExtension = new iTopExtension();
		$oPackageExtension->sSource = iTopExtension::SOURCE_WIZARD;
		$oPackageExtension->aModules = ['itop-structure', 'authent-local'];
		$this->assertFalse($oController->IsIncludedInPackage($oPackageExtension), 'IsIncludedInPackage should return false if the extension is a package extension');
	}
}
