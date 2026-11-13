<?php

/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 02/01/2020
 * Time: 14:43
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use iTopConfigParser;
use RunTimeEnvironment;
use utils;

class iTopConfigParserTest extends ItopTestCase
{
	private $conf_exists;
	/** @var false|string backup of the original config file */
	private $tmpSavePath;
	private $sConfigPath;

	public function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/core/iTopConfigParser.php');
		$this->RequireOnceItopFile('/setup/runtimeenv.class.inc.php');

		clearstatcache();
		$this->sConfigPath = utils::GetConfigFilePath();
		// saving the current config so we can restore it at the end !
		$this->tmpSavePath = tempnam(sys_get_temp_dir(), 'config-itop');

		$this->conf_exists = is_file($this->sConfigPath);
		if ($this->conf_exists) {
			copy($this->sConfigPath, $this->tmpSavePath);
		}
		clearstatcache();
	}

	public function tearDown(): void
	{
		parent::tearDown();
		if ($this->conf_exists) {
			// restoring config that was in place before the test
			@chmod($this->sConfigPath, 0770);
			$bRenameResult = rename($this->tmpSavePath, $this->sConfigPath);
			echo "Restored config file, result={$bRenameResult}";
			@chmod($this->sConfigPath, 0444);
		}
	}

	/**
	 * @dataProvider ParserProvider
	 * @throws \Exception
	 */
	public function testMergeConf($sInitialConfig, $aExpectedVarsMap)
	{
		$oITopConfigParser = new iTopConfigParser($sInitialConfig);

		$this->assertEquals($aExpectedVarsMap, $oITopConfigParser->GetVarsMap());

	}

	public function ParserProvider()
	{

		return [
			"test MySettings" => [
				'sInitialConfig' => '<?php
					$a=1;
					$MySettings = array(
						"b" => $a
					);',
				'aExpectedVarsMap' => [
					'MySettings' => ['b' => '$a'],
					'MyModuleSettings' => [],
					'MyModules' => [],
				],
			],

			"test MyModuleSettings" => [
				'sInitialConfig' => '<?php
					$a=1;
					$MyModuleSettings = array(
						"b" => $a
					);',
				'aExpectedVarsMap' => [
					'MySettings' => [],
					'MyModuleSettings' => ['b' => '$a'],
					'MyModules' => [],
				],
			],

			"test MyModules" => [
				'sInitialConfig' => '<?php
					$a=1;
					$MyModules = array(
						"b" => $a
					);',
				'aExpectedVarsMap' => [
					'MySettings' => [],
					'MyModuleSettings' => [],
					'MyModules' => ['b' => '$a'],
				],
			],

			"test MyModules + MyModuleSettings " => [
				'sInitialConfig' => '<?php
					$MyModules = array(
						"b" => $a
					);
					$MyModuleSettings = array(
						"e" => $d
					);',
				'aExpectedVarsMap' => [
					'MySettings' => [],
					'MyModuleSettings' => ['e' => '$d'],
					'MyModules' => ['b' => '$a'],
				],
			],

			"test preserve gloabl + concatenation" => [
				'sInitialConfig' => '<?php
					$a=1;
					$MyModules = array(
						"b" =>  $_SERVER["REQUEST_URI"] . "/toto"
					);',
				'aExpectedVarsMap' => [
					'MySettings' => [],
					'MyModuleSettings' => [],
					'MyModules' => ['b' => '$_SERVER["REQUEST_URI"] . "/toto"'],
				],
			],
			"test MyModules array of arrays" => [
				'sInitialConfig' => '<?php 
					$a=1;
					$MyModules = array(
					  "date_and_time_format" => array (
						  "default" => 
						  array (
						   "date" => "Y-m-d",
						    "time" => "H:i:s",
						    "date_time" => "$date $time",
						  ),
						),
					);',
				'aExpectedVarsMap' => [
					'MySettings' => [],
					'MyModuleSettings' => [],
					'MyModules' => [
						'date_and_time_format' => 'array("default" => array("date" => "Y-m-d", "time" => "H:i:s", "date_time" => "{$date} {$time}"))',
					],
				],
			],
		];
	}

	/**
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testConfigWriteToFile()
	{
		$tmpConfigFileBeforePath = tempnam(sys_get_temp_dir(), 'config-itop');
		$tmpConfigFileAfterPath = tempnam(sys_get_temp_dir(), 'config-itop');

		//create new config file
		$sConfigFile = utils::GetConfig()->GetLoadedFile();
		utils::GetConfig()->WriteToFile($tmpConfigFileBeforePath);

		//add few dynamic configurations in MySettings section
		$tmpConfigContentBefore = file_get_contents($tmpConfigFileBeforePath);
		$expected_line = <<< CONF
	'app_root_url' => 'http://\$_SERVER[\\'SERVER_NAME\\']/iTop/',
CONF;
		//add few dynamic configurations in MyModuleSettings section
		$tmpConfigNewContentBefore = preg_replace('/.*\'app_root_url.*,/', $expected_line, $tmpConfigContentBefore);
		$expected_line = <<< CONF
	\$MyModuleSettings = array(
	'shadok_module' => array ('gabu' => '\$_SERVER[\\'ZOMEU\\']'),
CONF;
		$tmpConfigNewContentBefore = preg_replace('/\$MyModuleSettings = array\(/', $expected_line, $tmpConfigNewContentBefore);

		//add few dynamic configurations in MyModules section
		$expected_line = <<< CONF
	'addons' => array(
		'user rights' => 'addons/userrights/userrightsprofile.class.inc.php',
		'user rights2' => '\$_SERVER[\\'TEST\\']'
	),
CONF;
		$tmpConfigNewContentBefore = preg_replace('/.*\'addons.*/', $expected_line, $tmpConfigNewContentBefore);

		unlink($tmpConfigFileBeforePath);
		fwrite(fopen($tmpConfigFileBeforePath, 'w'), $tmpConfigNewContentBefore);

		//write same content again
		$config = new Config($tmpConfigFileBeforePath, true);
		$config->WriteToFile($tmpConfigFileAfterPath);

		//compare
		$tmpConfigContentBefore = file_get_contents($tmpConfigFileAfterPath);
		$tmpConfigContentAfter = file_get_contents($tmpConfigFileAfterPath);
		unlink($tmpConfigFileAfterPath);
		unlink($tmpConfigFileBeforePath);
		$this->assertEquals($tmpConfigContentBefore, $tmpConfigContentAfter);
	}

	/**
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testConfigWriteToFile_FromScratchInstallation()
	{
		$oConfig = new Config();
		clearstatcache();
		$oTestEnv = new RunTimeEnvironment('test-phpunit');
		$oTestEnv->WriteConfigFileSafe($oConfig);
		$this->assertTrue(true, "Config file was written");
	}

	public function testSysAdminConfigComputation()
	{
		$sFixturesDir = __DIR__.'/Fixtures/SysAdminConfig';
		$sTestConfDir = APPCONF.'php-unit-tests';
		$sItopConfFile = $sTestConfDir.'/config-itop.sysadmin-test.php';
		$sTestSysAdminConfFile = $sTestConfDir.'/conf.sysadmin-test.yml';
		$sSysAdminConfFile = APPCONF.'production/conf.yml';

		// conf/ is not versioned: build the local configuration files from the fixtures
		if (!is_dir($sTestConfDir)) {
			mkdir($sTestConfDir, 0770, true);
		}
		copy($sFixturesDir.'/config-itop.php', $sItopConfFile);
		copy($sFixturesDir.'/conf.yml', $sTestSysAdminConfFile);

		// Config::Load() reads the system administrator configuration from a fixed location: install the fake one there
		$sSysAdminConfBackup = null;
		if (is_file($sSysAdminConfFile)) {
			$sSysAdminConfBackup = tempnam(sys_get_temp_dir(), 'conf-yml');
			copy($sSysAdminConfFile, $sSysAdminConfBackup);
		} elseif (!is_dir(dirname($sSysAdminConfFile))) {
			mkdir(dirname($sSysAdminConfFile), 0770, true);
		}
		copy($sTestSysAdminConfFile, $sSysAdminConfFile);

		try {
			$oConfig = new Config($sItopConfFile);
		} finally {
			if (is_null($sSysAdminConfBackup)) {
				unlink($sSysAdminConfFile);
			} else {
				rename($sSysAdminConfBackup, $sSysAdminConfFile);
			}
			unlink($sItopConfFile);
			unlink($sTestSysAdminConfFile);
		}

		// Settings
		$this->assertSame('itop-admin-db-host', $oConfig->Get('db_host'), 'iTop admin only setting must be kept');
		$this->assertSame('http://itop-admin.example.org/', $oConfig->Get('app_root_url'), 'iTop admin only setting must be kept');
		$this->assertSame('sys-admin-db-password', $oConfig->Get('db_pwd'), 'System admin setting must override iTop admin setting');
		$this->assertSame('sys-admin-smtp-password', $oConfig->Get('email_transport_smtp.password'), 'System admin only setting must be added');
		$this->assertSame('sys-admin-encryption-key', $oConfig->GetEncryptionKey(), 'Settings stored in dedicated members must use the system admin value');
		$this->assertEquals(
			['' => 'Error', 'DeadLock' => 'Error', 'Security' => 'Warning'],
			$oConfig->Get('log_level_min'),
			'Array settings must be merged recursively, with system admin values taking precedence'
		);

		// Module settings
		$this->assertSame('itop-admin-ldap-host', $oConfig->GetModuleSetting('authent-ldap', 'host'), 'iTop admin only module setting must be kept');
		$this->assertSame('sys-admin-ldap-password', $oConfig->GetModuleSetting('authent-ldap', 'default_pwd'), 'System admin module setting must override iTop admin module setting');
		$this->assertEquals(
			['keep' => 'itop-admin-value', 'override' => 'sys-admin-value', 'added' => 'sys-admin-value'],
			$oConfig->GetModuleSetting('test-module', 'options'),
			'Module settings must be merged recursively, with system admin values taking precedence'
		);
		$this->assertSame('sys-admin-api-key', $oConfig->GetModuleSetting('sys-admin-only-module', 'api_key'), 'System admin only module must be added');
	}
}
