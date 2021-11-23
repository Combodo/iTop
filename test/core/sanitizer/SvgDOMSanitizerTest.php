<?php

namespace Combodo\iTop\Test\UnitTest\Core\Sanitizer;

use SvgDOMSanitizer;


require_once __DIR__.'/AbstractDOMSanitizerTest.php';


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 */
class SvgDOMSanitizerTest extends AbstractDOMSanitizerTest
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

		$oSanitizer = new SvgDOMSanitizer();
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
		return array(
			array(
				'scripts.svg',
			),
		);
	}
}

