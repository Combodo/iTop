<?php
// Copyright (c) 2010-2017 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>
//

namespace Combodo\iTop\Test\UnitTest\Core;

use Attachment;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObject;
use InvalidExternalKeyValueException;
use lnkPersonToTeam;
use MetaModel;
use Organization;
use Person;
use Team;
use User;
use UserRights;
use utils;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBObjectTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;
	const INVALID_OBJECT_KEY = 123456789;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/coreexception.class.inc.php');
		$this->RequireOnceItopFile('core/dbobject.class.php');
	}

	/**
	 * Test default page name
	 */
	public function testGetUIPage()
	{
		static::assertEquals('UI.php', DBObject::GetUIPage());
	}

	/**
	 * Test PKey validation
	 * @dataProvider keyProviderOK
	 * @param $key
	 * @param $res
	 */
	public function testIsValidPKeyOK($key, $res)
	{
		static::assertEquals(DBObject::IsValidPKey($key), $res);
	}

	public function keyProviderOK()
	{
		return array(
			array(1, true),
			array('255', true),
			array(-24576, true),
			array(0123, true),
			array(0xCAFE, true),
			array(PHP_INT_MIN, true),
			array(PHP_INT_MAX, true),
			array('test', false),
			array('', false),
			array('a255', false),
			array('PHP_INT_MIN', false));
	}

	public function testGetOriginal()
	{
		$oObject = $this->CreateUserRequest(190664);

		static::assertNull($oObject->GetOriginal('sla_tto_passed'));
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	public function testListPreviousValuesForUpdatedAttributes()
	{
		$oOrg = $this->CreateOrganization('testListPreviousValuesForUpdatedAttributes');

		$this->assertCount(0, $oOrg->ListChanges());
		$oOrg->Set('code', strtoupper('testListPreviousValuesForUpdatedAttributes'));
		$this->assertCount(1, $oOrg->ListChanges());
		$oOrg->DBUpdate();
		$this->assertCount(0, $oOrg->ListChanges());
		$this->assertCount(1, $oOrg->ListPreviousValuesForUpdatedAttributes());

		$oOrg->DBUpdate();

		$this->assertCount(0, $oOrg->ListChanges());
		$this->assertCount(1, $oOrg->ListPreviousValuesForUpdatedAttributes());

		$oOrg->DBDelete();

		$oOrg = MetaModel::NewObject('Organization');
		$oOrg->Set('name', 'testListPreviousValuesForUpdatedAttributes');
		$oOrg->DBInsert();
		$oOrg->Set('code', strtoupper('testListPreviousValuesForUpdatedAttributes'));
		$oOrg->DBUpdate();
		$oOrg->DBUpdate();
		$this->markTestIncomplete('This test has not been implemented yet. wait for N°4967 fix');
		$this->debug("ERROR: N°4967 - 'Previous Values For Updated Attributes' not updated if DBUpdate is called without modifying the object");
		//$this->assertCount(0, $oOrg->ListPreviousValuesForUpdatedAttributes());
	}

	private function GetAlwaysTrueCallback(): callable
	{
		return static function () {
			return true;
		};
	}

	private function GetAlwaysFalseCallback(): callable
	{
		return static function () {
			return false;
		};
	}

	/**
	 * @covers DBObject::CheckChangedExtKeysValues()
	 */
	public function testCheckExtKeysSiloOnAttributeExternalKey()
	{
		//--- Preparing data...
		$oAlwaysTrueCallback = $this->GetAlwaysTrueCallback();
		$oAlwaysFalseCallback = $this->GetAlwaysFalseCallback();

		/** @var Organization $oDemoOrg */
		$oDemoOrg = MetaModel::GetObjectByName(Organization::class, 'Demo');
		/** @var Organization $oMyCompanyOrg */
		$oMyCompanyOrg = MetaModel::GetObjectByName(Organization::class, 'My Company/Department');

		/** @var Person $oPersonOfDemoOrg */
		$oPersonOfDemoOrg = MetaModel::GetObjectByName(Person::class, 'Agatha Christie');
		/** @var Person $oPersonOfMyCompanyOrg */
		$oPersonOfMyCompanyOrg = MetaModel::GetObjectByName(Person::class, 'My first name My last name');

		$sConfigurationManagerProfileId = 3; // Access to Person objects
		$oUserWithAllowedOrgs = $this->CreateDemoOrgUser($oDemoOrg, $sConfigurationManagerProfileId);

		$oAdminUser = MetaModel::GetObjectByName(User::class, 'admin', false);
		if (is_null($oAdminUser)) {
			$oAdminUser = $this->CreateUser('admin', 1);
		}

		/** @var Person $oPersonObject */
		$oPersonObject = $this->CreatePerson(0, $oMyCompanyOrg->GetKey());

		//--- Now we can do some tests !
		UserRights::Login($oUserWithAllowedOrgs->Get('login'));
		try {
			$oPersonObject->CheckChangedExtKeysValues();
		} catch (InvalidExternalKeyValueException $eCannotSave) {
			$this->fail('Should skip external keys already written in Database');
		}

		$oPersonObject->Set('manager_id', $oPersonOfDemoOrg->GetKey());
		try {
			$oPersonObject->CheckChangedExtKeysValues();
		} catch (InvalidExternalKeyValueException $eCannotSave) {
			$this->fail('Should allow objects in the same org as the current user');
		}

		try {
			$oPersonObject->CheckChangedExtKeysValues($oAlwaysFalseCallback);
			$this->fail('Should consider the callback returning "false"');
		} catch (InvalidExternalKeyValueException $eCannotSave) {
			// Ok, the exception was expected
		}

		$oPersonObject->Set('manager_id', $oPersonOfMyCompanyOrg->GetKey());
		try {
			$oPersonObject->CheckChangedExtKeysValues();
			$this->fail('Should not allow objects not being in the allowed orgs of the current user');
		} catch (InvalidExternalKeyValueException $eCannotSave) {
			$this->assertEquals('manager_id', $eCannotSave->GetAttCode(), 'Should report the wrong external key attcode');
			$this->assertEquals($oMyCompanyOrg->GetKey(), $eCannotSave->GetAttValue(), 'Should report the unauthorized external key value');
		}

		try {
			$oPersonObject->CheckChangedExtKeysValues($oAlwaysTrueCallback);
		} catch (InvalidExternalKeyValueException $eCannotSave) {
			$this->fail('Should consider the callback returning "true"');
		}

		// ugly hack to remove cached SQL queries :(
		//FIXME In 3.0+ this won't be necessary anymore thanks to UserRights::Logoff
		$this->SetNonPublicStaticProperty(MetaModel::class, 'aQueryCacheGetObject', []);

		UserRights::Login($oAdminUser->Get('login'));
		$oPersonObject->CheckChangedExtKeysValues();
		$this->assertTrue(true, 'Admin user can create objects in any org');
	}

	/**
	 * @covers DBObject::CheckChangedExtKeysValues()
	 */
	public function testCheckExtKeysOnAttributeLinkedSetIndirect()
	{
		//--- Preparing data...
		/** @var Organization $oDemoOrg */
		$oDemoOrg = MetaModel::GetObjectByName(Organization::class, 'Demo');
		/** @var Person $oPersonOnItDepartmentOrg */
		$oPersonOnItDepartmentOrg = MetaModel::GetObjectByName(Person::class, 'Anna Gavalda');
		/** @var Person $oPersonOnDemoOrg */
		$oPersonOnDemoOrg = MetaModel::GetObjectByName(Person::class, 'Claude Monet');

		$sConfigManagerProfileId = 3; // access to Team and Contact objects
		$oUserWithAllowedOrgs = $this->CreateDemoOrgUser($oDemoOrg, $sConfigManagerProfileId);

		//--- Now we can do some tests !
		UserRights::Login($oUserWithAllowedOrgs->Get('login'));

		$oTeam = MetaModel::NewObject(Team::class, [
			'name' => 'The A Team',
			'org_id' => $oDemoOrg->GetKey()
		]);

		// Part 1 - Test with an invalid id (non-existing object)
		//
		$oPersonLinks = \DBObjectSet::FromScratch(lnkPersonToTeam::class);
		$oPersonLinks->AddObject(MetaModel::NewObject(lnkPersonToTeam::class, [
			'person_id' => self::INVALID_OBJECT_KEY,
		]));
		$oTeam->Set('persons_list', $oPersonLinks);

		try {
			$oTeam->CheckChangedExtKeysValues();
			$this->fail('An unknown object should be detected as invalid');
		} catch (InvalidExternalKeyValueException $e) {
			// we are getting the exception on the lnk class
			// In consequence attcode is `lnkPersonToTeam.person_id` instead of `Team.persons_list`
			$this->assertEquals('person_id', $e->GetAttCode(), 'The reported attcode should be the external key on the link');
			$this->assertEquals(self::INVALID_OBJECT_KEY, $e->GetAttValue(), 'The reported value should be the external key on the link');
		}

		try {
			$oTeam->CheckChangedExtKeysValues($this->GetAlwaysTrueCallback());
		} catch (InvalidExternalKeyValueException $e) {
			$this->fail('Should have no error when callback returns true');
		}

		// Part 2 - Test with an allowed object
		//
		$oPersonLinks = \DBObjectSet::FromScratch(lnkPersonToTeam::class);
		$oPersonLinks->AddObject(MetaModel::NewObject(lnkPersonToTeam::class, [
			'person_id' => $oPersonOnDemoOrg->GetKey(),
		]));
		$oTeam->Set('persons_list', $oPersonLinks);

		try {
			$oTeam->CheckChangedExtKeysValues();
		} catch (InvalidExternalKeyValueException $e) {
			$this->fail('An authorized object should be detected as valid');
		}

		try {
			$oTeam->CheckChangedExtKeysValues($this->GetAlwaysFalseCallback());
			$this->fail('Should cascade the callback result when it is "false"');
		} catch (InvalidExternalKeyValueException $e) {
			// Ok, the exception was expected
		}

		// Part 3 - Test with a not allowed object
		//
		$oPersonLinks = \DBObjectSet::FromScratch(lnkPersonToTeam::class);
		$oPersonLinks->AddObject(MetaModel::NewObject(lnkPersonToTeam::class, [
			'person_id' => $oPersonOnItDepartmentOrg->GetKey(),
		]));
		$oTeam->Set('persons_list', $oPersonLinks);

		try {
			$oTeam->CheckChangedExtKeysValues();
			$this->fail('An unauthorized object should be detected as invalid');
		}
		catch (InvalidExternalKeyValueException $e) {
			// Ok, the exception was expected
		}

		try {
			$oTeam->CheckChangedExtKeysValues($this->GetAlwaysTrueCallback());
		} catch (InvalidExternalKeyValueException $e) {
			$this->fail('Should cascade the callback result when it is "true"');
		}

		$oTeam->DBInsert(); // persisting invalid value and resets the object changed values
		try {
			$oTeam->CheckChangedExtKeysValues();
		}
		catch (InvalidExternalKeyValueException $e) {
			$this->fail('An unauthorized value should be ignored when it is not being modified');
		}
	}

	/**
	 * @covers DBObject::CheckChangedExtKeysValues()
	 */
	public function testCheckExtKeysSiloOnAttributeObjectKey()
	{
		//--- Preparing data...
		/** @var Organization $oDemoOrg */
		$oDemoOrg = MetaModel::GetObjectByName(Organization::class, 'Demo');
		/** @var Person $oPersonOnItDepartmentOrg */
		$oPersonOnItDepartmentOrg = MetaModel::GetObjectByName(Person::class, 'Anna Gavalda');
		/** @var Person $oPersonOnDemoOrg */
		$oPersonOnDemoOrg = MetaModel::GetObjectByName(Person::class, 'Claude Monet');

		$sConfigManagerProfileId = 3; // access to Team and Contact objects
		$oUserWithAllowedOrgs = $this->CreateDemoOrgUser($oDemoOrg, $sConfigManagerProfileId);

		//--- Now we can do some tests !
		UserRights::Login($oUserWithAllowedOrgs->Get('login'));

		$oAttachment = MetaModel::NewObject(Attachment::class, [
			'item_class' => Person::class,
			'item_id' => $oPersonOnDemoOrg->GetKey(),
		]);
		try {
			$oAttachment->CheckChangedExtKeysValues();
		} catch (InvalidExternalKeyValueException $e) {
			$this->fail('Should be allowed to create an attachment pointing to a ticket in the allowed org list');
		}

		$oAttachment = MetaModel::NewObject(Attachment::class, [
			'item_class' => Person::class,
			'item_id' => $oPersonOnItDepartmentOrg->GetKey(),
		]);
		try {
			$oAttachment->CheckChangedExtKeysValues();
			$this->fail('There should be an error on attachment pointing to a non allowed org object');
		} catch (InvalidExternalKeyValueException $e) {
			$this->assertEquals('item_id', $e->GetAttCode(), 'Should report the object key attribute');
			$this->assertEquals($oPersonOnItDepartmentOrg->GetKey(), $e->GetAttValue(), 'Should report the object key value');
		}
	}

	private function CreateDemoOrgUser(Organization $oDemoOrg, string $sProfileId): User
	{
		utils::GetConfig()->SetModuleSetting('authent-local', 'password_validation.pattern', '');
		$oUserWithAllowedOrgs = $this->CreateContactlessUser('demo_test_' . __CLASS__, $sProfileId);
		/** @var \URP_UserOrg $oUserOrg */
		$oUserOrg = \MetaModel::NewObject('URP_UserOrg', ['allowed_org_id' => $oDemoOrg->GetKey(),]);
		$oAllowedOrgList = $oUserWithAllowedOrgs->Get('allowed_org_list');
		$oAllowedOrgList->AddItem($oUserOrg);
		$oUserWithAllowedOrgs->Set('allowed_org_list', $oAllowedOrgList);
		$oUserWithAllowedOrgs->DBWrite();

		return $oUserWithAllowedOrgs;
	}
}
