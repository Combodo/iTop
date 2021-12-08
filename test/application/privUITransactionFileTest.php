<?php
class privUITransactionFileTest extends \Combodo\iTop\Test\UnitTest\ItopDataTestCase
{
	/** @var int ID of the "support agent" pofile in the sample data */
	const SAMPLE_DATA_SUPPORT_PROFILE_ID = 5;
	const USER1_TEST_LOGIN = 'user1_support_test_privUITransaction';
	const USER2_TEST_LOGIN = 'user2_support_test_privUITransaction';

	/**
	 * @throws \SecurityException
	 * @uses self::SAMPLE_DATA_SUPPORT_PROFILE_ID
	 * @uses self::USER1_TEST_LOGIN
	 * @uses self::USER2_TEST_LOGIN
	 */
	public function testIsTransactionValid()
	{
		$this->CreateUser(static::USER1_TEST_LOGIN, self::SAMPLE_DATA_SUPPORT_PROFILE_ID);
		$this->CreateUser(static::USER2_TEST_LOGIN, self::SAMPLE_DATA_SUPPORT_PROFILE_ID);

		// create token in the user1 context
		$bUser1Login1 = UserRights::Login(self::USER1_TEST_LOGIN);
		$this->assertTrue($bUser1Login1, 'Login with user1 throw an error');
		$sTransactionIdUserSupport = privUITransactionFile::GetNewTransactionId();
		$bResult = privUITransactionFile::IsTransactionValid($sTransactionIdUserSupport, false);
		$this->assertTrue($bResult, 'Token created by support user must be valid in the support user context');

		// test token in the user2 context
		$bUser2Login = UserRights::Login(self::USER2_TEST_LOGIN);
		$this->assertTrue($bUser2Login, 'Login with user2 throw an error');
		$bResult = privUITransactionFile::IsTransactionValid($sTransactionIdUserSupport, false);
		$this->assertFalse($bResult, 'Token created by support user must be invalid in the admin user context');
		$bResult = privUITransactionFile::RemoveTransaction($sTransactionIdUserSupport);
		$this->assertFalse($bResult, 'Token created by support user cannot be removed in the admin user context');

		// test other methods in the user1 context
		$bUser1Login2 = UserRights::Login(self::USER1_TEST_LOGIN);
		$this->assertTrue($bUser1Login2, 'Login with user1 throw an error');
		$bResult = privUITransactionFile::RemoveTransaction($sTransactionIdUserSupport);
		$this->assertTrue($bResult, 'Token created by support user must be removed in the support user context');

		// test when no user logged (combodo-unauthenticated-form module for example)
		UserRights::_ResetSessionCache();
		$sTransactionIdUnauthenticatedUser = privUITransactionFile::GetNewTransactionId();
		$bResult = privUITransactionFile::IsTransactionValid($sTransactionIdUnauthenticatedUser, false);
		$this->assertTrue($bResult, 'Token created by unauthenticated user must be valid when no user logged');
		$bResult = privUITransactionFile::RemoveTransaction($sTransactionIdUnauthenticatedUser);
		$this->assertTrue($bResult, 'Token created by unauthenticated user must be removed when no user logged');
	}
}