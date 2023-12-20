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
	 * @since 2.7.10 3.0.4 3.1.2 3.2.0 test for N°6123 and N°6889
	 */
	public function testGetMysqlCliPortAndTransportOptions(string $sDbHost, ?int $iPort, ?int $iExpectedPortValue, string $sExpectedProtocolCliOption)
	{
		if (is_null($iExpectedPortValue)) {
			$sExpectedPortCliOption = '';
		} else {
			$sEscapedPortValue = \DBBackup::EscapeShellArg($iExpectedPortValue);
			$sExpectedPortCliOption = ' --port=' . $sEscapedPortValue;
		}

		$sActualCliOptions = $this->InvokeNonPublicStaticMethod(DBBackup::class, 'GetMysqlCliPortAndTransportOptions', [$sDbHost, $iPort]);
		$this->assertEquals($sExpectedPortCliOption . $sExpectedProtocolCliOption, $sActualCliOptions);
	}

	public function GetMysqlCliPortAndTransportOptionsProvider()
	{
		$iTestPort = 333306;
		$iDefaultPort = 3306; // cannot access \CMDBSource::MYSQL_DEFAULT_PORT in dataprovider :(

		return [
			'Localhost no port' => ['localhost', null, null, ''],
			'Localhost with port' => ['localhost', $iTestPort, $iTestPort, ' --protocol=tcp'],

			// we want both port and protocol for 127.0.0.1, because it is an ip address so using tcp/ip stack !
			'127.0.0.1 no port' => ['127.0.0.1', null, $iDefaultPort, ''],
			'127.0.0.1 with port' => ['127.0.0.1', $iTestPort, $iTestPort, ''],

			'IP no port' => ['192.168.1.15', null, $iDefaultPort, ''],
			'IP with port' => ['192.168.1.15', $iTestPort, $iTestPort, ''],

			'DNS no port' => ['dbserver.mycompany.com', null, $iDefaultPort, ''],
			'DNS with port' => ['dbserver.mycompany.com', $iTestPort, $iTestPort, ''],

			'Windows name no port' => ['dbserver', null, $iDefaultPort, ''],
			'Windows name with port' => ['dbserver', $iTestPort, $iTestPort, ''],
		];
	}

}
