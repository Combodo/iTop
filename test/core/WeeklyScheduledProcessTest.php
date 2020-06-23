<?php
namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use DateTime;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class WeeklyScheduledProcessTest extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'core/backgroundprocess.inc.php');
		require_once(APPROOT.'test/core/WeeklyScheduledProcessMockConfig.php');
	}


	/**
	 * @dataProvider GetNextOccurrenceProvider
	 * @test
	 *
	 * @param boolean $bEnabledValue true if task is enabled
	 * @param string $sCurrentTime Date string for current time, eg '2020-01-01 23:30'
	 * @param string $sTimeValue time to run that is set in the config, eg '23:30'
	 * @param string $sExpectedTime next occurrence that should be returned
	 *
	 * @throws \ProcessInvalidConfigException
	 */
	public function TestGetNextOccurrence($bEnabledValue, $sCurrentTime, $sTimeValue, $sExpectedTime)
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig($bEnabledValue, $sTimeValue);

		$sItopTimeZone = $oWeeklyImpl->getOConfig()->Get('timezone');
		$timezone = new \DateTimeZone($sItopTimeZone);
		$oExpectedDateTime = new DateTime($sExpectedTime, $timezone);

		$this->assertEquals($oExpectedDateTime, $oWeeklyImpl->GetNextOccurrence($sCurrentTime));
	}

	public function GetNextOccurrenceProvider()
	{
		return array(
			'Disabled process' => array(false, 'now', null, '3000-01-01'),
			'working day same day, prog noon and current before noon'         => array(true, '2020-05-11 11:00',   '12:00',   '2020-05-11 12:00'),
			'working day same day, prog noon and current is noon'             => array(true, '2020-05-11 12:00',   '12:00',   '2020-05-12 12:00'),
			'working day same day, prog noon and current after noon'          => array(true, '2020-05-11 13:00',   '12:00',   '2020-05-12 12:00'),
			'saturday, prog noon and current before noon'                     => array(true, '2020-05-09 11:00',   '12:00',   '2020-05-11 12:00'),
			'saturday, prog noon and current is noon'                         => array(true, '2020-05-09 12:00',   '12:00',   '2020-05-11 12:00'),
			'saturday, prog noon and current after noon'                      => array(true, '2020-05-09 13:00',   '12:00',   '2020-05-11 12:00'),
			'sunday, prog noon and current before noon'                       => array(true, '2020-05-10 11:00',   '12:00',   '2020-05-11 12:00'),
			'sunday, prog noon and current is noon'                           => array(true, '2020-05-10 12:00',   '12:00',   '2020-05-11 12:00'),
			'sunday, prog noon and current after noon'                        => array(true, '2020-05-10 13:00',   '12:00',   '2020-05-11 12:00'),
			'working day, day before, prog noon and current before midnight'  => array(true, '2020-05-11 23:59',   '00:00',   '2020-05-12 00:00'),
			'working day same day, prog noon and current is midnight'         => array(true, '2020-05-12 00:00',   '00:00',   '2020-05-13 00:00'),
			'working day same day, prog noon and current after midnight'      => array(true, '2020-05-12 00:01',   '00:00',   '2020-05-13 00:00'),
		);
	}
}

