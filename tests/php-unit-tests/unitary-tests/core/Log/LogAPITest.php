<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 31/08/2018
 * Time: 17:03
 */

namespace Combodo\iTop\Test\UnitTest\Core\Log;


use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class LogAPITest extends ItopDataTestCase
{
	private $mockFileLog;
	private $oMetaModelConfig;

	protected function setUp():void
	{
		parent::setUp();

		$this->mockFileLog = $this->createMock('FileLog');
		$this->oMetaModelConfig = $this->createMock('Config');
	}


	/**
	 * @dataProvider LogApiProvider
	 * @test
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
			[$this->oMetaModelConfig, "log msg", '', "Error", "log msg"],
			[$this->oMetaModelConfig, "log msg", 'PoudlardChannel', "Error", "log msg", 'PoudlardChannel'],
			[null, "log msg", '', "Error", "log msg"],
		];
	}

	/**
	 * @dataProvider LogWarningWithASpecificChannelProvider
	 * @test
	 */
	public function TestLogWarningWithASpecificChannel($expectedCallNb, $sExpectedLevel, $ConfigReturnedObject, $bExceptionRaised=false)
	{
		$this->oMetaModelConfig
			->method("Get")
			->willReturnMap([
				[\LogAPI::ENUM_CONFIG_PARAM_FILE, $ConfigReturnedObject],
				[\LogAPI::ENUM_CONFIG_PARAM_DB, $ConfigReturnedObject],
			]);

		\IssueLog::MockStaticObjects($this->mockFileLog, $this->oMetaModelConfig);

		$this->mockFileLog->expects($this->exactly($expectedCallNb))
			->method($sExpectedLevel)
			->with("log msg", "GaBuZoMeuChannel");

		try{
			\IssueLog::Warning("log msg", "GaBuZoMeuChannel");
			if ($bExceptionRaised) {
				$this->fail("raised should have been raised");
			}
		}
		catch(\Exception $e) {
			if (!$bExceptionRaised) {
				$this->fail("raised should NOT have been raised");
			}
		}
	}

	public function LogWarningWithASpecificChannelProvider()
	{
		return [
			"empty config"                             => [ 0, "Ok", ''],
			"Default Unknown Level"                    => [ 0, "Ok", 'TotoLevel', true],
			"Info as Default Level"                    => [ 1 , "Warning", 'Info'],
			"Error as Default Level"                   => [ 0, "Warning", 'Error'],
			"Empty array"                              => [ 0, "Ok", array()],
			"Channel configured on an undefined level" => [ 0, "Ok", ["GaBuZoMeuChannel" => "TotoLevel"], true],
			"Channel defined with Error"               => [ 0, "Warning", ["GaBuZoMeuChannel" => "Error"]],
			"Channel defined with Info"                => [ 1, "Warning", ["GaBuZoMeuChannel" => "Info"]],
		];
	}

	/**
	 * @dataProvider LogOkWithASpecificChannel
	 * @test
	 */
	public function TestLogOkWithASpecificChannel($expectedCallNb, $sExpectedLevel, $ConfigReturnedObject, $bExceptionRaised=false)
	{
		$this->oMetaModelConfig
			->method("Get")
			->willReturnMap([
				[\LogAPI::ENUM_CONFIG_PARAM_FILE, $ConfigReturnedObject],
				[\LogAPI::ENUM_CONFIG_PARAM_DB, $ConfigReturnedObject],
			]);

		\IssueLog::MockStaticObjects($this->mockFileLog, $this->oMetaModelConfig);

		$this->mockFileLog->expects($this->exactly($expectedCallNb))
			->method($sExpectedLevel)
			->with("log msg", "GaBuZoMeuChannel");

		try {
			\IssueLog::Ok("log msg", "GaBuZoMeuChannel");
			if ($bExceptionRaised) {
				$this->fail("raised should have been raised");
			}
		}
		catch (\Exception $e) {
			if (!$bExceptionRaised) {
				$this->fail("raised should NOT have been raised");
			}
		}
	}

	public function LogOkWithASpecificChannel()
	{
		return [
			"empty config" => [1, "Ok", ''],
			"Empty array"  => [1, "Ok", array()],
		];
	}

	/**
	 * Tests that we are creating a valid object, with all its mandatory fields set !
	 *
	 * @throws \CoreException
	 */
	public function testGetEventIssue(): void
	{
		$oEventIssue = $this->InvokeNonPublicStaticMethod(\LogAPI::class, 'GetEventIssue', [
			'My message',
			\LogChannels::CORE,
			['context' => 'hop'],
		]);

		// Finding mandatory fields in EventIssue class
		$aEventIssueAllAttributes = \MetaModel::ListAttributeDefs(\EventIssue::class);
		$aEventIssueMandatoryAttributes = array_filter($aEventIssueAllAttributes, static function ($oAttDef, $sAttCode) {
			if (false === $oAttDef->IsNullAllowed()) {
				return $oAttDef;
			}
		}, ARRAY_FILTER_USE_BOTH);

		// remove fields set in the OnInsert method
		unset($aEventIssueMandatoryAttributes['page']);

		foreach ($aEventIssueMandatoryAttributes as $sAttCode => $oAttDef) {
			$this->assertNotEmpty($oEventIssue->Get($sAttCode), "In the EventIssue instance returned by LogAPI the '$sAttCode' mandatory attr is empty :(");
		}
	}


	public function testGetLevelDefault()
	{
		$resultDb = $this->InvokeNonPublicStaticMethod(\LogAPI::class, 'GetLevelDefault', [\LogAPI::ENUM_CONFIG_PARAM_DB]);
		$resultFile = $this->InvokeNonPublicStaticMethod(\LogAPI::class, 'GetLevelDefault', [\LogAPI::ENUM_CONFIG_PARAM_FILE]);
		$resultFilePerDefaultWhenKeyNotFound = $this->InvokeNonPublicStaticMethod(\LogAPI::class, 'GetLevelDefault', ['foo']);

		$this->assertEquals(false, $resultDb);
		$this->assertEquals('Ok', $resultFile);
		$this->assertEquals('Ok', $resultFilePerDefaultWhenKeyNotFound);
	}

}
