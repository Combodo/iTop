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
class iTopModulesPhpVersionIntegrationTest extends ItopTestCase {
	/**
	 * We had a problem when version was switched from 2.8.0 to 3.0.0, so this test aims to detect such problems
	 *
	 * @param string $sVersion
	 * @param string $sExpectedMinVersion if null the test will expects an exception to occur
	 *
	 * @throws \Exception
	 * @since 3.0.0
	 * @dataProvider GetItopMinorVersionProvider
	 */
	public function testGetItopMinorVersion($sVersion, $sExpectedMinVersion) {
		if (is_null($sExpectedMinVersion)) {
			$this->expectException(\Exception::class);
		}
		$sActualMinVersion = \utils::GetItopMinorVersion($sVersion);
		if (!is_null($sExpectedMinVersion)) {
			$this->assertEquals($sExpectedMinVersion, $sActualMinVersion);
		}
	}

	public function GetItopMinorVersionProvider() {
		return [['2.8.0', '2.8'], ['3.0.0', '3.0'], ['3.', null], ['3', null]];
	}

	/**
	 * @param string $sPhpFile iTop module file
	 *
	 * @return string module version
	 */
	private function GetItopModuleVersion(string $sPhpFile): ?string {
		$sModulePath = realpath($sPhpFile);
		$sModuleFileName = basename($sModulePath);
		$sModuleName = preg_replace('/[^.]+\.([^.]+)\.php/', '$1', $sModuleFileName);

		$sFileContent = file_get_contents($sPhpFile);

		preg_match(
			"#'$sModuleName/([^']+)'#",
			$sFileContent,
			$matches
		);

		return $matches[1] ?? '';
	}

	/**
	 * Verify if the datamodel.*.xml files refer to the current itop version
	 * This is an integration test
	 *
	 * @group skipPostBuild
	 * @uses utils::GetItopMinorVersion()
	 */
	public function testITopModulesPhpVersion(): void {
		if (is_dir(APPROOT.'datamodels/2.x')) {
			$DatamodelsPath = APPROOT.'datamodels/2.x';
		} elseif (is_dir(APPROOT.'datamodels/1.x')) {
			$DatamodelsPath = APPROOT.'datamodels/1.x';
		} else {
			throw new \Exception('Cannot local the datamodels directory');
		}

		require_once APPROOT.'core/config.class.inc.php';
		$sPath = $DatamodelsPath.'/*/module.*.php';
		$aPhpFiles = glob($sPath);

		$sMinorVersion = \utils::GetItopMinorVersion();
		$sExpectedVersion = '/^'.str_replace('.', '\.', $sMinorVersion).'\.\d+$/';

		$aModuleWithError = [];
		foreach ($aPhpFiles as $sPhpFile) {
			$sActualVersion = $this->GetItopModuleVersion($sPhpFile);

			if (!preg_match($sExpectedVersion, $sActualVersion)) {
				$aModuleWithError[$sPhpFile] = $sActualVersion;
			}
		}

		self::assertEquals([], $aModuleWithError, 'Some modules have wrong versions ! They should match '.$sExpectedVersion);
	}
}
