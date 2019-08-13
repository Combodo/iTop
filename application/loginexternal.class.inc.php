<?php

/**
 * Class LoginExternal
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class LoginExternal implements iLoginFSMExtension
{

	/**
	 * Return the list of supported login modes for this plugin
	 *
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes()
	{
		return array('external');
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
				if (!isset($_SESSION['login_mode']))
				{
					$sAuthUser = $this->GetAuthUser();
					if ($sAuthUser && (strlen($sAuthUser) > 0))
					{
						$_SESSION['login_mode'] = 'external';
					}
				}
				break;

			case LoginWebPage::LOGIN_STATE_CHECK_CREDENTIALS:
				if ($_SESSION['login_mode'] == 'external')
				{
					$sAuthUser = $this->GetAuthUser();
					if (!UserRights::CheckCredentials($sAuthUser, '', $_SESSION['login_mode'], 'external'))
					{
						$iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;

						return LoginWebPage::LOGIN_FSM_RETURN_ERROR;
					}
				}
				break;

			case LoginWebPage::LOGIN_STATE_CREDENTIAL_OK:
				if ($_SESSION['login_mode'] == 'external')
				{
					$sAuthUser = $this->GetAuthUser();
					LoginWebPage::OnLoginSuccess($sAuthUser, 'external', $_SESSION['login_mode']);
				}
				break;

			case LoginWebPage::LOGIN_STATE_CONNECTED:
				if ($_SESSION['login_mode'] == 'external')
				{
					$_SESSION['can_logoff'] = false;
					return LoginWebPage::CheckLoggedUser($iErrorCode);
				}
				break;
		}

		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	/**
	 * @return bool
	 */
	private function GetAuthUser()
	{
		$sExtAuthVar = MetaModel::GetConfig()->GetExternalAuthenticationVariable(); // In which variable is the info passed ?
		eval('$sAuthUser = isset('.$sExtAuthVar.') ? '.$sExtAuthVar.' : false;'); // Retrieve the value
		/** @var string $sAuthUser */
		return $sAuthUser; // Retrieve the value
	}
}