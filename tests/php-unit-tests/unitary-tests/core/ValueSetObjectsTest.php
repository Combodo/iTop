<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use MockValueSetObjects;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use MetaModel;


class ValueSetObjectsTest extends ItopTestCase
{

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/valuesetdef.class.inc.php');
		$this->RequireOnceItopFile('application/startup.inc.php');
		$this->RequireOnceUnitTestFile('./MockValueSetObjects.php');
	}

	/**
	 * @return array
	 */
	public function GetGetFilterProvider()
	{
		return array(
			'Ticket contains bla'        => array("Ticket", "bla", "contains", "SELECT `Ticket` FROM Ticket AS `Ticket` WHERE (`Ticket`.`friendlyname` LIKE '%bla%')"),
			'Ticket equals bla'          => array("Ticket", "bla", "equals", "SELECT `Ticket` FROM Ticket AS `Ticket` WHERE (`Ticket`.`ref` = 'bla')"),
			'Ticket start_with bla'      => array("Ticket", "bla", "start_with", "SELECT `Ticket` FROM Ticket AS `Ticket` WHERE (`Ticket`.`ref` LIKE 'bla%')"),
			'UserRequest contains bla'   => array("UserRequest", "bla", "contains", "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`friendlyname` LIKE '%bla%')"),
			'UserRequest equals bla'     => array("UserRequest", "bla", "equals", "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`ref` = 'bla')"),
			'UserRequest start_with bla' => array("UserRequest", "bla", "start_with", "SELECT `UserRequest` FROM UserRequest AS `UserRequest` WHERE (`UserRequest`.`ref` LIKE 'bla%')"),
		);
	}


	/**
	 * @param $Class
	 * @param $sContains
	 * @param $sOperation
	 * @param $sExpectedOQL
	 *
	 * @dataProvider GetGetFilterProvider
	 * @return void
	 */
	public function testGetFiler($Class, $sContains, $sOperation, $sExpectedOQL)
	{
		$sFilterExp = 'SELECT '.$Class;
		$oValueSetObject = new MockValueSetObjects($sFilterExp);
		$sFilter = $oValueSetObject->GetFilterOQL($sOperation, $sContains);

		$this->assertEquals($sExpectedOQL, $sFilter);

	}
}