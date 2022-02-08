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


/**
 * @group itop-community
 *
 * @package Combodo\iTop\Test\UnitTest\Setup
 */
class iTopModulesPhpVersionIntegrationTest extends ItopTestCase {
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
	 *
	 * @since 2.7.7 3.0.1 3.1.0 N°4714 uses new {@link ITOP_CORE_VERSION} constant
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

		$sExpectedVersion = ITOP_CORE_VERSION;

		$aModuleWithError = [];
		foreach ($aPhpFiles as $sPhpFile) {
			$sActualVersion = $this->GetItopModuleVersion($sPhpFile);

			$this->assertSame($sExpectedVersion, $sActualVersion,
				'Module desc file does not contain the same version as the core: '.$sPhpFile);
		}

		self::assertEquals([], $aModuleWithError, 'Some modules have wrong versions ! They should match '.$sExpectedVersion);
	}
}
