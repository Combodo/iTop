<?php

namespace Combodo\iTop\Test\UnitTest\Setup\UnattendedInstall;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class InstallationFileServiceTest extends ItopDataTestCase {
	protected function setUp(): void {
		parent::setUp();
		require_once(dirname(__FILE__, 6) . '/setup/unattended-install/InstallationFileService.php');
		$this->sFolderToCleanup = null;
		\ModuleDiscovery::ResetCache();
	}

	protected function tearDown(): void {
		parent::tearDown();

		\ModuleDiscovery::$bDebugUnattended = false;
		$sModuleId = "itop-problem-mgmt";
		$this->RecurseMoveDir(APPROOT."data/production-modules/$sModuleId", APPROOT . "datamodels/2.x/$sModuleId");
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
		$sPath = realpath(dirname(__FILE__, 6)."/datamodels/2.x/installation.xml");
		$this->assertTrue(is_file($sPath));
		$oInstallationFileService = new \InstallationFileService($sPath);
		$oInstallationFileService->ProcessInstallationChoices($bInstallationOptionalChoicesChecked);
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
		$sPath = realpath(dirname(__FILE__, 6)."/datamodels/2.x/installation.xml");
		$oInstallationFileService = new \InstallationFileService($sPath);
		$oInstallationFileService->Init($bInstallationOptionalChoicesChecked);

		$aSelectedModules = $oInstallationFileService->GetSelectedModules();
		$aExpectedInstallationModules = [
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
			'itop-bridge-virtualization-storage',
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
			\ModuleDiscovery::$bDebugUnattended = true;
			$this->RecurseMoveDir(APPROOT . "datamodels/2.x/$sModuleId", APPROOT."data/production-modules/$sModuleId");
		}

		$sPath = realpath(dirname(__FILE__, 6)."/datamodels/2.x/installation.xml");
		$oInstallationFileService = new \InstallationFileService($sPath);
		$oInstallationFileService->Init(false);

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

		$aInfo = [];
		$aInfo[$sFromDir] = exec("tree -L 2 $sFromDir");
		$aInfo[$sToDir] = exec("tree -L 2 $sToDir");
		\IssueLog::Info("RecurseMoveDir", null, $aInfo);
	}
}
