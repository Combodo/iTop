<?php
require_once("../application/nicewebpage.class.inc.php");
/**
 * Web page used for displaying the login form
 */
class LoginWebPage extends NiceWebPage
{
    public function __construct()
    {
        parent::__construct("iTop Login");
        $this->add_style("
body {
	background-color: #eee;
	margin: 0;
	padding: 0;
}
#login {
	width: 230px;
	margin-left: auto;
	margin-right: auto;
	margin-top: 150px;
	padding: 20px;
	background-color: #fff;
	border: 1px solid #000;
}
.center {
	text-align: center;
}

h1 {
	color: #83b217;
	font-size: 16pt;
}
.v-spacer {
	padding-top: 1em;
}
		");
	}
	
	public function DisplayLoginForm($bFailedLogin = false)
	{
		$sAuthUser = utils::ReadParam('auth_user', '');
		$sAuthPwd = utils::ReadParam('suggest_pwd', '');

		$this->add("<div id=\"login\">\n");
		$this->add("<h1>Welcome to iTop!</h1>\n");
		if ($bFailedLogin)
		{
			$this->add("<p class=\"hilite\">Incorrect login/password, please try again.</p>\n");
		}
		else
		{
			$this->add("<p>Please identify yourself before continuing.</p>\n");
		}
		$this->add("<form method=\"post\">\n");
		$this->add("<table>\n");
		$this->add("<tr><td><label for=\"user\">User Name:</label></td><td><input id=\"user\" type=\"text\" name=\"auth_user\" value=\"$sAuthUser\" /></td></tr>\n");
		$this->add("<tr><td><label for=\"pwd\">Password:</label></td><td><input id=\"pwd\" type=\"password\" name=\"auth_pwd\" value=\"$sAuthPwd\" /></td></tr>\n");
		$this->add("<tr><td colspan=\"2\" class=\"center v-spacer\"> <input type=\"submit\" value=\"Enter iTop\" /></td></tr>\n");
		$this->add("</table>\n");
		$this->add("<input type=\"hidden\" name=\"loginop\" value=\"login\" />\n");
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
		$operation = utils::ReadParam('loginop', '');
		session_start();

		if ($operation == 'logoff')
		{
			self::ResetSession();
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
