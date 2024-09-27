<?php
namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DateTime;


class WeeklyScheduledProcessTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('core/backgroundprocess.inc.php');
		$this->RequireOnceUnitTestFile('./WeeklyScheduledProcessMockConfig.php');
	}

	public function testShouldPlanForNeverIfDisabled()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(false, '22:00', 'monday');
		$this->assertEquals(new DateTime('3000-01-01'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 21:00'), 'Disabled process should be planned for a date far in the future');
	}

	public function testNextOccurrenceShouldGiveTheSameDayWhenBeforeTheLimit()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '22:00', 'monday, tuesday');

		$this->assertEquals(new DateTime('2020-05-11 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 21:00'), '9 pm should be followed by 10pm');
		$this->assertEquals(new DateTime('2020-05-11 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 21:59'), '9:59 pm should be followed by 10pm');
		$this->assertEquals(new DateTime('2020-05-11 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 21:59:59'), '9:59:59 pm should be followed by 10pm');
		$this->assertEquals(new DateTime('2020-05-11 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 21:59:59.999'), '9:59:59 pm and 999 milliseconds should be followed by 10pm');

		$this->assertEquals(new DateTime('2020-05-12 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 22:00:00.0'), '10 pm should be followed by 10 pm on the NEXT matching day');
	}

	public function testNextOccurrenceShouldGiveTheNextDayWhateverTheDayOfTheWeek()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '22:00', 'monday, tuesday, wednesday, thursday, friday, saturday, sunday');
		$this->assertEquals(new DateTime('2020-05-12 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 23:00'), 'The occurrence after monday should be tuesday');
		$this->assertEquals(new DateTime('2020-05-13 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-12 23:00'), 'The occurrence after tuesday should be wednesday');
		$this->assertEquals(new DateTime('2020-05-14 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-13 23:00'), 'The occurrence after wednesday should be thursday');
		$this->assertEquals(new DateTime('2020-05-15 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-14 23:00'), 'The occurrence after thursday should be friday');
		$this->assertEquals(new DateTime('2020-05-16 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-15 23:00'), 'The occurrence after friday should be saturday');
		$this->assertEquals(new DateTime('2020-05-17 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-16 23:00'), 'The occurrence after saturday should be sunday');
		$this->assertEquals(new DateTime('2020-05-18 22:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-17 23:00'), 'The occurrence after sunday should be monday');
	}

	public function testNextOccurrenceFindsTheNextWeekWhenOneDayIsConfigured()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'monday');
		$this->assertEquals(new DateTime('2020-05-18 12:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 12:45'), 'The occurrence after monday should be monday of the next week');

		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'tuesday');
		$this->assertEquals(new DateTime('2020-05-19 12:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-12 12:45'), 'The occurrence after tuesday should be tuesday of the next week');

		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'wednesday');
		$this->assertEquals(new DateTime('2020-05-20 12:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-13 12:45'), 'The occurrence after wednesday should be wednesday of the next week');

		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'thursday');
		$this->assertEquals(new DateTime('2020-05-21 12:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-14 12:45'), 'The occurrence after thursday should be thursday of the next week');

		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'friday');
		$this->assertEquals(new DateTime('2020-05-22 12:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-15 12:45'), 'The occurrence after friday should be friday of the next week');

		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'saturday');
		$this->assertEquals(new DateTime('2020-05-23 12:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-16 12:45'), 'The occurrence after saturday should be saturday of the next week');

		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'sunday');
		$this->assertEquals(new DateTime('2020-05-24 12:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-17 12:45'), 'The occurrence after sunday should be sunday of the next week');
	}

	public function testNextOccurrenceShouldCopeWithATaskPlannedAtMidnightOnWeekEnds()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '00:00', 'saturday, sunday, monday');
		$this->assertEquals(new DateTime('2020-05-16 00:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-15 23:59'), 'The occurrence after friday 23:59 should be one second later');
		$this->assertEquals(new DateTime('2020-05-17 00:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-16 00:00'), 'The occurrence after saturday 00:00 should be sunday 00:00');
		$this->assertEquals(new DateTime('2020-05-18 00:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-17 00:00'), 'The occurrence after sunday 00:00 should be monday 00:00');
		$this->assertEquals(new DateTime('2020-05-23 00:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-18 00:00'), 'The occurrence after monday 00:00 should be on next saturday');
	}

	public function testWeekDaysConfigShouldBeCaseInsensitive()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'WEDnESdAY');
		$this->assertEquals([3], $oWeeklyImpl->InterpretWeekDays());
	}

	public function testWeekDaysConfigSpacesShouldBeIgnored()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', '  wednesday  ,tuesday');
		$this->assertEquals([2, 3], $oWeeklyImpl->InterpretWeekDays());
	}

	public function testWeekDaysConfigOrderShouldNotMatter()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'sunday, monday, tuesday, thursday');
		$this->assertEquals([1, 2, 4, 7], $oWeeklyImpl->InterpretWeekDays(), 'Days of week are sorted when the configuration is read');
	}

	public function testWeekDaysConfigWithInvalidDayShouldThrowAMeaningfulException()
	{
		$this->expectException(\ProcessInvalidConfigException::class);
		$this->expectExceptionMessage('itop-zabu-gomeu: wrong format for setting \'week_days\' (found \'mercredi\')');
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12:00', 'monday, tuesday, wednesday, mercredi');

		$oWeeklyImpl->InterpretWeekDays();
	}

	public function testTimeConfigShouldBeTrimmed()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '  22:33  ', 'monday');
		$this->assertEquals(new DateTime('2020-05-11 22:33:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 21:00'));
	}

	public function testTimeConfigSecondsShouldBeIgnored()
	{
		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '22:33:44.123', 'monday');
		$this->assertEquals(new DateTime('2020-05-11 22:33:00'), $oWeeklyImpl->GetNextOccurrence('2020-05-11 21:00'));
	}
	public function testEmptyTimeConfigShouldThrowAMeaningfulException()
	{
		$this->expectException(\ProcessInvalidConfigException::class);
		$this->expectExceptionMessage('itop-zabu-gomeu: wrong format for setting \'time\' (found \'\')');

		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '', 'monday');

		$oWeeklyImpl->GetNextOccurrence();
	}

	public function testBadlyFormattedTimeConfigShouldThrowAMeaningfulException()
	{
		$this->expectException(\ProcessInvalidConfigException::class);
		$this->expectExceptionMessage('itop-zabu-gomeu: wrong format for setting \'time\' (found \'12am\')');

		$oWeeklyImpl = new \WeeklyScheduledProcessMockConfig(true, '12am', 'monday');

		$oWeeklyImpl->GetNextOccurrence();
	}
}

