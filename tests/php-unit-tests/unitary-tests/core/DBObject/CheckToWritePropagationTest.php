<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Created by PhpStorm.
 * Date: 25/01/2018
 * Time: 11:12
 */

namespace Combodo\iTop\Test\UnitTest\Core\DBObject;

use Combodo\iTop\Application\UI\Base\Layout\NavigationMenu\NavigationMenuFactory;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreCannotSaveObjectException;
use DBObjectSet;
use DBSearch;
use DeleteException;
use MetaModel;
use URP_UserProfile;
use User;
use UserExternal;
use UserLocal;
use UserRights;

/**
 * @group itopRequestMgmt
 * @group userRights
 * @group defaultProfiles
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class CheckToWritePropagationTest extends ItopDataTestCase
{
	public function PortaPowerUserProvider()
	{
		return [
			'No profile' => [
				'aProfilesBeforeUserCreation'        => [
				],
				'bWaitForException'                         => 'CoreCannotSaveObjectException',
			],
			'Portal power user'                 => [
				'aProfilesBeforeUserCreation'        => [
					'Portal power user',
				],
				'bWaitForException'                         => 'CoreCannotSaveObjectException',
			],
			'Portal power user + Configuration Manager'                 => [
				'aProfilesBeforeUserCreation'        => [
					'Portal power user',
					'Configuration Manager',
				],
				'bWaitForException'                         => false,
			],
			'Portal power user + Configuration Manager + Admin'                 => [
				'aProfilesBeforeUserCreation'        => [
					'Portal power user',
					'Configuration Manager',
					'Administrator',
				],
				'bWaitForException'                         => false,
			],
		];
	}

	/**
	 * @dataProvider PortaPowerUserProvider
	 * @covers User::CheckPortalProfiles
	 */
	public function testUserLocalCreation($aProfilesBeforeUserCreation, $sWaitForException)
	{
		$oUser = new UserLocal();
		$sLogin = 'testUserLocalCreationWithPortalPowerUserProfile-'.uniqid('', true);
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'ABCD1234@gabuzomeu');
		$oUser->Set('language', 'EN US');
		if (false !== $sWaitForException) {
			$this->expectException($sWaitForException);
		}
		$this->commonUserCreationTest($oUser, $aProfilesBeforeUserCreation);
	}

	/**
	 * @dataProvider PortaPowerUserProvider
	 * @covers User::CheckPortalProfiles
	 */
	public function testUserLocalDelete($aProfilesBeforeUserCreation, $sWaitForException)
	{
		$oUser = new UserLocal();
		$sLogin = 'testUserLocalCreationWithPortalPowerUserProfile-'.uniqid('', true);
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'ABCD1234@gabuzomeu');
		$oUser->Set('language', 'EN US');
		if (false !== $sWaitForException) {
			$this->expectException($sWaitForException);
		}
		$this->commonUserCreationTest($oUser, $aProfilesBeforeUserCreation, false);

		$oUser->DBDelete();
	}

	/**
	 * @dataProvider PortaPowerUserProvider
	 * @covers User::CheckPortalProfiles
	 */
	public function testUserLocalUpdate($aProfilesBeforeUserCreation, $sWaitForException)
	{
		$oUser = new UserLocal();
		$sLogin = 'testUserLocalUpdateWithPortalPowerUserProfile-'.uniqid('', true);
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'ABCD1234@gabuzomeu');
		$oUser->Set('language', 'EN US');
		if (false !== $sWaitForException) {
			$this->expectException($sWaitForException);
		}
		$this->commonUserUpdateTest($oUser, $aProfilesBeforeUserCreation);
	}

	private function commonUserCreationTest($oUserToCreate, $aProfilesBeforeUserCreation, $bTestUserItopAccess = true)
	{
		$sUserClass = get_class($oUserToCreate);
		list ($sId, $aProfiles) = $this->CreateUserForProfileTesting($oUserToCreate, $aProfilesBeforeUserCreation);

		$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aProfilesBeforeUserCreation, $bTestUserItopAccess);
	}

	private function commonUserUpdateTest($oUserToCreate, $aProfilesBeforeUserCreation)
	{
		$sUserClass = get_class($oUserToCreate);
		list ($sId, $aProfiles) = $this->CreateUserForProfileTesting($oUserToCreate, ['Administrator']);

		$oUserToUpdate = MetaModel::GetObject($sUserClass, $sId);
		$oProfileList = $oUserToUpdate->Get('profile_list');
		while ($oObj = $oProfileList->Fetch()) {
			$oProfileList->RemoveItem($oObj->GetKey());
		}

		foreach ($aProfilesBeforeUserCreation as $sProfileName) {
			$oAdminUrpProfile = new URP_UserProfile();
			$oProfile = $aProfiles[$sProfileName];
			$oAdminUrpProfile->Set('profileid', $oProfile->GetKey());
			$oAdminUrpProfile->Set('reason', 'UNIT Tests');
			$oProfileList->AddItem($oAdminUrpProfile);
		}

		$oUserToUpdate->Set('profile_list', $oProfileList);
		$oUserToUpdate->DBWrite();

		$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aProfilesBeforeUserCreation);
	}

	private function CreateUserForProfileTesting(User $oUserToCreate, array $aProfilesBeforeUserCreation, $bDbInsert = true): array
	{
		$aProfiles = [];
		$oSearch = DBSearch::FromOQL('SELECT URP_Profiles');
		$oProfileSet = new DBObjectSet($oSearch);
		while (($oProfile = $oProfileSet->Fetch()) != null) {
			$aProfiles[$oProfile->Get('name')] = $oProfile;
		}

		$this->CreateTestOrganization();
		$oContact = $this->CreatePerson('1');
		$iContactId = $oContact->GetKey();

		$oUserToCreate->Set('contactid', $iContactId);

		$oUserProfileList = $oUserToCreate->Get('profile_list');
		foreach ($aProfilesBeforeUserCreation as $sProfileName) {
			$oUserProfile = new URP_UserProfile();
			$oProfile = $aProfiles[$sProfileName];
			$oUserProfile->Set('profileid', $oProfile->GetKey());
			$oUserProfile->Set('reason', 'UNIT Tests');
			$oUserProfileList->AddItem($oUserProfile);
		}

		$oUserToCreate->Set('profile_list', $oUserProfileList);
		if ($bDbInsert) {
			$sId = $oUserToCreate->DBInsert();
		} else {
			$sId = -1;
		}

		return [$sId, $aProfiles];
	}

	private function CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aExpectedAssociatedProfilesAfterUserCreation, $bTestItopConnection = true)
	{
		$oUser = MetaModel::GetObject($sUserClass, $sId);
		$oUserProfileList = $oUser->Get('profile_list');
		$aProfilesAfterCreation = [];
		while (($oProfile = $oUserProfileList->Fetch()) != null) {
			$aProfilesAfterCreation[] = $oProfile->Get('profile');
		}

		foreach ($aExpectedAssociatedProfilesAfterUserCreation as $sExpectedProfileName) {
			$this->assertTrue(in_array($sExpectedProfileName, $aProfilesAfterCreation),
				"profile \'$sExpectedProfileName\' should be asociated to user after creation. ".var_export($aProfilesAfterCreation, true));
		}

		if (!$bTestItopConnection) {
			return;
		}

		$_SESSION = [];

		UserRights::Login($oUser->Get('login'));

		if (!UserRights::IsPortalUser()) {
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

	/**
	 * @dataProvider ProfilesLinksProvider
	 */
	public function testProfilesLinksDBDelete(string $sProfileNameToRemove, $bRaiseException = false)
	{
		$aInitialProfiles = [$sProfileNameToRemove, 'Portal power user'];

		$oUser = new UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid('', true);
		$oUser->Set('login', $sLogin);

		[$sId, $aProfiles] = $this->CreateUserForProfileTesting($oUser, $aInitialProfiles);

		if ($bRaiseException) {
			$this->expectException(DeleteException::class);
		}

		$aURPUserProfileByUser = $this->GetURPUserProfileByUser($sId);
		if (array_key_exists($sProfileNameToRemove, $aURPUserProfileByUser)) {
			$oURPUserProfile = $aURPUserProfileByUser[$sProfileNameToRemove];
			$oURPUserProfile->DBDelete();
		}
	}

	/**
	 * @dataProvider ProfilesLinksProvider
	 */
	public function testProfilesLinksEdit_ChangeProfileId(string $sInitialProfile, $bRaiseException = false)
	{
		$oUser = new UserExternal();
		$sLogin = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid('', true);
		$oUser->Set('login', $sLogin);

		$sUserClass = get_class($oUser);
		list ($sId, $aProfiles) = $this->CreateUserForProfileTesting($oUser, [$sInitialProfile]);

		$oURP_Profile = MetaModel::GetObjectByColumn('URP_Profiles', 'name', 'Portal power user');

		$aURPUserProfileByUser = $this->GetURPUserProfileByUser($sId);

		if ($bRaiseException) {
			$this->expectException(CoreCannotSaveObjectException::class);
		}

		if (array_key_exists($sInitialProfile, $aURPUserProfileByUser)) {
			$oURPUserProfile = $aURPUserProfileByUser[$sInitialProfile];
			$oURPUserProfile->Set('profileid', $oURP_Profile->GetKey());
			$oURPUserProfile->DBWrite();
		}

		if (!$bRaiseException) {
			$aExpectedProfilesAfterUpdate = ['Portal power user', 'Portal user'];
			$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aExpectedProfilesAfterUpdate);
		}
	}

	public function ProfilesLinksProvider()
	{
		return [
			'Administrator' => ['sProfileNameToMove' => 'Administrator', 'bRaiseException' => true],
			'Portal user'   => ['sProfileNameToMove' => 'Portal user', 'bRaiseException' => true],
		];
	}

	/**
	 * @dataProvider ProfilesLinksProvider
	 */
	public function testProfilesLinksEdit_ChangeUserId($sProfileNameToMove, $bRaiseException = false)
	{
		$aInitialProfiles = [$sProfileNameToMove, 'Portal power user'];

		$oUser = new UserExternal();
		$sLogin1 = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid('', true);
		$oUser->Set('login', $sLogin1);

		$sUserClass = get_class($oUser);
		list ($sId, $aProfiles) = $this->CreateUserForProfileTesting($oUser, $aInitialProfiles);

		$oUser = new UserExternal();
		$sLogin2 = 'testUserLDAPUpdateWithPortalPowerUserProfile-'.uniqid('', true);
		$oUser->Set('login', $sLogin2);
		list ($sAnotherUserId, $aProfiles) = $this->CreateUserForProfileTesting($oUser, ['Configuration Manager']);

		if ($bRaiseException) {
			$this->expectException(CoreCannotSaveObjectException::class);
		}

		$aURPUserProfileByUser = $this->GetURPUserProfileByUser($sId);
		if (array_key_exists($sProfileNameToMove, $aURPUserProfileByUser)) {
			$oURPUserProfile = $aURPUserProfileByUser[$sProfileNameToMove];
			$oURPUserProfile->Set('userid', $sAnotherUserId);
			$oURPUserProfile->DBWrite();
		}

		if (!$bRaiseException) {
			$aExpectedProfilesAfterUpdate = [$sProfileNameToMove, 'Configuration Manager'];
			$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sAnotherUserId, $aExpectedProfilesAfterUpdate);

			$aExpectedProfilesAfterUpdate = ['Portal power user', 'Portal user'];
			$this->CheckProfilesAreOkAndThenConnectToITop($sUserClass, $sId, $aExpectedProfilesAfterUpdate);
		}
	}

	private function GetURPUserProfileByUser($iUserId): array
	{
		$aRes = [];
		$oSearch = DBSearch::FromOQL("SELECT URP_UserProfile WHERE userid=$iUserId");
		$oSet = new DBObjectSet($oSearch);
		while (($oURPUserProfile = $oSet->Fetch()) != null) {
			$aRes[$oURPUserProfile->Get('profile')] = $oURPUserProfile;
		}

		return $aRes;
	}

	public function CustomizedPortalsProvider()
	{
		return [
			'console + customized portal'               => [
				'aPortalDispatcherData' => [
					'customer-portal',
					'backoffice',
				],
			],
			'console + itop portal + customized portal' => [
				'aPortalDispatcherData' => [
					'itop-portal',
					'customer-portal',
					'backoffice',
				],
			],
		];
	}
}
