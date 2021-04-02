<?php

use Combodo\iTop\Test\UnitTest\ItopTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 * @covers ThemeHandler
 */
class ThemeHandlerTest extends ItopTestCase
{
	const PATTERN = '|\\\/var[^"]+testimages|';
	
	private $oCompileCSSServiceMock;
	private $sCssPath;
	private $sJsonThemeParamFile;
	private $sTmpDir;
	private $aDirsToCleanup= [];

	public function setUp()
	{
		parent::setUp();
		require_once(APPROOT.'application/themehandler.class.inc.php');
		require_once(APPROOT.'setup/modelfactory.class.inc.php');

		$this->oCompileCSSServiceMock = $this->createMock('CompileCSSService');
		ThemeHandler::mockCompileCSSService($this->oCompileCSSServiceMock);

		$this->sTmpDir = $this->tmpdir();
		$this->aDirsToCleanup[] = $this->sTmpDir;

		$this->recurseMkdir($this->sTmpDir."/branding/themes/basque-red");
		$this->sCssPath = $this->sTmpDir.'/branding/themes/basque-red/main.css';
		$this->sJsonThemeParamFile = $this->sTmpDir.'/branding/themes/basque-red/theme-parameters.json';
		$this->recurse_copy(APPROOT."/test/application/theme-handler/expected/css", $this->sTmpDir."/branding/css");
	}

	public function tearDown()
	{
		parent::tearDown();

		foreach ($this->aDirsToCleanup as $sDir)
		{
			echo $sDir;
			$this->rrmdir($sDir);
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
		$sTmpfile=tempnam(sys_get_temp_dir(),'');
		if (file_exists($sTmpfile))
		{
			unlink($sTmpfile);
		}
		mkdir($sTmpfile);
		if (is_dir($sTmpfile))
		{
			return $sTmpfile;
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
	 *
	 * @param $xmlDataCusto
	 * @param $sPrecompiledStylesheet
	 * @param $oTheme
	 *
	 * @group beforeSetup
	 *
	 * @throws \Exception
	 */
	public function testValidatePrecompiledStyles()
	{
		$aErrors = [];
		$aDataModelFiles=glob(dirname(__FILE__)."/../../datamodels/2.x/**/datamodel*.xml");
		$aImportsPaths = [APPROOT.'datamodels'];

		foreach ($aDataModelFiles as $sXmlDataCustoFilePath)
		{
			if (is_file($sXmlDataCustoFilePath))
			{
				$sXmlDataCustoFilePath = realpath($sXmlDataCustoFilePath);
				$sContent = file_get_contents($sXmlDataCustoFilePath);
				if (strpos($sContent, "precompiled_stylesheet") !== false)
				{
					$oDom = new MFDocument();
					$oDom->load($sXmlDataCustoFilePath);
					$oThemeNodes = $oDom->GetNodes("/itop_design/branding/themes/theme");

					// Parsing themes from DM
					foreach ($oThemeNodes as $oTheme)
					{
						/** @var \MFElement $oTheme */
						$sPrecompiledStylesheetUri = $oTheme->GetChildText('precompiled_stylesheet', '');
						if (empty($sPrecompiledStylesheetUri))
						{
							continue;
						}

						$sThemeId = $oTheme->getAttribute('id');

						//echo "===  theme: $sThemeId ===\n";
						$sPrecompiledFilePath = dirname(__FILE__)."/../../datamodels/2.x/".$sPrecompiledStylesheetUri;
						$sPreCompiledSig = ThemeHandler::GetSignature($sPrecompiledFilePath);
						if (empty(trim($sPreCompiledSig))){
							var_dump("$sThemeId: $sPrecompiledFilePath => " . realpath($sPrecompiledFilePath));
						}
						//echo "  precompiled signature: $sPreCompiledSig\n";

						if (empty($sPreCompiledSig))
						{
							$aErrors[] = "Signature in precompiled theme '".$sThemeId."' is not retrievable (cf precompiledsheet $sPrecompiledStylesheetUri / datamodel $sXmlDataCustoFilePath)";
							continue;
						}

						$aThemeParameters = [
							'variables' => [],
							'imports_utility' => [],
							'stylesheets' => [],
							'precompiled_stylesheet' => $sPrecompiledStylesheetUri,
						];

						/** @var \DOMNodeList $oVariables */
						$oVariables = $oTheme->GetNodes('variables/variable');
						foreach ($oVariables as $oVariable)
						{
							$sVariableId = $oVariable->getAttribute('id');
							$aThemeParameters['variables'][$sVariableId] = $oVariable->GetText();
						}

						/** @var \DOMNodeList $oImports */
						$oImports = $oTheme->GetNodes('imports/import');
						$oFindStylesheetObject = new FindStylesheetObject();

						foreach ($oImports as $oImport)
						{
							$sImportId = $oImport->getAttribute('id');
							$aThemeParameters['imports_utility'][$sImportId] = $oImport->GetText();
							ThemeHandler::FindStylesheetFile($oImport->GetText(), $aImportsPaths, $oFindStylesheetObject);
						}

						/** @var \DOMNodeList $oStylesheets */
						$oStylesheets = $oTheme->GetNodes('stylesheets/stylesheet');
						foreach ($oStylesheets as $oStylesheet)
						{
							$sStylesheetId = $oStylesheet->getAttribute('id');
							$aThemeParameters['stylesheets'][$sStylesheetId] = $oStylesheet->GetText();
							ThemeHandler::FindStylesheetFile($oStylesheet->GetText(), $aImportsPaths, $oFindStylesheetObject);
						}

						$aIncludedImages = ThemeHandler::GetIncludedImages($aThemeParameters['variables'], $oFindStylesheetObject->GetAllStylesheetPaths(), $sThemeId);
						$compiled_json_sig = ThemeHandler::ComputeSignature($aThemeParameters, $aImportsPaths, $aIncludedImages);
						//echo "  current signature: $compiled_json_sig\n";

						if ($sPreCompiledSig !== $compiled_json_sig)
						{
							$iLine = $oTheme->GetLineNo();
							$aErrors[] = "       $sPrecompiledStylesheetUri declared in $sXmlDataCustoFilePath:$iLine.";
							$this->assertEquals($sPreCompiledSig, $compiled_json_sig, "       $sPrecompiledStylesheetUri declared in $sXmlDataCustoFilePath:$iLine.");
							continue;
						}
					}
				}
			}
		}

		if (count($aErrors)!=0)
		{
			$sMsg = "Below precompiled files are not up to date. Please run a new setup and save your precompiled files again:\n";
			$sMsg .= implode("\n", $aErrors);
			$this->fail($sMsg);
		}
		else
		{
			$this->assertTrue(true);
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

	public function testGetSignatureWithFileWithoutSignature()
	{
		$sTmpFile = tempnam(sys_get_temp_dir(), "sig");
		file_put_contents($sTmpFile,"ffff");
		$this->assertEquals("",  ThemeHandler::GetSignature($sTmpFile));
	}

	public function testGetSignature()
	{
		$sSig = ThemeHandler::GetSignature(APPROOT.'test/application/theme-handler/expected/themes/basque-red/main.css');
		$sExpectedSig=<<<JSON
{"variables":"37c31105548fce44fecca5cb34e455c9","stylesheets":{"css-variables":"3c3f5adf98b9dbf893658314436c4b93","jqueryui":"78cfafc3524dac98e61fc2460918d4e5","main":"52d8a7c5530ceb3a4d777364fa4e1eea"},"imports":[],"images":{"css\/ui-lightness\/images\/ui-icons_222222_256x240.png":"3a3c5468f484f07ac4a320d9e22acb8c","css\/ui-lightness\/images\/ui-bg_diagonals-thick_20_666666_40x40.png":"4429d568c67d8dfeb9040273ea0fb8c4","css\/ui-lightness\/images\/ui-icons_E87C1E_256x240.png":"7003dd36cb2aa032c8ec871ce4d4e03d","css\/ui-lightness\/images\/ui-icons_1c94c4_256x240.png":"dbd693dc8e0ef04e90a2f7ac7b390086","css\/ui-lightness\/images\/ui-icons_F26522_256x240.png":"16278ec0c07270be571f4c2e97fcc10c","css\/ui-lightness\/images\/ui-bg_diagonals-thick_18_b81900_40x40.png":"e460a66d4b3e093fc651e62a236267cb","css\/ui-lightness\/images\/ui-icons_ffffff_256x240.png":"41612b0f4a034424f8321c9f824a94da","css\/ui-lightness\/images\/ui-icons_ffd27a_256x240.png":"dda1b6f694b0d196aefc66a1d6d758f6","images\/actions_right.png":"31c8906bd25d27b83a0a2466bf903462","images\/ac-background.gif":"76135f3697b41a15aed787cfd77776c7","images\/green-square.gif":"16ea9a497d72f5e66e4e8ea9ae08024e","images\/tv-item.gif":"719fe2d4566108e73162fb8868d3778c","images\/tv-collapsable.gif":"63a3351ea0d580797c9b8c386aa4f48b","images\/tv-expandable.gif":"a2d1af4128e4a798a7f3390b12a28574","images\/tv-item-last.gif":"2ae7e1d9972ce71e5caa65a086bc5b7e","images\/tv-collapsable-last.gif":"71acaa9d7c2616e9e8b7131a75ca65da","images\/tv-expandable-last.gif":"9d51036b3a8102742709da66789fd0f7","images\/red-header.gif":"c73b8765f0c8c3c183cb6a0c2bb0ec69","images\/green-header.gif":"0e22a09bb8051b2a274b3427ede62e82","images\/orange-header.gif":"ce1f93f0af64431771b4cbd6c99c567b","images\/calendar.png":"ab56e59af3c96ca661821257d376465e","images\/truncated.png":"c6f91108afe8159d417b4dc556cd3b2a","images\/plus.gif":"f00e1e6e1161f48608bb2bbc79b9948c","images\/minus.gif":"6d77c0c0c2f86b6995d1cdf78274eaab","images\/full-screen.png":"b541fadd3f1563856a4b44aeebd9d563","images\/indicator.gif":"03ce3dcc84af110e9da8699a841e5200","images\/delete.png":"93c047549c31a270a037840277cf59d3","images\/info-mini.png":"445c090ed777c5e6a08ac390fa896193","images\/ok.png":"f6973773335fd83d8d2875f9a3c925af","images\/error.png":"1af8a1041016f67669c5fd22dc88c82e","images\/eye-open-555.png":"9940f4e5b1248042c238e1924359fd5e","images\/eye-closed-555.png":"6ad3b0bae791bf61addc9d8ca80a642d","images\/eye-open-fff.png":"b7db2402d4d5c72314c25790a66150d4","images\/eye-closed-fff.png":"f9be7454dbb47b0e0bca3aa370ae7db5"}}
JSON;

		$this->assertEquals($sExpectedSig,  $sSig);
	}

	public function testGetVarSignature()
	{
		$sSignature=<<<JSON
{"variables":"37c31105548fce44fecca5cb34e455c9","stylesheets":{"css-variables":"934888ebb4991d4c76555be6b6d1d5cc","jqueryui":"78cfafc3524dac98e61fc2460918d4e5","main":"52d8a7c5530ceb3a4d777364fa4e1eea"},"imports":[]}
JSON;
		$var_sig = ThemeHandler::GetVarSignature($sSignature);

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
		$sExpectJsonFilePath = APPROOT.'test/application/theme-handler/expected/themes/basque-red/theme-parameters.json';
		$sExpectedThemeParamJson = file_get_contents($sExpectJsonFilePath);
		$aThemeParameters = json_decode($sExpectedThemeParamJson, true);
		if (is_file($this->sJsonThemeParamFile))
		{
			unlink($this->sJsonThemeParamFile);
		}
		if (is_file($this->sCssPath))
		{
			unlink($this->sCssPath);
		}

		$this->oCompileCSSServiceMock->expects($this->exactly(1))
			->method("CompileCSSFromSASS")
			->willReturn("====CSSCOMPILEDCONTENT====");

		if($readFromParamAttributeFromJson)
		{
			copy($sExpectJsonFilePath, $this->sJsonThemeParamFile);
			$this->assertTrue(ThemeHandler::CompileTheme('basque-red', true, "COMPILATIONTIMESTAMP", null, [$this->sTmpDir.'/branding/themes/'], $this->sTmpDir));
		}
		else
		{
			$this->assertTrue(ThemeHandler::CompileTheme('basque-red', true, "COMPILATIONTIMESTAMP", $aThemeParameters, [$this->sTmpDir.'/branding/themes/'], $this->sTmpDir));
		}
		$this->assertTrue(is_file($this->sCssPath));
		$this->assertEquals($sExpectedThemeParamJson, file_get_contents($this->sJsonThemeParamFile));
		$this->assertEquals(file_get_contents(APPROOT . 'test/application/theme-handler/expected/themes/basque-red/main.css'), file_get_contents($this->sCssPath));
	}

	public function CompileThemesProviderWithoutCss()
	{
		return [
			"pass ParamAttributes and Save them in Json" => [false],
			"use them from saved json" => [true]
		];
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
		$sCssPath = $this->sTmpDir . '/branding/themes/basque-red/main.css';
		copy(APPROOT . 'test/application/theme-handler/expected/themes/basque-red/main.css', $sCssPath);

		$this->oCompileCSSServiceMock->expects($this->exactly($CompileCount))
			->method("CompileCSSFromSASS")
			->willReturn("====CSSCOMPILEDCONTENT====");

		$this->assertEquals($CompileCount!=0,ThemeHandler::CompileTheme('basque-red', true, "COMPILATIONTIMESTAMP", json_decode($ThemeParametersJson, true), [$this->sTmpDir.'/branding/themes/'], $this->sTmpDir));
	}

	public function CompileThemesProviderEmptyArray()
	{
		$aEmptyImports = '{"variables":{"brand-primary":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports_utility":[],"imports_variable":[],"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/DO_NOT_CHANGE.jqueryui.scss","main":"..\/css\/DO_NOT_CHANGE.light-grey.scss"}}';
		$aEmptyStyleSheets='{"variables":{"brand-primary":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports_utility":{"css-variables":"..\/css\/DO_NOT_CHANGE.css-variables.scss"},"imports_variable":[],"stylesheets":[]}';
		$aEmptyVars='{"variables":[],"imports_utility":{"css-variables":"..\/css\/DO_NOT_CHANGE.css-variables.scss"},"imports_variable":[],"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/DO_NOT_CHANGE.jqueryui.scss","main":"..\/css\/DO_NOT_CHANGE.light-grey.scss"}}';
		return [
			"empty imports" => [$aEmptyImports],
			"empty styles" => [$aEmptyStyleSheets],
			"empty vars" => [$aEmptyVars, 1],
		];
	}

	/**
	 * @return array
	 */
	public function CompileThemesProvider()
	{
		$sModifiedVariableThemeParameterJson='{"variables":{"brand-primary1":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports_utility":{"css-variables":"..\/css\/DO_NOT_CHANGE.css-variables.scss"},"imports_variable":[],"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/DO_NOT_CHANGE.jqueryui.scss","main":"..\/css\/DO_NOT_CHANGE.light-grey.scss"}}';
		$sInitialThemeParamJson='{"variables":{"brand-primary":"#C53030","hover-background-color":"#F6F6F6","icons-filter":"grayscale(1)","search-form-container-bg-color":"#4A5568"},"imports_utility":{"css-variables":"..\/css\/DO_NOT_CHANGE.css-variables.scss"},"imports_variable":[],"stylesheets":{"jqueryui":"..\/css\/ui-lightness\/DO_NOT_CHANGE.jqueryui.scss","main":"..\/css\/DO_NOT_CHANGE.light-grey.scss"}}';
		$sImportFilePath = '/branding/css/DO_NOT_CHANGE.css-variables.scss';
		$sVarChangedMainCssPath="test/application/theme-handler/expected/themes/basque-red/main_varchanged.css";
		$sStylesheetMainCssPath="test/application/theme-handler/expected/themes/basque-red/main_stylesheet.css";
		$sImageMainCssPath="test/application/theme-handler/expected/themes/basque-red/main_imagemodified.css";
		$sImportModifiedMainCssPath="test/application/theme-handler/expected/themes/basque-red/main_importmodified.css";
		$sStylesheetFilePath = '/branding/css/DO_NOT_CHANGE.light-grey.scss';
		$sImageFilePath = 'test/application/theme-handler/copied/testimages/images/green-header.gif';
		return [
			"setup context: variables list modified without any file touched" => [$sModifiedVariableThemeParameterJson, 1,false,false,false,$sImportFilePath, $sVarChangedMainCssPath],
			"setup context: variables list modified with files touched" => [$sModifiedVariableThemeParameterJson, 1,false,true,false,$sImportFilePath, $sVarChangedMainCssPath, false],
			"itop page/theme loading; variables list modified without any file touched" => [$sModifiedVariableThemeParameterJson, 0,false,false,false,$sImportFilePath, $sVarChangedMainCssPath, false],
			//imports
			"import file missing" => [$sInitialThemeParamJson, 0, true, false, false, $sImportFilePath],
			"import file touched" => [$sInitialThemeParamJson, 0, false, true, false, $sImportFilePath],
			"import file modified" => [$sInitialThemeParamJson, 1, false, false, true, $sImportFilePath, $sImportModifiedMainCssPath],
			//stylesheets
			"stylesheets file missing" => [$sInitialThemeParamJson, 0, true, false, false, $sStylesheetFilePath],
			"stylesheets file touched" => [$sInitialThemeParamJson, 0, false, true, false, $sStylesheetFilePath],
			"stylesheets file modified" => [$sInitialThemeParamJson, 1, false, false, true, $sStylesheetFilePath, $sStylesheetMainCssPath],
			//images
			"image file missing" => [$sInitialThemeParamJson, 0, true, false, false, $sImageFilePath],
			"image file touched" => [$sInitialThemeParamJson, 0, false, true, false, $sImageFilePath],
			"image file modified" => [$sInitialThemeParamJson, 1, false, false, true, $sImageFilePath, $sImageMainCssPath],
		];
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
		if (is_file($this->sTmpDir.'/'.$sFileToTest))
		{
			$sFileToTest = $this->sTmpDir.'/'.$sFileToTest;
		} else {
			$sFileToTest = APPROOT.'/'.$sFileToTest;
		}

		//copy images in test dir
		$sAbsoluteImagePath = APPROOT.'test/application/theme-handler/copied/testimages/';
		$this->recurseMkdir($sAbsoluteImagePath);
		$this->aDirsToCleanup[] = dirname($sAbsoluteImagePath);
		$this->recurse_copy(APPROOT.'test/application/theme-handler/expected/testimages/', $sAbsoluteImagePath);

		//change approot-relative in css-variable to use absolute path
		$sCssVarPath = $this->sTmpDir."/branding/css/DO_NOT_CHANGE.css-variables.scss";
		$sBeforeReplacementCssVariableMd5sum = md5_file($sCssVarPath);
		echo 'BEFORE :'.$sBeforeReplacementCssVariableMd5sum.' '.$sCssVarPath.' ';
		$sCssVariableContent = file_get_contents($sCssVarPath);
		$sLine = '$approot-relative: "'.$sAbsoluteImagePath.'" !default;';
		$sCssVariableContent = preg_replace("/\\\$approot-relative: \"(.*)\"/", $sLine, $sCssVariableContent);
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
		$cssPath = $this->sTmpDir . '/branding/themes/basque-red/main.css';
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

		$this->oCompileCSSServiceMock->expects($this->exactly($iCompileCSSFromSASSCount))
			->method("CompileCSSFromSASS")
			->willReturn("====CSSCOMPILEDCONTENT====");

		$aThemeParameters = json_decode($ThemeParametersJson, true);
		$this->assertEquals($iCompileCSSFromSASSCount!=0,ThemeHandler::CompileTheme('basque-red', $bSetup, "COMPILATIONTIMESTAMP", $aThemeParameters, [$this->sTmpDir.'/branding/themes/'], $this->sTmpDir));

		if ($iCompileCSSFromSASSCount==1)
		{
			$sExpectedMainCssFile = APPROOT.$sExpectedMainCssPath;
			if (!is_file($sExpectedMainCssFile)) {
				$this->assertTrue(false, "Cannot find expected main css file $sExpectedMainCssFile");
			}

			$aPatterns = [static::PATTERN, '/'.$sBeforeReplacementCssVariableMd5sum.'/'];
			$aPatterns[] = "/8100523d2e76a70266f3e7110e2fe5fb/";
			$aPatterns[] = '/MD5SUM/';
			$aReplacements = [$sReplacement, $sAfterReplacementCssVariableMd5sum];
			$aReplacements[] = md5(json_encode($aThemeParameters['variables']));
			$aReplacements[] = $sAfterReplacementCssVariableMd5sum;
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
			echo (ThemeHandler::GetSignature($sActualCssFile));
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
		$aIncludedUrls = ThemeHandler::GetAllUrlFromScss(['attr' => "123"],APPROOT.$sScssFile);
		$this->assertEquals(['approot-relative', 'version', 'version1'], array_values($aIncludedUrls['aMissingVariables']));
		$this->assertEquals(["attr"=>"123"],
			$aIncludedUrls['aFoundVariables']);
		$aExpectedCompletedUrls = [
			'css/ui-lightness/images/tutu.jpg',
			"css/ui-lightness/images/tata.jpeg",
			"css/ui-lightness/images/tete.jpeg?g=123"
		];
		$aExpectedToCompleteUrls = [
			'\'abc/\'+ $approot-relative + "css/ui-lightness/images/toutou.png?v=" + $version',
			"\$approot-relative + \"css/ui-lightness/images/toto.png?v=\" + \$version",
			'$approot-relative + \'css/ui-lightness/images/titi.gif?v=\' + $version1',
		'"data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7?v=" + $version',
		'$approot-relative + \'node_modules/raleway-webfont/fonts/Raleway-Thin.jpeg\'',
		];

		$aIncludedUrls['aCompleteUrls'];
		$this->assertEquals($aExpectedCompletedUrls, array_values($aIncludedUrls['aCompleteUrls']));
		$this->assertEquals($aExpectedToCompleteUrls, array_values($aIncludedUrls['aToCompleteUrls']));
	}

	/**
	 * @return array
	 */
	public function GetAllUrlFromScssProvider()
	{
		return ['test-getimages.scss' => ['test/application/theme-handler/getimages/test-getimages.scss']];
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
		$aMissingVariables = ['gabu', 'toto', 'approot-relative', 'approot-relative2', 'gray-base', 'gray-darker', 'brand-primary', 'brand-primary-lightest', 'content-color', 'default-font-family', 'icons-filter'];
		list($aMissingVariables, $aFoundVariables) = ThemeHandler::FindMissingVariables(['gabu' => 'zomeu'], $aMissingVariables, ["a" => "b"], $sContent);
		$aExpectedFoundVariables = [
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
		];
		$this->assertEquals($aExpectedFoundVariables, $aFoundVariables);
		$this->assertEquals(['gray-darker', 'brand-primary', 'brand-primary-lightest'], $aMissingVariables);
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
		return [
			'XXX + $key1 UNresolved' => ["abc/'+ \$key1", ['key'=>'123'], false],
			'$key1 + XXX UNresolved' => ["\$key1 + abs", ['key'=>'123'], false],
			'XXX + $key UNresolved' => ["abc/'+ \$unknownkey", ['key'=>'123'], false],
			'XXX + $key resolved' => ["abc/'+ \$key", ['key'=>'123'], "abc/123"],
			'XXX + $key1 resolved' => ["abc/'+ \$key1", ['key1'=>'123'], "abc/123"],
			'$key + XXX resolved' => ["\$key + \"/abc", ['key'=>'123'], "123/abc"],
			'XXX + $key + YYY resolved' => ["abc/'+ \$key + '/def", ['key'=>'123'], "abc/123/def"],
		];
	}

	public function testGetIncludedImages()
	{
		$aStylesheetFile=glob($this->sTmpDir."/branding/css/*.scss");
		$aStylesheetFile[]=$this->sTmpDir."/branding/css/ui-lightness/DO_NOT_CHANGE.jqueryui.scss";
		$expectJsonFilePath = APPROOT.'test/application/theme-handler/expected/themes/basque-red/theme-parameters.json';
		$expectedThemeParamJson = file_get_contents($expectJsonFilePath);
		$aThemeParametersVariables = json_decode($expectedThemeParamJson, true);

		//simulate adding timestamp
		$aThemeParametersVariables['variables']['$version'] = microtime(true);

		$aIncludedImages = ThemeHandler::GetIncludedImages($aThemeParametersVariables['variables'], $aStylesheetFile, "basque-red");

		$aExpectedUris = json_decode(file_get_contents(APPROOT.'test/application/theme-handler/getimages/expected-getimages.json'), true);
		$aExpectedImages = [];
		foreach ($aExpectedUris as $sExpectedUri)
		{
			$aExpectedImages[] = ThemeHandler::GetAppRootWithSlashes().$sExpectedUri;
		}

		$this->assertEquals($aExpectedImages, $aIncludedImages);
	}

	/**
	 * @dataProvider FindStylesheetFileProvider
	 * @throws \Exception
	 */
	public function testFindStylesheetFile(string $sFileToFind, array $aAllImports){
		$sImportsPath = $this->sTmpDir.'/branding/';

		// Windows compat O:)
		$sFileToFind = $this->UpdateDirSep($sFileToFind);
		$sImportsPath = $this->UpdateDirSep($sImportsPath);

		$aExpectedAllImports = [];
		if (count($aAllImports) !== 0) {
			foreach ($aAllImports as $sFileURI) {
				$aExpectedAllImports[$sFileURI] = $this->UpdateDirSep($sImportsPath.$sFileURI);
			}
		}


		$oFindStylesheetObject = new FindStylesheetObject();
		ThemeHandler::FindStylesheetFile($sFileToFind, [$sImportsPath], $oFindStylesheetObject);

		$this->assertEquals([$sFileToFind], $oFindStylesheetObject->GetStylesheetFileURIs());
		$this->assertEquals($aExpectedAllImports, $oFindStylesheetObject->GetImportPaths());
		$this->assertEquals($sImportsPath.$sFileToFind, $oFindStylesheetObject->GetLastStyleSheetPath());

		$aExpectedAllStylesheetPaths = [];
		foreach (array_merge([$sFileToFind], $aAllImports) as $sFileUri) {
			$aExpectedAllStylesheetPaths [] = $this->UpdateDirSep($sImportsPath.$sFileUri);
		}
		$this->assertEquals($aExpectedAllStylesheetPaths, $oFindStylesheetObject->GetAllStylesheetPaths());
	}

	public function FindStylesheetFileProvider()
	{
		$sFileToFind3 = 'css/multi_imports.scss';
		$sFileToFind4 = 'css/included_file1.scss';
		$sFileToFind5 = 'css/included_scss/included_file2.scss';

		return [
			"single file to find" => [
				"sFileToFind" => "css/DO_NOT_CHANGE.light-grey.scss",
				"aAllImports" => [],
			],
			"scss with simple @imports" => [
				"sFileToFind" => "css/simple_import.scss",
				"aAllImports" => [$sFileToFind4],
			],
			"scss with multi @imports" => [
				"sFileToFind" => $sFileToFind3,
				"aAllImports" => [$sFileToFind4, $sFileToFind5]
			],
			"scss with simple @imports in another folder" => [
				"sFileToFind" => "css/simple_import2.scss",
				"aAllImports" => [$sFileToFind5]
			],
			"scss with @imports shortcut included_file3 => _included_file3.scss" => [
				"sFileToFind" => "css/shortcut.scss",
				"aAllImports" => ["css/_included_file3.scss", "css/included_scss/included_file4.scss"]
			],
			"scss with @imports shortcut same file and folder names => feature1/_feature1.scss" => [
				"sFileToFind" => "css/shortcut2.scss",
				"aAllImports" => ["css/feature1/_feature1.scss"],
			],
			"cross_reference & infinite loop" => [
				"sFileToFind" => "css/cross_reference1.scss",
				"aAllImports" => ["css/cross_reference2.scss"],
			],
		];
	}

	/**
	 * @param string $sPath
	 *
	 * @return string replace '/' by appropriate dir separator, depending on OS
	 *
	 * @uses DIRECTORY_SEPARATOR
	 */
	private function UpdateDirSep(string $sPath)
	{
		return str_replace('/', DIRECTORY_SEPARATOR, $sPath);
	}

	/**
	 * @param $sPath
	 * @param $sExpectedCanonicalPath
	 *
	 * @dataProvider CanonicalizePathProvider
	 */
	public function testCanonicalizePath($sExpectedCanonicalPath, $sPath)
	{
		$this->assertEquals($sExpectedCanonicalPath, ThemeHandler::CanonicalizePath($sPath), "Failed to reduce path $sPath");
	}

	public function CanonicalizePathProvider()
	{
		return [
			[ '/var/www/html/iTop/images/itop-logo-2.png', '/var/www/html/iTop/env-production/branding/themes/light-grey/../../../../images/itop-logo-2.png' ],
			[ '/var/www/html/iTop/env-production/branding/themes/light-grey/images/', '/var/www/html/iTop/env-production/branding/themes/light-grey/images/' ],
			[ '/var/www/html/iTop/css/ui-lightness/images/ui-icons_222222_256x240.png', '/var/www/html/iTop/env-production//branding/themes/light-grey//../../../../css/ui-lightness/images/ui-icons_222222_256x240.png' ]
		];
	}
}