<?php
/**
 * Copyright (C) 2018 Dennis Lassiter
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

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;
use Query;
use QueryOQL;

/**
 * This test creates call export on requests and check request usage counter.
 *
 * Transaction are disabled to avoid data inconsistency between test and call to export (outside test scope)
 * All objects created in this test will be deleted by the test.
 *
 * @group iTopQuery
 */
class QueryTest extends ItopDataTestCase
{
	// disable transaction to avoid data inconsistency between test and call to export (outside test scope)
	const USE_TRANSACTION = false;

	// user for exportation process
	const USER = 'dani2';
	const PASSWORD = '1TopCombodo+';
	private $oUser;

	/** @inheritDoc */
	public function setUp(): void
	{
		parent::setUp();

		// create export user
		$this->CreateExportUser();
	}

	/**
	 * Create new user for export authentication purpose.
	 */
	private function CreateExportUser()
	{
		$oAdminProfile = MetaModel::GetObjectFromOQL("SELECT URP_Profiles WHERE name = :name", array('name' => 'Administrator'), true);
		$this->oUser = $this->CreateUser(self::USER, $oAdminProfile->GetKey(), self::PASSWORD);
	}

	/**
	 * Create an OQL query to list Person objects.
	 *
	 * @param string $sName query name
	 * @param string $sDescription query description
	 * @param string $sOql query oql phrase
	 * @param string|null $sFields fields to export
	 */
	private function CreateQueryOQL(string $sName, string $sDescription, string $sOql, string $sFields = null) : QueryOQL
	{
		$oQuery = new QueryOQL();
		$oQuery->Set('name', $sName);
		$oQuery->Set('description', $sDescription);
		$oQuery->Set('oql', $sOql);

		if($sFields != null){
			$oQuery->Set('fields', $sFields);
		}

		$oQuery->DBInsert();
		$this->assertFalse($oQuery->IsNew());

		return $oQuery;
	}

	/**
	 * Test query export V1 usage.
	 *
	 * @param string $sDescription query description
	 * @param string $sOql query oql phrase
	 *
	 * @dataProvider getQueryProvider
	 */
	public function testQueryExportV1Usage(string $sDescription, string $sOql)
	{
		// create query OQL
		$oQuery = $this->CreateQueryOQL($this->dataName(), $sDescription, $sOql);

		// call export service
		$this->CallExportService($oQuery);

		// reload to update counter (done by export process)
		$oQuery->Reload();

		// extract counter
		$iResult = $oQuery->Get('export_count');

		// delete the query
		$oQuery->DBDelete();

		// test
		$this->assertEquals(1, $iResult);
	}

	/**
	 * Test query export V2 usage.
	 *
	 * @param string $sDescription query description
	 * @param string $sOql query oql phrase
	 *
	 * @dataProvider getQueryProvider
	 */
	public function testQueryExportV2Usage(string $sDescription, string $sOql)
	{
		// create query OQL
		$oQuery = $this->CreateQueryOQL($this->dataName(), $sDescription, $sOql, 'first_name');

		// call export service
		$this->CallExportService($oQuery);

		// reload to update counter (done by export process)
		$oQuery->Reload();

		// extract counter
		$iResult = $oQuery->Get('export_count');

		// delete the query
		$oQuery->DBDelete();

		// test
		$this->assertEquals(1, $iResult);
	}

	/**
	 * Data provide for test.
	 *
	 * @return array
	 */
	public function getQueryProvider()
	{
		return array(
			'Export #1' => array('query without params', 'SELECT Person'),
			'Export #2' => array('query with params', "SELECT Person WHERE first_name LIKE 'B%'")
		);
	}

	/**
	 * Call export for given query object.
	 *
	 * @param \Query $oQuery
	 *
	 * @return bool|string
	 */
	private function CallExportService(Query $oQuery)
	{
		// compute request url
		$url = 	$oQuery->GetExportUrl();

		// open curl
		$curl = curl_init();

		// curl options
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, self::USER . ':' . self::PASSWORD);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		// Force disable of certificate check as most of dev / test env have a self-signed certificate
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

		// execute curl
		$result = curl_exec($curl);

		// close curl
		curl_close($curl);

		return $result;
	}

	/** @inheritDoc */
	protected function tearDown(): void
	{
		$this->oUser->DBDelete();

		parent::tearDown();
	}

}
