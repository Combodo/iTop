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
		$sSourceOQL = 'SELECT `Person`, `Location` FROM Person AS `Person` JOIN Location AS `Location` ON `Person`.location_id = `Location`.id WHERE 1';
		$oSearch = DBSearch::FromOQL($sSourceOQL);

		$sFilterOQL = 'SELECT `Person` FROM Person AS `Person` WHERE (`Person`.`id` = 1) UNION SELECT `Person` FROM Person AS `Person` WHERE (`Person`.`id` = 1)';
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
	public function FilterOnSecondSelectedClass()
	{
		$sSourceOQL = 'SELECT `Person`, `Location` FROM Person JOIN Location ON `Person`.location_id = `Location`.id';
		$oSearch = DBSearch::FromOQL($sSourceOQL);

		$sFilterOQL = 'SELECT Location UNION SELECT Location';
		$oVisibleObjects = DBSearch::FromOQL($sFilterOQL);
		$sClassAlias = 'Location';
		$oVisibleObjects->AllowAllData();
		$oSearch = $oSearch->Filter($sClassAlias, $oVisibleObjects);

		// $oSearch->ToOQL();

		$oSet = new DBObjectSet($oSearch);
		$oSet->CountWithLimit(1);

		$this->assertTrue(true);
	}
}
