<?php
/**
 * Copyright (C) 2010-2020 Combodo SARL
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
use utils;


/**
 * Class SetupCssIntegrityChecklistTest
 */
class SetupCssIntegrityChecklistTest extends ItopTestCase
{
	public function testSetupCssIntegrity()
	{
		$sCssFileAbsPath = APPROOT."css/setup.css";

        // First check if the compiled file exists
        $this->assertTrue(file_exists($sCssFileAbsPath));

        // Then check that it is not empty
        $sVersionedCssFileContent = file_get_contents($sCssFileAbsPath);
        $this->assertGreaterThan(0, strlen($sVersionedCssFileContent), "Compiled setup.css file seems empty");

        // Then check that the compiled file is up-to-date
		$sScssFileRelPath = "css/setup.scss";
		$sScssFileAbsPath = APPROOT . $sScssFileRelPath;
        touch($sScssFileAbsPath);
        utils::GetCSSFromSASS($sScssFileRelPath);
        $sCompiledCssFileContent = file_get_contents($sCssFileAbsPath);
        $this->assertSame($sCompiledCssFileContent, $sVersionedCssFileContent, "Compiled setup.css file does not seem up to date as the one compiled just now is different");
	}
}
