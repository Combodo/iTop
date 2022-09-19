<?php
// Copyright (c) 2010-2021 Combodo SARL
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
		require_once(APPROOT.'core/dbobject.class.php');
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

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testAttributeRefresh_FriendlyName()
	{
		$oObject = \MetaModel::NewObject('Person', array('name' => 'Foo', 'first_name' => 'John', 'org_id' => 3, 'location_id' => 2));

		static::assertEquals('John Foo', $oObject->Get('friendlyname'));
		$oObject->Set('name', 'Who');
		static::assertEquals('John Who', $oObject->Get('friendlyname'));
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

		static::assertEquals('', $oObject->Get('friendlyname'));
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 */
	public function testEmptyAttributeEvaluation()
	{
		$oObject = \MetaModel::NewObject('Person', array('org_id' => 3, 'location_id' => 2));

		static::assertEquals('', $oObject->Get('friendlyname'));
	}

	/**
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testFriendlyNameLnk()
	{
		$oUserProfile = new \URP_UserProfile();
		$oUserProfile->Set('profileid', 2);

		static::assertEquals('', $oUserProfile->Get('friendlyname'));
	}

	/**
	 * @covers DBObject::NewObject
	 * @covers DBObject::Get
	 * @covers DBObject::Set
	 */
	public function testAttributeRefresh_ObsolescenceFlag()
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
	public function testAttributeRefresh_ExternalKeysAndFields()
	{
		static::assertDBQueryCount(0, function() use (&$oObject){
			$oObject = \MetaModel::NewObject('Person', array('name' => 'Foo', 'first_name' => 'John', 'org_id' => 3, 'location_id' => 2));
		});
		static::assertDBQueryCount(2, function() use (&$oObject){
			static::assertEquals('Demo', $oObject->Get('org_id_friendlyname'));
			static::assertEquals('Grenoble', $oObject->Get('location_id_friendlyname'));
		});

		// External key given as an id
		static::assertDBQueryCount(1, function() use (&$oObject){
			$oObject->Set('org_id', 2);
			static::assertEquals('IT Department', $oObject->Get('org_id_friendlyname'));
		});

		// External key given as an object
		static::assertDBQueryCount(1, function() use (&$oBordeaux){
			$oBordeaux = \MetaModel::GetObject('Location', 1);
		});

		static::assertDBQueryCount(0, function() use (&$oBordeaux, &$oObject){
			$oObject->Set('location_id', $oBordeaux);
			static::assertEquals('IT Department', $oObject->Get('org_id_friendlyname'));
			static::assertEquals('IT Department', $oObject->Get('org_name'));
			static::assertEquals('Bordeaux', $oObject->Get('location_id_friendlyname'));
		});
	}

	public function testSetExtKeyUnsetDependentAttribute()
	{
		$oObject = \MetaModel::NewObject('Person', array('name' => 'Foo', 'first_name' => 'John', 'org_id' => 3, 'location_id' => 2));
		$oOrg = \MetaModel::GetObject('Organization', 2);
		$oObject->Set('org_id', $oOrg);

		// though it's a dependent field, it keeps its value (not OQL based nor External field)
		static::assertEquals(2, $oObject->Get('location_id'));

		// Dependent external field is updated because the Set('org_id') is done with an object
		static::assertDBQueryCount(0, function() use (&$oObject){
			static::assertNotEmpty($oObject->Get('org_name'));
		});

		// Dependent external field is reset and reloaded from DB
		$oObject->Set('org_id', 3);
		static::assertDBQueryCount(1, function() use (&$oObject){
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
					static::assertDBQueryCount(0, function() use (&$oObject, &$oAttDef){
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
		static::assertDBQueryCount(2, function() use (&$oQueryOQL) {
			$oQueryOQL->DBIncrement('export_count', 1);
		});
	}

}
