<?php

/**
 * Class LoginURL
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class LoginURL implements iLoginFSMExtension
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

	/**
	 * Execute action for this login state
	 * If a page is displayed, the action must exit at this point
	 *
	 * @param string $sLoginState (see LoginWebPage::LOGIN_STATE_...)
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_...
	 *
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MySQLException
	 * @throws \OQLException
	 */
	public function LoginAction($sLoginState, &$iErrorCode)
	{
		switch ($sLoginState)
		{
			case LoginWebPage::LOGIN_STATE_MODE_DETECTION:
				if (!isset($_SESSION['login_mode']) && !$this->bErrorOccurred)
				{
					$sAuthUser = utils::ReadParam('auth_user', '', false, 'raw_data');
					$sAuthPwd = utils::ReadParam('auth_pwd', null, false, 'raw_data');
					if (!empty($sAuthUser) && !empty($sAuthPwd))
					{
						$_SESSION['login_mode'] = 'url';
					}
				}
				break;

			case LoginWebPage::LOGIN_STATE_CHECK_CREDENTIALS:
				if ($_SESSION['login_mode'] == 'url')
				{
					$sAuthUser = utils::ReadParam('auth_user', '', false, 'raw_data');
					$sAuthPwd = utils::ReadParam('auth_pwd', null, false, 'raw_data');
					if (!UserRights::CheckCredentials($sAuthUser, $sAuthPwd, $_SESSION['login_mode'], 'internal'))
					{
						$iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;
						return LoginWebPage::LOGIN_FSM_RETURN_ERROR;
					}
				}
				break;

			case LoginWebPage::LOGIN_STATE_CREDENTIAL_OK:
				if ($_SESSION['login_mode'] == 'url')
				{
					$sAuthUser = utils::ReadParam('auth_user', '', false, 'raw_data');
					LoginWebPage::OnLoginSuccess($sAuthUser, 'internal', $_SESSION['login_mode']);
				}
				break;

			case LoginWebPage::LOGIN_STATE_ERROR:
				if ($_SESSION['login_mode'] == 'url')
				{
					$this->bErrorOccurred = true;
				}
				break;

			case LoginWebPage::LOGIN_STATE_CONNECTED:
				if ($_SESSION['login_mode'] == 'url')
				{
					$_SESSION['can_logoff'] = true;
					return LoginWebPage::CheckLoggedUser($iErrorCode);
				}
				break;
		}

		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}
}