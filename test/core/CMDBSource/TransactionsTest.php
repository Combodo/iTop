<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use CMDBSource;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Exception;
use MetaModel;

/**
 *
 * @group itopRequestMgmt
 * Class TransactionsTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class TransactionsTest extends ItopTestCase
{
	/** @var DeadLockInjection */
	private $oMySQLiMock;

	protected function setUp()
	{
		parent::setUp();
		require_once ('DeadLockInjection.php');
		require_once(APPROOT.'/core/cmdbsource.class.inc.php');
		$sEnv = 'production';
		$sConfigFile = APPCONF.$sEnv.'/config-itop.php';

		MetaModel::Startup($sConfigFile, false, true, false, $sEnv);

		$oInitialMysqli = CMDBSource::GetMysqli();
		$this->oMySQLiMock = new DeadLockInjection();

		$oMockMysqli = $this->getMockBuilder('mysqli')
			->setMethods(['query'])
			->getMock();
		$oMockMysqli->expects($this->any())
			->method('query')
			->will($this->returnCallback(
				function ($sSql) use ($oInitialMysqli) {
					$this->oMySQLiMock->query($sSql);
					return $oInitialMysqli->query($sSql);
				}
			));

		$this->InvokeNonPublicStaticMethod('CMDBSource', 'SetMySQLiForQuery', [$oMockMysqli]);
	}

	/**
	 * Test DBInsertNoReload database transaction by provoking deadlock exceptions
	 *
	 * @dataProvider DBInsertProvider
	 *
	 * @param int $iFailAt  Specify the request occurrence that fails
	 * @param bool $bIsInDB Indicates if the object must have been created or not
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function testDBInsert($iFailAt, $bIsInDB)
	{
		// Create a UserRequest with 2 contacts
		$oTicket = MetaModel::NewObject('UserRequest', [
			'ref' => 'Test Ticket',
			'title' => 'Create OK',
			'description' => 'Create OK',
			'caller_id' => 15,
			'org_id' => 3,
		]);
		$oLinkSet = $oTicket->Get('contacts_list');
		$oLinkSet->AddItem(MetaModel::NewObject('lnkContactToTicket', ['contact_id' => 6]));
		$oLinkSet->AddItem(MetaModel::NewObject('lnkContactToTicket', ['contact_id' => 7]));

		$this->oMySQLiMock->SetFailAt($iFailAt);
		$this->debug("---> DBInsert()");
		try {
			$oTicket->DBWrite();
		}
		catch (Exception $e) {
			// If an exception occurs must be a deadlock
			$this->assertTrue(CMDBSource::IsDeadlockException($e), $e->getMessage());
		}

		// Verify if the ticket is considered as saved in the database
		$this->assertEquals($bIsInDB, !$oTicket->IsNew());

		if (!$oTicket->IsNew()) {
			$this->oMySQLiMock->SetShowRequest(false);
			// Delete created objects
			$oTicket->DBDelete();
		}
	}

	public function DBInsertProvider()
	{
		return [
			"Normal case" => ['iFailAt' => -1, 'bIsInDB' => true],
			"ticket" => ['iFailAt' => 1, 'bIsInDB' => false],
			"ticket_request" => ['iFailAt' => 2, 'bIsInDB' => false],
			"priv_change" => ['iFailAt' => 3, 'bIsInDB' => false],
			"priv_changeop" => ['iFailAt' => 4, 'bIsInDB' => false],
			"priv_changeop_create" => ['iFailAt' => 5, 'bIsInDB' => false],
			"History 4" => ['iFailAt' => 6, 'bIsInDB' => false],
			"History 5" => ['iFailAt' => 7, 'bIsInDB' => false],
			"History 6" => ['iFailAt' => 8, 'bIsInDB' => false],
			"History 7" => ['iFailAt' => 9, 'bIsInDB' => false],
			"History 8" => ['iFailAt' => 10, 'bIsInDB' => false],
			"History 9" => ['iFailAt' => 11, 'bIsInDB' => false],
			"History 10" => ['iFailAt' => 12, 'bIsInDB' => false],
			"History 11" => ['iFailAt' => 13, 'bIsInDB' => false],
			"History 12" => ['iFailAt' => 14, 'bIsInDB' => false],
			"History 13" => ['iFailAt' => 15, 'bIsInDB' => false],
			"History 14" => ['iFailAt' => 16, 'bIsInDB' => false],
			"History 15" => ['iFailAt' => 17, 'bIsInDB' => false],
			"History 16" => ['iFailAt' => 18, 'bIsInDB' => false],
			"History 17" => ['iFailAt' => 19, 'bIsInDB' => false],
			"History 18" => ['iFailAt' => 20, 'bIsInDB' => false],
			"History 19" => ['iFailAt' => 21, 'bIsInDB' => false],
			"History 20" => ['iFailAt' => 22, 'bIsInDB' => false],
			"History 21" => ['iFailAt' => 23, 'bIsInDB' => false],
			"History 22" => ['iFailAt' => 24, 'bIsInDB' => false],
			"History 23" => ['iFailAt' => 25, 'bIsInDB' => false],
			"History 24" => ['iFailAt' => 26, 'bIsInDB' => false],
			"History 25" => ['iFailAt' => 27, 'bIsInDB' => false],
			"History 26" => ['iFailAt' => 28, 'bIsInDB' => false],
			"History 27" => ['iFailAt' => 29, 'bIsInDB' => false],
			"History 28" => ['iFailAt' => 30, 'bIsInDB' => false],
			"History 29" => ['iFailAt' => 31, 'bIsInDB' => false],
			"History 30" => ['iFailAt' => 32, 'bIsInDB' => false],
			"History 31" => ['iFailAt' => 33, 'bIsInDB' => false],
			"History 32" => ['iFailAt' => 34, 'bIsInDB' => false],
			"History 33" => ['iFailAt' => 35, 'bIsInDB' => false],
			"History 34" => ['iFailAt' => 36, 'bIsInDB' => false],
			"History 35" => ['iFailAt' => 37, 'bIsInDB' => false],
			"History 36" => ['iFailAt' => 38, 'bIsInDB' => false],
			"History 37" => ['iFailAt' => 39, 'bIsInDB' => false],
			"History 38" => ['iFailAt' => 40, 'bIsInDB' => false],
		];
	}

	/**
 	 * Test DBUpdate database transaction by provoking deadlock exceptions
	 *
	 * @dataProvider DBUpdateProvider
	 * @param $iFailAt
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DeleteException
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public function testDBUpdate($iFailAt, $bIsModified)
	{
		// Create a UserRequest into the database with 2 contacts
		$oTicket = MetaModel::NewObject('UserRequest', [
			'ref' => 'Test Ticket',
			'title' => 'Create OK',
			'description' => 'Create OK',
			'solution' => 'Create OK',
			'caller_id' => 15,
			'org_id' => 3,
		]);
		$oLinkSet = $oTicket->Get('contacts_list');
		$oLinkSet->AddItem(MetaModel::NewObject('lnkContactToTicket', ['contact_id' => 6]));
		$oLinkSet->AddItem(MetaModel::NewObject('lnkContactToTicket', ['contact_id' => 7]));
		//$oTicket->Set('contacts_list', $oLinkSet);

		$this->oMySQLiMock->SetShowRequest(false);
		$oTicket->DBWrite();

		// Verify that the object is considered as saved in the database
		$this->assertEquals(true, !$oTicket->IsNew());

		// Reload from db
		$oTicket = MetaModel::GetObject('UserRequest', $oTicket->GetKey());
		$oTicket->Set('description', 'Update OK');
		$oTicket->Set('solution', 'Test OK');
		$oLinkSet = $oTicket->Get('contacts_list');
		$oLinkSet->AddItem(MetaModel::NewObject('lnkContactToTicket', ['contact_id' => 8]));
		$oTicket->Set('contacts_list', $oLinkSet);

		// Provoke an error during the update
		$this->oMySQLiMock->SetFailAt($iFailAt);
		$this->debug("---> DBUpdate()");
		try {
			$oTicket->DBWrite();
		}
		catch (Exception $e) {
			// If an exception occurs must be a deadlock
			$this->assertTrue(CMDBSource::IsDeadlockException($e));
		}

		// Verify if the ticket is considered as saved in the database
		$this->assertEquals($bIsModified, $oTicket->IsModified());

		// Reload from db after the update to check the value present in the database
		$oTicket = MetaModel::GetObject('UserRequest', $oTicket->GetKey());
		if ($bIsModified) {
			$this->assertEquals('Create OK', $oTicket->Get('solution'));
		} else {
			$this->assertEquals('Test OK', $oTicket->Get('solution'));
		}

		if (!$oTicket->IsNew()) {
			$this->oMySQLiMock->SetShowRequest(false);
			// Delete created objects
			$oTicket->DBDelete();
		}
	}

	public function DBUpdateProvider()
	{
		return [
			"Normal case" => ['iFailAt' => -1, 'bIsModified' => false],
			"ticket_request" => ['iFailAt' => 1, 'bIsModified' => true],
			"lnkcontacttoticket" => ['iFailAt' => 2, 'bIsModified' => true],
			"History 1" => ['iFailAt' => 3, 'bIsModified' => true],
			"History 2" => ['iFailAt' => 4, 'bIsModified' => true],
			"History 3" => ['iFailAt' => 5, 'bIsModified' => true],
			"History 4" => ['iFailAt' => 6, 'bIsModified' => true],
			"History 5" => ['iFailAt' => 7, 'bIsModified' => true],
			"History 6" => ['iFailAt' => 8, 'bIsModified' => true],
			"History 7" => ['iFailAt' => 9, 'bIsModified' => true],
			"History 8" => ['iFailAt' => 10, 'bIsModified' => true],
			"History 9" => ['iFailAt' => 11, 'bIsModified' => true],
			"History 10" => ['iFailAt' => 12, 'bIsModified' => true],
			"History 11" => ['iFailAt' => 13, 'bIsModified' => true],
			"History 12" => ['iFailAt' => 14, 'bIsModified' => true],
			"History 13" => ['iFailAt' => 15, 'bIsModified' => true],
		];
	}
}