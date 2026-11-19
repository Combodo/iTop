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
	 * @dataProvider ProviderModuleSettingsRoundTrip
	 *
	 * @param array<string, array<string, mixed>> $aModuleSettings
	 *
	 * @since 3.2.4 3.3.1 3.4.0 N°9728
	 */
	public function testWriteToFilePreservesModuleSettings(array $aModuleSettings): void
	{
		$sTmpFile = $this->GetTemporaryFilePath('config-module-settings-');
		$oConfig = new Config();
		foreach ($aModuleSettings as $sModule => $aProperties) {
			foreach ($aProperties as $sProperty => $value) {
				$oConfig->SetModuleSetting($sModule, $sProperty, $value);
			}
		}

		$this->assertTrue($oConfig->WriteToFile($sTmpFile));
		$oReloadedConfig = new Config($sTmpFile);
		$this->assertSame($aModuleSettings, $oReloadedConfig->ToArray()['module_settings']);

		$oReloadedConfig->Set('app_root_url', 'https://itop.example.invalid/');
		$this->assertTrue($oReloadedConfig->WriteToFile());
		$oSavedConfig = new Config($sTmpFile);
		$this->assertSame($aModuleSettings, $oSavedConfig->ToArray()['module_settings']);
		$this->assertSame('https://itop.example.invalid/', $oSavedConfig->Get('app_root_url'));
	}

	/**
	 * @return array<string, array{array<string, array<string, mixed>>}>
	 *
	 * @since 3.2.4 3.3.1 3.4.0 N°9728
	 */
	public function ProviderModuleSettingsRoundTrip(): array
	{
		$aModuleSettings = [
			'authent-cas' => [
				'cas_debug' => false,
				'cas_host' => 'sso.example.invalid',
				'cas_port' => 443,
				'cas_context' => '/cas',
				'cas_version' => '3.0',
				'service_base_url' => 'https://itop.example.invalid/',
			],
			'authent-ldap' => [
				'host' => 'ldap.example.invalid',
				'port' => 389,
				'default_user' => '',
				'default_pwd' => '',
				'base_dn' => 'ou=people,dc=example,dc=invalid',
				'user_query' => '(uid=%1$s)',
				'options' => [17 => 3, 8 => 0],
				'start_tls' => true,
				'debug' => false,
				'servers' => [],
			],
			'itop-attachments' => [
				'allowed_classes' => ['Ticket', 'Contact', 'FunctionalCI'],
				'position' => 'relations',
				'preview_max_width' => 600,
				'icon_preview_max_size' => 0,
			],
			'itop-backup' => [
				'mysql_bindir' => '/usr/bin/',
				'week_days' => '1,2,3,4,5',
				'time' => '02:30',
				'retention_count' => 7,
				'enabled' => true,
				'itop_backup_incident' => null,
			],
			'itop-log-mgmt' => [
				'browser_white_list' => ['/Firefox\\/[0-9.]+/', '/Chrome\\/[0-9.]+/'],
				'browser_only' => false,
			],
		];

		$aCases = [];
		foreach ($aModuleSettings as $sModule => $aProperties) {
			$aCases[$sModule] = [[$sModule => $aProperties]];
		}
		$aCases['all modules'] = [$aModuleSettings];
		$aCases['apostrophe in module name'] = [["customer's-module" => ['enabled' => true]]];
		$aCases['apostrophe in property name'] = [['itop-backup' => ["customer's_path" => '/var/backups/']]];
		$aCases['trailing backslash in module name'] = [['custom-module\\' => ['enabled' => false]]];
		$aCases['backslash before apostrophe in property name'] = [['itop-backup' => ["customer\\'s_path" => 'C:\\Backups\\']]];
		$aCases['spaces and double quotes in keys'] = [['custom module' => ['display "label"' => 'Backup status']]];

		return $aCases;
	}
}
