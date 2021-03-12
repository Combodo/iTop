<?php
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

namespace Combodo\iTop\Test\UnitTest\ReleaseChecklist;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use iTopDesignFormat;


/**
 * Class iTopDesignFormatChecklistTest
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 * @covers iTopDesignFormat
 *
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class SetupCssIntegrityChecklistTest extends ItopTestCase
{

	protected function setUp()
	{
		parent::setUp();
	}

	/**
	 */
	public function testSetupCssIntegrity()
	{
		$sSetupCssPath = APPROOT.'css/setup.css';
		$sSetupCssContent = file_get_contents($sSetupCssPath);
		$this->assertContains('/* integrityCheck: begin (do not remove/edit) */', $sSetupCssContent);
		$this->assertContains('/* integrityCheck: end (do not remove/edit) */', $sSetupCssContent);
		$this->assertGreaterThan(4000, strlen($sSetupCssContent), "Test if the resulting file $sSetupCssPath is long enough, the value is totally arbitrary (at the time of the writing the file is 5660o  long");
	}

}
