<?php

/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Helper\Session;

/**
 * Class LoginForm
 *
 * @since 2.7.0
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

	/**
	 * @inheritDoc
	 */
	protected function OnReadCredentials(&$iErrorCode)
	{
		if (!Session::IsSet('login_mode') || Session::Get('login_mode') == 'form') {
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

                if (LoginWebPage::getIOnExit() === LoginWebPage::EXIT_RETURN) {
                    return LoginWebPage::LOGIN_FSM_CONTINUE;
                }

				// No credentials yet, display the form
				$oPage = LoginWebPage::NewLoginWebPage();
				$oPage->DisplayLoginForm($this->bForceFormOnError);
				$oPage->output();
				$this->bForceFormOnError = false;
				exit;
			}
			Session::Set('login_temp_auth_user', $sAuthUser);
			Session::Set('login_mode', 'form');
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @inheritDoc
	 */
	protected function OnCheckCredentials(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'form')
		{
			$sAuthUser = utils::ReadPostedParam('auth_user', '', 'raw_data');
			$sAuthPwd = utils::ReadPostedParam('auth_pwd', null, 'raw_data');
			if (!UserRights::CheckCredentials($sAuthUser, $sAuthPwd, Session::Get('login_mode'), 'internal'))
			{
				$iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;
				return LoginWebPage::LOGIN_FSM_ERROR;
			}
			Session::Set('auth_user', $sAuthUser);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @inheritDoc
	 */
	protected function OnCredentialsOK(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'form')
		{
			// Store 'auth_user' in session for further use
			LoginWebPage::OnLoginSuccess(Session::Get('auth_user'), 'internal', Session::Get('login_mode'));
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @inheritDoc
	 */
	protected function OnError(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'form')
		{
			$this->bForceFormOnError = true;
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @inheritDoc
	 */
	protected function OnConnected(&$iErrorCode)
	{
		if (Session::Get('login_mode') == 'form')
		{
			Session::Set('can_logoff', true);
			return LoginWebPage::CheckLoggedUser($iErrorCode);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @inheritDoc
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

		$bEnableResetPassword = MetaModel::GetConfig()->Get('forgot_password');
		$sResetPasswordUrl = MetaModel::GetConfig()->Get('forgot_password.url');
		if ($sResetPasswordUrl == '')
		{
			$sResetPasswordUrl = utils::GetAbsoluteUrlAppRoot() . 'pages/UI.php?loginop=forgot_pwd';
		}
		$aData = array(
			'bEnableResetPassword' => $bEnableResetPassword,
			'sResetPasswordUrl' => $sResetPasswordUrl,
		);
		$oLoginContext->AddBlockExtension('login_links', new LoginBlockExtension('extensionblock/loginformlinks.html.twig', $aData));

		return $oLoginContext;
	}
}
