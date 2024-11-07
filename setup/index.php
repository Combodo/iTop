<?php
/*
 * Copyright (C) 2010-2024 Combodo SAS
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
 * Simple redirection page to check PHP requirements
 *
 * @see https://github.com/composer/composer/blob/master/doc/07-runtime.md#platform-check Composer's platform_check
 *
 * @since 3.0.0 N°3253
 */

require_once('../lib/autoload.php');

echo <<<HTML
<!DOCTYPE html>
<html>
<head>
<title>iTop setup - Checking minimum requirements</title>
<meta http-equiv="refresh" content="0; url=wizard.php">
<script>
document.location = "wizard.php";
</script>
</head>
<body>
<p>Redirecting to <a href="wizard.php">setup launch page</a>...</p>
</body>
</html>
HTML;
