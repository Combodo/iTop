<?php
/**
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Class LoginDefaultBefore
 */
class LoginDefaultBefore extends AbstractLoginFSMExtension
{
	/**
	 * Must be executed before the other login plugins
	 *
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes()
	{
		return array('before');
	}

	protected function OnStart(&$iErrorCode)
	{
		$iErrorCode = LoginWebPage::EXIT_CODE_OK;

		unset($_SESSION['login_temp_auth_user']);

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
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnReadCredentials(&$iErrorCode)
	{
		// Check if proposed login mode is present and allowed
		$aAllowedLoginTypes = MetaModel::GetConfig()->GetAllowedLoginTypes();
		$sProposedLoginMode = utils::ReadParam('login_mode', '');
		$index = array_search($sProposedLoginMode, $aAllowedLoginTypes);
		if ($index !== false)
		{
			// Force login mode
			LoginWebPage::SetLoginModeAndReload($sProposedLoginMode);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}
}

/**
 * Class LoginDefaultAfter
 */
class LoginDefaultAfter extends AbstractLoginFSMExtension implements iLogoutExtension
{


	/**
	 * Must be executed after the other login plugins
	 *
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes()
	{
		return array('after');
	}

	protected function OnError(&$iErrorCode)
	{
		self::ResetLoginSession();
		$iOnExit = LoginWebPage::getIOnExit();
		if ($iOnExit == LoginWebPage::EXIT_RETURN)
		{
			return LoginWebPage::LOGIN_FSM_RETURN; // Error, exit FSM
		}
		elseif ($iOnExit == LoginWebPage::EXIT_HTTP_401)
		{
			LoginWebPage::HTTP401Error(); // Error, exit
		}
		// LoginWebPage::EXIT_PROMPT
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCredentialsOk(&$iErrorCode)
	{
		if (!isset($_SESSION['login_mode']))
		{
			// If no plugin validated the user, exit
			self::ResetLoginSession();
			exit();
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Execute all actions to log out properly
	 */
	public function LogoutAction()
	{
		self::ResetLoginSession();
	}

	protected function OnConnected(&$iErrorCode)
	{
		unset($_SESSION['login_temp_auth_user']);
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	// Hard reset of the session
	private static function ResetLoginSession()
	{
		LoginWebPage::ResetSession();
		foreach (array_keys($_SESSION) as $sKey)
		{
			if (utils::StartsWith($sKey, 'login_'))
			{
				unset($_SESSION[$sKey]);
			}
		}
	}
}