<?php
class privUITransactionFileTest extends \Combodo\iTop\Test\UnitTest\ItopDataTestCase
{
	const USER_TEST_LOGIN = 'support_test_privUITransaction';

	public function testIsTransactionValid() {
		$this->CreateUser(static::USER_TEST_LOGIN, 5); // profile:5 is "Support agent"

		// create token in the support user context
		UserRights::Login(self::USER_TEST_LOGIN);
		$sTransactionIdUserSupport = privUITransactionFile::GetNewTransactionId();
		$bResult = privUITransactionFile::IsTransactionValid($sTransactionIdUserSupport, false);
		$this->assertTrue($bResult, 'Token created by support user must be valid in the support user context');

		// test token in the admin user context
		UserRights::Login('admin');
		$bResult = privUITransactionFile::IsTransactionValid($sTransactionIdUserSupport, false);
		$this->assertFalse($bResult, 'Token created by support user must be invalid in the admin user context');
		$bResult = privUITransactionFile::RemoveTransaction($sTransactionIdUserSupport);
		$this->assertFalse($bResult, 'Token created by support user cannot be removed in the admin user context');

		// test other methods in the support user context
		UserRights::Login(self::USER_TEST_LOGIN);
		$bResult = privUITransactionFile::RemoveTransaction($sTransactionIdUserSupport);
		$this->assertTrue($bResult, 'Token created by support user must be removed in the support user context');
	}
}