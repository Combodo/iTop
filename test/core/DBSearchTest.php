<?php
// Copyright (c) 2010-2021 Combodo SARL
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
use CoreOqlMultipleResultsForbiddenException;
use DBSearch;
use Exception;
use Expression;
use FunctionExpression;


/**
 * @group itopStorageMgmt
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
	const CREATE_TEST_ORG = true;

	/**
	 * @throws \Exception
	 */
	protected function setUp()
	{
		parent::setUp();

		require_once(APPROOT.'application/itopwebpage.class.inc.php');
		require_once(APPROOT.'application/displayblock.class.inc.php');
	}

	/**
	 * @group itopRequestMgmt
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
	 * @group itopRequestMgmt
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

	/**
	 * @dataProvider GetFirstResultProvider
	 *
	 * @param string $sOql query to test
	 * @param bool $bMustHaveOneResultMax arg passed to the tested function
	 * @param int $sReturn
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 * @throws \OQLException
	 *
	 * @covers       DBSearch::GetFirstResult()
	 */
	public function testGetFirstResult($sOql, $bMustHaveOneResultMax, $sReturn)
	{
		$oSearch = DBSearch::FromOQL($sOql);

		$bHasThrownException = false;
		try
		{
			$oFirstResult = $oSearch->GetFirstResult($bMustHaveOneResultMax);
		}
		catch (CoreOqlMultipleResultsForbiddenException $e)
		{
			$oFirstResult = null;
			$bHasThrownException = true;
		}

		switch ($sReturn)
		{
			case 'exception':
				self::assertEquals(true, $bHasThrownException, 'Exception raised');
				break;
			case 'null':
				self::assertNull($oFirstResult, 'Null returned');
				break;
			case 'object':
				self::assertInternalType('object', $oFirstResult, 'Object returned');
				break;
		}
	}

	public function GetFirstResultProvider()
	{
		return array(
			'One result' => array(
				'SELECT Person WHERE id = 1',
				false,
				'object',
			),
			'Multiple results, no exception' => array(
				'SELECT Person',
				false,
				'object',
			),
			'Multiple results, with exception' => array(
				'SELECT Person',
				true,
				'exception',
			),
			'Multiple results with "WHERE 1", with exception' => array(
				'SELECT Person WHERE 1',
				true,
				'exception',
			),
			'No result' => array(
				'SELECT Person WHERE id = -1',
				true,
				'null',
			),
		);
	}

	/**
	 * @throws Exception
	 */
	public function testSanity_GroupFunction_In_WherePart()
	{
		$sExceptionClass = '';
		$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface AS FCI");
		self::assertNotNull($oSearch);

		try
		{
			$oExpr1 = Expression::FromOQL('AVC(FCI.name)');
			//$aGroupBy = array('group1' => $oExpr1);
			//$oSearch->MakeGroupByQuery(array(), $aGroupBy, false, array(), array());
		}
		catch (Exception $e)
		{
			$sExceptionClass = get_class($e);
		}

		static::assertEquals('OQLParserSyntaxErrorException', $sExceptionClass);
	}

	public function testSanity_GroupFunction_In_GroupByPart()
	{
		$sExceptionClass = '';
		try
		{
			$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface AS FCI WHERE COUNT(FCI.name) = AVC(FCI.name)");
			//$oSearch->MakeGroupByQuery(array(), array(), false, array(), array());
		}
		catch (Exception $e)
		{
			$sExceptionClass = get_class($e);
		}

		static::assertEquals('OQLParserSyntaxErrorException', $sExceptionClass);
	}

	public function testSanity_UnknownGroupFunction_In_SelectPart()
	{
		$sExceptionClass = '';
		try
		{
			$oTimeExpr = Expression::FromOQL('FCI.speed');
			$oWrongExpr = new FunctionExpression('GABUZOMEU', array($oTimeExpr));
			// Alias => Expression
			$aFunctions = array(
				'_itop_wrong_' => $oWrongExpr,
			);
			$oSearch = DBSearch::FromOQL("SELECT FiberChannelInterface AS FCI");
			$oSearch->MakeGroupByQuery(array(), array(), false, $aFunctions, array());
		}
		catch (Exception $e)
		{
			$sExceptionClass = get_class($e);
		}

		//later on it should raise an exception...
		static::assertEquals('', $sExceptionClass);
	}

	/**
	 * @group itopRequestMgmt
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function testSelectInWithVariableExpressions()
	{
		$aReq = array(array(1, 0, 0), array(1, 1, 3), array(1, 2, 1), array(1, 0, 1), array(1, 1, 0), array(1, 2, 1));
		$sOrgs = $this->init_db(3, 4, $aReq);
		$allOrgIds = explode(",", $sOrgs);

		$TwoOrgIdsOnly = array($allOrgIds[0], $allOrgIds[1]);
		$oSearch = DBSearch::FromOQL("SELECT UserRequest WHERE org_id IN (:org_ids)");
		self::assertNotNull($oSearch);
		$oSet = new \CMDBObjectSet($oSearch, array(), array('org_ids' => $TwoOrgIdsOnly));
		static::assertEquals(4, $oSet->Count());

		// Content now generated with ajax call
		//		$_SERVER['REQUEST_URI'] = 'FAKE_REQUEST_URI';
		//		$_SERVER['REQUEST_METHOD'] = 'FAKE_REQUEST_METHOD';
		//		$oP = new \iTopWebPage("test");
		//		$oBlock = new \DisplayBlock($oSet->GetFilter(), 'list', false);
		//		$oHtml = $oBlock->GetDisplay($oP, 'package_table', array('menu' => true, 'display_limit' => false));
		//		$sHtml = BlockRenderer::RenderBlockTemplate($oHtml);
		//
		//		$iHtmlUserRequestLineCount = substr_count($sHtml, '<tr><td  data-object-class="UserRequest"');
		//		static::assertEquals(4, $iHtmlUserRequestLineCount, "Failed Generated html :".$sHtml);

		// As using $oP we added some content in the PHP buffer, we need to empty it !
		// Otherwise PHPUnit will throw the error : "Test code or tested code did not (only) close its own output buffers"
		// Previously we were using a output() call but this is time consuming and could cause "risky test" error !
		// ob_get_clean();
	}

	/**
	 * @since 2.7.2 3.0.0 N°3324
	 */
	public function testAllowAllData() {
		$oSimpleSearch = \DBObjectSearch::FromOQL('SELECT FunctionalCI');
		$oSimpleSearch->AllowAllData(false);
		self::assertFalse($oSimpleSearch->IsAllDataAllowed(), 'DBSearch AllowData value');
		$oSimpleSearch->AllowAllData(true);
		self::assertTrue($oSimpleSearch->IsAllDataAllowed(), 'DBSearch AllowData value');

		$sNestedQuery = 'SELECT FunctionalCI WHERE id IN (SELECT Server)';
		$this->CheckNestedSearch($sNestedQuery, true);
		$this->CheckNestedSearch($sNestedQuery, false);
	}

	private function CheckNestedSearch($sQuery, $bAllowAllData) {
		$oNestedQuerySearch = \DBObjectSearch::FromOQL($sQuery);
		$oNestedQuerySearch->AllowAllData($bAllowAllData);
		self::assertEquals($bAllowAllData, $oNestedQuerySearch->IsAllDataAllowed(), 'root DBSearch AllowData value');
		$oNestedSearchInExpression = null;
		$oNestedQuerySearch->GetCriteria()->Browse(function ($oExpression) use (&$oNestedSearchInExpression) {
			if ($oExpression instanceof \NestedQueryExpression) {
				$oNestedSearchInExpression = $oExpression->GetNestedQuery();

				return;
			}
		});
		self::assertNotNull($oNestedSearchInExpression, 'We must have a DBSearch inside a NestedQueryExpression inside the root DBSearch');
		/** @var \DBObjectSearch $oNestedSearchInExpression */
		self::assertEquals($bAllowAllData, $oNestedSearchInExpression->IsAllDataAllowed(), 'Nested DBSearch AllowData value');
	}

	/**
	 * BUG N°4031 check AttributeObjectKey used in JOIN condition
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \OQLException
	 */
	public function testAttributeObjectKey()
	{
		$sQuery = "SELECT II FROM InlineImage AS II JOIN UserRequest AS UR ON II.item_id = UR.id WHERE II.item_class = 'UserRequest'";
		$oSearch = \DBObjectSearch::FromOQL($sQuery);
		$oSearch->MakeSelectQuery();
		self::assertTrue(true);
	}
}
