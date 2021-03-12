<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
 * This file is part of iTop.
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Test\UnitTest\Integration;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use DOMDocument;
use iTopDesignFormat;


/**
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @group itop-community
 *
 * @covers iTopDesignFormat
 *
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class iTopModulesXmlVersionIntegrationTest extends ItopTestCase
{
	protected function setUp()
	{
		parent::setUp();

		require_once APPROOT.'setup/itopdesignformat.class.inc.php';
	}


	/**
	 * Verify if the datamodel.*.xml files refer to the latest version of the design
	 * This is an integration test
	 *
	 * @group skipPostBuild
	 *
	 * @dataProvider DatamodelItopXmlVersionProvider
	 */
	public function testDatamodelItopXmlVersion($sXmlFile)
	{
		$oOriginalXml = new DOMDocument();
		$oOriginalXml->load($sXmlFile);

		$oTransformedXml = new DOMDocument();
		$oTransformedXml->load($sXmlFile);
		$oFormat = new iTopDesignFormat($oTransformedXml);

		if ($oFormat->Convert()) {
			// Compare the original and new format
			$sExpectedXmlVersion = ITOP_DESIGN_LATEST_VERSION;
			$this->assertSame($oTransformedXml->saveXML(), $oOriginalXml->saveXML(),
				"Datamodel file $sXmlFile:2 not in the latest format ($sExpectedXmlVersion)");
		} else {
			$this->fail("Failed to convert $sXmlFile into the latest format");
		}
	}

	public function DatamodelItopXmlVersionProvider()
	{
		static::setUp();

		$sPath = APPROOT.'datamodels/2.x/*/datamodel.*.xml';
		$aXmlFiles = glob($sPath);

		$aXmlFiles[] = APPROOT.'core/datamodel.core.xml';
		$aXmlFiles[] = APPROOT.'application/datamodel.application.xml';

		$aTestCases = array();
		foreach ($aXmlFiles as $sXmlFile) {
			$aTestCases[$sXmlFile] = array(
				'sXmlFile' => $sXmlFile,
			);
		}

		return $aTestCases;
	}

}
