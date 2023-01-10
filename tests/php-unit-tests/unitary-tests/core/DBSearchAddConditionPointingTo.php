<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DBSearch;

class DBSearchAddConditionPointingToTest extends ItopTestCase
{

	protected function setUp(): void
	{
		parent::setUp();
		require_once(APPROOT.'application/startup.inc.php');
	}

	/**
	 * @dataProvider AddCondition_PointingToReAliasingMapProvider
	 *
	 * @param string $sMainReq
	 * @param string $sJoinedReq
	 * @param string $sExtKeyToRemote
	 * @param array $aExpectedReAliasingMap
	 *
	 * @throws \CoreException
	 * @throws \CoreWarning
	 * @throws \OQLException
	 */
	public function testAddCondition_PointingToReAliasingMap(string $sMainReq, string $sJoinedReq, string $sExtKeyToRemote, array $aExpectedReAliasingMap)
	{
		$oMainSearch = DBSearch::FromOQL($sMainReq);
		$oJoinedSearch = DBSearch::FromOQL($sJoinedReq);

		$aReAliasingMap = [];
		$oMainSearch->AddCondition_PointingTo($oJoinedSearch, $sExtKeyToRemote, TREE_OPERATOR_EQUALS, $aReAliasingMap);

		$this->debug($oMainSearch->ToOQL());
		$this->debug(print_r($aReAliasingMap, true));

		$this->assertEquals($aExpectedReAliasingMap, $aReAliasingMap, 'Canonicalized', 0.0, 10, true);
	}

	public function AddCondition_PointingToReAliasingMapProvider()
	{
		return [
			'No initial join'          => [
				'sMainReq'               => 'SELECT `Link` FROM lnkContactToTicket AS `Link` JOIN Ticket AS `Ticket` ON `Link`.ticket_id = `Ticket`.id WHERE (`Ticket`.`id` = :id)',
				'sJoinedReq'             => 'SELECT `Remote` FROM Contact AS `Remote` WHERE `Remote`.`obsolescence_flag` = 0',
				'sExtKeyToRemote'        => 'contact_id',
				'aExpectedReAliasingMap' => [],
			],
			'Initial join no renaming' => [
				'sMainReq'               => 'SELECT `Link` FROM lnkContactToTicket AS `Link` JOIN Ticket AS `Ticket` ON `Link`.ticket_id = `Ticket`.id JOIN Contact AS `Remote` ON `Link`.contact_id = `Remote`.id WHERE (`Ticket`.`id` = :id)',
				'sJoinedReq'             => 'SELECT `Remote` FROM Contact AS `Remote` WHERE `Remote`.`obsolescence_flag` = 0',
				'sExtKeyToRemote'        => 'contact_id',
				'aExpectedReAliasingMap' => ['Remote' => ['Remote']],
			],
			'Initial join renaming'    => [
				'sMainReq'               => 'SELECT `Link` FROM lnkContactToTicket AS `Link` JOIN Ticket AS `Ticket` ON `Link`.ticket_id = `Ticket`.id JOIN Contact AS `Contact` ON `Link`.contact_id = `Contact`.id WHERE (`Ticket`.`id` = :id)',
				'sJoinedReq'             => 'SELECT `Remote` FROM Contact AS `Remote` WHERE `Remote`.`obsolescence_flag` = 0',
				'sExtKeyToRemote'        => 'contact_id',
				'aExpectedReAliasingMap' => ['Remote' => ['Contact']],
			],
		];
	}
}