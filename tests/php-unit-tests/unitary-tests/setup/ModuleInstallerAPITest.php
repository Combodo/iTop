<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Config;
use MetaModel;
use ModuleInstallerAPI;

/**
 * Class ModuleInstallerAPI
 *
 * @covers ModuleInstallerAPI
 *
 */
class ModuleInstallerAPITest extends ItopDataTestCase
{
	protected static string $sWorkTable = "unit_tests_work_table";
	protected static string $sWorkTable2 = "unit_tests_work_table2";
	protected static string $sWorkTable3 = "unit_tests_work_table3";

	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('setup/moduleinstaller.class.inc.php');
	}

	public function tearDown(): void
	{
		foreach ([static::$sWorkTable, static::$sWorkTable2, static::$sWorkTable3] as $sTable) {
			if (CMDBSource::IsTable($sTable)) {
				CMDBSource::DropTable($sTable);
			}
		}

		parent::tearDown();
	}

	/**
	 * @param string $sTable
	 * @param string $sAttCode
	 *
	 * @return array
	 * @throws \CoreException
	 */
	protected function GetInfoFromTable(string $sTable, string $sAttCode): array
	{
		$sOrigTable = MetaModel::DBGetTable($sTable);
		$oOrigAttDef = MetaModel::GetAttributeDef($sTable, $sAttCode);
		$sOrigColName = array_key_first($oOrigAttDef->GetSQLColumns());

		return [$sOrigTable, $sOrigColName];
	}

	/**
	 * @param string $sDstTable
	 * @param array $aOrigTables
	 * @param string $sDstExistingColName
	 *
	 * @return void
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	protected function CreateDestinationTable(string $sDstTable, array $aOrigTables, string $sDstExistingColName): void
	{
		// Create a table with the same structure as the original table(s)
		// - Create a SQL query to get all the ids from the original tables
		if (is_array($aOrigTables)) {
			$sOrigDataQuery = implode(" UNION ", array_map(fn ($sTable) => "SELECT id FROM `{$sTable}`", $aOrigTables));
		}

		CMDBSource::Query(
			<<<SQL
CREATE TABLE `{$sDstTable}` AS {$sOrigDataQuery}
SQL
		);

		// Add a column to the destination table
		CMDBSource::Query(
			<<<SQL
ALTER TABLE `{$sDstTable}`
ADD `{$sDstExistingColName}` VARCHAR(255)
SQL
		);

		CMDBSource::CacheReset($sDstTable);
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
		$sOrigAttCode = "first_name";
		[$sOrigTable, $sOrigColName] = $this->GetInfoFromTable($sOrigClass, $sOrigAttCode);

		// Info for the destination table
		$sDstTable = static::$sWorkTable;
		$sDstNonExistingColName = "non_existing_column";
		$sDstExistingColName = "existing_column";
		$this->CreateDestinationTable($sDstTable, [$sOrigTable], $sDstExistingColName);

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
			"Move data to existing table fails if not explicitly wanted" => [
				"Dest. col. already exists?" => true,
				"bIgnoreExistingDstColumn param" => false,
				"Should work" => false,
			],
			"Move data to existing table on purpose" => [
				"Dest. col. already exists?" => true,
				"bIgnoreExistingDstColumn param" => true,
				"Should work" => true,
			],
		];
	}

	/**
	 *  Test that if we move two columns into the same one using $bIgnoreExistingDstColumn, we don't lose data from one of the columns
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \MySQLQueryHasNoResultException
	 */
	public function testMoveColumnInDB_MoveMultipleTable(): void
	{
		// Create 2 objects, so we know the ids for one of each class
		$oPerson = $this->createObject('Person', ['first_name' => 'John', 'name' => 'Doe', 'org_id' => 1]);
		$oTeam = $this->createObject('Team', ['name' => 'La tcheam', 'org_id' => 1]);

		// Info from the original tables (we don't need real data, just the ids)
		$sOrigClass = "Person";
		[$sOrigTable, $sOrigColName] = $this->GetInfoFromTable($sOrigClass, "first_name");

		$sOrigClass2 = "Team";
		[$sOrigTable2, $sOrigColName2] = $this->GetInfoFromTable($sOrigClass2, "friendlyname");

		// Info for the destination table
		$sDstTable = static::$sWorkTable3;
		$sDstColName = "existing_column";

		// Insert our ids into similar work tables
		// Then insert data into their respective columns to be moved
		$sOrigWorkTable = static::$sWorkTable;
		$this->CreateDestinationTable($sOrigWorkTable, [$sOrigTable], $sDstColName);
		CMDBSource::Query(
			<<<SQL
	UPDATE `{$sOrigWorkTable}`
	SET `{$sDstColName}` = 'from table 1'
	WHERE 1
	SQL
		);

		$sOrigWorkTable2 = static::$sWorkTable2;
		$this->CreateDestinationTable($sOrigWorkTable2, [$sOrigTable2], $sDstColName);
		CMDBSource::Query(
			<<<SQL
	UPDATE `{$sOrigWorkTable2}`
	SET `{$sDstColName}` = 'from table 2'
	WHERE 1
	SQL
		);

		// Create our destination table
		$this->CreateDestinationTable($sDstTable, [$sOrigTable, $sOrigTable2], $sDstColName);

		// Try to move data from both tables into the same column
		ModuleInstallerAPI::MoveColumnInDB($sOrigWorkTable, $sDstColName, $sDstTable, $sDstColName, true);
		ModuleInstallerAPI::MoveColumnInDB($sOrigWorkTable2, $sDstColName, $sDstTable, $sDstColName, true);

		// Check if data was actually moved by getting the value from the destination table for the ids we stored earlier
		$iPersonId = $oPerson->GetKey();
		$sFromTable1Data = CMDBSource::QueryToScalar(
			<<<SQL
	SELECT `{$sDstColName}` FROM `{$sDstTable}` WHERE `id` = {$iPersonId}
	LIMIT 1
	SQL
		);

		$iTeamId = $oTeam->GetKey();
		$sFromTable2Data = CMDBSource::QueryToScalar(
			<<<SQL
	SELECT `{$sDstColName}` FROM `{$sDstTable}` WHERE `id` = {$iTeamId}
	LIMIT 1
	SQL
		);

		$this->assertEquals('from table 1', $sFromTable1Data, "Data was not moved as expected");
		$this->assertEquals('from table 2', $sFromTable2Data, "Data was not moved as expected");
	}

	/**
	 * Test that moving columns from/to the same table works
	 *
	 * @covers       \ModuleInstallerAPI::MoveColumnInDB
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \MySQLQueryHasNoResultException
	 */
	public function testMoveColumnInDB_SameTable(): void
	{
		// Info from the original table
		$sOrigClass = "Person";
		$sOrigAttCode = "first_name";
		[$sOrigTable, $sOrigColName] = $this->GetInfoFromTable($sOrigClass, $sOrigAttCode);

		// Info for the destination column
		$sDstNonExistingColName = "non_existing_column";

		// Save value from original table as a reference
		$oPerson = MetaModel::GetObject($sOrigClass, 1);
		$sOrigValue = $oPerson->Get($sOrigAttCode);

		// Try to move data
		ModuleInstallerAPI::MoveColumnInDB($sOrigTable, $sOrigColName, $sOrigTable, $sDstNonExistingColName);

		// Check if data was actually moved
		// - Either way, the column should exist
		$sDstValue = CMDBSource::QueryToScalar(
			<<<SQL
SELECT `{$sDstNonExistingColName}` FROM `{$sOrigTable}` WHERE `id` = 1
LIMIT 1
SQL
		);

		// Put data back in the original table
		ModuleInstallerAPI::MoveColumnInDB($sOrigTable, $sDstNonExistingColName, $sOrigTable, $sOrigColName);

		$this->assertEquals($sOrigValue, $sDstValue, "Data was not moved as expected");
	}

	/**
	 * @covers \ModuleInstallerAPI::LoadLocalizedData
	 */
	public function testLoadLocalizedData_LoadsOnFirstInstall(): void
	{
		// Given
		[$oConfig, $sOrgName, $sTmpDir, $sPattern] = $this->PrepareLocalizedDataTestContext('XML_Load_FirstInstall_', 'fr_fr');
		$this->CreateLocalizedDataFile($sTmpDir, "en_us", $sOrgName);
		$this->CreateLocalizedDataFile($sTmpDir, "fr_fr", $sOrgName);
		// When no previous version, and current version higher than the first loading version
		ModuleInstallerAPI::LoadLocalizedData('', '3.3.0', $oConfig, '3.0.0', $sPattern);
		// Then data loaded
		$this->AssertOrganizationCountByName($sOrgName, 'en_us', 0);
		$this->AssertOrganizationCountByName($sOrgName, 'fr_fr', 1);
	}

	/**
	 * @covers \ModuleInstallerAPI::LoadLocalizedData
	 */
	public function testLoadLocalizedData_DoesNotLoadWhenVersionConditionIsNotMet(): void
	{
		// Given
		[$oConfig, $sOrgName, $sTmpDir, $sPattern] = $this->PrepareLocalizedDataTestContext('XML_Load_NoLoad_', 'en_us');
		$this->CreateLocalizedDataFile($sTmpDir, "en_us", $sOrgName);

		// When a previous version that is lower than the first loading version, but higher or equal to the current version
		ModuleInstallerAPI::LoadLocalizedData('3.0.0', '3.1.0', $oConfig, '3.0.0', $sPattern);
		// Then no data loaded
		$this->AssertOrganizationCountByName($sOrgName, 'en_us', 0);
	}

	/**
	 * @covers \ModuleInstallerAPI::LoadLocalizedData
	 */
	public function testLoadLocalizedData_FallbacksToEnUsWhenLanguageFileIsMissing(): void
	{
		[$oConfig, $sOrgName, $sTmpDir, $sPattern] = $this->PrepareLocalizedDataTestContext('XML_Load_Fallback_', 'fr_fr');
		// Intentionally create ONLY en_us file
		$this->CreateLocalizedDataFile($sTmpDir, 'en_us', $sOrgName);
		// When loading localized data in fr_fr, but only en_us file exists
		ModuleInstallerAPI::LoadLocalizedData('', '3.3.0', $oConfig, '3.0.0', $sPattern);

		$this->AssertOrganizationCountByName($sOrgName, 'fr_fr', 0);
		$this->AssertOrganizationCountByName($sOrgName, 'en_us', 1);
	}

	/**
	 * Prepare common context for LoadLocalizedData tests.
	 *
	 * @return array{0: Config, 1: string, 2: string, 3: string, 4: string}
	 */
	private function PrepareLocalizedDataTestContext(string $sOrgNamePrefix, string $sLanguage): array
	{
		$oConfig = MetaModel::GetConfig();
		$oConfig->SetDefaultLanguage($sLanguage);
		$this->assertNotNull($oConfig);

		$sOrgName = $sOrgNamePrefix.uniqid();

		$sTmpDir = static::CreateTmpdir();
		$this->aFileToClean[] = $sTmpDir;
		$sPattern = $sTmpDir.DIRECTORY_SEPARATOR.'data.{{language_code}}.xml';

		return [$oConfig, $sOrgName, $sTmpDir, $sPattern];
	}

	private function CreateLocalizedDataFile(string $sDir, string $sLang, string $sOrgName): string
	{
		$sFilePath = $sDir.DIRECTORY_SEPARATOR.'data.'.$sLang.'.xml';
		file_put_contents($sFilePath, $this->BuildOrganizationXml($sOrgName, $sLang));

		return $sFilePath;
	}

	private function BuildOrganizationXml(string $sOrgName, string $sLang): string
	{
		$iId = random_int(100000, 999999);
		$sOrgNameXml = htmlspecialchars($sOrgName, ENT_XML1);

		return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Set>
	<Organization alias="Organization" id="{$iId}">
		<name>{$sOrgNameXml}</name>
		<code>{$sLang}</code>
		<status>active</status>
	</Organization>
</Set>
XML;
	}

	private function AssertOrganizationCountByName(string $sOrgName, string $sLanguage, int $iExpectedCount): void
	{
		$sOrgTable = MetaModel::DBGetTable('Organization');
		$iCount = (int) CMDBSource::QueryToScalar(
			"SELECT COUNT(*) FROM `{$sOrgTable}` WHERE `name` = ".CMDBSource::Quote($sOrgName)." AND `code` = ".CMDBSource::Quote($sLanguage)
		);

		$this->assertEquals($iExpectedCount, $iCount);
	}
}
