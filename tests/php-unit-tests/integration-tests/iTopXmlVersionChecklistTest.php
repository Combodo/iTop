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


/**
 * @package Combodo\iTop\Test\UnitTest\Setup
 * @group beforeSetup
 */
class iTopXmlVersionIntegrationTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('core/config.class.inc.php');
		$this->RequireOnceItopFile('setup/itopdesignformat.class.inc.php');
	}


	/**
	 * Verify if the latest version of the XML datamodel is aligned with the app. core version
	 * This is an integration test
	 *
	 * @group skipPostBuild
	 */
	public function testItopXmlVersion()
	{
		// Retrieve only first 2 parts of the version
		$aCoreVersionParts = explode('.', ITOP_CORE_VERSION);
		$sCoreVersion = $aCoreVersionParts[0].'.'.$aCoreVersionParts[1];

		$sXMLVersion = ITOP_DESIGN_LATEST_VERSION;
		$this->assertSame($sXMLVersion, $sCoreVersion, "XML datamodel version (ITOP_DESIGN_LATEST_VERSION={$sXMLVersion}) is not aligned with the app. core version (ITOP_CORE_VERSION={$sCoreVersion})");
	}
}
