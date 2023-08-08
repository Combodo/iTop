<?php

namespace Combodo\iTop\Test\UnitTest\Setup;

use ApplicationMenu;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Config;
use MetaModel;
use MFCompiler;
use ParentMenuNodeCompiler;
use RunTimeEnvironment;

/**
 * @group menu_compilation
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @since 3.1 N°4762
 * @covers \MFCompiler::DoCompile
 */
class MFCompilerMenuTest extends ItopTestCase {
	private static $aPreviousEnvMenus;
	private static $aPreviousEnvMenuCount;

	public function setUp(): void {
		parent::setUp();
		require_once APPROOT . 'setup/compiler.class.inc.php';
		require_once APPROOT . 'setup/modelfactory.class.inc.php';
		require_once APPROOT . 'application/utils.inc.php';
	}

	public function tearDown(): void {
		parent::tearDown();
	}

	private function GetCurrentEnvDeltaXmlPath(string $sEnv) : string {
		return APPROOT."data/$sEnv.delta.xml";
	}

	public function CompileMenusProvider(){
		return [
			'legacy_algo' => [ 'sEnv' => 'legacy_algo', 'bLegacyMenuCompilation' => true ],
			'menu_compilation_fix' => [ 'sEnv' => 'menu_compilation_fix', 'bLegacyMenuCompilation' => false ],
		];
	}

	/**
	 * @dataProvider CompileMenusProvider
	 */
	public function testCompileMenus($sEnv, $bLegacyMenuCompilation){
		$sConfigFilePath = \utils::GetConfigFilePath($sEnv);

		//copy conf from production to phpunit context
		$sDirPath = dirname($sConfigFilePath);
		if (! is_dir($sDirPath)){
			mkdir($sDirPath);
		}
		$oConfig = new Config(\utils::GetConfigFilePath());
		$oConfig->WriteToFile($sConfigFilePath);

		$oConfig = new Config($sConfigFilePath);
		if ($bLegacyMenuCompilation){
			ParentMenuNodeCompiler::UseLegacyMenuCompilation();
		}
		$oConfig->WriteToFile();
		$oRunTimeEnvironment = new RunTimeEnvironment($sEnv);
		$oRunTimeEnvironment->CompileFrom(\utils::GetCurrentEnvironment());
		$oConfig->WriteToFile();

		$sConfigFile = APPCONF.\utils::GetCurrentEnvironment().'/'.ITOP_CONFIG_FILE;
		MetaModel::Startup($sConfigFile, false /* $bModelOnly */, true /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);

		$aMenuGroups = ApplicationMenu::GetMenuGroups();
		if (! is_null(static::$aPreviousEnvMenus)){
			$this->assertEquals(static::$aPreviousEnvMenus, $aMenuGroups);
		} else {
			$this->assertNotEquals([], $aMenuGroups);
		}
		static::$aPreviousEnvMenus = $aMenuGroups;

		$aMenuCount = ApplicationMenu::GetMenusCount();

		if (! is_null(static::$aPreviousEnvMenuCount)){
			$this->assertEquals(static::$aPreviousEnvMenuCount, $aMenuCount);
		} else {
			$this->assertNotEquals([], $aMenuCount);
		}
		static::$aPreviousEnvMenuCount = $aMenuCount;
	}

	public function CompileMenusWithDeltaProvider(){
		return [
			'Menus are broken with specific delta XML using LEGACY algo' => [ 'sDeltaFile' => 'delta_broken_menus.xml', 'sEnv' => 'broken_menus', 'bLegacyMenuCompilation' => true ],
			'Menus repaired using same delta XML with NEW algo' => [ 'sDeltaFile' => 'delta_broken_menus.xml', 'sEnv' => 'fixed_menus', 'bLegacyMenuCompilation' => false ],
		];
	}

	/**
	 * @dataProvider CompileMenusWithDeltaProvider
	 */
	public function testCompileMenusWithDelta($sDeltaFile, $sEnv, $bLegacyMenuCompilation){
		$sProvidedDeltaPath = __DIR__.'/ressources/datamodels/'.$sDeltaFile;
		if (is_file($sProvidedDeltaPath)){
			$sDeltaXmlPath = $this->GetCurrentEnvDeltaXmlPath($sEnv);
			copy($sProvidedDeltaPath, $sDeltaXmlPath);
		}
		$sConfigFilePath = \utils::GetConfigFilePath($sEnv);

		//copy conf from production to phpunit context
		$sDirPath = dirname($sConfigFilePath);
		if (! is_dir($sDirPath)){
			mkdir($sDirPath);
		}
		$oConfig = new Config(\utils::GetConfigFilePath());
		$oConfig->WriteToFile($sConfigFilePath);

		$oConfig = new Config($sConfigFilePath);
		if ($bLegacyMenuCompilation){
			ParentMenuNodeCompiler::UseLegacyMenuCompilation();
		}
		$oConfig->WriteToFile();
		$oRunTimeEnvironment = new RunTimeEnvironment($sEnv);
		$oRunTimeEnvironment->CompileFrom(\utils::GetCurrentEnvironment());
		$oConfig->WriteToFile();

		if ($bLegacyMenuCompilation){
			/**
			 * PHP Notice:  Undefined index: ConfigManagement in /var/www/html/iTop/env-broken_menus/itop-structure/model.itop-structure.php on line 925
			 */
			error_reporting(E_ALL & ~E_NOTICE);
			$this->expectErrorMessage("Call to a member function GetIndex() on null");
		}
		$sConfigFile = APPCONF.\utils::GetCurrentEnvironment().'/'.ITOP_CONFIG_FILE;
		MetaModel::Startup($sConfigFile, false /* $bModelOnly */, true /* $bAllowCache */, false /* $bTraceSourceFiles */, $sEnv);

		$this->assertNotEquals([], ApplicationMenu::GetMenuGroups());
		$this->assertNotEquals([], ApplicationMenu::GetMenusCount());
	}
}
