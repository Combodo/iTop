<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class DBUnionSearchTest extends ItopDataTestCase
{

	/**
	 * @dataProvider UnionSearchProvider
	 *
	 * @param $sOQL
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function testUnionSearch($sOQL)
	{
		$oSearch = DBSearch::FromOQL($sOQL);

		$oSet = new DBObjectSet($oSearch);
		if ($oSet->Count() > 0) {
			$aSelectedAliases = array_keys($oSearch->GetSelectedClasses());
			$aFirstRow = $oSet->FetchAssoc();
			$aAliases = array_keys($aFirstRow);
			$this->assertEquals($aSelectedAliases, $aAliases);
		}

		$aSelectedClasses = $oSearch->GetSelectedClasses();
		foreach ($aSelectedClasses as $sSelectedAlias => $sSelectedClass) {
			$oSearchTest = $oSearch->DeepClone();
			$oSearchTest->SetSelectedClasses([$sSelectedAlias]);
			$oSet = new DBObjectSet($oSearchTest);
			if ($oSet->Count() > 0) {
				$aSelectedAliases = array_keys($oSearchTest->GetSelectedClasses());
				$aFirstRow = $oSet->FetchAssoc();
				$aAliases = array_keys($aFirstRow);
				$this->assertEquals($aSelectedAliases, $aAliases);
			}
		}
	}

	public function UnionSearchProvider()
	{
		return [
			'Same class' => ["SELECT Server UNION SELECT Server"],
			'different class same alias' => ['SELECT Server AS fci UNION SELECT VirtualMachine AS fci'],
			'different class no alias' => ['SELECT Server UNION SELECT VirtualMachine'],
			'multiple classes same alias' => ['SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE (`L`.`org_id` = 3) UNION SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE (`L`.`org_id` = 2)'],
			'multiple classes' => ['SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE (`L`.`org_id` = 3) UNION SELECT `L1`, `P1` FROM Person AS `P1` JOIN Location AS `L1` ON `P1`.location_id = `L1`.id WHERE (`P1`.`org_id` = 2)'],
		];
	}

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
	 * @dataProvider FilterOnSecondSelectedClassProvider
	 * @return void
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function testFilterOnSecondSelectedClass($sSourceOQL, $sClassAlias, $sFilterOQL, $sExpected)
	{
		$oSearch = DBSearch::FromOQL($sSourceOQL);

		$oVisibleObjects = DBSearch::FromOQL($sFilterOQL);
		$oVisibleObjects->AllowAllData();
		$oSearch = $oSearch->Filter($sClassAlias, $oVisibleObjects);

		$sResult = $oSearch->ToOQL();
		$this->debug($sResult);

		$this->assertEquals($sExpected, $sResult);

		$oSet = new DBObjectSet($oSearch);
		$oSet->CountWithLimit(1);

		$this->assertTrue(true);
	}

	public function FilterOnSecondSelectedClassProvider()
	{
		return [
			[
				'sSourceOQL'  => "SELECT P1, L1 FROM Person AS P1 JOIN Location AS L1 ON P1.location_id = L1.id WHERE L1.id = 1",
				'sClassAlias' => "L1",
				'sFilterOQL'  => "SELECT Location AS L2 WHERE L2.id = 2 UNION SELECT Location AS L3 WHERE L3.id = 3",
				'sExpected'   => "SELECT `P1`, `L1` FROM Person AS `P1` JOIN Location AS `L1` ON `P1`.location_id = `L1`.id WHERE ((`L1`.`id` = 1) AND (`L1`.`id` = 2)) UNION SELECT `P1`, `L1` FROM Person AS `P1` JOIN Location AS `L1` ON `P1`.location_id = `L1`.id WHERE ((`L1`.`id` = 1) AND (`L1`.`id` = 3))",
			],
			[
				'sSourceOQL'  => 'SELECT L1, P1 FROM Person AS P1 JOIN Location AS L1 ON P1.location_id = L1.id WHERE L1.id = 1',
				'sClassAlias' => 'L1',
				'sFilterOQL'  => 'SELECT Location AS L2 WHERE L2.id = 2 UNION SELECT Location AS L3 WHERE L3.id = 3',
				'sExpected'   => 'SELECT `L1`, `P1` FROM Person AS `P1` JOIN Location AS `L1` ON `P1`.location_id = `L1`.id WHERE ((`L1`.`id` = 1) AND (`L1`.`id` = 2)) UNION SELECT `L1`, `P1` FROM Person AS `P1` JOIN Location AS `L1` ON `P1`.location_id = `L1`.id WHERE ((`L1`.`id` = 1) AND (`L1`.`id` = 3))',
			],
			[
				'sSourceOQL'  => "SELECT L1,O1 FROM Location AS L1 JOIN Organization AS O1 ON L1.org_id=O1.id JOIN Organization AS O2 ON O1.parent_id = O2.id WHERE L1.name != 'l1-name' AND O1.name != 'o1-name' AND ISNULL(O2.name) != 0",
				'sClassAlias' => "O1",
				'sFilterOQL'  => "SELECT Organization AS O1 WHERE O1.id = 2 UNION SELECT Organization AS O2 WHERE O2.id = 3",
				'sExpected'   => "SELECT `L1`, `O1` FROM Location AS `L1` JOIN Organization AS `O1` ON `L1`.org_id = `O1`.id JOIN Organization AS `O2` ON `O1`.parent_id = `O2`.id WHERE ((((`L1`.`name` != 'l1-name') AND (`O1`.`name` != 'o1-name')) AND (ISNULL(`O2`.`name`) != 0)) AND (`O1`.`id` = 2)) UNION SELECT `L1`, `O1` FROM Location AS `L1` JOIN Organization AS `O1` ON `L1`.org_id = `O1`.id JOIN Organization AS `O2` ON `O1`.parent_id = `O2`.id WHERE ((((`L1`.`name` != 'l1-name') AND (`O1`.`name` != 'o1-name')) AND (ISNULL(`O2`.`name`) != 0)) AND (`O1`.`id` = 3))",
			],
			[
				'sSourceOQL'  => "SELECT L1,O2 FROM Location AS L1 JOIN Organization AS O1 ON L1.org_id=O1.id JOIN Organization AS O2 ON O1.parent_id = O2.id WHERE L1.name != 'l1-name' AND O1.name != 'o1-name' AND ISNULL(O2.name) != 0",
				'sClassAlias' => 'O2',
				'sFilterOQL'  => 'SELECT Organization AS O1 WHERE O1.id = 2 UNION SELECT Organization AS O2 WHERE O2.id = 3',
				'sExpected'   => "SELECT `L1`, `O2` FROM Location AS `L1` JOIN Organization AS `O1` ON `L1`.org_id = `O1`.id JOIN Organization AS `O2` ON `O1`.parent_id = `O2`.id WHERE ((((`L1`.`name` != 'l1-name') AND (`O1`.`name` != 'o1-name')) AND (ISNULL(`O2`.`name`) != 0)) AND (`O2`.`id` = 2)) UNION SELECT `L1`, `O2` FROM Location AS `L1` JOIN Organization AS `O1` ON `L1`.org_id = `O1`.id JOIN Organization AS `O2` ON `O1`.parent_id = `O2`.id WHERE ((((`L1`.`name` != 'l1-name') AND (`O1`.`name` != 'o1-name')) AND (ISNULL(`O2`.`name`) != 0)) AND (`O2`.`id` = 3))",
			],
			[
				'sSourceOQL'  => "SELECT L1,O1 FROM Location AS L1 JOIN Organization AS O1 ON L1.org_id=O1.id JOIN Organization AS O2 ON O1.parent_id = O2.id WHERE L1.name != 'l1-name' AND O1.name != 'o1-name' AND ISNULL(O2.name) != 0",
				'sClassAlias' => 'O2',
				'sFilterOQL'  => 'SELECT Organization AS O1 WHERE O1.id = 2 UNION SELECT Organization AS O2 WHERE O2.id = 3',
				// This is another problem, we should not be able to filter on not selected classes
				'sExpected'   => "SELECT `L1`, `O1` FROM Location AS `L1` JOIN Organization AS `O1` ON `L1`.org_id = `O1`.id JOIN Organization AS `O2` ON `O1`.parent_id = `O2`.id WHERE ((((`L1`.`name` != 'l1-name') AND (`O1`.`name` != 'o1-name')) AND (ISNULL(`O2`.`name`) != 0)) AND (`O2`.`id` = 2)) UNION SELECT `L1`, `O1` FROM Location AS `L1` JOIN Organization AS `O1` ON `L1`.org_id = `O1`.id JOIN Organization AS `O2` ON `O1`.parent_id = `O2`.id WHERE ((((`L1`.`name` != 'l1-name') AND (`O1`.`name` != 'o1-name')) AND (ISNULL(`O2`.`name`) != 0)) AND (`O2`.`id` = 3))",
			],
			[
				'sSourceOQL'  => "SELECT P1, O1 FROM Person AS P1 JOIN Organization AS O1 ON P1.org_id = O1.id JOIN Location AS L1 ON P1.location_id = L1.id JOIN Organization AS O2 ON L1.org_id = O2.id WHERE L1.name != '' AND P1.name != '' AND O1.name != '' AND O2.name != ''",
				'sClassAlias' => 'O1',
				'sFilterOQL'  => 'SELECT Organization AS O1 WHERE O1.id = 2 UNION SELECT Organization AS O2 WHERE O2.id = 3',
				'sExpected'   => "SELECT `P1`, `O1` FROM Person AS `P1` JOIN Organization AS `O1` ON `P1`.org_id = `O1`.id JOIN Location AS `L1` ON `P1`.location_id = `L1`.id JOIN Organization AS `O2` ON `L1`.org_id = `O2`.id WHERE (((((`L1`.`name` != '') AND (`P1`.`name` != '')) AND (`O1`.`name` != '')) AND (`O2`.`name` != '')) AND (`O1`.`id` = 2)) UNION SELECT `P1`, `O1` FROM Person AS `P1` JOIN Organization AS `O1` ON `P1`.org_id = `O1`.id JOIN Location AS `L1` ON `P1`.location_id = `L1`.id JOIN Organization AS `O2` ON `L1`.org_id = `O2`.id WHERE (((((`L1`.`name` != '') AND (`P1`.`name` != '')) AND (`O1`.`name` != '')) AND (`O2`.`name` != '')) AND (`O1`.`id` = 3))",
			],
			[
				'sSourceOQL'  => "SELECT P1, O2 FROM Person AS P1 JOIN Organization AS O1 ON P1.org_id = O1.id JOIN Location AS L1 ON P1.location_id = L1.id JOIN Organization AS O2 ON L1.org_id = O2.id WHERE L1.name != '' AND P1.name != '' AND O1.name != '' AND O2.name != ''",
				'sClassAlias' => 'O2',
				'sFilterOQL'  => 'SELECT Organization AS O1 WHERE O1.id = 2 UNION SELECT Organization AS O2 WHERE O2.id = 3',
				'sExpected'   => "SELECT `P1`, `O2` FROM Person AS `P1` JOIN Organization AS `O1` ON `P1`.org_id = `O1`.id JOIN Location AS `L1` ON `P1`.location_id = `L1`.id JOIN Organization AS `O2` ON `L1`.org_id = `O2`.id WHERE (((((`L1`.`name` != '') AND (`P1`.`name` != '')) AND (`O1`.`name` != '')) AND (`O2`.`name` != '')) AND (`O2`.`id` = 2)) UNION SELECT `P1`, `O2` FROM Person AS `P1` JOIN Organization AS `O1` ON `P1`.org_id = `O1`.id JOIN Location AS `L1` ON `P1`.location_id = `L1`.id JOIN Organization AS `O2` ON `L1`.org_id = `O2`.id WHERE (((((`L1`.`name` != '') AND (`P1`.`name` != '')) AND (`O1`.`name` != '')) AND (`O2`.`name` != '')) AND (`O2`.`id` = 3))",
			],
		];
	}

	/**
	 * @dataProvider SetSelectedClassesProvider
	 * @param $sOQL
	 * @param $sSelectedClass
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function testSetSelectedClasses(string $sOQL, array $aSelectedClasses, string $sExpectedOQL)
	{
		$oSearch = DBSearch::FromOQL($sOQL);
		$this->debug($oSearch->ToOQL());
		$this->debug("Set selected classes to [ ".implode(" ,", $aSelectedClasses)." ]");
		$oSearch->SetSelectedClasses($aSelectedClasses);
		$this->debug($oSearch->ToOQL());
		$this->assertEquals($sExpectedOQL, $oSearch->ToOQL());
	}

	public function SetSelectedClassesProvider()
	{
		return [
			'N°6151' => [
				'OQL'             => "SELECT P FROM Person AS P JOIN User AS U ON U.contactid = P.id UNION SELECT P FROM Person AS P JOIN User AS U ON U.contactid = P.id",
				'SelectedClasses' => ['U'],
				'Expected OQL'    => "SELECT `U` FROM Person AS `P` JOIN User AS `U` ON `U`.contactid = `P`.id WHERE 1 UNION SELECT `U` FROM Person AS `P` JOIN User AS `U` ON `U`.contactid = `P`.id WHERE 1",
			],
		];
	}
}
