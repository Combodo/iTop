<!DOCTYPE html>
<html>
<head>
<title>iTop Setup - redirection</title>
<style>
* {
	position: relative;
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}

body.error-container {
	background: rgba(0, 0, 0, 0) radial-gradient(red, black) repeat scroll 0% 0%;
}

div.error-message {
	height: 100vh;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;

	color: white;
	text-shadow: black 1px 0 10px;
}

div.error-message > h1 {
	font-weight: bolder;
	font-size: 4rem;
	text-transform: full-width;
	text-decoration: underline overline red;
	margin: 2rem 0;
}

div.error-message > p {
	font-size: 1.5rem;
	margin: 0.5rem 0;
}
</style>
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
	console.debug("skip=", bSkipErrorDisplay);
	if (!bSkipErrorDisplay) {
		var $pageBody = $("body");
		$pageBody.addClass("error-container");
		$pageBody.append("<div class='error-message'>" +
		 "<h1>😭 iTop cannot install</h1>" +
		  "<p>💣 PHP error occurred</p>" +
		  "<p>Your system doesn't meet iTop requirements !</p>")
	}
});
</script>
HTML;


register_shutdown_function(static function () {
	$error = error_get_last();
	if ($error
		&& (isset($error['type']))
		&& (in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR], true))) {
		ob_end_clean();
	}
});
ob_start();
require_once("index.setup.php");
ob_end_clean();

echo <<<HTML
<script>
bSkipErrorDisplay = true;
document.location = "index.setup.php";
</script>
HTML;
?>
</body>
</html>

