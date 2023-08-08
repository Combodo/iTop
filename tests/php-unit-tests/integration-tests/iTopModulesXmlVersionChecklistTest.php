<?php
/**
 * Copyright (C) 2013-2023 Combodo SARL
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
 * @covers iTopDesignFormat
 *
 * @package Combodo\iTop\Test\UnitTest\Setup
 * @group beforeSetup
 */
class iTopModulesXmlVersionIntegrationTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('setup/itopdesignformat.class.inc.php');
	}


	/**
	 * Verify if the `datamodels/2.x/datamodel.*.xml` files refer to the latest version of the design
	 * This is an integration test
	 *
	 * As ess and pro targets are copying modules into datamodels/2.x this test can only be run on a community target !
	 *
	 * @group itop-community
	 * @group skipPostBuild
	 *
	 * @dataProvider DatamodelItopXmlVersionProvider
	 *
	 * @since 3.0.3 3.1.0 move itop-community group in this method
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

		$sAppRoot = $this->GetAppRoot();

		$sPath = $sAppRoot.'datamodels/2.x/*/datamodel.*.xml';
		$aXmlFiles = glob($sPath);

		$aXmlFiles[] = $sAppRoot.'core/datamodel.core.xml';
		$aXmlFiles[] = $sAppRoot.'application/datamodel.application.xml';

		$aTestCases = array();
		foreach ($aXmlFiles as $sXmlFile) {
			$aTestCases[$sXmlFile] = array(
				'sXmlFile' => $sXmlFile,
			);
		}

		return $aTestCases;
	}

}
