<?php

use Combodo\iTop\Application\Helper\Session;

/**
 * Class LoginBasic
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class LoginBasic extends AbstractLoginFSMExtension
{
	/**
	 * Return the list of supported login modes for this plugin
	 *
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes()
	{
		return array('basic');
	}

	protected function OnModeDetection(&$iErrorCode)
	{
		if (!Session::IsSet('login_mode'))
		{
			if (isset($_SERVER['HTTP_AUTHORIZATION']) && !empty($_SERVER['HTTP_AUTHORIZATION']))
			{
				Session::Set('login_mode', 'basic');
			}
			elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && !empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION']))
			{
				Session::Set('login_mode', 'basic');
			}
			elseif (isset($_SERVER['PHP_AUTH_USER']))
			{
				Session::Set('login_mode', 'basic');
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnReadCredentials(&$iErrorCode)
	{
		if (!Session::IsSet('login_mode') || Session::Get('login_mode') == 'basic')
		{
			list($sAuthUser) = $this->GetAuthUserAndPassword();
			Session::Set('login_temp_auth_user', $sAuthUser);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}


	protected function OnCheckCredentials(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'basic')
		{
			list($sAuthUser, $sAuthPwd) = $this->GetAuthUserAndPassword();
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
		if (Session::Get('login_mode') == 'basic')
		{
			LoginWebPage::OnLoginSuccess(Session::Get('auth_user'), 'internal', Session::Get('login_mode'));
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnError(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'basic')
		{
            $iOnExit = LoginWebPage::getIOnExit();
            if ($iOnExit === LoginWebPage::EXIT_RETURN)
            {
                return LoginWebPage::LOGIN_FSM_RETURN; // Error, exit FSM
            }
			LoginWebPage::HTTP401Error();
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnConnected(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'basic')
		{
			Session::Set('can_logoff', true);
			return LoginWebPage::CheckLoggedUser($iErrorCode);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	private function GetAuthUserAndPassword()
	{
		$sAuthUser = '';
		$sAuthPwd = null;
		$sAuthorization = '';
		if (isset($_SERVER['HTTP_AUTHORIZATION']) && !empty($_SERVER['HTTP_AUTHORIZATION']))
		{
			$sAuthorization = $_SERVER['HTTP_AUTHORIZATION'];
		}
		elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && !empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION']))
		{
			$sAuthorization = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
		}

		if (!empty($sAuthorization))
		{
			list($sAuthUser, $sAuthPwd) = explode(':', base64_decode(substr($sAuthorization, 6)));
		}
		else
		{
			if (isset($_SERVER['PHP_AUTH_USER']))
			{
				$sAuthUser = $_SERVER['PHP_AUTH_USER'];
				// Unfortunately, the RFC is not clear about the encoding...
				// IE and FF supply the user and password encoded in ISO-8859-1 whereas Chrome provides them encoded in UTF-8
				// So let's try to guess if it's an UTF-8 string or not... fortunately all encodings share the same ASCII base
				if (!LoginWebPage::LooksLikeUTF8($sAuthUser))
				{
					// Does not look like and UTF-8 string, try to convert it from iso-8859-1 to UTF-8
					// Supposed to be harmless in case of a plain ASCII string...
					$sAuthUser = iconv('iso-8859-1', 'utf-8', $sAuthUser);
				}
				$sAuthPwd = $_SERVER['PHP_AUTH_PW'];
				if (!LoginWebPage::LooksLikeUTF8($sAuthPwd))
				{
					// Does not look like and UTF-8 string, try to convert it from iso-8859-1 to UTF-8
					// Supposed to be harmless in case of a plain ASCII string...
					$sAuthPwd = iconv('iso-8859-1', 'utf-8', $sAuthPwd);
				}
			}
		}
		return array($sAuthUser, $sAuthPwd);
	}
}
