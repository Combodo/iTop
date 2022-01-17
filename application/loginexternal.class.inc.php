<?php

use Combodo\iTop\Application\Helper\Session;

/**
 * Class LoginExternal
 *
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
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
		if (!Session::IsSet('login_mode'))
		{
			$sAuthUser = $this->GetAuthUser();
			if ($sAuthUser && (strlen($sAuthUser) > 0))
			{
				Session::Set('login_mode', 'external');
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCheckCredentials(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'external')
		{
			$sAuthUser = $this->GetAuthUser();
			if (!UserRights::CheckCredentials($sAuthUser, '', Session::Get('login_mode'), 'external'))
			{
				$iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;
				return LoginWebPage::LOGIN_FSM_ERROR;
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCredentialsOK(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'external')
		{
			$sAuthUser = $this->GetAuthUser();
			LoginWebPage::OnLoginSuccess($sAuthUser, 'external', Session::Get('login_mode'));
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnConnected(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'external')
		{
			Session::Set('can_logoff', false);
			return LoginWebPage::CheckLoggedUser($iErrorCode);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnError(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'external')
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