<?php


namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

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
	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'/core/cmdbsource.class.inc.php');
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
		$this->assertEquals($bResult, CMDBSource::IsSameFieldTypes($sItopFieldType, $sDbFieldType));
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
				true,
				"ENUM('CSP A','CSP M','NA','OEM(ROC)','OPEN(VL)','RETAIL (Boite)') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
				"enum('CSP A','CSP M','NA','OEM(ROC)','OPEN(VL)','RETAIL (Boite)') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci",
			),
		);
	}
}
