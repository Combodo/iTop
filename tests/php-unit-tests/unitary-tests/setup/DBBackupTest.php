<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DateTime;
use DBBackup;
use utils;

class DBBackupTest extends ItopTestCase
{
	protected const DUMMY_DB_HOST = 'localhost';
	protected const DUMMY_DB_NAME = 'itopdb';
	protected const DUMMY_DB_SUBNAME = 'myitop';

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
		$oConfigOnDisk = utils::GetConfig(true);
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

		// depending on the MySQL vendor, we would have `--ssl` or `--ssl-mode=REQUIRED`
		if (CMDBSource::IsSslModeDBVersion())
		{
			$this->assertStringStartsWith(' --ssl-mode=REQUIRED', $sCliArgsMinCfg);
		}
		else
		{
			$this->assertStringStartsWith(' --ssl', $sCliArgsMinCfg);
			$this->assertStringNotContainsString('--ssl-mode', $sCliArgsMinCfg);
		}
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

		// depending on the MySQL vendor, we would have `--ssl` or `--ssl-mode=VERIFY_CA`
		if (CMDBSource::IsSslModeDBVersion())
		{
			$this->assertStringStartsWith(' --ssl-mode=VERIFY_CA', $sCliArgsCapathCfg);
		}
		else
		{
			$this->assertStringStartsWith(' --ssl', $sCliArgsCapathCfg);
			$this->assertStringNotContainsString('--ssl-mode', $sCliArgsCapathCfg);

		}
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

	/**
	 * @dataProvider MakeNameProvider
	 * @covers \DBBackup::MakeName
	 *
	 * @param string $sInputFormat
	 * @param \DateTime $oBackupDateTime
	 * @param string $sExpectedFilename
	 *
	 * @return void
	 */
	public function testMakeName(string $sInputFormat, DateTime $oBackupDateTime, string $sExpectedFilename): void
	{
		$oConfig = utils::GetConfig();

		// See https://github.com/Combodo/iTop/commit/f7ee21f1d7d1c23910506e9e31b57f33311bd5e0#diff-d693fb790e3463d1aa960c2b8b293532b1bbd12c3b8f885d568d315c404f926aR131
		$oConfig->Set('db_host', static::DUMMY_DB_HOST);
		$oConfig->Set('db_name', static::DUMMY_DB_NAME);
		$oConfig->Set('db_subname', static::DUMMY_DB_SUBNAME);

		$oBackup = new DBBackup($oConfig);
		$sTestedFilename = $oBackup->MakeName($sInputFormat, $oBackupDateTime);

		$this->assertEquals($sExpectedFilename, $sTestedFilename, "Backup filename for '$sInputFormat' format doesn't match. Got '$sTestedFilename', expected '$sExpectedFilename'.");
	}

	public function MakeNameProvider(): array
	{
		$oBackupDateTime = DateTime::createFromFormat('Y-m-d H:i:s', '1985-07-30 15:30:59');

		return [
			'Default format' => [
				'__DB__-%Y-%m-%d',
				$oBackupDateTime,
				static::DUMMY_DB_NAME.'-1985-07-30',
			],
			'With all standard DB placeholders' => [
				'__HOST__-__DB__-__SUBNAME__-%Y-%m-%d',
				$oBackupDateTime,
				static::DUMMY_DB_HOST.'-'.static::DUMMY_DB_NAME.'-'.static::DUMMY_DB_SUBNAME.'-1985-07-30',
			],
			'With time which is a placeholder that needs to be translated (minutes defined by "%M" when its actually "i" in the transformation matrix)' => [
				'__DB__-%Y-%m-%d_%H:%M:%S',
				$oBackupDateTime,
				static::DUMMY_DB_NAME.'-1985-07-30_15:30:59',
			],
			'With user defined string that would be translated if using \DateTime::format() directly' => [
				'__DB__-%Y-%m-%d-production',
				$oBackupDateTime,
				static::DUMMY_DB_NAME.'-1985-07-30-production',
			],
		];
	}
}
