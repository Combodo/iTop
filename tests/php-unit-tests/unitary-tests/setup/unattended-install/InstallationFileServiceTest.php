<?php

namespace Combodo\iTop\Test\UnitTest\Setup\UnattendedInstall;

use PHPUnit\Framework\TestCase;

class InstallationFileServiceTest extends TestCase {
	protected function setUp(): void {
		require_once(dirname(__FILE__, 6) . '/setup/unattended-install/InstallationFileService.php');
		parent::setUp();
	}

	public function GetDefaultModulesProvider() {
		return [
			'all checked' => [ true ],
			'only defaut + mandatory' => [ false ],
		];
	}

	/**
	 * @dataProvider GetDefaultModulesProvider
	 */
	public function testGetDefaultModules($bAllChecked=false) {
		$sPath = realpath(dirname(__FILE__, 6)."/datamodels/2.x/installation.xml");
		$this->assertTrue(is_file($sPath));
		$oInstallationFileService = new \InstallationFileService($sPath);
		$oInstallationFileService->Init($bAllChecked);
		$aExpectedModules = [
			"itop-config-mgmt",
			"itop-attachments",
			"itop-profiles-itil",
			"itop-welcome-itil",
			"itop-tickets",
			"itop-files-information",
			"combodo-db-tools",
			"itop-core-update",
			"itop-hub-connector",
			"itop-oauth-client",
			"itop-datacenter-mgmt",
			"itop-endusers-devices",
			"itop-storage-mgmt",
			"itop-virtualization-mgmt",
			"itop-service-mgmt",
			"itop-request-mgmt",
			"itop-portal",
			"itop-portal-base",
			"itop-change-mgmt",
		];

		$aExpectedUnselectedModules = [
			'itop-change-mgmt-itil',
			'itop-incident-mgmt-itil',
			'itop-request-mgmt-itil',
			'itop-service-mgmt-provider',
		];

		if ($bAllChecked){
			$aExpectedModules []= "itop-problem-mgmt";
			$aExpectedModules []= "itop-knownerror-mgmt";
		} else {
			$aExpectedUnselectedModules []= "itop-problem-mgmt";
			$aExpectedUnselectedModules []= "itop-knownerror-mgmt";
		}


		sort($aExpectedModules);
		$aModules = $oInstallationFileService->GetSelectedModules();
		sort($aModules);

		$this->assertEquals($aExpectedModules, $aModules);

		$aUnselectedModules = $oInstallationFileService->GetUnSelectedModules();
		sort($aExpectedUnselectedModules);
		sort($aUnselectedModules);
		$this->assertEquals($aExpectedUnselectedModules, $aUnselectedModules);
	}
}
