<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 31/08/2018
 * Time: 17:03
 */

namespace Combodo\iTop\Test\UnitTest\Core;


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DBObjectSearch;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class OQLParserTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

	/**
	 * @dataProvider NestedQueryProvider
	 *
	 * @param $sQuery
	 *
	 * @throws \OQLException
	 */
	public function testGoodNestedQueryQueryParser($sQuery)
	{
		$this->debug($sQuery);
		$oDbObjectSearch = DBObjectSearch::FromOQL($sQuery);
		$sOql=$oDbObjectSearch->ToOQL();
		$this->debug($sOql);
		self::assertEquals($sQuery,$sOql);
	}

	public function NestedQueryProvider()
	{
		return array(
			array('SELECT `UserRequest` FROM UserRequest AS `UserRequest` JOIN Person AS `P` ON `UserRequest`.agent_id = `P`.id JOIN Organization AS `Organization` ON `P`.org_id = `Organization`.id WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = `UserRequest`.`org_id`)))'),
			array('SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = `UserRequest`.`org_id`)))'),
			array('SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE 1))'),
			array('SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`org_id` NOT IN (SELECT `Organization` FROM Organization AS `Organization` WHERE 1))'),
			array('SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` JOIN Organization AS `Organization1` ON `Organization`.parent_id BELOW `Organization1`.id WHERE (`Organization1`.`id` = \'3\')))'),
			array('SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`org_id` IN (SELECT `Organization` FROM Organization AS `Organization` WHERE (`Organization`.`id` = \'3\')))'),
			array("SELECT `L`, `P` FROM Location AS `L` JOIN Person AS `P` ON `P`.location_id = `L`.id WHERE 1"),
			array("SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE ((`UserRequest`.`agent_id` = :current_contact_id) AND (`UserRequest`.`status` NOT IN ('closed', 'resolved')))"),
			array("SELECT `L` FROM Person AS `P` JOIN Location AS `L` ON `P`.location_id = `L`.id WHERE 1"),
		);
	}

}
