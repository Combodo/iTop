<?php


namespace Combodo\iTop\Test\UnitTest\Core;


use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DailyRotatingLogFileNameBuilder;
use DateTime;
use WeeklyRotatingLogFileNameBuilder;

class LogFileNameBuilderTest extends ItopTestCase
{
	public function ShouldRotateProvider()
	{
		return array(
			'WEEKLY Same week' => array('WeeklyRotatingLogFileNameBuilder', '2020-02-01 00:00', '2020-02-01 00:00', false),
			'WEEKLY 1 week diff, same month' => array('WeeklyRotatingLogFileNameBuilder', '2020-02-01 00:00', '2020-02-08 00:00', true),
			'WEEKLY 2 weeks diff, same month' => array('WeeklyRotatingLogFileNameBuilder', '2020-02-01 00:00', '2020-02-15 00:00', true),
			'WEEKLY 1 week diff, different month' => array('WeeklyRotatingLogFileNameBuilder', '2020-01-27 00:00', '2020-02-03 00:00', true),
			'WEEKLY same week, different month' => array('WeeklyRotatingLogFileNameBuilder', '2020-01-27 00:00', '2020-02-02 00:00', false),
			'WEEKLY 1 week diff, different year' => array('WeeklyRotatingLogFileNameBuilder', '2019-12-30 00:00', '2020-01-06 00:00', true),
			'WEEKLY same week, different year' => array('WeeklyRotatingLogFileNameBuilder', '2019-12-30 00:00', '2020-01-05 00:00', true),
			'DAILY Same day' => array('DailyRotatingLogFileNameBuilder', '2020-02-01 00:00', '2020-02-01 15:42', false),
			'DAILY Same week, different day' => array('DailyRotatingLogFileNameBuilder', '2020-02-01 00:00', '2020-02-02 00:00', true),
			'DAILY 1 week diff' => array('DailyRotatingLogFileNameBuilder', '2020-02-01 00:00', '2020-02-08 00:00', true),
		);
	}

	/**
	 * @param string $sFileNameBuilderClass RotatingLogFileNameBuilder impl
	 * @param string $sDateModified format Y-m-d H:i
	 * @param string $sDateNow format Y-m-d H:i
	 * @param bool $bExpected
	 *
	 * @dataProvider ShouldRotateProvider
	 */
	public function testShouldRotate($sFileNameBuilderClass, $sDateModified, $sDateNow, $bExpected)
	{
		$oDateModified = DateTime::createFromFormat('Y-m-d H:i', $sDateModified);
		$oDateNow = DateTime::createFromFormat('Y-m-d H:i', $sDateNow);

		/** @var \RotatingLogFileNameBuilder $oFileBuilder */
		$oFileBuilder = new $sFileNameBuilderClass();
		$bShouldRotate = $oFileBuilder->ShouldRotate($oDateModified, $oDateNow);

		$this->assertEquals($bExpected, $bShouldRotate);
	}

	public function CronNextOccurrenceProvider()
	{
		return array(
			'DAILY morning' => array('DailyRotatingLogFileNameBuilder', '2020-02-01 05:00', '2020-02-02 00:00'),
			'DAILY midnight' => array('DailyRotatingLogFileNameBuilder', '2020-02-01 00:00', '2020-02-02 00:00'),
			'WEEKLY monday 12:42' => array('WeeklyRotatingLogFileNameBuilder', '2020-02-03 12:42', '2020-02-10 00:00'),
			'WEEKLY monday 00:00' => array('WeeklyRotatingLogFileNameBuilder', '2020-02-03 00:00', '2020-02-10 00:00'),
			'WEEKLY tuesday 12:42' => array('WeeklyRotatingLogFileNameBuilder', '2020-02-04 12:42', '2020-02-10 00:00'),
			'WEEKLY sunday 12:42' => array('WeeklyRotatingLogFileNameBuilder', '2020-02-02 12:42', '2020-02-03 00:00'),
		);
	}

	/**
	 * @param string $sFileNameBuilderClass RotatingLogFileNameBuilder impl
	 * @param string $sDateNow format Y-m-d H:i
	 * @param string $sExpectedOccurrence format Y-m-d H:i
	 *
	 * @dataProvider CronNextOccurrenceProvider
	 */
	public function testCronNextOccurrence($sFileNameBuilderClass, $sDateNow, $sExpectedOccurrence)
	{
		$oDateNow = DateTime::createFromFormat('Y-m-d H:i', $sDateNow);

		/** @var \RotatingLogFileNameBuilder $oFileBuilder */
		$oFileBuilder = new $sFileNameBuilderClass();
		$oActualOccurrence = $oFileBuilder->GetCronProcessNextOccurrence($oDateNow);
		$sActualOccurrence = $oActualOccurrence->format('Y-m-d H:i');

		$this->assertEquals($sExpectedOccurrence, $sActualOccurrence);
	}
}