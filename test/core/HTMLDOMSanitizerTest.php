<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use HTMLDOMSanitizer;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class HTMLDOMSanitizerTest extends ItopTestCase
{
	const INPUT_DIRECTORY = 'sanitizer/input';
	const OUTPUT_DIRECTORY = 'sanitizer/output';

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

	private function ReadTestFile($sFileToTest, $sFolderName)
	{
		$sCurrentPath = __DIR__;

		return file_get_contents($sCurrentPath.DIRECTORY_SEPARATOR
			.$sFolderName.DIRECTORY_SEPARATOR
			.$sFileToTest);
	}

	private function RemoveNewLines($sText)
	{
		$sText = str_replace("\r\n", "\n", $sText);
		$sText = str_replace("\r", "\n", $sText);
		$sText = str_replace("\n", '', $sText);

		return $sText;
	}

	public function DoSanitizeProvider()
	{
		return array(
			array(
				'utf-8_wrong_character_email_truncated.txt',
			),
		);
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
		$aTagsWhiteList = array(
			// we don't test HTML and BODY as the parser removes them if context isn't appropriate
			'a' => array('href', 'name', 'style', 'target', 'title'),
			'p' => array('style'),
			'blockquote' => array('style'),
			'br' => array(),
			'span' => array('style'),
			'div' => array('style'),
			'b' => array(),
			'i' => array(),
			'u' => array(),
			'em' => array(),
			'strong' => array(),
			'img' => array('src', 'style', 'alt', 'title'),
			'ul' => array('style'),
			'ol' => array('style'),
			'li' => array('style'),
			'h1' => array('style'),
			'h2' => array('style'),
			'h3' => array('style'),
			'h4' => array('style'),
			'nav' => array('style'),
			'section' => array('style'),
			'code' => array('style'),
			'table' => array('style', 'width', 'summary', 'align', 'border', 'cellpadding', 'cellspacing'),
			'thead' => array('style'),
			'tbody' => array('style'),
			'tr' => array('style', 'colspan', 'rowspan'),
			'td' => array('style', 'colspan', 'rowspan'),
			'th' => array('style', 'colspan', 'rowspan'),
			'fieldset' => array('style'),
			'legend' => array('style'),
			'font' => array('face', 'color', 'style', 'size'),
			'big' => array(),
			'small' => array(),
			'tt' => array(),
			'kbd' => array(),
			'samp' => array(),
			'var' => array(),
			'del' => array(),
			's' => array(), // strikethrough
			'ins' => array(),
			'cite' => array(),
			'q' => array(),
			'hr' => array('style'),
			'pre' => array(),
			'center' => array(),
		);
		$aTestCaseArray = array();

		$sInputText = $this->ReadTestFile('whitelist_test.html', self::INPUT_DIRECTORY);
		foreach ($aTagsWhiteList as $sTag => $aTagAttributes)
		{
			$sTestCaseText = $sInputText;
			$sStartTag = "<$sTag";
			$iAttrCounter = 0;
			foreach ($aTagAttributes as $sTagAttribute)
			{
				$sStartTag .= $this->GetTagAttributeValue($sTagAttribute, $iAttrCounter);
				$iAttrCounter++;
			}
			$sStartTag .= '>';
			$sTestCaseText = str_replace('##START_TAG##', $sStartTag, $sTestCaseText);

			$sClosingTag = $this->IsClosingTag($sTag) ? "</$sTag>" : '';
			$sTestCaseText = str_replace('##END_TAG##', $sClosingTag, $sTestCaseText);

			$aTestCaseArray[$sTag] = array($sTestCaseText);
		}

		return $aTestCaseArray;
	}

	/**
	 * Test the fix for ticket N°2556
	 *
	 * @dataProvider PreserveBlackListedTagContentProvider
	 *
	 */
	public function testDoSanitizePreserveBlackListedTagContent($html, $expected)
	{
		$oSanitizer = new HTMLDOMSanitizer();
		$sSanitizedHtml = $oSanitizer->DoSanitize($html);

		$this->assertEquals($expected, str_replace("\n", '', $sSanitizedHtml));
	}

	public function PreserveBlackListedTagContentProvider()
	{
		return array(
			'basic' => array(
				'html' => '<iframe>bar</iframe>',
				'expected' => 'bar',
			),
			'basic with body' => array(
				'html' => '<body><iframe>bar</iframe></body>',
				'expected' => 'bar',
			),
			'basic with html and body tags' => array(
				'html' => '<html><body lang="EN-GB" link="#0563C1" vlink="#954F72"><iframe>bar</iframe></body></html>',
				'expected' => 'bar',
			),
			'basic with attributes' => array(
				'html' => '<iframe baz="1">bar</iframe>',
				'expected' => 'bar',
			),
			'basic with comment' => array(
				'html' => '<iframe baz="1">bar<!-- foo --></iframe>',
				'expected' => 'bar',
			),
			'basic with contentRemovable tag' => array(
				'html' => '<iframe baz="1">bar<style>foo</style><script>boo</script></iframe>',
				'expected' => 'bar',
			),
			'nested' => array(
				'html' => '<iframe>foo<article>baz</article>oof<article><iframe>bar</iframe>oof</article></iframe>',
				'expected' => 'foobazoofbaroof',
			),
			'nested with not closed br' => array(
				'html' => '<iframe>foo<article>baz</article>oof<br><article><iframe>bar</iframe>oof</article></iframe>',
				'expected' => 'foobazoof<br>baroof',
			),
			'nested with allowed' => array(
				'html' => '<iframe><div><article><p>baz</p>zab</article></div>oof</iframe>',
				'expected' => '<div><p>baz</p>zab</div>oof',
			),
			'nested with spaces' => array(
				'html' => '<iframe><article>baz</article> oof</iframe>',
				'expected' => 'baz oof',
			),
			'nested with attributes' => array(
				'html' => '<iframe baz="1"><article baz="1" biz="2">baz</article>oof</iframe>',
				'expected' => 'bazoof',
			),
			'nested with allowed and attributes and spaces ' => array(
				'html' => '<html><body><iframe baz="1"><div baz="baz"><article baz="1" biz="2">baz</article>rab</div> oof</iframe></body></html>',
				'expected' => '<div>bazrab</div> oof',
			),
			'nested with allowed and contentRemovable tags' => array(
				'html' => '<html><body><iframe baz="1"><div ><article>baz</article>rab</div> oof<embed>embedTExt</embed></iframe><style>foo</style><script>boo</script></body></html>',
				'expected' => '<div>bazrab</div> oof',
			),

			'regression: if head present => body is not trimmed' => array(
				'html' => '<html><head></head><body lang="EN-GB" link="#0563C1" vlink="#954F72">bar</body></html>',
				'expected' => 'bar',
			),
		);
	}

	/**
	 * Generates an appropriate value for the given attribute, or use the counter if needed.
	 * This is necessary as most of the attributes with empty or inappropriate values (like a numeric for a href) are removed by the parser
	 *
	 * @param string $sTagAttribute
	 * @param int $iAttributeCounter
	 *
	 * @return string attribute value
	 */
	private function GetTagAttributeValue($sTagAttribute, $iAttributeCounter)
	{
		$sTagAttrValue = ' '.$sTagAttribute.'="';
		if (in_array($sTagAttribute, array('href', 'src')))
		{
			return $sTagAttrValue.'http://www.combodo.com"';
		}

		if ($sTagAttribute === 'style')
		{
			return $sTagAttrValue.'color: black"';
		}

		return $sTagAttrValue.$iAttributeCounter.'"';
	}

	private function IsClosingTag($sTag)
	{
		if (in_array($sTag, array('br', 'img', 'hr')))
		{
			return false;
		}

		return true;
	}

	/**
	 * @dataProvider CallInlineImageProcessImageTagProvider
	 */
	public function testDoSanitizeCallInlineImageProcessImageTag($sHtml, $iExpectedCount)
	{
		require_once APPROOT.'test/core/sanitizer/InlineImageMock.php';

		$oSanitizer = new HTMLDOMSanitizer();
		$oSanitizer->DoSanitize($sHtml);

		$iCalledCount = \InlineImage::GetCallCounter();
		$this->assertEquals($iExpectedCount, $iCalledCount);
	}

	public function CallInlineImageProcessImageTagProvider()
	{
		return array(
			'no image' => array(
				'html' => '<p>bar</p>',
				'expected' => 0,
			),
			'basic image' => array(
				'html' => '<img />',
				'expected' => 1,
			),
			'nested images within forbidden tags' => array(
				'html' => '<html><body><img /><iframe baz="1"><div baz="baz"><article baz="1" biz="2">baz<img /><img /></article>rab</div> oof<img /></iframe><img /></body></html>',
				'expected' => 5,
			),
			'nested images within forbidden and removed tags' => array(
				'html' => '<html><body><img /><iframe baz="1"><div baz="baz"><object baz="1" biz="2">baz<img /><img /></object>rab</div> oof<img /></iframe><img /></body></html>',
				'expected' => 3,
			),
		);
	}


}

