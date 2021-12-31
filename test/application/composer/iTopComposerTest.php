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

	protected function setUp()
	{
		parent::setUp();
		clearstatcache();
	}

	public function testListAllTestDir()
	{
		$oiTopComposer = new iTopComposer();
		$aDirs = $oiTopComposer->ListAllTestDir();

		$this->assertTrue(is_array($aDirs));

		foreach ($aDirs as $sDir)
		{
			$this->assertRegExp('#[tT]ests?/?$#', $sDir);
		}

	}

	public function testListDeniedTestDir()
	{
		$oiTopComposer = new iTopComposer();
		$aDirs = $oiTopComposer->ListDeniedTestDir();

		$this->assertTrue(is_array($aDirs));

		foreach ($aDirs as $sDir)
		{
			$this->assertRegExp('#[tT]ests?/?$#', $sDir);
		}
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

		$this->assertEmpty($aMissing, "The iTop instance running this test has matching directories That must be either in the allowed or in the denied list:".var_export($aMissing, true));

	}


}
