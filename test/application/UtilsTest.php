<?php
/**
 * Copyright (C) 2018 Dennis Lassiter
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

class UtilsTest extends \Combodo\iTop\Test\UnitTest\ItopTestCase
{
	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/utils.inc.php');
	}

	/**
	 * @dataProvider memoryLimitDataProvider
	 */
	public function testIsMemoryLimit($expected, $memoryLimit, $requiredMemory)
	{
		$this->assertSame($expected, utils::IsMemoryLimitOk($memoryLimit, $requiredMemory));
	}

	/**
	 * DataProvider for testIsMemoryLimitOk
	 *
	 * @return array
	 */
	public function memoryLimitDataProvider()
	{
		return [
			[true, '-1', 1024],
			[true, -1, 1024],
			[true, 1024, 1024],
			[true, 2048, 1024],
			[false, 1024, 2048],
		];
	}

	/**
	 * @dataProvider realPathDataProvider
	 */
	public function testRealPath($sPath, $sBasePath, $expected)
	{
		$this->assertSame($expected, utils::RealPath($sPath, $sBasePath));
	}

	public function realPathDataProvider()
	{
		parent::setUp(); // if not called, APPROOT won't be defined :(

		$sItopRootPath = APPROOT;
		$sSep = DIRECTORY_SEPARATOR;

		return [
			'licence.txt' => [$sItopRootPath.'license.txt', $sItopRootPath, $sItopRootPath.'license.txt'],
			'unexisting file' => [$sItopRootPath.'license_DOES_NOT_EXIST.txt', $sItopRootPath, false],
			'/license.txt' => [$sItopRootPath.$sSep.'license.txt', $sItopRootPath, $sItopRootPath.'license.txt'],
			'%2flicense.txt' => [$sItopRootPath.'%2flicense.txt', $sItopRootPath, false],
			'../license.txt' => [$sItopRootPath.'..'.$sSep.'license.txt', $sItopRootPath, false],
			'%2e%2e%2flicense.txt' => [$sItopRootPath.'%2e%2e%2flicense.txt', $sItopRootPath, false],
			'application/utils.inc.php with basepath=APPROOT' => [
				$sItopRootPath.'application/utils.inc.php',
				$sItopRootPath,
				$sItopRootPath.'application'.$sSep.'utils.inc.php',
			],
			'application/utils.inc.php with basepath=APPROOT/application' => [
				$sItopRootPath.'application/utils.inc.php',
				$sItopRootPath.'application',
				$sItopRootPath.'application'.$sSep.'utils.inc.php',
			],
			'basepath containing / and \\' => [
				$sItopRootPath.'sources/form/form.class.inc.php',
				$sItopRootPath.'sources/form\\form.class.inc.php',
				$sItopRootPath.'sources'.$sSep.'form'.$sSep.'form.class.inc.php',
			],
		];
	}
}