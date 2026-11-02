<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\UI\Base\Layout\ActivityPanel\ActivityEntry;

use Combodo\iTop\Application\Helper\HtmlDiffHelper;
use Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry;
use Combodo\iTop\Renderer\BlockRenderer;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DateTime;

/**
 * Tests for rendering HTML attribute comparisons in the activity panel.
 *
 * @since 3.3.0 N°1365 - Test HTML attribute activity rendering
 */
class EditsEntryTest extends ItopDataTestCase
{
	/**
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry::SetHtmlComparison
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry::HasHtmlComparisons
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry::HasHtmlComparison
	 * @covers \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry::GetHtmlComparison
	 * @since 3.3.0 N°1365 - Render ticket HTML differences
	 */
	public function testHtmlComparisonIsRenderedInTheActivityEntry(): void
	{
		$aComparison = HtmlDiffHelper::Compare('<p>Old ticket text</p>', '<p>New ticket text</p>');
		$oEntry = new EditsEntry(new DateTime('2026-01-02 03:04:05'), 'author', 'UserRequest');
		$oEntry->AddAttribute('description', 'Description changed');
		$oEntry->SetHtmlComparison('description', $aComparison);

		$this->assertTrue($oEntry->HasHtmlComparisons());
		$this->assertTrue($oEntry->HasHtmlComparison('description'));
		$this->assertFalse($oEntry->HasHtmlComparison('title'));
		$this->assertSame($aComparison, $oEntry->GetHtmlComparison('description'));

		$sHtml = BlockRenderer::RenderBlockTemplates($oEntry);
		$this->assertStringContainsString('ibo-rich-text-diff--versions', $sHtml);
		$this->assertStringContainsString('ibo-rich-text-diff--removed', $sHtml);
		$this->assertStringContainsString('ibo-rich-text-diff--added', $sHtml);
		$this->assertStringContainsString('Old</span> ticket text', $sHtml);
		$this->assertStringContainsString('New</span> ticket text', $sHtml);
	}
}
