<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;

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
		$oConfig->Set('db_tls.key', 'key');
		$oConfig->Set('db_tls.cert', 'cert');

		$sCliArgsNoTls = \DBBackup::GetMysqlCliTlsOptions($oConfig);
		$this->assertEmpty($sCliArgsNoTls);

		$oConfig->Set('db_tls.ca', 'ca');
		$sCliArgsMinCfg = \DBBackup::GetMysqlCliTlsOptions($oConfig);
		$this->assertEquals(' --ssl --ssl-key="key" --ssl-cert="cert" --ssl-ca="ca"', $sCliArgsMinCfg);

		$oConfig->Set('db_tls.capath', 'capath');
		$sCliArgsCapathCfg = \DBBackup::GetMysqlCliTlsOptions($oConfig);
		$this->assertEquals(' --ssl --ssl-key="key" --ssl-cert="cert" --ssl-ca="ca" --ssl-capath="capath"',
			$sCliArgsCapathCfg);
	}
}
