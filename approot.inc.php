<?php
define('APPROOT', dirname(__FILE__).'/');
define('MODULESROOT', APPROOT.'modules/');
if (function_exists('microtime'))
{
	$fItopStarted = microtime(true); 
}
else
{
	$fItopStarted = 1000 * time();
}
?>
