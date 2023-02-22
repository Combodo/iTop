<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use ApplicationMenu;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use MetaModel;
use MFCompiler;
use RunTimeEnvironment;

/**
 * @group menu_compilation
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @since 3.0.x N°4762
 * @covers \MFCompiler::UseLatestPrecompiledFile
 */
class MFCompilerMenuTest extends ItopTestCase {
	private static $aPreviousEnvMenus;

	public function setUp(): void {
		parent::setUp();
		$this->RequireOnceItopFile('setup/compiler.class.inc.php');
		$this->RequireOnceItopFile('setup/modelfactory.class.inc.php');
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
			$sConfigFilePath = \utils::GetConfigFilePath($sEnv);

			//copy conf from production to phpunit context
			$sDirPath = dirname($sConfigFilePath);
			if (! is_dir($sDirPath)){
				mkdir($sDirPath);
			}
			$oConfig = new Config(\utils::GetConfigFilePath());
			$oConfig->WriteToFile($sConfigFilePath);

			$oConfig = new Config($sConfigFilePath);
			$oConfig->Set('set_menu_compilation_algorithm', 'v2', 'test', true);
			$oConfig->WriteToFile();
			$oRunTimeEnvironment = new RunTimeEnvironment($sEnv);
			$oRunTimeEnvironment->CompileFrom(\utils::GetCurrentEnvironment());
			$oConfig->Set('set_menu_compilation_algorithm', 'v1', 'test', true);
			$oConfig->WriteToFile();
		}
		$this->RequireOnceItopFile('application/utils.inc.php');

		$sConfigFile = APPCONF.\utils::GetCurrentEnvironment().'/'.ITOP_CONFIG_FILE;
		MetaModel::Startup($sConfigFile, false /* $bModelOnly */, true /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);

		$aMenuGroups = ApplicationMenu::GetMenuGroups();
		$this->assertNotEquals([], $aMenuGroups);

		if (! is_null(static::$aPreviousEnvMenus)){
			$this->assertEquals(static::$aPreviousEnvMenus, $aMenuGroups);
		} else {
			$this->assertNotEquals([], $aMenuGroups);
		}
		static::$aPreviousEnvMenus = $aMenuGroups;

		//$this->InvokeNonPublicMethod(MFCompiler::class, 'CompileThemes', $this->oMFCompiler, [$oBrandingNode, $this->sTmpDir]);
	}
}
