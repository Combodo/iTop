<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\Helper;

use Combodo\iTop\Application\Helper\CKEditorHelper;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @covers WebPage
 */
class CKEditorHelperTest extends ItopTestCase
{
	/**
	 * @dataProvider CheckFilesExistProvider
	 * @param string $sMethodName
	 */
	public function testCheckFilesExist($sMethodName)
	{
		$aFilesRelPaths = CKEditorHelper::$sMethodName();
		foreach ($aFilesRelPaths as $sFileRelPath) {
			$this->assertTrue(file_exists(APPROOT.$sFileRelPath), $sMethodName.' method returns a non existing file: '.$sFileRelPath);
		}
	}

	public function CheckFilesExistProvider(): array
	{
		return [
			'GetJSFilesRelPathsForCKEditor' => ['GetJSFilesRelPathsForCKEditor'],
		];
	}
}
