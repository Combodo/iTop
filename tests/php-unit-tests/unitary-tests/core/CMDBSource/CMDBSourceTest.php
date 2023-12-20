<?php


namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use utils;

/**
 * @since 2.7.0
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class CMDBSourceTest extends ItopTestCase
{
	protected function setUp(): void
	{

		parent::setUp();
		$this->RequireOnceItopFile('/core/cmdbsource.class.inc.php');
	}

	/**
	 * @covers       CMDBSource::IsSameFieldTypes
	 * @dataProvider compareFieldTypesProvider
	 *
	 * @param boolean $bResult
	 * @param string $sItopFieldType
	 * @param string $sDbFieldType
	 */
	public function testCompareFieldTypes($bResult, $sItopFieldType, $sDbFieldType)
	{
		$this->assertEquals($bResult, CMDBSource::IsSameFieldTypes($sItopFieldType, $sDbFieldType), "$sItopFieldType\n VS\n $sDbFieldType");
	}

	public function compareFieldTypesProvider()
	{
		return array(
			'same datetime types' => array(true, 'DATETIME', 'DATETIME'),
			'different types' => array(false, 'VARCHAR(255)', 'INT(11)'),
			'different types, same type options' => array(false, 'VARCHAR(11)', 'INT(11)'),
			'same int declaration, same case' => array(true, 'INT(11)', 'INT(11)'),
			'same int declaration, different case on data type' => array(true, 'INT(11)', 'int(11)'),
			'same enum declaration, same case' => array(
				true,
				"ENUM('error','idle','planned','running')",
				"ENUM('error','idle','planned','running')",
			),
			'same enum declaration, different case on data type' => array(
				true,
				"ENUM('error','idle','planned','running')",
				"enum('error','idle','planned','running')",
			),
			'same enum declaration, different case on type options' => array(
				false,
				"ENUM('ERROR','IDLE','planned','running')",
				"ENUM('error','idle','planned','running')",
			),
			'same enum declaration, different case on both data type and type options' => array(
				false,
				"ENUM('ERROR','IDLE','planned','running')",
				"enum('error','idle','planned','running')",
			),
			'MariaDB 10.2 nullable datetime' => array(
				true,
				'DATETIME',
				"datetime DEFAULT 'NULL'",
			),
			'MariaDB 10.2 nullable text' => array(
				true,
				'TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
				"text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'NULL'",
			),
			'MariaDB 10.2 nullable unsigned int' => array(
				true,
				'INT(11) UNSIGNED',
				"int(11) unsigned DEFAULT 'NULL'",
			),
			'MariaDB 10.2 varchar with default value' => array(
				true,
				'VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 0',
				"varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0'",
			),
			'varchar with default value not at the end' => array(
				true,
				"VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 0 COMMENT 'my comment'",
				"varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT 'my comment'",
			),
			'MariaDB 10.2 Enum with string default value' => array(
				true,
				"ENUM('error','idle','planned','running') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'planned'",
				"enum('error','idle','planned','running') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'planned'",
			),
			'MariaDB 10.2 Enum with numeric default value' => array(
				true,
				"ENUM('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1'",
				"enum('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1'",
			),
			'ENUM with values containing parenthesis' => array(
				true, // see N°3065 : if having distinct values having parenthesis in enum values will cause comparison to be inexact
				"ENUM('CSP A','CSP M','NA','OEM(ROC)','OPEN(VL)','RETAIL (Boite)') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
				"enum('CSP A','CSP M','NA','OEM(ROC)','OPEN(VL)','RETAIL (Boite)') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
			),
			// N°3065 before the fix this returned true :(
			'ENUM with different values, containing parenthesis' => array(
				false,
				"ENUM('value 1 (with parenthesis)','value 2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
				"enum('value 1 (with parenthesis)','value 3') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
			),
		);
	}

	/**
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @since 3.0.0 N°4215
	 */
	public function testIsOpenedDbConnectionUsingTls() {
		$oConfig = utils::GetConfig();
		CMDBSource::InitFromConfig($oConfig);
		$oMysqli = CMDBSource::GetMysqli();

		// resets \CMDBSource::$oMySQLiForQuery to simulate call to \CMDBSource::Init with a TLS connexion
		$this->InvokeNonPublicStaticMethod(CMDBSource::class, 'SetMySQLiForQuery',[null]);

		// before N°4215 fix, this was crashing : "Call to a member function query() on null"
		$bIsTlsCnx = $this->InvokeNonPublicStaticMethod(CMDBSource::class, 'IsOpenedDbConnectionUsingTls',[$oMysqli]);
		$this->assertFalse($bIsTlsCnx);
	}

	/**
	 * @dataProvider InitServerAndPortProvider
	 * @since 2.7.10 3.0.4 3.1.2 3.2.0 N°6889 method creation to keep track of the behavior change (port will return null)
	 */
	public function testInitServerAndPort(string $sDbHost, string $sExpectedServer, ?int $iExpectedPort)
	{
		$sActualServer = null;
		$iActualPort = null;
		CMDBSource::InitServerAndPort($sDbHost, $sActualServer, $iActualPort);

		$this->assertNotNull($sActualServer);
		$this->assertEquals($sExpectedServer, $sActualServer);
		$this->assertEquals($iExpectedPort, $iActualPort);
	}

	public function InitServerAndPortProvider()
	{
		return [
			'localhost no port' => ['localhost', 'localhost', null],
			'localhost with port' => ['localhost:333306', 'localhost', 333306],
			'persistent localhost no port' => ['p:localhost', 'p:localhost', null],
			'persistent localhost with port' => ['p:localhost:333306', 'p:localhost', 333306],
			'ip no port' => ['192.168.1.10', '192.168.1.10', null],
			'ip with port' => ['192.168.1.10:333306', '192.168.1.10', 333306],
			'persistent ip no port' => ['p:192.168.1.10', 'p:192.168.1.10', null],
			'persistent ip with port' => ['p:192.168.1.10:333306', 'p:192.168.1.10', 333306],
			'domain no port' => ['dbserver.mycompany.com', 'dbserver.mycompany.com', null],
			'domain with port' => ['dbserver.mycompany.com:333306', 'dbserver.mycompany.com', 333306],
			'persistent domain no port' => ['p:dbserver.mycompany.com', 'p:dbserver.mycompany.com', null],
			'persistent domain with port' => ['p:dbserver.mycompany.com:333306', 'p:dbserver.mycompany.com', 333306],
		];
	}
}
