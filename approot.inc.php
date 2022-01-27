<?php

define('APPROOT', dirname(__FILE__).'/');
define('APPCONF', APPROOT.'conf/');

/**
 * iTop Datamodel XML format version
 *
 * It was also used in iTop 3.0.0 to get iTop core version (instead of {@see ITOP_VERSION} which gives the application version).
 * To address this need you should now use {@see ITOP_CORE_VERSION}
 *
 * @see ITOP_CORE_VERSION to get full iTop core version
 */
define('ITOP_DESIGN_LATEST_VERSION', '3.1');

require_once APPROOT.'bootstrap.inc.php';
