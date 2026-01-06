<?php

namespace Combodo\iTop\Test\UnitTest\Integration;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ItopExtensionsMap;
use iTopExtensionsMapFake;
use ModuleDiscovery;
use WizardController;

class WizStepModulesChoiceTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/setup/unattended-install/InstallationFileService.php');
		require_once __DIR__.'/iTopExtensionsMapFake.php';
		require_once __DIR__.'/WizStepModulesChoiceFake.php';

		$this->oStep = new \WizStepModulesChoiceFake(new WizardController('', ''), '');
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

}
