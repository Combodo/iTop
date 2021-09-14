<?php
/*
 * Copyright (C) 2013-2021 Combodo SARL
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
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use ExceptionLog;




class ExceptionLogTest extends ItopDataTestCase
{
	private $mockFileLog;
	private $oMetaModelConfig;

	protected function setUp()
	{
		require_once (__DIR__.'/ExceptionLogTest/Exceptions.php');
		parent::setUp();

		$oConfig = \MetaModel::GetConfig();
		$oConfig->Set('developer_mode.enabled', false);

		$this->mockFileLog = $this->createMock('FileLog');
		$this->oMetaModelConfig = $this->createMock('Config');
	}

	/**
	 * @dataProvider logProvider
	 */
	public function testLogInFile($aLevels, $aExceptions, $sChannel, $aExpectedWriteNumber, $logLevelMin, $aExpectedDbWriteNumber, $logLevelMinWriteInDb)
	{
		ExceptionLog::Enable();

		$this->oMetaModelConfig
			->method("Get")
			->willReturnCallback(function ($code) use ($logLevelMin, $logLevelMinWriteInDb) {
				switch ($code) {
					case 'log_level_min':

						if (is_null($logLevelMin))
						{
							$oConf = \MetaModel::GetConfig();
							$logLevelMin = $oConf->Get('log_level_min');
						}

						return $logLevelMin;
					case 'log_level_min.write_in_db':

						if (is_null($logLevelMinWriteInDb))
						{
							$oConf = \MetaModel::GetConfig();
							$logLevelMinWriteInDb = $oConf->Get('log_level_min.write_in_db');
						}

						return $logLevelMinWriteInDb;
				}
				return '';
			});

		$aContext = ['contextKey1' => 'value'];

		foreach ($aLevels as $i => $sLevel) {
			$oException = $aExceptions[$i];
			$iExpectedWriteNumber = $aExpectedWriteNumber[$i];
			$iExpectedDbWriteNumber = $aExpectedDbWriteNumber[$i];
			$aExpectedContext = array_merge($aContext, ['exception' => $oException, 'exception class' => get_class($oException)]); //The context is preserved, and, if the key 'exception' is not yet in the array, it is added
			$this->mockFileLog->expects($this->exactly($iExpectedWriteNumber))
				->method($sLevel)
				->with($oException->GetMessage(), $sChannel, $aExpectedContext)
			;

			ExceptionLog::MockStaticObjects($this->mockFileLog, $this->oMetaModelConfig);

			ExceptionLog::FromException($oException, $aContext, $sLevel);

			$oExpectedLastEventIssue = $this->InvokeNonPublicStaticMethod('ExceptionLog', 'getLastEventIssue', []);

			if (0 == $iExpectedDbWriteNumber) {
				$this->assertNull($oExpectedLastEventIssue);
			} else {
				$this->assertInstanceOf(\EventIssue::class, $oExpectedLastEventIssue);
				$this->assertEquals($aExpectedContext, $oExpectedLastEventIssue->Get('data'));
			}
		}
	}

	public function logProvider()
	{
		require_once (__DIR__.'/ExceptionLogTest/Exceptions.php');

		return [
			'use parent' => [
				'aLevels' => ['Debug'],
				'aExceptions' => [new \GrandChildException('Foo')],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => ['Exception' => 'Debug'],
				'iExpectedDbWriteNumber' => [1],
				'logLevelMinWriteInDb' => ['Exception' => 'Debug'],
			],
			'flat configuration' => [
				'aLevels' => ['Debug'],
				'aExceptions' => [new \GrandChildException('Foo')],
				'sChannel' => 'GrandChildException',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => 'Debug',
				'iExpectedDbWriteNumber' => [1],
				'logLevelMinWriteInDb' => 'Debug',
			],
			'Default conf has expected levels' => [
				'aLevels' => ['Debug', 'Warning'],
				'aExceptions' => [new \GrandChildException('I am first'), new \GrandChildException('I am 2d')],
				'sChannel' => 'GrandChildException',
				'aExpectedWriteNumber' => [0, 1],
				'logLevelMin' => null,
				'iExpectedDbWriteNumber' => [0, 0],
				'logLevelMinWriteInDb' => null,
			],
			'use correct order in inheritance tree' => [
				'aLevels' => ['Trace', 'Debug', 'Info', 'Error'],
				'aExceptions' => [new \GrandChildException('I am first'), new \GrandChildException('I am 2d'), new \GrandChildException('I am 3rd'), new \GrandChildException('I am 4th')],
				'sChannel' => 'GrandChildException',
				'aExpectedWriteNumber' => [0, 1, 1, 1],
				'logLevelMin' => ['ChildException' => 'Error', 'GrandChildException' => 'Debug', ],
				'iExpectedDbWriteNumber' => [0, 1, 1, 1],
				'logLevelMinWriteInDb' => ['ChildException' => 'Error', 'GrandChildException' => 'Debug', ],
			],
			'handle namespaced classes' => [
				'aLevels' => ['Debug'],
				'aExceptions' => [new \Namespaced\Exception\ExceptionInNamespace('Foo')],
				'sChannel' => 'Namespaced\Exception\ExceptionInNamespace',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => ['Namespaced\Exception\ExceptionInNamespace' => 'Debug'],
				'iExpectedDbWriteNumber' => [1],
				'logLevelMinWriteInDb' => ['Namespaced\Exception\ExceptionInNamespace' => 'Debug'],
			],
			'not enabled by default' => [
				'aLevels' => ['Debug'],
				'aExceptions' => [new \Exception('Foo')],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [0],
				'logLevelMin' => null,
				'iExpectedDbWriteNumber' => [0],
				'logLevelMinWriteInDb' => null,
			],
			'explicitly disabled' => [
				'aLevels' => ['Info'],
				'aExceptions' => [new \Exception('Foo')],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [0],
				'logLevelMin' => ['Exception' => 'Error'],
				'iExpectedDbWriteNumber' => [0],
				'logLevelMinWriteInDb' =>  ['Exception' => 'Error'],
			],
			'default channel, default conf' => [
				'aLevels' => ['Warning'],
				'aExceptions' => [new \Exception('Foo')],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => null,
				'iExpectedDbWriteNumber' => [0],
				'logLevelMinWriteInDb' => null,
			],
			'enabled' => [
				'aLevels' => ['Debug'],
				'aExceptions' => [new \Exception('Foo')],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [1],
				'logLevelMin' => ['Exception' => 'Debug'],
				'iExpectedDbWriteNumber' => [1],
				'logLevelMinWriteInDb' => ['Exception' => 'Debug'],
			],
			'file: 2 enabled, 2 filtered, db: 1 enabled, 3 filtered' => [
				'aLevels' => ['Debug', 'Trace', 'Warning', 'Error'],
				'aExceptions' => [new \Exception('I am first'), new \Exception('I am 2d'), new \Exception('I am 3rd'), new \Exception('I am 4th')],
				'sChannel' => 'Exception',
				'aExpectedWriteNumber' => [0, 0, 1, 1],
				'logLevelMin' => null,
				'iExpectedDbWriteNumber' => [0, 0, 0, 1],
				'logLevelMinWriteInDb' => null,
			],
		];
	}

}



