<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\Log;


use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DeprecatedCallsLog;

class DeprecatedCallsLogTest extends ItopTestCase {
	/**
	 * We are testing for a undefined offset error. This was throwing a Notice, but starting with PHP 8.0 it was converted to a Warning ! Also the message was changed :(
	 *
	 * @link https://www.php.net/manual/en/migration80.incompatible.php check "A number of notices have been converted into warnings:"
	 */
	private function SetUndefinedOffsetExceptionToExpect(): void {
		/** @noinspection ConstantCanBeUsedInspection Preferring the function call as it is easier to read and won't cost that much in this PHPUnit context */
		if (version_compare(PHP_VERSION, '8.0', '>=')) {
			$this->expectWarning();
			$sUndefinedOffsetExceptionMessage = 'Undefined array key "tutu"';
		}
		else {
			$this->expectNotice();
			$sUndefinedOffsetExceptionMessage = 'Undefined index: tutu';
		}
		$this->expectExceptionMessage($sUndefinedOffsetExceptionMessage);
	}

	public function testPhpNoticeWithoutDeprecatedCallsLog(): void {
		$this->SetUndefinedOffsetExceptionToExpect();

		$aArray = [];
		if ('toto' === $aArray['tutu']) {
			//Do nothing, just raising a undefined offset warning
		}
	}

	/**
	 * @runInSeparateProcess Necessary, due to the DeprecatedCallsLog being enabled (no mean to reset)
	 *
	 * The error handler set by DeprecatedCallsLog during startup was causing PHPUnit to miss PHP notices like "undefined offset"
	 *
	 * The error handler is now disabled when running PHPUnit
	 *
	 * @since 3.0.4 N°6274
	 * @covers DeprecatedCallsLog::DeprecatedNoticesErrorHandler
	 */
	public function testPhpNoticeWithDeprecatedCallsLog(): void {
		$this->RequireOnceItopFile('core/log.class.inc.php');
		DeprecatedCallsLog::Enable(); // will set error handler
		$this->SetUndefinedOffsetExceptionToExpect();

		$aArray = [];
		if ('toto' === $aArray['tutu']) {
			//Do nothing, just raising a undefined offset warning
		}
	}
}
