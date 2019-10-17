<?php

namespace Combodo\iTop\Test\UnitTest\Core;


define('NUM_PRECISION', 2);

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBSearch;
use MetaModel;
use SetupUtils;
use utils;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class OQLToSQLTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;
	const CREATE_TEST_ORG = false;
	const TEST_CSV_RESULT = 'OQLToSQLTest.csv';

	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');

		SetupUtils::builddir(APPROOT.'log/test/OQLToSQL');
	}

	private function GetPreviousTestResult($sTestId)
	{
		$sResultFile = APPROOT.'log/test/OQLToSQL/'.$sTestId.'.txt';
		if (!is_file($sResultFile))
		{
			return null;
		}

		$aResult = unserialize(file_get_contents($sResultFile));
		return $aResult;
	}

	private function SaveTestResult($sTestId, $aResult)
	{
		$sResultFile = APPROOT.'log/test/OQLToSQL/'.$sTestId.'.txt';
		if (is_file($sResultFile))
		{
			@unlink($sResultFile);
		}
		file_put_contents($sResultFile, serialize($aResult));
	}

	/**
	 * @doesNotPerformAssertions
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testOQLLegacySetup()
	{
		utils::GetConfig()->Set('use_legacy_dbsearch', true, 'Test');
		utils::GetConfig()->Set('apc_cache.enabled', false, 'Test');
		utils::GetConfig()->Set('expression_cache_enabled', false, 'Test');
		utils::GetConfig()->Set('query_cache_enabled', false, 'Test');
		$sConfigFile = utils::GetConfig()->GetLoadedFile();
		@chmod($sConfigFile, 0770);
		utils::GetConfig()->WriteToFile();
		@chmod($sConfigFile, 0444); // Read-only

		SetupUtils::rrmdir($sResultFile = APPROOT.'log/test');
	}

	/**
	 * @dataProvider OQLSelectProvider
	 * @depends testOQLLegacySetup
	 *
	 * @param $sOQL
	 *
	 * @param array $aOrderBy
	 * @param array $aArgs
	 * @param null $aAttToLoad
	 * @param null $aExtendedDataSpec
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 *
	 * @throws \Exception
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 */
	public function testOQLLegacySelect($sOQL, $aOrderBy = array(), $aArgs = array(), $aAttToLoad = null, $aExtendedDataSpec = null, $iLimitCount = 20, $iLimitStart = 0)
	{
		$this->assertTrue(utils::GetConfig()->Get('use_legacy_dbsearch'));
		$this->assertFalse(utils::GetConfig()->Get('apc_cache.enabled'));
		$this->assertFalse(utils::GetConfig()->Get('query_cache_enabled'));
		$this->assertFalse(utils::GetConfig()->Get('expression_cache_enabled'));

		$aPrevious = $this->GetPreviousTestResult($this->GetId());
		if (is_null($aPrevious))
		{
			$aResult = $this->OQLSelectRunner($sOQL, $aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart);
			// no test yet, just save
			$this->SaveTestResult($this->GetId(), $aResult);
			$this->debug("Test result saved");
		}
		$this->assertTrue(true);
	}

	/**
	 * @doesNotPerformAssertions
	 *
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
	public function testOQLSetup()
	{
		utils::GetConfig()->Set('use_legacy_dbsearch', false, 'test');
		utils::GetConfig()->Set('apc_cache.enabled', false, 'test');
		utils::GetConfig()->Set('query_cache_enabled', false, 'test');
		utils::GetConfig()->Set('expression_cache_enabled', false, 'test');
		$sConfigFile = utils::GetConfig()->GetLoadedFile();
		@chmod($sConfigFile, 0770);
		utils::GetConfig()->WriteToFile();
		@chmod($sConfigFile, 0444); // Read-only

		$aCSVHeader = array(
			'test', 'OQL','count',
			'Legacy Count Joins', 'Count Joins',
			'Legacy Count Duration', 'Count Duration',
			'Legacy Data Joins', 'Data Joins',
			'Legacy Data Duration', 'Data Duration',
			'Count Joins Diff', 'Data Joins Diff',
		);
		$this->WriteToCsvHeader(self::TEST_CSV_RESULT, $aCSVHeader);
	}

	/**
	 * @dataProvider OQLSelectProvider
	 * @depends testOQLSetup
	 *
	 * @param $sOQL
	 *
	 * @param array $aOrderBy
	 * @param array $aArgs
	 * @param null $aAttToLoad
	 * @param null $aExtendedDataSpec
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 *
	 * @throws \Exception
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 */
	public function testOQLSelect($sOQL, $aOrderBy = array(), $aArgs = array(), $aAttToLoad = null, $aExtendedDataSpec = null, $iLimitCount = 20, $iLimitStart = 0)
	{
		$this->assertFalse(utils::GetConfig()->Get('use_legacy_dbsearch'));
		$this->assertFalse(utils::GetConfig()->Get('apc_cache.enabled'));
		$this->assertFalse(utils::GetConfig()->Get('query_cache_enabled'));
		$this->assertFalse(utils::GetConfig()->Get('expression_cache_enabled'));

		$aResult = $this->OQLSelectRunner($sOQL, $aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart);
		$this->assertNull($aResult);
	}

	/**
	 * @param $sOQL
	 *
	 * @param array $aOrderBy
	 * @param array $aArgs
	 * @param null $aAttToLoadNames
	 * @param null $aExtendedDataSpec
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 *
	 * @return array|null
	 * @throws \Exception
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 */
	private function OQLSelectRunner($sOQL, $aOrderBy = array(), $aArgs = array(), $aAttToLoadNames = null, $aExtendedDataSpec = null, $iLimitCount = 20, $iLimitStart = 0)
	{
		if (is_null($aAttToLoadNames))
		{
			$aAttToLoad = null;
		}
		else
		{
			$aAttToLoad = array();
			foreach ($aAttToLoadNames as $sClass => $aAttCodes)
			{
				$aAttToLoad[$sClass] = array();
				foreach ($aAttCodes as $sAttCode)
				{
					if (!empty($sAttCode))
					{
						if (MetaModel::IsValidAttCode($sClass, $sAttCode))
						{
							$aAttToLoad[$sClass][$sAttCode] = MetaModel::GetAttributeDef($sClass, $sAttCode);
						}
					}
				}
			}
		}

		$oSearch = DBSearch::FromOQL($sOQL);
		$sClass = $oSearch->GetClass();
		if (empty($aOrderBy))
		{
			$aOrderBy = MetaModel::GetOrderByDefault($sClass);
		}

		$sSQLCount = $oSearch->MakeSelectQuery($aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, 0, 0, true);
		$fStart = $this->GetMicroTime();
		$aRow = $this->GetArrayResult($sSQLCount);
		$fCountDuration = $this->GetMicroTime() - $fStart;
		if (is_null($aRow))
		{
			$iCount = 0;
		}
		else
		{
			$iCount = intval($aRow[0]['COUNT']);
		}
		$iJoinCount = count(explode(' JOIN ', $sSQLCount)) - 1;


		$sSQL = $oSearch->MakeSelectQuery($aOrderBy, $aArgs, $aAttToLoad, $aExtendedDataSpec, $iLimitCount, $iLimitStart);
		$fStart = $this->GetMicroTime();
		$aRow = $this->GetArrayResult($sSQL);
		$fDataDuration = $this->GetMicroTime() - $fStart;
		if (is_null($aRow))
		{
			$aRow = array();
		}
		// Store only to the 10 first entries
		$aRow = array_slice($aRow, 0, 10);

		$iJoinData = count(explode(' JOIN ', $sSQL)) - 1;

		$aResult = array(
			'oql' => $sOQL,
			'count_sql' => $sSQLCount,
			'count_join_count' => $iJoinCount,
			'count' => $iCount,
			'count_duration' => $fCountDuration,
			'data_sql' => $sSQL,
			'data_join_count' => $iJoinData,
			'data_duration' => $fDataDuration,
		);

		//$this->debug($aResult);

		$aResult['data'] = $aRow;

		$aPrevious = $this->GetPreviousTestResult($this->GetId());
		if (is_null($aPrevious))
		{
			return $aResult;
		}

		$this->debug("count: ".$aResult['count']);
		$this->debug("count_join_count: ".$aPrevious['count_join_count']." -> ".$aResult['count_join_count']);
		$this->debug("count_duration  : ".round($aPrevious['count_duration'], NUM_PRECISION)." -> ".round($aResult['count_duration'], NUM_PRECISION));
		$this->debug("data_join_count : ".$aPrevious['data_join_count']." -> ".$aResult['data_join_count']);
		$this->debug("data_duration   : ".round($aPrevious['data_duration'], NUM_PRECISION)." -> ".round($aResult['data_duration'], NUM_PRECISION));

		$aCSVData = array(
			$this->GetId(),	$sOQL, $aResult['count'],
			$aPrevious['count_join_count'], $aResult['count_join_count'],
			round($aPrevious['count_duration'], NUM_PRECISION), round($aResult['count_duration'], NUM_PRECISION),
			$aPrevious['data_join_count'], $aResult['data_join_count'],
			round($aPrevious['data_duration'], NUM_PRECISION), round($aResult['data_duration'], NUM_PRECISION),
			$aPrevious['count_join_count'] - $aResult['count_join_count'], $aPrevious['data_join_count'] - $aResult['data_join_count'],
		);
		$this->WriteToCsvData(self::TEST_CSV_RESULT, $aCSVData);

		// Compare result
		$aFields = array('oql', 'count', 'data');
		foreach ($aFields as $sField)
		{
			$this->assertEquals($aPrevious[$sField], $aResult[$sField], "$sField differ");
		}

		if ($aPrevious['data_join_count'] != $aResult['data_join_count'])
		{
			unset($aPrevious['data']);
			unset($aResult['data']);
			$this->debug("Previous");
			$this->debug($aPrevious);
			$this->debug("Current");
			$this->debug($aResult);
		}
		return null;
	}


	private function OQLSelectProviderStatic()
	{
		$aArgs = array(
			'ActionEmail_finalclass' => 'ActionEmail',
			'UserInternal_status' => 'active',
			'current_contact_id' => '2',
			'id' => 3,
			'login' => 'admin',
			'menu_code' => 'WelcomeMenuPage',
			'name' => 'database_uuid',
			'this->brand_id' => '1',
			'this->finalclass' => 'NetworkDevice',
			'this->id' => 3,
			'this->location_id' => 2,
			'this->org_id' => 3,
			'this->osfamily_id' => '6',
			'this->osversion_id' => '8',
			'this->rack_id' => '3',
			'this->request_type' => 'incident',
			'this->service_id' => '1',
			'user_id' => '5',
			'userid' => '5',
		);

		$aAttToLoad150 = array(
			'WebServer' => array(
				'business_criticity',
				'description',
				'name',
				'friendlyname',
				'obsolescence_flag',
				'finalclass',
			),
		);

		$aData =  array(
			"SELECT WebServer 150" => array("SELECT `WebServer` FROM WebServer AS `WebServer` WHERE 1", array(), array(), $aAttToLoad150, null, null, 3, 0),
			"SELECT WebServer 151" => array("SELECT `WebServer` FROM WebServer AS `WebServer` WHERE 1", array(), array(), array(), null, null, 3, 0),
			"SELECT L JOIN 176" => array("SELECT `L` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1", unserialize('a:1:{s:14:"L.friendlyname";b:1;}'), array(), null, null, 3, 0),
			"SELECT P JOIN 177" => array("SELECT `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, null, 3, 0),
			"SELECT L,P JOIN 178" => array("SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, null, 3, 0),
			"SELECT P,L JOIN 179" => array("SELECT `P`, `L` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, null, 3, 0),
			"SELECT L,P JOIN 180" => array("SELECT `L`, `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, null, 3, 0),
			"SELECT L JOIN 181" => array("SELECT `L` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", unserialize('a:1:{s:14:"L.friendlyname";b:1;}'), array(), null, null, 3, 0),
			"SELECT UserRequest 114" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Organization AS `Organization` ON `UserRequest`.org_id = `Organization`.id JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE (`Organization1`.`id` = '3')", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 115" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (('2018-12-01' < `UserRequest`.`start_date`) AND (ISNULL(DATE_FORMAT(`UserRequest`.`start_date`, '%Y-%m-%d')) != 1))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 116" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`agent_id` = :current_contact_id) AND (`UserRequest`.`status` NOT IN ('closed', 'resolved')))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 117" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`caller_id` = :current_contact_id) AND (`UserRequest`.`status` NOT IN ('closed')))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 118" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` IN ('escalated_tto', 'escalated_ttr')) OR (`UserRequest`.`escalation_flag` = 'yes'))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 119" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`agent_id`) != 1))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 120" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`finalclass`) != 1))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 121" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`org_id`) != 1))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 122" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`status`) != 1))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 123" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (ISNULL(`UserRequest`.`org_id`) != 1)", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 124" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` != 'closed')", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 125" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` NOT IN ('closed'))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 126" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE 1", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 25" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`id` = :id)", array(), unserialize('a:1:{s:2:"id";i:987654321;}'), null, null, 0, 0),
			"SELECT ApplicationSolution 2" => array("SELECT `ApplicationSolution` FROM ApplicationSolution AS `ApplicationSolution` WHERE 1", array('ApplicationSolution.friendlyname' => true), $aArgs),
			"SELECT AuditCategory 3" => array("SELECT `AuditCategory` FROM AuditCategory AS `AuditCategory` WHERE 1", array('AuditCategory.friendlyname' => true), $aArgs),
			"SELECT Brand 4" => array("SELECT `Brand` FROM Brand AS `Brand` WHERE (`Brand`.`friendlyname` LIKE '%%')", array('Brand.friendlyname' => true), $aArgs),
			"SELECT Brand 5" => array("SELECT `Brand` FROM Brand AS `Brand` WHERE 1", array('Brand.friendlyname' => true), $aArgs),
			"SELECT BusinessProcess 6" => array("SELECT `BusinessProcess` FROM BusinessProcess AS `BusinessProcess` WHERE 1", array('BusinessProcess.friendlyname' => true), $aArgs),
			"SELECT Change 7" => array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`agent_id` = :current_contact_id) AND (`Change`.`status` NOT IN ('closed')))", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 8" => array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(DATE_FORMAT(`Change`.`start_date`, '%Y-%m-%d')) != 1))", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 9" => array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(`Change`.`category`) != 1))", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 10" => array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(`Change`.`finalclass`) != 1))", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 11" => array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(`Change`.`status`) != 1))", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 12" => array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`id` != :this->id) AND (`Change`.`friendlyname` LIKE '%%'))", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 13" => array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`status` != 'closed') AND (`Change`.`friendlyname` LIKE '%%'))", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 14" => array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`friendlyname` LIKE '%%')", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 15" => array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`status` != 'closed')", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 16" => array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`status` IN ('planned'))", array('Change.friendlyname' => true), $aArgs),
			"SELECT Change 17" => array("SELECT `Change` FROM Change AS `Change` WHERE 1", array('Change.friendlyname' => true), $aArgs),
			"SELECT ContactType 18" => array("SELECT `ContactType` FROM ContactType AS `ContactType` WHERE 1", array('ContactType.friendlyname' => true), $aArgs),
			"SELECT Contact 19" => array("SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`id` = :id)", array('Contact.friendlyname' => true), $aArgs),
			"SELECT Contact 20" => array("SELECT `Contact` FROM Contact AS `Contact` WHERE 1", array('Contact.friendlyname' => true), $aArgs),
			"SELECT ContractType 21" => array("SELECT `ContractType` FROM ContractType AS `ContractType` WHERE (`ContractType`.`friendlyname` LIKE '%%')", array('ContractType.friendlyname' => true), $aArgs),
			"SELECT ContractType 22" => array("SELECT `ContractType` FROM ContractType AS `ContractType` WHERE 1", array('ContractType.friendlyname' => true), $aArgs),
			"SELECT Contract 23" => array("SELECT `Contract` FROM Contract AS `Contract` WHERE 1", array('Contract.friendlyname' => true), $aArgs),
			"SELECT CustomerContract 24" => array("SELECT `CustomerContract` FROM CustomerContract AS `CustomerContract` WHERE 1", array('CustomerContract.friendlyname' => true), $aArgs),
			"SELECT DBProperty 25" => array("SELECT `DBProperty` FROM DBProperty AS `DBProperty` WHERE (`DBProperty`.`name` = :name)", array('DBProperty.friendlyname' => true), $aArgs),
			"SELECT DBServer 26" => array("SELECT `DBServer` FROM DBServer AS `DBServer` WHERE 1", array('DBServer.friendlyname' => true), $aArgs),
			"SELECT DatabaseSchema 27" => array("SELECT `DatabaseSchema` FROM DatabaseSchema AS `DatabaseSchema` WHERE 1", array('DatabaseSchema.friendlyname' => true), $aArgs),
			"SELECT DeliveryModel 28" => array("SELECT `DeliveryModel` FROM DeliveryModel AS `DeliveryModel` WHERE (`DeliveryModel`.`friendlyname` LIKE '%%')", array('DeliveryModel.friendlyname' => true), $aArgs),
			"SELECT DeliveryModel 29" => array("SELECT `DeliveryModel` FROM DeliveryModel AS `DeliveryModel` WHERE 1", array('DeliveryModel.friendlyname' => true), $aArgs),
			"SELECT DocumentType 30" => array("SELECT `DocumentType` FROM DocumentType AS `DocumentType` WHERE (`DocumentType`.`friendlyname` LIKE '%%')", array('DocumentType.friendlyname' => true), $aArgs),
			"SELECT DocumentType 31" => array("SELECT `DocumentType` FROM DocumentType AS `DocumentType` WHERE 1", array('DocumentType.friendlyname' => true), $aArgs),
			"SELECT Document 32" => array("SELECT `Document` FROM Document AS `Document` WHERE 1", array('Document.friendlyname' => true), $aArgs),
			"SELECT Enclosure 33" => array("SELECT `Enclosure` FROM Enclosure AS `Enclosure` WHERE ((`Enclosure`.`rack_id` = :this->rack_id) AND (`Enclosure`.`friendlyname` LIKE '%%'))", array('Enclosure.friendlyname' => true), $aArgs),
			"SELECT Enclosure 34" => array("SELECT `Enclosure` FROM Enclosure AS `Enclosure` WHERE (`Enclosure`.`friendlyname` LIKE '%%')", array('Enclosure.friendlyname' => true), $aArgs),
			"SELECT Enclosure 35" => array("SELECT `Enclosure` FROM Enclosure AS `Enclosure` WHERE 1", array('Enclosure.friendlyname' => true), $aArgs),
			"SELECT Farm 36" => array("SELECT `Farm` FROM Farm AS `Farm` WHERE 1", array('Farm.friendlyname' => true), $aArgs),
			"SELECT FunctionalCI 37" => array("SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE 1", array('FunctionalCI.friendlyname' => true), $aArgs),
			"SELECT Group 38" => array("SELECT `Group` FROM Group AS `Group` WHERE (`Group`.`friendlyname` LIKE '%%')", array('Group.friendlyname' => true), $aArgs),
			"SELECT Group 39" => array("SELECT `Group` FROM Group AS `Group` WHERE 1", array('Group.friendlyname' => true), $aArgs),
			"SELECT Hypervisor 40" => array("SELECT `Hypervisor` FROM Hypervisor AS `Hypervisor` WHERE 1", array('Hypervisor.friendlyname' => true), $aArgs),
			"SELECT IOSVersion 41" => array("SELECT `IOSVersion` FROM IOSVersion AS `IOSVersion` WHERE 1", array('IOSVersion.friendlyname' => true), $aArgs),
			"SELECT IPPhone 42" => array("SELECT `IPPhone` FROM IPPhone AS `IPPhone` WHERE 1", array('IPPhone.friendlyname' => true), $aArgs),
			"SELECT Licence 43" => array("SELECT `Licence` FROM Licence AS `Licence` WHERE 1", array('Licence.friendlyname' => true), $aArgs),
			"SELECT Location 44" => array("SELECT `Location` FROM Location AS `Location` WHERE 1", array('Location.friendlyname' => true), $aArgs),
			"SELECT LogicalVolume 45" => array("SELECT `LogicalVolume` FROM LogicalVolume AS `LogicalVolume` WHERE 1", array('LogicalVolume.friendlyname' => true), $aArgs),
			"SELECT MiddlewareInstance 46" => array("SELECT `MiddlewareInstance` FROM MiddlewareInstance AS `MiddlewareInstance` WHERE 1", array('MiddlewareInstance.friendlyname' => true), $aArgs),
			"SELECT Middleware 47" => array("SELECT `Middleware` FROM Middleware AS `Middleware` WHERE 1", array('Middleware.friendlyname' => true), $aArgs),
			"SELECT MobilePhone 48" => array("SELECT `MobilePhone` FROM MobilePhone AS `MobilePhone` WHERE 1", array('MobilePhone.friendlyname' => true), $aArgs),
			"SELECT Model 49" => array("SELECT `Model` FROM Model AS `Model` WHERE (((`Model`.`brand_id` = :this->brand_id) AND (`Model`.`type` = :this->finalclass)) AND (`Model`.`friendlyname` LIKE '%%'))", array('Model.friendlyname' => true), $aArgs),
			"SELECT Model 50" => array("SELECT `Model` FROM Model AS `Model` WHERE (`Model`.`friendlyname` LIKE '%%')", array('Model.friendlyname' => true), $aArgs),
			"SELECT Model 51" => array("SELECT `Model` FROM Model AS `Model` WHERE 1", array('Model.friendlyname' => true), $aArgs),
			"SELECT NAS 52" => array("SELECT `NAS` FROM NAS AS `NAS` WHERE 1", array('NAS.friendlyname' => true), $aArgs),
			"SELECT NetworkDeviceType 53" => array("SELECT `NetworkDeviceType` FROM NetworkDeviceType AS `NetworkDeviceType` WHERE 1", array('NetworkDeviceType.friendlyname' => true), $aArgs),
			"SELECT NetworkDevice 54" => array("SELECT `NetworkDevice` FROM NetworkDevice AS `NetworkDevice` WHERE 1", array('NetworkDevice.friendlyname' => true), $aArgs),
			"SELECT NetworkInterface 55" => array("SELECT `NetworkInterface` FROM NetworkInterface AS `NetworkInterface` WHERE 1", array('NetworkInterface.friendlyname' => true), $aArgs),
			"SELECT OSFamily 56" => array("SELECT `OSFamily` FROM OSFamily AS `OSFamily` WHERE (`OSFamily`.`friendlyname` LIKE '%%')", array('OSFamily.friendlyname' => true), $aArgs),
			"SELECT OSFamily 57" => array("SELECT `OSFamily` FROM OSFamily AS `OSFamily` WHERE 1", array('OSFamily.friendlyname' => true), $aArgs),
			"SELECT OSLicence 58" => array("SELECT `OSLicence` FROM OSLicence AS `OSLicence` WHERE ((`OSLicence`.`osversion_id` = :this->osversion_id) AND (`OSLicence`.`friendlyname` LIKE '%%'))", array('OSLicence.friendlyname' => true), $aArgs),
			"SELECT OSLicence 59" => array("SELECT `OSLicence` FROM OSLicence AS `OSLicence` WHERE (`OSLicence`.`friendlyname` LIKE '%%')", array('OSLicence.friendlyname' => true), $aArgs),
			"SELECT OSLicence 60" => array("SELECT `OSLicence` FROM OSLicence AS `OSLicence` WHERE 1", array('OSLicence.friendlyname' => true), $aArgs),
			"SELECT OSVersion 61" => array("SELECT `OSVersion` FROM OSVersion AS `OSVersion` WHERE ((`OSVersion`.`osfamily_id` = :this->osfamily_id) AND (`OSVersion`.`friendlyname` LIKE '%%'))", array('OSVersion.friendlyname' => true), $aArgs),
			"SELECT OSVersion 62" => array("SELECT `OSVersion` FROM OSVersion AS `OSVersion` WHERE (`OSVersion`.`friendlyname` LIKE '%%')", array('OSVersion.friendlyname' => true), $aArgs),
			"SELECT OSVersion 63" => array("SELECT `OSVersion` FROM OSVersion AS `OSVersion` WHERE 1", array('OSVersion.friendlyname' => true), $aArgs),
			"SELECT Organization 64" => array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = :id)", array('Organization.friendlyname' => true), $aArgs),
			"SELECT Organization 65" => array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = :id)", array('Organization.friendlyname' => true), $aArgs),
			"SELECT Organization 66" => array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", array('Organization.friendlyname' => true), $aArgs),
			"SELECT OtherSoftware 67" => array("SELECT `OtherSoftware` FROM OtherSoftware AS `OtherSoftware` WHERE 1", array('OtherSoftware.friendlyname' => true), $aArgs),
			"SELECT PCSoftware 68" => array("SELECT `PCSoftware` FROM PCSoftware AS `PCSoftware` WHERE 1", array('PCSoftware.friendlyname' => true), $aArgs),
			"SELECT PC 69" => array("SELECT `PC` FROM PC AS `PC` WHERE 1", array('PC.friendlyname' => true), $aArgs),
			"SELECT Patch 70" => array("SELECT `Patch` FROM Patch AS `Patch` WHERE 1", array('Patch.friendlyname' => true), $aArgs),
			"SELECT Peripheral 71" => array("SELECT `Peripheral` FROM Peripheral AS `Peripheral` WHERE 1", array('Peripheral.friendlyname' => true), $aArgs),
			"SELECT Person 72" => array("SELECT `Person` FROM Person AS `Person` WHERE ((`Person`.`org_id` = 1) AND (ISNULL(`Person`.`org_id`) != 1))", array('Person.friendlyname' => true), $aArgs),
			"SELECT Person 73" => array("SELECT `Person` FROM Person AS `Person` WHERE ((`Person`.`org_id` = 2) AND (ISNULL(`Person`.`org_id`) != 1))", array('Person.friendlyname' => true), $aArgs),
			"SELECT Person 74" => array("SELECT `Person` FROM Person AS `Person` WHERE (`Person`.`id` = :id)", array('Person.friendlyname' => true), $aArgs),
			"SELECT Person 75" => array("SELECT `Person` FROM Person AS `Person` WHERE 1", array('Person.friendlyname' => true), $aArgs),
			"SELECT Person 76 DISTINCT" => array("SELECT `p` FROM Person AS `p` JOIN UserRequest AS `u` ON `u`.agent_id = `p`.id WHERE (`u`.`status` != 'closed')", array('p.friendlyname' => true), $aArgs),
			"SELECT Phone 76" => array("SELECT `Phone` FROM Phone AS `Phone` WHERE 1", array('Phone.friendlyname' => true), $aArgs),
			"SELECT PowerConnection 77" => array("SELECT `PowerConnection` FROM PowerConnection AS `PowerConnection` WHERE ((`PowerConnection`.`location_id` = :this->location_id) AND (`PowerConnection`.`friendlyname` LIKE '%%'))", array('PowerConnection.friendlyname' => true), $aArgs),
			"SELECT PowerConnection 78" => array("SELECT `PowerConnection` FROM PowerConnection AS `PowerConnection` WHERE (`PowerConnection`.`friendlyname` LIKE '%%')", array('PowerConnection.friendlyname' => true), $aArgs),
			"SELECT PowerConnection 79" => array("SELECT `PowerConnection` FROM PowerConnection AS `PowerConnection` WHERE 1", array('PowerConnection.friendlyname' => true), $aArgs),
			"SELECT Printer 80" => array("SELECT `Printer` FROM Printer AS `Printer` WHERE 1", array('Printer.friendlyname' => true), $aArgs),
			"SELECT ProviderContract 81" => array("SELECT `ProviderContract` FROM ProviderContract AS `ProviderContract` WHERE 1", array('ProviderContract.friendlyname' => true), $aArgs),
			"SELECT Query 82" => array("SELECT `Query` FROM Query AS `Query` WHERE 1", array('Query.friendlyname' => true), $aArgs),
			"SELECT Rack 83" => array("SELECT `Rack` FROM Rack AS `Rack` WHERE ((`Rack`.`location_id` = :this->location_id) AND (`Rack`.`friendlyname` LIKE '%%'))", array('Rack.friendlyname' => true), $aArgs),
			"SELECT Rack 84" => array("SELECT `Rack` FROM Rack AS `Rack` WHERE (`Rack`.`friendlyname` LIKE '%%')", array('Rack.friendlyname' => true), $aArgs),
			"SELECT Rack 85" => array("SELECT `Rack` FROM Rack AS `Rack` WHERE 1", array('Rack.friendlyname' => true), $aArgs),
			"SELECT SANSwitch 86" => array("SELECT `SANSwitch` FROM SANSwitch AS `SANSwitch` WHERE 1", array('SANSwitch.friendlyname' => true), $aArgs),
			"SELECT SLA 87" => array("SELECT `SLA` FROM SLA AS `SLA` WHERE 1", array('SLA.friendlyname' => true), $aArgs),
			"SELECT SLT 88" => array("SELECT `SLT` FROM SLT AS `SLT` WHERE 1", array('SLT.friendlyname' => true), $aArgs),
			"SELECT Server 89" => array("SELECT `Server` FROM Server AS `Server` WHERE (`Server`.`name` NOT LIKE '%2')", array('Server.friendlyname' => true), $aArgs),
			"SELECT Server 90" => array("SELECT `Server` FROM Server AS `Server` WHERE 1", array('Server.friendlyname' => true), $aArgs),
			"SELECT ServiceFamily 91" => array("SELECT `ServiceFamily` FROM ServiceFamily AS `ServiceFamily` WHERE (`ServiceFamily`.`friendlyname` LIKE '%%')", array('ServiceFamily.friendlyname' => true), $aArgs),
			"SELECT ServiceFamily 92" => array("SELECT `ServiceFamily` FROM ServiceFamily AS `ServiceFamily` WHERE 1", array('ServiceFamily.friendlyname' => true), $aArgs),
			"SELECT ServiceSubcategory 93" => array("SELECT `ServiceSubcategory` FROM ServiceSubcategory AS `ServiceSubcategory` WHERE ((((`ServiceSubcategory`.`service_id` = :this->service_id) AND (ISNULL(:this->request_type) OR (`ServiceSubcategory`.`request_type` = :this->request_type))) AND (`ServiceSubcategory`.`status` != 'obsolete')) AND (`ServiceSubcategory`.`friendlyname` LIKE '%%'))", array('ServiceSubcategory.friendlyname' => true), $aArgs),
			"SELECT ServiceSubcategory 94" => array("SELECT `ServiceSubcategory` FROM ServiceSubcategory AS `ServiceSubcategory` WHERE (`ServiceSubcategory`.`friendlyname` LIKE '%%')", array('ServiceSubcategory.friendlyname' => true), $aArgs),
			"SELECT ServiceSubcategory 95" => array("SELECT `ServiceSubcategory` FROM ServiceSubcategory AS `ServiceSubcategory` WHERE 1", array('ServiceSubcategory.friendlyname' => true), $aArgs),
			"SELECT Service 96" => array("SELECT `Service` FROM Service AS `Service` WHERE (`Service`.`friendlyname` LIKE '%%')", array('Service.friendlyname' => true), $aArgs),
			"SELECT Service 97" => array("SELECT `Service` FROM Service AS `Service` WHERE 1", array('Service.friendlyname' => true), $aArgs),
			"SELECT ShortcutOQL 98" => array("SELECT `ShortcutOQL` FROM ShortcutOQL AS `ShortcutOQL` WHERE (`ShortcutOQL`.`id` = :id)", array('ShortcutOQL.friendlyname' => true), $aArgs),
			"SELECT Shortcut 99" => array("SELECT `Shortcut` FROM Shortcut AS `Shortcut` WHERE (`Shortcut`.`user_id` = :user_id)", array('Shortcut.friendlyname' => true), $aArgs),
			"SELECT Software 100" => array("SELECT `Software` FROM Software AS `Software` WHERE 1", array('Software.friendlyname' => true), $aArgs),
			"SELECT StorageSystem 101" => array("SELECT `StorageSystem` FROM StorageSystem AS `StorageSystem` WHERE 1", array('StorageSystem.friendlyname' => true), $aArgs),
			"SELECT Subnet 102" => array("SELECT `Subnet` FROM Subnet AS `Subnet` WHERE 1", array('Subnet.friendlyname' => true), $aArgs),
			"SELECT SynchroDataSource 103" => array("SELECT `SynchroDataSource` FROM SynchroDataSource AS `SynchroDataSource` WHERE 1", array('SynchroDataSource.friendlyname' => true), $aArgs),
			"SELECT Tablet 104" => array("SELECT `Tablet` FROM Tablet AS `Tablet` WHERE 1", array('Tablet.friendlyname' => true), $aArgs),
			"SELECT TapeLibrary 105" => array("SELECT `TapeLibrary` FROM TapeLibrary AS `TapeLibrary` WHERE 1", array('TapeLibrary.friendlyname' => true), $aArgs),
			"SELECT Team 106" => array("SELECT `Team` FROM Team AS `Team` WHERE (`Team`.`friendlyname` LIKE '%%')", array('Team.friendlyname' => true), $aArgs),
			"SELECT Team 107" => array("SELECT `Team` FROM Team AS `Team` WHERE 1", array('Team.friendlyname' => true), $aArgs),
			"SELECT Trigger 108" => array("SELECT `Trigger` FROM Trigger AS `Trigger` WHERE 1", array('Trigger.friendlyname' => true), $aArgs),
			"SELECT URP_Profiles 109" => array("SELECT `URP_Profiles` FROM URP_Profiles AS `URP_Profiles` WHERE 1", array('URP_Profiles.friendlyname' => true), $aArgs),
			"SELECT URP_UserProfile 110" => array("SELECT `URP_UserProfile` FROM URP_UserProfile AS `URP_UserProfile` WHERE (`URP_UserProfile`.`userid` = :userid)", array('URP_UserProfile.friendlyname' => true), $aArgs),
			"SELECT UserDashboard 111" => array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = :user_id) AND (`UserDashboard`.`menu_code` = :menu_code))", array('UserDashboard.friendlyname' => true), $aArgs),
			"SELECT UserInternal 112" => array("SELECT `UserInternal` FROM UserInternal AS `UserInternal` WHERE ((`UserInternal`.`login` = :login) AND (`UserInternal`.`status` = :UserInternal_status))", array('UserInternal.friendlyname' => true), $aArgs),
			"SELECT UserLocal 113" => array("SELECT `UserLocal` FROM UserLocal AS `UserLocal` WHERE (`UserLocal`.`id` = :id)", array('UserLocal.friendlyname' => true), $aArgs),
			"SELECT User 127" => array("SELECT `User` FROM User AS `User` WHERE (`User`.`friendlyname` LIKE '%%')", array('User.friendlyname' => true), $aArgs),
			"SELECT User 128" => array("SELECT `User` FROM User AS `User` WHERE (`User`.`id` = :id)", array('User.friendlyname' => true), $aArgs),
			"SELECT User 129" => array("SELECT `User` FROM User AS `User` WHERE 1", array('User.friendlyname' => true), $aArgs),
			"SELECT VLAN 130" => array("SELECT `VLAN` FROM VLAN AS `VLAN` WHERE 1", array('VLAN.friendlyname' => true), $aArgs),
			"SELECT VirtualMachine 131" => array("SELECT `VirtualMachine` FROM VirtualMachine AS `VirtualMachine` WHERE 1", array('VirtualMachine.friendlyname' => true), $aArgs),
			"SELECT WebApplication 132" => array("SELECT `WebApplication` FROM WebApplication AS `WebApplication` WHERE 1", array('WebApplication.friendlyname' => true), $aArgs),
			"SELECT WebServer 133" => array("SELECT `WebServer` FROM WebServer AS `WebServer` WHERE 1", array('WebServer.friendlyname' => true), $aArgs),
			"SELECT appUserPreferences 134" => array("SELECT `appUserPreferences` FROM appUserPreferences AS `appUserPreferences` WHERE (`appUserPreferences`.`id` = :id)", array('appUserPreferences.friendlyname' => true), $aArgs),
			"SELECT appUserPreferences 135" => array("SELECT `appUserPreferences` FROM appUserPreferences AS `appUserPreferences` WHERE (`appUserPreferences`.`userid` = :userid)", array('appUserPreferences.friendlyname' => true), $aArgs),
			"SELECT c 136" => array("SELECT `c` FROM CustomerContract AS `c` WHERE (`c`.`end_date` < '2018-12-01')", array('c.friendlyname' => true), $aArgs),
			"SELECT c 137" => array("SELECT `c` FROM ProviderContract AS `c` WHERE (`c`.`end_date` < '2018-12-01')", array('c.friendlyname' => true), $aArgs),
			"SELECT datasource 138" => array("SELECT `datasource` FROM SynchroDataSource AS `datasource` WHERE 1", array('datasource.friendlyname' => true), $aArgs),
			"SELECT i 139" => array("SELECT `i` FROM UserRequest AS `i` WHERE ((`i`.`agent_id` = :current_contact_id) AND (`i`.`status` NOT IN ('closed', 'resolved')))", array('i.friendlyname' => true), $aArgs),
			"SELECT s 140" => array("SELECT `s` FROM Service AS `s` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE (((`cc`.`org_id` = :this->org_id) AND (`s`.`status` != 'obsolete')) AND (`s`.`friendlyname` LIKE '%%'))", array('s.friendlyname' => true), $aArgs),
			"SELECT t 141" => array("SELECT `t` FROM Team AS `t` JOIN lnkDeliveryModelToContact AS `l1` ON `l1`.contact_id = `t`.id JOIN DeliveryModel AS `dm` ON `l1`.deliverymodel_id = `dm`.id JOIN Organization AS `o` ON `o`.deliverymodel_id = `dm`.id WHERE ((`o`.`id` = :this->org_id) AND (`t`.`friendlyname` LIKE '%%'))", array('t.friendlyname' => true), $aArgs),
			"SELECT t 142" => array("SELECT `t` FROM TriggerOnObjectCreate AS `t` WHERE (`t`.`target_class` IN ('appUserPreferences'))", array('t.friendlyname' => true), $aArgs),
			"SELECT t 143" => array("SELECT `t` FROM TriggerOnObjectUpdate AS `t` WHERE (`t`.`target_class` IN ('appUserPreferences'))", array('t.friendlyname' => true), $aArgs),
		);

		$aData["SELECT 1"] = array("SELECT `UserInternal` FROM UserInternal AS `UserInternal` WHERE ((`UserInternal`.`login` = 'admin') AND (`UserInternal`.`status` = 'enabled'))", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 2"] = array("SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`id` = '987654321')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 3"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", array(), array(), null, array(), 4, 0);
		$aData["SELECT 4"] = array("SELECT `appUserPreferences` FROM appUserPreferences AS `appUserPreferences` WHERE (`appUserPreferences`.`userid` = '1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 5"] = array("SELECT `Shortcut` FROM Shortcut AS `Shortcut` WHERE (`Shortcut`.`user_id` = '1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 6"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '1') AND (`UserDashboard`.`menu_code` = 'WelcomeMenuPage'))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 7"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '1') AND (`UserDashboard`.`menu_code` = 'WelcomeMenuPage'))", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 8"] = array("SELECT `BusinessProcess` FROM BusinessProcess AS `BusinessProcess` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 9"] = array("SELECT `ApplicationSolution` FROM ApplicationSolution AS `ApplicationSolution` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 10"] = array("SELECT `Contact` FROM Contact AS `Contact` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 11"] = array("SELECT `Location` FROM Location AS `Location` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 12"] = array("SELECT `Contract` FROM Contract AS `Contract` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 13"] = array("SELECT `Server` FROM Server AS `Server` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 14"] = array("SELECT `NetworkDevice` FROM NetworkDevice AS `NetworkDevice` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 15"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` != 'closed')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 16"] = array("SELECT `i` FROM UserRequest AS `i` WHERE ((`i`.`agent_id` = 1) AND (`i`.`status` NOT IN ('closed', 'resolved')))", array(), array(), null, array(), 3, 0);
		$aData["SELECT 17"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 18"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", array(), array(), null, array(), 52, 0);
		$aData["SELECT 19"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = '')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 20"] = array("SELECT `DBProperty` FROM DBProperty AS `DBProperty` WHERE (`DBProperty`.`name` = 'database_uuid')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 21"] = array("SELECT `DBProperty` FROM DBProperty AS `DBProperty` WHERE (`DBProperty`.`name` = 'database_uuid')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 22"] = array("SELECT `WebServer` FROM WebServer AS `WebServer` WHERE 1", array(), array(), unserialize('a:1:{s:9:"WebServer";a:6:{i:0;s:18:"business_criticity";i:1;s:11:"description";i:2;s:4:"name";i:3;s:12:"friendlyname";i:4;s:17:"obsolescence_flag";i:5;s:10:"finalclass";}}'), array(), 0, 0);
		$aData["SELECT 23"] = array("SELECT `WebServer` FROM WebServer AS `WebServer` WHERE 1", array(), array(), unserialize('a:1:{s:9:"WebServer";a:6:{i:0;s:18:"business_criticity";i:1;s:11:"description";i:2;s:4:"name";i:3;s:12:"friendlyname";i:4;s:17:"obsolescence_flag";i:5;s:10:"finalclass";}}'), array(), 0, 3);
		$aData["SELECT 24"] = array("SELECT `WebServer` FROM WebServer AS `WebServer` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 25"] = array("SELECT `WebServer` FROM WebServer AS `WebServer` WHERE 1", array(), array(), null, array(), 0, 3);
		$aData["SELECT 26"] = array("SELECT `L` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1", unserialize('a:1:{s:14:"L.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 27"] = array("SELECT `L` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1", unserialize('a:1:{s:14:"L.friendlyname";b:1;}'), array(), null, array(), 3, 0);
		$aData["SELECT 28"] = array("SELECT `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 29"] = array("SELECT `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, array(), 3, 0);
		$aData["SELECT 30"] = array("SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 31"] = array("SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, array(), 3, 0);
		$aData["SELECT 32"] = array("SELECT `P`, `L` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 33"] = array("SELECT `P`, `L` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, array(), 3, 0);
		$aData["SELECT 34"] = array("SELECT `L`, `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 35"] = array("SELECT `L`, `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", array(), array(), null, array(), 3, 0);
		$aData["SELECT 36"] = array("SELECT `L` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", unserialize('a:1:{s:14:"L.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 37"] = array("SELECT `L` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1", unserialize('a:1:{s:14:"L.friendlyname";b:1;}'), array(), null, array(), 3, 0);
		$aData["SELECT 38"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Organization AS `Organization` ON `UserRequest`.org_id = `Organization`.id JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE (`Organization1`.`id` = '3')", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 39"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Organization AS `Organization` ON `UserRequest`.org_id = `Organization`.id JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE (`Organization1`.`id` = '3')", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 40"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (('2018-12-01' < `UserRequest`.`start_date`) AND (ISNULL(DATE_FORMAT(`UserRequest`.`start_date`, '%Y-%m-%d')) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 41"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (('2018-12-01' < `UserRequest`.`start_date`) AND (ISNULL(DATE_FORMAT(`UserRequest`.`start_date`, '%Y-%m-%d')) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 42"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`agent_id` = '') AND (`UserRequest`.`status` NOT IN ('closed', 'resolved')))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 43"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`agent_id` = '') AND (`UserRequest`.`status` NOT IN ('closed', 'resolved')))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 44"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`caller_id` = '') AND (`UserRequest`.`status` NOT IN ('closed')))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 45"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`caller_id` = '') AND (`UserRequest`.`status` NOT IN ('closed')))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 46"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` IN ('escalated_tto', 'escalated_ttr')) OR (`UserRequest`.`escalation_flag` = 'yes'))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 47"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` IN ('escalated_tto', 'escalated_ttr')) OR (`UserRequest`.`escalation_flag` = 'yes'))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 48"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`agent_id`) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 49"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`agent_id`) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 50"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`finalclass`) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 51"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`finalclass`) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 52"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`org_id`) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 53"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`org_id`) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 54"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`status`) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 55"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` NOT IN ('closed', 'rejected')) AND (ISNULL(`UserRequest`.`status`) != 1))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 56"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (ISNULL(`UserRequest`.`org_id`) != 1)", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 57"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (ISNULL(`UserRequest`.`org_id`) != 1)", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 58"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` != 'closed')", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 59"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` != 'closed')", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 60"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` NOT IN ('closed'))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 61"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` NOT IN ('closed'))", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 62"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE 1", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 63"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE 1", unserialize('a:1:{s:24:"UserRequest.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 64"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`id` = '987654321')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 65"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`id` = '987654321')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 66"] = array("SELECT `ApplicationSolution` FROM ApplicationSolution AS `ApplicationSolution` WHERE 1", unserialize('a:1:{s:32:"ApplicationSolution.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 67"] = array("SELECT `ApplicationSolution` FROM ApplicationSolution AS `ApplicationSolution` WHERE 1", unserialize('a:1:{s:32:"ApplicationSolution.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 68"] = array("SELECT `AuditCategory` FROM AuditCategory AS `AuditCategory` WHERE 1", unserialize('a:1:{s:26:"AuditCategory.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 69"] = array("SELECT `AuditCategory` FROM AuditCategory AS `AuditCategory` WHERE 1", unserialize('a:1:{s:26:"AuditCategory.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 70"] = array("SELECT `Brand` FROM Brand AS `Brand` WHERE (`Brand`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:18:"Brand.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 71"] = array("SELECT `Brand` FROM Brand AS `Brand` WHERE (`Brand`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:18:"Brand.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 72"] = array("SELECT `Brand` FROM Brand AS `Brand` WHERE 1", unserialize('a:1:{s:18:"Brand.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 73"] = array("SELECT `Brand` FROM Brand AS `Brand` WHERE 1", unserialize('a:1:{s:18:"Brand.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 74"] = array("SELECT `BusinessProcess` FROM BusinessProcess AS `BusinessProcess` WHERE 1", unserialize('a:1:{s:28:"BusinessProcess.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 75"] = array("SELECT `BusinessProcess` FROM BusinessProcess AS `BusinessProcess` WHERE 1", unserialize('a:1:{s:28:"BusinessProcess.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 76"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`agent_id` = '') AND (`Change`.`status` NOT IN ('closed')))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 77"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`agent_id` = '') AND (`Change`.`status` NOT IN ('closed')))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 78"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(DATE_FORMAT(`Change`.`start_date`, '%Y-%m-%d')) != 1))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 79"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(DATE_FORMAT(`Change`.`start_date`, '%Y-%m-%d')) != 1))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 80"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(`Change`.`category`) != 1))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 81"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(`Change`.`category`) != 1))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 82"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(`Change`.`finalclass`) != 1))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 83"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(`Change`.`finalclass`) != 1))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 84"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(`Change`.`status`) != 1))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 85"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`creation_date` > '2018-12-01') AND (ISNULL(`Change`.`status`) != 1))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 86"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`id` != '3') AND (`Change`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 87"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`id` != '3') AND (`Change`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 88"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`status` != 'closed') AND (`Change`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 89"] = array("SELECT `Change` FROM Change AS `Change` WHERE ((`Change`.`status` != 'closed') AND (`Change`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 90"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 91"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 92"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`status` != 'closed')", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 93"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`status` != 'closed')", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 94"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`status` IN ('planned'))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 95"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`status` IN ('planned'))", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 96"] = array("SELECT `Change` FROM Change AS `Change` WHERE 1", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 97"] = array("SELECT `Change` FROM Change AS `Change` WHERE 1", unserialize('a:1:{s:19:"Change.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 98"] = array("SELECT `ContactType` FROM ContactType AS `ContactType` WHERE 1", unserialize('a:1:{s:24:"ContactType.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 99"] = array("SELECT `ContactType` FROM ContactType AS `ContactType` WHERE 1", unserialize('a:1:{s:24:"ContactType.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 100"] = array("SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`id` = '3')", unserialize('a:1:{s:20:"Contact.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 101"] = array("SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`id` = '3')", unserialize('a:1:{s:20:"Contact.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 102"] = array("SELECT `Contact` FROM Contact AS `Contact` WHERE 1", unserialize('a:1:{s:20:"Contact.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 103"] = array("SELECT `Contact` FROM Contact AS `Contact` WHERE 1", unserialize('a:1:{s:20:"Contact.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 104"] = array("SELECT `ContractType` FROM ContractType AS `ContractType` WHERE (`ContractType`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:25:"ContractType.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 105"] = array("SELECT `ContractType` FROM ContractType AS `ContractType` WHERE (`ContractType`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:25:"ContractType.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 106"] = array("SELECT `ContractType` FROM ContractType AS `ContractType` WHERE 1", unserialize('a:1:{s:25:"ContractType.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 107"] = array("SELECT `ContractType` FROM ContractType AS `ContractType` WHERE 1", unserialize('a:1:{s:25:"ContractType.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 108"] = array("SELECT `Contract` FROM Contract AS `Contract` WHERE 1", unserialize('a:1:{s:21:"Contract.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 109"] = array("SELECT `Contract` FROM Contract AS `Contract` WHERE 1", unserialize('a:1:{s:21:"Contract.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 110"] = array("SELECT `CustomerContract` FROM CustomerContract AS `CustomerContract` WHERE 1", unserialize('a:1:{s:29:"CustomerContract.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 111"] = array("SELECT `CustomerContract` FROM CustomerContract AS `CustomerContract` WHERE 1", unserialize('a:1:{s:29:"CustomerContract.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 112"] = array("SELECT `DBProperty` FROM DBProperty AS `DBProperty` WHERE (`DBProperty`.`name` = 'database_uuid')", unserialize('a:1:{s:23:"DBProperty.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 113"] = array("SELECT `DBProperty` FROM DBProperty AS `DBProperty` WHERE (`DBProperty`.`name` = 'database_uuid')", unserialize('a:1:{s:23:"DBProperty.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 114"] = array("SELECT `DBServer` FROM DBServer AS `DBServer` WHERE 1", unserialize('a:1:{s:21:"DBServer.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 115"] = array("SELECT `DBServer` FROM DBServer AS `DBServer` WHERE 1", unserialize('a:1:{s:21:"DBServer.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 116"] = array("SELECT `DatabaseSchema` FROM DatabaseSchema AS `DatabaseSchema` WHERE 1", unserialize('a:1:{s:27:"DatabaseSchema.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 117"] = array("SELECT `DatabaseSchema` FROM DatabaseSchema AS `DatabaseSchema` WHERE 1", unserialize('a:1:{s:27:"DatabaseSchema.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 118"] = array("SELECT `DeliveryModel` FROM DeliveryModel AS `DeliveryModel` WHERE (`DeliveryModel`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:26:"DeliveryModel.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 119"] = array("SELECT `DeliveryModel` FROM DeliveryModel AS `DeliveryModel` WHERE (`DeliveryModel`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:26:"DeliveryModel.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 120"] = array("SELECT `DeliveryModel` FROM DeliveryModel AS `DeliveryModel` WHERE 1", unserialize('a:1:{s:26:"DeliveryModel.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 121"] = array("SELECT `DeliveryModel` FROM DeliveryModel AS `DeliveryModel` WHERE 1", unserialize('a:1:{s:26:"DeliveryModel.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 122"] = array("SELECT `DocumentType` FROM DocumentType AS `DocumentType` WHERE (`DocumentType`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:25:"DocumentType.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 123"] = array("SELECT `DocumentType` FROM DocumentType AS `DocumentType` WHERE (`DocumentType`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:25:"DocumentType.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 124"] = array("SELECT `DocumentType` FROM DocumentType AS `DocumentType` WHERE 1", unserialize('a:1:{s:25:"DocumentType.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 125"] = array("SELECT `DocumentType` FROM DocumentType AS `DocumentType` WHERE 1", unserialize('a:1:{s:25:"DocumentType.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 126"] = array("SELECT `Document` FROM Document AS `Document` WHERE 1", unserialize('a:1:{s:21:"Document.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 127"] = array("SELECT `Document` FROM Document AS `Document` WHERE 1", unserialize('a:1:{s:21:"Document.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 128"] = array("SELECT `Enclosure` FROM Enclosure AS `Enclosure` WHERE ((`Enclosure`.`rack_id` = '3') AND (`Enclosure`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:22:"Enclosure.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 129"] = array("SELECT `Enclosure` FROM Enclosure AS `Enclosure` WHERE ((`Enclosure`.`rack_id` = '3') AND (`Enclosure`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:22:"Enclosure.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 130"] = array("SELECT `Enclosure` FROM Enclosure AS `Enclosure` WHERE (`Enclosure`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:22:"Enclosure.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 131"] = array("SELECT `Enclosure` FROM Enclosure AS `Enclosure` WHERE (`Enclosure`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:22:"Enclosure.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 132"] = array("SELECT `Enclosure` FROM Enclosure AS `Enclosure` WHERE 1", unserialize('a:1:{s:22:"Enclosure.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 133"] = array("SELECT `Enclosure` FROM Enclosure AS `Enclosure` WHERE 1", unserialize('a:1:{s:22:"Enclosure.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 134"] = array("SELECT `Farm` FROM Farm AS `Farm` WHERE 1", unserialize('a:1:{s:17:"Farm.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 135"] = array("SELECT `Farm` FROM Farm AS `Farm` WHERE 1", unserialize('a:1:{s:17:"Farm.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 136"] = array("SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE 1", unserialize('a:1:{s:25:"FunctionalCI.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 137"] = array("SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE 1", unserialize('a:1:{s:25:"FunctionalCI.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 138"] = array("SELECT `Group` FROM Group AS `Group` WHERE (`Group`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:18:"Group.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 139"] = array("SELECT `Group` FROM Group AS `Group` WHERE (`Group`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:18:"Group.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 140"] = array("SELECT `Group` FROM Group AS `Group` WHERE 1", unserialize('a:1:{s:18:"Group.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 141"] = array("SELECT `Group` FROM Group AS `Group` WHERE 1", unserialize('a:1:{s:18:"Group.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 142"] = array("SELECT `Hypervisor` FROM Hypervisor AS `Hypervisor` WHERE 1", unserialize('a:1:{s:23:"Hypervisor.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 143"] = array("SELECT `Hypervisor` FROM Hypervisor AS `Hypervisor` WHERE 1", unserialize('a:1:{s:23:"Hypervisor.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 144"] = array("SELECT `IOSVersion` FROM IOSVersion AS `IOSVersion` WHERE 1", unserialize('a:1:{s:23:"IOSVersion.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 145"] = array("SELECT `IOSVersion` FROM IOSVersion AS `IOSVersion` WHERE 1", unserialize('a:1:{s:23:"IOSVersion.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 146"] = array("SELECT `IPPhone` FROM IPPhone AS `IPPhone` WHERE 1", unserialize('a:1:{s:20:"IPPhone.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 147"] = array("SELECT `IPPhone` FROM IPPhone AS `IPPhone` WHERE 1", unserialize('a:1:{s:20:"IPPhone.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 148"] = array("SELECT `Licence` FROM Licence AS `Licence` WHERE 1", unserialize('a:1:{s:20:"Licence.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 149"] = array("SELECT `Licence` FROM Licence AS `Licence` WHERE 1", unserialize('a:1:{s:20:"Licence.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 150"] = array("SELECT `Location` FROM Location AS `Location` WHERE 1", unserialize('a:1:{s:21:"Location.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 151"] = array("SELECT `Location` FROM Location AS `Location` WHERE 1", unserialize('a:1:{s:21:"Location.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 152"] = array("SELECT `LogicalVolume` FROM LogicalVolume AS `LogicalVolume` WHERE 1", unserialize('a:1:{s:26:"LogicalVolume.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 153"] = array("SELECT `LogicalVolume` FROM LogicalVolume AS `LogicalVolume` WHERE 1", unserialize('a:1:{s:26:"LogicalVolume.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 154"] = array("SELECT `MiddlewareInstance` FROM MiddlewareInstance AS `MiddlewareInstance` WHERE 1", unserialize('a:1:{s:31:"MiddlewareInstance.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 155"] = array("SELECT `MiddlewareInstance` FROM MiddlewareInstance AS `MiddlewareInstance` WHERE 1", unserialize('a:1:{s:31:"MiddlewareInstance.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 156"] = array("SELECT `Middleware` FROM Middleware AS `Middleware` WHERE 1", unserialize('a:1:{s:23:"Middleware.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 157"] = array("SELECT `Middleware` FROM Middleware AS `Middleware` WHERE 1", unserialize('a:1:{s:23:"Middleware.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 158"] = array("SELECT `MobilePhone` FROM MobilePhone AS `MobilePhone` WHERE 1", unserialize('a:1:{s:24:"MobilePhone.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 159"] = array("SELECT `MobilePhone` FROM MobilePhone AS `MobilePhone` WHERE 1", unserialize('a:1:{s:24:"MobilePhone.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 160"] = array("SELECT `Model` FROM Model AS `Model` WHERE (((`Model`.`brand_id` = '1') AND (`Model`.`type` = 'NetworkDevice')) AND (`Model`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:18:"Model.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 161"] = array("SELECT `Model` FROM Model AS `Model` WHERE (((`Model`.`brand_id` = '1') AND (`Model`.`type` = 'NetworkDevice')) AND (`Model`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:18:"Model.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 162"] = array("SELECT `Model` FROM Model AS `Model` WHERE (`Model`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:18:"Model.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 163"] = array("SELECT `Model` FROM Model AS `Model` WHERE (`Model`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:18:"Model.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 164"] = array("SELECT `Model` FROM Model AS `Model` WHERE 1", unserialize('a:1:{s:18:"Model.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 165"] = array("SELECT `Model` FROM Model AS `Model` WHERE 1", unserialize('a:1:{s:18:"Model.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 166"] = array("SELECT `NAS` FROM NAS AS `NAS` WHERE 1", unserialize('a:1:{s:16:"NAS.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 167"] = array("SELECT `NAS` FROM NAS AS `NAS` WHERE 1", unserialize('a:1:{s:16:"NAS.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 168"] = array("SELECT `NetworkDeviceType` FROM NetworkDeviceType AS `NetworkDeviceType` WHERE 1", unserialize('a:1:{s:30:"NetworkDeviceType.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 169"] = array("SELECT `NetworkDeviceType` FROM NetworkDeviceType AS `NetworkDeviceType` WHERE 1", unserialize('a:1:{s:30:"NetworkDeviceType.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 170"] = array("SELECT `NetworkDevice` FROM NetworkDevice AS `NetworkDevice` WHERE 1", unserialize('a:1:{s:26:"NetworkDevice.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 171"] = array("SELECT `NetworkDevice` FROM NetworkDevice AS `NetworkDevice` WHERE 1", unserialize('a:1:{s:26:"NetworkDevice.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 172"] = array("SELECT `NetworkInterface` FROM NetworkInterface AS `NetworkInterface` WHERE 1", unserialize('a:1:{s:29:"NetworkInterface.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 173"] = array("SELECT `NetworkInterface` FROM NetworkInterface AS `NetworkInterface` WHERE 1", unserialize('a:1:{s:29:"NetworkInterface.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 174"] = array("SELECT `OSFamily` FROM OSFamily AS `OSFamily` WHERE (`OSFamily`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:21:"OSFamily.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 175"] = array("SELECT `OSFamily` FROM OSFamily AS `OSFamily` WHERE (`OSFamily`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:21:"OSFamily.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 176"] = array("SELECT `OSFamily` FROM OSFamily AS `OSFamily` WHERE 1", unserialize('a:1:{s:21:"OSFamily.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 177"] = array("SELECT `OSFamily` FROM OSFamily AS `OSFamily` WHERE 1", unserialize('a:1:{s:21:"OSFamily.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 178"] = array("SELECT `OSLicence` FROM OSLicence AS `OSLicence` WHERE ((`OSLicence`.`osversion_id` = '8') AND (`OSLicence`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:22:"OSLicence.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 179"] = array("SELECT `OSLicence` FROM OSLicence AS `OSLicence` WHERE ((`OSLicence`.`osversion_id` = '8') AND (`OSLicence`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:22:"OSLicence.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 180"] = array("SELECT `OSLicence` FROM OSLicence AS `OSLicence` WHERE (`OSLicence`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:22:"OSLicence.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 181"] = array("SELECT `OSLicence` FROM OSLicence AS `OSLicence` WHERE (`OSLicence`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:22:"OSLicence.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 182"] = array("SELECT `OSLicence` FROM OSLicence AS `OSLicence` WHERE 1", unserialize('a:1:{s:22:"OSLicence.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 183"] = array("SELECT `OSLicence` FROM OSLicence AS `OSLicence` WHERE 1", unserialize('a:1:{s:22:"OSLicence.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 184"] = array("SELECT `OSVersion` FROM OSVersion AS `OSVersion` WHERE ((`OSVersion`.`osfamily_id` = '6') AND (`OSVersion`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:22:"OSVersion.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 185"] = array("SELECT `OSVersion` FROM OSVersion AS `OSVersion` WHERE ((`OSVersion`.`osfamily_id` = '6') AND (`OSVersion`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:22:"OSVersion.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 186"] = array("SELECT `OSVersion` FROM OSVersion AS `OSVersion` WHERE (`OSVersion`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:22:"OSVersion.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 187"] = array("SELECT `OSVersion` FROM OSVersion AS `OSVersion` WHERE (`OSVersion`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:22:"OSVersion.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 188"] = array("SELECT `OSVersion` FROM OSVersion AS `OSVersion` WHERE 1", unserialize('a:1:{s:22:"OSVersion.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 189"] = array("SELECT `OSVersion` FROM OSVersion AS `OSVersion` WHERE 1", unserialize('a:1:{s:22:"OSVersion.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 190"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = '3')", unserialize('a:1:{s:25:"Organization.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 191"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = '3')", unserialize('a:1:{s:25:"Organization.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 192"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", unserialize('a:1:{s:25:"Organization.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 193"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", unserialize('a:1:{s:25:"Organization.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 194"] = array("SELECT `OtherSoftware` FROM OtherSoftware AS `OtherSoftware` WHERE 1", unserialize('a:1:{s:26:"OtherSoftware.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 195"] = array("SELECT `OtherSoftware` FROM OtherSoftware AS `OtherSoftware` WHERE 1", unserialize('a:1:{s:26:"OtherSoftware.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 196"] = array("SELECT `PCSoftware` FROM PCSoftware AS `PCSoftware` WHERE 1", unserialize('a:1:{s:23:"PCSoftware.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 197"] = array("SELECT `PCSoftware` FROM PCSoftware AS `PCSoftware` WHERE 1", unserialize('a:1:{s:23:"PCSoftware.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 198"] = array("SELECT `PC` FROM PC AS `PC` WHERE 1", unserialize('a:1:{s:15:"PC.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 199"] = array("SELECT `PC` FROM PC AS `PC` WHERE 1", unserialize('a:1:{s:15:"PC.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 200"] = array("SELECT `Patch` FROM Patch AS `Patch` WHERE 1", unserialize('a:1:{s:18:"Patch.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 201"] = array("SELECT `Patch` FROM Patch AS `Patch` WHERE 1", unserialize('a:1:{s:18:"Patch.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 202"] = array("SELECT `Peripheral` FROM Peripheral AS `Peripheral` WHERE 1", unserialize('a:1:{s:23:"Peripheral.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 203"] = array("SELECT `Peripheral` FROM Peripheral AS `Peripheral` WHERE 1", unserialize('a:1:{s:23:"Peripheral.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 204"] = array("SELECT `Person` FROM Person AS `Person` WHERE ((`Person`.`org_id` = 1) AND (ISNULL(`Person`.`org_id`) != 1))", unserialize('a:1:{s:19:"Person.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 205"] = array("SELECT `Person` FROM Person AS `Person` WHERE ((`Person`.`org_id` = 1) AND (ISNULL(`Person`.`org_id`) != 1))", unserialize('a:1:{s:19:"Person.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 206"] = array("SELECT `Person` FROM Person AS `Person` WHERE ((`Person`.`org_id` = 2) AND (ISNULL(`Person`.`org_id`) != 1))", unserialize('a:1:{s:19:"Person.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 207"] = array("SELECT `Person` FROM Person AS `Person` WHERE ((`Person`.`org_id` = 2) AND (ISNULL(`Person`.`org_id`) != 1))", unserialize('a:1:{s:19:"Person.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 208"] = array("SELECT `Person` FROM Person AS `Person` WHERE (`Person`.`id` = '3')", unserialize('a:1:{s:19:"Person.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 209"] = array("SELECT `Person` FROM Person AS `Person` WHERE (`Person`.`id` = '3')", unserialize('a:1:{s:19:"Person.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 210"] = array("SELECT `Person` FROM Person AS `Person` WHERE 1", unserialize('a:1:{s:19:"Person.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 211"] = array("SELECT `Person` FROM Person AS `Person` WHERE 1", unserialize('a:1:{s:19:"Person.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 212"] = array("SELECT `p` FROM Person AS `p` JOIN UserRequest AS `u` ON `u`.agent_id = `p`.id WHERE (`u`.`status` != 'closed')", unserialize('a:1:{s:14:"p.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 213"] = array("SELECT `p` FROM Person AS `p` JOIN UserRequest AS `u` ON `u`.agent_id = `p`.id WHERE (`u`.`status` != 'closed')", unserialize('a:1:{s:14:"p.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 214"] = array("SELECT `Phone` FROM Phone AS `Phone` WHERE 1", unserialize('a:1:{s:18:"Phone.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 215"] = array("SELECT `Phone` FROM Phone AS `Phone` WHERE 1", unserialize('a:1:{s:18:"Phone.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 216"] = array("SELECT `PowerConnection` FROM PowerConnection AS `PowerConnection` WHERE ((`PowerConnection`.`location_id` = '2') AND (`PowerConnection`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:28:"PowerConnection.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 217"] = array("SELECT `PowerConnection` FROM PowerConnection AS `PowerConnection` WHERE ((`PowerConnection`.`location_id` = '2') AND (`PowerConnection`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:28:"PowerConnection.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 218"] = array("SELECT `PowerConnection` FROM PowerConnection AS `PowerConnection` WHERE (`PowerConnection`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:28:"PowerConnection.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 219"] = array("SELECT `PowerConnection` FROM PowerConnection AS `PowerConnection` WHERE (`PowerConnection`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:28:"PowerConnection.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 220"] = array("SELECT `PowerConnection` FROM PowerConnection AS `PowerConnection` WHERE 1", unserialize('a:1:{s:28:"PowerConnection.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 221"] = array("SELECT `PowerConnection` FROM PowerConnection AS `PowerConnection` WHERE 1", unserialize('a:1:{s:28:"PowerConnection.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 222"] = array("SELECT `Printer` FROM Printer AS `Printer` WHERE 1", unserialize('a:1:{s:20:"Printer.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 223"] = array("SELECT `Printer` FROM Printer AS `Printer` WHERE 1", unserialize('a:1:{s:20:"Printer.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 224"] = array("SELECT `ProviderContract` FROM ProviderContract AS `ProviderContract` WHERE 1", unserialize('a:1:{s:29:"ProviderContract.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 225"] = array("SELECT `ProviderContract` FROM ProviderContract AS `ProviderContract` WHERE 1", unserialize('a:1:{s:29:"ProviderContract.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 226"] = array("SELECT `Query` FROM Query AS `Query` WHERE 1", unserialize('a:1:{s:18:"Query.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 227"] = array("SELECT `Query` FROM Query AS `Query` WHERE 1", unserialize('a:1:{s:18:"Query.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 228"] = array("SELECT `Rack` FROM Rack AS `Rack` WHERE ((`Rack`.`location_id` = '2') AND (`Rack`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:17:"Rack.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 229"] = array("SELECT `Rack` FROM Rack AS `Rack` WHERE ((`Rack`.`location_id` = '2') AND (`Rack`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:17:"Rack.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 230"] = array("SELECT `Rack` FROM Rack AS `Rack` WHERE (`Rack`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:17:"Rack.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 231"] = array("SELECT `Rack` FROM Rack AS `Rack` WHERE (`Rack`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:17:"Rack.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 232"] = array("SELECT `Rack` FROM Rack AS `Rack` WHERE 1", unserialize('a:1:{s:17:"Rack.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 233"] = array("SELECT `Rack` FROM Rack AS `Rack` WHERE 1", unserialize('a:1:{s:17:"Rack.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 234"] = array("SELECT `SANSwitch` FROM SANSwitch AS `SANSwitch` WHERE 1", unserialize('a:1:{s:22:"SANSwitch.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 235"] = array("SELECT `SANSwitch` FROM SANSwitch AS `SANSwitch` WHERE 1", unserialize('a:1:{s:22:"SANSwitch.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 236"] = array("SELECT `SLA` FROM SLA AS `SLA` WHERE 1", unserialize('a:1:{s:16:"SLA.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 237"] = array("SELECT `SLA` FROM SLA AS `SLA` WHERE 1", unserialize('a:1:{s:16:"SLA.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 238"] = array("SELECT `SLT` FROM SLT AS `SLT` WHERE 1", unserialize('a:1:{s:16:"SLT.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 239"] = array("SELECT `SLT` FROM SLT AS `SLT` WHERE 1", unserialize('a:1:{s:16:"SLT.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 240"] = array("SELECT `Server` FROM Server AS `Server` WHERE (`Server`.`name` NOT LIKE '%2')", unserialize('a:1:{s:19:"Server.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 241"] = array("SELECT `Server` FROM Server AS `Server` WHERE (`Server`.`name` NOT LIKE '%2')", unserialize('a:1:{s:19:"Server.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 242"] = array("SELECT `Server` FROM Server AS `Server` WHERE 1", unserialize('a:1:{s:19:"Server.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 243"] = array("SELECT `Server` FROM Server AS `Server` WHERE 1", unserialize('a:1:{s:19:"Server.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 244"] = array("SELECT `ServiceFamily` FROM ServiceFamily AS `ServiceFamily` WHERE (`ServiceFamily`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:26:"ServiceFamily.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 245"] = array("SELECT `ServiceFamily` FROM ServiceFamily AS `ServiceFamily` WHERE (`ServiceFamily`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:26:"ServiceFamily.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 246"] = array("SELECT `ServiceFamily` FROM ServiceFamily AS `ServiceFamily` WHERE 1", unserialize('a:1:{s:26:"ServiceFamily.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 247"] = array("SELECT `ServiceFamily` FROM ServiceFamily AS `ServiceFamily` WHERE 1", unserialize('a:1:{s:26:"ServiceFamily.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 248"] = array("SELECT `ServiceSubcategory` FROM ServiceSubcategory AS `ServiceSubcategory` WHERE ((((`ServiceSubcategory`.`service_id` = '1') AND (ISNULL('incident') OR (`ServiceSubcategory`.`request_type` = 'incident'))) AND (`ServiceSubcategory`.`status` != 'obsolete')) AND (`ServiceSubcategory`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:31:"ServiceSubcategory.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 249"] = array("SELECT `ServiceSubcategory` FROM ServiceSubcategory AS `ServiceSubcategory` WHERE ((((`ServiceSubcategory`.`service_id` = '1') AND (ISNULL('incident') OR (`ServiceSubcategory`.`request_type` = 'incident'))) AND (`ServiceSubcategory`.`status` != 'obsolete')) AND (`ServiceSubcategory`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:31:"ServiceSubcategory.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 250"] = array("SELECT `ServiceSubcategory` FROM ServiceSubcategory AS `ServiceSubcategory` WHERE (`ServiceSubcategory`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:31:"ServiceSubcategory.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 251"] = array("SELECT `ServiceSubcategory` FROM ServiceSubcategory AS `ServiceSubcategory` WHERE (`ServiceSubcategory`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:31:"ServiceSubcategory.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 252"] = array("SELECT `ServiceSubcategory` FROM ServiceSubcategory AS `ServiceSubcategory` WHERE 1", unserialize('a:1:{s:31:"ServiceSubcategory.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 253"] = array("SELECT `ServiceSubcategory` FROM ServiceSubcategory AS `ServiceSubcategory` WHERE 1", unserialize('a:1:{s:31:"ServiceSubcategory.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 254"] = array("SELECT `Service` FROM Service AS `Service` WHERE (`Service`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:20:"Service.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 255"] = array("SELECT `Service` FROM Service AS `Service` WHERE (`Service`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:20:"Service.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 256"] = array("SELECT `Service` FROM Service AS `Service` WHERE 1", unserialize('a:1:{s:20:"Service.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 257"] = array("SELECT `Service` FROM Service AS `Service` WHERE 1", unserialize('a:1:{s:20:"Service.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 258"] = array("SELECT `ShortcutOQL` FROM ShortcutOQL AS `ShortcutOQL` WHERE (`ShortcutOQL`.`id` = '3')", unserialize('a:1:{s:24:"ShortcutOQL.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 259"] = array("SELECT `ShortcutOQL` FROM ShortcutOQL AS `ShortcutOQL` WHERE (`ShortcutOQL`.`id` = '3')", unserialize('a:1:{s:24:"ShortcutOQL.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 260"] = array("SELECT `Shortcut` FROM Shortcut AS `Shortcut` WHERE (`Shortcut`.`user_id` = '5')", unserialize('a:1:{s:21:"Shortcut.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 261"] = array("SELECT `Shortcut` FROM Shortcut AS `Shortcut` WHERE (`Shortcut`.`user_id` = '5')", unserialize('a:1:{s:21:"Shortcut.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 262"] = array("SELECT `Software` FROM Software AS `Software` WHERE 1", unserialize('a:1:{s:21:"Software.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 263"] = array("SELECT `Software` FROM Software AS `Software` WHERE 1", unserialize('a:1:{s:21:"Software.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 264"] = array("SELECT `StorageSystem` FROM StorageSystem AS `StorageSystem` WHERE 1", unserialize('a:1:{s:26:"StorageSystem.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 265"] = array("SELECT `StorageSystem` FROM StorageSystem AS `StorageSystem` WHERE 1", unserialize('a:1:{s:26:"StorageSystem.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 266"] = array("SELECT `Subnet` FROM Subnet AS `Subnet` WHERE 1", unserialize('a:1:{s:19:"Subnet.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 267"] = array("SELECT `Subnet` FROM Subnet AS `Subnet` WHERE 1", unserialize('a:1:{s:19:"Subnet.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 268"] = array("SELECT `SynchroDataSource` FROM SynchroDataSource AS `SynchroDataSource` WHERE 1", unserialize('a:1:{s:30:"SynchroDataSource.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 269"] = array("SELECT `SynchroDataSource` FROM SynchroDataSource AS `SynchroDataSource` WHERE 1", unserialize('a:1:{s:30:"SynchroDataSource.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 270"] = array("SELECT `Tablet` FROM Tablet AS `Tablet` WHERE 1", unserialize('a:1:{s:19:"Tablet.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 271"] = array("SELECT `Tablet` FROM Tablet AS `Tablet` WHERE 1", unserialize('a:1:{s:19:"Tablet.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 272"] = array("SELECT `TapeLibrary` FROM TapeLibrary AS `TapeLibrary` WHERE 1", unserialize('a:1:{s:24:"TapeLibrary.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 273"] = array("SELECT `TapeLibrary` FROM TapeLibrary AS `TapeLibrary` WHERE 1", unserialize('a:1:{s:24:"TapeLibrary.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 274"] = array("SELECT `Team` FROM Team AS `Team` WHERE (`Team`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:17:"Team.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 275"] = array("SELECT `Team` FROM Team AS `Team` WHERE (`Team`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:17:"Team.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 276"] = array("SELECT `Team` FROM Team AS `Team` WHERE 1", unserialize('a:1:{s:17:"Team.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 277"] = array("SELECT `Team` FROM Team AS `Team` WHERE 1", unserialize('a:1:{s:17:"Team.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 278"] = array("SELECT `Trigger` FROM Trigger AS `Trigger` WHERE 1", unserialize('a:1:{s:20:"Trigger.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 279"] = array("SELECT `Trigger` FROM Trigger AS `Trigger` WHERE 1", unserialize('a:1:{s:20:"Trigger.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 280"] = array("SELECT `URP_Profiles` FROM URP_Profiles AS `URP_Profiles` WHERE 1", unserialize('a:1:{s:25:"URP_Profiles.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 281"] = array("SELECT `URP_Profiles` FROM URP_Profiles AS `URP_Profiles` WHERE 1", unserialize('a:1:{s:25:"URP_Profiles.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 282"] = array("SELECT `URP_UserProfile` FROM URP_UserProfile AS `URP_UserProfile` WHERE (`URP_UserProfile`.`userid` = '5')", unserialize('a:1:{s:28:"URP_UserProfile.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 283"] = array("SELECT `URP_UserProfile` FROM URP_UserProfile AS `URP_UserProfile` WHERE (`URP_UserProfile`.`userid` = '5')", unserialize('a:1:{s:28:"URP_UserProfile.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 284"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '5') AND (`UserDashboard`.`menu_code` = 'WelcomeMenuPage'))", unserialize('a:1:{s:26:"UserDashboard.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 285"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '5') AND (`UserDashboard`.`menu_code` = 'WelcomeMenuPage'))", unserialize('a:1:{s:26:"UserDashboard.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 286"] = array("SELECT `UserInternal` FROM UserInternal AS `UserInternal` WHERE ((`UserInternal`.`login` = 'admin') AND (`UserInternal`.`status` = 'active'))", unserialize('a:1:{s:25:"UserInternal.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 287"] = array("SELECT `UserInternal` FROM UserInternal AS `UserInternal` WHERE ((`UserInternal`.`login` = 'admin') AND (`UserInternal`.`status` = 'active'))", unserialize('a:1:{s:25:"UserInternal.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 288"] = array("SELECT `UserLocal` FROM UserLocal AS `UserLocal` WHERE (`UserLocal`.`id` = '3')", unserialize('a:1:{s:22:"UserLocal.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 289"] = array("SELECT `UserLocal` FROM UserLocal AS `UserLocal` WHERE (`UserLocal`.`id` = '3')", unserialize('a:1:{s:22:"UserLocal.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 290"] = array("SELECT `User` FROM User AS `User` WHERE (`User`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:17:"User.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 291"] = array("SELECT `User` FROM User AS `User` WHERE (`User`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:17:"User.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 292"] = array("SELECT `User` FROM User AS `User` WHERE (`User`.`id` = '3')", unserialize('a:1:{s:17:"User.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 293"] = array("SELECT `User` FROM User AS `User` WHERE (`User`.`id` = '3')", unserialize('a:1:{s:17:"User.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 294"] = array("SELECT `User` FROM User AS `User` WHERE 1", unserialize('a:1:{s:17:"User.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 295"] = array("SELECT `User` FROM User AS `User` WHERE 1", unserialize('a:1:{s:17:"User.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 296"] = array("SELECT `VLAN` FROM VLAN AS `VLAN` WHERE 1", unserialize('a:1:{s:17:"VLAN.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 297"] = array("SELECT `VLAN` FROM VLAN AS `VLAN` WHERE 1", unserialize('a:1:{s:17:"VLAN.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 298"] = array("SELECT `VirtualMachine` FROM VirtualMachine AS `VirtualMachine` WHERE 1", unserialize('a:1:{s:27:"VirtualMachine.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 299"] = array("SELECT `VirtualMachine` FROM VirtualMachine AS `VirtualMachine` WHERE 1", unserialize('a:1:{s:27:"VirtualMachine.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 300"] = array("SELECT `WebApplication` FROM WebApplication AS `WebApplication` WHERE 1", unserialize('a:1:{s:27:"WebApplication.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 301"] = array("SELECT `WebApplication` FROM WebApplication AS `WebApplication` WHERE 1", unserialize('a:1:{s:27:"WebApplication.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 302"] = array("SELECT `WebServer` FROM WebServer AS `WebServer` WHERE 1", unserialize('a:1:{s:22:"WebServer.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 303"] = array("SELECT `WebServer` FROM WebServer AS `WebServer` WHERE 1", unserialize('a:1:{s:22:"WebServer.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 304"] = array("SELECT `appUserPreferences` FROM appUserPreferences AS `appUserPreferences` WHERE (`appUserPreferences`.`id` = '3')", unserialize('a:1:{s:31:"appUserPreferences.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 305"] = array("SELECT `appUserPreferences` FROM appUserPreferences AS `appUserPreferences` WHERE (`appUserPreferences`.`id` = '3')", unserialize('a:1:{s:31:"appUserPreferences.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 306"] = array("SELECT `appUserPreferences` FROM appUserPreferences AS `appUserPreferences` WHERE (`appUserPreferences`.`userid` = '5')", unserialize('a:1:{s:31:"appUserPreferences.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 307"] = array("SELECT `appUserPreferences` FROM appUserPreferences AS `appUserPreferences` WHERE (`appUserPreferences`.`userid` = '5')", unserialize('a:1:{s:31:"appUserPreferences.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 308"] = array("SELECT `c` FROM CustomerContract AS `c` WHERE (`c`.`end_date` < '2018-12-01')", unserialize('a:1:{s:14:"c.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 309"] = array("SELECT `c` FROM CustomerContract AS `c` WHERE (`c`.`end_date` < '2018-12-01')", unserialize('a:1:{s:14:"c.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 310"] = array("SELECT `c` FROM ProviderContract AS `c` WHERE (`c`.`end_date` < '2018-12-01')", unserialize('a:1:{s:14:"c.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 311"] = array("SELECT `c` FROM ProviderContract AS `c` WHERE (`c`.`end_date` < '2018-12-01')", unserialize('a:1:{s:14:"c.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 312"] = array("SELECT `datasource` FROM SynchroDataSource AS `datasource` WHERE 1", unserialize('a:1:{s:23:"datasource.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 313"] = array("SELECT `datasource` FROM SynchroDataSource AS `datasource` WHERE 1", unserialize('a:1:{s:23:"datasource.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 314"] = array("SELECT `i` FROM UserRequest AS `i` WHERE ((`i`.`agent_id` = '') AND (`i`.`status` NOT IN ('closed', 'resolved')))", unserialize('a:1:{s:14:"i.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 315"] = array("SELECT `i` FROM UserRequest AS `i` WHERE ((`i`.`agent_id` = '') AND (`i`.`status` NOT IN ('closed', 'resolved')))", unserialize('a:1:{s:14:"i.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 316"] = array("SELECT `s` FROM Service AS `s` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE (((`cc`.`org_id` = '3') AND (`s`.`status` != 'obsolete')) AND (`s`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:14:"s.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 317"] = array("SELECT `s` FROM Service AS `s` JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE (((`cc`.`org_id` = '3') AND (`s`.`status` != 'obsolete')) AND (`s`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:14:"s.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 318"] = array("SELECT `t` FROM Team AS `t` JOIN lnkDeliveryModelToContact AS `l1` ON `l1`.contact_id = `t`.id JOIN DeliveryModel AS `dm` ON `l1`.deliverymodel_id = `dm`.id JOIN Organization AS `o` ON `o`.deliverymodel_id = `dm`.id WHERE ((`o`.`id` = '3') AND (`t`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:14:"t.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 319"] = array("SELECT `t` FROM Team AS `t` JOIN lnkDeliveryModelToContact AS `l1` ON `l1`.contact_id = `t`.id JOIN DeliveryModel AS `dm` ON `l1`.deliverymodel_id = `dm`.id JOIN Organization AS `o` ON `o`.deliverymodel_id = `dm`.id WHERE ((`o`.`id` = '3') AND (`t`.`friendlyname` LIKE '%%'))", unserialize('a:1:{s:14:"t.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 320"] = array("SELECT `t` FROM TriggerOnObjectCreate AS `t` WHERE (`t`.`target_class` IN ('appUserPreferences'))", unserialize('a:1:{s:14:"t.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 321"] = array("SELECT `t` FROM TriggerOnObjectCreate AS `t` WHERE (`t`.`target_class` IN ('appUserPreferences'))", unserialize('a:1:{s:14:"t.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 322"] = array("SELECT `t` FROM TriggerOnObjectUpdate AS `t` WHERE (`t`.`target_class` IN ('appUserPreferences'))", unserialize('a:1:{s:14:"t.friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 323"] = array("SELECT `t` FROM TriggerOnObjectUpdate AS `t` WHERE (`t`.`target_class` IN ('appUserPreferences'))", unserialize('a:1:{s:14:"t.friendlyname";b:1;}'), array(), null, array(), 20, 0);
		$aData["SELECT 324"] = array("SELECT `UserInternal` FROM UserInternal AS `UserInternal` WHERE ((`UserInternal`.`login` = 'admin') AND (`UserInternal`.`status` = 'enabled'))", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 325"] = array("SELECT `Contact` FROM Contact AS `Contact` WHERE (`Contact`.`id` = '987654321')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 326"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", array(), array(), null, array(), 4, 0);
		$aData["SELECT 327"] = array("SELECT `appUserPreferences` FROM appUserPreferences AS `appUserPreferences` WHERE (`appUserPreferences`.`userid` = '1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 328"] = array("SELECT `Shortcut` FROM Shortcut AS `Shortcut` WHERE (`Shortcut`.`user_id` = '1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 329"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '1') AND (`UserDashboard`.`menu_code` = 'WelcomeMenuPage'))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 330"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '1') AND (`UserDashboard`.`menu_code` = 'WelcomeMenuPage'))", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 331"] = array("SELECT `BusinessProcess` FROM BusinessProcess AS `BusinessProcess` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 332"] = array("SELECT `ApplicationSolution` FROM ApplicationSolution AS `ApplicationSolution` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 333"] = array("SELECT `Contact` FROM Contact AS `Contact` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 334"] = array("SELECT `Location` FROM Location AS `Location` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 335"] = array("SELECT `Contract` FROM Contract AS `Contract` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 336"] = array("SELECT `Server` FROM Server AS `Server` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 337"] = array("SELECT `NetworkDevice` FROM NetworkDevice AS `NetworkDevice` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 338"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` != 'closed')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 339"] = array("SELECT `i` FROM UserRequest AS `i` WHERE ((`i`.`agent_id` = 1) AND (`i`.`status` NOT IN ('closed', 'resolved')))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 340"] = array("SELECT `i` FROM UserRequest AS `i` WHERE ((`i`.`agent_id` = 1) AND (`i`.`status` NOT IN ('closed', 'resolved')))", array(), array(), null, array(), 3, 0);
		$aData["SELECT 341"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", array(), array(), null, array(), 0, 0);
		$aData["SELECT 342"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", array(), array(), null, array(), 52, 0);
		$aData["SELECT 343"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = '')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 344"] = array("SELECT `DBProperty` FROM DBProperty AS `DBProperty` WHERE (`DBProperty`.`name` = 'database_uuid')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 345"] = array("SELECT `DBProperty` FROM DBProperty AS `DBProperty` WHERE (`DBProperty`.`name` = 'database_uuid')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 346"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '1') AND (`UserDashboard`.`menu_code` = 'UserRequest:Overview'))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 347"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = '987654321')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 348"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = '0')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 349"] = array("SELECT `lnkErrorToFunctionalCI` FROM lnkErrorToFunctionalCI AS `lnkErrorToFunctionalCI` JOIN KnownError AS `KnownError` ON `lnkErrorToFunctionalCI`.error_id = `KnownError`.id WHERE (`KnownError`.`id` = '-1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 350"] = array("SELECT `lnkErrorToFunctionalCI` FROM lnkErrorToFunctionalCI AS `lnkErrorToFunctionalCI` JOIN KnownError AS `KnownError` ON `lnkErrorToFunctionalCI`.error_id = `KnownError`.id WHERE (`KnownError`.`id` = '-1')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 351"] = array("SELECT `lnkDocumentToError` FROM lnkDocumentToError AS `lnkDocumentToError` JOIN KnownError AS `KnownError` ON `lnkDocumentToError`.error_id = `KnownError`.id WHERE (`KnownError`.`id` = '-1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 352"] = array("SELECT `lnkDocumentToError` FROM lnkDocumentToError AS `lnkDocumentToError` JOIN KnownError AS `KnownError` ON `lnkDocumentToError`.error_id = `KnownError`.id WHERE (`KnownError`.`id` = '-1')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 353"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '1') AND (`UserDashboard`.`menu_code` = 'Change:Overview'))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 354"] = array("SELECT `InlineImage` FROM InlineImage AS `InlineImage` WHERE (`InlineImage`.`temp_id` = 'adm83AD.tmp')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 355"] = array("SELECT `Attachment` FROM Attachment AS `Attachment` WHERE (`Attachment`.`temp_id` = 'adm83AD.tmp')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 356"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '1') AND (`UserDashboard`.`menu_code` = 'Service:Overview'))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 357"] = array("SELECT `c` FROM CustomerContract AS `c` WHERE (`c`.`end_date` < DATE_ADD(NOW(), INTERVAL 30 DAY))", array(), array(), null, array(), 3, 0);
		$aData["SELECT 358"] = array("SELECT `c` FROM ProviderContract AS `c` WHERE (`c`.`end_date` < DATE_ADD(NOW(), INTERVAL 30 DAY))", array(), array(), null, array(), 3, 0);
		$aData["SELECT 359"] = array("SELECT `DeliveryModel` FROM DeliveryModel AS `DeliveryModel` WHERE 1", array(), array(), null, array(), 52, 0);
		$aData["SELECT 360"] = array("SELECT `DeliveryModel` FROM DeliveryModel AS `DeliveryModel` WHERE (`DeliveryModel`.`friendlyname` LIKE '%%')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), unserialize('a:1:{s:13:"DeliveryModel";a:1:{i:0;s:12:"friendlyname";}}'), array(), 0, 0);
		$aData["SELECT 361"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", array(), array(), null, array(), 3, 0);
		$aData["SELECT 362"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE 1", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), unserialize('a:1:{s:12:"Organization";a:7:{i:0;s:4:"code";i:1;s:6:"status";i:2;s:9:"parent_id";i:3;s:22:"parent_id_friendlyname";i:4;s:27:"parent_id_obsolescence_flag";i:5;s:12:"friendlyname";i:6;s:17:"obsolescence_flag";}}'), array(), 10, 0);
		$aData["SELECT 363"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`name` LIKE '%demo%')", array(), array(), null, array(), 3, 0);
		$aData["SELECT 364"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`name` LIKE '%demo%')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 365"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`name` LIKE '%demo%')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 366"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`name` LIKE '%demo%')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), unserialize('a:1:{s:12:"Organization";a:7:{i:0;s:4:"code";i:1;s:6:"status";i:2;s:9:"parent_id";i:3;s:22:"parent_id_friendlyname";i:4;s:27:"parent_id_obsolescence_flag";i:5;s:12:"friendlyname";i:6;s:17:"obsolescence_flag";}}'), array(), 10, 0);
		$aData["SELECT 367"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = '3')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 368"] = array("SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = '3')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 369"] = array("SELECT `replica`, `datasource` FROM SynchroReplica AS `replica` JOIN SynchroDataSource AS `datasource` ON `replica`.sync_source_id = `datasource`.id WHERE ((`replica`.`dest_class` = 'Organization') AND (`replica`.`dest_id` = '3'))", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 370"] = array("SELECT `datasource` FROM SynchroDataSource AS `datasource` WHERE 1", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 371"] = array("SELECT `Trigger` FROM Trigger AS `Trigger` WHERE 1", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 372"] = array("SELECT `UserDashboard` FROM UserDashboard AS `UserDashboard` WHERE ((`UserDashboard`.`user_id` = '1') AND (`UserDashboard`.`menu_code` = 'Organization__overview'))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 373"] = array("SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE (`FunctionalCI`.`org_id` = '3')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 374"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` != 'closed') AND (`UserRequest`.`org_id` = '3'))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 375"] = array("SELECT `p` FROM Person AS `p` JOIN User AS `u` ON `u`.contactid = `p`.id WHERE (`p`.`org_id` = '3')", array(), array(), null, array(), 3, 0);
		$aData["SELECT 376"] = array("SELECT `p` FROM Person AS `p` JOIN User AS `u` ON `u`.contactid = `p`.id WHERE (`p`.`org_id` = '3')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 377"] = array("SELECT `p` FROM Person AS `p` JOIN User AS `u` ON `u`.contactid = `p`.id WHERE (`p`.`org_id` = '3')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), unserialize('a:1:{s:1:"p";a:13:{i:0;s:4:"name";i:1;s:6:"org_id";i:2;s:19:"org_id_friendlyname";i:3;s:24:"org_id_obsolescence_flag";i:4;s:6:"status";i:5;s:11:"location_id";i:6;s:24:"location_id_friendlyname";i:7;s:29:"location_id_obsolescence_flag";i:8;s:5:"email";i:9;s:5:"phone";i:10;s:12:"friendlyname";i:11;s:17:"obsolescence_flag";i:12;s:10:"finalclass";}}'), array(), 10, 0);
		$aData["SELECT 378"] = array("SELECT `i` FROM UserRequest AS `i` WHERE (((`i`.`agent_id` = 1) AND (`i`.`status` NOT IN ('closed', 'resolved'))) AND (`i`.`org_id` = '3'))", array(), array(), null, array(), 3, 0);
		$aData["SELECT 379"] = array("SELECT `Location` FROM Location AS `Location` WHERE 1", array(), array(), null, array(), 3, 0);
		$aData["SELECT 380"] = array("SELECT `Location` FROM Location AS `Location` WHERE 1", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), unserialize('a:1:{s:8:"Location";a:8:{i:0;s:6:"status";i:1;s:6:"org_id";i:2;s:19:"org_id_friendlyname";i:3;s:24:"org_id_obsolescence_flag";i:4;s:4:"city";i:5;s:7:"country";i:6;s:12:"friendlyname";i:7;s:17:"obsolescence_flag";}}'), array(), 10, 0);
		$aData["SELECT 381"] = array("SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE (`FunctionalCI`.`finalclass` IN ('Server', 'VirtualMachine', 'PC'))", array(), array(), null, array(), 4, 0);
		$aData["SELECT 382"] = array("SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE (`FunctionalCI`.`id` = '987654321')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 383"] = array("SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE (`FunctionalCI`.`finalclass` IN ('Server', 'VirtualMachine', 'PC'))", array(), array(), null, array(), 52, 0);
		$aData["SELECT 384"] = array("SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE (`FunctionalCI`.`finalclass` IN ('Server', 'VirtualMachine', 'PC'))", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 385"] = array("SELECT `Software` FROM Software AS `Software` WHERE (`Software`.`id` = '987654321')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 386"] = array("SELECT `Software` FROM Software AS `Software` WHERE (`Software`.`type` = 'WebServer')", array(), array(), null, array(), 52, 0);
		$aData["SELECT 387"] = array("SELECT `Software` FROM Software AS `Software` WHERE (`Software`.`type` = 'WebServer')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 388"] = array("SELECT `SoftwareLicence` FROM SoftwareLicence AS `SoftwareLicence` WHERE (`SoftwareLicence`.`id` = '987654321')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 389"] = array("SELECT `SoftwareLicence` FROM SoftwareLicence AS `SoftwareLicence` WHERE (`SoftwareLicence`.`software_id` = 0)", array(), array(), null, array(), 52, 0);
		$aData["SELECT 390"] = array("SELECT `SoftwareLicence` FROM SoftwareLicence AS `SoftwareLicence` WHERE (`SoftwareLicence`.`software_id` = 0)", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 391"] = array("SELECT `lnkContactToFunctionalCI` FROM lnkContactToFunctionalCI AS `lnkContactToFunctionalCI` JOIN FunctionalCI AS `FunctionalCI` ON `lnkContactToFunctionalCI`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 392"] = array("SELECT `lnkContactToFunctionalCI` FROM lnkContactToFunctionalCI AS `lnkContactToFunctionalCI` JOIN FunctionalCI AS `FunctionalCI` ON `lnkContactToFunctionalCI`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 393"] = array("SELECT `lnkDocumentToFunctionalCI` FROM lnkDocumentToFunctionalCI AS `lnkDocumentToFunctionalCI` JOIN FunctionalCI AS `FunctionalCI` ON `lnkDocumentToFunctionalCI`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 394"] = array("SELECT `lnkDocumentToFunctionalCI` FROM lnkDocumentToFunctionalCI AS `lnkDocumentToFunctionalCI` JOIN FunctionalCI AS `FunctionalCI` ON `lnkDocumentToFunctionalCI`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 395"] = array("SELECT `lnkApplicationSolutionToFunctionalCI` FROM lnkApplicationSolutionToFunctionalCI AS `lnkApplicationSolutionToFunctionalCI` JOIN FunctionalCI AS `FunctionalCI` ON `lnkApplicationSolutionToFunctionalCI`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 396"] = array("SELECT `lnkApplicationSolutionToFunctionalCI` FROM lnkApplicationSolutionToFunctionalCI AS `lnkApplicationSolutionToFunctionalCI` JOIN FunctionalCI AS `FunctionalCI` ON `lnkApplicationSolutionToFunctionalCI`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 397"] = array("SELECT `WebApplication` FROM WebApplication AS `WebApplication` JOIN WebServer AS `WebServer` ON `WebApplication`.webserver_id = `WebServer`.id WHERE (`WebServer`.`id` = '-1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 398"] = array("SELECT `WebApplication` FROM WebApplication AS `WebApplication` JOIN WebServer AS `WebServer` ON `WebApplication`.webserver_id = `WebServer`.id WHERE (`WebServer`.`id` = '-1')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 399"] = array("SELECT `lnkFunctionalCIToProviderContract` FROM lnkFunctionalCIToProviderContract AS `lnkFunctionalCIToProviderContract` JOIN FunctionalCI AS `FunctionalCI` ON `lnkFunctionalCIToProviderContract`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 400"] = array("SELECT `lnkFunctionalCIToProviderContract` FROM lnkFunctionalCIToProviderContract AS `lnkFunctionalCIToProviderContract` JOIN FunctionalCI AS `FunctionalCI` ON `lnkFunctionalCIToProviderContract`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 401"] = array("SELECT `lnkFunctionalCIToService` FROM lnkFunctionalCIToService AS `lnkFunctionalCIToService` JOIN FunctionalCI AS `FunctionalCI` ON `lnkFunctionalCIToService`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 402"] = array("SELECT `lnkFunctionalCIToService` FROM lnkFunctionalCIToService AS `lnkFunctionalCIToService` JOIN FunctionalCI AS `FunctionalCI` ON `lnkFunctionalCIToService`.functionalci_id = `FunctionalCI`.id WHERE (`FunctionalCI`.`id` = '-1')", array(), array(), null, array(), 0, 0);
		$aData["SELECT 403"] = array("SELECT `t` FROM Change AS `t` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `t`.id WHERE (((`lnk`.`functionalci_id` = '-2') AND (`t`.`status` NOT IN ('rejected', 'resolved', 'closed'))) AND (`lnk`.`impact_code` != 'not_impacted'))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 404"] = array("SELECT `t` FROM UserRequest AS `t` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `t`.id WHERE (((`lnk`.`functionalci_id` = '-2') AND (`t`.`status` NOT IN ('rejected', 'resolved', 'closed'))) AND (`lnk`.`impact_code` != 'not_impacted'))", array(), array(), null, array(), 0, 0);
		$aData["SELECT 405"] = array("SELECT `t` FROM Change AS `t` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `t`.id WHERE (((`lnk`.`functionalci_id` = '-2') AND (`t`.`status` NOT IN ('rejected', 'resolved', 'closed'))) AND (`lnk`.`impact_code` != 'not_impacted'))", array(), array(), null, array(), 3, 0);
		$aData["SELECT 406"] = array("SELECT `t` FROM UserRequest AS `t` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `t`.id WHERE (((`lnk`.`functionalci_id` = '-2') AND (`t`.`status` NOT IN ('rejected', 'resolved', 'closed'))) AND (`lnk`.`impact_code` != 'not_impacted'))", array(), array(), null, array(), 3, 0);
		$aData["SELECT 407"] = array("SELECT `InlineImage` FROM InlineImage AS `InlineImage` WHERE (`InlineImage`.`temp_id` = 'admBBC2.tmp')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);
		$aData["SELECT 408"] = array("SELECT `Attachment` FROM Attachment AS `Attachment` WHERE (`Attachment`.`temp_id` = 'admBBC2.tmp')", unserialize('a:1:{s:12:"friendlyname";b:1;}'), array(), null, array(), 0, 0);

		return $aData;
	}

	public function OQLSelectProvider()
	{
		$aData = $this->OQLSelectProviderStatic();

		// Dynamic entries
		@include ('oql_records.php');

		return $aData;
	}

	private function GetId()
	{
		$sId = str_replace('"', '', $this->getName());
		$sId = str_replace('Legacy', '', $sId);
		$sId = str_replace(' ', '_', $sId);
		return $sId;
	}

	/**
	 * @param $sSQL
	 *
	 * @param int $iLimit
	 *
	 * @return array|null
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	private function GetArrayResult($sSQL, $iLimit = 10)
	{
		$resQuery = CMDBSource::Query($sSQL);
		if (!$resQuery)
		{
			return null;
		}
		else
		{
			$aRow = array();
			$iCount = 0;
			while ($aRes = CMDBSource::FetchArray($resQuery))
			{
				if ($iCount < $iLimit)
				{
					$aRow[] = $aRes;
				}
				$iCount++;
				unset($aRes);
			}
			CMDBSource::FreeResult($resQuery);
			return $aRow;
		}
	}
}
