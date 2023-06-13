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
 * Date: 25/01/2018
 * Time: 11:12
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenuFactory;
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
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class UserRightsTest extends ItopDataTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		try {
			utils::GetConfig()->SetModuleSetting('authent-local', 'password_validation.pattern', '');
			self::CreateUser('admin', 1);
		}
		catch (CoreCannotSaveObjectException $e) {
		}
	}

	public static $aClasses = [
		'FunctionalCI'       => ['class' => 'FunctionalCI', 'attcode' => 'name'],
		'URP_UserProfile'    => ['class' => 'URP_UserProfile', 'attcode' => 'reason'],
		'UserLocal'          => ['class' => 'UserLocal', 'attcode' => 'login'],
		'UserRequest'        => ['class' => 'UserRequest', 'attcode' => 'title'],
		'ModuleInstallation' => ['class' => 'ModuleInstallation', 'attcode' => 'name'],
	];


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

	/**
	 * @param string $sLogin
	 * @param int $iProfileId initial profile
	 *
	 * @return \DBObject
	 * @throws \CoreException
	 * @throws \Exception
	 */
	protected function AddUser(string $sLogin, int $iProfileId): DBObject
	{
		$oUser = self::CreateUser($sLogin, $iProfileId);
		$oUser->DBUpdate();

		return $oUser;
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

	/** Test IsActionAllowed
	 *
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
		$this->AddUser('test1', $iProfileId);
		$_SESSION = array();
		UserRights::Login('test1');
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
		$this->AddUser('test1', $iProfileId);
		$_SESSION = [];
		UserRights::Login('test1');
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
		$oUser = $this->AddUser('test1', $iProfileId);
		$_SESSION = [];
		UserRights::Login('test1');

		try {
			$this->AddProfileToUser($oUser, 2);
			$this->fail('Profile should not be added');
		} catch (CoreCannotSaveObjectException $e) {
		}

		// logout
		$_SESSION = [];
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
		$oUser = $this->AddUser('test1', $iProfileId);
		$_SESSION = [];
		UserRights::Login('test1');

		try {
			$this->AddProfileToUser($oUser, 1); // trying to become an admin
			$this->fail('User should not modify self');
		} catch (CoreException $e) {
		}

		// logout
		$_SESSION = [];
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
		$oUser = $this->AddUser('test1', $iProfileId);
		$_SESSION = [];
		UserRights::Login('test1');

		try {
			$oUser->DBDelete();
			$this->fail('Current User cannot be deleted');
		} catch (DeleteException $e) {
		}

		// logout
		$_SESSION = [];
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
		$oUser = $this->AddUser('test1', $iProfileId);
		$_SESSION = [];
		UserRights::Login('test1');

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
		$oUser = $this->AddUser('test1', 3);
		$_SESSION = [];
		UserRights::Login('test1');

		try {
			$this->AddProfileToUser($oUser, 1);
			$this->fail('Should not be able to upgrade to Administrator');
		} catch (CoreCannotSaveObjectException $e) {
		} catch (CoreException $e) {
		}

		// logout
		$_SESSION = [];
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
		$oUser = $this->AddUser('test1', 1);
		$_SESSION = [];
		UserRights::Login('test1');
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

		// logout
		$_SESSION = [];
	}

	/**
	 *@dataProvider NonAdminCanListOwnProfilesProvider
	 */
	public function testNonAdminCanListOwnProfiles($bHideAdministrators)
	{
		$oUser = $this->AddUser('test1', 2); // portal user
		$_SESSION = [];
		utils::GetConfig()->Set('security.hide_administrators', $bHideAdministrators);
		UserRights::Login('test1');

		// List the link between the User and the Profiles
		$oSearch = new DBObjectSearch('URP_UserProfile');
		$oSearch->AddCondition('userid', $oUser->GetKey());
		$oSet = new DBObjectSet($oSearch);
		$this->assertEquals(1, $oSet->Count());

		// Get the Profiles as well
		$oSearch = DBObjectSearch::FromOQL('SELECT URP_Profiles JOIN URP_UserProfile ON URP_UserProfile.profileid = URP_Profiles.id WHERE URP_UserProfile.userid='.$oUser->GetKey());
		$oSet = new DBObjectSet($oSearch);
		$this->assertEquals(1, $oSet->Count());

		// logout
		$_SESSION = [];
	}

	public function NonAdminCanListOwnProfilesProvider(): array
	{
		return [
			'with Admins visible'=> [false],
			'with Admins hidden' => [true],
		];
	}
	/**
	 *@dataProvider NonAdminCannotListAdminProfilesProvider
	 */
	public function testNonAdminCannotListAdminProfiles($bHideAdministrators, $iExpectedCount)
	{
		utils::GetConfig()->Set('security.hide_administrators', $bHideAdministrators);

		$this->AddUser('test1', 2); // portal user
		$oUserAdmin = $this->AddUser('admin1', 1);
		$_SESSION = [];
		UserRights::Login('test1');

		$oSearch = new DBObjectSearch('URP_UserProfile');
		$oSearch->AddCondition('userid', $oUserAdmin->GetKey());
		$oSet = new DBObjectSet($oSearch);
		$this->assertEquals($iExpectedCount, $oSet->Count());
		// Get the Profiles as well
		$oSearch = DBObjectSearch::FromOQL('SELECT URP_Profiles JOIN URP_UserProfile ON URP_UserProfile.profileid = URP_Profiles.id WHERE URP_UserProfile.userid='.$oUserAdmin->GetKey());
		$oSet = new DBObjectSet($oSearch);
		$this->assertEquals($iExpectedCount, $oSet->Count());

		// logout
		$_SESSION = [];
	}

	public function NonAdminCannotListAdminProfilesProvider(): array
	{
		return [
			'with Admins visible'=> [false, 1],
			'with Admins hidden' => [true, 0],
		];
	}

	/**
	 * @dataProvider WithConstraintParameterProvider
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param bool $bExpected
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function testWithConstraintParameter(string $sClass, string $sAttCode, bool $bExpected)
	{
		$oAttDef = \MetaModel::GetAttributeDef($sClass, $sAttCode);
		$this->assertTrue(method_exists($oAttDef, "GetHasConstraint"));
		$this->assertEquals($bExpected, $oAttDef->GetHasConstraint());
	}

	public function WithConstraintParameterProvider()
	{
		return [
			['User', 'profile_list', true],
			['User', 'allowed_org_list', true],
			['Person', 'team_list', false],
		];
	}

	public function PortaPowerUserProvider(){
		return [
			'Portal power user only => user should be repaired by adding User portal profile' => [
				'aAssociatedProfilesBeforeUserCreation' => [
					'Portal power user'
				],
				'aExpectedAssociatedProfilesAfterUserCreation'=> [
					'Portal power user',
					'Portal user',
				]
			],
			'Portal power user + Support Agent => profiles untouched' => [
				'aAssociatedProfilesBeforeUserCreation' => [
					'Portal power user',
					'Support Agent',
				],
				'aExpectedAssociatedProfilesAfterUserCreation'=> [
					'Portal power user',
					'Support Agent',
				]
			],
		];
	}

	/**
	 * @since 3.1.0 N°5324
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserLocalCreation($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserLocal();
		$sLogin = 'testUserLocalCreationWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'ABCD1234@gabuzomeu');
		$oUser->Set('language', 'EN US');
		$this->commonUserCreation($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @since 3.1.0 N°5324
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserLocalUpdate($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserLocal();
		$sLogin = 'testUserLocalUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'ABCD1234@gabuzomeu');
		$oUser->Set('language', 'EN US');
		$this->commonUserUpdate($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @since 3.1.0 N°5324
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserLDAPCreation($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserLDAP();
		$sLogin = 'testUserLDAPCreationWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$this->commonUserCreation($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @since 3.1.0 N°5324
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserLDAPUpdate($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserLDAP();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$this->commonUserUpdate($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @since 3.1.0 N°5324
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserExternalCreation($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPCreationWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$this->commonUserCreation($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @since 3.1.0 N°5324
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserExternalUpdate($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$this->commonUserUpdate($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	public function CreateUserForProfileTesting(\User $oUserToCreate, array $aAssociatedProfilesBeforeUserCreation) : array
	{
		$aProfiles = [];
		$oSearch = \DBSearch::FromOQL("SELECT URP_Profiles");
		$oProfileSet = new DBObjectSet($oSearch);
		while (($oProfile = $oProfileSet->Fetch()) != null){
			$aProfiles[$oProfile->Get('name')] = $oProfile;
		}

		$this->CreateTestOrganization();
		$oContact = $this->CreatePerson("1");
		$iContactid = $oContact->GetKey();

		$oUserToCreate->Set('contactid', $iContactid);
		$sUserClass = get_class($oUserToCreate);

		$oUserProfileList = $oUserToCreate->Get('profile_list');
		foreach ($aAssociatedProfilesBeforeUserCreation as $sProfileName){
			$oUserProfile = new URP_UserProfile();
			$oProfile = $aProfiles[$sProfileName];
			$oUserProfile->Set('profileid', $oProfile->GetKey());
			$oUserProfile->Set('reason', 'UNIT Tests');
			$oUserProfileList->AddItem($oUserProfile);
		}

		$oUserToCreate->Set('profile_list', $oUserProfileList);
		$sId = $oUserToCreate->DBInsert();

		return [ $sId, $aProfiles];
	}

	public function commonUserCreation($oUserToCreate, $aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$sUserClass = get_class($oUserToCreate);
		list ($sId, $aProfiles)  = $this->CreateUserForProfileTesting($oUserToCreate, $aAssociatedProfilesBeforeUserCreation);

		$this->CheckProfilesAreOk($sUserClass, $sId, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	public function CheckProfilesAreOk($sUserClass, $sId, $aExpectedAssociatedProfilesAfterUserCreation){
		$oUser = \MetaModel::GetObject($sUserClass, $sId);
		$oUserProfileList = $oUser->Get('profile_list');
		$aProfilesAfterCreation=[];
		while (($oProfile = $oUserProfileList->Fetch()) != null){
			$aProfilesAfterCreation[] = $oProfile->Get('profile');
		}

		foreach ($aExpectedAssociatedProfilesAfterUserCreation as $sExpectedProfileName){
			$this->assertTrue(in_array($sExpectedProfileName, $aProfilesAfterCreation),
				"profile \'$sExpectedProfileName\' should be asociated to user after creation. " .  var_export($aProfilesAfterCreation, true) );
		}

		$_SESSION = [];

		//$this->expectException(\Exception::class);
		UserRights::Login($oUser->Get('login'));

		if (! UserRights::IsPortalUser()) {
			//calling this API triggers Fatal Error on below OQL used by \User->GetContactObject() for a user with only 'portal power user' profile
			/**
			 * Error: No result for the single row query: 'SELECT DISTINCT `Contact`.`id` AS `Contactid`, `Contact`.`name` AS `Contactname`, `Contact`.`status` AS `Contactstatus`, `Contact`.`org_id` AS `Contactorg_id`, `Organization_org_id`.`name` AS `Contactorg_name`, `Contact`.`email` AS `Contactemail`, `Contact`.`phone` AS `Contactphone`, `Contact`.`notify` AS `Contactnotify`, `Contact`.`function` AS `Contactfunction`, `Contact`.`finalclass` AS `Contactfinalclass`, IF((`Contact`.`finalclass` IN ('Team', 'Contact')), CAST(CONCAT(COALESCE(`Contact`.`name`, '')) AS CHAR), CAST(CONCAT(COALESCE(`Contact_poly_Person`.`first_name`, ''), COALESCE(' ', ''), COALESCE(`Contact`.`name`, '')) AS CHAR)) AS `Contactfriendlyname`, COALESCE((`Contact`.`status` = 'inactive'), 0) AS `Contactobsolescence_flag`, `Contact`.`obsolescence_date` AS `Contactobsolescence_date`, CAST(CONCAT(COALESCE(`Organization_org_id`.`name`, '')) AS CHAR) AS `Contactorg_id_friendlyname`, COALESCE((`Organization_org_id`.`status` = 'inactive'), 0) AS `Contactorg_id_obsolescence_flag` FROM `contact` AS `Contact` INNER JOIN `organization` AS `Organization_org_id` ON `Contact`.`org_id` = `Organization_org_id`.`id` LEFT JOIN `person` AS `Contact_poly_Person` ON `Contact`.`id` = `Contact_poly_Person`.`id` WHERE ((`Contact`.`id` = 40) AND 0) '.
			 */
			NavigationMenuFactory::MakeStandard();
		}

		$this->assertTrue(true, 'after fix N°5324 no exception raised');
		// logout
		$_SESSION = [];
	}

	public function commonUserUpdate($oUserToCreate, $aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$sUserClass = get_class($oUserToCreate);
		list ($sId, $aProfiles)  = $this->CreateUserForProfileTesting($oUserToCreate, ["Administrator"]);

		$oUserToUpdate = \MetaModel::GetObject($sUserClass, $sId);
		$oProfileList = $oUserToUpdate->Get('profile_list');
		while($oObj = $oProfileList->Fetch()){
			$oProfileList->RemoveItem($oObj->GetKey());
		}

		foreach ($aAssociatedProfilesBeforeUserCreation as $sProfileName){
			$oAdminUrpProfile = new URP_UserProfile();
			$oProfile = $aProfiles[$sProfileName];
			$oAdminUrpProfile->Set('profileid', $oProfile->GetKey());
			$oAdminUrpProfile->Set('reason', 'UNIT Tests');
			$oProfileList->AddItem($oAdminUrpProfile);
		}

		$oUserToUpdate->Set('profile_list', $oProfileList);
		$oUserToUpdate->DBWrite();

		$this->CheckProfilesAreOk($sUserClass, $sId, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	public function testUpdateUserExternalProfilesViaLinks(){
		$aInitialProfiles = [ "Administrator", "Portal power user"];

		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);

		$sUserClass = get_class($oUser);
		list ($sId, $aProfiles)  = $this->CreateUserForProfileTesting($oUser, $aInitialProfiles);

		$aURPUserProfileByUser = $this->GetURPUserProfileByUser($sId);
		$aProfilesToRemove = ["Administrator"];
		foreach ($aProfilesToRemove as $sProfileName){
			if (array_key_exists($sProfileName, $aURPUserProfileByUser)){
				$oURPUserProfile = $aURPUserProfileByUser[$sProfileName];
				$oURPUserProfile->DBDelete();
			}
		}

		$aExpectedProfilesAfterUpdate = ["Portal power user", "Portal user"];
		$this->CheckProfilesAreOk($sUserClass, $sId, $aExpectedProfilesAfterUpdate);
	}

	public function BulkUpdateUserExternalProfilesViaLinksProvider(){
		return [
			'user profiles REPAIR 1' => [
				"aInitialProfiles" => [ "Administrator"],
				"aOperation" => [
					'-Administrator',
					'+Portal power user',
				],
				"aExpectedProfilesAfterUpdate" => ["Portal power user", "Portal user"],
			],
			'user profiles REPAIR 2' => [
				"aInitialProfiles" => [ "Administrator"],
				"aOperation" => [
					'+Portal power user',
					'-Administrator',
				],
				"aExpectedProfilesAfterUpdate" => ["Portal power user", "Portal user"],
			],
			'user profiles REPAIR 3' => [
				"aInitialProfiles" => [ "Administrator", "Portal power user"],
				"aOperation" => [
					'-Administrator',
				],
				"aExpectedProfilesAfterUpdate" => ["Portal power user", "Portal user"],
			],
			'NOTHING DONE with 1 profile' => [
				"aInitialProfiles" => [ "Administrator", "Portal power user"],
				"aOperation" => [
					'-Portal power user',
				],
				"aExpectedProfilesAfterUpdate" => ["Administrator"],
			],
			'NOTHING DONE with 2 profiles including power...' => [
				"aInitialProfiles" => [ "Administrator"],
				"aOperation" => [
					'+Portal power user',
				],
				"aExpectedProfilesAfterUpdate" => ["Administrator", "Portal power user"],
			],
			'NOTHING DONE with 2 profiles including power again ...' => [
				"aInitialProfiles" => [ "Administrator"],
				"aOperation" => [
					'+Portal power user',
				],
				"aExpectedProfilesAfterUpdate" => ["Portal user", "Portal power user"],
			],
		];
	}

	/**
	 * @dataProvider BulkUpdateUserExternalProfilesViaLinksProvider
	 */
	public function testBulkUpdateUserExternalProfilesViaLinks($aInitialProfiles, $aOperation, $aExpectedProfilesAfterUpdate){
		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);

		$sUserClass = get_class($oUser);
		list ($sId, $aProfiles)  = $this->CreateUserForProfileTesting($oUser, $aInitialProfiles);

		\cmdbAbstractObject::SetEventDBLinksChangedBlocked(true);

		$aURPUserProfileByUser = $this->GetURPUserProfileByUser($sId);
		foreach ($aOperation as $sOperation){
			$sOp = substr($sOperation,0, 1);
			$sProfileName = substr($sOperation,1);

			if ($sOp === "-"){
				if (array_key_exists($sProfileName, $aURPUserProfileByUser)){
					$oURPUserProfile = $aURPUserProfileByUser[$sProfileName];
					$oURPUserProfile->DBDelete();
				}
			} else {
				$oAdminUrpProfile = new URP_UserProfile();
				$oProfile = $aProfiles[$sProfileName];
				$oAdminUrpProfile->Set('profileid', $oProfile->GetKey());
				$oAdminUrpProfile->Set('userid', $sId);
				$oAdminUrpProfile->DBInsert();
			}
		}

		\cmdbAbstractObject::SetEventDBLinksChangedBlocked(false);
		\cmdbAbstractObject::FireEventDbLinksChangedForAllObjects();

		$this->CheckProfilesAreOk($sUserClass, $sId, $aExpectedProfilesAfterUpdate);
	}

	private function GetURPUserProfileByUser($iUserId) : array {
		$aRes = [];
		$oSearch = \DBSearch::FromOQL("SELECT URP_UserProfile WHERE userid=$iUserId");
		$oSet = new DBObjectSet($oSearch);
		while (($oURPUserProfile = $oSet->Fetch()) != null){
			$aRes[$oURPUserProfile->Get('profile')] = $oURPUserProfile;
		}

		return $aRes;
	}
}
