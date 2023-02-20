<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use ApplicationMenu;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use MetaModel;
use MFCompiler;
use RunTimeEnvironment;

/**
 * @group menu_compilation
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @covers \MFCompiler::UseLatestPrecompiledFile
 */
class MFCompilerMenuTest extends ItopTestCase {
	private static $aPreviousEnvMenus;

	public function setUp(): void {
		parent::setUp();
		$this->RequireOnceItopFile('setup/compiler.class.inc.php');
		$this->RequireOnceItopFile('setup/modelfactory.class.inc.php');
		//$this->SetNonPublicProperty($oFactory, 'oDOMDocument', $oInitialDocument);
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	public function CompileMenusProvider(){
		return [
			'production' => ['production'],
			'phpunit' => ['phpunit'],
		];
	}
	/**
	 * @dataProvider CompileMenusProvider
	 */
	public function testCompileMenus($sEnv){
		if(\utils::GetCurrentEnvironment() != $sEnv) {
			MFCompiler::UseEnhancementMenuCompilation();
			$oRunTimeEnvironment = new RunTimeEnvironment($sEnv);
			$oRunTimeEnvironment->CompileFrom(\utils::GetCurrentEnvironment());
		}
		$this->RequireOnceItopFile('application/utils.inc.php');

		$sConfigFile = APPCONF.\utils::GetCurrentEnvironment().'/'.ITOP_CONFIG_FILE;
		MetaModel::Startup($sConfigFile, false /* $bModelOnly */, true /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);

		$aMenuGroups = ApplicationMenu::GetMenuGroups();
		$this->assertNotEquals([], $aMenuGroups);

		if (! is_null(static::$aPreviousEnvMenus)){
			$this->assertEquals(static::$aPreviousEnvMenus, $aMenuGroups);
		}
		static::$aPreviousEnvMenus = $aMenuGroups;

		//$this->InvokeNonPublicMethod(MFCompiler::class, 'CompileThemes', $this->oMFCompiler, [$oBrandingNode, $this->sTmpDir]);
	}
}
