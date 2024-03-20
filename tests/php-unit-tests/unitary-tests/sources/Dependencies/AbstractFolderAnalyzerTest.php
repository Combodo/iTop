<?php

namespace Combodo\iTop\Test\UnitTest\Dependencies;

use Combodo\iTop\Test\UnitTest\Dependencies\Fake\FakeFolderAnalyzer;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

class AbstractFolderAnalyzerTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceUnitTestFile('Fake/FakeFolderAnalyzer.php');
	}

	public function testListDeniedButStillPresentFoldersAbsPaths(): void
	{
		$oAnalyzer = new FakeFolderAnalyzer();
		$this->assertEquals(
			$oAnalyzer->ExpectedDeniedButStillPresentFoldersAbsPaths(),
			$oAnalyzer->ListDeniedButStillPresentFoldersAbsPaths(),
			'Wrong calculation, we got:'.var_export($oAnalyzer->ListDeniedButStillPresentFoldersAbsPaths(), true)
		);
	}

	public function testListAllFoldersAbsPaths(): void
	{
		$oAnalyzer = new FakeFolderAnalyzer();
		$this->assertEquals(
			$oAnalyzer->ExpectedAllFoldersAbsPathsIsQuestionnable(),
			$oAnalyzer->ListAllFoldersAbsPaths(),
			'Wrong calculation, we got:'.var_export($oAnalyzer->ListAllFoldersAbsPaths(), true)
		);
	}
}