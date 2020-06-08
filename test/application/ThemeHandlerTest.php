<?php

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @covers utils
 */
class ThemeHandlerTest extends ItopTestCase
{
	private $compileCSSServiceMock;
	private $cssPath;
	private $jsonThemeParamFile;
	private $tmpDir;
	private $aDirsToCleanup=array();

	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/themehandler.class.inc.php');
		require_once(APPROOT.'setup/modelfactory.class.inc.php');

		$this->compileCSSServiceMock = $this->createMock('CompileCSSService');
		ThemeHandler::mockCompileCSSService($this->compileCSSServiceMock);

		$this->tmpDir=$this->tmpdir();
		$aDirsToCleanup[] = $this->tmpDir;

		if (!is_dir($this->tmpDir ."/branding"))
		{
			@mkdir($this->tmpDir."/branding");
		}
		@mkdir($this->tmpDir."/branding/themes/");
		@mkdir($this->tmpDir."/branding/themes/basque-red");
		$this->cssPath = $this->tmpDir . '/branding/themes/basque-red/main.css';
		$this->jsonThemeParamFile = $this->tmpDir . '/branding/themes/basque-red/theme-parameters.json';
		$this->recurse_copy(APPROOT."/test/application/theme-handler/expected/css", $this->tmpDir."/branding/css");
	}

	public function tearDown()
	{
		parent::tearDown();
		foreach ($this->aDirsToCleanup as $dir)
		{
			$this->rrmdir($dir);
		}
	}

	function rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($dir."/".$object))
						$this->rrmdir($dir."/".$object);
					else
						unlink($dir."/".$object);
				}
			}
			rmdir($dir);
		}
	}

	function tmpdir() {
		$tmpfile=tempnam(sys_get_temp_dir(),'');
		if (file_exists($tmpfile))
		{
			unlink($tmpfile);
		}
		mkdir($tmpfile);
		if (is_dir($tmpfile))
		{
			return $tmpfile;
		}

		return sys_get_temp_dir();
	}

	public function recurse_copy($src,$dst) {
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					$this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}

	/**
	 * Test used to be notified by CI when precompiled styles are not up to date anymore in code repository.
	 * @param $xmlDataCusto
	 * @dataProvider providePrecompiledStyleSheets
	 * @throws \Exception
	 */
	public function testValidatePrecompiledStyles($xmlDataCusto)
	{
		echo "=== datamodel custo: $xmlDataCusto\n";
		$oDom = new MFDocument();
		$oDom->load($xmlDataCusto);
		/**DOMNodeList **/$oThemeNodes=$oDom->GetNodes("/itop_design/branding/themes/theme");
		$this->assertNotNull($oThemeNodes);

		// Parsing themes from DM
		foreach($oThemeNodes as $oTheme)
		{
			$sPrecompiledStylesheet = $oTheme->GetChildText('precompiled_stylesheet', '');
			if (empty($sPrecompiledStylesheet))
			{
				continue;
			}

			$sThemeId = $oTheme->getAttribute('id');

			echo "===  theme: $sThemeId ===\n";
			$precompiledSig= ThemeHandler::GetSignature(dirname(__FILE__)."/../../datamodels/2.x/".$sPrecompiledStylesheet);
			echo "  precompiled signature: $precompiledSig\n";
			$this->assertFalse(empty($precompiledSig), "Signature in precompiled theme '".$sThemeId."' is not retrievable (cf precompiledsheet $sPrecompiledStylesheet / datamodel $xmlDataCusto)");

			$aThemeParameters = array(
				'variables' => array(),
				'imports' => array(),
				'stylesheets' => array(),
				'precompiled_stylesheet' => '',
			);

			$aThemeParameters['precompiled_stylesheet'] = $sPrecompiledStylesheet;
			/** @var \DOMNodeList $oVariables */
			$oVariables = $oTheme->GetNodes('variables/variable');
			foreach($oVariables as $oVariable)
			{
				$sVariableId = $oVariable->getAttribute('id');
				$aThemeParameters['variables'][$sVariableId] = $oVariable->GetText();
			}

			/** @var \DOMNodeList $oImports */
			$aStylesheetFiles = array();
			$aImportsPaths = array(APPROOT.'datamodels');
			$oImports = $oTheme->GetNodes('imports/import');
			foreach($oImports as $oImport)
			{
				$sImportId = $oImport->getAttribute('id');
				$aThemeParameters['imports'][$sImportId] = $oImport->GetText();
				$sFile = ThemeHandler::FindStylesheetFile($oImport->GetText(), $aImportsPaths);
				$aStylesheetFiles[] = $sFile;
			}

			/** @var \DOMNodeList $oStylesheets */
			$oStylesheets = $oTheme->GetNodes('stylesheets/stylesheet');
			foreach($oStylesheets as $oStylesheet)
			{
				$sStylesheetId = $oStylesheet->getAttribute('id');
				$aThemeParameters['stylesheets'][$sStylesheetId] = $oStylesheet->GetText();
				$sFile = ThemeHandler::FindStylesheetFile($oStylesheet->GetText(), $aImportsPaths);
				$aStylesheetFiles[] = $sFile;
			}
			$sThemeFolderPath = APPROOT.'env-production/branding/themes/'.$sThemeId.'/test';
			if (!is_dir($sThemeFolderPath))
			{
				mkdir($sThemeFolderPath);
			}

			$aIncludedImages=ThemeHandler::GetIncludedImages($aThemeParameters['variables'], $aStylesheetFiles, $sThemeFolderPath);
			$compiled_json_sig = ThemeHandler::ComputeSignature($aThemeParameters, $aImportsPaths, $aIncludedImages);
			echo "  current signature: $compiled_json_sig\n";
			rmdir($sThemeFolderPath);
			$this->assertEquals($precompiledSig, $compiled_json_sig, "Precompiled signature does not match currently compiled one on theme '".$sThemeId."' (cf precompiledsheet $sPrecompiledStylesheet / datamodel $xmlDataCusto)");
		}

	}

	public function providePrecompiledStyleSheets()
	{
		$datamodelfiles=glob(dirname(__FILE__)."/../../datamodels/2.x/**/datamodel*.xml");
		$test_set = array();

		foreach ($datamodelfiles as $datamodelfile)
		{
			if (is_file($datamodelfile) &&
				$datamodelfile=="/var/www/html/iTop/test/application/../../datamodels/2.x/itop-config-mgmt/datamodel.itop-config-mgmt.xml")
			{
				$content=file_get_contents($datamodelfile);
				if (strpos($content, "precompiled_stylesheet")!==false)
				{
					$test_set[$datamodelfile]=array($datamodelfile);
				}
			}
		}

		return $test_set;
	}

	public function testGetSignature()
	{
		$sig = ThemeHandler::GetSignature(APPROOT.'test/application/theme-handler/expected/themes/basque-red/main.css');
		$expect_sig=<<<JSON
{"variables":"37c31105548fce44fecca5cb34e455c9","stylesheets":{"css-variables":"934888ebb4991d4c76555be6b6d1d5cc","jqueryui":"78cfafc3524dac98e61fc2460918d4e5","main":"52d8a7c5530ceb3a4d777364fa4e1eea"},"imports":[],"images":[]}
JSON;

		$this->assertEquals($expect_sig,$sig);
	}

	public function testGetVarSignature()
	{
		$sig=<<<JSON
{"variables":"37c31105548fce44fecca5cb34e455c9","stylesheets":{"css-variables":"934888ebb4991d4c76555be6b6d1d5cc","jqueryui":"78cfafc3524dac98e61fc2460918d4e5","main":"52d8a7c5530ceb3a4d777364fa4e1eea"},"imports":[]}
JSON;
		$var_sig = ThemeHandler::GetVarSignature($sig);

		$this->assertEquals("37c31105548fce44fecca5cb34e455c9",$var_sig);
	}

	/**
	 * @param bool $readFromParamAttributeFromJson
	 *
	 * @throws \CoreException
	 * @dataProvider CompileThemesProviderWithoutCss
	 */
	public function testCompileThemeWithoutCssFile_FocusOnParamAttribute($readFromParamAttributeFromJson=false)
	{
		$expectJsonFilePath = APPROOT.'test/application/theme-handler/expected/themes/basque-red/theme-parameters.json';
		$expectedThemeParamJson = file_get_contents($expectJsonFilePath);
		$aThemeParameters = json_decode($expectedThemeParamJson, true);
		if (is_file($this->jsonThemeParamFile))
		{
			unlink($this->jsonThemeParamFile);
		}
		if (is_file($this->cssPath))
		{
			unlink($this->cssPath);
		}

		$this->compileCSSServiceMock->expects($this->exactly(1))
			->method("CompileCSSFromSASS")
			->willReturn("====CSSCOMPILEDCONTENT====");

		if($readFromParamAttributeFromJson)
		{
			copy($expectJsonFilePath, $this->jsonThemeParamFile);
			ThemeHandler::CompileTheme('basque-red', true, null, array($this->tmpDir.'/branding/themes/'), $this->tmpDir);
		}
		else
		{
			ThemeHandler::CompileTheme('basque-red', true, $aThemeParameters, array($this->tmpDir.'/branding/themes/'), $this->tmpDir);
		}
		$this->assertTrue(is_file($this->cssPath));
		$this->assertEquals($expectedThemeParamJson, file_get_contents($this->jsonThemeParamFile));
		$this->assertEquals(file_get_contents(APPROOT . 'test/application/theme-handler/expected/themes/basque-red/main.css'), file_get_contents($this->cssPath));
	}

	public function CompileThemesProviderWithoutCss()
	{
		return array(
			"pass ParamAttributes and Save them in Json" => array(false),
			"use them from saved json" => array(true)
		);
	}

	/**
	 * @param $ThemeParametersJson
	 *
	 * @param int $CompileCount
	 *
	 * @throws \CoreException
	 * @dataProvider CompileThemesProviderEmptyArray
	 */
	public function testCompileThemesEmptyArray($ThemeParametersJson, $CompileCount=0)
	{
		$cssPath = $this->tmpDir . '/branding/themes/basque-red/main.css';
		copy(APPROOT . 'test/application/theme-handler/expected/themes/basque-red/main.css', $cssPath);

		$this->compileCSSServiceMock->expects($this->exactly($CompileCount))
			->method("CompileCSSFromSASS")
			->willReturn("====CSSCOMPILEDCONTENT====");

		ThemeHandler::CompileTheme('basque-red', true, json_decode($ThemeParametersJson, true), array($this->tmpDir.'/branding/themes/'), $this->tmpDir);
	}

	public function CompileThemesProviderEmptyArray()
	{
		$emptyImports = '{"variables":{"brand-primary":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports":[],"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/jqueryui.scss","main":"..\/css\/light-grey.scss"}}';
		$emptyStyleSheets='{"variables":{"brand-primary":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports":{"css-variables":"..\/css\/css-variables.scss"},"stylesheets":[]}';
		$emptyVars='{"variables":[],"imports":{"css-variables":"..\/css\/css-variables.scss"},"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/jqueryui.scss","main":"..\/css\/light-grey.scss"}}';
		return array(
			"empty imports" => array($emptyImports),
			"empty styles" => array($emptyStyleSheets),
			"empty vars" => array($emptyVars, 1),
		);
	}


	/**
	 * @param $ThemeParametersJson
	 * @param $CompileCSSFromSASSCount
	 * @param int $missingFile
	 * @param int $filesTouchedRecently
	 * @param int $fileMd5sumModified
	 * @param null $fileToTest
	 *
	 * @param null $expected_maincss_path
	 *
	 * @throws \CoreException
	 * @dataProvider CompileThemesProvider
	 */
	public function testCompileThemes($ThemeParametersJson, $CompileCSSFromSASSCount, $missingFile=0, $filesTouchedRecently=0, $fileMd5sumModified=0, $fileToTest=null, $expected_maincss_path=null, $bSetup=true)
	{
		if (is_file($this->tmpDir.'/'.$fileToTest))
		{
			$fileToTest=$this->tmpDir.'/'.$fileToTest;
		}
		else
		{
			$fileToTest=APPROOT.'/'.$fileToTest;
		}

		$cssPath = $this->tmpDir . '/branding/themes/basque-red/main.css';
		copy(APPROOT."test/application/theme-handler/expected/themes/basque-red/main_testcompilethemes.css", $cssPath);

		$sAbsoluteImagePath = APPROOT .'test/application/theme-handler/copied/testimages/';
		@mkdir(APPROOT .'test/application/theme-handler/copied/');
		@mkdir($sAbsoluteImagePath);
		$aDirsToCleanup[] = $sAbsoluteImagePath;
		$this->recurse_copy(APPROOT .'test/application/theme-handler/expected/testimages/', $sAbsoluteImagePath);

		//change approot-relative in css-variable to use absolute path
		$sCssVarPath = $this->tmpDir."/branding/css/css-variables.scss";
		$sCssVariableContent = file_get_contents($sCssVarPath);
		$sLine = '$approot-relative: "' . $sAbsoluteImagePath . '" !default;';
		
		$sCssVariableContent=preg_replace("/\\\$approot-relative: \"(.*)\"/", $sLine, $sCssVariableContent);
		file_put_contents($sCssVarPath, $sCssVariableContent);

		if ($missingFile==1)
		{
			unlink($fileToTest);
		}

		if ($filesTouchedRecently==1)
		{
			sleep(1);
			touch($fileToTest);
		}

		if ($fileMd5sumModified==1)
		{
			sleep(1);
			file_put_contents($fileToTest, "###\n".file_get_contents($fileToTest));
		}

		$this->compileCSSServiceMock->expects($this->exactly($CompileCSSFromSASSCount))
			->method("CompileCSSFromSASS")
			->willReturn("====CSSCOMPILEDCONTENT====");

		ThemeHandler::CompileTheme('basque-red', $bSetup, json_decode($ThemeParametersJson, true), array($this->tmpDir.'/branding/themes/'), $this->tmpDir);

		if ($CompileCSSFromSASSCount==1)
		{
			$sExpectedMainCssFile = APPROOT.$expected_maincss_path;
			if (!is_file($sExpectedMainCssFile))
			{
				$this->assertTrue(false, "Cannot find expected main css file $sExpectedMainCssFile");
			}
			$this->assertEquals(file_get_contents($sExpectedMainCssFile), file_get_contents($cssPath));
		}
	}

	/**
	 * @return array
	 */
	public function CompileThemesProvider()
	{
		$modifiedVariableThemeParameterJson='{"variables":{"brand-primary1":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports":{"css-variables":"..\/css\/css-variables.scss"},"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/jqueryui.scss","main":"..\/css\/light-grey.scss"}}';
		$initialThemeParamJson='{"variables":{"brand-primary":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports":{"css-variables":"..\/css\/css-variables.scss"},"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/jqueryui.scss","main":"..\/css\/light-grey.scss"}}';
		$import_file_path = '/branding/css/css-variables.scss';
		$varchanged_maincss="test/application/theme-handler/expected/themes/basque-red/main_varchanged.css";
		$stylesheet_maincss="test/application/theme-handler/expected/themes/basque-red/main_stylesheet.css";
		$image_maincss="test/application/theme-handler/expected/themes/basque-red/main_imagemodified.css";
		$importmodified_maincss="test/application/theme-handler/expected/themes/basque-red/main_importmodified.css";
		$stylesheet_file_path = '/branding/css/light-grey.scss';
		$image_file_path = 'test/application/theme-handler/copied/testimages/images/green-header.gif';
		return array(
			"setup context: variables list modified without any file touched" => array($modifiedVariableThemeParameterJson, 1,0,0,0,$import_file_path, $varchanged_maincss),
			"setup context: variables list modified with files touched" => array($modifiedVariableThemeParameterJson, 1,0,1,0,$import_file_path, $varchanged_maincss, false),
			"itop page/theme loading; variables list modified without any file touched" => array($modifiedVariableThemeParameterJson, 0,0,0,0,$import_file_path, $varchanged_maincss, false),
			//imports
			"import file missing" => array($initialThemeParamJson, 0, 1, 0, 0, $import_file_path),
			"import file touched" => array($initialThemeParamJson, 0, 0, 1, 0, $import_file_path),
			"import file modified" => array($initialThemeParamJson, 1, 0, 0, 1, $import_file_path, $importmodified_maincss),
			//stylesheets
			"stylesheets file missing" => array($initialThemeParamJson, 0, 1, 0, 0, $stylesheet_file_path),
			"stylesheets file touched" => array($initialThemeParamJson, 0, 0, 1, 0, $stylesheet_file_path),
			"stylesheets file modified" => array($initialThemeParamJson, 1, 0, 0, 1, $stylesheet_file_path, $stylesheet_maincss),
			//images
			"image file missing" => array($initialThemeParamJson, 0, 1, 0, 0, $image_file_path),
			"image file touched" => array($initialThemeParamJson, 0, 0, 1, 0, $image_file_path),
			"image file modified" => array($initialThemeParamJson, 1, 0, 0, 1, $image_file_path, $image_maincss),
		);
	}

	/**
	 * @param $sScssFile
	 *
	 * @dataProvider GetAllUrlFromScssProvider
	 */
	public function testGetAllUrlFromScss($sScssFile)
	{
		$aIncludedUrls = ThemeHandler::GetAllUrlFromScss(array('attr' => "123"),APPROOT.$sScssFile);
		$this->assertEquals(array('version1'), array_values($aIncludedUrls['aMissingVariables']));
		$this->assertEquals(array("approot-relative" => "../../../../../", "version" => "aaa", "attr"=>"123"),
			$aIncludedUrls['aFoundVariables']);
		$expected_array = array(
			'css/ui-lightness/images/tutu.jpg',
			"css/ui-lightness/images/tata.jpeg",
			'abc/../../../../../css/ui-lightness/images/toutou.png?v=aaa',
			"../../../../../css/ui-lightness/images/toto.png?v=aaa",
			"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7?v=aaa",
			"css/ui-lightness/images/tete.jpeg?g=123"
		);
		$aIncludedUrls['aCompleteUrls'];
		$this->assertEquals($expected_array, array_values($aIncludedUrls['aCompleteUrls']));
		$this->assertEquals(array('$approot-relative + \'css/ui-lightness/images/titi.gif?v=\' + $version1'), array_values($aIncludedUrls['aToCompleteUrls']));
	}

	/**
	 * @return array
	 */
	public function GetAllUrlFromScssProvider()
	{
		return array('test-getimages.scss' => array('test/application/theme-handler/getimages/test-getimages.scss'));
	}

	/**
	 * @param $sUrlTemplate
	 * @param $aFoundVariables
	 * @param $sExpectedUrl
	 *
	 * @dataProvider ResolveUrlProvider
	 */
	public function testResolveUrl($sUrlTemplate, $aFoundVariables, $sExpectedUrl)
	{
		$this->assertEquals($sExpectedUrl, ThemeHandler::ResolveUrl($sUrlTemplate, $aFoundVariables));
	}

	public function ResolveUrlProvider()
	{
		return array(
			'XXX + $key1 UNresolved' => array("abc/'+ \$key1", array('key'=>'123'), false),
			'$key1 + XXX UNresolved' => array("\$key1 + abs", array('key'=>'123'), false),
			'XXX + $key UNresolved' => array("abc/'+ \$unknownkey", array('key'=>'123'), false),
			'XXX + $key resolved' => array("abc/'+ \$key", array('key'=>'123'), "abc/123"),
			'XXX + $key1 resolved' => array("abc/'+ \$key1", array('key1'=>'123'), "abc/123"),
			'$key + XXX resolved' => array("\$key + \"/abc", array('key'=>'123'), "123/abc"),
			'XXX + $key + YYY resolved' => array("abc/'+ \$key + '/def", array('key'=>'123'), "abc/123/def"),
		);
	}

	public function testGetIncludedImages()
	{
		$aStylesheetFile=glob($this->tmpDir."/branding/css/*.scss");
		$aStylesheetFile[]=$this->tmpDir."/branding/css/ui-lightness/jqueryui.scss";
		$expectJsonFilePath = APPROOT.'test/application/theme-handler/expected/themes/basque-red/theme-parameters.json';
		$expectedThemeParamJson = file_get_contents($expectJsonFilePath);
		$aThemeParametersVariables = json_decode($expectedThemeParamJson, true);
		$aIncludedImages = ThemeHandler::GetIncludedImages($aThemeParametersVariables['variables'], $aStylesheetFile, "RELATIVEPATH");

		$aExpectedImages = json_decode(file_get_contents(APPROOT.'test/application/theme-handler/getimages/expected-getimages.json'), true);
		$this->assertEquals($aExpectedImages, $aIncludedImages);
	}
}