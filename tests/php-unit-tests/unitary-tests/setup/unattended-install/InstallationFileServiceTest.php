<?php

namespace Combodo\iTop\Test\UnitTest\Setup\UnattendedInstall;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ItopExtensionsMap;
use iTopExtension;
use RunTimeEnvironment;
use InstallationFileService;
use ModuleDiscovery;

class InstallationFileServiceTest extends ItopTestCase {
	protected function setUp(): void {
		parent::setUp();
		require_once(dirname(__FILE__, 6) . '/setup/unattended-install/InstallationFileService.php');
		ModuleDiscovery::ResetCache();
	}

	protected function tearDown(): void {
		parent::tearDown();
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
		$oInstallationFileService = new InstallationFileService('', 'production', [], true);

		$oProductionEnv = $this->createMock(RunTimeEnvironment::class);
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
		$oInstallationFileService = new InstallationFileService($sPath, 'production', [], $bInstallationOptionalChoicesChecked);
		$oProductionEnv = $this->createMock(RunTimeEnvironment::class);
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
		$oInstallationFileService = new InstallationFileService($sPath, 'production', $aSelectedExtensions, false);
		$oProductionEnv = $this->createMock(RunTimeEnvironment::class);
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
			'itop-change-mgmt',
			'itop-problem-mgmt',
			'itop-request-mgmt',
			'itop-service-mgmt-provider',
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
		$sJsonContent = str_replace('ROOTDIR_TOREPLACE', addslashes(APPROOT), $sJsonContent);
        return json_decode($sJsonContent, true);
	}

	/**
	 * @dataProvider GetDefaultModulesProvider
	 */
	public function testGetAllSelectedModules($bInstallationOptionalChoicesChecked=false) {
		$sPath = $this->GetInstallationPath();
		$oInstallationFileService = new InstallationFileService($sPath, 'production', [], $bInstallationOptionalChoicesChecked);

		$oProductionEnv = $this->createMock(RunTimeEnvironment::class);
		$oProductionEnv->expects($this->once())
			->method('AnalyzeInstallation')
			->willReturn($this->GetMockListOfFoundModules());
		$oInstallationFileService->SetProductionEnv($oProductionEnv);

		$oItopExtensionsMap = $this->createMock(ItopExtensionsMap::class);
		$oItopExtensionsMap->expects($this->once())
			->method('GetAllExtensions')
			->willReturn([]);
		$oInstallationFileService->SetItopExtensionsMap($oItopExtensionsMap);

		$oInstallationFileService->Init();

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
			'authent-cas',
			'authent-external',
			'authent-ldap',
			'authent-local',
			'itop-backup',
			'itop-config',
			'itop-sla-computation',
			'itop-bridge-virtualization-storage',
		];

		if ($bInstallationOptionalChoicesChecked){
			$aExpectedInstallationModules []= "itop-problem-mgmt";
			$aExpectedInstallationModules []= "itop-knownerror-mgmt";
		}

		sort($aExpectedInstallationModules);

		$aSelectedModules = array_keys($oInstallationFileService->GetSelectedModules());
		sort($aSelectedModules);
		$this->assertEquals($aExpectedInstallationModules, $aSelectedModules);

		$this->ValidateNonItilExtensionComputation($oInstallationFileService, $bInstallationOptionalChoicesChecked);
	}

	private function ValidateNonItilExtensionComputation($oInstallationFileService, bool $bInstallationOptionalChoicesChecked, array $aAdditionalExtensions=[]) {
		$aGetAfterComputationSelectedExtensions = $oInstallationFileService->GetAfterComputationSelectedExtensions();
		sort($aGetAfterComputationSelectedExtensions);
		$aExpectedExtensions = array_merge($aAdditionalExtensions, [
			'itop-change-mgmt-simple',
			'itop-config-mgmt-core',
			'itop-config-mgmt-datacenter',
			'itop-config-mgmt-end-user',
			'itop-config-mgmt-storage',
			'itop-config-mgmt-virtualization',
			'itop-service-mgmt-enterprise',
			'itop-ticket-mgmt-simple-ticket',
			'itop-ticket-mgmt-simple-ticket-enhanced-portal',
		]);
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
		$oInstallationFileService = new InstallationFileService($sPath, 'production', $aSelectedExtensions);

		$oProductionEnv = $this->createMock(RunTimeEnvironment::class);
		$oProductionEnv->expects($this->once())
			->method('AnalyzeInstallation')
			->willReturn($this->GetMockListOfFoundModules());
		$oInstallationFileService->SetProductionEnv($oProductionEnv);

		$oItopExtensionsMap = $this->createMock(ItopExtensionsMap::class);
		$oItopExtensionsMap->expects($this->once())
			->method('GetAllExtensions')
			->willReturn([]);
		$oInstallationFileService->SetItopExtensionsMap($oItopExtensionsMap);

		$oInstallationFileService->Init();

		$aSelectedModules = array_keys($oInstallationFileService->GetSelectedModules());
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
			'authent-cas',
			'authent-external',
			'authent-ldap',
			'authent-local',
			'itop-backup',
			'itop-config',
			'itop-sla-computation',
			'itop-bridge-virtualization-storage',
		];
		if ($bKnownMgtSelected){
			$aExpectedInstallationModules []= "itop-knownerror-mgmt";
		}

		sort($aExpectedInstallationModules);
		sort($aSelectedModules);
		$this->assertEquals($aExpectedInstallationModules, $aSelectedModules);

		$this->ValidateItilExtensionComputation($oInstallationFileService, $bKnownMgtSelected, $bCoreMgtSelected);
	}

	private function CreateItopExtension(string $sSource, string $sCode, array $aModules, array $aMissingDependencies, bool $bIsVisible) : iTopExtension{
		$oExtension = new iTopExtension();
		$oExtension->sCode = $sCode;
		$oExtension->sSource = $sSource;
		$oExtension->aModules = $aModules;
		$oExtension->aMissingDependencies = $aMissingDependencies;
		$oExtension->bVisible = $bIsVisible;
		return $oExtension;
	}

	public function CanChooseUnpackageExtensionProvider() {
		return [
			'extension in SOURCE_REMOTE' => [
				'sCode' => "extension-from-designer",
				'bInstallationOptionalChoicesChecked' => false,
				'sSource' => 'data',
				'bExpectedRes' => true
			],
			'extension in SOURCE_WIZARD' => [
				'sCode' => 'extension-from-package',
				'bInstallationOptionalChoicesChecked' => true,
				'sSource' => 'datamodels',
				'bExpectedRes' => false
			],
			'extension in SOURCE_MANUAL + optional OK' => [
				'sCode' => 'extension-from-package',
				'bInstallationOptionalChoicesChecked' => true,
				'sSource' => 'extensions',
				'bExpectedRes' => true
			],
			'extension in SOURCE_MANUAL + optional NOT OK' => [
				'sCode' => 'extension-from-package',
				'bInstallationOptionalChoicesChecked' => false,
				'sSource' => 'extensions',
				'bExpectedRes' => false
			],
		];
	}

	/**
	 * @dataProvider CanChooseUnpackageExtensionProvider
	 */
	public function testCanChooseUnpackageExtension(string $sCode, bool $bInstallationOptionalChoicesChecked, string $sSource, bool $bExpectedRes) {
		$sPath = $this->GetInstallationPath();
		$oInstallationFileService = new InstallationFileService($sPath, 'production', [], $bInstallationOptionalChoicesChecked);

		$oItopExtension = $this->CreateItopExtension($sSource, $sCode, [], [], true);
		$this->assertEquals($bExpectedRes, $oInstallationFileService->CanChooseUnpackageExtension($oItopExtension));
	}

	public function ProcessExtensionModulesNotSpecifiedInChoicesProvider() {
		return [
			'extensions to install OK' => [
				'aExtensionData' => [
					'extension1' => [
						//'itop-request-mgmt-itil', //unselected
						'combodo-monitoring',
						'itop-config-mgmt', //already selected
					],
					'extension2' => [
						//'itop-incident-mgmt-itil', //unselected
						'combodo-monitoring2',
						'itop-attachments', //already selected
					]
				],
				'bExtensionCanBeChoosen' => true,
				'aMissingDependencies' => [],
				'bIsVisible' => true,
				'bExpectedAdditionalExtensions' => [
					'extension1', 'extension2'
				],
				'bExpectedAdditionalModules' => [
					'combodo-monitoring', 'combodo-monitoring2'
				]
			],
			'extensions to install cannot be choose,' => [
				'aExtensionData' => [
					'extension1' => [
						'combodo-monitoring',
					],
					'extension2' => [
						'combodo-monitoring2',
					]
				],
				'bExtensionCanBeChoosen' => false,
				'aMissingDependencies' => [],
				'bIsVisible' => true,
				'bExpectedAdditionalExtensions' => [],
				'bExpectedAdditionalModules' => []
			],
			'extensions to install not visible' => [
				'aExtensionData' => [
					'extension1' => [
						'combodo-monitoring',
					],
					'extension2' => [
						'combodo-monitoring2',
					]
				],
				'bExtensionCanBeChoosen' => true,
				'aMissingDependencies' => [],
				'bIsVisible' => false,
				'bExpectedAdditionalExtensions' => [],
				'bExpectedAdditionalModules' => []
			],
			'extensions to install with missing dependencies' => [
				'aExtensionData' => [
					'extension1' => [
						'combodo-monitoring',
					],
					'extension2' => [
						'combodo-monitoring2',
					]
				],
				'bExtensionCanBeChoosen' => true,
				'aMissingDependencies' => ['missing-module'],
				'bIsVisible' => true,
				'bExpectedAdditionalExtensions' => [],
				'bExpectedAdditionalModules' => []
			],
			'extensions to install with unselectable ITIL module' => [
				'aExtensionData' => [
					'extension1' => [
						'itop-request-mgmt-itil', //unselected
						'combodo-monitoring',
					],
					'extension2' => [
						'itop-incident-mgmt-itil', //unselected
						'combodo-monitoring2',
					]
				],
				'bExtensionCanBeChoosen' => true,
				'aMissingDependencies' => [],
				'bIsVisible' => true,
				'bExpectedAdditionalExtensions' => [],
				'bExpectedAdditionalModules' => []
			],
			'extensions already processed' => [
				'aExtensionData' => [
					'itop-config-mgmt-core' => [
						'itop-config-mgmt', //already selected
					],
				],
				'bExtensionCanBeChoosen' => true,
				'aMissingDependencies' => [],
				'bIsVisible' => true,
				'bExpectedAdditionalExtensions' => [
				],
				'bExpectedAdditionalModules' => [
				]
			],
		];
	}

	/**
	 * @dataProvider ProcessExtensionModulesNotSpecifiedInChoicesProvider
	 */
	public function testProcessExtensionModulesNotSpecifiedInChoices(array $aExtensionData, bool $bExtensionCanBeChoosen,
		array $aMissingDependencies, bool $bIsVisible, array $bExpectedAdditionalExtensions, array $bExpectedAdditionalModules) {
		$sPath = $this->GetInstallationPath();
		$oInstallationFileService = new InstallationFileService($sPath, 'production', [], true);

		$oProductionEnv = $this->createMock(RunTimeEnvironment::class);
		$oProductionEnv->expects($this->once())
			->method('AnalyzeInstallation')
			->willReturn($this->GetMockListOfFoundModules());
		$oInstallationFileService->SetProductionEnv($oProductionEnv);

		$oItopExtensionsMap = $this->createMock(ItopExtensionsMap::class);
		$aItopExtensionMap = [];

		$sSource = $bExtensionCanBeChoosen ? iTopExtension::SOURCE_REMOTE : iTopExtension::SOURCE_WIZARD;
		foreach ($aExtensionData as $sExtensionCode => $aModules){
			$aItopExtensionMap[]= $this->CreateItopExtension($sSource, $sExtensionCode, $aModules, $aMissingDependencies, $bIsVisible);
		}
		$oItopExtensionsMap->expects($this->once())
			->method('GetAllExtensions')
			->willReturn($aItopExtensionMap);
		$oInstallationFileService->SetItopExtensionsMap($oItopExtensionsMap);

		$oInstallationFileService->Init();

		$aSelectedModules = array_keys($oInstallationFileService->GetSelectedModules());
		sort($aSelectedModules);
		$aExpectedInstallationModules = array_merge($bExpectedAdditionalModules, [
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
			"itop-problem-mgmt",
			"itop-knownerror-mgmt",
			'authent-cas',
			'authent-external',
			'authent-ldap',
			'authent-local',
			'itop-backup',
			'itop-config',
			'itop-sla-computation',
			'itop-bridge-virtualization-storage',
		]);
		sort($aExpectedInstallationModules);

		$this->assertEquals($aExpectedInstallationModules, $aSelectedModules);

		$this->ValidateNonItilExtensionComputation($oInstallationFileService, true, $bExpectedAdditionalExtensions);
	}


}
