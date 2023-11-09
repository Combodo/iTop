<?php
/*!
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ormDocument;

/**
 * Tests of the ormDocument class
 */
class ormDocumentTest extends ItopDataTestCase
{
	/**
	 * @inheritDoc
	 */
	protected function LoadRequiredItopFiles(): void
	{
		parent::LoadRequiredItopFiles();

		$this->RequireOnceItopFile('core/ormdocument.class.inc.php');
	}


	/**
	 * @param array $aDocAData
	 * @param array $aDocBData
	 * @param bool $bExpectedResult
	 *
	 * @dataProvider EqualsExceptDownloadsCountProvider
	 */
	public function testEqualsExceptDownloadsCount(array $aDocAData, array $aDocBData, bool $bExpectedResult)
	{
		$oDocA = new ormDocument(base64_decode($aDocAData[0]), $aDocAData[1], $aDocAData[2], $aDocAData[3]);
		$oDocB = new ormDocument(base64_decode($aDocBData[0]), $aDocBData[1], $aDocBData[2], $aDocBData[3]);

		$bTestedResult = $oDocA->EqualsExceptDownloadsCount($oDocB);
		$this->assertSame($bExpectedResult, $bTestedResult);
	}

	public function EqualsExceptDownloadsCountProvider(): array
	{
		$sFirstDummyTextFileContentBase64 = "Rmlyc3Q=";
		$sSecondDummyTextFileContentBase64 = "U2Vjb25k";

		return [
			'Total different files' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0
				],
				[
					$sSecondDummyTextFileContentBase64,
					"image/png",
					"b.png",
					1
				],
				false,
			],
			'Different data only' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0
				],
				[
					$sSecondDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0
				],
				false,
			],
			'Different mime types only' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0
				],
				[
					$sFirstDummyTextFileContentBase64,
					"image/png",
					"a.txt",
					0
				],
				false,
			],
			'Different file names only' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0
				],
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"b.txt",
					0
				],
				false,
			],
			'Different download counts only' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0
				],
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					1
				],
				true,
			],
			'Identical files, different object instances' => [
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0
				],
				[
					$sFirstDummyTextFileContentBase64,
					"text/plain",
					"a.txt",
					0
				],
				false,
			],
		];
	}
}
