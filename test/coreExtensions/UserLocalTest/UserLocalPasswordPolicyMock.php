<?php
class UserLocalPasswordPolicyMock implements \UserLocalPasswordValidator
{

	public function __construct()
	{
	}

	/**
	 * @param string $proposedValue
	 * @param array $aOptions
	 * @param UserLocal $oUserLocal
	 *
	 * @return UserLocalPasswordValidity
	 */
	public function ValidatePassword($proposedValue, $aOptions, UserLocal $oUserLocal)
	{
		$message = (isset($aOptions['sCheckIssues'])) ? $aOptions['sCheckIssues'] : 'UserLocalPasswordPolicyMock error message';

		return new UserLocalPasswordValidity($aOptions['bCheckStatus'], $message);
	}
}

class UserLocalPasswordPolicyMockBis extends UserLocalPasswordPolicyMock
{


}