<?php

namespace Combodo\iTop\Test\UnitTest\Core;

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
	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'core/config.class.inc.php');
		require_once(APPROOT.'setup/backup.class.inc.php');
		require_once(APPROOT.'core/cmdbsource.class.inc.php'); // DBBackup dependency
	}

	/**
	 * @throws \CoreException
	 * @throws \ConfigException
	 * @throws \MySQLException
	 */
	public function testGetMysqlCliTlsOptions()
	{
		$oConfigToTest = utils::GetConfig();

		// No TLS connection = no additional CLI args !
		$oConfigToTest->Set('db_tls.enabled', false);
		$sCliArgsNoTls = DBBackup::GetMysqlCliTlsOptions($oConfigToTest);
		$this->assertEmpty($sCliArgsNoTls);

		// We need a connection to the DB, so let's open it !
		$oConfigOnDisk = utils::GetConfig();
		CMDBSource::InitFromConfig($oConfigOnDisk);

		// TLS connection configured = we need one CLI arg
		$oConfigToTest->Set('db_tls.enabled', true);
		$sCliArgsMinCfg = DBBackup::GetMysqlCliTlsOptions($oConfigToTest);
		// depending on the MySQL version, we would have `--ssl` or `--ssl-mode=VERIFY_CA`
		$this->assertStringStartsWith(' --ssl', $sCliArgsMinCfg);

		// TLS connection configured + CA option = we need multiple CLI args
		$sTestCa = 'my_test_ca';
		$oConfigToTest->Set('db_tls.ca', $sTestCa);
		$sCliArgsCapathCfg = DBBackup::GetMysqlCliTlsOptions($oConfigToTest);
		$this->assertStringStartsWith(' --ssl', $sCliArgsMinCfg);
		$this->assertStringEndsWith('--ssl-ca="'.$sTestCa.'"', $sCliArgsCapathCfg);
	}
}
