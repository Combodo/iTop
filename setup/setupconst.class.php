<?php

/**
 * Constants to be used by Setup checks
 *
 * They used to be in SetupUtils, but we moved them so that a PHP version check can be done without requiring to much iTop code
 *
 * @since 2.8.0 N°3253 move from SetupUtils to this new SetupConst class
 */
class SetupConst
{
	private function __construct()
	{
		// this is a utility class and should stay as it !
	}

	// -- Minimum versions (requirements : forbids installation if not met)
	const PHP_MIN_VERSION = '7.1.3'; // 7 will be supported until the end of 2019 (see http://php.net/supported-versions.php)
	const MYSQL_MIN_VERSION = '5.6.0'; // 5.6 to have fulltext on InnoDB for Tags fields (N°931)
	const MYSQL_NOT_VALIDATED_VERSION = ''; // MySQL 8 is now OK (N°2010 in 2.7.0) but has no query cache so mind the perf on large volumes !

	// -- versions that will be the minimum in next iTop major release (warning if not met)
	const PHP_NEXT_MIN_VERSION = ''; //
	const MYSQL_NEXT_MIN_VERSION = ''; // no new MySQL requirement for next iTop version
	// -- First recent version that is not yet validated by Combodo (warning)
	const PHP_NOT_VALIDATED_VERSION = '8.0.0';

	const MIN_MEMORY_LIMIT = 33554432; // 32 * 1024 * 1024 - we can use expressions in const since PHP 5.6 but we are in the setup !
}