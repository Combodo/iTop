<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\Log;


use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DeprecatedCallsLog;

class DeprecatedCallsLogTest extends ItopTestCase {
	public function testPhpNoticeWithoutDeprecatedCallsLog(): void {
		$this->expectNotice();

		$aArray = [];
		if ('toto' === $aArray['tutu']) {
			//Do nothing, just raising a undefined offset warning
		}
	}

	/**
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
		$this->expectNotice();

		$aArray = [];
		if ('toto' === $aArray['tutu']) {
			//Do nothing, just raising a undefined offset warning
		}
	}
}
