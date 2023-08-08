<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

use ApplicationException;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use iTopDesignFormat;
use utils;


/**
 * @covers iTopDesignFormat
 *
 * @package Combodo\iTop\Test\UnitTest\Setup
 * @group beforeSetup
 */
class iTopModulesPhpVersionIntegrationTest extends ItopTestCase
{
	/**
	 * Verify if `module.*.php` files contained in `datamodels/1.x` or `datamodels/2.x` refers to the current itop version
	 * This is an integration test
	 *
	 * As ess and pro targets are copying modules into datamodels/2.x this test can only be run on a community target !
	 *
	 * @group itop-community
	 * @group skipPostBuild
	 *
	 * @dataProvider iTopModulesPhpVersionProvider
	 *
	 * @since 2.7.7 3.0.1 3.1.0 N°4714 uses new {@link ITOP_CORE_VERSION} constant
	 */
	public function testiTopModulesPhpVersion($sExpectedVersion, $sPhpFile)
	{

		$sModulePath = realpath($sPhpFile);
		$sModuleFileName = basename($sModulePath);
		$sModuleName = preg_replace('/[^.]+\.([^.]+)\.php/', '$1', $sModuleFileName);

		$sFileContent = file_get_contents($sPhpFile);

		preg_match(
			"#'$sModuleName/([^']+)'#",
			$sFileContent,
			$matches
		);

		$this->assertSame($sExpectedVersion, $matches[1],
			'Module desc file does not contain the same version as the core: '.$sPhpFile);
	}

	public function iTopModulesPhpVersionProvider()
	{
		parent::setUp();

		$this->RequireOnceItopFile('core/config.class.inc.php');
		$this->RequireOnceItopFile('application/utils.inc.php');

		if (is_dir(APPROOT.'datamodels/2.x')) {
			$DatamodelsPath = APPROOT.'datamodels/2.x';
		} elseif (is_dir(APPROOT.'datamodels/1.x')) {
			$DatamodelsPath = APPROOT.'datamodels/1.x';
		} else {
			throw new \Exception('Cannot local the datamodels directory');
		}

		$sPath = $DatamodelsPath.'/*/module.*.php';
		$aPhpFiles = glob($sPath);

		$sExpectedVersion = ITOP_CORE_VERSION;

		$aTestCases = array();
		foreach ($aPhpFiles as $sPhpFile) {
			$aTestCases[$sPhpFile] = array(
				'sExpectedVersion' => $sExpectedVersion,
				'sPhpFile' => $sPhpFile,
			);
		}

		return $aTestCases;
	}

	/**
	 * @dataProvider ItopWikiVersionProvider
	 * @since 2.7.7 3.0.1 3.1.1 N°4714 new ITOP_CORE_VERSION constant
	 */
	public function testItopWikiVersion($sItopVersion, $sExpectedWikiVersion) {
		try {
			$sActualWikiVersion = utils::GetItopVersionWikiSyntax($sItopVersion);
		}
		catch (ApplicationException $e) {
			self::fail('Cannot get wiki version : '.$e->getMessage());
		}
		self::assertSame($sExpectedWikiVersion, $sActualWikiVersion, 'Computed wiki version is wrong !');
	}

	public function ItopWikiVersionProvider()
	{
		return [
			['2.7.0', '2_7_0'],
			['2.7.7', '2_7_0'],
			['3.0.0', '3_0_0'],
			['3.0.1', '3_0_0'],
			['3.1.0', '3_1_0'],
			['3.1.1', '3_1_0'],
		];
	}
}
