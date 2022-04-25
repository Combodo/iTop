<?php

use Combodo\iTop\Composer\iTopComposer;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * Copyright (C) 2010-2021 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */


class iTopComposerTest extends ItopTestCase
{

	protected function setUp(): void
	{
		parent::setUp();
		clearstatcache();
	}

	/**
	 * @dataProvider IsTestDirProvider
	 * @return void
	 */
	public function testIsTestDir($sDirName, $bIsTest)
	{
		$isTestDir = iTopComposer::IsTestDir($sDirName);
		$this->assertIsInt($isTestDir);
		if (true === $bIsTest) {
			$this->assertTrue(($isTestDir > 0));
		} else {
			$this->assertSame(0, $isTestDir);
		}
	}

	public function IsTestDirProvider()
	{
		return [
			'test'    => ['test', true],
			'Test'    => ['Test', true],
			'tests'   => ['tests', true],
			'Tests'   => ['Tests', true],
			'testaa'  => ['testaa', false],
			'Testaa'  => ['Testaa', false],
			'testsaa' => ['testsaa', false],
			'Testsaa' => ['Testsaa', false],
		];
	}

	public function testListAllTestDir()
	{
		$oiTopComposer = new iTopComposer();
		$aDirs = $oiTopComposer->ListAllTestDir();

		$this->assertTrue(is_array($aDirs));

		foreach ($aDirs as $sDir) {
			$sDirName = basename($sDir);
			$this->assertRegExp(iTopComposer::TEST_DIR_REGEXP, $sDirName, "Directory not matching test dir : $sDir");
		}

	}

	public function testListDeniedTestDir()
	{
		$oiTopComposer = new iTopComposer();
		$aDirs = $oiTopComposer->ListDeniedTestDir();

		$this->assertTrue(is_array($aDirs));

		$aDeniedDirWrongFormat = [];
		foreach ($aDirs as $sDir)
		{
			if (false === iTopComposer::IsTestDir($sDir)) {
				$aDeniedDirWrongFormat[] = $sDir;
			}
		}

		$this->assertEmpty($aDeniedDirWrongFormat,
			'There are elements in \Combodo\iTop\Composer\iTopComposer::ListDeniedTestDir that are not test dirs :'.var_export($aDeniedDirWrongFormat, true));
	}

	public function testListAllowedTestDir()
	{
		$oiTopComposer = new iTopComposer();
		$aDirs = $oiTopComposer->ListAllowedTestDir();

		$this->assertTrue(is_array($aDirs));
	}

	/**
	 * This is NOT a unit test, this test the iTop instance running the test ...
	 */
	public function testNoDeniedDirIsPresentForNow()
	{
		$oiTopComposer = new iTopComposer();

		$aDeniedButStillPresent = $oiTopComposer->ListDeniedButStillPresent();

		$this->assertEmpty(
			$aDeniedButStillPresent,
			'The iTop instance running this test must not contain any denied test directory, found: '.var_export($aDeniedButStillPresent, true)
		);
	}


	/**
	 * This is NOT a unit test, this test the iTop instance running the test ...
	 */
	public function testAllDirCovered()
	{
		$oiTopComposer = new iTopComposer();
		$aAllowedAndDeniedDirs = array_merge(
			$oiTopComposer->ListAllowedTestDir(),
			$oiTopComposer->ListDeniedTestDir()
		);

		$aExistingDirs = $oiTopComposer->ListAllTestDir();

		$aMissing = array_diff($aExistingDirs, $aAllowedAndDeniedDirs);
		$aExtra = array_diff($aAllowedAndDeniedDirs, $aExistingDirs);

		$this->assertEmpty(
			$aMissing,
			'Test dirs exists in /lib !'."\n"
				.'  They must be declared either in the allowed or denied list in '.iTopComposer::class." (see N°2651).\n"
				.'  List of dirs:'."\n".var_export($aMissing, true)
		);
	}
}
