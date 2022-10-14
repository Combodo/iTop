<?php

// Main autoload, this is the one to use in the PHPUnit configuration
// It is customized to include both
// - Vendors
require_once 'vendor/autoload.php';
// - Custom test case PHP classes
require_once 'ItopTestCase.php';
require_once 'ItopDataTestCase.php';
