<?php

namespace Users;

use CMDBObjectSet;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Combodo\iTop\Users\ITopUserQuotaRepository;
use DBObjectSearch;
use MetaModel;
use User;

class ITopUserQuotaRepositoryTest extends ItopDataTestCase{

	private static bool $bDatasetInitialized = false;

	protected function setUp(): void
	{
		parent::setUp();

		if (self::$bDatasetInitialized) {
			return;
		}

		$this->createUsersQuotaDataset();
		self::$bDatasetInitialized = true;
	}


	/**	 * Creates a deterministic dataset for quota tests.	 * Users are created only once (idempotent on login).	 */
	private function createUsersQuotaDataset(): void
	{
		// Keep names unique and easy to clean up later if needed.
		$sPrefix = 'quota_test_';

		// Create one user per quota "kind".
		// NOTE: profile names can vary by iTop distribution; we try common ones.
		$this->createUserIfMissing($sPrefix.'console', true, ['Administrator', 'Configuration Administrator']);
		$this->createUserIfMissing($sPrefix.'portal', true, ['Portal user', 'Portal User']);
		$this->createUserIfMissing($sPrefix.'readonly', true, ['ReadOnlyCI']);
		$this->createUserIfMissing($sPrefix.'application', true, ['Service Desk Agent', 'Change Manager', 'Administrator']);
		$this->createUserIfMissing($sPrefix.'disabled', false, ['Service Desk Agent', 'Administrator']);
		$this->createUserIfMissing($sPrefix.'disabled', false, ['Service Desk Agent', 'Administrator']);



	}

	private function createUserIfMissing(string $sLogin, bool $bEnabled, array $aCandidateProfileNames): void
	{
		if ($this->findUserByLogin($sLogin) !== null) {
			return;
		}

		$iProfileId = $this->findFirstProfileIdByNames($aCandidateProfileNames);
		$this->assertNotNull(
			$iProfileId,
			sprintf('Could not find any profile among: %s', implode(', ', $aCandidateProfileNames))
		);

		$oOrg = MetaModel::NewObject('Organization');
		$oOrg->Set('name', 'Quota Test Org');
		$oOrg->DBInsert();

		$oPerson = MetaModel::NewObject('Person');
		$oPerson->Set('name', strtoupper($sLogin));
		$oPerson->Set('first_name', 'Quota');
		$oPerson->Set('org_id', $oOrg->GetKey());
		$oPerson->Set('email', $sLogin.'@example.invalid');
		$oPerson->DBInsert();

		$oUser = MetaModel::NewObject('UserLocal');
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'QuotaTest#123');
		$oUser->Set('contactid', $oPerson->GetKey());
		$oUser->Set('status', $bEnabled ? 'enabled' : 'disabled');

		$oProfileList = $oUser->Get('profile_list');
		$oLink = MetaModel::NewObject('URP_UserProfile');
		$oLink->Set('profileid', $iProfileId);
		$oProfileList->AddItem($oLink);
		$oUser->Set('profile_list', $oProfileList);

		$oUser->DBInsert();
	}

	private function findFirstProfileIdByNames(array $aProfileNames): ?int
	{
		foreach ($aProfileNames as $sProfileName) {
			$oSearch = DBObjectSearch::FromOQL('SELECT URP_Profiles WHERE name = :name');
			$oSet = new CMDBObjectSet($oSearch, [], ['name' => $sProfileName]);
			$oProfile = $oSet->Fetch();
			if ($oProfile !== false && $oProfile !== null) {
				return (int) $oProfile->GetKey();
			}
		}
		return null;
	}

	private function findUserByLogin(string $sLogin): ?User
	{
		$oSearch = DBObjectSearch::FromOQL('SELECT User WHERE login = :login');
		$oSet = new CMDBObjectSet($oSearch, [], ['login' => $sLogin]);
		$oUser = $oSet->Fetch();
		return ($oUser instanceof User) ? $oUser : null;
	}


	public function testNotDuplicateInDifferentQuotas(): void
	{
		$oITopUserRepository = new ITopUserQuotaRepository();

		$aQuotaUsers = [
			'console' => $oITopUserRepository->GetUsersFromFilter($oITopUserRepository->GetConsoleUsers()),
			'portal' => $oITopUserRepository->GetUsersFromFilter($oITopUserRepository->GetPortalUsers()),
			'disabled' => $oITopUserRepository->GetUsersFromFilter($oITopUserRepository->GetDisabledUsers()),
			'readonly' => $oITopUserRepository->GetReadOnlyUsers(),
			'application' => $oITopUserRepository->GetUsersFromFilter($oITopUserRepository->GetApplicationUsers()),
		];

		$aUserToQuotas = [];
		foreach ($aQuotaUsers as $sQuota => $aUsers) {
			foreach ($aUsers as $oUser) {
				$sUserId = (string) $oUser->GetKey();
				$aUserToQuotas[$sUserId][$sQuota] = true;
			}
		}

		$aDuplicates = [];
		foreach ($aUserToQuotas as $sUserId => $aQuotas) {
			$aQuotaNames = array_keys($aQuotas);
			if (count($aQuotaNames) > 1) {
				sort($aQuotaNames);
				$aDuplicates[] = sprintf('User #%s appears in: %s', $sUserId, implode(', ', $aQuotaNames));
			}
		}

		$this->assertEmpty(
			$aDuplicates,
			"Some users are counted in multiple quotas:\n- ".implode("\n- ", $aDuplicates)
		);
	}

	public function testAllUsersAreInQuota () {
		$oITopUserRepository = new ITopUserQuotaRepository();

		$oConsoleUsersFilter = $oITopUserRepository->GetConsoleUsers();
		$aConsoleUsers = $oITopUserRepository->GetUsersFromFilter($oConsoleUsersFilter);
		$oPortalUsersFilter = $oITopUserRepository->GetPortalUsers();
		$aPortalUsers = $oITopUserRepository->GetUsersFromFilter($oPortalUsersFilter);
		$oDisabledUsersFilter = $oITopUserRepository->GetDisabledUsers();
		$aDisabledUsers = $oITopUserRepository->GetUsersFromFilter($oDisabledUsersFilter);
		$aReadOnlyUsers = $oITopUserRepository->GetReadOnlyUsers();
		$oApplicationUsersFilter = $oITopUserRepository->GetApplicationUsers();
		$aApplicationUsers = $oITopUserRepository->GetUsersFromFilter($oApplicationUsersFilter);

		$aAllUsersFromQuota = array_merge($aConsoleUsers, $aPortalUsers, $aDisabledUsers, $aReadOnlyUsers, $aApplicationUsers);

		$oAllUsersFilter = $oITopUserRepository->GetAllUsers();
		$aAllUsersFromOQL = $oITopUserRepository->GetUsersFromFilter($oAllUsersFilter);

		$this->assertEmpty(array_merge(array_diff($aAllUsersFromQuota, $aAllUsersFromOQL), array_diff($aAllUsersFromOQL, $aAllUsersFromQuota)));
	}

	public function testAllUsersInQuotaAreUsersObjects ()
	{
		$oITopUserRepository = new ITopUserQuotaRepository();

		$oConsoleUsersFilter = $oITopUserRepository->GetConsoleUsers();
		$aConsoleUsers = $oITopUserRepository->GetUsersFromFilter($oConsoleUsersFilter);
		$oPortalUsersFilter = $oITopUserRepository->GetPortalUsers();
		$aPortalUsers = $oITopUserRepository->GetUsersFromFilter($oPortalUsersFilter);
		$oDisabledUsersFilter = $oITopUserRepository->GetDisabledUsers();
		$aDisabledUsers = $oITopUserRepository->GetUsersFromFilter($oDisabledUsersFilter);
		$aReadOnlyUsers = $oITopUserRepository->GetReadOnlyUsers();
		$oApplicationUsersFilter = $oITopUserRepository->GetApplicationUsers();
		$aApplicationUsers = $oITopUserRepository->GetUsersFromFilter($oApplicationUsersFilter);

		$aAllQuotaUsers = array_merge($aConsoleUsers, $aPortalUsers, $aDisabledUsers, $aReadOnlyUsers, $aApplicationUsers);

		foreach ($aAllQuotaUsers as $oUser) {
			$this->assertInstanceOf(User::class, $oUser);
		}
		

	}

}