<?php
/**
 *
 *  * Copyright (C) 2013-2019 Combodo SARL
 *  *
 *  * This file is part of iTop.
 *  *
 *  * iTop is free software; you can redistribute it and/or modify
 *  * it under the terms of the GNU Affero General Public License as published by
 *  * the Free Software Foundation, either version 3 of the License, or
 *  * (at your option) any later version.
 *  *
 *  * iTop is distributed in the hope that it will be useful,
 *  * but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  * GNU Affero General Public License for more details.
 *  *
 *  * You should have received a copy of the GNU Affero General Public License
 *  
 */

use ScssPhp\ScssPhp\Compiler;

class ThemeHandler{

	public static function GetTheme()
	{
		$sThemeId = MetaModel::GetConfig()->Get('backoffice_default_theme');
		$sEnvPath =  APPROOT.'env-' . utils::GetCurrentEnvironment() .'/';
		$sThemePath = $sEnvPath.'/branding/themes/'.$sThemeId.'/';
		$aThemeParameters = json_decode(file_get_contents($sThemePath.'theme-parameters.json'), true);
		$sThemeCssPath = $sThemePath.'main.css';
		
		$sTheme = '';
		$iStyleLastModified = 0;
		clearstatcache();
		// Loading files to import and stylesheet to compile, also getting most recent modification time on overall files
		foreach ($aThemeParameters['imports'] as $sImport)
		{
			$sTheme.= '@import "' . $sImport . '";' . "\n";
			
			$iImportLastModified = filemtime($sEnvPath.$sImport);
			$iStyleLastModified = $iStyleLastModified < $iImportLastModified ? $iImportLastModified : $iStyleLastModified;
		}
		foreach ($aThemeParameters['stylesheets'] as $sStylesheet)
		{
			$sTheme.= '@import "' . $sStylesheet . '";'."\n";
			
			$iStylesheetLastModified = filemtime($sEnvPath.$sStylesheet);
			$iStyleLastModified = $iStyleLastModified < $iStylesheetLastModified ? $iStylesheetLastModified : $iStyleLastModified;
		}
		
		// Checking if our compiled css is outdated
		if (!file_exists($sThemeCssPath) || (is_writable($sThemePath) && (filemtime($sThemeCssPath) < $iStyleLastModified)))
		{
			$oScss = new Compiler();
			$oScss->setFormatter('ScssPhp\\ScssPhp\\Formatter\\Expanded');
			// Setting our xml variables
			$oScss->setVariables($aThemeParameters['variables']);
			// Setting our import path to env-*
			$oScss->setImportPaths($sEnvPath);
			// Temporary disabling max exec time while compiling
			$iCurrentMaxExecTime = (int) ini_get('max_execution_time');
			set_time_limit(0);
			// Compiling our theme
			$sThemeCss = $oScss->compile($sTheme);
			set_time_limit($iCurrentMaxExecTime);
			file_put_contents($sThemePath.'main.css', $sThemeCss);
		}
		// Return absolute url to theme compiled css
		return utils::GetAbsoluteUrlModulesRoot().'/branding/themes/'.$sThemeId.'/main.css';
	}
}

