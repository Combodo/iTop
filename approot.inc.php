<?php

define('APPROOT', __DIR__.'/');
define('APPCONF', APPROOT.'conf/');

/**
 * iTop Datamodel XML format version
 *
 * It was also used in iTop 3.0.0 to get iTop core version (instead of {@see ITOP_VERSION} which gives the application version).
 * To address this need you should now use {@see ITOP_CORE_VERSION}
 *
 * @see ITOP_CORE_VERSION to get full iTop core version
 */
define('ITOP_DESIGN_LATEST_VERSION', '3.2');

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
define('ITOP_CORE_VERSION', '3.2.0');

/**
 * @var string
 * @since 3.0.4 3.1.0 3.2.0 N°6274 Allow to test if PHPUnit is currently running. Starting with PHPUnit 9.5 we'll be able to replace it with $GLOBALS['phpunit_version']
 * @since 3.0.4 3.1.1 3.2.0 N°6976 Fix constant name (DeprecatedCallsLog error handler was never set)
 */
const ITOP_PHPUNIT_RUNNING_CONSTANT_NAME = 'ITOP_PHPUNIT_RUNNING';

require_once APPROOT.'bootstrap.inc.php';
