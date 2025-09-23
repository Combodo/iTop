<?php

class TokenValidation
{
	// construct function
	public function __construct()
	{
	}
	public function isSetupTokenValid($sParamToken) : bool
	{
		if (!file_exists(APPROOT.'data/.setup')) {
			return false;
		}
		$sSetupToken = trim(file_get_contents(APPROOT.'data/.setup'));
		unlink(APPROOT.'data/.setup');
		return $sParamToken === $sSetupToken;
	}

}