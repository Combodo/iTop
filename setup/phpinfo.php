<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

/**
 * Since N°1934 this page is accessible only to iTop admin users
 * @since 2.5.2 2.6.1 2.7.0 N°1934 must login as admin user to use
 *        to check if PHP is up and running, use phpcheck.php !
 */
require_once('../approot.inc.php');

try {
	require_once(APPROOT.'/application/startup.inc.php');
} catch (Exception $e) {
	// This means we don't have a valid iTop installation running
	echo <<<EOF
No valid installation found, cannot continue !<br>
If you need to check that PHP is running, use <a href="phpcheck.php">phpcheck.php</a>
EOF;
	die(-1);
}

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (N°1934)

/** @noinspection ForgottenDebugOutputInspection */
phpinfo();
?>
