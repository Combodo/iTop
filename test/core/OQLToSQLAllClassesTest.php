<?php

namespace Combodo\iTop\Test\UnitTest\Core;


define('PRECISION', 2);

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBSearch;
use MetaModel;
use SetupUtils;
use utils;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class OQLToSQLAllCLassesTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;
	const CREATE_TEST_ORG = false;

	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');

		SetupUtils::builddir(APPROOT.'log/test/OQLToSQL');
	}

	public function testEmptyAlias()
	{
		$oFilter = new DBObjectSearch('Organization', '');
		$oFilter->AllowAllData();
		$sSQL = $oFilter->MakeSelectQuery();
		CMDBSource::Query($sSQL);
		$this->assertTrue(true);
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
	public function testOQLLegacyAllClasses($sOQL, $aOrderBy = array(), $aArgs = array(), $aAttToLoad = null, $aExtendedDataSpec = null, $iLimitCount = 20, $iLimitStart = 0)
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
	public function testOQLAllClasses($sOQL, $aOrderBy = array(), $aArgs = array(), $aAttToLoad = null, $aExtendedDataSpec = null, $iLimitCount = 20, $iLimitStart = 0)
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
	 * @param null $aAttToLoad
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
		$this->debug("count_duration  : ".round($aPrevious['count_duration'], PRECISION)." -> ".round($aResult['count_duration'], PRECISION));
		$this->debug("data_join_count : ".$aPrevious['data_join_count']." -> ".$aResult['data_join_count']);
		$this->debug("data_duration   : ".round($aPrevious['data_duration'], PRECISION)." -> ".round($aResult['data_duration'], PRECISION));

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

	static $aPureAbstractClasses = ['AbstractResource', 'ResourceAdminMenu', 'ResourceRunQueriesMenu', 'ResourceItopIntegrityMenu'];

	public function OQLSelectProvider()
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');

		$aData = array();

		// $sOQL, $aOrderBy = array(), $aArgs = array(), $aAttToLoad = null, $aExtendedDataSpec = null, $iLimitCount = 20, $iLimitStart = 0

		$aClasses = MetaModel::GetClasses();
		sort($aClasses);

		foreach ($aClasses as $sClass)
		{
			if (in_array($sClass, self::$aPureAbstractClasses))
			{
				// These classes are pure abstract (no table in database)
				continue;
			}
			$sOQL = "SELECT $sClass";
			$aOrderBy = array();
			if (MetaModel::IsValidAttCode($sClass, 'friendlyname'))
			{
				$aOrderBy["$sClass.friendlyname"] = true;
			}
			$aData[$sOQL] = array($sOQL, $aOrderBy, array(), null, null, 10, 0);
		}

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
