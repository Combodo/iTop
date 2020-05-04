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
	private static $oCompileCSSService;
	/**
	 * Return default theme name and parameters
	 *
	 * @return array
	 * @since 2.7.0
	 */
	public static function GetDefaultThemeInformation()
	{
		return array(
			'name' => 'light-grey',
			'parameters' => array(
				'variables' => array(),
				'imports' => array(
					'css-variables' => '../css/css-variables.scss',
				),
				'stylesheets' => array(
					'jqueryui' => '../css/ui-lightness/jqueryui.scss',
					'main' => '../css/light-grey.scss',
				),
			),
		);
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
			
			static::CompileTheme($sThemeId, false, $aDefaultTheme['parameters']);
		}

		// Return absolute url to theme compiled css
		return utils::GetAbsoluteUrlModulesRoot().'/branding/themes/'.$sThemeId.'/main.css';
	}

	/**
	 * Compile the $sThemeId theme, the actual compilation is skipped when either
	 * 1) The produced CSS file exists and is more recent than any of its components (imports, stylesheets)
	 * 2) The produced CSS file exists and its signature is equal to the expected signature (imports, stylesheets, variables)
	 *
	 * @param string $sThemeId
	 * @param bool $bSetup : indicated whether compilation is performed in setup context (true) or when loading a page/theme (false)
	 * @param array|null $aThemeParameters Parameters (variables, imports, stylesheets) for the theme, if not passed, will be retrieved from compiled DM
	 * @param array|null $aImportsPaths Paths where imports can be found. Must end with '/'
	 * @param string|null $sWorkingPath Path of the folder used during compilation. Must end with a '/'
	 *
	 * @throws \CoreException
	 */
	public static function CompileTheme($sThemeId, $bSetup=false, $aThemeParameters = null, $aImportsPaths = null, $sWorkingPath = null)
	{
		// Default working path
		if($sWorkingPath === null)
		{
			$sWorkingPath = APPROOT.'env-'.utils::GetCurrentEnvironment().'/';
		}

		// Default import paths (env-*)
		if($aImportsPaths === null)
		{
			$aImportsPaths = array(
				APPROOT.'env-'.utils::GetCurrentEnvironment().'/',
			);
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

		$sTmpThemeScssContent = '';
		$iStyleLastModified = 0;
		clearstatcache();
		// Loading files to import and stylesheet to compile, also getting most recent modification time on overall files
		foreach ($aThemeParameters['imports'] as $sImport)
		{
			$sTmpThemeScssContent .= '@import "'.$sImport.'";'."\n";

			$sFile = static::FindStylesheetFile($sImport, $aImportsPaths);
			$iImportLastModified = @filemtime($sFile);
			$iStyleLastModified = $iStyleLastModified < $iImportLastModified ? $iImportLastModified : $iStyleLastModified;
		}
		foreach ($aThemeParameters['stylesheets'] as $sStylesheet)
		{
			$sTmpThemeScssContent .= '@import "'.$sStylesheet.'";'."\n";

			$sFile = static::FindStylesheetFile($sStylesheet, $aImportsPaths);
			$iStylesheetLastModified = @filemtime($sFile);
			$iStyleLastModified = $iStyleLastModified < $iStylesheetLastModified ? $iStylesheetLastModified : $iStyleLastModified;
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
			$sActualSignature = static::ComputeSignature($aThemeParameters, $aImportsPaths);

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
				if (!self::$oCompileCSSService)
				{
					self::$oCompileCSSService = new CompileCSSService();
				}
				$sTmpThemeCssContent = self::$oCompileCSSService->CompileCSSFromSASS($sTmpThemeScssContent, $aImportsPaths,
					$aThemeParameters['variables']);
				file_put_contents($sThemeCssPath, $sSignatureComment.$sTmpThemeCssContent);
			}
		}
	}

	/**
	 * Compute the signature of a theme defined by its theme parameters. The signature is a JSON structure of
	 * 1) one MD5 of all the variables/values (JSON encoded)
	 * 2) the MD5 of each stylesheet file
	 * 3) the MD5 of each import file
	 *
	 * @param string[] $aThemeParameters
	 * @param string[] $aImportsPaths
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function ComputeSignature($aThemeParameters, $aImportsPaths)
	{
		$aSignature = array(
			'variables' => md5(json_encode($aThemeParameters['variables'])),
			'stylesheets' => array(),
			'imports' => array(),
		);

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
		return json_encode($aSignature);
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
	private static function FindStylesheetFile($sFile, $aImportsPaths)
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

	public static function mockCompileCSSService($oCompileCSSServiceMock)
	{
		self::$oCompileCSSService = $oCompileCSSServiceMock;
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

	public function CompileCSSFromSASS($sSassContent, $aImportPaths = array(), $aVariables = array()){
		return utils::CompileCSSFromSASS($sSassContent, $aImportPaths, $aVariables);
	}

}

