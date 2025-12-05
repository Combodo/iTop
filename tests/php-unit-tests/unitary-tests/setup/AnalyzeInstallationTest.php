<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use AnalyzeInstallation;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ModuleInstallationService;

class AnalyzeInstallationTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/AnalyzeInstallation.php');
		$this->RequireOnceItopFile('setup/ModuleInstallationService.php');
		$this->RequireOnceItopFile('setup/modulediscovery.class.inc.php');
		$this->RequireOnceItopFile('setup/runtimeenv.class.inc.php');
	}

	public static function AnalyzeInstallationProvider()
	{
		//$aModules = json_decode(file_get_contents(__DIR__.'/ressources/priv_modules.json'), true);
		$aAnalyzeInstallationOutput = json_decode(file_get_contents(__DIR__.'/ressources/analyze_installation_output.json'), true);
		$aAvailableModules = json_decode(file_get_contents(__DIR__.'/ressources/available_modules.json'), true);
		return [
			'new modules not in DB setup history' => [
				'aAvailableModules' => [
					'mandatory_module/1.0.0' => [
						"mandatory" => true,
						"ga" => "bu",
					],
					'optional_module/6.6.6' => [
						"mandatory" => false,
						"zo" => "meu",
					],
				],
				'aInstalledModules' => [],
				'expected' => [
					'_Root_' => [
						'installed_version' => '',
						'available_version' => 'ITOP_VERSION_FULL',
						'name_code' => 'ITOP_APPLICATION',
					],
					'mandatory_module' => [
						"mandatory" => true,
						'installed_version' => '',
						'available_version' => '1.0.0',
						'install' => [
							'flag' => 2,
							'message' => 'the module is part of the application',
						],
						"ga" => "bu",
					],
					'optional_module' => [
						"mandatory" => false,
						'installed_version' => '',
						'available_version' => '6.6.6',
						"zo" => "meu",
						'install' => [
							'flag' => 1,
							'message' => '',
						],
					],
				],
			],
			'new modules ALREADY in DB setup history' => [
				'aAvailableModules' => [
					'mandatory_module/1.0.0' => [
						"mandatory" => true,
						"ga" => "bu",
					],
					'optional_module/6.6.6' => [
						"mandatory" => false,
						"zo" => "meu",
					],
				],
				'aInstalledModules' => json_decode(file_get_contents(__DIR__.'/ressources/priv_modules_simpleusecase.json'), true),
				'expected' => [
					'_Root_' => [
						'installed_version' => '3.3.0-dev-svn',
						'available_version' => 'ITOP_VERSION_FULL',
						'name_code' => 'ITOP_APPLICATION',
					],
					'mandatory_module' => [
						"mandatory" => true,
						'installed_version' => '3.3.0',
						'available_version' => '1.0.0',
						"ga" => "bu",
						'install' => [
							'flag' => 2,
							'message' => 'the module is part of the application',
						],
						'uninstall' => [
							'flag' => 3,
							'message' => 'the module is part of the application',
						],
					],
					'optional_module' => [
						"mandatory" => false,
						'installed_version' => '3.3.0',
						'available_version' => '6.6.6',
						"zo" => "meu",
						'install' => [
							'flag' => 1,
							'message' => '',
						],
						'uninstall' => [
							'flag' => 1,
							'message' => '',
						],
					],
				],
			],
			'dummyfirst installation' => [
				'aAvailableModules' => [],
				'aInstalledModules' => [],
				'expected' => [
					'_Root_' => [
						'installed_version' => '',
						'available_version' => 'ITOP_VERSION_FULL',
						'name_code' => 'ITOP_APPLICATION',
					],
				],
			],
			'dummy 2nd installation' => [
				'aAvailableModules' => [],
				'aInstalledModules' => json_decode(file_get_contents(__DIR__.'/ressources/priv_modules2.json'), true),
				'expected' => [
					'_Root_' => [
						'installed_version' => '3.3.0-dev-svn',
						'available_version' => 'ITOP_VERSION_FULL',
						'name_code' => 'ITOP_APPLICATION',
					],
				],
			],
			'real_case' => [
				'aAvailableModules' => $aAvailableModules,
				'aInstalledModules' => json_decode(file_get_contents(__DIR__.'/ressources/priv_modules2.json'), true),
				'expected' => $aAnalyzeInstallationOutput,
			],
		];
	}

	/**
	 * @dataProvider AnalyzeInstallationProvider
	 */
	public function testAnalyzeInstallation($aAvailableModules, $aInstalledModules, $expected)
	{
		$sContent = str_replace(['ITOP_VERSION_FULL', 'ITOP_APPLICATION'], [ITOP_VERSION_FULL, ITOP_APPLICATION], json_encode($expected));
		$expected = json_decode($sContent, true);

		$this->SetNonPublicProperty(AnalyzeInstallation::GetInstance(), "aAvailableModules", $aAvailableModules);
		//$aModules = json_decode(file_get_contents(__DIR__.'/ressources/priv_modules2.json'), true);
		$this->SetNonPublicProperty(ModuleInstallationService::GetInstance(), "aSelectInstall", $aInstalledModules);

		$oConfig =  $this->createMock(\Config::class);

		$modulesPath = [
			APPROOT.'extensions',
		];
		$aModules = AnalyzeInstallation::GetInstance()->AnalyzeInstallation($oConfig, $modulesPath, false, null);
		$this->assertEquals($expected, $aModules);
	}
}
