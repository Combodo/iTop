<?php

/**
 * Class LoginForm
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class LoginForm extends AbstractLoginFSMExtension implements iLoginUIExtension
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

	protected function OnReadCredentials(&$iErrorCode)
	{
		if (!isset($_SESSION['login_mode']) || ($_SESSION['login_mode'] == 'form'))
		{
			$sAuthUser = utils::ReadPostedParam('auth_user', '', 'raw_data');
			$sAuthPwd = utils::ReadPostedParam('auth_pwd', null, 'raw_data');
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
				$oPage->DisplayLoginForm($this->bForceFormOnError);
				$oPage->output();
				$this->bForceFormOnError = false;
				exit;
			}

			$_SESSION['login_temp_auth_user'] =  $sAuthUser;
			$_SESSION['login_mode'] = 'form';
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
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
				return LoginWebPage::LOGIN_FSM_ERROR;
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCredentialsOK(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'form')
		{
			if (isset($_SESSION['auth_user']))
			{
				// If FSM reenter this state (example 2FA) then the auth_user is not resubmitted
				$sAuthUser = $_SESSION['auth_user'];
			}
			else
			{
				$sAuthUser = utils::ReadPostedParam('auth_user', '', 'raw_data');
			}
			// Store 'auth_user' in session for further use
			LoginWebPage::OnLoginSuccess($sAuthUser, 'internal', $_SESSION['login_mode']);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnError(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'form')
		{
			$this->bForceFormOnError = true;
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnConnected(&$iErrorCode)
	{
		if ($_SESSION['login_mode'] == 'form')
		{
			$_SESSION['can_logoff'] = true;
			return LoginWebPage::CheckLoggedUser($iErrorCode);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @return LoginTwigContext
	 * @throws \Exception
	 */
	public function GetTwigContext()
	{
		$oLoginContext = new LoginTwigContext();
		$oLoginContext->AddPostedVar('auth_user');
		$oLoginContext->AddPostedVar('auth_pwd');

		$sAuthUser = utils::ReadParam('auth_user', '', true, 'raw_data');
		$sAuthPwd = utils::ReadParam('suggest_pwd', '', true, 'raw_data');

		$aData = array(
			'sAuthUser' => $sAuthUser,
			'sAuthPwd' => $sAuthPwd,
		);
		$oLoginContext->AddBlockExtension('login_input', new LoginBlockExtension('extensionblock/loginforminput.html.twig', $aData));
		$oLoginContext->AddBlockExtension('login_submit', new LoginBlockExtension('extensionblock/loginformsubmit.html.twig'));
		$oLoginContext->AddBlockExtension('login_form_footer', new LoginBlockExtension('extensionblock/loginformfooter.html.twig'));

		$bEnableResetPassword = empty(MetaModel::GetConfig()->Get('forgot_password')) ? true : MetaModel::GetConfig()->Get('forgot_password');
		$sResetPasswordUrl = utils::GetAbsoluteUrlAppRoot() . 'pages/UI.php?loginop=forgot_pwd';
		$aData = array(
			'bEnableResetPassword' => $bEnableResetPassword,
			'sResetPasswordUrl' => $sResetPasswordUrl,
		);
		$oLoginContext->AddBlockExtension('login_links', new LoginBlockExtension('extensionblock/loginformlinks.html.twig', $aData));

		return $oLoginContext;
	}
}
