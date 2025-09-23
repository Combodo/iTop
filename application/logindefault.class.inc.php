<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Helper\Session;

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

		Session::Unset('login_temp_auth_user');

		// Check if proposed login mode is present and allowed
		$aAllowedLoginTypes = MetaModel::GetConfig()->GetAllowedLoginTypes();
		$sProposedLoginMode = utils::ReadParam('login_mode', '');
		$index = array_search($sProposedLoginMode, $aAllowedLoginTypes);
		if ($index !== false)
		{
			// Force login mode
			Session::Set('login_mode', $sProposedLoginMode);
		}
		else
		{
			Session::Unset('login_mode');
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
		} else {
			$sRawLoginMode = utils::ReadParam('login_mode', '', false, utils::ENUM_SANITIZATION_FILTER_RAW_DATA);
			if ($sProposedLoginMode !== $sRawLoginMode) {
				IssueLog::Error("Authentication issue due to login_mode parameter sanitization. Please avoid special characters", null, ['sRawLoginMode' => $sRawLoginMode]);
				//IssueLog::Error("Authentication issue due to login_mode parameter sanitization. Please avoid special characters", null, ['sRawLoginMode' => utils::HtmlEntities($sRawLoginMode)]);
			}
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
		if ($iOnExit === LoginWebPage::EXIT_RETURN)
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
		if (!Session::IsSet('login_mode'))
		{
            // N°6358 - if EXIT_RETURN was asked, send an error
            if (LoginWebPage::getIOnExit() === LoginWebPage::EXIT_RETURN) {
                $iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;
                return LoginWebPage::LOGIN_FSM_ERROR;
            }

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
		Session::Unset('login_temp_auth_user');
		if (is_null(UserRights::GetUserObject())){
			//N°7085 avoid infinite loop
			IssueLog::Error("No user logged in. exit");
			exit(-1);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	// Hard reset of the session
	private static function ResetLoginSession()
	{
		LoginWebPage::ResetSession();
		foreach (Session::ListVariables() as $sKey)
		{
			if (utils::StartsWith($sKey, 'login_'))
			{
				Session::Unset($sKey);
			}
		}
	}
}
