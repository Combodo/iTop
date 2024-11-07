<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     https://www.combodo.com/documentation/combodo-software-license.html
 *
 */

namespace Combodo\iTop\Cas;

use AbstractLoginFSMExtension;
use CMDBObject;
use Combodo\iTop\Application\Helper\Session;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use iLoginUIExtension;
use iLogoutExtension;
use LoginBlockExtension;
use LoginTwigContext;
use LoginWebPage;
use MetaModel;
use phpCAS;
use User;
use UserExternal;
use utils;

/**
 * Class CASLoginExtension
 */
class CASLoginExtension extends AbstractLoginFSMExtension implements iLogoutExtension, iLoginUIExtension
{
	const LOGIN_MODE = 'cas';

	/**
	 * Return the list of supported login modes for this plugin
	 *
	 * @return array of supported login modes
	 */
	public function ListSupportedLoginModes()
	{
		return array(static::LOGIN_MODE);
	}

	protected function OnStart(&$iErrorCode)
	{
		Session::Unset('phpCAS');
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnReadCredentials(&$iErrorCode)
	{
		if (LoginWebPage::getIOnExit() === LoginWebPage::EXIT_RETURN) {
			// Not allowed if not already connected
			return LoginWebPage::LOGIN_FSM_CONTINUE;
		}

		if (empty(Session::Get('login_mode')) || Session::Get('login_mode') == static::LOGIN_MODE)
		{
			static::InitCASClient();
			if (phpCAS::isAuthenticated())
			{
				Session::Set('login_mode', static::LOGIN_MODE);
				Session::Set('auth_user', phpCAS::getUser());
				Session::Unset('login_will_redirect');
			}
			else
			{
				if (!Session::IsSet('login_will_redirect'))
				{
					Session::Set('login_will_redirect', true);
				}
				else
				{
					Session::Unset('login_will_redirect');
					$iErrorCode = LoginWebPage::EXIT_CODE_MISSINGLOGIN;
					return LoginWebPage::LOGIN_FSM_ERROR;
				}
				Session::Set('login_mode', static::LOGIN_MODE);
				phpCAS::forceAuthentication(); // Redirect to CAS and exit
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCheckCredentials(&$iErrorCode)
	{
		if (Session::Get('login_mode') == static::LOGIN_MODE)
		{
			if (!Session::IsSet('auth_user'))
			{
				$iErrorCode = LoginWebPage::EXIT_CODE_WRONGCREDENTIALS;
				return LoginWebPage::LOGIN_FSM_ERROR;
			}
			if (Config::Get('cas_user_synchro' ))
			{
				self::DoUserProvisioning(Session::Get('auth_user'));
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnCredentialsOK(&$iErrorCode)
	{
		if (Session::Get('login_mode') == static::LOGIN_MODE)
		{
			$sAuthUser = Session::Get('auth_user');
			if (!LoginWebPage::CheckUser($sAuthUser))
			{
				$iErrorCode = LoginWebPage::EXIT_CODE_NOTAUTHORIZED;
				return LoginWebPage::LOGIN_FSM_ERROR;
			}
			LoginWebPage::OnLoginSuccess($sAuthUser, 'external', Session::Get('login_mode'));
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnError(&$iErrorCode)
	{
		if (Session::Get('login_mode') == static::LOGIN_MODE)
		{
			Session::Unset('phpCAS');
			if (LoginWebPage::getIOnExit() === LoginWebPage::EXIT_RETURN) {
				// don't display the login page
				return LoginWebPage::LOGIN_FSM_CONTINUE;
			}
			if ($iErrorCode != LoginWebPage::EXIT_CODE_MISSINGLOGIN)
			{
				$oLoginWebPage = new LoginWebPage();
				$oLoginWebPage->DisplayLogoutPage(false, Dict::S('CAS:Error:UserNotAllowed'));
				exit();
			}
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	protected function OnConnected(&$iErrorCode)
	{
		if (Session::Get('login_mode') == static::LOGIN_MODE)
		{
			Session::Set('can_logoff', true);
			return LoginWebPage::CheckLoggedUser($iErrorCode);
		}
		return LoginWebPage::LOGIN_FSM_CONTINUE;
	}

	/**
	 * Execute all actions to log out properly
	 */
	public function LogoutAction()
	{
		$sCASLogoutUrl = Config::Get('cas_logout_redirect_service');
		if (empty($sCASLogoutUrl))
		{
			$sCASLogoutUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
		}
		static::InitCASClient();
		phpCAS::logoutWithRedirectService($sCASLogoutUrl); // Redirects to the CAS logout page
	}

	private static function InitCASClient()
	{
		$bCASDebug = Config::Get('cas_debug');
		if ($bCASDebug)
		{
			phpCAS::setLogger(new CASLogger(APPROOT.'log/cas.log'));
		}

		// Initialize phpCAS
		$sCASVersion = Config::Get('cas_version');
		$sCASHost = Config::Get('cas_host');
		$iCASPort = Config::Get('cas_port');
		$sCASContext = Config::Get('cas_context');
		$sServiceBaseURL = Config::Get('service_base_url', self::GetServiceBaseURL());
		phpCAS::client($sCASVersion, $sCASHost, $iCASPort, $sCASContext, $sServiceBaseURL, false /* session already started */);
		$sCASCACertPath = Config::Get('cas_server_ca_cert_path');
		if (empty($sCASCACertPath))
		{
			// If no certificate authority is provided, do not attempt to validate
			// the server's certificate
			// THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
			// VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
			phpCAS::setNoCasServerValidation();
		}
		else
		{
			phpCAS::setCasServerCACert($sCASCACertPath);
		}
	}

	private static function GetServiceBaseURL()
	{
		$protocol = $_SERVER['REQUEST_SCHEME'];
		$protocol .= '://';
		if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
			// explode the host list separated by comma and use the first host
			$hosts = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST']);
			// see rfc7239#5.3 and rfc7230#2.7.1: port is in HTTP_X_FORWARDED_HOST if non default
			return $protocol . $hosts[0];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_SERVER'])) {
			$server_url = $_SERVER['HTTP_X_FORWARDED_SERVER'];
		} else {
			if (empty($_SERVER['SERVER_NAME'])) {
				$server_url = $_SERVER['HTTP_HOST'];
			} else {
				$server_url = $_SERVER['SERVER_NAME'];
			}
		}
		if (!strpos($server_url, ':')) {
			if (empty($_SERVER['HTTP_X_FORWARDED_PORT'])) {
				$server_port = $_SERVER['SERVER_PORT'];
			} else {
				$ports = explode(',', $_SERVER['HTTP_X_FORWARDED_PORT']);
				$server_port = $ports[0];
			}

			$server_url .= ':';
			$server_url .= $server_port;
		}
		return $protocol . $server_url;
	}

	private function DoUserProvisioning($sLogin)
	{
		$bCASUserSynchro = Config::Get('cas_user_synchro');
		if (!$bCASUserSynchro)
		{
			return;
		}

		CMDBObject::SetTrackInfo('CAS/LDAP Synchro');
		$oUser = LoginWebPage::FindUser($sLogin, false);
		if ($oUser)
		{
			if ($oUser->Get('status') == 'enabled')
			{
				CASUserProvisioning::UpdateUser($oUser);
			}
			return;
		}
		CASUserProvisioning::CreateUser($sLogin, '', 'external');
	}

	/**
	 * @return LoginTwigContext
	 */
	public function GetTwigContext()
	{
		$oLoginContext = new LoginTwigContext();
		$oLoginContext->SetLoaderPath(APPROOT.'env-'.utils::GetCurrentEnvironment().'/authent-cas/view');

		$aData = array(
			'sLoginMode' => static::LOGIN_MODE,
			'sLabel' => Dict::S('CAS:Login:SignIn'),
			'sTooltip' => Dict::S('CAS:Login:SignInTooltip'),
		);
		$oLoginContext->AddBlockExtension('login_sso_buttons', new LoginBlockExtension('cas_sso_button.html.twig', $aData));

		return $oLoginContext;
	}
}

/**
 * Automatic creation & update of CAS users
 *
 */
class CASUserProvisioning
{
	/**
	 * Called when no user is found in iTop for the corresponding 'name'. This method
	 * can create/synchronize the User in iTop with an external source (such as AD/LDAP) on the fly
	 *
	 * @return bool true if the user is a valid one, false otherwise
	 * @throws \ArchivedObjectException
	 * @throws \CoreCannotSaveObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \CoreWarning
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 * @throws \OQLException
	 */
	public static function CreateUser()
	{
		$bOk = true;

		$sCASMemberships = Config::Get('cas_memberof');
		$bFound =  false;
		if (!empty($sCASMemberships))
		{
			$sCASMemberOfName = Config::Get('cas_memberof_attribute_name', 'memberOf');
			if (phpCAS::hasAttribute($sCASMemberOfName))
			{
				// A list of groups is specified, the user must a be member of (at least) one of them to pass
				$aCASMemberships = array();
				$aTmp = explode(';', $sCASMemberships);
				setlocale(LC_ALL, "en_US.utf8"); // !!! WARNING: this is needed to have  the iconv //TRANSLIT working fine below !!!
				foreach($aTmp as $sGroupName)
				{
					$aCASMemberships[] = trim(iconv('UTF-8', 'ASCII//TRANSLIT', $sGroupName)); // Just in case remove accents and spaces...
				}

				$aMemberOf = phpCAS::getAttribute($sCASMemberOfName);
				if (!is_array($aMemberOf)) $aMemberOf = array($aMemberOf); // Just one entry, turn it into an array
				$aFilteredGroupNames = array();
				foreach($aMemberOf as $sGroupName)
				{
					phpCAS::log("Info: user if a member of the group: ".$sGroupName);
					$sGroupName = trim(iconv('UTF-8', 'ASCII//TRANSLIT', $sGroupName)); // Remove accents and spaces as well
					$aFilteredGroupNames[] = $sGroupName;
					$bIsMember = false;
					foreach($aCASMemberships as $sCASPattern)
					{
						if (self::IsPattern($sCASPattern))
						{
							if (preg_match($sCASPattern, $sGroupName))
							{
								$bIsMember = true;
								break;
							}
						}
						else if ($sCASPattern == $sGroupName)
						{
							$bIsMember = true;
							break;
						}
					}
					if ($bIsMember)
					{
						// If needed create a new user for this email/profile
						$bOk = self::CreateCASUser(phpCAS::getUser(), $aMemberOf);
						if($bOk)
						{
							$bFound = true;
						}
						else
						{
							phpCAS::log("User ".phpCAS::getUser()." cannot be created in iTop. Logging off...");
						}
						break;
					}
				}
				if($bOk && !$bFound)
				{
					phpCAS::log("User ".phpCAS::getUser().", none of his/her groups (".implode('; ', $aFilteredGroupNames).") match any of the required groups: ".implode('; ', $aCASMemberships));
				}
			}
			else
			{
				// Too bad, the user is not part of any of the group => not allowed
				phpCAS::log("No '$sCASMemberOfName' attribute found for user ".phpCAS::getUser().". Are you using the SAML protocol (S1) ?");
			}
		}
		else
		{
			// No membership: no way to create the user that should exist prior to authentication
			phpCAS::log("User ".phpCAS::getUser().": missing user account in iTop (or iTop badly configured, Cf setting cas_memberof)");
			$bFound = false;
		}

		if (!$bFound)
		{
			// The user is not part of the allowed groups, => log out
			$sCASLogoutUrl = Config::Get('cas_logout_redirect_service');
			if (empty($sCASLogoutUrl))
			{
				$sCASLogoutUrl = utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
			}
			phpCAS::logoutWithRedirectService($sCASLogoutUrl); // Redirects to the CAS logout page
			// Will never return !
		}
		return $bFound;
	}

	/**
	 * Called after the user has been authenticated and found in iTop. This method can
	 * Update the user's definition (profiles...) on the fly to keep it in sync with an external source
	 *
	 * @param User $oUser The user to update/synchronize
	 *
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function UpdateUser(User $oUser)
	{
		$bCASUpdateProfiles = Config::Get('cas_update_profiles');
		$sCASMemberOfName = Config::Get('cas_memberof_attribute_name', 'memberOf');
		if ($bCASUpdateProfiles && (phpCAS::hasAttribute($sCASMemberOfName)))
		{
			$aMemberOf = phpCAS::getAttribute($sCASMemberOfName);
			if (!is_array($aMemberOf)) $aMemberOf = array($aMemberOf); // Just one entry, turn it into an array

			self::SetProfilesFromCAS($oUser, $aMemberOf);
		}
		// No groups defined in CAS or not CAS at all: do nothing...
	}

	/**
	 * Helper method to create a CAS based user
	 *
	 * @param string $sLogin
	 * @param array $aGroups
	 *
	 * @return bool true on success, false otherwise
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MissingQueryArgument
	 * @throws \MySQLException
	 * @throws \MySQLHasGoneAwayException
	 */
	protected static function CreateCASUser($sLogin, $aGroups)
	{
		if (!MetaModel::IsValidClass('URP_Profiles'))
		{
			phpCAS::log("URP_Profiles is not a valid class. Automatic creation of Users is not supported in this context, sorry.");
			return false;
		}

		$oUser = MetaModel::GetObjectByName('UserExternal', $sLogin, false);
		if ($oUser == null)
		{
			// Create the user, link it to a contact
			if (phpCAS::hasAttribute('mail'))
			{
				$sEmail = phpCAS::getAttribute('mail');
			}
			else
			{
				$sEmail = $sLogin;
			}
			phpCAS::log("Info: the user '$sLogin' does not exist. A new UserExternal will be created.");
			$oSearch = new DBObjectSearch('Person');
			$oSearch->AddCondition('email', $sEmail);
			$oSet = new DBObjectSet($oSearch);
			switch($oSet->Count())
			{
				case 0:
					phpCAS::log("Error: found no contact with the email: '$sEmail'. Cannot create the user in iTop.");
					return false;

				case 1:
					$oContact = $oSet->Fetch();
					$iContactId = $oContact->GetKey();
					phpCAS::log("Info: Found 1 contact '".$oContact->GetName()."' (id=$iContactId) corresponding to the email '$sEmail'.");
					break;

				default:
					phpCAS::log("Error: ".$oSet->Count()." contacts have the same email: '$sEmail'. Cannot create a user for this email.");
					return false;
			}

			$oUser = new UserExternal();
			$oUser->Set('login', $sLogin);
			$oUser->Set('contactid', $iContactId);
			$oUser->Set('language', MetaModel::GetConfig()->GetDefaultLanguage());
		}
		else
		{
			phpCAS::log("Info: the user '$sLogin' already exists (id=".$oUser->GetKey().").");
		}

		// Now synchronize the profiles
		return self::SetProfilesFromCAS($oUser, $aGroups);
	}

	/**
	 * @param User $oUser
	 * @param array $aGroups
	 *
	 * @return bool
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	protected static function SetProfilesFromCAS($oUser, $aGroups)
	{
		if (!MetaModel::IsValidClass('URP_Profiles'))
		{
			phpCAS::log("URP_Profiles is not a valid class. Automatic creation of Users is not supported in this context, sorry.");
			return false;
		}

		// read all the existing profiles
		$oProfilesSearch = new DBObjectSearch('URP_Profiles');
		$oProfilesSet = new DBObjectSet($oProfilesSearch);
		$aAllProfiles = array();
		while($oProfile = $oProfilesSet->Fetch())
		{
			$aAllProfiles[mb_strtolower($oProfile->GetName())] = $oProfile->GetKey();
		}

		// Translate the CAS/LDAP group names into iTop profile names
		$aProfiles = array();
		$sPattern = Config::Get('cas_profile_pattern');
		foreach($aGroups as $sGroupName)
		{
			if (preg_match($sPattern, $sGroupName, $aMatches))
			{
				if (array_key_exists(mb_strtolower($aMatches[1]), $aAllProfiles))
				{
					$aProfiles[] = $aAllProfiles[mb_strtolower($aMatches[1])];
					phpCAS::log("Info: Adding the profile '{$aMatches[1]}' from CAS.");
				}
				else
				{
					phpCAS::log("Warning: {$aMatches[1]} is not a valid iTop profile (extracted from group name: '$sGroupName'). Ignored.");
				}
			}
			else
			{
				phpCAS::log("Info: The CAS group '$sGroupName' does not seem to match an iTop pattern. Ignored.");
			}
		}
		if (count($aProfiles) == 0)
		{
			phpCAS::log("Info: The user '".$oUser->GetName()."' has no profiles retrieved from CAS. Default profile(s) will be used.");

			// Second attempt: check if there is/are valid default profile(s)
			$sCASDefaultProfiles = Config::Get('cas_default_profiles');
			$aCASDefaultProfiles = explode(';', $sCASDefaultProfiles);
			foreach($aCASDefaultProfiles as $sDefaultProfileName)
			{
				if (array_key_exists(mb_strtolower($sDefaultProfileName), $aAllProfiles))
				{
					$aProfiles[] = $aAllProfiles[mb_strtolower($sDefaultProfileName)];
					phpCAS::log("Info: Adding the default profile '".$aAllProfiles[mb_strtolower($sDefaultProfileName)]."' from CAS.");
				}
				else
				{
					phpCAS::log("Warning: the default profile {$sDefaultProfileName} is not a valid iTop profile. Ignored.");
				}
			}

			if (count($aProfiles) == 0)
			{
				phpCAS::log("Error: The user '".$oUser->GetName()."' has no profiles in iTop, and therefore cannot be created.");
				return false;
			}
		}

		// Now synchronize the profiles
		LoginWebPage::SynchronizeProfiles($oUser, $aProfiles, 'CAS/LDAP Synchro');

		phpCAS::log("Info: the user '".$oUser->GetName()."' (id=".$oUser->GetKey().") now has the following profiles: '".implode("', '", $aProfiles)."'.");
		if ($oUser->IsModified())
		{
			$oUser->DBWrite();
		}

		return true;
	}

	/**
	 * Helper function to check if the supplied string is a literal string or a regular expression pattern
	 * @param string $sCASPattern
	 * @return bool True if it's a regular expression pattern, false otherwise
	 */
	protected static function IsPattern($sCASPattern)
	{
		if ((substr($sCASPattern, 0, 1) == '/') && (substr($sCASPattern, -1) == '/'))
		{
			// the string is enclosed by slashes, let's assume it's a pattern
			return true;
		}
		else
		{
			return false;
		}
	}
}
