<?php

define('APPROOT', dirname(__FILE__).'/');
define('APPCONF', APPROOT.'conf/');


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
define('ITOP_CORE_VERSION', '2.7.11');


require_once APPROOT.'bootstrap.inc.php';
