<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 21/11/2019
 * Time: 09:14
 */

namespace coreExtensions;


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use UserLocal;
use UserLocalPasswordPolicyMockNotValid;
use UserLocalPasswordPolicyMockNotValidBis;
use UserLocalPasswordPolicyMockValid;
use UserLocalPasswordPolicyMockValidBis;
use UserLocalPasswordValidity;
use UserPasswordPolicyRegex;

/**
 * test class for UserLocal class
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class UserLocalTest extends ItopDataTestCase
{

	public function setUp()
	{
		parent::setUp();

		require_once(APPROOT.'application/startup.inc.php');
		require_once (APPROOT.'test/coreExtensions/UserLocalTest/UserLocalPasswordPolicyMock.php');
		require_once (APPROOT.'env-production/authent-local/model.authent-local.php');
	}

	/**
	 * @dataProvider ProviderValidatePassword
	 *
	 * @runTestsInSeparateProcesses
	 * @preserveGlobalState disabled
	 * @backupGlobals disabled
	 */
	public function testValidatePassword($sPassword, $aValidatorNames, $aConfigValueMap, $bExpectedCheckStatus, $expectedCheckIssues = null, $sUserLanguage = null)
	{
		$configMock = $this->createMock(\Config::class);

		$configMock
			->method('GetModuleSetting')
			->willReturnMap($aConfigValueMap);

		if (isset($sUserLanguage))
		{
			\Dict::SetUserLanguage($sUserLanguage);
		}

		/** @var UserLocal $oUserLocal */
		$oUserLocal = \MetaModel::NewObject('UserLocal', array('login' => 'john'));
		/** @var \ormLinkSet $oProfileSet */
		$oProfileSet = $oUserLocal->Get('profile_list');

		$oProfileSet->AddItem(
			\MetaModel::NewObject('URP_UserProfile', array('profileid' => 1))
		);

		$aValidatorCollection = array();
		foreach ($aValidatorNames as $class)
		{
			$aValidatorCollection[] = new $class();
		}

		$oUserLocal->ValidatePassword($sPassword, $configMock, $aValidatorCollection);

		list($bCheckStatus, $aCheckIssues, $aSecurityIssues) =  $oUserLocal->CheckToWrite();

		$this->assertSame($bExpectedCheckStatus, $bCheckStatus);

		if (isset($expectedCheckIssues))
		{
			$this->assertContains($expectedCheckIssues, $aCheckIssues);
		}
	}

	public function ProviderValidatePassword()
	{
		return array(
			'validPattern' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap' => array(
					array('authent-local', 'password_validation.pattern', null, '.{1,10}')
				),
				'expectedCheckStatus' => true,
			),
			'notValidPattern' => array(
				'password' => 'foo',
				'aValidatorCollection' => array(
					'UserPasswordPolicyRegex',
				),
				'valueMap' => array(
					array('authent-local', 'password_validation.pattern', null, '.{6,10}')
				),
				'expectedCheckStatus' => false,
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
		$oBefore = is_null($sBefore) ? null : date(\AttributeDate::GetInternalFormat(), strtotime($sBefore));
		$oExpectedAfter = is_null($sExpectedAfter) ? null : date(\AttributeDate::GetInternalFormat(), strtotime($sExpectedAfter));

		$aUserLocalValues = array('login' => 'john');
		if (!is_null($oBefore))
		{
			$aUserLocalValues['password_renewed_date'] = $oBefore;
		}

		/** @var UserLocal $oUserLocal */
		$oUserLocal = \MetaModel::NewObject('UserLocal', $aUserLocalValues);
		/** @var \ormLinkSet $oProfileSet */
		$oProfileSet = $oUserLocal->Get('profile_list');

		$oProfileSet->AddItem(
			\MetaModel::NewObject('URP_UserProfile', array('profileid' => 1))
		);


		$this->assertEquals($oBefore, $oUserLocal->Get('password_renewed_date'));

		//INSERT
		$oUserLocal->Set('password', 'fooBar1???');
		$oUserLocal->DBWrite();
		$this->assertEquals($oBefore, $oUserLocal->Get('password_renewed_date'), 'INSERT changes the "password_renewed_date"');

		//UPDATE password_renewed_date
		$oUserLocal->Set('password_renewed_date', $oBefore);
		$oUserLocal->DBWrite();
		$this->assertEquals($oBefore, $oUserLocal->Get('password_renewed_date'), 'UPDATE can target and change the "password_renewed_date"');

		//UPDATE password
		$oUserLocal->Set('password', 'fooBar1???1');
		$oUserLocal->DBWrite();
		$this->assertEquals($oExpectedAfter, $oUserLocal->Get('password_renewed_date'), 'UPDATE "password" fields trigger automatic change of the  "password_renewed_date" field');


		//UPDATE both password & password_renewed_date
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
}

