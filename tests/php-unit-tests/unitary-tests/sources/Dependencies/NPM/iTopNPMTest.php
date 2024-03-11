<?php

namespace Combodo\iTop\Test\UnitTest\Dependencies\NPM;

use Combodo\iTop\Dependencies\NPM\iTopNPM;
use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * Copyright (C) 2010-2023 Combodo SARL
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
class iTopNPMTest extends ItopTestCase
{

	protected function setUp(): void
	{
		parent::setUp();
		clearstatcache();
	}

	/**
	 * This is NOT a unit test, this test the iTop instance running the test ...
	 */
	public function testAllFoldersCovered()
	{
		$oDependenciesHandler = new iTopNPM();
		$aAllowedAndDeniedDirs = array_merge(
			$oDependenciesHandler->ListAllowedQuestionnableFoldersAbsPaths(),
			$oDependenciesHandler->ListDeniedQuestionnableFolderAbsPaths()
		);

		$aExistingDirs = $oDependenciesHandler->ListAllQuestionnableFoldersAbsPaths();

		$aMissing = array_diff($aExistingDirs, $aAllowedAndDeniedDirs);
		$aExtra = array_diff($aAllowedAndDeniedDirs, $aExistingDirs);

		$this->assertEmpty(
			$aMissing,
			'Test dirs exists in /node_modules !'."\n"
			.'  They must be declared either in the allowed or denied list in '.iTopNPM::class." (see N°2651).\n"
			.'  List of dirs:'."\n".var_export($aMissing, true)
		);
	}
}
