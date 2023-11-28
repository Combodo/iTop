<?php
/*
 * Copyright (C) 2013-2023 Combodo SARL
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\UnitTest\Core\Log;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ExceptionLog;


require_once(__DIR__.'/ExceptionLogTest/Exceptions.php');

class ExceptionLogTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

	protected function setUp(): void
	{
		require_once(__DIR__.'/ExceptionLogTest/Exceptions.php');
		parent::setUp();
	}

	/**
	 * @dataProvider logProvider
	 */
	public function testLogInFile($aLevels, $aExceptions, $sChannel, $aExpectedWriteNumber, $logLevelMin, $aExpectedDbWriteNumber, $logLevelMinWriteInDb)
	{

		$mockFileLog = $this->createMock('FileLog');
		$oMockConfig = $this->createMock('Config');

		$oMockConfig
			->method("Get")
			->willReturnCallback(function ($code) use ($logLevelMin, $logLevelMinWriteInDb) {
				switch ($code) {
					case 'log_level_min':

						if (is_null($logLevelMin))
						{
							$logLevelMin = '';//this should be the default value, if it did change, please fix it here
						}

						return $logLevelMin;
					case 'log_level_min.write_in_db':

						if (is_null($logLevelMinWriteInDb))
						{
							$logLevelMinWriteInDb = [ 'Exception' => 'Error', ];//this should be the default value, if it did change, please fix it here
						}

						return $logLevelMinWriteInDb;
				}
				return '';
			});

		$aContext = ['contextKey1' => 'value'];

		foreach ($aLevels as $i => $sLevel) {
			$sExpectedFile = __FILE__;
			// @formatter:off
			$oException = new $aExceptions[$i]("Iteration number $i"); $sExpectedLine = __LINE__; //Both should remain on the same line
			// @formatter:on

			$iExpectedWriteNumber = $aExpectedWriteNumber[$i];
			$iExpectedDbWriteNumber = $aExpectedDbWriteNumber[$i];
			$aExpectedFileContext = array_merge($aContext, [
					'exception class'               => get_class($oException),
					'file'                          => $sExpectedFile,
					'line'                          => $sExpectedLine,
				]
			); //The context is preserved, and, if the key 'exception class' is not yet in the array, it is added
			$mockFileLog->expects($this->exactly($iExpectedWriteNumber))
				->method($sLevel)
				->with($oException->GetMessage(), $sChannel, $aExpectedFileContext);

			ExceptionLog::MockStaticObjects($mockFileLog, $oMockConfig);

			ExceptionLog::LogException($oException, $aContext, $sLevel);

			$oExpectedLastEventIssue = $this->InvokeNonPublicStaticMethod('ExceptionLog', 'GetLastEventIssue', []);

			if (0 == $iExpectedDbWriteNumber) {
				if (!is_null($oExpectedLastEventIssue)) {
					$this->fail("Level '$sLevel': unexpected EventIssue");
				}
			} else {
				$this->assertInstanceOf(\EventIssue::class, $oExpectedLastEventIssue, "Level '$sLevel': missing EventIssue");
				$this->assertEquals($aExpectedFileContext, $oExpectedLastEventIssue->Get('data'));
			}
		}
	}

	/**
	 * @return array[]
	 * aLevels: log levels to iterate on (AND name of the method that will be called on the underlying FileLog)
	 * aExceptions: For each log level => Exception to generate
	 * sChannel: Expected 2nd argument to the FileLog::{Level}
	 * aExpectedWriteNumber: For each log level => Number of times the method FileLog::{Level} will be called
	 * logLevelMin: Configuration / log_level_min
	 * iExpectedDbWriteNumber: For each log level => 1 if at least ONE EventIssue has been recorded into the DB
	 * logLevelMinWriteInDb: Configuration / log_level_min.write_in_db
	 */
	public function logProvider()
	{
		return [
			'use parent' => [
				'aLevels' => ['Debug'],
				'aExceptions' => [\GrandChildException::class],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => ['Exception' => 'Debug'],
				'iExpectedDbWriteNumber' => [1],
				'logLevelMinWriteInDb' => ['Exception' => 'Debug'],
			],
			'flat configuration' => [
				'aLevels' => ['Error'],
				'aExceptions' => [\GrandChildException::class],
				'sChannel' => 'GrandChildException',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => 'Error',
				'iExpectedDbWriteNumber' => [1],
				'logLevelMinWriteInDb' => 'Error',
			],
			'Default conf has expected levels' => [
				'aLevels' => ['Debug', 'Warning'],
				'aExceptions' => [\GrandChildException::class, \GrandChildException::class],
				'sChannel' => 'GrandChildException',
				'aExpectedWriteNumber' => [0, 1],
				'logLevelMin' => null,
				'iExpectedDbWriteNumber' => [0, 0],
				'logLevelMinWriteInDb' => null,
			],
			'use correct order in inheritance tree' => [
				'aLevels' => ['Trace', 'Debug', 'Info', 'Error'],
				'aExceptions' => [\GrandChildException::class, \GrandChildException::class, \GrandChildException::class, \GrandChildException::class],
				'sChannel' => 'GrandChildException',
				'aExpectedWriteNumber' => [0, 1, 1, 1],
				'logLevelMin' => ['ChildException' => 'Error', 'GrandChildException' => 'Debug', ],
				'iExpectedDbWriteNumber' => [0, 1, 1, 1],
				'logLevelMinWriteInDb' => ['ChildException' => 'Error', 'GrandChildException' => 'Debug', ],
			],
			'handle namespaced classes' => [
				'aLevels' => ['Debug'],
				'aExceptions' => [\Namespaced\Exception\ExceptionInNamespace::class],
				'sChannel' => 'Namespaced\Exception\ExceptionInNamespace',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => ['Namespaced\Exception\ExceptionInNamespace' => 'Debug'],
				'iExpectedDbWriteNumber' => [1],
				'logLevelMinWriteInDb' => ['Namespaced\Exception\ExceptionInNamespace' => 'Debug'],
			],
			'not enabled by default' => [
				'aLevels' => ['Debug'],
				'aExceptions' => [\Exception::class],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [0],
				'logLevelMin' => null,
				'iExpectedDbWriteNumber' => [0],
				'logLevelMinWriteInDb' => null,
			],
			'explicitly disabled' => [
				'aLevels' => ['Info'],
				'aExceptions' => [\Exception::class],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [0],
				'logLevelMin' => ['Exception' => false],
				'iExpectedDbWriteNumber' => [0],
				'logLevelMinWriteInDb' =>  ['Exception' => false],
			],
			'default channel, default conf' => [
				'aLevels' => ['Warning'],
				'aExceptions' => [\Exception::class],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => null,
				'iExpectedDbWriteNumber' => [0],
				'logLevelMinWriteInDb' => null,
			],
			'enabled' => [
				'aLevels' => ['Debug'],
				'aExceptions' => [\Exception::class],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => ['Exception' => 'Debug'],
				'iExpectedDbWriteNumber' => [1],
				'logLevelMinWriteInDb' => ['Exception' => 'Debug'],
			],
			'file: 2 enabled, 2 filtered, db: 1 enabled, 3 filtered' => [
				'aLevels'                => ['Debug', 'Trace', 'Warning', 'Error'],
				'aExceptions'            => [\Exception::class, \Exception::class, \Exception::class, \Exception::class],
				'sChannel'               => 'Exception',
				'aExpectedWriteNumber'   => [0, 0, 1, 1],
				'logLevelMin'            => null,
				'iExpectedDbWriteNumber' => [0, 0, 0, 1],
				'logLevelMinWriteInDb'   => null,
			],
			'Simple Error (testing Throwable signature)' => [
				'aLevels'                => ['Error'],
				'aExceptions'            => [\Error::class],
				'sChannel'               => 'Error',
				'aExpectedWriteNumber'   => [1],
				'logLevelMin'            => 'Error',
				'iExpectedDbWriteNumber' => [1],
				'logLevelMinWriteInDb'   => 'Error',
			],
			"use '' to enable all" => [
				'aLevels' => ['Error'],
				'aExceptions' => [\GrandChildException::class, \Exception::class],
				'sChannel' => 'GrandChildException',
				'aExpectedWriteNumber' => [1, 1],
				'logLevelMin' => ['' => 'Error'],
				'iExpectedDbWriteNumber' => [1, 1],
				'logLevelMinWriteInDb' => ['' => 'Error'],
			],
		];
	}

	/**
	 * @dataProvider exceptionClassProvider
	 */
	public function testExceptionClassFromHierarchy($aLogConfig, $sActualExceptionClass, $sExpectedExceptionClass)
	{
		$oMockConfig = $this->createMock('Config');

		$oMockConfig
			->method('Get')
			->willReturn($aLogConfig);

		ExceptionLog::MockStaticObjects(null, $oMockConfig);
		$sReturnedExceptionClass = $this->InvokeNonPublicStaticMethod(ExceptionLog::class, 'ExceptionClassFromHierarchy', [$sActualExceptionClass]);

		static::assertEquals($sExpectedExceptionClass, $sReturnedExceptionClass, 'Not getting correct exception in hierarchy !');
	}

	public function exceptionClassProvider()
	{
		// WARNING : cannot use Exception::class or LogAPI constants for levels :/
		return [
			'Exception, defined in config'                          => [
				'aLogConfig'              => ['Exception' => 'Debug'],
				'sActualExceptionClass'   => 'Exception',
				'sExpectedExceptionClass' => 'Exception',
			],
			'Child of Exception, Exception defined in config'       => [
				'aLogConfig'              => ['Exception' => 'Debug'],
				'sActualExceptionClass'   => 'ChildException',
				'sExpectedExceptionClass' => 'Exception',
			],
			'Grand child of Exception, Exception defined in config' => [
				'aLogConfig'              => ['Exception' => 'Debug'],
				'sActualExceptionClass'   => 'GrandChildException',
				'sExpectedExceptionClass' => 'Exception',
			],
			'Exception, just a default level defined in config'     => [
				'aLogConfig'              => 'Debug',
				'sActualExceptionClass'   => 'Exception',
				'sExpectedExceptionClass' => null,
			],
			'Exception, no exception class defined in config'       => [
				'aLogConfig'              => ['IssueLog' => 'Debug'],
				'sActualExceptionClass'   => 'Exception',
				'sExpectedExceptionClass' => null,
			],
			'Exception, just the child defined in config'           => [
				'aLogConfig'              => ['ChildException' => 'Debug'],
				'sActualExceptionClass'   => 'Exception',
				'sExpectedExceptionClass' => null,
			],
			'Exception, Exception and the child defined in config'  => [
				'aLogConfig'              => ['Exception' => 'Debug', 'ChildException' => 'Debug'],
				'sActualExceptionClass'   => 'Exception',
				'sExpectedExceptionClass' => 'Exception',
			],
		];
	}
	public function testGetLevelDefault()
	{
		$resultDb = $this->InvokeNonPublicStaticMethod(\ExceptionLog::class, 'GetLevelDefault', [\ExceptionLog::ENUM_CONFIG_PARAM_DB]);
		$resultFile = $this->InvokeNonPublicStaticMethod(\ExceptionLog::class, 'GetLevelDefault', [\ExceptionLog::ENUM_CONFIG_PARAM_FILE]);
		$resultFilePerDefaultWhenKeyNotFound = $this->InvokeNonPublicStaticMethod(\ExceptionLog::class, 'GetLevelDefault', ['foo']);

		$this->assertEquals(false, $resultDb);
		$this->assertEquals('Ok', $resultFile);
		$this->assertEquals('Ok', $resultFilePerDefaultWhenKeyNotFound);
	}
}



