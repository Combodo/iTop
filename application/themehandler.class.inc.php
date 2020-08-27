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
			
			static::CompileTheme($sThemeId, $aDefaultTheme['parameters']);
		}

		// Return absolute url to theme compiled css
		return utils::GetAbsoluteUrlModulesRoot().'/branding/themes/'.$sThemeId.'/main.css';
	}

	/**
	 * Compile the $sThemeId theme
	 *
	 * @param string $sThemeId
	 * @param array|null $aThemeParameters Parameters (variables, imports, stylesheets) for the theme, if not passed, will be retrieved from compiled DM
	 * @param array|null $aImportsPaths Paths where imports can be found. Must end with '/'
	 * @param string|null $sWorkingPath Path of the folder used during compilation. Must end with a '/'
	 *
	 * @throws \CoreException
	 */
	public static function CompileTheme($sThemeId, $aThemeParameters = null, $aImportsPaths = null, $sWorkingPath = null)
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

			$iImportLastModified = @filemtime($sWorkingPath.$sImport);
			$iStyleLastModified = $iStyleLastModified < $iImportLastModified ? $iImportLastModified : $iStyleLastModified;
		}
		foreach ($aThemeParameters['stylesheets'] as $sStylesheet)
		{
			$sTmpThemeScssContent .= '@import "'.$sStylesheet.'";'."\n";

			$iStylesheetLastModified = @filemtime($sWorkingPath.$sStylesheet);
			$iStyleLastModified = $iStyleLastModified < $iStylesheetLastModified ? $iStylesheetLastModified : $iStyleLastModified;
		}

		// Checking if our compiled css is outdated
		if (!file_exists($sThemeCssPath) || (is_writable($sThemeFolderPath) && (@filemtime($sThemeCssPath) < $iStyleLastModified)))
		{
			$sTmpThemeCssContent = utils::CompileCSSFromSASS($sTmpThemeScssContent, $aImportsPaths, $aThemeParameters['variables']);
			file_put_contents($sThemeCssPath, $sTmpThemeCssContent);
		}
	}
}

