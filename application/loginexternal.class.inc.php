<?php

/**
 * Class LoginExternal
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class LoginExternal extends AbstractLoginFSMExtension
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

	protected function OnModeDetection(&$iErrorCode)
	{
		if (!isset($_SESSION['login_mode']))
		{
			$sAuthUser = $this->GetAuthUser();
			if ($sAuthUser && (strlen($sAuthUser) > 0))
			{
				$_SESSION['login_mode'] = 'external';
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCheckCredentials(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'external')
		{
			$sAuthUser = $this->GetAuthUser();
			if (!UserRights::CheckCredentials($sAuthUser, '', $_SESSION['login_mode'], 'external'))
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
		if ($_SESSION['login_mode'] == 'external')
		{
			$sAuthUser = $_SESSION['auth_user'];
			LoginWebPage::OnLoginSuccess($sAuthUser, 'external', $_SESSION['login_mode']);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnConnected(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'external')
		{
			$_SESSION['can_logoff'] = false;
			return LoginWebPage::CheckLoggedUser($iErrorCode);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnError(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'external')
		{
			LoginWebPage::HTTP401Error();
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
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