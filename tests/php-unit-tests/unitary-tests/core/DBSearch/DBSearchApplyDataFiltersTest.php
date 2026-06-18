<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSearch;
use DBUnionSearch;
use UserRights;

class DBSearchApplyDataFiltersTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	/**
	 * @throws \Exception
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceUnitTestFile('Fixtures/N9687_CustomGetSelectFilterClass.php');
	}

	public function testApplyDataFiltersOnDBObjectSearchShouldAcceptGetSelectFilterClassReturningDBUnionSearch()
	{
		// Use custom select filter that returns a DBUnionSearch
		$sPreviousSelectModuleClass = get_class(UserRights::GetModuleInstance());
		UserRights::SelectModule('\\Combodo\\iTop\\Test\\UnitTest\\Core\\Fixtures\\N9687_CustomGetSelectFilterClass');

		// Create a user and login, otherwise the select filter won't apply
		self::CreateUser('test_dbsearch_applydatafilters', 3);
		UserRights::Login('test_dbsearch_applydatafilters');

		// Create a person
		$oCreatedPerson = $this->CreatePerson(microtime());

		// Try to retrieve it using the select filter
		$oSearch = DBObjectSearch::FromOQL("SELECT Person WHERE id = {$oCreatedPerson->GetKey()}");
		$oFilteredSearch = $this->InvokeNonPublicMethod(DBObjectSearch::class, 'ApplyDataFilters', $oSearch);

		// Restore original select module to not interfere with next tests
		UserRights::SelectModule($sPreviousSelectModuleClass);

		$this->assertEquals(DBUnionSearch::class, get_class($oFilteredSearch), "DBObjectSearch::ApplyDataFilters() should be able to return a \DBUnionSearch");
	}
}
