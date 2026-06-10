<?php

namespace Users;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Combodo\iTop\Users\ITopUserCountingRepository;
use MetaModel;
use User;

class ITopUserCountingRepositoryTest extends ItopDataTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->CreateReadOnlyUsers();
		$this->CreateDisabledUsers();
		$this->CreatePortalUsers();
		if (MetaModel::IsValidClass('UserToken')) {
			$this->CreateTokenUsers();
		}
		$this->CreateConsoleUsers();
		$this->CreateBusinessPartnerUser();

	}

	private function CreateReadOnlyUsers()
	{
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Ticket ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Service Catalog ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration ReadOnly', 'Portal user']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Ticket ReadOnly', 'Portal user']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Service Catalog ReadOnly', 'Portal user']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration ReadOnly', 'Ticket ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Ticket ReadOnly', 'Service Catalog ReadOnly']);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration ReadOnly', 'Ticket ReadOnly', 'Service Catalog ReadOnly']);
	}

	private function CreateDisabledUsers()
	{
		$iDisabledUser = $this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration Manager'], bReturnLogin: false);
		$oUser = \MetaModel::GetObject('User', $iDisabledUser);
		$oUser->Set('status', 'disabled');
		$oUser->DBUpdate();

		$iDisabledReadOnlyUser = $this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Ticket ReadOnly'], bReturnLogin: false);
		$oUser = \MetaModel::GetObject('User', $iDisabledReadOnlyUser);
		$oUser->Set('status', 'disabled');
		$oUser->DBUpdate();
	}

	private function CreatePortalUsers()
	{
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Portal user'], false);

		$iDisabledPortalUser = $this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Portal user'], bReturnLogin: false);
		$oUser = \MetaModel::GetObject('User', $iDisabledPortalUser);
		$oUser->Set('status', 'disabled');
		$oUser->DBUpdate();
	}

	private function CreateTokenUsers()
	{
		$this->GivenTokenUserInDB(['Configuration Manager'], false);
		$this->GivenTokenUserInDB(['Portal user'], false);
		$this->GivenTokenUserInDB(['Configuration ReadOnly'], false);
	}

	private function CreateConsoleUsers()
	{
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Configuration Manager'], bReturnLogin: false);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Administrator'], bReturnLogin: false);
	}

	private function CreateBusinessPartnerUser()
	{
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Business partner user'], bReturnLogin: false);
		$this->GivenUserInDB('qpf_z17H3232*"ré$"é', ['Business partner user'], bReturnLogin: false);
	}

	/**
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public function testNotDuplicateInDifferentCountsCategories(): void
	{
		$oITopUserRepository = new ITopUserCountingRepository();

		$aCountedUsers = [
			'console' => $oITopUserRepository->GetConsoleUsers(),
			'portal' => $oITopUserRepository->GetPortalUsers(),
			'disabled' => $oITopUserRepository->GetDisabledUsers(),
			'readonly' => $oITopUserRepository->GetReadOnlyUsers(),
			'application' => $oITopUserRepository->GetApplicationUsers(),
			'businesspartner' => $oITopUserRepository->GetBusinessPartnerUsers(),
		];

		$aCountedUserFormated = [];
		foreach ($aCountedUsers as $sCountedCategory => $aUsers) {
			foreach ($aUsers as $oUser) {
				$sUserId = (string) $oUser->GetKey();
				$aCountedUserFormated[$sUserId][$sCountedCategory] = true;
			}
		}

		$aDuplicates = [];
		foreach ($aCountedUserFormated as $sUserId => $aCountedCategory) {
			$aCountedCategoryName = array_keys($aCountedCategory);
			if (count($aCountedCategoryName) > 1) {
				sort($aCountedCategoryName);
				$aDuplicates[] = sprintf('User #%s appears in: %s', $sUserId, implode(', ', $aCountedCategoryName));
			}
		}

		$this->assertEmpty(
			$aDuplicates,
			"Some users are counted in multiple categories:\n- ".implode("\n- ", $aDuplicates)
		);
	}

	public function testAllUsersAreCounted()
	{
		$oITopUserRepository = new ITopUserCountingRepository();

		$aConsoleUsers = $oITopUserRepository->GetConsoleUsers();
		$aPortalUsers = $oITopUserRepository->GetPortalUsers();
		$aDisabledUsers = $oITopUserRepository->GetDisabledUsers();
		$aReadOnlyUsers = $oITopUserRepository->GetReadOnlyUsers();
		$aApplicationUsers = $oITopUserRepository->GetApplicationUsers();
		$aBusinessPartnerUsers = $oITopUserRepository->GetBusinessPartnerUsers();

		$aAllUsersFromMergedCounts = array_merge($aConsoleUsers, $aPortalUsers, $aDisabledUsers, $aReadOnlyUsers, $aApplicationUsers, $aBusinessPartnerUsers);

		$aAllUsersFromOQL = $oITopUserRepository->GetAllUsers();

		$this->assertEmpty( // asserts that all users are in both arrays
			array_merge(
				array_diff(
					$aAllUsersFromMergedCounts,
					$aAllUsersFromOQL
				),
				array_diff(
					$aAllUsersFromOQL,
					$aAllUsersFromMergedCounts
				)
			)
		);

		// check for cardinality
		$aMergedCountingUsersIds = [];
		foreach ($aAllUsersFromMergedCounts as $oUser) {
			$aMergedCountingUsersIds[] = (int) $oUser->GetKey();
		}

		$aOqlUsersIds = [];
		foreach ($aAllUsersFromOQL as $oUser) {
			$aOqlUsersIds[] = (int) $oUser->GetKey();
		}

		sort($aMergedCountingUsersIds, SORT_NUMERIC);
		sort($aOqlUsersIds, SORT_NUMERIC);

		$this->assertSame($aOqlUsersIds, $aMergedCountingUsersIds);
	}

	public function testAllCountedUsersAreUsersObjects()
	{
		$oITopUserRepository = new ITopUserCountingRepository();

		$aConsoleUsers = $oITopUserRepository->GetConsoleUsers();
		$aPortalUsers = $oITopUserRepository->GetPortalUsers();
		$aDisabledUsers = $oITopUserRepository->GetDisabledUsers();
		$aReadOnlyUsers = $oITopUserRepository->GetReadOnlyUsers();
		$aApplicationUsers = $oITopUserRepository->GetApplicationUsers();
		$aBusinessPartnerUsers = $oITopUserRepository->GetBusinessPartnerUsers();

		$aCountedUsers = array_merge($aConsoleUsers, $aPortalUsers, $aDisabledUsers, $aReadOnlyUsers, $aApplicationUsers, $aBusinessPartnerUsers);

		foreach ($aCountedUsers as $oUser) {
			$this->assertInstanceOf(User::class, $oUser);
		}
	}

	/**
	 * @param array $aProfilesList
	 * @param $sExpectedCategorie
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @dataProvider RealUseCasesDataProvider
	 */
	public function testRealUseCases(array $aProfilesList, $sExpectedCategorie)
	{
		$iUser = $this->GivenUserInDB('<_ç"éue"ç_u', $aProfilesList, bReturnLogin: false);
		$oUser = \MetaModel::GetObject('User', $iUser);
		$oITopUserRepository = new ITopUserCountingRepository();
		$aUsers = match ($sExpectedCategorie) {
			'console' => $oITopUserRepository->GetConsoleUsers(),
			'readonly' => $oITopUserRepository->GetReadOnlyUsers(),
			'disabled' => $oITopUserRepository->GetDisabledUsers(),
			'portal' => $oITopUserRepository->GetPortalUsers(),
			'application' => $oITopUserRepository->GetApplicationUsers(),
			'businesspartner' => $oITopUserRepository->GetBusinessPartnerUsers(),
			default => throw new \InvalidArgumentException(sprintf('Unexpected category %s', $sExpectedCategorie)),
		};

		$bUserInCorrectCategory = false;
		foreach ($aUsers as $oUserInCategory) {
			if ($oUserInCategory->GetKey() === $oUser->GetKey()) {
				$bUserInCorrectCategory = true;
			}
		}
		$this->assertTrue($bUserInCorrectCategory, 'User with profiles '.implode(', ', $aProfilesList).' should be counted as '.$sExpectedCategorie.' user');
	}

	public function RealUseCasesDataProvider()
	{
		return [
			'User with profiles Support Agent and Configuration ReadOnly'  => [
				'profiles' => ['Support Agent', 'Configuration ReadOnly'],
				'expected category' => 'console',
			],
			 'User with profiles Configuration ReadOnly and Service Catalog ReadOnly' => [
				'profiles' => ['Configuration ReadOnly', 'Service Catalog ReadOnly'],
				'expected category' => 'readonly',
			],
			'User with profiles Portal user and Service Catalog ReadOnly' => [
				 'profiles' => ['Portal user', 'Service Catalog ReadOnly'],
			 'expected category' => 'portal',
			],
			'User with profiles Support Agent and Portal user' => [
				'profiles' => ['Support Agent', 'Portal user'],
				'expected category' => 'portal',
			],
			'User with profiles Configuration Manager and Ticket ReadOnly' => [
				'profiles' => ['Configuration Manager', 'Ticket ReadOnly'],
				'expected category' => 'console',
			],
		];
	}
}
