<?php

// Main autoload, this is the one to use in the PHPUnit configuration
//
// It was previously used to include both the vendor autoloader and our custom base test case classes, but these are now autoloaded from ./src/BasetestCase
// This file should then no longer be necessary, but we have to keep it until projects / branches / modules have been corrected.
require_once 'vendor/autoload.php';
