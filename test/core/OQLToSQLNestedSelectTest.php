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
	const TEST_CSV_RESULT = 'OQLToSQLTest.csv';

	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');

		SetupUtils::builddir(APPROOT.'log/test/OQLToSQL');
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

		$this->debug($aResult);

		//$aResult['data'] = $aRow;

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
			"SELECT UserRequest 112" =>array('SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.org_id IN (SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = `UserRequest`.`org_id`)))'),
			"SELECT UserRequest 113" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE  `UserRequest`.org_id IN (SELECT `Organization` FROM Organization AS `Organization` JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE (`Organization1`.`id` = '3'))", array('UserRequest.friendlyname' => true), $aArgs),
			"SELECT UserRequest 111" => array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE  `UserRequest`.org_id IN (1,2,3)", array('UserRequest.friendlyname' => true), $aArgs),
			);


		return $aData;
	}

	public function OQLSelectProvider()
	{
		$aData = $this->OQLSelectProviderStatic();


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
