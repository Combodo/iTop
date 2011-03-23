<?php
define('APPROOT', dirname(__FILE__).'/');
if (function_exists('microtime'))
{
	$fItopStarted = microtime(true); 
}
else
{
	$fItopStarted = 1000 * time();
}
?>
