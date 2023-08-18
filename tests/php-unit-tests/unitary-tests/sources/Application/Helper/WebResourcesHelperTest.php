<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\Helper;

use Combodo\iTop\Application\Helper\WebResourcesHelper;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @covers \WebPage
 */
class WebResourcesHelperTest extends ItopTestCase
{
	/**
	 * @dataProvider CheckFilesExistProvider
	 * @param string $sMethodName
	 */
	public function testCheckFilesExist($sMethodName)
	{
		$aFilesRelPaths = WebResourcesHelper::$sMethodName();
		foreach ($aFilesRelPaths as $sFileRelPath) {
			$this->assertTrue(file_exists(APPROOT.$sFileRelPath), $sMethodName.' method returns a non existing file: '.$sFileRelPath);
		}
	}

	public function CheckFilesExistProvider(): array
	{
		return [
			'GetJSFilesRelPathsForCKEditor' => ['GetJSFilesRelPathsForCKEditor'],
			'GetCSSFilesRelPathsForC3JS' => ['GetCSSFilesRelPathsForC3JS'],
			'GetJSFilesRelPathsForC3JS' => ['GetJSFilesRelPathsForC3JS'],
		];
	}
}
