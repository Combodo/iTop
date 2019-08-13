<?php
/**
 * Class LoginBasic
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class LoginBasic implements iLoginFSMExtension
{
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
					if (isset($_SERVER['HTTP_AUTHORIZATION']) && !empty($_SERVER['HTTP_AUTHORIZATION']))
					{
						$_SESSION['login_mode'] = 'basic';
					}
					elseif (isset($_SERVER['PHP_AUTH_USER']))
					{
						$_SESSION['login_mode'] = 'basic';
					}
				}
				break;

			case LoginWebPage::LOGIN_STATE_CHECK_CREDENTIALS:
				if ($_SESSION['login_mode'] == 'basic')
				{
					list($sAuthUser, $sAuthPwd) = $this->GetAuthUserAndPassword();
					if (!UserRights::CheckCredentials($sAuthUser, $sAuthPwd, $_SESSION['login_mode'], 'internal'))
					{
						$iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;

						return LoginWebPage::LOGIN_FSM_RETURN_ERROR;
					}
				}
				break;

			case LoginWebPage::LOGIN_STATE_CREDENTIAL_OK:
				if ($_SESSION['login_mode'] == 'basic')
				{
					list($sAuthUser) = $this->GetAuthUserAndPassword();
					LoginWebPage::OnLoginSuccess($sAuthUser, 'internal', $_SESSION['login_mode']);
				}
				break;

			case LoginWebPage::LOGIN_STATE_ERROR:
				if ($_SESSION['login_mode'] == 'basic')
				{
					LoginWebPage::HTTP401Error();
				}
				break;

			case LoginWebPage::LOGIN_STATE_CONNECTED:
				if ($_SESSION['login_mode'] == 'basic')
				{
					$_SESSION['can_logoff'] = true;
					return LoginWebPage::CheckLoggedUser($iErrorCode);
				}
				break;
		}

		return LoginWebPage::LOGIN_FSM_RETURN_CONTINUE;
	}

	/**
	 * Return the list of supported login modes for this plugin
	 *
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes()
	{
		return array('basic');
	}

	private function GetAuthUserAndPassword()
	{
		$sAuthUser = '';
		$sAuthPwd = null;
		if (isset($_SERVER['HTTP_AUTHORIZATION']) && !empty($_SERVER['HTTP_AUTHORIZATION']))
		{
			list($sAuthUser, $sAuthPwd) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
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