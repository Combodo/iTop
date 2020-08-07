<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DBSearch;

/**
 * Class DBSearchIntersectTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBSearchIntersectTest extends ItopTestCase
{

	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');
	}

	/**
	 * @dataProvider FilterProvider
	 *
	 * @param $sLeftSelect
	 * @param $sRightSelect
	 * @param $sClassAlias
	 * @param $sResult
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testFilter($sLeftSelect, $sRightSelect, $sClassAlias, $sResult)
	{
		$oLeftSearch = DBSearch::FromOQL($sLeftSelect);
		$oRightSearch = DBSearch::FromOQL($sRightSelect);

		$oResultSearch = $oLeftSearch->Filter($sClassAlias, $oRightSearch);
		CMDBSource::TestQuery($oResultSearch->MakeSelectQuery());
		$this->assertEquals($sResult, $oResultSearch->ToOQL());
	}

	public function FilterProvider()
	{
		$aTests = array();

		$aTests['Union filtered by parent class'] = array(
			'left' => "SELECT ApplicationSolution UNION SELECT BusinessProcess",
			'right' => "SELECT FunctionalCI WHERE org_id = 3",
			'alias' => "ApplicationSolution",
			'result' => "SELECT `ApplicationSolution` FROM ApplicationSolution AS `ApplicationSolution` WHERE (`ApplicationSolution`.`org_id` = 3) UNION SELECT `BusinessProcess` FROM BusinessProcess AS `BusinessProcess` WHERE (`BusinessProcess`.`org_id` = 3)");

// Bug to fix
//		$aTests['Test union #2902'] = array(
//			'left' => "SELECT `L-1` FROM ServiceFamily AS `L-1` WHERE 1",
//			'right' => "SELECT `sf` FROM ServiceFamily AS `sf` JOIN Service AS `s` ON `s`.servicefamily_id = `sf`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE (`cc`.`org_id` = 3) UNION SELECT `sf` FROM ServiceFamily AS `sf` JOIN Service AS `s` ON `s`.servicefamily_id = `sf`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN Organization AS `child` ON `cc`.org_id = `child`.id JOIN Organization AS `root` ON `child`.parent_id BELOW `root`.id WHERE (`root`.`id` = 3)",
//			'alias' => "L-1",
//			'result' => "SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `s` ON `s`.servicefamily_id = `L-1`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id WHERE (`cc`.`org_id` = 3) UNION SELECT `L-1` FROM ServiceFamily AS `L-1` JOIN Service AS `s` ON `s`.servicefamily_id = `L-1`.id JOIN lnkCustomerContractToService AS `l1` ON `l1`.service_id = `s`.id JOIN CustomerContract AS `cc` ON `l1`.customercontract_id = `cc`.id JOIN Organization AS `child` ON `cc`.org_id = `child`.id JOIN Organization AS `root` ON `child`.parent_id BELOW `root`.id WHERE (`root`.`id` = 3)");

		$aTests['Multiple selected classes inverted'] = array(
			'left' => "SELECT `L`, `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1",
			'right' => "SELECT Person WHERE org_id = 3",
			'alias' => "P",
			'result' => "SELECT `L`, `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE (`P`.`org_id` = 3)");

		$aTests['Multiple selected classes inverted 1'] = array(
			'left' => "SELECT `L`, `P`, `D` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id JOIN PC AS D ON D.location_id = L.id JOIN Person AS P2 ON P.manager_id = P2.id WHERE 1",
			'right' => "SELECT Location WHERE org_id = 3",
			'alias' => "L",
			'result' => "SELECT `L`, `P`, `D` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id JOIN PC AS `D` ON `D`.location_id = `L`.id JOIN Person AS `P2` ON `P`.manager_id = `P2`.id WHERE (`L`.`org_id` = 3)");

		$aTests['Multiple selected classes inverted 2'] = array(
			'left' => "SELECT `L`, `P`, `D` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id JOIN PC AS D ON D.location_id = L.id JOIN Person AS P2 ON P.manager_id = P2.id WHERE (`L`.`org_id` = 3)",
			'right' => "SELECT Person WHERE org_id = 3",
			'alias' => "P",
			'result' => "SELECT `L`, `P`, `D` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id JOIN PC AS `D` ON `D`.location_id = `L`.id JOIN Person AS `P2` ON `P`.manager_id = `P2`.id WHERE ((`L`.`org_id` = 3) AND (`P`.`org_id` = 3))");

		$aTests['Same class'] = array(
			'left' => "SELECT Contact WHERE name = 'Christie'",
			'right' => "SELECT Contact WHERE org_id = 3",
			'alias' => "Contact",
			'result' => "SELECT `Contact` FROM Contact AS `Contact` WHERE ((`Contact`.`name` = 'Christie') AND (`Contact`.`org_id` = 3))");

		$aTests['Different Alias'] = array(
			'left' => "SELECT Contact AS C WHERE C.name = 'Christie'",
			'right' => "SELECT Contact AS CC WHERE CC.org_id = 3",
			'alias' => "C",
			'result' => "SELECT `C` FROM Contact AS `C` WHERE ((`C`.`name` = 'Christie') AND (`C`.`org_id` = 3))");

		$aTests['Multiple selected classes'] = array(
			'left' => "SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1",
			'right' => "SELECT Location WHERE org_id = 3",
			'alias' => "L",
			'result' => "SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE (`L`.`org_id` = 3)");

		$aTests['Joined classes'] = array(
			'left' => "SELECT `L` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1",
			'right' => "SELECT Person WHERE org_id = 3",
			'alias' => "P",
			'result' => "SELECT `L` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE (`P`.`org_id` = 3)");

		$aTests['Joined filter'] = array(
			'left' => "SELECT `P` FROM Person AS `P` WHERE 1",
			'right' => "SELECT `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE `L`.org_id = 3",
			'alias' => "P",
			'result' => "SELECT `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE (`L`.`org_id` = 3)");

		$aTests['Joined filter on joined classes'] = array(
			'left' => "SELECT `L` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1",
			'right' => "SELECT Person FROM Person AS Person JOIN Location ON Person.location_id = Location.id WHERE Location.org_id = 3",
			'alias' => "P",
			'result' => "SELECT `L` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id JOIN Location AS `Location` ON `P`.location_id = `Location`.id WHERE (`Location`.`org_id` = 3)");

		$aTests['Alias collision'] = array(
			'left' => "SELECT `L` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1",
			'right' => "SELECT Person FROM Person AS Person JOIN Location AS `L` ON Person.location_id = `L`.id WHERE `L`.org_id = 3",
			'alias' => "P",
			'result' => "SELECT `L` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id JOIN Location AS `L1` ON `P`.location_id = `L1`.id WHERE (`L1`.`org_id` = 3)");

		$aTests['Test Subclass1'] = array(
			'left' => "SELECT `U` FROM UserRequest AS `U` WHERE `U`.agent_id = 3",
			'right' => "SELECT `Ticket` WHERE org_id = 3",
			'alias' => "U",
			'result' => "SELECT `U` FROM UserRequest AS `U` WHERE ((`U`.`agent_id` = 3) AND (`U`.`org_id` = 3))");

		$aTests['Test Subclass and join'] = array(
			'left' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Person AS `P` ON `UserRequest`.agent_id = `P`.id  WHERE `UserRequest`.agent_id = 3",
			'right' => "SELECT `Ticket` WHERE org_id = 3",
			'alias' => "UserRequest",
			'result' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Person AS `P` ON `UserRequest`.agent_id = `P`.id WHERE ((`UserRequest`.`agent_id` = 3) AND (`UserRequest`.`org_id` = 3))");

		$aTests['Test Subclass and union'] = array(
			'left' => "SELECT `U` FROM UserRequest AS `U` WHERE `U`.agent_id = 3 UNION SELECT `T` FROM Ticket AS `T` WHERE `T`.agent_id = 3 ",
			'right' => "SELECT `Ticket` WHERE org_id = 3",
			'alias' => "U",
			'result' => "SELECT `U` FROM UserRequest AS `U` WHERE ((`U`.`agent_id` = 3) AND (`U`.`org_id` = 3)) UNION SELECT `T` FROM Ticket AS `T` WHERE ((`T`.`agent_id` = 3) AND (`T`.`org_id` = 3))");



		return $aTests;
	}


	/**
	 * @dataProvider IntersectProvider
	 *
	 * @param $sLeftSelect
	 * @param $sRightSelect
	 * @param $sResult
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testIntersect($sLeftSelect, $sRightSelect, $sResult)
	{
		$oLeftSearch = DBSearch::FromOQL($sLeftSelect);
		$oRightSearch = DBSearch::FromOQL($sRightSelect);

		$oResultSearch = $oLeftSearch->Intersect($oRightSearch);
		$sOQLResult = $oResultSearch->ToOQL();
		CMDBSource::TestQuery($oResultSearch->MakeSelectQuery());
		$this->assertEquals($sResult, $sOQLResult);
	}

	public function IntersectProvider()
	{
		$aTests = array();

		$aTests['Nested selects 2'] = array(
			'left' => "SELECT `U` FROM UserRequest AS `U` WHERE U.agent_id = 3",
			'right' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Person AS `P` ON `UserRequest`.agent_id = `P`.id JOIN Organization AS `Organization` ON `P`.org_id = `Organization`.id WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = `UserRequest`.`org_id`)))",
			'result' => "SELECT `U` FROM UserRequest AS `U` JOIN Person AS `P` ON `U`.agent_id = `P`.id JOIN Organization AS `Organization` ON `P`.org_id = `Organization`.id WHERE ((`U`.`agent_id` = 3) AND (`U`.`org_id` IN (SELECT `Organization1` FROM Organization AS `Organization1` WHERE (`Organization1`.`id` = `U`.`org_id`))))");

		$aTests['Nested selects'] = array(
			'left' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Person AS `P` ON `UserRequest`.agent_id = `P`.id JOIN Organization AS `Organization` ON `P`.org_id = `Organization`.id WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = `UserRequest`.`org_id`)))",
			'right' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE UserRequest.agent_id = 3",
			'result' => "SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Person AS `P` ON `UserRequest`.agent_id = `P`.id JOIN Organization AS `Organization` ON `P`.org_id = `Organization`.id WHERE ((`UserRequest`.`org_id` IN (SELECT `Organization1` FROM Organization AS `Organization1` WHERE (`Organization1`.`id` = `UserRequest`.`org_id`))) AND (`UserRequest`.`agent_id` = 3))");

		$aTests['Multiple selected classes inverted'] = array(
			'left' => "SELECT `L`, `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1",
			'right' => "SELECT Person WHERE org_id = 3",
			'result' => "SELECT `L`, `P` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE (`P`.`org_id` = 3)");

		$aTests['Multiple selected classes inverted 2'] = array(
			'left' => "SELECT `L`, `P`, `D` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id JOIN PC AS D ON D.location_id = L.id JOIN Person AS P2 ON P.manager_id = P2.id WHERE (`L`.`org_id` = 3)",
			'right' => "SELECT Person WHERE org_id = 3",
			'result' => "SELECT `L`, `P`, `D` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id JOIN PC AS `D` ON `D`.location_id = `L`.id JOIN Person AS `P2` ON `P`.manager_id = `P2`.id WHERE ((`L`.`org_id` = 3) AND (`P`.`org_id` = 3))");

		$aTests['Same class'] = array(
			'left' => "SELECT Contact WHERE name = 'Christie'",
			'right' => "SELECT Contact WHERE org_id = 3",
			'result' => "SELECT `Contact` FROM Contact AS `Contact` WHERE ((`Contact`.`name` = 'Christie') AND (`Contact`.`org_id` = 3))");

		$aTests['Different Alias'] = array(
			'left' => "SELECT Contact AS C WHERE C.name = 'Christie'",
			'right' => "SELECT Contact AS CC WHERE CC.org_id = 3",
			'result' => "SELECT `C` FROM Contact AS `C` WHERE ((`C`.`name` = 'Christie') AND (`C`.`org_id` = 3))");

		$aTests['Multiple selected classes'] = array(
			'left' => "SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1",
			'right' => "SELECT Location WHERE org_id = 3",
			'result' => "SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE (`L`.`org_id` = 3)");

		$aTests['Alias collision'] = array(
			'left' => "SELECT `P` FROM Person AS `P` WHERE 1",
			'right' => "SELECT `Person` FROM Person AS `Person` JOIN Person AS `P` ON `P`.manager_id = `Person`.id WHERE `P`.org_id = 3",
			'result' => "SELECT `P` FROM Person AS `P` JOIN Person AS `P1` ON `P1`.manager_id = `P`.id WHERE (`P1`.`org_id` = 3)");

		return $aTests;
	}

	/**
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testIntersectNotOptimizedPointingTo()
	{
		$sBaseQuery = "SELECT l FROM lnkContactToFunctionalCI AS l JOIN Contact AS c ON l.contact_id = c.id";
		$sOQL = "SELECT l FROM lnkContactToFunctionalCI AS l JOIN Person AS p ON l.contact_id = p.id";
		$sResult = "SELECT `l` FROM lnkContactToFunctionalCI AS `l` JOIN Contact AS `c` ON `l`.contact_id = `c`.id JOIN Person AS `p` ON `l`.contact_id = `p`.id WHERE 1";

		$oSearchA = DBSearch::FromOQL($sBaseQuery);
		$oSearchB = DBSearch::FromOQL($sOQL);
		$oIntersect = $oSearchA->Intersect($oSearchB);
		CMDBSource::TestQuery($oIntersect->MakeSelectQuery());
		$this->assertEquals($sResult, $oIntersect->ToOQL());
	}

	/**
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testIntersectNotOptimizedReferencedBy()
	{
		$sBaseQuery = "SELECT o FROM Organization AS o JOIN Contact AS c ON c.org_id = o.id WHERE c.id = 1";
		$sOQL = "SELECT o FROM Organization AS o JOIN Person AS p ON p.org_id = o.id WHERE p.id = 2";
		$sResult = "SELECT `o` FROM Organization AS `o` JOIN Contact AS `c` ON `c`.org_id = `o`.id JOIN Person AS `p` ON `p`.org_id = `o`.id WHERE ((`c`.`id` = 1) AND (`p`.`id` = 2))";

		$oSearchA = DBSearch::FromOQL($sBaseQuery);
		$oSearchB = DBSearch::FromOQL($sOQL);
		$oIntersect = $oSearchA->Intersect($oSearchB);
		CMDBSource::TestQuery($oIntersect->MakeSelectQuery());
		$this->assertEquals($sResult, $oIntersect->ToOQL());
	}

	/**
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testIntersectOptimizedReferencedBy()
	{
		$sBaseQuery = "SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = 'some name'";
		$sQueryA = "SELECT r FROM UserRequest AS r JOIN Service AS s ON r.service_id = s.id JOIN Organization AS o ON s.org_id = o.id WHERE r.agent_id = 456 AND s.servicefamily_id = 789 AND o.name = 'right_name'";
		$sResult = "SELECT `s` FROM Service AS `s` JOIN Organization AS `o` ON `s`.org_id = `o`.id JOIN UserRequest AS `r` ON `r`.service_id = `s`.id WHERE ((`o`.`name` = 'some name') AND (((`r`.`agent_id` = 456) AND (`s`.`servicefamily_id` = 789)) AND (`o`.`name` = 'right_name')))";

		$oSearchA = DBSearch::FromOQL($sQueryA);
		$oSearchB = DBSearch::FromOQL($sBaseQuery);
		$oSearchB->AddCondition_ReferencedBy($oSearchA, 'service_id');
		CMDBSource::TestQuery($oSearchB->MakeSelectQuery());
		$this->assertEquals($sResult, $oSearchB->ToOQL());
	}

	/**
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testIntersectNotOptimizedAddConditionPointingTo()
	{
		$sBaseQuery = "SELECT Person FROM Person AS Person";
		$sQueryA = "SELECT o FROM Organization AS o JOIN Contact AS c ON c.org_id = o.id";
		$sResult = "SELECT `Person` FROM Person AS `Person` JOIN Organization AS `o` ON `Person`.org_id = `o`.id JOIN Contact AS `c` ON `c`.org_id = `o`.id WHERE 1";

		$oSearchA = DBSearch::FromOQL($sQueryA);
		$oSearchB = DBSearch::FromOQL($sBaseQuery);
		$oSearchB->AddCondition_PointingTo($oSearchA, 'org_id');
		CMDBSource::TestQuery($oSearchB->MakeSelectQuery());
		$this->assertEquals($sResult, $oSearchB->ToOQL());
	}

	/**
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testIntersectOptimizedAddConditionPointingTo()
	{
		$sBaseQuery = "SELECT ur FROM UserRequest AS ur JOIN Person AS p ON ur.agent_id = p.id WHERE p.status != 'terminated'";
		$sQueryA = "SELECT o FROM Organization AS o JOIN UserRequest AS r ON r.org_id = o.id JOIN Person AS p ON r.caller_id = p.id WHERE o.name LIKE 'Company' AND r.service_id = 123 AND p.employee_number LIKE '007'";
		$sResult = "SELECT `ur` FROM UserRequest AS `ur` JOIN Person AS `p` ON `ur`.agent_id = `p`.id JOIN Organization AS `o` ON `ur`.org_id = `o`.id JOIN Person AS `p11` ON `ur`.caller_id = `p11`.id WHERE ((`p`.`status` != 'terminated') AND (((`o`.`name` LIKE 'Company') AND (`ur`.`service_id` = 123)) AND (`p11`.`employee_number` LIKE '007')))";

		$oSearchA = DBSearch::FromOQL($sQueryA);
		$oSearchB = DBSearch::FromOQL($sBaseQuery);
		$oSearchB->AddCondition_PointingTo($oSearchA, 'org_id');
		CMDBSource::TestQuery($oSearchB->MakeSelectQuery());
		$this->assertEquals($sResult, $oSearchB->ToOQL());
	}

	/**
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testIntersectNotOptimizedAddConditionReferencedBy()
	{
		$sBaseQuery = "SELECT Person FROM Person AS Person";
		$sQueryA = "SELECT l FROM lnkContactToFunctionalCI AS l JOIN Contact AS c ON l.contact_id = c.id";
		$sResult = "SELECT `Person` FROM Person AS `Person` JOIN lnkContactToFunctionalCI AS `l` ON `l`.contact_id = `Person`.id JOIN Contact AS `c` ON `l`.contact_id = `c`.id WHERE 1";

		$oSearchA = DBSearch::FromOQL($sQueryA);
		$oSearchB = DBSearch::FromOQL($sBaseQuery);
		$oSearchB->AddCondition_ReferencedBy($oSearchA, 'contact_id');
		CMDBSource::TestQuery($oSearchA->MakeSelectQuery());
		$this->assertEquals($sResult, $oSearchB->ToOQL());
	}

	/**
	 * @dataProvider IntersectOptimizationProvider
	 * @doesNotPerformAssertions
	 *
	 * @param string $sOQL
	 * @param string $sResult
	 *
	 * @throws \CoreException
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function testIntersectOptimization($sBaseQuery, $sOQL, $sResult)
	{
		$oSearchA = DBSearch::FromOQL($sBaseQuery);
		$oSearchB = DBSearch::FromOQL($sOQL);
		$oIntersect = $oSearchA->Intersect($oSearchB);
		CMDBSource::TestQuery($oIntersect->MakeSelectQuery());
		$this->assertEquals($sResult, $oIntersect->ToOQL());
	}

	public function IntersectOptimizationProvider()
	{
		$aQueries = array(
			'Exact same query' => array(
				'Base Query' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "The World Company"',
				'Filter OQL' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "The World Company"',
				'Result    ' => "SELECT `s` FROM Service AS `s` JOIN Organization AS `o` ON `s`.org_id = `o`.id WHERE ((`o`.`name` = 'The World Company') AND (`o`.`name` = 'The World Company'))",
			),
			'Same query, other aliases' => array(
				'Base Query' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "The World Company"',
				'Filter OQL' => 'SELECT s2 FROM Service AS s2 JOIN Organization AS o2 ON s2.org_id = o2.id WHERE o2.name = "The World Company"',
				'Result    ' => "SELECT `s` FROM Service AS `s` JOIN Organization AS `o` ON `s`.org_id = `o`.id WHERE ((`o`.`name` = 'The World Company') AND (`o`.`name` = 'The World Company'))",
			),
			'Same aliases, different condition' => array(
				'Base Query' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "The World Company"',
				'Filter OQL' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.parent_id = 0',
				'Result    ' => "SELECT `s` FROM Service AS `s` JOIN Organization AS `o` ON `s`.org_id = `o`.id WHERE ((`o`.`name` = 'The World Company') AND (`o`.`parent_id` = 0))",
			),
			'Other aliases, different condition' => array(
				'Base Query' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "The World Company"',
				'Filter OQL' => 'SELECT s2 FROM Service AS s2 JOIN Organization AS o2 ON s2.org_id = o2.id WHERE o2.parent_id = 0',
				'Result    ' => "SELECT `s` FROM Service AS `s` JOIN Organization AS `o` ON `s`.org_id = `o`.id WHERE ((`o`.`name` = 'The World Company') AND (`o`.`parent_id` = 0))",
			),
			'Same aliases, simpler query tree' => array(
				'Base Query' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "The World Company"',
				'Filter OQL' => 'SELECT s FROM Service AS s WHERE name LIKE "Save the World"',
				'Result    ' => "SELECT `s` FROM Service AS `s` JOIN Organization AS `o` ON `s`.org_id = `o`.id WHERE ((`o`.`name` = 'The World Company') AND (`s`.`name` LIKE 'Save the World'))",
			),
			'Other aliases, simpler query tree' => array(
				'Base Query' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "The World Company"',
				'Filter OQL' => 'SELECT s2 FROM Service AS s2 WHERE name LIKE "Save the World"',
				'Result    ' => "SELECT `s` FROM Service AS `s` JOIN Organization AS `o` ON `s`.org_id = `o`.id WHERE ((`o`.`name` = 'The World Company') AND (`s`.`name` LIKE 'Save the World'))",
			),
			'Same aliases, different query tree' => array(
				'Base Query' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "The World Company"',
				'Filter OQL' => 'SELECT s FROM Service AS s JOIN ServiceFamily AS f ON s.servicefamily_id = f.id WHERE s.org_id = 123 AND f.name = "Care"',
				'Result    ' => "SELECT `s` FROM Service AS `s` JOIN Organization AS `o` ON `s`.org_id = `o`.id JOIN ServiceFamily AS `f` ON `s`.servicefamily_id = `f`.id WHERE ((`o`.`name` = 'The World Company') AND ((`s`.`org_id` = 123) AND (`f`.`name` = 'Care')))",
			),
			'Other aliases, different query tree' => array(
				'Base Query' => 'SELECT s FROM Service AS s JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "The World Company"',
				'Filter OQL' => 'SELECT s2 FROM Service AS s2 JOIN ServiceFamily AS f ON s2.servicefamily_id = f.id WHERE s2.org_id = 123 AND f.name = "Care"',
				'Result    ' => "SELECT `s` FROM Service AS `s` JOIN Organization AS `o` ON `s`.org_id = `o`.id JOIN ServiceFamily AS `f` ON `s`.servicefamily_id = `f`.id WHERE ((`o`.`name` = 'The World Company') AND ((`s`.`org_id` = 123) AND (`f`.`name` = 'Care')))",
			),

			'2 - Exact same query' => array(
				'Base Query' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.name = "Help"',
				'Filter OQL' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.name = "Help"',
				'Result    ' => "SELECT `o` FROM Organization AS `o` JOIN Service AS `s` ON `s`.org_id = `o`.id WHERE ((`s`.`name` = 'Help') AND (`s`.`name` = 'Help'))",
			),
			'2 - Same query, other aliases' => array(
				'Base Query' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.name = "Help"',
				'Filter OQL' => 'SELECT o2 FROM Organization AS o2 JOIN Service AS s2 ON s2.org_id = o2.id WHERE s2.name = "Help"',
				'Result    ' => "SELECT `o` FROM Organization AS `o` JOIN Service AS `s` ON `s`.org_id = `o`.id WHERE ((`s`.`name` = 'Help') AND (`s`.`name` = 'Help'))",
			),
			'2 - Same aliases, different condition' => array(
				'Base Query' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.name = "Help"',
				'Filter OQL' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.servicefamily_id = 321',
				'Result    ' => "SELECT `o` FROM Organization AS `o` JOIN Service AS `s` ON `s`.org_id = `o`.id WHERE ((`s`.`name` = 'Help') AND (`s`.`servicefamily_id` = 321))",
			),
			'2 - Other aliases, different condition' => array(
				'Base Query' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.name = "Help"',
				'Filter OQL' => 'SELECT o2 FROM Organization AS o2 JOIN Service AS s2 ON s2.org_id = o2.id WHERE s2.servicefamily_id = 321',
				'Result    ' => "SELECT `o` FROM Organization AS `o` JOIN Service AS `s` ON `s`.org_id = `o`.id WHERE ((`s`.`name` = 'Help') AND (`s`.`servicefamily_id` = 321))",
			),
			'2 - Same aliases, simpler query tree' => array(
				'Base Query' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.name = "Help"',
				'Filter OQL' => 'SELECT o FROM Organization AS o WHERE o.name = "Demo"',
				'Result    ' => "SELECT `o` FROM Organization AS `o` JOIN Service AS `s` ON `s`.org_id = `o`.id WHERE ((`s`.`name` = 'Help') AND (`o`.`name` = 'Demo'))",
			),
			'2 - Other aliases, simpler query tree' => array(
				'Base Query' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.name = "Help"',
				'Filter OQL' => 'SELECT o2 FROM Organization AS o2 WHERE o2.name = "Demo"',
				'Result    ' => "SELECT `o` FROM Organization AS `o` JOIN Service AS `s` ON `s`.org_id = `o`.id WHERE ((`s`.`name` = 'Help') AND (`o`.`name` = 'Demo'))",
			),
			'2 - Same aliases, different query tree' => array(
				'Base Query' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.name = "Help"',
				'Filter OQL' => 'SELECT o FROM Organization AS o JOIN Location AS l ON l.org_id = o.id WHERE l.name = "Paris"',
				'Result    ' => "SELECT `o` FROM Organization AS `o` JOIN Service AS `s` ON `s`.org_id = `o`.id JOIN Location AS `l` ON `l`.org_id = `o`.id WHERE ((`s`.`name` = 'Help') AND (`l`.`name` = 'Paris'))",
			),
			'2 - Other aliases, different query tree' => array(
				'Base Query' => 'SELECT o FROM Organization AS o JOIN Service AS s ON s.org_id = o.id WHERE s.name = "Help"',
				'Filter OQL' => 'SELECT o2 FROM Organization AS o2 JOIN Location AS l ON l.org_id = o2.id WHERE l.name = "Paris"',
				'Result    ' => "SELECT `o` FROM Organization AS `o` JOIN Service AS `s` ON `s`.org_id = `o`.id JOIN Location AS `l` ON `l`.org_id = `o`.id WHERE ((`s`.`name` = 'Help') AND (`l`.`name` = 'Paris'))",
			),

			'Internal query optimizations 1' => array(
				'Base Query' => 'SELECT o FROM Organization AS o',
				'Filter OQL' => 'SELECT o FROM Organization AS o JOIN Location AS l ON l.org_id = o.id JOIN Organization AS p ON o.parent_id = p.id WHERE l.name = "Paris" AND p.code LIKE "toto"',
				'Result    ' => "SELECT `o` FROM Organization AS `o` JOIN Organization AS `p` ON `o`.parent_id = `p`.id JOIN Location AS `l` ON `l`.org_id = `o`.id WHERE ((`l`.`name` = 'Paris') AND (`p`.`code` LIKE 'toto'))",
			),
			'Internal query optimizations 2' => array(
				'Base Query' => 'SELECT r FROM UserRequest AS r JOIN Service AS s ON r.service_id = s.id JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "left_name"',
				'Filter OQL' => 'SELECT r FROM UserRequest AS r JOIN Service AS s ON r.service_id = s.id JOIN Organization AS o ON s.org_id = o.id WHERE o.name = "right_name"',
				'Result    ' => "SELECT `r` FROM UserRequest AS `r` JOIN Service AS `s` ON `r`.service_id = `s`.id JOIN Organization AS `o` ON `s`.org_id = `o`.id WHERE ((`o`.`name` = 'left_name') AND (`o`.`name` = 'right_name'))",
			),
		);

		return $aQueries;
	}

	/**
	 * Bug #2970
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \OQLException
	 */
	public function testFilterOnJoin()
	{
		$sReq1 = "SELECT `L-1` FROM Organization AS `L-1` WHERE (`L-1`.`id` = :current_contact->org_id)";
		$sReq2 = "SELECT `L-1-1` FROM CustomerContract AS `L-1-1` JOIN Organization AS `O` ON `L-1-1`.org_id = `O`.id WHERE (((`L-1-1`.`status` = 'active') OR (`L-1-1`.`status` = 'standby')) AND (`O`.`id` = :current_contact->org_id))";

		$oFilter1 = DBSearch::FromOQL($sReq1);
		$oFilter2 = DBSearch::FromOQL($sReq2);
		$aRealiasingMap = array();
		$oFilter1 = $oFilter1->Join($oFilter2,
			DBSearch::JOIN_REFERENCED_BY,
			'org_id',
			TREE_OPERATOR_EQUALS, $aRealiasingMap);

		$sRes1 = $oFilter1->ToOQL();
		$this->debug($sRes1);

		foreach($oFilter1->GetCriteria_ReferencedBy() as $sForeignClass => $aReferences)
		{
			foreach ($aReferences as $sForeignExtKeyAttCode => $aFiltersByOperator)
			{
				foreach ($aFiltersByOperator as $iOperatorCode => $aFilters)
				{
					foreach ($aFilters as $index => $oForeignFilter)
					{
						$this->debug($oForeignFilter->ToOQL());
					}
				}
			}
		}

		$this->assertFalse(strpos($sRes1, '`O`.'));

		$sReq3 = "SELECT `CustomerContract` FROM CustomerContract AS `CustomerContract` WHERE (`CustomerContract`.`org_id` IN ('2'))";
		$oFilter3 = DBSearch::FromOQL($sReq3);

		$oFilter1 = $oFilter1->Filter('L-1-1', $oFilter3);

		$sRes1 = $oFilter1->ToOQL();
		$this->debug($sRes1);

		$this->assertFalse(strpos($sRes1, '`O`.'));
	}
}
