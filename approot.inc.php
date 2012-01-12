<?php

define('APPROOT', dirname(__FILE__).'/');
define('APPCONF', APPROOT.'conf/');
define('ITOP_DEFAULT_ENV', 'production');

if (function_exists('microtime'))
{
	$fItopStarted = microtime(true); 
}
else
{
	$fItopStarted = 1000 * time();
}
?>
