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
	 * @dataProvider ProviderPreserveModuleSettingsOnWriteToFile
	 *
	 * @param array<string|int, array<string|int, mixed>> $aModuleSettings
	 *
	 * @since 3.2.3 N°9728 - Cover module setting keys when writing configuration.
	 */
	public function testPreserveModuleSettingsOnWriteToFile(array $aModuleSettings): void
	{
		$sTmpFile = tempnam(sys_get_temp_dir(), 'config-module-settings-');
		$this->assertNotFalse($sTmpFile);
		$this->aFileToClean[] = $sTmpFile;

		$oConfig = new Config();
		foreach ($aModuleSettings as $sModule => $aProperties) {
			foreach ($aProperties as $sProperty => $value) {
				$oConfig->SetModuleSetting($sModule, $sProperty, $value);
			}
		}

		for ($i = 0; $i < 2; $i++) {
			$this->assertTrue($oConfig->WriteToFile($sTmpFile));
			$oConfig = new Config($sTmpFile);
			$this->assertSame($aModuleSettings, $oConfig->ToArray()['module_settings']);
		}
	}

	/**
	 * @return array<string, array{array<string|int, array<string|int, mixed>>}>
	 *
	 * @since 3.2.3 N°9728 - Cover module setting keys when writing configuration.
	 */
	public function ProviderPreserveModuleSettingsOnWriteToFile(): array
	{
		return [
			'ordinary keys and different value types' => [[
				'itop-test' => [
					'string' => "A value with 'quotes' and \\backslashes",
					'bool' => false,
					'integer' => 42,
					'null' => null,
					'array' => ['nested' => ['first', 'second']],
				],
			]],
			'apostrophe in module name' => [["module's name" => ['property' => 'value']]],
			'apostrophe in property name' => [['module' => ["property's name" => 'value']]],
			'backslash in module name' => [['module\\name' => ['property' => 'value']]],
			'backslash in property name' => [['module' => ['property\\name' => 'value']]],
			'trailing backslash in module name' => [['module\\' => ['property' => 'value']]],
			'trailing backslash in property name' => [['module' => ['property\\' => 'value']]],
			'backslash before apostrophe in both keys' => [["module\\'name" => ["property\\'name" => 'value']]],
			'double quotes and dollar signs' => [['module"$name' => ['property"$name' => 'value']]],
			'integer keys' => [[42 => [0 => 'value']]],
			'numeric string keys with leading zeros' => [['042' => ['007' => 'value']]],
			'multiple modules and properties' => [[
				'module' => ['property' => 'first value', "property's name" => 'second value'],
				"module's name" => ['property' => 'third value', 'property\\' => 'fourth value'],
			]],
			'CAS authentication with Windows paths' => [[
				'authent-cas' => [
					'cas_debug' => false,
					'cas_include_path' => 'C:\\Program Files\\CAS\\',
					'cas_host' => 'sso.example.invalid',
					'cas_port' => 443,
					'cas_context' => '/cas',
					'cas_version' => '3.0',
					'service_base_url' => 'https://itop.example.invalid/',
				],
			]],
			'LDAP authentication with integer option keys and empty servers' => [[
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
			]],
			'attachments with class lists and zero size limit' => [[
				'itop-attachments' => [
					'allowed_classes' => ['Ticket', 'Contact', 'FunctionalCI'],
					'position' => 'relations',
					'preview_max_width' => 600,
					'icon_preview_max_size' => 0,
				],
			]],
			'backup schedule and browser log patterns' => [[
				'itop-backup' => [
					'mysql_bindir' => '/usr/bin/',
					'week_days' => '1,2,3,4,5',
					'time' => '02:30',
					'retention_count' => 7,
					'enabled' => true,
					'itop_backup_incident' => "Backup's status: \"ready\"\nNext run at 02:30",
				],
				'itop-log-mgmt' => [
					'browser_white_list' => ['/Firefox\\/[0-9.]+/', '/Chrome\\/[0-9.]+/', '/Edg\\/[0-9.]+/'],
					'browser_only' => false,
				],
			]],
			'unauthenticated forms with deeply nested settings' => [[
				'combodo-unauthenticated-forms' => [
					'custom_theme_url' => '',
					'forms' => [
						'close_ticket' => [
							'enabled' => true,
							'description' => "Close the user's ticket",
							'prompt_form' => false,
							'fields' => [
								'user_satisfaction' => 1,
								'user_comment' => "I'm satisfied.\nThank you!",
							],
							'stimulus' => 'ev_close',
							'labels' => [
								'mail_button' => null,
								'form_title' => 'Close "$this->object()"',
								'form_description' => '<p>Confirm the ticket\'s closure.</p>',
								'feedback_success' => 'Ticket closed.',
								'feedback_expired' => '',
								'feedback_already_used' => 'This link has already been used.',
								'feedback_error' => 'Unable to close the ticket.',
							],
						],
						'reopen_ticket' => [
							'enabled' => false,
							'description' => 'Reopen a ticket',
							'prompt_form' => true,
							'fields' => ['public_log' => null],
							'stimulus' => 'ev_reopen',
							'labels' => [],
						],
					],
				],
			]],
			'integration settings with nullable and empty overrides' => [[
				'combodo-vcs-integration' => [
					'webhook_user_id' => null,
					'synchro_auto_interval' => 300,
					'webhook_host_overload' => '',
					'webhook_scheme_overload' => 'https',
					'asynchronous_handler_interval' => 0,
				],
				'dokuwiki-brick' => [
					'dokuwiki_url' => 'https://wiki.example.invalid/doku.php?id=$this->name()',
					'dokuwiki_user' => '',
					'dokuwiki_password' => '',
				],
				'customer-survey' => [
					'anonymous_survey' => true,
					'quiz_scale' => '0',
				],
			]],
			'nested form keys with apostrophes and backslashes' => [[
				'combodo-unauthenticated-forms' => [
					'forms' => [
						"customer's form\\" => [
							'fields' => ["customer's comment" => 'value', 'path\\' => 'C:\\Temp\\'],
							'labels' => ["button's label" => "It's done!"],
						],
					],
				],
			]],
		];
	}
}
