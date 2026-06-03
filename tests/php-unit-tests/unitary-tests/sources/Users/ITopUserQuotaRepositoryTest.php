<?php

namespace Users;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Combodo\iTop\Users\ITopUserQuotaRepository;
use User;

class ITopUserQuotaRepositoryTest extends ItopDataTestCase{
	protected function setUp(): void
	{
		parent::setUp();
		$this->CreateReadOnlyUsers();

	}

	private function CreateReadOnlyUsers()
	{
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Ticket ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Service Catalog ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration ReadOnly', 'Ticket ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Ticket ReadOnly', 'Service Catalog ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration ReadOnly', 'Ticket ReadOnly', 'Service Catalog ReadOnly']);
	}

	private function CreateDisabledUser() {
		$sUser = $this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration Manager']);
		// get user by login
		$oUser = \MetaModel::GetObjectByName('User', $sUser);
		$oUser->Set('status', 'disabled');
		$oUser->DBUpdate();
	}

	/**
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public function testNotDuplicateInDifferentQuotas(): void
	{
		$oITopUserRepository = new ITopUserQuotaRepository();

		$aQuotaUsers = [
			'console' => $oITopUserRepository->GetConsoleUsers(),
			'portal' => $oITopUserRepository->GetPortalUsers(),
			'disabled' => $oITopUserRepository->GetDisabledUsers(),
			'readonly' => $oITopUserRepository->GetReadOnlyUsers(),
			'application' => $oITopUserRepository->GetApplicationUsers(),
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

		$aConsoleUsers = $oITopUserRepository->GetConsoleUsers();
		$aPortalUsers = $oITopUserRepository->GetPortalUsers();
		$aDisabledUsers = $oITopUserRepository->GetDisabledUsers();
		$aReadOnlyUsers = $oITopUserRepository->GetReadOnlyUsers();
		$aApplicationUsers = $oITopUserRepository->GetApplicationUsers();

		$aAllUsersFromQuota = array_merge($aConsoleUsers, $aPortalUsers, $aDisabledUsers, $aReadOnlyUsers, $aApplicationUsers);

		$aAllUsersFromOQL = $oITopUserRepository->GetAllUsers();

		$this->assertEmpty(array_merge(array_diff($aAllUsersFromQuota, $aAllUsersFromOQL), array_diff($aAllUsersFromOQL, $aAllUsersFromQuota)));
	}

	public function testAllUsersInQuotaAreUsersObjects ()
	{
		$oITopUserRepository = new ITopUserQuotaRepository();

		$aConsoleUsers = $oITopUserRepository->GetConsoleUsers();
		$aPortalUsers = $oITopUserRepository->GetPortalUsers();
		$aDisabledUsers = $oITopUserRepository->GetDisabledUsers();
		$aReadOnlyUsers = $oITopUserRepository->GetReadOnlyUsers();
		$aApplicationUsers = $oITopUserRepository->GetApplicationUsers();

		$aAllQuotaUsers = array_merge($aConsoleUsers, $aPortalUsers, $aDisabledUsers, $aReadOnlyUsers, $aApplicationUsers);

		foreach ($aAllQuotaUsers as $oUser) {
			$this->assertInstanceOf(User::class, $oUser);
		}
		

	}

}