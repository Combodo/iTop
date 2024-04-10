<?php

namespace Combodo\iTop\Test\UnitTest\Setup\UnattendedInstall;

use PHPUnit\Framework\TestCase;

class InstallationFileServiceTest extends TestCase {
	private $sFolderToCleanup;

	protected function setUp(): void {
		parent::setUp();
		require_once(dirname(__FILE__, 6) . '/setup/unattended-install/InstallationFileService.php');
		$this->sFolderToCleanup = null;
		\ModuleDiscovery::ResetCache();
	}

	protected function tearDown(): void {
		parent::tearDown();

		$sModuleId = "itop-problem-mgmt";
		$this->RecurseMoveDir(APPROOT."data/production-modules/$sModuleId", APPROOT . "datamodels/2.x/$sModuleId");
	}

	private function GetInstallationPath() {
		return realpath(__DIR__ . '/installation.xml');
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
	public function testProcessInstallationChoices($bInstallationOptionalChoicesChecked=false) {
		$sPath = realpath($this->GetInstallationPath());
		$this->assertTrue(is_file($sPath));
		$oInstallationFileService = new \InstallationFileService($sPath, 'production', [], $bInstallationOptionalChoicesChecked);
		$oInstallationFileService->ProcessInstallationChoices();
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

		if ($bInstallationOptionalChoicesChecked){
			$aExpectedModules []= "itop-problem-mgmt";
			$aExpectedModules []= "itop-knownerror-mgmt";
		} else {
			$aExpectedUnselectedModules []= "itop-problem-mgmt";
			$aExpectedUnselectedModules []= "itop-knownerror-mgmt";
		}

		sort($aExpectedModules);
		$aModules = array_keys($oInstallationFileService->GetSelectedModules());
		sort($aModules);

		$this->assertEquals($aExpectedModules, $aModules);

		$aUnselectedModules = array_keys($oInstallationFileService->GetUnSelectedModules());
		sort($aExpectedUnselectedModules);
		sort($aUnselectedModules);
		$this->assertEquals($aExpectedUnselectedModules, $aUnselectedModules);
	}

	/**
	 * @dataProvider GetDefaultModulesProvider
	 */
	public function testGetAllSelectedModules($bInstallationOptionalChoicesChecked=false) {
		$sPath = realpath($this->GetInstallationPath());
		$oInstallationFileService = new \InstallationFileService($sPath, 'production', [], $bInstallationOptionalChoicesChecked);
		$oInstallationFileService->Init();

		$aSelectedModules = $oInstallationFileService->GetSelectedModules();
		$aExpectedInstallationModules = [
			'combodo-backoffice-darkmoon-theme',
		    'itop-structure',
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
		if ($bInstallationOptionalChoicesChecked){
			$aExpectedInstallationModules []= "itop-problem-mgmt";
			$aExpectedInstallationModules []= "itop-knownerror-mgmt";
		}

		$aExpectedAuthenticationModules = [
			'authent-cas',
			'authent-external',
			'authent-ldap',
			'authent-local',
		];

		$aUnvisibleModules = [
			'itop-backup',
			'itop-config',
			'itop-sla-computation',
		];

		$aAutoSelectedModules = [
			'itop-bridge-cmdb-services',
			'itop-bridge-virtualization-storage',
			'itop-bridge-cmdb-ticket',
			'itop-bridge-datacenter-mgmt-services',
			'itop-bridge-endusers-devices-services',
			'itop-bridge-storage-mgmt-services',
			'itop-bridge-virtualization-mgmt-services',
		];

		$this->checkModuleList("installation.xml choices", $aExpectedInstallationModules, $aSelectedModules);
		$this->checkModuleList("authentication category", $aExpectedAuthenticationModules, $aSelectedModules);
		$this->checkModuleList("unvisible", $aUnvisibleModules, $aSelectedModules);
		$this->checkModuleList("auto-select", $aAutoSelectedModules, $aSelectedModules);
		$this->assertEquals([], $aSelectedModules, "there should be no more modules remaining apart from below lists");
	}

	private function GetSelectedItilExtensions(bool $coreExtensionIncluded, bool $bKnownMgtIncluded) : array {
		$aExtensions = [
			'itop-config-mgmt-datacenter',
			'itop-config-mgmt-end-user',
			'itop-config-mgmt-storage',
			'itop-config-mgmt-virtualization',
			'itop-service-mgmt-enterprise',
			'itop-ticket-mgmt-itil',
			'itop-ticket-mgmt-itil-user-request',
			'itop-ticket-mgmt-itil-incident',
			'itop-ticket-mgmt-itil-enhanced-portal',
			'itop-change-mgmt-itil',
		];

		if ($coreExtensionIncluded){
			$aExtensions[]= 'itop-config-mgmt-core';
		}

		if ($bKnownMgtIncluded){
			$aExtensions[]= 'itop-kown-error-mgmt';
		}

		return $aExtensions;

	}

	public function ItilExtensionProvider() {
		return [
			'all itil extensions + INCLUDING known-error-mgt' => [
				'aSelectedExtensions' => $this->GetSelectedItilExtensions(true, true),
				'bKnownMgtSelected' => true,
			],
			'all itil extensions WITHOUT known-error-mgt' => [
				'aSelectedExtensions' => $this->GetSelectedItilExtensions(true, false),
				'bKnownMgtSelected' => false,
			],
			'all itil extensions WITHOUT core mandatory ones + INCLUDING known-error-mgt' => [
				'aSelectedExtensions' => $this->GetSelectedItilExtensions(false, true),
				'bKnownMgtSelected' => true,
			],
			'all itil extensions WITHOUT core mandatory ones and WITHOUT known-error-mgt' => [
				'aSelectedExtensions' => $this->GetSelectedItilExtensions(false, false),
				'bKnownMgtSelected' => false,
			],
		];
	}

	/**
	 * @dataProvider ItilExtensionProvider
	 */
	public function testGetAllSelectedModules_withItilExtensions(array $aSelectedExtensions, bool $bKnownMgtSelected) {
		$sPath = realpath($this->GetInstallationPath());
		$oInstallationFileService = new \InstallationFileService($sPath, 'production', $aSelectedExtensions);
		$oInstallationFileService->Init();

		$aSelectedModules = $oInstallationFileService->GetSelectedModules();
		$aExpectedInstallationModules = [
			'combodo-backoffice-darkmoon-theme',
            'itop-structure',
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
			"itop-request-mgmt-itil",
			"itop-incident-mgmt-itil",
			"itop-portal",
			"itop-portal-base",
			"itop-change-mgmt-itil",
			"itop-full-itil",
		];
		if ($bKnownMgtSelected){
			$aExpectedInstallationModules []= "itop-knownerror-mgmt";
		}

		$aExpectedAuthenticationModules = [
			'authent-cas',
			'authent-external',
			'authent-ldap',
			'authent-local',
		];

		$aUnvisibleModules = [
			'itop-backup',
			'itop-config',
			'itop-sla-computation',
		];

		$aAutoSelectedModules = [
			'itop-bridge-cmdb-services',
			'itop-bridge-virtualization-storage',
			'itop-bridge-cmdb-ticket',
			'itop-bridge-datacenter-mgmt-services',
		    'itop-bridge-endusers-devices-services',
		    'itop-bridge-storage-mgmt-services',
		    'itop-bridge-virtualization-mgmt-services',
		];

		$this->checkModuleList("installation.xml choices", $aExpectedInstallationModules, $aSelectedModules);
		$this->checkModuleList("authentication category", $aExpectedAuthenticationModules, $aSelectedModules);
		$this->checkModuleList("unvisible", $aUnvisibleModules, $aSelectedModules);
		$this->checkModuleList("auto-select", $aAutoSelectedModules, $aSelectedModules);
		$this->assertEquals([], $aSelectedModules, "there should be no more modules remaining apart from below lists");
	}

	private function checkModuleList(string $sModuleCategory, array $aExpectedModuleList, array &$aSelectedModules) {
		$aMissingModules = [];

		foreach ($aExpectedModuleList as $sModuleId){
			if (! array_key_exists($sModuleId, $aSelectedModules)){
				$aMissingModules[]=$sModuleId;
			} else {
				unset($aSelectedModules[$sModuleId]);
			}
		}

		$this->assertEquals([], $aMissingModules, "$sModuleCategory modules are missing");

	}

	public function ProductionModulesProvider() {
		return [
			'module autoload as located in production-modules' => [ true ],
			'module not loaded' => [ false ],
		];
	}

	/**
	 * @dataProvider ProductionModulesProvider
	 */
	public function testGetAllSelectedModules_ProductionModules(bool $bModuleInProductionModulesFolder) {
		$sModuleId = "itop-problem-mgmt";
		if ($bModuleInProductionModulesFolder){
			if (! is_dir(APPROOT."data/production-modules")){
				@mkdir(APPROOT."data/production-modules");
			}

			$this->RecurseMoveDir(APPROOT . "datamodels/2.x/$sModuleId", APPROOT."data/production-modules/$sModuleId");
		}

		$sPath = realpath($this->GetInstallationPath());
		$oInstallationFileService = new \InstallationFileService($sPath, 'production', [], false);
		$oInstallationFileService->Init();

		$aSelectedModules = $oInstallationFileService->GetSelectedModules();
		$this->assertEquals($bModuleInProductionModulesFolder, array_key_exists($sModuleId, $aSelectedModules));
	}

	private function RecurseMoveDir($sFromDir, $sToDir) {
		if (! is_dir($sFromDir)){
			return;
		}

		if (! is_dir($sToDir)){
			@mkdir($sToDir);
		}

		foreach (glob("$sFromDir/*") as $sPath){
			$sToPath = $sToDir.'/'.basename($sPath);
			if (is_file($sPath)){
				@rename($sPath, $sToPath);
			} else {
				$this->RecurseMoveDir($sPath, $sToPath);
			}
		}

		@rmdir($sFromDir);
	}
}
