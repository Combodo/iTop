<?php

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 31/08/2018
 * Time: 17:03
 */

namespace Combodo\iTop\Test\UnitTest\Core;


use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class LogAPITest extends ItopTestCase
{
	private $mockFileLog;
	private $oMetaModelConfig;

	protected function setUp()
	{
		parent::setUp();
		$this->mockFileLog = $this->createMock('FileLog');
		$this->oMetaModelConfig = $this->createMock('Config');
	}


	/**
	/**
	 * @dataProvider LogApiProvider
	 * @test
	 * @backupGlobals disabled
	 */
	public function TestLogApi($oConfigObject, $sMessage, $Channel, $sExpectedLevel, $sExpectedMessage, $sExpectedChannel = '')
	{
		\IssueLog::MockStaticObjects($this->mockFileLog, $oConfigObject);

		$this->mockFileLog->expects($this->exactly(1))
			->method($sExpectedLevel)
			->with($sExpectedMessage, $sExpectedChannel);

		\IssueLog::Error($sMessage, $Channel);
	}

	public function LogApiProvider()
	{
		return [
			[ $this->oMetaModelConfig, "log msg", '' , "Error", "log msg"],
			[ $this->oMetaModelConfig, "log msg", 'PoudlardChannel' , "Error", "log msg", 'PoudlardChannel'],
			[  array(), "log msg", '' , "Error", "log msg"], // Bruno?
		];
	}

	/**
	/** TODISCUSS
	 * @test
	 * @backupGlobals disabled
	 */
	public function TestUnknownLevel()
	{
		$this->mockFileLog->expects($this->exactly(1))
			->method("Error")
			->with("invalid log level 'TotoLevel'");

		\IssueLog::Log('TotoLevel', "log msg");
	}

	/**
	/**
	 * @dataProvider LogWithChannelLogLevelApiProvider
	 * @test
	 * @backupGlobals disabled
	 */
	public function TestLogWithChannelLogLevelApi($expectedCallNb, $sExpectedLevel, $ConfigReturnedObject, $bExceptionRaised=false)
	{
		$this->oMetaModelConfig
			->method("Get")
			->with('log_level_min')
			->willReturn($ConfigReturnedObject);

		\IssueLog::MockStaticObjects($this->mockFileLog, $this->oMetaModelConfig);

		$this->mockFileLog->expects($this->exactly($expectedCallNb))
			->method($sExpectedLevel)
			->with("log msg", "GaBuZoMeuChannel");

		try{
			\IssueLog::Warning("log msg", "GaBuZoMeuChannel");
			if ($bExceptionRaised)
			{
				$this->fail("raised should have been raised");
			}
		}
		catch(Exception $e)
		{
			if (!$bExceptionRaised)
			{
				$this->fail("raised should NOT have been raised");
			}
		}
	}

	public function LogWithChannelLogLevelApiProvider()
	{
		return [
			[ 0, "Ok", ''],
			[ 0, "Ok", 'TotoLevel'],
			[ 0, "Ok", array()],
			[ 0, "Ok", ["GaBuZoMeuChannel" => "TotoLevel"], true],
			[ 1, "Error", ["GaBuZoMeuChannel" => "Error"]],
			[ 0, "Info", ["GaBuZoMeuChannel" => "Info"]],
		];
	}

}
