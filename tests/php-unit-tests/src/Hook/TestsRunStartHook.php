<?php

declare(strict_types=1);

/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Hook;

require_once __DIR__ . '/../../../../approot.inc.php';

use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;
use utils;

/**
 * Class TestsRunStartHook
 *
 * IMPORTANT: This will no longer work in PHPUnit 10.0 and there is no alternative for now, so we will have to migrate it when the time comes
 * @link https://localheinz.com/articles/2023/02/14/extending-phpunit-with-its-new-event-system/#content-hooks-event-system
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Test\UnitTest\Hook
 * @since N°6097 2.7.10 3.0.4 3.1.1
 */
class TestsRunStartHook implements BeforeFirstTestHook, AfterLastTestHook
{
	/**
	 * Use the modification time on this file to check whereas it is newer than the requirements in a test case
	 *
	 * @return string Abs. path to a file generated when the global tests run starts.
	 */
	public static function GetRunStartedFileAbsPath(): string
	{
		// Note: This can't be put in the cache-<ENV> folder as we have multiple <ENV> running across the test cases
		//       We also don't want to put it in the unit tests folder as it is not supposed to be writable
		return APPROOT.'data/.php-unit-tests-run-started';
	}

	/**
	 * @inheritDoc
	 */
	public function executeBeforeFirstTest(): void
	{
		// Create / change modification timestamp of file marking the beginning of the tests run
		touch(static::GetRunStartedFileAbsPath());
	}

	/**
	 * @inheritDoc
	 */
	public function executeAfterLastTest(): void
	{
		// Cleanup of file marking the beginning of the tests run
		if (file_exists(static::GetRunStartedFileAbsPath())) {
			unlink(static::GetRunStartedFileAbsPath());
		}
	}


}