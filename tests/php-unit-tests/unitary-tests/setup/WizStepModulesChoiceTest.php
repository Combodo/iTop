<?php

namespace Combodo\iTop\Test\UnitTest\Integration;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use iTopExtensionsMap;
use iTopExtensionsMapFake;
use ModuleDiscovery;
use WizardController;
use WizStepModulesChoiceFake;
use XMLParameters;

class WizStepModulesChoiceTest extends ItopTestCase
{
	private WizStepModulesChoiceFake $oWizStepModulesChoiceFake;
	private WizardController $oWizard;
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/setup/unattended-install/InstallationFileService.php');
		require_once __DIR__.'/WebPageFake.php';
		require_once __DIR__.'/iTopExtensionsMapFake.php';
		require_once __DIR__.'/WizStepModulesChoiceFake.php';

		$this->oWizard = new WizardController('', '');
		$this->oWizStepModulesChoiceFake = new WizStepModulesChoiceFake($this->oWizard, '');
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
					'dependency_issue' => false,
					'mandatory' => false,
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
					'dependency_issue' => false,
					'mandatory' => false,
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
					'dependency_issue' => false,
					'mandatory' => false,
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
					'dependency_issue' => false,
					'mandatory' => true,
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
					'dependency_issue' => false,
					'mandatory' => true,
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
					'dependency_issue' => false,
					'mandatory' => false,
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
					'dependency_issue' => false,
					'mandatory' => false,
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
					'dependency_issue' => false,
					'mandatory' => false,
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
					'dependency_issue' => false,
					'mandatory' => true,
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
					'dependency_issue' => false,
					'mandatory' => true,
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
					'dependency_issue' => false,
					'mandatory' => false,
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
					'dependency_issue' => false,
					'mandatory' => false,
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
					'dependency_issue' => false,
					'mandatory' => false,
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
					'dependency_issue' => false,
					'mandatory' => false,
				],
			],
			'A non installed extension with missing dependencies should be not checked and disabled' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => false,
						'missing_dependencies' => [
							'itop-ext1-1',
						],
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
					'missing_dependencies' => true,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => true,
					'checked' => false,
					'dependency_issue' => true,
					'mandatory' => false,
				],
			],
			'An installed extension with missing dependencies should be not checked and disabled' => [
				'aExtensionsOnDiskOrDb' => [
					'itop-ext1' => [
						'installed' => true,
						'missing_dependencies' => [
							'itop-ext1-1',
						],
					],
				],
				'aWizardStepDefinition' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
					'missing_dependencies' => true,
				],
				'bCurrentSelected' => false,
				'bDisableUninstallChecks' => false,
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => true,
					'disabled' => true,
					'checked' => false,
					'dependency_issue' => true,
					'mandatory' => false,
				],
			],
		];
	}

	/**
	 * @dataProvider ProviderComputeChoiceFlags
	 */
	public function testComputeChoiceFlags($aExtensionsOnDiskOrDb, $aWizardStepDefinition, $bIsCurrentSelected, $bDisableUninstallChecks, $aExpectedFlags)
	{
		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsOnDiskOrDb));
		$aFlags = $this->oWizStepModulesChoiceFake->ComputeChoiceFlags($aWizardStepDefinition, '_0', $bIsCurrentSelected ? ['_0' => '_0'] : [], false, $bDisableUninstallChecks, true);
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
		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsOnDiskOrDb));
		[$aAddedList, $aRemovedList, $aNotUninstallableList] = $this->oWizStepModulesChoiceFake->GetAddedAndRemovedExtensions($aSelectedExtensions);
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
		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

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
		$this->oWizStepModulesChoiceFake->GetSelectedModules($aStepInfo, $aSelectedExtensions, $aModules, '', '', $aExtensions);
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

		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

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
		$this->oWizStepModulesChoiceFake->GetSelectedModules($aStepInfo, $aSelectedExtensions, $aModules, '', '', $aExtensions);
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
		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

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
		$this->oWizStepModulesChoiceFake->GetSelectedModules($aStepInfo, $aSelectedExtensions, $aModules, '', '', $aExtensions);
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
		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

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
		$this->oWizStepModulesChoiceFake->GetSelectedModules($aStepInfo, $aSelectedExtensions, $aModules, '', '', $aExtensions);
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
		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

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
		$this->oWizStepModulesChoiceFake->GetSelectedModules($aStepInfo, ['_0' => '_0'], $aModules, '', '', $aExtensions);
	}

	public function testGetSelectedModulesShouldNotThrowAnExceptionWhenAMandatoryModuleIsMissing()
	{
		$aExtensionsMapData = [];
		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsMapData));

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
					'mandatory' => true,
					'source_label' => '',
					'uninstallable' => true,
					'missing' => true,
				],
			],
		];

		$aModules = [];
		$aExtensions = [];
		$this->oWizStepModulesChoiceFake->GetSelectedModules($aStepInfo, ['_0' => '_0'], $aModules, '', '', $aExtensions);
		$this->assertCount(0, $aModules);
		$this->assertCount(1, $aExtensions);

	}

	public function ProviderDisplayOptions()
	{
		return [
			'no choices' => [
				'aStepOptions' => [],
				'aStepAlternatives' => [],

				'aSelectedComponents' => [],
				'aDefaults' => [],
				'aExpectedHTML' => '',
			],
			'one not installed extension' => [
				'aStepOptions' => [
					[
						'extension_code' => 'itop-ext-not-installed',
						'title' => 'My extension',
						'description' => 'Do something',
						'more_info' => '',
						'modules' => [],
						'mandatory' => false,
						'source_label' => 'Local extensions folder',
						'uninstallable' => true,
						'missing' => false,
						'version' => '1.2.3',
					],
				],
				'aStepAlternatives' => [],

				'aSelectedComponents' => [],
				'aDefaults' => [],
				'aExpectedHTML' => <<<HTML

			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-ext-not-installed">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-ext-not-installed" name="choice[_0]" type="checkbox" value="_0" />
					
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-ext-not-installed"><b>My extension</b></label>
						
						<div id="badge--itop-ext-not-installed--to-be-installed" class="ibo-badge ibo-block checked ibo-is-cyan" title="This extension will be installed during the setup." >to be installed</div><div id="badge--itop-ext-not-installed--not-installed" class="ibo-badge ibo-block unchecked ibo-is-blue-grey" title="This extension is not part of the current installation." >not installed</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						<span>v1.2.3</span><span>Local extensions folder</span>
					</div>
					<div class="ibo-extension-details--information--description">
						Do something
		
					</div>
				</div>
			</div>
		
HTML,
			],
			'one installed extension' => [
				'aStepOptions' => [
					[
						'extension_code' => 'itop-ext-installed',
						'title' => 'My extension',
						'description' => 'Do something',
						'more_info' => '',
						'modules' => [],
						'mandatory' => false,
						'source_label' => 'Local extensions folder',
						'uninstallable' => true,
						'missing' => false,
						'version' => '1.2.3',
					],
				],
				'aStepAlternatives' => [],

				'aSelectedComponents' => [],
				'aDefaults' => [],
				'aExpectedHTML' => <<<HTML

			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-ext-installed">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-ext-installed" name="choice[_0]" type="checkbox" value="_0" />
					
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-ext-installed"><b>My extension</b></label>
						
						<div id="badge--itop-ext-installed--installed" class="ibo-badge ibo-block checked ibo-is-green" title="This extension is part of the current installation." >installed</div><div id="badge--itop-ext-installed--to-be-uninstalled" class="ibo-badge ibo-block unchecked ibo-is-red" title="This extension will be uninstalled during the setup." >to be uninstalled</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						<span>v1.2.3</span><span>Local extensions folder</span>
					</div>
					<div class="ibo-extension-details--information--description">
						Do something
		
					</div>
				</div>
			</div>
		
HTML,
			],

			'one installed extension that cannot be uninstalled' => [
				'aStepOptions' => [
					[
						'extension_code' => 'itop-ext-installed',
						'title' => 'My extension',
						'description' => 'Do something',
						'more_info' => '',
						'modules' => [],
						'mandatory' => false,
						'source_label' => 'Local extensions folder',
						'uninstallable' => false,
						'missing' => false,
						'version' => '1.2.3',
					],
				],
				'aStepAlternatives' => [],

				'aSelectedComponents' => [],
				'aDefaults' => [],
				'aExpectedHTML' => <<<HTML

			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-ext-installed">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-ext-installed" name="choice[_0]" type="checkbox" value="_0"  disabled data-disabled="disabled" checked />
					<input type="hidden" name="choice[_0]" value="_0"/>
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-ext-installed"><b>My extension</b></label>
						
						<div id="badge--itop-ext-installed--installed" class="ibo-badge ibo-block checked ibo-is-green" title="This extension is part of the current installation." >installed</div><div id="badge--itop-ext-installed--to-be-uninstalled" class="ibo-badge ibo-block unchecked ibo-is-red" title="This extension will be uninstalled during the setup." >to be uninstalled</div><div id="badge--itop-ext-installed--not-uninstallable" class="ibo-badge ibo-block ibo-is-yellow" title="Once this extension has been installed, it should not be uninstalled." >cannot be uninstalled</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						<span>v1.2.3</span><span>Local extensions folder</span>
					</div>
					<div class="ibo-extension-details--information--description">
						Do something
		
					</div>
				</div>
			</div>
		
HTML,
			],
			'one mandatory extension' => [
				'aStepOptions' => [
					[
						'extension_code' => 'itop-ext-not-installed',
						'title' => 'My extension',
						'description' => 'Do something',
						'more_info' => '',
						'modules' => [],
						'mandatory' => true,
						'source_label' => 'Local extensions folder',
						'uninstallable' => true,
						'missing' => false,
						'version' => '1.2.3',
					],
				],
				'aStepAlternatives' => [],

				'aSelectedComponents' => [],
				'aDefaults' => [],
				'aExpectedHTML' => <<<HTML

			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-ext-not-installed">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-ext-not-installed" name="choice[_0]" type="checkbox" value="_0"  disabled data-disabled="disabled" checked />
					<input type="hidden" name="choice[_0]" value="_0"/>
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-ext-not-installed"><b>My extension</b></label>
						
						<div id="badge--itop-ext-not-installed--to-be-installed" class="ibo-badge ibo-block checked ibo-is-cyan" title="This extension will be installed during the setup." >to be installed</div><div id="badge--itop-ext-not-installed--not-installed" class="ibo-badge ibo-block unchecked ibo-is-blue-grey" title="This extension is not part of the current installation." >not installed</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						<span>v1.2.3</span><span>Local extensions folder</span>
					</div>
					<div class="ibo-extension-details--information--description">
						Do something
		
					</div>
				</div>
			</div>
		
HTML,
			],
			'one choice alternative' => [
				'aStepOptions' => [],
				'aStepAlternatives' => [
					[
						'extension_code' => 'itop-alt-nothing',
						'title' => 'No Change',
						'description' => 'Do nothing',
						'modules' =>  [],
					],
				],

				'aSelectedComponents' => [],
				'aDefaults' => [],
				'aExpectedHTML' => <<<HTML

			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-alt-nothing">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-alt-nothing" name="choice[_0]" type="radio" value="_0"  checked />
					
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-alt-nothing"><b>No Change</b></label>
						
						<div id="badge--itop-alt-nothing--to-be-installed" class="ibo-badge ibo-block checked ibo-is-cyan" title="This extension will be installed during the setup." >to be installed</div><div id="badge--itop-alt-nothing--not-installed" class="ibo-badge ibo-block unchecked ibo-is-blue-grey" title="This extension is not part of the current installation." >not installed</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						
					</div>
					<div class="ibo-extension-details--information--description">
						Do nothing
		
					</div>
				</div>
			</div>
		
HTML,
			],
			'two choices alternative with non-empty installed' => [
				'aStepOptions' => [],
				'aStepAlternatives' => [
					[
						'extension_code' => 'itop-alt-something',
						'title' => 'Change',
						'description' => 'I am something',
						'modules' =>  [
							'itop-alt-module',
						],
					],
					[
						'extension_code' => 'itop-alt-nothing',
						'title' => 'No Change',
						'description' => 'Do nothing',
						'modules' =>  [],
					],
				],

				'aSelectedComponents' => [],
				'aDefaults' => [],
				'aExpectedHTML' => <<<HTML

			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-alt-something">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-alt-something" name="choice[_0]" type="radio" value="_0" />
					
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-alt-something"><b>Change</b></label>
						
						<div id="badge--itop-alt-something--installed" class="ibo-badge ibo-block checked ibo-is-green" title="This extension is part of the current installation." >installed</div><div id="badge--itop-alt-something--to-be-uninstalled" class="ibo-badge ibo-block unchecked ibo-is-red" title="This extension will be uninstalled during the setup." >to be uninstalled</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						
					</div>
					<div class="ibo-extension-details--information--description">
						I am something
		
					</div>
				</div>
			</div>
		
			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-alt-nothing">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-alt-nothing" name="choice[_0]" type="radio" value="_1"  checked />
					
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-alt-nothing"><b>No Change</b></label>
						
						<div id="badge--itop-alt-nothing--to-be-installed" class="ibo-badge ibo-block checked ibo-is-cyan" title="This extension will be installed during the setup." >to be installed</div><div id="badge--itop-alt-nothing--not-installed" class="ibo-badge ibo-block unchecked ibo-is-blue-grey" title="This extension is not part of the current installation." >not installed</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						
					</div>
					<div class="ibo-extension-details--information--description">
						Do nothing
		
					</div>
				</div>
			</div>
		
HTML,
			],
			'two choices with sub options' => [
				'aStepOptions' => [],
				'aStepAlternatives' => [
					[
						'extension_code' => 'itop-alt-something',
						'title' => 'Change',
						'description' => 'I am something',
						'modules' =>  [],
						'sub_options' => [
							'options' => [
								[
									'extension_code' => 'itop-ext-not-installed',
									'title' => 'My extension',
									'description' => 'Do something',
									'more_info' => '',
									'modules' => [],
									'mandatory' => false,
									//'source_label' => '',
									'uninstallable' => true,
									'missing' => false,
									//'version' => '1.2.3',
								],
							],
						],
					],
					[
						'extension_code' => 'itop-alt-nothing',
						'title' => 'No Change',
						'description' => 'Do nothing',
						'modules' =>  [],
					],
				],

				'aSelectedComponents' => [],
				'aDefaults' => [],
				'aExpectedHTML' => <<<HTML

			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-alt-something">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-alt-something" name="choice[_0]" type="radio" value="_0" />
					
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-alt-something"><b>Change</b></label>
						
						<div id="badge--itop-alt-something--installed" class="ibo-badge ibo-block checked ibo-is-green" title="This extension is part of the current installation." >installed</div><div id="badge--itop-alt-something--to-be-uninstalled" class="ibo-badge ibo-block unchecked ibo-is-red" title="This extension will be uninstalled during the setup." >to be uninstalled</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						
					</div>
					<div class="ibo-extension-details--information--description">
						I am something
		<div id="sub_choicesitop-alt-something">
			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-ext-not-installed">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-ext-not-installed" name="choice[_0_0]" type="checkbox" value="_0_0" />
					
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-ext-not-installed"><b>My extension</b></label>
						
						<div id="badge--itop-ext-not-installed--to-be-installed" class="ibo-badge ibo-block checked ibo-is-cyan" title="This extension will be installed during the setup." >to be installed</div><div id="badge--itop-ext-not-installed--not-installed" class="ibo-badge ibo-block unchecked ibo-is-blue-grey" title="This extension is not part of the current installation." >not installed</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						
					</div>
					<div class="ibo-extension-details--information--description">
						Do something
		
					</div>
				</div>
			</div>
		</div>
					</div>
				</div>
			</div>
		
			<div class="ibo-extension-details ibo-content-block ibo-block " data-id="itop-alt-nothing">
				<div class="ibo-extension-details--actions">
					<input class="wiz-choice" id="itop-alt-nothing" name="choice[_0]" type="radio" value="_1"  checked />
					
				</div>
				<div class="ibo-extension-details--information">
					<div class="ibo-extension-details--information--label">
						<label for="itop-alt-nothing"><b>No Change</b></label>
						
						<div id="badge--itop-alt-nothing--to-be-installed" class="ibo-badge ibo-block checked ibo-is-cyan" title="This extension will be installed during the setup." >to be installed</div><div id="badge--itop-alt-nothing--not-installed" class="ibo-badge ibo-block unchecked ibo-is-blue-grey" title="This extension is not part of the current installation." >not installed</div>
					</div>
					<div class="ibo-extension-details--information--metadata">
						
					</div>
					<div class="ibo-extension-details--information--description">
						Do nothing
		
					</div>
				</div>
			</div>
		
HTML,
			],

		];
	}

	/**
	 * @dataProvider ProviderDisplayOptions
	 */
	public function testDisplayOptions($aStepOptions, $aStepAlternatives, $aSelectedComponents, $aDefaults, $sExpectedHTML)
	{
		$aExtensionsOnDiskOrDb = [
			'itop-ext-not-installed' => [
				'installed' => false,
			],
			'itop-ext-installed' => [
				'installed' => true,
			],
			'itop-alt-nothing' => [
				'installed' => false,
			],
			'itop-alt-something' => [
				'installed' => true,
			],
		];
		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsOnDiskOrDb));
		$aStepInfo = [
			'options' => $aStepOptions,
			'alternatives' => $aStepAlternatives,
		];
		$oPage = new \WebPageFake();

		$this->oWizStepModulesChoiceFake->DisplayOptions($oPage, $aStepInfo, $aSelectedComponents, $aDefaults);

		$this->assertEquals($sExpectedHTML, $oPage->sContent);
	}

	public function testGetSelectedComponents()
	{
		$aParams = new XMLParameters(__DIR__.'/ressources/installation_330.xml');
		$aSteps = $aParams->Get('steps', []);

		$aSelectedExtensions = ["itop-config-mgmt-core","itop-config-mgmt-datacenter","itop-config-mgmt-end-user","itop-config-mgmt-storage","itop-config-mgmt-virtualization","itop-container-mgmt","itop-service-mgmt-enterprise","itop-ticket-mgmt-simple-ticket","itop-ticket-mgmt-simple-ticket-enhanced-portal","itop-change-mgmt-simple","itop-kown-error-mgmt","itop-problem-mgmt","combodo-oauth2-client","combodo-mfa-extended","combodo-data-replication","combodo-api-playground","combodo-snapshot"];
		$aRes = $this->oWizStepModulesChoiceFake->GetSelectedComponents($aSteps, json_encode($aSelectedExtensions));

		$aExpected = json_decode('[{"_0":"_0","_1":"_1","_2":"_2","_3":"_3","_4":"_4","_4_0":"_4_0"},{"_0":"_0"},{"_0":"_0","_0_0":"_0_0"},{"_0":"_0"},{"_0":"_0","_1":"_1"}]', true);
		$this->assertEquals($aExpected, $aRes);
	}

	public function testGetWizardSteps()
	{
		$this->oWizard->SetParameter('source_dir', __DIR__.'/ressources');
		$aExtensionsOnDiskOrDb = [
			'itop-ext-not-installed' => [
				'installed' => false,
			],
			'itop-ext-installed' => [
				'installed' => true,
			],
		];
		$this->oWizStepModulesChoiceFake->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsOnDiskOrDb));

		$expected = [
			["class" => "WizStepWelcome","state" => ""],
			["class" => "WizStepDetectedInfo","state" => ""],
			["class" => "WizStepUpgradeMiscParams","state" => ""],
		];

		for ($i = 0;$i <= 5; $i++) {
			$expected [] = ["class" => "WizStepModulesChoice","state" => "".$i];
		}
		$this->assertEquals($expected, $this->oWizStepModulesChoiceFake->GetWizardSteps());
	}

	public function testGetWizardStepsWithoutAnyExtension()
	{
		$this->oWizard->SetParameter('source_dir', __DIR__.'/ressources');
		$oExtensionMap = $this->createMock(iTopExtensionsMap::class);
		$oExtensionMap->expects(self::any())->method('GetAllExtensionsOptionInfo')->willReturn([]);

		$this->oWizStepModulesChoiceFake->setExtensionMap($oExtensionMap);

		$expected = [
			["class" => "WizStepWelcome","state" => ""],
			["class" => "WizStepDetectedInfo","state" => ""],
			["class" => "WizStepUpgradeMiscParams","state" => ""],
		];

		for ($i = 0;$i <= 4; $i++) {
			$expected [] = ["class" => "WizStepModulesChoice","state" => "".$i];
		}
		$this->assertEquals($expected, $this->oWizStepModulesChoiceFake->GetWizardSteps());
	}
}
