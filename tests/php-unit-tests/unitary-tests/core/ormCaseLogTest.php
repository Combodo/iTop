<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ormCaseLog;


/**
 * Tests of the ormCaseLog class
 *
 * @covers \ormCaseLog
 */
class ormCaseLogTest extends ItopDataTestCase
{
	/**
	 * @covers \ormCaseLog::GetEntryCount()
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function testGetEntryCount()
	{
		// New log, with no entry
		$oLog = new ormCaseLog();
		$this->assertEquals($oLog->GetEntryCount(), 0, 'Should be no entry yet, returned '.$oLog->GetEntryCount());

		// Add an entry
		$oLog->AddLogEntry('First entry');
		$this->assertEquals($oLog->GetEntryCount(), 1, 'Should be 1 entry, returned '.$oLog->GetEntryCount());
	}
}
