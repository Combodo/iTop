<?php

namespace Combodo\iTop\Test\UnitTest\Core\Sanitizer;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

abstract class AbstractDOMSanitizerTest extends ItopTestCase
{
	const INPUT_DIRECTORY = 'input';
	const OUTPUT_DIRECTORY = 'output';

	protected function setUp(): void
	{
		parent::setUp();
		require_once(APPROOT.'application/utils.inc.php');
		require_once(APPROOT.'core/htmlsanitizer.class.inc.php');
	}

	protected function ReadTestFile($sFileToTest, $sFolderName)
	{
		$sCurrentPath = __DIR__;

		return file_get_contents($sCurrentPath.DIRECTORY_SEPARATOR
			.$sFolderName.DIRECTORY_SEPARATOR
			.$sFileToTest);
	}

	protected function RemoveNewLines($sText)
	{
		$sText = str_replace("\r\n", "\n", $sText);
		$sText = str_replace("\r", "\n", $sText);
		$sText = str_replace("\n", '', $sText);

		return $sText;
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
	protected function GetTagAttributeValue($sTagAttribute, $iAttributeCounter)
	{
		$sTagAttrValue = ' '.$sTagAttribute.'="';
		if (in_array($sTagAttribute, array('href', 'src'))) {
			return $sTagAttrValue.'http://www.combodo.com"';
		}

		if ($sTagAttribute === 'style') {
			return $sTagAttrValue.'color: black"';
		}

		return $sTagAttrValue.$iAttributeCounter.'"';
	}

	protected function IsClosingTag($sTag)
	{
		if (in_array($sTag, array('br', 'img', 'hr'))) {
			return false;
		}

		return true;
	}
}

