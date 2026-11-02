<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
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

	/**
	 * @covers \ormCaseLog::UpdateEntryAt()
	 * @covers \ormCaseLog::GetModifiedEntry()
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \OQLException
	 * @since 3.3.0 N°1365 - Test indexed CaseLog entry replacement
	 */
	public function testUpdateEntryAtPreservesOtherEntriesAndOriginalMetadata()
	{
		$oLog = new ormCaseLog();
		$oLog->AddLogEntry('Old entry', 'First author');
		$oLog->AddLogEntry('Middle entry', 'Second author');
		$oLog->AddLogEntry('Newest entry', 'Third author');

		$aEntriesBefore = $oLog->GetAsArray();
		$sOriginalDate = $aEntriesBefore[1]['date'];
		$sOriginalAuthor = $aEntriesBefore[1]['user_login'];

		$oLog->UpdateEntryAt(1, 'A longer edited middle entry');

		$aEntriesAfter = $oLog->GetAsArray();
		$this->assertSame('<p>Old entry</p>', $oLog->GetEntryAt(0));
		$this->assertSame('<p>A longer edited middle entry</p>', $oLog->GetEntryAt(1));
		$this->assertSame('<p>Newest entry</p>', $oLog->GetEntryAt(2));
		$this->assertSame($sOriginalDate, $aEntriesAfter[1]['date']);
		$this->assertSame($sOriginalAuthor, $aEntriesAfter[1]['user_login']);
		$this->assertSame(1, $oLog->GetModifiedEntryIndex());
		$this->assertTrue($oLog->WasModifiedEntryEdited());
		$this->assertSame('<p>A longer edited middle entry</p>', $oLog->GetModifiedEntry(ormCaseLog::ENUM_FORMAT_HTML));
	}

	/**
	 * @covers \ormCaseLog::UpdateEntryAt()
	 * @throws \CoreException
	 * @since 3.3.0 N°1365 - Reject invalid CaseLog entry indexes
	 */
	public function testUpdateEntryAtRejectsInvalidIndex()
	{
		$this->expectException(\CoreException::class);

		$oLog = new ormCaseLog();
		$oLog->UpdateEntryAt(0, 'Invalid entry');
	}

	/**
	 * @covers \ormCaseLog::AddLogEntry()
	 * @covers \ormCaseLog::GetModifiedEntry()
	 * @covers \ormCaseLog::GetModifiedEntryIndex()
	 * @covers \ormCaseLog::WasModifiedEntryEdited()
	 * @since 3.3.0 N°1365 - Test CaseLog append tracking
	 */
	public function testAddLogEntryTracksTheModifiedEntry(): void
	{
		$oLog = new ormCaseLog();
		$this->assertSame('', $oLog->GetModifiedEntry());
		$this->assertNull($oLog->GetModifiedEntryIndex());
		$this->assertFalse($oLog->WasModifiedEntryEdited());

		$oLog->AddLogEntry('First entry');
		$this->assertSame(0, $oLog->GetModifiedEntryIndex());
		$this->assertFalse($oLog->WasModifiedEntryEdited());
		$this->assertSame('First entry', $oLog->GetModifiedEntry());
		$this->assertSame('<p>First entry</p>', $oLog->GetModifiedEntry(ormCaseLog::ENUM_FORMAT_HTML));

		$oLog->AddLogEntry('Second entry', 'Another author');
		$this->assertSame(1, $oLog->GetModifiedEntryIndex());
		$this->assertSame('Second entry', $oLog->GetModifiedEntry());
		$this->assertSame('', $oLog->GetModifiedEntry('unknown'));
	}

	/**
	 * @covers \ormCaseLog::AddLogEntry()
	 * @covers \ormCaseLog::GetModifiedEntry()
	 * @covers \ormCaseLog::GetModifiedEntryIndex()
	 * @since 3.3.0 N°1365 - Test tracking when a new CaseLog entry is merged
	 */
	public function testAddLogEntryTracksMergedEntry(): void
	{
		$oLog = new ormCaseLog();
		$oLog->AddLogEntry('First part', 'Same author');
		$oLog->AddLogEntry('Second part', 'Same author');

		$this->assertSame(1, $oLog->GetEntryCount());
		$this->assertSame(0, $oLog->GetModifiedEntryIndex());
		$sModifiedEntry = $oLog->GetModifiedEntry(ormCaseLog::ENUM_FORMAT_HTML);
		$this->assertStringContainsString('First part', $sModifiedEntry);
		$this->assertStringContainsString('Second part', $sModifiedEntry);
	}

	/**
	 * @covers \ormCaseLog::GetModifiedEntry()
	 * @since 3.3.0 N°1365 - Keep latest-entry compatibility without an index
	 */
	public function testGetModifiedEntryFallsBackToLatestWithoutIndexedMetadata(): void
	{
		$oLog = new ormCaseLog();
		$oLog->AddLogEntry('Legacy modified entry');
		$this->SetNonPublicProperty($oLog, 'm_iModifiedEntryIndex', null);

		$this->assertSame('Legacy modified entry', $oLog->GetModifiedEntry());
	}

	/**
	 * @covers \ormCaseLog::AddLogEntryFromJSON()
	 * @covers \ormCaseLog::GetModifiedEntry()
	 * @since 3.3.0 N°1365 - Test CaseLog JSON append tracking
	 */
	public function testAddLogEntryFromJsonTracksTheModifiedEntry(): void
	{
		$oLog = new ormCaseLog();
		$oLog->AddLogEntry('Older entry', 'Another author');
		$oLog->AddLogEntryFromJSON((object)[
			'date' => '2024-01-02 03:04:05',
			'message' => '<p>Latest entry</p>',
			'format' => ormCaseLog::ENUM_FORMAT_HTML,
		]);

		$this->assertSame(1, $oLog->GetModifiedEntryIndex());
		$this->assertFalse($oLog->WasModifiedEntryEdited());
		$this->assertSame('Latest entry', $oLog->GetModifiedEntry());
		$this->assertSame('<p>Latest entry</p>', $oLog->GetModifiedEntry(ormCaseLog::ENUM_FORMAT_HTML));
	}

	/**
	 * @covers \ormCaseLog::ClearModifiedFlag()
	 * @covers \ormCaseLog::GetModifiedEntry()
	 * @covers \ormCaseLog::GetModifiedEntryIndex()
	 * @covers \ormCaseLog::WasModifiedEntryEdited()
	 * @since 3.3.0 N°1365 - Test resetting CaseLog edit tracking
	 */
	public function testClearModifiedFlagResetsEntryTracking(): void
	{
		$oLog = new ormCaseLog();
		$oLog->AddLogEntry('Entry');
		$oLog->UpdateEntryAt(0, 'Edited entry');

		$oLog->ClearModifiedFlag();

		$this->assertNull($oLog->GetModifiedEntryIndex());
		$this->assertFalse($oLog->WasModifiedEntryEdited());
		$this->assertSame('', $oLog->GetModifiedEntry());
	}

	/**
	 * @covers \ormCaseLog::UpdateEntryAt()
	 * @covers \ormCaseLog::GetEntryAt()
	 * @covers \ormCaseLog::GetModifiedEntry()
	 * @since 3.3.0 N°1365 - Test CaseLog edits at both ends of a log
	 */
	public function testUpdateEntryAtHandlesFirstAndLastEntries(): void
	{
		$oLog = new ormCaseLog();
		$oLog->AddLogEntry('First entry', 'First author');
		$oLog->AddLogEntry('Middle entry', 'Second author');
		$oLog->AddLogEntry('Last entry', 'Third author');

		$oLog->UpdateEntryAt(0, '<p>Updated first entry with <strong>markup</strong></p>');
		$this->assertSame('<p>Updated first entry with <strong>markup</strong></p>', $oLog->GetEntryAt(0));
		$this->assertSame('<p>Middle entry</p>', $oLog->GetEntryAt(1));
		$this->assertSame('<p>Last entry</p>', $oLog->GetEntryAt(2));
		$this->assertSame(0, $oLog->GetModifiedEntryIndex());
		$this->assertSame('<p>Updated first entry with <strong>markup</strong></p>', $oLog->GetModifiedEntry(ormCaseLog::ENUM_FORMAT_HTML));

		$oLog->UpdateEntryAt(2, '<p>Updated last entry</p>');
		$this->assertSame('<p>Updated first entry with <strong>markup</strong></p>', $oLog->GetEntryAt(0));
		$this->assertSame('<p>Middle entry</p>', $oLog->GetEntryAt(1));
		$this->assertSame('<p>Updated last entry</p>', $oLog->GetEntryAt(2));
		$this->assertSame(2, $oLog->GetModifiedEntryIndex());
		$this->assertTrue($oLog->WasModifiedEntryEdited());
	}
}
