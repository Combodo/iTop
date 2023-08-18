<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 21/11/2019
 * Time: 09:14
 */

namespace Combodo\iTop\Test\UnitTest\Module\AuthentLocal;


use AttributeDate;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Config;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use MetaModel;
use ormLinkSet;
use URP_UserProfile;
use User;
use UserLocal;
use UserRights;
use utils;

/**
 * test class for UserLocal class
 */
class UserLocalTest extends ItopDataTestCase
{

	public function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceUnitTestFile('./UserLocalTest/UserLocalPasswordPolicyMock.php');
		$this->RequireOnceItopFile('env-production/authent-local/model.authent-local.php');
	}

	/**
	 * @dataProvider ProviderValidatePassword
	 */
	public function testValidatePassword($sPassword, $aValidatorNames, $aConfigValueMap, $bExpectedCheckStatus, $expectedCheckIssues = null, $sUserLanguage = null)
	{
		$configMock = $this->createMock(Config::class);
		$configMock
			->method('GetModuleSetting')
			->willReturnMap($aConfigValueMap);
		restore_error_handler();

		if (isset($sUserLanguage)) {
			Dict::SetUserLanguage($sUserLanguage);
		}

		/** @var UserLocal $oUserLocal */
		$oUserLocal = MetaModel::NewObject(UserLocal::class, array('login' => 'john'));
		/** @var ormLinkSet $oProfileSet */
		$oProfileSet = $oUserLocal->Get('profile_list');

		$oProfileSet->AddItem(
			MetaModel::NewObject(URP_UserProfile::class, array('profileid' => 1))
		);

		$aValidatorCollection = array();
		foreach ($aValidatorNames as $class)
		{
			$aValidatorCollection[] = new $class();
		}

		$oUserLocal->ValidatePassword($sPassword, $configMock, $aValidatorCollection);

		list($bCheckStatus, $aCheckIssues, $aSecurityIssues) = $oUserLocal->CheckToWrite();

		$this->assertSame($bExpectedCheckStatus, $bCheckStatus);

		if (isset($expectedCheckIssues)) {
			$this->assertContains($expectedCheckIssues, $aCheckIssues);
		}
	}

	public function ProviderValidatePassword()
	{
		return array(
			'validPattern'    => array(
				'password'             => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap'             => array(
					array('authent-local', 'password_validation.pattern', null, '.{1,10}'),
				),
				'expectedCheckStatus'  => true,
			),
			'notValidPattern' => array(
				'password'             => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap'             => array(
					array('authent-local', 'password_validation.pattern', null, '.{6,10}'),
				),
				'expectedCheckStatus'  => false,
			),
			'noPattern' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap' => array(
					array('authent-local', 'password_validation.pattern', null, '')
				),
				'expectedCheckStatus' => true,
			),
			'validClass' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserLocalPasswordPolicyMockValid',
				),
				'valueMap' => array(),
				'expectedCheckStatus' => true,
			),
			'notValidClass' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserLocalPasswordPolicyMockNotValid',
				),
				'valueMap' => array(),
				'expectedCheckStatus' => false,
			),

			'validation_composition_10' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserLocalPasswordPolicyMockValid',
					'UserLocalPasswordPolicyMockNotValid',
				),
				'valueMap' => array(),
				'expectedCheckStatus' => false,
				'expectedCheckIssues' => 'UserLocalPasswordPolicyMockNotValid',
			),


			'validation_composition_01' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserLocalPasswordPolicyMockNotValid',
					'UserLocalPasswordPolicyMockValid',
				),
				'valueMap' => array(),
				'expectedCheckStatus' => false,
				'expectedCheckIssues' => 'UserLocalPasswordPolicyMockNotValid',
			),

			'validation_composition_11' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserLocalPasswordPolicyMockValid',
					'UserLocalPasswordPolicyMockValidBis',
				),
				'valueMap' => array(),
				'expectedCheckStatus' => true,
			),
			'validation_composition_00' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserLocalPasswordPolicyMockNotValid',
					'UserLocalPasswordPolicyMockNotValidBis',
				),
				'valueMap' => array(),
				'expectedCheckStatus' => false,
				'expectedCheckIssues' => 'UserLocalPasswordPolicyMockNotValid',
			),

			'notValidPattern custom message FR' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap' => array(
					array('authent-local', 'password_validation.pattern', null, '.{6,10}'),
					array('authent-local', 'password_validation.message', null, array('FR FR' => 'fr message', 'EN US' => 'en message')),

				),
				'expectedCheckStatus' => false,
				'expectedCheckIssues' => 'fr message',
				'userLanguage' => 'FR FR',
			),
			'notValidPattern custom message EN' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap' => array(
					array('authent-local', 'password_validation.pattern', null, '.{6,10}'),
					array('authent-local', 'password_validation.message', null, array('FR FR' => 'fr message', 'EN US' => 'en message')),

				),
				'expectedCheckStatus' => false,
				'expectedCheckIssues' => 'en message',
				'userLanguage' => 'EN US',
			),
			'notValidPattern custom message Fallback' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap' => array(
					array('authent-local', 'password_validation.pattern', null, '.{6,10}'),
					array('authent-local', 'password_validation.message', null, array('EN US' => 'en message')),

				),
				'expectedCheckStatus' => false,
				'expectedCheckIssues' => 'en message',
				'userLanguage' => 'FR FR',
			),
			'notValidPattern custom message empty array' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap' => array(
					array('authent-local', 'password_validation.pattern', null, '.{6,10}'),
					array('authent-local', 'password_validation.message', null, array()),

				),
				'expectedCheckStatus' => false,
				'expectedCheckIssues' => 'Password must be at least 8 characters and include uppercase, lowercase, numeric and special characters.',
				'userLanguage' => 'EN US',
			),
			'notValidPattern custom message string not array' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap' => array(
					array('authent-local', 'password_validation.pattern', null, '.{6,10}'),
					array('authent-local', 'password_validation.message', null, 'not an array'),

				),
				'expectedCheckStatus' => false,
				'expectedCheckIssues' => 'not an array',
				'userLanguage' => 'EN US',
			),
		);
	}

	/**
	 * @dataProvider ProviderPasswordRenewal
	 *
	 */
	public function testPasswordRenewal($sBefore, $sExpectedAfter)
	{
		$sDateFormat = AttributeDate::GetInternalFormat();
		$oBefore = is_null($sBefore) ? null : date($sDateFormat, strtotime($sBefore));
		$oNow = date($sDateFormat);
		$oExpectedAfter = is_null($sExpectedAfter) ? null : date($sDateFormat, strtotime($sExpectedAfter));

		$aUserLocalValues = array('login' => 'john');
		if (!is_null($oBefore))
		{
			$aUserLocalValues['password_renewed_date'] = $oBefore;
		}

		/** @var UserLocal $oUserLocal */
		$oUserLocal = MetaModel::NewObject(UserLocal::class, $aUserLocalValues);
		/** @var ormLinkSet $oProfileSet */
		$oProfileSet = $oUserLocal->Get('profile_list');

		$oProfileSet->AddItem(
			MetaModel::NewObject(URP_UserProfile::class, array('profileid' => 1))
		);

		$this->assertEquals($oBefore, $oUserLocal->Get('password_renewed_date'));

		//INSERT
		$oUserLocal->Set('password', 'fooBar1???');
		$oUserLocal->DBWrite();
		$this->assertEquals($oNow, $oUserLocal->Get('password_renewed_date'), 'INSERT sets the "password_renewed_date" to the current date');

		//UPDATE password_renewed_date
		$oUserLocal = MetaModel::GetObject(UserLocal::class, $oUserLocal->GetKey());
		$oUserLocal->Set('password_renewed_date', $oBefore);
		$oUserLocal->DBWrite();
		$this->assertEquals($oBefore, $oUserLocal->Get('password_renewed_date'), 'UPDATE can target and change the "password_renewed_date"');

		//UPDATE password
		$oUserLocal = MetaModel::GetObject(UserLocal::class, $oUserLocal->GetKey());
		$oUserLocal->Set('password', 'fooBar1???1');
		$oUserLocal->DBWrite();
		$this->assertEquals($oExpectedAfter, $oUserLocal->Get('password_renewed_date'), 'UPDATE "password" fields trigger automatic change of the  "password_renewed_date" field');

		//UPDATE both password & password_renewed_date
		$oUserLocal = MetaModel::GetObject(UserLocal::class, $oUserLocal->GetKey());
		$oUserLocal->Set('password', 'fooBar1???2');
		$oUserLocal->Set('password_renewed_date', $oBefore);
		$oUserLocal->DBWrite();
		$this->assertEquals($oBefore, $oUserLocal->Get('password_renewed_date'), 'UPDATE can target and change both "password" and "password_renewed_date"');
	}

	public function ProviderPasswordRenewal()
	{
		return array(
			'nominal case' => array(
				'oExpectedBefore' => null,
				'oExpectedAfter' => 'now',
			),
			'date initiated' => array(
				'oBefore' => '-1 day',
				'oExpectedAfter' => 'now',
			),
			'date initiated in the future' => array(
				'oBefore' => '+1 day',
				'oExpectedAfter' => 'now',
			),
		);
	}

	/**
	 * @dataProvider CanExpireFixProvider
	 *
	 */
	public function testCanExpireFix($sExpirationMode, $sBefore, bool $bRenewedDateTouched)
	{
		$oBefore = is_null($sBefore) ? null : date(AttributeDate::GetInternalFormat(), strtotime($sBefore));
		$oNow = date(AttributeDate::GetInternalFormat());
		$oExpectedAfter = $bRenewedDateTouched ? $oNow : $oBefore;

		$aUserLocalValues = array('login' => 'john');
		if (!is_null($oBefore))
		{
			$aUserLocalValues['password_renewed_date'] = $oBefore;
		}

		/** @var UserLocal $oUserLocal */
		$oUserLocal = MetaModel::NewObject(UserLocal::class, $aUserLocalValues);
		/** @var ormLinkSet $oProfileSet */
		$oProfileSet = $oUserLocal->Get('profile_list');

		$oProfileSet->AddItem(
			MetaModel::NewObject(URP_UserProfile::class, array('profileid' => 1))
		);

		$this->assertEquals($oBefore, $oUserLocal->Get('password_renewed_date'));

		//INSERT
		$oUserLocal->Set('password', 'fooBar1???');
		$oUserLocal->DBWrite();
		$this->assertEquals($oNow, $oUserLocal->Get('password_renewed_date'), 'INSERT sets the "password_renewed_date" to the current date');

		$oUserLocal = MetaModel::GetObject(UserLocal::class, $oUserLocal->GetKey());
		$oUserLocal->Set('password_renewed_date', $oBefore);
		$oUserLocal->DBWrite();
		$this->assertEquals($oBefore, $oUserLocal->Get('password_renewed_date'), 'UPDATE can target and change the "password_renewed_date"');

		//UPDATE password
		$oUserLocal = MetaModel::GetObject(UserLocal::class, $oUserLocal->GetKey());
		$oUserLocal->Set('expiration', $sExpirationMode);
		$oUserLocal->DBWrite();
		$this->assertEquals($oExpectedAfter, $oUserLocal->Get('password_renewed_date'), 'UPDATE "password" fields trigger automatic change of the  "password_renewed_date" field');
	}

	public function CanExpireFixProvider()
	{
		return array(
			'EXPIRE_CAN: nominal case' => array(
				'sExpirationMode' => 'can_expire',
				'oExpectedBefore' => null,
				'bRenewedDateTouched' => true,
			),
			'EXPIRE_NEVER (default mode): nothing changed on UserLocal' => array(
				'sExpirationMode' => 'never_expire',
				'oExpectedBefore' => null,
				'bRenewedDateTouched' => false,
			),
			'EXPIRE_FORCE: nominal case' => array(
				'sExpirationMode' => 'force_expire',
				'oExpectedBefore' => null,
				'bRenewedDateTouched' => true,
			),
			'EXPIRE_ONE_TIME_PWD: nominal case' => array(
				'sExpirationMode' => 'otp_expire',
				'oExpectedBefore' => null,
				'bRenewedDateTouched' => true,
			),
			'date initiated' => array(
				'sExpirationMode' => 'can_expire',
				'oBefore' => '-1 day',
				'bRenewedDateTouched' => false,
			),
			'date initiated in the future' => array(
				'sExpirationMode' => 'can_expire',
				'oBefore' => '+1 day',
				'bRenewedDateTouched' => false,
			),
		);
	}

	/**
	 * @runInSeparateProcess Otherwise, and only in the CI, test fails asserting $oProfilesSet->Count() == 0
	 */
	public function testGetUserProfileList()
	{
		utils::GetConfig()->SetModuleSetting('authent-local', 'password_validation.pattern', '');
		$sAdminLogin = 'admin';
		$oExistingAdminUser = MetaModel::GetObjectByColumn(User::class, 'login', $sAdminLogin, false);
		if (\is_null($oExistingAdminUser)) {
			$sAdministratorProfileId = 1;
			$this->CreateContactlessUser($sAdminLogin, $sAdministratorProfileId);
		}

		// By default should see all profiles
		$oProfilesSet = $this->GetAdminUserProfileList();
		$this->assertIsObject($oProfilesSet);
		$this->assertInstanceOf(ormLinkSet::class, $oProfilesSet);
		$this->assertGreaterThan(0, $oProfilesSet->Count());

		// non admin user : seeing profiles depends on the security.hide_administrators config param value
		$sSupportAgentProfileId = 5;
		$sSupportAgentLogin = 'support_agent';
		$this->CreateContactlessUser($sSupportAgentLogin, $sSupportAgentProfileId);
		UserRights::Login($sSupportAgentLogin);
		MetaModel::GetConfig()->Set('security.hide_administrators', true);
		$oProfilesSet = $this->GetAdminUserProfileList();
		$this->assertIsObject($oProfilesSet);
		$this->assertInstanceOf(ormLinkSet::class, $oProfilesSet);
		$this->assertEquals(0, $oProfilesSet->Count());
		MetaModel::GetConfig()->Set('security.hide_administrators', false);
		$oProfilesSet = $this->GetAdminUserProfileList();
		$this->assertIsObject($oProfilesSet);
		$this->assertInstanceOf(ormLinkSet::class, $oProfilesSet);
		$this->assertGreaterThan(0, $oProfilesSet->Count());

		// admin user : will always see profiles whatever the security.hide_administrators config param value is
		UserRights::Login($sAdminLogin);
		MetaModel::GetConfig()->Set('security.hide_administrators', true);
		$oProfilesSet = $this->GetAdminUserProfileList();
		$this->assertIsObject($oProfilesSet);
		$this->assertInstanceOf(ormLinkSet::class, $oProfilesSet);
		$this->assertGreaterThan(0, $oProfilesSet->Count());
		MetaModel::GetConfig()->Set('security.hide_administrators', false);
		$oProfilesSet = $this->GetAdminUserProfileList();
		$this->assertIsObject($oProfilesSet);
		$this->assertInstanceOf(ormLinkSet::class, $oProfilesSet);
		$this->assertGreaterThan(0, $oProfilesSet->Count());
	}

	private function GetAdminUserProfileList(): ormLinkSet
	{
		$oSearch = new DBObjectSearch(UserLocal::class);
		$oSearch->AllowAllData();
		$oSearch->AddCondition('login', 'admin', '=');
		$oObjectSet = new DBObjectSet($oSearch);
		/** @noinspection OneTimeUseVariablesInspection */
		$oUser = $oObjectSet->Fetch();
		return $oUser->Get('profile_list');
	}
}

