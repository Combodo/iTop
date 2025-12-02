<?php

namespace Combodo\iTop\Test\UnitTest\Core\Sanitizer;

use HTMLDOMSanitizer;
use InlineImageMock;

require_once __DIR__.'/AbstractDOMSanitizerTest.php';

class HTMLDOMSanitizerTest extends AbstractDOMSanitizerTest
{
	/**
	 * @dataProvider DoSanitizeProvider
	 *
	 * @param string $sFileToTest filename
	 */
	public function testDoSanitize($sFileToTest)
	{
		$sInputHtml = $this->ReadTestFile($sFileToTest, self::INPUT_DIRECTORY);
		$sOutputHtml = $this->ReadTestFile($sFileToTest, self::OUTPUT_DIRECTORY);
		$sOutputHtml = $this->RemoveNewLines($sOutputHtml);

		$oSanitizer = new HTMLDOMSanitizer();
		$sRes = $oSanitizer->DoSanitize($sInputHtml);

		// Removing newlines as the parser gives different results depending on the PHP version
		// Didn't manage to get it right :
		// - no php.ini difference
		// - playing with the parser preserveWhitespace/formatOutput parser options didn't help
		// So we're removing new lines on both sides :/
		$sOutputHtml = $this->RemoveNewLines($sOutputHtml);
		$sRes = $this->RemoveNewLines($sRes);

		$this->debug($sRes);
		$this->assertEquals($sOutputHtml, $sRes);
	}

	public function DoSanitizeProvider()
	{
		return [
			[
				'scripts.html',
			],
		];
	}

	/**
	 * @dataProvider WhiteListProvider
	 *
	 * @param string $sHtmlToTest HTML content
	 */
	public function testDoSanitizeWhiteList($sHtmlToTest)
	{
		$oSanitizer = new HTMLDOMSanitizer();
		$sRes = $oSanitizer->DoSanitize($sHtmlToTest);

		// Removing newlines as the parser gives different results depending on the PHP version
		// Didn't manage to get it right :
		// - no php.ini difference
		// - playing with the parser preserveWhitespace/formatOutput parser options didn't help
		// So we're removing new lines on both sides :/
		$sHtmlToTest = $this->RemoveNewLines($sHtmlToTest);
		$sRes = $this->RemoveNewLines($sRes);

		$this->debug($sRes);
		$this->assertEquals($sHtmlToTest, $sRes);
	}

	public function WhiteListProvider()
	{
		// This is a copy of \HTMLDOMSanitizer::$aTagsWhiteList
		// should stay a copy as we want to check we're not removing something by mistake as it was done with the CENTER tag (N°2558)
		$aTagsWhiteList = [
			// we don't test HTML and BODY as the parser removes them if context isn't appropriate
			'a' => ['href', 'name', 'style', 'target', 'title'],
			'p' => ['style'],
			'blockquote' => ['style'],
			'br' => [],
			'span' => ['style'],
			'div' => ['style'],
			'b' => [],
			'i' => [],
			'u' => [],
			'em' => [],
			'strong' => [],
			'img' => ['src', 'style', 'alt', 'title'],
			'ul' => ['style'],
			'ol' => ['reversed', 'start', 'style', 'type'],
			'li' => ['style', 'value'],
			'h1' => ['style'],
			'h2' => ['style'],
			'h3' => ['style'],
			'h4' => ['style'],
			'nav' => ['style'],
			'section' => ['style'],
			'code' => ['style'],
			'table' => ['style', 'width', 'summary', 'align', 'border', 'cellpadding', 'cellspacing'],
			'thead' => ['style'],
			'tbody' => ['style'],
			'tr' => ['style', 'colspan', 'rowspan'],
			'td' => ['style', 'colspan', 'rowspan'],
			'th' => ['style', 'colspan', 'rowspan'],
			'fieldset' => ['style'],
			'legend' => ['style'],
			'font' => ['face', 'color', 'style', 'size'],
			'big' => [],
			'small' => [],
			'tt' => [],
			'kbd' => [],
			'samp' => [],
			'var' => [],
			'del' => [],
			's' => [], // strikethrough
			'ins' => [],
			'cite' => [],
			'q' => [],
			'hr' => ['style'],
			'pre' => [],
			'center' => [],
		];
		$aTestCaseArray = [];

		$sInputText = $this->ReadTestFile('whitelist_test.html', self::INPUT_DIRECTORY);
		foreach ($aTagsWhiteList as $sTag => $aTagAttributes) {
			$sTestCaseText = $sInputText;
			$sStartTag = "<$sTag";
			$iAttrCounter = 0;
			foreach ($aTagAttributes as $sTagAttribute) {
				$sStartTag .= $this->GetTagAttributeValue($sTagAttribute, $iAttrCounter);
				$iAttrCounter++;
			}
			$sStartTag .= '>';
			$sTestCaseText = str_replace('##START_TAG##', $sStartTag, $sTestCaseText);

			$sClosingTag = $this->IsClosingTag($sTag) ? "</$sTag>" : '';
			$sTestCaseText = str_replace('##END_TAG##', $sClosingTag, $sTestCaseText);

			$aTestCaseArray[$sTag] = [$sTestCaseText];
		}

		return $aTestCaseArray;
	}

	/**
	 * @dataProvider RemoveBlackListedTagContentProvider
	 */
	public function testDoSanitizeRemoveBlackListedTagContent($html, $expected)
	{
		$oSanitizer = new HTMLDOMSanitizer();
		$sSanitizedHtml = $oSanitizer->DoSanitize($html);

		$this->assertEquals($expected, str_replace("\n", '', $sSanitizedHtml));
	}

	public function RemoveBlackListedTagContentProvider()
	{
		return [
			'basic' => [
				'html' => 'foo<iframe>bar</iframe>baz',
				'expected' => '<p>foobaz</p>',
			],
			'basic with body' => [
				'html' => '<body>foo<iframe>bar</iframe>baz</body>',
				'expected' => 'foobaz',
			],
			'basic with html and body tags' => [
				'html' => '<html><body lang="EN-GB" link="#0563C1" vlink="#954F72">foo<iframe>bar</iframe>baz</body></html>',
				'expected' => 'foobaz',
			],
			'basic with attributes' => [
				'html' => 'foo<iframe baz="1">bar</iframe>baz',
				'expected' => '<p>foobaz</p>',
			],
			'basic with comment' => [
				'html' => 'foo<iframe baz="1">bar<!-- foo --></iframe>baz',
				'expected' => '<p>foobaz</p>',
			],
			'basic with contentRemovable tag' => [
				'html' => 'foo<iframe baz="1">bar<style>foo</style><script>boo</script></iframe>baz',
				'expected' => '<p>foobaz</p>',
			],
			'nested' => [
				'html' => 'before<iframe>foo<article>baz</article>oof<article><iframe>bar</iframe>oof</article></iframe>after',
				'expected' => '<p>beforeafter</p>',
			],
			'nested with not closed br' => [
				'html' => 'before<iframe>foo<article>baz</article>oof<br><article><iframe>bar</iframe>oof</article></iframe>after',
				'expected' => '<p>beforeafter</p>',
			],
			'nested with allowed' => [
				'html' => 'before<iframe><div><article><p>baz</p>zab</article></div>oof</iframe>after',
				'expected' => '<p>beforeafter</p>',
			],
			'nested with spaces' => [
				'html' => 'before<iframe><article>baz</article> oof</iframe>after',
				'expected' => '<p>beforeafter</p>',
			],
			'nested with attributes' => [
				'html' => 'before<iframe baz="1"><article baz="1" biz="2">baz</article>oof</iframe>after',
				'expected' => '<p>beforeafter</p>',
			],
			'nested with allowed and attributes and spaces ' => [
				'html' => '<html><body>before<iframe baz="1"><div baz="baz"><article baz="1" biz="2">baz</article>rab</div> oof</iframe>after</body></html>',
				'expected' => 'beforeafter',
			],
			'nested with allowed and contentRemovable tags' => [
				'html' => '<html><body>before<iframe baz="1"><div ><article>baz</article>rab</div> oof<embed>embedTExt</embed></iframe>middle<style>foo</style>after<script>boo</script></body></html>',
				'expected' => 'beforemiddleafter',
			],

			'regression: if head present => body is not trimmed' => [
				'html' => '<html><head></head><body lang="EN-GB" link="#0563C1" vlink="#954F72">bar</body></html>',
				'expected' => 'bar',
			],

			'ordered list with attributes' => [
				'html' => '<ol start="100" reversed="reversed" type="I" baz="1" biz="2"><li value="101" baz="1" biz="2">Some list item</li></ol>',
				'expected' => '<ol start="100" reversed="reversed" type="I"><li value="101">Some list item</li></ol>',
			],

		];
	}

	/**
	 * @dataProvider CallInlineImageProcessImageTagProvider
	 * @uses         \InlineImageMock
	 */
	public function testDoSanitizeCallInlineImageProcessImageTag($sHtml, $iExpectedCount)
	{
		$this->RequireOnceUnitTestFile('./InlineImageMock.php');
		InlineImageMock::ResetCallCounter();

		$oSanitizer = new HTMLDOMSanitizer(InlineImageMock::class);
		$oSanitizer->DoSanitize($sHtml);

		$iCalledCount = \InlineImageMock::GetCallCounter();
		$this->assertEquals($iExpectedCount, $iCalledCount);
	}

	public function CallInlineImageProcessImageTagProvider()
	{
		return [
			'no image' => [
				'html' => '<p>bar</p>',
				'expected' => 0,
			],
			'basic image' => [
				'html' => '<img />',
				'expected' => 1,
			],
			'nested images within forbidden tags' => [
				'html' => '<html><body><img /><iframe baz="1"><div baz="baz"><article baz="1" biz="2">baz<img /><img /></article>rab</div> oof<img /></iframe><img /></body></html>',
				'expected' => 2,
			],
//          This test will be restored with the ticket n°2556
//			'nested images within forbidden and removed tags' => array(
//				'html' => '<html><body><img /><iframe baz="1"><div baz="baz"><object baz="1" biz="2">baz<img /><img /></object>rab</div> oof<img /></iframe><img /></body></html>',
//				'expected' => 2,
//			),
		];
	}

}
