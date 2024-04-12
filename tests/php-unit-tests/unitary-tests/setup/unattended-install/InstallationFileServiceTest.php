<?php

namespace Combodo\iTop\Test\UnitTest\Setup\UnattendedInstall;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use PHPUnit\Framework\TestCase;

/**
 * @group itop-clone-only
 */
class InstallationFileServiceTest extends ItopTestCase {
	protected function setUp(): void {
		parent::setUp();
		require_once(dirname(__FILE__, 6) . '/setup/unattended-install/InstallationFileService.php');
		\ModuleDiscovery::ResetCache();
	}

	protected function tearDown(): void {
		parent::tearDown();

		$sModuleId = "itop-problem-mgmt";
		$this->RecurseMoveDir(APPROOT."data/production-modules/$sModuleId", APPROOT . "datamodels/2.x/$sModuleId");
	}

	private function GetInstallationPath() : string {
		return realpath(__DIR__ . '/resources/installation.xml');
	}

	private function GetModuleData($sCategory, bool $bIsVisible, bool $bIsAutoSelect, bool $bProductionModulesInRootDir=false) : array {
		$sRootDir = $bProductionModulesInRootDir ? APPROOT.'data/production-modules/' : '';

		$aModuleData = [
			'category' => $sCategory,
			'visible' => $bIsVisible,
			'root_dir' => $sRootDir,
		];

		if ($bIsAutoSelect){
			$aModuleData['auto_select'] = true;
		}

		return $aModuleData;
	}

	public function ProcessDefaultModulesProvider() {
		parent::setUp();
		return [
			'root module' => [
				'aAllFoundModules' => [
					'_Root_' => $this->GetModuleData('authentication', false, false, true),
				],
				'aExpectedSelectedModules' => [],
				'aExpectedAutoSelectModules' => [],
			],
			'auto-select root module' => [
				'aAllFoundModules' => [
					'_Root_' => $this->GetModuleData('authentication', false, true, true),
				],
				'aExpectedSelectedModules' => [],
				'aExpectedAutoSelectModules' => [],
			],
			'autoselect module only' => [
				'aAllFoundModules' => [
					'autoselect-only' => $this->GetModuleData('mycategory', true, true),
				],
				'aExpectedSelectedModules' => [],
				'aExpectedAutoSelectModules' => ['autoselect-only'],
			],
			'autoselect/invisible module' => [
				'aAllFoundModules' => [
					'autoselect-only' => $this->GetModuleData('mycategory', false, true),
				],
				'aExpectedSelectedModules' => [],
				'aExpectedAutoSelectModules' => ['autoselect-only'],
			],
			'autoselect/invisible/in-root-dir module' => [
				'aAllFoundModules' => [
					'autoselect-only' => $this->GetModuleData('mycategory', false, true , true),
				],
				'aExpectedSelectedModules' => [],
				'aExpectedAutoSelectModules' => ['autoselect-only'],
			],
			'visible/authent module' => [
				'aAllFoundModules' => [
					'authent-module' => $this->GetModuleData('authentication', true, false , false),
				],
				'aExpectedSelectedModules' => ['authent-module'],
				'aExpectedAutoSelectModules' => [],
			],
			'invisible module' => [
				'aAllFoundModules' => [
					'visible-module' => $this->GetModuleData('mycategory', false, false , false),
				],
				'aExpectedSelectedModules' => ['visible-module'],
				'aExpectedAutoSelectModules' => [],
			],
			'in-root-dir module' => [
				'aAllFoundModules' => [
					'in-root-dir-module' => $this->GetModuleData('mycategory', true, false , true),
				],
				'aExpectedSelectedModules' => ['in-root-dir-module'],
				'aExpectedAutoSelectModules' => [],
			],
		];
	}
	/**
	 * @dataProvider ProcessDefaultModulesProvider
	 */
	public function testProcessDefaultModules(array $aAllFoundModules, array $aExpectedSelectedModules, array $aExpectedAutoSelectModules) {
		$oInstallationFileService = new \InstallationFileService('', 'production', [], true);

		$oProductionEnv = $this->createMock(\RunTimeEnvironment::class);
		$oProductionEnv->expects($this->once())
			->method('AnalyzeInstallation')
			->willReturn($aAllFoundModules);

		$oInstallationFileService->SetProductionEnv($oProductionEnv);
		$oInstallationFileService->ProcessDefaultModules();

		sort($aExpectedSelectedModules);
		$aModules = array_keys($oInstallationFileService->GetSelectedModules());
		sort($aModules);

		$this->assertEquals($aExpectedSelectedModules, $aModules);

		$aAutoSelectModules = array_keys($oInstallationFileService->GetAutoSelectModules());
		sort($aAutoSelectModules);
		$this->assertEquals($aExpectedAutoSelectModules, $aAutoSelectModules);
	}

	public function ProcessInstallationChoicesProvider() {
		return [
			'all checked' => [ true ],
			'only defaut + mandatory' => [ false ],
		];
	}

	/**
	 * @dataProvider ProcessInstallationChoicesProvider
	 */
	public function testProcessInstallationChoices($bInstallationOptionalChoicesChecked) {
		$sPath = $this->GetInstallationPath();
		$oInstallationFileService = new \InstallationFileService($sPath, 'production', [], $bInstallationOptionalChoicesChecked);
		$oProductionEnv = $this->createMock(\RunTimeEnvironment::class);
		$oProductionEnv->expects($this->never())
			->method('AnalyzeInstallation');
		$oInstallationFileService->SetProductionEnv($oProductionEnv);

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

		$aGetAfterComputationSelectedExtensions = $oInstallationFileService->GetAfterComputationSelectedExtensions();
		sort($aGetAfterComputationSelectedExtensions);
		$aExpectedExtensions = [
			'itop-change-mgmt-simple',
		    'itop-config-mgmt-core',
		    'itop-config-mgmt-datacenter',
		    'itop-config-mgmt-end-user',
		    'itop-config-mgmt-storage',
		    'itop-config-mgmt-virtualization',
		    'itop-service-mgmt-enterprise',
		    'itop-ticket-mgmt-simple-ticket',
			'itop-ticket-mgmt-simple-ticket-enhanced-portal',
		];
		if ($bInstallationOptionalChoicesChecked){
			$aExpectedExtensions []= "itop-problem-mgmt";
			$aExpectedExtensions []= 'itop-kown-error-mgmt';
		}
		sort($aExpectedExtensions);
		$this->assertEquals($aExpectedExtensions, $aGetAfterComputationSelectedExtensions);

		$this->ValidateNonItilExtensionComputation($oInstallationFileService, $bInstallationOptionalChoicesChecked);
	}

	/**
	 * @dataProvider ItilExtensionProvider
	 */
	public function testProcessInstallationChoicesWithItilChoices(array $aSelectedExtensions, bool $bKnownMgtSelected, bool $bCoreMgtSelected) {
		$sPath = $this->GetInstallationPath();
		$oInstallationFileService = new \InstallationFileService($sPath, 'production', $aSelectedExtensions, false);
		$oProductionEnv = $this->createMock(\RunTimeEnvironment::class);
		$oProductionEnv->expects($this->never())
			->method('AnalyzeInstallation');
		$oInstallationFileService->SetProductionEnv($oProductionEnv);

		$oInstallationFileService->ProcessInstallationChoices();

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
			"itop-request-mgmt-itil",
			"itop-incident-mgmt-itil",
			"itop-portal",
			"itop-portal-base",
			"itop-change-mgmt-itil",
		];
		if ($bKnownMgtSelected){
			$aExpectedInstallationModules []= "itop-knownerror-mgmt";
		}

		sort($aExpectedInstallationModules);
		$aModules = array_keys($oInstallationFileService->GetSelectedModules());
		sort($aModules);

		$this->assertEquals($aExpectedInstallationModules, $aModules);

		$aExpectedUnselectedModules = [
			0 => 'itop-change-mgmt',
		    1 => 'itop-problem-mgmt',
		    2 => 'itop-request-mgmt',
		    3 => 'itop-service-mgmt-provider',
		];
		if (!$bKnownMgtSelected){
			$aExpectedUnselectedModules[]='itop-knownerror-mgmt';
		}
		$aUnselectedModules = array_keys($oInstallationFileService->GetUnSelectedModules());
		sort($aExpectedUnselectedModules);
		sort($aUnselectedModules);
		$this->assertEquals($aExpectedUnselectedModules, $aUnselectedModules);

		$this->ValidateItilExtensionComputation($oInstallationFileService, $bKnownMgtSelected, $bCoreMgtSelected);
	}

	public function GetDefaultModulesProvider() {
		return [
			'check all possible modules' => [true],
			'only minimum defaul/mandatory from installation.xml' => [false],
		];
	}

	private function GetMockListOfFoundModules() : array {
		$sJsonContent = file_get_contents(realpath(__DIR__ . '/resources/AnalyzeInstallation.json'));
		$sJsonContent = str_replace('ROOTDIR_TOREPLACE', APPROOT, $sJsonContent);
		return json_decode($sJsonContent, true);
	}

	/**
	 * @dataProvider GetDefaultModulesProvider
	 */
	public function testGetAllSelectedModules($bInstallationOptionalChoicesChecked=false) {
		$sPath = $this->GetInstallationPath();
		$oInstallationFileService = new \InstallationFileService($sPath, 'production', [], $bInstallationOptionalChoicesChecked);

		$oProductionEnv = $this->createMock(\RunTimeEnvironment::class);
		$oProductionEnv->expects($this->once())
			->method('AnalyzeInstallation')
			->willReturn($this->GetMockListOfFoundModules());
		$oInstallationFileService->SetProductionEnv($oProductionEnv);

		$oInstallationFileService->Init();

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

		$this->ValidateNonItilExtensionComputation($oInstallationFileService, $bInstallationOptionalChoicesChecked);
	}

	private function ValidateNonItilExtensionComputation($oInstallationFileService, bool $bInstallationOptionalChoicesChecked) {
		$aGetAfterComputationSelectedExtensions = $oInstallationFileService->GetAfterComputationSelectedExtensions();
		sort($aGetAfterComputationSelectedExtensions);
		$aExpectedExtensions = [
			'itop-change-mgmt-simple',
			'itop-config-mgmt-core',
			'itop-config-mgmt-datacenter',
			'itop-config-mgmt-end-user',
			'itop-config-mgmt-storage',
			'itop-config-mgmt-virtualization',
			'itop-service-mgmt-enterprise',
			'itop-ticket-mgmt-simple-ticket',
			'itop-ticket-mgmt-simple-ticket-enhanced-portal',
		];
		if ($bInstallationOptionalChoicesChecked){
			$aExpectedExtensions []= "itop-problem-mgmt";
			$aExpectedExtensions []= 'itop-kown-error-mgmt';
		}
		sort($aExpectedExtensions);
		$this->assertEquals($aExpectedExtensions, $aGetAfterComputationSelectedExtensions);
	}

	private function ValidateItilExtensionComputation($oInstallationFileService, bool $bKnownMgtSelected, bool $bCoreMgtSelected) {
		$aGetAfterComputationSelectedExtensions = $oInstallationFileService->GetAfterComputationSelectedExtensions();
		sort($aGetAfterComputationSelectedExtensions);
		$aExpectedExtensions = [
			'itop-change-mgmt-itil',
			'itop-config-mgmt-datacenter',
			'itop-config-mgmt-end-user',
			'itop-config-mgmt-storage',
			'itop-config-mgmt-virtualization',
			'itop-service-mgmt-enterprise',
			'itop-ticket-mgmt-itil',
			'itop-ticket-mgmt-itil-enhanced-portal',
			'itop-ticket-mgmt-itil-incident',
			'itop-ticket-mgmt-itil-user-request',
		];
		if ($bCoreMgtSelected){
			$aExpectedExtensions []= 'itop-config-mgmt-core';
		}
		if ($bKnownMgtSelected){
			$aExpectedExtensions []= 'itop-kown-error-mgmt';
		}
		sort($aExpectedExtensions);
		$this->assertEquals($aExpectedExtensions, $aGetAfterComputationSelectedExtensions);
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
				'bCoreMgtSelected' => true,
			],
			'all itil extensions WITHOUT known-error-mgt' => [
				'aSelectedExtensions' => $this->GetSelectedItilExtensions(true, false),
				'bKnownMgtSelected' => false,
				'bCoreMgtSelected' => true,
			],
			'all itil extensions WITHOUT core mandatory ones + INCLUDING known-error-mgt' => [
				'aSelectedExtensions' => $this->GetSelectedItilExtensions(false, true),
				'bKnownMgtSelected' => true,
				'bCoreMgtSelected' => false,
			],
			'all itil extensions WITHOUT core mandatory ones and WITHOUT known-error-mgt' => [
				'aSelectedExtensions' => $this->GetSelectedItilExtensions(false, false),
				'bKnownMgtSelected' => false,
				'bCoreMgtSelected' => false,
			],
		];
	}

	/**
	 * @dataProvider ItilExtensionProvider
	 */
	public function testGetAllSelectedModules_withItilExtensions(array $aSelectedExtensions, bool $bKnownMgtSelected, bool $bCoreMgtSelected) {
		$sPath = $this->GetInstallationPath();
		$oInstallationFileService = new \InstallationFileService($sPath, 'production', $aSelectedExtensions);

		$oProductionEnv = $this->createMock(\RunTimeEnvironment::class);
		$oProductionEnv->expects($this->once())
			->method('AnalyzeInstallation')
			->willReturn($this->GetMockListOfFoundModules());
		$oInstallationFileService->SetProductionEnv($oProductionEnv);

		$oInstallationFileService->Init();

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
			'itop-bridge-virtualization-storage',
		];

		$this->checkModuleList("installation.xml choices", $aExpectedInstallationModules, $aSelectedModules);
		$this->checkModuleList("authentication category", $aExpectedAuthenticationModules, $aSelectedModules);
		$this->checkModuleList("unvisible", $aUnvisibleModules, $aSelectedModules);
		$this->checkModuleList("auto-select", $aAutoSelectedModules, $aSelectedModules);
		$this->assertEquals([], $aSelectedModules, "there should be no more modules remaining apart from below lists");

		$this->ValidateItilExtensionComputation($oInstallationFileService, $bKnownMgtSelected, $bCoreMgtSelected);
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
