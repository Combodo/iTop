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
			'Administrator FunctionalCI write' => array(1 , array('class' => 'FunctionalCI', 'action' => 7, 'res' => true)),
			'Administrator UserRequest write' => array(1 , array('class' => 'UserRequest', 'action' => 7, 'res' => true)),
			'Administrator URP_UserProfile write' => array(1 , array('class' => 'URP_UserProfile', 'action' => 7, 'res' => true)),
			'Administrator UserLocal write' => array(1 , array('class' => 'UserLocal', 'action' => 7, 'res' => true)),
			'Administrator ModuleInstallation write' => array(1 , array('class' => 'ModuleInstallation', 'action' => 7, 'res' => true)),

			/* User Portal  (7 = UR_ACTION_CREATE) */
			'User Portal FunctionalCI write' => array(2 , array('class' => 'FunctionalCI', 'action' => 7, 'res' => false)),
			'User Portal UserRequest write' => array(2 , array('class' => 'UserRequest', 'action' => 7, 'res' => true)),
			'User Portal URP_UserProfile write' => array(2 , array('class' => 'URP_UserProfile', 'action' => 7, 'res' => false)),
			'User Portal UserLocal write' => array(2 , array('class' => 'UserLocal', 'action' => 7, 'res' => false)),
			'User Portal ModuleInstallation write' => array(2 , array('class' => 'ModuleInstallation', 'action' => 7, 'res' => false)),

			/* Configuration manager (7 = UR_ACTION_CREATE) */
			'Configuration manager FunctionalCI write' => array(3 , array('class' => 'FunctionalCI', 'action' => 7, 'res' => true)),
			'Configuration manager UserRequest write' => array(3 , array('class' => 'UserRequest', 'action' => 7, 'res' => false)),
			'Configuration manager URP_UserProfile write' => array(3 , array('class' => 'URP_UserProfile', 'action' => 7, 'res' => false)),
			'Configuration manager UserLocal write' => array(3 , array('class' => 'UserLocal', 'action' => 7, 'res' => false)),
			'Configuration manager ModuleInstallation write' => array(3 , array('class' => 'ModuleInstallation', 'action' => 7, 'res' => false)),

			/* Administrator (1 = UR_ACTION_READ)  */
			'Administrator FunctionalCI read' => array(1 , array('class' => 'FunctionalCI', 'action' => 1, 'res' => true)),
			'Administrator UserRequest read' => array(1 , array('class' => 'UserRequest', 'action' => 1, 'res' => true)),
			'Administrator URP_UserProfile read' => array(1 , array('class' => 'URP_UserProfile', 'action' => 1, 'res' => true)),
			'Administrator UserLocal read' => array(1 , array('class' => 'UserLocal', 'action' => 1, 'res' => true)),
			'Administrator ModuleInstallation read' => array(1 , array('class' => 'ModuleInstallation', 'action' => 1, 'res' => true)),

			/* User Portal  (1 = UR_ACTION_READ) */
			'User Portal FunctionalCI read' => array(2 , array('class' => 'FunctionalCI', 'action' => 1, 'res' => true)),
			'User Portal UserRequest read' => array(2 , array('class' => 'UserRequest', 'action' => 1, 'res' => true)),
			'User Portal URP_UserProfile read' => array(2 , array('class' => 'URP_UserProfile', 'action' => 1, 'res' => false)),
			'User Portal UserLocal read' => array(2 , array('class' => 'UserLocal', 'action' => 1, 'res' => false)),
			'User Portal ModuleInstallation read' => array(2 , array('class' => 'ModuleInstallation', 'action' => 1, 'res' => true)),

			/* Configuration manager (1 = UR_ACTION_READ) */
			'Configuration manager FunctionalCI read' => array(3 , array('class' => 'FunctionalCI', 'action' => 1, 'res' => true)),
			'Configuration manager UserRequest read' => array(3 , array('class' => 'UserRequest', 'action' => 1, 'res' => true)),
			'Configuration manager URP_UserProfile read' => array(3 , array('class' => 'URP_UserProfile', 'action' => 1, 'res' => false)),
			'Configuration manager UserLocal read' => array(3 , array('class' => 'UserLocal', 'action' => 1, 'res' => false)),
			'Configuration manager ModuleInstallation read' =>array(3 , array('class' => 'ModuleInstallation', 'action' => 1, 'res' => true)),
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
	 * URP_UserProfile    => addon/userrights   grant_by_profile
	 * UserLocal          => addon/authentication   grant_by_profile
	 * ModuleInstallation => core	view_in_gui
	 *
	 */
	public function ActionAllowedOnAttributeProvider()
	{
		$aClassActionResult = array(
			/* Administrator (2 = UR_ACTION_MODIFY)  */
			'Administrator FunctionalCI' => array(1 , array('class' => 'FunctionalCI', 'action' => 2, 'res' => true)),
			'Administrator UserRequest' => array(1 , array('class' => 'UserRequest', 'action' => 2, 'res' => true)),
			'Administrator URP_UserProfile' => array(1 , array('class' => 'URP_UserProfile', 'action' => 2, 'res' => true)),
			'Administrator UserLocal' => array(1 , array('class' => 'UserLocal', 'action' => 2, 'res' => true)),
			'Administrator ModuleInstallation' => array(1 , array('class' => 'ModuleInstallation', 'action' => 2, 'res' => true)),

			/* User Portal  (2 = UR_ACTION_MODIFY) */
			'User Portal FunctionalCI' => array(2 , array('class' => 'FunctionalCI', 'action' => 2, 'res' => false)),
			'User Portal UserRequest' => array(2 , array('class' => 'UserRequest', 'action' => 2, 'res' => true)),
			'User Portal URP_UserProfile' => array(2 , array('class' => 'URP_UserProfile', 'action' => 2, 'res' => false)),
			'User Portal UserLocal' => array(2 , array('class' => 'UserLocal', 'action' => 2, 'res' => false)),
			'User Portal ModuleInstallation' => array(2 , array('class' => 'ModuleInstallation', 'action' => 2, 'res' => true)),

			/* Configuration manager (2 = UR_ACTION_MODIFY) */
			'Configuration manager FunctionalCI' => array(3 , array('class' => 'FunctionalCI', 'action' => 2, 'res' => true)),
			'Configuration manager UserRequest' => array(3 , array('class' => 'UserRequest', 'action' => 2, 'res' => false)),
			'Configuration manager URP_UserProfile' => array(3 , array('class' => 'URP_UserProfile', 'action' => 2, 'res' => false)),
			'Configuration manager UserLocal' => array(3 , array('class' => 'UserLocal', 'action' => 2, 'res' => false)),
			'Configuration manager ModuleInstallation' => array(3 , array('class' => 'ModuleInstallation', 'action' => 2, 'res' => true)),
		);

		return $aClassActionResult;
	}

}
