<?php
// Copyright (C) 2010-2021 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Class LoginWebPage
 *
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\Helper\Session;

/**
 * Web page used for displaying the login form
 */

class LoginWebPage extends NiceWebPage
{
	const EXIT_PROMPT = 0;
	const EXIT_HTTP_401 = 1;
	const EXIT_RETURN = 2;
	
	const EXIT_CODE_OK = 0;
	const EXIT_CODE_MISSINGLOGIN = 1;
	const EXIT_CODE_MISSINGPASSWORD = 2;
	const EXIT_CODE_WRONGCREDENTIALS = 3;
	const EXIT_CODE_MUSTBEADMIN = 4;
	const EXIT_CODE_PORTALUSERNOTAUTHORIZED = 5;
	const EXIT_CODE_NOTAUTHORIZED = 6;

	// Login FSM States
	const LOGIN_STATE_START = 'start';                          // Entry state
	const LOGIN_STATE_MODE_DETECTION = 'login mode detection';  // Detect which login plugin to use
	const LOGIN_STATE_READ_CREDENTIALS = 'read credentials';    // Read the credentials
	const LOGIN_STATE_CHECK_CREDENTIALS = 'check credentials';  // Check if the credentials are valid
	const LOGIN_STATE_CREDENTIALS_OK = 'credentials ok';        // User provisioning
	const LOGIN_STATE_USER_OK = 'user ok';                      // Additional check (2FA)
	const LOGIN_STATE_CONNECTED = 'connected';                  // User connected
	const LOGIN_STATE_SET_ERROR = 'prepare for error';	        // Internal state to trigger ERROR state
	const LOGIN_STATE_ERROR = 'error';                          // An error occurred, next state will be NONE

	// Login FSM Returns
	const LOGIN_FSM_RETURN = 0;           // End the FSM OK (connected)
	const LOGIN_FSM_ERROR = 1;        // Error signaled
	const LOGIN_FSM_CONTINUE = 2;     // Continue FSM

	protected static $sHandlerClass = __class__;
	private static $iOnExit;

	public static function RegisterHandler($sClass)
	{
		self::$sHandlerClass = $sClass;
	}

	/**
	 * @return \LoginWebPage
	 */
	public static function NewLoginWebPage()
	{
		return new self::$sHandlerClass;
	}

	protected static $m_sLoginFailedMessage = '';
	
	public function __construct($sTitle = null)
	{
		if ($sTitle === null) {
			$sTitle = Dict::S('UI:Login:Title');
		}

		parent::__construct($sTitle);
		$this->SetStyleSheet();
		$this->no_cache();
		$this->add_xframe_options();
	}
	
	public function SetStyleSheet()
	{
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/login.css');
		$this->add_linked_stylesheet(utils::GetAbsoluteUrlAppRoot().'css/font-awesome/css/all.min.css');
	}

	public static function SetLoginFailedMessage($sMessage)
	{
		self::$m_sLoginFailedMessage = $sMessage;
	}

	/**
	 * @param $oUser
	 * @param array $aProfiles
	 *
	 * @return array
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public static function SynchronizeProfiles(&$oUser, array $aProfiles, $sOrigin)
	{
		$oProfilesSet = $oUser->Get(‘profile_list’);
		//delete old profiles
		$aExistingProfiles = [];
		while ($oProfile = $oProfilesSet->Fetch())
		{
			array_push($aExistingProfiles, $oProfile->Get('profileid'));
			$iArrayKey = array_search($oProfile->Get('profileid'), $aProfiles);
			if (!$iArrayKey)
			{
				$oProfilesSet->RemoveItem($oProfile->Get('profileid'));
			}
			else
			{
				unset($aProfiles[$iArrayKey]);
			}
		}
		//add profiles not already linked with user
		foreach ($aProfiles as $iProfileId)
		{
			$oLink = new URP_UserProfile();
			$oLink->Set('profileid', $iProfileId);
			$oLink->Set('reason', $sOrigin);

			$oProfilesSet->AddItem(MetaModel::NewObject('URP_UserProfile', array('profileid' => $iProfileId, 'reason' => $sOrigin)));
		}
		$oUser->Set('profile_list', $oProfilesSet);
	}

	public function DisplayLoginHeader($bMainAppLogo = false)
	{
		$sVersionShort = Dict::Format('UI:iTopVersion:Short', ITOP_APPLICATION, ITOP_VERSION);
		$sIconUrl = Utils::GetConfig()->Get('app_icon_url');
		$sDisplayIcon = Branding::GetLoginLogoAbsoluteUrl();
		$this->add("<div id=\"login-logo\"><a href=\"".htmlentities($sIconUrl, ENT_QUOTES,
				self::PAGES_CHARSET)."\"><img title=\"$sVersionShort\" src=\"$sDisplayIcon\"></a></div>\n");
	}

	public function DisplayLoginForm($bFailedLogin = false)
	{
		$oTwigContext = new LoginTwigRenderer();
		$aPostedVars = array_merge(array('login_mode', 'loginop'), $oTwigContext->GetPostedVars());

		$sMessage = Dict::S('UI:Login:IdentifyYourself');

		// Error message
		if ($bFailedLogin)
		{
			if (self::$m_sLoginFailedMessage != '')
			{
				$sMessage = self::$m_sLoginFailedMessage;
			}
			else
			{
				$sMessage = Dict::S('UI:Login:IncorrectLoginPassword');
			}
		}

		// Keep the OTHER parameters posted
		$aPreviousPostedVars = array();
		foreach($_POST as $sPostedKey => $postedValue)
		{
			if (!in_array($sPostedKey, $aPostedVars))
			{
				if (is_array($postedValue))
				{
					foreach($postedValue as $sKey => $sValue)
					{
						$sName = "{$sPostedKey}[{$sKey}]";
						$aPreviousPostedVars[$sName] = $sValue;
					}
				}
				else
				{
					$aPreviousPostedVars[$sPostedKey] = $postedValue;
				}
			}
		}

		$aVars = array(
			'bFailedLogin' => $bFailedLogin,
			'sMessage' => $sMessage,
			'aPreviousPostedVars' => $aPreviousPostedVars,
		);
		$aVars = array_merge($aVars, $oTwigContext->GetDefaultVars());

		$oTwigContext->Render($this, 'login.html.twig', $aVars);
	}

	public function DisplayForgotPwdForm($bFailedToReset = false, $sFailureReason = null)
	{
		$sAuthUser = utils::ReadParam('auth_user', '', true, 'raw_data');

		$oTwigContext = new LoginTwigRenderer();
		$aVars = $oTwigContext->GetDefaultVars();
		$aVars['sAuthUser'] = $sAuthUser;
		$aVars['bFailedToReset'] = $bFailedToReset;
		$aVars['sFailureReason'] = $sFailureReason;

		$oTwigContext->Render($this, 'forgotpwdform.html.twig', $aVars);
	}

	protected function ForgotPwdGo()
	{
		$sAuthUser = utils::ReadParam('auth_user', '', true, 'raw_data');

		try
		{
			UserRights::Login($sAuthUser); // Set the user's language (if possible!)
            /** @var UserInternal $oUser */
            $oUser = UserRights::GetUserObject();

			if ($oUser != null)
			{
				if (!MetaModel::IsValidAttCode(get_class($oUser), 'reset_pwd_token'))
				{
					throw new Exception(Dict::S('UI:ResetPwd-Error-NotPossible'));
				}
				if (!$oUser->CanChangePassword())
				{
					throw new Exception(Dict::S('UI:ResetPwd-Error-FixedPwd'));
				}

				$sTo = $oUser->GetResetPasswordEmail(); // throws Exceptions if not allowed
				if ($sTo == '')
				{
					throw new Exception(Dict::S('UI:ResetPwd-Error-NoEmail'));
				}

				// This token allows the user to change the password without knowing the previous one
				$sToken = substr(md5(APPROOT.uniqid()), 0, 16);
				$oUser->Set('reset_pwd_token', $sToken);
				CMDBObject::SetTrackInfo('Reset password');
				$oUser->AllowWrite(true);
				$oUser->DBUpdate();

				$oEmail = new Email();
				$oEmail->SetRecipientTO($sTo);
				$sFrom = MetaModel::GetConfig()->Get('forgot_password_from');
				$oEmail->SetRecipientFrom($sFrom);
				$oEmail->SetSubject(Dict::S('UI:ResetPwd-EmailSubject', $oUser->Get('login')));
				$sResetUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?loginop=reset_pwd&auth_user='.urlencode($oUser->Get('login')).'&token='.urlencode($sToken);
				$oEmail->SetBody(Dict::Format('UI:ResetPwd-EmailBody', $sResetUrl, $oUser->Get('login')));
				$iRes = $oEmail->Send($aIssues, true /* force synchronous exec */);
				switch ($iRes)
				{
					//case EMAIL_SEND_PENDING:
					case EMAIL_SEND_OK:
						break;

					case EMAIL_SEND_ERROR:
					default:
						IssueLog::Error('Failed to send the email with the NEW password for '.$oUser->Get('friendlyname').': '.implode(', ', $aIssues));
						throw new Exception(Dict::S('UI:ResetPwd-Error-Send'));
				}
			}


			$oTwigContext = new LoginTwigRenderer();
			$aVars = $oTwigContext->GetDefaultVars();
			$oTwigContext->Render($this, 'forgotpwdsent.html.twig', $aVars);
		}
		catch(Exception $e)
		{
			$this->DisplayForgotPwdForm(true, $e->getMessage());
		}
	}

	public function DisplayResetPwdForm($sErrorMessage = null)
	{
		$sAuthUser = utils::ReadParam('auth_user', '', false, 'raw_data');
		$sToken = utils::ReadParam('token', '', false, 'raw_data');

		UserRights::Login($sAuthUser); // Set the user's language
		$oUser = UserRights::GetUserObject();

		$oTwigContext = new LoginTwigRenderer();
		$aVars = $oTwigContext->GetDefaultVars();

		$aVars['sAuthUser'] = $sAuthUser;
		$aVars['sToken'] = $sToken;
		$aVars['sErrorMessage'] = $sErrorMessage;

		if (($oUser == null))
		{
			$aVars['bNoUser'] = true;
		}
		else
		{
			$aVars['bNoUser'] = false;
			$aVars['sUserName'] = $oUser->GetFriendlyName();
			$oEncryptedToken = $oUser->Get('reset_pwd_token');

			if (!$oEncryptedToken->CheckPassword($sToken))
			{
				$aVars['bBadToken'] = true;
			}
			else
			{
				$aVars['bBadToken'] = false;
			}
		}

		$oTwigContext->Render($this, 'resetpwdform.html.twig', $aVars);
	}

	public function DoResetPassword()
	{
		$sAuthUser = utils::ReadParam('auth_user', '', false, 'raw_data');
		$sToken = utils::ReadParam('token', '', false, 'raw_data');
		$sNewPwd = utils::ReadPostedParam('new_pwd', '', 'raw_data');

		UserRights::Login($sAuthUser); // Set the user's language
		/** @var \UserLocal $oUser */
		$oUser = UserRights::GetUserObject();

		$oTwigContext = new LoginTwigRenderer();
		$aVars = $oTwigContext->GetDefaultVars();

		$aVars['sAuthUser'] = $sAuthUser;
		$aVars['sToken'] = $sToken;
		if (($oUser == null))
		{
			$aVars['bNoUser'] = true;
		}
		else
		{
			$aVars['bNoUser'] = false;
			$oEncryptedToken = $oUser->Get('reset_pwd_token');

			if (!$oEncryptedToken->CheckPassword($sToken))
			{
				$aVars['bBadToken'] = true;
			}
			else
			{
				$aVars['bBadToken'] = false;
				// Trash the token and change the password
				$oUser->Set('reset_pwd_token', new ormPassword());
				$oUser->AllowWrite(true);
				$oUser->SetPassword($sNewPwd); // Does record the change into the DB
				$aVars['sUrl'] = utils::GetAbsoluteUrlAppRoot();
			}
		}

		$oTwigContext->Render($this, 'resetpwddone.html.twig', $aVars);
	}

	public function DisplayChangePwdForm($bFailedLogin = false, $sIssue = null)
	{
		$oTwigContext = new LoginTwigRenderer();
		$aVars = $oTwigContext->GetDefaultVars();
		$aVars['bFailedLogin'] = $bFailedLogin;
		$aVars['sIssue'] = $sIssue;
		$oTwigContext->Render($this, 'changepwdform.html.twig', $aVars);
	}

	public function DisplayLogoutPage($bPortal, $sTitle = null)
	{
		$sUrl = utils::GetAbsoluteUrlAppRoot();
		$sUrl .= $bPortal ? 'portal/' : 'pages/UI.php';
		$sTitle = empty($sTitle) ? Dict::S('UI:LogOff:ThankYou') : $sTitle;
		$sMessage = Dict::S('UI:LogOff:ClickHereToLoginAgain');

		$oTwigContext = new LoginTwigRenderer();
		$aVars = $oTwigContext->GetDefaultVars();
		$aVars['sUrl'] = $sUrl;
		$aVars['sTitle'] = $sTitle;
		$aVars['sMessage'] = $sMessage;

		$oTwigContext->Render($this, 'logout.html.twig', $aVars);
		$this->output();
	}

	public static function ResetSession()
	{
		// Unset all of the session variables.
		Session::Unset('auth_user');
		Session::Unset('login_state');
		Session::Unset('can_logoff');
		Session::Unset('archive_mode');
		Session::Unset('impersonate_user');
		UserRights::_ResetSessionCache();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
	}

	static function SecureConnectionRequired()
	{
		return MetaModel::GetConfig()->GetSecureConnectionRequired();
	}

	/**
	 * Guess if a string looks like an UTF-8 string based on some ranges of multi-bytes encoding
	 * @param string $sString
	 * @return bool True if the string contains some typical UTF-8 multi-byte sequences
	 */
	static function LooksLikeUTF8($sString)
	{
		return preg_match('%(?:
        			[\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        			|\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        			|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
        			|\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        			|\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        			|[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
        			|\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        	)+%xs', $sString);
	}
	/**
	 * Attempt a login
	 *
	 * @param int iOnExit What action to take if the user is not logged on (one of the class constants EXIT_...)
	 *
	 * @return int|void One of the class constants EXIT_CODE_...
	 * @throws \Exception
	 */
	protected static function Login($iOnExit)
	{
		self::$iOnExit = $iOnExit;
		if (self::SecureConnectionRequired() && !utils::IsConnectionSecure())
		{
			// Non secured URL... request for a secure connection
			throw new Exception('Secure connection required!');
		}
		$bLoginDebug = MetaModel::GetConfig()->Get('login_debug');

		if (Session::Get('login_state') == self::LOGIN_STATE_ERROR)
		{
			Session::Set('login_state', self::LOGIN_STATE_START);
		}
		$sLoginState = Session::Get('login_state');

		$sSessionLog = '';
		if ($bLoginDebug)
		{
			IssueLog::Info("---------------------------------");
			IssueLog::Info($_SERVER['REQUEST_URI']);
			IssueLog::Info("--> Entering Login FSM with state: [$sLoginState]");
			$sSessionLog = session_id().' '.utils::GetSessionLog();
			IssueLog::Info("SESSION: $sSessionLog");
		}

		$iErrorCode = self::EXIT_CODE_OK;

		// Finite state machine loop
		while (true)
		{
			try
			{
				$aLoginPlugins = self::GetLoginPluginList();
				if (empty($aLoginPlugins))
				{
					throw new Exception("Missing login classes");
				}

				/** @var iLoginFSMExtension $oLoginFSMExtensionInstance */
				foreach ($aLoginPlugins as $oLoginFSMExtensionInstance)
				{
					if ($bLoginDebug)
					{
						$sCurrSessionLog = session_id().' '.utils::GetSessionLog();
						if ($sCurrSessionLog != $sSessionLog)
						{
							$sSessionLog = $sCurrSessionLog;
							IssueLog::Info("SESSION: $sSessionLog");
						}
						IssueLog::Info("Login: state: [$sLoginState] call: ".get_class($oLoginFSMExtensionInstance));
					}
					$iResponse = $oLoginFSMExtensionInstance->LoginAction($sLoginState, $iErrorCode);
					if ($iResponse == self::LOGIN_FSM_RETURN)
					{
						Session::WriteClose();
						return $iErrorCode; // Asked to exit FSM, generally login OK
					}
					if ($iResponse == self::LOGIN_FSM_ERROR)
					{
						$sLoginState = self::LOGIN_STATE_SET_ERROR; // Next state will be error
						// An error was detected, skip the other plugins turn
						break;
					}
					// The plugin has nothing to do for this state, continue to the next plugin
				}

				// Every plugin has nothing else to do in this state, go forward
				$sLoginState = self::AdvanceLoginFSMState($sLoginState);
				Session::Set('login_state', $sLoginState);
			}
			catch (Exception $e)
			{
				IssueLog::Error($e->getTraceAsString());
				static::ResetSession();
				die($e->getMessage());
			}
		}
	}

	/**
	 * Get plugins list ordered by config 'allowed_login_types'
	 * Use the login mode to filter plugins
	 *
	 * @param string $sInterface 'iLoginFSMExtension' or 'iLogoutExtension'
	 * @param bool $bFilterWithMode if false do not filter the plugin list with login mode
	 *
	 * @return array of plugins
	 */
	public static function GetLoginPluginList($sInterface = 'iLoginFSMExtension', $bFilterWithMode = true)
	{
		$aAllPlugins = array();

		if ($bFilterWithMode)
		{
			$sCurrentLoginMode = Session::Get('login_mode', '');
		}
		else
		{
			$sCurrentLoginMode = '';
		}

		/** @var iLoginExtension $oLoginExtensionInstance */
		foreach (MetaModel::EnumPlugins($sInterface) as $oLoginExtensionInstance)
		{
			$aLoginModes = $oLoginExtensionInstance->ListSupportedLoginModes();
			$aLoginModes = (is_array($aLoginModes) ? $aLoginModes : array());
			foreach ($aLoginModes as $sLoginMode)
			{
				// Keep only the plugins for the current login mode + before + after
				if (empty($sCurrentLoginMode) || ($sLoginMode == $sCurrentLoginMode) || ($sLoginMode == 'before') || ($sLoginMode == 'after'))
				{
					if (!isset($aAllPlugins[$sLoginMode]))
					{
						$aAllPlugins[$sLoginMode] = array();
					}
					$aAllPlugins[$sLoginMode][] = $oLoginExtensionInstance;
					break; // Stop here to avoid registering a plugin twice
				}
			}
		}

		// Order and filter by the config list of allowed types (allowed_login_types)
		$aAllowedLoginModes = array_merge(array('before'), MetaModel::GetConfig()->GetAllowedLoginTypes(), array('after'));
		$aPlugins = array();
		foreach ($aAllowedLoginModes as $sAllowedMode)
		{
			if (isset($aAllPlugins[$sAllowedMode]))
			{
				$aPlugins = array_merge($aPlugins, $aAllPlugins[$sAllowedMode]);
			}
		}
		return $aPlugins;
	}

	/**
	 * Advance Login Finite State Machine to the next step
	 *
	 * @param string $sLoginState Current step
	 *
	 * @return string next step
	 */
	private static function AdvanceLoginFSMState($sLoginState)
	{
		switch ($sLoginState)
		{
			case self::LOGIN_STATE_START:
				return self::LOGIN_STATE_MODE_DETECTION;

			case self::LOGIN_STATE_MODE_DETECTION:
				return self::LOGIN_STATE_READ_CREDENTIALS;

			case self::LOGIN_STATE_READ_CREDENTIALS:
				return self::LOGIN_STATE_CHECK_CREDENTIALS;

			case self::LOGIN_STATE_CHECK_CREDENTIALS:
				return self::LOGIN_STATE_CREDENTIALS_OK;

			case self::LOGIN_STATE_CREDENTIALS_OK:
				return self::LOGIN_STATE_USER_OK;

			case self::LOGIN_STATE_USER_OK:
				return self::LOGIN_STATE_CONNECTED;

			case self::LOGIN_STATE_CONNECTED:
			case self::LOGIN_STATE_ERROR:
				return self::LOGIN_STATE_START;

			case self::LOGIN_STATE_SET_ERROR:
				return self::LOGIN_STATE_ERROR;
		}

		// Default reset to NONE
		return self::LOGIN_STATE_START;
	}

	/**
	 * Login API: Check that credentials correspond to a valid user
	 * Used only during login process when the password is known
	 *
	 * @api
	 *
	 * @param string $sAuthUser
	 * @param string $sAuthPassword
	 * @param string $sAuthentication ('internal' or 'external')
	 *
	 * @return bool (true if User OK)
	 *
	 */
	public static function CheckUser($sAuthUser, $sAuthPassword = '', $sAuthentication = 'external')
	{
		$oUser = self::FindUser($sAuthUser, true, ucfirst(strtolower($sAuthentication)));
		if (is_null($oUser))
		{
			return false;
		}

		return $oUser->CheckCredentials($sAuthPassword);
	}

	/**
	 * Login API: Store User info in the session when connection is OK
	 *
	 * @api
	 *
	 * @param $sAuthUser
	 * @param $sAuthentication
	 * @param $sLoginMode
	 *
	 * @throws ArchivedObjectException
	 * @throws CoreCannotSaveObjectException
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws CoreWarning
	 * @throws MySQLException
	 * @throws OQLException
	 * @throws \Exception
	 */
	public static function OnLoginSuccess($sAuthUser, $sAuthentication, $sLoginMode)
	{
		// User is Ok, let's save it in the session and proceed with normal login
		$bLoginSuccess = UserRights::Login($sAuthUser, $sAuthentication); // Login & set the user's language
		if (!$bLoginSuccess)
		{
			throw new Exception("Bad user");
		}
		if (MetaModel::GetConfig()->Get('log_usage')) {
			$oLog = new EventLoginUsage();
			$oLog->Set('userinfo', UserRights::GetUser());
			$oLog->Set('user_id', UserRights::GetUserObject()->GetKey());
			$oLog->Set('message', 'Successful login');
			$oLog->DBInsertNoReload();
		}

		Session::Set('auth_user', $sAuthUser);
		Session::Set('login_mode', $sLoginMode);
		UserRights::_InitSessionCache();
	}

	/**
	 * Login API: Check that an already logger User is still valid
	 *
	 * @api
	 *
	 * @param int $iErrorCode
	 *
	 * @return int LOGIN_FSM_RETURN_OK or LOGIN_FSM_RETURN_ERROR
	 */
	public static function CheckLoggedUser(&$iErrorCode)
	{
		if (Session::IsSet('auth_user'))
		{
			// Already authenticated
			$bRet = UserRights::Login(Session::Get('auth_user')); // Login & set the user's language
			if ($bRet)
			{
				$iErrorCode = self::EXIT_CODE_OK;
				return self::LOGIN_FSM_RETURN;
			}
		}
		// The user account is no longer valid/enabled
		$iErrorCode = self::EXIT_CODE_WRONGCREDENTIALS;

		return self::LOGIN_FSM_ERROR;
	}

	/**
	 * Exit with an HTTP 401 error
	 */
	public static function HTTP401Error()
	{
		header('WWW-Authenticate: Basic realm="'.Dict::Format('UI:iTopVersion:Short', ITOP_APPLICATION, ITOP_VERSION));
		header('HTTP/1.0 401 Unauthorized');
		header('Content-type: text/html; charset='.self::PAGES_CHARSET);
		// Note: displayed when the user will click on Cancel
		echo '<p><strong>'.Dict::S('UI:Login:Error:AccessRestricted').'</strong></p>';
		exit;
	}

	public static function SetLoginModeAndReload($sNewLoginMode)
	{
		if (Session::Get('login_mode') == $sNewLoginMode)
		{
			return;
		}
		Session::Set('login_mode', $sNewLoginMode);
		self::HTTPReload();
	}

	public static function HTTPReload()
	{
		$sOriginURL = utils::GetCurrentAbsoluteUrl();
		if (!utils::StartsWith($sOriginURL, utils::GetAbsoluteUrlAppRoot()))
		{
			// If the found URL does not start with the configured AppRoot URL
			$sOriginURL = utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
		}
		self::HTTPRedirect($sOriginURL);
	}

	public static function HTTPRedirect($sURL)
	{
		header('HTTP/1.1 307 Temporary Redirect');
		header('Location: '.$sURL);
		exit;
	}


	/**
	 * Provisioning API: Find a User
	 *
	 * @api
	 *
	 * @param string $sAuthUser
	 * @param bool $bMustBeValid
	 * @param string $sType
	 *
	 * @return \User|null
	 */
	public static function FindUser($sAuthUser, $bMustBeValid = true, $sType = 'External')
	{
		try
		{
			$aArgs = array('login' => $sAuthUser);
			$sUserClass = "User$sType";
			$oSearch = DBObjectSearch::FromOQL("SELECT $sUserClass WHERE login = :login");
			if ($bMustBeValid)
			{
				$oSearch->AddCondition('status', 'enabled');
			}
			$oSet = new DBObjectSet($oSearch, array(), $aArgs);
			if ($oSet->CountExceeds(0))
			{
				/** @var User $oUser */
				$oUser = $oSet->Fetch();

				return $oUser;
			}
		}
		catch (Exception $e)
		{
			IssueLog::Error($e->getMessage());
		}
		return null;
	}

	/**
 	 * Provisioning API: Find a Person by email
	 *
	 * @api
	 *
	 * @param string $sEmail
	 *
	 * @return \Person|null
	 */
	public static function FindPerson($sEmail)
	{
		/** @var \Person $oPerson */
		$oPerson = null;
		try
		{
			$oSearch = new DBObjectSearch('Person');
			$oSearch->AddCondition('email', $sEmail);
			$oSet = new DBObjectSet($oSearch);
			if ($oSet->CountExceeds(1))
			{
				throw new Exception(Dict::S('UI:Login:Error:MultipleContactsHaveSameEmail'));
			}
			$oPerson = $oSet->Fetch();
		}
		catch (Exception $e)
		{
			IssueLog::Error($e->getMessage());
		}
		return $oPerson;
	}

	/**
	 * Provisioning API: Create a person
	 *
	 * @api
	 *
	 * @param string $sFirstName
	 * @param string $sLastName
	 * @param string $sEmail
	 * @param string $sOrganization
	 * @param array $aAdditionalParams
	 *
	 * @return \Person
	 */
	public static function ProvisionPerson($sFirstName, $sLastName, $sEmail, $sOrganization, $aAdditionalParams = array())
	{
		/** @var Person $oPerson */
		$oPerson = null;
		try
		{
			CMDBObject::SetTrackOrigin('custom-extension');
			$sInfo = 'External User provisioning';
			if (Session::IsSet('login_mode'))
			{
				$sInfo .= " (".Session::Get('login_mode').")";
			}
			CMDBObject::SetTrackInfo($sInfo);

			$oPerson = MetaModel::NewObject('Person');
			$oPerson->Set('first_name', $sFirstName);
			$oPerson->Set('name', $sLastName);
			$oPerson->Set('email', $sEmail);
			$oOrg = MetaModel::GetObjectByName('Organization', $sOrganization, false);
			if (is_null($oOrg))
			{
				throw new Exception(Dict::S('UI:Login:Error:WrongOrganizationName'));
			}
			$oPerson->Set('org_id', $oOrg->GetKey());
			foreach ($aAdditionalParams as $sAttCode => $sValue)
			{
				$oPerson->Set($sAttCode, $sValue);
			}
			$oPerson->DBInsert();
		}
		catch (Exception $e)
		{
			IssueLog::Error($e->getMessage());
		}
		return $oPerson;
	}

	/**
	 * Provisioning API: Create or update a User
	 *
	 * @api
	 *
	 * @param string $sAuthUser
	 * @param Person $oPerson
	 * @param array $aRequestedProfiles profiles to add to the new user
	 *
	 * @return \UserExternal|null
	 */
	public static function ProvisionUser($sAuthUser, $oPerson, $aRequestedProfiles)
	{
		if (!MetaModel::IsValidClass('URP_Profiles'))
		{
			IssueLog::Error("URP_Profiles is not a valid class. Automatic creation of Users is not supported in this context, sorry.");
			return null;
		}

		/** @var UserExternal $oUser */
		$oUser = null;
		try
		{
			CMDBObject::SetTrackOrigin('custom-extension');
			$sInfo = 'External User provisioning';
			if (Session::IsSet('login_mode'))
			{
				$sInfo .= " (".Session::Get('login_mode').")";
			}
			CMDBObject::SetTrackInfo($sInfo);

			$oUser = MetaModel::GetObjectByName('UserExternal', $sAuthUser, false);
			if (is_null($oUser))
			{
				$oUser = MetaModel::NewObject('UserExternal');
				$oUser->Set('login', $sAuthUser);
				$oUser->Set('contactid', $oPerson->GetKey());
				$oUser->Set('language', MetaModel::GetConfig()->GetDefaultLanguage());
			}

			// read all the existing profiles
			$oProfilesSearch = new DBObjectSearch('URP_Profiles');
			$oProfilesSet = new DBObjectSet($oProfilesSearch);
			$aAllProfiles = array();
			while ($oProfile = $oProfilesSet->Fetch())
			{
				$aAllProfiles[strtolower($oProfile->GetName())] = $oProfile->GetKey();
			}

			$aProfiles = array();
			foreach ($aRequestedProfiles as $sRequestedProfile)
			{
				$sRequestedProfile = strtolower($sRequestedProfile);
				if (isset($aAllProfiles[$sRequestedProfile]))
				{
					$aProfiles[] = $aAllProfiles[$sRequestedProfile];
				}
			}

			if (empty($aProfiles))
			{
				throw new Exception(Dict::S('UI:Login:Error:NoValidProfiles'));
			}

			// Now synchronize the profiles
			$sOrigin = 'External User provisioning';
			if (Session::IsSet('login_mode'))
			{
				$sOrigin .= " (".Session::Get('login_mode').")";
			}
			$aExistingProfiles = self::SynchronizeProfiles($oUser, $aProfiles, $sOrigin);
			if ($oUser->IsModified())
			{
				$oUser->DBWrite();
			}
		}
		catch (Exception $e)
		{
			IssueLog::Error($e->getMessage());
		}

		return $oUser;
	}

	/**
	 * Overridable: depending on the user, head toward a dedicated portal
	 * @param string|null $sRequestedPortalId
	 * @param int $iOnExit How to complete the call: redirect or return a code
	 */	 
	protected static function ChangeLocation($sRequestedPortalId = null, $iOnExit = self::EXIT_PROMPT)
	{
		$ret = call_user_func(array(self::$sHandlerClass, 'Dispatch'), $sRequestedPortalId);
		if ($ret === true)
		{
			return self::EXIT_CODE_OK;
		}
		else if($ret === false)
		{
			throw new Exception('Nowhere to go??');
		}
		else
		{
			if ($iOnExit == self::EXIT_RETURN)
			{
				return self::EXIT_CODE_PORTALUSERNOTAUTHORIZED;
			}
			else
			{
				// No rights to be here, redirect to the portal
				header('Location: '.$ret);
				die();
			}
		}
		return self::EXIT_CODE_OK;
	}

	/**
	 * Check if the user is already authentified, if yes, then performs some additional validations:
	 * - if $bMustBeAdmin is true, then the user must be an administrator, otherwise an error is displayed
	 * - if $bIsAllowedToPortalUsers is false and the user has only access to the portal, then the user is redirected
	 * to the portal
	 *
	 * @param bool $bMustBeAdmin Whether or not the user must be an admin to access the current page
	 * @param bool $bIsAllowedToPortalUsers Whether or not the current page is considered as part of the portal
	 * @param int iOnExit What action to take if the user is not logged on (one of the class constants EXIT_...)
	 *
	 * @return int|mixed|string
	 * @throws \Exception
	 */
	static function DoLogin($bMustBeAdmin = false, $bIsAllowedToPortalUsers = false, $iOnExit = self::EXIT_PROMPT)
	{
		$sRequestedPortalId = $bIsAllowedToPortalUsers ? 'legacy_portal' : 'backoffice';
		return self::DoLoginEx($sRequestedPortalId, $bMustBeAdmin, $iOnExit);
	}

	/**
	 * Check if the user is already authentified, if yes, then performs some additional validations to redirect towards
	 * the desired "portal"
	 *
	 * @param string|null $sRequestedPortalId The requested "portal" interface, null for any
	 * @param bool $bMustBeAdmin Whether or not the user must be an admin to access the current page
	 * @param int iOnExit What action to take if the user is not logged on (one of the class constants EXIT_...)
	 *
	 * @return int|mixed|string
	 * @throws \Exception
	 */
	static function DoLoginEx($sRequestedPortalId = null, $bMustBeAdmin = false, $iOnExit = self::EXIT_PROMPT)
	{
		$operation = utils::ReadParam('loginop', '');
	
		$sMessage = self::HandleOperations($operation); // May exit directly
	
		$iRet = self::Login($iOnExit);
		if ($iRet == self::EXIT_CODE_OK)
		{
			if ($bMustBeAdmin && !UserRights::IsAdministrator())
			{
				if ($iOnExit == self::EXIT_RETURN)
				{
					return self::EXIT_CODE_MUSTBEADMIN;
				}
				else
				{
					require_once(APPROOT.'/setup/setuppage.class.inc.php');
					$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
					$oP->add("<h1>".Dict::S('UI:Login:Error:AccessAdmin')."</h1>\n");
					$oP->p("<a href=\"".utils::GetAbsoluteUrlAppRoot()."pages/logoff.php\">".Dict::S('UI:LogOffMenu')."</a>");
					$oP->output();
					exit;
				}
			}
			$iRet = call_user_func(array(self::$sHandlerClass, 'ChangeLocation'), $sRequestedPortalId, $iOnExit);
		}
		if ($iOnExit == self::EXIT_RETURN)
		{
			return $iRet;
		}
		else
		{
			return $sMessage;
		}
	}	
	protected static function HandleOperations($operation)
	{
		$sMessage = ''; // most of the operations never return, but some can return a message to be displayed
		if ($operation == 'logoff')
		{
			self::ResetSession();
			$oPage = self::NewLoginWebPage();
			$oPage->DisplayLoginForm(false /* not a failed attempt */);
			$oPage->output();
			exit;
		}
		else if ($operation == 'forgot_pwd')
		{
			$oPage = self::NewLoginWebPage();
			$oPage->DisplayForgotPwdForm();
			$oPage->output();
			exit;
		}
		else if ($operation == 'forgot_pwd_go')
		{
			$oPage = self::NewLoginWebPage();
			$oPage->ForgotPwdGo();
			$oPage->output();
			exit;
		}
		else if ($operation == 'reset_pwd')
		{
			$oPage = self::NewLoginWebPage();
			$oPage->DisplayResetPwdForm();
			$oPage->output();
			exit;
		}
		else if ($operation == 'do_reset_pwd')
		{

			try {
				$oPage = self::NewLoginWebPage();
				$oPage->DoResetPassword();
			}
			catch (CoreCannotSaveObjectException $e)
			{
				$oPage = self::NewLoginWebPage();
				$oPage->DisplayResetPwdForm($e->getIssue());
			}

			$oPage->output();
			exit;
		}
		else if ($operation == 'change_pwd')
		{
			if (Session::IsSet('auth_user'))
			{
				$sAuthUser = Session::Get('auth_user');
				$sIssue = Session::Get('pwd_issue');
				Session::Unset('pwd_issue');
				$bFailedLogin = ($sIssue != null); // Force the "failed login" flag to display the "issue" message

				UserRights::Login($sAuthUser); // Set the user's language
				$oPage = self::NewLoginWebPage();
				$oPage->DisplayChangePwdForm($bFailedLogin, $sIssue);
				$oPage->output();
				exit;
			}
		}
		else if ($operation == 'check_pwd_policy')
		{
			$sAuthUser = Session::Get('auth_user');
			UserRights::Login($sAuthUser); // Set the user's language

			$aPwdMap = array();

			foreach (array('new_pwd', 'retype_new_pwd') as $postedPwd)
			{
				$oUser = new UserLocal();
				$oUser->ValidatePassword($_POST[$postedPwd]);

				$aPwdMap[$postedPwd]['isValid'] = $oUser->IsPasswordValid();
				$aPwdMap[$postedPwd]['message'] = $oUser->getPasswordValidityMessage();
			}
			echo json_encode($aPwdMap);
			die();
		}
		if ($operation == 'do_change_pwd')
		{
			if (Session::IsSet('auth_user'))
			{
				$sAuthUser = Session::Get('auth_user');
				UserRights::Login($sAuthUser); // Set the user's language
				$sOldPwd = utils::ReadPostedParam('old_pwd', '', 'raw_data');
				$sNewPwd = utils::ReadPostedParam('new_pwd', '', 'raw_data');

				try
				{
					if (UserRights::CanChangePassword() && ((!UserRights::CheckCredentials($sAuthUser, $sOldPwd)) || (!UserRights::ChangePassword($sOldPwd, $sNewPwd))))
					{
						$oPage = self::NewLoginWebPage();
						$oPage->DisplayChangePwdForm(true); // old pwd was wrong
						$oPage->output();
						exit;
					}
				}
				catch (CoreCannotSaveObjectException $e)
				{
					$oPage = self::NewLoginWebPage();
					$oPage->DisplayChangePwdForm(true, $e->getIssue()); // password policy was not met.
					$oPage->output();
					exit;
				}
				$sMessage = Dict::S('UI:Login:PasswordChanged');
			}
		}
		return $sMessage;
	}
	
	protected static function Dispatch($sRequestedPortalId)
	{
		if ($sRequestedPortalId === null) return true; // allowed to any portal => return true
		
		$aPortalsConf = PortalDispatcherData::GetData();
		$aDispatchers = array();
		foreach($aPortalsConf as $sPortalId => $aConf)
		{
			$sHandlerClass = $aConf['handler'];
			$aDispatchers[$sPortalId] = new $sHandlerClass($sPortalId);
		}
		
		if (array_key_exists($sRequestedPortalId, $aDispatchers) && $aDispatchers[$sRequestedPortalId]->IsUserAllowed())
		{
			return true;
		}
		foreach($aDispatchers as $sPortalId => $oDispatcher)
		{
			if ($oDispatcher->IsUserAllowed()) return $oDispatcher->GetUrl();
		}
		return false; // nothing matched !!
	}

	/**
	 * @return mixed
	 */
	public static function getIOnExit()
	{
		return self::$iOnExit;
	}

} // End of class
