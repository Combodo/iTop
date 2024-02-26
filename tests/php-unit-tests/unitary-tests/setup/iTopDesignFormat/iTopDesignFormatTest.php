<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMDocument;
use DOMXPath;
use iTopDesignFormat;
use ReflectionException;
use utils;


/**
 * @covers iTopDesignFormat
 *
 * @since 2.7.0 N°2586
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class iTopDesignFormatTest extends ItopTestCase
{
	const SAMPLES_DIR_PATH = 'Convert-samples/';

	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('setup/modelfactory.class.inc.php');
		$this->RequireOnceItopFile('setup/itopdesignformat.class.inc.php');
	}

	public function testGetPreviousDesignVersion()
	{
		$this->assertSame('3.0', iTopDesignFormat::GetPreviousDesignVersion('3.1'));
		$this->assertSame('1.7', iTopDesignFormat::GetPreviousDesignVersion('3.0'));
		$this->assertSame('1.6', iTopDesignFormat::GetPreviousDesignVersion('1.7'));
		$this->assertSame('1.5', iTopDesignFormat::GetPreviousDesignVersion('1.6'));
		$this->assertNull(iTopDesignFormat::GetPreviousDesignVersion('1.0'));
		$this->assertNull(iTopDesignFormat::GetPreviousDesignVersion(''));
		$this->assertNull(iTopDesignFormat::GetPreviousDesignVersion('NonExistingVersion'));
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
		$sSamplesRelDirPath = self::SAMPLES_DIR_PATH;

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

	/**
	 * Same provider as {@see testConvert} so that we need to modify only 1 provider when adding a new version
	 *
	 * Filters the dataprovider to get only upward conversions
	 * On each upward conversion, will take the expected file and tries to convert from its immediate previous version
	 * For example in '3.0_To_3.1' we will get the expected file which is in 3.1 version, and tries to convert this file from 3.0 to 3.1 : result must be the same content.
	 *
	 * This will guarantee that update-xml script will continue to work: we want to be able to convert files from version N to version N during version N dev
	 *
	 * @dataProvider ConvertProvider
	 *
	 * @param $sXmlFileName Corresponding files should exist in the `Convert-samples`, with a '.expected' suffix
	 *
	 * @return void
	 * @since 3.1.0 N°5779 method creation
	 */
	public function testConvertNToN($sXmlFileName)
	{
		$sSamplesRelDirPath = self::SAMPLES_DIR_PATH;

		$sInputXml = $this->GetFileContent($sSamplesRelDirPath.$sXmlFileName.'.input');
		$oInputDesignFormat = static::GetItopFormatFromString($sInputXml);
		$sInputVersion = $oInputDesignFormat->GetVersion();

		$sExpectedXml = $this->GetFileContent($sSamplesRelDirPath.$sXmlFileName.'.expected');
		$oExpectedDesignFormat = static::GetItopFormatFromString($sExpectedXml);
		$sExpectedVersion = $oExpectedDesignFormat->GetVersion();

		if (version_compare($sInputVersion, $sExpectedVersion, '>=')) {
			$this->markTestSkipped("This dataset correspond to a downward conversion ($sInputVersion to $sExpectedVersion) and we want to test upwards conversions => skipping !");
		}

		$sExpectedPreviousVersion = iTopDesignFormat::GetPreviousDesignVersion($sExpectedVersion);
		$oExpectedDesignFormat->GetITopDesignNode()->setAttribute('version', $sExpectedPreviousVersion);
		$bConversionResult = $oExpectedDesignFormat->Convert($sExpectedVersion);

		$this->assertTrue($bConversionResult,
			'There were conversion errors: '.var_export($oExpectedDesignFormat->GetErrors(), true));

		/** @noinspection PhpRedundantOptionalArgumentInspection We REALLY want those options so specifying it anyway */
		$sConvertedXml = $oExpectedDesignFormat->GetXmlAsString(null, true, false);
		// Erase dynamic values
		$sConvertedXml = preg_replace('@<trashed_node id="\w+"@', '<trashed_node id="XXX"', $sConvertedXml);
		$this->assertEquals($sExpectedXml, $sConvertedXml, 'Havin a file with N version, applying conversion from N-1 to N should not change the content');
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

		return file_get_contents($sCurrentPath . DIRECTORY_SEPARATOR . $sFileName . '.xml');
	}

	/**
	 * @since 3.2.0 N°6558 method creation
	 */
	public function testAVersionsContent(): void
	{
		$aAVersionsErrors = [];

		foreach (iTopDesignFormat::$aVersions as $sXmlVersion => $aXmlVersionData) {
			foreach (['previous', 'go_to_previous', 'next', 'go_to_next'] as $sVersionParamKey) {
				if (false === array_key_exists($sVersionParamKey, $aXmlVersionData)) {
					$aAVersionsErrors[] = "$sXmlVersion version: missing `$sVersionParamKey` key !";
				}
			}

			foreach (['previous', 'next'] as $sXmlVersionPointingToKey) {
				if (false === array_key_exists($sXmlVersionPointingToKey, $aXmlVersionData)) {
					continue;
				}
				if (utils::IsNullOrEmptyString($aXmlVersionData[$sXmlVersionPointingToKey])) {
					continue;
				}
				if (false === \array_key_exists($aXmlVersionData[$sXmlVersionPointingToKey], iTopDesignFormat::$aVersions)) {
					$aAVersionsErrors[] = "$sXmlVersion version: invalid value for `$sXmlVersionPointingToKey` key ! Value=" . $aXmlVersionData[$sXmlVersionPointingToKey];
				}
			}

			$oItopDesignFormatClass = new \ReflectionClass(iTopDesignFormat::class);
			foreach (['go_to_previous', 'go_to_next'] as $sXmlConversionMethodKey) {
				if (false === array_key_exists($sXmlConversionMethodKey, $aXmlVersionData)) {
					continue;
				}
				$sXmlConversionMethod = $aXmlVersionData[$sXmlConversionMethodKey];
				if (utils::IsNullOrEmptyString($sXmlConversionMethod)) {
					continue;
				}
				try {
					/** @noinspection PhpExpressionResultUnusedInspection */
					$oItopDesignFormatClass->getMethod($sXmlConversionMethod);
				} catch (ReflectionException $e) {
					$aAVersionsErrors[] = "$sXmlVersion version: conversion method `$sXmlConversionMethod` for key `$sXmlConversionMethodKey` does not exist";
				}
			}
		}

		$this->assertCount(0, $aAVersionsErrors, 'There were errors detected in iTopDesignFormat::$aVersions : ' . var_export($aAVersionsErrors, true));
	}
}