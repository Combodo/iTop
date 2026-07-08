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
	 * @covers \ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion
	 * @dataProvider LoadLocalizedData_RequiredLanguageProvider
	 */
	public function testLoadLocalizedData_LoadRequiredLanguageOnFirstInstall(string $sRequiredLanguage, array $aExpectedOrganizationNames): void
	{
		// Given
		[$oConfig, $sPattern] = $this->GivenLocalizedDataTestContext($sRequiredLanguage);

		// When no previous version, and current version higher than the first loading version
		ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion($oConfig, '', '3.3.0', '3.0.0', $sPattern);

		// Then the expected localized organizations are loaded
		$this->AssertOrganizationNamesExist($aExpectedOrganizationNames);
	}

	public function LoadLocalizedData_RequiredLanguageProvider(): array
	{
		return [
			'Required fr_fr and file exists' => [
				'required language' => 'fr_fr',
				'expected organization names' => ['Client (Test)', 'Département informatique (Test)'],
			],
			'Required en_us and file exists' => [
				'required language' => 'en_us',
				'expected organization names' => ['Customer (Test)', 'IT Department (Test)'],
			],
			'Required de_de but fallback to en_us' => [
				'required language' => 'de_de',
				'expected organization names' => ['Customer (Test)', 'IT Department (Test)'],
			],
		];
	}

	/**
	 * @covers \ModuleInstallerAPI::IsVersionCrossed
	 * @dataProvider IsVersionCrossedProvider
	 */
	public function testIsVersionCrossed_ReturnsExpectedValue(string $sPreviousVersion, string $sCurrentVersion, string $sFirstLoadingVersion, bool $bExpected): void
	{
		$bIsVersionCrossed = ModuleInstallerAPI::IsVersionCrossed($sPreviousVersion, $sCurrentVersion, $sFirstLoadingVersion);

		$this->assertSame($bExpected, $bIsVersionCrossed);
	}

	public function IsVersionCrossedProvider(): array
	{
		return [
			'First install always crosses' => ['', '3.2.0', '3.0.0', true],
			'Upgrade crosses first loading version' => ['3.0.0', '3.2.0', '3.1.0', true],
			'Upgrade but first loading version already passed' => ['3.1.0', '3.2.0', '3.0.0', false],
			'Reinstall same version does not cross' => ['3.1.0', '3.1.0', '3.0.0', false],
			'Downgrade does not cross' => ['3.2.0', '3.1.0', '3.0.0', false],
			'First install with suffixed current version' => ['', '3.2-dev', '3.0.0', true],
			'Upgrade with suffixed current version crosses' => ['1.0.3-2', '1.2.4-1', '1.1.0', true],
			'Upgrade with suffixed current version below threshold does not cross' => ['1.0.0', '1.2.0-beta', '1.2.0', false],
		];
	}

	/**
	 * @covers \ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion
	 */
	public function testLoadLocalizedData_LoadsWhenVersionCrossingIsTrue(): void
	{
		[$oConfig, $sPattern] = $this->GivenLocalizedDataTestContext('FR FR', 'data.sample.organizations.en_us.xml');

		ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion($oConfig, '3.0.0', '3.2.0', '3.1.0', $sPattern);

		$this->AssertOrganizationNamesExist(['Client (Test)', 'Département informatique (Test)']);
	}
	// Test when a file is loaded twice because of the version conditions, it doesn't create duplicates (idempotent loading)
	public function testLoadLocalizedData_IdempotentLoading(): void
	{
		// Given
		[$oConfig, $sPattern] = $this->GivenLocalizedDataTestContext('en_us', 'data.sample.organizations.en_us.xml');

		// When LoadLocalizedData is called twice with conditions that would load the file both times
		ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion($oConfig, '', '3.0.1', '3.0.0', $sPattern);
		ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion($oConfig, '3.0.1', '3.1.0', '3.0.2', $sPattern);

		// Then no duplicated data load side effect and values remain stable
		$this->AssertOrganizationNamesExist(['Customer (Test)', 'IT Department (Test)']);
	}

	/**
	 * @covers \ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion
	 */
	public function testLoadLocalizedData_LoadsRequestedNonEnUsFileWhenItExists(): void
	{
		[$oConfig, $sPattern] = $this->GivenLocalizedDataTestContext('en_us', 'data.sample.organizations.fr_fr.xml');

		ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion($oConfig, '', '3.3.0', '3.0.0', $sPattern);

		$this->AssertOrganizationNamesExist(['Client (Test)', 'Département informatique (Test)']);
	}

	/**
	 * @covers \ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion
	 */
	public function testLoadLocalizedData_ThrowsWhenRequestedNonEnUsFileDoesNotExist(): void
	{
		[$oConfig, $sPattern] = $this->GivenLocalizedDataTestContext('en_us', 'data.sample.organizations.fr_fr.missing.xml', false);

		$this->expectException(\Exception::class);
		$this->expectExceptionMessage("File $sPattern not found");

		ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion($oConfig, '', '3.3.0', '3.0.0', $sPattern);
	}

	/**
	 * @covers \ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion
	 * @dataProvider LoadLocalizedData_InvalidParametersProvider
	 */
	public function testLoadLocalizedData_ThrowsOnInvalidParameters(string $sPreviousVersion, string $sCurrentVersion, string $sFirstLoadingVersion, string $sPattern, string $sExpectedMessage): void
	{
		$oConfig = MetaModel::GetConfig();
		$this->assertNotNull($oConfig);

		$this->expectException(\CoreUnexpectedValue::class);
		$this->expectExceptionMessage($sExpectedMessage);

		ModuleInstallerAPI::LoadLocalizedDataOnCrossingVersion($oConfig, $sPreviousVersion, $sCurrentVersion, $sFirstLoadingVersion, $sPattern);
	}

	public function LoadLocalizedData_InvalidParametersProvider(): array
	{
		$sTmpDir = static::CreateTmpdir();
		$this->aFileToClean[] = $sTmpDir;

		return [
			'Invalid previous version format' => [
				'previous' => 'v3.2',
				'current' => '3.2.0',
				'first' => '3.0.0',
				'pattern' => $sTmpDir.DIRECTORY_SEPARATOR.'data.en_us.xml',
				'message' => 'sPreviousVersion',
			],
			'Invalid current version format' => [
				'previous' => '',
				'current' => '3',
				'first' => '3.0.0',
				'pattern' => $sTmpDir.DIRECTORY_SEPARATOR.'data.en_us.xml',
				'message' => 'sCurrentVersion',
			],
			'Invalid first loading version format' => [
				'previous' => '',
				'current' => '3.2.0',
				'first' => '3.0.0-beta.1',
				'pattern' => $sTmpDir.DIRECTORY_SEPARATOR.'data.en_us.xml',
				'message' => 'sFirstLoadingVersion',
			],
		];
	}

	/**
	 * Prepare common context for LoadLocalizedData tests.
	 *
	 * @return array{0: Config, 1: string}
	 */
	private function GivenLocalizedDataTestContext(string $sLanguage, string $sDataFileName = 'data.sample.organizations.en_us.xml', bool $bAssertFileExists = true): array
	{
		$oConfig = MetaModel::GetConfig();
		$oConfig->SetDefaultLanguage($sLanguage);
		$this->assertNotNull($oConfig);

		$sPattern = __DIR__.DIRECTORY_SEPARATOR.'resources2'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'load-localized-data'.DIRECTORY_SEPARATOR.$sDataFileName;
		if ($bAssertFileExists) {
			$this->assertFileExists($sPattern);
		}

		return [$oConfig, $sPattern];
	}

	private function AssertOrganizationNamesExist(array $aExpectedOrganizationNames): void
	{
		foreach ($aExpectedOrganizationNames as $sExpectedName) {
			$this->AssertOrganizationNameExists($sExpectedName);
		}
	}

	private function AssertOrganizationNameExists(string $sExpectedName): void
	{
		$oSet = new \DBObjectSet(
			\DBSearch::FromOQL("SELECT Organization WHERE name = :org_name"),
			[],
			['org_name' => $sExpectedName]
		);
		$this->assertGreaterThanOrEqual(1, $oSet->Count(), "Expected at least one Organization named '{$sExpectedName}'");
	}
}
