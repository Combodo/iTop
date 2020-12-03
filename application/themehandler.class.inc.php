<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

/**
 * Class ThemeHandler
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @since 2.7.0
 */
class ThemeHandler
{
	const IMAGE_EXTENSIONS = ['png', 'gif', 'jpg', 'jpeg'];

	private static $oCompileCSSService;

	public static function GetAppRootWithSlashes()
	{
		return str_replace('\\', '/', APPROOT);
	}

	/**
	 * Return default theme name and parameters
	 *
	 * @return array
	 * @since 2.7.0
	 */
	public static function GetDefaultThemeInformation()
	{
		return [
			'name' => 'light-grey',
			'parameters' => [
				'variables' => [],
				'imports' => [
					'css-variables' => '../css/css-variables.scss',
				],
				'stylesheets' => [
					'jqueryui' => '../css/ui-lightness/jqueryui.scss',
					'main' => '../css/light-grey.scss',
				],
			],
		];
	}

	/**
	 * Return the ID of the theme currently defined in the config. file
	 *
	 * @return string
	 */
	public static function GetCurrentThemeId()
	{
		try
		{
			if (is_null(MetaModel::GetConfig()))
			{
				throw new CoreException('no config');
			}
			$sThemeId = MetaModel::GetConfig()->Get('backoffice_default_theme');
		}
		catch(CoreException $oCompileException)
		{
			// Fallback on our default theme in case the config. is not available yet
			$aDefaultTheme =  ThemeHandler::GetDefaultThemeInformation();
			$sThemeId = $aDefaultTheme['name'];
		}

		return $sThemeId;
	}

	/**
	 * Return the absolute path of the compiled theme folder.
	 *
	 * @param string $sThemeId
	 *
	 * @return string
	 */
	public static function GetCompiledThemeFolderAbsolutePath($sThemeId)
	{
		return APPROOT.'env-'.utils::GetCurrentEnvironment().'/branding/themes/'.$sThemeId.'/';
	}
	
	/**
	 * Return the absolute URL for the current theme CSS file
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetCurrentThemeUrl()
	{
		try
		{
			// Try to compile theme defined in the configuration
			$sThemeId = static::GetCurrentThemeId();
			static::CompileTheme($sThemeId);
		}
		catch(CoreException $oCompileException)
		{
			// Fallback on our default theme (should always be compilable) in case the previous theme doesn't exists
			$aDefaultTheme =  ThemeHandler::GetDefaultThemeInformation();
			$sThemeId = $aDefaultTheme['name'];
			$sDefaultThemeDirPath = static::GetCompiledThemeFolderAbsolutePath($sThemeId);
			
			// Create our theme dir if it doesn't exist (XML theme node removed, renamed etc..)
			if(!is_dir($sDefaultThemeDirPath))
			{
				SetupUtils::builddir($sDefaultThemeDirPath);
			}
			
			static::CompileTheme($sThemeId, false, "", $aDefaultTheme['parameters']);
		}

		// Return absolute url to theme compiled css
		return utils::GetAbsoluteUrlModulesRoot().'branding/themes/'.$sThemeId.'/main.css';
	}

	/**
	 * Compile the $sThemeId theme, the actual compilation is skipped when either
	 * 1) The produced CSS file exists and is more recent than any of its components (imports, stylesheets)
	 * 2) The produced CSS file exists and its signature is equal to the expected signature (imports, stylesheets, variables)
	 *
	 * @param string $sThemeId
	 * @param boolean $bSetup
	 * @param string $sSetupCompilationTimestamp : setup compilation timestamp in micro secunds
	 * @param array|null $aThemeParameters Parameters (variables, imports, stylesheets) for the theme, if not passed, will be retrieved from compiled DM
	 * @param array|null $aImportsPaths Paths where imports can be found. Must end with '/'
	 * @param string|null $sWorkingPath Path of the folder used during compilation. Must end with a '/'
	 *
	 * @throws \CoreException
	 * @return boolean: indicate whether theme compilation occured
	 */
	public static function CompileTheme($sThemeId, $bSetup=false, $sSetupCompilationTimestamp="", $aThemeParameters = null, $aImportsPaths = null, $sWorkingPath = null)
	{
		if ($sSetupCompilationTimestamp==="")
		{
			$sSetupCompilationTimestamp = microtime(true);
		}

		$sSetupCompilationTimestampInSecunds = (strpos($sSetupCompilationTimestamp, '.') !==false) ? explode('.', $sSetupCompilationTimestamp)[0] : $sSetupCompilationTimestamp;

		$sEnv = APPROOT.'env-'.utils::GetCurrentEnvironment().'/';

		// Default working path
		if($sWorkingPath === null)
		{
			$sWorkingPath = $sEnv;
		}

		// Default import paths (env-*)
		if($aImportsPaths === null)
		{
			$aImportsPaths = [ $sEnv];
		}

		// Note: We do NOT check that the folder exists!
		$sThemeFolderPath = $sWorkingPath.'/branding/themes/'.$sThemeId.'/';
		$sThemeCssPath = $sThemeFolderPath.'main.css';

		// Save parameters if passed... (typically during DM compilation)
		if(is_array($aThemeParameters))
		{
			if (!is_dir($sThemeFolderPath))
			{
				mkdir($sWorkingPath.'/branding/');
				mkdir($sWorkingPath.'/branding/themes/');
			}
			file_put_contents($sThemeFolderPath.'/theme-parameters.json', json_encode($aThemeParameters));
		}
		// ... Otherwise, retrieve them from compiled DM (typically when switching current theme in the config. file)
		else
		{
			$aThemeParameters = json_decode(@file_get_contents($sThemeFolderPath.'theme-parameters.json'), true);
			if ($aThemeParameters === null)
			{
				throw new CoreException('Could not load "'.$sThemeId.'" theme parameters from file, check that it has been compiled correctly');
			}
		}

		$aThemeParametersWithVersion = self::CloneThemeParameterAndIncludeVersion($aThemeParameters, $sSetupCompilationTimestampInSecunds);

		$sTmpThemeScssContent = '';
		$iStyleLastModified = 0;
		clearstatcache();
		// Loading files to import and stylesheet to compile, also getting most recent modification time on overall files

		$aStylesheetFiles = [];
		foreach ($aThemeParameters['imports'] as $sImport)
		{
			$sTmpThemeScssContent .= '@import "'.$sImport.'";'."\n";

			$sFile = static::FindStylesheetFile($sImport, $aImportsPaths);
			$iImportLastModified = @filemtime($sFile);
			$aStylesheetFiles[] = $sFile;
			$iStyleLastModified = $iStyleLastModified < $iImportLastModified ? $iImportLastModified : $iStyleLastModified;
		}
		foreach ($aThemeParameters['stylesheets'] as $sStylesheet)
		{
			$sTmpThemeScssContent .= '@import "'.$sStylesheet.'";'."\n";

			$sFile = static::FindStylesheetFile($sStylesheet, $aImportsPaths);
			$iStylesheetLastModified = @filemtime($sFile);
			$aStylesheetFiles[] = $sFile;
			$iStyleLastModified = $iStyleLastModified < $iStylesheetLastModified ? $iStylesheetLastModified : $iStyleLastModified;
		}

		$aIncludedImages=static::GetIncludedImages($aThemeParametersWithVersion, $aStylesheetFiles, $sThemeId);
		foreach ($aIncludedImages as $sImage)
		{
			if (is_file($sImage))
			{
				$iStylesheetLastModified = @filemtime($sImage);
				$iStyleLastModified = $iStyleLastModified < $iStylesheetLastModified ? $iStylesheetLastModified : $iStyleLastModified;
			}
		}

		// Checking if our compiled css is outdated
		$iFilemetime = @filemtime($sThemeCssPath);
		$bFileExists = file_exists($sThemeCssPath);
		$bVarSignatureChanged=false;
		if ($bFileExists && $bSetup)
		{
			$sPrecompiledSignature = static::GetSignature($sThemeCssPath);
			//check variable signature has changed which is independant from any file modification
			if (!empty($sPrecompiledSignature)){
				$sPreviousVariableSignature = static::GetVarSignature($sPrecompiledSignature);
				$sCurrentVariableSignature = md5(json_encode($aThemeParameters['variables']));
				$bVarSignatureChanged= ($sPreviousVariableSignature!==$sCurrentVariableSignature);
			}
		}

		if (!$bFileExists || $bVarSignatureChanged || (is_writable($sThemeFolderPath) && ($iFilemetime < $iStyleLastModified)))
		{
			// Dates don't match. Second chance: check if the already compiled stylesheet exists and is consistent based on its signature
			$sActualSignature = static::ComputeSignature($aThemeParameters, $aImportsPaths, $aIncludedImages);

			if ($bFileExists && !$bSetup)
			{
				$sPrecompiledSignature = static::GetSignature($sThemeCssPath);
			}

			if (!empty($sPrecompiledSignature) && $sActualSignature == $sPrecompiledSignature)
			{
				touch($sThemeCssPath); // Stylesheet is up to date, mark it as more recent to speedup next time
			}
			else
			{
				// Alas, we really need to recompile
				// Add the signature to the generated CSS file so that the file can be used as a precompiled stylesheet if needed
				$sSignatureComment =
					<<<CSS
/*
=== SIGNATURE BEGIN ===
$sActualSignature
=== SIGNATURE END ===
*/

CSS;
				if (!static::$oCompileCSSService)
				{
					static::$oCompileCSSService = new CompileCSSService();
				}
				//store it again to change $version with latest compiled time
				$sTmpThemeCssContent = static::$oCompileCSSService->CompileCSSFromSASS($sTmpThemeScssContent, $aImportsPaths,
					$aThemeParametersWithVersion);
				file_put_contents($sThemeFolderPath.'/theme-parameters.json', json_encode($aThemeParameters));
				file_put_contents($sThemeCssPath, $sSignatureComment.$sTmpThemeCssContent);
				SetupLog::Info("Theme $sThemeId file compiled.");
				return true;
			}
		}
		return false;
	}

	/**
	 * Compute the signature of a theme defined by its theme parameters. The signature is a JSON structure of
	 * 1) one MD5 of all the variables/values (JSON encoded)
	 * 2) the MD5 of each stylesheet file
	 * 3) the MD5 of each import file
	 * 3) the MD5 of each images referenced in style sheets
	 *
	 * @param string[] $aThemeParameters
	 * @param string[] $aImportsPaths
	 * @param string[] $aIncludedImages
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function ComputeSignature($aThemeParameters, $aImportsPaths, $aIncludedImages)
	{
		$aSignature = [
			'variables' => md5(json_encode($aThemeParameters['variables'])),
			'stylesheets' => [],
			'imports' => [],
			'images' => []
		];

		foreach ($aThemeParameters['imports'] as $key => $sImport)
		{
			$sFile = static::FindStylesheetFile($sImport, $aImportsPaths);
			$aSignature['stylesheets'][$key] = md5_file($sFile);
		}
		foreach ($aThemeParameters['stylesheets'] as $key => $sStylesheet)
		{
			$sFile = static::FindStylesheetFile($sStylesheet, $aImportsPaths);
			$aSignature['stylesheets'][$key] = md5_file($sFile);
		}
		foreach ($aIncludedImages as $sImage)
		{
			if (is_file($sImage)) {
				$sUri = str_replace(self::GetAppRootWithSlashes(), '', $sImage);
				$aSignature['images'][$sUri] = md5_file($sImage);
			}
		}

		return json_encode($aSignature);
	}

	/**
	 * Search for images referenced in stylesheet files
	 *
	 * @param array $aThemeParametersVariables
	 * @param array $aStylesheetFiles
	 * @param string $sThemeId : used only for logging purpose
	 *
	 * @return array complete path of the images, but with slashes as dir separator instead of DIRECTORY_SEPARATOR
	 * @since 3.0.0
	 */
	public static function GetIncludedImages($aThemeParametersVariables, $aStylesheetFiles, $sThemeId)
	{
		$sTargetThemeFolderPath = static::GetCompiledThemeFolderAbsolutePath($sThemeId);

		$aCompleteUrls = [];
		$aToCompleteUrls = [];
		$aMissingVariables = [];
		$aFoundVariables = ['version'=>''];
		$aMap = [
			'aCompleteUrls' => $aCompleteUrls,
			'aToCompleteUrls' => $aToCompleteUrls,
			'aMissingVariables' => $aMissingVariables,
			'aFoundVariables' => $aFoundVariables,
		];

		foreach ($aStylesheetFiles as $sStylesheetFile)
		{
			$aRes = static::GetAllUrlFromScss($aThemeParametersVariables, $sStylesheetFile);
			/** @var array $aVal */
			foreach($aMap as $key => $aVal)
			{
				if (array_key_exists($key, $aMap))
				{
					$aMap[$key] = array_merge($aVal, $aRes[$key]);
				}
			}
		}

		$aMap = static::ResolveUncompleteUrlsFromScss($aMap, $aThemeParametersVariables, $aStylesheetFiles);
		$aImages = [];

		foreach ($aMap ['aCompleteUrls'] as $sUri => $sUrl)
		{
			$sImg = $sUrl;
			if (preg_match("/(.*)\?/", $sUrl, $aMatches))
			{
				$sImg=$aMatches[1];
			}

			if (static::HasImageExtension($sImg)
				&& ! array_key_exists($sImg, $aImages))
			{
				$sFilePath = realpath($sImg);
				if ($sFilePath !== false) {
					$sFilePathWithSlashes = str_replace('\\', '/', $sFilePath);
					$aImages[$sImg] = $sFilePathWithSlashes;
					continue;
				}

				$sCanonicalPath = static::CanonicalizePath($sTargetThemeFolderPath.'/'.$sImg);
				$sFilePath = realpath($sCanonicalPath);
				if ($sFilePath !== false) {
					$sFilePathWithSlashes = str_replace('\\', '/', $sFilePath);
					$aImages[$sImg] = $sFilePathWithSlashes;
					continue;
				}

				SetupLog::Warning("Cannot find $sCanonicalPath ($sImg) during SCSS $sThemeId precompilation");
			}
		}

		return array_values($aImages);
	}

	/**
	 * Reduce path without using realpath (works only when file exists)
	 * @param $path
	 *
	 * @return string
	 */
	public static function CanonicalizePath($path)
	{
		$path = explode('/', str_replace('//','/', $path));
		$stack = array();
		foreach ($path as $seg) {
			if ($seg == '..') {
				// Ignore this segment, remove last segment from stack
				array_pop($stack);
				continue;
			}

			if ($seg == '.') {
				// Ignore this segment
				continue;
			}

			$stack[] = $seg;
		}

		return implode('/', $stack);
	}

	/**
	 * Complete url using provided variables. Example with $var=1: XX + $var => XX1
	 * @param $aMap
	 * @param $aThemeParametersVariables
	 * @param $aStylesheetFile
	 *
	 * @return mixed
	 */
	public static function ResolveUncompleteUrlsFromScss($aMap, $aThemeParametersVariables, $aStylesheetFile)
	{
		$sContent="";
		foreach ($aStylesheetFile as $sStylesheetFile)
		{
			if (is_file($sStylesheetFile))
			{
				$sContent .= '\n' . file_get_contents($sStylesheetFile);
			}
		}

		$aMissingVariables=$aMap['aMissingVariables'];
		$aFoundVariables=$aMap['aFoundVariables'];
		$aToCompleteUrls=$aMap['aToCompleteUrls'];
		$aCompleteUrls=$aMap['aCompleteUrls'];
		list($aMissingVariables, $aFoundVariables) = static::FindMissingVariables($aThemeParametersVariables, $aMissingVariables, $aFoundVariables, $sContent, true);
		list($aToCompleteUrls, $aCompleteUrls) = static::ResolveUrls($aFoundVariables, $aToCompleteUrls, $aCompleteUrls);
		$aMap['aMissingVariables']=$aMissingVariables;
		$aMap['aFoundVariables']=$aFoundVariables;
		$aMap['aToCompleteUrls']=$aToCompleteUrls;
		$aMap['aCompleteUrls']=$aCompleteUrls;
		return $aMap;
	}

	/**
	 * Find missing variable values from SCSS content based on their name.
	 *
	 * @param $aThemeParametersVariables
	 * @param $aMissingVariables
	 * @param $aFoundVariables
	 * @param $sContent : scss content
	 * @param bool $bForceEmptyValueWhenNotFound
	 *
	 * @return array
	 */
	public static function FindMissingVariables($aThemeParametersVariables, $aMissingVariables, $aFoundVariables, $sContent, $bForceEmptyValueWhenNotFound=false)
	{
		$aNewMissingVars = [];
		if (!empty($aMissingVariables))
		{
			foreach ($aMissingVariables as $var)
			{
				if (array_key_exists($var, $aThemeParametersVariables))
				{
					$aFoundVariables[$var] = $aThemeParametersVariables[$var];
				}
				else
				{
					if (preg_match_all("/\\\$$var\s*:\s*[\"']{0,1}(.*)[\"']{0,1};/", $sContent, $aValues))
					{
						$sValue = $aValues[1][0];
						if (preg_match_all("/([^!]+)!/", $sValue, $aSubValues))
						{
							$sValue = trim($aSubValues[1][0], ' "\'');
						}

						if (strpos($sValue, '$') === false)
						{
							$aFoundVariables[$var] = $sValue;
						}
						else{
							$aNewMissingVars[] = $var;
						}
					}
					else
					{
						if ($bForceEmptyValueWhenNotFound)
						{
							$aFoundVariables[$var] = '';
						}
						else
						{
							$aNewMissingVars[] = $var;
						}
					}
				}
			}
		}

		return [ $aNewMissingVars, $aFoundVariables ];
	}

	/**
	 * @param $aFoundVariables
	 * @param array $aToCompleteUrls
	 * @param array $aCompleteUrls
	 *
	 * @return array
	 */
	public static function ResolveUrls($aFoundVariables, array $aToCompleteUrls, array $aCompleteUrls)
	{
		if (!empty($aFoundVariables))
		{
			$aFoundVariablesWithEmptyValue = [];
			foreach ($aFoundVariables as $aFoundVariable => $sValue)
			{
				$aFoundVariablesWithEmptyValue[$aFoundVariable] = '';
			}

			foreach ($aToCompleteUrls as $sUrlTemplate)
			{
				unset($aToCompleteUrls[$sUrlTemplate]);
				$sResolvedUrl = static::ResolveUrl($sUrlTemplate, $aFoundVariables);
				if ($sResolvedUrl == false)
				{
					$aToCompleteUrls[$sUrlTemplate] = $sUrlTemplate;
				}
				else
				{
					$sUri = static::ResolveUrl($sUrlTemplate, $aFoundVariablesWithEmptyValue);
					$aExplodedUri = explode('?', $sUri);
					if (empty($aExplodedUri))
					{
						$aCompleteUrls[$sUri] = $sResolvedUrl;
					}
					else
					{
						$aCompleteUrls[$aExplodedUri[0]] = $sResolvedUrl;
					}
				}
			}
		}

		return [ $aToCompleteUrls, $aCompleteUrls];
	}

	/**
	 * Find all referenced URLs from a SCSS file.
	 * @param $aThemeParametersVariables
	 * @param $sStylesheetFile
	 *
	 * @return array
	 */
	public static function GetAllUrlFromScss($aThemeParametersVariables, $sStylesheetFile)
	{
		$aCompleteUrls = [];
		$aToCompleteUrls = [];
		$aMissingVariables = [];
		$aFoundVariables = [];

		if (is_file($sStylesheetFile))
		{
			$sContent = file_get_contents($sStylesheetFile);
			if (preg_match_all("/url\s*\((.*)\)/", $sContent, $aMatches))
			{
				foreach ($aMatches[1] as $path)
				{
					if (!array_key_exists($path, $aCompleteUrls)
						&& !array_key_exists($path, $aToCompleteUrls))
					{
						if (preg_match_all("/\\$([\w\-_]+)/", $path, $aCurrentVars))
						{
							/** @var string $aCurrentVars */
							foreach ($aCurrentVars[1] as $var)
							{
								if (!array_key_exists($var, $aMissingVariables))
								{
									$aMissingVariables[$var] = $var;
								}
							}
							$aToCompleteUrls[$path] = $path;
						}
						else
						{
							$aCompleteUrls[$path] = trim($path, "\"'");
						}
					}
				}
			}
			if (!empty($aMissingVariables))
			{
				list($aMissingVariables, $aFoundVariables) = static::FindMissingVariables($aThemeParametersVariables, $aMissingVariables, $aFoundVariables, $sContent);
				list($aToCompleteUrls, $aCompleteUrls) = static::ResolveUrls($aFoundVariables, $aToCompleteUrls, $aCompleteUrls);
			}
		}

		return  [
			'aCompleteUrls' => $aCompleteUrls,
			'aToCompleteUrls' => $aToCompleteUrls,
			'aMissingVariables' => $aMissingVariables,
			'aFoundVariables' => $aFoundVariables
		];
	}

	/**
	 * Calculate url based on its template + variables.
	 * @param $sUrlTemplate
	 * @param $aFoundVariables
	 *
	 * @return bool|string
	 */
	public static function ResolveUrl($sUrlTemplate, $aFoundVariables)
	{
		$aPattern= [];
		$aReplacement= [];
		foreach ($aFoundVariables as $aFoundVariable => $aFoundVariableValue)
		{
			//XX + $key + YY
			$aPattern[]="/['\"]\s*\+\s*\\\$" . $aFoundVariable . "[\s\+]+\s*['\"]/";
			$aReplacement[]=$aFoundVariableValue;
			//$key + YY
			$aPattern[]="/\\\$" . $aFoundVariable. "[\s\+]+\s*['\"]/";
			$aReplacement[]=$aFoundVariableValue;
			//XX + $key
			$aPattern[]="/['\"]\s*[\+\s]+\\\$" . $aFoundVariable . "$/";
			$aReplacement[]=$aFoundVariableValue;
		}
		$sResolvedUrl=preg_replace($aPattern, $aReplacement, $sUrlTemplate);
		if (strpos($sResolvedUrl, "+")!==false)
		{
			return false;
		}
		return trim($sResolvedUrl, "\"'");
	}

	/**
	 * indicate whether a string ends with image suffix.
	 * @param $path
	 *
	 * @return bool
	 */
	private static function HasImageExtension($path)
	{
		foreach (static::IMAGE_EXTENSIONS as $sExt)
		{
			if (endsWith($path, $sExt))
			{
				return true;
			}
		}
		return false;
	}


	/**
	 * Extract the signature for a generated CSS file. The signature MUST be alone one line immediately
	 * followed (on the next line) by the === SIGNATURE END === pattern
	 *
	 * Note the signature can be place anywhere in the CSS file (obviously inside a CSS comment !) but the
	 * function will be faster if the signature is at the beginning of the file (since the file is scanned from the start)
	 *
	 * @param $sFilepath
	 *
	 * @return string
	 */
	public static function GetSignature($sFilepath)
	{
		$sPreviousLine = '';
		$hFile = @fopen($sFilepath, "r");
		if ($hFile !== false)
		{
			$sLine = '';
			do
			{
				$sPreviousLine = $sLine;
				$sLine = rtrim(fgets($hFile)); // Remove the trailing \n
			}
			while (($sLine !== false) && ($sLine != '=== SIGNATURE END ==='));
			fclose($hFile);
		}
		return $sPreviousLine;
	}

	public static function GetVarSignature($JsonSignature)
	{
		$aJsonArray = json_decode($JsonSignature, true);
		if (array_key_exists('variables', $aJsonArray))
		{
			return $aJsonArray['variables'];
		}
		return false;
	}

	/**
	 * Find the given file in the list of ImportsPaths directory
	 * @param string $sFile
	 * @param string[] $aImportsPaths
	 * @throws Exception
	 * @return string
	 */
	public static function FindStylesheetFile($sFile, $aImportsPaths)
	{
		foreach($aImportsPaths as $sPath)
		{
			$sImportedFile = realpath($sPath.'/'.$sFile);
			if (file_exists($sImportedFile))
			{
				return $sImportedFile;
			}
		}
		return ''; // Not found, fail silently, maybe the SCSS compiler knowns better...
	}

	public static function MockCompileCSSService($oCompileCSSServiceMock)
	{
		static::$oCompileCSSService = $oCompileCSSServiceMock;
	}

	/**
	 * Clone variable array and include $version with bSetupCompilationTimestamp value
	 * @param $aThemeParameters
	 * @param $bSetupCompilationTimestamp
	 *
	 * @return array
	 */
	public static function CloneThemeParameterAndIncludeVersion($aThemeParameters, $bSetupCompilationTimestamp)
	{
		$aThemeParametersVariable = [];
		if (array_key_exists('variables', $aThemeParameters))
		{
			if (is_array($aThemeParameters['variables']))
			{
				$aThemeParametersVariable = array_merge([], $aThemeParameters['variables']);
			}
		}

		$aThemeParametersVariable['$version'] = $bSetupCompilationTimestamp;
		return $aThemeParametersVariable;
	}
}

class CompileCSSService
{
	/**
	 * CompileCSSService constructor.
	 */
	public function __construct()
	{
	}

	public function CompileCSSFromSASS($sSassContent, $aImportPaths =  [], $aVariables = []){
		return utils::CompileCSSFromSASS($sSassContent, $aImportPaths, $aVariables);
	}

}

