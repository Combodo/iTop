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

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 25/01/2018
 * Time: 11:12
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreCannotSaveObjectException;
use CoreException;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use DeleteException;
use URP_UserProfile;
use UserRights;
use utils;

/**
 * @group itopRequestMgmt
 * @group userRights
 * @group defaultProfiles
 */
class UserRightsTest extends ItopDataTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		utils::GetConfig()->SetModuleSetting('authent-local', 'password_validation.pattern', '');
	}

	public static $aClasses = [
		'FunctionalCI'       => ['class' => 'FunctionalCI', 'attcode' => 'name'],
		'URP_UserProfile'    => ['class' => 'URP_UserProfile', 'attcode' => 'reason'],
		'UserLocal'          => ['class' => 'UserLocal', 'attcode' => 'login'],
		'UserRequest'        => ['class' => 'UserRequest', 'attcode' => 'title'],
		'ModuleInstallation' => ['class' => 'ModuleInstallation', 'attcode' => 'name'],
	];

	/**
	 * @param string $sLoginPrefix
	 * @param int $iProfileId initial profile
	 *
	 * @return \DBObject
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected function CreateUniqueUserAndLogin(string $sLoginPrefix, int $iProfileId): DBObject
	{
		static $iCount = 0;
		$sLogin = $sLoginPrefix.$iCount;
		$iCount++;

		$oUser = self::CreateUser($sLogin, $iProfileId);
		$_SESSION = array();
		UserRights::Login($sLogin);
		return $oUser;
	}

	public function testIsLoggedIn()
	{
		$this->assertFalse(UserRights::IsLoggedIn());
	}

	/**
	 * Test Login validation
	 *
	 * @dataProvider LoginProvider
	 *
	 * @param $sLogin
	 * @param $bResult
	 *
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public function testLogin($sLogin, $bResult)
	{
		$_SESSION = [];
		if ($sLogin == 'admin') {
			// Fixture data required in this case only
			try {
				self::CreateUser('admin', 1);
			}
			catch (CoreCannotSaveObjectException $e) {
				// The admin account could exist, depending on where and when the test suite is executed
			}
		}
		$this->assertEquals($bResult, UserRights::Login($sLogin));
		$this->assertEquals($bResult, UserRights::IsLoggedIn());
	}

	public function LoginProvider(): array
	{
		return [
			['admin', true],
			['NotALoginForUnitTests', false],
			['', false],
		];
	}

	/** Test IsActionAllowed when not logged => always true
	 *
	 * @dataProvider ActionAllowedNotLoggedProvider
	 *
	 * @param $aClassAction
	 *
	 * @throws \CoreException
	 */
	public function testIsActionAllowedNotLogged($aClassAction)
	{
		$bRes = UserRights::IsActionAllowed($aClassAction['class'], $aClassAction['action']) == UR_ALLOWED_YES;
		$this->assertEquals(true, $bRes);
	}

	public function ActionAllowedNotLoggedProvider(): array
	{
		$aClassActions = [];

		foreach (array_keys(self::$aClasses) as $sClass) {
			for ($i = 1; $i < 8; $i++) {
				$aClassAction = ['class' => $sClass, 'action' => $i];
				$aClassActions[] = [$aClassAction];
			}
		}

		return $aClassActions;
	}

	/**
	 * @dataProvider ActionAllowedProvider
	 *
	 * @param int $iProfileId
	 * @param array $aClassActionResult
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public function testIsActionAllowed(int $iProfileId, array $aClassActionResult)
	{
		$this->CreateUniqueUserAndLogin('test1', $iProfileId);
		$bRes = UserRights::IsActionAllowed($aClassActionResult['class'], $aClassActionResult['action']) == UR_ALLOWED_YES;
		$this->assertEquals($aClassActionResult['res'], $bRes);
	}

	/*
	 * FunctionalCI       => bizmodel	searchable
	 * UserRequest        => bizmodel	searchable	requestmgmt
	 * URP_UserProfile    => addon/userrights
	 * UserLocal          => addon/authentication
	 * ModuleInstallation => core	view_in_gui
	 *
	 * Profiles:
	 * 1 - Administrator
	 * 2 - User Portal
	 * 3 - Configuration manager
	 *
	 */
	public function ActionAllowedProvider(): array
	{
		return [
			/* Administrator (7 = UR_ACTION_CREATE)  */
			'Administrator FunctionalCI write'               => [1, ['class' => 'FunctionalCI', 'action' => 7, 'res' => true]],
			'Administrator UserRequest write'                => [1, ['class' => 'UserRequest', 'action' => 7, 'res' => true]],
			'Administrator URP_UserProfile write'            => [1, ['class' => 'URP_UserProfile', 'action' => 7, 'res' => true]],
			'Administrator UserLocal write'                  => [1, ['class' => 'UserLocal', 'action' => 7, 'res' => true]],
			'Administrator ModuleInstallation write'         => [1, ['class' => 'ModuleInstallation', 'action' => 7, 'res' => true]],

			/* User Portal  (7 = UR_ACTION_CREATE) */
			'User Portal FunctionalCI write'                 => [2, ['class' => 'FunctionalCI', 'action' => 7, 'res' => false]],
			'User Portal UserRequest write'                  => [2, ['class' => 'UserRequest', 'action' => 7, 'res' => true]],
			'User Portal URP_UserProfile write'              => [2, ['class' => 'URP_UserProfile', 'action' => 7, 'res' => false]],
			'User Portal UserLocal write'                    => [2, ['class' => 'UserLocal', 'action' => 7, 'res' => false]],
			'User Portal ModuleInstallation write'           => [2, ['class' => 'ModuleInstallation', 'action' => 7, 'res' => false]],

			/* Configuration manager (7 = UR_ACTION_CREATE) */
			'Configuration manager FunctionalCI write'       => [3, ['class' => 'FunctionalCI', 'action' => 7, 'res' => true]],
			'Configuration manager UserRequest write'        => [3, ['class' => 'UserRequest', 'action' => 7, 'res' => false]],
			'Configuration manager URP_UserProfile write'    => [3, ['class' => 'URP_UserProfile', 'action' => 7, 'res' => false]],
			'Configuration manager UserLocal write'          => [3, ['class' => 'UserLocal', 'action' => 7, 'res' => false]],
			'Configuration manager ModuleInstallation write' => [3, ['class' => 'ModuleInstallation', 'action' => 7, 'res' => false]],

			/* Administrator (1 = UR_ACTION_READ)  */
			'Administrator FunctionalCI read'                => [1, ['class' => 'FunctionalCI', 'action' => 1, 'res' => true]],
			'Administrator UserRequest read'                 => [1, ['class' => 'UserRequest', 'action' => 1, 'res' => true]],
			'Administrator URP_UserProfile read'             => [1, ['class' => 'URP_UserProfile', 'action' => 1, 'res' => true]],
			'Administrator UserLocal read'                   => [1, ['class' => 'UserLocal', 'action' => 1, 'res' => true]],
			'Administrator ModuleInstallation read'          => [1, ['class' => 'ModuleInstallation', 'action' => 1, 'res' => true]],

			/* User Portal  (1 = UR_ACTION_READ) */
			'User Portal FunctionalCI read'                  => [2, ['class' => 'FunctionalCI', 'action' => 1, 'res' => true]],
			'User Portal UserRequest read'                   => [2, ['class' => 'UserRequest', 'action' => 1, 'res' => true]],
			'User Portal URP_UserProfile read'               => [2, ['class' => 'URP_UserProfile', 'action' => 1, 'res' => false]],
			'User Portal UserLocal read'                     => [2, ['class' => 'UserLocal', 'action' => 1, 'res' => false]],
			'User Portal ModuleInstallation read'            => [2, ['class' => 'ModuleInstallation', 'action' => 1, 'res' => true]],

			/* Configuration manager (1 = UR_ACTION_READ) */
			'Configuration manager FunctionalCI read'        => [3, ['class' => 'FunctionalCI', 'action' => 1, 'res' => true]],
			'Configuration manager UserRequest read'         => [3, ['class' => 'UserRequest', 'action' => 1, 'res' => true]],
			'Configuration manager URP_UserProfile read'     => [3, ['class' => 'URP_UserProfile', 'action' => 1, 'res' => false]],
			'Configuration manager UserLocal read'           => [3, ['class' => 'UserLocal', 'action' => 1, 'res' => false]],
			'Configuration manager ModuleInstallation read'  => [3, ['class' => 'ModuleInstallation', 'action' => 1, 'res' => true]],
		];
	}


	/** Test IsActionAllowedOnAttribute
	 *
	 * @dataProvider ActionAllowedOnAttributeProvider
	 *
	 * @param int $iProfileId
	 * @param array $aClassActionResult
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public function testIsActionAllowedOnAttribute(int $iProfileId, array $aClassActionResult)
	{
		$this->CreateUniqueUserAndLogin('test1', $iProfileId);
		$sClass = $aClassActionResult['class'];
		$bRes = UserRights::IsActionAllowedOnAttribute($sClass, self::$aClasses[$sClass]['attcode'], $aClassActionResult['action']) == UR_ALLOWED_YES;
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
	public function ActionAllowedOnAttributeProvider(): array
	{
		return [
			/* Administrator (2 = UR_ACTION_MODIFY)  */
			'Administrator FunctionalCI'               => [1, ['class' => 'FunctionalCI', 'action' => 2, 'res' => true]],
			'Administrator UserRequest'                => [1, ['class' => 'UserRequest', 'action' => 2, 'res' => true]],
			'Administrator URP_UserProfile'            => [1, ['class' => 'URP_UserProfile', 'action' => 2, 'res' => true]],
			'Administrator UserLocal'                  => [1, ['class' => 'UserLocal', 'action' => 2, 'res' => true]],
			'Administrator ModuleInstallation'         => [1, ['class' => 'ModuleInstallation', 'action' => 2, 'res' => true]],

			/* User Portal  (2 = UR_ACTION_MODIFY) */
			'User Portal FunctionalCI'                 => [2, ['class' => 'FunctionalCI', 'action' => 2, 'res' => false]],
			'User Portal UserRequest'                  => [2, ['class' => 'UserRequest', 'action' => 2, 'res' => true]],
			'User Portal URP_UserProfile'              => [2, ['class' => 'URP_UserProfile', 'action' => 2, 'res' => false]],
			'User Portal UserLocal'                    => [2, ['class' => 'UserLocal', 'action' => 2, 'res' => false]],
			'User Portal ModuleInstallation'           => [2, ['class' => 'ModuleInstallation', 'action' => 2, 'res' => true]],

			/* Configuration manager (2 = UR_ACTION_MODIFY) */
			'Configuration manager FunctionalCI'       => [3, ['class' => 'FunctionalCI', 'action' => 2, 'res' => true]],
			'Configuration manager UserRequest'        => [3, ['class' => 'UserRequest', 'action' => 2, 'res' => false]],
			'Configuration manager URP_UserProfile'    => [3, ['class' => 'URP_UserProfile', 'action' => 2, 'res' => false]],
			'Configuration manager UserLocal'          => [3, ['class' => 'UserLocal', 'action' => 2, 'res' => false]],
			'Configuration manager ModuleInstallation' => [3, ['class' => 'ModuleInstallation', 'action' => 2, 'res' => true]],
		];
	}

	/**
	 * @dataProvider ProfileDenyingConsoleProvider
	 * @doesNotPerformAssertions
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public function testProfileDenyingConsole(int $iProfileId)
	{
		$oUser = $this->CreateUniqueUserAndLogin('test1', $iProfileId);

		try {
			$this->AddProfileToUser($oUser, 2);
			$this->fail('Profile should not be added');
		} catch (CoreCannotSaveObjectException $e) {
		}
	}

	public function ProfileDenyingConsoleProvider(): array
	{
		return [
			'Administrator'         => [1],
		];
	}

	/**
	 * @dataProvider ProfileCannotModifySelfProvider
	 * @doesNotPerformAssertions
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public function testProfileCannotModifySelf(int $iProfileId)
	{
		$oUser = $this->CreateUniqueUserAndLogin('test1', $iProfileId);

		try {
			$this->AddProfileToUser($oUser, 1); // trying to become an admin
			$this->fail('User should not modify self');
		} catch (CoreException $e) {
		}
	}

	public function ProfileCannotModifySelfProvider(): array
	{
		return [
			'Configuration manager' => [3],
		];
	}

	/**
	 * @dataProvider DeletingSelfUserProvider
	 * @doesNotPerformAssertions
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public function testDeletingSelfUser(int $iProfileId)
	{
		$oUser = $this->CreateUniqueUserAndLogin('test1', $iProfileId);

		try {
			$oUser->DBDelete();
			$this->fail('Current User cannot be deleted');
		} catch (DeleteException $e) {
		}
	}

	public function DeletingSelfUserProvider(): array
	{
		return [
			'Administrator'         => [1],
			'Configuration manager' => [3],
		];
	}

	/**
	 * @dataProvider RemovingOwnContactProvider
	 * @doesNotPerformAssertions
	 *
	 * @param int $iProfileId
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public function testRemovingOwnContact(int $iProfileId)
	{
		$oUser = $this->CreateUniqueUserAndLogin('test1', $iProfileId);

		$oUser->Set('contactid', 0);

		try {
			$oUser->DBWrite();
			$this->fail('Current User cannot remove his own contact');
		} catch (CoreCannotSaveObjectException $e) {
		}
	}

	public function RemovingOwnContactProvider(): array
	{
		return [
			'Administrator'         => [1],
			'Configuration manager' => [3],
		];
	}

	/**
	 * @doesNotPerformAssertions
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public function testUpgradingToAdmin()
	{
		$oUser = $this->CreateUniqueUserAndLogin('test1', 3);

		try {
			$this->AddProfileToUser($oUser, 1);
			$this->fail('Should not be able to upgrade to Administrator');
		} catch (CoreCannotSaveObjectException $e) {
		} catch (CoreException $e) {
		}
	}

	/**
	 * @doesNotPerformAssertions
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionUnknownLanguage
	 * @throws \OQLException
	 */
	public function testDenyingUserModification()
	{
		$oUser = $this->CreateUniqueUserAndLogin('test1', 1);
		$this->AddProfileToUser($oUser, 3);

		// Keep only the profile 3 (remove profile 1)
		$oUserProfile = new URP_UserProfile();
		$oUserProfile->Set('profileid', 3);
		$oUserProfile->Set('reason', 'UNIT Tests');
		$oSet = DBObjectSet::FromObject($oUserProfile);
		$oUser->Set('profile_list', $oSet);

		try {
			$oUser->DBWrite();
			$this->fail('Should not be able to deny User modifications');
		} catch (CoreCannotSaveObjectException $e) {
		}
	}

	/**
	 *@dataProvider NonAdminCanListOwnProfilesProvider
	 */
	public function testNonAdminCanListOwnProfiles($bHideAdministrators)
	{
		utils::GetConfig()->Set('security.hide_administrators', $bHideAdministrators);
		$oUser = $this->CreateUniqueUserAndLogin('test1', 2); // portal user

		// List the link between the User and the Profiles
		$oSearch = new DBObjectSearch('URP_UserProfile');
		$oSearch->AddCondition('userid', $oUser->GetKey());
		$oSet = new DBObjectSet($oSearch);
		$this->assertEquals(1, $oSet->Count());

		// Get the Profiles as well
		$oSearch = DBObjectSearch::FromOQL('SELECT URP_Profiles JOIN URP_UserProfile ON URP_UserProfile.profileid = URP_Profiles.id WHERE URP_UserProfile.userid='.$oUser->GetKey());
		$oSet = new DBObjectSet($oSearch);
		$this->assertEquals(1, $oSet->Count());
	}

	public function NonAdminCanListOwnProfilesProvider(): array
	{
		return [
			'with Admins visible'=> [false],
			'with Admins hidden' => [true],
		];
	}
	/**
	 * @dataProvider NonAdminCannotListAdminProfilesProvider
	 */
	public function testNonAdminCannotListAdminProfiles($bHideAdministrators, $iExpectedCount)
	{
		utils::GetConfig()->Set('security.hide_administrators', $bHideAdministrators);

		$oUserAdmin = $this->CreateUser('admin1', 1);
		$this->CreateUniqueUserAndLogin('test1', 2); // portal user

		$oSearch = new DBObjectSearch('URP_UserProfile');
		$oSearch->AddCondition('userid', $oUserAdmin->GetKey());
		$oSet = new DBObjectSet($oSearch);
		$this->assertEquals($iExpectedCount, $oSet->Count());
		// Get the Profiles as well
		$oSearch = DBObjectSearch::FromOQL('SELECT URP_Profiles JOIN URP_UserProfile ON URP_UserProfile.profileid = URP_Profiles.id WHERE URP_UserProfile.userid='.$oUserAdmin->GetKey());
		$oSet = new DBObjectSet($oSearch);
		$this->assertEquals($iExpectedCount, $oSet->Count());
	}

	public function NonAdminCannotListAdminProfilesProvider(): array
	{
		return [
			'with Admins visible'=> [false, 1],
			'with Admins hidden' => [true, 0],
		];
	}

	public function testFindUser_ExistingInternalUser()
	{
		$sLogin = 'AnInternalUser'.uniqid();
		$iKey = $this->GivenObjectInDB(\UserLocal::class, ['login' => $sLogin]);

		$this->assertDBQueryCount(
			1,
			fn() => $this->FindUserAndAssertItHasBeenFound($sLogin, $iKey),
			'A query should be performed the first time FindUser is called'
		);

		$this->assertDBQueryCount(
			0,
			fn() => $this->FindUserAndAssertItHasBeenFound($sLogin, $iKey),
			'The cache should prevent additional queries on subsequent calls'
		);
	}

	public function testFindUser_ExistingExternalUser()
	{
		$sLogin = 'AnExternalUser'.uniqid();
		$iKey = $this->GivenObjectInDB(\UserExternal::class, ['login' => $sLogin]);

		$this->assertDBQueryCount(
			2,
			fn() => $this->FindUserAndAssertItHasBeenFound($sLogin, $iKey),
			'Some queries should be performed the first time FindUser is called'
		);

		$this->assertDBQueryCount(
			0,
			fn() => $this->FindUserAndAssertItHasBeenFound($sLogin, $iKey),
			'The cache should prevent additional queries on subsequent calls'
		);
	}

	public function testFindUser_UnknownLogin()
	{
		$sLogin = 'NobodyLogin';

		$this->assertDBQueryCount(
			2,
			fn() => $this->FindUserAndAssertItWasNotFound($sLogin),
			'Some queries should be performed the first time FindUser is called'
		);

		$this->assertDBQueryCount(
			0,
			fn() => $this->FindUserAndAssertItWasNotFound($sLogin),
			'The cache should prevent additional queries on subsequent calls'
		);
	}

	public function FindUserAndAssertItHasBeenFound($sLogin, $iExpectedKey)
	{
		$oUser = $this->InvokeNonPublicStaticMethod(UserRights::class, "FindUser", [$sLogin]);
		static::assertIsDBObject(\User::class, $iExpectedKey, $oUser, 'FindUser should return the User object corresponding to the login');
	}
	public function FindUserAndAssertItWasNotFound($sLogin)
	{
		$oUser = $this->InvokeNonPublicStaticMethod(UserRights::class, "FindUser", [$sLogin]);
		static::assertNull($oUser, 'FindUser should return null when the login is unknown');
	}
}
