<?php
require_once("../application/nicewebpage.class.inc.php");
/**
 * Web page used for displaying the login form
 */
class login_web_page extends nice_web_page
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
		$this->add("<input type=\"hidden\" name=\"operation\" value=\"login\" />\n");
		$this->add("</form>\n");
		$this->add("</div>\n");
	}
	
	static function DoLogin()
	{
		$operation = utils::ReadParam('operation', '');
		session_start();
		
		if (!session_is_registered('auth_user') || !session_is_registered('auth_pwd'))
		{
			if ($operation == 'login')
			{
				$sAuthUser = utils::ReadParam('auth_user', '', 'post');
				$sAuthPwd = utils::ReadParam('auth_pwd', '', 'post');
			}
			else
			{
				$oPage = new login_web_page();
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
			$oPage = new login_web_page();
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
