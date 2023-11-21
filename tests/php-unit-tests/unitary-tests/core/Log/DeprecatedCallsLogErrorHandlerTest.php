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
use LogAPI;
use utils;
use const E_USER_DEPRECATED;

class DeprecatedCallsLogErrorHandlerTest extends ItopTestCase {
	public const DISABLE_DEPRECATEDCALLSLOG_ERRORHANDLER = false;

	/**
	 * @covers DeprecatedCallsLog::DeprecatedNoticesErrorHandler
	 * @since 3.0.4 3.1.1 3.2.0 N°6976
	 */
	public function testPhpLibMethodNoticeCatched():void {
		$sNoticeMessage = __METHOD__.uniqid(' @trigger_error unique message - ', true);

		$oMockFileLog = $this->createMock(FileLog::class);
		$oMockFileLog->expects($this->exactly(1))
			->method(LogAPI::LEVEL_WARNING)
			->with($this->stringContains($sNoticeMessage), DeprecatedCallsLog::ENUM_CHANNEL_PHP_LIBMETHOD, []);

		$oMockConfig = $this->createMock(Config::class);
		$oMockConfig
			->method("Get")
			->willReturnCallback(function ($sConfigParameterName) {
				if ($sConfigParameterName==='log_level_min'){
					return [DeprecatedCallsLog::ENUM_CHANNEL_PHP_LIBMETHOD => LogAPI::LEVEL_WARNING];
				}
				/** @noinspection NullPointerExceptionInspection */
				return utils::GetConfig()->Get($sConfigParameterName);
			});

		$this->RequireOnceItopFile('core/log.class.inc.php');
		DeprecatedCallsLog::Enable(); // will set error handler
		DeprecatedCallsLog::MockStaticObjects($oMockFileLog, $oMockConfig);

		@trigger_error($sNoticeMessage, E_USER_DEPRECATED);
	}
}
