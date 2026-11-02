<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\Helper;

use Combodo\iTop\Application\Helper\HtmlDiffHelper;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMDocument;
use DOMXPath;

/**
 * Tests for rendered rich-text comparisons.
 *
 * @since 3.3.0 N°1365 - Test HTML comparisons by visible text
 */
class HtmlDiffHelperTest extends ItopTestCase
{
	/**
	 * @dataProvider CompareContentCasesProvider
	 * @since 3.3.0 N°1365 - Cover common rendered-text changes and formatting-only changes
	 */
	public function testCompareContentCases(string $sPreviousHtml, string $sNewHtml, string $sExpectedRemoved, string $sExpectedAdded): void
	{
		$aComparison = HtmlDiffHelper::Compare($sPreviousHtml, $sNewHtml);

		$this->assertSame($sExpectedRemoved, $this->NormalizeMarkedText($this->GetMarkedText($aComparison['previous'], 'ibo-rich-text-diff--removed')));
		$this->assertSame($sExpectedAdded, $this->NormalizeMarkedText($this->GetMarkedText($aComparison['new'], 'ibo-rich-text-diff--added')));
	}

	/**
	 * @return array<string, array{string, string, string, string}>
	 * @since 3.3.0 N°1365 - Cover rich-text comparison test data
	 */
	public function CompareContentCasesProvider(): array
	{
		return [
			'unchanged html' => ['<p>Same content</p>', '<p>Same content</p>', '', ''],
			'formatting changes only' => ['<p><strong>Same</strong> text</p>', '<div><em>Same</em> text</div>', '', ''],
			'word replacement' => ['<p>Keep old value.</p>', '<p>Keep new value.</p>', 'old', 'new'],
			'word insertion' => ['<p>Keep the sentence.</p>', '<p>Keep the extra sentence.</p>', '', 'extra'],
			'word deletion' => ['<p>Keep the extra sentence.</p>', '<p>Keep the sentence.</p>', 'extra', ''],
			'punctuation replacement' => ['<p>Wait, now!</p>', '<p>Wait; now?</p>', ',!', ';?'],
			'collapsed whitespace' => ['<p>one   two</p>', '<p>one two</p>', '', ''],
			'entity and word replacement' => ['<p>Tom &amp; Jerry</p>', '<p>Tom &#38; Jamie</p>', 'Jerry', 'Jamie'],
			'entity serialization only' => ['<p>Rock &amp; Roll</p>', '<p>Rock &#38; Roll</p>', '', ''],
			'unicode accents' => ['<p>café déjà</p>', '<p>café toujours</p>', 'déjà', 'toujours'],
			'unicode emoji in unchanged context' => ['<p>Keep 😀 and old</p>', '<p>Keep 😀 and new</p>', 'old', 'new'],
			'case-sensitive word replacement' => ['<p>Open the request</p>', '<p>open the request</p>', 'Open', 'open'],
			'CJK characters' => ['<p>你好世界</p>', '<p>你好朋友</p>', '世界', '朋友'],
			'word split across inline elements' => ['<p>hel<strong>lo</strong> old</p>', '<p><em>hello</em> new</p>', 'old', 'new'],
			'word preserved across nested formatting' => ['<p>hello <strong>world</strong></p>', '<div><b>hello</b> brave <em>world</em></div>', '', 'brave'],
			'paragraph boundary replacement' => ['<p>First</p><p>old</p>', '<p>First</p><ul><li>new</li></ul>', 'old', 'new'],
			'line break replacement' => ['<p>First<br>old</p>', '<p>First<br>new</p>', 'old', 'new'],
			'malformed markup normalized by the parser' => ['<p>Keep <strong>old</p>', '<p>Keep <strong>new</p>', 'old', 'new'],
			'empty markup only' => ['<p></p>', '<div></div>', '', ''],
			'empty previous version' => ['', '<p>Added text</p>', '', 'Added text'],
			'empty new version' => ['<p>Removed text</p>', '', 'Removed text', ''],
			'link destination changes only' => ['<p><a href="https://example.com/one">Documentation</a></p>', '<p><a href="https://example.com/two">Documentation</a></p>', '', ''],
		];
	}

	/**
	 * @since 3.3.0 N°1365 - Preserve safe formatting and entities in compared output
	 */
	public function testComparePreservesSafeMarkup(): void
	{
		$aComparison = HtmlDiffHelper::Compare(
			'<p><strong>old</strong> &amp; unchanged</p>',
			'<div><em>new</em> &amp; unchanged</div>'
		);

		$this->assertStringContainsString('<strong>', $aComparison['previous']);
		$this->assertStringContainsString('&amp; unchanged', $aComparison['previous']);
		$this->assertStringContainsString('<em>', $aComparison['new']);
		$this->assertStringContainsString('&amp; unchanged', $aComparison['new']);
		$this->assertSame('old', $this->GetMarkedText($aComparison['previous'], 'ibo-rich-text-diff--removed'));
		$this->assertSame('new', $this->GetMarkedText($aComparison['new'], 'ibo-rich-text-diff--added'));
	}

	/**
	 * @since 3.3.0 N°1365 - Ensure inline images are compared as visible content
	 */
	public function testCompareMarksAddedAndRemovedImages(): void
	{
		$aComparison = HtmlDiffHelper::Compare(
			'<p>Photo <img src="/old.png" alt="old image"></p>',
			'<p>Photo <img src="/new.png" alt="new image"></p>'
		);

		$this->assertSame('ibo-rich-text-diff--removed ibo-rich-text-diff--image', $this->GetMarkedImageClasses($aComparison['previous']));
		$this->assertSame('ibo-rich-text-diff--added ibo-rich-text-diff--image', $this->GetMarkedImageClasses($aComparison['new']));
	}

	/**
	 * @since 3.3.0 N°1365 - Preserve meaningful preformatted whitespace
	 */
	public function testComparePreservesPreformattedWhitespaceDifferences(): void
	{
		$aComparison = HtmlDiffHelper::Compare('<pre>one  two</pre>', '<pre>one two</pre>');

		$this->assertSame('  ', $this->GetMarkedText($aComparison['previous'], 'ibo-rich-text-diff--removed'));
		$this->assertSame(' ', $this->GetMarkedText($aComparison['new'], 'ibo-rich-text-diff--added'));
	}

	/**
	 * @since 3.3.0 N°1365 - Sanitize scripts from rendered comparisons
	 */
	public function testCompareDoesNotExposeNonRenderedScriptContent(): void
	{
		$aComparison = HtmlDiffHelper::Compare('<p>Visible</p><script>alert(1)</script>', '<p>Visible changed</p>');

		$this->assertStringNotContainsString('<script', strtolower($aComparison['previous']));
		$this->assertStringNotContainsString('alert(1)', $aComparison['previous']);
		$this->assertStringContainsString('Visible', $aComparison['previous']);
	}

	/**
	 * @since 3.3.0 N°1365 - Keep large comparisons bounded and retain common context
	 */
	public function testCompareLargeUnrelatedBodiesStaysBounded(): void
	{
		$aPreviousWords = [];
		$aNewWords = [];
		for ($i = 0; $i < 600; $i++) {
			$aPreviousWords[] = 'old'.$i;
			$aNewWords[] = 'new'.$i;
		}
		$aComparison = HtmlDiffHelper::Compare(
			'<p>prefix '.implode(' ', $aPreviousWords).' suffix</p>',
			'<p>prefix '.implode(' ', $aNewWords).' suffix</p>'
		);

		$this->assertSame(implode(' ', $aPreviousWords), $this->NormalizeMarkedText($this->GetMarkedText($aComparison['previous'], 'ibo-rich-text-diff--removed')));
		$this->assertSame(implode(' ', $aNewWords), $this->NormalizeMarkedText($this->GetMarkedText($aComparison['new'], 'ibo-rich-text-diff--added')));
		$this->assertStringNotContainsString('ibo-rich-text-diff--removed">prefix', $aComparison['previous']);
		$this->assertStringNotContainsString('ibo-rich-text-diff--removed">suffix', $aComparison['previous']);
		$this->assertStringNotContainsString('ibo-rich-text-diff--added">prefix', $aComparison['new']);
		$this->assertStringNotContainsString('ibo-rich-text-diff--added">suffix', $aComparison['new']);
	}

	/**
	 * Extract the text wrapped in spans with the requested CSS class.
	 *
	 * @param string $sHtml
	 * @param string $sClass
	 *
	 * @return string
	 * @since 3.3.0 N°1365 - Assert the rendered diff markers
	 */
	private function GetMarkedText(string $sHtml, string $sClass): string
	{
		$oDocument = new DOMDocument('1.0', 'UTF-8');
		$oDocument->loadHTML('<?xml encoding="UTF-8"?><html><body><div>'.$sHtml.'</div></body></html>', LIBXML_HTML_NODEFDTD);
		$oXPath = new DOMXPath($oDocument);
		$oNodes = $oXPath->query('//span[contains(concat(" ", normalize-space(@class), " "), " '.$sClass.' ")]');
		$aText = [];
		if ($oNodes !== false) {
			foreach ($oNodes as $oNode) {
				$aText[] = $oNode->textContent;
			}
		}

		return implode('', $aText);
	}

	/**
	 * Return classes for all image wrappers in one comparison version.
	 *
	 * @param string $sHtml
	 *
	 * @return string
	 * @since 3.3.0 N°1365 - Assert image change markers
	 */
	private function GetMarkedImageClasses(string $sHtml): string
	{
		$oDocument = new DOMDocument('1.0', 'UTF-8');
		$oDocument->loadHTML('<?xml encoding="UTF-8"?><html><body><div>'.$sHtml.'</div></body></html>', LIBXML_HTML_NODEFDTD);
		$oXPath = new DOMXPath($oDocument);
		$oNodes = $oXPath->query('//span[contains(concat(" ", normalize-space(@class), " "), " ibo-rich-text-diff--image ")]');
		$aClasses = [];
		if ($oNodes !== false) {
			foreach ($oNodes as $oNode) {
				$aClasses[] = $oNode->getAttribute('class');
			}
		}

		return implode(',', $aClasses);
	}

	/**
	 * Normalize whitespace when comparing concatenated marked text ranges.
	 *
	 * @param string $sText
	 *
	 * @return string
	 * @since 3.3.0 N°1365 - Keep whitespace-token assertions readable
	 */
	private function NormalizeMarkedText(string $sText): string
	{
		return trim(preg_replace('/[\p{Z}\s]+/u', ' ', $sText) ?? $sText);
	}
}
