<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DBBackup;
use utils;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBBackupTest extends ItopTestCase
{
	/**
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \ConfigException
	 */
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('setup/backup.class.inc.php');

		// We need a connection to the DB, so let's open it !
		// We are using the default config file... as the server might not be configured for all the combination we are testing
		// For example dev env and ci env won't accept TLS connection
		$oConfigOnDisk = utils::GetConfig();
		CMDBSource::InitFromConfig($oConfigOnDisk);
	}

	/**
	 * No TLS connection = no additional CLI args !
	 *
	 * @throws \CoreException
	 * @throws \ConfigException
	 * @throws \MySQLException
	 */
	public function testGetMysqlCliTlsOptionsNoTls()
	{
		$oConfigToTest = utils::GetConfig();

		$oConfigToTest->Set('db_tls.enabled', false);
		$sCliArgsNoTls = DBBackup::GetMysqlCliTlsOptions($oConfigToTest);

		$this->assertEmpty($sCliArgsNoTls);
	}

	/**
	 * TLS connection configured = we need one CLI arg
	 *
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testGetMysqlCliTlsOptionsWithTlsNoCa()
	{
		$oConfigToTest = utils::GetConfig();
		$oConfigToTest->Set('db_tls.enabled', true);
		$sCliArgsMinCfg = DBBackup::GetMysqlCliTlsOptions($oConfigToTest);

		// depending on the MySQL version, we would have `--ssl` or `--ssl-mode=VERIFY_CA`
		$this->assertStringStartsWith(' --ssl', $sCliArgsMinCfg);
	}

	/**
	 * TLS connection configured + CA option = we need multiple CLI args
	 *
	 * @return void
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testGetMysqlCliTlsOptionsWithTlsAndCa()
	{
		$oConfigToTest = utils::GetConfig();
		$sTestCa = 'my_test_ca';

		$oConfigToTest->Set('db_tls.enabled', true);
		$oConfigToTest->Set('db_tls.ca', $sTestCa);
		$sCliArgsCapathCfg = DBBackup::GetMysqlCliTlsOptions($oConfigToTest);

		$this->assertStringStartsWith(' --ssl', $sCliArgsCapathCfg);
		$this->assertStringEndsWith('--ssl-ca='.DBBackup::EscapeShellArg($sTestCa), $sCliArgsCapathCfg);
	}

	/**
	 * @dataProvider GetMysqlCliPortAndTransportOptionsProvider
	 *
	 * @since 2.7.10 3.0.4 3.1.2 3.2.0 test for N°6123 and N°6889
	 */
	public function testGetMysqlCliPortAndTransportOptions(string $sHost, ?int $iPort, string $sExpectedCliOptions)
	{
		$sActualCliOptions = $this->InvokeNonPublicStaticMethod(DBBackup::class, 'GetMysqlCliPortAndTransportOptions', [$sHost, $iPort]);

		$this->assertEquals($sExpectedCliOptions, $sActualCliOptions);
	}

	public function GetMysqlCliPortAndTransportOptionsProvider()
	{
		$sPortToTest = 333306;
		$sEscapedPortValue = \DBBackup::EscapeShellArg($sPortToTest);

		return [
			'Localhost no port' => ['localhost', null, ''],
			'Localhost with port' => ['localhost', $sPortToTest, ' --port=' . $sEscapedPortValue . ' --protocol=tcp'],
			'127.0.0.1 no port' => ['127.0.0.1', null, ''],
			'127.0.0.1 with port' => ['127.0.0.1', $sPortToTest, ' --port=' . $sEscapedPortValue],
			'IP no port' => ['192.168.1.15', null, ''],
			'IP with port' => ['192.168.1.15', $sPortToTest, ' --port=' . $sEscapedPortValue],
			'DNS no port' => ['dbserver.mycompany.com', null, ''],
			'DNS with port' => ['dbserver.mycompany.com', $sPortToTest, ' --port=' . $sEscapedPortValue],
			'Windows name no port' => ['dbserver', null, ''],
			'Windows name with port' => ['dbserver', $sPortToTest, ' --port=' . $sEscapedPortValue],
		];
	}
}
