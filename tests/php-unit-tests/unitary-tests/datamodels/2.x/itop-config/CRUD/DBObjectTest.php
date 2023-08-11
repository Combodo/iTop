<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\CRUD;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use lnkContactToFunctionalCI;
use MetaModel;

class DBObjectTest extends ItopDataTestCase
{
	const USE_TRANSACTION = true;
	const CREATE_TEST_ORG = true;
	const DEBUG_UNIT_TEST = false;


	public function testReloadNotNecessaryForInsert()
	{
		$oPerson = $this->CreatePersonInstance();

		// Insert without Reload
		$oPerson->DBInsert();

		// Get initial values
		$aValues1 = [];
		foreach (MetaModel::GetAttributesList('Person') as $sAttCode) {
			if (MetaModel::GetAttributeDef('Person', $sAttCode) instanceof \AttributeLinkedSet) {
				continue;
			}
			$aValues1[$sAttCode] = $oPerson->Get($sAttCode);
		}
		$sOrgName1 = $oPerson->Get('org_name');
		/** @var \ormLinkSet $oCIList1 */
		$oCIList1 = $oPerson->Get('cis_list');
		$oTeamList1 = $oPerson->Get('team_list');

		$sPerson1 = print_r($oPerson, true);

		// 1st Reload
		$oPerson->Reload(true);
		$sPerson2 = print_r($oPerson, true);
		$this->assertNotEquals($sPerson1, $sPerson2);

		$aValues2 = [];
		foreach (MetaModel::GetAttributesList('Person') as $sAttCode) {
			if (MetaModel::GetAttributeDef('Person', $sAttCode) instanceof \AttributeLinkedSet) {
				continue;
			}
			$aValues2[$sAttCode] = $oPerson->Get($sAttCode);
		}

		$sOrgName2 = $oPerson->Get('org_name');
		/** @var \ormLinkSet $oCIList2 */
		$oCIList2 = $oPerson->Get('cis_list');
		$oTeamList2 = $oPerson->Get('team_list');

		$this->assertEquals($sOrgName1, $sOrgName2);
		$this->assertTrue($oCIList1->Equals($oCIList2));
		$this->assertTrue($oTeamList1->Equals($oTeamList2));
		$this->assertEquals($aValues1, $aValues2);

		// 2nd Reload
		$oPerson->Reload(true);
		$sPerson3 = print_r($oPerson, true);
		$this->assertEquals($sPerson2, $sPerson3);

	}

	public function testFriendlynameResetOnExtKeyReset()
	{
		$oPerson = $this->CreatePersonInstance();
		$oManager = $this->CreatePersonInstance();
		$oManager->DBInsert();

		$oPerson->Set('manager_id', $oManager->GetKey());

		$this->assertNotEmpty($oPerson->Get('manager_id_friendlyname'));

		$oPerson->Set('manager_id', 0);

		$this->assertEmpty($oPerson->Get('manager_id_friendlyname'));
	}

	public function testReloadNotNecessaryForUpdate()
	{
		$oPerson = $this->CreatePersonInstance();
		$oPerson->DBInsert();
		$oManager = $this->CreatePersonInstance();
		$oManager->DBInsert();

		$oPerson->Set('manager_id', $oManager->GetKey());
		$oPerson->DBUpdate();

		$sManagerFriendlyname1 = $oPerson->Get('manager_id_friendlyname');
		$oCIList1 = $oPerson->Get('cis_list');
		$oTeamList1 = $oPerson->Get('team_list');
		$aValues1 = [];
		foreach (MetaModel::GetAttributesList('Person') as $sAttCode) {
			if (MetaModel::GetAttributeDef('Person', $sAttCode) instanceof \AttributeLinkedSet) {
				continue;
			}
			$aValues1[$sAttCode] = $oPerson->Get($sAttCode);
		}

		$sPerson1 = print_r($oPerson, true);

		// 1st Reload
		$oPerson->Reload(true);

		$sPerson2 = print_r($oPerson, true);
		$this->assertNotEquals($sPerson1, $sPerson2);

		$sManagerFriendlyname2 = $oPerson->Get('manager_id_friendlyname');
		$oCIList2 = $oPerson->Get('cis_list');
		$oTeamList2 = $oPerson->Get('team_list');
		$aValues2 = [];
		foreach (MetaModel::GetAttributesList('Person') as $sAttCode) {
			if (MetaModel::GetAttributeDef('Person', $sAttCode) instanceof \AttributeLinkedSet) {
				continue;
			}
			$aValues2[$sAttCode] = $oPerson->Get($sAttCode);
		}

		$this->assertEquals($sManagerFriendlyname1, $sManagerFriendlyname2);
		$this->assertTrue($oCIList1->Equals($oCIList2));
		$this->assertTrue($oTeamList1->Equals($oTeamList2));
		$this->assertEquals($aValues1, $aValues2);

		// 2nd Reload
		$oPerson->Reload(true);
		$sPerson3 = print_r($oPerson, true);
		$this->assertEquals($sPerson2, $sPerson3);
	}

	public function testGetObjectUpdateUnderReentryProtection()
	{
		$oPerson = $this->CreatePersonInstance();
		$oPerson->DBInsert();

		$oPerson->Set('email', 'test@combodo.com');
		$oPerson->DBUpdate();

		$this->assertFalse($oPerson->IsModified());

		$oNewPerson = MetaModel::GetObject('Person', $oPerson->GetKey());
		$this->assertNotEquals($oPerson->GetObjectUniqId(), $oNewPerson->GetObjectUniqId());

		MetaModel::StartReentranceProtection($oPerson);

		$oPerson->Set('email', 'test1@combodo.com');
		$oPerson->DBUpdate();

		$this->assertTrue($oPerson->IsModified());

		$oNewPerson = MetaModel::GetObject('Person', $oPerson->GetKey());
		$this->assertEquals($oPerson->GetObjectUniqId(), $oNewPerson->GetObjectUniqId());

		MetaModel::StopReentranceProtection($oPerson);
	}

	public function testObjectIsReadOnly()
	{
		$oPerson = $this->CreatePersonInstance();

		$sMessage = 'Not allowed to write to this object !';
		$oPerson->SetReadOnly($sMessage);
		try {
			$oPerson->Set('email', 'test1@combodo.com');
			$this->assertTrue(false, 'Set() should have raised a CoreException');
		}
		catch (\CoreException $e) {
			$this->assertEquals($sMessage, $e->getMessage());
		}

		$oPerson->SetReadWrite();

		$oPerson->Set('email', 'test1@combodo.com');
	}

	/**
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	private function CreatePersonInstance()
	{
		$oServer1 = $this->CreateServer(1);
		$oServer2 = $this->CreateServer(2);

		$sClass = 'Person';
		$aParams = [
			'name'       => 'Person_'.rand(10000, 99999),
			'first_name' => 'Test',
			'org_id'     => $this->getTestOrgId(),
		];

		$oPerson = MetaModel::NewObject($sClass);
		foreach ($aParams as $sAttCode => $oValue) {
			$oPerson->Set($sAttCode, $oValue);
		}

		$oNewLink1 = new lnkContactToFunctionalCI();
		$oNewLink1->Set('functionalci_id', $oServer1->GetKey());
		$oNewLink2 = new lnkContactToFunctionalCI();
		$oNewLink2->Set('functionalci_id', $oServer2->GetKey());
		$oCIs = $oPerson->Get('cis_list');
		$oCIs->AddItem($oNewLink1);
		$oCIs->AddItem($oNewLink2);
		$oPerson->Set('cis_list', $oCIs);

		return $oPerson;
	}
}
