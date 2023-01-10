<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Test\UnitTest\ItopTestCase;

class SCSSCompilationTest extends ItopTestCase
{

	/**
	 * @dataProvider CompileDefaultThemesProvider
	 * @doesNotPerformAssertions
	 * @return void
	 */
	public function testCompileDefaultThemes($sSassRelPath, $aImportRelPaths)
	{
		$aImportPaths = [];
		foreach ($aImportRelPaths as $sPath) {
			$aImportPaths[] = APPROOT.$sPath;
		}

		$sSassPath = APPROOT.$sSassRelPath;
		$sCSS = utils::CompileCSSFromSASS(file_get_contents($sSassPath), $aImportPaths);

		$this->debug($sCSS);
	}

	public function CompileDefaultThemesProvider()
	{
		return [
			'console' => ['css/backoffice/main.scss', ['css/backoffice/']],
			'portal' => ['env-production/itop-portal-base/portal/public/css/bootstrap-theme-combodo.scss', ['env-production//itop-portal-base/portal/public/css/']],
		];
	}

}
