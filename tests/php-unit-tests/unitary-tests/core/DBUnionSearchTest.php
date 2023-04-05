<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBUnionSearchTest extends ItopDataTestCase
{

	public function testFilterOnFirstSelectedClass()
	{
		$sSourceOQL = 'SELECT `Person`, `Location` FROM Person AS `Person` JOIN Location AS `Location` ON `Person`.location_id = `Location`.id WHERE (`Location`.`id` = 1)';
		$oSearch = DBSearch::FromOQL($sSourceOQL);

		$sFilterOQL = 'SELECT `Person` FROM Person AS `Person` WHERE (`Person`.`id` = 2) UNION SELECT `Person` FROM Person AS `Person` WHERE (`Person`.`id` = 3)';
		$oVisibleObjects = DBSearch::FromOQL($sFilterOQL);
		$sClassAlias = 'Person';
		$oVisibleObjects->AllowAllData();
		$oSearch = $oSearch->Filter($sClassAlias, $oVisibleObjects);
		$this->InvokeNonPublicMethod(get_class($oSearch), 'SetDataFiltered', $oSearch, []);

		$this->debug($oSearch->ToOQL());

		$oSet = new DBObjectSet($oSearch);
		$oSet->Count();

		$this->assertTrue(true);
	}

	/**
	 * Ignored test (provokes PHP Error)
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function testFilterOnSecondSelectedClass()
	{
		$sSourceOQL = 'SELECT P1, L1 FROM Person AS P1 JOIN Location AS L1 ON P1.location_id = L1.id WHERE L1.id = 1';
		$oSearch = DBSearch::FromOQL($sSourceOQL);

		$sFilterOQL = 'SELECT Location AS L2 WHERE L2.id = 2 UNION SELECT Location AS L3 WHERE L3.id = 3';
		$oVisibleObjects = DBSearch::FromOQL($sFilterOQL);
		$sClassAlias = 'L1';
		$oVisibleObjects->AllowAllData();
		$oSearch = $oSearch->Filter($sClassAlias, $oVisibleObjects);

		$oSearch->ToOQL();

		$oSet = new DBObjectSet($oSearch);
		$oSet->CountWithLimit(1);

		$this->assertTrue(true);
	}
}
