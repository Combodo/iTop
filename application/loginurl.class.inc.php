<?php

/**
 * Class LoginURL
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class LoginURL extends AbstractLoginFSMExtension
{
	/**
	 * @var bool
	 */
	private $bErrorOccurred = false;

	/**
	 * Return the list of supported login modes for this plugin
	 *
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes()
	{
		return array('url');
	}

	protected function OnModeDetection(&$iErrorCode)
	{
		if (!isset($_SESSION['login_mode']) && !$this->bErrorOccurred)
		{
			$sAuthUser = utils::ReadParam('auth_user', '', false, 'raw_data');
			$sAuthPwd = utils::ReadParam('auth_pwd', null, false, 'raw_data');
			if (!empty($sAuthUser) && !empty($sAuthPwd))
			{
				$_SESSION['login_mode'] = 'url';
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnReadCredentials(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'url')
		{
			$_SESSION['login_temp_auth_user'] =  utils::ReadParam('auth_user', '', false, 'raw_data');
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCheckCredentials(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'url')
		{
			$sAuthUser = utils::ReadParam('auth_user', '', false, 'raw_data');
			$sAuthPwd = utils::ReadParam('auth_pwd', null, false, 'raw_data');
			if (!UserRights::CheckCredentials($sAuthUser, $sAuthPwd, $_SESSION['login_mode'], 'internal'))
			{
				$iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;
				return LoginWebPage::LOGIN_FSM_ERROR;
			}
			// Save the checked user
			$_SESSION['auth_user'] = $sAuthUser;
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCredentialsOK(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'url')
		{
			$sAuthUser = $_SESSION['auth_user'];
			LoginWebPage::OnLoginSuccess($sAuthUser, 'internal', $_SESSION['login_mode']);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnError(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'url')
		{
			$this->bErrorOccurred = true;
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnConnected(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'url')
		{
			$_SESSION['can_logoff'] = true;
			return LoginWebPage::CheckLoggedUser($iErrorCode);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}
}