<?php
// Copyright (c) 2010-2018 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 06/02/2018
 * Time: 09:58
 */

namespace Combodo\iTop\Test\UnitTest\Core;


use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBSearch;
use Exception;
use Expression;
use FunctionExpression;


/**
 * Tests of the DBSearch class.
 * <ul>
 * <li>MakeGroupByQuery</li>
 * </ul>
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBSearchTest extends ItopDataTestCase
{

	/**
	 * @throws \Exception
	 */
	protected function setUp()
	{
		parent::setUp();
	}

	/**
	 * @dataProvider UReqProvider
	 * @param $iOrgNb
	 * @param $iPersonNb
	 * @param $aReq
	 * @param $iLimit
	 * @param $aCountRes
	 * @throws Exception
	 */
	public function testMakeGroupByQuery($iOrgNb, $iPersonNb, $aReq, $iLimit, $aCountRes)
	{
		$sOrgs = $this->init_db($iOrgNb, $iPersonNb, $aReq);

		$oSearch = DBSearch::FromOQL("SELECT UserRequest WHERE org_id IN ($sOrgs)");
		self::assertNotNull($oSearch);
		$oExpr1 = Expression::FromOQL('UserRequest.org_id');

		// Alias => Expression
		$aGroupBy = array('org_id' => $oExpr1);

		$oTimeExpr = Expression::FromOQL('UserRequest.time_spent');
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
			);

		// Alias => Order
		$aOrderBy = array('_itop_sum_' => true, '_itop_count_' => true);

		$aArgs = array();

		$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy, $iLimit);
		$this->debug($sSQL);

		$aRes = CMDBSource::QueryToArray($sSQL);
		$this->debug($aRes);

		self::assertEquals(count($aCountRes), count($aRes));
		for ($i = 0; $i < count($aCountRes); $i++)
		{
			self::assertEquals($aCountRes[$i], $aRes[$i]['_itop_count_']);
		}
	}


	public function UReqProvider()
	{
		return array(
			"1 line" => array(1, 1, array(array(1, 0, 0)), 0, array('1')),
			"2 same lines" => array(1, 1, array(array(1, 0, 0), array(1, 0, 0)), 0, array('2')),
			"2 diff lines" => array(2, 2, array(array(1, 0, 0), array(1, 1, 1)), 0, array('1', '1')),
			"4 lines" => array(2, 2, array(array(1, 0, 0), array(1, 1, 1), array(1, 0, 0), array(1, 1, 1)), 0, array('2', '2')),
			"5 lines" => array(2, 2, array(array(1, 0, 0), array(1, 0, 0), array(1, 1, 1), array(1, 0, 0), array(1, 1, 1)), 0, array('2', '3')),
			"6 lines" => array(2, 4, array(array(1, 0, 0), array(1, 1, 3), array(1, 1, 1), array(1, 1, 3), array(1, 0, 2), array(1, 1, 1)), 0, array('2', '4')),
			"6 lines limit" => array(2, 4, array(array(1, 0, 0), array(1, 1, 3), array(1, 1, 1), array(1, 1, 1), array(1, 0, 0), array(1, 1, 1)), 1, array('2')),
		);
	}

	/**
	 * @param int $iOrgNb Number of Organization to create
	 * @param int $iPersonNb Number of Person to create
	 * @param array $aReq  UserRequests to create: array(array([time_spent value], [org index], [person index]))
	 * @return string organization list for select
	 * @throws Exception
	 */
	private function init_db($iOrgNb, $iPersonNb, $aReq)
	{
		$aOrgIds = array();
		$sOrgs = '';
		for($i = 0; $i < $iOrgNb; $i++)
		{
			$oObj = $this->CreateOrganization('UnitTest_Org'.$i);
			$sKey = $oObj->GetKey();
			$aOrgIds[] = $sKey;
			if ($i > 0)
			{
				$sOrgs .= ",";
			}
			$sOrgs .= $sKey;
		}
		self::assertEquals($iOrgNb, count($aOrgIds));

		$aPersonIds = array();
		for($i = 0; $i < $iPersonNb; $i++)
		{
			$oObj = $this->CreatePerson($i, $aOrgIds[$i % $iOrgNb]);
			$aPersonIds[] = $oObj->GetKey();
		}
		self::assertEquals($iPersonNb, count($aPersonIds));

		$i = 0;
		foreach($aReq as $aParams)
		{
			$oObj = $this->CreateUserRequest($i, $aParams[0], $aOrgIds[$aParams[1]], $aPersonIds[$aParams[2]]);
			self::assertNotNull($oObj);
			$i++;
		}
		return $sOrgs;
	}

	/**
	 * @throws Exception
	 */
	public function testGroupByUnion()
	{
		$oServer = $this->CreateServer(1);

		$this->CreatePhysicalInterface(1, 1000, $oServer->GetKey());
		$this->CreateFiberChannelInterface(1, 1000, $oServer->GetKey());


		$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface AS FCI WHERE FCI.name = '1' UNION SELECT PhysicalInterface AS PHI WHERE PHI.name = '1'");
		self::assertNotNull($oSearch);
		$oExpr1 = Expression::FromOQL('FCI.name');

		// Alias => Expression (first select reference)
		$aGroupBy = array('group1' => $oExpr1);

		$oTimeExpr = Expression::FromOQL('FCI.speed');
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);

		// Alias => Order
		$aOrderBy = array('group1' => true, '_itop_count_' => true);

		$aArgs = array();

		$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);
		$this->debug($sSQL);

		$aRes = CMDBSource::QueryToArray($sSQL);
		$this->debug($aRes);
	}

	/**
	 * @throws Exception
	 */
	public function testOrderBy_1()
	{

		$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface");
		self::assertNotNull($oSearch);

		// Alias => Expression (first select reference)
		$oExpr1 = Expression::FromOQL('FiberChannelInterface.name');
		$aGroupBy = array('group1' => $oExpr1);

		$oTimeExpr = Expression::FromOQL('FiberChannelInterface.speed');
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);
		$aArgs = array();

		// Alias => Order
		$aOrderBy = array(
			'group1' => true,
			'_itop_sum_' => true,
			'_itop_avg_' => true,
			'_itop_min_' => true,
			'_itop_max_' => true);
		$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);
		self::assertNotEmpty($sSQL);

		// Alias => Order
		$aOrderBy = array('nothing_good' => true);
		$this->expectException("CoreException");
		$oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);

		self::assertTrue(false);
	}

	/**
	 * @throws Exception
	 */
	public function testSanity_1()
	{
		$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface AS FCI WHERE FCI.name = '1' UNION SELECT PhysicalInterface AS PHI WHERE PHI.name = '1'");
		self::assertNotNull($oSearch);
		$oExpr1 = Expression::FromOQL('FCI.name');

		// Alias => Expression (first select reference)
		$aGroupBy = array('group1' => $oExpr1);

		$oTimeExpr = Expression::FromOQL('FCI.speed');
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'group1' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);
		$aArgs = array();

		// Alias => Order
		$aOrderBy = array(
			'group1' => true,
			'_itop_sum_' => true,
			'_itop_avg_' => true,
			'_itop_min_' => true,
			'_itop_max_' => true);

		$this->expectException("CoreException");
		$oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);

		self::assertTrue(false);
	}

	/**
	 * @throws Exception
	 */
	public function testSanity_2()
	{
		$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface AS FCI WHERE FCI.name = '1' UNION SELECT PhysicalInterface AS PHI WHERE PHI.name = '1'");
		self::assertNotNull($oSearch);

		// Alias => Expression (first select reference)
		$oExpr1 = Expression::FromOQL('FCI.name');
		$aGroupBy = array('group1' => $oExpr1);

		$oTimeExpr = Expression::FromOQL('FCI.speed');
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);
		$aArgs = array();

		// Alias => Order
		$aOrderBy = array(
			'group1' => true,
			'_itop_sum_' => true,
			'_itop_avg_' => true,
			'_itop_min_' => true,
			'_itop_max_' => true);

		$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);
		self::assertNotEmpty($sSQL);

		$aGroupBy = array('group1' => 'FCI.name');
		$this->expectException("CoreException");
		$oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);

		self::assertTrue(false);
	}

	/**
	 * @throws Exception
	 */
	public function testSanity_3()
	{
		$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface AS FCI WHERE FCI.name = '1' UNION SELECT PhysicalInterface AS PHI WHERE PHI.name = '1'");
		self::assertNotNull($oSearch);

		// Alias => Expression (first select reference)
		$oExpr1 = Expression::FromOQL('FCI.name');
		$aGroupBy = array('group1' => $oExpr1);

		$oTimeExpr = Expression::FromOQL('FCI.speed');
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);
		$aArgs = array();

		// Alias => Order
		$aOrderBy = array(
			'group1' => true,
			'_itop_sum_' => true,
			'_itop_avg_' => true,
			'_itop_min_' => true,
			'_itop_max_' => true);

		$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);
		self::assertNotEmpty($sSQL);

		$aFunctions = array(
			'_itop_sum_' => 'SumExpr',
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);

		$this->expectException("CoreException");
		$oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);

		self::assertTrue(false);
	}

	/**
	 * @throws Exception
	 */
	public function testSanity_4()
	{
		$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface AS FCI WHERE FCI.name = '1' UNION SELECT PhysicalInterface AS PHI WHERE PHI.name = '1'");
		self::assertNotNull($oSearch);

		// Alias => Expression (first select reference)
		$oExpr1 = Expression::FromOQL('FCI.name');
		$aGroupBy = array('group1' => $oExpr1);

		$oTimeExpr = Expression::FromOQL('FCI.speed');
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);
		$aArgs = array();

		// Alias => Order
		$aOrderBy = array(
			'group1' => true,
			'_itop_sum_' => true,
			'_itop_avg_' => true,
			'_itop_min_' => true,
			'_itop_max_' => true);

		$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);
		self::assertNotEmpty($sSQL);

		$aOrderBy = array(
			'group1' => true,
			'_itop_sum_' => true,
			'_itop_avg_' => 'ASC',
			'_itop_min_' => true,
			'_itop_max_' => true);

		$this->expectException("CoreException");
		$oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);

		self::assertTrue(false);
	}

	/**
	 * @throws Exception
	 */
	public function testSanity_5()
	{
		$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface AS FCI WHERE FCI.name = '1' UNION SELECT PhysicalInterface AS PHI WHERE PHI.name = '1'");
		self::assertNotNull($oSearch);

		// Alias => Expression (first select reference)
		$oExpr1 = Expression::FromOQL('FCI.name');
		$aGroupBy = array('group1' => $oExpr1);

		$oTimeExpr = Expression::FromOQL('FCI.speed');
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);
		$aArgs = array();

		// Alias => Order
		$aOrderBy = array(
			'group1' => true,
			'_itop_sum_' => true,
			'_itop_avg_' => true,
			'_itop_min_' => true,
			'_itop_max_' => true);
		$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);
		self::assertNotEmpty($sSQL);

		// Alias => Order
		$aOrderBy = array('nothing_good' => true);
		$this->expectException("CoreException");
		$oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy);

		self::assertTrue(false);
	}

	/**
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testNoGroupBy()
	{
		$aReq = array(array(1, 0, 0), array(1, 1, 3), array(1, 1, 1), array(1, 1, 1), array(1, 0, 0), array(1, 1, 1));
		$sOrgs = $this->init_db(2, 4, $aReq);

		$oSearch = DBSearch::FromOQL("SELECT UserRequest WHERE org_id IN ($sOrgs)");
		self::assertNotNull($oSearch);


		$oTimeExpr = Expression::FromOQL('UserRequest.time_spent');
		$oSumExpr = new FunctionExpression('SUM', array($oTimeExpr));
		$oAvgExpr = new FunctionExpression('AVG', array($oTimeExpr));
		$oMinExpr = new FunctionExpression('MIN', array($oTimeExpr));
		$oMaxExpr = new FunctionExpression('MAX', array($oTimeExpr));
		// Alias => Expression
		$aFunctions = array(
			'_itop_sum_' => $oSumExpr,
			'_itop_avg_' => $oAvgExpr,
			'_itop_min_' => $oMinExpr,
			'_itop_max_' => $oMaxExpr,
		);

		$aGroupBy = array();
		$aOrderBy = array();
		$aArgs = array();

		$sSQL = $oSearch->MakeGroupByQuery($aArgs, $aGroupBy, false, $aFunctions, $aOrderBy, 0);
		$this->debug($sSQL);

		$aRes = CMDBSource::QueryToArray($sSQL);
		$this->debug($aRes);

		self::assertEquals(1, count($aRes));
	}

}
