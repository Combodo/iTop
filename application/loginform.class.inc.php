<?php

/**
 * Class LoginForm
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class LoginForm extends AbstractLoginFSMExtension
{
	private $bForceFormOnError = false;

	/**
	 * Return the list of supported login modes for this plugin
	 *
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes()
	{
		return array('form');
	}

	protected function OnModeDetection(&$iErrorCode)
	{
		$sAuthUser = utils::ReadPostedParam('auth_user', '', 'raw_data');
		$sAuthPwd = utils::ReadPostedParam('auth_pwd', null, 'raw_data');
		if (!empty($sAuthUser) && !empty($sAuthPwd))
		{
			$_SESSION['login_mode'] = 'form';
		}
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	protected function OnReadCredentials(&$iErrorCode)
	{
		if (!isset($_SESSION['login_mode']) || ($_SESSION['login_mode'] == 'form'))
		{
			$sAuthUser = utils::ReadPostedParam('auth_user', '', 'raw_data');
			$sAuthPwd = utils::ReadPostedParam('auth_pwd', null, 'raw_data');
			$_SESSION['login_mode'] = 'form';
			if ($this->bForceFormOnError || empty($sAuthUser) || empty($sAuthPwd))
			{
				if (array_key_exists('HTTP_X_COMBODO_AJAX', $_SERVER))
				{
					// X-Combodo-Ajax is a special header automatically added to all ajax requests
					// Let's reply that we're currently logged-out
					header('HTTP/1.0 401 Unauthorized');
					exit;
				}

				// No credentials yet, display the form
				$oPage = LoginWebPage::NewLoginWebPage();
				$oPage->DisplayLoginForm('form', $this->bForceFormOnError);
				$oPage->output();
				$this->bForceFormOnError = false;
				exit;
			}
		}
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	protected function OnCheckCredentials(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'form')
		{
			$sAuthUser = utils::ReadPostedParam('auth_user', '', 'raw_data');
			$sAuthPwd = utils::ReadPostedParam('auth_pwd', null, 'raw_data');
			if (!UserRights::CheckCredentials($sAuthUser, $sAuthPwd, $_SESSION['login_mode'], 'internal'))
			{
				$iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;
				return LoginWebPage::LOGIN_FSM_RETURN_ERROR;
			}
		}
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	protected function OnCredentialsOK(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'form')
		{
			$sAuthUser = utils::ReadPostedParam('auth_user', '', 'raw_data');
			LoginWebPage::OnLoginSuccess($sAuthUser, 'internal', $_SESSION['login_mode']);
		}
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	protected function OnError(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'form')
		{
			$this->bForceFormOnError = true;
		}
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	protected function OnConnected(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'form')
		{
			$_SESSION['can_logoff'] = true;
			return LoginWebPage::CheckLoggedUser($iErrorCode);
		}
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}
}
