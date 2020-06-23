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

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @covers utils
 */
class UtilsTest extends \Combodo\iTop\Test\UnitTest\ItopTestCase
{
	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/utils.inc.php');
	}

	public function testEndsWith()
	{
		$this->assertFalse(utils::EndsWith('a', 'bbbb'));
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
	 * @covers       utils::RealPath()
	 */
	public function testRealPath($sPath, $sBasePath, $expected)
	{
		$this->assertSame($expected, utils::RealPath($sPath, $sBasePath));
	}

	public function realPathDataProvider()
	{
		parent::setUp(); // if not called, APPROOT won't be defined :(

		$sSep = DIRECTORY_SEPARATOR;
		$sItopRootRealPath = realpath(APPROOT).$sSep;

		return [
			'licence.txt' => [APPROOT.'license.txt', APPROOT, $sItopRootRealPath.'license.txt'],
			'unexisting file' => [APPROOT.'license_DOES_NOT_EXIST.txt', APPROOT, false],
			'/license.txt' => [APPROOT.$sSep.'license.txt', APPROOT, $sItopRootRealPath.'license.txt'],
			'%2flicense.txt' => [APPROOT.'%2flicense.txt', APPROOT, false],
			'../license.txt' => [APPROOT.'..'.$sSep.'license.txt', APPROOT, false],
			'%2e%2e%2flicense.txt' => [APPROOT.'%2e%2e%2flicense.txt', APPROOT, false],
			'application/utils.inc.php with basepath=APPROOT' => [
				APPROOT.'application/utils.inc.php',
				APPROOT,
				$sItopRootRealPath.'application'.$sSep.'utils.inc.php',
			],
			'application/utils.inc.php with basepath=APPROOT/application' => [
				APPROOT.'application/utils.inc.php',
				APPROOT.'application',
				$sItopRootRealPath.'application'.$sSep.'utils.inc.php',
			],
			'basepath containing / and \\' => [
				APPROOT.'sources/form/form.class.inc.php',
				APPROOT.'sources/form\\form.class.inc.php',
				$sItopRootRealPath.'sources'.$sSep.'form'.$sSep.'form.class.inc.php',
			],
		];
	}

	/**
	 * @dataProvider LocalPathProvider
	 *
	 * @param $sAbsolutePath
	 * @param $expected
	 */
	public function testLocalPath($sAbsolutePath, $expected)
	{
		$this->assertSame($expected, utils::LocalPath($sAbsolutePath));

	}

	public function LocalPathProvider()
	{
		return array(
			'index.php' => array(
				'sAbsolutePath' => APPROOT.'index.php',
				'expected' => 'index.php',
			),
			'non existing' => array(
				'sAbsolutePath' => APPROOT.'nonexisting/nonexisting',
				'expected' => false,
			),
			'outside' => array(
				'sAbsolutePath' => '/tmp',
				'expected' => false,
			),
			'application/cmdbabstract.class.inc.php' => array(
				'sAbsolutePath' => APPROOT.'application/cmdbabstract.class.inc.php',
				'expected' => 'application/cmdbabstract.class.inc.php',
			),
			'dir' => array(
				'sAbsolutePath' => APPROOT.'application/.',
				'expected' => 'application',
			),
			'root' => array(
				'sAbsolutePath' => APPROOT.'.',
				'expected' => '',
			),
		);
	}

	/**
	 * @dataProvider appRootUrlProvider
	 * @covers utils::GetAppRootUrl
	 */
	public function testGetAppRootUrl($sReturnValue, $sCurrentScript, $sAppRoot, $sAbsoluteUrl)
	{
		$this->assertEquals($sReturnValue, utils::GetAppRootUrl($sCurrentScript, $sAppRoot, $sAbsoluteUrl));
	}

	public function appRootUrlProvider()
	{
		return array(
			'Setup index (windows antislash)' => array('http://localhost/', 'C:\Dev\wamp64\www\itop-dev\setup\index.php', 'C:\Dev\wamp64\www\itop-dev', 'http://localhost/setup/'),
			'Setup index (windows slash)' => array('http://127.0.0.1/', 'C:/web/setup/index.php', 'C:/web', 'http://127.0.0.1/setup/'),
			'Setup index (windows slash, drive letter case difference)' => array('http://127.0.0.1/', 'c:/web/setup/index.php', 'C:/web', 'http://127.0.0.1/setup/'),
		);
	}

}
