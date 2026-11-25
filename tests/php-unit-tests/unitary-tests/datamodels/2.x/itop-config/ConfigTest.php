<?php

/**
 * Copyright (C) 2010-2024 Combodo SAS
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */

namespace Combodo\iTop\Test\UnitTest\Module\iTopConfig;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use PhpParser\ConstExprEvaluator;
use PhpParser\Node;
use PhpParser\ParserFactory;
use utils;

class ConfigTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/config.class.inc.php');
	}

	/**
	 *
	 * @dataProvider ProviderPreserveVarOnWriteToFile
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 *
	 */
	public function testPreserveVarOnWriteToFile($sConfigFile, $sExpectedContains, $aChanges)
	{
		$sTmpFile = tempnam(sys_get_temp_dir(), "target");

		$oConfig = new Config($sConfigFile);

		foreach ($aChanges as $key => $val) {
			$oConfig->Set($key, $val);
		}

		$oConfig->WriteToFile($sTmpFile);

		$this->assertFileExists($sTmpFile);
		$sFileContent = file_get_contents($sTmpFile);

		$this->assertStringContainsString($sExpectedContains, $sFileContent, "File content doesn't contain : ".$sExpectedContains);
	}

	public function ProviderPreserveVarOnWriteToFile()
	{
		return [
			'preserve var' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-var.php',
				'sExpectedContains' => 	"'app_root_url' => 'http://' . (isset(\$_SERVER['SERVER_NAME']) ? \$_SERVER['SERVER_NAME'] : 'localhost') . '/itop/iTop/'",
				'aChanges' => [],
			],
			'preserve joker' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-joker.php',
				'sExpectedContains' => 	"'app_root_url' => 'http://%server(SERVER_NAME)?:localhost%/itop/iTop/'",
				'aChanges' => [],
			],

			'preserve set same value' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-var.php',
				'sExpectedContains' => 	"'app_root_url' => 'http://' . (isset(\$_SERVER['SERVER_NAME']) ? \$_SERVER['SERVER_NAME'] : 'localhost') . '/itop/iTop/'",
				'aChanges' => ['app_root_url' => 'http://localhost/itop/iTop/'],
			],

			'overwrite var' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-var.php',
				'sExpectedContains' => 	"'app_root_url' => 'foo",
				'aChanges' => ['app_root_url' => 'foo'],
			],
			'overwrite joker' => [
				'sConfigFile' => __DIR__.'/ConfigTest/config-itop-joker.php',
				'sExpectedContains' => 	"'app_root_url' => 'foo",
				'aChanges' => ['app_root_url' => 'foo'],
			],
		];
	}

	/**
	 * @since 3.2.3
	 * @dataProvider ProviderArrayKeys
	 */
	public function testWriteToFilePreservesModuleKeys(int|string $key): void
	{
		$oConfig = new Config();
		$oConfig->SetModuleSetting('existing-module', 'existing.property', true);
		$oConfig->SetModuleSetting($key, 'sample.property', 'value');

		$this->AssertWriteToFilePreservesSettings($oConfig, [
			'existing-module' => ['existing.property' => true],
			$key => ['sample.property' => 'value'],
		], $oConfig->GetAddons());
	}

	/**
	 * @since 3.2.3
	 * @dataProvider ProviderArrayKeys
	 */
	public function testWriteToFilePreservesPropertyKeys(int|string $key): void
	{
		$oConfig = new Config();
		$oConfig->SetModuleSetting('sample-module', 'existing.property', true);
		$oConfig->SetModuleSetting('sample-module', $key, [$key => 'value']);

		$this->AssertWriteToFilePreservesSettings($oConfig, [
			'sample-module' => [
				'existing.property' => true,
				$key => [$key => 'value'],
			],
		], $oConfig->GetAddons());
	}

	/**
	 * @since 3.2.3
	 * @dataProvider ProviderArrayKeys
	 */
	public function testWriteToFilePreservesAddons(int|string $key): void
	{
		$oConfig = new Config();
		$aAddons = $oConfig->GetAddons();
		$aAddons[$key] = 'addons/'.$key.'/module.php';
		$oConfig->SetAddons($aAddons);

		$this->AssertWriteToFilePreservesSettings($oConfig, [], $aAddons);
	}

	/**
	 * @since 3.2.3
	 * @dataProvider ProviderArrayKeys
	 */
	public function testWriteToFilePreservesIncrementalAddons(int|string $key): void
	{
		$aAddons = (new Config())->GetAddons();
		$aAddons[$key] = 'addons/'.$key.'/module.php';
		$sSourceFile = tempnam(sys_get_temp_dir(), 'config-source');
		$this->aFileToClean[] = $sSourceFile;
		$this->assertNotFalse(file_put_contents($sSourceFile, "<?php\n"
			."\$MySettings = [];\n"
			."\$MyModuleSettings = [];\n"
			."\$MyModules = [];\n"
			."\$MyModules['addons'] = ".var_export($aAddons, true).";\n"));

		$this->AssertWriteToFilePreservesSettings(new Config($sSourceFile), [], $aAddons);
	}

	/**
	 * @since 3.2.3
	 * @return array<string, array{int|string}>
	 */
	public static function ProviderArrayKeys(): array
	{
		return [
			'module and property separators' => ['sample-module.setting_1'],
			'apostrophe' => ["O'Reilly"],
			'double quote' => ['a"b'],
			'backslash' => ['a\\b'],
			'repeated backslashes' => ['a\\\\b'],
			'trailing backslash' => ['trailing\\'],
			'backslash before apostrophe' => ["a\\'b"],
			'unicode' => ["caf\u{00E9}-\u{65E5}\u{672C}"],
			'line breaks' => ["first\nsecond\rthird"],
			'dollar sign' => ['$value'],
			'empty string' => [''],
			'zero' => [0],
			'integer' => [7],
			'negative integer' => [-7],
			'numeric string' => ['7'],
			'leading zero' => ['07'],
		];
	}

	/**
	 * @since 3.2.3
	 * @param array<int|string, array<int|string, mixed>> $aModuleSettings
	 * @param array<int|string, string> $aAddons
	 */
	private function AssertWriteToFilePreservesSettings(Config $oConfig, array $aModuleSettings, array $aAddons): void
	{
		$oOriginalConfig = utils::GetConfig();
		$oParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
		$oEvaluator = new ConstExprEvaluator();

		try {
			for ($i = 0; $i < 2; $i++) {
				$sTargetFile = tempnam(sys_get_temp_dir(), 'config-target');
				$this->aFileToClean[] = $sTargetFile;
				$this->assertTrue($oConfig->WriteToFile($sTargetFile));

				$aNodes = $oParser->parse(file_get_contents($sTargetFile));
				$this->assertCount(3, $aNodes);
				$aValues = [];
				foreach ($aNodes as $oNode) {
					$this->assertInstanceOf(Node\Stmt\Expression::class, $oNode);
					$this->assertInstanceOf(Node\Expr\Assign::class, $oNode->expr);
					$this->assertInstanceOf(Node\Expr\Variable::class, $oNode->expr->var);
					$aValues[$oNode->expr->var->name] = $oEvaluator->evaluateDirectly($oNode->expr->expr);
				}
				$this->assertSame(['MySettings', 'MyModuleSettings', 'MyModules'], array_keys($aValues));
				$this->assertSame($aModuleSettings, $aValues['MyModuleSettings']);
				$this->assertSame(['addons' => $aAddons], $aValues['MyModules']);

				$oConfig = new Config($sTargetFile);
				$this->assertSame($aAddons, $oConfig->GetAddons());
				foreach ($aModuleSettings as $sModule => $aProperties) {
					foreach ($aProperties as $sProperty => $value) {
						$this->assertSame($value, $oConfig->GetModuleSetting($sModule, $sProperty));
					}
				}
			}
		} finally {
			utils::SetConfig($oOriginalConfig);
		}
	}
}
