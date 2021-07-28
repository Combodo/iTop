<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMDocument;
use DOMXPath;
use iTopDesignFormat;


/**
 * Class iTopDesignFormatTest
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 * @covers iTopDesignFormat
 *
 * @since 2.7.0 N°2586
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class TestForITopDesignFormatClass extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();

		require_once APPROOT.'setup/modelfactory.class.inc.php';
		require_once APPROOT.'setup/itopdesignformat.class.inc.php';
	}

	/**
	 * @covers       iTopDesignFormat::Convert
	 * @dataProvider ConvertProvider
	 *
	 * @param string $sTargetVersion
	 * @param string $sXmlFileName Example "1.7_to_1.6". Corresponding files should exist with the ".input" and ".Expected" suffix
	 *
	 * @throws \Exception
	 */
	public function testConvert($sTargetVersion, $sXmlFileName)
	{
		$sSamplesRelDirPath = 'Convert-samples/';
		$sInputXml = $this->GetFileContent($sSamplesRelDirPath.$sXmlFileName.'.input');
		$sExpectedXml = $this->GetFileContent($sSamplesRelDirPath.$sXmlFileName.'.expected');

		$oInputDocument = new DOMDocument();
		libxml_clear_errors();
		$oInputDocument->preserveWhiteSpace = false;
		$oInputDocument->loadXML($sInputXml);
		$oInputDocument->formatOutput = true;
		$oDesignFormat = new iTopDesignFormat($oInputDocument);
		$oDesignFormat->Convert($sTargetVersion);
		$sConvertedXml = $oInputDocument->saveXML();

		$this->assertEquals($sExpectedXml, $sConvertedXml);
	}

	public function ConvertProvider()
	{
		return array(
			'1.7 to 1.6' => array('1.6', '1.7_to_1.6'),
			'1.7 to 3.0' => array('3.0', '1.7_to_3.0'),
			'3.0 to 1.7' => array('1.7', '3.0_to_1.7'),
		);
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