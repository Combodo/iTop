<?php

use Combodo\iTop\Portal\Kernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/config/bootstrap.php';
require_once APPROOT . '/application/loginwebpage.class.inc.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

// Note: Manually refactored ternary condition to be PHP 5.x compatible
if ($trustedProxies = isset($_SERVER['TRUSTED_PROXIES']) ? $_SERVER['TRUSTED_PROXIES'] : (isset($_ENV['TRUSTED_PROXIES']) ? $_ENV['TRUSTED_PROXIES'] : false) ) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

// Note: Manually refactored ternary condition to be PHP 5.x compatible
if ($trustedHosts = isset($_SERVER['TRUSTED_HOSTS']) ? $_SERVER['TRUSTED_HOSTS'] : (isset($_ENV['TRUSTED_HOSTS']) ? $_ENV['TRUSTED_HOSTS'] : false) ) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
