<?php

/**
 * Login finite state machine
 *
 * Execute the action corresponding to the current login state.
 *
 *  * If a page is displayed, the action must exit at this point
 *  * if LoginWebPage::LOGIN_FSM_RETURN_ERROR is returned $iErrorCode must be set
 *  * if LoginWebPage::LOGIN_FSM_RETURN_OK is returned then the login is OK and terminated
 *  * if LoginWebPage::LOGIN_FSM_CONTINUE is returned then the FSM will proceed to next plugin or to next state
 *
 * @api
 * @package LoginExtensibilityAPI
 * @since 2.7.0
 */
abstract class AbstractLoginFSMExtension implements iLoginFSMExtension
{
	/**
	 * @inheritDoc
	 */
	abstract public function ListSupportedLoginModes();

	/**
	 * @inheritDoc
	 */
	public function LoginAction($sLoginState, &$iErrorCode)
	{
		switch ($sLoginState) {
			case LoginWebPage::LOGIN_STATE_START:
				return $this->OnStart($iErrorCode);

			case LoginWebPage::LOGIN_STATE_MODE_DETECTION:
				return $this->OnModeDetection($iErrorCode);

			case LoginWebPage::LOGIN_STATE_READ_CREDENTIALS:
				return $this->OnReadCredentials($iErrorCode);

			case LoginWebPage::LOGIN_STATE_CHECK_CREDENTIALS:
				return $this->OnCheckCredentials($iErrorCode);

			case LoginWebPage::LOGIN_STATE_CREDENTIALS_OK:
				return $this->OnCredentialsOK($iErrorCode);

			case LoginWebPage::LOGIN_STATE_USER_OK:
				return $this->OnUsersOK($iErrorCode);

			case LoginWebPage::LOGIN_STATE_CONNECTED:
				return $this->OnConnected($iErrorCode);

			case LoginWebPage::LOGIN_STATE_ERROR:
				return $this->OnError($iErrorCode);
		}

		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Initialization
	 *
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_CONTINUE
	 * @api
	 */
	protected function OnStart(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Detect login mode explicitly without respecting configured order (legacy mode)
	 * In most case do nothing here
	 *
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_CONTINUE
	 * @api
	 */
	protected function OnModeDetection(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Obtain the credentials either if login mode is empty or set to yours.
	 * This step can be called multiple times by the FSM:
	 * for example:
	 * 1 - display login form
	 * 2 - read the values posted by the user (store that in session)
	 *
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_CONTINUE
	 * @api
	 */
	protected function OnReadCredentials(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Control the validity of the data from the session
	 * Automatic user provisioning can be done here
	 *
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_CONTINUE
	 * @api
	 */
	protected function OnCheckCredentials(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_CONTINUE
	 * @api
	 */
	protected function OnCredentialsOK(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_CONTINUE
	 * @api
	 */
	protected function OnUsersOK(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_CONTINUE
	 * @api
	 */
	protected function OnConnected(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * @param int $iErrorCode (see LoginWebPage::EXIT_CODE_...)
	 *
	 * @return int LoginWebPage::LOGIN_FSM_RETURN_ERROR, LoginWebPage::LOGIN_FSM_RETURN_OK or LoginWebPage::LOGIN_FSM_CONTINUE
	 * @api
	 */
	protected function OnError(&$iErrorCode)
	{
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}
}
