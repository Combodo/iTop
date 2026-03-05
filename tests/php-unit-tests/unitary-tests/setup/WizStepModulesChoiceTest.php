<?php

namespace Combodo\iTop\Test\UnitTest\Integration;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use iTopExtension;
use iTopExtensionsMap;
use iTopExtensionsMapFake;
use ModuleDiscovery;
use WizardController;
use WizStepModulesChoiceFake;
use XMLParameters;

class WizStepModulesChoiceTest extends ItopTestCase
{
	private WizStepModulesChoiceFake $oStep;
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/setup/unattended-install/InstallationFileService.php');
		require_once __DIR__.'/iTopExtensionsMapFake.php';
		require_once __DIR__.'/WizStepModulesChoiceFake.php';

		$this->oStep = new WizStepModulesChoiceFake(new WizardController('', ''), '');
		ModuleDiscovery::ResetCache();
	}

	public function ProviderComputeChoiceFlags()
	{
		return [
			'A not selected, not installed extension should not be checked and be enabled' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => false,
					'checked' => false,
				],
			],
			'A selected but not installed extension should be checked and enabled' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
				],
				'bCurrentSelected' => true,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => false,
					'checked' => true,
				],
			],
			'A missing extension should be disabled and unchecked' => [
				'aExtensionsOnDiskOrDb' => [
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'missing' => true,
					'uninstallable' => true,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => true,
					'installed' => true,
					'disabled' => true,
					'checked' => false,
				],
			],
			'A missing extension should always be disabled and unchecked, even when mandatory' => [
				'aExtensionsOnDiskOrDb' => [
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => true,
					'missing' => true,
					'uninstallable' => true,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => true,
					'installed' => true,
					'disabled' => true,
					'checked' => false,
				],
			],
			'A missing extension should always be disabled and unchecked, even when non-uninstallable' => [
				'aExtensionsOnDiskOrDb' => [
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => true,
					'missing' => true,
					'uninstallable' => false,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => false,
					'missing' => true,
					'installed' => true,
					'disabled' => true,
					'checked' => false,
				],
			],
			'An installed but not selected extension should not be checked and be enabled' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => true,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => true,
					'disabled' => false,
					'checked' => false,
				],
			],
			'An installed non uninstallable extension should be checked and disabled' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => true,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => false,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => false,
					'missing' => false,
					'installed' => true,
					'disabled' => true,
					'checked' => true,
				],
			],
			'An installed non uninstallable extension should be enabled if the "disable uninstallation check" flag is set' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => true,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => false,
				],
				'bCurrentSelected' => true,
				'bDisableUninstallChecks' => true,
				'aExpectedFlags' => [
					'uninstallable' => false,
					'missing' => false,
					'installed' => true,
					'disabled' => false,
					'checked' => true,
				],
			],
			'A mandatory extension should be checked and disabled' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => true,
					'uninstallable' => true,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => true,
					'checked' => true,
				],
			],
			'A mandatory extension should be checked and disabled even if the "disable uninstallation check" flag is set' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => true,
					'uninstallable' => true,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => true,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => true,
					'checked' => true,
				],
			],
			'An optional sub extension should not force its parent flags' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => false,
					],
					'itop-ext1-1' => [
						'installed' => false,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
					'sub_options' => [
						'options' => [
							[
								'extension_code' => 'itop-ext1-1',
								'mandatory' => false,
								'uninstallable' => true,
							],
						],
					],
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => false,
					'checked' => false,
				],
			],
			'A mandatory sub extension should force its parent to be checked and disabled' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => false,
					],
					'itop-ext1-1' => [
						'installed' => false,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
					'sub_options' => [
						'options' => [
							[
								'extension_code' => 'itop-ext1-1',
								'mandatory' => true,
								'uninstallable' => true,
							],
						],
					],
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => true,
					'checked' => true,
				],
			],
			'An installed non uninstallable sub extension should force its parent to be checked and disabled' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => true,
					],
					'itop-ext1-1' => [
						'installed' => true,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
					'sub_options' => [
						'options' => [
							[
								'extension_code' => 'itop-ext1-1',
								'mandatory' => false,
								'uninstallable' => false,
							],
						],
					],
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => true,
					'disabled' => true,
					'checked' => true,
				],
			],
			'A non installed non uninstallable sub extension should not force its parent flags' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => true,
					],
					'itop-ext1-1' => [
						'installed' => false,
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
					'sub_options' => [
						'options' => [
							[
								'extension_code' => 'itop-ext1-1',
								'mandatory' => false,
								'uninstallable' => false,
							],
						],
					],
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => true,
					'disabled' => false,
					'checked' => false,
				],
			],
		];
	}

	/**
	 * @dataProvider ProviderComputeChoiceFlags
	 */
	public function testComputeChoiceFlags($aExtensionsOnDiskOrDb, $aWizardStepDefinition, $bIsCurrentSelected, $bDisableUninstallChecks, $aExpectedFlags)
	{
		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsOnDiskOrDb));
		$aFlags = $this->oStep->ComputeChoiceFlags($aWizardStepDefinition, '_0', $bIsCurrentSelected ? ['_0' => '_0'] : [], false, $bDisableUninstallChecks, true);
		$this->assertEquals($aExpectedFlags, $aFlags);
	}

	public function ProviderGetAllExtensionsToDisplayInSetupMandatoryFlag()
	{
		return [
			'A manually added extension should not be mandatory by default' => [
				'bExtensionSource' =>  'extensions',//iTopExtension::SOURCE_MANUAL
				'bDisableUninstallChecks' => false,
				'bExpectedMandatory' => false,
			],
			'A remotely added extension should be mandatory by default' => [
				'bExtensionSource' =>  'data',//iTopExtension::SOURCE_REMOTE
				'bDisableUninstallChecks' => false,
				'bExpectedMandatory' => true,
			],
			'A remotely added extension should not be mandatory by default if uninstall checks has been disabled' => [
				'bExtensionSource' =>  'data',//iTopExtension::SOURCE_REMOTE
				'bDisableUninstallChecks' => true,
				'bExpectedMandatory' => false,
			],

		];
	}

	/**
	 * @dataProvider ProviderGetAllExtensionsToDisplayInSetupMandatoryFlag
	 */
	public function testGetAllExtensionsToDisplayInSetupMandatoryFlag($bExtensionSource, $bDisableUninstallChecks, $bExpectedMandatory)
	{
		$aExtensionsOnDiskOrDb = [
			'itop-ext1' => [
				'installed' => true,
				'source' => $bExtensionSource,
			],
		];
		$oMap = iTopExtensionsMapFake::createFromArray($aExtensionsOnDiskOrDb);
		$aExtensions = $oMap->GetAllExtensionsToDisplayInSetup(false, !$bDisableUninstallChecks);
		$this->assertEquals($bExpectedMandatory, $aExtensions['itop-ext1']->bMandatory);
	}

	public function ProviderGetAddedAndRemovedExtensions()
	{
		return [
			'no extensions' => [
				'aExtensionsOnDiskOrDb' => [],

				'aSelected' => [],
				'aExpectedAddedList' => [],
				'aExpectedRemovedList' => [],
			],
			'no extensions added nor removed' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'aSelected' => [],
				'aExpectedAddedList' => [],
				'aExpectedRemovedList' => [],
			],
			'One added extension' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'aSelected' => ['itop-ext1'],
				'aExpectedAddedList' => ['itop-ext1' => 'itop-ext1'],
				'aExpectedRemovedList' => [],
			],
			'One removed extension' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => true,
					],
				],
				'aSelected' => [],
				'aExpectedAddedList' => [],
				'aExpectedRemovedList' => ['itop-ext1' => 'itop-ext1'],
			],
			'Forced removed extension' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => true,
						'uninstallable' => false,
					],
				],
				'aSelected' => [],
				'aExpectedAddedList' => [],
				'aExpectedRemovedList' => ['itop-ext1' => 'itop-ext1'],
			],
			'added and removed extensions' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext-added1' => [
						'installed' => false,
					],
					'itop-ext-added2' => [
						'installed' => false,
					],
					'itop-ext-removed1' => [
						'installed' => true,
					],
					'itop-ext-removed2' => [
						'installed' => true,
					],
				],
				'aSelected' => ['itop-ext-added1', 'itop-ext-added2'],
				'aExpectedAddedList' => ['itop-ext-added1' => 'itop-ext-added1', 'itop-ext-added2' => 'itop-ext-added2'],
				'aExpectedRemovedList' => ['itop-ext-removed1' => 'itop-ext-removed1', 'itop-ext-removed2' => 'itop-ext-removed2'],
			],

		];
	}

	/**
	 * @dataProvider ProviderGetAddedAndRemovedExtensions
	 */
	public function testGetAddedAndRemovedExtensions($aExtensionsOnDiskOrDb, $aSelectedExtensions, $aExpectedAddedList, $aExpectedRemovedList)
	{
		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsOnDiskOrDb));
		[$aAddedList, $aRemovedList, $aNotUninstallableList] = $this->oStep->GetAddedAndRemovedExtensions($aSelectedExtensions);
		$this->assertEquals($aExpectedAddedList, $aAddedList);
		$this->assertEquals($aExpectedRemovedList, $aRemovedList);
	}

	public function testGetStepInfo_PackageWithoutInstallationXML()
	{

		$aExtensionsOnDiskOrDb = self::GivenExtensionsOnDisk();
		$oWizStepModulesChoice = $this->GivenWizStepModulesChoiceWithoutXmlInstallation($aExtensionsOnDiskOrDb);

		$expected = [
			'title'       => 'Modules Selection',
			'description' => '<h2>Select the modules to install. You can launch the installation again to install new modules, but you cannot remove already installed modules.</h2>',
			'banner'      => '/images/icons/icons8-apps-tab.svg',
			'options'     => $aExtensionsOnDiskOrDb,
		];

		$this->CallAndCheckTwice($oWizStepModulesChoice, null, $expected);
		$this->CallAndCheckTwice($oWizStepModulesChoice, 1, null);
	}

	private function GivenWizStepModulesChoiceWithoutXmlInstallation(array $aExtensionsOnDiskOrDb): WizStepModulesChoiceFake
	{
		$oExtensionsMap = $this->createMock(iTopExtensionsMap::class);
		$oExtensionsMap->expects($this->once())
			->method('GetAllExtensionsOptionInfo')
			->willReturn($aExtensionsOnDiskOrDb);

		$oWizard = new WizardController('', '');
		$oWizStepModulesChoice = new WizStepModulesChoiceFake($oWizard, '');
		$oWizStepModulesChoice->setExtensionMap($oExtensionsMap);

		return $oWizStepModulesChoice;
	}

	public static function PackageWithInstallationXMLProvider()
	{
		require_once __DIR__.'/../../../../approot.inc.php';
		require_once APPROOT.'setup/parameters.class.inc.php';

		$aUsecases = [];

		$aUsecases["[no step] with extensions"] = [
			'iGetStepInfoIdxArg'                 => null,
			'expected'              => self::GetStep(0),
		];

		for ($i = 0; $i < 4; $i++) {
			$aUsecases["[step $i] with extensions"] = [
				'iGetStepInfoIdxArg'                 => $i,
				'expected'              => self::GetStep($i),
			];
		}

		$aUsecases["[step 6] with extensions => NO STEP ANYMORE"] = [
			'iGetStepInfoIdxArg'                 => 6,
			'expected'              => null,
			'iGetAllExtensionsOptionInfoCallCount' => 1,
		];

		return $aUsecases;
	}

	/**
	 * @dataProvider PackageWithInstallationXMLProvider
	 */
	public function testGetStepInfo_PackageWithInstallationXMLWithExtensions($iGetStepInfoIdxArg, $expected, $iGetAllExtensionsOptionInfoCallCount = 0)
	{
		$aExtensionsOnDiskOrDb = self::GivenExtensionsOnDisk();
		$oWizStepModulesChoice = $this->GivenWizStepModulesChoiceWithXmlInstallation($aExtensionsOnDiskOrDb, $iGetAllExtensionsOptionInfoCallCount);

		$this->CallAndCheckTwice($oWizStepModulesChoice, $iGetStepInfoIdxArg, $expected);
	}

	public function testGetStepInfo_PackageWithInstallationXML_AfterLastStepWithExtensions()
	{
		$expected = [
			'title'       => 'Extensions',
			'description' => '<h2>Select additional extensions to install. You can launch the installation again to install new extensions or remove installed ones.</h2>',
			'banner'      => '/images/icons/icons8-puzzle.svg',
			'options'     => self::GivenExtensionsOnDisk(),
		];

		$aExtensionsOnDiskOrDb = self::GivenExtensionsOnDisk();
		$oWizStepModulesChoice = $this->GivenWizStepModulesChoiceWithXmlInstallation($aExtensionsOnDiskOrDb, 1);

		$this->CallAndCheckTwice($oWizStepModulesChoice, 5, $expected);
	}

	public function testGetStepInfo_PackageWithInstallationXMLAfterLastStepWithoutExtensions()
	{
		$oWizStepModulesChoice = $this->GivenWizStepModulesChoiceWithXmlInstallation([], 1);

		$this->CallAndCheckTwice($oWizStepModulesChoice, 5, null);
	}

	public function testGetStepInfo_PackageWithInstallationXML_MakeSureNextStepIsAlsoCached()
	{
		$aExtensionsOnDiskOrDb = self::GivenExtensionsOnDisk();
		$oWizStepModulesChoice = $this->GivenWizStepModulesChoiceWithXmlInstallation($aExtensionsOnDiskOrDb, 1);

		$this->CallAndCheckTwice($oWizStepModulesChoice, 4, self::GetStep(4));

		$expected = [
			'title'       => 'Extensions',
			'description' => '<h2>Select additional extensions to install. You can launch the installation again to install new extensions or remove installed ones.</h2>',
			'banner'      => '/images/icons/icons8-puzzle.svg',
			'options'     => $aExtensionsOnDiskOrDb,
		];
		$this->CallAndCheckTwice($oWizStepModulesChoice, 5, $expected);
	}

	private static function GivenExtensionsOnDisk(): array
	{
		return [
			'itop-ext-added1' => [
				'installed' => false,
			],
			'itop-ext-added2' => [
				'installed' => false,
			],
		];
	}

	private function GivenWizStepModulesChoiceWithXmlInstallation(array $aExtensionsOnDiskOrDb, $iGetAllExtensionsOptionInfoCallCount): WizStepModulesChoiceFake
	{
		$oExtensionsMap = $this->createMock(iTopExtensionsMap::class);
		$oExtensionsMap->expects($this->exactly($iGetAllExtensionsOptionInfoCallCount))
			->method('GetAllExtensionsOptionInfo')
			->willReturn($aExtensionsOnDiskOrDb);

		$oWizard = new WizardController('', '');
		//needed to find installation.xml
		$oWizard->SetParameter('source_dir', __DIR__.'/ressources');
		$oWizStepModulesChoice = new WizStepModulesChoiceFake($oWizard, '');
		$oWizStepModulesChoice->setExtensionMap($oExtensionsMap);

		return $oWizStepModulesChoice;
	}

	private function CallAndCheckTwice($oStep, $iGetStepInfoIdxArg, $expected)
	{
		$aRes = $this->InvokeNonPublicMethod(WizStepModulesChoiceFake::class, 'GetStepInfo', $oStep, [$iGetStepInfoIdxArg]);
		$this->assertEquals($expected, $aRes, "step:".$iGetStepInfoIdxArg);

		$aRes = $this->InvokeNonPublicMethod(WizStepModulesChoiceFake::class, 'GetStepInfo', $oStep, [$iGetStepInfoIdxArg]);
		$this->assertEquals($expected, $aRes, "(2nd call) step:".$iGetStepInfoIdxArg);
	}

	private static function GetStep($index)
	{
		$aParams = new XMLParameters(__DIR__.'/ressources/installation.xml');
		$aSteps = $aParams->Get('steps', []);

		return $aSteps[$index] ?? null;
	}

	public function ProviderGetSelectedModules()
	{
		return [
			'No extension selected' => [
				'aSelected' => [],
				'aExpectedModules' => [],
				'aExpectedExtensions' => [],
			],
			'One extension selected' => [
				'aSelected' => ['_0' => '_0'],
				'aExpectedModules' => ['combodo-sample-module' => true],
				'aExpectedExtensions' => ['combodo-sample'],
			],
			'More extensions selected' => [
				'aSelected' => ['_0' => '_0', '_1' => '_1'],
				'aExpectedModules' => ['combodo-sample-module' => true, 'combodo-test-moduleA' => true, 'combodo-test-moduleB' => true],
				'aExpectedExtensions' => ['combodo-sample', 'combodo-test'],
			],
		];
	}

	/**
	 * @dataProvider ProviderGetSelectedModules
	 */
	public function testGetSelectedModules($aSelectedExtensions, $aExpectedModules, $aExpectedExtensions)
	{
		$aExtensionsMapData = [
			'combodo-sample' => [
				'installed' => false,
			],
			'combodo-test' => [
				'installed' => false,
			],
		];
		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

		$aStepInfo = [
			'title' => 'Extensions',
			'description' => '',
			'banner' => '',
			'options' => [
				[
					'extension_code' => 'combodo-sample',
					'title' => 'Sample extension',
					'description' => '',
					'more_info' => '',
					'default' => true,
					'modules' => [
						'combodo-sample-module',
					],
					'mandatory' => false,
					'source_label' => '',
					'uninstallable' => true,
					'missing' => false,
				],
				[
					'extension_code' => 'combodo-test',
					'title' => 'Test extension',
					'description' => '',
					'more_info' => '',
					'default' => true,
					'modules' => [
						'combodo-test-moduleA',
						'combodo-test-moduleB',
					],
					'mandatory' => false,
					'source_label' => '',
					'uninstallable' => true,
					'missing' => false,
				],
			],
		];

		$aModules = [];
		$aExtensions = [];
		$this->oStep->GetSelectedModules($aStepInfo, $aSelectedExtensions, $aModules, '', '', $aExtensions);
		$this->assertEquals($aExpectedModules, $aModules);
		$this->assertEquals($aExpectedExtensions, $aExtensions);
	}

	public function testGetSelectedModulesShouldAlwaysSelectMandatoryExtension()
	{

		$aSelectedExtensions = ['_0' => '_0'];

		$aExtensionsMapData = [
			'combodo-sample' => [
				'installed' => true,
			],
		];

		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

		$aStepInfo = [
			'title' => 'Extensions',
			'description' => '',
			'banner' => '',
			'options' => [
				[
					'extension_code' => 'combodo-sample',
					'title' => 'Sample extension',
					'description' => '',
					'more_info' => '',
					'default' => true,
					'modules' => [
						'combodo-sample-module',
					],
					'mandatory' => false,
					'source_label' => '',
					'uninstallable' => true,
					'missing' => true,
				],
			],
		];

		$aExpectedModules = ['combodo-sample-module' => true];
		$aExpectedExtensions = ['combodo-sample'];

		$aModules = [];
		$aExtensions = [];
		$this->oStep->GetSelectedModules($aStepInfo, $aSelectedExtensions, $aModules, '', '', $aExtensions);
		$this->assertEquals($aExpectedModules, $aModules);
		$this->assertEquals($aExpectedExtensions, $aExtensions);
	}

	public function testGetSelectedModulesShouldShouldParseAutoSelectCondition()
	{
		//the 'auto_select' parameter, contrary to its name, deselect the module if its result is false

		$aSelectedExtensions = ['_0' => '_0'];

		$aExtensionsMapData = [
			'combodo-sample' => [
				'installed' => true,
				'module_info' => [
					'combodo-sample-module' => [
						'auto_select' => 'true && false',
					],
				],
			],
		];
		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

		$aStepInfo = [
			'title' => 'Extensions',
			'description' => '',
			'banner' => '',
			'options' => [
				[
					'extension_code' => 'combodo-sample',
					'title' => 'Sample extension',
					'description' => '',
					'more_info' => '',
					'default' => true,
					'modules' => [
						'combodo-sample-module',
					],
					'mandatory' => false,
					'source_label' => '',
					'uninstallable' => true,
					'missing' => true,
				],
			],
		];

		$aExpectedModules = [];
		$aExpectedExtensions = ['combodo-sample'];

		$aModules = [];
		$aExtensions = [];
		$this->oStep->GetSelectedModules($aStepInfo, $aSelectedExtensions, $aModules, '', '', $aExtensions);
		$this->assertEquals($aExpectedModules, $aModules);
		$this->assertEquals($aExpectedExtensions, $aExtensions);
	}

	public function testGetSelectedModulesWithSubOptions()
	{

		$aSelectedExtensions = ['_0' => '_0', '_0_0' => '_0_0'];

		$aExtensionsMapData = [
			'combodo-sample' => [
				'installed' => false,
			],
			'combodo-sub-sample' => [
				'installed' => false,
			],
		];
		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

		$aStepInfo = [
			'options' => [
				[
					'extension_code' => 'combodo-sample',
					'title' => 'Sample extension',
					'description' => '',
					'more_info' => '',
					'default' => true,
					'modules' => [
						'combodo-sample-module',
					],
					'mandatory' => false,
					'source_label' => '',
					'uninstallable' => true,
					'missing' => false,
					'sub_options' => [
						'options' => [
							[
								'extension_code' => 'combodo-sub-sample',
								'title' => 'Sample sub extension',
								'description' => '',
								'more_info' => '',
								'default' => true,
								'modules' => [
									'combodo-sub-sample-module',
								],
								'mandatory' => false,
								'source_label' => '',
								'uninstallable' => true,
								'missing' => false,
							],
						],
					],
				],
			],
		];

		$aExpectedModules = ['combodo-sample-module' => true, 'combodo-sub-sample-module' => true];
		$aExpectedExtensions = ['combodo-sample', 'combodo-sub-sample'];

		$aModules = [];
		$aExtensions = [];
		$this->oStep->GetSelectedModules($aStepInfo, $aSelectedExtensions, $aModules, '', '', $aExtensions);
		$this->assertEquals($aExpectedModules, $aModules);
		$this->assertEquals($aExpectedExtensions, $aExtensions);
	}

	public function testGetSelectedModulesShouldThrowAnExceptionWhenAnySelectedExtensionDoesNotHaveAnyAssociatedModules()
	{
		$aExtensionsMapData = [
			'combodo-sample' => [
				'installed' => false,
			],
		];
		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

		//GetSelectedModules
		$aStepInfo = [
			'title' => 'Extensions',
			'description' => '',
			'banner' => '',
			'options' => [
				[
					'extension_code' => 'combodo-sample',
					'title' => 'Sample extension',
					'description' => '',
					'more_info' => '',
					'default' => true,
					'modules' => [],
					'mandatory' => false,
					'source_label' => '',
					'uninstallable' => true,
					'missing' => false,
				],
			],
		];

		$aModules = [];
		$aExtensions = [];
		$this->expectException('Exception');
		$this->expectExceptionMessage('Extension combodo-sample does not have any module associated');
		$this->oStep->GetSelectedModules($aStepInfo, ['_0' => '_0'], $aModules, '', '', $aExtensions);
	}

}
