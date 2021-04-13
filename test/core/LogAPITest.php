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
		];
	}
	
	/**
	 * @dataProvider LogWarningWithASpecificChannelProvider
	 * @test
	 * @backupGlobals disabled
	 */
	public function TestLogWarningWithASpecificChannel($expectedCallNb, $sExpectedLevel, $ConfigReturnedObject, $bExceptionRaised=false)
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
		catch(\Exception $e)
		{
			if (!$bExceptionRaised)
			{
				$this->fail("raised should NOT have been raised");
			}
		}
	}

	public function LogWarningWithASpecificChannelProvider()
	{
		return [
			"empty config" => [ 0, "Ok", ''],
			"Default Unknown Level" => [ 0, "Ok", 'TotoLevel', true],
			"Info as Default Level" => [ 1 , "Warning", 'Info'],
			"Error as Default Level" => [ 0, "Warning", 'Error'],
			"Empty array" => [ 0, "Ok", array()],
			"Channel configured on an undefined level" => [ 0, "Ok", ["GaBuZoMeuChannel" => "TotoLevel"], true],
			"Channel defined with Error" => [ 0, "Warning", ["GaBuZoMeuChannel" => "Error"]],
			"Channel defined with Info" => [ 1, "Warning", ["GaBuZoMeuChannel" => "Info"]],
		];
	}

	/**
	 * @dataProvider LogOkWithASpecificChannel
	 * @test
	 * @backupGlobals disabled
	 */
	public function TestLogOkWithASpecificChannel($expectedCallNb, $sExpectedLevel, $ConfigReturnedObject, $bExceptionRaised=false)
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
			\IssueLog::Ok("log msg", "GaBuZoMeuChannel");
			if ($bExceptionRaised)
			{
				$this->fail("raised should have been raised");
			}
		}
		catch(\Exception $e)
		{
			if (!$bExceptionRaised)
			{
				$this->fail("raised should NOT have been raised");
			}
		}
	}

	public function LogOkWithASpecificChannel()
	{
		return [
			"empty config" => [ 1, "Ok", ''],
			"Empty array" => [ 1, "Ok", array()],
		];
	}

}
