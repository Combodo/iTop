<?php

use Combodo\iTop\Application\Helper\Session;

/**
 * Class LoginURL
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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
		if (!Session::IsSet('login_mode') && !$this->bErrorOccurred)
		{
			$sAuthUser = utils::ReadParam('auth_user', '', false, 'raw_data');
			$sAuthPwd = utils::ReadParam('auth_pwd', null, false, 'raw_data');
			if (!empty($sAuthUser) && !empty($sAuthPwd))
			{
				Session::Set('login_mode', 'url');
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnReadCredentials(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'url')
		{
			Session::Set('login_temp_auth_user', utils::ReadParam('auth_user', '', false, 'raw_data'));
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCheckCredentials(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'url')
		{
			$sAuthUser = utils::ReadParam('auth_user', '', false, 'raw_data');
			$sAuthPwd = utils::ReadParam('auth_pwd', null, false, 'raw_data');
			if (!UserRights::CheckCredentials($sAuthUser, $sAuthPwd, Session::Get('login_mode'), 'internal'))
			{
				$iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;
				return LoginWebPage::LOGIN_FSM_ERROR;
			}
			Session::Set('auth_user', $sAuthUser);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCredentialsOK(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'url')
		{
			LoginWebPage::OnLoginSuccess(Session::Get('auth_user'), 'internal', Session::Get('login_mode'));
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnError(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'url')
		{
			$this->bErrorOccurred = true;
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnConnected(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'url')
		{
			Session::Set('can_logoff', true);
			return LoginWebPage::CheckLoggedUser($iErrorCode);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}
}
