<?php

namespace Combodo\iTop\Test\UnitTest\Module\iTopPortalDarkTheme;

use Combodo\iTop\Test\UnitTest\ItopTestCase;
use PortalDarkThemeUIExtension;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

/**
 * @covers \PortalDarkThemeUIExtension
 */
class PortalDarkThemeUIExtensionTest extends ItopTestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('application/applicationextension/portal/iPortalUIExtension.php');
		$this->RequireOnceItopFile('application/applicationextension/portal/AbstractPortalUIExtension.php');
		$this->RequireOnceItopFile('datamodels/2.x/itop-portal-dark-theme/main.itop-portal-dark-theme.php');
	}

	/**
	 * @dataProvider ComputeModeProvider
	 */
	public function testComputeMode(?string $sBackofficeThemeId, array $aDarkThemeIds, bool $bFollowOs, string $sExpectedMode)
	{
		$this->assertSame($sExpectedMode, PortalDarkThemeUIExtension::ComputeMode($sBackofficeThemeId, $aDarkThemeIds, $bFollowOs));
	}

	/**
	 * Note: values of the PortalDarkThemeUIExtension::ENUM_MODE_* constants, as the class is not loaded yet when providers run
	 */
	public static function ComputeModeProvider(): array
	{
		return [
			'dark theme, following OS' => ['darkmoon', ['darkmoon'], true, 'dark'],
			'dark theme, not following OS' => ['darkmoon', ['darkmoon'], false, 'dark'],
			'custom dark theme' => ['my-dark-theme', ['darkmoon', 'my-dark-theme'], false, 'dark'],
			'light theme, following OS' => ['fullmoon', ['darkmoon'], true, 'auto'],
			'light theme, not following OS' => ['fullmoon', ['darkmoon'], false, 'light'],
			'no theme, following OS' => [null, ['darkmoon'], true, 'auto'],
			'no dark theme configured' => ['darkmoon', [], false, 'light'],
		];
	}

	/**
	 * The stylesheets are served as is: they must be compiled from the SCSS sources and versioned.
	 *
	 * @dataProvider StylesheetsProvider
	 */
	public function testStylesheetIsUpToDate(string $sFileName)
	{
		$sCssDir = $this->GetAppRoot().'datamodels/2.x/itop-portal-dark-theme/css/';
		$oCompiler = new Compiler();
		$oCompiler->setOutputStyle(OutputStyle::COMPRESSED);
		$oCompiler->setImportPaths([$sCssDir]);
		$sExpectedCss = $oCompiler->compileString(file_get_contents($sCssDir.$sFileName.'.scss'))->getCss();

		$this->assertFileExists($sCssDir.$sFileName.'.css');
		$this->assertSame($sExpectedCss, file_get_contents($sCssDir.$sFileName.'.css'), "$sFileName.css is not up to date with the SCSS sources: compile it again");
	}

	public static function StylesheetsProvider(): array
	{
		return [
			'always dark' => ['portal-dark-theme'],
			'following the OS' => ['portal-dark-theme-auto'],
		];
	}
}
