<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Class LoginWebPage
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once(APPROOT."/application/nicewebpage.class.inc.php");
/**
 * Web page used for displaying the login form
 */
class LoginWebPage extends NiceWebPage
{
    public function __construct()
    {
        parent::__construct("iTop Login");
        $this->add_style(<<<EOF
body {
	background: #eee;
	margin: 0;
	padding: 0;
}
#login-logo {
	margin-top: 150px;
	width: 300px;
	padding-left: 20px;
	padding-right: 20px;
	padding-top: 10px;
	padding-bottom: 10px;
	margin-left: auto;
	margin-right: auto;
	background: #f6f6f1;
	height: 54px;
	border-top: 1px solid #000;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: 0;
	text-align: center;
}
#login-logo img {
	border: 0;
}
#login {
	width: 300px;
	margin-left: auto;
	margin-right: auto;
	padding: 20px;
	background-color: #fff;
	border-bottom: 1px solid #000;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-top: 0;
	text-align: center;
}
#pwd, #user,#old_pwd, #new_pwd, #retype_new_pwd {
	width: 10em;
}
.center {
	text-align: center;
}

h1 {
	color: #1C94C4;
	font-size: 16pt;
}
.v-spacer {
	padding-top: 1em;
}
EOF
);
	}
	
	public function DisplayLoginForm($sLoginType, $bFailedLogin = false)
	{
		switch($sLoginType)
		{
			case 'basic':
			case 'url':
			$this->add_header('WWW-Authenticate: Basic realm="'.Dict::Format('UI:iTopVersion:Short', ITOP_VERSION));
			$this->add_header('HTTP/1.0 401 Unauthorized');
			// Note: displayed when the user will click on Cancel
			$this->add('<p><strong>'.Dict::S('UI:Login:Error:AccessRestricted').'</strong></p>');
			break;
			
			case 'external':
			case 'form':
			default: // In case the settings get messed up...
			$sAuthUser = utils::ReadParam('auth_user', '');
			$sAuthPwd = utils::ReadParam('suggest_pwd', '');
	
			$sVersionShort = Dict::Format('UI:iTopVersion:Short', ITOP_VERSION);
			$this->add("<div id=\"login-logo\"><a href=\"http://www.combodo.com/itop\"><img title=\"$sVersionShort\" src=\"../images/itop-logo-external.png\"></a></div>\n");
			$this->add("<div id=\"login\">\n");
			$this->add("<h1>".Dict::S('UI:Login:Welcome')."</h1>\n");
			if ($bFailedLogin)
			{
				$this->add("<p class=\"hilite\">".Dict::S('UI:Login:IncorrectLoginPassword')."</p>\n");
			}
			else
			{
				$this->add("<p>".Dict::S('UI:Login:IdentifyYourself')."</p>\n");
			}
			$this->add("<form method=\"post\">\n");
			$this->add("<table width=\"100%\">\n");
			$this->add("<tr><td style=\"text-align:right\"><label for=\"user\">".Dict::S('UI:Login:UserNamePrompt').":</label></td><td style=\"text-align:left\"><input id=\"user\" type=\"text\" name=\"auth_user\" value=\"$sAuthUser\" /></td></tr>\n");
			$this->add("<tr><td style=\"text-align:right\"><label for=\"pwd\">".Dict::S('UI:Login:PasswordPrompt').":</label></td><td style=\"text-align:left\"><input id=\"pwd\" type=\"password\" name=\"auth_pwd\" value=\"$sAuthPwd\" /></td></tr>\n");
			$this->add("<tr><td colspan=\"2\" class=\"center v-spacer\"> <input type=\"submit\" value=\"".Dict::S('UI:Button:Login')."\" /></td></tr>\n");
			$this->add("</table>\n");
			$this->add("<input type=\"hidden\" name=\"loginop\" value=\"login\" />\n");
			$this->add("</form>\n");
			$this->add("</div>\n");
			break;
		}
	}

	public function DisplayChangePwdForm($bFailedLogin = false)
	{
		$sAuthUser = utils::ReadParam('auth_user', '');
		$sAuthPwd = utils::ReadParam('suggest_pwd', '');

		$sVersionShort = Dict::Format('UI:iTopVersion:Short', ITOP_VERSION);
		$sInconsistenPwdMsg = Dict::S('UI:Login:RetypePwdDoesNotMatch');
		$this->add_script(<<<EOF
function GoBack()
{
	window.history.back();
}

function DoCheckPwd()
{
	if ($('#new_pwd').val() != $('#retype_new_pwd').val())
	{
		alert('$sInconsistenPwdMsg');
		return false;
	}
	return true;
}
EOF
);
		$this->add("<div id=\"login-logo\"><a href=\"http://www.combodo.com/itop\"><img title=\"$sVersionShort\" src=\"../images/itop-logo.png\"></a></div>\n");
		$this->add("<div id=\"login\">\n");
		$this->add("<h1>".Dict::S('UI:Login:ChangeYourPassword')."</h1>\n");
		if ($bFailedLogin)
		{
			$this->add("<p class=\"hilite\">".Dict::S('UI:Login:IncorrectOldPassword')."</p>\n");
		}
		$this->add("<form method=\"post\">\n");
		$this->add("<table width=\"100%\">\n");
		$this->add("<tr><td style=\"text-align:right\"><label for=\"old_pwd\">".Dict::S('UI:Login:OldPasswordPrompt').":</label></td><td style=\"text-align:left\"><input type=\"password\" id=\"old_pwd\" name=\"old_pwd\" value=\"\" /></td></tr>\n");
		$this->add("<tr><td style=\"text-align:right\"><label for=\"new_pwd\">".Dict::S('UI:Login:NewPasswordPrompt').":</label></td><td style=\"text-align:left\"><input type=\"password\" id=\"new_pwd\" name=\"new_pwd\" value=\"\" /></td></tr>\n");
		$this->add("<tr><td style=\"text-align:right\"><label for=\"retype_new_pwd\">".Dict::S('UI:Login:RetypeNewPasswordPrompt').":</label></td><td style=\"text-align:left\"><input type=\"password\" id=\"retype_new_pwd\" name=\"retype_new_pwd\" value=\"\" /></td></tr>\n");
		$this->add("<tr><td colspan=\"2\" class=\"center v-spacer\">  <input type=\"button\" onClick=\"GoBack();\" value=\"".Dict::S('UI:Button:Cancel')."\" />&nbsp;&nbsp;<input type=\"submit\" onClick=\"return DoCheckPwd();\" value=\"".Dict::S('UI:Button:ChangePassword')."\" /></td></tr>\n");
		$this->add("</table>\n");
		$this->add("<input type=\"hidden\" name=\"loginop\" value=\"do_change_pwd\" />\n");
		$this->add("</form>\n");
		$this->add("</div>\n");
	}

	static function ResetSession()
	{
		if (isset($_SESSION['login_mode']))
		{
			$sPreviousLoginMode = $_SESSION['login_mode'];
		}
		else
		{
			$sPreviousLoginMode = '';
		}
		// Unset all of the session variables.
		$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (isset($_COOKIE[session_name()]))
		{
			setcookie(session_name(), '', time()-3600, '/');
		}		
		// Finally, destroy the session.
		session_destroy();
	}

	static function SecureConnectionRequired()
	{
		return MetaModel::GetConfig()->GetSecureConnectionRequired();
	}

	static function IsConnectionSecure()
	{
		$bSecured = false;

		if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off'))
		{
			$bSecured = true;
		}
		return $bSecured;
	}
	
	protected static function Login()
	{
		if (self::SecureConnectionRequired() && !self::IsConnectionSecure())
		{
			// Non secured URL... redirect to a secured one
			$sUrl = Utils::GetAbsoluteUrl(true /* query string */, true /* force HTTPS */);
			header("Location: $sUrl");			
			exit;
		}

		$aAllowedLoginTypes = MetaModel::GetConfig()->GetAllowedLoginTypes();

		if (isset($_SESSION['auth_user']))
		{
			//echo "User: ".$_SESSION['auth_user']."\n";
			// Already authentified
			UserRights::Login($_SESSION['auth_user']); // Login & set the user's language
			return true;
		}
		else
		{
			$index = 0;
			$sLoginMode = '';
			$sAuthentication = 'internal';
			while(($sLoginMode == '') && ($index < count($aAllowedLoginTypes)))
			{
				$sLoginType = $aAllowedLoginTypes[$index];
				switch($sLoginType)
				{
					case 'form':
					// iTop standard mode: form based authentication
					$sAuthUser = utils::ReadPostedParam('auth_user', '');
					$sAuthPwd = utils::ReadPostedParam('auth_pwd', '');
					if ($sAuthUser != '')
					{
						$sLoginMode = 'form';
					}
					break;
					
					case 'basic':
					// Standard PHP authentication method, works with Apache...
					// Case 1) Apache running in CGI mode + rewrite rules in .htaccess
					if (isset($_SERVER['HTTP_AUTHORIZATION']) && !empty($_SERVER['HTTP_AUTHORIZATION']))
					{
						list($sAuthUser, $sAuthPwd) = explode(':' , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
						$sLoginMode = 'basic';
					}
					else if (isset($_SERVER['PHP_AUTH_USER']))
					{
						$sAuthUser = $_SERVER['PHP_AUTH_USER'];
						$sAuthPwd = $_SERVER['PHP_AUTH_PW'];
						$sLoginMode = 'basic';
					}
					break;

					case 'external':
					// Web server supplied authentication
					$bExternalAuth = false;
                    $sExtAuthVar = MetaModel::GetConfig()->GetExternalAuthenticationVariable(); // In which variable is the info passed ?
                    $sEval = '$bExternalAuth = isset('.$sExtAuthVar.');';
                    eval($sEval);
                    if ($bExternalAuth)
                    {
						eval('$sAuthUser = '.$sExtAuthVar.';'); // Retrieve the value
						$sAuthPwd = ''; // No password in this case the web server already authentified the user...
						$sLoginMode = 'external';
						$sAuthentication = 'external';
					}
					break;

					case 'url':
					// Credentials passed directly in the url
					$sAuthUser = utils::ReadParam('auth_user', '');
					if ($sAuthUser != '')
					{
						$sAuthPwd = utils::ReadParam('auth_pwd', '');
						$sLoginMode = 'url';
					}		
					break;	
				}
				$index++;
			}
			//echo "\nsLoginMode: $sLoginMode (user: $sAuthUser / pwd: $sAuthPwd\n)";
			if ($sLoginMode == '')
			{
				// First connection
				$sDesiredLoginMode = utils::ReadParam('login_mode');
				if (in_array($sDesiredLoginMode, $aAllowedLoginTypes))
				{
					$sLoginMode = $sDesiredLoginMode;
				}
				else
				{
					$sLoginMode = $aAllowedLoginTypes[0]; // First in the list...
				}
				$oPage = new LoginWebPage();
				$oPage->DisplayLoginForm( $sLoginMode, false /* no previous failed attempt */);
				$oPage->output();
				exit;
			}
			else
			{
				if (!UserRights::CheckCredentials($sAuthUser, $sAuthPwd, $sAuthentication))
				{
					self::ResetSession();
					$oPage = new LoginWebPage();
					$oPage->DisplayLoginForm( $sLoginMode, true /* failed attempt */);
					$oPage->output();
					exit;
				}
				else
				{
					// User is Ok, let's save it in the session and proceed with normal login
					UserRights::Login($sAuthUser, $sAuthentication); // Login & set the user's language
					
					if (MetaModel::GetConfig()->Get('log_usage'))
					{
						$oLog = new EventLoginUsage();
						$oLog->Set('userinfo', UserRights::GetUser());
						$oLog->Set('user_id', UserRights::GetUserObject()->GetKey());
						$oLog->Set('message', 'Successful login');
						$oLog->DBInsertNoReload();
					}
					
					$_SESSION['auth_user'] = $sAuthUser;
					$_SESSION['login_mode'] = $sLoginMode;
				}
			}
		}
	}
	
	/**
	 * Check if the user is already authentified, if yes, then performs some additional validations:
	 * - if $bMustBeAdmin is true, then the user must be an administrator, otherwise an error is displayed
	 * - if $bIsAllowedToPortalUsers is false and the user has only access to the portal, then the user is redirected to the portal
	 * @param bool $bMustBeAdmin Whether or not the user must be an admin to access the current page
	 * @param bool $bIsAllowedToPortalUsers Whether or not the current page is considered as part of the portal
	 */
	static function DoLogin($bMustBeAdmin = false, $bIsAllowedToPortalUsers = false)
	{
		$operation = utils::ReadParam('loginop', '');
		session_name(MetaModel::GetConfig()->Get('session_name'));
		session_start();

		if ($operation == 'logoff')
		{
			if (isset($_SESSION['login_mode']))
			{
				$sLoginMode = $_SESSION['login_mode'];
			}
			else
			{
				$aAllowedLoginTypes = MetaModel::GetConfig()->GetAllowedLoginTypes();
				if (count($aAllowedLoginTypes) > 0)
				{
					$sLoginMode = $aAllowedLoginTypes[0];
				}
				else
				{
					$sLoginMode = 'form';
				}
			}
			self::ResetSession();
			$oPage = new LoginWebPage();
			$oPage->DisplayLoginForm( $sLoginMode, false /* not a failed attempt */);
			$oPage->output();
			exit;
		}		
		else if ($operation == 'change_pwd')
		{
			$sAuthUser = $_SESSION['auth_user'];
			UserRights::Login($sAuthUser); // Set the user's language
			$oPage = new LoginWebPage();
			$oPage->DisplayChangePwdForm();
			$oPage->output();
			exit;
		}
		if ($operation == 'do_change_pwd')
		{
			$sAuthUser = $_SESSION['auth_user'];
			UserRights::Login($sAuthUser); // Set the user's language
			$sOldPwd = utils::ReadPostedParam('old_pwd');
			$sNewPwd = utils::ReadPostedParam('new_pwd');
			if (UserRights::CanChangePassword() && ((!UserRights::CheckCredentials($sAuthUser, $sOldPwd)) || (!UserRights::ChangePassword($sOldPwd, $sNewPwd))))
			{
				$oPage = new LoginWebPage();
				$oPage->DisplayChangePwdForm(true); // old pwd was wrong
				$oPage->output();
			}
		}
		
		self::Login();

		if ($bMustBeAdmin && !UserRights::IsAdministrator())
		{	
			require_once(APPROOT.'/setup/setuppage.class.inc.php');
			$oP = new SetupWebPage(Dict::S('UI:PageTitle:FatalError'));
			$oP->add("<h1>".Dict::S('UI:Login:Error:AccessAdmin')."</h1>\n");	
			$oP->p("<a href=\"../pages/logoff.php\">".Dict::S('UI:LogOffMenu')."</a>");
			$oP->output();
			exit;
		}
		elseif ( (!$bIsAllowedToPortalUsers) && (UserRights::IsPortalUser()))
		{
			// No rights to be here, redirect to the portal
			header('Location: ../portal/index.php');
		}
	}

} // End of class
?>
