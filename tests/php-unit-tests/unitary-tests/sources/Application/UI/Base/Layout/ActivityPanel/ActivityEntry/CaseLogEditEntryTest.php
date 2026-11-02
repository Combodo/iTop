<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\UI\Base\Layout\ActivityPanel\ActivityEntry;

use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DateTime;

/**
 * Tests for rendering CaseLog edit comparisons.
 *
 * @since 3.3.0 N°1365 - Test CaseLog rendered comparisons
 */
class CaseLogEditEntryTest extends ItopDataTestCase
{
	/**
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry::GetPreviousContent
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry::GetNewContent
	 * @since 3.3.0 N°1365 - Highlight removed and added CaseLog text
	 */
	public function testGetContentMethodsReturnMarkedVersions(): void
	{
		$oEntry = new CaseLogEditEntry(
			new DateTime('2026-01-02 03:04:05'),
			'author',
			'private_log',
			0,
			123,
			'<p>Keep old text</p>',
			'<p>Keep new text</p>'
		);

		$this->assertTrue($oEntry->HasContentSnapshot());
		$this->assertStringContainsString('ibo-rich-text-diff--removed', $oEntry->GetPreviousContent());
		$this->assertStringContainsString('old', $oEntry->GetPreviousContent());
		$this->assertStringContainsString('ibo-rich-text-diff--added', $oEntry->GetNewContent());
		$this->assertStringContainsString('new', $oEntry->GetNewContent());
	}

	/**
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry::GetContentComparison
	 * @since 3.3.0 N°1365 - Do not mark formatting-only changes
	 */
	public function testGetContentMethodsLeaveFormattingOnlyChangesUnmarked(): void
	{
		$oEntry = new CaseLogEditEntry(
			new DateTime('2026-01-02 03:04:05'),
			'author',
			'private_log',
			0,
			123,
			'<p><strong>Same text</strong></p>',
			'<div><em>Same text</em></div>'
		);

		$this->assertStringNotContainsString('ibo-rich-text-diff--removed', $oEntry->GetPreviousContent());
		$this->assertStringNotContainsString('ibo-rich-text-diff--added', $oEntry->GetNewContent());
		$this->assertStringContainsString('<strong>', $oEntry->GetPreviousContent());
		$this->assertStringContainsString('<em>', $oEntry->GetNewContent());
	}

	/**
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry::HasContentSnapshot
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry::GetPreviousContent
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\CaseLogEditEntry::GetNewContent
	 * @since 3.3.0 N°1365 - Keep missing historical snapshots empty
	 */
	public function testMissingSnapshotsReturnEmptyVersions(): void
	{
		$oEntry = new CaseLogEditEntry(new DateTime('2026-01-02 03:04:05'), 'author', 'private_log', 0, 123);

		$this->assertFalse($oEntry->HasContentSnapshot());
		$this->assertSame('', $oEntry->GetPreviousContent());
		$this->assertSame('', $oEntry->GetNewContent());
	}
}
