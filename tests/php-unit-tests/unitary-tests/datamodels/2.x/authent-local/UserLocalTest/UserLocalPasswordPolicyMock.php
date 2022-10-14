<?php
class UserLocalPasswordPolicyMockValid implements \UserLocalPasswordValidator
{
	const CHECK_STATUS = true;
	const MESSAGE = null;

	public function __construct()
	{
	}

	/**
	 * @param string $proposedValue
	 * @param UserLocal $oUserLocal
	 * @param $config
	 *
	 * @return UserLocalPasswordValidity
	 */
	public function ValidatePassword($proposedValue, UserLocal $oUserLocal, $config)
	{
		return new UserLocalPasswordValidity(static::CHECK_STATUS, static::MESSAGE);
	}
}

class UserLocalPasswordPolicyMockNotValid extends UserLocalPasswordPolicyMockValid
{
	const CHECK_STATUS = false;
	const MESSAGE = 'UserLocalPasswordPolicyMockNotValid';
}

class UserLocalPasswordPolicyMockValidBis extends UserLocalPasswordPolicyMockValid
{
}

class UserLocalPasswordPolicyMockNotValidBis extends UserLocalPasswordPolicyMockNotValid
{
	const MESSAGE = 'UserLocalPasswordPolicyMockNotValidBis';
}