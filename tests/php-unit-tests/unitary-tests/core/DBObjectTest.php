<?php
// Copyright (c) 2010-2023 Combodo SARL
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

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 02/10/2017
 * Time: 13:58
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreException;
use DBObject;
use lnkContactToFunctionalCI;
use lnkPersonToTeam;
use MetaModel;


/**
 * @group specificOrgInSampleData
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBObjectTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/dbobject.class.php');
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
		$this->assertDBQueryCount(2, function() use (&$oObject){
			$oObject->Set('org_id', 2);
			static::assertEquals('IT Department', $oObject->Get('org_id_friendlyname'));
		});
		$this->assertEquals(1, $this->GetObjectReloadCount($sClass, $sKey));
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
		$this->assertDBQueryCount(2, function() use (&$oTeam){
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
		});	}

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

}
