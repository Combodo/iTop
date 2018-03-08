<?php
/**
 * Copyright (C) 2010-2018 Combodo SARL
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
use Exception;

class SearchFormTest extends ItopDataTestCase
{

	/**
	 * @throws Exception
	 */
	protected function setUp()
	{
		parent::setUp();

		require_once(APPROOT."sources/application/search/searchform.class.inc.php");
	}

	/**
	 */
	public function testGetFields()
	{
		$aFields = SearchForm::GetFields('Contact', 'Contact');
		$this->debug(json_encode($aFields, JSON_PRETTY_PRINT));
		$this->assertCount(7, $aFields);

		$oSearch = \DBSearch::FromOQL("SELECT Contact AS C WHERE C.status = 'active'");
		$aFields = SearchForm::GetFields($oSearch->GetClass(), $oSearch->GetClassAlias());
		$this->debug(json_encode($aFields, JSON_PRETTY_PRINT));
	}

	/**
	 * @dataProvider GetCriterionProvider
	 * @throws \OQLException
	 */
	public function testGetCriterion($sOQL, $iOrCount)
	{
		$aCriterion = SearchForm::GetCriterion(\DBObjectSearch::FromOQL($sOQL));
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
			array('OQL' => "SELECT Contact WHERE status = 'active' OR name LIKE 'toto%'", 2),
			array('OQL' => "SELECT UserRequest WHERE DATE_SUB(NOW(), INTERVAL 14 DAY) < start_date", 1),
			array('OQL' => "SELECT UserRequest WHERE start_date > '2017-01-01' AND '2018-01-01' >= start_date", 1),
		);
	}
}
