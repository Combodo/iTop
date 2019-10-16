<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use DBBackup;

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

	public function testGetMysqlCliTlsOptions()
	{
		$oConfig = new Config();
		$oConfig->Set('db_tls.enabled', false);

		$sCliArgsNoTls = DBBackup::GetMysqlCliTlsOptions($oConfig);
		$this->assertEmpty($sCliArgsNoTls);

		$oConfig->Set('db_tls.enabled', true);
		$sCliArgsMinCfg = DBBackup::GetMysqlCliTlsOptions($oConfig);
		$this->assertEquals(' --ssl', $sCliArgsMinCfg);

		$sTestCa = 'my_test_ca';
		$oConfig->Set('db_tls.ca', $sTestCa);
		$sCliArgsCapathCfg = DBBackup::GetMysqlCliTlsOptions($oConfig);
		$this->assertEquals(' --ssl --ssl-ca="'.$sTestCa.'"', $sCliArgsCapathCfg);
	}
}
