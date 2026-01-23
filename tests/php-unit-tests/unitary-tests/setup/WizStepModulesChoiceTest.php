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
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => false,
					'checked' => true,
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
				'aExpectedFlags' => [
					'uninstallable' => false,
					'missing' => false,
					'installed' => true,
					'disabled' => true,
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
	public function testComputeChoiceFlags($aExtensionsOnDiskOrDb, $aWizardStepDefinition, $bIsCurrentSelected, $aExpectedFlags)
	{
		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensionsOnDiskOrDb));
		$aFlags = $this->oStep->ComputeChoiceFlags($aWizardStepDefinition, '_0', $bIsCurrentSelected ? ['_0' => '_0'] : [], false, false, true);
		$this->assertEquals($aExpectedFlags, $aFlags);
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
		$aExtensionsOnDiskOrDb = [
			'itop-ext-added1' => [
				'installed' => false,
			],
			'itop-ext-added2' => [
				'installed' => false,
			],
		];

		$oWizStepModulesChoice = $this->GivenWizStepModulesChoice($aExtensionsOnDiskOrDb);

		$expected = [
			'title'       => 'Modules Selection',
			'description' => '<h2>Select the modules to install. You can launch the installation again to install new modules, but you cannot remove already installed modules.</h2>',
			'banner'      => '/images/icons/icons8-apps-tab.svg',
			'options'     => $aExtensionsOnDiskOrDb,
		];

		$this->CallAndCheckTwice($oWizStepModulesChoice, [], $expected);
		$this->CallAndCheckTwice($oWizStepModulesChoice, [1], null);
	}

	public static function PackageWithInstallationXMLProvider()
	{

		require_once __DIR__.'/../../../../approot.inc.php';
		require_once APPROOT.'setup/parameters.class.inc.php';

		$aExtensionsOnDiskOrDb = [
			'itop-ext-added1' => [
				'installed' => false,
			],
			'itop-ext-added2' => [
				'installed' => false,
			],
		];

		$aUsecases = [];

		$expected = self::GetStep(0);
		$aUsecases["[no step] with extensions"] = [
			'aExtensionsOnDiskOrDb' => $aExtensionsOnDiskOrDb,
			'aArgs'                 => [],
			'expected'              => $expected,
		];

		for ($i = 0; $i < 5; $i++) {
			$expected = self::GetStep($i);
			$aUsecases["[step $i] with extensions"] = [
				'aExtensionsOnDiskOrDb' => $aExtensionsOnDiskOrDb,
				'aArgs'                 => [$i],
				'expected'              => $expected,
			];
		}

		$expected = [
			'title'       => 'Extensions',
			'description' => '<h2>Select additional extensions to install. You can launch the installation again to install new extensions or remove installed ones.</h2>',
			'banner'      => '/images/icons/icons8-puzzle.svg',
			'options'     => $aExtensionsOnDiskOrDb,
		];
		$aUsecases["[step 5] with extensions => EXTENSION STEP"] = [
			'aExtensionsOnDiskOrDb' => $aExtensionsOnDiskOrDb,
			'aArgs'                 => [5],
			'expected'              => $expected,
		];

		$aUsecases["[step 6] with extensions => NO STEP ANYMORE"] = [
			'aExtensionsOnDiskOrDb' => $aExtensionsOnDiskOrDb,
			'aArgs'                 => [6],
			'expected'              => null,
		];

		$aUsecases["[step 5] without extensions => NO STEP ANYMORE"] = [
			'aExtensionsOnDiskOrDb' => [],
			'aArgs'                 => [5],
			'expected'              => null,
		];

		return $aUsecases;
	}

	/**
	 * @dataProvider PackageWithInstallationXMLProvider
	 */
	public function testGetStepInfo_PackageWithInstallationXML($aExtensionsOnDiskOrDb, $aArgs, $expected)
	{
		$oWizStepModulesChoice = $this->GivenWizStepModulesChoice($aExtensionsOnDiskOrDb, true);

		$this->CallAndCheckTwice($oWizStepModulesChoice, $aArgs, $expected);
	}

	private function GivenWizStepModulesChoice(array $aExtensionsOnDiskOrDb, $bInstallationXmlProvided = false): WizStepModulesChoiceFake
	{
		$oExtensionsMap = $this->createMock(iTopExtensionsMap::class);
		$oExtensionsMap->expects($this->once())
			->method('GetAllExtensionsOptionInfo')
			->willReturn($aExtensionsOnDiskOrDb);

		$oWizard = new WizardController('', '');
		if ($bInstallationXmlProvided) {
			//needed to find installation.xml
			$oWizard->SetParameter('source_dir', __DIR__.'/ressources');
		}
		$oWizStepModulesChoice = new WizStepModulesChoiceFake($oWizard, '');
		$oWizStepModulesChoice->setExtensionMap($oExtensionsMap);

		return $oWizStepModulesChoice;
	}

	private function CallAndCheckTwice($oStep, $aArgs, $expected)
	{
		$aRes = $this->InvokeNonPublicMethod(WizStepModulesChoiceFake::class, 'GetStepInfo', $oStep, $aArgs);
		$this->assertEquals($expected, $aRes, "step:".var_export($aArgs, true));

		$aRes = $this->InvokeNonPublicMethod(WizStepModulesChoiceFake::class, 'GetStepInfo', $oStep, $aArgs);
		$this->assertEquals($expected, $aRes, "(2nd call) step:".var_export($aArgs, true));
	}

	private static function GetStep($index)
	{
		$aParams = new XMLParameters(__DIR__.'/ressources/installation.xml');
		$aSteps = $aParams->Get('steps', []);

		return $aSteps[$index] ?? null;
	}

}
