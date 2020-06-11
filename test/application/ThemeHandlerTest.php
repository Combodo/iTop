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
	const PATTERN = '|\\\/var[^"]+testimages|';
	
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

		$this->recurseMkdir($this->tmpDir."/branding/themes/basque-red");
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
			if (!$this->recurseMkdir($sThemeFolderPath))
			{
				$this->assertTrue(false, "Cannot create directory $sThemeFolderPath");
			}

			$aIncludedImages=ThemeHandler::GetIncludedImages($aThemeParameters['variables'], $aStylesheetFiles, $sThemeFolderPath);
			$compiled_json_sig = ThemeHandler::ComputeSignature($aThemeParameters, $aImportsPaths, $aIncludedImages);
			echo "  current signature: $compiled_json_sig\n";
			rmdir($sThemeFolderPath);
			$this->assertEquals($precompiledSig, $compiled_json_sig, "Precompiled signature does not match currently compiled one on theme '".$sThemeId."' (cf precompiledsheet $sPrecompiledStylesheet / datamodel $xmlDataCusto)");
		}
	}

	function recurseMkdir($dir)
	{
		if (is_dir($dir))
		{
			return true;
		}

		$sParentDir = dirname($dir);
		if (!$this->recurseMkdir($sParentDir))
		{
			return false;
		}

		return @mkdir($dir);
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
{"variables":"37c31105548fce44fecca5cb34e455c9","stylesheets":{"css-variables":"1d4b4ae2a6fba3db101f8dd1cecab082","jqueryui":"78cfafc3524dac98e61fc2460918d4e5","main":"52d8a7c5530ceb3a4d777364fa4e1eea"},"imports":[],"images":[]}
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
	 * @return array
	 */
	public function CompileThemesProvider()
	{
		$sModifiedVariableThemeParameterJson='{"variables":{"brand-primary1":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports":{"css-variables":"..\/css\/css-variables.scss"},"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/jqueryui.scss","main":"..\/css\/light-grey.scss"}}';
		$sInitialThemeParamJson='{"variables":{"brand-primary":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports":{"css-variables":"..\/css\/css-variables.scss"},"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/jqueryui.scss","main":"..\/css\/light-grey.scss"}}';
		$sImportFilePath = '/branding/css/css-variables.scss';
		$sVarChangedMainCssPath="test/application/theme-handler/expected/themes/basque-red/main_varchanged.css";
		$sStylesheetMainCssPath="test/application/theme-handler/expected/themes/basque-red/main_stylesheet.css";
		$sImageMainCssPath="test/application/theme-handler/expected/themes/basque-red/main_imagemodified.css";
		$sImportModifiedMainCssPath="test/application/theme-handler/expected/themes/basque-red/main_importmodified.css";
		$sStylesheetFilePath = '/branding/css/light-grey.scss';
		$sImageFilePath = 'test/application/theme-handler/copied/testimages/images/green-header.gif';
		return array(
			"setup context: variables list modified without any file touched" => array($sModifiedVariableThemeParameterJson, 1,false,false,false,$sImportFilePath, $sVarChangedMainCssPath),
			"setup context: variables list modified with files touched" => array($sModifiedVariableThemeParameterJson, 1,false,true,false,$sImportFilePath, $sVarChangedMainCssPath, false),
			"itop page/theme loading; variables list modified without any file touched" => array($sModifiedVariableThemeParameterJson, 0,false,false,false,$sImportFilePath, $sVarChangedMainCssPath, false),
			//imports
			"import file missing" => array($sInitialThemeParamJson, 0, true, false, false, $sImportFilePath),
			"import file touched" => array($sInitialThemeParamJson, 0, false, true, false, $sImportFilePath),
			"import file modified" => array($sInitialThemeParamJson, 1, false, false, true, $sImportFilePath, $sImportModifiedMainCssPath),
			//stylesheets
			"stylesheets file missing" => array($sInitialThemeParamJson, 0, true, false, false, $sStylesheetFilePath),
			"stylesheets file touched" => array($sInitialThemeParamJson, 0, false, true, false, $sStylesheetFilePath),
			"stylesheets file modified" => array($sInitialThemeParamJson, 1, false, false, true, $sStylesheetFilePath, $sStylesheetMainCssPath),
			//images
			"image file missing" => array($sInitialThemeParamJson, 0, true, false, false, $sImageFilePath),
			"image file touched" => array($sInitialThemeParamJson, 0, false, true, false, $sImageFilePath),
			"image file modified" => array($sInitialThemeParamJson, 1, false, false, true, $sImageFilePath, $sImageMainCssPath),
		);
	}


	/**
	 * @param $ThemeParametersJson
	 * @param int $iCompileCSSFromSASSCount
	 * @param boolean $bMissingFile
	 * @param boolean $bFilesTouchedRecently
	 * @param boolean $bFileMd5sumModified
	 * @param null $sFileToTest
	 * @param null $sExpectedMainCssPath
	 * @param bool $bSetup
	 *
	 * @throws \CoreException
	 * @dataProvider CompileThemesProvider
	 */
	public function testCompileThemes($ThemeParametersJson, $iCompileCSSFromSASSCount, $bMissingFile=false, $bFilesTouchedRecently=false, $bFileMd5sumModified=false, $sFileToTest=null, $sExpectedMainCssPath=null, $bSetup=true)
	{
		$sAfterReplacementCssVariableMd5sum='';
		if (is_file($this->tmpDir.'/'.$sFileToTest))
		{
			$sFileToTest=$this->tmpDir.'/'.$sFileToTest;
		}
		else
		{
			$sFileToTest=APPROOT.'/'.$sFileToTest;
		}

		//copy images in test dir
		$sAbsoluteImagePath = APPROOT .'test/application/theme-handler/copied/testimages/';
		$this->recurseMkdir($sAbsoluteImagePath);
		$aDirsToCleanup[] = $sAbsoluteImagePath;
		$this->recurse_copy(APPROOT .'test/application/theme-handler/expected/testimages/', $sAbsoluteImagePath);

		//change approot-relative in css-variable to use absolute path
		$sCssVarPath = $this->tmpDir."/branding/css/css-variables.scss";
		$sBeforeReplacementCssVariableMd5sum = md5_file($sCssVarPath);
		echo 'BEFORE :' . $sBeforeReplacementCssVariableMd5sum  .' ' . $sCssVarPath . ' ';
		$sCssVariableContent = file_get_contents($sCssVarPath);
		$sLine = '$approot-relative: "'.$sAbsoluteImagePath.'" !default;';
		$sCssVariableContent=preg_replace("/\\\$approot-relative: \"(.*)\"/", $sLine, $sCssVariableContent);
		file_put_contents($sCssVarPath, $sCssVariableContent);
		if ($bMissingFile)
		{
			$sAfterReplacementCssVariableMd5sum = $sBeforeReplacementCssVariableMd5sum;
			unlink($sFileToTest);
		}

		if (is_file($sCssVarPath))
		{
			$sAfterReplacementCssVariableMd5sum = md5_file($sCssVarPath);
		}

		//change cssvar md5sum + image absolute paths
		$sMainCssContent = file_get_contents(APPROOT."test/application/theme-handler/expected/themes/basque-red/main_testcompilethemes.css");
		$sMainCssContent = preg_replace('/MD5SUM/', $sAfterReplacementCssVariableMd5sum, $sMainCssContent);
		$sReplacement = rtrim($sAbsoluteImagePath, '/');
		$sReplacement=preg_replace('|\/|', '\/', $sReplacement);
		$sMainCssContent = preg_replace(static::PATTERN,  $sReplacement, $sMainCssContent);
		$cssPath = $this->tmpDir . '/branding/themes/basque-red/main.css';
		echo 'PUT md5sum: '.$sAfterReplacementCssVariableMd5sum.' in '.$cssPath.' ';
		file_put_contents($cssPath, $sMainCssContent);

		//should be after main.css modification to make sure precompilation check will be performed
		if ($bFilesTouchedRecently)
		{
			sleep(1);
			touch($sFileToTest);
		}

		//same: it should be after main.css modification
		if ($bFileMd5sumModified)
		{
			$sMd5sum = md5_file($sFileToTest);
			echo ' BEFORE touch: ' . $sMd5sum  .' ' . $sFileToTest;
			sleep(1);
			file_put_contents($sFileToTest, "###\n".file_get_contents($sFileToTest));

			$sMd5sum = md5_file($sFileToTest);
			echo ' AFTER touch: ' . $sMd5sum  .' ' . $sFileToTest;
		}

		if (is_file($sCssVarPath))
		{
			$sAfterReplacementCssVariableMd5sum = md5_file($sCssVarPath);
		}

		$this->compileCSSServiceMock->expects($this->exactly($iCompileCSSFromSASSCount))
			->method("CompileCSSFromSASS")
			->willReturn("====CSSCOMPILEDCONTENT====");

		$aThemeParameters = json_decode($ThemeParametersJson, true);
		ThemeHandler::CompileTheme('basque-red', $bSetup, $aThemeParameters, array($this->tmpDir.'/branding/themes/'), $this->tmpDir);

		if ($iCompileCSSFromSASSCount==1)
		{
			$sExpectedMainCssFile = APPROOT.$sExpectedMainCssPath;
			if (!is_file($sExpectedMainCssFile))
			{
				$this->assertTrue(false, "Cannot find expected main css file $sExpectedMainCssFile");
			}

			$aPatterns = array(static::PATTERN, '/'.$sBeforeReplacementCssVariableMd5sum.'/');
			$aPatterns[] = "/8100523d2e76a70266f3e7110e2fe5fb/";
			$aReplacements = array($sReplacement, $sAfterReplacementCssVariableMd5sum);
			$aReplacements[] = md5(json_encode($aThemeParameters['variables']));
			var_dump($aReplacements);
			$this->DoInnerJsonValidation($sExpectedMainCssFile, $cssPath, $aPatterns, $aReplacements);
		}
	}

	public function DoInnerJsonValidation($sExpectedCssFile, $sActualCssFile, $aPatterns, $aReplacements)
	{
		$sActualContent = file_get_contents($sActualCssFile);

		//replace absolute path to fix it in any envt
		$sExpectedContent = preg_replace($aPatterns,  $aReplacements, file_get_contents($sExpectedCssFile));

		//echo($sExpectedContent);
		if ($sExpectedContent != $sActualContent)
		{
			//try to have inner json diff failure
			/** @var array $aExpectedJson */
			//replace absolute path to fix it in any envt
			$sExpectedJson = preg_replace($aPatterns,  $aReplacements, ThemeHandler::GetSignature($sExpectedCssFile));
			$aExpectedJson = json_decode($sExpectedJson, true);
			/** @var array $aActualJson */
			$aActualJson = json_decode(ThemeHandler::GetSignature($sActualCssFile), true);
			$this->assertEquals($aExpectedJson, $aActualJson, "CSS file dont match ($sExpectedCssFile / $sActualCssFile)");
		}

		$this->assertTrue(true);
	}

	/**
	 * @param $sScssFile
	 *
	 * @dataProvider GetAllUrlFromScssProvider
	 */
	public function testGetAllUrlFromScss($sScssFile)
	{
		$aIncludedUrls = ThemeHandler::GetAllUrlFromScss(array('attr' => "123"),APPROOT.$sScssFile);
		$this->assertEquals(array('approot-relative', 'version', 'version1'), array_values($aIncludedUrls['aMissingVariables']));
		$this->assertEquals(array("attr"=>"123"),
			$aIncludedUrls['aFoundVariables']);
		$aExpectedCompletedUrls = array(
			'css/ui-lightness/images/tutu.jpg',
			"css/ui-lightness/images/tata.jpeg",
			"css/ui-lightness/images/tete.jpeg?g=123"
		);
		$aExpectedToCompleteUrls = array(
			'\'abc/\'+ $approot-relative + "css/ui-lightness/images/toutou.png?v=" + $version',
			"\$approot-relative + \"css/ui-lightness/images/toto.png?v=\" + \$version",
			'$approot-relative + \'css/ui-lightness/images/titi.gif?v=\' + $version1',
		'"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7?v=" + $version',
	);

		$aIncludedUrls['aCompleteUrls'];
		$this->assertEquals($aExpectedCompletedUrls, array_values($aIncludedUrls['aCompleteUrls']));
		$this->assertEquals($aExpectedToCompleteUrls, array_values($aIncludedUrls['aToCompleteUrls']));
	}

	/**
	 * @return array
	 */
	public function GetAllUrlFromScssProvider()
	{
		return array('test-getimages.scss' => array('test/application/theme-handler/getimages/test-getimages.scss'));
	}

	public function testFindMissingVariables()
	{
		$sContent = <<< 'SCSS'
$approot-relative: "../../../../../" !default; // relative to env-***/branding/themes/***/main.css
$approot-relative2: "../../" !default; // relative to env-***/branding/themes/***/main.css
$gray-base:              #000 !default;
$gray-darker:            lighten($gray-base, 13.5%) !default; // #222
$brand-primary: 	$combodo-orange !default;
$brand-primary-lightest:	lighten($brand-primary, 15%) !default;
$content-color: #eeeeee !default; 
$default-font-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif !default;
$icons-filter: hue-rotate(0deg) !default;
$toto : titi;
SCSS;
		$aMissingVariables = array('gabu', 'toto', 'approot-relative', 'approot-relative2', 'gray-base', 'gray-darker', 'brand-primary', 'brand-primary-lightest', 'content-color', 'default-font-family', 'icons-filter');
		list($aMissingVariables, $aFoundVariables) = ThemeHandler::FindMissingVariables(array('gabu' => 'zomeu'), $aMissingVariables, array("a" => "b"), $sContent);
		$aExpectedFoundVariables = array(
			'gabu' => 'zomeu',
			'toto' => 'titi',
		    'approot-relative' => '../../../../../',
		    'approot-relative2' => '../../',
		    'gray-base' => '#000',
		    'a' => 'b',
		    'content-color' => '#eeeeee',
		    'default-font-family' => 'Trebuchet MS,Tahoma,Verdana,Arial,sans-serif',
		    'icons-filter' => 'hue-rotate(0deg)',
			'toto' => 'titi',
		);
		$this->assertEquals($aExpectedFoundVariables, $aFoundVariables);
		$this->assertEquals(array('gray-darker', 'brand-primary', 'brand-primary-lightest'), $aMissingVariables);
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