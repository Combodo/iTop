<?php

/**
 * Class LoginDefault
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class LoginDefault implements iLoginFSMExtension
{
	/**
	 * Return the list of supported login modes for this plugin
	 *
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes()
	{
		return array('default');
	}

	/**
	 * Execute action for this login state
	 * If a page is displayed, the action must exit at this point
	 * if LoginWebPage::LOGIN_FSM_RETURN_ERROR is returned $iErrorCode must be set
	 * if LoginWebPage::LOGIN_FSM_RETURN_OK is returned then the login is OK and terminated
	 * if LoginWebPage::LOGIN_FSM_RETURN_IGNORE is returned then the FSM will proceed to next plugin or state
	 *
	 * @param string $sLoginState (see LoginWebPage::LOGIN_STATE_...)
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_RETURN_IGNORE
	 */
	public function LoginAction($sLoginState, &$iErrorCode)
	{
		switch ($sLoginState)
		{
			case LoginWebPage::LOGIN_STATE_START:
				// Check if proposed login mode is present and allowed
				$aAllowedLoginTypes = MetaModel::GetConfig()->GetAllowedLoginTypes();
				$sProposedLoginMode = utils::ReadParam('login_mode', '');
				$index = array_search($sProposedLoginMode, $aAllowedLoginTypes);
				if ($index !== false)
				{
					// Force login mode
					$_SESSION['login_mode'] = $sProposedLoginMode;
				}
				else
				{
					unset($_SESSION['login_mode']);
				}
				break;

			case LoginWebPage::LOGIN_STATE_ERROR:
				$_SESSION['login_error_count'] = (isset($_SESSION['login_error_count']) ? $_SESSION['login_error_count'] : 0) + 1;
				break;

			case LoginWebPage::LOGIN_STATE_CONNECTED:
				unset($_SESSION['login_error_count']);
				break;
		}

		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}
}