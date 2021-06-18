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
use DBObject;


/**
 * @group specificOrgInSampleData
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class DBObjectTest extends ItopDataTestCase
{
	const CREATE_TEST_ORG = true;

	protected function setUp()
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
}
