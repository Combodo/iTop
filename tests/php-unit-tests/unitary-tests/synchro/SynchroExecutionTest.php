<?php

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class SynchroExecutionTest extends ItopDataTestCase
{
	private function InitAndGetTZ($sTZ = null): string
	{
		$sTZ = $sTZ ?? 'Europe/Paris';
		MetaModel::GetConfig()->Set('timezone', $sTZ);
		return $sTZ;
	}

	public function testGetDateMinusRetentionWithTimeChange()
	{
		$sTZ = $this->InitAndGetTZ();
		$oObj = new SynchroExecution($this->createMock(SynchroDataSource::class));
		$oDate = DateTime::createFromFormat('Y-m-d H:i:s', "2026-03-29 03:30:01", new DateTimeZone($sTZ));

		$oObj->ExactlySubtractSeconds($oDate, 3600);
		//03h30 minus 1hours => 03h minus 30mn => 03h
		//                   => 03h with timechange => 2h
		//                   => 02 minus 30mn => 01h30
		$this->assertEquals("2026-03-29 01:30:01", $oDate->Format('Y-m-d H:i:s'));
	}

	public function testGetDateMinusRetentionWithTimeChangeAndUTC()
	{
		$sTZ = $this->InitAndGetTZ('UTC');
		$oObj = new SynchroExecution($this->createMock(SynchroDataSource::class));
		$oDate = DateTime::createFromFormat('Y-m-d H:i:s', "2026-03-29 03:30:01", new DateTimeZone($sTZ));

		$oObj->ExactlySubtractSeconds($oDate, 3600);
		$this->assertEquals("2026-03-29 02:30:01", $oDate->Format('Y-m-d H:i:s'));
	}

	public function testGetDateMinusRetention()
	{
		$sTZ = $this->InitAndGetTZ();
		$oObj = new SynchroExecution($this->createMock(SynchroDataSource::class));
		$oDate = DateTime::createFromFormat('Y-m-d H:i:s', "2026-04-01 03:30:01", new DateTimeZone($sTZ));

		$oObj->ExactlySubtractSeconds($oDate, 3600);
		$this->assertEquals("2026-04-01 02:30:01", $oDate->Format('Y-m-d H:i:s'));
	}
}
