<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 31/08/2018
 * Time: 17:03
 */

namespace Combodo\iTop\Test\UnitTest\Core;


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
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
			array("SELECT `U` FROM User AS `U` JOIN Person AS `P` ON `U`.contactid = `P`.id WHERE ((`U`.`status` = 'enabled') AND (`U`.`id` NOT IN (SELECT `U` FROM User AS `U` JOIN Person AS `P` ON `U`.contactid = `P`.id JOIN URP_UserOrg AS `L` ON `L`.userid = `U`.id WHERE ((`U`.`status` = 'enabled') AND (`L`.`allowed_org_id` = `P`.`org_id`)) UNION SELECT `U` FROM User AS `U` WHERE ((`U`.`status` = 'enabled') AND (`U`.`id` NOT IN (SELECT `U` FROM User AS `U` JOIN URP_UserOrg AS `L` ON `L`.userid = `U`.id WHERE (`U`.`status` = 'enabled')))))))"),
			array("SELECT `Ur` FROM UserRequest AS `Ur` WHERE (`Ur`.`id` NOT IN (SELECT `Ur` FROM UserRequest AS `Ur` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `Ur`.id WHERE 1))"),
			array("SELECT `T` FROM Ticket AS `T` WHERE ((`T`.`finalclass` IN ('userrequest', 'change')) AND (`T`.`id` NOT IN (SELECT `Ur` FROM UserRequest AS `Ur` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `Ur`.id WHERE 1 UNION SELECT `C` FROM Change AS `C` JOIN lnkFunctionalCIToTicket AS `lnk` ON `lnk`.ticket_id = `C`.id WHERE 1)))"),
			array("SELECT `PhysicalDevice` FROM PhysicalDevice AS `PhysicalDevice` WHERE ((`PhysicalDevice`.`status` = 'production') AND (`PhysicalDevice`.`id` NOT IN (SELECT `p` FROM PhysicalDevice AS `p` JOIN lnkFunctionalCIToProviderContract AS `l` ON `l`.functionalci_id = `p`.id WHERE 1)))"),
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
