<?php

if (UserRights::IsLoggedIn()) {
	throw new Exception("User should not be authenticated at this point");
}
require_once(APPROOT.'/application/startup.inc.php');

LoginWebPage::DoLogin(true);

echo 'Yo !';
