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

namespace Combodo\iTop\Test\UnitTest\Module\iTopProfilesItil;

use Combodo\iTop\Application\Helper\Session;
use Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenuFactory;
use Combodo\iTop\ItilProfiles\UserProfilesEventListener;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DBObjectSet;
use URP_UserProfile;
use UserRights;

/**
 * @since 3.1.0 N°5324
 * @group itopRequestMgmt
 * @group userRights
 * @group defaultProfiles
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class UserProfilesEventListenerTest extends ItopDataTestCase
{
	public function setUp(): void {
		parent::setUp();

		//reset conf to have nominal behaviour
		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME, null);
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
			'Portal power user + Configuration Manager => profiles untouched' => [
				'aAssociatedProfilesBeforeUserCreation' => [
					'Portal power user',
					'Configuration Manager',
				],
				'aExpectedAssociatedProfilesAfterUserCreation'=> [
					'Portal power user',
					'Configuration Manager',
				]
			],
		];
	}

	/**
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
		$this->commonUserCreationTest($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
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
		$this->commonUserUpdateTest($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserLDAPCreation($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserLDAP();
		$sLogin = 'testUserLDAPCreationWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$this->commonUserCreationTest($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserLDAPUpdate($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserLDAP();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$this->commonUserUpdateTest($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserExternalCreation($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPCreationWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$this->commonUserCreationTest($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @dataProvider PortaPowerUserProvider
	 */
	public function testUserExternalUpdate($aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation)
	{
		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$this->commonUserUpdateTest($oUser, $aAssociatedProfilesBeforeUserCreation, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	public function CreateUserForProfileTesting(\User $oUserToCreate, array $aAssociatedProfilesBeforeUserCreation, $bDbInsert=true) : array
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
		if ($bDbInsert){
			$sId = $oUserToCreate->DBInsert();
		} else {
			$sId = -1;
		}

		return [ $sId, $aProfiles];
	}

	public function commonUserCreationTest($oUserToCreate, $aAssociatedProfilesBeforeUserCreation,
		$aExpectedAssociatedProfilesAfterUserCreation, $bTestUserItopAccess=true)
	{
		$sUserClass = get_class($oUserToCreate);
		list ($sId, $aProfiles)  = $this->CreateUserForProfileTesting($oUserToCreate, $aAssociatedProfilesBeforeUserCreation);

		$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aExpectedAssociatedProfilesAfterUserCreation, $bTestUserItopAccess);
	}

	public function CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aExpectedAssociatedProfilesAfterUserCreation, $bTestItopConnection=true){
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

		if (! $bTestItopConnection){
			return;
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

	public function commonUserUpdateTest($oUserToCreate, $aAssociatedProfilesBeforeUserCreation,
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

		$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aExpectedAssociatedProfilesAfterUserCreation);
	}

	/**
	 * @dataProvider ProfilesLinksProvider
	 */
	public function testProfilesLinksDBDelete(string $sProfileNameToRemove, $bRaiseException=false){
		$aInitialProfiles = [ $sProfileNameToRemove, "Portal power user"];

		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);

		$sUserClass = get_class($oUser);
		list ($sId, $aProfiles)  = $this->CreateUserForProfileTesting($oUser, $aInitialProfiles);

		if ($bRaiseException){
			$this->expectException(\DeleteException::class);
		}

		$aURPUserProfileByUser = $this->GetURPUserProfileByUser($sId);
		if (array_key_exists($sProfileNameToRemove, $aURPUserProfileByUser)){
			$oURPUserProfile = $aURPUserProfileByUser[$sProfileNameToRemove];
			$oURPUserProfile->DBDelete();
		}

		if (! $bRaiseException) {
			$aExpectedProfilesAfterUpdate = ["Portal power user", "Portal user"];
			$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aExpectedProfilesAfterUpdate);
		}
	}

	/**
	 * @dataProvider ProfilesLinksProvider
	 */
	public function testProfilesLinksEdit_ChangeProfileId(string $sInitialProfile, $bRaiseException=false){
		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);

		$sUserClass = get_class($oUser);
		list ($sId, $aProfiles)  = $this->CreateUserForProfileTesting($oUser, [$sInitialProfile]);

		$oURP_Profile = \MetaModel::GetObjectByColumn("URP_Profiles", "name", "Portal power user");

		$aURPUserProfileByUser = $this->GetURPUserProfileByUser($sId);

		if ($bRaiseException){
			$this->expectException(\CoreCannotSaveObjectException::class);
		}

		if (array_key_exists($sInitialProfile, $aURPUserProfileByUser)){
			$oURPUserProfile = $aURPUserProfileByUser[$sInitialProfile];
			$oURPUserProfile->Set('profileid', $oURP_Profile->GetKey());
			$oURPUserProfile->DBWrite();
		}

		if (!$bRaiseException) {
			$aExpectedProfilesAfterUpdate = ["Portal power user", "Portal user"];
			$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aExpectedProfilesAfterUpdate);
		}
	}

	public function ProfilesLinksProvider() {
		return [
			"Administrator" => [ "sProfileNameToMove" => "Administrator" ],
			"Portal user" => [ "sProfileNameToMove" => "Portal user", "bRaiseException" => true ],
		];
	}

	/**
	 * @dataProvider ProfilesLinksProvider
	 */
	public function testProfilesLinksEdit_ChangeUserId($sProfileNameToMove, $bRaiseException=false){
		$aInitialProfiles = [ $sProfileNameToMove, "Portal power user"];

		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);

		$sUserClass = get_class($oUser);
		list ($sId, $aProfiles)  = $this->CreateUserForProfileTesting($oUser, $aInitialProfiles);

		$oUser = new \UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		list ($sAnotherUserId, $aProfiles) = $this->CreateUserForProfileTesting($oUser, ["Configuration Manager"]);

		if ($bRaiseException){
			$this->expectException(\CoreCannotSaveObjectException::class);
		}

		$aURPUserProfileByUser = $this->GetURPUserProfileByUser($sId);
		if (array_key_exists($sProfileNameToMove, $aURPUserProfileByUser)){
			$oURPUserProfile = $aURPUserProfileByUser[$sProfileNameToMove];
			$oURPUserProfile->Set('userid', $sAnotherUserId);
			$oURPUserProfile->DBWrite();
		}

		if (! $bRaiseException) {
			$aExpectedProfilesAfterUpdate = [$sProfileNameToMove, "Configuration Manager"];
			$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sAnotherUserId, $aExpectedProfilesAfterUpdate);

			$aExpectedProfilesAfterUpdate = ["Portal power user", "Portal user"];
			$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aExpectedProfilesAfterUpdate);
		}
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

	public function testUserProfilesEventListenerInit_nominal(){
		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init();

		$this->assertTrue($oUserProfilesEventListener->IsRepairmentEnabled());
	}

	public function testUserProfilesEventListenerInit_badlyconfigured(){
		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME, "a string instead of an array");

		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init();

		$this->assertFalse($oUserProfilesEventListener->IsRepairmentEnabled());
	}

	public function testUserProfilesEventListenerInit_specifically_disabled(){
		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME, []);
		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init();

		$this->assertFalse($oUserProfilesEventListener->IsRepairmentEnabled());
	}

	public function CustomizedPortalsProvider(){
		return [
			'console + customized portal' => [
				'aPortalDispatcherData' => [
					'customer-portal',
					'backoffice'
				]],
			'console + itop portal + customized portal' => [
				'aPortalDispatcherData' => [
					'itop-portal',
					'customer-portal',
					'backoffice'
				]
			],
		];
	}

	/**
	 * @dataProvider CustomizedPortalsProvider
	 */
	public function testUserProfilesEventListenerInit_furtherportals_norepairmentconfigured($aPortalDispatcherData){
		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init($aPortalDispatcherData);

		$this->assertFalse($oUserProfilesEventListener->IsRepairmentEnabled());
	}

	public function testUserProfilesEventListenerInit_furtherportals_repairmentconfigured(){
		$aPortalDispatcherData = [
			'itop-portal',
			'customer-portal',
			'backoffice'
		];

		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME, ['Portal power user' => 'Portal user']);

		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init($aPortalDispatcherData);

		$this->assertTrue($oUserProfilesEventListener->IsRepairmentEnabled());
	}

	public function testUserProfilesEventListenerInit_with_unknownprofile(){
		$aPortalDispatcherData = [
			'itop-portal',
			'customer-portal',
			'backoffice'
		];

		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME, ['Portal power user' => 'Dummy Profile']);

		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init($aPortalDispatcherData);

		$this->assertFalse($oUserProfilesEventListener->IsRepairmentEnabled());
	}

	public function testInit_ConfWithOneWarningProfile() {
		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME,
			['Ticket Manager' => 'Administrator', 'Portal power user' => null]
		);
		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init();
		$this->assertTrue($oUserProfilesEventListener->IsRepairmentEnabled());
	}

	public function testInit_ConfWithFurtherWarningProfiles() {
		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME,
			['Ticket Manager' => null, 'Portal power user' => null]
		);
		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init();
		$this->assertTrue($oUserProfilesEventListener->IsRepairmentEnabled());
	}

	public function testInit_ConfWithFurtherWarningProfilesAndOneRepairment() {
		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME,
			['Portal power user' => null, 'Ticket Manager' => null, 'Administrator' => "Configuration Manager"]
		);
		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init();
		$this->assertTrue($oUserProfilesEventListener->IsRepairmentEnabled());
	}

	public function testRepairProfiles_WithAnotherFallbackProfile()
	{
		$oUser = new \UserLocal();
		$sLogin = 'testUserLocalCreationWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'ABCD1234@gabuzomeu');
		$oUser->Set('language', 'EN US');

		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME,
			['Portal power user' => 'Ticket Manager']
		);
		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init();
		$this->assertTrue($oUserProfilesEventListener->IsRepairmentEnabled());

		$this->CreateUserForProfileTesting($oUser, ['Portal power user'], false);
		$oUserProfilesEventListener->ValidateThenRepairOrWarn($oUser);

		$oUserProfileList = $oUser->Get('profile_list');
		$aProfilesAfterCreation=[];
		while (($oProfile = $oUserProfileList->Fetch()) != null){
			$aProfilesAfterCreation[] = $oProfile->Get('profile');
		}

		$this->assertContains('Ticket Manager', $aProfilesAfterCreation, var_export($aProfilesAfterCreation, true));
		$this->assertContains('Portal power user', $aProfilesAfterCreation, var_export($aProfilesAfterCreation, true));
	}

	public function testRepairProfiles_MultiRepairmentConf()
	{
		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME,
			[
				'Administrator' => 'Configuration Manager',
				'Portal power user' => 'Ticket Manager'
			]
		);
		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->Init();
		$this->assertTrue($oUserProfilesEventListener->IsRepairmentEnabled());

		$oUser = new \UserLocal();
		$sLogin = 'testUserLocalCreationWithPortalPowerUserProfile-'.uniqid();
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'ABCD1234@gabuzomeu');
		$oUser->Set('language', 'EN US');
		$this->CreateUserForProfileTesting($oUser, ['Portal power user'], false);
		$oUserProfilesEventListener->ValidateThenRepairOrWarn($oUser);

		$oUserProfileList = $oUser->Get('profile_list');
		$aProfilesAfterCreation=[];
		while (($oProfile = $oUserProfileList->Fetch()) != null){
			$aProfilesAfterCreation[] = $oProfile->Get('profile');
		}

		$this->assertContains('Ticket Manager', $aProfilesAfterCreation, var_export($aProfilesAfterCreation, true));
		$this->assertContains('Portal power user', $aProfilesAfterCreation, var_export($aProfilesAfterCreation, true));

		$oUser2 = new \UserLocal();
		$sLogin = 'testUserLocalCreationWithPortalPowerUserProfile-'.uniqid();
		$oUser2->Set('login', $sLogin);
		$oUser2->Set('password', 'ABCD1234@gabuzomeu');
		$oUser2->Set('language', 'EN US');

		$this->CreateUserForProfileTesting($oUser2, ['Administrator'], false);
		$oUserProfilesEventListener->ValidateThenRepairOrWarn($oUser2);

		$oUserProfileList = $oUser2->Get('profile_list');
		$aProfilesAfterCreation=[];
		while (($oProfile = $oUserProfileList->Fetch()) != null){
			$aProfilesAfterCreation[] = $oProfile->Get('profile');
		}

		$this->assertContains('Administrator', $aProfilesAfterCreation, var_export($aProfilesAfterCreation, true));
		$this->assertContains('Configuration Manager', $aProfilesAfterCreation, var_export($aProfilesAfterCreation, true));
	}

	public function testUserCreationWithWarningMessageConf()
	{
		$_SESSION = [];
		$oAdminUser = new \UserLocal();
		$sLogin = 'testUserCreationWithWarningMessageConf-Admin'.uniqid();
		$oAdminUser->Set('login', $sLogin);
		$oAdminUser->Set('password', 'ABCD1234@gabuzomeu');
		$oAdminUser->Set('language', 'EN US');
		$aAssociatedProfilesBeforeUserCreation = ['Administrator'];
		$this->commonUserCreationTest($oAdminUser, $aAssociatedProfilesBeforeUserCreation, $aAssociatedProfilesBeforeUserCreation, false);
		UserRights::Login($oAdminUser->Get('login'));

		$aAssociatedProfilesBeforeUserCreation = [
			'Portal power user'
		];

		$oUser = new \UserLocal();
		$sLogin = 'testUserCreationWithWarningMessageConf-'.uniqid();
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'ABCD1234@gabuzomeu');
		$oUser->Set('language', 'EN US');

		\MetaModel::GetConfig()->Set(UserProfilesEventListener::USERPROFILE_REPAIR_ITOP_PARAM_NAME,
			['Portal power user' => null ]
		);

		$this->SetNonPublicStaticProperty(EventService::class, "aEventListeners", []);
		$oUserProfilesEventListener = new UserProfilesEventListener();
		$oUserProfilesEventListener->RegisterEventsAndListeners();

		$this->expectException(\CoreCannotSaveObjectException::class);

		$this->commonUserCreationTest($oUser, $aAssociatedProfilesBeforeUserCreation, $aAssociatedProfilesBeforeUserCreation, false);

		/*$aObjMessages = Session::Get('obj_messages');
		$this->assertNotEmpty($aObjMessages);
		$sKey = sprintf("%s::%s", get_class($oUser), $oUser->GetKey());
		$this->assertTrue(array_key_exists($sKey, $aObjMessages));

		$aExpectedMessages = [
			[
				'rank' => 1,
				'severity' => 'WARNING',
				'message' => \Dict::Format("Class:User/NonStandaloneProfileWarning", 'Portal power user')
			]
		];
		$this->assertEquals($aExpectedMessages, array_values($aObjMessages[$sKey]), var_export($aObjMessages[$sKey], true));
*/
		$_SESSION = [];
	}
}
