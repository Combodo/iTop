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
			'selected but not installed extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'bUpgrade' => false,
				'bDisableUninstallCheck' => false,
				'sChoiceId' => '_0',
				'aStepInfo' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
				],
				'aSelected' => ['_0' => '_0'],
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => false,
					'checked' => true,
				],
			],
			'not selected, not installed extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'bUpgrade' => true,
				'bDisableUninstallCheck' => false,
				'sChoiceId' => '_0',
				'aStepInfo' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
				],
				'aSelected' => [],
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => false,
					'checked' => false,
				],
			],
			'installed extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => true,
					],
				],
				'bUpgrade' => true,
				'bDisableUninstallCheck' => false,
				'sChoiceId' => '_0',
				'aStepInfo' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => true,
				],
				'aSelected' => [],
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => true,
					'disabled' => false,
					'checked' => false,
				],
			],
			'installed non uninstallable extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => true,
					],
				],
				'bUpgrade' => true,
				'bDisableUninstallCheck' => false,
				'sChoiceId' => '_0',
				'aStepInfo' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => false,
					'uninstallable' => false,
				],
				'aSelected' => [],
				'aExpectedFlags' => [
					'uninstallable' => false,
					'missing' => false,
					'installed' => true,
					'disabled' => true,
					'checked' => true,
				],
			],
			'mandatory extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'bUpgrade' => true,
				'bDisableUninstallCheck' => false,
				'sChoiceId' => '_0',
				'aStepInfo' => [
					'extension_code' => 'itop-ext1',
					'mandatory' => true,
					'uninstallable' => true,
				],
				'aSelected' => [],
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => true,
					'checked' => true,
				],
			],
			'optional sub extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => false,
					],
					'itop-ext1-1' => [
						'installed' => false,
					],
				],
				'bUpgrade' => true,
				'bDisableUninstallCheck' => false,
				'sChoiceId' => '_0',
				'aStepInfo' => [
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
				'aSelected' => [],
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => false,
					'checked' => false,
				],
			],
			'mandatory sub extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => false,
					],
					'itop-ext1-1' => [
						'installed' => false,
					],
				],
				'bUpgrade' => true,
				'bDisableUninstallCheck' => false,
				'sChoiceId' => '_0',
				'aStepInfo' => [
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
				'aSelected' => [],
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => false,
					'disabled' => true,
					'checked' => true,
				],
			],
			'non uninstallable sub extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => true,
					],
					'itop-ext1-1' => [
						'installed' => true,
					],
				],
				'bUpgrade' => true,
				'bDisableUninstallCheck' => false,
				'sChoiceId' => '_0',
				'aStepInfo' => [
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
				'aSelected' => [],
				'aExpectedFlags' => [
					'uninstallable' => true,
					'missing' => false,
					'installed' => true,
					'disabled' => true,
					'checked' => true,
				],
			],
		];
	}

	/**
	 * @dataProvider ProviderComputeChoiceFlags
	 */
	public function testComputeChoiceFlags($aExtensions, $bUpgrade, $bDisableUninstallCheck, $sChoiceId, $aStepInfo, $aSelected, $aExpectedFlags)
	{
		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensions));
		$aFlags = $this->oStep->ComputeChoiceFlags($aStepInfo, $sChoiceId, $aSelected, false, $bDisableUninstallCheck, $bUpgrade);
		$this->assertEquals($aExpectedFlags, $aFlags);
	}

	public function ProviderGetAddedAndRemovedExtensions()
	{
		return [
			'no extensions' => [
				'aExtensions' => [],

				'aSelected' => [],
				'sExpectedAddedList' => '<ul><li>No extension added.</li></ul>',
				'sExpectedRemovedList' => '<ul><li>No extension removed.</li></ul>',
			],
			'no extensions selected' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'aSelected' => [],
				'sExpectedAddedList' => '<ul><li>No extension added.</li></ul>',
				'sExpectedRemovedList' => '<ul><li>No extension removed.</li></ul>',
			],
			'no extensions removed' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => true,
					],
				],
				'aSelected' => ['itop-ext1'],
				'sExpectedAddedList' => '<ul><li>No extension added.</li></ul>',
				'sExpectedRemovedList' => '<ul><li>No extension removed.</li></ul>',
			],
			'One added extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => false,
					],
				],
				'aSelected' => ['itop-ext1'],
				'sExpectedAddedList' => '<ul><li>itop-ext1</li></ul>',
				'sExpectedRemovedList' => '<ul><li>No extension removed.</li></ul>',
			],
			'One removed extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => true,
					],
				],
				'aSelected' => [],
				'sExpectedAddedList' => '<ul><li>No extension added.</li></ul>',
				'sExpectedRemovedList' => '<ul><li>itop-ext1</li></ul>',
			],
			'Forced removed extension' => [
				'aExtensions' => [
					'itop-ext1' => [
						'installed' => true,
						'uninstallable' => false,
					],
				],
				'aSelected' => [],
				'sExpectedAddedList' => '<ul><li>No extension added.</li></ul>',
				'sExpectedRemovedList' => '<ul><li>itop-ext1 (forced uninstallation)</li></ul>',
			],
			'added and removed extensions' => [
				'aExtensions' => [
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
				'sExpectedAddedList' => '<ul><li>itop-ext-added1</li><li>itop-ext-added2</li></ul>',
				'sExpectedRemovedList' => '<ul><li>itop-ext-removed1</li><li>itop-ext-removed2</li></ul>',
			],

		];
	}

	/**
	 * @dataProvider ProviderGetAddedAndRemovedExtensions
	 */
	public function testGetAddedAndRemovedExtensions($aExtensions, $aSelectedExtensions, $sExpectedAddedList, $sExpectedRemovedList)
	{
		$this->oStep->setExtensionMap(iTopExtensionsMapFake::createFromArray($aExtensions));
		[$sAddedList, $sRemovedList] = $this->oStep->GetAddedAndRemovedExtensions($aSelectedExtensions);
		$this->assertEquals($sExpectedAddedList, $sAddedList);
		$this->assertEquals($sExpectedRemovedList, $sRemovedList);
	}

}
