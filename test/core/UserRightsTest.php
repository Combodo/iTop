<?php
// Copyright (c) 2010-2018 Combodo SARL
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
 * Date: 25/01/2018
 * Time: 11:12
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use PHPUnit\Framework\TestCase;
use UserRights;

class UserRightsTest extends ItopDataTestCase
{

	public static $aClasses = array(
		'FunctionalCI' => array('class' => 'FunctionalCI', 'attcode' => 'name'),
		'URP_UserProfile' => array('class' => 'URP_UserProfile', 'attcode' => 'reason'),
		'UserLocal' => array('class' => 'UserLocal', 'attcode' => 'login'),
		'UserRequest' => array('class' => 'UserRequest', 'attcode' => 'title'),
		'ModuleInstallation' => array('class' => 'ModuleInstallation', 'attcode' => 'name'),
	);


	public function testIsLoggedIn()
	{
		$this->assertFalse(UserRights::IsLoggedIn());
	}

	/**
	 * Test Login validation
	 * @dataProvider LoginProvider
	 * @param $sLogin
	 * @param $bResult
	 */
	public function testLogin($sLogin, $bResult)
	{
		$_SESSION = array();
		$this->assertEquals($bResult, UserRights::Login($sLogin));
		$this->assertEquals($bResult, UserRights::IsLoggedIn());
	}

	public function LoginProvider()
	{
		return array(
			array('admin', true),
			array('NotALoginForUnitTests', false),
			array('', false),
		);
	}

	/**
	 * @param string $sLogin
	 * @param int $iProfileId initial profile
	 * @return \DBObject
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected function AddUser($sLogin, $iProfileId)
	{
		$oUser = self::CreateUser('test1', $iProfileId);
		$oUser->DBUpdate();
		return $oUser;
	}

	/** Test IsActionAllowed when not logged => always true
	 * @dataProvider ActionAllowedNotLoggedProvider
	 * @param $aClassAction
	 */
	public function testIsActionAllowedNotLogged($aClassAction)
	{
		$bRes = (UserRights::IsActionAllowed($aClassAction['class'], $aClassAction['action'])) ? true : false;
		$this->assertEquals(true, $bRes);
	}

	public function ActionAllowedNotLoggedProvider()
	{
		$aClassActions = array();

		foreach(array_keys(self::$aClasses) as $sClass)
		{
			for ($i = 1; $i < 8; $i++)
			{
				$aClassAction = array('class' => $sClass, 'action' => $i);
				$aClassActions[] = array($aClassAction);
			}
		}
		return $aClassActions;
	}

	/** Test IsActionAllowed
	 * @dataProvider ActionAllowedProvider
	 * @param $iProfileId
	 * @param $aClassActionResult
	 * @throws \CoreException
	 * @throws \Exception
	 */
	public function testIsActionAllowed($iProfileId, $aClassActionResult)
	{
		$this->AddUser('test1', $iProfileId);
		$_SESSION = array();
		$this->assertTrue(UserRights::Login('test1'));
		$bRes = (UserRights::IsActionAllowed($aClassActionResult['class'], $aClassActionResult['action'])) ? true : false;
		$this->assertEquals($aClassActionResult['res'], $bRes);
	}

	/*
	 * FunctionalCI       => bizmodel	searchable
	 * UserRequest        => bizmodel	searchable	requestmgmt
	 * URP_UserProfile    => addon/userrights
	 * UserLocal          => addon/authentication
	 * ModuleInstallation => core	view_in_gui
	 *
	 */
	public function ActionAllowedProvider()
	{
		return array(
			/* Administrator (7 = UR_ACTION_CREATE)  */
			array(1 , array('class' => 'FunctionalCI', 'action' => 7, 'res' => true)),
			array(1 , array('class' => 'UserRequest', 'action' => 7, 'res' => true)),
			array(1 , array('class' => 'URP_UserProfile', 'action' => 7, 'res' => true)),
			array(1 , array('class' => 'UserLocal', 'action' => 7, 'res' => true)),
			array(1 , array('class' => 'ModuleInstallation', 'action' => 7, 'res' => true)),

			/* User Portal  (7 = UR_ACTION_CREATE) */
			array(2 , array('class' => 'FunctionalCI', 'action' => 7, 'res' => false)),
			array(2 , array('class' => 'UserRequest', 'action' => 7, 'res' => true)),
			array(2 , array('class' => 'URP_UserProfile', 'action' => 7, 'res' => false)),
			array(2 , array('class' => 'UserLocal', 'action' => 7, 'res' => false)),
			array(2 , array('class' => 'ModuleInstallation', 'action' => 7, 'res' => false)),

			/* Configuration manager (7 = UR_ACTION_CREATE) */
			array(3 , array('class' => 'FunctionalCI', 'action' => 7, 'res' => true)),
			array(3 , array('class' => 'UserRequest', 'action' => 7, 'res' => false)),
			array(3 , array('class' => 'URP_UserProfile', 'action' => 7, 'res' => false)),
			array(3 , array('class' => 'UserLocal', 'action' => 7, 'res' => false)),
			array(3 , array('class' => 'ModuleInstallation', 'action' => 7, 'res' => false)),

			/* Administrator (1 = UR_ACTION_READ)  */
			array(1 , array('class' => 'FunctionalCI', 'action' => 1, 'res' => true)),
			array(1 , array('class' => 'UserRequest', 'action' => 1, 'res' => true)),
			array(1 , array('class' => 'URP_UserProfile', 'action' => 1, 'res' => true)),
			array(1 , array('class' => 'UserLocal', 'action' => 1, 'res' => true)),
			array(1 , array('class' => 'ModuleInstallation', 'action' => 1, 'res' => true)),

			/* User Portal  (1 = UR_ACTION_READ) */
			array(2 , array('class' => 'FunctionalCI', 'action' => 1, 'res' => true)),
			array(2 , array('class' => 'UserRequest', 'action' => 1, 'res' => true)),
			array(2 , array('class' => 'URP_UserProfile', 'action' => 1, 'res' => false)),
			array(2 , array('class' => 'UserLocal', 'action' => 1, 'res' => false)),
			array(2 , array('class' => 'ModuleInstallation', 'action' => 1, 'res' => true)),

			/* Configuration manager (1 = UR_ACTION_READ) */
			array(3 , array('class' => 'FunctionalCI', 'action' => 1, 'res' => true)),
			array(3 , array('class' => 'UserRequest', 'action' => 1, 'res' => true)),
			array(3 , array('class' => 'URP_UserProfile', 'action' => 1, 'res' => false)),
			array(3 , array('class' => 'UserLocal', 'action' => 1, 'res' => false)),
			array(3 , array('class' => 'ModuleInstallation', 'action' => 1, 'res' => true)),
		);
	}


	/** Test IsActionAllowedOnAttribute
	 * @dataProvider ActionAllowedOnAttributeProvider
	 * @param $iProfileId
	 * @param $aClassActionResult
	 * @throws \Exception
	 */
	public function testIsActionAllowedOnAttribute($iProfileId, $aClassActionResult)
	{
		$this->AddUser('test1', $iProfileId);
		$_SESSION = array();
		$this->assertTrue(UserRights::Login('test1'));
		$sClass = $aClassActionResult['class'];
		$bRes = (UserRights::IsActionAllowedOnAttribute($sClass, self::$aClasses[$sClass]['attcode'], $aClassActionResult['action'])) ? true : false;
		$this->assertEquals($aClassActionResult['res'], $bRes);

	}

	/*
	 * FunctionalCI       => bizmodel	searchable
	 * UserRequest        => bizmodel	searchable	requestmgmt
	 * URP_UserProfile    => addon/userrights
	 * UserLocal          => addon/authentication
	 * ModuleInstallation => core	view_in_gui
	 *
	 */
	public function ActionAllowedOnAttributeProvider()
	{
		$aClassActionResult = array(
			/* Administrator (2 = UR_ACTION_MODIFY)  */
			array(1 , array('class' => 'FunctionalCI', 'action' => 2, 'res' => true)),
			array(1 , array('class' => 'UserRequest', 'action' => 2, 'res' => true)),
			array(1 , array('class' => 'URP_UserProfile', 'action' => 2, 'res' => true)),
			array(1 , array('class' => 'UserLocal', 'action' => 2, 'res' => true)),
			array(1 , array('class' => 'ModuleInstallation', 'action' => 2, 'res' => true)),

			/* User Portal  (2 = UR_ACTION_MODIFY) */
			array(2 , array('class' => 'FunctionalCI', 'action' => 2, 'res' => false)),
			array(2 , array('class' => 'UserRequest', 'action' => 2, 'res' => true)),
			array(2 , array('class' => 'URP_UserProfile', 'action' => 2, 'res' => true)),
			array(2 , array('class' => 'UserLocal', 'action' => 2, 'res' => true)),
			array(2 , array('class' => 'ModuleInstallation', 'action' => 2, 'res' => true)),

			/* Configuration manager (2 = UR_ACTION_MODIFY) */
			array(3 , array('class' => 'FunctionalCI', 'action' => 2, 'res' => true)),
			array(3 , array('class' => 'UserRequest', 'action' => 2, 'res' => false)),
			array(3 , array('class' => 'URP_UserProfile', 'action' => 2, 'res' => true)),
			array(3 , array('class' => 'UserLocal', 'action' => 2, 'res' => true)),
			array(3 , array('class' => 'ModuleInstallation', 'action' => 2, 'res' => true)),
		);

		return $aClassActionResult;
	}

}
