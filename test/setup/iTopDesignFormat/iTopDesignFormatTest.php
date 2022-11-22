<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMDocument;
use DOMXPath;
use iTopDesignFormat;


/**
 * @covers iTopDesignFormat
 *
 * @since 2.7.0 N°2586
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class iTopDesignFormatTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		require_once APPROOT.'setup/modelfactory.class.inc.php';
		require_once APPROOT.'setup/itopdesignformat.class.inc.php';
	}

	/**
	 * @covers       iTopDesignFormat::Convert
	 * @dataProvider ConvertProvider
	 *
	 * @param string $sXmlFileName Corresponding files should exist in the `Convert-samples` dir with the `.expected` and `.input` suffixes
	 *                      Example "1.7_to_1.6" for `Convert-samples/1.7_to_1.6.expected.xml` and `Convert-samples/1.7_to_1.6.input.xml`
	 *
	 * @throws \Exception
	 */
	public function testConvert($sXmlFileName, $iExpectedErrors = 0, $sFirstErrorMessage = '')
	{
		$sSamplesRelDirPath = 'Convert-samples/';

		$sExpectedXml = $this->GetFileContent($sSamplesRelDirPath.$sXmlFileName.'.expected');
		$oExpectedDesignFormat = static::GetItopFormatFromString($sExpectedXml);
		$sTargetVersion = $oExpectedDesignFormat->GetVersion();

		$sInputXml = $this->GetFileContent($sSamplesRelDirPath.$sXmlFileName.'.input');
		$oInputDesignFormat = static::GetItopFormatFromString($sInputXml);
		$bResult = $oInputDesignFormat->Convert($sTargetVersion);
		$aErrors = $oInputDesignFormat->GetErrors();
		$this->assertCount($iExpectedErrors, $aErrors,
			'errors in input format: '.var_export($aErrors, true));
		if ($iExpectedErrors > 0) {
			$this->assertFalse($bResult);
			$this->assertEquals($sFirstErrorMessage, $aErrors[0]);
		}

		/** @noinspection PhpRedundantOptionalArgumentInspection We REALLY want those options so specifying it anyway */
		$sConvertedXml = $oInputDesignFormat->GetXmlAsString(null, true, false);
		// Erase dynamic values
		$sConvertedXml = preg_replace('@<trashed_node id="\w+"@', '<trashed_node id="XXX"', $sConvertedXml);
		$this->assertEquals($sExpectedXml, $sConvertedXml);
	}

	private static function GetItopFormatFromString(string $sFileContent): iTopDesignFormat
	{
		$oInputDocument = new DOMDocument();
		/** @noinspection PhpComposerExtensionStubsInspection */
		libxml_clear_errors();
		$oInputDocument->formatOutput = true;
		$oInputDocument->preserveWhiteSpace = false;
		$oInputDocument->loadXML($sFileContent);

		return new iTopDesignFormat($oInputDocument);
	}

	public function ConvertProvider()
	{
		return [
			'1.6 to 1.7 2' => ['sXmlFileName' => '1.6_to_1.7_2'],
			'1.7 to 1.6'   => ['sXmlFileName' => '1.7_to_1.6'],
			'1.7 to 1.6 2' => ['sXmlFileName' => '1.7_to_1.6_2'],
			'1.7 to 3.0'   => ['sXmlFileName' => '1.7_to_3.0'],
			'3.0 to 1.7'   => ['sXmlFileName' => '3.0_to_1.7'],
			'3.0 to 3.1'   => ['sXmlFileName' => '3.0_to_3.1'],
			'3.1 to 3.0'   => ['sXmlFileName' => '3.1_to_3.0'],
			'Bug_4569'     => ['sXmlFileName' => 'Bug_4569'],
		];
	}

	/**
	 * @covers       iTopDesignFormat::Convert
	 * @dataProvider ConvertBackAndForthProvider
	 *
	 * @param string $sTargetVersion
	 * @param string $sXmlFileName Example "1.7_to_1.6". Corresponding files should exist with the ".input" and ".Expected" suffix
	 *
	 * @throws \Exception
	 */
	public function testConvertBackAndForth($sTargetVersion, $sXmlFileName)
	{
		$sSamplesRelDirPath = 'Convert-samples/';
		$sInputXml = $this->GetFileContent($sSamplesRelDirPath.$sXmlFileName.'.input');

		$oInputDocument = new DOMDocument();
		libxml_clear_errors();
		$oInputDocument->preserveWhiteSpace = false;
		$oInputDocument->loadXML($sInputXml);

		$oXPath = new DOMXPath($oInputDocument);
		$oItopDesignNode = $oXPath->query('/itop_design')->item(0);
		if (!$oItopDesignNode) {
			$this->fail('Bad XML format');
		}
		$sSourceVersion = $oItopDesignNode->getAttribute('version');

		$oInputDocument->formatOutput = true;
		$oDesignFormat = new iTopDesignFormat($oInputDocument);
		$oDesignFormat->Convert($sTargetVersion);
		$sConvertedXml = $oInputDocument->saveXML();

		// Convert back
		$oInputDocument = new DOMDocument();
		libxml_clear_errors();
		$oInputDocument->preserveWhiteSpace = false;
		$oInputDocument->loadXML($sConvertedXml);
		$oInputDocument->formatOutput = true;
		$oDesignFormat = new iTopDesignFormat($oInputDocument);
		$oDesignFormat->Convert($sSourceVersion);
		$sConvertedXml = $oInputDocument->saveXML();

		$this->assertEquals($sInputXml, $sConvertedXml);
	}

	public function ConvertBackAndForthProvider()
	{
		return [
			'1.7 to 3.0' => ['3.0', '1.7'],
		];
	}

	/**
	 * @covers       iTopDesignFormat::MoveNode
	 * @dataProvider MoveNodeProvider
	 *
	 * @param string $sXmlFileName Example "from_deleted_to_not-in-definition"
	 */
	public function testMoveNode(string $sXmlFileName)
	{
		$sSamplesRelDirPath = 'MoveNode-samples/';
		$sInputXml = $this->GetFileContent($sSamplesRelDirPath.$sXmlFileName.'.input');
		$sExpectedXml = $this->GetFileContent($sSamplesRelDirPath.$sXmlFileName.'.expected');

		// Prepare document
		$oInputDocument = new DOMDocument();
		libxml_clear_errors();
		$oInputDocument->preserveWhiteSpace = false;
		$oInputDocument->loadXML($sInputXml);
		$oInputDocument->formatOutput = true;
		$oDesignFormat = new iTopDesignFormat($oInputDocument);

		$oXPath = new DOMXPath($oInputDocument);

		// Move nodes
		// Note: We could have pass the XPaths in the provider, but as for now they are the same for all cases, it's easier to read like this. Feel free to change it in the future if necessary.
		$oFNode = $oXPath->query("//f")->item(0);
		// - Self node
		$oCNodeList = $oXPath->query("//c");
		if ($oCNodeList->length > 0) {
			$oCNode = $oCNodeList->item(0);
			$this->InvokeNonPublicMethod('iTopDesignFormat', 'MoveNode', $oDesignFormat, [$oCNode, $oFNode]);
		}
		// - In parent node
		$oENodeList = $oXPath->query("//e");
		if ($oENodeList->length > 0) {
			$oENode = $oENodeList->item(0);
			$this->InvokeNonPublicMethod('iTopDesignFormat', 'MoveNode', $oDesignFormat, [$oENode, $oFNode]);
		}

		$sConvertedXml = $oInputDocument->saveXML();
		$this->assertEquals($sExpectedXml, $sConvertedXml);
	}

	public function MoveNodeProvider()
	{
		return array(
			'From deleted to deleted' => array('from_deleted_to_deleted'),
			'From deleted to in definition' => array('from_deleted_to_in-definition'),
			'From deleted to not in definition' => array('from_deleted_to_not-in-definition'),
			'From in definition to deleted' => array('from_in-definition_to_deleted'),
			'From in definition to in definition' => array('from_in-definition_to_in-definition'),
			'From in definition to not in definition' => array('from_in-definition_to_not-in-definition'),
			'From not in definition to deleted' => array('from_not-in-definition_to_deleted'),
			'From not in definition to in definition' => array('from_not-in-definition_to_in-definition'),
			'From not in definition to not in definition' => array('from_not-in-definition_to_not-in-definition'),
		);
	}

	private function GetFileContent($sFileName)
	{
		$sCurrentPath = __DIR__;

		return file_get_contents($sCurrentPath.DIRECTORY_SEPARATOR.$sFileName.'.xml');
	}
}