<?php

class UserLocalPasswordPolicyMockValid implements \UserLocalPasswordValidator
{
	public const CHECK_STATUS = true;
	public const MESSAGE = null;

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
	public const CHECK_STATUS = false;
	public const MESSAGE = 'UserLocalPasswordPolicyMockNotValid';
}

class UserLocalPasswordPolicyMockValidBis extends UserLocalPasswordPolicyMockValid
{
}

class UserLocalPasswordPolicyMockNotValidBis extends UserLocalPasswordPolicyMockNotValid
{
	public const MESSAGE = 'UserLocalPasswordPolicyMockNotValidBis';
}
