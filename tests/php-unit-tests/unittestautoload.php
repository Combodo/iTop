<?php

// Main autoload, this is the one to use in the PHPUnit configuration
//
// This file was previously mentioned as deprecated, and now it HAS to be used (see phpunit.xml/bootstrap attribute)
require_once 'vendor/autoload.php';

// Required to benefit from symfony/framework-bundle's KernelTestCase, which is in a package which is a mix of runtime and test tools
require_once  __DIR__.'/../../lib/autoload.php';
