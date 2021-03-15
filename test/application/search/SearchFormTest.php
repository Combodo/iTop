<?php
/**
 * Copyright (C) 2010-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */


namespace Combodo\iTop\Test\UnitTest\Application\Search;

use Combodo\iTop\Application\Search\SearchForm;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use Exception;

/**
 * @group itopRequestMgmt
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class SearchFormTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	/**
	 * @throws Exception
	 */
	protected function setUp()
	{
		parent::setUp();

		require_once(APPROOT."sources/application/search/searchform.class.inc.php");
	}

	/**
	 * @dataProvider GetFieldsProvider
	 * @throws \OQLException
	 * @throws \CoreException
	 */
	public function testGetFields($sOQL)
	{
		$oSearchForm = new SearchForm();
		$oSearch = \DBSearch::FromOQL($sOQL);
		$aFields = $oSearchForm->GetFields(new \DBObjectSet($oSearch));
		$this->debug($sOQL);
		$this->debug(json_encode($aFields, JSON_PRETTY_PRINT));
		$this->assertTrue(count($aFields['zlist']) > 0);
		$this->assertTrue(count($aFields['others']) > 0);
	}

	public function GetFieldsProvider()
	{
		return array(
			array("SELECT Contact"),
			array("SELECT Contact AS C WHERE C.status = 'active'"),
			array("SELECT Person"),
			array(
				"SELECT Person AS p JOIN UserRequest AS u ON u.agent_id = p.id WHERE u.status != 'closed'",
			),
		);
	}


	/**
	 * @dataProvider GetCriterionProvider
	 *
	 * @param $sOQL
	 * @param $iOrCount
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 */
	public function testGetCriterion($sOQL, $iOrCount)
	{
		$oSearchForm = new SearchForm();
		try
		{
			$oSearch = \DBSearch::FromOQL($sOQL);
			$aFields = $oSearchForm->GetFields(new \DBObjectSet($oSearch));
			/** @var DBObjectSearch $oSearch */
			$aCriterion = $oSearchForm->GetCriterion($oSearch, $aFields);
		} catch (\OQLException $e)
		{
			$this->assertTrue(false);

			return;
		}
		$aRes = array('base_oql' => $sOQL, 'criterion' => $aCriterion);
		$this->debug(json_encode($aRes));
		$this->debug($sOQL);
		$this->debug(json_encode($aCriterion, JSON_PRETTY_PRINT));
		$this->assertCount($iOrCount, $aCriterion['or']);
	}

	public function GetCriterionProvider()
	{
		return array(
			array('OQL' => "SELECT Contact", 1),
			array('OQL' => "SELECT Contact WHERE status = 'active'", 1),
			array('OQL' => "SELECT Contact AS C WHERE C.status = 'active'", 1),
			array('OQL' => "SELECT Contact WHERE status = 'active' AND name LIKE 'toto%'", 1),
			array('OQL' => "SELECT Contact WHERE status = 'active' AND org_id = 3", 1),
			array('OQL' => "SELECT Contact WHERE status IN ('active', 'inactive')", 1),
			array('OQL' => "SELECT Contact WHERE status NOT IN ('active')", 1),
			array('OQL' => "SELECT Contact WHERE status NOT IN ('active', 'inactive')", 1),
			array('OQL' => "SELECT Contact WHERE status = 'active' OR name LIKE 'toto%'", 2),
			array('OQL' => "SELECT UserRequest WHERE DATE_SUB(NOW(), INTERVAL 14 DAY) < start_date", 1),
			array('OQL' => "SELECT UserRequest WHERE start_date > '2017-01-01 00:00:00' AND '2018-01-01 00:00:00' >= start_date", 1),
			array('OQL' => "SELECT UserRequest WHERE start_date > '2017-01-01 00:00:00' AND status = 'active' AND org_id = 3 AND '2018-01-01 00:00:00' >= start_date", 1),
			array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-01 00:00:00' >= start_date", 1),
			array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-01 01:00:00' > start_date", 1),
			array('OQL' => "SELECT UserRequest WHERE start_date >= '2017-01-01 00:00:00' AND '2017-01-02 00:00:00' > start_date", 1),
			array(
				'OQL' => "SELECT FunctionalCI WHERE ((business_criticity IN ('high', 'medium')) OR ISNULL(business_criticity)) AND 1",
				1
			),

		);
	}
}
