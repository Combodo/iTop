<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use ModuleInstallerAPI;


/**
 * Class ModuleInstallerAPITest
 *
 * @covers ModuleInstallerAPI
 *
 */
class ModuleInstallerAPITest extends ItopDataTestCase
{
	protected static string $sWorkTable = "unit_tests_work_table";

	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('setup/moduleinstaller.class.inc.php');
	}

	public function tearDown(): void
	{
		if (CMDBSource::IsTable(static::$sWorkTable)) {
			CMDBSource::DropTable(static::$sWorkTable);
		}

		parent::tearDown();
	}

	/**
	 * Test that the new $bIgnoreExistingDstColumn parameter works as expected and doesn't break the previous behavior
	 *
	 * @covers       \ModuleInstallerAPI::MoveColumnInDB
	 * @dataProvider MoveColumnInDB_IgnoreExistingDstColumnParamProvider
	 *
	 * @param bool $bDstColAlreadyExists
	 * @param bool $bIgnoreExistingDstColumn
	 * @param bool $bShouldWork
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	public function testMoveColumnInDB_IgnoreExistingDstColumnParam(bool $bDstColAlreadyExists, bool $bIgnoreExistingDstColumn, bool $bShouldWork): void
	{
		// Info from the original table
		$sOrigClass = "Person";
		$sOrigTable = MetaModel::DBGetTable($sOrigClass);
		$sOrigAttCode = "first_name";
		$oOrigAttDef = MetaModel::GetAttributeDef($sOrigClass, $sOrigAttCode);
		$sOrigColName = array_key_first($oOrigAttDef->GetSQLColumns());

		// Info for the destination table
		$sDstTable = static::$sWorkTable;
		$sDstNonExistingColName = "non_existing_column";
		$sDstExistingColName = "existing_column";

		// Create work table with an empty $sDstExistingColName column
		// Data will then either be moved to the $sDstExistingColName or $sDstNonExistingColName column depending on the test case
		CMDBSource::Query(
			<<<SQL
CREATE TABLE `{$sDstTable}` AS
SELECT id
FROM {$sOrigTable}
SQL
		);
		CMDBSource::Query(
			<<<SQL
ALTER TABLE `{$sDstTable}`
ADD `{$sDstExistingColName}` VARCHAR(255)
SQL
		);
		CMDBSource::CacheReset($sDstTable);

		// Save value from original table as a reference
		$oPerson = MetaModel::GetObject($sOrigClass, 1);
		$sOrigValue = $oPerson->Get($sOrigAttCode);

		// Try to move data
		$sDstColName = $bDstColAlreadyExists ? $sDstExistingColName : $sDstNonExistingColName;
		ModuleInstallerAPI::MoveColumnInDB($sOrigTable, $sOrigColName, $sDstTable, $sDstColName, $bIgnoreExistingDstColumn);

		// Check if data was actually moved
		// - Either way, the column should exist
		$sDstValue = CMDBSource::QueryToScalar(
			<<<SQL
SELECT `{$sDstColName}` FROM `{$sDstTable}` WHERE `id` = 1
LIMIT 1
SQL
		);

		// Put data back in the original table
		ModuleInstallerAPI::MoveColumnInDB($sDstTable, $sDstColName, $sOrigTable, $sOrigColName);

		if ($bShouldWork) {
			$this->assertEquals($sOrigValue, $sDstValue, "Data was not moved as expected");
		} else {
			$this->assertEquals(null, $sDstValue, "Data should NOT have moved");
		}
	}

	public function MoveColumnInDB_IgnoreExistingDstColumnParamProvider(): array
	{
		return [
			"Nominal use case, move data to a non-existing column" => [
				"Dest. col. already exists?" => false,
				"bIgnoreExistingDstColumn param" => false,
				"Should work" => true,
			],
			"Move data to non-existing table fails if not explicitly wanted" => [
				"Dest. col. already exists?" => true,
				"bIgnoreExistingDstColumn param" => false,
				"Should work" => false,
			],
			"Move data to non-existing table on purpose" => [
				"Dest. col. already exists?" => true,
				"bIgnoreExistingDstColumn param" => true,
				"Should work" => true,
			],
		];
	}

}
