<?php

namespace Combodo\iTop\Test\UnitTest\Core\Sanitizer;

use HTMLSanitizer;
use SVGDOMSanitizer;


require_once __DIR__.'/AbstractDOMSanitizerTest.php';


class SVGDOMSanitizerTest extends AbstractDOMSanitizerTest
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

		$oSanitizer = new SVGDOMSanitizer();
		$sResFromSvgSanitizer = $oSanitizer->DoSanitize($sInputHtml);

		// Removing newlines as the parser gives different results depending on the PHP version
		// Didn't manage to get it right :
		// - no php.ini difference
		// - playing with the parser preserveWhitespace/formatOutput parser options didn't help
		// So we're removing new lines on both sides :/
		$sOutputHtml = $this->RemoveNewLines($sOutputHtml);
		$sResFromSvgSanitizer = $this->RemoveNewLines($sResFromSvgSanitizer);

		$this->debug($sResFromSvgSanitizer);
		$this->assertEquals($sOutputHtml, $sResFromSvgSanitizer);

		// N°6023 checking call through the factory is working as well
		$sResFromSanitizerFactory = HTMLSanitizer::Sanitize($sInputHtml, 'svg_sanitizer');
		$sResFromSanitizerFactory = $this->RemoveNewLines($sResFromSanitizerFactory);
		$this->assertEquals($sOutputHtml, $sResFromSanitizerFactory);
	}

	public function DoSanitizeProvider()
	{
		return [
			['scripts.svg'],
		];
	}
}

