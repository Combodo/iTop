<!DOCTYPE html>
<html>
<head>
<title>iTop Setup - redirection</title>
<link type="text/css" href="../css/setup.css" rel="stylesheet">
</head>
<body>
<?php
/*
 * Copyright (C) 2010-2020 Combodo SARL
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
 * Simple redirection page
 * Will display an error message if a parse error occurs !
 *
 * @since 3.0.0 N°3253
 */
require_once('../approot.inc.php');


echo <<<'HTML'
<script src="../js/jquery.min.js"></script>
<script>
bSkipErrorDisplay = false;
$(document).ready(function () {
	if (!bSkipErrorDisplay) {
		var $pageBody = $("body");
		// $pageBody.addClass("error-container");
		$pageBody.append("<div id='ibo-page-container'>" +
		  "<h1>😭 iTop cannot install</h1>" +
		  "<p class=\"message message-error\">💣 PHP version isn't compatible</p>" +
		  "<p>Please check <a href=\"https://www.itophub.io/wiki/page?id=latest%3Ainstall%3Ainstalling_itop#software_requirements\" target=\"_blank\">iTop requirements</a></p>" +
		   "</div>")
	}
});
</script>
HTML;


function HandlePageErrors()
{
	$error = error_get_last();
	if ($error
		&& (isset($error['type']))
		&& (in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR], true))) {
		ob_end_clean();
	}
}


register_shutdown_function('HandlePageErrors');
ob_start();
require_once("wizard.php");
ob_end_clean();

//echo <<<HTML
//<script>
//bSkipErrorDisplay = true;
//document.location = "wizard.php";
//</script>
//HTML;
?>
</body>
</html>
