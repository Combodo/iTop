<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core\Log;


use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DeprecatedCallsLog;
use LogAPI;
use utils;
use const APPROOT;
use const E_USER_DEPRECATED;

class DeprecatedCallsLogErrorHandlerTest extends ItopTestCase {
	public const DISABLE_DEPRECATEDCALLSLOG_ERRORHANDLER = false;

	/**
	 * @covers DeprecatedCallsLog::DeprecatedNoticesErrorHandler
	 * @since 3.0.4 3.1.1 3.2.0 N°6976
	 */
	public function testPhpLibMethodNoticeCatched():void {
		$oConfig = utils::GetConfig(true);
		$oConfig->Set('log_level_min', [\DeprecatedCallsLog::ENUM_CHANNEL_PHP_LIBMETHOD => LogAPI::LEVEL_WARNING]);

		$this->RequireOnceItopFile('core/log.class.inc.php');
		DeprecatedCallsLog::Enable(); // will set error handler

		$sNoticeMessage = __METHOD__.uniqid(' @trigger_error unique message - ', true);
		@trigger_error($sNoticeMessage, E_USER_DEPRECATED);

		// no notice when running in PHPUnit
		$sDeprecatedCallsLogFileContent = file_get_contents(APPROOT.DeprecatedCallsLog::LOG_DEPRECATED_CALLS_LOG_FILENAME);
		$this->assertStringContainsString($sNoticeMessage, $sDeprecatedCallsLogFileContent);
	}
}
