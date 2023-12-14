<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\DBObject;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use URP_UserProfile;
use User;
use UserLocal;

class CustomCheckToWriteTest extends ItopDataTestCase
{
	public function PortaPowerUserProvider()
	{
		return [
			'No profile'                                        => [
				'aProfiles'            => [],
				'bExpectedCheckStatus' => false,
			],
			'Portal power user'                                 => [
				'aProfiles'            => ['Portal power user',],
				'bExpectedCheckStatus' => true,
			],
			'Portal power user + Configuration Manager'         => [
				'aProfiles'            => ['Portal power user', 'Configuration Manager',],
				'bExpectedCheckStatus' => true,
			],
			'Portal power user + Configuration Manager + Admin' => [
				'aProfiles'            => ['Portal power user', 'Configuration Manager', 'Administrator',],
				'bExpectedCheckStatus' => true,
			],
		];
	}

	/**
	 * @dataProvider PortaPowerUserProvider
	 * @covers       User::CheckPortalProfiles
	 */
	public function testUserLocalCheckPortalProfiles($aProfiles, $bExpectedCheckStatus)
	{
		$oUser = new UserLocal();
		$sLogin = 'testUserLocalCreationWithPortalPowerUserProfile-'.uniqid('', true);
		$oUser->Set('login', $sLogin);
		$oUser->Set('password', 'ABCD1234@gabuzomeu');
		$oUser->Set('language', 'EN US');
		$oProfileList = $oUser->Get('profile_list');

		foreach ($aProfiles as $sProfileName) {
			$oAdminUrpProfile = new URP_UserProfile();
			$oAdminUrpProfile->Set('profileid', self::$aURP_Profiles[$sProfileName]);
			$oAdminUrpProfile->Set('reason', 'UNIT Tests');
			$oProfileList->AddItem($oAdminUrpProfile);
		}

		$oUser->Set('profile_list', $oProfileList);

		[$bCheckStatus, $aCheckIssues, $bSecurityIssue] = $oUser->CheckToWrite();
		$this->assertEquals($bExpectedCheckStatus, $bCheckStatus);
	}

}
