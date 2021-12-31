<?php

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @covers \MFCompiler::UseLatestPrecompiledFile
 */
class MFCompilerTest extends ItopTestCase {
	/** @var array  */
	private static $aFoldersToCleanup;
	
	/** @var array  */
	private static $aRessources;

	/** @var \MFCompiler  */
	private $oMFCompiler;

	private $sTmpDir;

	public function setUp() {
		parent::setUp();
		require_once(APPROOT.'setup/compiler.class.inc.php');
		require_once(APPROOT.'setup/modelfactory.class.inc.php');
		require_once(__DIR__.'/SubMFCompiler.php');

		$this->sTmpDir = $this->CreateTmpdir();
		$this->oMFCompiler = new SubMFCompiler($this->createMock(\ModelFactory::class), '');
	}

	public function tearDown() {
		parent::tearDown();
		$this->RecurseRmdir($this->sTmpDir);
	}

	public static function Init(){
		if (!is_null(self::$aFoldersToCleanup)){
			return;
		}
		clearstatcache();
		$sPrefix = 'scsstest_';
		$sAppRootForProvider = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR;
		$sTempTargetDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'UseLatestPrecompiledFileProvider';
		$sExtensionTargetDir = $sAppRootForProvider . 'extensions/UseLatestPrecompiledFileProvider';
		$sSourceDir = $sAppRootForProvider . 'datamodels' . DIRECTORY_SEPARATOR . '2.x';
		$sDatamodel2xTargetDir = $sSourceDir . DIRECTORY_SEPARATOR . '/UseLatestPrecompiledFileProvider';

		mkdir($sTempTargetDir);
		mkdir($sExtensionTargetDir);
		mkdir($sDatamodel2xTargetDir);

		self::$aFoldersToCleanup = [ $sTempTargetDir, $sExtensionTargetDir, $sDatamodel2xTargetDir ];

		self::$aRessources['sPostCompilation1'] = tempnam($sTempTargetDir, $sPrefix);
		sleep(1);

		//datamodel XML file in extension folder
		self::$aRessources['sPrecompiledInExtensionFile1'] = tempnam($sExtensionTargetDir, $sPrefix);
		self::$aRessources['sPrecompiledInExtensionFileUri1'] = "UseLatestPrecompiledFileProvider" . DIRECTORY_SEPARATOR . basename(self::$aRessources['sPrecompiledInExtensionFile1']);

		//datamodel XML file in source dir /datamodels/2.x folder
		self::$aRessources['sPrecompiledInDataModelXXFile1'] = tempnam($sDatamodel2xTargetDir, $sPrefix);
		self::$aRessources['sPrecompiledInDataModelXXFileUri1'] = "UseLatestPrecompiledFileProvider" . DIRECTORY_SEPARATOR . basename(self::$aRessources['sPrecompiledInDataModelXXFile1']);

		sleep(1);


		//generate ressources from a previous setup: called postcompiled
		self::$aRessources['sPostCompilation2'] = tempnam($sTempTargetDir, $sPrefix);
		sleep(1);

		//simulate copy of /data/models.2.x or extensions ressources during setup in a temp directory
		self::$aRessources['sCopiedExtensionFile1'] = $sTempTargetDir . DIRECTORY_SEPARATOR . basename(self::$aRessources['sPrecompiledInExtensionFile1']);
		copy(self::$aRessources['sPrecompiledInExtensionFile1'], self::$aRessources['sCopiedExtensionFile1']);

		self::$aRessources['sCopiedDataModelXXFile1'] = $sTempTargetDir . DIRECTORY_SEPARATOR . basename(self::$aRessources['sPrecompiledInDataModelXXFile1']);
		copy(self::$aRessources['sPrecompiledInDataModelXXFile1'], self::$aRessources['sCopiedDataModelXXFile1']);

		self::$aRessources['sMissingFile'] = tempnam($sTempTargetDir, $sPrefix);
		unlink(self::$aRessources['sMissingFile']);

		/*foreach (self::$aRessources as $sKey => $sRessource){
			if (is_file($sRessource)) {
				var_dump("$sKey $sRessource:" . filemtime($sRessource));
			}
		}*/
	}

	public static function tearDownAfterClass()
	{
		if (is_null(self::$aFoldersToCleanup)){
			return;
		}
		
		foreach (self::$aFoldersToCleanup as $sFolder){
			if (is_dir($sFolder)){
				foreach (glob("$sFolder/**") as $sFile){
					unlink($sFile);
				}
				rmdir($sFolder);
			}
		}
	}

	/**
	 * @dataProvider UseLatestPrecompiledFileProvider
	 *
	 * @param string $sTempTargetDir
	 * @param string $sPrecompiledFileUri
	 * @param string $sPostCompilationLatestPrecompiledFile
	 * @param string $sThemeDir
	 * @param ?string $sExpectedReturn
	 */
	public function testUseLatestPrecompiledFile(string $sTempTargetDir, string $sPrecompiledFileUri, string $sPostCompilationLatestPrecompiledFile, string $sThemeDir, ?string $sExpectedReturn, bool $bDisableThemePrecompilationViaConf = false){
		if ($bDisableThemePrecompilationViaConf){
			utils::GetConfig()->Set('theme.enable_precompilation', false);
		}
		$sRes = $this->oMFCompiler->UseLatestPrecompiledFile($sTempTargetDir, $sPrecompiledFileUri, $sPostCompilationLatestPrecompiledFile, $sThemeDir);
		$this->assertEquals($sExpectedReturn, $sRes);
	}

	public function UseLatestPrecompiledFileProvider(){
		self::init();
		return [
			'no precompiled file at all' => $this->BuildProviderUseCaseArray('', self::$aRessources['sMissingFile'], null),
			'deactivate precompilation via conf' => $this->BuildProviderUseCaseArray('', self::$aRessources['sPostCompilation1'], null, true),
			'no precompiled file configured in precompiled_stylesheet XM section' => $this->BuildProviderUseCaseArray('', self::$aRessources['sPostCompilation1'], self::$aRessources['sPostCompilation1']),
			'missing precompiled file in precompiled_stylesheet section' => $this->BuildProviderUseCaseArray(self::$aRessources['sMissingFile'], self::$aRessources['sPostCompilation1'], self::$aRessources['sPostCompilation1'] ),
			'no precompiled file generated in previous setup in /data/precompiled_styles' => $this->BuildProviderUseCaseArray(self::$aRessources['sPrecompiledInExtensionFileUri1'], self::$aRessources['sMissingFile'], self::$aRessources['sCopiedExtensionFile1'] ),
			'(extensions) XML precompiled_stylesheet file older than last post setup generated file in /data/precompiled_styles' => $this->BuildProviderUseCaseArray(self::$aRessources['sPrecompiledInExtensionFileUri1'], self::$aRessources['sPostCompilation2'], self::$aRessources['sPostCompilation2'] ),
			'last post setup generated file in /data/precompiled_styles older than (extensions) XML precompiled_stylesheet file' => $this->BuildProviderUseCaseArray(self::$aRessources['sPrecompiledInExtensionFileUri1'], self::$aRessources['sPostCompilation1'], self::$aRessources['sCopiedExtensionFile1'] ),
			'(datamodels/N.x) XML precompiled_stylesheet file older than last post setup generated file in /data/precompiled_styles' => $this->BuildProviderUseCaseArray(self::$aRessources['sPrecompiledInDataModelXXFileUri1'], self::$aRessources['sPostCompilation2'], self::$aRessources['sPostCompilation2'] ),
			'(datamodels/N.x) last post setup generated file in /data/precompiled_styles older than (extensions) XML precompiled_stylesheet file' => $this->BuildProviderUseCaseArray(self::$aRessources['sPrecompiledInDataModelXXFileUri1'], self::$aRessources['sPostCompilation1'], self::$aRessources['sCopiedDataModelXXFile1'] ),
		];
	}

	private function BuildProviderUseCaseArray(string $sPrecompiledFileUri, string $sPostCompilationLatestPrecompiledFile, $sExpectedReturn, $bDisableThemePrecompilationViaConf = false) : array{
		return [
			"sTempTargetDir" => sys_get_temp_dir(),
			"sPrecompiledFileUri" => $sPrecompiledFileUri,
			"sPostCompilationLatestPrecompiledFile" => $sPostCompilationLatestPrecompiledFile,
			"sThemeDir" => "test",
			"sExpectedReturn" => $sExpectedReturn,
			"bDisableThemePrecompilationViaConf" => $bDisableThemePrecompilationViaConf
		];
	}

	public function testCompileThemes(){
		$sFullmoonThemeCompiledFolder = $this->sTmpDir.DIRECTORY_SEPARATOR.'branding'.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.'fullmoon'.DIRECTORY_SEPARATOR;

		$sXmlDataCustoFilePath = realpath(__DIR__.'/ressources/datamodels/datamodel-branding.xml');
		$oDom = new MFDocument();
		$oDom->load($sXmlDataCustoFilePath);

		/** @var \MFElement $oBrandingNode */
		$oBrandingNode = $oDom->GetNodes('branding')->item(0);

		$this->RecurseMkdir($sFullmoonThemeCompiledFolder);
		file_put_contents($sFullmoonThemeCompiledFolder.'main.css', "");

		$aImportsPaths = array(
			APPROOT.'css/',
			APPROOT.'css/backoffice/main.scss',
			$this->sTmpDir.'//',
		);


		$aThemeParameters = [
			'variables' => [
				'ibo-page-banner--background-color' => '$ibo-color-red-600',
				'ibo-page-banner--text-color' => '$ibo-color-red-100',
				'ibo-page-banner--text-content' => '"THIS IS A TEST INSTANCE"',
			],
			'variable_imports' => ['ok-because-xsi-type-variables' => 'ok-because-xsi-type-variables.scss'],
			'utility_imports' => ['ok-because-xsi-type-utilities' => 'ok-because-xsi-type-utilities.scss'],
			'stylesheets' => [
				"fullmoon" => '../css/backoffice/main.scss',
				"environment-banner" => '../css/backoffice/themes/page-banner.scss',
			],
		];

		$oThemeHandlerService = $this->createMock(\ThemeHandlerService::class);
		$oThemeHandlerService->expects($this->exactly(1))
			->method("CompileTheme")
			->with("fullmoon", true, $this->oMFCompiler->GetCompilationTimeStamp(), $aThemeParameters, $aImportsPaths, $this->sTmpDir . '/');


		//CompileTheme($sThemeId, $bSetup = false, $sSetupCompilationTimestamp="", $aThemeParameters = null, $aImportsPaths = null, $sWorkingPath = null)
		MFCompiler::SetThemeHandlerService($oThemeHandlerService);
		$this->oMFCompiler->CompileThemes($oBrandingNode, $this->sTmpDir);
	}
}
