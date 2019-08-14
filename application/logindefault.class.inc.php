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
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	protected function OnError(&$iErrorCode)
	{
		$_SESSION['login_error_count'] = (isset($_SESSION['login_error_count']) ? $_SESSION['login_error_count'] : 0) + 1;
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	protected function OnConnected(&$iErrorCode)
	{
		unset($_SESSION['login_error_count']);
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
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
		unset($_SESSION['login_mode']);
		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	/**
	 * Execute all actions to log out properly
	 */
	public function LogoutAction()
	{
		unset($_SESSION['login_mode']);
	}
}