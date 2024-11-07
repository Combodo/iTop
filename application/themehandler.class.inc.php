<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
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

	/** @var \CompileCSSService */
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
			'name' => 'fullmoon',
			'parameters' => [
				'variables' => [],
				'imports' => [],
				'stylesheets' => [
					'main' => '../css/backoffice/main.scss',
				]
			],
		];
	}

	/**
	 * Return the ID of the theme currently defined in the config. file
	 *
	 * @deprecated 3.0.0, will be removed in 3.1, see N°3898
	 * @return string
	 */
	public static function GetCurrentThemeId()
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod();
		static::GetCurrentUserThemeId();
	}

	/**
	 * @return string ID of the theme currently defined in the config. file, which applies to all users by default. If non defined, fallback on the default one.
	 * @since 3.0.0
	 */
	public static function GetApplicationThemeId(): string
	{
		try {
			$sThemeId = utils::GetConfig()->Get('backoffice_default_theme');
		}
		catch (CoreException $oCompileException) {
			// Fallback on our default theme in case the config. is not available yet
			$aDefaultTheme = ThemeHandler::GetDefaultThemeInformation();
			$sThemeId = $aDefaultTheme['name'];
		}

		return $sThemeId;
	}

	/**
	 * @return string ID of the theme to use for the current user as per they preferences. If non defined, fallback on the app. theme ID.
	 * @since 3.0.0
	 */
	public static function GetCurrentUserThemeId(): string
	{
		$sThemeId = null;

		try {
			if (true === utils::GetConfig()->Get('user_preferences.allow_backoffice_theme_override')) {
				$sThemeId = appUserPreferences::GetPref('backoffice_theme', null);
			}
		}
		catch (Exception $oException) {
			// Do nothing, already handled by $sThemeId null by default
		}

		// Fallback on the app. theme
		if (is_null($sThemeId)) {
			$sThemeId = static::GetApplicationThemeId();
		}

		return $sThemeId;
	}

	/**
	 * @param string $sThemeId
	 *
	 * @return string Label of the theme which is either a dict entry ('theme:<THEME_ID>') or the ID if no localized dict. entry found.
	 * @since 3.0.0
	 */
	public static function GetThemeLabel(string $sThemeId): string
	{
		$sDictEntryCode = 'theme:'.$sThemeId;
		$sDictEntryValue = Dict::S('theme:'.$sThemeId);

		return ($sDictEntryCode === $sDictEntryValue) ? $sThemeId : $sDictEntryValue;
	}

	/**
	 * @return array Associative array of <THEME_ID> => <THEME_LABEL>, ordered by labels
	 * @since 3.0.0
	 */
	public static function GetAvailableThemes(): array
	{
		$aThemes = [];

		foreach (glob(static::GetCompiledThemesFolderAbsolutePath().'/*') as $sPath) {
			if (is_dir($sPath)) {
				$sThemeId = basename($sPath);
				$sThemeLabel = static::GetThemeLabel($sThemeId);

				$aThemes[$sThemeId] = $sThemeLabel;
			}
		}
		asort($aThemes);

		return $aThemes;
	}

	/**
	 * @param string $sThemeId
	 *
	 * @return bool True if $sThemeId is a valid theme that can be used.
	 * @since 3.0.0
	 */
	public static function IsValidTheme(string $sThemeId): bool
	{
		return array_key_exists($sThemeId, static::GetAvailableThemes());
	}

	/**
	 * @return string Absolute path to the folder containing all the compiled themes
	 * @since 3.0.0
	 */
	public static function GetCompiledThemesFolderAbsolutePath(): string
	{
		return APPROOT.'env-'.utils::GetCurrentEnvironment().'/branding/themes/';
	}

	/**
	 * @param string $sThemeId
	 *
	 * @return string Absolute path to the folder containing the $sThemeId theme
	 */
	public static function GetCompiledThemeFolderAbsolutePath(string $sThemeId): string
	{
		return static::GetCompiledThemesFolderAbsolutePath().$sThemeId.'/';
	}

	/**
	 * @param string $sThemeId
	 *
	 * @return string Absolute path of the compiled file for the $sThemeId theme (Note: It doesn't mean that the theme is actually compiled)
	 * @since 3.0.0
	 */
	public static function GetCompiledThemeFileAbsolutePath(string $sThemeId): string
	{
		return static::GetCompiledThemeFolderAbsolutePath($sThemeId).'main.css';
	}

	/**
	 * @param string $sThemeId
	 *
	 * @return string Absolute URL of the compiled file for the $sThemeId theme (Note: It doesn't mean that the theme is actually compiled)
	 * @throws \Exception
	 * @since 3.0.0
	 */
	public static function GetCompiledThemeFileAbsoluteUrl(string $sThemeId): string
	{
		return utils::GetAbsoluteUrlModulesRoot().'branding/themes/'.$sThemeId.'/main.css';
	}

	/**
	 * Return the absolute URL for the current theme CSS file
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetCurrentThemeUrl(): string
	{
		try {
			// Try to compile theme defined in the configuration
			// Note: In maintenance mode we should stick to the app theme (also we don't have access to many PHP classes, including the user preferences)
			$sThemeId = SetupUtils::IsInMaintenanceMode() ? static::GetApplicationThemeId() : static::GetCurrentUserThemeId();
			if (static::ShouldThemeSignatureCheckBeForced($sThemeId)) {
				static::CompileTheme($sThemeId);
			}
		}
		catch (CoreException $oCompileException) {
			// Fallback on our default theme (should always be compilable) in case the previous theme doesn't exists
			$aDefaultTheme = ThemeHandler::GetDefaultThemeInformation();
			$sThemeId = $aDefaultTheme['name'];
			$sDefaultThemeDirPath = static::GetCompiledThemeFolderAbsolutePath($sThemeId);

			// Create our theme dir if it doesn't exist (XML theme node removed, renamed etc..)
			if (!is_dir($sDefaultThemeDirPath)) {
				SetupUtils::builddir($sDefaultThemeDirPath);
			}

			if (static::ShouldThemeSignatureCheckBeForced($sThemeId)) {
				static::CompileTheme($sThemeId, false, "", $aDefaultTheme['parameters']);
			}
		}

		return static::GetCompiledThemeFileAbsoluteUrl($sThemeId);
	}

	/**
	 * @param string $sThemeId
	 *
	 * @return bool True if the $sThemeId signature check -and possibly the compilation- should be forced (dev. environment, missing compiled file, ...)
	 */
	protected static function ShouldThemeSignatureCheckBeForced(string $sThemeId): bool
	{
		if (utils::IsDevelopmentEnvironment()) {
			return true;
		}

		if (false === file_exists(static::GetCompiledThemeFileAbsolutePath($sThemeId))) {
			return true;
		}

		if (true === utils::GetConfig()->Get('theme.force_signature_check_at_runtime')) {
			return true;
		}

		return false;
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
	 * @param array|null $aImportsPaths Folder paths where imports can be found. Must end with '/'
	 * @param string|null $sWorkingPath Path of the folder used during compilation. Must end with a '/'
	 *
	 * @throws \CoreException
	 * @return boolean: indicate whether theme compilation occured
	 */
	public static function CompileTheme($sThemeId, $bSetup=false, $sSetupCompilationTimestamp="", $aThemeParameters = null, $aImportsPaths = null, $sWorkingPath = null) {
		if ($sSetupCompilationTimestamp === "") {
			$sSetupCompilationTimestamp = microtime(true);
		}

		$sSetupCompilationTimestampInSecunds = (strpos($sSetupCompilationTimestamp, '.') !== false) ? explode('.',
			$sSetupCompilationTimestamp)[0] : $sSetupCompilationTimestamp;

		$sEnv = APPROOT.'env-'.utils::GetCurrentEnvironment().'/';

		// Default working path
		if ($sWorkingPath === null) {
			$sWorkingPath = $sEnv;
		}

		// Default import paths (env-*)
		if ($aImportsPaths === null) {
			$aImportsPaths = [$sEnv];
		}

		// Note: We do NOT check that the folder exists!
		$sThemeFolderPath = $sWorkingPath.'/branding/themes/'.$sThemeId.'/';
		$sThemeCssPath = $sThemeFolderPath.'main.css';

		// Save parameters if passed... (typically during DM compilation)
		if (is_array($aThemeParameters)) {
			if (!is_dir($sThemeFolderPath)) {
				mkdir($sWorkingPath.'/branding/');
				mkdir($sWorkingPath.'/branding/themes/');
			}
			file_put_contents($sThemeFolderPath.'/theme-parameters.json', json_encode($aThemeParameters));
		} // ... Otherwise, retrieve them from compiled DM (typically when switching current theme in the config. file)
		else {
			$aThemeParameters = json_decode(@file_get_contents($sThemeFolderPath.'theme-parameters.json'), true);
			if ($aThemeParameters === null) {
				throw new CoreException('Could not load "'.$sThemeId.'" theme parameters from file, check that it has been compiled correctly');
			}
		}

		$aThemeParametersWithVersion = self::CloneThemeParameterAndIncludeVersion($aThemeParameters, $sSetupCompilationTimestampInSecunds, $aImportsPaths);

		clearstatcache();

		// Loading files to import and stylesheet to compile, also getting most recent modification time on overall files
		$sTmpThemeScssContent = '';
		$oFindStylesheetObject = new FindStylesheetObject();

		if (isset($aThemeParameters['utility_imports'])) {
			foreach ($aThemeParameters['utility_imports'] as $sImport) {
				static::FindStylesheetFile($sImport, $aImportsPaths, $oFindStylesheetObject);
			}
		}

		if (isset($aThemeParameters['stylesheets'])) {
			foreach ($aThemeParameters['stylesheets'] as $sStylesheet) {
				static::FindStylesheetFile($sStylesheet, $aImportsPaths, $oFindStylesheetObject);
			}
		}

		foreach ($oFindStylesheetObject->GetStylesheetFileURIs() as $sStylesheet){
			$sTmpThemeScssContent .= '@import "'.$sStylesheet.'";'."\n";
		}

		if (isset($aThemeParameters['variable_imports'])) {
			foreach ($aThemeParameters['variable_imports'] as $sImport) {
				static::FindStylesheetFile($sImport, $aImportsPaths, $oFindStylesheetObject);
			}
		}

		$iStyleLastModified = $oFindStylesheetObject->GetLastModified();

		$aIncludedImages=static::GetIncludedImages($aThemeParametersWithVersion, $oFindStylesheetObject->GetAllStylesheetPaths(), $sThemeId);
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
				SetupLog::Info("Compiling theme $sThemeId...");
				$sTmpThemeCssContent = static::$oCompileCSSService->CompileCSSFromSASS($sTmpThemeScssContent, $aImportsPaths,
					$aThemeParametersWithVersion);
				SetupLog::Info("$sThemeId theme compilation done.");
				file_put_contents($sThemeFolderPath.'/theme-parameters.json', json_encode($aThemeParameters));
				file_put_contents($sThemeCssPath, $sSignatureComment.$sTmpThemeCssContent);
				return true;
			}
		}
		return false;
	}

	/**
	 * @since 3.0.0 N°2982
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
	public static function ComputeSignature($aThemeParameters, $aImportsPaths, $aIncludedImages) {
		$aSignature = [
			'variables' => md5(json_encode($aThemeParameters['variables'])),
			'stylesheets' => [],
			'variable_imports' => [],
			'images' => [],
			'utility_imports' => []
		];

		$oFindStylesheetObject = new FindStylesheetObject();

		if (isset($aThemeParameters['variable_imports'])) {
			foreach ($aThemeParameters['variable_imports'] as $key => $sImport) {
				static::FindStylesheetFile($sImport, $aImportsPaths, $oFindStylesheetObject);
				$sFile = $oFindStylesheetObject->GetLastStylesheetFile();
				if (!empty($sFile)) {
					$aSignature['variable_imports'][$key] = md5_file($sFile);
				}
			}
		}

		if (isset($aThemeParameters['utility_imports'])) {
			foreach ($aThemeParameters['utility_imports'] as $key => $sImport) {
				static::FindStylesheetFile($sImport, $aImportsPaths, $oFindStylesheetObject);
				$sFile = $oFindStylesheetObject->GetLastStylesheetFile();
				if (!empty($sFile)) {
					$aSignature['utility_imports'][$key] = md5_file($sFile);
				}
			}
		}
		if (isset($aThemeParameters['stylesheets'])) {
			foreach ($aThemeParameters['stylesheets'] as $key => $sStylesheet) {
				static::FindStylesheetFile($sStylesheet, $aImportsPaths, $oFindStylesheetObject);
				$sFile = $oFindStylesheetObject->GetLastStylesheetFile();

				if (!empty($sFile)) {
					$aSignature['stylesheets'][$key] = md5_file($sFile);
				}
			}
		}

		$aFiles = $oFindStylesheetObject->GetImportPaths();
		if (count($aFiles) !== 0) {
			foreach ($aFiles as $sFileURI => $sFilePath) {
				$aSignature['utility_imports'][$sFileURI] = md5_file($sFilePath);
			}
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
	 * @since 3.0.0 N°2982
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
				$sFilePath = utils::RealPath($sImg, APPROOT);
				if ($sFilePath !== false) {
					$sFilePathWithSlashes = str_replace('\\', '/', $sFilePath);
					$aImages[$sImg] = $sFilePathWithSlashes;
					continue;
				}

				$sCanonicalPath = static::CanonicalizePath($sTargetThemeFolderPath.'/'.$sImg);
				$sFilePath = utils::RealPath($sCanonicalPath, APPROOT);
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
	 * @since 3.0.0 N°2982
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
	 * @since 3.0.0 N°2982
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
	 * @since 3.0.0 N°2982
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
	 * @since 3.0.0 N°2982
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
					$iRemainingClosingParenthesisPos = strpos($path, ')');
					if ($iRemainingClosingParenthesisPos !== false){
						$path = substr($path, 0, $iRemainingClosingParenthesisPos);
					}
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
	 * @since 3.0.0 N°2982
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
	 * @since 3.0.0 N°2982
	 * Extract the signature for a generated CSS file.
	 * The signature MUST be alone one line immediately followed (on the next line) by the === SIGNATURE END === pattern
	 * The signature MUST be in the first 100th lines of the file.
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
		$iCount = 0;
		$sPreviousLine = '';
		$hFile = @fopen($sFilepath, "r");
		if ($hFile !== false)
		{
			$sLine = '';
			do
			{
				$iCount++;
				$sPreviousLine = $sLine;
				$sLine = rtrim(fgets($hFile)); // Remove the trailing \n
			}
			while (($sLine !== false) && ($sLine != '=== SIGNATURE END ===') && ($iCount <= 100));
			fclose($hFile);
		}
		return $sPreviousLine;
	}

	/**
	 * @since 3.0.0 N°2982
	 * @param $JsonSignature
	 *
	 * @return false|mixed
	 */
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
	 * @param string $sFileURI
	 * @param string[] $aImportsPaths
	 * @param FindStylesheetObject $oFindStylesheetObject
	 * @param bool $bImports
	 *
	 * @throws \Exception
	 *@since 3.0.0 N°2982
	 * Find the given file in the list '$aImportsPaths' of directory and all included stylesheets as well
	 * Compute latest timestamp found among all found stylesheets
	 *
	 */
	public static function FindStylesheetFile(string $sFileURI, array $aImportsPaths, $oFindStylesheetObject, $bImports = false)
	{
		if (! $bImports) {
			$oFindStylesheetObject->ResetLastStyleSheet();
		}

		foreach($aImportsPaths as $sPath)
		{
			$sAlterableFileURI = $sFileURI;
			$sFilePath = $sPath.'/'.$sAlterableFileURI;
			$sImportedFile = realpath($sFilePath);
			if ($sImportedFile === false){
				// Handle shortcut syntax : @import "typo" ;
				// file matched: typo.scss
				$sFilePath2 = "$sFilePath.scss";
				$sImportedFile = realpath($sFilePath2);
				if ($sImportedFile){
					self::FindStylesheetFile("$sAlterableFileURI.scss", [ $sPath ], $oFindStylesheetObject, $bImports);
					$sImportedFile = false;
				}
			}

			if ($sImportedFile === false){
				// Handle shortcut syntax : @import "typo" ;
				// file matched: _typo.scss
				$sShortCut = substr($sFilePath, strrpos($sFilePath, '/') + 1);
				$sFilePath = static::ReplaceLastOccurrence($sShortCut, "_$sShortCut.scss", $sFilePath);
				$sAlterableFileURI = static::ReplaceLastOccurrence($sShortCut, "_$sShortCut.scss", $sAlterableFileURI);
				$sImportedFile = realpath($sFilePath);
			}

			if ((file_exists($sImportedFile))
				&& (!$oFindStylesheetObject->AlreadyFetched($sImportedFile)))
			{
				if ($bImports){
					$oFindStylesheetObject->AddImport($sAlterableFileURI, $sImportedFile);
				}else{
					$oFindStylesheetObject->AddStylesheet($sAlterableFileURI, $sImportedFile);
				}
				$oFindStylesheetObject->UpdateLastModified($sImportedFile);

				//Regexp matching on all included scss files : @import 'XXX.scss';
				$sDirUri = dirname($sAlterableFileURI);
				preg_match_all('/@import \s*[\"\']([^\"\']*)\s*[\"\']\s*;/', file_get_contents($sImportedFile), $aMatches);
				if ( (is_array($aMatches)) && (count($aMatches)!==0) ){
					foreach ($aMatches[1] as $sImportedFile){
						self::FindStylesheetFile("$sDirUri/$sImportedFile", [ $sPath ], $oFindStylesheetObject, true);
					}
				}
			}
		}
	}

	/**
	 * @param $search
	 * @param $replace
	 * @param $subject
	 *
	 * @since 3.0.0 N°2982
	 * Replaces last occurrence of the string
	 * @return string|string[]
	 */
	public static function ReplaceLastOccurrence($sSearch, $sReplace, $sSubject)
	{
		$iPos = strrpos($sSubject, $sSearch);

		if($iPos !== false)
		{
			$sSubject = substr_replace($sSubject, $sReplace, $iPos, strlen($sSearch));
		}

		return $sSubject;
	}

	/**
	 * @since 3.0.0 N°2982
	 * Used for testing purpose
	 * @param $oCompileCSSServiceMock
	 */
	public static function MockCompileCSSService($oCompileCSSServiceMock)
	{
		static::$oCompileCSSService = $oCompileCSSServiceMock;
	}

	/**
	 * @since 3.0.0 N°2982
	 * Clone variable array and include $version with bSetupCompilationTimestamp value
	 * @param $aThemeParameters
	 * @param $bSetupCompilationTimestamp
	 * @param $aImportsPaths
	 *
	 * @return array
	 */
	public static function CloneThemeParameterAndIncludeVersion($aThemeParameters, $bSetupCompilationTimestamp, $aImportsPaths)
	{
		$aThemeParametersVariable = [];
		if (array_key_exists('variables', $aThemeParameters))
		{
			if (is_array($aThemeParameters['variables']))
			{
				$aThemeParametersVariable = array_merge([], $aThemeParameters['variables']);
			}
		}

		if (array_key_exists('variable_imports', $aThemeParameters))
		{
			if (is_array($aThemeParameters['variable_imports']))
			{
				$aThemeParametersVariable = array_merge($aThemeParametersVariable, static::GetVariablesFromFile($aThemeParameters['variable_imports'], $aImportsPaths));
			}
		}

		$aThemeParametersVariable['$version'] = $bSetupCompilationTimestamp;
		return $aThemeParametersVariable;
	}

	/**
	 * @param $aVariableFiles
	 * @param $aImportsPaths
	 *
	 * @return array
	 * @since 3.0.0 N°3593
	 */
	public static function GetVariablesFromFile($aVariableFiles, $aImportsPaths){
		$aVariablesResults = [];
		foreach ($aVariableFiles as $sVariableFile)
		{
			foreach($aImportsPaths as $sPath) {
				$sFilePath = $sPath.'/'.$sVariableFile;
				$sImportedFile = realpath($sFilePath);
				if ($sImportedFile !== false) {
					$sFileContent = file_get_contents($sImportedFile);
					$aVariableMatches = [];

					preg_match_all('/\s*\$(.*?)\s*:\s*[\"\']{0,1}(.*?)[\"\']{0,1}\s*[;!]/', $sFileContent, $aVariableMatches);
					$aVariableMatches = array_combine($aVariableMatches[1], array_map(function ($sVariableValue) {
						return $sVariableValue;
					}, $aVariableMatches[2]));
					$aVariablesResults = array_merge($aVariablesResults, $aVariableMatches);
					break;
				}
			}
		}
		array_map( function($sVariableValue) { return ltrim($sVariableValue); }, $aVariablesResults );
		return $aVariablesResults;
	}

}

