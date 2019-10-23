<?php

//namespace Combodo\iTop\Test\UnitTest\Core;


define('NUM_PRECISION', 2);

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class OQLToSQLTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;
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
	 * @dataProvider OQLGroupByProvider
	 * @depends      testOQLLegacySetup
	 *
	 * @param $sOQL
	 * @param $aArgs
	 * @param $aGroupByExpr
	 * @param bool $bExcludeNullValues
	 * @param array $aSelectExpr
	 * @param array $aOrderBy
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function testOQLGroupByLegacy($sOQL, $aArgs, $aGroupByExpr, $bExcludeNullValues = false, $aSelectExpr = array(), $aOrderBy = array(), $iLimitCount = 0, $iLimitStart = 0)
	{
		$this->assertTrue(utils::GetConfig()->Get('use_legacy_dbsearch'));
		$this->assertFalse(utils::GetConfig()->Get('apc_cache.enabled'));
		$this->assertFalse(utils::GetConfig()->Get('query_cache_enabled'));
		$this->assertFalse(utils::GetConfig()->Get('expression_cache_enabled'));

		$aPrevious = $this->GetPreviousTestResult($this->GetId());
		if (is_null($aPrevious))
		{
			$aResult = $this->OQLGroupByRunner($sOQL, $aArgs, $aGroupByExpr, $bExcludeNullValues, $aSelectExpr, $aOrderBy, $iLimitCount, $iLimitStart);
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
	 * @dataProvider OQLGroupByProvider
	 * @depends      testOQLSetup
	 *
	 * @param $sOQL
	 * @param $aArgs
	 * @param $aGroupByExpr
	 * @param bool $bExcludeNullValues
	 * @param array $aSelectExpr
	 * @param array $aOrderBy
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function testOQLGroupBy($sOQL, $aArgs, $aGroupByExpr, $bExcludeNullValues = false, $aSelectExpr = array(), $aOrderBy = array(), $iLimitCount = 0, $iLimitStart = 0)
	{
		$this->assertFalse(utils::GetConfig()->Get('use_legacy_dbsearch'));
		$this->assertFalse(utils::GetConfig()->Get('apc_cache.enabled'));
		$this->assertFalse(utils::GetConfig()->Get('query_cache_enabled'));
		$this->assertFalse(utils::GetConfig()->Get('expression_cache_enabled'));

		$aResult = $this->OQLGroupByRunner($sOQL, $aArgs, $aGroupByExpr, $bExcludeNullValues, $aSelectExpr, $aOrderBy, $iLimitCount, $iLimitStart);
		$this->assertNull($aResult);
	}

	/**
	 * @param $sOQL
	 * @param $aArgs
	 * @param $aGroupByExpr
	 * @param bool $bExcludeNullValues
	 * @param array $aSelectExpr
	 * @param array $aOrderBy
	 * @param int $iLimitCount
	 * @param int $iLimitStart
	 *
	 * @return array|null
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	private function OQLGroupByRunner($sOQL, $aArgs, $aGroupByExpr, $bExcludeNullValues = false, $aSelectExpr = array(), $aOrderBy = array(), $iLimitCount = 0, $iLimitStart = 0)
	{
		$oSearch = DBSearch::FromOQL($sOQL);

		$aGroupByExpr = Expression::ConvertArrayFromOQL($aGroupByExpr);
		$aSelectExpr = Expression::ConvertArrayFromOQL($aSelectExpr);

		$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupByExpr, $bExcludeNullValues, $aSelectExpr, $aOrderBy, $iLimitCount, $iLimitStart);
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
			'data_sql' => $sSQL,
			'data_join_count' => $iJoinData,
			'data_duration' => $fDataDuration,
		);

		$aResult['data'] = $aRow;

		$aPrevious = $this->GetPreviousTestResult($this->GetId());
		if (is_null($aPrevious))
		{
			return $aResult;
		}

		$this->debug("data_join_count : ".$aPrevious['data_join_count']." -> ".$aResult['data_join_count']);
		$this->debug("data_duration   : ".round($aPrevious['data_duration'], NUM_PRECISION)." -> ".round($aResult['data_duration'], NUM_PRECISION));

		// Compare result
		$aFields = array('oql', 'data');
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

	private function OQLGroupByProviderStatic()
	{
		$aData = array();

		$aData["SELECT 1"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` != 'closed')", array(), unserialize('a:1:{s:6:"group1";s:22:"`UserRequest`.`status`";}'), false, array(), array(), 0, 0);
		$aData["SELECT 2"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE 1", array(), unserialize('a:1:{s:12:"grouped_by_1";s:22:"`UserRequest`.`status`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:1;}'), 0, 0);
		$aData["SELECT 4"] = array("SELECT `Contact` FROM Contact AS `Contact` WHERE 1", array(), unserialize('a:1:{s:12:"grouped_by_1";s:18:"`Contact`.`status`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:0;}'), 0, 0);
		$aData["SELECT 5"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` NOT IN ('closed', 'rejected'))", array(), unserialize('a:1:{s:12:"grouped_by_1";s:22:"`UserRequest`.`status`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:0;}'), 0, 0);
		$aData["SELECT 6"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` NOT IN ('closed', 'rejected'))", array(), unserialize('a:1:{s:12:"grouped_by_1";s:24:"`UserRequest`.`agent_id`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:0;}'), 0, 0);
		$aData["SELECT 7"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` NOT IN ('closed', 'rejected'))", array(), unserialize('a:1:{s:12:"grouped_by_1";s:26:"`UserRequest`.`finalclass`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:0;}'), 0, 0);
		$aData["SELECT 8"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`status` NOT IN ('closed', 'rejected'))", array(), unserialize('a:1:{s:12:"grouped_by_1";s:22:"`UserRequest`.`org_id`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:0;}'), 0, 0);
		$aData["SELECT 9"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (DATE_SUB(NOW(), INTERVAL 14 DAY) < `UserRequest`.`start_date`)", array(), unserialize('a:1:{s:12:"grouped_by_1";s:28:"`UserRequest`.`request_type`";}'), true, array(), unserialize('a:1:{s:12:"_itop_count_";b:0;}'), 0, 0);
		$aData["SELECT 10"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (DATE_SUB(NOW(), INTERVAL 14 DAY) < `UserRequest`.`start_date`)", array(), unserialize('a:1:{s:12:"grouped_by_1";s:51:"DATE_FORMAT(`UserRequest`.`start_date`, \'%Y-%m-%d\')";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:1;}'), 0, 0);
		$aData["SELECT 11"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`creation_date` > DATE_SUB(NOW(), INTERVAL 7 DAY))", array(), unserialize('a:1:{s:12:"grouped_by_1";s:19:"`Change`.`category`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:0;}'), 0, 0);
		$aData["SELECT 12"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`creation_date` > DATE_SUB(NOW(), INTERVAL 7 DAY))", array(), unserialize('a:1:{s:12:"grouped_by_1";s:21:"`Change`.`finalclass`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:0;}'), 0, 0);
		$aData["SELECT 13"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`creation_date` > DATE_SUB(NOW(), INTERVAL 7 DAY))", array(), unserialize('a:1:{s:12:"grouped_by_1";s:17:"`Change`.`status`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:0;}'), 0, 0);
		$aData["SELECT 14"] = array("SELECT `Change` FROM Change AS `Change` WHERE (`Change`.`creation_date` > DATE_SUB(NOW(), INTERVAL 7 DAY))", array(), unserialize('a:1:{s:12:"grouped_by_1";s:46:"DATE_FORMAT(`Change`.`start_date`, \'%Y-%m-%d\')";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:1;}'), 0, 0);
		$aData["SELECT 15"] = array("SELECT `FunctionalCI` FROM FunctionalCI AS `FunctionalCI` WHERE (`FunctionalCI`.`org_id` = '3')", array(), unserialize('a:1:{s:6:"group1";s:27:"`FunctionalCI`.`finalclass`";}'), false, array(), array(), 0, 0);
		$aData["SELECT 16"] = array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`status` != 'closed') AND (`UserRequest`.`org_id` = '3'))", array(), unserialize('a:1:{s:6:"group1";s:22:"`UserRequest`.`status`";}'), false, array(), array(), 0, 0);
		$aData["SELECT 17"] = array("SELECT `Ticket` FROM Ticket AS `Ticket` WHERE (`Ticket`.`org_id` = '3')", array(), unserialize('a:1:{s:12:"grouped_by_1";s:21:"`Ticket`.`finalclass`";}'), true, array(), unserialize('a:1:{s:12:"grouped_by_1";b:0;}'), 0, 0);

		return $aData;
	}

	public function OQLGroupByProvider()
	{
		$aData = $this->OQLGroupByProviderStatic();

		// Dynamic entries
		@include ('oql_group_by_records.php');

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
