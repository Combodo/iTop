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

require_once("../application/nicewebpage.class.inc.php");
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
	
	public function DisplayLoginForm($bFailedLogin = false)
	{
		$sAuthUser = utils::ReadParam('auth_user', '');
		$sAuthPwd = utils::ReadParam('suggest_pwd', '');

		$sVersionShort = Dict::Format('UI:iTopVersion:Short', ITOP_VERSION);
		$this->add("<div id=\"login-logo\"><a href=\"http://www.combodo.com/itop\"><img title=\"$sVersionShort\" src=\"../images/itop-logo.png\"></a></div>\n");
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
		$this->add("<table>\n");
		$this->add("<tr><td><label for=\"user\">".Dict::S('UI:Login:UserNamePrompt').":</label></td><td><input id=\"user\" type=\"text\" name=\"auth_user\" value=\"$sAuthUser\" /></td></tr>\n");
		$this->add("<tr><td><label for=\"pwd\">".Dict::S('UI:Login:PasswordPrompt').":</label></td><td><input id=\"pwd\" type=\"password\" name=\"auth_pwd\" value=\"$sAuthPwd\" /></td></tr>\n");
		$this->add("<tr><td colspan=\"2\" class=\"center v-spacer\"> <input type=\"submit\" value=\"".Dict::S('UI:Button:Login')."\" /></td></tr>\n");
		$this->add("</table>\n");
		$this->add("<input type=\"hidden\" name=\"loginop\" value=\"login\" />\n");
		$this->add("</form>\n");
		$this->add("</div>\n");
	}

	public function DisplayChangePwdForm($bFailedLogin = false)
	{
		$sAuthUser = utils::ReadParam('auth_user', '');
		$sAuthPwd = utils::ReadParam('suggest_pwd', '');

		$sVersionShort = Dict::Format('UI:iTopVersion:Short', ITOP_VERSION);
		$this->add("<div id=\"login-logo\"><a href=\"http://www.combodo.com/itop\"><img title=\"$sVersionShort\" src=\"../images/itop-logo.png\"></a></div>\n");
		$this->add("<div id=\"login\">\n");
		$this->add("<h1>".Dict::S('UI:Login:ChangeYourPassword')."</h1>\n");
		if ($bFailedLogin)
		{
			$this->add("<p class=\"hilite\">".Dict::S('UI:Login:IncorrectOldPassword')."</p>\n");
		}
		$this->add("<form method=\"post\">\n");
		$this->add("<table>\n");
		$this->add("<tr><td><label for=\"old_pwd\">".Dict::S('UI:Login:OldPasswordPrompt').":</label></td><td><input type=\"password\" id=\"old_pwd\" name=\"old_pwd\" value=\"\" /></td></tr>\n");
		$this->add("<tr><td><label for=\"new_pwd\">".Dict::S('UI:Login:NewPasswordPrompt').":</label></td><td><input type=\"password\" id=\"new_pwd\" name=\"new_pwd\" value=\"\" /></td></tr>\n");
		$this->add("<tr><td><label for=\"retype_new_pwd\">".Dict::S('UI:Login:RetypeNewPasswordPrompt').":</label></td><td><input type=\"password\" id=\"retype_new_pwd\" name=\"retype_new_pwd\" value=\"\" /></td></tr>\n");
		$this->add("<tr><td colspan=\"2\" class=\"center v-spacer\">  <input type=\"button\" onClick=\"GoBack();\" value=\"".Dict::S('UI:Button:Cancel')."\" />&nbsp;&nbsp;<input type=\"submit\" value=\"".Dict::S('UI:Button:ChangePassword')."\" /></td></tr>\n");
		$this->add("</table>\n");
		$this->add("<input type=\"hidden\" name=\"loginop\" value=\"do_change_pwd\" />\n");
		$this->add("</form>\n");
		$this->add("</div>\n");
	}

	static protected function ResetSession()
	{
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
		$oConfig = new Config(ITOP_CONFIG_FILE);
		return $oConfig->GetSecureConnectionRequired();
	}

	static function IsConnectionSecure()
	{
		$bSecured = false;

		if ( !empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!= 'off') )
		{
			$bSecured = true;
		}
		return $bSecured;
	}
	
	static function DoLogin()
	{
		if (self::SecureConnectionRequired() && !self::IsConnectionSecure())
		{
			// Non secured URL... redirect to a secured one
			$sUrl = Utils::GetAbsoluteUrl(true /* query string */, true /* force HTTPS */);
			header("Location: $sUrl");			
			exit;
		}
		$bHTTPBasicAuthentication  = (utils::ReadParam('auth', '', 'get') == 'http_basic');
		if ($bHTTPBasicAuthentication)
		{
			// Basic HTTP/PHP authentication mecanism
			//
			// meme avec ca c'est pourri - return;
			if (!isset($_SERVER['PHP_AUTH_USER']))
			{
				header('WWW-Authenticate: Basic realm="iTop access is restricted"');
				header('HTTP/1.0 401 Unauthorized');
					// Note: accessed when the user will click on Cancel
				echo '<p><strong>'.Dict::S('UI:Login:Error:AccessRestricted').'</strong></p>';
				exit;
			}
			else
			{
				$sAuthUser = $_SERVER['PHP_AUTH_USER'];
				$sAuthPwd = $_SERVER['PHP_AUTH_PW'];
				if (!UserRights::Login($sAuthUser, $sAuthPwd))
				{
					header('WWW-Authenticate: Basic realm="Unknown user \''.$sAuthUser.'\'"');
					header('HTTP/1.0 401 Unauthorized');
					// Note: accessed when the user will click on Cancel
					// Todo: count the attempts
					echo '<p><strong>'.Dict::S('UI:Login:Error:AccessRestricted').'</strong></p>';
					exit;
				}
			}
			return;
		}

		// Home-made authentication mecanism
		//
		$operation = utils::ReadParam('loginop', '');
		session_start();

		if ($operation == 'logoff')
		{
			self::ResetSession();
		}
		
		if ($operation == 'change_pwd')
		{
			$oPage = new LoginWebPage();
			$oPage->DisplayChangePwdForm();
			$oPage->output();
			exit;
		}
		if ($operation == 'do_change_pwd')
		{
			$sAuthUser = $_SESSION['auth_user'];
			$sOldPwd = utils::ReadPostedParam('old_pwd');
			$sNewPwd = utils::ReadPostedParam('new_pwd');
			if (UserRights::CanChangePassword() && ((!UserRights::Login($sAuthUser, $sOldPwd)) || (!UserRights::ChangePassword($sOldPwd, $sNewPwd))))
			{
				$oPage = new LoginWebPage();
				$oPage->DisplayChangePwdForm(true); // old pwd was wrong
				$oPage->output();
				exit;
			}
			else
			{
				// Remember the changed password
				$_SESSION['auth_pwd'] = $sNewPwd;
				return;
			}
		}
		
		if (!isset($_SESSION['auth_user']) || !isset($_SESSION['auth_pwd']))
		{
			if ($operation == 'loginurl')
			{
				$sAuthUser = utils::ReadParam('auth_user', '', 'get');
				$sAuthPwd = utils::ReadParam('auth_pwd', '', 'get');
			}
			else if ($operation == 'login')
			{
				$sAuthUser = utils::ReadParam('auth_user', '', 'post');
				$sAuthPwd = utils::ReadParam('auth_pwd', '', 'post');
			}
			else
			{
				$oPage = new LoginWebPage();
				$oPage->DisplayLoginForm();
				$oPage->output();
				exit;
			}
		}
		else
		{
			$sAuthUser = $_SESSION['auth_user'];
			$sAuthPwd = $_SESSION['auth_pwd'];
		}
		if (!UserRights::Login($sAuthUser, $sAuthPwd))
		{
			self::ResetSession();
			$oPage = new LoginWebPage();
			$oPage->DisplayLoginForm( true /* failed attempt */);
			$oPage->output();
			exit;
		}
		else
		{
			$_SESSION['auth_user'] = $sAuthUser ;
			$_SESSION['auth_pwd'] = $sAuthPwd;
		}
	}
} // End of class
?>
