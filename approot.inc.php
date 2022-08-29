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
define('ITOP_DESIGN_LATEST_VERSION', '3.0');

/**
 * Constant containing the iTop core version, whatever application was built
 *
 * Note that in iTop 3.0.0 we used {@see ITOP_DESIGN_LATEST_VERSION} to get core version.
 * When releasing, both constants should be updated : see `.make/release/update-versions.php` for that !
 *
 * @since 2.7.7 3.0.1 3.1.0 N°4714 constant creation
 * @used-by utils::GetItopVersionWikiSyntax()
 * @used-by iTopModulesPhpVersionIntegrationTest
 */
define('ITOP_CORE_VERSION', '3.0.2');

require_once APPROOT.'bootstrap.inc.php';
