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

use ScssPhp\ScssPhp\Compiler;

/**
 * Class ThemeHandler
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @since 2.7.0
 */
class ThemeHandler
{
	/**
	 * Return the absolute URL for the default theme CSS file
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function GetDefaultThemeUrl()
	{
		$sThemeId = MetaModel::GetConfig()->Get('backoffice_default_theme');
		static::CompileTheme($sThemeId);

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

		// Save parameters if passed...
		if(is_array($aThemeParameters))
		{
			file_put_contents($sThemeFolderPath.'/theme-parameters.json', json_encode($aThemeParameters));
		}
		// ... Otherwise, retrieve them from compiled DM
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
			$oScss = new Compiler();
			$oScss->setFormatter('ScssPhp\\ScssPhp\\Formatter\\Expanded');
			// Setting our xml variables
			$oScss->setVariables($aThemeParameters['variables']);
			// Setting our imports paths
			$oScss->setImportPaths($aImportsPaths);
			// Temporary disabling max exec time while compiling
			$iCurrentMaxExecTime = (int)ini_get('max_execution_time');
			set_time_limit(0);
			// Compiling our theme
			$sTmpThemeCssContent = $oScss->compile($sTmpThemeScssContent);
			set_time_limit($iCurrentMaxExecTime);
			file_put_contents($sThemeCssPath, $sTmpThemeCssContent);
		}
	}
}

