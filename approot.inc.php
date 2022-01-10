<?php

define('APPROOT', dirname(__FILE__).'/');
define('APPCONF', APPROOT.'conf/');

/**
 * iTop Datamodel XML format version
 * And also iTop core version
 *
 * To test for iTop core version use this constant instead of {@link ITOP_VERSION} !
 */
define('ITOP_DESIGN_LATEST_VERSION', '3.0');

require_once APPROOT.'bootstrap.inc.php';
