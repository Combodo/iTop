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
use DOMDocument;
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
class iTopModulesXmlVersionChecklistTest extends ItopTestCase
{


	/**
	 * Verify if the datamodel.*.xml files refer to the current itop version
	 * This is part of the checklist tests.
	 *
	 * @dataProvider DatamodelItopXmlVersionProvider
	 */
	public function testDatamodelItopXmlVersion($sExpectedXmlVersion, $sXmlFile)
	{
		$sFileContent = file_get_contents($sXmlFile);

		preg_match(
			'/<itop_design .* version="([^"]+)">/',
			$sFileContent,
			$matches
		);

		$this->assertSame($sExpectedXmlVersion, $matches[1]);
	}

	public function DatamodelItopXmlVersionProvider()
	{
		parent::setUp();

		require_once APPROOT.'core/config.class.inc.php';
		require_once APPROOT.'application/utils.inc.php';

		$sPath = APPROOT.'datamodels/2.x/*/datamodel.*.xml';
		$aXmlFiles = glob($sPath);

		$sItopVersionShort = \utils::GetItopVersionShort();
		$aItopVersion = explode('.', $sItopVersionShort);
		$sExpectedXmlVersion = ($aItopVersion[0] - 1).'.'.($aItopVersion[1]); // eg: 2.7.0-dev become 1.7

		$aTestCases = array();
		foreach ($aXmlFiles as $sXmlFile)
		{
			$aTestCases[$sXmlFile] = array(
				'sExpectedXmlVersion' => $sExpectedXmlVersion,
				'sXmlFile' => $sXmlFile,
			);
		}

		return $aTestCases;
	}

}
