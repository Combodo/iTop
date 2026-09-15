<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\DotMake\Dependencies\Composer\Tcpdf;

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @coversNothing
 */
class TcpdfUpdateFontsTest extends ItopTestCase
{
	public function testThatDroidSansFallbackFilesAreCopiedToTcpdfFontsFolderAfterLibraryUpdate(): void
	{
		$sSourcePattern = APPROOT
			.'.make'.DIRECTORY_SEPARATOR.'dependencies'.DIRECTORY_SEPARATOR.'composer'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'droidsansfallback.*';
		$aSourceFiles = glob($sSourcePattern);
		$this->assertIsArray($aSourceFiles, 'Unable to read source TCPDF custom font files.');
		$this->assertNotEmpty($aSourceFiles, 'No source files found for pattern droidsansfallback.*');

		foreach ($aSourceFiles as $sSourceFilePath) {
			$sFontFileName = basename($sSourceFilePath);
			$sDestinationFilePath = APPROOT
				.'lib'.DIRECTORY_SEPARATOR.'tecnickcom'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.$sFontFileName;

			$this->assertFileExists($sDestinationFilePath, "Missing copied font file: {$sFontFileName}");
			$this->assertSame(
				hash_file('sha256', $sSourceFilePath),
				hash_file('sha256', $sDestinationFilePath),
				"Copied font file content mismatch: {$sFontFileName}"
			);
		}
	}
}
