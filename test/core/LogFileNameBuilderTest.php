<?php


namespace Combodo\iTop\Test\UnitTest\Core;


use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DailyRotatingLogFileNameBuilder;
use DateTime;
use WeeklyRotatingLogFileNameBuilder;

class LogFileNameBuilderTest extends ItopTestCase
{
	public function WeeklyRotationProvider()
	{
		return array(
			'Same week' => array('2020-02-01', '2020-02-01', false),
			'1 week diff, same month' => array('2020-02-01', '2020-02-08', true),
			'2 weeks diff, same month' => array('2020-02-01', '2020-02-15', true),
			'1 week diff, different month' => array('2020-01-27', '2020-02-03', true),
			'same week, different month' => array('2020-01-27', '2020-02-02', false),
			'1 week diff, different year' => array('2019-12-30', '2020-01-06', true),
			'same week, different year' => array('2019-12-30', '2020-01-05', true),
		);
	}

	/**
	 * @param string $sDateModified format Y-m-d
	 * @param string $sDateNow format Y-m-d
	 * @param bool $bExpected
	 *
	 * @dataProvider WeeklyRotationProvider
	 */
	public function testWeeklyRotation($sDateModified, $sDateNow, $bExpected)
	{
		$oDateModified = DateTime::createFromFormat('Y-m-d', $sDateModified);
		$oDateNow = DateTime::createFromFormat('Y-m-d', $sDateNow);

		$oFileBuilder = new WeeklyRotatingLogFileNameBuilder('c:/this/is/just/a/stub.invalid');
		$bShouldRotate = $oFileBuilder->ShouldRotate($oDateModified, $oDateNow);

		$this->assertEquals($bExpected, $bShouldRotate);
	}

	public function DailyRotationProvider()
	{
		return array(
			'Same day' => array('2020-02-01 00:00', '2020-02-01 15:42', false),
			'Same week, different day' => array('2020-02-01 00:00', '2020-02-02 00:00', true),
			'1 week diff' => array('2020-02-01 00:00', '2020-02-08 00:00', true),
		);
	}

	/**
	 * @param string $sDateModified format Y-m-d G:i
	 * @param string $sDateNow format Y-m-d G:i
	 * @param bool $bExpected
	 *
	 * @dataProvider DailyRotationProvider
	 */
	public function testDailyRotation($sDateModified, $sDateNow, $bExpected)
	{
		$oDateModified = DateTime::createFromFormat('Y-m-d G:i', $sDateModified);
		$oDateNow = DateTime::createFromFormat('Y-m-d G:i', $sDateNow);

		$oFileBuilder = new DailyRotatingLogFileNameBuilder('c:/this/is/just/a/stub.invalid');
		$bShouldRotate = $oFileBuilder->ShouldRotate($oDateModified, $oDateNow);

		$this->assertEquals($bExpected, $bShouldRotate);
	}
}