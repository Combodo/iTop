<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\Log;


use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use DeprecatedCallsLog;
use FileLog;
use IssueLog;
use LogAPI;
use utils;
use const E_USER_DEPRECATED;
use const ITOP_PHPUNIT_RUNNING_CONSTANT_NAME;

class DeprecatedCallsLogErrorHandlerTest extends ItopTestCase {
	public const DISABLE_DEPRECATEDCALLSLOG_ERRORHANDLER = false;

	/**
	 * @covers DeprecatedCallsLog::DeprecatedNoticesErrorHandler
	 * @since 3.0.4 3.1.1 3.2.0 N°6976
	 *
	 * @runInSeparateProcess so that other tests won't set the constant !
	 */
	public function testPhpLibMethodNoticeCatched():void {
		if (defined(ITOP_PHPUNIT_RUNNING_CONSTANT_NAME)) {
			// Should not happen thanks to the process isolation !
			$this->fail('Constant to disable error handler is set, so we cannot test :(');
		}

		$sNoticeMessage = __METHOD__.uniqid(' @trigger_error unique message - ', true);

		// to check that error handler is really set
		$oMockIssueLogFile = $this->createMock(FileLog::class);
		$oMockIssueLogFile->expects($this->exactly(1))
			->method(LogAPI::LEVEL_TRACE)
			->with($this->stringContains(DeprecatedCallsLog::class), DeprecatedCallsLog::ENUM_CHANNEL_PHP_LIBMETHOD, []);

		// to check the error handler is logging correctly
		$oMockDeprecatedLogFile = $this->createMock(FileLog::class);
		$oMockDeprecatedLogFile->expects($this->exactly(1))
			->method(LogAPI::LEVEL_WARNING)
			->with($this->stringContains($sNoticeMessage), DeprecatedCallsLog::ENUM_CHANNEL_PHP_LIBMETHOD, []);

		$oMockConfig = $this->createMock(Config::class);
		$oMockConfig
			->method("Get")
			->willReturnCallback(function ($sConfigParameterName) {
				if ($sConfigParameterName==='log_level_min'){
					return [
						DeprecatedCallsLog::ENUM_CHANNEL_PHP_LIBMETHOD => LogAPI::LEVEL_TRACE
					];
				}
				/** @noinspection NullPointerExceptionInspection */
				return utils::GetConfig()->Get($sConfigParameterName);
			});

		$this->RequireOnceItopFile('core/log.class.inc.php');
		IssueLog::Enable(APPROOT.'log/error.log'); // to get log when setting error handler
		IssueLog::MockStaticObjects($oMockIssueLogFile, $oMockConfig);
		DeprecatedCallsLog::Enable(); // will set error handler
		DeprecatedCallsLog::MockStaticObjects($oMockDeprecatedLogFile, $oMockConfig);

		@trigger_error($sNoticeMessage, E_USER_DEPRECATED);
	}
}
