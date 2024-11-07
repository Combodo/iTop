<?php
// Copyright (c) 2010-2024 Combodo SAS
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
use AttributeDateTime;
use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreException;
use DateTime;
use DBObject;
use InvalidExternalKeyValueException;
use lnkContactToFunctionalCI;
use lnkPersonToTeam;
use MetaModel;
use Organization;
use Person;
use Team;
use User;
use UserRequest;
use UserRights;
use utils;


/**
 * @group specificOrgInSampleData
 */
class DBObjectTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;
	const INVALID_OBJECT_KEY = 123456789;

	// Counts
	public $aReloadCount = [];


	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/dbobject.class.php');

		$this->EventService_RegisterListener(EVENT_DB_OBJECT_RELOAD, [$this, 'CountObjectReload']);
	}

	/**
	 * Test default page name
	 * @covers DBObject::GetUIPage
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

	/**
	 * @group itopRequestMgmt
	 * @covers DBObject::GetOriginal
	 */
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

		$oOrg->Set('name', $oOrg->Get('name'));
		$this->assertCount(0, $oOrg->ListChanges());
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
		$this->assertCount(1, $oOrg->ListPreviousValuesForUpdatedAttributes());
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testAttributeRefresh_FriendlyNameWithoutCascade()
	{
		$oObject = \MetaModel::NewObject('Person', array('name' => 'Foo', 'first_name' => 'John', 'org_id' => 3, 'location_id' => 2));

		static::assertEquals('John Foo', $oObject->Get('friendlyname'));
		$oObject->Set('name', 'Who');
		static::assertEquals('John Who', $oObject->Get('friendlyname'));
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testAttributeRefresh_FriendlyNameWithCascade()
	{
		$oServer = \MetaModel::NewObject('Server', ['name' => 'ServerTest', 'org_id' => 3]);
		$oServer->DBInsert();
		$oDBServer = \MetaModel::NewObject('DBServer', ['name' => 'DBServerTest', 'org_id' => 3, 'system_id' => $oServer]);

		static::assertEquals('ServerTest', $oDBServer->Get('system_name'));
		static::assertEquals('DBServerTest ServerTest', $oDBServer->Get('friendlyname'));
	}

	/**
	 * @covers MetaModel::GetObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testAttributeRefresh_FriendlyNameFromDB()
	{
		$oObject = \MetaModel::NewObject('Person', array('name' => 'Gary', 'first_name' => 'Romain', 'org_id' => 3, 'location_id' => 2));
		$oObject->DBInsert();
		$iObjKey = $oObject->GetKey();

		$oObject = \MetaModel::GetObject('Person', $iObjKey);

		static::assertEquals('Romain Gary', $oObject->Get('friendlyname'));
		$oObject->Set('name', 'Duris');
		static::assertEquals('Romain Duris', $oObject->Get('friendlyname'));
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 */
	public function testPartialAttributeEvaluation()
	{
		$oObject = \MetaModel::NewObject('Person', array('name' => 'Foo', 'org_id' => 3, 'location_id' => 2));
		static::assertEquals(' Foo', $oObject->Get('friendlyname'));
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 */
	public function testEmptyAttributeEvaluation()
	{
		$oObject = \MetaModel::NewObject('Person', array('org_id' => 3, 'location_id' => 2));

		static::assertEquals(' ', $oObject->Get('friendlyname'));
	}

	/**
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testFriendlyNameLnk()
	{
		$oUserProfile = new \URP_UserProfile();
		$oUserProfile->Set('profileid', 2);

		static::assertEquals('Link between  and Portal user', $oUserProfile->Get('friendlyname'));
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testAttributeRefresh_ObsolescenceFlagWithoutCascade()
	{
		$oObject = \MetaModel::NewObject('Person', array('name' => 'Foo', 'first_name' => 'John', 'org_id' => 3, 'location_id' => 2));

		static::assertEquals(false, (bool)$oObject->Get('obsolescence_flag'));
		$oObject->Set('status', 'inactive');
		static::assertEquals(true, (bool)$oObject->Get('obsolescence_flag'));
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testAttributeRefresh_ObsolescenceFlagWithCascade()
	{
		$this->markTestSkipped('Postponed');
		// Necessary ext. key for $oDBServer
		$oServer = \MetaModel::NewObject('Server', ['name' => 'ServerTest', 'org_id' => 3]);
		$oServer->DBInsert();
		$oDBServer = \MetaModel::NewObject('DBServer', ['name' => 'DBServerTest', 'org_id' => 3, 'system_id' => $oServer, 'status' => 'inactive']);
		$oDBServer->DBInsert();

		$oDBSchema = \MetaModel::NewObject('DatabaseSchema', ['name' => 'DBSchemaTest', 'org_id' => 3, 'dbserver_id' => $oDBServer]);
		static::assertEquals(true, $oDBSchema->Get('obsolescence_flag'));
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testAttributeRefresh_ExternalKeysAndFields()
	{
		$this->assertDBQueryCount(0, function() use (&$oObject){
			$oObject = \MetaModel::NewObject('Person', array('name' => 'Foo', 'first_name' => 'John', 'org_id' => 3, 'location_id' => 2));
		});
		$this->assertDBQueryCount(2, function() use (&$oObject){
			static::assertEquals('Demo', $oObject->Get('org_id_friendlyname'));
			static::assertEquals('Grenoble', $oObject->Get('location_id_friendlyname'));
		});

		// External key given as an id
		$this->assertDBQueryCount(1, function() use (&$oObject){
			$oObject->Set('org_id', 2);
			static::assertEquals('IT Department', $oObject->Get('org_id_friendlyname'));
		});

		// External key given as an object
		$this->assertDBQueryCount(1, function() use (&$oBordeaux){
			$oBordeaux = \MetaModel::GetObject('Location', 1);
		});

		$this->assertDBQueryCount(0, function() use (&$oBordeaux, &$oObject){
			/** @var DBObject $oObject */
			$oObject->Set('location_id', $oBordeaux);
			static::assertEquals('IT Department', $oObject->Get('org_id_friendlyname'));
			static::assertEquals('IT Department', $oObject->Get('org_name'));
			static::assertEquals('Bordeaux', $oObject->Get('location_id_friendlyname'));
		});

		static::assertEquals('Bordeaux', $oObject->Get('location_id_friendlyname'));
//		static::assertEquals('toto', $oObject->EvaluateExpression(\Expression::FromOQL("CONCAT(org_name, '-', location_id_friendlyname)")));
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testInsertNoReloadAttributeRefresh_ExternalKeysAndFields()
	{
		$this->ResetReloadCount();

		$this->assertDBQueryCount(0, function() use (&$oObject){
			$oObject = \MetaModel::NewObject('Person', ['name' => 'Foo', 'first_name' => 'John', 'org_id' => 3, 'location_id' => 2]);
		});
		// The number of queries depends on the installed modules so it varies on CI
		$oObject->DBInsertNoReload();
		$sClass = get_class($oObject);
		$sKey = $oObject->GetKey();
		$this->debug("Created $sClass::$sKey");
		$this->DebugReloadCount("Person::DBInsertNoReload()");

		$this->assertDBQueryCount(0, function() use (&$oObject){
			static::assertEquals('Demo', $oObject->Get('org_id_friendlyname'));
			static::assertEquals('Grenoble', $oObject->Get('location_id_friendlyname'));
		});
		$this->DebugReloadCount("Get('org_id_friendlyname') and Get('location_id_friendlyname')");

		// External key given as an id
		$this->assertDBQueryCount(1, function() use (&$oObject){
			$oObject->Set('org_id', 2);
			static::assertEquals('IT Department', $oObject->Get('org_id_friendlyname'));
		});
		$this->assertEquals(0, $this->GetObjectReloadCount($sClass, $sKey));
		$this->DebugReloadCount("Set('org_id', 2) and Get('org_id_friendlyname')");

		// External key given as an object
		$this->assertDBQueryCount(1, function() use (&$oBordeaux){
			$oBordeaux = MetaModel::GetObject('Location', 1);
		});
		$this->DebugReloadCount("GetObject('Location', 1)");

		$this->assertDBQueryCount(0, function() use (&$oBordeaux, &$oObject){
			$oObject->Set('location_id', $oBordeaux);
			static::assertEquals('IT Department', $oObject->Get('org_id_friendlyname'));
			static::assertEquals('IT Department', $oObject->Get('org_name'));
			static::assertEquals('Bordeaux', $oObject->Get('location_id_friendlyname'));
		});
		$this->DebugReloadCount("Set('location_id',...) Get('org_id_friendlyname') Get('org_name') Get('location_id_friendlyname')");
	}


	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testInsertNoReloadAttributeLinkSet()
	{
		$this->ResetReloadCount();

		$this->assertDBQueryCount(0, function() use (&$oPerson){
			$oPerson = MetaModel::NewObject('Person', ['name' => 'Foo', 'first_name' => 'John', 'org_id' => 3, 'location_id' => 2]);
		});
		// The number of queries depends on the installed modules so it varies on CI
		$oPerson->DBInsertNoReload();
		$sPersonClass = get_class($oPerson);
		$sPersonKey = $oPerson->GetKey();
		$this->debug("Created $sPersonClass::$sPersonKey");
		$this->DebugReloadCount("Person::DBInsertNoReload()");

		$this->assertDBQueryCount(1, function() use (&$oTeam, &$oPerson){
			$oTeam = MetaModel::NewObject('Team', ['name' => 'Team Foo', 'org_id' => 3]);
			// Add person to team
			$oNewLink = new lnkPersonToTeam();
			$oNewLink->Set('person_id', $oPerson->GetKey());
			$oPersons = $oTeam->Get('persons_list');
			$oPersons->AddItem($oNewLink);
			$oTeam->Set('persons_list', $oPersons);
		});

		// The number of queries depends on the installed modules so it varies on CI
		$oTeam->DBInsertNoReload();
		$this->assertCount(0, $oTeam->ListChanges());

		$oTeam->DBUpdate();
		$this->assertCount(0, $oTeam->ListChanges());
		$this->DebugReloadCount("Team::DBUpdate()");

		$sTeamClass = get_class($oTeam);
		$sTeamKey = $oTeam->GetKey();
		$this->debug("Created $sTeamClass::$sTeamKey");
		$this->DebugReloadCount("Team::DBInsertNoReload()");

		$this->assertCount(0, $oTeam->ListChanges());

		// External key given as an id
		$this->assertDBQueryCount(1, function() use (&$oTeam){
			$oTeam->Set('org_id', 2);
			static::assertEquals('IT Department', $oTeam->Get('org_id_friendlyname'));
		});
		$this->DebugReloadCount("Set('org_id', 2) and Get('org_id_friendlyname')");
		$this->assertCount(1, $oTeam->ListChanges());
	}


	public function testSetExtKeyUnsetDependentAttribute()
	{
		$oObject = \MetaModel::NewObject('Person', array('name' => 'Foo', 'first_name' => 'John', 'org_id' => 3, 'location_id' => 2));
		$oOrg = \MetaModel::GetObject('Organization', 2);
		$oObject->Set('org_id', $oOrg);

		// though it's a dependent field, it keeps its value (not OQL based nor External field)
		static::assertEquals(2, $oObject->Get('location_id'));

		// Dependent external field is updated because the Set('org_id') is done with an object
		$this->assertDBQueryCount(0, function() use (&$oObject){
			static::assertNotEmpty($oObject->Get('org_name'));
		});

		// Dependent external field is reset and reloaded from DB
		$oObject->Set('org_id', 3);
		$this->assertDBQueryCount(1, function() use (&$oObject){
			static::assertNotEmpty($oObject->Get('org_name'));
		});
	}

	/**
	 * @covers AttributeDateTime::MakeRealValue
	 */
	public function testSetAttributeDateTimeWithTimestamp(): void
	{
		$oUserRequest = $this->CreateUserRequest(0);
		$iNow = time();
		$oMyDate = new DateTime('2024-02-14 18:12');
		$sMyDate = $oMyDate->format(AttributeDateTime::GetInternalFormat());

		// First test string obtained with Get() converts into a standard DateTime object
		$oUserRequest->Set('start_date', $iNow);
		$sSavedDate = $oUserRequest->Get('start_date');
		$oSavedDate = new DateTime($sSavedDate);
		$this->assertSame($iNow, $oSavedDate->getTimestamp());

		// Second test that string obtained with Get() is of the \AttributeDateTime::GetInternalFormat format
		$oUserRequest->Set('start_date', $oMyDate->getTimestamp());
		$this->assertEquals($sMyDate, $oUserRequest->Get('start_date'));
	}

	/**
	 * @covers AttributeDateTime::MakeRealValue
	 */
	public function testSetAttributeDateTimeWithString(): void
	{
		$oUserRequest = $this->CreateUserRequest(0);
		$oMyDate = new DateTime('2024-02-14 18:12');
		$sMyDate = $oMyDate->format(AttributeDateTime::GetInternalFormat());

		$oUserRequest->Set('start_date', $sMyDate);
		$this->assertEquals($sMyDate, $oUserRequest->Get('start_date'));
	}

	/**
	 * @covers AttributeDateTime::MakeRealValue
	 */
	public function testSetAttributeDateTimeWithDateTime(): void
	{
		$oUserRequest = $this->CreateUserRequest(0);
		$oMyDate = new DateTime('2024-02-14 18:12');
		$sMyDate = $oMyDate->format(AttributeDateTime::GetInternalFormat());

		$oUserRequest->Set('start_date', $oMyDate);
		$this->assertEquals($sMyDate, $oUserRequest->Get('start_date'));
	}

	/**
	 * @group Integration
	 */
	public function testModelExpressions()
	{
		foreach (\MetaModel::GetClasses() as $sClass)
		{
			if (\MetaModel::IsAbstract($sClass)) continue;

			$oObject = \MetaModel::NewObject($sClass);
			foreach (\MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if ($oAttDef->IsBasedOnOQLExpression())
				{
					$this->debug("$sClass::$sAttCode");
					$this->assertDBQueryCount(0, function() use (&$oObject, &$oAttDef){
						$oObject->EvaluateExpression($oAttDef->GetOQLExpression());
					});
				}
			}
		}
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
		$this->bIsUsingSilo = true;
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
		$sLogin = $this->GivenUserRestrictedToAnOrganizationInDB($oDemoOrg->GetKey(), $sConfigurationManagerProfileId);

		$oAdminUser = MetaModel::GetObjectByName(User::class, 'admin', false);
		if (is_null($oAdminUser)) {
			$oAdminUser = $this->CreateUser('admin', 1);
		}

		/** @var Person $oPersonObject */
		$oPersonObject = $this->CreatePerson(0, $oMyCompanyOrg->GetKey());

		//--- Now we can do some tests !
		UserRights::Login($sLogin);
		$this->ResetMetaModelQueyCacheGetObject();

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

		UserRights::Logoff();
		$this->ResetMetaModelQueyCacheGetObject();

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
		$this->bIsUsingSilo = true;
		/** @var Organization $oDemoOrg */
		$oDemoOrg = MetaModel::GetObjectByName(Organization::class, 'Demo');
		/** @var Person $oPersonOnItDepartmentOrg */
		$oPersonOnItDepartmentOrg = MetaModel::GetObjectByName(Person::class, 'Anna Gavalda');
		/** @var Person $oPersonOnDemoOrg */
		$oPersonOnDemoOrg = MetaModel::GetObjectByName(Person::class, 'Claude Monet');

		$sConfigManagerProfileId = 3; // access to Team and Contact objects
		$sLogin = $this->GivenUserRestrictedToAnOrganizationInDB($oDemoOrg->GetKey(), $sConfigManagerProfileId);

		//--- Now we can do some tests !
		UserRights::Login($sLogin);
		$this->ResetMetaModelQueyCacheGetObject();

		$oTeam = MetaModel::NewObject(Team::class, [
			'name' => 'The A Team',
			'org_id' => $oDemoOrg->GetKey()
		]);

		// Part 1 - Test with an invalid id (non-existing object)
		//
		$oPersonLinks = \DBObjectSet::FromScratch(lnkPersonToTeam::class);
		$oPersonLinks->AddObject(MetaModel::NewObject(lnkPersonToTeam::class, [
			'person_id' => self::INVALID_OBJECT_KEY,
			'team_id' => $oTeam->GetKey(),
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
			'team_id' => $oTeam->GetKey(),
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
			'team_id' => $oTeam->GetKey(),
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
		$this->bIsUsingSilo = true;
		/** @var Organization $oDemoOrg */
		$oDemoOrg = MetaModel::GetObjectByName(Organization::class, 'Demo');
		/** @var Person $oPersonOnItDepartmentOrg */
		$oPersonOnItDepartmentOrg = MetaModel::GetObjectByName(Person::class, 'Anna Gavalda');
		/** @var Person $oPersonOnDemoOrg */
		$oPersonOnDemoOrg = MetaModel::GetObjectByName(Person::class, 'Claude Monet');

		$sConfigManagerProfileId = 3; // access to Team and Contact objects
		$sLogin = $this->GivenUserRestrictedToAnOrganizationInDB($oDemoOrg->GetKey(), $sConfigManagerProfileId);

		//--- Now we can do some tests !
		UserRights::Login($sLogin);
		$this->ResetMetaModelQueyCacheGetObject();

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
		$this->ResetMetaModelQueyCacheGetObject();
		try {
			$oAttachment->CheckChangedExtKeysValues();
			$this->fail('There should be an error on attachment pointing to a non allowed org object');
		} catch (InvalidExternalKeyValueException $e) {
			$this->assertEquals('item_id', $e->GetAttCode(), 'Should report the object key attribute');
			$this->assertEquals($oPersonOnItDepartmentOrg->GetKey(), $e->GetAttValue(), 'Should report the object key value');
		}
	}

	/**
	 * Test attribute integer incrementation.
	 *
	 * @covers DBObject::DBIncrement
	 *
	 * @dataProvider getAttributeIntegerDBIncrementProvider
	 *
	 */
	public function testAttributeIntegerDBIncrement(string $sAttrCode, array $aValues, $expectedResult)
	{
		// create query object
		$oQueryOQL = \MetaModel::NewObject('QueryOQL', [
			'name' => 'Test Query',
			'description' => 'Test Query',
			'oql' => 'SELECT Person'
		]);
		$oQueryOQL->DBInsert();

		// iterate throw increments...
		foreach ($aValues as $aValue) {
			$oQueryOQL->DBIncrement($sAttrCode, $aValue);
		}

		// retrieve counter current value
		$iCounter = $oQueryOQL->Get($sAttrCode);

		// assert equals
		$this->assertEquals($expectedResult, $iCounter);
	}

	/**
	 * Data provider for test attribute integer incrementation.
	 *
	 * @return array data
	 */
	public function getAttributeIntegerDBIncrementProvider()
	{
		return array(
			'Incrementation #1' => array('export_count', [5], 5),
			'Incrementation #2' => array('export_count', [5, 10], 15),
			'Incrementation #3' => array('export_count', [50, 20, 10, 100], 180),
			'Incrementation #4' => array('export_count', [50, 20, -10, 1000], 1060)
		);
	}

	/**
	 * Test attribute integer increment with AttributeText.
	 *
	 * @covers DBObject::DBIncrement
	 *
	 */
	public function testAttributeTextDBIncrement()
	{
		// create query object
		$oQueryOQL = \MetaModel::NewObject('QueryOQL', [
			'name' => 'Test Query',
			'description' => 'Test Query',
			'oql' => 'SELECT Person'
		]);
		$oQueryOQL->DBInsert();

		// assert exception
		$this->expectException(CoreException::class);

		// try incrementation
		$oQueryOQL->DBIncrement('description');
	}

	/**
	 * Test attribute integer increment when object dirty.
	 *
	 * @covers DBObject::DBIncrement
	 *
	 */
	public function testAttributeIntegerDBIncrementDirty()
	{
		// create query object
		$oQueryOQL = \MetaModel::NewObject('QueryOQL', [
			'name' => 'Test Query',
			'description' => 'Test Query',
			'oql' => 'SELECT Person'
		]);
		$oQueryOQL->DBInsert();

		// change description
		$oQueryOQL->Set('description', 'new name');

		// assert exception
		$this->expectException(CoreException::class);

		// try incrementation
		$oQueryOQL->DBIncrement('export_count');
	}

	/**
	 * Test query count with attribute integer increment.
	 *
	 * @covers DBObject::DBIncrement
	 *
	 */
	public function testAttributeIntegerDBIncrementQueryCount()
	{
		// create query object
		$oQueryOQL = \MetaModel::NewObject('QueryOQL', [
			'name' => 'Test Query',
			'description' => 'Test Query',
			'oql' => 'SELECT Person'
		]);
		$oQueryOQL->DBInsert();

		// assert query count
		$this->assertDBQueryCount(2, function() use (&$oQueryOQL) {
			$oQueryOQL->DBIncrement('export_count', 1);
		});
	}

	public function testReloadNotNecessaryForInsert()
	{
		$oPerson = $this->CreatePersonInstance();

		// Insert without Reload
		$key = $oPerson->DBInsert();
		$this->assertSame($key, $oPerson->GetKey());

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
		// N°6281 - Rest API core/create key value is no more between quote
		$this->assertSame($key, $oPerson->GetKey());

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
	 * @group itop-community
	 *
	 * @covers       \DBObject::EnumTransitions
	 * @dataProvider EnumTransitionsProvider
	 *
	 * @param string $sObjClass
	 * @param array $aObjData
	 * @param string|null $sObjCurrentState
	 * @param string $sSortType
	 * @param array $aExpectedSortedStimuli
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function testEnumTransitions(string $sObjClass, array $aObjData, ?string $sObjCurrentState, string $sSortType, array $aExpectedSortedStimuli): void
	{
		// Create temp object
		$oObject = MetaModel::NewObject($sObjClass, $aObjData);

		// Force current state if necessary
		if (false === is_null($sObjCurrentState)) {
			$oObject->Set('status', $sObjCurrentState);
		}

		// Force sort type
		$oConfig = MetaModel::GetConfig();
		$oConfig->Set('lifecycle.transitions_sort_type', $sSortType);

		// Retrieve sorted transitions
		$aTestedSortedTransitions = $oObject->EnumTransitions();

		// Compare arrays keys as they reflect the order and as arrays values are not scalars
		$aTestedSortedStimuli = array_keys($aTestedSortedTransitions);
		$this->assertEquals($aExpectedSortedStimuli, $aTestedSortedStimuli, 'Transitions are not ordered as expected');
	}

	public function EnumTransitionsProvider(): array
	{
		$sUserRequestClassName = 'UserRequest';
		$aUserRequestData = [
			'org_id' => 3,
			'caller_id' => 3,
			'title' => 'Test for EnumTransitions method',
			'description' => 'Hello there!',
		];

		return [
			'UserRequest - XML sort' => [
				$sUserRequestClassName,
				$aUserRequestData,
				null,
				'xml',
				[
					'ev_assign',
				    'ev_timeout',
				    'ev_wait_for_approval',
				    'ev_autoresolve',
				],
			],
			'UserRequest - XML sort when in specific state' => [
				$sUserRequestClassName,
				$aUserRequestData,
				'assigned',
				'xml',
				[
					'ev_pending',
				    'ev_resolve',
				    'ev_reassign',
				    'ev_timeout',
				    'ev_autoresolve',
				],
			],
			'UserRequest - Alphabetical (labels not codes) sort' => [
				$sUserRequestClassName,
				$aUserRequestData,
				null,
				'alphabetical',
				[
					'ev_assign',
				    'ev_autoresolve',
				    'ev_timeout',
				    'ev_wait_for_approval',
				],
			],
			'UserRequest - Alphabetical (labels not codes) sort when in specific state' => [
				$sUserRequestClassName,
				$aUserRequestData,
				'assigned',
				'alphabetical',
				[
				    'ev_autoresolve',
					'ev_resolve',
				    'ev_pending',
				    'ev_reassign',
				    'ev_timeout',
				],
			],
			'UserRequest - Fixed sort' => [
				$sUserRequestClassName,
				$aUserRequestData,
				null,
				'fixed',
				[
				    'ev_wait_for_approval',
					'ev_assign',
				    'ev_timeout',
				    'ev_autoresolve',
				],
			],
			'UserRequest - Fixed sort when in specific state' => [
				$sUserRequestClassName,
				$aUserRequestData,
				'resolved',
				'fixed',
				[
					'ev_reopen',
					'ev_autoresolve',
					'ev_close',
				],
			],
			'UserRequest - Relative sort' => [
				$sUserRequestClassName,
				$aUserRequestData,
				null,
				'relative',
				[
				    'ev_wait_for_approval',
					'ev_assign',
				    'ev_timeout',
				    'ev_autoresolve',
				],
			],
			'UserRequest - Relative sort when in specific state' => [
				$sUserRequestClassName,
				$aUserRequestData,
				'resolved',
				'relative',
				[
					'ev_autoresolve',
					'ev_close',
					'ev_reopen',
				],
			],
		];
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

	/**
	 * Data provider for test deletion
	 *  N°5547 - Object deletion fails if friendlyname too long
	 *
	 * @return array data
	 */
	public function getDeletionLongValueProvider()
	{
		return [
			'friendlyname longer than 255 chracters with smiley'               => [
				'0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789-0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789-ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopq',
				'😁😂🤣😃😄😅😆😗🥰😘😍😎😋😊😉😙😚',
			],
			'the same friendlyname in other order with error before fix 5547 ' => [
				'😁😂🤣😃😄😅😆😗🥰😘😍😎😋😊😉😙😚',
				'0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789-0123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789-ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopq',
			],
		];
	}

	/**
	 * N°5547 - Object deletion fails if friendlyname too long
	 *
	 * @covers       DBObject::DBIncrement
	 *
	 * @dataProvider getDeletionLongValueProvider
	 *
	 */
	public function testDeletionLongValue(string $sName, string $sFirstName)
	{
		// Create a UserRequest with 2 contacts
		$oPerson = MetaModel::NewObject('Person', [
			'name'       => $sName,
			'first_name' => $sFirstName,
			'org_id'     => 1,
		]);
		$oPerson->DBWrite();

		$bDeletionOK = true;
		try {
			$oDeletionPlan = $oPerson->DBDelete();
		}
		catch (CoreException $e) {
			$bDeletionOK = false;
		}
		$this->assertTrue($bDeletionOK);
	}

	public function ResetReloadCount()
	{
		$this->aReloadCount = [];
	}

	public function DebugReloadCount($sMsg, $bResetCount = true)
	{
		$iTotalCount = 0;
		$aTotalPerClass = [];
		foreach ($this->aReloadCount as $sClass => $aCountByKeys) {
			$iClassCount = 0;
			foreach ($aCountByKeys as $iCount) {
				$iClassCount += $iCount;
			}
			$iTotalCount += $iClassCount;
			$aTotalPerClass[$sClass] = $iClassCount;
		}
		$this->debug("$sMsg - $iTotalCount reload(s)");
		foreach ($this->aReloadCount as $sClass => $aCountByKeys) {
			$this->debug("    $sClass => $aTotalPerClass[$sClass] reload(s)");
			foreach ($aCountByKeys as $sKey => $iCount) {
				$this->debug("        $sClass::$sKey => $iCount");
			}
		}
		if ($bResetCount) {
			$this->ResetReloadCount();
		}
	}

	public function CountObjectReload(EventData $oData)
	{
		$oObject = $oData->Get('object');
		$sClass = get_class($oObject);
		$sKey = $oObject->GetKey();
		$iCount = $this->GetObjectReloadCount($sClass, $sKey);
		$this->aReloadCount[$sClass][$sKey] = 1 + $iCount;
	}

	public function GetObjectReloadCount($sClass, $sKey)
	{
		return $this->aReloadCount[$sClass][$sKey] ?? 0;
	}

	/**
	 * @since 3.1.0-3 3.1.1 3.2.0 N°6716 test creation
	 */
	public function testConstructorMemoryFootprint():void
	{
		$idx = 0;
		$fStart = microtime(true);
		$fStartLoop = $fStart;
		$iInitialPeak = 0;
		$iMaxAllowedMemoryIncrease = 1 * 1024 * 1024;

		for ($i = 0; $i < 5000; $i++) {
			/** @noinspection PhpUnusedLocalVariableInspection We intentionally use a reference that will disappear on each loop */
			$oPerson = new \Person();
			if (0 == ($idx % 100)) {
				$fDuration = microtime(true) - $fStartLoop;
				$iMemoryPeakUsage = memory_get_peak_usage();
				if ($iInitialPeak === 0) {
					$iInitialPeak = $iMemoryPeakUsage;
					$sInitialPeak = \utils::BytesToFriendlyFormat($iInitialPeak, 4);
				}

				$sCurrPeak = \utils::BytesToFriendlyFormat($iMemoryPeakUsage, 4);
				echo "$idx ".sprintf('%.1f ms', $fDuration * 1000)." - Peak Memory Usage: $sCurrPeak\n";

				$this->assertTrue(($iMemoryPeakUsage - $iInitialPeak) <= $iMaxAllowedMemoryIncrease , "Peak memory changed from $sInitialPeak to $sCurrPeak after $i loops");

				$fStartLoop = microtime(true);
			}
			$idx++;
		}

		$fTotalDuration = microtime(true) - $fStart;
		echo 'Total duration: '.sprintf('%.3f s', $fTotalDuration)."\n\n";
	}
	/**
	 * Data provider for test deletion
	 *  N°5547 - Object deletion fails if friendlyname too long
	 *
	 * @return array data
	 */
	public function DeletionLongValueProvider()
	{
		return [
			// UserRequest.title is an AttributeString (maxsize = 255)
			'title 250 chars' => ['title', 250],
			'title 254 chars' => ['title', 254],
			'title 255 chars' => ['title', 255],
			'title 256 chars' => ['title', 256],
			'title 300 chars' => ['title', 300],

			// UserRequest.pending_reason is an AttributeText (maxsize=65535) with format=text
			'pending_reason 250 chars' => ['pending_reason', 250],
			'pending_reason 60000 chars' => ['pending_reason', 60000],
			'pending_reason 65534 chars' => ['pending_reason', 65534],
			'pending_reason 65535 chars' => ['pending_reason', 65535],
			'pending_reason 65536 chars' => ['pending_reason', 65536],
			'pending_reason 70000 chars' => ['pending_reason', 70000],
		];
	}

	/**
	 * Test check long field with non ascii characters
	 *
	 * @covers       DBObject::DBDelete
	 *
	 * @dataProvider DeletionLongValueProvider
	 *
	 * @since 3.1.2 N°3448 - Framework field size check not correctly implemented for multi-bytes languages/strings
	 */
	public function testCheckLongValueInAttribute(string $sAttrCode, int $iValueLength)
	{
		$sPrefix = 'a'; // just a small prefix so that the emoji bytes won't have a power of 2 (we want a non even value)
		$sEmojiToRepeat = '😎'; // this emoji is 4 bytes long
		$sEmojiRepeats = str_repeat($sEmojiToRepeat, $iValueLength - mb_strlen($sPrefix));
		$sValueToSet = 	$sPrefix . $sEmojiRepeats;

		$oTicket = MetaModel::NewObject('UserRequest', [
			'ref'         => 'Test Ticket',
			'title'       => 'Create OK',
			'description' => 'Create OK',
			'caller_id'   => 15,
			'org_id'      => 3,
		]);

		$oTicket->Set($sAttrCode, $sValueToSet);
		$sValueInObject = $oTicket->Get($sAttrCode);
		$this->assertSame($sValueToSet, $sValueInObject, 'Set should not alter the value even if the value is too long');

		$oAttDef = MetaModel::GetAttributeDef(UserRequest::class, $sAttrCode);
		$iAttrMaxSize = $oAttDef->GetMaxSize();
		$bIsValueToSetBelowAttrMaxSize = ($iValueLength <= $iAttrMaxSize);
		/** @noinspection PhpUnusedLocalVariableInspection */
		[$bCheckStatus, $aCheckIssues, $bSecurityIssue] = $oTicket->CheckToWrite();
		$this->assertEquals($bIsValueToSetBelowAttrMaxSize, $bCheckStatus, "CheckResult result:" . var_export($aCheckIssues, true));

		$oTicket->SetTrim($sAttrCode, $sValueToSet);
		$sValueInObject = $oTicket->Get($sAttrCode);
		if ($bIsValueToSetBelowAttrMaxSize) {
			$this->assertEquals($sValueToSet, $sValueInObject,'Should not alter string that is already shorter than attribute max length');
		} else {
			$this->assertEquals($iAttrMaxSize, mb_strlen($sValueInObject),'Should truncate at the same length than attribute max length');
			$sLastCharsOfValueInObject = mb_substr($sValueInObject, -30);
			$this->assertStringContainsString(' -truncated', $sLastCharsOfValueInObject, 'Should end with "truncated" comment');
		}
	}

	public function SetTrimProvider()
	{
		return [
				'short string should not be truncated' => ['name','name'],
		        'simple ascii string longer than 255 characters truncated' => [
							str_repeat('e',300),
							str_repeat('e',232) . ' -truncated (300 chars)'
		        ],
				'smiley string longer than 255 characters truncated' => [
					str_repeat('😃',300),
					str_repeat('😃',232) . ' -truncated (300 chars)'
				],

			];
	}

	/**
	 * @dataProvider SetTrimProvider
	 * @return void
	 */
	public function testSetTrim($sName, $sResult){
		$oOrganisation = MetaModel::NewObject(Organization::class);
		$oOrganisation->SetTrim('name', $sName);
		$this->assertEquals($sResult, $oOrganisation->Get('name'), 'SetTrim must limit string to 255 characters');
	}

	/**
	 * @covers DBObject::SetComputedDate
	 * @return void
	 */
	public function testSetComputedDateOnAttributeDate(){
		$oObject = MetaModel::NewObject(\CustomerContract::class, ['name'=>'Test contract','org_id'=>'3','provider_id'=>'2']);
		$oObject->Set('start_date',time());
		$oObject->SetComputedDate('end_date', "+2 weeks", 'start_date');
		$this->assertTrue(true,'No fatal error on computing date');
	}
	/**
	 * @covers DBObject::SetComputedDate
	 * @return void
	 */
	public function testSetComputedDateOnAttributeDateTime(){
		$oObject = MetaModel::NewObject(\WorkOrder::class, ['name'=>'Test workorder','description'=>'Toto']);
		$oObject->Set('start_date','2024-01-01 09:45:00');
		$oObject->SetComputedDate('end_date', "+2 weeks", 'start_date');
		$this->assertTrue(true,'No fatal error on computing date');
		$this->assertEquals("2024-01-15 09:45:00", $oObject->Get('end_date'), 'SetComputedDate +2 weeks on a WorkOrder DateTimeAttribute');

	}
}
